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
class JElementGantry extends JElement {
	
    /**
     * @global gantry used to access the core Gantry class
     * @param  $name
     * @param  $value
     * @param  $node
     * @param  $control_name
     * @return void
     */
	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;
		
		$output = "";
        $document =& JFactory::getDocument();

        require_once(dirname(__FILE__)."/../admin_format.php");
        $dispatcher =JDispatcher::getInstance();
        $dispatcher->register('onAfterRender',"GantryAdminPageFormatter");
        $dispatcher->register('onAfterDispatch',"GantryAdminPageFormatter");




		$arrayList = "'" . implode("', '", explode(",", str_replace(" ", "", $node->attributes('default')))) . "'";



		if (!defined('GANTRY_ADMIN')) {
            JFactory::getApplication()->set('MooToolsVersion', '1.2.4 +Compat');
            JHTML::addIncludePath(JPATH_PLUGINS.DS.'system'.DS.'mtupgrade');

			require_once(dirname(dirname(__FILE__)) . '/../gantry.php');

            //replace mootools with our own moo1.3 version
            $moo11 = JURI::root(true) .'/media/system/js/mootools.js';
            $moo12 = JURI::root(true) .'/plugins/system/mtupgrade/mootools.js';
            $moo13 = $gantry->gantryUrl.'/js/mootools.js';
            $a = array();
            foreach ($document->_scripts as $k => $v) {
                if ($k == $moo11 || $k == $moo12) { $a[$moo13] = $v; }
                else { $a[$k] = $v; }
            }
            $document->_scripts = $a;

			gantry_import('core.gantrybrowser');
			$browser = new GantryBrowser();

            $gantry_created_dirs = array(
                $gantry->custom_dir,
                $gantry->custom_menuitemparams_dir
            );

			$this->template = end(explode(DS, $gantry->templatePath));
			gantry_addStyle($gantry->gantryUrl.'/admin/widgets/gantry.css');
			if ($browser->name == 'ie' && $browser->version == '7' && file_exists($gantry->gantryPath . DS . 'admin' . DS . 'widgets' . DS . 'gantry-ie7.css')) {
				gantry_addStyle($gantry->gantryUrl.'/admin/widgets/gantry-ie7.css');
			}
			gantry_addScript($gantry->gantryUrl.'/admin/widgets/gantry.js');
			gantry_addInlineScript("var GantrySlideList = [".$arrayList."];var AdminURI = '".JURI::base()."';var UnallowedParams = ['" . implode("', '", $gantry->dontsetinmenuitem) . '\'];');
			gantry_addInlineScript($this->gantryLang());
			
			// fixes Firefox < 3.7 input line-height issue
			
			if (($browser->name == 'firefox' && $browser->version < '3.7') || ($browser->name == 'ie' && $browser->version > '6')) {
				$css = ".text-short, .text-medium, .text-long, .text-color {padding-top: 4px;height:19px;}";
				gantry_addInlineStyle($css);
			}
			
			if ($browser->name == 'ie' && $browser->shortversion == '7') {
				$css = "
					.g-surround, .g-inner, .g-surround > div {zoom: 1;position: relative;}
					.text-short, .text-medium, .text-long, .text-color {border:0 !important;}
					.selectbox {z-index:500;position:relative;}
					.group-fusionmenu, .group-splitmenu {position:relative;margin-top:0 !important;zoom:1;}
					.scroller .inner {position:relative;}
					.moor-hexLabel {display:inline-block;zoom:1;float:left;}
					.moor-hexLabel input {float:left;}
				";
				gantry_addInlineStyle($css);
			}


            //create dirs needed by gantry
            foreach($gantry_created_dirs as $dir){
                if (is_readable(dirname($dir)) && is_writeable(dirname($dir)) &&!JFolder::exists($dir)){
                    JFolder::create($dir);
                }
            }


            if (version_compare(JVERSION, '1.5.14', '<=')) {
                $tmpscripts = array();
                foreach ($document->_scripts as $script => $type){
                    if ($script != $gantry->baseUrl.'media/system/js/mootools.js'){
                        $tmpscripts[$script] = $type;
                    }
                    else {
                        $tmpscripts[$gantry->gantryUrl.'/js/mootools-1.1.2.js'] = $type;
                    }
                }
                $document->_scripts = $tmpscripts;
            }
            
			$this->checkAjaxTool();
			
			define('GANTRY_ADMIN', 1);
		}
        if (file_exists($gantry->templatePath."/gantry.scripts.php") && is_readable($gantry->templatePath."/gantry.scripts.php")){
            include_once($gantry->templatePath."/gantry.scripts.php");
            if (function_exists('gantry_params_init')){
                gantry_params_init();
            }
        }
        $this->_parent->addElementPath($gantry->templatePath.DS.'elements');
        $this->_parent->addElementPath($gantry->templatePath.DS.'admin/elements');
	}
	
	function gantryLang() {
		return "
			GantryLang = {
				'preset_title': '" . JText::_('PRESET_TITLE') . "',
				'preset_select': '" . JText::_('PRESET_SELECT') . "',
				'preset_name': '" . JText::_('PRESET_NAME') . "',
				'key_name': '" . JText::_('KEY_NAME') . "',
				'preset_naming': '" . JText::_('PRESET_NAMING') . "',
				'preset_skip': '" . JText::_('PRESET_SKIP') . "',
				'success_save': '" . JText::_('SUCCESS_SAVE') . "',
				'success_msg': '" .JText::_('SUCCESS_MSG') . "',
				'fail_save': '" . JText::_('FAIL_SAVE') . "',
				'fail_msg': '" . JText::_('FAIL_MSG') . "',
				'cancel': '" . JText::_('CANCEL') . "',
				'save': '" . JText::_('SAVE') . "',
				'retry': '" . JText::_('RETRY') . "',
				'close': '" . JText::_('CLOSE') . "',
				'show_parameters': '" . JText::_('SHOW_PARAMETERS') . "'
			};
			var GantryPrefix = 'params';
		";
	}
	
	function checkAjaxTool() {
		global $gantry;
		
		$ajax_tool = "gantry-ajax-admin.php";
		$admin_system = JPATH_ROOT . "/administrator/templates/system/";
		$origin = $gantry->gantryPath . "/admin/$ajax_tool";
		
		if ((!file_exists($admin_system . $ajax_tool) || (filesize($admin_system . $ajax_tool) != filesize($origin))) && file_exists($admin_system) && is_dir($admin_system) && is_writable($admin_system)) {
			jimport('joomla.filesystem.file');
			
			if (file_exists($admin_system . $ajax_tool)) JFile::delete($admin_system . $ajax_tool);
			JFile::copy($origin, $admin_system . $ajax_tool);
		}
	}
}