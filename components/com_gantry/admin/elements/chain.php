<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version        3.1.10 March 5, 2011
 * @author        RocketTheme http://www.rockettheme.com
 * @copyright     Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

gantry_import('core.config.gantrygroupelement');
/**
 * Renders chained element
 *
 * @package gantry
 * @subpackage admin.elements
 */
class JElementChain extends GantryGroupElement {
    var    $_name = 'Preset';

    function fetchElement($name, $value, &$node, $control_name) {
        global $gantry;

        $buffer = '';
        $class = ($node->attributes('class') ? 'class="' . $node->attributes('class') . '"' : 'class="inputbox"');
        $chain = $node->children();

        $buffer .= "<div class='wrapper'>";
        foreach ($chain as $item) {
            $type = $item->attributes('type');
            $element = $this->_loadElementType($type);

            $itemName = $name . "-" . $item->attributes('name');
            $itemValue = $gantry->get($itemName);

            $buffer .= '<div class="chain ' . $itemName . ' chain-' . $type . '">';
            $buffer .= '<span class="chain-label">' . JTEXT::_($item->attributes('label')) . '</span>';
            $buffer .= $element->fetchElement($itemName, $itemValue, $item, $control_name);
            $buffer .= "</div>";

        }
        $buffer .= "</div>";

        return $buffer;
    }



}


