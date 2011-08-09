<?php
/**
 * @package     gantry
 * @subpackage  features
 * @version		@VERSION@ @BUILD_DATE@
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - @COPYRIGHT_YEAR@ RocketTheme, LLC
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
class GantryFeatureMoo125 extends GantryFeature {
    var $_feature_name = 'moo125';

	function isEnabled(){
		if (version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.6', '<')){
            if (JPluginHelper::isEnabled('system', 'mtupgrade')) return true;
			else return false;
		} else {
			return false;
		}
	}

    function isInPosition($position) {
        return false;
    }

	function isOrderable(){
		return false;
	}

	function init() {
        global $gantry;
        
        $doc =& $gantry->document;
        
        JHTML::_( 'behavior.mootools' );  
        
        //remove default mootools from default includes
		$oldmoo = JURI::root(true) .'/plugins/system/mtupgrade/mootools.js';
		$newmoo = $gantry->gantryUrl.'/js/mootools-1.2.5.js';
		
		$a = array();
		foreach ($doc->_scripts as $k => $v) {
			if ($k == $oldmoo) { $a[$newmoo] = $v; }
			else { $a[$k] = $v; }
		}
		$doc->_scripts = $a;
	}

}