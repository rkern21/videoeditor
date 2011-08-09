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
gantry_import('core.config.gantrygroupelement');

/**
 * Renders chained element
 *
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementGroupedSelection extends GantryGroupElement
{
	var	$_name = 'Preset';

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;

        $buffer = '';
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

        $lis = "";
        $activeElement = "";
        $options = "";

        $groups = $node->children();

        foreach ($groups as $group) {
            // Set up the info for the select box
			$optionData = $group->attributes('label');
			$optionValue = $group->attributes('name');

			$selected = ($value == $optionValue) ? "selected='selected'" : "";
			$active = ($value == $optionValue) ? "class='active'" : "";
			if (strlen($active)) $activeElement = $optionData;
			$options .= "<option value='".$optionValue."' ".$selected.">".JText::_($optionData)."</option>\n";
			$lis .= "<li ".$active.">".JText::_($optionData)."</li>";

            $chain = $group->children();
			
			$buffer .= "<div class='group-$optionValue groupedsel' style='clear: both;margin: 10px -15px;'>";
			$buffer .= "	<table class='paramlist admintable' width='100%' cellspacing='1'>";
            foreach ($chain as $item) {
            	$type =  $item->attributes('type');
                $element = $this->_loadElementType($type);
                $itemName = $name."-".$optionValue."-".$item->attributes('name');
                $itemValue = $gantry->get($itemName);
				
				$innerTable = "";

				if ($type != 'hidden') {
					$p = 'params' . $item->attributes('name');
					$l = $p . '-lbl';
					$d = JText::_($item->attributes('label')) . ' :: ' . JText::_($item->attributes('description'));
					
					$innerTable .= "<tr>";
					$innerTable .= "	<td class='paramlist_key' width='40%'>";
					$innerTable .= "		<span class='editlinktip'><label id='$l' class='hasTip' title='$d'>";
					$innerTable .= 			JText::_($item->attributes('label'));
					$innerTable .= "		</label></span>";
					$innerTable .= 		"</td>";
					$innerTable .= "	<td class='paramlist_value'>";
					$innerTable .= "		{{-PARAM-}}";
					$innerTable .= "	</td>";
					$innerTable .= "</tr>";					
				}
				
				if ($type != 'hidden') {
					$element = $element->fetchElement($itemName,$itemValue,$item, $control_name);
					if (strlen($innerTable)) $innerTable = str_replace("{{-PARAM-}}", $element, $innerTable);
					else $innerTable = $element;
				}
				
                $buffer .= $innerTable;
            }
			$buffer .= "	</table>";
			$buffer .= "</div>";
			
        }

        
		if (!defined('GANTRY_SELECTBOX')) {
			$this->template = end(explode(DS, $gantry->templatePath));
			$gantry->addScript($gantry->gantryUrl.'/admin/widgets/selectbox/js/selectbox.js');

			define('GANTRY_SELECTBOX', 1);
		}
        // Output the select box
        $html  = "<div class='selectbox-wrapper'>";

		$html .= "	<div class='selectbox'>";

		$html .= "		<div class='selectbox-top'>";
		$html .= "			<div class='selected'><span>".JText::_($activeElement)."</span></div>";
		$html .= "			<div class='arrow'></div>";
		$html .= "		</div>";
		$html .= "		<div class='selectbox-dropdown'>";
		$html .= "			<ul>".JText::_($lis)."</ul>";
		$html .= "			<div class='selectbox-dropdown-bottom'><div class='selectbox-dropdown-bottomright'></div></div>";
		$html .= "		</div>";

		$html .= "	</div>";

		$html .= "	<select id='params$name' name='params[$name]' class='selectbox-real'>";
		$html .= 		$options;
		$html .= "	</select>";
		$html .= "</div>";
		$html .= "<div class='clr'></div>";

        $html .= "</div>".$buffer;


        return $html;
	}
}
