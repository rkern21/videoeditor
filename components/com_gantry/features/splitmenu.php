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
class GantryFeatureSplitMenu extends GantryFeature {
    var $_feature_name = 'splitmenu';
    var $_feature_prefix = 'menu-type';

	function init() {
		global $gantry;
		$gantry->addStyle('splitmenu.css');
	}

    function isEnabled() {
        global $gantry;
        $menu_enabled = $gantry->get('menu-enabled');
        $selected_menu = $gantry->get($this->_feature_prefix);
        $cookie = 0;
        if ($gantry->browser->platform == 'iphone'){
            $prefix = $gantry->get('template_prefix');
            $cookiename = $prefix.$gantry->browser->platform.'-switcher';
            $cookie = $gantry->retrieveTemp('platform', $cookiename);
        }
        if (1 == (int)$menu_enabled && $selected_menu == $this->_feature_name && $cookie==0) return true;
        return false;
    }

    function isInPosition($position){
        if ($this->get('mainmenu-position') == $position || $this->get('submenu-position') == $position) return true;
        return false;
    }
	function isOrderable(){
		return false;
	}
	

	function render($position="") {
        global $gantry;
        $output='';
        $renderer	= $gantry->document->loadRenderer('module');
        $options	 = array( 'style' => "raw" );

        $group_params_str = '';
        $params=array();
        $group_params = $gantry->getParams($this->_feature_prefix."-".$this->_feature_name, true);

        foreach($group_params as $param_name => $param_value){
            $group_params_str .=  $param_name."=". $param_value['value']."\n";
        }

        if($position == $this->get('mainmenu-position')) {
            $params = $gantry->getParams($this->_feature_prefix."-".$this->_feature_name."-mainmenu", true);
            $module	 = JModuleHelper::getModule( 'mod_roknavmenu' );
            $module->params = '';
            foreach($params as $param_name => $param_value){
                $module->params .=  $param_name."=". $param_value['value']."\n";
            }
            $module->params .= $group_params_str;
            $output .= $renderer->render( $module, $options );
        }

        if ($position == $this->get('submenu-position')) {
            $params = $gantry->getParams($this->_feature_prefix."-".$this->_feature_name."-submenu", true);
			$options = array( 'style' => "submenu");
            $module	 = JModuleHelper::getModule( 'mod_roknavmenu' );
            $module->params = '';
            foreach($params as $param_name => $param_value){
                $module->params .=  $param_name."=". $param_value['value']."\n";
            }
            $module->params .= $group_params_str;
            $output .= $renderer->render( $module, $options );
        }
		return $output;
	}
}