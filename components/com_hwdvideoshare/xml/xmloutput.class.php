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
 * Description
 *
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
class HWDVS_xmlOutput
{
    /**
     *
     */
    function checkCacheThenWrite()
    {
		$c = hwd_vs_Config::get_instance();
  		$db =& JFactory::getDBO();
		$my = & JFactory::getUser();

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');

		$cpl = JRequest::getCmd( 'cpl', null );
		if ($cpl == 1)
		{
			if (!empty($c->xmlcustom01))
			{
				$playlist = explode(",", $c->xmlcustom01);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom01 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT * FROM #__hwdvidsvideos WHERE id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom01[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom01, 'custom01');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom01, 'custom01');
			}
			return;
		}
		else if ($cpl == 2)
		{
			if (!empty($c->xmlcustom02))
			{
				$playlist = explode(",", $c->xmlcustom02);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom02 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT * FROM #__hwdvidsvideos WHERE id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom02[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom02, 'custom02');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom02, 'custom02');
			}
			return;
		}
		else if ($cpl == 3)
		{
			if (!empty($c->xmlcustom03))
			{
				$playlist = explode(",", $c->xmlcustom03);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom03 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT * FROM #__hwdvidsvideos WHERE id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom03[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom03, 'custom03');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom03, 'custom03');
			}
			return;
		}
		else if ($cpl == 4)
		{
			if (!empty($c->xmlcustom04))
			{
				$playlist = explode(",", $c->xmlcustom04);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom04 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT * FROM #__hwdvidsvideos WHERE id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom04[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom04, 'custom04');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom04, 'custom04');
			}
			return;
		}
		else if ($cpl == 5)
		{
			if (!empty($c->xmlcustom05))
			{
				$playlist = explode(",", $c->xmlcustom05);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom05 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT * FROM #__hwdvidsvideos WHERE id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom05[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom05, 'custom05');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom05, 'custom05');
			}
			return;
		}

		// set cache variables
		$cachedir = JPATH_SITE.'/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)

		/**
		 * Generate Today's Playlists
		 */
		$cachetime = $c->xmlcache_today; // Seconds to cache files for
		$page = 'http://xmlplaylists_today'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;
		@clearstatcache();

		// Show file from cache if still valid
		if (time() - $cachetime < $cachefile_created)
		{
			echo "Already cached today's playlists... aborting<br />";
		}
		else
		{
			 // Now the script has run, generate a new cache file
			$fp = fopen($cachefile, 'w');

			// save the contents of output buffer to the file
			@fwrite($fp, ob_get_contents());
			@fclose($fp);

			$join     = ' LEFT JOIN #__users ON #__users.id = #__hwdvidsvideos.user_id';
			if ($c->userdisplay == 1)
			{
				$select   = '#__users.username,';
				$select_f = '#__users.username,';
			}
			else
			{
				$select   = '#__users.name,';
				$select_f = '#__users.name,';
			}
			if ($c->cbint == "2")
			{
				$join.= ' LEFT JOIN #__community_users ON #__community_users.userid = #__hwdvidsvideos.user_id';
				$select  .= ' #__community_users.*,';
				$select_f.= ' #__community_users.*';
			}
			else if ($c->cbint == "1")
			{
				$join.= ' LEFT JOIN #__comprofiler ON #__comprofiler.user_id = #__hwdvidsvideos.user_id';
				$select  .= ' #__comprofiler.avatar,';
				$select_f.= ' #__comprofiler.avatar';
			}
			else
			{
				if ($c->userdisplay == 1)
				{
					$select_f = '#__users.username';
				}
				else
				{
					$select_f = '#__users.name';
				}
			}

			// query SQL for today's data
			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' COUNT(#__hwdvidslogs_views.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_views ON #__hwdvidsvideos.id = #__hwdvidslogs_views.videoid '.$join.' WHERE #__hwdvidslogs_views.date >= DATE_SUB(NOW(),INTERVAL 1 DAY) AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY counts DESC LIMIT 0, 10');
			$rowsmostviewed_today = $db->loadObjectList();
			if (count($rowsmostviewed_today) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostviewed_today, 'mostviewed_today');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostviewed_today, 'mostviewed_today');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' COUNT(#__hwdvidslogs_favours.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_favours ON #__hwdvidsvideos.id = #__hwdvidslogs_favours.videoid '.$join.' WHERE #__hwdvidslogs_favours.date >= DATE_SUB(NOW(),INTERVAL 1 DAY) AND favour = 1 AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY counts DESC LIMIT 0, 10');
			$rowsmostfavoured_today = $db->loadObjectList();
			if (count($rowsmostfavoured_today) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostfavoured_today, 'mostfavoured_today');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostfavoured_today, 'mostfavoured_today');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' SUM(#__hwdvidslogs_votes.vote)/COUNT(#__hwdvidslogs_votes.videoid) AS sums, COUNT(#__hwdvidslogs_votes.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_votes ON #__hwdvidsvideos.id = #__hwdvidslogs_votes.videoid '.$join.' WHERE #__hwdvidslogs_votes.date >= DATE_SUB(NOW(),INTERVAL 1 DAY) AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY sums DESC LIMIT 0, 10');
			$rowsmostpopular_today = $db->loadObjectList();
			if (count($rowsmostpopular_today) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostpopular_today, 'mostpopular_today');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostpopular_today, 'mostpopular_today');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 AND #__hwdvidsvideos.featured = 1 ORDER BY #__hwdvidsvideos.ordering ASC LIMIT 0, 25');
			$rows_featured = $db->loadObjectList();
			hwdvsDrawFile::XMLDataFile($rows_featured, 'featured');
			hwdvsDrawFile::XMLPlaylistFile($rows_featured, 'featured');

			$db->SetQuery('SELECT DISTINCT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' LEFT JOIN #__hwdvidslogs_views ON #__hwdvidslogs_views.videoid = #__hwdvidsvideos.id WHERE #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 AND #__hwdvidslogs_views.date > NOW() - INTERVAL 1440 MINUTE ORDER BY #__hwdvidslogs_views.date DESC LIMIT 0, 10');
			$rows_bwn = $db->loadObjectList();
			hwdvsDrawFile::XMLDataFile($rows_bwn, 'bwn');
			hwdvsDrawFile::XMLPlaylistFile($rows_bwn, 'bwn');

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 ORDER BY #__hwdvidsvideos.date_uploaded ASC LIMIT 0, 10');
			$rows_recent = $db->loadObjectList();
			hwdvsDrawFile::XMLDataFile($rows_recent, 'recent');
			hwdvsDrawFile::XMLPlaylistFile($rows_recent, 'recent');
		}

		/**
		 * Generate This Weeks's Playlists
		 */
		$cachetime = $c->xmlcache_thisweek; // Seconds to cache files for
		$page = 'http://xmlplaylists_thisweek'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;
		@clearstatcache();

		// Show file from cache if still valid
		if (time() - $cachetime < $cachefile_created)
		{
			echo "Already cached this week's playlists... aborting<br />";
		}
		else
		{
			 // Now the script has run, generate a new cache file
			$fp = @fopen($cachefile, 'w');

			// save the contents of output buffer to the file
			@fwrite($fp, ob_get_contents());
			@fclose($fp);

			// sql search filters
			$where = ' WHERE published = 1';
			$where .= ' AND approved = "yes"';

			$join = ' LEFT JOIN #__users ON #__users.id = #__hwdvidsvideos.user_id';
			if ($c->userdisplay == 1) {
				$select   = '#__users.username,';
				$select_f = '#__users.username,';
			} else {
				$select   = '#__users.name,';
				$select_f = '#__users.name,';
			}
			if ($c->cbint == "2") {
				$join.= ' LEFT JOIN #__community_users ON #__community_users.userid = #__hwdvidsvideos.user_id';
				$select  .= ' #__community_users.*,';
				$select_f.= ' #__community_users.*';
			} else if ($c->cbint == "1") {
				$join.= ' LEFT JOIN #__comprofiler ON #__comprofiler.user_id = #__hwdvidsvideos.user_id';
				$select  .= ' #__comprofiler.avatar,';
				$select_f.= ' #__comprofiler.avatar';
			} else {
				if ($c->userdisplay == 1) {
					$select_f = '#__users.username';
				} else {
					$select_f = '#__users.name';
				}
			}

			// query SQL for this week's data
			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' COUNT(#__hwdvidslogs_views.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_views ON #__hwdvidsvideos.id = #__hwdvidslogs_views.videoid '.$join.' WHERE #__hwdvidslogs_views.date >= DATE_SUB(NOW(),INTERVAL 7 DAY) AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY counts DESC LIMIT 0, 10');
			$rowsmostviewed_thisweek = $db->loadObjectList();
			if (count($rowsmostviewed_thisweek) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostviewed_thisweek, 'mostviewed_thisweek');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostviewed_thisweek, 'mostviewed_thisweek');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' COUNT(#__hwdvidslogs_favours.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_favours ON #__hwdvidsvideos.id = #__hwdvidslogs_favours.videoid '.$join.' WHERE #__hwdvidslogs_favours.date >= DATE_SUB(NOW(),INTERVAL 7 DAY) AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY counts DESC LIMIT 0, 10');
			$rowsmostfavoured_thisweek = $db->loadObjectList();
			if (count($rowsmostfavoured_thisweek) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostfavoured_thisweek, 'mostfavoured_thisweek');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostfavoured_thisweek, 'mostfavoured_thisweek');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' SUM(#__hwdvidslogs_votes.vote)/COUNT(#__hwdvidslogs_votes.videoid) AS sums, COUNT(#__hwdvidslogs_votes.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_votes ON #__hwdvidsvideos.id = #__hwdvidslogs_votes.videoid '.$join.' WHERE #__hwdvidslogs_votes.date >= DATE_SUB(NOW(),INTERVAL 7 DAY) AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY sums DESC LIMIT 0, 10');
			$rowsmostpopular_thisweek = $db->loadObjectList();
			if (count($rowsmostfavoured_thisweek) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostpopular_thisweek, 'mostpopular_thisweek');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostpopular_thisweek, 'mostpopular_thisweek');
			}

			if (!empty($c->xmlcustom01))
			{
				$playlist = explode(",", $c->xmlcustom01);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom01 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom01[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom01, 'custom01');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom01, 'custom01');
			}

			if (!empty($c->xmlcustom02))
			{
				$playlist = explode(",", $c->xmlcustom02);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom02 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom02[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom02, 'custom02');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom02, 'custom02');
			}

			if (!empty($c->xmlcustom03))
			{
				$playlist = explode(",", $c->xmlcustom03);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom03 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom03[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom03, 'custom03');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom03, 'custom03');
			}

			if (!empty($c->xmlcustom04))
			{
				$playlist = explode(",", $c->xmlcustom04);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom04 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom04[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom04, 'custom04');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom04, 'custom04');
			}

			if (!empty($c->xmlcustom05))
			{
				$playlist = explode(",", $c->xmlcustom05);
				$playlist = preg_replace("/[^0-9]/", "", $playlist);

				$counter = 0;
				$rows_custom05 = array();
				for ($i=0, $n=count($playlist); $i < $n; $i++)
				{
					$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.id = '.$playlist[$i]);
					$row = $db->loadObject();
					if (isset($row->id))
					{
						$rows_custom05[$counter] = $row;
						$counter++;
					}
				}

				hwdvsDrawFile::XMLDataFile($rows_custom05, 'custom05');
				hwdvsDrawFile::XMLPlaylistFile($rows_custom05, 'custom05');
			}
		}

		/**
		 * Generate This Month's Playlists
		 */
		$cachetime = $c->xmlcache_thismonth; // Seconds to cache files for
		$page = 'http://xmlplaylists_thismonth'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;
		@clearstatcache();

		// Show file from cache if still valid
		if (time() - $cachetime < $cachefile_created)
		{
			echo "Already cached this months's playlists... aborting<br />";
		}
		else
		{
			 // Now the script has run, generate a new cache file
			$fp = @fopen($cachefile, 'w');

			// save the contents of output buffer to the file
			@fwrite($fp, ob_get_contents());
			@fclose($fp);

			$join = ' LEFT JOIN #__users ON #__users.id = #__hwdvidsvideos.user_id';
			if ($c->userdisplay == 1)
			{
				$select = '#__users.username,';
			}
			else
			{
				$select = '#__users.name,';
			}
			if ($c->cbint == "2")
			{
				$join.= ' LEFT JOIN #__community_users ON #__community_users.userid = #__hwdvidsvideos.user_id';
				$select.= ' #__community_users.*,';
			}
			else if ($c->cbint == "1")
			{
				$join.= ' LEFT JOIN #__comprofiler ON #__comprofiler.user_id = #__hwdvidsvideos.user_id';
				$select.= ' #__comprofiler.avatar,';
			}

			// sql search filters
			$where = ' WHERE published = 1';
			$where .= ' AND approved = "yes"';

			// query SQL for this month's data
			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' COUNT(#__hwdvidslogs_views.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_views ON #__hwdvidsvideos.id = #__hwdvidslogs_views.videoid '.$join.' WHERE #__hwdvidslogs_views.date >= DATE_SUB(NOW(),INTERVAL 30 DAY) AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY counts DESC LIMIT 0, 10');
			$rowsmostviewed_thismonth = $db->loadObjectList();
			if (count($rowsmostviewed_thismonth) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostviewed_thismonth, 'mostviewed_thismonth');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostviewed_thismonth, 'mostviewed_thismonth');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' COUNT(#__hwdvidslogs_favours.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_favours ON #__hwdvidsvideos.id = #__hwdvidslogs_favours.videoid '.$join.' WHERE #__hwdvidslogs_favours.date >= DATE_SUB(NOW(),INTERVAL 30 DAY) AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY counts DESC LIMIT 0, 10');
			$rowsmostfavoured_thismonth = $db->loadObjectList();
			if (count($rowsmostfavoured_thismonth) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostfavoured_thismonth, 'mostfavoured_thismonth');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostfavoured_thismonth, 'mostfavoured_thismonth');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' SUM(#__hwdvidslogs_votes.vote)/COUNT(#__hwdvidslogs_votes.videoid) AS sums, COUNT(#__hwdvidslogs_votes.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidslogs_votes ON #__hwdvidsvideos.id = #__hwdvidslogs_votes.videoid '.$join.' WHERE #__hwdvidslogs_votes.date >= DATE_SUB(NOW(),INTERVAL 30 DAY) AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY sums DESC LIMIT 0, 10');
			$rowsmostpopular_thismonth = $db->loadObjectList();
			if (count($rowsmostpopular_thismonth) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostpopular_thismonth, 'mostpopular_thismonth');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostpopular_thismonth, 'mostpopular_thismonth');
			}

		}

		/**
		 * Generate All Time Playlists
		 */
		$cachetime = $c->xmlcache_thismonth; // Seconds to cache files for
		$page = 'http://xmlplaylists_alltime'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;
		@clearstatcache();

		// Show file from cache if still valid
		if (time() - $cachetime < $cachefile_created)
		{
			echo "Already cached full playlists... aborting<br />";
		}
		else
		{
			 // Now the script has run, generate a new cache file
			$fp = @fopen($cachefile, 'w');

			// save the contents of output buffer to the file
			@fwrite($fp, ob_get_contents());
			@fclose($fp);

			// sql search filters
			$where = ' WHERE published = 1';
			$where .= ' AND approved = "yes"';

			$join     = ' LEFT JOIN #__users ON #__users.id = #__hwdvidsvideos.user_id';
			if ($c->userdisplay == 1)
			{
				$select   = '#__users.username,';
				$select_f = '#__users.username,';
			}
			else
			{
				$select   = '#__users.name,';
				$select_f = '#__users.name,';
			}
			if ($c->cbint == "2")
			{
				$join.= ' LEFT JOIN #__community_users ON #__community_users.userid = #__hwdvidsvideos.user_id';
				$select  .= ' #__community_users.*,';
				$select_f.= ' #__community_users.*';
			}
			else if ($c->cbint == "1")
			{
				$join.= ' LEFT JOIN #__comprofiler ON #__comprofiler.user_id = #__hwdvidsvideos.user_id';
				$select  .= ' #__comprofiler.avatar,';
				$select_f.= ' #__comprofiler.avatar';
			}
			else
			{
				if ($c->userdisplay == 1)
				{
					$select_f = '#__users.username';
				}
				else
				{
					$select_f = '#__users.name';
				}
			}

			// query SQL for all data
			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 ORDER BY #__hwdvidsvideos.number_of_views DESC LIMIT 0, 10');
			$rowsmostviewed_alltime = $db->loadObjectList();
			if (count($rowsmostviewed_alltime) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostviewed_alltime, 'mostviewed_alltime');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostviewed_alltime, 'mostviewed_alltime');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select.' COUNT(#__hwdvidsfavorites.videoid) AS counts FROM #__hwdvidsvideos LEFT JOIN #__hwdvidsfavorites ON #__hwdvidsvideos.id = #__hwdvidsfavorites.videoid '.$join.' WHERE #__hwdvidsfavorites.videoid IS NOT NULL AND #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 GROUP BY #__hwdvidsvideos.id ORDER BY counts DESC LIMIT 0, 10');
			$rowsmostfavoured_alltime = $db->loadObjectList();
			if (count($rowsmostfavoured_alltime) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostfavoured_alltime, 'mostfavoured_alltime');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostfavoured_alltime, 'mostfavoured_alltime');
			}

			$db->SetQuery('SELECT #__hwdvidsvideos.*, '.$select_f.' FROM #__hwdvidsvideos '.$join.' WHERE #__hwdvidsvideos.approved = "yes" AND #__hwdvidsvideos.published = 1 ORDER BY #__hwdvidsvideos.updated_rating DESC LIMIT 0, 10');
			$rowsmostpopular_alltime = $db->loadObjectList();
			if (count($rowsmostpopular_alltime) > 0) {
				hwdvsDrawFile::XMLDataFile($rowsmostpopular_alltime, 'mostpopular_alltime');
				hwdvsDrawFile::XMLPlaylistFile($rowsmostpopular_alltime, 'mostpopular_alltime');
			}

		}
		return;
	}
}
?>