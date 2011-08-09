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
 * Renders Grouped Parameters element
 *
 * @package gantry
 * @subpackage admin.elements
 */
class JElementGroupedParams extends GantryGroupElement
{
	var	$_name = 'GroupedParams';

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;

        $buffer = '';
		$class = ( $node->attributes('class') ? $node->attributes('class') : '' );
        $chain = $node->children();
		
		$buffer .= "<div class='wrapper wrapper-".$name." ".$class."'>";


		// Columns
		$leftOpen = "<div class='group-left'>";
		$rightOpen = "<div class='group-right'>";
		$noneOpen = "<div class='group-none'>";
		
		$divClose = "</div>";
		
        foreach ($chain as $item) {
            $type =  $item->attributes('type');
            $element = $this->_loadElementType($type);

			$position = ($item->attributes('position')) ? $item->attributes('position') : 'none';
			$showLabel = ($item->attributes('showlabel') == "no") ? false : true;
			$position .= "Open";
			$bufferItem = "";



            $itemName = $name."-".$item->attributes('name');
            $itemValue = $gantry->get($itemName);

            $bufferItem .= '<div class="group '.$itemName.' group-'.$type.'">';
            if ($showLabel) $bufferItem .= '<span class="group-label">'.JTEXT::_($item->attributes('label')).'</span>';
            $bufferItem .= $element->fetchElement($itemName,$itemValue,$item, $control_name);
            $bufferItem .= "</div>";
			
			$$position .= $bufferItem;

        }
		
		$buffer .= $leftOpen . $divClose . $rightOpen . $divClose . $noneOpen . $divClose;
		
		$buffer .= "</div>";

        return $buffer;
	}
}
