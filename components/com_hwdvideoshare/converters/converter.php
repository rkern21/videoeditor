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

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
define('CONVERTPATH', dirname(__FILE__) );

if(substr(PHP_OS, 0, 3) == "WIN") {

	define('JPATH_SITE', str_replace("\components\com_hwdvideoshare\converters", "", CONVERTPATH) );

} else {

	define('JPATH_SITE', str_replace("/components/com_hwdvideoshare/converters", "", CONVERTPATH) );

}

header('Content-type: text/html; charset=utf-8');

include_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'converters'.DS.'__ConversionTools.php');
include_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'converters'.DS.'__ConvertVideo.php');
include_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'converters'.DS.'__ExtractDuration.php');
include_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'converters'.DS.'__GenerateThumbnail.php');
include_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'converters'.DS.'__InjectMetaData.php');
include_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'converters'.DS.'__MoveMoovAtom.php');
include_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'converters'.DS.'__UpdateDatabase.php');
include_once(JPATH_SITE.DS.'configuration.php');
include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');

$c          = hwd_vs_Config::get_instance();
$s          = hwd_vs_SConfig::get_instance();
$row        = new JConfig;
$batch      = rand(100, 999);
$path_base  = JPATH_SITE.DS.'hwdvideos';
$output     = '';

$output.= "<html><head><link type=\"text/css\" rel=\"stylesheet\" href=\"../../../administrator/components/com_hwdvideoshare/assets/css/converter.css\" /></head><body>";

if (is_callable('exec') && function_exists('exec')) {
	// continue
} else {
	$output.= "You must enable the exec() function before you can convert videos.<br /><br />";
	$output.= "<img src=\"../../../components/com_hwdvideoshare/assets/images/icons/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The exec() function <font color=\"red\"><b>is not available</b></font><br /><br />";
	$output.= "</body></html>";
	echo $output;
	exit;
}
if (ini_get('safe_mode')) {
	$output.= "You must disable safe_mode before you can convert videos.<br /><br />";
	$output.= "<img src=\"../../../components/com_hwdvideoshare/assets/images/icons/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The PHP safe mode is <font color=\"red\"><b>On</b></font><br /><br />";
	$output.= "</body></html>";
	echo $output;
	exit;
} else {
	// continue
}

// Restart converter
$output.= "<div class=\"box\"><div><h2>Re-start Converter</h2></div>
        <div style=\"padding:5px;\">
          <a href=\"../../../components/com_hwdvideoshare/converters/converter.php?internal=1\">
            <img src=\"../../../administrator/components/com_hwdvideoshare/assets/images/go.png\" border=\"0\" alt=\"\" />
          </a>
        </div>
      </div>";

// Make a database connection to the Joomla database
$output.= "<div class=\"box\"><div><h2>Connecting to Database</h2></div>";

$connectServer = hwd_vs_ConverterTools::connectServer($row);
if ($connectServer) {
	$output.= "<div class=\"success\">SUCCESS: Connected successfully to Joomla SQL server</div>";
} else {
	die('Could not connect: ' . mysql_error());
}

$connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
if ($connectDatabase) {
	$output.= "<div class=\"success\">SUCCESS: Connected successfully to Joomla SQL database</div>";
} else {
    die ('Can\'t use Joomla database : ' . mysql_error());
}

$setupBatchCON = hwd_vs_ConverterTools::setupBatch($row, "queuedforconversion", $batch);
if ($setupBatchCON) {
	$output.= "<div class=\"success\">SUCCESS: Successfully setup \"core\" batch conversion</div>";
} else {
    die ('Can\'t setup conversion : ' . $setupBatch);
}

$setupBatchTHU = hwd_vs_ConverterTools::setupBatch($row, "queuedforthumbnail", $batch);
if ($setupBatchTHU) {
	$output.= "<div class=\"success\">SUCCESS: Successfully setup \"flv\" batch conversion</div>";
} else {
    die ('Can\'t setup conversion : ' . $setupBatch);
}

$setupBatchSWF = hwd_vs_ConverterTools::setupBatch($row, "queuedforswf", $batch);
if ($setupBatchSWF) {
	$output.= "<div class=\"success\">SUCCESS: Successfully setup \"swf\" batch conversion</div>";
} else {
    die ('Can\'t setup conversion : ' . $setupBatch);
}

