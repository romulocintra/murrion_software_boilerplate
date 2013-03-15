<?php
class Facebook_login extends CI_Controller {
	function login_done()
	{		
		$facebook = $this->my_facebook_model->facebook;
		
		$user = $facebook->getUser();

		//var_dump($user);

		if ($user)
		{
			try
			{
				$user_profile = $facebook->api('/me');
			}
			catch (FacebookApiException $e)
			{
				//echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
				$user = null;
			}
		}

		if (isset($user_profile))
		{
			$user_details = $this->user_model->get_user(array(
				"user_facebook_id" => $user_profile["id"]
			));
			
			if (!$user_details)
			{
				// check if this email address already exists
				$exists_email = $this->user_model->get_user(array(
					"user_email" => $user_profile["email"]
				));
				
				if ($exists_email)
				{
					$error = "There is already a user with this email address registered on " . $this->config->item("site_name");
				}
				else
				{				
					$user_insert = array(
						"user_facebook_id" => $user_profile["id"],
						"user_type" => "customer",
						"user_email" => $user_profile["email"],
						"user_name" => $user_profile["first_name"],
						"user_last_name" => $user_profile["last_name"],
						"user_password" => md5(mt_rand().microtime()),
						"user_email_updates" => 1,
						"user_approved" => 1,
						"user_active" => 1,
					);
					
					$user_id = $this->user_model->store_user($user_insert);
					
					if ($user_id)
					{
						$user_details = $this->user_model->get_user($user_id);
					}
					else
					{
						$error = "Unable to create the user using Facebook";
					}
				}
			}

			if ($user_details)
			{
				$error = $this->user_model->login_user_logic($user_details);
			}

			if (isset($error))
			{
				$this->session->set_flashdata("error", $error);
				redirect("user/login");
			}
		}

		$this->load->view("public/user/facebook/login");
	}
	function logout()
	{
		$facebook = $this->my_facebook_model->facebook;

		setcookie('fbs_'.$facebook->getAppId(), '', time()-100, '/');
		
        $this->session->unset_userdata("user_id");
		
		session_destroy();
		
		redirect("");
	}
}