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
	
	function remote_realex($product_id = 0, $price = 100, $admin = false)
	{
		$this->load->library('form_validation');
		$this->load->helper('realex');
	
		$error = false;

		$data["show_form"] = true;
		
		$admin = $admin ? $admin : $this->input->post("admin");
		
		$product_id = $product_id ? $product_id : $this->input->post("product_id");
		$data["price"] = $price ? $price : $this->input->post("$price");
		
		$data["admin"] = $admin;
		
		$view = "public/payment/remote_realex";
		
		if ( $error )
		{
			$this->load->view($view, $data);
			return;
		}

		$data["product_id"] = $product_id;
		$data["error_message"] = "";
		$data["full_xml"] = "";
		
		$data["values"] = array(
			"pas_cccvc" => $this->input->post("pas_cccvc"),
			"pas_cccvcind" => $this->input->post("pas_cccvcind"),
			"pas_ccmonth" => $this->input->post("pas_ccmonth"),
			"pas_ccname" => $this->input->post("pas_ccname"),
			"pas_ccnum" => $this->input->post("pas_ccnum"),
			"pas_cctype" => $this->input->post("pas_cctype"),
			"pas_ccyear" => $this->input->post("pas_ccyear"),
		);

		if(!$this->input->post("pay"))
		{
			$this->load->view($view, $data);
			return;
		}
		
        $this->form_validation->set_rules("pas_cctype", "Card Type", "required|alpha");
        $this->form_validation->set_rules("pas_ccnum", "Card Number", "required|is_natural_no_zero|min_length[12]|max_length[19]");
        $this->form_validation->set_rules("pas_cccvc", "Security Code", "required|is_natural_no_zero|min_length[3]|max_length[4]");
        $this->form_validation->set_rules("pas_ccmonth", "Expiry Date Month", "required|is_natural_no_zero|exact_length[2]");
        $this->form_validation->set_rules("pas_ccyear", "Expiry Date Year", "required|is_natural_no_zero|exact_length[2]");
        $this->form_validation->set_rules("pas_ccname", "Cardholder Name", "required|min_length[1]|max_length[100]");
		
	    if ($this->form_validation->run() == FALSE || isset($validation_errors))
		{
			$error = true;
			$data["error_message"] = isset($validation_errors) ? $validation_errors : validation_errors();
		}
		
		if ( $error )
		{
			$this->load->view($view, $data);
			return;
		}

		$URL="https://epage.payandshop.com/epage-remote.cgi";
		
		$parentElements = array();
		$TSSChecks = array();
		$currentElement = 0;
		$currentTSSCheck = "";
		
		$data_set = array('parentElements'=>$parentElements); //set it
		$this->session->set_userdata($data_set);
		
		$currency = $this->config->item('currency');
		
		$cardnumber = $this->input->post("pas_ccnum");
		$cardname = $this->input->post("pas_ccname");
		$cardtype = $this->input->post("pas_cctype");
		$expdate = $this->input->post("pas_ccmonth").$this->input->post("pas_ccyear");
		
		$security = $this->input->post("pas_cccvc");
		
		//Replace these with the values you receive from Realex Payments.(If we have not already contacted you with these details please call us)
		$merchantid = $this->config->item('realex_merchant_id');
		$secret = $this->config->item('realex_sharedsecret');
		
		
		$realex_test_mode = false;
				
		$account = element("param_num_value",$realex_test_mode) ? "internettest" : $this->config->item('realex_account');
		
		
		// The Timestamp is created here and used in the digital signature
		$timestamp = strftime("%Y%m%d%H%M%S");
		mt_srand((double)microtime()*1000000);
		
		
		// Order ID -  You can use any alphanumeric combination for the orderid.Although each transaction must have a unique orderid.
		$orderid = $product_id;
		
		if($this->input->post("price"))
		{
			$amount = $this->input->post("price"). "00";
		}

		// This section of code creates the md5hash that is needed
		$tmp = "$timestamp.$merchantid.$orderid.$amount.$currency.$cardnumber";
		$md5hash = md5($tmp);
		$tmp = "$md5hash.$secret";
		$md5hash = md5($tmp);
		
		//A number of variables are needed to generate the request xml that is send to Realex Payments.
		$xml = "<request type='auth' timestamp='$timestamp'>
			<merchantid>$merchantid</merchantid>
			<account>$account</account>
			<orderid>$orderid</orderid>
			<amount currency='$currency'>$amount</amount>
			<card> 
				<number>$cardnumber</number>
				<expdate>$expdate</expdate>
				<type>$cardtype</type> 
				<chname>$cardname</chname>
				<issueno></issueno>
				<cvn>
					<number>$security</number>
					<presind>1</presind>
				</cvn>
			</card> 
			<autosettle flag='1'/>
			<md5hash>$md5hash</md5hash>
			<tssinfo>
				<address type=\"billing\">
					 <country>ie</country>
				</address>
			</tssinfo>
		</request>";
		
		//or the integrated cURL way.... (if you've compiled cURL into PHP)
		$ch = curl_init();    
		curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, "payandshop.com php version 0.9"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		
		//not in the demo
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		//not in the demo
		
		$response = curl_exec ($ch);     
		
		curl_close ($ch);
		
		//echo $response;
		// Tidy it up
		
		$response = preg_replace ( "/[[:space:]]+/i", " ", $response );
		$response = preg_replace ( "/[[:space:]]+/i", " ", $response );
		
		$response_array = xml2array($response);
		
		//$this->session->unset_userdata("parentElements");
		
		$i = 0;
		$response_values = array();
		
		while(isset($response_array[$i]))
		{
			$response_values[$response_array[$i]["tag"]] = element("value", $response_array[$i]);
			$i++;
		}
		if($response_values["RESULT"] != "00")
		{
			$data["error_message"] = $response_values["MESSAGE"];
			$data["error_code"] = $response_values["RESULT"];
			$data["full_xml"] = $response;
			
			$this->load->view($view, $data);
		}
		else
		{
			$response_values["AMOUNT"] = $amount;
			$this->remote_payment_response($response_values);
		}

	}  
	
	function remote_payment_response($response)
    {
    	/*
		array(15) {
			 ["MERCHANTID"]=> string(10) "themarvels" 
			 ["ACCOUNT"]=> string(8) "internet" 
			 ["ORDERID"]=> string(6) "1159_9" 
			 ["AUTHCODE"]=> string(5) "12345" 
			 ["RESULT"]=> string(2) "00" 
			 ["CVNRESULT"]=> string(1) "U" 
			 ["AVSPOSTCODERESPONSE"]=> string(1) "U" 
			 ["AVSADDRESSRESPONSE"]=> string(1) "U" 
			 ["BATCHID"]=> string(6) "104489" 
			 ["MESSAGE"]=> string(26) "[ test system ] Authorised" 
			 ["PASREF"]=> string(17) "13606014055038565" 
			 ["TIMETAKEN"]=> string(1) "0" 
			 ["AUTHTIMETAKEN"]=> string(1) "0" 
			 ["CARDISSUER"]=> string(1) " " 
			 ["MD5HASH"]=> string(32) "896a9700cbf9651297266a619d775bbf" } 
		*/
		$data = array(
            'sha1hash' => element("MD5HASH", $response),
            'result' => element("RESULT", $response),
            'authcode' => element("AUTHCODE", $response),
            'message' => element("MESSAGE", $response),
            'pasref' => element("PASREF", $response),
            'avspostcoderesult' => element("AVSPOSTCODERESPONSE", $response),
            'avsaddressresult' => element("AVSADDRESSRESPONSE", $response),
            'cvnresult' => element("CVNRESULT", $response),
            'batchid' => element("BATCHID", $response),
            'pas_uuid' => "",
        );
		$realex_values = $data;
		
		$order_id = element("ORDERID", $response);
		$pos = strpos($order_id, "_");
		
        if ($pos > 0)
        {
        	$realex_attempt = (int) substr($order_id, $pos + 1, strlen($order_id));
			$order_id = substr($order_id, 0, $pos);
       	}

        $data["amount"] = element("AMOUNT", $response)/100;

        /*
         * Any specific post purchase actions upon successful payment
         */
        if (isset($data["result"]) && $data["result"] == "00" && $order_id != 0)
        {
        	
        }
        /*
         * Based on the Result, Notify the user and email the business
         */
        $this->realex_result($data);
    }
    
	function cvn()
	{
		$this->load->view("public/payment/cvn");
	}

}

/* End of file payment.php */
/* Location: ./application/controllers/payment.php */