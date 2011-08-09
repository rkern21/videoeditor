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
class hwd_vs_fixerrors {
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
		$page = 'http://fixerrorfile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create

		$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;
		@clearstatcache();

		if ($override == 2) {
			// Show file from cache if still valid
			if (time() - $cachetime < $cachefile_created) {
				$mainframe->enqueueMessage(_HWDVIDS_M_FIX_RUN);
				return;
			}
		}

		 // Now the script has run, generate a new cache file
		$fp = @fopen($cachefile, 'w');

		// save the contents of output buffer to the file
		@fwrite($fp, ob_get_contents());
		@fclose($fp);

		hwd_vs_fixerrors::fixVideoDataFormat();
		hwd_vs_fixerrors::cleanAntileech();

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
	function fixVideoDataFormat($videoid=null) {
		$db = & JFactory::getDBO();

        if (isset($videoid)) {

			$query = 'SELECT id, title, description, tags'
					. ' FROM #__hwdvidsvideos'
					. ' WHERE id = '.$videoid
					;
			$db->SetQuery($query);
			$rows = $db->loadObjectList();

        } else {

			$query = 'SELECT *'
					. ' FROM #__hwdvidsvideos'
					;
			$db->SetQuery($query);
			$rows = $db->loadObjectList();

		}

		for ($i=0, $n=count($rows); $i < $n; $i++) {

			$row = $rows[$i];

			$title 				= hwd_vs_tools::generatePostTitle($row->title);
			$description 		= hwd_vs_tools::generatePostDescription($row->description);
			$tags 				= hwd_vs_tools::generatePostTags($row->tags);

			$thumb_snap = $row->thumb_snap;
			if ($row->thumb_snap == "0:00:00") {
				$sec = intval(hwd_vs_tools::hms2sec($row->video_length));
				if ($sec < 2) {
					$thumb_snap = "0:00:02";
				} else {
					$thumb_snap = hwd_vs_tools::sec2hms($sec/2);
				}
			}

			// update sql
			$db->SetQuery("UPDATE #__hwdvidsvideos SET title = \"$title\", description = \"$description\", tags = \"$tags\", thumb_snap = \"$thumb_snap\"  WHERE id = $row->id");
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
	function cleanAntileech() {
		$db = & JFactory::getDBO();
		$db->SetQuery('TRUNCATE #__hwdvidsantileech');
		$db->query();
		return true;
    }
}