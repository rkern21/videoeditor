<?php
/**
 * RokNewsPager Module
 * @package RocketTheme
 * @subpackage roknewspager
 * @version   1.7 August 26, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (JPath::clean(dirname(__FILE__).'/lib/helper.php'));

$theme = $params->get('theme', 'light');

$document =& JFactory::getDocument();
if ($params->get("load_css", "1") == "1")  $document->addStyleSheet(JURI::Root(true)."/modules/mod_roknewspager/themes/$theme/roknewspager.css");

/* IE 6-7-8 stylesheets */
$iebrowser = modRokNewsPagerHelper::getBrowser();

if ($iebrowser && $params->get("load_css", "1") == "1") {
	$style = JURI::Root(true)."/modules/mod_roknewspager/themes/$theme/roknewspager-ie$iebrowser";
	$check = JPath::clean(dirname(__FILE__)."/themes/$theme/roknewspager-ie$iebrowser");

	if (file_exists($check.".css")) $document->addStyleSheet($style.".css");
	elseif (file_exists($check.".php")) $document->addStyleSheet($style.".php");
}
/* End IE 6-7-8 stylesheets */

modRokNewsPagerHelper::loadScripts($params);

//no caching, ajax based
$list = modRokNewsPagerHelper::getList($params);

$show_accordion = $params->get('show_accordion', 0);

$count = modRokNewsPagerHelper::getRowCount($params);
$show_paging =  $params->get('show_paging', 1);
$perpage = $params->get('article_count', 5);
$offset = JRequest::getInt('offset',0);

$show_thumbnails = $params->get('show_thumbnails',1);
$thumbnail_link = $params->get('thumbnail_link',1);

$show_overlays = $params->get('show_overlays',1);
$overlay = $params->get('overlay',"");

$show_readmore = $params->get('show_readmore',1);
$readmore_text = $params->get('readmore_text',JText::_("Read More..."));

$show_ratings = $params->get('show_ratings',1);
$show_title = $params->get('show_title',1);

$show_preview_text = $params->get('show_preview_text',1);
$show_comment_count = $params->get('show_comment_count',0);
$show_author = $params->get('show_author',0);
$show_published_date = $params->get('show_published_date',0);

$pages = ceil($count/$perpage);
$curpage = intval(($offset/$perpage)+1);

if ($params->get('module_ident','name')=='name') {
    $passed_module_name = JRequest::getString('module');
    if (isset($passed_module_name) && $module->title=="") $module->title = $passed_module_name;
    $module_name = $module->title;
} else {
    $passed_module_id = JRequest::getString('moduleid');
    if (isset($passed_module_id) && $module->id=="") $module->id = $passed_module_id;
    $module_id = $module->id;
}

if ($show_accordion) require(JModuleHelper::getLayoutPath('mod_roknewspager', 'accordion'));
else require(JModuleHelper::getLayoutPath('mod_roknewspager'));