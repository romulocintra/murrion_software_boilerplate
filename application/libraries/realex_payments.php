<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Realex Payments RealAuth class
 *
 * This CodeIgniter library to integrate the Realex Payments RealAuth service
 * http://www.realexpayments.com/web-payments
 *
 * @package		Realex_payments
 * @author		Seán Downey, <sean@downey.ie>
 * @version		0.7.0
 * @copyright	Copyright (c) 2011, Seán Downey
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		https://github.com/seandowney/codeigniter-realex-payments
 */
class Realex_payments
{

    protected $CI;
    // realex related settings
    protected $realex_merchant_id;
    protected $realex_sharedsecret;
    protected $realex_account;
    protected $realex_auto_settle;
    protected $realex_payment_url;
    // order specific fields
    protected $currency;
    protected $order_id;
    protected $amount;
    protected $timestamp;
    protected $digital_signature;
    // response fields
    protected $result;
    protected $pasref;
    protected $authcode;
    // form related
    protected $form_name = 'RealexForm';
    protected $form_id = 'RealexForm';
    protected $button_label = '';
    protected $button_prefix = '<p>';
    protected $button_suffix = '</p>';

    /**
     * Constructor

     * @access	public
     */
    public function __construct()
    {
        if (!isset($this->CI))
        {
            $this->CI = & get_instance();
        }

        $this->CI->load->helper('url');
        $this->CI->load->helper('form');
        $this->CI->load->config('realex_payments');
        $this->CI->lang->load('realex_payments');

        log_message('debug', "Realex Payments Class Initialized");

        $this->button_label = $this->CI->lang->line('realex_payments_form_button_label');

        $this->timestamp = date("YmdHis");
        $this->realex_merchant_id = $this->CI->config->item('realex_merchant_id');
        $this->realex_sharedsecret = $this->CI->config->item('realex_sharedsecret');
        $this->realex_account = $this->CI->config->item('realex_account');
        $this->currency = $this->CI->config->item('currency');
        $this->realex_auto_settle = $this->CI->config->item('realex_auto_settle');
        $this->realex_payment_url = $this->CI->config->item('realex_payment_url');
    }

    /**
     * Return the Realex Form
     *
     * Return the html of the form to submit to Realex
     *
     * @since	0.5
     * @access	public
     * @param	string	$order_id
     * @param	int		$amount
     * @return	html
     */
    function return_form($order_id, $amount)
    {
        $this->order_id = $order_id;
        $this->amount = $amount;

        $form_html = '';

        // validate the data
        if ($this->_valid_data())
        {
            log_message('debug', "Realex Payments - Generating Form HTML");

            // Generate the digital signature
            $this->_generate_request_signature();

            // setup the form
            $form_attributes = array('name' => $this->form_name, 'id' => $this->form_id);

            $form_html = form_open($this->realex_payment_url, $form_attributes);

            $form_html .= $this->button_prefix . form_submit('submit', $this->button_label, "class='pay_realex btn btn-info'") . $this->button_suffix;
            $form_html .= form_hidden("MERCHANT_ID", $this->realex_merchant_id);
            $form_html .= form_hidden("ACCOUNT", $this->realex_account);
            $form_html .= form_hidden("AUTO_SETTLE_FLAG", $this->realex_auto_settle);
            $form_html .= form_hidden("ORDER_ID", $order_id);
            $form_html .= form_hidden("CURRENCY", $this->currency);
            $form_html .= form_hidden("AMOUNT", $amount);
            $form_html .= form_hidden("TIMESTAMP", $this->timestamp);
            $form_html .= form_hidden("SHA1HASH", $this->digital_signature);

            if (!empty($this->comment1) AND $this->_validate_field('comment1', $this->comment1))
            {
                $form_html .= form_hidden('COMMENT1', $this->comment1);
            }
            if (!empty($this->comment2) AND $this->_validate_field('comment2', $this->comment2))
            {
                $form_html .= form_hidden('COMMENT2', $this->comment2);
            }
            if (!empty($this->customer_num) AND $this->_validate_field('customer_num', $this->customer_num))
            {
                $form_html .= form_hidden('CUST_NUM', $this->customer_num);
            }
            if (!empty($this->product_id) AND $this->_validate_field('product_id', $this->product_id))
            {
                $form_html .= form_hidden('PROD_ID', $this->product_id);
            }
            $form_html .= form_close();
        }
        else
        {

            log_message('debug', "Realex Payments - Form Data Invalid");

            $form_html = 'There is an error with some form data';
        }


        return $form_html;
    }

