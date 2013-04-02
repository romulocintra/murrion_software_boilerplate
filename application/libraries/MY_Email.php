<?php
class MY_Email extends CI_Email {
	var $_raw_subject = "";
	
	function subject($subject)
	{
		$this->_raw_subject = $subject;
		return parent::subject($subject);
	}
	function bcc($bcc)
	{
		if (is_array($bcc)) {
			$this->_raw_bcc = implode(",", $bcc);
		} else {
			$this->_raw_bcc = $bcc;
		}
		return parent::bcc($bcc);
	}
	function cc($cc)
	{
		$this->_raw_cc = implode(",", $cc);
		return parent::cc($cc);
	}
	
	function send($log_message=TRUE)
	{
		$CI =& get_instance();
		
		if ($CI->config->item("local"))
		{
			$sent = TRUE;
		}
		else
		{
			$sent = parent::send();
		}

		if ($log_message)
		{		
			if (!isset($this->_raw_cc)) $this->_raw_cc = null;
			if (!isset($this->_raw_bcc)) $this->_raw_bcc = null;
			
			$CI->db->insert("mur_email_sent", array(
				"email_sent_from" =>  $this->clean_email($this->_headers['From']),
				"email_sent_to" => is_array($this->_recipients) ? implode(",", $this->_recipients) : $this->_recipients,
				"email_sent_subject" => $this->_raw_subject,
				"email_sent_text" => $this->_body,
				"email_sent_bcc" => is_array($this->_raw_cc) ? implode(",", $this->_raw_cc) : $this->_raw_cc,
				"email_sent_cco" => is_array($this->_raw_bcc) ? implode(",", $this->_raw_bcc) : $this->_raw_bcc,
				"email_sent_debugger" => $this->print_debugger(),
			));
		}

		return $sent;
	}
}