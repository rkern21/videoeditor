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
 * Renders a spacer element
 *
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementPreset extends JElement
{
	var	$_name = 'Preset';
	
	function fetchElement($name, $value, &$node, $control_name)
	{

		global $gantry;
		
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );
		$mode = $node->attributes('mode');
		if (!isset($mode)) $mode = 'dropdown';
		
		$options = array();
		if (!array_key_exists($name,$gantry->presets)) {
				return JText::_('Unable to find the preset information'); 
		}
		foreach ($gantry->presets[$name] as $preset_name => $preset_value)
		{
			$val	= $preset_name;
			$text	= $preset_value['name'];
			$options[] = JHTML::_('select.option', $val, JText::_($text));
		}
		
		if (!defined('GANTRY_PRESET')) {
			gantry_import('core.gantryjson');
			
			$this->template = end(explode(DS, $gantry->templatePath));
			gantry_addScript($gantry->gantryUrl.'/admin/widgets/preset/js/preset.js');
			gantry_addScript($gantry->gantryUrl.'/admin/widgets/preset/js/preset-saver.js');
			gantry_addInlineScript('var Presets = {};var PresetsKeys = {};');
			
			if (isset($gantry->customPresets[$name])) {
				gantry_addInlineScript('var CustomPresets = '.GantryJSON::encode($gantry->customPresets[$name]).';');
			}
			
			define('GANTRY_PRESET', 1);
		}

		$this->presets = $gantry->originalPresets[$name];
		gantry_addInlineScript($this->populatePresets($name));
		
		if ($mode == 'dropdown') {
			include_once('selectbox.php');
			gantry_addInlineScript("window.addEvent('domready', PresetDropdown.init.bind(PresetDropdown, '$name'));");
			$selectbox = new JElementSelectBox;
			$node->addAttribute('preset', true);
			return $selectbox->fetchElement($name, $value, $node, $control_name, $options);
		} else {
			gantry_addInlineScript("window.addEvent('domready', Scroller.init.bind(Scroller, '$name'));");
			return $this->scrollerLayout($name, $value, $node, $control_name);
		}
	}
	
	function populatePresets($name) {
		global $gantry;
		
		$output = "";
		$output2 = "";
		foreach($this->presets as $key => $presets) {
            $preset_name = $this->presets[$key]['name'];
			$output .= "'$preset_name': {";
			foreach($presets as $keyName => $preset) {
                if ($keyName != 'name'){
				    $output .= "'$keyName': '$preset', ";
                }
			}
			$output = substr($output, 0, -2);
			$output .= "}, ";
		}

		$output = substr($output, 0, -2);
		
		foreach($gantry->originalPresets[$name] as $key => $preset) {
			$output2 .= "'" . $key . "', ";
		}
		
		$output = 'Presets["'.$name.'"] = new Hash({'.$output.'});';
		$output2 = "PresetsKeys['".$name."'] = [" . substr($output2, 0, -2) . "];";
		
		return $output . $output2;
	}
	
	function scrollerLayout($name, $value, &$node, $control_name) {
		global $gantry;
		
		$realname = $name;
		$presets = $gantry->presets;
		$totCount = count($presets[$name]);
		$width = $totCount * 119 - 26;
		if ($width < 335) $width = 335;
		$html = "";
		$html .= "
		<div class='wrapper'>
			<div class='".$name."'>
				<div class='scroller'>
					<div class='inner'>
						<div class=\"wrapper\" style=\"width: ".$width."px;\">";
							
							$i = 1;
							foreach($presets[$name] as $key => $preset) {
                                $preset_name = $preset['name'];
								if ($i == 1) $class = " first";
								else if ($i == $totCount) $class = " last";
								else $class = "";
								
								$name = strtolower(str_replace(" ", "", $key));
								
								$html .= "<div class='preset$i block$class'>";
								$html .= "	<div style='background:url(../templates/".$this->template."/admin/presets/$name.png) no-repeat'></div>";
								$html .= "	<span>".$preset_name."</span>";
								if (isset($gantry->customPresets[$realname][$key])) {
									$html .= "<div id='keydelete-".$key."' class='delete-preset'><span>X</span></div>";
								}
								$html .= "</div>";

								$i++;
							}
							
		$html .= "
						</div>
					</div>
				</div>
				<div class='bar'><div class='bar-right'></div></div>
			</div>
			<div id='params".$realname."' type='hidden' class='im-a-preset' value='1' />
		</div>
		";

		return $html;
	}
}
