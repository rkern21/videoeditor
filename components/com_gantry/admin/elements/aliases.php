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

class JElementAliases extends JElement
{
	var	$_name = 'Aliases';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;
		
		$intro = "<div class='alias-label'>".$name." &rarr; </div>";
		include_once('position.php');
		$selectbox = new JElementPosition;
		return $intro.$selectbox->fetchElement($name, $value, $node, $control_name);
	}
}
