<?php
/**
 * RokTwittie Module
 *
 * @package RocketTheme
 * @subpackage roktwittie
 * @version   2.0 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined('_JEXEC') or die();

require_once( JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'parameter'.DS.'element'.DS.'radio.php');

class JElementOauth extends JElementRadio
{
	function fetchElement($name, $value, $node, $control_name)
	{	
		$html = '<p>Enabling this requires registering your website as Twitter application, more about it <a href="http://www.rockettheme.com/extensions-joomla/roktwittie#registration" target="_blank">here</a>.</p>';
		
		$html .= parent::fetchElement($name, $value, $node, $control_name);
		
		return $html;
	}
}