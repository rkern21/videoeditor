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

class hwd_vs_usrfunc
{
   /**
    * List Groups Created by User
    */
    function yourGroups()
	{
		global $hwdvsItemid;
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();
		$app->redirect(JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=viewChannel&Itemid='.$hwdvsItemid.'&user_id='.$my->id.'&sort=groups');
	}
   /**
    * List Groups User Has Joined
    */
	function yourMemberships()
	{
		global $hwdvsItemid;
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();
		$app->redirect(JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=viewChannel&Itemid='.$hwdvsItemid.'&user_id='.$my->id.'&sort=memberships');
	}
   /**
    * List User Videos
    */
	function yourVideos()
	{
		global $hwdvsItemid;
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();
		$app->redirect(JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=viewChannel&Itemid='.$hwdvsItemid.'&user_id='.$my->id.'&sort=videos');
	}
   /**
    * List User Favourite Videos
    */
	function yourFavourites()
	{
		global $hwdvsItemid;
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();
		$app->redirect(JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=viewChannel&Itemid='.$hwdvsItemid.'&user_id='.$my->id.'&sort=favourites');
	}
   /**
    * List User Favourite Videos
    */
	function yourPlaylists()
	{
		global $hwdvsItemid;
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();
		$app->redirect(JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=viewChannel&Itemid='.$hwdvsItemid.'&user_id='.$my->id.'&sort=playlists');
	}
   /**
    * List User Favourite Videos
    */
	function yourChannel()
	{
		global $hwdvsItemid;
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();
		$app->redirect(JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=viewChannel&Itemid='.$hwdvsItemid.'&user_id='.$my->id);
	}
   /**
    * Edit video details
    */
    function editVideoInfo()
	{
		global $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();

		$video_id	= JRequest::getInt( 'video_id', 0 );

		$row = new hwdvids_video($db);
		$row->load( $video_id );

		$db->SetQuery("SELECT * FROM #__hwdvidsvideos WHERE id = $video_id");
		$db->loadObject($row);

		// check component access settings and deny those without privileges
		if ($c->access_method == 0) {
			if (!hwd_vs_access::allowAccess( $c->gtree_mdrt, $c->gtree_mdrt_child, hwd_vs_access::userGID( $my->id ))) {
				if ($my->id == $row->user_id) {
					if ($my->id == "0") {
						$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
						$app->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid );
					}
					if ($c->allowvidedit == "0") {
						$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
						$app->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid );
					}
					// continue
				} else {
					$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
					$app->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid );
				}
			}
		}

		hwd_vs_html::editVideoInfo($row);
	}

   /**
    * Save editted video details
    */
	function saveVideoInfo()
	{
		global $Itemid, $mainframe;
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$c = hwd_vs_Config::get_instance();
		$app = & JFactory::getApplication();

		$row = new hwdvids_video($db);

		$uid = JRequest::getInt( 'owner', 0, 'post' );
		$rowid = JRequest::getInt( 'id', 0, 'post' );
		$referrer = JRequest::getVar( 'referrer', JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid );

		// check component access settings and deny those without privileges
		if (!hwd_vs_access::allowAccess( $c->gtree_mdrt, $c->gtree_mdrt_child, hwd_vs_access::userGID( $my->id ))) {
			if ($my->id == $uid) {
				if ($my->id == "0") {
					$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
					$app->redirect( $referrer );
				}
				if ($c->allowvidedit == "0") {
					$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
					$app->redirect( $referrer );
				}
				// continue
			} else {
				$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
				$app->redirect( $referrer );
			}
		}

		$row->load( $rowid );
		$old_category = $row->category_id;

		$file_name_org   = $_FILES['thumbnail_file']['name'];
		$file_ext        = substr($file_name_org, strrpos($file_name_org, '.') + 1);

		$thumbnail = '';
		if ($_FILES['thumbnail_file']['tmp_name'] !== "") {

			if ($row->video_type == "local" || $row->video_type == "swf" || $row->video_type == "mp4")
			{
				$videocode = $row->video_id;
				$thumbnail = $file_ext;
			}
			else
			{
				$videocode = "tp-".$row->id;
				$thumbnail = "tp-".$row->id.".".$file_ext;
			}

			$base_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS;
			$upload_result = hwd_vs_tools::uploadFile("thumbnail_file", $videocode, $base_Dir, 2, "jpg,jpeg", 1);

			if ($upload_result[0] == "0")
			{
				$msg = $upload_result[1];
				$app->enqueueMessage($msg);
				$app->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid.'&task=editvideo&video_id='.$row->id );
			}
			else
			{
				require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'thumbnail.inc.php');

				$thumb_path_s = JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS.$videocode.'.'.$file_ext;
				$thumb_path_l = JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS.'l_'.$videocode.'.'.$file_ext;

				$twidth_s = round($c->con_thumb_n);
				$theight_s = round($c->con_thumb_n*$c->tar_fb);
				$twidth_l = round($c->con_thumb_l);
				$theight_l = round($c->con_thumb_l*$c->tar_fb);

				list($width, $height, $type, $attr) = @getimagesize($thumb_path_s);
				$ratio = $width/$height;

				//echo $thumb_path_s."<br />".$ratio."<br />".$width."<br />".$height."<br />".$c->tar_fb."<br />".$twidth_s."<br />".$theight_s;

				if ($ratio > 1)
				{
					$resized_l = new Thumbnail($thumb_path_s);
					$resized_l->resize($twidth_l,$twidth_l);
					$resized_l->cropFromCenter($twidth_l, $theight_l);
					$resized_l->save($thumb_path_l);
					$resized_l->destruct();

					$resized_s = new Thumbnail($thumb_path_s);
					$resized_s->resize($twidth_s,$twidth_s);
					$resized_s->cropFromCenter($twidth_s, $theight_s);
					$resized_s->save($thumb_path_s);
					$resized_s->destruct();
				}
				else
				{
					$resized_l = new Thumbnail($thumb_path_s);
					$resized_l->resize($twidth_l,2000);
					$resized_l->cropFromCenter($twidth_l, $theight_l);
					$resized_l->save($thumb_path_l);
					$resized_l->destruct();

					$resized_s = new Thumbnail($thumb_path_s);
					$resized_s->resize($twidth_s,1000);
					$resized_s->cropFromCenter($twidth_s, $theight_s);
					$resized_s->save($thumb_path_s);
					$resized_s->destruct();
				}
			}
		}
		else
		{
			//echo "No thumbnail uploaded";
		}

		$title = hwd_vs_tools::generatePostTitle();
		$description = hwd_vs_tools::generatePostDescription();
		$tags = hwd_vs_tools::generatePostTags();

		$password = Jrequest::getVar( 'hwdvspassword', '' );
		if (!empty($password))
		{
			$password = md5($password);
			$_POST['password'] 		= $password;
		}

		$_POST['id'] 				= $rowid;
		$_POST['title'] 			= $title;
		$_POST['description'] 		= $description;
		$_POST['category_id'] 		= JRequest::getInt( 'category_id', 0 );
		$_POST['tags'] 				= $tags;
		if (!empty($thumbnail))
		{
			$_POST['thumbnail'] 	= $thumbnail;
		}

		// bind it to the table
		if (!$row->bind($_POST))
		{
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
		}

		// Make sure the record is valid
   		if (!$row->check())
   		{
        	$this->setError($this->_db->getErrorMsg());
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
    	}

		// store it in the db
		if (!$row->store())
		{
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
		}

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
		hwd_vs_recount::recountVideosInCategory($row->category_id);
		hwd_vs_recount::recountVideosInCategory($old_category_id);

		$msg = _HWDVIDS_ALERT_VIDEDITSAVED;
		$app->enqueueMessage($msg);
		$app->redirect( $referrer );
	}

   /**
	* Delete videos
	*/
	function deleteVideo()
	{
		global $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();

		$videoid	= JRequest::getInt( 'videoid', 0, 'post' );
		$url    	= Jrequest::getVar( 'url', '' );

		$row = new hwdvids_video($db);
		$row->load( $videoid );

		// check component access settings and deny those without privileges
		if ($c->access_method == 0) {
			if (!hwd_vs_access::allowAccess( $c->gtree_mdrt, $c->gtree_mdrt_child, hwd_vs_access::userGID( $my->id ))) {
				if ($my->id == $row->user_id) {
					if ($my->id == "0") {
						$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
						if (!empty($url)) {
							$app->redirect( $url );
						} else {
							$app->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid );
						}
					}
					if ($c->allowvidedit == "0") {
						$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
						if (!empty($url)) {
							$app->redirect( $url );
						} else {
							$app->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid );
						}

					}
					// continue
				} else {
					$app->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
					if (!empty($url)) {
						$app->redirect( $url );
					} else {
						$app->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid );
					}
				}
			}
		}

		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 'deleted', published = 0, featured = 0 WHERE id = $videoid");
		$db->Query();

		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$app->enqueueMessage(_HWDVIDS_ALERT_ADMIN_USERVIDDEL);
		if (!empty($url)) {
			$app->redirect( $url );
		} else {
			$app->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&Itemid='.$Itemid );
		}
	}
   /**
	* Featured videos
	*/
	function featuredVideos()
	{
		global $mainframe, $limitstart, $Itemid, $hwdvs_joinv, $hwdvs_selectv;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$limit 	= intval($c->vpp);

		$where = ' WHERE video.approved = "yes"';
		$where .= ' AND video.published = 1';
		$where .= ' AND video.featured = 1';

		$db->SetQuery( 'SELECT count(*)'
					 . ' FROM #__hwdvidsvideos AS video'
					 . $where
					 );
  		$total = $db->loadResult();
		echo $db->getErrorMsg();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT'.$hwdvs_selectv
				. ' FROM #__hwdvidsvideos AS video'
				. $hwdvs_joinv
				. $where
				. ' ORDER BY video.date_uploaded DESC'
				;

		$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();

		hwd_vs_html::featuredVideos($rows, $pageNav, $total);
	}
   /**
	* Featured groups
	*/
	function featuredGroups()
	{
		global $mainframe, $limitstart, $Itemid, $hwdvs_joing, $hwdvs_selectg;
		$db = & JFactory::getDBO();
		$c = hwd_vs_Config::get_instance();

		$limit 	= intval($c->gpp);

		$where = ' WHERE g.published = 1';
		$where .= ' AND g.featured = 1';

		$db->SetQuery( 'SELECT count(*)'
					 . ' FROM #__hwdvidsgroups AS g'
					 . $where
					 );
  		$total = $db->loadResult();
		echo $db->getErrorMsg();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT'.$hwdvs_selectg
				. ' FROM #__hwdvidsgroups AS g'
				. $hwdvs_joing
				. $where
				. ' ORDER BY g.id DESC'
				;

		$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();

		hwd_vs_html::featuredGroups($rows, $pageNav, $total);
	}
   /**
	* Featured groups
	*/
	function setUserTemplate()
	{
		global $mainframe, $Itemid;
		$app = & JFactory::getApplication();

		$hwdvstemplate	= JRequest::getCmd( 'hwdvstemplate' );
		$url	= JRequest::getVar( 'url' );
		$url	= urldecode($url);
		$states = explode("----", $hwdvstemplate);

		$app->setUserState( "com_hwdvideoshare.template_folder", $states[0] );
		$app->setUserState( "com_hwdvideoshare.template_element", $states[1] );

		$app->setUserState( "com_hwdphotoshare.template_folder", "hwdps-template" );
		$app->setUserState( "com_hwdphotoshare.template_element", $states[1] );

		$app->enqueueMessage("Your gallery theme has been changed.");
		$app->redirect( $url );
	}
   /**
    * Edit video details
    */
    function publishVideo()
	{
		global $mainframe, $option, $Itemid;
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();

		$video_id	= JRequest::getInt( 'video_id', 0 );
		$publish	= JRequest::getInt( 'publish', 0 );

		// check component access settings and deny those without privileges
		if ($c->access_method == 0) {
			if (!hwd_vs_access::allowAccess( $c->gtree_mdrt, $c->gtree_mdrt_child, hwd_vs_access::userGID( $my->id ))) {
				if ($my->id == 0 || $video_id == 0) {
					return;
				}
			}
		}

		$db->setQuery( "UPDATE #__hwdvidsvideos"
						. "\nSET published =" . intval( $publish )
						. "\n WHERE id = $video_id"
						);
		if (!$db->query()) {
		echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
		}

		switch ( $publish ) {
			case 1:
				$msg = $total ._HWDVIDS_ALERT_ADMIN_VIDPUB." ";
				break;

			case 0:
			default:
				$msg = $total ._HWDVIDS_ALERT_ADMIN_VIDUNPUB." ";
				break;
		}

		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/index.php?option='.$option.'&task=frontpage' );
	}
}
?>