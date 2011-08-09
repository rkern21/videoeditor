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
 * Renders an animation element
 *
 * @package gantry
 * @subpackage admin.elements
 */
class JElementAnimation extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Animation';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

		$options    = array ();
        $choices    = array("linear",
                            "Quad.easeOut",
                            "Quad.easeIn",
                            "Quad.easeInOut",
                            "Cubic.easeOut",
                            "Cubic.easeIn",
                            "Cubic.easeInOut",
                            "Quart.easeOut",
                            "Quart.easeIn",
                            "Quart.easeInOut",
                            "Quint.easeOut",
                            "Quint.easeIn",
                            "Quint.easeInOut",
                            "Expo.easeOut",
                            "Expo.easeIn",
                            "Expo.easeInOut",
                            "Circ.easeOut",
                            "Circ.easeIn",
                            "Circ.easeInOut",
                            "Sine.easeOut",
                            "Sine.easeIn",
                            "Sine.easeInOut",
                            "Back.easeOut",
                            "Back.easeIn",
                            "Back.easeInOut",
                            "Bounce.easeOut",
                            "Bounce.easeIn",
                            "Bounce.easeInOut",
                            "Elastic.easeOut",
                            "Elastic.easeIn",
                            "Elastic.easeInOut");

		foreach ($choices as $option)
		{
			$options[] = JHTML::_('select.option', $option, $option);
		}

		include_once('selectbox.php');
		$selectbox = new JElementSelectBox;
		return $selectbox->fetchElement($name, $value, $node, $control_name, $options);
	}
}
