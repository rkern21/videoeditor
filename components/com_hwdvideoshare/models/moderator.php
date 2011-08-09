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

class hwd_vs_moderator
{
   /**
    * List Groups Created by User
    */
    function pending()
	{
		global $mainframe, $limitstart, $isModerator, $hwdvsItemid, $hwdvs_joinv, $hwdvs_selectv;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		if (!$isModerator)
		{
			$msg = "Only moderators can access this page";
			$mainframe->enqueueMessage($msg);
			$mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=frontpage&Itemid='.$hwdvsItemid );
		}

		$limit     = intval($c->vpp);

		$where = ' WHERE video.approved = "pending"';

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos AS video $hwdvs_joinv $where" );
		$total = $db->loadResult();

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

		hwd_vs_html::pending($rows, $pageNav, $total);
	}
   /**
    * List Groups Created by User
    */
    function approvevideo()
	{
		global $mainframe, $option, $isModerator;
  		$db =& JFactory::getDBO();
		$my = & JFactory::getUser();

		if (!$isModerator)
		{
			$msg = "Only moderators can access this page";
			$mainframe->enqueueMessage($msg);
			$mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=frontpage&Itemid='.$hwdvsItemid );
		}

		$video_id = JRequest::getInt( "videoid", "" );

		$db->setQuery("UPDATE #__hwdvidsvideos SET approved = 'yes', published = 1 WHERE id = $video_id");
		if (!$db->query()) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'events.php');
		$row = new hwdvids_video($db);
		$row->load( $cids );
		$params->title = $row->title;
		$params->id = $row->id;
		$params->category_id = $row->category_id;
		$params->type = $row->video_type;
		$params->user_id = $row->user_id;
		hwdvsEvent::onAfterVideoApproval($params);

		$msg = $total ._HWDVIDS_ALERT_ADMIN_VIDAPP." ";
		$mainframe->enqueueMessage($msg);
		$mainframe->redirect( JURI::root( true ) . '/index.php?option=com_hwdvideoshare&task=pending' );
	}
}
?>