<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: installer.php 728 2011-06-14 11:01:32Z nikosdion $
 * @since 3.3
 */

defined('_JEXEC') or die('');

jimport('joomla.application.component.model');
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');

class AkeebaModelInstaller extends JModel
{
	/** @var object JTable object */
	var $_table = null;

	/** @var object JTable object */
	var $_url = null;

	/**
	 * Overridden constructor
	 * @access	protected
	 */
	public function __construct()
	{
		parent::__construct();

	}
	
	/**
	 * Fetches a package from the upload for and saves it to the temporary directory
	 */
	public function upload()
	{
		// Get the uploaded file information
		$userfile = JRequest::getVar('install_package', null, 'files', 'array' );

		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_WARNINSTALLFILE'));
			} else {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
			}
			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_WARNINSTALLZLIB'));
			} else {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			}
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_NO_FILE_SELECTED'));
			} else {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('No file selected'));
			}
			return false;
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 )
		{
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_WARNINSTALLUPLOADERROR'));
			} else {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLUPLOADERROR'));
			}
			return false;
		}

		// Build the appropriate paths
		$config =& JFactory::getConfig();
		$tmp_dest 	= $config->getValue('config.tmp_path').DS.$userfile['name'];
		$tmp_src	= $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Store the uploaded package's location
		$session = JFactory::getSession();
		$session->set('compressed_package', $tmp_dest, 'akeeba');
		
		return true;
	}
	
	public function download()
	{
		// Get a database connector
		$db = & JFactory::getDBO();

		// Get the URL of the package to install
		$url = JRequest::getString('install_url');

		// Did you give us a URL?
		if (!$url) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL'));
			} else {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('Please enter a URL'));
			}
			return false;
		}

		// Download the package at the URL given
		$p_file = JInstallerHelper::downloadPackage($url);

		// Was the package downloaded?
		if (!$p_file) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL'));
			} else {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('Invalid URL'));
			}
			return false;
		}
		
		$config =& JFactory::getConfig();
		$tmp_dest 	= $config->getValue('config.tmp_path');

		// Store the uploaded package's location
		$session = JFactory::getSession();
		$session->set('compressed_package', $tmp_dest.DS.$p_file, 'akeeba');
		
		return true;
	}
	
	function extract()
	{
		$session = JFactory::getSession();
		$compressed_package = $session->get('compressed_package', null, 'akeeba');
		
		// Do we have a compressed package?
		if(is_null($compressed_package)) {
			JError::raiseWarning('', JText::_('@todo - TRANSLATE: No package specified'));
			return false;
		}
		
		// Extract the package
		$package = JInstallerHelper::unpack($compressed_package);
		$session->set('package', $package, 'akeeba');
		
		return true;
	}
	
	function fromDirectory()
	{
		// Get the path to the package to install
		$p_dir = JRequest::getString('install_directory');
		$p_dir = JPath::clean( $p_dir );

		// Did you give us a valid directory?
		if (!is_dir($p_dir)) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_PLEASE_ENTER_A_PACKAGE_DIRECTORY'));
			} else {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('Please enter a package directory'));
			}
			return false;
		}

		// Detect the package type
		$type = JInstallerHelper::detectType($p_dir);

		// Did you give us a valid package?
		if (!$type) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_PATH_DOES_NOT_HAVE_A_VALID_PACKAGE'));
			} else {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('Path does not have a valid package'));
			}
			return false;
		}

		$package['packagefile'] = null;
		$package['extractdir'] = null;
		$package['dir'] = $p_dir;
		$package['type'] = $type;
		
		$session = JFactory::getSession();
		$session->set('package', $package, 'akeeba');

		return true;
	}

	function realInstall()
	{
		$this->setState('action', 'install');
		
		$session = JFactory::getSession();
		$package = $session->get('package', null, 'akeeba');

		// Was the package unpacked?
		if (!$package || empty($package)) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				$this->setState('message', JText::_('COM_INSTALLER_UNABLE_TO_FIND_INSTALL_PACKAGE'));
			} else {
				$this->setState('message', JText::_('Unable to find install package'));
			}
			return false;
		}

		// Get an installer instance
		$installer =& JInstaller::getInstance();

		// Install the package
		if (!$installer->install($package['dir'])) {
			// There was an error installing the package
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				$msg = JText::sprintf('COM_INSTALLER_INSTALL_ERROR', JText::_('COM_INSTALLER_TYPE_TYPE_'.$package['type']));
			} else {
				$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Error'));
			}
			$result = false;
		} else {
			// Package installed sucessfully
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				$msg = JText::sprintf('COM_INSTALLER_INSTALL_SUCCESS', JText::_('COM_INSTALLER_TYPE_TYPE_'.$package['type']));
			} else {
				$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Success'));
			}
			$result = true;
		}

		// Set some model state values
		JFactory::getApplication()->enqueueMessage($msg);
		$this->setState('name', $installer->get('name'));
		$this->setState('result', $result);
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));
		$this->setState('extension_message', $installer->get('extension_message'));
		JFactory::getApplication()->setUserState('com_installer.redirect_url', $installer->get('redirect_url'));

		return $result;
	}

	function cleanUp()
	{
		$session = JFactory::getSession();
		$package = $session->get('package', $package, 'akeeba');

		// Was the package unpacked?
		if (!$package || empty($package)) {
			if(version_compare(JVERSION, '1.6.0', 'ge')) {
				$this->setState('message', JText::_('COM_INSTALLER_UNABLE_TO_FIND_INSTALL_PACKAGE'));
			} else {
				$this->setState('message', JText::_('Unable to find install package'));
			}
			return false;
		}
		
		// Cleanup the install files
		if (!is_file($package['packagefile'])) {
			$config =& JFactory::getConfig();
			$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
		
		return true;
	}
	
	public function getExtensionName($p_dir)
	{
		if(version_compare(JVERSION,'1.6.0','ge')) {
			return $this->_getExtensionName_j16($p_dir);
		} else {
			return $this->_getExtensionName_j15($p_dir);
		}
	}
	
	private function _getExtensionName_j15($p_dir)
	{
		if(empty($p_dir)) return false;
		
		// Search the install dir for an xml file
		$files = JFolder::files($p_dir, '\.xml$', 1, true);

		if (count($files) > 0)
		{

			foreach ($files as $file)
			{
				$xmlDoc = & JFactory::getXMLParser();
				$xmlDoc->resolveErrors(true);

				if (!$xmlDoc->loadXML($file, false, true))
				{
					// Free up memory from DOMIT parser
					unset ($xmlDoc);
					continue;
				}
				$root = & $xmlDoc->documentElement;
				if (!is_object($root) || ($root->getTagName() != "install" && $root->getTagName() != 'mosinstall'))
				{
					unset($xmlDoc);
					continue;
				}

				$type = $root->getAttribute('type');
				$name = false;
				$cname = '';
				$group = '';
				
				unset($xmlDoc);
				$xml = & JFactory::getXMLParser('Simple');
				$xml->loadFile($file);
				$xmlDoc = $xml->document;
				
				// Get the name
				switch($type) {
					case 'component':
					case 'template':
						$name = $xmlDoc->getElementByPath('name');
						$name = JFilterInput::clean($name->data(), 'cmd');
						if($type == 'template') {
							$cname = $xml->document->attributes('client');
						}
						break;
					
					case 'module':
					case 'plugin':
						$cname = $xml->document->attributes('client');
						$group = $xml->document->attributes('group');
						$element =& $xml->document->getElementByPath('files');
						if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
							$files = $element->children();
							foreach ($files as $file) {
								if ($file->attributes($type)) {
									$name = $file->attributes($type);
									break;
								}
							}
						}
						break;
				}
				
				if(empty($name)) $name = false;
				
				if($name !== false) {
					// Make sure the extension is laready installed - otherwise there is no point!
					jimport('joomla.filesystem.file');
					jimport('joomla.filesystem.folder');
					switch($type) {
						case 'component':
							if(
								!JFolder::exists(JPATH_ROOT.'/components/com_'.$name)
								&& !JFolder::exists(JPATH_ROOT.'/administrator/components/com_'.$name)
							) $name = false;
							break;
							
						case 'template':
							$base = ($cname == 'site') ? JPATH_ROOT : JPATH_ADMINISTRATOR;
							$base .= '/templates/';
							if(
								!JFolder::exists($base.$name)
							) $name = false;
							break;
							
						case 'module':
							$base = ($cname == 'site') ? JPATH_ROOT : JPATH_ADMINISTRATOR;
							$base .= '/modules/';
							if(
								!JFolder::exists($base.'mod_'.$name)
							) $name = false;
							break;
							
						case 'plugin':
							$base = JPATH_ROOT.'/plugins/'.$group.'/';
							if(
								!JFile::exists($base.'plg_'.$name.'.php')
								&& !JFile::exists($base.$name.'.php')
								&& !JFolder::exists($base.$name)
								&& !JFolder::exists($base.'plg_'.$name)
							) $name = false;
							break;
					}
				}
				
				// Free up memory from DOMIT parser
				unset ($xmlDoc);
				
				if($name === false) {
					return false;
				}
				
				// Return the name
				return array(
					'name' => $name, 
					'client' => $cname,
					'group' => $group
				);
			}

			return false;
		} else {
			return false;
		}
	}
	
	private function _getExtensionName_j16($p_dir)
	{
		// Search the install dir for an XML file
		$files = JFolder::files($p_dir, '\.xml$', 1, true);

		if ( ! count($files))
		{
			JError::raiseWarning(1, JText::_('JLIB_INSTALLER_ERROR_NOTFINDXMLSETUPFILE'));
			return false;
		}

		foreach ($files as $file)
		{
			if( ! $xml = JFactory::getXML($file))
			{
				continue;
			}

			if(($xml->getName() != 'install') && ($xml->getName() != 'extension'))
			{
				unset($xml);
				continue;
			}

			$type = (string)$xml->attributes()->type;
			
			unset($xml);
			$xml = JFactory::getXMLParser('simple');
			$xml->loadFile($file);
			
			// Get the name
			switch($type) {
				case 'component':
				case 'template':
					$name = $xml->document->getElementByPath('name');
					$name = JFilterInput::clean($name->data(), 'cmd');
					if($type == 'template') {
						$cname = $xml->document->attributes('client');
					}
					break;

				case 'module':
				case 'plugin':
					$cname = $xml->document->attributes('client');
					$group = $xml->document->attributes('group');
					$element =& $xml->document->getElementByPath('files');
					if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
						$files = $element->children();
						foreach ($files as $file) {
							if ($file->attributes($type)) {
								$name = $file->attributes($type);
								break;
							}
						}
					}
					break;
			}

			if(empty($name)) $name = false;

			if($name !== false) {
				// Make sure the extension is laready installed - otherwise there is no point!
				jimport('joomla.filesystem.file');
				jimport('joomla.filesystem.folder');
				switch($type) {
					case 'component':
						$name = strtolower($name);
						$name = substr($name,0,4) == 'com_' ? substr($name,4) : $name;
						if(
							!JFolder::exists(JPATH_ROOT.'/components/com_'.$name)
							&& !JFolder::exists(JPATH_ROOT.'/administrator/components/com_'.$name)
						) $name = false;
						break;

					case 'template':
						$base = ($cname == 'site') ? JPATH_ROOT : JPATH_ADMINISTRATOR;
						$base .= '/templates/';
						if(
							!JFolder::exists($base.$name)
						) $name = false;
						break;

					case 'module':
						$base = ($cname == 'site') ? JPATH_ROOT : JPATH_ADMINISTRATOR;
						$base .= '/modules/';
						if(
							!JFolder::exists($base.'mod_'.$name)
						) $name = false;
						break;

					case 'plugin':
						$base = JPATH_ROOT.'/plugins/'.$group.'/';
						if(
							!JFile::exists($base.'plg_'.$name.'.php')
							&& !JFile::exists($base.$name.'.php')
							&& !JFolder::exists($base.$name)
							&& !JFolder::exists($base.'plg_'.$name)
						) $name = false;
						break;
						
					default:
						$name = false;
				}
			} else {
				return false;
			}

			// Free up memory from DOMIT parser
			unset ($xml);
			
			if($name === false) {
				return false;
			}
			
			// Return the name
			return array(
				'name' => $name, 
				'client' => $cname,
				'group' => $group
			);
			
			// Free up memory
			unset ($xml);
			return $type;
		}

		return false;
	}
}