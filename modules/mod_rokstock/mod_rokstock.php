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

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::Root(true)."/modules/mod_rokstock/rokstock.css");

$passed_module_name = JRequest::getString('module_name');
if (isset($passed_module_name) && $module->title=="") $module->title = $passed_module_name;
$module_name = $module->title;

$stocklist = $params->get("default_stocks", ".DJI, .INX, .IXIC");
$default_cookie = ".DJI%2C+.INX%2C+.IXIC";
$cookie_name = "rokstock_tickers";

$show_main_chart = ($params->get("show_main_chart", "1") == "1") ? true : false;
$url = JURI::Root(true)."/index.php?option=com_rokmodule&amp;tmpl=component&amp;type=raw&amp;module=".$module_name;
$images = JURI::Root(true)."/modules/mod_rokstock/images/";
$output = "";
$output_type = "default";

$save_cookie = ($params->get("store_cookie", "1") == "1") ? true : false;
$duration_cookie = $params->get("store_time", 30);


if ($params->get("user_interaction", "1") == "1") modRokStockHelper::loadScripts($params, $url);

if ($save_cookie) {
	if (!isset($_COOKIE[$cookie_name])) setcookie($cookie_name, urlencode($stocklist), time()+60*60*24*$duration_cookie);
	else if (isset($_COOKIE[$cookie_name])) $stocklist = urldecode(JRequest::getVar($cookie_name,$default_cookie,'COOKIE'));
}

if (isset($_GET['values'])) $stocklist = $_GET['values'];

if (isset($_GET['output_type']) && isset($_GET['chart_values']) && $_GET['output_type'] == 'chart') {
	$output_type = "chart";
	$chart_values = $_GET['chart_values'];
} else if (isset($_GET['output_type']) && isset($_GET['detail_values']) && $_GET['output_type'] == 'detail') {
	$output_type = "detail";
	$detail_values = $_GET['detail_values'];
} else if (isset($_GET['output_type']) && isset($_GET['details_value']) && $_GET['output_type'] == 'moredetails') {
	$output_type = "moredetails";
	$detail_values = $_GET['details_value'];
}

switch($output_type) {
	case "chart":
		require(JModuleHelper::getLayoutPath('mod_rokstock', 'chart'));
		break;
	case "detail":
		$tickers = modRokStockHelper::getStock($detail_values,$params);
		if (!$tickers) echo "false";
		else require(JModuleHelper::getLayoutPath('mod_rokstock', 'detail'));	
		break;
	case "moredetails":
		$tickers = modRokStockHelper::getStock($detail_values,$params);
		if (!$tickers) echo "false";
		else require(JModuleHelper::getLayoutPath('mod_rokstock', 'moredetails'));
		break;
	case "default": default: 
		$tickers = modRokStockHelper::getStock($stocklist,$params);
		require(JModuleHelper::getLayoutPath('mod_rokstock'));		
}
