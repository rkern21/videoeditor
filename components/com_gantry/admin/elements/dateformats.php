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
class JElementDateFormats extends JElement
{
	var	$_name = 'DateFormats';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );
		
		$options = array();
		$dates = $node->children();

	    $now = &JFactory::getDate();
		
		foreach ($dates as $option)
		{
			$val = $option->attributes('value');
			$option->_data = $now->toFormat($val);
			$options[] = JHTML::_('select.option', $val, $option->data());
		}

		include_once('selectbox.php');
		$selectbox = new JElementSelectBox;
		return $selectbox->fetchElement($name, $value, $node, $control_name, $options);
	}
}
