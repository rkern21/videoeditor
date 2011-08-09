<?php
/**
* RokTabs Icons, Custom Param
*
* @package RocketTheme
* @subpackage roktabs.elements
* @version   1.16 November 1, 2010
* @author    RocketTheme http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/


// no direct access
defined('_JEXEC') or die();

/**
 * @package RocketTheme
 * @subpackage roktabs.elements
 */
class JElementRokTabsIcons extends JElement {
	
	var	$_name = 'roktabsicons';
	
	function fetchElement($name, $value, &$node, $control_name)
	{

		$document 	=& JFactory::getDocument();
		$app =& JFactory::getApplication();
		
		if (!defined('ROKTABS_ICONS')) {
			define('ROKTABS_ICONS', 1);
			
			
			$db =& JFactory::getDBO();
			$query = 'SELECT template'
						. ' FROM #__templates_menu'
						. ' WHERE client_id = 0 AND (menuid = 0 OR menuid = 0)'
						. ' ORDER BY menuid DESC';
			$db->setQuery($query, 0, 1);
			$template = $db->loadResult();
			
			$path = JURI::Root(true)."/modules/mod_roktabs/";
			$document->addStyleSheet($path.'admin/icons.css');
			$document->addScript($path.'admin/icons.js');
			$document->addScriptDeclaration("
				var SitePath = '".JURI::Root(true)."', TemplatePath = 'templates/".$template."', ModulePath = 'modules/mod_roktabs';
				window.addEvent('domready', function() {new RokTabsIcons();});
			");
		
		}
			
		$html = "";
		
		$value = str_replace(" ", "", $value);
		$list = explode(",", $value);
		
		$i = 0;
		foreach($list as $img) {
			$i++;
			$html .= "<div class='icons'>";
			$html .= "	<span class='tab_label'>Tab " . $i . ":</span> ";
			$html .= " <div class='preview_".$name.$i." icons_previews'></div>";
			$html .= "	<select class='inputbox'>";
			$html .= 		$this->loadIcons($name, $img, $template);
			$html .= "	</select>";
			$html .= "	<div class='controls'>";
			$html .= "		<span class='add' title='Add new tab icon'></span>";
			$html .= "		<span class='remove' title='Remove current tab icon'></span>";
			$html .= "	</div>";
			$html .= "	<div style='clear: both;'></div>";
			$html .= "</div>";
		}
		
		$html .= "<input id='params".$name."' name='params[".$name."]' type='hidden' value='".$value."' />";
		return $html;
	}
	
	function loadIcons($name, $value, $template)
	{
		$path = JPATH_SITE.DS."modules".DS."mod_roktabs".DS."images".DS;
		$urlPath = JURI::Root(true)."/modules/mod_roktabs/images/";
		
		if ($this->_parent->get('tabs_iconpath') != '') {
			$path = JPATH_SITE.DS.$this->_parent->get('tabs_iconpath');
			$urlPath = JURI::Root(true)."/".$this->_parent->get('tabs_iconpath');
		}
		
		$path = str_replace('__template__', 'templates'.DS.$template, $path);
		$urlPath = str_replace('__template__', 'templates/'.$template, $path);
		$path = str_replace('__module__', 'modules/mod_roktabs', $path);
		$urlPath = str_replace('__module__', 'modules/mod_roktabs', $path);
		
		$icons = array('__none__');
		$html = "";
		
		if ($handle = @opendir($path)) {
		    while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {
		            $ext = strtolower(substr($file, strrpos($file, '.') + 1));
					if ($ext == 'gif' || $ext == 'bmp' || $ext == 'jpg' || $ext == 'png') {
						array_push($icons, $file);
					}
		        }
		    }
		    closedir($handle);
		}
		
		foreach($icons as $icon) {
			if ($icon == $value) $selected = "selected='selected'";
			else $selected = "";
			$html .= "<option alt='".$urlPath.$icon."' $selected>".$icon."</option>";
		}
		
		return $html;
	}
}