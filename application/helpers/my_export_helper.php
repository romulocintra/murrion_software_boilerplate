<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Generate a 'doc' file using provided data
 *
 * @access public
 * @param string
 * @param string
 */
function generate_my_doc($html, $filename)
{
	header("Content-Type: application/vnd.ms-word");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Disposition: attachment; filename=$filename");

	echo $html;
	exit();
}

/**
 * Generate a 'xls' file using provided data
 *
 * @access public
 * @param array
 * @param string
 */
function generate_my_xls($array, $filename)
{
	header('Content-type: application/msexcel');
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	echo $array;
	exit();
}

/* End of file my_export_helper.php */
/* Location: ./application/helpers/my_export_helper.php */