$setupBatchMP4 = hwd_vs_ConverterTools::setupBatch($row, "queuedformp4", $batch);
if ($setupBatchMP4) {
	$output.= "<div class=\"success\">SUCCESS: Successfully setup \"mp4\" batch conversion</div>";
} else {
    die ('Can\'t setup conversion : ' . $setupBatch);
}

$setupBatchRD = hwd_vs_ConverterTools::setupBatch($row, "re-calculate_duration", $batch);
if ($setupBatchRD) {
	$output.= "<div class=\"success\">SUCCESS: Successfully setup \"re-generation\" batch conversion</div>";
} else {
    die ('Can\'t setup conversion : ' . $setupBatch);
}

$setupBatchRT = hwd_vs_ConverterTools::setupBatch($row, "re-generate_thumb", $batch);
if ($setupBatchRT) {
	$output.= "<div class=\"success\">SUCCESS: Successfully setup \"re-generation\" batch conversion</div>";
} else {
    die ('Can\'t setup conversion : ' . $setupBatch);
}

$output.= "</div>";

unset($selectBatch);
$selectBatch = hwd_vs_ConverterTools::selectBatch($row, "queuedforconversion", $batch);
$count = mysql_num_rows($selectBatch);

	if ($count == 0) {
		$output.= "<div class=\"box\"><h2>There are no videos to convert</h2><h3>Exiting...</h3></div>";
	} else {

		$n = 1;
		while ($result = mysql_fetch_array($selectBatch)) {

			$filename_original = $result['video_id'];
			list($filename_noext, $filename_ext) = @split('\.', $filename_original);

			$path_original = $path_base.DS.'uploads'.DS.'originals'.DS.$filename_original;
			$path_new_flv  = $path_base.DS.'uploads'.DS.$filename_noext.'.flv';
			$path_new_mp4  = $path_base.DS.'uploads'.DS.$filename_noext.'.mp4';

			hwd_vs_ConverterTools::set($row, $filename_original);

		   /******************************************
			* CONVERT VIDEOS TO FLV FORMAT
			**/
			$ConvertVideo = hwd_vs_ConvertVideo::convert($path_original, $path_new_flv, $filename_ext, $path_new_mp4);

			$output.= $ConvertVideo[6];

			if (intval($ConvertVideo[0]) !== 2) {
				hwd_vs_ConverterTools::reset($row, $filename_original, $path_new_flv);

				$file_contents = '['.date('Y-m-d H:i:s').'] [ Conversion Failed ] INPUT: '.$ConvertVideo[2].' OUTPUT: '.hwd_vs_ConverterTools::processOutput($ConvertVideo[3], " ");
				hwd_vs_ConverterTools::writeLog($file_contents);

				$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";

				$n = $n + 1;
				continue;
			} else {
				@chmod($path_new_flv, 0755);
				@chmod($path_new_mp4, 0755);
			}

		   /******************************************
			* FLASH META DATA MANIPULATION (INSERT onMetaData TAG)
			**/
			$InjectMetaData = hwd_vs_InjectMetaData::inject($path_new_flv);

			$output.= $InjectMetaData[4];

			if ($InjectMetaData[0] == 0) {
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Meta Injection Failed ] INPUT: '.$InjectMetaData[2].' OUTPUT: '.$InjectMetaData[3];
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

		   /******************************************
			* GET VIDEO LENGTH
			**/
			$ExtractDuration = hwd_vs_ExtractDuration::extract($path_new_flv, hwd_vs_ConverterTools::processOutput($ConvertVideo[3]));

			$output.= $ExtractDuration[3];

			if ($ExtractDuration[1] == "0:00:01") {
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Duration Lookup Failed ]';
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

		   /******************************************
			* CREATE THUMBNAIL IMAGE
			**/
			if (file_exists($path_new_mp4) && filesize($path_new_mp4) > 0)
			{
				$path_video = $path_new_mp4;
			}
			else
			{
				$path_video = $path_new_flv;
			}
			$GenerateThumbnail = hwd_vs_GenerateThumbnail::draw($path_base, $path_video, $filename_noext, $filename_ext, $ExtractDuration[1], $ExtractDuration[0]);

			$output.= $GenerateThumbnail[9];

			if ($GenerateThumbnail[0] == 0) {
				// write to log
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Static Thumbnail Generation Failed ] INPUT: '.$GenerateThumbnail[2].' OUTPUT: '.$GenerateThumbnail[3];
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

			if ($GenerateThumbnail[1] == 0) {
				// write to log
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Dynamic Thumbnail Generation Failed ] INPUT: '.$GenerateThumbnail[4].' OUTPUT: '.$GenerateThumbnail[5];
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

			if($c->abortthumbfail == 1){
				if($GenerateThumbnail[0] == 0){
					hwd_vs_ConverterTools::reset($row, $filename_original, $path_new_flv);

					$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";
					$n = $n + 1;
					continue;
				}
			}

		   /******************************************
			* UPDATE APPROVAL STATUS IN DATABASE
			**/

			if (!hwd_vs_ConverterTools::pingServer($connectServer)) {
			  $closeServer = hwd_vs_ConverterTools::closeServer($connectServer);
			  $connectServer = hwd_vs_ConverterTools::connectServer($row);
			  $connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
			}

		    $output.= hwd_vs_ConverterTools::finish($n, $count, $row, $filename_original, $filename_noext, $ExtractDuration[0], $ExtractDuration[1], $path_new_flv, $path_original);
			$file_contents = '['.date('Y-m-d H:i:s').'] [ Successful Video Conversion ] Video ID: '.$result['id'];
			hwd_vs_ConverterTools::writeLog($file_contents);

			$n = $n + 1;

		}
	}
   /**
	* END WHILE
	**/

