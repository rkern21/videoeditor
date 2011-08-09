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

class JElementSignin extends JElement
{
	function fetchElement($name, $value, $node, $control_name)
	{		
		global $mainframe;
		
		if ($message = JRequest::getVar( 'message', 0, 'get', 'string' )) {
			$mainframe->enqueueMessage($message);
		}
		
		$image = JURI::Root(true)."/modules/mod_roktwittie/admin/images/oauth.png";
		
		$document =& JFactory::getDocument();
		$document->addScript(JURI::root(true) ."/modules/mod_roktwittie/admin/js/oauth".$this->_getJSVersion().".js");
		
		$cid = JRequest::getVar( 'cid', array( JRequest::getVar( 'id', 0, 'method', 'int' ) ), 'method', 'array' );
		$url = JURI::Root(true)."/modules/mod_roktwittie/api.php?task=redirect&cid=" . (int) $cid[0];
		
		return '<a id="'.$name.'-key" href="' . $url . '"><img src="' . $image . '" alt="Sign in with Twitter"/></a>';
	}
	
	private function _getJSVersion()
	{
		if (version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.6', '<')){
			if (JPluginHelper::isEnabled('system', 'mtupgrade')){
				return "-mt1.2";
			} else {
				return "";
			}
		} else {
			return "";
		}
	}
}