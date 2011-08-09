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

/**
 * This class is the HTML generator for hwdVideoShare frontend
 *
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC2.13
 */
class hwd_vs_channels
{
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function channels()
	{
		global $mainframe, $limitstart, $hwdvs_joinc, $hwdvs_selectc, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		$limit 	= intval($c->gpp);

		$where = ' WHERE c.published = 1';
		if (!$my->id) {
		$where .= ' AND c.public_private = "public"';
		}

		$db->SetQuery( 'SELECT count(*)'
					 . ' FROM #__hwdvidschannels AS c'
					 . $where
					 );
  		$total = $db->loadResult();
		echo $db->getErrorMsg();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		//Groups that are published
		$query = 'SELECT'.$hwdvs_selectc
				. ' FROM #__hwdvidschannels AS c'
				. $hwdvs_joinc
				. $where
				. ' ORDER BY c.date_modified DESC'
				;

		$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();

		//Featured groups
		$query = 'SELECT'.$hwdvs_selectc
				. ' FROM #__hwdvidschannels AS c'
				. $hwdvs_joinc
				. $where
		        . ' AND c.featured = 1'
				. ' ORDER BY c.date_modified DESC'
				. ' LIMIT 0, 3'
				;

		$db->SetQuery($query);
		$rowsfeatured = $db->loadObjectList();

		hwd_vs_html::channels($rows, $rowsfeatured, $pageNav, $total);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
	function createChannel()
	{
		global $mosConfig_live_site, $Itemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$app = & JFactory::getApplication();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		if (!$my->id)
		{
			$smartyvs->assign("showconnectionbox", 1);
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_LOG2ADDC, "exclamation.png", 0);
			return;
		}

		$db->SetQuery( 'SELECT count(*) FROM #__hwdvidschannels WHERE user_id = '.$my->id );
		$channel_exists = $db->loadResult();

		if ($channel_exists == 1)
		{
			$app->redirect(JURI::root() . "index.php?option=com_hwdvideoshare&Itemid=$Itemid&task=viewChannel&user_id=".$my->id);
		}

		hwd_vs_html::createChannel();
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
	function deletechannel()
	{
		global $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$userid = $my->id;
		$groupid	= JRequest::getInt( 'groupid', 0 );

		if (!$my->id) {
			$msg = _HWDVIDS_ALERT_LOG2REMG;
			mosRedirect( $mosConfig_live_site."/index.php?option=com_hwdvideoshare&task=groups&Itemid=".$Itemid, $msg );
		}

		$db->SetQuery("DELETE FROM #__hwdvidsgroups WHERE id = $groupid AND adminid = $my->id");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
		}

		$db->SetQuery("DELETE FROM #__hwdvidsgroup_membership WHERE groupid = $groupid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
		}

		$db->SetQuery("DELETE FROM #__hwdvidsgroup_videos WHERE groupid = $groupid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
		}

		$msg = _HWDVIDS_ALERT_GREMOVED;
		$mainframe->enqueueMessage($msg);
		$mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=groups&Itemid='.$Itemid );
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
	function viewChannel()
	{
		global $smartyvs, $mainframe, $mosConfig_live_site, $limitstart, $Itemid, $hwdvs_joinv, $hwdvs_selectv, $hwdvs_joing, $hwdvs_selectg;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		$user_id = JRequest::getInt( 'user_id', 0, 'request' );
		$sort = JRequest::getWord( 'sort', 'none', 'request' );

		$db->SetQuery( 'SELECT count(*) FROM #__hwdvidschannels WHERE user_id = '.$user_id );
  		$channel_exists = $db->loadResult();

		if ( $channel_exists == 0 && $user_id == $my->id && $sort == "none" )
		{
			$mainframe->redirect( JURI::root() . 'index.php?option=com_hwdvideoshare&task=createChannel&Itemid='.$Itemid );
		}

		if ( $channel_exists == 0)
		{
			$db->SetQuery( 'SELECT username FROM #__users WHERE id = '.$user_id );
			$username = $db->loadResult();

			if (!isset($username) || empty($username))
			{
				$smartyvs->assign("showconnectionbox", 0);
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_NOACCESS, "This channel does not exist", "exclamation.png", 0);
				return;
			}

			$smartyvs->assign("channelExists", null);
			$channel->user_id = $user_id;
		}
		else
		{
			$smartyvs->assign("channelExists", 1);

			$query = 'SELECT * FROM #__hwdvidschannels WHERE user_id = '.$user_id;
			$db->SetQuery($query);
			$channel = $db->loadObject();

			$channel->views++;
			$db->SetQuery("UPDATE #__hwdvidschannels SET views = $channel->views WHERE id = $channel->id");
			$db->Query();

			$query = 'SELECT registerDate, lastvisitDate FROM #__users WHERE id = '.$channel->user_id;
			$db->SetQuery($query);
			$channelUser = $db->loadObject();

			$channel->registerDate = $channelUser->registerDate;
			$channel->lastvisitDate = $channelUser->lastvisitDate;

			$query = 'SELECT count(*) FROM #__hwdvidssubs WHERE memberid = '.$channel->user_id;
			$db->SetQuery($query);
			$channel->subscribers = $db->loadResult();

			$query = 'SELECT count(*) FROM #__hwdvidsvideos WHERE user_id = '.$channel->user_id;
			$db->SetQuery($query);
			$channel->uploads = $db->loadResult();

			if ($c->cbavatar == "2" && $c->cbint !== "5")
			{
				$channel->thumbnail = hwd_vs_tools::generateAvatar($channel->user_id, null, 0);
			}
			else
			{
				if (file_exists(JPATH_SITE.DS."hwdvideos".DS."thumbs".DS.$channel->channel_thumbnail))
				{
					$channel->thumbnail = JURI::root()."hwdvideos/thumbs/".$channel->channel_thumbnail;
				}
				else
				{
					$channel->thumbnail = null;
				}
			}
		}

		jimport('joomla.html.pagination');
		switch ($sort)
		{
			case "uploads":
			case "none":

				$limit     = intval($c->vpp);

				$where = ' WHERE video.published = 1';
				$where .= ' AND video.approved = "yes"';
				$where .= ' AND video.user_id = '.$channel->user_id;

				$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos AS video $hwdvs_joinv $where" );
				$total = $db->loadResult();

				$pageNav = new JPagination( $total, $limitstart, $limit );

				$query = 'SELECT'.$hwdvs_selectv
						. ' FROM #__hwdvidsvideos AS video'
						. $hwdvs_joinv
						. ' LEFT JOIN #__hwdvidscategories AS `access` ON access.id = video.category_id'
						. $where
						. ' ORDER BY video.date_uploaded DESC'
						;
				$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
				$rows = $db->loadObjectList();

				$smartyvs->assign("title", "Uploads");
				$smartyvs->assign("select_uploads", "selected=\"selected\"");
				$type = "videos";

			break;
			case "favourites":

				$limit     = intval($c->vpp);

				$where = ' WHERE video.approved = "yes"';
				$where .= ' AND video.published = 1';
				$where .= ' AND f.userid = '.$channel->user_id;

				$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos AS video $hwdvs_joinv LEFT JOIN #__hwdvidsfavorites AS f ON video.id = f.videoid $where" );
				$total = $db->loadResult();

				$pageNav = new JPagination( $total, $limitstart, $limit );

				$query = 'SELECT'.$hwdvs_selectv
						. ' FROM #__hwdvidsvideos AS video'
						. $hwdvs_joinv
						. ' LEFT JOIN #__hwdvidsfavorites AS f ON video.id = f.videoid'
						. $where
						. ' ORDER BY video.date_uploaded DESC'
						;
				$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
				$rows = $db->loadObjectList();

				$smartyvs->assign("title", "Favourite Videos");
				$smartyvs->assign("select_favourites", "selected=\"selected\"");
				$type = "videos";

			break;
			case "viewed":

				$limit     = intval($c->vpp);

				$where = ' WHERE video.approved = "yes"';
				$where .= ' AND video.published = 1';
				$where .= ' AND f.userid = '.$channel->user_id;

				$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos AS video $hwdvs_joinv LEFT JOIN #__hwdvidslogs_views AS f ON video.id = f.videoid $where" );
				$total = $db->loadResult();

				$pageNav = new JPagination( $total, $limitstart, $limit );

				$query = 'SELECT'.$hwdvs_selectv
						. ' FROM #__hwdvidsvideos AS video'
						. $hwdvs_joinv
						. ' LEFT JOIN #__hwdvidslogs_views AS f ON video.id = f.videoid'
						. $where
						. ' ORDER BY f.date DESC'
						;
				$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
				$rows = $db->loadObjectList();

				$smartyvs->assign("title", "Recently Viewed");
				$smartyvs->assign("select_viewed", "selected=\"selected\"");
				$type = "videos";

			break;
			case "liked":

				$limit     = intval($c->vpp);

				$where = ' WHERE video.approved = "yes"';
				$where .= ' AND video.published = 1';
				$where .= ' AND f.userid = '.$channel->user_id;
				$where .= ' AND f.vote > 3';

				$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos AS video $hwdvs_joinv LEFT JOIN #__hwdvidslogs_votes AS f ON video.id = f.videoid $where" );
				$total = $db->loadResult();

				$pageNav = new JPagination( $total, $limitstart, $limit );

				$query = 'SELECT'.$hwdvs_selectv
						. ' FROM #__hwdvidsvideos AS video'
						. $hwdvs_joinv
						. ' LEFT JOIN #__hwdvidslogs_votes AS f ON video.id = f.videoid'
						. $where
						. ' ORDER BY f.date DESC'
						;
				$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
				$rows = $db->loadObjectList();

				$smartyvs->assign("title", "Recently Liked Videos");
				$smartyvs->assign("select_liked", "selected=\"selected\"");
				$type = "videos";

			break;
			case "disliked":

				$limit     = intval($c->vpp);

				$where = ' WHERE video.approved = "yes"';
				$where .= ' AND video.published = 1';
				$where .= ' AND f.userid = '.$channel->user_id;
				$where .= ' AND f.vote < 3';

				$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos AS video $hwdvs_joinv LEFT JOIN #__hwdvidslogs_votes AS f ON video.id = f.videoid $where" );
				$total = $db->loadResult();

				$pageNav = new JPagination( $total, $limitstart, $limit );

				$query = 'SELECT'.$hwdvs_selectv
						. ' FROM #__hwdvidsvideos AS video'
						. $hwdvs_joinv
						. ' LEFT JOIN #__hwdvidslogs_votes AS f ON video.id = f.videoid'
						. $where
						. ' ORDER BY f.date DESC'
						;
				$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
				$rows = $db->loadObjectList();

				$smartyvs->assign("title", "Recently Disliked Videos");
				$smartyvs->assign("select_disliked", "selected=\"selected\"");
				$type = "videos";

			break;
			case "groups":

				$limit     = intval($c->gpp);

				$where = ' WHERE g.adminid = '.$user_id;
				$where .= ' AND g.published = 1';

				$db->SetQuery( "SELECT count(*) FROM #__hwdvidsgroups AS g $where" );
				$total = $db->loadResult();

				$pageNav = new JPagination( $total, $limitstart, $limit );

				$query = 'SELECT'.$hwdvs_selectg
						. ' FROM #__hwdvidsgroups AS g'
						. $hwdvs_joing
						. $where
						. ' ORDER BY g.date DESC'
						;
				$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
				$rows = $db->loadObjectList();

				$smartyvs->assign("title", "Groups");
				$smartyvs->assign("select_groups", "selected=\"selected\"");
				$type = "groups";

			break;
			case "playlists":

				$limit     = intval($c->gpp);

				$where = ' WHERE pl.published = 1';
				$where.= ' AND pl.user_id = '.$user_id;

				$db->SetQuery( "SELECT count(*) FROM #__hwdvidsplaylists AS pl $where" );
				$total = $db->loadResult();

				$pageNav = new JPagination( $total, $limitstart, $limit );

				$query = 'SELECT * FROM #__hwdvidsplaylists AS pl'
						. $where
						. ' ORDER BY pl.date_created DESC'
						;
				$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
				$rows = $db->loadObjectList();

				$smartyvs->assign("title", "Playlists");
				$smartyvs->assign("select_playlists", "selected=\"selected\"");
				$type = "playlists";

			break;
			case "memberships":

				$limit     = intval($c->gpp);

				$where = ' WHERE m.approved = 1';
				$where.= ' AND m.memberid = '.$user_id;
				$where.= ' AND g.published = 1';

				$db->SetQuery( "SELECT count(*) FROM #__hwdvidsgroup_membership AS m LEFT JOIN #__hwdvidsgroups AS g ON m.groupid = g.id $where" );
				$total = $db->loadResult();

				$pageNav = new JPagination( $total, $limitstart, $limit );

				$query = 'SELECT'.$hwdvs_selectg
						. ' FROM #__hwdvidsgroup_membership AS m'
						. ' LEFT JOIN #__hwdvidsgroups AS g ON m.groupid = g.id'
						. $hwdvs_joing
						. $where
						. ' ORDER BY m.date DESC'
						;
				$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
				$rows = $db->loadObjectList();

				$smartyvs->assign("title", "Group Memberships");
				$smartyvs->assign("select_memberships", "selected=\"selected\"");
				$type = "groups";

			break;
			case "subscriptions":

				$smartyvs->assign("title", "Subscriptions");
				$smartyvs->assign("select_subscriptions", "selected=\"selected\"");
				$type = "subscriptions";

			break;
		}

        ////////////////////

		$where = ' WHERE video.approved = "yes"';
		$where .= ' AND video.published = 1';
		$where .= ' AND f.userid = '.$channel->user_id;

		$query = 'SELECT'.$hwdvs_selectv
               	. ' FROM #__hwdvidsvideos AS video'
				. $hwdvs_joinv
				. ' LEFT JOIN #__hwdvidsfavorites AS f ON video.id = f.videoid'
				. $where
				. ' ORDER BY video.date_uploaded DESC'
				. ' LIMIT 0, 5'
				;

		$db->SetQuery($query);
		$rows_favourites = $db->loadObjectList();

        ////////////////////

		$where = ' WHERE video.approved = "yes"';
		$where .= ' AND video.published = 1';
		$where .= ' AND f.userid = '.$channel->user_id;

		$query = 'SELECT'.$hwdvs_selectv
               	. ' FROM #__hwdvidsvideos AS video'
				. $hwdvs_joinv
				. ' LEFT JOIN #__hwdvidslogs_views AS f ON video.id = f.videoid'
				. $where
				. ' ORDER BY f.date DESC'
				. ' LIMIT 0, 5'
				;

		$db->SetQuery($query);
		$rows_recentlyviewed = $db->loadObjectList();

        ////////////////////

		hwd_vs_html::viewChannel($channel, $rows, $type, $pageNav, $total, $rows_favourites, $rows_recentlyviewed);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function saveChannel()
	{
		global $mainframe;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

   		hwd_vs_channels::bindNewChannel();
	}
    /**
     * Outputs frontpage HTML
     *
     * @param string $option  the joomla component name
     * @param array  $rows  array of video data
     * @param array  $rowsfeatured  array of featured video data
     * @param object $pageNav  page navigation object
     * @param int    $total  the total video count
     * @return       Nothing
     */
    function updateChannel()
	{
		global $Itemid, $mainframe;
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$c = hwd_vs_Config::get_instance();

		$id = JRequest::getInt( 'id', 0 );
		$row = new hwdvids_channel($db);
		$row->load( $id );

		if ($row->user_id != $my->id)
		{
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
			$mainframe->redirect( JRoute::_("index.php?option=com_hwdvideoshare&Itemid=$Itemid&task=channels") );
		}

		$channel_description  = Jrequest::getVar( 'channel_description', _HWDVS_UNKNOWN );

		$_POST['channel_description'] = $channel_description;

		$file_name_org   = $_FILES['thumbnail_file']['name'];
		$file_ext        = substr($file_name_org, strrpos($file_name_org, '.') + 1);

		$thumbnail = '';
		if ($_FILES['thumbnail_file']['tmp_name'] !== "")
		{
			$videocode = "ch-".$row->id;

			$base_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS;
			$upload_result = hwd_vs_tools::uploadFile("thumbnail_file", $videocode, $base_Dir, 2, "jpg,jpeg,png,gif", 1);

			if ($upload_result[0] == "0")
			{
				$msg = $upload_result[1];
				$mainframe->enqueueMessage($msg);
				$mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid.'&task=editvideo&video_id='.$row->id );
			}
			else
			{
				require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'thumbnail.inc.php');

				$thumb_path = JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS.$videocode.'.'.$file_ext;

				$twidth_s = round($c->con_thumb_n);
				$theight_s = round($c->con_thumb_n*$c->tar_fb);

				list($width, $height, $type, $attr) = @getimagesize($thumb_path);
				$ratio = $width/$height;

				//echo $thumb_path."<br />".$ratio."<br />".$width."<br />".$height."<br />".$c->tar_fb."<br />".$twidth_s."<br />".$theight_s;

				if ($ratio > 1)
				{
					$resized = new Thumbnail($thumb_path);
					$resized->resize($twidth_s,$twidth_s);
					$resized->cropFromCenter($twidth_s, $theight_s);
					$resized->save($thumb_path);
					$resized->destruct();
				}
				else
				{
					$resized = new Thumbnail($thumb_path);
					$resized->resize($twidth_s,1000);
					$resized->cropFromCenter($twidth_s, $theight_s);
					$resized->save($thumb_path);
					$resized->destruct();
				}
			}
			$_POST['channel_thumbnail'] = $videocode.'.'.$file_ext;
		}
		else
		{
			//echo "No thumbnail uploaded";
		}

