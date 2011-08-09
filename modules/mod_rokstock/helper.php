<?php
/**
 * RokStock Module
 *
 * @package RocketTheme
 * @subpackage rokstock
 * @version   0.7 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'modules'.DS.'mod_rokstock'.DS.'googlestock.class.php');

class modRokStockHelper
{
	
	function loadScripts(&$params, $url)
	{
		JHTML::_('behavior.mootools');
		
		$js_file = JURI::base() . 'modules/mod_rokstock/tmpl/js/rokstock'.modRokStockHelper::_getJSVersion().'.js';
		
		if (!defined('ROKSTOCK_JS')) {
			$save_cookie = ($params->get("store_cookie", "1") == "1") ? 1 : 0;
			$duration_cookie = $params->get("store_time", 30);
			$externals = ($params->get('externals', "1") == "1") ? 1 : 0;
			$show_main_chart = ($params->get("show_main_chart", "1") == "1") ? 1 : 0;
			$show_tooltips = ($params->get("show_tooltips", "1") == "1") ? 1 : 0;
			
			$document =& JFactory::getDocument();
			$document->addScript($js_file);
			$document->addScriptDeclaration("window.addEvent('domready', function() {
	new RokStock({
		detailURL: '{$url}',
		cookie: {$save_cookie},
		cookieDuration: {$duration_cookie},
		externalLinks: {$externals},
		mainChart: {$show_main_chart},
		toolTips: {$show_tooltips}
	});
});");
			define('ROKSTOCK_JS',1);
		}
	}
	
	function getStock($stocks,&$params) {
	 	$gstock = new googleStock(JPATH_CACHE);
		$output = $gstock->makeRequest($stocks);
//		var_dump($output);
				
		return $output;
	}
	
	function _getJSVersion() {
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
