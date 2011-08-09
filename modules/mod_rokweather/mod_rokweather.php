<?php
/**
 * @version   1.0 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

JHTML::_('behavior.mootools');

$defaultDegree = $params->get('default_degree', 0);

$doc =& JFactory::getDocument();
$doc->addStyleSheet(JURI::Root(true).'/modules/mod_rokweather/rokweather.css');	

if ($params->get('user_interaction', 1) == 1) {
	$doc->addScript(JURI::Root(true).'/modules/mod_rokweather/tmpl/js/rokweather'.modRokWeatherHelper::_getJSVersion().'.js');
	$doc->addScriptDeclaration("window.addEvent('domready', function() {new RokWeather({defaultDegree: {$defaultDegree}});});");
}

$module_name = $module_id = false;
if ($params->get('module_ident','name')=='name') {
    $passed_module_name = JRequest::getString('module');
    if (isset($passed_module_name) && $module->title=="") $module->title = $passed_module_name;
    $module_name = $module->title;
} else {
    $passed_module_id = JRequest::getString('moduleid');
    if (isset($passed_module_id) && $module->id=="") $module->id = $passed_module_id;
    $module_id = $module->id;
}

if (isset($_REQUEST['weather_location'])) {
    $weather_location = JRequest::getString("weather_location");
} elseif (isset($_COOKIE["rokweather_location"])) {
    $weather_location = JRequest::getString("rokweather_location", '', 'COOKIE', 'STRING');
} else {
    $weather_location = $params->get("default_location","New York,NY");
}


$moduleType = ($params->get('module_ident','name')=='name') ? "module=" . $module_name : "moduleid=" . $module_id;
$url = JRoute::_( "index.php?option=com_rokmodule&tmpl=component&type=raw&".$moduleType, true);


$icon_url = JURI::base()."modules/mod_rokweather";
$output = "";

$weather = modRokWeatherHelper::getWeather($weather_location,$icon_url,$params);

require(JModuleHelper::getLayoutPath('mod_rokweather'));