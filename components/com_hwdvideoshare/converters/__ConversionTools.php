<?php
/**
 *    @version 2.1.2 Build 21201 Alpha [ Linkwater ]
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

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwd_vs_ConverterTools
{
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function connectServer($row)
	{
		return mysql_connect($row->host, $row->user, $row->password);
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function connectDatabase($row, $dbconnect)
	{
		return mysql_select_db($row->db, $dbconnect);
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function pingServer($dbconnect)
	{
		return mysql_ping($dbconnect);
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function closeServer($dbconnect)
	{
		@mysql_close($dbconnect);
	}
	/**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function setupBatch($row, $type, $batch)
	{
		// select videos that are waiting conversion
		$sql = "SELECT * FROM ".$row->dbprefix."hwdvidsvideos where approved = '".$type."'";
		$query = @mysql_query($sql) or die('error: ' . mysql_error());

		while ($result = @mysql_fetch_array($query)) {
			$id = $result['id'];
			//set current video to "converting_$type_$batch"
			$sql1 = "UPDATE ".$row->dbprefix."hwdvidsvideos SET approved = 'converting_".$type."_".$batch."' WHERE id = '$id'";
			@mysql_query($sql1) or die('error: ' . mysql_error());
		}

		return true;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function selectBatch($row, $type, $batch)
	{
		$data = array();

		// select videos that are waiting conversion
		$sql = "SELECT * FROM ".$row->dbprefix."hwdvidsvideos where approved ='converting_".$type."_".$batch."'";
		$data = mysql_query($sql) or die('error: ' . mysql_error());

		return $data;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function set($row, $filename_original=null, $id=null)
	{
		if ($filename_original == null) {
			$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET approved = 'converting' WHERE id = '$id'";
		} else {
			$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET approved = 'converting' WHERE video_id = '$filename_original'";
		}
		@mysql_query($sql) or die('error: ' . mysql_error());
		return true;
	}
	/**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function reset($row, $filename_original, $path_new, $status="queuedforconversion")
	{
		$sqlaa = "UPDATE ".$row->dbprefix."hwdvidsvideos SET approved='".$status."' WHERE video_id = '$filename_original'";
		@mysql_query($sqlaa) or die('error: ' . mysql_error());
		if (@file_exists($path_new)) {
			@unlink($path_new);
		}
		return true;
	}



    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function setPending($row, $filename_original)
	{
		$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET approved='pending' WHERE video_id = '$filename_original'";
		$db = @mysql_query($sqlpending);

		if (!$db) {
			@mysql_close($dbconnect);
			$connectServer = hwd_vs_ConverterTools::connectServer($row);
			$connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
			@mysql_query($sql) or die('error: ' . mysql_error());
		}

		return true;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function updateVideoId($row, $filename_original, $filename_noext)
	{
		$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET video_id='$filename_noext' WHERE video_id = '$filename_original'";
		$db = @mysql_query($sqlpending);

		if (!$db) {
			@mysql_close($dbconnect);
			$connectServer = hwd_vs_ConverterTools::connectServer($row);
			$connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
			@mysql_query($sql) or die('error: ' . mysql_error());
		}

		return true;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function addDuration($row, $filename_noext, $duration)
	{
		$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET video_length='$duration' WHERE video_id = '$filename_noext'";
		$db = @mysql_query($sqlpending);

		if (!$db) {
			@mysql_close($dbconnect);
			$connectServer = hwd_vs_ConverterTools::connectServer($row);
			$connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
			@mysql_query($sql) or die('error: ' . mysql_error());
		}

		return true;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function addThumbPosition($row, $filename_noext, $thumb_position)
	{
		$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET thumb_snap='$thumb_position' WHERE video_id = '$filename_noext'";
		$db = @mysql_query($sqlpending);

		if (!$db) {
			@mysql_close($dbconnect);
			$connectServer = hwd_vs_ConverterTools::connectServer($row);
			$connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
			@mysql_query($sql) or die('error: ' . mysql_error());
		}

		return true;
	}
	/**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function autoApprove($row, $filename_noext, $override=0)
	{

		$c = hwd_vs_Config::get_instance();
		if ($c->aav == 1 || $override==1) {
			$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET approved='yes' WHERE video_id = '$filename_noext'";
			$db = @mysql_query($sqlpending);

			if (!$db) {
				@mysql_close($dbconnect);
				$connectServer = hwd_vs_ConverterTools::connectServer($row);
				$connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
				@mysql_query($sql) or die('error: ' . mysql_error());
			}
		}

		return true;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function deleteOriginal($path_new, $path_original)
	{
		$c = hwd_vs_Config::get_instance();
		if ($c->deleteoriginal == 1) {
			if (@file_exists("$path_new") && @file_exists("$path_original")) {
				if ($path_new != $path_original) {
					@unlink($path_original);
				}
			}
		}

		return true;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
    function processOutput($cmd_output, $break="\n")
	{
		$output = '';
		if (is_array($cmd_output)) {
			foreach ($cmd_output as $outputline) {
				$output = $output . $outputline . $break;
			}
		} else {
			$output = $cmd_output;
		}
		return $output;
	}

    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function writeLog($file_contents)
	{
		$c = hwd_vs_Config::get_instance();

		if ($c->logconvert == 1) {
			$logfile = JPATH_SITE.DS.'media'.DS.'hwdVideoShare_VideoConversionLog.dat';
			if (!file_exists($logfile)) {
				$fo = fopen($logfile, 'w');
				fclose($fo);
			}

			if (file_exists($logfile)) {
				$fo = fopen($logfile, 'a');
				fwrite($fo, $file_contents."\n");
				fclose($fo);
			}
		}
		return;
	}
    /**
     * Convert seconds to HOURS:MINUTES:SECONDS format
     * @param database A database connector object
     */
	function sec2hms ($sec, $padHours = false)
	{
		// holds formatted string
		$hms = "";

		// there are 3600 seconds in an hour, so if we
		// divide total seconds by 3600 and throw away
		// the remainder, we've got the number of hours
		$hours = intval(intval($sec) / 3600);

		// add to $hms, with a leading 0 if asked for
		$hms .= ($padHours)
			  ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
			  : $hours. ':';

		// dividing the total seconds by 60 will give us
		// the number of minutes, but we're interested in
		// minutes past the hour: to get that, we need to
		// divide by 60 again and keep the remainder
		$minutes = intval(($sec / 60) % 60);

		// then add to $hms (with a leading 0 if needed)
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

		// seconds are simple - just divide the total
		// seconds by 60 and keep the remainder
		$seconds = intval($sec % 60);

		// add to $hms, again with a leading 0 if needed
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

		// done!
		return $hms;
	}
   /**
	* Convert seconds to HOURS:MINUTES:SECONDS format
	**/
	function hms2sec ($time, $padHours = false)
	{
		$temp = explode(":",$time);

		if (is_numeric($temp[0])) {
			$hour = $temp[0];
		} else { $hour = 0; }
		if (is_numeric($temp[0])) {
			$minute = $temp[1];
		} else { $minute = 0; }
		if (is_numeric($temp[0])) {
			$second = $temp[2];
		} else { $second = 0; }

 		$sec = ($hour*3600) + ($minute*60) + ($second);
		return $sec;
	}
   /**
	* Finish conversion
	**/
	function finish($n, $count, $row, $filename_original, $filename_noext, $duration, $thumb_position, $path_new_flv, $path_original)
	{
		$output = "";

		$output.= "<div class=\"box\"><div><h2>Finishing Conversion (File ".$n." of ".$count.")</h2></div>";

		$output.= "<div><b>UPDATING STATUS TO PENDING</b></div>";
		hwd_vs_ConverterTools::setPending($row, $filename_original);

		$output.= "<div><b>UPDATING VIDEO ID</b></div>";
		hwd_vs_ConverterTools::updateVideoId($row, $filename_original, $filename_noext);

		$output.= "<div><b>UPDATING VIDEO DURATION</b></div>";
		hwd_vs_ConverterTools::addDuration($row, $filename_noext, $duration);

		$output.= "<div><b>UPDATING VIDEO DURATION</b></div>";
		hwd_vs_ConverterTools::addThumbPosition($row, $filename_noext, $thumb_position);

		$output.= "<div><b>CHECKING AUTOAPPROVAL SETTINGS</b></div>";
		hwd_vs_ConverterTools::autoApprove($row, $filename_noext);

		$output.= "<div><b>DELETING ORIGINAL</b></div>";
		hwd_vs_ConverterTools::deleteOriginal($path_new_flv, $path_original);

		$output.= "<div class=\"success\"><b>SUCCESS: All processes complete for file ".$n." of ".$count."</b></div>";

		$output.= "</div>";

		return $output;
	}
}