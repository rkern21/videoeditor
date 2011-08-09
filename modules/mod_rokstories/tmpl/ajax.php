<?php 
/**
 * RokStories Module
 *
 * @package RocketTheme
 * @subpackage rokstories.tmpl
 * @version   1.9 September 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

	// no direct access
	defined('_JEXEC') or die('Restricted access');
	
	$install_sql = dirname(__FILE__).DS."../install.rokstories.sql";
	$images_path = JURI::Root(true)."/modules/mod_rokstories/images/sample/";
	$mainframe = JFactory::getApplication();
	$dbprefix = $mainframe->getCfg('dbprefix');
	
	if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) die("You aren't allowed to import data from outside the Admin CP.");

	$user =& JFactory::getUser();
	if ($user->usertype != "Super Administrator" && $user->usertype != "Administrator") die("You must be logged into your site (front-end) as Administrator or Super-Administrator, in order to import data.");

	$db =& JFactory::getDBO();
	
	// Check for existance
	if (JRequest::getVar("duplicate") != 'true') {
		$query = "SELECT ".$dbprefix."sections.title, ".$dbprefix."categories.title FROM ".$dbprefix."sections, ".$dbprefix."categories WHERE ".$dbprefix."sections.title = 'RokStories' OR ".$dbprefix."categories.title = 'RokStories Samples' LIMIT 1";
		$db->setQuery($query);
		if (!$db->query()) {
			die($db->getErrorMsg());
		} else {
			die('please.confirm');
		}
	}
	
	// RokStories Section
	$query = "INSERT INTO `".$dbprefix."sections` (`title`, `name`, `alias`, `image`, `scope`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `ordering`, `access`, `count`, `params`) VALUES('RokStories', '', 'rokstories', '', 'content', 'left', '', 1, 0, '0000-00-00 00:00:00', 2, 0, 1, '');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	} else {
		$section_id = $db->insertid();
	}
	
	// RokStories Category
	$query = "INSERT INTO `".$dbprefix."categories` (`parent_id`, `title`, `name`, `alias`, `image`, `section`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `editor`, `ordering`, `access`, `count`, `params`) VALUES(0, 'RokStories Samples', '', 'rokstories-samples', '', '$section_id', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, '');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	} else {
		$category_id = $db->insertid();
	}
		
	// RokStories Content
	$query = "INSERT INTO `".$dbprefix."content` (`title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`) VALUES('Featured Article Headline', 'featured-article-headline', '', '<img src=\"".$images_path."/rokstories3.jpg\" alt=\"image\" />\r\n\r\n<p>Integer consequat iaculis sollicitudin. Donec faucibus urna mattis ipsum egestas ullamcorper. Nam semper lacinia blandit. Integer aliquet quam sit amet nibh posuere pharetra. Fusce fermentum, neque ut tincidunt suscipit, tortor mauris placerat augue, at ultricies tortor ante id est.</p>', '', 1, $section_id, 0, $category_id, '2009-06-11 06:09:41', 62, '', '2009-06-11 06:46:01', 62, 0, '0000-00-00 00:00:00', '2009-06-11 06:09:41', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_vote=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nlanguage=\nkeyref=\nreadmore=', 2, 0, 3, '', '', 0, 0, 'robots=\nauthor=');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	}
	
	$query = "INSERT INTO `".$dbprefix."content` (`title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`) VALUES('Another Featured Article', 'another-featured-article', '', '<img src=\"".$images_path."rokstories2.jpg\" alt=\"image\" />\r\n\r\n<p>Phasellus sit amet odio eros. Ut sagittis metus volutpat eros bibendum accumsan. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In scelerisque aliquam tincidunt. Duis quis dui ac augue hendrerit elementum. Phasellus risus mauris, volutpat eget molestie vel, rhoncus eu lorem. Morbi a nisi quam.</p>', '', 1, $section_id, 0, $category_id, '2009-06-11 06:11:38', 62, '', '2009-06-11 06:45:42', 62, 0, '0000-00-00 00:00:00', '2009-06-11 06:11:38', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_vote=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nlanguage=\nkeyref=\nreadmore=', 2, 0, 2, '', '', 0, 0, 'robots=\nauthor=');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	}
	
	$query = "INSERT INTO `".$dbprefix."content` (`title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`) VALUES('Important Featured Story', 'important-featured-story', '', '<img src=\"".$images_path."rokstories1.jpg\" alt=\"image\" />\r\n\r\n<p>Phasellus sit amet odio eros. Ut sagittis metus volutpat eros bibendum accumsan. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In scelerisque aliquam tincidunt. Duis quis dui ac augue hendrerit elementum. Phasellus risus mauris, volutpat eget molestie vel, rhoncus eu lorem. Morbi a nisi quam.</p>', '', 1, $section_id, 0, $category_id, '2009-06-11 06:12:23', 62, '', '2009-06-11 06:45:29', 62, 0, '0000-00-00 00:00:00', '2009-06-11 06:12:23', '0000-00-00 00:00:00', '', '', 'show_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_vote=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nlanguage=\nkeyref=\nreadmore=', 2, 0, 1, '', '', 0, 0, 'robots=\nauthor=');";
	$db->setQuery($query);
	if (!$db->query()) {
		die($db->getErrorMsg());
	}
	
	die('success.'.$section_id.'.'.$category_id);
?>