		// bind it to the table
		if (!$row -> bind($_POST)) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// store it in the db
		if (!$row -> store()) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = "Channel saved";
		$mainframe->enqueueMessage($msg);
		$mainframe->redirect( JRoute::_("index.php?option=com_hwdvideoshare&Itemid=$Itemid&task=viewchannel&user_id=$my->id") );
	}
	/**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function bindNewChannel()
	{
		global $mainframe, $params, $Itemid, $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_live_site, $mosConfig_sitename;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$query = 'SELECT username FROM #__users WHERE id = '.$my->id;
		$db->SetQuery( $query );
		$username = $db->loadResult();

		$channel_name 		 = $username;
		$channel_description = Jrequest::getVar( 'channel_description', _HWDVIDS_UNKNOWN );
		$channel_thumbnail 	 = Jrequest::getVar( 'channel_thumbnail', _HWDVIDS_UNKNOWN );
		$public_private 	 = JRequest::getWord( 'public_private' );
		$date_created 		 = date('Y-m-d H:i:s');
		$date_modified 		 = date('Y-m-d H:i:s');
		$user_id			 = $my->id;
		$featured		     = 0;
		$published = 1;

		//$checkform = hwd_vs_tools::checkGroupFormComplete( $group_name, $public_private, $allow_comments, $group_description );
		//if (!$checkform) { return; }

		$row = new hwdvids_channel($db);

		$_POST['channel_name'] 		    = $channel_name;
		$_POST['channel_description'] 	= $channel_description;
		$_POST['channel_thumbnail'] 	= $channel_thumbnail;
		$_POST['public_private'] 	    = $public_private;
		$_POST['date_created'] 	        = $date_created;
		$_POST['date_modified']         = $date_modified;
		$_POST['user_id'] 			    = $user_id;
		$_POST['featured'] 			    = $featured;
		$_POST['published'] 		    = $published;

		// bind it to the table
		if (!$row -> bind($_POST)) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// store it in the db
		if (!$row -> store()) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = _HWDVIDS_ALERT_CSAVED;

		$mainframe->enqueueMessage($msg);
		$mainframe->redirect( JURI::root() . 'index.php?option=com_hwdvideoshare&task=viewchannel&Itemid='.$Itemid.'&user_id='.$my->id );
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function joingroup()
	{
		global $Itemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$url =  Jrequest::getVar( 'url', JURI::root() );

		if (!$my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_LOG2JOING);
			$mainframe->redirect( $url );
		}

		$memberid = $my->id;
		$groupid = JRequest::getInt( 'groupid', 0 );

		$date = date('Y-m-d H:i:s');
		$published = 1;

		$row = new hwdvids_groupmember($db);

		$_POST['memberid'] = $memberid;
		$_POST['groupid'] = $groupid;
		$_POST['date'] = $date;
		$_POST['approved'] = 1;

		// bind it to the table
		if (!$row -> bind($_POST)) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// store it in the db
		if (!$row -> store()) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// perform maintenance
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
		hwd_vs_recount::recountMembersInGroup($groupid);

		$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
		if ( file_exists($api_AUP))
		{
			require_once ($api_AUP);
			AlphaUserPointsHelper::newpoints( 'plgaup_joinVideoGroup_hwdvs' );
		}

		$mainframe->enqueueMessage(_HWDVIDS_ALERT_SUCJOIN);
		$mainframe->redirect( $url );
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function leavegroup()
	{
		global $Itemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$url =  Jrequest::getVar( 'url', JURI::root() );

		if (!$my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_LOG2LEAVEG);
			$mainframe->redirect( $url );
		}

		$memberid = $my->id;
		$groupid = JRequest::getInt( 'groupid', 0 );

		$where = ' WHERE memberid = '.$memberid;
		$where .= ' AND groupid = '.$groupid;

		$db->SetQuery( 'DELETE FROM #__hwdvidsgroup_membership'
							. $where
							);

		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		// perform maintenance
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
		hwd_vs_recount::recountMembersInGroup($groupid);

		$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
		if ( file_exists($api_AUP))
		{
			require_once ($api_AUP);
			AlphaUserPointsHelper::newpoints( 'plgaup_leaveVideoGroup_hwdvs' );
		}

		$mainframe->enqueueMessage(_HWDVIDS_ALERT_SUCLEAVE);
		$mainframe->redirect( $url );
  	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function editChannel()
	{
		global $mosConfig_live_site, $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$channel_id = JRequest::getInt( 'channel_id', 0 );

		$row = new hwdvids_channel($db);
		$row->load( $channel_id );

		if ($row->user_id !== $my->id)
		{
			$mainframe->enqueueMessage("You do not have permission to edit this channel");
			$mainframe->redirect( JRoute::_("index.php?option=com_hwdvideoshare&Itemid=$Itemid&task=channels") );
		}

		hwd_vs_html::editChannelInfo($row);
  	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function subscribeChannel()
	{
		global $Itemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$url =  Jrequest::getVar( 'url', JURI::root() );

		if (!$my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_LOG2JOING);
			$mainframe->redirect( $url );
		}

		$memberid = $my->id;
		$userid = JRequest::getInt( 'userid', 0 );

		$date = date('Y-m-d H:i:s');
		$published = 1;

		$row = new hwdvidssubs($db);

		$_POST['memberid'] = $memberid;
		$_POST['userid'] = $userid;
		$_POST['date'] = $date;

		// bind it to the table
		if (!$row -> bind($_POST)) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// store it in the db
		if (!$row -> store()) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// perform maintenance
		//require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
		//hwd_vs_recount::recountMembersInGroup($groupid);

		$mainframe->enqueueMessage(_HWDVIDS_ALERT_SUCJOIN);
		$mainframe->redirect( $url );
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function unsubscribeChannel()
	{
		global $Itemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$url =  Jrequest::getVar( 'url', JURI::root() );

		if (!$my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_LOG2LEAVEG);
			$mainframe->redirect( $url );
		}

		$memberid = $my->id;
		$userid = JRequest::getInt( 'userid', 0 );

		$where = ' WHERE memberid = '.$memberid;
		$where .= ' AND userid = '.$userid;

		$db->SetQuery( 'DELETE FROM #__hwdvidssubs'
							. $where
							);

		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		// perform maintenance
		//require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
		//hwd_vs_recount::recountMembersInGroup($groupid);

		$mainframe->enqueueMessage(_HWDVIDS_ALERT_SUCLEAVE);
		$mainframe->redirect( $url );
  	}
}
?>