unset($selectBatch);
$selectBatch = hwd_vs_ConverterTools::selectBatch($row, "queuedforthumbnail", $batch);
$count = mysql_num_rows($selectBatch);

	// check if any waiting videos
	if ($count == 0) {
		$output.= "<div class=\"box\"><h2>There are no videos waiting for thumbnail creation</h2><h3>Exiting...</h3></div>";
	} else {
	   /**
		* Process each video file
		**/
		$n = 1;
		while ($result = @mysql_fetch_array($selectBatch)) {

			$filename_original = $result['video_id'];
			$path_original = $path_base . '/uploads/originals/' . $filename_original;
			list($filename_noext, $filename_ext) = @split('\.', $filename_original);
			$path_new_flv = $path_base.DS.'uploads'.DS.$filename_noext.'.flv';
			$path_new_mp4 = $path_base.DS.'uploads'.DS.$filename_noext.'.mp4';

			hwd_vs_ConverterTools::set($row, $filename_original);

		   /******************************************
			* COPYING VIDEOS INTO THE UPLOAD DIRECTORY
			**/
			$output.= "<div class=\"box\"><div><h2>Copying FLV Video File (File ".$n." of ".$count.")</h2></div>";
				if(@copy($path_original, $path_new_flv)){
					$output.= "<div class=\"success\">SUCCESS: Original video file copied to upload directory</div>";
				} else {
					$output.= "<div class=\"error\">ERROR: Could not copy the original video into the upload directory. Check directory permissions.</div>";
				}
			$output.= "</div>";

			if(!file_exists($path_new_flv) || (filesize($path_new_flv) == 0)){
				hwd_vs_ConverterTools::reset($row, $filename_original, $path_new_flv, "queuedforthumbnail");

				$file_contents = '['.date('Y-m-d H:i:s').'] [ Copy Failed ] LOCATION: '.$path_new_flv;
				hwd_vs_ConverterTools::writeLog($file_contents);

				$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";

				$n = $n + 1;
				continue;

			}
			chmod($path_new_flv, 0755);
		   /******************************************
			* GET VIDEO LENGTH
			**/
			$ExtractDuration = hwd_vs_ExtractDuration::extract($path_new_flv, '');

			$output.= $ExtractDuration[3];

			if ($ExtractDuration[1] == "0:00:01") {
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Duration Lookup Failed ]';
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

		   /******************************************
			* CREATE THUMBNAIL IMAGE
			**/
			if (file_exists($path_new_mp4) && filesize($path_new_mp4) > 0)
			{
				$path_video = $path_new_mp4;
			}
			else
			{
				$path_video = $path_new_flv;
			}
			$GenerateThumbnail = hwd_vs_GenerateThumbnail::draw($path_base, $path_video, $filename_noext, $filename_ext, $ExtractDuration[1], $ExtractDuration[0]);
			$GenerateThumbnail = hwd_vs_GenerateThumbnail::draw($path_base, $path_video, $filename_noext, $filename_ext, $ExtractDuration[1], $ExtractDuration[0]);

			$output.= $GenerateThumbnail[9];

			if ($GenerateThumbnail[0] == 0) {
				// write to log
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Static Thumbnail Generation Failed ] INPUT: '.$GenerateThumbnail[2].' OUTPUT: '.$GenerateThumbnail[3];
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

			if ($GenerateThumbnail[1] == 0) {
				// write to log
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Dynamic Thumbnail Generation Failed ] INPUT: '.$GenerateThumbnail[4].' OUTPUT: '.$GenerateThumbnail[5];
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

			if($c->abortthumbfail == 1){
				if($GenerateThumbnail[0] == 0){
					hwd_vs_ConverterTools::reset($row, $filename_original, $path_new_flv, "queuedforthumbnail");

					$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";
					$n = $n + 1;
					continue;
				}
			}

		   /******************************************
			* UPDATE APPROVAL STATUS IN DATABASE
			**/
			if (!hwd_vs_ConverterTools::pingServer($connectServer)) {
			  $closeServer = hwd_vs_ConverterTools::closeServer($connectServer);
			  $connectServer = hwd_vs_ConverterTools::connectServer($row);
			  $connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
			}

		    $output.= hwd_vs_ConverterTools::finish($n, $count, $row, $filename_original, $filename_noext, $ExtractDuration[0], $ExtractDuration[1], $path_new_flv, $path_original);
			$file_contents = '['.date('Y-m-d H:i:s').'] [ Successful Thumbnail Generation ] Video ID: '.$result['id'];
			hwd_vs_ConverterTools::writeLog($file_contents);

			$n = $n + 1;

		}
	}

   /**
	* END WHILE
	**/

