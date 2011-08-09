<?php
/**
 * @package   Hybrid Template - RocketTheme
 * @version   1.5.4 November 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
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
class GantryFeatureRTL extends GantryFeature {

    var $_feature_name = 'rtl';

    function isInPosition($position) {
        return false;
    }
	function isOrderable(){
		return false;
	}
	
    
	function init() {
        global $gantry;
        $document =& $gantry->document;
        
        $g_direction = $gantry->get('direction');
        
        if ($g_direction != '') $document->direction = $g_direction;

        
        if ($document->direction == "rtl") {
			$gantry->addStyle("rtl.css");
			$gantry->addBodyClass("rtl");
		}
	}
}