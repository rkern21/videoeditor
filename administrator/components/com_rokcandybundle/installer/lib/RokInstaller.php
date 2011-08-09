<?php
/**
 * @package   Installer Bundle Framework - RocketTheme
 * @version   @VERSION@ @BUILD_DATE@
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - @COPYRIGHT_YEAR@ RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Installer uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

jimport('joomla.installer.installer');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');

/**
 * Joomla base installer class
 *
 * @package		Joomla.Framework
 * @subpackage	Installer
 * @since		1.5
 */
class RokInstaller extends JInstaller
{	
	/**
	 * Set an installer adapter by name
	 *
	 * @access	public
	 * @param	string	$name		Adapter name
	 * @param	object	$adapter	Installer adapter object
	 * @return	boolean True if successful
	 * @since	1.5
	 */
	function setAdapter($name, $adapter = null)
	{
		$ret = parent::setAdapter($name, $adapter);
		if (!$ret){
			return $ret;
		}
		if (!is_object($adapter))
		{
			// Try to load the adapter object
			$adapter_file= dirname(__FILE__).DS.'adapters'.DS.strtolower($name).'.php';
			if (JFile::exists($adapter_file)){ 
				require_once($adapter_file);
				$class = 'RokInstaller'.ucfirst($name);
				if (!class_exists($class)) {
					return false;
				}

				$adapter = new $class($this);
				$adapter->parent =& $this;
                $this->_adapters[strtolower($name)] = &$adapter;
			}	
		}
		return true;
	}
}
