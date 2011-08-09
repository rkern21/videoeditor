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

class JElementPosition extends JElement
{
	var	$_name = 'Preset';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;
		
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

		$unique = $node->attributes('unique');
				
		if (isset($unique) && $unique == 'true') $positions = $gantry->getUniquePositions();
		else $positions = $gantry->getPositions();
		
		$options = array();
		foreach ($positions as  $position)
		{
			$val	= $position;
			$text	= $position;
			$options[] = JHTML::_('select.option', $val, $text);
		}
		
		
		include_once('selectbox.php');
		$selectbox = new JElementSelectBox;
		return $selectbox->fetchElement($name, $value, $node, $control_name, $options, false);
	}
}
