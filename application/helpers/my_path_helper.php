<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * .
 *
 * @access public
 * @param array
 * @param string
 * @param string
 * @param boolean
 * @param string
 */
function my_absolute_path($segments = array(), $basepath = NULL, $sep = DIRECTORY_SEPARATOR, $create_subfolders = FALSE, $mode="file")
{
	$basepath = $basepath ? rtrim($basepath," /") : realpath(".");
	
	if ($create_subfolders)
	{
		$acumulated = $basepath.$sep;
		
		if (!is_array($segments))
		{
			$my_segments = explode($sep, $segments);
		}
		else
		{
			$my_segments = $segments;
		}
		
		if ($mode == "folder")
		{
			$all_but_file = $my_segments;
		}
		else
		{
			$all_but_file = array_slice($my_segments, 0, count($my_segments) - 1);
		}
		
		foreach ($all_but_file as $seg)
		{
			$acumulated .= $seg.$sep;

			if (!file_exists($acumulated))
			{
				mkdir($acumulated, 0777);
			}
		}
	}
	
	return $basepath.$sep.(
		is_array($segments) ? implode($sep, $segments) : $segments);
}

/* End of file my_path_helper.php */
/* Location: ./application/helpers/my_path_helper.php */