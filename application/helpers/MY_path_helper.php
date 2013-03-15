<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Returns an absolute path from the parameters received
 *
 * @access public
 * @param $segments array => it may be an array of segments of the path or a raw string
 * @param $basepath string => the starting point for the path. By default it will be the root folder of the codeigniter installation
 * @param $sep string => the director separator to use. By default it uses system's separator
 * @param $create_subfolders boolean => it can create the subfolders automatically if they don't exist
 * @param $mode string => if it's a folder, it can create it, if it's a file, the last segment must be ignored.
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