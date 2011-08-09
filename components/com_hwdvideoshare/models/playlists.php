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
class hwd_vs_playlists
{
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function playlists()
	{
		global $mainframe, $limitstart, $hwdvs_joing, $hwdvs_selectg, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		$limit 	= intval($c->gpp);

		$where = ' WHERE pl.published = 1';
		if (!$my->id) {
		$where .= ' AND pl.public_private = "public"';
		}

		$db->SetQuery( 'SELECT count(*)'
					 . ' FROM #__hwdvidsplaylists AS pl'
					 . $where
					 );
  		$total = $db->loadResult();
		echo $db->getErrorMsg();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		//Groups that are published
		$query = 'SELECT *'
				. ' FROM #__hwdvidsplaylists AS pl'
				. $where
				. ' ORDER BY pl.date_created DESC'
				;

		$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();

		//Featured groups
		$query = 'SELECT *'
				. ' FROM #__hwdvidsplaylists AS pl'
				. $where
		        . ' AND pl.featured = 1'
				. ' ORDER BY pl.date_created DESC'
				. ' LIMIT 0, '.$c->fpfeaturedgroups
				;

		$db->SetQuery($query);
		$rowsfeatured = $db->loadObjectList();

		hwd_vs_html::playlists($rows, $rowsfeatured, $pageNav, $total);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
	function createPlaylist()
	{
		global $mosConfig_live_site, $Itemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		if (!$my->id) {
			$smartyvs->assign("showconnectionbox", 1);
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_LOG2ADDC, "exclamation.png", 0);
			return;
		}

		hwd_vs_html::createPlaylist();
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
	function deletePlaylist()
	{
		global $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$userid = $my->id;
		$playlistid	= JRequest::getInt( 'playlistid', 0 );

		if (!$my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_LOG2REMG);
			$mainframe->redirect( JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$Itemid."&task=playlists") );
		}