unset($selectBatch);
$selectBatch = hwd_vs_ConverterTools::selectBatch($row, "queuedforswf", $batch);
$count = mysql_num_rows($selectBatch);

	// check if any waiting videos
	if ($count == 0) {
		$output.= "<div class=\"box\"><h2>There are no videos waiting for swf processing</h2><h3>Exiting...</h3></div>";
	} else {
	   /**
		* Process each video file
		**/
		$n = 1;
		while ($result = @mysql_fetch_array($selectBatch)) {

			$filename_original = $result['video_id'];
			$path_original = $path_base . '/uploads/originals/' . $filename_original;
			list($filename_noext, $filename_ext) = @split('\.', $filename_original);
			$path_new = $path_base . "/uploads/" . $filename_noext . ".swf";

			hwd_vs_ConverterTools::set($row, $filename_original);

		   /******************************************
			******************************************
			* COPYING VIDEOS INTO THE UPLAOD DIRECTORY
			**/
			$output.= "<div class=\"box\"><div><h2>Copying SWF Video File (File ".$n." of ".$count.")</h2></div>";
				if(copy($path_original, $path_new)){
					$output.= "<div class=\"success\">SUCCESS: Original video file copied to upload directory</div>";
				} else {
					$output.= "<div class=\"error\">ERROR: Could not copy the original video into the upload directory. Check directory permissions.</div>";
				}
			$output.= "</div>";

			if(!file_exists($path_new) || (filesize($path_new) == 0)){
				hwd_vs_ConverterTools::reset($row, $filename_original, $path_new_flv, "queuedforswf");

				$file_contents = '['.date('Y-m-d H:i:s').'] [ Copy Failed ] LOCATION: '.$path_new;
				hwd_vs_ConverterTools::writeLog($file_contents);

				$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";

				$n = $n + 1;
				continue;

			}
			chmod($path_new, 0755);

		   /******************************************
			* UPDATE APPROVAL STATUS IN DATABASE
			**/
			if (!hwd_vs_ConverterTools::pingServer($connectServer)) {
			  $closeServer = hwd_vs_ConverterTools::closeServer($connectServer);
			  $connectServer = hwd_vs_ConverterTools::connectServer($row);
			  $connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
			}

		    $output.= hwd_vs_ConverterTools::finish($n, $count, $row, $filename_original, $filename_noext, $ExtractDuration[0], $ExtractDuration[1], $path_new_flv, $path_original);
			$file_contents = '['.date('Y-m-d H:i:s').'] [ Successful SWF Process ] Video ID: '.$result['id'];
			hwd_vs_ConverterTools::writeLog($file_contents);

			$n = $n + 1;

		}
	}

   /**
	* END WHILE
	**/

