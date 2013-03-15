<?php
class User extends CI_Controller {
    function __construct()
    {
        parent::__construct();
    }

    function login()
    {
        $data['title'] = "Login";

        if ($this->session->flashdata("redirect_to"))
        {
            $this->session->keep_flashdata("redirect_to");
        }

        if ($this->input->post("submit_login"))
        {
            $user_details = $this->user_model->get_user(array(
                // "user_type" => "customer",
                "user_password" => md5($this->input->post("user_password")),
                "user_email" => $this->input->post("user_email")
                    ));

            $error = $this->user_model->login_user_logic($user_details, $data);

            if ($error)
            {
                $data["error"] = $error;
            }
        }

        // Facebook login integration

        $data["loginUrl"] = $this->my_facebook_model->get_login_url();

        $this->load->view('public/user/login', $data);
    }
    function logout()
    {
        $this->session->unset_userdata("user_id");
        redirect("");
    }
	function register_login()
	{
		$this->load->helper("captcha");

		$vals = array(
			'img_path' => './uploads/',
			'img_url' => base_url() . "uploads/",
			"img_width" => 220,
			"img_height" => 60,
			"font_path" => "./system/fonts/texb.ttf",
			"word" => strtoupper(random_string("alpha", 5))
		);
		
		$cap = create_captcha($vals);
		
		$this->db->insert("mur_captcha", array(
			'captcha_time' => $cap['time'],
			'ip_address' => $this->input->ip_address(),
			'word' => $cap['word']
		));

		$data["captcha"] = $cap["image"];

		$data["county_dropdown"] = $this->area_model->get_county_dropdown(array(
			"field_name" => "user_county",
			"county_id" => $this->input->post("user_county"),
			"country_classname" => "span3",
			"classname" => "span3",
			"separated" => TRUE
		));

		$data["title"] = "Register or log into " . $this->config->item("site_name");
		
		$this->load->view('public/user/register_login', $data);
	}

    function register()
    {
		$this->load->helper(array("captcha", "string"));

        if ($this->input->post("submit_register"))
        {
            $this->form_validation->set_rules("user_name", "First Name", "required|trim");
            $this->form_validation->set_rules("user_last_name", "Last Name", "required|trim");
            $this->form_validation->set_rules("user_email", "Email address", "required|valid_email");
            $this->form_validation->set_rules("user_email2", "Repeat email", "required|matches[user_email]");
            $this->form_validation->set_rules("user_password", "Your Password", "required|min_length[5]");
            $this->form_validation->set_rules("user_password2", "Repeat Password", "required|matches[user_password]");
            $this->form_validation->set_rules("agree_terms", "Agree Terms and Conditions", "required");
            $this->form_validation->set_rules('security_words', 'Security words', 'callback_check_captcha');

            $data["user_details"] = array(
                "user_name" => $this->input->post("user_name"),
                "user_last_name" => $this->input->post("user_last_name"),
                "user_email" => $this->input->post("user_email"),
                "user_password" => md5($this->input->post("user_password")),
            );

            if (!$this->form_validation->run())
            {
                $data["error"] = validation_errors();
            }
            // check if email already exists
            else if ($this->db->get_where("mur_user", array("user_email" => $data["user_details"]["user_email"]))->row_array())
            {
                $data["error"] = "There is already a registered user with your e-mail address";
            }
            else
            {
                $data["user_details"]["user_type"] = "user";
                $return = $this->user_model->store_user($data["user_details"]);

                if ($return)
                {		
                    // send activation email
                    $this->load->library("email");

                    $message = $this->load->view("private/email_templates/activate", $data, TRUE);

                    $config['mailtype'] = 'html';
                    $this->email->initialize($config);
                    $this->email->from($this->config->item("noreply_email"));

                    $this->email->to($data["user_details"]["user_email"]);

                    $this->email->subject($this->config->item("site_name")." Account Verification");
                    $this->email->message($message);
                    $this->email->send();

                    $this->session->set_flashdata("message", "An email has been sent to " . $data["user_details"]["user_email"] .
                            ", you will find a link in this email that must be clicked to activate your " .
                            $this->config->item("site_name")." account (if this email is not in your inbox, please check your bulk/spam folder)");

					$this->user_model->do_login($return);

                    redirect("");
                }
            }
        }
        else
        {
            $data["user_details"] = array();
        }


		$vals = array(
			'img_path' => './uploads/',
			'img_url' => base_url() . "uploads/",
			"img_width" => 220,
			"img_height" => 60,
			"font_path" => "./system/fonts/texb.ttf",
			"word" => strtoupper(random_string("alpha", 5))
		);
		
		$cap = create_captcha($vals);
		
		$this->db->insert("mur_captcha", array(
			'captcha_time' => $cap['time'],
			'ip_address' => $this->input->ip_address(),
			'word' => $cap['word']
		));

		$data["captcha"] = $cap["image"];

        $data['title'] = "Register";

        $this->load->view('public/user/register', $data);
    }