		$db->SetQuery("DELETE FROM #__hwdvidsplaylists WHERE id = $playlistid AND user_id = $my->id");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
		}

		$msg = _HWDVIDS_ALERT_PLREMOVED;
		$mainframe->enqueueMessage($msg);
		$mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=playlists&Itemid='.$Itemid );
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function savePlaylist()
	{
		global $mainframe, $params, $Itemid, $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_live_site, $mosConfig_sitename;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		if ($c->disablecaptcha == "0") {
			$sessid = session_id();
			if (empty($sessid)) {
				session_start();
			}
			if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) {
				// Insert you code for processing the form here, e.g emailing the submission, entering it into a database.
   		    	hwd_vs_playlists::bindNewPlaylist();
				unset($_SESSION['security_code']);
			} else {
				// Insert your code for showing an error message here
        		hwd_vs_tools::infomessage(3, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_ERRSC, "exclamation.png", 0);
				return;
			}

   		} else {
   		    hwd_vs_playlists::bindNewPlaylist();
		}
	}
	/**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function bindNewPlaylist()
	{
		global $mainframe, $params, $Itemid, $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_live_site, $mosConfig_sitename;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

			$playlist_name        = Jrequest::getVar( 'playlist_name', _HWDVIDS_UNKNOWN );
			$playlist_description = Jrequest::getVar( 'playlist_description', _HWDVIDS_UNKNOWN );
			$public_private       = JRequest::getWord( 'public_private' );
			$date_created         = date('Y-m-d H:i:s');
			$date_modified        = date('Y-m-d H:i:s');
			$allow_comments       = JRequest::getInt( 'allow_comments', 0, 'request' );
			$user_id              = $my->id;
			$thumbnail            = '';
			$total_videos         = 0;
			$featured             = 0;
			if ($c->aag == 1) {
				$published        = 1;
			} else {
				$published        = 0;
			}
			$params               = null;

			//$checkform = hwd_vs_tools::checkPlaylistFormComplete( $group_name, $public_private, $allow_comments, $group_description );
			//if (!$checkform) { return; }

			$row = new hwdvids_playlist($db);

			$_POST['playlist_name'] 	   = $playlist_name;
			$_POST['playlist_description'] = $playlist_description;
			$_POST['public_private'] 	   = $public_private;
			$_POST['date_created'] 	       = $date_created;
			$_POST['date_modified'] 	   = $date_modified;
			$_POST['allow_comments'] 	   = $allow_comments;
			$_POST['user_id'] 	           = $user_id;
			$_POST['thumbnail'] 	       = $thumbnail;
			$_POST['total_videos'] 	       = $total_videos;
			$_POST['featured'] 			   = $featured;
			$_POST['published'] 	       = $published;
			$_POST['params'] 	           = $params;

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

			if ($c->aag == 1) {
				$msg = _HWDVIDS_ALERT_GSAVED;
			} else {
				$msg = _HWDVIDS_ALERT_GPENDING;
			}
			$mainframe->enqueueMessage($msg);
			$mainframe->redirect( JURI::root() . 'index.php?option=com_hwdvideoshare&task=playlists&Itemid='.$Itemid );
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function editPlaylist()
	{
		global $mosConfig_live_site, $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$playlistid = JRequest::getInt( 'playlistid', 0 );

		$row = new hwdvids_playlist($db);
		$row->load( $playlistid );

		//check valid user
		if ($row->user_id != $my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
			$mainframe->redirect( JURI::root() . 'index.php?option=com_hwdvideoshare&task=playlists&Itemid='.$Itemid );
		}

		if (empty($row->playlist_data))
		{
			$row->playlist_data = 0;
		}

		if (!empty($row->playlist_data))
		{
			$playlist = explode(",", $row->playlist_data);
			$playlist = preg_replace("/[^0-9]/", "", $playlist);

			$counter = 0;
			$pl_videos = array();
			for ($i=0, $n=count($playlist); $i < $n; $i++)
			{
				$db->SetQuery('SELECT * FROM #__hwdvidsvideos WHERE id = '.$playlist[$i]);
				$video = $db->loadObject();
				if (isset($video->id))
				{
					$pl_videos[$counter] = $video;
					$counter++;
				}
			}
		}
		else
		{
			$pl_videos = null;
		}

		hwd_vs_html::editPlaylist($row, $pl_videos);
  	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function updatePlaylist()
	{
		global $Itemid, $mainframe;
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$c = hwd_vs_Config::get_instance();

		$playlist_id = JRequest::getInt( 'playlist_id', 0 );
		$referrer = JRequest::getVar( 'referrer', JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=viewChannel&Itemid='.$Itemid.'&user_id='.$my->id.'&sort=playlists' );

		$row = new hwdvids_playlist($db);
		$row->load( $playlist_id );

		if ($row->user_id != $my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
			$mainframe->redirect( $referrer );
		}

		$playlist_name 		   = Jrequest::getVar( 'playlist_name', _HWDPS_UNKNOWN );
		$playlist_description  = Jrequest::getVar( 'playlist_description', _HWDPS_UNKNOWN );
		$public_private    	   = JRequest::getWord( 'public_private' );

		$_POST['id'] 		            = $playlist_id;
		$_POST['playlist_name'] 		= $playlist_name;
		$_POST['playlist_description']  = $playlist_description;
		$_POST['public_private'] 	    = $public_private;

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

		$msg = _HWDVIDS_ALERT_PLSAVED;
		$mainframe->enqueueMessage($msg);
		$mainframe->redirect( $referrer );
  	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function viewPlaylist()
	{
		global $mainframe;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		$playlist_id = JRequest::getInt( 'playlist_id', 0 );

		$row = new hwdvids_playlist($db);
		$row->load( $playlist_id );

		hwd_vs_html::viewPlaylist($row);
	}
	/**
	 * Save editted video details
	 */
	function reorderplaylist()
	{
	    global $Itemid, $mainframe;
	    $db =& JFactory::getDBO();
	    $my = & JFactory::getUser();

	    $playlist_id  = JRequest::getInt( 'playlist_id', 0 );
	    $orderdata = JRequest::getVar( 'orderdata' );
	    $neworder = explode("_", $orderdata);
		$updatedOrder = "";

	    for ($i=0, $n=count($neworder)-1; $i < $n; $i++)
	    {
	      $orderslot = explode("--", $neworder[$i]);
	      $order = intval(preg_replace("/[^0-9]/", "", $orderslot[0]));
	      $pid = intval(preg_replace("/[^0-9]/", "", $orderslot[1]));

		  $updatedOrder.= "$pid,";
	    }

		if (substr($updatedOrder, -1) == ",")
		{
			$updatedOrder = substr($updatedOrder, 0, strlen($updatedOrder)-1);
		}

	      // update ordering
	      $db->SetQuery("UPDATE #__hwdvidsplaylists SET playlist_data = \"$updatedOrder\" WHERE id = $playlist_id");
	      $db->Query();
	      if ( !$db->query() ) {
	        echo "<script language=\"javascript\" type=\"text/javascript\"> alert('".addslashes($db->getErrorMsg())."'); window.history.go(-1); </script>\n";
	        exit();
	      }

	    // perform maintenance
		//require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdphotoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
	   // hwd_ps_tools::setAlbumModifiedDate($album_id);
	   /// include_once(JPATH_SITE.DS.'components'.DS.'com_hwdphotoshare'.DS.'xml'.DS.'xmloutput.class.php');
	   // hwd_ps_xmlOutput::prepareSlideshowXML($album_id);

		$row = new hwdvids_playlist($db);
		$row->load( $playlist_id );
		if (!empty($row->playlist_data))
		{
			$playlist = explode(",", $row->playlist_data);
			$playlist = preg_replace("/[^0-9]/", "", $playlist);

			$counter = 0;
			$pl_videos = array();
			for ($i=0, $n=count($playlist); $i < $n; $i++)
			{
				$db->SetQuery('SELECT * FROM #__hwdvidsvideos WHERE id = '.$playlist[$i]);
				$video = $db->loadObject();
				if (isset($video->id))
				{
					$pl_videos[$counter] = $video;
					$counter++;
				}
			}
		}

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		hwdvsDrawFile::XMLDataFile($pl_videos, "pl_$playlist_id");
		hwdvsDrawFile::XMLPlaylistFile($pl_videos, "pl_$playlist_id");

	    $msg = _HWDPS_ALERT_AREORGANISED;
	    $mainframe->enqueueMessage($msg);
	    $mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=editPlaylist&playlistid='.$playlist_id.'&Itemid='.$Itemid );
    }
   /**
	* Save editted video details
	*/
	function removeVideoFromPlaylist()
	  {
	    global $Itemid, $mainframe;
	    $db =& JFactory::getDBO();
	    $my = & JFactory::getUser();

	    $playlist_id  = JRequest::getInt( 'playlist_id', 0 );
	    $video_id  = JRequest::getInt( 'video_id', 0 );

		$row = new hwdvids_playlist($db);
		$row->load( $playlist_id );

		if ( $row->user_id !== $my->id )
		{
			$msg = "You do not have permission to remove videos from this playlist";
			$mainframe->enqueueMessage($msg);
			$mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=editPlaylist&playlistid='.$playlist_id.'&Itemid='.$Itemid );
		}

		if (!empty($row->playlist_data))
		{
			$playlist = explode(",", $row->playlist_data);
			$playlist = preg_replace("/[^0-9]/", "", $playlist);

			$pl_videos = array();
			for ($i=0, $n=count($playlist); $i < $n; $i++)
			{
				if ($playlist[$i] !== $video_id)
				{
					$pl_videos[] = $playlist[$i];
				}
			}
		}

		$newData = implode(",", $pl_videos);

		$db->SetQuery("UPDATE #__hwdvidsplaylists SET playlist_data = \"$newData\" WHERE id = $playlist_id");
		if ( !$db->query() ) { echo "<script language=\"javascript\" type=\"text/javascript\"> alert('".addslashes($db->getErrorMsg())."'); window.history.go(-1); </script>\n"; exit(); }

		// perform maintenance
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
		hwd_vs_recount::recountVideosInPlaylist($playlist_id);

		$row = new hwdvids_playlist($db);
		$row->load( $playlist_id );
		if (!empty($row->playlist_data))
		{
			$playlist = explode(",", $row->playlist_data);
			$playlist = preg_replace("/[^0-9]/", "", $playlist);

			$counter = 0;
			$pl_videos = array();
			for ($i=0, $n=count($playlist); $i < $n; $i++)
			{
				$db->SetQuery('SELECT * FROM #__hwdvidsvideos WHERE id = '.$playlist[$i]);
				$video = $db->loadObject();
				if (isset($video->id))
				{
					$pl_videos[$counter] = $video;
					$counter++;
				}
			}
		}

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		hwdvsDrawFile::XMLDataFile($pl_videos, "pl_$playlist_id");
		hwdvsDrawFile::XMLPlaylistFile($pl_videos, "pl_$playlist_id");

	    $msg = _HWDPS_ALERT_AREORGANISED;
	    $mainframe->enqueueMessage($msg);
		$mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=editPlaylist&playlistid='.$playlist_id.'&Itemid='.$Itemid );
	}
}
?>