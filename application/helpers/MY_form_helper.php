<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Creates an array with all months names.
 *
 * @access public
 */
function get_month_dropdown()
{
	$month_list = array();
	for ($i=1; $i <= 12; $i++)
	{
		$m = str_pad($i, 2, "0", STR_PAD_LEFT);	
		$t = mktime(0, 0, 0, $m);
	
		$month_list[$m] = date("F", $t);
	}
	return $month_list;
}

/**
 * Creates an array with the years between start_year and end_year. 
 * If not end_year provided, the array will end at system actual year.
 *
 * @access public
 * @param integer
 * @param integer
 */
function get_year_dropdown($start_year = 2013, $end_year = NULL)
{
	$end_year = $end_year ? $end_year : date("Y");
	$year_list = array();
	
	for ($i=$start_year; $i <= $end_year; $i++)
	{
		$y = str_pad($i, 2, "0", STR_PAD_LEFT);
		$year_list[$y] = $y;
	}
	return $year_list;
}

/**
 * Create an array with all hours of the day
 *
 * @access public
 */
function get_hour_dropdown()
{
	$hour_list = array();
	
	for ($i=0; $i <= 23; $i++)
	{
		$y = str_pad($i, 2, "0", STR_PAD_LEFT);
		$hour_list[$y] = $y;
	}
	return $hour_list;
}

/**
 * Returns an array with minutes list
 *
 * @access public
 * @param integer
 */
function get_minute_dropdown($interval = 1)
{
	$minute_list = array();
	
	for ($i=0; $i <= 59; $i++)
	{
		$y = str_pad($i, 2, "0", STR_PAD_LEFT);
		
		if ($i % $interval == 0)
		{
			$minute_list[$y] = $y;
		}
	}
	return $minute_list;
}

/* End of file my_form_helper.php */
/* Location: ./application/helpers/my_form_helper.php */