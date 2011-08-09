<?php
/**
* RokModule Check, Custom Param
*
* @package RocketTheme
* @subpackage rokstories.elements
* @version   1.9 September 1, 2010
* @author    RocketTheme http://www.rockettheme.com
* @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/


// no direct access
defined('_JEXEC') or die();
/**
 * @package RocketTheme
 * @subpackage rokstories.elements
 */
class JElementRokModuleCheck extends JElement {
	

	function fetchElement($name, $value, &$node, $control_name)
	{
		
		if (defined('ROKMODULE_CHECK')) return;

		$rokmodule = JPATH_SITE.DS.'components'.DS.'com_rokmodule'.DS.'rokmodule.php';
		$warning_style = "style='background: #FFF3A3;border: 1px solid #E7BD72;color: #B79000;display: block;padding: 8px 10px;'";
		$success_style = "style='background: #d2edc9;border: 1px solid #90e772;color: #2b7312;display: block;padding: 8px 10px;'";
		
		if (file_exists($rokmodule)) {
			define('ROKMODULE_CHECK', 1);
			return "<span $success_style>You successfully passed the RokModule check.</span>";
		}
		else {
			define('ROKMODULE_CHECK', 0);
			return "<span $warning_style>You failed the RokModule check. In order to properly use this module, it is necessary that you install the latest RokModule version. Please <a target='_blank' href='http://www.rockettheme.com/extensions-downloads/free/1012-rokmodule'>click here</a> to download it.</span>";
		}
	}
	
}

?>