    /**
     * Return the response from Realex contains the correct data
     *
     * @since	0.5
     * @access	public
     * @return	object
     */
    function return_response_status()
    {
        $return_data = array();
        $return_data['status'] = FALSE;

        log_message('debug', "Realex Payments - Processing the Payment Response");


        // get the fields in the POST
        $post_vars = $this->CI->input->post();

        if (!empty($post_vars))
        {

            $this->timestamp = $post_vars['TIMESTAMP'];
            $this->result = $post_vars['RESULT'];
            $this->order_id = $post_vars['ORDER_ID'];
            $this->message = $post_vars['MESSAGE'];
            $this->authcode = $post_vars['AUTHCODE'];
            $this->pasref = $post_vars['PASREF'];
            $this->realexsha1 = $post_vars['SHA1HASH'];

            // Add details to the return_object
            $return_data['order_id'] = $this->order_id;
            if (!empty($post_vars['COMMENT1']))
            {
                $return_data['comment1'] = $post_vars['COMMENT1'];
            }
            if (!empty($post_vars['COMMENT2']))
            {
                $return_data['comment2'] = $post_vars['COMMENT2'];
            }
            if (!empty($post_vars['CUST_NUM']))
            {
                $return_data['customer_num'] = $post_vars['CUST_NUM'];
            }
            if (!empty($post_vars['PROD_ID']))
            {
                $return_data['product_id'] = $post_vars['PROD_ID'];
            }
            if (!empty($post_vars['AMOUNT']))
            {
                $return_data['amount'] = $post_vars['AMOUNT'];
            }

            // check that the signatures are valid
            if ($this->realexsha1 === $this->_generate_response_signature())
            {

                // Check if the payment was successful or not
                if ($this->result == '00')
                {
                    // successful payment
                    $return_data['status'] = TRUE;
                }
                else
                {
                    $return_data['error'] = 'PAYMENT_FAILURE';
                    $return_data['message'] = $this->message;

                    if ($this->result == 101 OR $this->result == 102)
                    {
                        $return_data['cause'] = 'DECLINED';
                    }
                    elseif ($this->result == 103)
                    {
                        $return_data['cause'] = 'LOST_STOLEN';
                    }
                    elseif ($this->result >= 200 AND $this->result < 300)
                    {
                        $return_data['cause'] = 'BANK_ERROR';
                    }
                    elseif ($this->result >= 300 AND $this->result < 400)
                    {
                        $return_data['cause'] = 'PAYMENT_SYSTEM_ERROR';
                    }
                    elseif ($this->result >= 500 AND $this->result < 600)
                    {
                        $return_data['cause'] = 'INVALID_DATA';
                    }
                    else
                    {
                        $return_data['cause'] = 'OTHER';
                    }
                }
            }
            else
            {
                $return_data['error'] = 'HASH_MISMATCH';
            }
        }
        else
        {
            $return_data['error'] = 'POST_VARS_EMPTY';
        }

        return $return_data;
    }

    /**
     * Set values in the class
     *
     * @since	0.5
     * @access	public
     * @param	string	$field
     * @param	string	$value
     */
    function set_field($field = NULL, $value = NULL)
    {
        $this->{$field} = $value;

        return $this;
    }

