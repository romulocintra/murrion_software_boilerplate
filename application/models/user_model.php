<?php
class User_model extends MY_Model
{
    var $user_details = NULL;
    var $column_prefix = "user";
    var $table = "mur_user";
    var $primary_key = "user_id";
    var $fully_delete = FALSE;
    var $has_created_date = TRUE;
    var $name_columns = "user_name";
    var $where_method = "user_where_parameters";

    function __construct()
    {
        parent::__construct();
    }

    function get_user_list($parameters = array())
    {
        return $this->get_item_list($parameters);
    }

    function store_user(&$data)
    {
        if (!element("user_id", $data))
        {
            $data["user_activation_token"] = md5(mt_rand() . microtime());
            $data["user_recovery_token"] = md5(mt_rand() . microtime());
        }

        return $this->store_item($data);
    }

    function delete_user($user_id)
    {
        return $this->delete_item($user_id);
    }

    function get_user($parameters)
    {
        if (!is_array($parameters))
        {
            $user_id = $parameters;

            $parameters = array();

            $parameters["user_id"] = $user_id;
        }

        $this->db->select("mur_user.*");

        return $this->get_item($parameters);
    }

    function user_where_parameters($parameters = array())
    {
        $join_with_firm = (bool) element("join_with_firm", $parameters);

        $this->db->where("user_deleted", 0);

        if (element("user_email", $parameters))
        {
            $this->db->where("user_email", $parameters["user_email"]);
        }
        if (element("user_password", $parameters))
        {
            $this->db->where("user_password", $parameters["user_password"]);
        }
        if (element("user_activation_token", $parameters))
        {
            $this->db->where("user_activation_token", $parameters["user_activation_token"]);
        }

        if (array_key_exists("user_id", $parameters))
        {
            $this->db->where("mur_user.user_id", $parameters["user_id"]);
        }

        // it can be zero
        if (isset($parameters["user_active"]))
        {
            $this->db->where("user_active", (int) $parameters["user_active"]);
        }
        if (isset($parameters["user_approved"]))
        {
            $this->db->where("user_approved", (int) $parameters["user_approved"]);
        }
    }

    function do_login($user_id)
    {
        $user_data = array(
            "user_last_login" => my_now(),
            "user_id" => $user_id
        );
        $this->store_user($user_data);

        $this->session->set_userdata("user_id", $user_id);

        $this->clear_login_attempt($user_id);

        // store the login of the user
        $this->db->insert("mur_user_login", array(
            "user_id" => $user_id,
            "user_login_useragent" => $this->input->user_agent(),
            "user_login_ip" => $this->input->ip_address()
        ));
    }

    function is_logged_in()
    {
        return (bool) $this->session->userdata("user_id");
    }

    function is_admin()
    {
        return $this->get_user_field("user_type") == "admin";
    }

    function is_profile($profile_list = array())
    {
        $user_type = $this->get_user_field("user_type");

        if (is_array($profile_list))
        {
            return in_array($user_type, $profile_list);
        }
        else
        {
            return $user_type == $profile_list;
        }
    }
    function get_user_field($key)
    {
        if ($this->user_details === NULL)
        {
            $this->user_details = $this->get_user((int) $this->session->userdata("user_id"));
        }

        return element($key, $this->user_details);
    }

    function get_user_details()
    {
        if ($this->user_details === NULL)
        {
            $this->user_details = $this->get_user((int) $this->session->userdata("user_id"));
        }

        return $this->user_details;
    }
    function clear_login_attempt($user_id)
    {
        $data = array(
            "user_id" => (int) $user_id,
            "user_login_attempts" => 0
        );
        $this->store_user($data);
    }

    function store_login_attempt($email, $user_type = "customer")
    {
        $sql = "UPDATE mur_user SET user_login_attempts = user_login_attempts+1 WHERE user_email=? AND user_type=?";

        $return = $this->db->query($sql, array($email, $user_type));

        if ($this->db->affected_rows() > 0)
        {
            $this->db->select("user_login_attempts");
            $this->db->from("mur_user");
            $this->db->where("user_email", $email);

            $row = $this->db->get()->row_array();

            if ($row["user_login_attempts"] >= 5)
            {
                return TRUE;
            }
        }

        return 0;
    }
    function login_user_logic($user_details)
    {
        $data = array();

        if ($user_details)
        {
            if (!$user_details["user_active"])
            {
                $data["error"] = "You have registered for " . $this->config->item("site_name") . " but your account has not yet been activated.  When you registered an email was sent to you with a link to click on to activate your account. This email may have been automatically directed to your SPAM/BULK folder.<br /><br />
" . anchor("user/resend_activation/".$user_details["user_id"], "Click here if you want to have the activation email resent to you.");
            }

            if (!isset($data["error"]) || !$data["error"])
            {
                $this->user_model->do_login($user_details["user_id"]);
                $this->session->set_flashdata("message", "Logged in successfully");

                if ($user_details["user_type"] == "user")
                {
					if ($this->session->flashdata("redirect_to") && stripos($this->session->flashdata("redirect_to"), "user/login") === FALSE)
					{
						redirect($this->session->flashdata("redirect_to"));
					}
					else
					{
						redirect("");
					}
                }
            }
        }
        else
        {
            $too_many = $this->user_model->store_login_attempt($this->input->post("user_email"), "user");

            if ($too_many)
            {
                $data["error"] = "Your account is currently suspended due to 5 invalid login attempts being made. " .
                        "To re-activate your account click on the \"Forgotten your password\"" .
                        " link to have a reset password sent to your e-mail";
            }
            else
            {
				$data["error"] = "Login Failed, please ensure that the email address and password that you have entered are correct and try again. ".
					"If you have forgotten your password, you can have it reset and emailed to you using the ".anchor("user/forgot", "Forgotten your password")." function";
            }
        }

        return element("error", $data, FALSE);
    }

}