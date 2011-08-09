<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2011 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 * @version $Id: libraries.php 694 2011-06-03 22:32:37Z nikosdion $
 */

// Protection against direct access
defined('AKEEBAENGINE') or die('Restricted access');

/**
 * Joomla! 1.6 libraries off-site relocation workaround
 * 
 * After the application of patch 23377
 * (http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemEdit&tracker_item_id=23377)
 * it is possible for the webmaster to move the libraries directory of his Joomla!
 * site to an arbitrary location in the folder tree. This filter works around this
 * new feature by creating a new extra directory inclusion filter.
 */
class AEFilterPlatformLibraries extends AEAbstractFilter
{
	public function __construct()
	{
		$this->object	= 'dir';
		$this->subtype	= 'inclusion';
		$this->method	= 'direct';
		
		// FIXME This filter doesn't work very well on many live hosts. Disabled for now.
		parent::__construct();
		return;
		
		

		if(empty($this->filter_name)) $this->filter_name = strtolower(basename(__FILE__,'.php'));

		// Get the saved library path and compare it to the default
		$jlibdir = AEPlatform::get_platform_configuration_option('jlibrariesdir', '');
		if(empty($jlibdir)) {
			if(defined('JPATH_LIBRARIES')) {
				$jlibdir = JPATH_LIBRARIES;	
			} elseif(defined('JPATH_PLATFORM')) {
				$jlibdir = JPATH_PLATFORM;	
			} else {
				$jlibdir = false;
			}
		}
		
		if($jlibdir !== false) {
			$jlibdir = AEUtilFilesystem::TranslateWinPath($jlibdir);
			$defaultLibraries = AEUtilFilesystem::TranslateWinPath(JPATH_SITE.DS.'libraries');
			
			if($defaultLibraries != $jlibdir)
			{
				// The path differs, add it here
				$this->filter_data['JPATH_LIBRARIES'] = $jlibdir;
			}
		} else {
			$this->filter_data = array();
		}
		parent::__construct();
	}
}