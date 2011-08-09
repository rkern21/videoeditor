<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2011 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 * @version $Id: scanner.php 410 2011-01-24 11:29:01Z nikosdion $
 */

// Protection against direct access
defined('AKEEBAENGINE') or die('Restricted access');

/* Windows system detection */
if(!defined('_AKEEBA_IS_WINDOWS'))
{
	if (function_exists('php_uname'))
		define('_AKEEBA_IS_WINDOWS', stristr(php_uname(), 'windows'));
	else
		define('_AKEEBA_IS_WINDOWS', DS == '\\');
}

/**
 * A filesystem scanner, for internal use
 */
class AEUtilScanner
{
	public static function &getFiles($folder, $fullpath = false)
	{
		// Initialize variables
		$arr = array();
		$false = false;

		if(!is_dir($folder) && !is_dir($folder.'/')) return $false;

		$handle = @opendir($folder);
		if ($handle === FALSE) {
			$handle = @opendir($folder.'/');
		}
		// If directory is not accessible, just return FALSE
		if ($handle === FALSE) {
			return $false;
		}

		$registry =& AEFactory::getConfiguration();
		$dereferencesymlinks = $registry->get('engine.archiver.common.dereference_symlinks');		
		
		while ( (($file = @readdir($handle)) !== false) )
		{
			if (($file != '.') && ($file != '..'))
			{
				// # Fix 2.4.b1: Do not add DS if we are on the site's root and it's an empty string
				// # Fix 2.4.b2: Do not add DS is the last character _is_ DS
				$ds = ($folder == '') || ($folder == '/') || (@substr($folder, -1) == '/') || (@substr($folder, -1) == DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR;
				$dir = "$folder/$file";
				$isDir = @is_dir($dir);
				$isLink = @is_link($dir);
				if (!$isDir || ($isDir && $isLink && !$dereferencesymlinks) ) {
					if($fullpath) {
						$data = _AKEEBA_IS_WINDOWS ? AEUtilFilesystem::TranslateWinPath($dir) : $dir;
					} else {
						$data = _AKEEBA_IS_WINDOWS ? AEUtilFilesystem::TranslateWinPath($file) : $file;
					}
					if($data) $arr[] = $data;
				}
			}
		}
		@closedir($handle);

		return $arr;
	}

	public static function &getFolders($folder, $fullpath = false)
	{
		// Initialize variables
		$arr = array();
		$false = false;

		if(!is_dir($folder) && !is_dir($folder.'/')) return $false;

		$handle = @opendir($folder);
		if ($handle === FALSE) {
			$handle = @opendir($folder.'/');
		}
		// If directory is not accessible, just return FALSE
		if ($handle === FALSE) {
			return $false;
		}

		$registry =& AEFactory::getConfiguration();
		$dereferencesymlinks = $registry->get('engine.archiver.common.dereference_symlinks');		
		
		while ( (($file = @readdir($handle)) !== false) )
		{
			if (($file != '.') && ($file != '..'))
			{
				$dir = "$folder/$file";
				$isDir = @is_dir($dir);
				$isLink = @is_link($dir);
				if ($isDir) {
					if(!$dereferencesymlinks && $isLink) continue;
					if($fullpath) {
						$data = _AKEEBA_IS_WINDOWS ? AEUtilFilesystem::TranslateWinPath($dir) : $dir;
					} else {
						$data = _AKEEBA_IS_WINDOWS ? AEUtilFilesystem::TranslateWinPath($file) : $file;
					}
					if($data) $arr[] = $data;
				}
			}
		}
		@closedir($handle);

		return $arr;
	}
}