<?php
/**
 * @version		$Id: controller.php 11299 2008-11-22 01:40:44Z ian $
 * @package		Joomla
 * @subpackage	Users
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Users Component Controller
 *
 * @package		Joomla
 * @subpackage	Users
 * @since 1.5
 */
class UsersController extends JController
{
	function frontpage()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'frontpage.php');
		hwdvids_BE_frontpage::frontpage();
		return;
	}

	function videos()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::showvideos();
		return;
	}

	function editvidsA()
	{
		global $cid;
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::editvideos($cid);
		return;
	}

	function editvids()
	{
		global $cid;
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::editvideos($cid[0]);
		return;
	}

	function savevid()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::savevideo();
		return;
	}

	function apply()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::savevideo();
		return;
	}

	function cancelvid()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::cancelvideo();
		return;
	}

	function publish()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::publishvid($cid, 1);
		return;
	}

	function unpublish()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::publishvid($cid, 0);
		return;
	}

	function feature()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::featurevid($cid, 1);
		return;
	}

	function unfeature()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::featurevid($cid, 0);
		return;
	}

	function delete()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::deletevids($cid);
		return;
	}

	function orderVideoUp()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::orderAll($cid[0], -1);
		return;
	}

	function orderVideoDown()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::orderAll($cid[0], 1);
		return;
	}

	function saveVideoOrder()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::saveOrder();
		return;
	}

	function orderFeaturedVideoUp()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::orderFeatured($cid[0], -1);
		return;
	}

	function orderFeaturedVideoDown()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::orderFeatured($cid[0], 1);
		return;
	}

	function saveFeaturedVideoOrder()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::saveFeaturedOrder();
		return;
	}

	function changeuserselect()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::changeuserselect($cid);
		return;
	}

	function updatevideosource()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'videos.php');
		hwdvids_BE_videos::updateVideoSource();
		return;
	}

	function categories()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::showcategories();
		return;
	}

	function editcatA()
	{
		global $cid;
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::editcategories($cid);
		return;
	}

	function editcat()
	{
		global $cid;
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::editcategories($cid[0]);
		return;
	}

	function newcat()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::editcategories(0);
		return;
	}

	function savecat()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::savecategories();
		return;
	}

	function cancelcat()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::cancelcat();
		return;
	}

	function deletecat()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::deletecategories($cid);
		return;
	}

	function publishcat()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::publishcategory($cid, 1);
		return;
	}

  	function unpublishcat()
  	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::publishcategory($cid, 0);
		return;
	}

	function orderCategoryUp()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::orderAll($cid[0], -1);
		return;
	}

	function orderCategoryDown()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::orderAll($cid[0], 1);
		return;
	}

	function saveCategoryOrder()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'categories.php');
		hwdvids_BE_cats::saveOrder();
		return;
	}

	function groups()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::showgroups();
		return;
	}

	function editgrpA()
	{
		global $cid;
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::editgroups($cid);
		return;
	}

	function editgrp()
	{
		global $cid;
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::editgroups($cid[0]);
		return;
	}

	function savegrp()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::savegroup();
		return;
	}

	function cancelgrp()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::cancelgrp();
		return;
	}

	function publishg()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::publishg($cid, 1);
		return;
	}

	function unpublishg()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::publishg($cid, 0);
		return;
	}

	function featureg()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::featureg($cid, 1);
		return;
	}

  	function unfeatureg()
  	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::featureg($cid, 0);
		return;
	}

	function deletegroups()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::deletegroups($cid);
		return;

	}

	function serversettings()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::showserversettings();
		return;
	}

	function saveserver()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::saveserver($ffmpegpath, $flvtool2path, $mencoderpath, $phppath);
		return;
	}

	function generalsettings()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::showgeneralsettings();
		return;
	}

	function layoutsettings()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::showlayoutsettings();
		return;
	}

	function homepagelayout()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::showhomepagelayout();
		return;
	}

	function playerlayout()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::showplayerlayout();
		return;
	}

	function savegeneral()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::savegeneral();
		return;
	}

	function savelayout()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::saveLayout();
		return;
	}

	function approvals()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'approvals.php');
		hwdvids_BE_approvals::showapprovals();
		return;
	}

	function approve()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'approvals.php');
		hwdvids_BE_approvals::approve($cid, 1);
		return;
	}

	function watchvideo()
	{
		global $cid;
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'approvals.php');
		hwdvids_BE_approvals::watchvideo($cid, 1);
		return;
	}

	function reported()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'reported.php');
		hwdvids_BE_flagged::showflagged();
		return;
	}

	function deleteReportedVideo()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'reported.php');
		hwdvids_BE_flagged::deleteflaggedvid($cid );
		return;
	}

	function deleteReportedGroup()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'reported.php');
		hwdvids_BE_flagged::deleteflaggedgroup($cid );
		return;
	}

	function readReportedVideo()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'reported.php');
		hwdvids_BE_flagged::readflaggedvid($cid );
		return;
	}

	function readReportedGroup()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'reported.php');
		hwdvids_BE_flagged::readflaggedgroup($cid );
		return;
	}

	function converter()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::converter();
		return;
	}

	function startconverter()
	{
		global $cid;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::startconverter($cid);
		return;
	}

	function resetFailedConversions()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::resetfconv();
		return;
	}

	function ajaxReinsertMetaFLV()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::ajaxReinsertMetaFLV();
		return;
	}

	function ajaxRegenerateImage()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::ajaxRegenerateImage();
		return;
	}

	function ajaxRecalculateDuration()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::ajaxRecalculateDuration();
		return;
	}

	function ajaxMoveMoovAtom()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::ajaxMoveMoovAtom();
		return;
	}

	function ajaxReconvertFLV()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::ajaxReconvertFLV();
		return;
	}

	function ajaxReconvertMP4()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'converter.php');
		hwdvids_BE_converter::ajaxReconvertMP4();
		return;
	}

	function maintenance()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::maintenance();
		return;
	}

	function runmaintenance()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::runmaintenance();
		return;
	}

	function clearplaylistcache()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::clearPlaylistCache();
		return;
	}

	function cleartemplatecache()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::clearTemplateCache();
		return;
	}

	function ajax_archivelogs()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::ajax_ArchiveLogs();
		return;
	}

	function ajax_warphdsync()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::ajax_WarpHdSync();
		return;
	}

	function regeneratethumbnails()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::regenerateThumbnails();
		return;
	}

	function recalculatedurations()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::recalculateDurations();
		return;
	}

	function plugins()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'plugins.php');
		hwdvids_BE_plugins::plugins();
		return;
	}

	function import()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::import();
		return;
	}

	function ftpupload()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::ftpupload();
		return;
	}

	function remoteupload()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::remoteupload();
		return;
	}

	function rtmpupload()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::rtmpupload();
		return;
	}

	function sqlrestore()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::sqlRestore();
		return;
	}

	function csvimport()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::csvImport();
		return;
	}

	function seyretimport()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::seyretImport();
		return;
	}

	function jomsocialImport()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::jomsocialImport();
		return;
	}

	function seyretimportundo()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::seyretImportUndo();
		return;
	}

	function scandirectory()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::scanDirectory();
		return;
	}

	function importdirectory()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::importDirectory();
		return;
	}

	function thirdpartyimport()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::thirdPartyImport();
		return;
	}

	function redoListImport()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'imports.php');
		hwdvids_BE_imports::redoListImport();
		return;
	}

	function export()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'exports.php');
		hwdvids_BE_exports::backuptables();
		return;
	}

	function botJombackup()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'exports.php');
		hwdvids_BE_exports::botJombackup();
		return;
	}

	function insertVideo()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'plugins.php');
		hwdvids_BE_plugins::insertVideo();
		return;
	}

	function cancelThumbnailRegeneration()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::cancelThumbnailRegeneration();
		return;
	}

	function cancelDurationRecalculation()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'maintenance.php');
		hwdvids_BE_maintenance::cancelDurationRecalculation();
		return;

	}

	function cancel()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'frontpage.php');
		hwdvids_BE_frontpage::frontpage();
		return;
	}

	function homepage()
	{
		hwdvs_fileManagement::checkDirectoryStructure();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'frontpage.php');
		hwdvids_BE_frontpage::frontpage();
		return;
	}

	function restoreDefaults()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'settings.php');
		hwdvids_BE_settings::restoreDefaults();
		return;
	}

	function removeGroupMember()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::removeGroupMember();
		return;
	}

	function removeGroupVideo()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwdvids_BE_groups::removeGroupVideo();
		return;
	}
}

?>