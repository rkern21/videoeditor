<?php
/**
 * Sample Data Custom Module
 *
 * @package RocketTheme
 * @subpackage rokstories.elements
 * @version   1.9 September 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */


// no direct access
defined('_JEXEC') or die();
/**
 * @package RocketTheme
 * @subpackage rokstories.elements
 */
class JElementSampleData extends JElement {
	

	function fetchElement($name, $value, &$node, $control_name)
	{
		$output = '';
		$document 	=& JFactory::getDocument();
		
		if (defined('ROKSTORIES_ADMIN')) return;
		define('ROKSTORIES_ADMIN', 1);
		
		if (!ROKMODULE_CHECK) return "You need RokModule in order to install Sample Data.";
		
		$module_id = JRequest::getVar('cid', null);
		if(is_array($module_id)) $module_id = $module_id[0];
		if (!$module_id) $module_id = JRequest::getVar('id', null);
		
		$document->addScript(JURI::Root(true)."/modules/mod_rokstories/admin/importData.js");
		$document->addStyleSheet(JURI::Root(true)."/modules/mod_rokstories/admin/rokstories-admin.css");
		$document->addScriptDeclaration("		window.RokStoriesAdminPath = '".JURI::Root(true)."/index.php?option=com_rokmodule&tmpl=component&type=raw&moduleid=$module_id';");
		
		$output .= "<div id='rokstories-admin-wrapper'>
						<div>
							<button>Import Sample Data</button>
						</div>
					</div>";
		
		return $output;
	}
	
}

?>