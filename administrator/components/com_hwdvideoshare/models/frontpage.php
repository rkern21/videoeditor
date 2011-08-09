<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    Originally Joomla/Mambo Community Builder : Plugin Handler
 *    @package Community Builder
 *    @copyright (C) Beat and JoomlaJoe, www.joomlapolis.com and various
 *    @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
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

class hwdvids_BE_frontpage
{
	function frontpage()
	{
		global $mainframe, $limitstart, $Itemid;
		$db =& JFactory::getDBO();

		$stats = array();

        $db->setQuery( "SELECT count(*) FROM #__hwdvidsvideos WHERE approved = \"pending\"" );
		$stats['approvals'] = $db->loadResult();

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos WHERE approved = \"queuedforconversion\" AND approved = \"queuedforthumbnail\"" );
		$stats['conversion'] = $db->loadResult();

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidsflagged_videos WHERE status = \"UNREAD\"" );
		$stats['reportedvideos'] = $db->loadResult();

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidsflagged_groups WHERE status = \"UNREAD\"" );
		$stats['reportedgroups'] = $db->loadResult();

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos" );
		$stats['totalvideos'] = $db->loadResult();

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidscategories" );
		$stats['totalcategories'] = $db->loadResult();

		$db->SetQuery( "SELECT count(*) FROM #__users" );
		$stats['totalusers'] = $db->loadResult();

		$db->SetQuery( "SELECT id FROM #__users ORDER BY registerDate DESC" );
		$stats['latestuser'] = hwd_vs_tools::generateBEUserFromID($db->loadResult());

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidsgroups" );
		$stats['totalgroups'] = $db->loadResult();

		$db->SetQuery( "SELECT id, group_name FROM #__hwdvidsgroups ORDER BY date DESC" );
		$latestgroup = $db->loadObject();
		if (!empty($latestgroup->id)) { $stats['latestgroup'] = "<a href=\"index.php?option=com_hwdvideoshare&task=editgrpA&hidemainmenu=1&cid=".$latestgroup->id."\">$latestgroup->group_name</a>"; } else { $stats['latestgroup']=null; }

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos WHERE date_uploaded >= DATE_SUB(NOW(),INTERVAL 1 DAY)" );
		$stats['totalvideostoday'] = $db->loadResult();

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidsvideos WHERE date_uploaded >= DATE_SUB(NOW(),INTERVAL 7 DAY)" );
		$stats['totalvideosweek'] = $db->loadResult();

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidslogs_views" );
		$views_30 = $db->loadResult();
		$db->SetQuery( "SELECT SUM(views) FROM #__hwdvidslogs_archive" );
		$views_all = $db->loadResult();
		$stats['totalviews'] = $views_30 + $views_all;

		$db->SetQuery( "SELECT count(*) FROM #__hwdvidslogs_favours" );
		$favours_30 = $db->loadResult();
		$db->SetQuery( "SELECT SUM(favours) FROM #__hwdvidslogs_archive" );
		$favours_all = $db->loadResult();
		$stats['totalfavours'] = $favours_30 + $favours_all;

		$db->SetQuery( "SELECT * FROM #__hwdvidsvideos ORDER BY updated_rating DESC, rating_number_votes DESC LIMIT 0, 10" );
		$mostpopular = $db->loadObjectList();
		$db->SetQuery( "SELECT * FROM #__hwdvidsvideos ORDER BY number_of_views DESC LIMIT 0, 10" );
		$mostviewed = $db->loadObjectList();
		$db->SetQuery( "SELECT * FROM #__hwdvidsvideos ORDER BY date_uploaded DESC LIMIT 0, 10" );
		$mostrecent = $db->loadObjectList();
		$db->SetQuery( "SELECT * FROM #__hwdvidsgroups ORDER BY date DESC LIMIT 0, 10" );
		$recentgroups = $db->loadObjectList();

		hwdvids_HTML::frontpage($stats, $mostpopular, $mostviewed, $mostrecent, $recentgroups);
	}
}
?>