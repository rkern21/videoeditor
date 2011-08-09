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
class JElementLayout extends JElement {
	

	function fetchElement($name, $value, &$node, $control_name)
	{

		if (!defined("ROKFEATURETABLE")) $this->loadAssets();

		$this->data = $this->getData($node);
		$this->actions = array("class" => "classname", "sub" => "subline text", "link" => 'cell link', "style" => "");
		
		$render = "";
		$render .= "<div class='rft-wrapper'>\n";
		$render .= "	<div class='status'>\n";
		$render .= "		Current Table Layout: <span id='no_of_rows'>".$node->attributes('rows')."</span> x <span id='no_of_columns'>".$node->attributes('columns')."</span>\n";
		$render .= "	</div>\n";
		$render .= "	<div id='rft-settings' class='settings'></div>\n";
		$render .= "</div>\n";
		
		$render .= "<div id='rft-layout'>\n";
		
		// tabs
		$render .= "	<div id='tabs'>\n";
	for ($i = 0; $i < $this->counter; $i++)
		$render .= "		<div class='tab tab-".($i+1)."".(!$i ? ' active' : '')."'><div class='inner-tab'><span class='column-title'>COL ".($i+1)."</span><span class='delete-tab'></span></div><span class='remove-column'>remove</span></div>\n";
		$render .= "		<div class='add-tab' ".($this->counter == 6 ? 'style=\'display: none;\'' : '')."><span> + </span></div>\n";
		$render .= "	</div>\n";

		// panels
		$render .= "	<div id='panels'>\n";
	for ($i = 0; $i < $this->counter; $i++) {
		$render .= "		<div class='panel panel-".($i+1)."".(!$i ? ' active': '')."'>\n";
		// rows
		$data = $this->data[$i]['parsed'];
		for ($j = 0; $j < count($data); $j++) {
			$render .= $this->createRow($data, $j);
		}
		
		if (!count($data)) $render .= $this->createRow($data, 0);
		
		$render .= "		</div>\n";
	}
		$render .= "	</div>\n";
		$render .= "</div>";

		// real fields for storing data
		$render .= "<div id='rft-data'>\n";
		foreach($this->data as $data) {
			$render .= "	<textarea id='params".$data['name']."' name='params[".$data['name']."]' class='text_area' rows='10' cols='50'>".$data['value']."</textarea>\n";
		}
		$render .= "</div>\n";
		
		return $render;
		
	}
	
	function loadAssets() {
		define("ROKFEATURETABLE", 1);
		$doc =& JFactory::getDocument();
		$moduleAssets = JURI::Root(true) . "/modules/mod_rokfeaturetable/admin";
		
		$doc->addStyleSheet($moduleAssets . "/css/rokfeaturetable.css");
		$doc->addScript($moduleAssets . "/js/rokfeaturetable".$this->_getJSVersion().".js");
	}
	
	function getData($node) {
		$data = array();
		if (!isset($this->counter)) $this->counter = 0;
		
		foreach($node->children() as $child) {
			$name = $child->attributes('name');
			$value = $this->_parent->get($name);
			array_push($data, array('name' => $name, 'value' => $value, 'parsed' => $this->parse($value)));
			
			if (!empty($value)) $this->counter += 1;
		}
		
		if (!$this->counter) $this->counter = 1;
		
		return $data;
	}
	
	function createRow($data, $j) {			
			$key = $this->getCurrentKey($data, $j);
			$values = $this->getCurrent($data, $j);
			
			$value = $this->getValue($values);
			if ($value == $this->actions['class']) $value = 'Row ' . ($j+1);

			$render = "		<div class='row row-".($j+1)."'>\n";
			$render .= "			<span class='row-title'>ROW ".($j+1)."</span>\n";
			$render .= "			<div class='input-wrapper'>\n";
			$render .= "				<input type='text' value='".$value."' />\n";
		// actions
		foreach($this->actions as $action => $default) {
			if (isset($data[$key]))	$action_value = $this->getAction($action, $key, $data[$key]);
			if (empty($action_value) || !isset($data[$key])) $action_value = $default;
			if ($action_value == $default && $key == 'class') $action_value = 'row-1';
			if (isset($data[$key]['classes']) && $action == 'class') $action_value .= " ".$data[$key]['classes'];
			
			$render .= "				<div class='action-input-wrapper action-".$action."'><input type='text' name='".$action."' class='action-input' rel='".$default."' value='".$action_value."' /></div>\n";
		}
			$render .= "			</div>\n";
			$render .= "			<span class='row-action add'><span>+</span></span>\n";
			$render .= "			<span class='row-action remove'><span>-</span></span>\n";
			$render .= "			<span class='row-action action-button class'><span>class</span></span>\n";
			$render .= "			<span class='row-action action-button sub'><span>subline</span></span>\n";
			$render .= "			<span class='row-action action-button link'><span>link</span></span>\n";
			$render .= "			<span class='row-action action-button style'><span>style</span></span>\n";
			$render .= "		</div>\n";
			
			return $render;
	}
	