    /**
     * Set The Submit Button Delimiter
     *
     * Permits a prefix/suffix to be added to the submit button
     *
     * @since	0.5
     * @access	public
     * @param	string
     * @param	string
     * @return	void
     */
    function set_button_delimiters($prefix = '<p>', $suffix = '</p>')
    {
        $this->button_prefix = $prefix;
        $this->button_suffix = $suffix;

        return $this;
    }

    /**
     * Generate the disgital signature used to make the request to Realex
     *
     * @since	0.5
     * @access	private
     * @return	string
     */
    private function _generate_request_signature()
    {
        // PART1 = ( TIMESTAMP.MERCHANT_ID.ORDER_ID.AMOUNT.CURRENCY )
        $part_one = sha1($this->timestamp . "." .
                $this->realex_merchant_id . "." .
                $this->order_id . "." .
                $this->amount . "." .
                $this->currency);

        // SIG = (PART1.SHAREDSECRET)
        $this->digital_signature = sha1($part_one . "." . $this->realex_sharedsecret);

        return $this->digital_signature;
    }

    /**
     * Generate the disgital signature used to verify the response from Realex
     *
     * @since	0.5
     * @access	private
     * @return	string
     */
    private function _generate_response_signature()
    {
        // PART1 = (TIMESTAMP.MERCHANT_ID.ORDER_ID.RESULT.MESSAGE.PASREF.AUTHCODE)
        $part_one = sha1($this->timestamp . "." .
                $this->realex_merchant_id . "." .
                $this->order_id . "." .
                $this->result . "." .
                $this->message . "." .
                $this->pasref . "." .
                $this->authcode);

        // SIG = (PART1.SHAREDSECRET)
        $this->digital_signature = sha1($part_one . "." . $this->realex_sharedsecret);

        return $this->digital_signature;
    }

    private function _valid_data()
    {
        if ($this->_validate_field('realex_merchant_id', $this->realex_merchant_id)
                AND $this->_validate_field('realex_account', $this->realex_account)
                AND $this->_validate_field('order_id', $this->order_id)
                AND $this->_validate_field('amount', $this->amount)
                AND $this->_validate_field('currency', $this->currency)
                AND $this->_validate_field('timestamp', $this->timestamp)
                AND $this->_validate_field('realex_auto_settle', $this->realex_auto_settle)
        )
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Check the data being passed in the form is valid
     *
     * @param string $field
     * @param string $data
     * @return bool
     */
    private function _validate_field($field, $data)
    {
        $regex_data = array(
            'realex_merchant_id' => '[a-zA-Z0-9]+',
            'realex_account' => '[a-zA-Z0-9]+',
            'order_id' => '[a-zA-Z0-9\-\_]+',
            'amount' => '[0-9]+',
            'currency' => '[A-Z]{2,3}',
            'timestamp' => '[0-9]{14}',
            'realex_auto_settle' => '[0|1]',
            'customer_num' => '[a-zA-Z0-9\-\_]+',
            'product_id' => '[a-zA-Z0-9\-\_]+',
            'comment1' => '[a-zA-Z0-9\-\_ ]+',
            'comment2' => '[a-zA-Z0-9\-\_ ]+',
        );

        if (($field == 'order_id' OR $field == 'customer_num' OR $field == 'product_id')
                AND strlen($data) > 50)
        {
            return FALSE;
        }
        elseif (($field == 'comment1' OR $field == 'comment2')
                AND strlen($data) > 255)
        {
            return FALSE;
        }
        elseif (($field == 'timestamp')
                AND $data < date("YmdHis", strtotime('one day ago')))
        {
            return FALSE;
        }

        if (array_key_exists($field, $regex_data))
        {
            if (preg_match("/^" . $regex_data[$field] . "$/", $data))
            {
                return TRUE;
            }
        }

        return FALSE;
    }

}

/* End of file realex_payments.php */
/* Location: ./application/libraries/realex_payments.php */