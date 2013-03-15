<?php
function generate_my_doc($html, $filename=NULL)
{
	header("Content-Type: application/vnd.ms-word");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header('Content-Disposition: attachment; filename="'.$filename.'"');

	echo $html;
	exit();
}
function generate_my_xls($array, $filename)
{
	header('Content-type: application/msexcel');
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	echo $array;
	exit();
}
