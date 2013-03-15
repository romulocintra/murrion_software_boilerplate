<?php
function values_separated_by_comma()
{
	$output_arr = array();

	foreach (func_get_args() as $arg)
	{
		if (strlen(trim($arg)) > 0)
		{
			$output_arr[] = $arg;
		}
	}
	
	return implode(", ", $output_arr);
}
/**
 * Convert BR tags to nl
 *
 * @param string The string to convert
 * @return string The converted string
 */
function br2nl($string)
{
    return strtr($string, array(PHP_EOL => '<br />', "\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
}


function template_replace($template, $text)
{
	foreach ($template as $code => $value)
	{
		$text = str_replace($code, $value, $text);
	}

	return $text;
}