unset($selectBatch);
$selectBatch = hwd_vs_ConverterTools::selectBatch($row, "queuedformp4", $batch);
$count = mysql_num_rows($selectBatch);

	// check if any waiting videos
	if ($count == 0) {
		$output.= "<div class=\"box\"><h2>There are no videos waiting for mp4 processing</h2><h3>Exiting...</h3></div>";
	} else {
	   /**
		* Process each video file
		**/
		$n = 1;
		while ($result = @mysql_fetch_array($selectBatch)) {
			$filename_original = $result['video_id'];
			$path_original = $path_base . '/uploads/originals/' . $filename_original;
			list($filename_noext, $filename_ext) = @split('\.', $filename_original);

			$path_original = $path_base . '/uploads/originals/' . $filename_original;
			$path_new_flv  = $path_base . "/uploads/" . $filename_noext . ".flv";
			$path_new_mp4  = $path_base . "/uploads/" . $filename_noext . ".mp4";

			hwd_vs_ConverterTools::set($row, $filename_original);

		   /******************************************
			* CONVERT VIDEOS TO FLV FORMAT (OR COPY)
			**/
		    if ($c->reconvertflv == 1) {

				$ConvertVideo = hwd_vs_ConvertVideo::convert($path_original, $path_new_flv, $filename_ext, $path_new_mp4);

				$output.= $ConvertVideo[6];

				if (intval($ConvertVideo[0]) !== 2) {
					hwd_vs_ConverterTools::reset($row, $filename_original, $path_new_flv, "queuedformp4");

					$file_contents = '['.date('Y-m-d H:i:s').'] [ Conversion Failed ] INPUT: '.$ConvertVideo[2].' OUTPUT: '.hwd_vs_ConverterTools::processOutput($ConvertVideo[3], " ");
					hwd_vs_ConverterTools::writeLog($file_contents);

					$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";

					$n = $n + 1;
					continue;
				} else {
					@chmod($path_new_flv, 0755);
					@chmod($path_new_mp4, 0755);
				}

			} else {

				$ConvertVideo = hwd_vs_ConvertVideo::convert($path_original, $path_new_flv, $filename_ext, $path_new_mp4, 1, 0);

				$output.= $ConvertVideo[6];

				if (intval($ConvertVideo[0]) !== 2) {
					hwd_vs_ConverterTools::reset($row, $filename_original, $path_new_flv, "queuedformp4");

					$file_contents = '['.date('Y-m-d H:i:s').'] [ Conversion Failed ] INPUT: '.$ConvertVideo[2].' OUTPUT: '.hwd_vs_ConverterTools::processOutput($ConvertVideo[3], " ");
					hwd_vs_ConverterTools::writeLog($file_contents);

					$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";

					$n = $n + 1;
					continue;
				} else {
					@chmod($path_new_flv, 0755);
					@chmod($path_new_mp4, 0755);
				}

				$output.= "<div class=\"box\"><div><h2>Copying MP4 Video File (File ".$n." of ".$count.")</h2></div>";
					if(copy($path_original, $path_new_mp4)){
						$output.= "<div class=\"success\">SUCCESS: Original video file copied to upload directory</div>";
					} else {
						$output.= "<div class=\"error\">ERROR: Could not copy the original video into the upload directory. Check directory permissions.</div>";
					}
				$output.= "</div>";

				if(!file_exists($path_new_mp4) || (filesize($path_new_mp4) == 0)){
					hwd_vs_ConverterTools::reset($row, $filename_original, $path_new_mp4, "queuedformp4");

					$file_contents = '['.date('Y-m-d H:i:s').'] [ Copy Failed ] LOCATION: '.$path_new_mp4;
					hwd_vs_ConverterTools::writeLog($file_contents);

					$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";

					$n = $n + 1;
					continue;

				}
				chmod($path_new_mp4, 0755);

			}

		   /******************************************
			* FLASH META DATA MANIPULATION (INSERT onMetaData TAG)
			**/
			$InjectMetaData = hwd_vs_InjectMetaData::inject($path_new_flv);

			$output.= $InjectMetaData[4];

			if ($InjectMetaData[0] == 0) {
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Meta Injection Failed ] INPUT: '.$InjectMetaData[2].' OUTPUT: '.$InjectMetaData[3];
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

		   /******************************************
			* GET VIDEO LENGTH
			**/
			$ExtractDuration = hwd_vs_ExtractDuration::extract($path_new_flv, hwd_vs_ConverterTools::processOutput($ConvertVideo[3]));

			$output.= $ExtractDuration[3];

			if ($ExtractDuration[1] == "0:00:01") {
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Duration Lookup Failed ]';
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

		   /******************************************
			* CREATE THUMBNAIL IMAGE
			**/
			if (file_exists($path_new_mp4) && filesize($path_new_mp4) > 0)
			{
				$path_video = $path_new_mp4;
			}
			else
			{
				$path_video = $path_new_flv;
			}
			$GenerateThumbnail = hwd_vs_GenerateThumbnail::draw($path_base, $path_video, $filename_noext, $filename_ext, $ExtractDuration[1], $ExtractDuration[0]);

			$output.= $GenerateThumbnail[9];

			if ($GenerateThumbnail[0] == 0) {
				// write to log
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Static Thumbnail Generation Failed ] INPUT: '.$GenerateThumbnail[2].' OUTPUT: '.$GenerateThumbnail[3];
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

			if ($GenerateThumbnail[1] == 0) {
				// write to log
				$file_contents = '['.date('Y-m-d H:i:s').'] [ Dynamic Thumbnail Generation Failed ] INPUT: '.$GenerateThumbnail[4].' OUTPUT: '.$GenerateThumbnail[5];
				hwd_vs_ConverterTools::writeLog($file_contents);
			}

			if($c->abortthumbfail == 1){
				if($GenerateThumbnail[0] == 0){
					hwd_vs_ConverterTools::reset($row, $filename_original, $path_video, "queuedformp4");

					$output.= "<div class=\"abortbox\"><h2>File ".$n." of ".$count." Aborted!</h2></div>";
					$n = $n + 1;
					continue;
				}
			}

		   /******************************************
			* UPDATE APPROVAL STATUS IN DATABASE
			**/

			if (!hwd_vs_ConverterTools::pingServer($connectServer)) {
			  $closeServer = hwd_vs_ConverterTools::closeServer($connectServer);
			  $connectServer = hwd_vs_ConverterTools::connectServer($row);
			  $connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
			}

		    $output.= hwd_vs_ConverterTools::finish($n, $count, $row, $filename_original, $filename_noext, $ExtractDuration[0], $ExtractDuration[1], $path_video, $path_original);
			$file_contents = '['.date('Y-m-d H:i:s').'] [ Successful MP4 Process ] Video ID: '.$result['id'];
			hwd_vs_ConverterTools::writeLog($file_contents);

			$n = $n + 1;

		}
	}
	/**
	* END WHILE
	**/

