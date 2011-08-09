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

class hwdvids_BE_flagged
{
   /**
	* show flagged media
	*/
	function showflagged()
	{
		global $option, $limit, $limitstart;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$search 		= $app->getUserStateFromRequest( "search{$option}", 'search', '' );
		$search 		= $db->getEscaped( trim( strtolower( $search ) ) );

		$query = "SELECT a.*, u.*"
				. "\nFROM #__hwdvidsvideos AS u"
				. "\n LEFT JOIN #__hwdvidsflagged_videos AS a ON u.id = a.videoid"
				. "\nWHERE a.status = \"UNREAD\""
				. "\nORDER BY a.date"
							;
		$db->SetQuery( $query );
		$rowsfv = $db->loadObjectList();

		$query = "SELECT a.*, u.*"
				. "\nFROM #__hwdvidsgroups AS u"
				. "\n LEFT JOIN #__hwdvidsflagged_groups AS a ON u.id = a.groupid"
				. "\nWHERE a.status = \"UNREAD\""
				. "\nORDER BY a.date"
							;
		$db->SetQuery( $query );
		$rowsfg = $db->loadObjectList();

		hwdvids_HTML::showflagged($rowsfv, $rowsfg);
	}
   /**
	* delete flagged videos
	*/
	function deleteflaggedvid($cid=null)
	{
		global $task, $option;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$total = count ( $cid );
		$cids = implode( ',', $cid );

		//Get VideoID
		$query = 'SELECT videoid'
					. ' FROM #__hwdvidsflagged_videos'
					. ' WHERE videoid = '.$cids
					;
		$db->SetQuery( $query );
		$videoid = $db->loadObject();

		$db->SetQuery("DELETE FROM #__hwdvidsflagged_videos WHERE videoid = $videoid->videoid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 'deleted', published = 0, featured = 0 WHERE id = $videoid->videoid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("DELETE FROM #__hwdvidsfavorites WHERE videoid = $videoid->videoid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("DELETE FROM #__hwdvidsgroup_videos WHERE videoid = $videoid->videoid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("DELETE FROM #__hwdvidsrating WHERE videoid = $videoid->videoid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = $total ._HWDVIDS_ALERT_ADMIN_FLAGMDEL;
		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=reported' );
	}
   /**
	* delete flagged groups
	*/
	function deleteflaggedgroup($cid=null)
	{
		global $task, $option;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$total = count ( $cid );
		$cids = implode( ',', $cid );

		//Get CommentID
		$query = 'SELECT groupid'
					. ' FROM #__hwdvidsflagged_groups'
					. ' WHERE groupid = '.$cids
					;
		$db->SetQuery( $query );
		$groupid = $db->loadObject();

		$db->SetQuery("DELETE FROM #__hwdvidsflagged_groups WHERE groupid = $groupid->groupid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("DELETE FROM #__hwdvidsgroups WHERE id = $groupid->groupid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("DELETE FROM #__hwdvidsgroup_membership WHERE id = $groupid->groupid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("DELETE FROM #__hwdvidsgroup_videos WHERE id = $groupid->groupid");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = $total ._HWDVIDS_ALERT_ADMIN_FLAGMDEL;
		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=reported' );
	}
   /**
	* ignore flagged videos
	*/
	function readflaggedvid($cid=null)
	{
		global $task, $option;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$total = count ( $cid );
		$cids = implode( ',', $cid );

		$db->SetQuery("UPDATE #__hwdvidsflagged_videos SET status = 'READ' WHERE videoid IN ( $cids )");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = $total ._HWDVIDS_ALERT_ADMIN_FLAGMREAD;
		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=reported' );
	}
   /**
	* ignore flagged groups
	*/
	function readflaggedgroup($cid=null)
	{
		global $task, $option;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$total = count ( $cid );
		$cids = implode( ',', $cid );

		$db->SetQuery("UPDATE #__hwdvidsflagged_groups SET status = 'READ' WHERE groupid IN ( $cids )");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = $total ._HWDVIDS_ALERT_ADMIN_FLAGMREAD;
		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=reported' );
	}
   /**
	* watch flagged videos
	*/
	function watchflaggedvideo()
	{
		hwdvids_HTML::watchflaggedvideo();
	}
}
?>