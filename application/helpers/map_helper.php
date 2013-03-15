<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Returns the distance between two pairs of coords
 *
 * @access public
 * @param string
 * @param string
 * @param string
 * @param string
 * @param string
 */
function point_distance($lat1, $lon1, $lat2, $lon2, $unit)
{ 
	$theta = $lon1 - $lon2; 
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
	$dist = acos($dist); 
	$dist = rad2deg($dist); 
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
	
	if ($unit == "K")
	{
		return ($miles * 1.609344); 
	}
	else if ($unit == "N")
	{
		return ($miles * 0.8684);
	}
	else
	{
		return $miles;
	}
}

/**
 * Returns coords from provided address
 *
 * @access public
 * @param string
 * @param string
 */
$addresses_searched = array();
function coordinates_from_address($address, $output_mode = "array")
{
	global $addresses_searched;

	/**
	 *  I implement this to avoid repeated calls to google maps
	 *  and I can increase the performance.
	 **/
	if (isset($addresses_searched[$address]))
	{
		return $output_mode == "text" ? implode(", ", $addresses_searched[$address]) : $addresses_searched[$address];
	}

	$geocoder = json_decode(file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.
		urlencode($address).'&sensor=false'), TRUE);
	
	$coordinates = array();
	if ($geocoder)
	{
		if ($geocoder['status'] == 'OK')
		{
			if (isset($geocoder['results']))
			{
				$coordinates = array(
					$geocoder['results'][0]['geometry']['location']['lat'],
					$geocoder['results'][0]['geometry']['location']['lng']
				);
			}
			else
			{
				$coordinates = array(
					$geocoder['geometry']['location']['lat'],
					$geocoder['geometry']['location']['lng']
				);
			}
		}
	}
	
	if ($coordinates)
	{
		$addresses_searched[$address] = $coordinates;
		return $output_mode == "text" ? implode(", ", $coordinates) : $coordinates;
	}
	else
	{
		return NULL;
	}	
}

/* End of file my_form_helper.php */
/* Location: ./application/helpers/my_form_helper.php */