    function activate($token)
    {
        $data["user_details"] = $this->user_model->get_user(array(
            "user_activation_token" => $token,
            "user_active" => 0,
        ));

        if ($data["user_details"])
        {
            // do the actual activation
            $new_data = array(
                "user_id" => $data["user_details"]["user_id"],
                "user_active" => 1
            );
            $this->user_model->store_user($new_data);

            $this->session->set_userdata("user_id", $data["user_details"]["user_id"]);

			if ($this->user_model->is_firm_admin())
			{
				$message = "Hello " . $data["user_details"]["user_name"] . ",<br />" .
						"Thank you for confirming your e-mail address. Now, the administrators must approve your account.";

				// activate the firm
				$this->firm_model->activate_firm($data["user_details"]["firm_id"], 1);
				
				// send email to admin saying that the user is active
				$this->load->library("email");

				$email_message = $this->load->view("private/email_templates/admin_firm_activated", $data, TRUE);

				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				$this->email->from($this->config->item("noreply_email"));

				$this->email->to($this->config->item("info_email"));

				$this->email->subject($this->config->item("site_name")." firm activated");
				$this->email->message($email_message);
				$this->email->send();
				//////
			}
			else
			{
				$message = "Hello " . $data["user_details"]["user_name"] . ",<br />" .
						"Thank you for registering for " . $this->config->item("site_name") . ", your account is now active.<br />";
			}

            $this->session->set_flashdata("message", $message);

            // redirect to the proper page
            $this->user_model->user_registration_redirect("redirect", "account");
        }
        else
        {
            $this->session->set_flashdata("error", "This activation token doesn't seem to be valid");
            redirect("");
        }
    }
    function check_captcha($val)
    {		
        if (!empty($val))
        {
			// First, delete old captchas
			$expiration = time()-7200; // Two hour limit
			$this->db->query("DELETE FROM mur_captcha WHERE captcha_time < ".$expiration);
			
			// Then see if a captcha exists:
			$sql = "SELECT COUNT(*) AS count FROM mur_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
			$binds = array($val, $this->input->ip_address(), $expiration);
			$query = $this->db->query($sql, $binds);
			$row = $query->row();
			
			if ($row->count == 0)
			{
                $this->form_validation->set_message('check_captcha', "You have entered the unique security characters incorrectly");
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else
        {
            $this->form_validation->set_message('check_captcha', "The Security Words field is required");
            return FALSE;
        }
    }
    function forgot()
    {
		$this->load->helper("captcha");

        if ($this->input->post("submit_forgot"))
        {
            $this->form_validation->set_rules('security_words', 'Security words', 'callback_check_captcha');

            if ($this->form_validation->run())
            {
                $user_details = $this->user_model->get_user(array(
                    "user_email" => $this->input->post("user_email"),
                        //"is_not_facebook_user" => TRUE,
                        ));

                if ($user_details)
                {
                    if ($user_details["user_facebook_id"])
                    {
                        $data["error"] = "Your email address was registered for ".$this->config->item("site_name")." using your Facebook account, please use the " .
                                anchor($this->my_facebook_model->get_login_url(), "Facebook Login") . " to access your ".$this->config->item("site_name")." account";
                    }
                    else if ($user_details["user_type"] == "provider" && !$user_details["user_approved"])
                    {
                        $data["error"] = "The email address that you have entered is registered for " . $this->config->item("site_name") . " but your firm administrator has not yet approved your account. Please contact your firm administrator to have your account approved";
                    }
                    else if ($user_details["user_active"])
                    {
                        $this->load->library("email");

						$data["user_details"] = $user_details;
                        $data["reset_password_link"] = anchor("user/send_password/" . $user_details["user_activation_token"]);

                        $message = $this->load->view("private/email_templates/reset_password", $data, TRUE);

                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);
                        $this->email->from($this->config->item("noreply_email"));

                        $this->email->to($user_details["user_email"]);

                        $this->email->subject($this->config->item("site_name")." password reset request");
                        $this->email->message($message);
                        $this->email->send();

                        $this->session->set_flashdata("message", "An email has been sent to you with instructions on how to reset password. Be sure to check your spam and junk filters for the message.");

                        redirect("");
                    }
                    else
                    {
                        $date = my_format_datetime($user_details["user_created_date"]);

                        $data["error"] = "The email address that you have entered is registered but the account has not been activated. " .
                                "Immediately after you filled the registration form (" . $date . "), an email was sent to you with an activation link. " .
                                "This link needs to be clicked in order to activate your account and login to the site." .
                                "If you cannot find this activation email, " . anchor("user/resend_activation/" . $user_details["user_id"], "click here") . " to have it resent." .
                                "Note: Activation email may automatically go into your spam/bulk folder";
                    }
                }
                else
                {
                    $data["error"] = "The email address is not registered, please go to our " . anchor("user/register", "registration page") . " to create a new account.";
                }
            }
            else
            {
                $data["error"] = validation_errors();
            }
        }

		$vals = array(
			'img_path' => './uploads/',
			'img_url' => base_url() . "uploads/",
			"img_width" => 220,
			"img_height" => 60,
			"font_path" => "./system/fonts/texb.ttf",
			"word" => strtoupper(random_string("alpha", 5))
		);
		
		$cap = create_captcha($vals);
		
		$this->db->insert("mur_captcha", array(
			'captcha_time' => $cap['time'],
			'ip_address' => $this->input->ip_address(),
			'word' => $cap['word']
		));

		$data["captcha"] = $cap["image"];

        $data["title"] = "Recover your ".$this->config->item("site_name")." Password";
        $this->load->view("public/user/forgot", $data);
    }

    function resend_activation($user_id)
    {
        $data["user_details"] = $this->user_model->get_user(array(
            "user_id" => $user_id,
            "user_active" => 0,
        ));

        if ($data["user_details"])
        {
            $this->load->library("email");

            $message = $this->load->view("private/email_templates/activate", $data, TRUE);

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->from($this->config->item("noreply_email"));

            $this->email->to($data["user_details"]["user_email"]);

            $this->email->subject($this->config->item("site_name")." Account Verification");
            $this->email->message($message);
            $this->email->send();

            $this->session->set_flashdata("message", "An email has been sent to " . $data["user_details"]["user_email"] .
                    ", you will find a link in this email that must be clicked to activate your " .
                    $this->config->item("site_name")." account (if this email is not in your inbox, please check your bulk/spam folder");
        }

        redirect("");
    }

    function send_password($token)
    {
        $data["user_details"] = $this->user_model->get_user(array(
            "user_activation_token" => $token,
            "user_active" => 1,
                ));

        if ($data["user_details"])
        {
            $this->load->helper('string');

            $new_password = random_string('alnum', 10);

            // do the actual activation
            $new_data = array(
                "user_id" => $data["user_details"]["user_id"],
                "user_password" => md5($new_password)
            );
            $this->user_model->store_user($new_data);

            $this->load->library("email");

            $data["new_password"] = $new_password;

            $message = $this->load->view("private/email_templates/password_sent", $data, TRUE);

            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->from($this->config->item("noreply_email"));

            $this->email->to($data["user_details"]["user_email"]);

            $this->email->subject($this->config->item("site_name") . " password reset");
            $this->email->message($message);
            $this->email->send();

            $this->user_model->clear_login_attempt($data["user_details"]["user_id"]);

            $message = "Email sent with the new password. <br />Note: Activation email may automatically go into your spam/bulk folder";

            $this->session->set_flashdata("message", $message);
            redirect("");
        }
        else
        {
            $this->session->set_flashdata("error", "This activation token doesn't seem to be valid");
            redirect("");
        }
    }

    function password()
    {
        if ($this->input->post("submit_password"))
        {
            $user_details = $this->user_model->get_user(array(
				"user_password" => md5($this->input->post("current_password")),
				"user_id" => $this->session->userdata("user_id"),
			));

            if ($user_details)
            {
                $this->form_validation->set_rules("new_password1", "New Password", "required|min_length[5]");
                $this->form_validation->set_rules("new_password2", "Repeat Password", "required|matches[new_password1]");

                if ($this->form_validation->run())
                {
                    $new_data = array(
                        "user_id" => $this->session->userdata("user_id"),
                        "user_password" => md5($this->input->post("new_password1")),
                    );

                    $return = $this->user_model->store_user($new_data);

                    if ($return)
                    {
                        $this->session->set_flashdata("message", "Password modified successfully");
                        redirect("account");
                    }
                    else
                    {
                        $data["error"] = "Unable to edit the password. Try it again later, please";
                    }
                }
                else
                {
                    $data["error"] = validation_errors();
                }
            }
            else
            {
                $data["error"] = "Current password entered is incorrect";
            }
        }

        $data["title"] = "Edit password";
        $this->load->view("public/user/password", $data);
    }
}