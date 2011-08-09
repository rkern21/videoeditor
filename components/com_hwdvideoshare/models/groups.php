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
class hwd_vs_groups
{
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function groups()
	{
		global $mainframe, $limitstart, $hwdvs_joing, $hwdvs_selectg, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

		$limit 	= intval($c->gpp);

		$where = ' WHERE g.published = 1';
		if (!$my->id) {
		$where .= ' AND g.public_private = "public"';
		}

		$db->SetQuery( 'SELECT count(*)'
					 . ' FROM #__hwdvidsgroups AS g'
					 . $where
					 );
  		$total = $db->loadResult();
		echo $db->getErrorMsg();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		//Groups that are published
		$query = 'SELECT'.$hwdvs_selectg
				. ' FROM #__hwdvidsgroups AS g'
				. $hwdvs_joing
				. $where
				. ' ORDER BY g.date DESC'
				;

		$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();

		//Featured groups
		$query = 'SELECT'.$hwdvs_selectg
				. ' FROM #__hwdvidsgroups AS g'
				. $hwdvs_joing
				. $where
		        . ' AND g.featured = 1'
				. ' ORDER BY g.date DESC'
				. ' LIMIT 0, '.$c->fpfeaturedgroups
				;

		$db->SetQuery($query);
		$rowsfeatured = $db->loadObjectList();

		hwd_vs_html::groups($rows, $rowsfeatured, $pageNav, $total);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
	function creategroup()
	{
		global $mosConfig_live_site, $Itemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

		if (!$my->id) {
			$smartyvs->assign("showconnectionbox", 1);
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_LOG2ADDG, "exclamation.png", 0);
			return;
		}

		hwd_vs_html::createGroup();
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
	function deletegroup()
	{
		global $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

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
	function viewGroup()
	{
		global $mainframe, $mosConfig_live_site, $limitstart, $Itemid, $hwdvs_joinv, $hwdvs_selectv;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

		$groupid = JRequest::getInt( 'group_id', 0, 'request' );

		$limit 	= intval($c->vpp);

		$whereG = ' WHERE published = 1';
		$whereG .= ' AND id = '.(int)$groupid;
		if (!$my->id) {
		$whereG .= ' AND public_private = "public"';
		}

		//Check group can be viewed
		$db->SetQuery( 'SELECT count(*)'
					 . ' FROM #__hwdvidsgroups'
					 . $whereG
					 );
  		$groupexists = $db->loadResult();
		echo $db->getErrorMsg();

		if ( $groupexists<1 ) {
			hwd_vs_tools::infomessage(3, 0,  _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_GROUPNOEXIST, "exclamation.png", 0);
			return;
		}

		$whereV = ' WHERE video.published = 1';
		$whereV .= ' AND video.approved = "yes"';
		$whereV .= ' AND l.groupid = '.$groupid;

		$db->SetQuery( 'SELECT count(*)'
					 . ' FROM #__hwdvidsvideos AS video'
					 . ' LEFT JOIN #__hwdvidsgroup_videos AS l ON l.videoid = video.id'
					 . $whereV
					 );
  		$total = $db->loadResult();
		echo $db->getErrorMsg();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		//Videos that are approved(converted) and published in this group
		$query = 'SELECT'.$hwdvs_selectv
				. ' FROM #__hwdvidsvideos AS video'
				. $hwdvs_joinv
				. ' LEFT JOIN #__hwdvidsgroup_videos AS l ON l.videoid = video.id'
				. $whereV
				. ' ORDER BY video.date_uploaded DESC'
				;

		$db->SetQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();

		//Videos that are approved(converted) and published in this group
		$query = 'SELECT m.*, u.name, u.username'
				. ' FROM #__hwdvidsgroup_membership AS m'
				. ' LEFT JOIN #__users AS u ON u.id = m.memberid'
		        . ' WHERE m.groupid = '.$groupid
		        . ' AND m.approved = 1'
				. ' ORDER BY date DESC'
				;

		$db->SetQuery($query);
		$members = $db->loadObjectList();
		//Group details
		$query = 'SELECT a.*, u.name, u.username'
				. ' FROM #__hwdvidsgroups AS a'
				. ' LEFT JOIN #__users AS u ON u.id = a.adminid'
				. ' WHERE a.id = '.$groupid
				. ' ORDER BY id DESC'
				;

		$db->SetQuery( $query );
    	$groupdetails = $db->loadObject();

		hwd_vs_html::viewGroup($rows, $pageNav, $total, $members, $groupdetails);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function savegroup()
	{
		global $mainframe, $params, $Itemid, $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_live_site, $mosConfig_sitename;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

		if ($c->disablecaptcha == "0") {
			$sessid = session_id();
			if (empty($sessid)) {
				session_start();
			}
			if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) {
				// Insert you code for processing the form here, e.g emailing the submission, entering it into a database.
   		    	hwd_vs_groups::bindNewGroup();
				unset($_SESSION['security_code']);
			} else {
				// Insert your code for showing an error message here
        		hwd_vs_tools::infomessage(3, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_ERRSC, "exclamation.png", 0);
				return;
			}

   		} else {
   		    hwd_vs_groups::bindNewGroup();
		}
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
    function updateGroup()
	{
		global $Itemid, $mainframe;
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$c = hwd_vs_Config::get_instance();

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

		$id = JRequest::getInt( 'id', 0 );
		$referrer = JRequest::getVar( 'referrer', JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=yourgroups&Itemid='.$Itemid );

		$row = new hwdvids_group($db);
		$row->load( $id );

		if ($row->adminid != $my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
			$mainframe->redirect( $referrer );
		}

		$group_name 		= Jrequest::getVar( 'group_name', _HWDPS_UNKNOWN );
		$group_description  = Jrequest::getVar( 'group_description', _HWDPS_UNKNOWN );
		$privacy        	= JRequest::getWord( 'privacy' );
		$allow_comments		= JRequest::getInt( 'allow_comments', 0, 'request' );

		$_POST['group_name'] 		= $group_name;
		$_POST['group_description'] = $group_description;
		$_POST['privacy'] 	        = $privacy;
		$_POST['allow_comments'] 	= $allow_comments;

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

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
		hwd_vs_recount::recountVideosInCategory($row->category_id);

		$msg = _HWDVIDS_ALERT_GSAVED;
		$mainframe->enqueueMessage($msg);
		$mainframe->redirect( $referrer );
	}
	/**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function bindNewGroup()
	{
		global $mainframe, $params, $Itemid, $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_live_site, $mosConfig_sitename;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

			$group_name 		= Jrequest::getVar( 'group_name', _HWDVIDS_UNKNOWN );
			$public_private 	= JRequest::getWord( 'public_private' );
			$date 				= date('Y-m-d H:i:s');
			$allow_comments		= JRequest::getInt( 'allow_comments', 0, 'request' );
			$require_approval   = JRequest::getInt( 'require_approval', 0, 'request' );
			$group_description  = Jrequest::getVar( 'group_description', _HWDVIDS_UNKNOWN );
			$featured			= 0;
			$adminid            = $my->id;
			if ($c->aag == 1) {
				$published = 1;
			} else {
				$published = 0;
			}

			$checkform = hwd_vs_tools::checkGroupFormComplete( $group_name, $public_private, $allow_comments, $group_description );
			if (!$checkform) { return; }

			$row = new hwdvids_group($db);

			$_POST['group_name'] 		= $group_name;
			$_POST['public_private'] 	= $public_private;
			$_POST['date'] 				= $date;
			$_POST['allow_comments'] 	= $allow_comments;
			$_POST['require_approval'] 	= $require_approval;
			$_POST['group_description'] = $group_description;
			$_POST['featured'] 			= $featured;
			$_POST['adminid'] 			= $adminid;
			$_POST['published'] 		= $published;

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

			// mail admin notification
			if ($c->mailgroupnotification == 1) {
				$jconfig = new jconfig();

				$mailbody = ""._HWDVIDS_MAIL_BODY3.$jconfig->sitename.".\n";
				$mailbody .= ""._HWDVIDS_MAIL_BODY4."\"".stripslashes($group_name)."\".\n";
				if (isset($row->id)) {
					$mailbody .= "".JURI::root()."index.php?option=com_hwdvideoshare&Itemid=".$Itemid."&task=viewgroup&group_id=".$row->id."\n\n";
				}
				$mailbody .= ""._HWDVIDS_MAIL_BODY5."\n";
				$mailbody .= JURI::root()."administrator";

				JUtility::sendMail( $jconfig->mailfrom, $jconfig->fromname, $c->mailnotifyaddress, _HWDVIDS_MAIL_SUBJECT2.$jconfig->sitename.' ', $mailbody );
			}

			// automatically add admin to group
			$autoa2g = @$_POST['add2group'];
			if (isset($autoa2g)) {
				if ($autoa2g == "1") {

					$date = date('Y-m-d H:i:s');
					$published = 1;

					$_POST['memberid'] = $my->id;
					$_POST['date'] = $date;
					$_POST['groupid'] = $row->id;
					$_POST['approved'] = 1;

					$row = new hwdvids_groupmember($db);

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
					$row->checkin();

					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
					hwd_vs_recount::recountMembersInGroup($row->groupid);
				}
			}

			$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
			if ( file_exists($api_AUP))
			{
				require_once ($api_AUP);
				AlphaUserPointsHelper::newpoints( 'plgaup_addVideoGroup_hwdvs' );
			}

			if ($c->aag == 1) {
				$msg = _HWDVIDS_ALERT_GSAVED;
			} else {
				$msg = _HWDVIDS_ALERT_GPENDING;
			}
			$mainframe->enqueueMessage($msg);
			$mainframe->redirect( JURI::root() . 'index.php?option=com_hwdvideoshare&task=groups&Itemid='.$Itemid );
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

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

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

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

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
    function editGroup()
	{
		global $mosConfig_live_site, $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		if (!hwd_vs_access::checkAccess($c->gtree_grup, $c->gtree_grup_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORGRUP, _HWDVIDS_ALERT_GRUP_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.group")) {return;}

		$groupid = JRequest::getInt( 'groupid', 0 );

		$row = new hwdvids_group($db);
		$row->load( $groupid );

		//check valid user
		if ($row->adminid != $my->id) {
			$mainframe->enqueueMessage(_HWDVIDS_ALERT_NOPERM);
			$mainframe->redirect( JURI::root() . 'index.php?option=com_hwdvideoshare&task=groups&Itemid='.$Itemid );
		}

		//Videos that are approved(converted) and published in this group
		$query = 'SELECT user.*, mem.memberid'
				. ' FROM #__hwdvidsgroup_membership AS `mem`'
				. ' LEFT JOIN #__hwdvidsgroups AS `group` ON mem.groupid = group.id'
				. ' LEFT JOIN #__users AS `user` ON user.id = mem.memberid'
				. ' WHERE mem.groupid = '.$row->id
				;
		$db->SetQuery($query);
		$grp_members = $db->loadObjectList();
		echo $db->getErrorMsg();

		hwd_vs_html::editGroupInfo($row, $grp_members);
  	}

}
?>