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
class hwd_vs_logs {
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
	function initiate($override=null)
	{
		global $mainframe;

		// set cache variables
		$cachedir = JPATH_SITE.'/administrator/cache/'; // Directory to cache files in (keep outside web root)
		$cachetime = 86400; // Seconds to cache files for
		$cacheext = 'cache'; // Extension to give cached files (usually cache, htm, txt)
		$page = 'http://archivefile'; // Requested page
		$cachefile = $cachedir . md5($page) . '.' . $cacheext; // Cache file to either load or create

		$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;
		@clearstatcache();

		if ($override == 2) {
			// Show file from cache if still valid
			if (time() - $cachetime < $cachefile_created) {
				$mainframe->enqueueMessage(_HWDVIDS_M_LOG_RUN);
				return;
			}
		}

		 // Now the script has run, generate a new cache file
		$fp = @fopen($cachefile, 'w');

		// save the contents of output buffer to the file
		@fwrite($fp, ob_get_contents());
		@fclose($fp);

		$text = '';
		$text.= hwd_vs_logs::archiveViews();
		$text.= hwd_vs_logs::archiveFavours();
		$text.= hwd_vs_logs::archiveVotes();

		return $text;
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
	function archiveViews()
	{
		$db = & JFactory::getDBO();

		$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_views WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY)");
		$count = $db->loadResult();
		$text = $count." view logs need to be archived\n";
		if ( $count == 0 ) { return $text; }

		$db->SetQuery("SELECT * FROM #__hwdvidslogs_views WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY) LIMIT 0, 500");
		$rows = $db->loadObjectList();
		$totalLogged = 0;

		if (count($rows) > 0)
		{
			for ($i=0, $n=count($rows); $i < $n; $i++)
			{
				$row = $rows[$i];
				$total = 0;
				$tta = 0;
				$ara = 0;
				$views = 0;

				$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_views WHERE videoid = ".$row->videoid." AND date <= DATE_SUB(NOW(),INTERVAL 30 DAY)");
				$tta = $db->loadResult();
				if ( $tta == 0 ) { continue; }

				$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_archive WHERE videoid = ".$row->videoid);
				$total = $db->loadResult();

				if ($total > 0)
				{
					$db->SetQuery("SELECT views FROM #__hwdvidslogs_archive WHERE videoid = ".$row->videoid);
					$ara = $db->loadResult();
					$views = $ara + $tta;

					if ($views > 0)
					{
						$db->SetQuery("UPDATE #__hwdvidslogs_archive SET views = $views WHERE videoid = ".$row->videoid);
						if ( !$db->query() )
						{
							echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
							exit();
						}
					}
				}
				else
				{
					$views = $tta;
					if ($views > 0)
					{
						$row_new = new hwdvidslogs_archive($db);

						$_POST['videoid'] 		= $row->videoid;
						$_POST['views'] 		= $views;

						if (!$row_new->bind($_POST))
						{
							echo "<script> alert('".$row_new->getError()."'); window.history.go(-1); </script>\n";
							exit();
						}

						if (!$row_new->store())
						{
							echo "<script> alert('".$row_new -> getError()."'); window.history.go(-1); </script>\n";
							exit();
						}
					}
				}

				if ($views > 0)
				{
					$db->SetQuery("DELETE FROM #__hwdvidslogs_views WHERE videoid = ".$row->videoid." AND date <= DATE_SUB(NOW(),INTERVAL 30 DAY)" );
					if ( !$db->query() )
					{
						echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
						exit();
					}
				}

				$totalLogged = $totalLogged + $tta;
			}
		}

		$text = "Successfully archived ".$totalLogged." views\n";

		$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_views WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY)");
		$count = $db->loadResult();
		$text.= $count." view logs need to be archived\n";

		return $text;
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
	function archiveFavours()
	{
		$db = & JFactory::getDBO();

		$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_favours WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY)");
		$count = $db->loadResult();
		$text = $count." favour logs need to be archived\n";
		if ( $count == 0 ) { return $text; }

		$db->SetQuery("SELECT * FROM #__hwdvidslogs_favours WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY) LIMIT 0, 500");
		$rows = $db->loadObjectList();
		$totalLogged = 0;