	function parse($value) {
		$rows = array();
		$previous = '';
		$lines = explode("\n", $value);

		foreach($lines as $line) {
			$cell = explode("::", $line);
			$previous = (isset($prefix)) ? $prefix : false;
			$previous = str_replace('-sub', '', str_replace('-link', '', str_replace('-classes', '', $previous)));
			
			$prefix = $cell[0];
			$text = (!empty($cell[1]) ? $this->parseLine($cell[1]) : '');
			$value = (is_array($text) ? $text['value'] : $text);
			
			if (!empty($value)) {
				if (substr($prefix, -4) == '-sub') {
					$previous_cell = $rows[$previous];
					if (is_array($previous_cell)) $rows[$previous]['sub'] = $value;
					else if (empty($previous_cell)) $rows[$previous] = array('value' => '', 'sub' => $value);
					else $rows[$previous] = array('value' => $previous_cell, 'sub' => $value);
				}
				else if (substr($prefix, -5) == '-link') {
					$previous_cell = $rows[$previous];
					if (is_array($previous_cell)) $rows[$previous]['link'] = $value;
					else if (empty($previous_cell)) $rows[$previous] = array('value' => '', 'link' => $value);
					else $rows[$previous] = array('value' => $previous_cell, 'link' => $value);
				}
				else if (substr($prefix, -8) == '-classes') {
					$previous_cell = $rows[$previous];
					if (is_array($previous_cell)) $rows[$previous]['classes'] = $value;
					else if (empty($previous_cell)) $rows[$previous] = array('value' => '', 'classes' => $value);
					else $rows[$previous] = array('value' => $previous_cell, 'classes' => $value);
				}
				else {
					$rows[$prefix] = $value;
					if (is_array($text) && isset($text['style'])) {
						if (!is_array($rows[$prefix])) {
							$rows[$prefix] = array('value' => $rows[$prefix], 'style' => $text['style']);
						} else {
							$rows[$prefix]['style'] = $text['style'];
						}
					}
				}
			}
		}

		return $rows;
	}
	
	function parseLine($text) {
		if (strpos($text, "|") !== false) {
			$bits = explode("|", $text);
			$style = $bits[0];
			$value = $bits[1];
			
			$text = array('value' => $value, 'style' => $style);
		}
		
		return $text;
	}
	
	function getCurrent($data, $index) {
		if (!count($data)) $data = $this->actions;
		$keys = array_keys($data);
		return $data[$keys[$index]];
	}
	
	function getCurrentKey($data, $index) {
		if (!count($data)) $data = $this->actions;
		$keys = array_keys($data);
		return $keys[$index];
	}
	
	function getValue($value) {
		return (is_array($value)) ? $value['value'] : $value;
	}
	
	function getAction($action, $key, $data) {
		switch($action) { 
			case 'sub':
				if (is_array($data) && (array_key_exists('sub', $data))) return $data['sub'];
				else return '';
				break;
			case 'style': 
				if (is_array($data) && (array_key_exists('style', $data))) return $data['style'];
				else return '';
				break;
			case 'link':
				if (is_array($data) && (array_key_exists('link', $data))) return $data['link'];
				else return '';
				break;
			default:
				return $key;
		}
	}
	
	function isEmpty($index) {
		$key = $this->data[$index];
		
		if ($key && !empty($key['value'])) return false;
		return true;
	}
	
	function _getJSVersion() {
		if (version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.6', '<')){
			if (JPluginHelper::isEnabled('system', 'mtupgrade')){
				return "-mt1.2";
			} else {
				return "";
			}
		} else {
			return "";
		}
	}
}

?>