<?php
/**
 * RokFeatureTable Module
 *
 * @package RocketTheme
 * @subpackage rokfeaturetable
 * @version   1.1 September 13, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

//modRokFeatureTableHelper::loadScripts($params);
JHTML::_('behavior.mootools');
$doc = &JFactory::getDocument();
$doc->addScript(JURI::Root(true).'/modules/mod_rokfeaturetable/tmpl/js/rokfeaturetable.js');
if ($params->get('builtin_css', 1)) $doc->addStyleSheet(JURI::Root(true).'/modules/mod_rokfeaturetable/tmpl/css/rokfeaturetable.css');

$document =& JFactory::getDocument();

// Cache this basd on access level
$conf =& JFactory::getConfig();
if ($conf->getValue('config.caching') && $params->get("module_cache", 0)) { 
	$user =& JFactory::getUser();
	$cache =& JFactory::getCache('mod_rokfeaturetable');
    $cache->setCaching(true);
	$args = array(&$params);
	$checksum = md5($args[0]->_raw);
	$data = $cache->get(array('modRokFeatureTableHelper', 'getData'), $args, 'mod_rokfeaturetable-'.$user->get('aid', 0).'-'.$checksum);
}
else {
    $data = modRokFeatureTableHelper::getData($params);
}
$counter = 0;
require(JModuleHelper::getLayoutPath('mod_rokfeaturetable'));