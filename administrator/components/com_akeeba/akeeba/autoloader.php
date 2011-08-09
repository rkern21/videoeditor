<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2011 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 * @version $Id: autoloader.php 409 2011-01-24 09:30:22Z nikosdion $
 */

// Protection against direct access
defined('AKEEBAENGINE') or die('Restricted access');

global $Akeeba_Class_Map;

// Class map
if(empty($Akeeba_Class_Map))
{
	$Akeeba_Class_Map = array(
		'AECoreDomain'		=> 'core'.DIRECTORY_SEPARATOR.'domain',
		'AECore'			=> 'core',
		'AEUtil'			=> 'utils',
		'AEAbstract'		=> 'abstract',
		'AEPlatform'		=> 'platform'.DIRECTORY_SEPARATOR.AKEEBAPLATFORM,
		'AEFilterStackPlatform'	=> 'platform'.DIRECTORY_SEPARATOR.AKEEBAPLATFORM.DIRECTORY_SEPARATOR.'filters'.DIRECTORY_SEPARATOR.'stack',
		'AEFilterPlatform'	=> 'platform'.DIRECTORY_SEPARATOR.AKEEBAPLATFORM.DIRECTORY_SEPARATOR.'filters',
		'AEDriverPlatform'	=> 'platform'.DIRECTORY_SEPARATOR.AKEEBAPLATFORM.DIRECTORY_SEPARATOR.'drivers',
		'AEArchiver'		=> 'engines'.DIRECTORY_SEPARATOR.'archiver',
		'AEDump'			=> 'engines'.DIRECTORY_SEPARATOR.'dump',
		'AEFinalization'	=> 'engines'.DIRECTORY_SEPARATOR.'finalization',
		'AEScan'			=> 'engines'.DIRECTORY_SEPARATOR.'scan',
		'AEWriter'			=> 'engines'.DIRECTORY_SEPARATOR.'writer',
		'AEPostproc'		=> 'engines'.DIRECTORY_SEPARATOR.'proc',
		'AEFilterStack'		=> 'filters'.DIRECTORY_SEPARATOR.'stack',
		'AEFilter'			=> 'filters',
		'AEDriver'			=> 'drivers'
	);
}

/**
 * Loads the $class from a file in the directory $path, if and only if
 * the class name starts with $prefix. Will also try the plugins path
 * if the class is not present in the regular location.
 * @param string $class The class name
 * @param string $prefix The prefix to test
 * @param string $path The path to load the class from
 * @return bool True if we loaded the class
 */
function LoadIfPrefix($class, $prefix, $path)
{
	// Find the root path of Akeeba's installation. Static so that we can save some CPU time.
	static $root;
	if(empty($root))
	{
		if(defined('AKEEBAROOT')) {
			$root = AKEEBAROOT;
		} else {
			$root = dirname(__FILE__);
		}
	}

	if(strpos($class, $prefix) === 0)
	{
		$filename = strtolower(substr($class, strlen($prefix))) . '.php';
		// Try the regular path
		if(file_exists($root.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$filename))
		{
			require_once $path.DIRECTORY_SEPARATOR.$filename;
			return true;
		}
		// Try the plugins path
		elseif(file_exists($root.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$filename))
		{
			require_once $root.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$filename;
			return true;
		}
		else
		{
			return false;
		}
	}
	return false;
}

/**
 * PHP5 class autoloader for all of Akeeba's classes
 * @param string $class_name The class name to load
 */
function AEAutoloader($class_name)
{
	global $Akeeba_Class_Map;
	// We can only handle AE* class names
	if(substr($class_name,0,2) != 'AE') return;

	// The configuration class is a special case
	if($class_name == 'AEConfiguration') {
		if(defined('AKEEBAROOT')) {
			$root = AKEEBAROOT;
		} else {
			$root = dirname(__FILE__);
		}
		require_once $root.DIRECTORY_SEPARATOR.'configuration.php';
	}

	// Try to load the class using the prefix-to-path mapping, also handles plugin path
	foreach($Akeeba_Class_Map as $prefix => $path)
	{
		if( LoadIfPrefix($class_name, $prefix, $path) ) return;
	}

	return;
}