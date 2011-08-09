<?php
/**
 * RokNewsFlash Module
 *
 * @package RocketTheme
 * @subpackage roknewsflash
 * @version   1.4 August 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

modRokNewsflashHelper::loadScripts($params);

$theme = $params->get('theme', 'light');
$document =& JFactory::getDocument();
if ($params->get("load_css", "1") == "1")  $document->addStyleSheet(JURI::Root(true)."/modules/mod_roknewsflash/tmpl/themes/$theme/roknewsflash.css");

/* IE 6-7-8 stylesheets */
$iebrowser = modRokNewsflashHelper::getBrowser();

if ($iebrowser && $params->get("load_css", "1") == "1") {
	$style = JURI::Root(true)."/modules/mod_roknewsflash/tmpl/themes/$theme/roknewspager-ie$iebrowser";
	$check = dirname(__FILE__)."/tmpl/themes/$theme/roknewspager-ie$iebrowser";

	if (file_exists($check.".css")) $document->addStyleSheet($style.".css");
	elseif (file_exists($check.".php")) $document->addStyleSheet($style.".php");
}
/* End IE 6-7-8 stylesheets */

// Cache this basd on access level
$conf =& JFactory::getConfig();
if ($conf->getValue('config.caching') && $params->get("module_cache", 0)) { 
	$user =& JFactory::getUser();
	$aid  = (int) $user->get('aid', 0);
	switch ($aid) {
	    case 0:
	        $level = "public";
	        break;
	    case 1:
	        $level = "registered";
	        break;
	    case 2:
	        $level = "special";
	        break;
	}
	// Cache this based on access level
	$cache =& JFactory::getCache('mod_roknewsflash-' . $level);
	$list = $cache->call(array('modRokNewsflashHelper', 'getList'), $params);
}
else {
    $list = modRokNewsflashHelper::getList($params);
}
$counter = 0;
require(JModuleHelper::getLayoutPath('mod_roknewsflash'));