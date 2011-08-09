<?php
/**
 * @package     gantry
 * @subpackage  features
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

defined('JPATH_BASE') or die();

gantry_import('core.gantryfeature');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryFeatureModuleOverlays extends GantryFeature {

    var $_feature_name = 'moduleoverlays';

    function isEnabled(){
        return true;
    }
    function isInPosition($position) {
        return false;
    }
	function isOrderable(){
		return false;
	}
	
    
	function init() {
        global $gantry;
		
		// overwrite overlays param if set
		if (!$gantry->get('moduleoverlays-enabled')) {
			JRequest::setVar('tp',0);
		}
	}
}