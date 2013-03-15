<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Realex Payments RealAuth configuration
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
/*
  | -------------------------------------------------------------------------
  | Realex Payments class config
  | -------------------------------------------------------------------------
  |
  | Your Response URL must be setup and stored in the Realex system.
  |
 */

/* * ************************************************************************************************
 * Please update these settings
 * ************************************************************************************************ */

/*
 * Whether to use the live payments account of test test account
 */
$config['live_payments'] = TRUE;

/*
 * Supplied by Realex – note this is not the merchant number supplied by your bank.
 */
$config['realex_merchant_id'] = '';

/*
 * The Shared secret is used to create a digital signature for communication with the Realex systems.
 * Your Realex account manager will give you the secret when your account is first configured.
 * NOTE: It is very important that this information only be divulged to authorised account contacts.
 * It is strongly recommended that the shared secret not be sent by email as this is not a secure channel of communication.
 */
$config['realex_sharedsecret'] = '';

/*
 * The sub‐account to use for this transaction. If not present, the default sub‐account, ‘internet’, will be used.
 */
$config['realex_account'] = 'internet';

/*
 * Used to signify whether or not you wish the transaction to be captured in the next batch or not. 
 * If set to “1” and assuming the transaction is authorised then it will automatically be settled in the next batch. 
 * If set to “0” then the merchant must use the realcontrol application to manually settle the transaction. 
 * This option can be used if a merchant wishes to delay the payment until after the goods have been shipped. 
 * Transactions can be settled for up to 115% of the original amount.
 */
$config['realex_auto_settle'] = 1;

/*
 * Set this to indicate the currency in which you wish to trade. 3 characters Examples: EUR,GBP and USD
 * The currency must be supported by one of your Sage Pay merchant accounts or the transaction will be rejected.
 */
$config['currency'] = 'EUR';


// check if we want to use use test details

$current_url = $_SERVER["SERVER_NAME"];

if (stristr($current_url, 'localhost') || stristr($current_url, 'test'))
{
    // test account details
    $config['realex_account'] = 'internettest';
}


/* * ************************************************************************************************
 * You should not have to update below here
 * ************************************************************************************************* */

$config['realex_payment_url'] = "https://epage.payandshop.com/epage.cgi";


/* End of file realex_payments.php */
/* Location: ./application/config/realex_payments.php */