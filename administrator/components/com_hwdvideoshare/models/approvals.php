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

class hwdvids_BE_approvals
{
   /**
	* show waiting approvals
	*/
	function showapprovals()
	{
		global $option, $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"pending\""
							);
		$total = $db->loadResult();
		echo $db->getErrorMsg();

		$where = array(
		"a.approved = \"pending\"",
		);

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = "SELECT a.*"
				. "\nFROM #__hwdvidsvideos AS a"
				. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
				. "\nORDER BY a.video_id"
				;
		$db->SetQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();

		hwdvids_HTML::showapprovals($rows, $pageNav);
	}
   /**
	* approve (& publish) videos
	*/
	function approve($cid=null, $publish=1)
	{
		global $option;
  		$db =& JFactory::getDBO();
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();

		if (count( $cid ) < 1) {
			$action = $publish == 1 ? 'approve' : ($publish == -1 ? 'unapprove' : 'unpublishg');
			echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
			exit;
		}

		$total = count ( $cid );
		$cids = implode( ',', $cid );

		$db->setQuery( "UPDATE #__hwdvidsvideos"
						. "\nSET approved = 'yes', published = 1"
						. "\n WHERE id IN ( $cids )"
						. "\n AND ( checked_out = 0 OR ( checked_out = $my->id ) )"
						);
		if (!$db->query()) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (count($cid) > 1)
		{
			include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'events.php');
			for ($i=0, $n=count($cid); $i < $n; $i++)
			{
				$row = new hwdvids_video($db);
				$row->load( $cid[$i] );
				$params->title = $row->title;
				$params->id = $row->id;
				$params->category_id = $row->category_id;
				$params->type = $row->video_type;
				$params->user_id = $row->user_id;
				hwdvsEvent::onAfterVideoApproval($params);
			}
		}
		else
		{
			include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'events.php');
			$row = new hwdvids_video($db);
			$row->load( $cids );
			$params->title = $row->title;
			$params->id = $row->id;
			$params->category_id = $row->category_id;
			$params->type = $row->video_type;
			$params->user_id = $row->user_id;
			hwdvsEvent::onAfterVideoApproval($params);
		}

		$msg = $total ._HWDVIDS_ALERT_ADMIN_VIDAPP." ";
		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=approvals' );
	}
   /**
	* watch unapproved videos
	*/
	function watchvideo()
	{
		hwdvids_HTML::watchvideo();
	}
}
?>