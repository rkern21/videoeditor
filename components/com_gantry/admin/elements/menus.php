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
class JElementMenus extends JElement
{
	var	$_name = 'Menus';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );


        $db =& JFactory::getDBO();

        require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_menus'.DS.'helpers'.DS.'helper.php' );
        $menuTypes 	= MenusHelper::getMenuTypes();
        if (null == $value || !isset($value) || !in_array($value, $menuTypes)){
            $value = $menuTypes[0];
        }

        foreach ($menuTypes as $menutype) {
            $options[] = JHTML::_('select.option', $menutype, $menutype);
        }
        array_unshift($options, JHTML::_('select.option', '', '- '.JText::_('Select Menu').' -'));


		include_once('selectbox.php');
		$selectbox = new JElementSelectBox;
		return $selectbox->fetchElement($name, $value, $node, $control_name, $options);
	}
}
