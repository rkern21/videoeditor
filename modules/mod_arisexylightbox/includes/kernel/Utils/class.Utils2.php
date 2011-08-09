<?php
/*
 * ARI Framework Lite
 *
 * @package		ARI Framework Lite
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

class AriUtils2
{
	function parseValueBySample($str, $sample)
	{
		return AriUtils2::parseValue($str, gettype($sample));
	}
	
	function parseValue($str, $type)
	{
		$retVal = $str;
		switch ($type)
		{
			case 'boolean':
				if (is_null($str))
				{
					$retVal = false;
				}
				else
				{
					$str = strtolower(trim($str));
					if ($str == 'true' || $str == 'false')
					{
	                	$retVal = ($str == 'true');
					}
					else
					{
						$retVal = !empty($str);
					}
				}
                break;

            case 'NULL':
                $retVal = null;
                break;

            case 'integer':
                $retVal = intval($str, 10);
                break;

            case 'double':
            case 'float':
                $retVal = floatval($str);
                break;
		}
		
		return $retVal;
	}
	
	function getParam($arr, $name, $defValue = null)
	{
		$retValue = $defValue;
		
		if (is_array($arr) && isset($arr[$name]))
		{
			$retValue = $arr[$name];
		}
		else if (is_object($arr) && isset($arr->{$name}))
		{
			$retValue = $arr->{$name};
		}

		return $retValue;
	}
}
?>