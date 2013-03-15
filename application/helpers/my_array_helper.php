<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Turn into (INT) integer all array values
 *
 * @access public
 * @param array
 */
function array_values_integer($array)
{
	$new_array = array();
	foreach ($array as $element)
	{
		$new_array[] = (int) $element;
	}
	return $new_array;
}

/**
 * Check if the provided array is empty
 *
 * @access public
 * @param array
 */
function array_is_empty($array)
{
	if (!is_array($array) || empty($array))
	{
		return TRUE;
	}
	
	foreach ($array as $item)
	{
		if (empty($item))
		{
			return TRUE;
		}
	}
	return FALSE;
}

/* End of file my_array_helper.php */
/* Location: ./application/helpers/my_array_helper.php */
