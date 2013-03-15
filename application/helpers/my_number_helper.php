<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Returns the division of provided numbers
 *
 * @access public
 * @param integer
 * @param integer
 */
function dividable_by_zero($a, $b)
{
	if ($b == 0)
	{
		return 0;
	}
	else
	{
		return $a / $b;
	}
}

/**
 * Returns the provided number on desired format
 *
 * @access public
 * @param integer
 * @param integer/boolean
 * @param string
 */
function my_format_number($num, $dec = FALSE, $mode = "")
{
	$dec = $dec ? 2 : 0;
	
	if ($dec && $num == (int) $num)
	{
		$dec = 0;
	}
	
	if (is_numeric($num))
	{
		$return = number_format($num, $dec, '.', ',');
	}
	else
	{
		$return = 0;
	}

	if ($mode == "pct")
	{
		$return .= "%";
	}
	else if ($mode == "euro")
	{
		$return = "&euro;" . $return;
	}
	else if ($mode == "pound")
	{
		$return = "&pound;" . $return;
	}
	
	return $return;
}

/**
 * Returns a string as boolean
 *
 * @access public
 * @param string
 */
function to_boolean($value)
{
    if ($value && strtolower($value) !== "false")
    {
        return true;
    }
    else
    {
        return false;
    }
}

/* End of file my_number_helper.php */
/* Location: ./application/helpers/my_number_helper.php */