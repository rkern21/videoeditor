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
 * Renders a showmax element
 *
 * @package     gantry
 * @subpackage  admin.elements
 */

class JElementShowmax extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Showmax';

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;
		
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );
		
		$position = str_replace('-showmax', '', $name);
		$count = count($gantry->getPositions($position));
		
		$options = $choices = array();
        
		for($i = 1; $i <= $count; $i++){
			array_push($choices, $i);
		}
		
		if (!$count) $choices = array(1, 2, 3, 4, 5, 6);

		foreach ($choices as $option)
		{
			$options[] = JHTML::_('select.option', $option, $option);
		}

		include_once('selectbox.php');
		$selectbox = new JElementSelectBox;
		return $selectbox->fetchElement($name, $value, $node, $control_name, $options);
	}
}
