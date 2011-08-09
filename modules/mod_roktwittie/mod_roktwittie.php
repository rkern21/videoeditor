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

defined('_JEXEC') or die('Restricted access');
require_once dirname(__FILE__).DS.'helper.php';

$document =& JFactory::getDocument();
if ($params->get("load_css", "1") == "1")  $document->addStyleSheet(JURI::root(true) ."/modules/mod_roktwittie/css/roktwittie.css");

/* IE 6-7-8 stylesheets */
$iebrowser = modRokTwittieHelper::getBrowser();

if ($iebrowser && $params->get("load_css", "1") == "1") {
	$style = $document->baseurl."/modules/mod_roktwittie/css/roktwittie-ie$iebrowser";
	$check = dirname(__FILE__)."/css/roktwittie-ie$iebrowser";

	if (file_exists($check.".css")) $document->addStyleSheet($style.".css");
	elseif (file_exists($check.".php")) $document->addStyleSheet($style.".php");
}
/* End IE 6-7-8 stylesheets */

if ($params->get("enable_statuses", "1") == "1" || $params->get("enable_usernames", "1") == "1")
{
	$status = modRokTwittieHelper::request($params, $module, "status");
	$friends = modRokTwittieHelper::request($params, $module, "friends");

	if (is_array($status) && is_array($friends))
	{
		modRokTwittieHelper::loadScripts($params, $module);
		require(JModuleHelper::getLayoutPath('mod_roktwittie'));
	}
	else
		require(JModuleHelper::getLayoutPath('mod_roktwittie', 'error'));
}
else
{
	require(JModuleHelper::getLayoutPath('mod_roktwittie', 'error'));
}