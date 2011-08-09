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

class AriParametersHelper
{
	function flatParametersToArray($flatParams, $splitter = '_', $defaultNS = '_default')
	{
		$params = array($defaultNS => array());
		
		if (empty($flatParams))
			return $params;
		
		if (!is_array($flatParams))
			$flatParams = $flatParams->toArray();
		$currentNS = null;
		foreach ($flatParams as $key => $value)
		{
			$nsList = explode($splitter, $key);
			$paramKey = array_pop($nsList);
			$cnt = count($nsList);
			if ($cnt == 0)
			{
				$currentNS =& $params[$defaultNS];
			}
			else
			{
				$currentNS =& $params;
				for ($i = 0; $i < $cnt; $i++)
				{
					$ns = $nsList[$i];
					if (!array_key_exists($ns, $currentNS))
					{
						$currentNS[$ns] = array();
					}
						
					$currentNS =& $currentNS[$ns];
				}
			}

			$currentNS[$paramKey] = $value;
		}

		return $params;
	}
	
	function getUniqueOverrideParameters($srcParams, $overrideParams, $caseInsensitive = false)
	{
		$uniqueParams = array();
		
		foreach ($srcParams as $srcKey => $srcValue)
		{
			if (is_array($srcValue))
			{
				if (isset($overrideParams[$srcKey]) || ($caseInsensitive && isset($overrideParams[strtolower($srcKey)])))
				{
					$subParams = AriParametersHelper::getUniqueOverrideParameters(
						$srcValue, 
						isset($overrideParams[$srcKey]) ? $overrideParams[$srcKey] : $overrideParams[strtolower($srcKey)],
						$caseInsensitive);
					if (count($subParams) > 0)
						$uniqueParams[$srcKey] = $subParams;
				}
			}
			else if (array_key_exists($srcKey, $overrideParams) || ($caseInsensitive && array_key_exists(strtolower($srcKey), $overrideParams)))
			{
				$overrideValue = AriUtils2::parseValueBySample(
					isset($overrideParams[$srcKey]) ? $overrideParams[$srcKey] : $overrideParams[strtolower($srcKey)], 
					$srcValue);
				if ($overrideValue != $srcValue)
					$uniqueParams[$srcKey] = $overrideValue; 
			}
		}
		
		return $uniqueParams;
	}
}
?>