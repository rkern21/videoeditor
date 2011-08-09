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

class AriSortUtils extends AriObject
{
	var $_key;
	var $_dir;
	
	function __construct($key, $dir = 'asc')
	{
		$this->_key = $key;
		$this->_dir = strtolower($dir);
	}
	
	function sort($a, $b)
	{
		$res = strcmp($a[$this->_key], $b[$this->_key]);
		
		return $this->_dir == 'asc' 
			? $res
			: -$res;
	}
}

class AriUtils extends AriObject
{
	function sortAssocArray($data, $key, $dir = 'asc')
	{
		$sort = new AriSortUtils($key, $dir);
		usort($data, array(&$sort, 'sort'));
		
		return $data;
	}
	
	function parseValueBySample($str, $sample)
	{
		return AriUtils::parseValue($str, gettype($sample));
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
	
	function getValue($val, $emptyValue)
	{
		return !empty($val) ? $val : $emptyValue;
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
	
	function getFilteredParam($arr, $name, $defValue = null, $filterMask = 0)
	{
		$param = AriUtils::getParam($arr, $name, $defValue);
		
		return $param;
	}

	function generateUniqueId()
	{
        mt_srand ((float) microtime() * 1000000);
        $key = mt_rand();

        return md5($key);
	}

	function resolvePath($path)
	{
		global $mosConfig_absolute_path;
		
		if (empty($mosConfig_absolute_path) || strlen($mosConfig_absolute_path) == 1 || strpos($path, $mosConfig_absolute_path) !== 0)
			$path = $mosConfig_absolute_path . '/' . $path;
		
		return $path;
	}

	function absPath2Url($path)
	{
		global $mosConfig_live_site, $mosConfig_absolute_path;
		
		$absPath = str_replace('\\', '/', $mosConfig_absolute_path);
		$path = str_replace('\\', '/', $path);
		if ($absPath != '/')
		{
			if (strstr($path, $absPath) !== 0)
			{
				$correctedParts = array();
				$absPathParts = explode('/', $absPath);
				$pathParts = explode('/', $path);
				for ($i = 0; $i < count($absPathParts) && $i < count($pathParts); $i++)
				{
					if ($absPathParts[$i] != $pathParts[$i])
						break;
						
					$correctedParts[]= $absPathParts[$i]; 
				}
				$absPath = implode('/', $correctedParts);
			}

			$path = str_replace($absPath, $mosConfig_live_site, $path);
		}
		else
		{
			$path = $mosConfig_live_site . $path;
		}
		
		return $path;
	}
	
	function absPath2Relative($path)
	{
		global $mosConfig_live_site, $mosConfig_absolute_path;
		
		$absPath = str_replace('\\', '/', $mosConfig_absolute_path);
		$path = str_replace('\\', '/', $path);
		if ($absPath != '/')
		{
			$path = str_replace($absPath, '', $path);
		}
		
		if (strpos($path, '/') === 0) $path = substr($path, 1);
		
		return $path;
	}
	
	function isRemoteResource($link)
	{
		if (empty($link))
			return false;
			
		return preg_match('/(https?|ftp):\/\/.+/', $link);
	}
}
?>