unset($selectBatch);
$selectBatch = hwd_vs_ConverterTools::selectBatch($row, "re-calculate_duration", $batch);
$count = mysql_num_rows($selectBatch);

	// check if any waiting videos
	if ($count == 0) {
		$output.= "<div class=\"box\"><h2>There are no videos waiting for duration re-calculation</h2><h3>Exiting...</h3></div>";
	} else {
	   /**
		* Process each video file
		**/
		$n = 1;
		while ($result = @mysql_fetch_array($selectBatch)) {

			$filename_noext = $result['video_id'];
			$id = intval($result['id']);

			hwd_vs_ConverterTools::set($row, null, $id);

			$output.= "<div class=\"box\"><div><h2>Re-calculating Duration (File ".$n." of ".$count.")</h2></div>";

				if ($result['video_type'] == "local" || $result['video_type'] == "mp4") {

					$path_new = $path_base . "/uploads/" . $filename_noext . ".flv";
					$ExtractDuration = hwd_vs_ExtractDuration::extract($path_new, '');


				} else if ($result['video_type'] == "swf") {

					$ExtractDuration[0] = "0:00:02";
					$ExtractDuration[1] = "0:00:01";

				} else {

					$ExtractDuration[0] = "0:00:02";
					$ExtractDuration[1] = "0:00:01";

				}

				if ($result['video_length'] == "0:00:02" || $ExtractDuration[0] !== "0:00:02")
				{
					$output.= "<div><b>ADDING NEW DURATION (".$ExtractDuration[0].")</b></div>";
					hwd_vs_ConverterTools::addDuration($row, $filename_noext, $ExtractDuration[0]);
					if ($result['thumb_snap'] == "0:00:00" || $result['thumb_snap'] == "0:00:01" || $result['thumb_snap'] == "0:00:02")
					{
						hwd_vs_ConverterTools::addThumbPosition($row, $filename_noext, $ExtractDuration[1]);
					}
				}

				$output.= "<div><b>RE-APPROVING</b></div>";
				$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET approved='yes' WHERE id = ".$result['id'];
				$db = @mysql_query($sqlpending);
				if (!$db) {
					@mysql_close($dbconnect);
					$connectServer = hwd_vs_ConverterTools::connectServer($row);
					$connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
					@mysql_query($sql) or die('error: ' . mysql_error());
				}

			$output.= "</div>";
		   /**
			* Finish
			**/
			$n = $n + 1;
			$output.= "</div>";
		}
	}
	/**
	* END WHILE
	**/

