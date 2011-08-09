<?php
/**
 * RokStories Module
 *
 * @package RocketTheme
 * @subpackage rokstories
 * @version   1.9 September 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

if (modRokStoriesHelper::checkRequest()) require(JModuleHelper::getLayoutPath('mod_rokstories', 'ajax'));
else {
	$doc =& JFactory::getDocument();	
	
	if ($params->get("load_css", "1") == "1")  $doc->addStyleSheet(JURI::Root(true)."/modules/mod_rokstories/tmpl/css/rokstories.css");
	/* IE 6-7-8 stylesheets */
	$iebrowser = modRokStoriesHelper::getBrowser();

	if ($iebrowser && $params->get("load_css", "1") == "1") {
		$style = JURI::Root(true)."/modules/mod_rokstories/tmpl/css/rokstories-ie$iebrowser";
		$check = dirname(__FILE__)."/tmpl/css/rokstories-ie$iebrowser";

		if (file_exists($check.".css")) $doc->addStyleSheet($style.".css");
		elseif (file_exists($check.".php")) $doc->addStyleSheet($style.".php");
	}
	/* End IE 6-7-8 stylesheets */
	
	modRokStoriesHelper::loadScripts($module, $params);

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
		$cache =& JFactory::getCache('mod_rokstories-' . $level);
		$list = $cache->call(array('modRokStoriesHelper', 'getList'), $params);
	}
	else {
	    $list = modRokStoriesHelper::getList($params);
	}

	if ($params->get('layout_type') == 'layout3') require(JModuleHelper::getLayoutPath('mod_rokstories', 'layout3'));
	else if ($params->get('layout_type') == 'layout4') require(JModuleHelper::getLayoutPath('mod_rokstories', 'layout4'));
	else if ($params->get('layout_type') == 'layout5') require(JModuleHelper::getLayoutPath('mod_rokstories', 'layout5'));
	else if ($params->get('layout_type') == 'layout6') require(JModuleHelper::getLayoutPath('mod_rokstories', 'layout6'));
	else require(JModuleHelper::getLayoutPath('mod_rokstories'));
}