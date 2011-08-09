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
if (!defined('HWDVS_ADMIN_PATH')) { define('HWDVS_ADMIN_PATH', dirname(__FILE__).'/../'); }

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC3.5
 */
class hwd_vs_recount {
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
	function initiate($override) {

		global $mainframe;

		// set cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cachetime = 86400; // Seconds to cache files for
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://recountfile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create

		$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;
		@clearstatcache();

		if ($override == 2) {
			// Show file from cache if still valid
			if (time() - $cachetime < $cachefile_created) {
				$mainframe->enqueueMessage(_HWDVIDS_M_COUNT_RUN);
				return;
			}
		}

		// Now the script has run, generate a new cache file
		$fp = @fopen($cachefile, 'w');

		// save the contents of output buffer to the file
		@fwrite($fp, ob_get_contents());
		@fclose($fp);

		hwd_vs_recount::recountVideosInCategory();
		hwd_vs_recount::recountSubcatsInCategory();
		hwd_vs_recount::recountMembersInGroup();
		hwd_vs_recount::recountVideosInGroup();
		hwd_vs_recount::recountVideoViews();
		hwd_vs_recount::recountRatings();
		hwd_vs_recount::recountNumberOfComments();

		return true;
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
	function recountVideosInCategory($catid=null) {
		$db = & JFactory::getDBO();
		$c = hwd_vs_Config::get_instance();

        if (isset($catid)) {
			$rows[0]->id = $catid;
        } else {
        	// get all categories
			$query = 'SELECT id'
					. ' FROM #__hwdvidscategories'
					;
			$db->SetQuery($query);
			$rows = $db->loadObjectList();
		}

		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			if ($c->countcvids == 1) {
				$cids = hwd_vs_tools::getChildCategories($row->id);
				$where = ' WHERE category_id IN ('.$cids.')';
			} else {
				$where = ' WHERE category_id = '.$row->id;
			}

			// count videos in category
			$query = 'SELECT count(*)'
					. ' FROM #__hwdvidsvideos'
					. $where
					. ' AND published = 1'
					. ' AND approved = "yes"'
					;
			$db->SetQuery($query);
			$total = $db->loadResult();

			// update category
			$db->SetQuery("UPDATE #__hwdvidscategories SET num_vids = $total WHERE id = $row->id");
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}

		return true;
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
	function recountSubcatsInCategory($catid=null) {
		$db = & JFactory::getDBO();

        if (isset($catid)) {
			$rows[0]->id = $catid;
        } else {
			// get all categories
			$query = 'SELECT id'
					. ' FROM #__hwdvidscategories'
					;
			$db->SetQuery($query);
			$rows = $db->loadObjectList();
		}

		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			// count subcats in category
			$query = 'SELECT count(*)'
					. ' FROM #__hwdvidscategories'
					. ' WHERE parent = '.$row->id
					;
			$db->SetQuery($query);
			$total = $db->loadResult();

			// update category
			$db->SetQuery("UPDATE #__hwdvidscategories SET num_subcats = $total WHERE id = $row->id");
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		return true;
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
	function recountMembersInGroup($groupid=null) {
		$db = & JFactory::getDBO();

        if (isset($groupid)) {
			$rows[0]->id = $groupid;
        } else {
			// get all groups
			$query = 'SELECT id'
					. ' FROM #__hwdvidsgroups'
					;
			$db->SetQuery($query);
			$rows = $db->loadObjectList();
		}

		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			//count group members
			$db->SetQuery( 'SELECT count(*)'
							. ' FROM #__hwdvidsgroup_membership'
							. ' WHERE groupid = '.$row->id
							);
			$total = $db->loadResult();

			// update category
			$db->SetQuery("UPDATE #__hwdvidsgroups SET total_members = $total WHERE id = $row->id");
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		return true;
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
	function recountVideosInGroup($groupid=null) {
		$db = & JFactory::getDBO();

        if (isset($groupid)) {
			$rows[0]->id = $groupid;
        } else {
			// get all groups
			$query = 'SELECT id'
					. ' FROM #__hwdvidsgroups'
					;
			$db->SetQuery($query);
			$rows = $db->loadObjectList();
		}

		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			//count group members
			$db->SetQuery( 'SELECT count(*)'
							. ' FROM #__hwdvidsgroup_videos'
							. ' WHERE groupid = '.$row->id
							);
			$total = $db->loadResult();

			// update category
			$db->SetQuery("UPDATE #__hwdvidsgroups SET total_videos = $total WHERE id = $row->id");
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		return true;
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
	function recountVideoViews($videoid=null)
	{
		$db = & JFactory::getDBO();

        if (isset($videoid))
        {
			$rows[0]->id = $videoid;
        }
        else
        {
			$query = 'SELECT id FROM #__hwdvidsvideos';
			$db->SetQuery($query);
			$rows = $db->loadObjectList();
		}

		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			$db->SetQuery('SELECT count(*) FROM #__hwdvidslogs_views WHERE videoid = '.$row->id);
			$total1 = $db->loadResult();

			$db->SetQuery('SELECT views FROM #__hwdvidslogs_archive WHERE videoid = '.$row->id);
			$total2 = $db->loadResult();

			$total = intval($total1 + $total2);

			if ($total > 0)
			{
				$db->SetQuery("UPDATE #__hwdvidsvideos SET number_of_views = $total WHERE id = $row->id");
				$db->Query();
				if ( !$db->query() )
				{
					echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
					exit();
				}
			}
		}
		return true;
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
	function recountRatings($videoid=null) {
		$db = & JFactory::getDBO();

        if (isset($videoid)) {
			$rows[0]->id = $videoid;
        } else {
			// get all groups
			$query = 'SELECT id, rating_number_votes, rating_total_points'
					. ' FROM #__hwdvidsvideos'
					;
			$db->SetQuery($query);
			$rows = $db->loadObjectList();
		}

		for ($i=0, $n=count($rows); $i < $n; $i++) {

			$row = $rows[$i];

			if ($row->rating_number_votes == 0) {
				$new_rating = 0;
			} else {
				$new_rating = $row->rating_total_points/$row->rating_number_votes;
			}

			$db->SetQuery("UPDATE #__hwdvidsvideos SET updated_rating = $new_rating WHERE id = $row->id");
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		return true;
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
	function recountNumberOfComments($videoid=null)
	{
		$db = & JFactory::getDBO();
		$c = hwd_vs_Config::get_instance();

        if (isset($videoid))
        {
			$rows[0]->id = $videoid;
        }
        else
        {
			$query = 'SELECT id FROM #__hwdvidsvideos';
			$db->SetQuery($query);
			$rows = $db->loadObjectList();
		}

		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			$comments = 0;
			if ( $c->commssys == 0 )
			{
				$tableCheck = "SELECT `object_group` FROM #__jcomments";
				$db->setQuery($tableCheck);
				if ($db->query())
				{
					$db->SetQuery("SELECT count(*) FROM #__jcomments WHERE `object_group` = \"com_hwdvideoshare_v\" AND `object_id` = ".$row->id);
					$comments = $db->loadResult();
				}
			}
			else if ( $c->commssys == 2 )
			{
				//joomlaComment
				$comments = 0;
			}
			else if ( $c->commssys == 3 )
			{
				$tableCheck = "SELECT `option` FROM #__jomcomment";
				$db->setQuery($tableCheck);
				if ($db->query())
				{
					$db->SetQuery("SELECT count(*) FROM #__jomcomment WHERE `option` = \"com_hwdvideoshare_v\" AND `contentid` = ".$row->id);
					$comments = $db->loadResult();
				}
			}
			else if ( $c->commssys == 7 )
			{
				//kunena
				$comments = 0;
			}
			else if ( $c->commssys == 9 )
			{
				//kunena
				$comments = 0;
			}

			$db->SetQuery("UPDATE #__hwdvidsvideos SET number_of_comments = $comments WHERE id = $row->id");
			if ( !$db->query() )
			{
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		return true;
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
	function recountVideosInPlaylist($playlistid=null)
	{
		$db = & JFactory::getDBO();

        if (isset($playlistid))
        {
			$rows[0]->id = $playlistid;
        }
        else
        {
			$query = 'SELECT id FROM #__hwdvidsplaylists';
			$db->SetQuery($query);
			$rows = $db->loadObjectList();
		}

		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			$db->SetQuery( 'SELECT playlist_data FROM #__hwdvidsplaylists WHERE id = '.$row->id );
			$data = $db->loadResult();
			$videos = explode(",", $data);
			$total = count($videos);

			$db->SetQuery("UPDATE #__hwdvidsplaylists SET total_videos = $total WHERE id = $row->id");
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		return true;
    }
}