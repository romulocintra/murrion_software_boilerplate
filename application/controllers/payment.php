<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function payment_response()
    {
		//$this->output->enable_profiler();
		
        /*
         * get the Post details and return in a usable format
         */
        $response_array = $this->realex_payments->return_response_status();

        $post_vars = $this->input->post();

        //$this->log_payment_response('realex', $post_vars);

        /*
         *  Check if the payment was successful
         */
        if (!empty($response_array['status']) && $response_array['status'])
        {
            if (element("order_id", $response_array))
            {
            }

            /*
             * display the success view
             */
            $this->load->view('public/payment/realex_success', $response_array);
        }
        else
        {

            switch ($response_array['error'])
            {
                case 'PAYMENT_FAILURE':
                    $response_array['failure_message'] = $this->_payment_failure($response_array);
                    break;
                case 'HASH_MISMATCH':
                    $response_array['failure_message'] = $this->lang->line('realex_response_hash_mismatch');
                    break;

                default:
                    $response_array['failure_message'] = $this->lang->line('realex_response_no_data');
                    break;
            }

            /*
             * display the failure view
             */
            $this->load->view('public/payment/failure', $response_array);
        }
    }

    function _payment_failure($response_array)
    {
        $failure_message = '';
        switch (element("cause", $response_array))
        {
            case 'DECLINED':
                $failure_message = $this->lang->line('realex_response_declined');
                break;
            case 'LOST_STOLEN':
                $failure_message = $this->lang->line('realex_response_lost_stolen');
                break;
            case 'BANK_ERROR':
                $failure_message = $this->lang->line('realex_response_bank_error');
                break;
            case 'PAYMENT_SYSTEM_ERROR':
                $failure_message = $this->lang->line('realex_response_payment_system_error');
                break;

            default:
                $failure_message = $this->lang->line('realex_response_other_error');
                break;
        }

        return $failure_message;
    }

    /**
     * A function called by Paypal if a payment is made
     */
    function paypal_ipn()
    {
        $post_vars = $_POST;
		//log_message("error", var_export($post_vars, TRUE));

		if ($this->_validate_paypal())
		{
			$item_number = $_POST["item_number"];
		}
		else
		{
			show_404();
		}
    }

    /**
     * Paypal redirects back to this function upon successful payment
     */
    function paypal_success()
    {
		$data = array();
        $item_number = (isset($_GET["item_number"])) ? $_GET["item_number"] : "";

        $this->load->view('public/payment/paypal_payment_successful', $data);
    }

    /**
     * Paypal redirects back to this function upon payment cancellation
     */
    function paypal_cancel()
    {
		$data = array();
		
        $this->load->view('public/payment/paypal_cancelled', $data);
    }

	/*
	 *	https://www.x.com/developers/PayPal/documentation-tools/code-sample/216623
	 **/
	private function _validate_paypal()
	{
		// STEP 1: Read POST data
		 
		// reading posted data from directly from $_POST causes serialization 
		// issues with array data in POST
		// reading raw POST data from input stream instead. 
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
			 $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}
		 
		 
		// STEP 2: Post IPN data back to paypal to validate
		 
		$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		 
		// In wamp like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
		// of the certificate as shown below.
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		if( !($res = curl_exec($ch)) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			return FALSE;
			//exit;
		}
		curl_close($ch);
		 
		 
		// STEP 3: Inspect IPN validation result and act accordingly
		 
		if (strcmp ($res, "VERIFIED") == 0) {
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment
		 
		 	return TRUE;
		 
			// assign posted variables to local variables
			/*$item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = $_POST['receiver_email'];
			$payer_email = $_POST['payer_email'];*/
		} else if (strcmp ($res, "INVALID") == 0) {
			// log for manual investigation
			return FALSE;
		}
	}

}

/* End of file payment.php */
/* Location: ./application/controllers/payment.php */