unset($selectBatch);
$selectBatch = hwd_vs_ConverterTools::selectBatch($row, "re-generate_thumb", $batch);
$count = mysql_num_rows($selectBatch);

	// check if any waiting videos
	if ($count == 0) {
		$output.= "<div class=\"box\"><h2>There are no videos waiting for thumbnail re-generation</h2><h3>Exiting...</h3></div>";
	} else {
	   /**
		* Process each video file
		**/
		$n = 1;
		while ($result = @mysql_fetch_array($selectBatch)) {
			$filename_noext = $result['video_id'];
			hwd_vs_ConverterTools::set($row, $filename_noext);
			$output.= "<div class=\"box\"><div><h2>Re-calculating Duration (File ".$n." of ".$count.")</h2></div>";

				if ($result['video_type'] == "local" || $result['video_type'] == "mp4") {

					$output.= "<div><b>TAKING NEW THUMBNAILS (".$result['thumb_snap'].")</b></div>";
					$path_new_flv = $path_base.DS.'uploads'.DS.$filename_noext.'.flv';
					$path_new_mp4 = $path_base.DS.'uploads'.DS.$filename_noext.'.mp4';
					if (file_exists($path_new_mp4) && filesize($path_new_mp4) > 0)
					{
						$path_video = $path_new_mp4;
					}
					else
					{
						$path_video = $path_new_flv;
					}
					$GenerateThumbnail = hwd_vs_GenerateThumbnail::draw($path_base, $path_video, $result['video_id'], "flv", $result['thumb_snap'], $result['video_length']);

				} else if ($result['video_type'] == "swf") {

					$GenerateThumbnail = '';

				} else {

					$GenerateThumbnail = '';

				}

				if ($GenerateThumbnail[0] == 0) {
				  $output.= "<div><b>STATIC FFMPEG INPUT</b></div>";
				  $output.= "<div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$GenerateThumbnail[2]."</textarea></div>";
				  $output.= "<div><b>STATIC FFMPEG OUTPUT</b></div>";
				  $output.= "<div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($GenerateThumbnail[3])."</textarea></div>";
				}
				if ($GenerateThumbnail[1] == 0) {
				  $output.= "<div><b>DYNAMIC FFMPEG INPUT</b></div>";
				  $output.= "<div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$GenerateThumbnail[4]."</textarea></div>";
				  $output.= "<div><b>DYNAMIC FFMPEG OUTPUT</b></div>";
				  $output.= "<div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($GenerateThumbnail[5])."</textarea></div>";
				}
				if ($GenerateThumbnail[6] == 0) {
				  $output.= "<div><b>LARGE FFMPEG INPUT</b></div>";
				  $output.= "<div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$GenerateThumbnail[7]."</textarea></div>";
				  $output.= "<div><b>LARGE FFMPEG OUTPUT</b></div>";
				  $output.= "<div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($GenerateThumbnail[8])."</textarea></div>";
				}

				$output.= "<div><b>RE-APPROVING</b></div>";
				$sql = "UPDATE ".$row->dbprefix."hwdvidsvideos SET approved='yes' WHERE id = ".$result['id'];
				$db = @mysql_query($sqlpending);
				if (!$db) {
					@mysql_close($dbconnect);
					$connectServer = hwd_vs_ConverterTools::connectServer($row);
					$connectDatabase = hwd_vs_ConverterTools::connectDatabase($row, $connectServer);
					@mysql_query($sql) or die('error: ' . mysql_error());
				}

			$output.= "</div>";
		   /**
			* Finish
			**/
			$n = $n + 1;
			$output.= "</div>";
		}
	}
	/**
	* END WHILE
	**/
	$output.= "</body></html>";
	$closeServer = hwd_vs_ConverterTools::closeServer($connectServer);

	$internal = intval(@$_REQUEST['internal']);
	if ($internal == 1) { print $output; }

?>
