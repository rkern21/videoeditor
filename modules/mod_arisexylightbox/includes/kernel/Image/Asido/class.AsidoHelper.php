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

if (!class_exists('Asido')) AriKernel::import('Image.Asido.asido');

class AriAsidoHelper extends AriObject
{
	function init($preferDrivers = array('gd', 'imagick2', 'imagick', 'magickwand'))
	{
		$alias = array('imagick2' => 'imagick2_ext', 'imagick' => 'imagick_ext', 'magickwand' => 'magick_wand');
		
		reset($preferDrivers);

		foreach ($preferDrivers as $driver)
		{
			if (AriAsidoHelper::isExtensionLoaded(array($driver)))
			{
				if (array_key_exists($driver, $alias)) $driver = $alias[$driver];
				
				asido::driver($driver);

				return true;
			}
		}

		return false;
	}
	
	function isExtensionLoaded($drivers = array('imagick2', 'imagick', 'gd', 'magickwand'))
	{
		reset($drivers);

		foreach ($drivers as $driver)
		{
			if (($driver == 'imagick2' && !class_exists('Imagick')) ||
				($driver == 'imagick' && !function_exists('imagick_readImage'))) continue ;
			
			if ($driver == 'imagick2')
				$driver = 'imagick';	
			
			if (@extension_loaded($driver))
			{
				return true;
			}
		}
		
		return false;
	}
}
?>