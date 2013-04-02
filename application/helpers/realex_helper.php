<?php

/**
 * Dropdown with Credit Card types
 *
 * @access public
 */
function realex_credit_cards()
{
	return array(
				"" => "-Select-",
				"AMEX" => "American Express",
                "LASER" => "Laser",
                "MC" => "MasterCard",
                "VISA" => "Visa");
}

/**
 * Dropdown with Expiry date months
 *
 * @access public
 */
function realex_expiry_month()
{
	return array(
				"" => "-Select-",
	            "01" => "01-Jan",
                "02" => "02-Feb",
                "03" => "03-Mar",
                "04" => "04-Apr",
                "05" => "05-May",
                "06" => "06-Jun",
                "07" => "07-Jul",
                "08" => "08-Aug",
                "09" => "09-Sep",
                "10" => "10-Oct",
                "11" => "11-Nov",
                "12" => "12-Dec",);
}

/**
 * Dropdown with Expiry date years
 *
 * @access public
 */
function realex_expiry_year()
{
	$start = date("Y");
	$expiry_year = array("" => "-Select-");
	
	for ($i = 0; $i < 11; $i ++)
	{
		$expiry_year[substr($i + $start, 2, 2)] = $i+ $start;
	}
	
	return $expiry_year;
}

/**
 * Callback function to parse the XML into an array
 *
 * @access public
 * @param  string
 */
function xml2array($xml)
{
    $opened = array();
    $opened[1] = 0;
   
    $xml_parser = xml_parser_create();
    
    xml_parse_into_struct($xml_parser, $xml, $xmlarray);
    
    $array = array_shift($xmlarray);
    
    unset($array["level"]);
    unset($array["type"]);
    
    $arrsize = sizeof($xmlarray);
    
	for( $j=0; $j< $arrsize; $j++ ){
        $val = $xmlarray[$j];
        switch($val["type"])
        {
            case "open":
                $opened[$val["level"]] = 0;
            case "complete":
                $index = "";
                for($i = 1; $i < ($val["level"]); $i++)
                    $index .= "[" . $opened[$i] . "]";
                
                $path = explode('][', substr($index, 1, -1));
                $value = &$array;
                
                foreach($path as $segment)
                    $value = &$value[$segment];
                
                $value = $val;
                unset($value["level"]);
                unset($value["type"]);
                
                if($val["type"] == "complete")
                    $opened[$val["level"]-1]++;
            break;
            case "close":
                @$opened[$val["level"]-1]++;
                unset($opened[$val["level"]]);
            break;
        }
    }
	xml_parser_free($xml_parser);
    
    return $array;
} 