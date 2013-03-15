<?php
function american_date_to_mysql($date, $datetime=FALSE, $start=TRUE)
{
    $date = explode("/", $date);
    return $date[2] . "-" . $date[0] . "-" . $date[1] . ($datetime ? " " . ($start ? "00:00:00" : "23:59:59") : "");
}
function normal_date_to_mysql($date, $datetime=FALSE, $start=TRUE)
{
    $date = explode("/", $date);

    if (isset($date[0], $date[1], $date[2]))
	{
	    return $date[2] . "-" . $date[1] . "-" . $date[0] . ($datetime ? " " . ($start ? "00:00:00" : "23:59:59") : "");
    }
	else
	{
	    return NULL;
    }
}
function my_now()
{
	return date("Y-m-d H:i:s");
}
function normal_datetime_to_mysql($datetime)
{
	$date = strpos($datetime, ":") === FALSE ? trim($datetime) : substr($datetime, 0, strpos($datetime, " "));
	
	$date_formatted = normal_date_to_mysql($date);
	if ($date_formatted !== NULL)
	{
		$date_formatted .= " ".substr($datetime, strpos($datetime, " ")+1);

		while (substr_count($date_formatted, ":") < 2)
		{
			$date_formatted .= ":00";
		}
		
		return $date_formatted;
	}
	else
	{
		return NULL;
	}
}
function my_format_date($date, $default = "", $format="d-M-Y")
{
	$totime = strtotime($date);
	
	if ($totime > 0)
	{
		return date($format, $totime);
	}
	else
	{
		return $default;
	}
}
function my_format_datetime($datetime=NULL, $default = "")
{
	if ($datetime === NULL)
	{
		$totime = time();
	}
	else
	{
		$totime = strtotime($datetime);
	}
	
	if ($totime > 0)
	{
		return date("d/m/Y H:i:s", $totime);
	}
	else
	{
		return $default;
	}
}
function day_week_bounds($day)
{
	$day = substr($day, 0, 10); // only date-part
	$day_time = strtotime($day);
	
	$weekday = date("w", $day_time);

	$seconds_in_day = 60 * 60 * 24;
	
	$start_day = $day_time - ($seconds_in_day * $weekday);
	$end_day = $day_time + ($seconds_in_day * (6 - $weekday));
	
	return array(date("Y-m-d", $start_day)." 00:00:00", date("Y-m-d", $end_day)." 23:59:59");
}
function month_generate_range($month, $year, $type='start', $datetime=TRUE)
{
    return $type == 'start' ?
	    $year . "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01" . ($datetime ? " 00:00:00" : "") :
	    $year . "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-31" . ($datetime ? " 23:59:59" : "");
}

function generate_daily_range($day, $month, $year, $type='start', $datetime=TRUE)
{
    return $year . "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT) . 
		($datetime ? ($type == "start" ? " 00:00:00" : " 23:59:59") : "");
}
function ago($time)
{
	if (!is_int($time))
	{
		$time = strtotime($time);
	}
	
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++)
   {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1)
   {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago ";
}
