<?php
function array_values_integer($array)
{
	$new_array = array();
	foreach ($array as $element)
	{
		$new_array[] = (int) $element;
	}
	return $new_array;
}
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
