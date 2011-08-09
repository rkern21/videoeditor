<?php
/**
* RokModule Check, Custom Param
*
* @package RocketTheme
* @subpackage rokstories.elements
* @version   1.1 September 13, 2010
* @author    RocketTheme http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/


// no direct access
defined('_JEXEC') or die();
/**
 * @package RocketTheme
 * @subpackage rokstories.elements
 */
class JElementTemplates extends JElement {
	

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $mainframe;
		
		$this->_templates = JPATH_ROOT . DS . 'modules' . DS . 'mod_rokfeaturetable' . DS . 'templates';
		$this->_jtemplate = $this->_getCurrentTemplatePath();
		$output = "";
		
		jimport('joomla.filesystem.file');
		if (JFolder::exists($this->_templates)) {
			$files = JFolder::files($this->_templates, "\.txt", true, true);
			
			if (JFolder::exists($this->_jtemplate)) {
				$jfiles = JFolder::files($this->_jtemplate, "\.txt", true, true);
				if (count($jfiles)) $this->merge($files, $jfiles);
			}
			
			if (count($files)) {
				$output = "<select id='templates'>\n";
				$output .= "<option value='_select_' class='disabled' selected='selected'>Select a Template</option>";
				foreach($files as $file) {
					$title = JFile::stripExt(JFile::getName($file));
					$title = str_replace("-", " ", str_replace("_", " ", $title));
					$title = ucwords($title);
					$output .= "<option value='".JFile::read($file)."'>".$title."</option>";
				}
				$output .= "</select>\n";
				
				$output .= "<span id='import-button' class='action-import'><span>import</span></span>\n";
			}
		} else {
			$output = "Templates folder was not found.";
		}
		
		return $output;
	}
	
	function merge(&$files, $jfiles) {
		$clean_files = $this->getCleanArray($files);
		$clean_jfiles = $this->getCleanArray($jfiles);

		foreach($clean_jfiles as $index => $jfile) {
			if (in_array($jfile, $clean_files)) $files[array_search($jfile, $clean_files)] = $jfiles[$index];
			else array_push($files, $jfiles[$index]);
		}
		
		sort($files);
		return $files;
	}
	
	function getCleanArray($array) {
		jimport('joomla.filesystem.file');
		$newArray = array();

		foreach($array as $value) array_push($newArray, JFile::stripExt(JFile::getName($value)));
		
		return $newArray;
	}
	
	function _getCurrentTemplate() {
        $db =& JFactory::getDBO();
		$query = 'SELECT template'
					. ' FROM #__templates_menu'
					. ' WHERE client_id = 0 AND menuid = 0'
					. ' ORDER BY menuid DESC';
		$db->setQuery($query, 0, 1);
		$template = $db->loadResult();
		
		return $template;
    }

	function _getCurrentTemplatePath() {
		$template = $this->_getCurrentTemplate();
		
		return JPATH_ROOT . DS . 'templates' . DS . $template . DS . 'admin' . DS . 'rft-templates';
	}
}