<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * hwdVideoShare Component Controller
 *
 * @package		hwdVideoShare
 */
class UserController extends JController
{
	function downloadfile()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'core.php');
		hwd_vs_core::downloadFile();
	}

	function deliverthumb()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'core.php');
		hwd_vs_core::deliverThumb();
	}

	function frontpage()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'core.php');
		hwd_vs_core::frontpage();
	}

	function upload()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'uploads.php');
		hwd_vs_uploads::uploadMedia();
	}

	function uploadconfirmperl()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'uploads.php');
		hwd_vs_uploads::uploadConfirmPerl();
	}

	function uploadconfirmflash()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'uploads.php');
		hwd_vs_uploads::uploadConfirmFlash();
	}

	function uploadconfirmphp()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'uploads.php');
		hwd_vs_uploads::uploadConfirmPhp();
	}

	function uploadconfirmwarp()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'uploads.php');
		hwd_vs_uploads::uploadConfirmWarp();
	}

	function addconfirm()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'uploads.php');
		hwd_vs_uploads::addConfirm();
	}

	function groups()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwd_vs_groups::groups();
	}

	function creategroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwd_vs_groups::createGroup();
	}

	function editgroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwd_vs_groups::editGroup();
	}

	function savegroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwd_vs_groups::saveGroup();
	}

	function updategroup() {
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
        hwd_vs_groups::updateGroup();
        return;
	}

	function deletegroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwd_vs_groups::deleteGroup();
	}

	function viewgroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwd_vs_groups::viewGroup();
	}

	function joingroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwd_vs_groups::joinGroup();
	}

	function leavegroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'groups.php');
		hwd_vs_groups::leaveGroup();
	}

	function yourvideos()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::yourVideos();
	}

	function yourfavourites()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::yourFavourites();
	}

	function yourgroups()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::yourGroups();
	}

	function yourmemberships()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::yourMemberships();
	}

	function yourplaylists()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::yourPlaylists();
	}

	function yourchannel()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::yourChannel();
	}

	function editvideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::editVideoInfo();
	}

	function savevideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::saveVideoInfo();
	}

	function deletevideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::deleteVideo();
	}

	function featuredvideos()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::featuredVideos();
	}

	function featuredgroups()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::featuredGroups();
	}

	function setusertemplate()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::setUserTemplate();
	}

	function publishvideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'usrfunc.php');
		hwd_vs_usrfunc::publishVideo();
	}

	function rate()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'standardsuite.php');
		hwd_vs_standard::rate();
	}

	function addfavourite()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'standardsuite.php');
		hwd_vs_standard::addFavourite();
	}

	function removefavourite()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'standardsuite.php');
		hwd_vs_standard::removeFavourite();
	}

	function addvideotogroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'standardsuite.php');
		hwd_vs_standard::addVideoToGroup();
	}

	function reportvideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'standardsuite.php');
		hwd_vs_standard::reportVideo();
	}

	function reportgroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'standardsuite.php');
		hwd_vs_standard::reportGroup();
	}

	function nextvideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'standardsuite.php');
		hwd_vs_standard::viewNextVideo();
	}

	function previousvideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'standardsuite.php');
		hwd_vs_standard::viewPrevVideo();
	}

	function ajax_rate()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'ajaxsuite.php');
		hwd_vs_ajax::rate();
	}

	function ajax_addtofavourites()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'ajaxsuite.php');
		hwd_vs_ajax::addToFavourites();
	}

	function ajax_removefromfavourites()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'ajaxsuite.php');
		hwd_vs_ajax::removeFromFavourites();
	}

	function ajax_reportvideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'ajaxsuite.php');
		hwd_vs_ajax::reportVideo();
	}

	function ajax_addvideotogroup()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'ajaxsuite.php');
		hwd_vs_ajax::addVideoToGroup();
	}

	function grabjomsocialplayer()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'ajaxsuite.php');
		hwd_vs_ajax::grabAjaxPlayer();
	}

	function grabajaxplayer()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'ajaxsuite.php');
		hwd_vs_ajax::grabAjaxPlayer();
	}

	function rss()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'rss.php');
		hwd_vs_rss::feeds();
	}

	function search()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'core.php');
		hwd_vs_core::search();
	}

	function displayresults()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'core.php');
		hwd_vs_core::displayResults();
	}

	function viewvideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'core.php');
		hwd_vs_core::viewVideo();
	}

	function categories()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'core.php');
		hwd_vs_core::categories();
	}

	function viewcategory()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'core.php');
		hwd_vs_core::viewCategory();
	}

	function gotocategory()
	{
		global $hwdvsItemid, $mainframe;
		$app = & JFactory::getApplication();

		$menu = &JMenu::getInstance('site');
		$mparams = &$menu->getParams($hwdvsItemid);
		$hwdmcid = $mparams->get( 'hwdmcid', '');
		if (!empty($hwdmcid))
		{
			$url = JRoute::_('index.php?option=com_hwdvideoshare&task=viewcategory&cat_id='.$hwdmcid.'&Itemid='.$hwdvsItemid);
			$url = str_replace("&amp;", "&", $url);
		}
		else
		{
			$url = JRoute::_('index.php?option=com_hwdvideoshare&task=viewcategory&cat_id='.$hwdmcid.'&Itemid='.$hwdvsItemid);
			$url = str_replace("&amp;", "&", $url);
		}
		$app->redirect( $url );
	}

	function insertVideo()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'plugins.php');
		hwdvids_BE_plugins::insertVideo();
		return;
	}




	function channels()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'channels.php');
		hwd_vs_channels::channels();
	}

	function viewChannel()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'channels.php');
		hwd_vs_channels::viewChannel();
	}

	function createChannel()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'channels.php');
		hwd_vs_channels::createChannel();
	}

	function editChannel()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'channels.php');
		hwd_vs_channels::editChannel();
	}

	function saveChannel()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'channels.php');
		hwd_vs_channels::saveChannel();
	}

	function updateChannel()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'channels.php');
		hwd_vs_channels::updateChannel();
	}


	function playlists()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::playlists();
	}

	function createPlaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::createPlaylist();
	}

	function savePlaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::savePlaylist();
	}

	function viewPlaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::viewPlaylist();
	}

	function editPlaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::editPlaylist();
	}

	function deletePlaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::deletePlaylist();
	}

	function updatePlaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::updatePlaylist();
	}

	function reorderplaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::reorderplaylist();
	}

	function subscribeChannel()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'channels.php');
		hwd_vs_channels::subscribeChannel();
	}

	function unsubscribeChannel()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'channels.php');
		hwd_vs_channels::unsubscribeChannel();
	}

	function ajax_addvideotoplaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'ajaxsuite.php');
		hwd_vs_ajax::addVideoToPlaylist();
	}

	function removeVideoFromPlaylist()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'playlists.php');
		hwd_vs_playlists::removeVideoFromPlaylist();
	}

	function pending()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'moderator.php');
		hwd_vs_moderator::pending();
	}

	function approvevideo()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'models'.DS.'moderator.php');
		hwd_vs_moderator::approvevideo();
	}
}
?>