		if (count($rows) > 0)
		{
			for ($i=0, $n=count($rows); $i < $n; $i++)
			{
				$row = $rows[$i];

				$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_favours WHERE videoid = ".$row->videoid." AND date <= DATE_SUB(NOW(),INTERVAL 30 DAY)");
				$tta = $db->loadResult();
				if ( $tta == 0 ) { continue; }

				$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_archive WHERE videoid = ".$row->videoid);
				$total = $db->loadResult();

				if ($total > 0)
				{
					$db->SetQuery("SELECT favours FROM #__hwdvidslogs_archive WHERE videoid = ".$row->videoid);
					$favours = $db->loadResult();
					$favours = $favours + $tta;

					$db->SetQuery("UPDATE #__hwdvidslogs_archive SET favours = $favours WHERE videoid = ".$row->videoid);
					if ( !$db->query() )
					{
						echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
						exit();
					}
				}
				else
				{
					$row_new = new hwdvidslogs_archive($db);

					$_POST['videoid'] 		= $row->videoid;
					$_POST['favours'] 		= $tta;

					if (!$row_new->bind($_POST))
					{
						echo "<script> alert('".$row_new->getError()."'); window.history.go(-1); </script>\n";
						exit();
					}

					if (!$row_new->store())
					{
						echo "<script> alert('".$row_new -> getError()."'); window.history.go(-1); </script>\n";
						exit();
					}
				}

				$db->SetQuery("DELETE FROM #__hwdvidslogs_favours WHERE videoid = ".$row->videoid." AND date <= DATE_SUB(NOW(),INTERVAL 30 DAY)" );
				if ( !$db->query() )
				{
					echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
					exit();
				}

				$totalLogged = $totalLogged + $tta;
			}
		}

		$text = "Successfully archived ".$totalLogged." favours\n";

		$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_favours WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY)");
		$count = $db->loadResult();
		$text.= $count." favour logs need to be archived\n";

		return $text;
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
	function archiveVotes()
	{
		$db = & JFactory::getDBO();

		$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_votes WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY)");
		$count = $db->loadResult();
		$text = $count." vote logs need to be archived\n";
		if ( $count == 0 ) { return $text; }

		$db->SetQuery("SELECT * FROM #__hwdvidslogs_votes WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY) LIMIT 0, 1000");
		$rows = $db->loadObjectList();

		if (count($rows) > 0)
		{
			for ($i=0, $n=count($rows); $i < $n; $i++)
			{
				$row = $rows[$i];

				$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_archive WHERE videoid = ".$row->videoid);
				$total = $db->loadResult();

				if ($total > 0)
				{
					$db->SetQuery("SELECT number_of_votes, sum_of_votes FROM #__hwdvidslogs_archive WHERE videoid = ".$row->videoid);
					$data = $db->loadObject();
					$number_of_votes = $data->number_of_votes + 1;
					$sum_of_votes    = $data->sum_of_votes    + $row->vote;
					$rating          = $sum_of_votes/$number_of_votes;

					$db->SetQuery("UPDATE #__hwdvidslogs_archive SET number_of_votes=".$number_of_votes.", sum_of_votes=".$sum_of_votes.", rating=".$rating." WHERE videoid = $row->videoid");
					if ( !$db->query() )
					{
						echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
						exit();
					}
				}
				else
				{
					$row_new = new hwdvidslogs_archive($db);

					$_POST['videoid'] 			= $row->videoid;
					$_POST['number_of_votes'] 	= 1;
					$_POST['sum_of_votes'] 		= $row->vote;
					$_POST['rating'] 			= $row->vote;

					if (!$row_new->bind($_POST))
					{
						echo "<script> alert('".$row_new->getError()."'); window.history.go(-1); </script>\n";
						exit();
					}

					if (!$row_new->store())
					{
						echo "<script> alert('".$row_new -> getError()."'); window.history.go(-1); </script>\n";
						exit();
					}
				}

				$db->SetQuery("DELETE FROM #__hwdvidslogs_votes WHERE id = ".$row->id." AND date <= DATE_SUB(NOW(),INTERVAL 30 DAY)" );
				if ( !$db->query() )
				{
					echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
					exit();
				}
			}
		}

		$text = "Successfully archived ".count($rows)." votes\n";

		$db->SetQuery("SELECT count(*) FROM #__hwdvidslogs_votes WHERE date <= DATE_SUB(NOW(),INTERVAL 30 DAY)");
		$count = $db->loadResult();
		$text.= $count." vote logs need to be archived\n";

		return $text;
    }
}