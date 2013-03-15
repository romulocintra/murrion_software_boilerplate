<?php
require my_absolute_path(array("application", "3rdparty", "facebook-facebook-php-sdk-d1051eb", 'src', 'facebook.php'));

class My_facebook_model extends CI_Model {
	var $user;
	public $facebook;
	
	function __construct()
	{
		parent::__construct();
		
		$this->config->load("facebook", TRUE);
		
		$this->facebook = new Facebook(array(
			'appId'  => $this->config->item("appId", "facebook"),
			'secret' => $this->config->item("secret", "facebook"),
		));

		$this->user = $this->facebook->getUser();
	}
	function is_logged_in()
	{
		if ($this->user)
		{
			try
			{
				$user_profile = $this->facebook->api('/me');
				return TRUE;
			}
			catch (FacebookApiException $e)
			{
				return FALSE;
			}
		}
	}
	function get_login_url()
	{
		return $this->facebook->getLoginUrl(array('scope' => 'email', 'redirect_uri' => site_url("facebook_login/login_done")));
	}
	function get_logout_url()
	{
		return $this->facebook->getLogoutUrl(array('next' => site_url("facebook_login/logout")));
	}
}