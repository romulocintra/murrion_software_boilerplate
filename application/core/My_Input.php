<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Input extends CI_Input {
	public function __construct()
	{
		if (PHP_EOL == "\r\n")
		{
			$this->_standardize_newlines = FALSE;
		}
		
		parent::__construct();
	}
}  