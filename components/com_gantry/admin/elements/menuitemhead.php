<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementMenuItemHead extends JElement {

	var	$_name = 'MenuItemHead';

	function fetchElement($name, $value, &$node, $control_name) {
        global $gantry;
        
		if (!defined("MENUITEM_HEADER")) {
			$this->template = end(explode(DS, $gantry->templatePath));
			gantry_addScript($gantry->gantryUrl.'/admin/widgets/menuitemhead/js/menuitemhead.js');

			define("MENUITEM_HEADER", 1);
		}
		
		if (JFile::exists(dirname(__FILE__).DS."menuitem.php")) {
            require_once(dirname(__FILE__).DS."menuitem.php");
        } else {
            require_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'parameter'.DS.'element'.DS."menuitem.php");
        }

		if (JFile::exists(dirname(__FILE__).DS."toggle.php")) {
            require_once(dirname(__FILE__).DS."toggle.php");
        } else {
            require_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'parameter'.DS.'element'.DS."toggle.php");
        }
		
		$instance = new JElementMenuItem;
		$toggle = new JElementToggle;
		
		$output = '<div id="master-bar">';
		$output .= '	<span id="master-defaults" class="button active">' . JText::_('DEFAULTS') . '</span>';
		$output .= '	<span id="master-items" class="button">' . JText::_('MENU_ITEMS') . '</span>';
		$output .= '	<div class="master-items">' . $instance->fetchElement($name,$value,$node, $control_name) . '</div>';
		$output .= '</div>';
		$output .= '<div id="master-bar-desc">';
		$output .= ' 	<span class="notice_defaults">'.JText::_('DEFAULTS_NOTICE').'</span>';
		$output .= '	<span class="notice_menuitems"><a id="erase-custom" href="#">Remove Custom Settings for this Menu Item</a><br /><br /></span>';
		$output .= '	<span class="notice_menuitems">'.JText::_('MENUITEMS_NOTICE').'</span>';
		$output .= "</div>";
		
		return $output;
	}
	
}