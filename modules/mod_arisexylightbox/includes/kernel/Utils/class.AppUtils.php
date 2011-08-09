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

jimport('joomla.filesystem.folder');

class AriAppUtils
{
	function getExtraFieldsFromINI($path, $iniFileName, $recurse = false, $fullPath = false, $i18n = false)
	{
		$fields = array();

		$iniFileName = basename($iniFileName);
		if (empty($iniFileName))
			return $fields;
		
		$filePath = JPATH_ROOT . DS . $path . DS . $iniFileName;
		if ($i18n)
			$filePath = AriAppUtils::getLocalizedFileName($filePath);
		
		if (!@file_exists($filePath) || !is_file($filePath) || !is_readable($filePath))
			return $fields;
			
		$iniFields = parse_ini_file($filePath, true);
		if (empty($iniFields))
			return $fields;
			
		foreach ($iniFields as $secName => $secItems)
		{
			$prop = strtolower($secName);
			foreach ($secItems as $itemKey => $itemValue)
			{
				$key = $itemKey;
				if ($fullPath)
					$key = $path . DS . $key;
				if (!isset($fields[$key]))
					$fields[$key] = array();
					
				$fields[$key][$prop] = $itemValue;
			}
		}
		
		if ($recurse)
		{
			$subFolders = JFolder::folders($path);
			foreach ($subFolders as $subFolder)
			{
				$subFolderFields = AriAppUtils::getExtraFieldsFromINI($path . DS . $subFolder, $iniFileName, $recurse, $fullPath);
				if (count($subFolderFields) > 0)
					$fields = array_merge($fields, $subFolderFields);
			}
		}
	
		return $fields;
	}
	
	function getLocalizedFileName($filePath)
	{
		if (empty($filePath))
			return $filePath;
		
		$lang =& JFactory::getLanguage(); 
		$langTag = $lang->get('tag');

		if (empty($langTag))
			return $filePath;

		$defLang = $lang->getDefault();
		$prefLangs = array($langTag);
		if ($defLang != $langTag)
			$prefLangs[] = $defLang;
		
		$pathInfo = pathinfo($filePath);
		$baseName = !empty($pathInfo['extension']) ? basename($filePath, '.' . $pathInfo['extension']) : $pathInfo['basename'];
		foreach ($prefLangs as $prefLang)
		{
			$langFile = $pathInfo['dirname'] . DS . $baseName . '.' . $prefLang;
			if (!empty($pathInfo['extension']))
				$langFile .= '.' . $pathInfo['extension'];

			if (@file_exists($langFile) && is_file($langFile))
			{
				$filePath = $langFile;
				break;
			}
		}

		return $filePath;
	}
}
?>