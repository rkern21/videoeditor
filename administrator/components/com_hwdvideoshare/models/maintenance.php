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

class hwdvids_BE_maintenance
{
   /**
	* system cleanup
	*/
	function maintenance()
	{
		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"deleted\""
							);
		$total = $db->loadResult();
		echo $db->getErrorMsg();

		$permdelete_report = null;
		$fixerrors_report = null;
		$recount_report = null;
		$archivelogs_report = null;

		// set fixerror cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://fixerrorfile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$fixerror_cache = (@file_exists($cachefile)) ? @date ("F d Y H:i:s.", filemtime($cachefile)) : "Never";

		// set recount cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://recountfile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$recount_cache = (@file_exists($cachefile)) ? @date ("F d Y H:i:s.", filemtime($cachefile)) : "Never";

		// set archive cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://archivefile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$archive_cache = (@file_exists($cachefile)) ? @date ("F d Y H:i:s.", filemtime($cachefile)) : "Never";

		hwdvids_HTML::maintenance($permdelete_report, $total, $fixerrors_report, $recount_report, $archivelogs_report, $fixerror_cache, $recount_cache, $archive_cache);
	}
   /**
	* system cleanup
	*/
	function runmaintenance()
	{
		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$run_permdel = JRequest::getInt( 'run_permdel', 0 );
		$run_fixerrors = JRequest::getInt( 'run_fixerrors', 0 );
		$run_recount = JRequest::getInt( 'run_recount', 0 );
		$run_archivelogs = JRequest::getInt( 'run_archivelogs', 0 );

		$permdelete_report = null;
		$fixerrors_report = null;
		$recount_report = null;
		$archivelogs_report = null;

		if ($run_permdel == 1) {
			// permenantly delete
			$query = "SELECT a.*"
					. "\nFROM #__hwdvidsvideos AS a"
					. "\nWHERE a.approved = \"deleted\""
								;
			$db->SetQuery( $query );
			$rows = $db->loadObjectList();

			for($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];

				$files   = array();
				$files[] = JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".flv";
				$files[] = JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".mp4";
				$files[] = JPATH_SITE."/hwdvideos/thumbs/".$row->video_id.".jpg";
				$files[] = JPATH_SITE."/hwdvideos/thumbs/".$row->video_id.".gif";
				$files[] = JPATH_SITE."/hwdvideos/thumbs/l_".$row->video_id.".jpg";

				for($j=0, $m=count( $files ); $j < $m; $j++)
				{
					$file = &$files[$j];

					if (@file_exists($file))
					{
						@unlink($file);
					}
				}
			}

			$db->SetQuery("DELETE FROM #__hwdvidsvideos WHERE approved = \"deleted\"");
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
			$permdelete_report = true;
		}

		if ($run_fixerrors !== 0) {
			// perform 'fix errors' maintenance
			require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_fixerrors.class.php');
			$fixerrors_report = hwd_vs_fixerrors::initiate($run_fixerrors);
		}

		if ($run_recount !== 0) {
			// perform 'recount' maintenance
			require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
			$recount_report = hwd_vs_recount::initiate($run_recount);
		}

		// set fixerror cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://fixerrorfile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$fixerror_cache = (@file_exists($cachefile)) ? @date ("F d Y H:i:s.", filemtime($cachefile)) : "Never";

		// set recount cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://recountfile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$recount_cache = (@file_exists($cachefile)) ? @date ("F d Y H:i:s.", filemtime($cachefile)) : "Never";

		// set archive cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://archivefile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$archive_cache = (@file_exists($cachefile)) ? @date ("F d Y H:i:s.", filemtime($cachefile)) : "Never";

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"deleted\""
							);
		$total = $db->loadResult();
		echo $db->getErrorMsg();

		hwdvids_HTML::maintenance($permdelete_report, $total, $fixerrors_report, $recount_report, $archivelogs_report, $fixerror_cache, $recount_cache, $archive_cache);
	}
   /**
	* system cleanup
	*/
	function clearPlaylistCache()
	{
		// set cache variables
		$cachedir = JPATH_SITE.'/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)

		$page_today = 'http://xmlplaylists_today';
		$cachefile_today = $cachedir . md5($page_today) . '.' . $cacheext;

		$page_thisweek = 'http://xmlplaylists_thisweek';
		$cachefile_thisweek = $cachedir . md5($page_thisweek) . '.' . $cacheext;

		$page_thismonth = 'http://xmlplaylists_thismonth';
		$cachefile_thismonth = $cachedir . md5($page_thismonth) . '.' . $cacheext;

		$page_alltime = 'http://xmlplaylists_alltime';
		$cachefile_alltime = $cachedir . md5($page_alltime) . '.' . $cacheext;

		if (file_exists($cachefile_today)) { unlink($cachefile_today); }
		if (file_exists($cachefile_thisweek)) { unlink($cachefile_thisweek); }
		if (file_exists($cachefile_thismonth)) { unlink($cachefile_thismonth); }
		if (file_exists($cachefile_alltime)) { unlink($cachefile_alltime); }

		echo "Playlist Cache Successfully Cleared";
		exit;
	}
   /**
	* system cleanup
	*/
	function clearTemplateCache()
	{
		global $smartyvs;
		$c = hwd_vs_Config::get_instance();

		$smartyvs->clear_compiled_tpl();

		$vs_temp_cache = JPATH_SITE.'/cache/hwdvs'.$c->hwdvids_template_file;
		$smartyvs->compile_dir = $vs_temp_cache;
		$smartyvs->clear_compiled_tpl();

		echo "Template Cache Successfully Cleared";
		exit;
	}
   /**
	* system cleanup
	*/
	function regenerateThumbnails()
	{
 		global $option;
 		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

  		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 're-generate_thumb' WHERE video_type IN ('local','mp4') AND approved = 'yes'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$app->enqueueMessage(_HWDVIDS_RUNCON);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=converter' );
	}
   /**
	* system cleanup
	*/
	function recalculateDurations()
	{
 		global $option;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

  		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 're-calculate_duration' WHERE approved = 'yes'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$app->enqueueMessage(_HWDVIDS_RUNCON);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=converter' );
	}
   /**
	* system cleanup
	*/
	function cancelThumbnailRegeneration()
	{
 		global $option;
 		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

  		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 'yes' WHERE video_type IN ('local','mp4') AND approved = 're-generate_thumb'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=converter' );
	}
   /**
	* system cleanup
	*/
	function cancelDurationRecalculation()
	{
 		global $option;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

  		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 'yes' WHERE approved = 're-calculate_duration'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=converter' );
	}
   /**
	* system cleanup
	*/
	function ajax_ArchiveLogs()
	{
		global $smartyvs;
		$c = hwd_vs_Config::get_instance();

		// perform 'archivelogs' maintenance
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_archivelogs.class.php');
		$archivelogs_report = hwd_vs_logs::initiate();

		$text = '<textarea cols="50" rows="4">';
		$text.= $archivelogs_report;
		$text.= '</textarea>';

		echo $text;

		// set archive cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://archivefile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create
		$archive_cache = (@file_exists($cachefile)) ? @date ("F d Y H:i:s.", filemtime($cachefile)) : "Never";

		exit;

	}
   /**
	* system cleanup
	*/
	function ajax_WarpHdSync()
	{
		global $smartyvs;
		$db = & JFactory::getDBO();
		$c = hwd_vs_Config::get_instance();

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'warp'.DS.'infin-lib.php');
		$accountKey = $c->warpAccountKey;
		$secretKey = $c->warpSecretKey;

		$query = 'SELECT * FROM #__hwdvidsvideos WHERE video_type = "warphd"';
		$db->SetQuery($query);
		$rows = $db->loadObjectList();

		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			$infinVideo = new InfinovationVideo($accountKey, $secretKey);
			$videoInfo = $infinVideo->getVideoInfo($row->video_id);

			$duration = hwd_vs_tools::sec2hms($videoInfo->duration);

			$db->SetQuery("UPDATE #__hwdvidsvideos SET video_length = \"$duration\" WHERE id = $row->id");
			if ( !$db->query() )
			{
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}

		echo "Synchronised ".count($rows)." WarpHD videos";
		exit;
	}
}
?>