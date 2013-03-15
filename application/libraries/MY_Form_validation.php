<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Form_validation Class
 *
 * Extends Form_Validation library
 *
 * Adds one validation rule, "unique" and accepts a
 * parameter, the name of the table and column that
 * you are checking, specified in the forum table.column
 *
 * Note that this update should be used with the
 * form_validation library introduced in CI 1.7.0
 */
class MY_Form_validation extends CI_Form_validation
{
	function __construct($config = array())
	{
	    parent::__construct($config);
	}

	function check_email_list($val)
	{
		$emails = explode(",", $val);
		
		foreach ($emails as $email)
		{
			$email = trim($email);
			
			if ($email && !$this->valid_email($email))
			{
				$this->set_message('check_email_list', "The address &ldquo;".$email."&rdquo; doesn't seem to be a valid e-mail address.");
				return FALSE;
			}
		}

		return TRUE;
	}
	function check_phone($val)
	{
		$val = trim(str_replace(array("-", " "), array(""), $val));
		
		if (!is_numeric($val))
		{
			$this->set_message("check_phone", "The %s field doesn't contain a valid phone number");
			return FALSE;
		}
		
		return TRUE;
	}
	function not_matches($val, $val2)
	{
		if ($val == $val2)
		{
			$this->set_message("not_matches", "The %s field cannot have the value of '".$val2."'");
			return FALSE;
		}
		
		return TRUE;
	}
	function check_captcha($val)
	{
		$CI =& get_instance();
		
		if (!empty($val))
		{
			$resp = recaptcha_check_answer($CI->config->item("recaptcha_private"),
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);
			
			if (!$resp->is_valid)
			{
				$CI->form_validation->set_message('check_captcha',"You have entered the unique security characters incorrectly");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			$CI->form_validation->set_message('check_captcha',"Enter security words in the red box below");
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

    /**
     * Validate URL
     *
     * @access    public
     * @param    string
     * @return    string
     */
    function valid_url($url)
    {
		if (strpos($url, "http") !== 0)
		{
			$url = "http://" . $url;
		}

        $pattern = "%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i";
        if (!preg_match($pattern, $url))
        {
			$this->set_message("valid_url", "The %s field doesn't contain a valid URL");
            return FALSE;
        }

        return TRUE;
    }
}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */