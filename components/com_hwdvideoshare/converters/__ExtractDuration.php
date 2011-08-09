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
class hwd_vs_ExtractDuration
{
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function extract($path_new, $output)
	{
		defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
		defined('CONVERTPATH') ? null : define('CONVERTPATH', dirname(__FILE__));

		if(substr(PHP_OS, 0, 3) == "WIN")
		{
			defined('JPATH_SITE') ? null : define('JPATH_SITE', str_replace("\components\com_hwdvideoshare\converters", "", CONVERTPATH) );
		}
		else
		{
			defined('JPATH_SITE') ? null : define('JPATH_SITE', str_replace("/components/com_hwdvideoshare/converters", "", CONVERTPATH) );
		}

		// get hwdVideoShare general settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
		$c = hwd_vs_Config::get_instance();

		// get hwdVideoShare server settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
		$s = hwd_vs_SConfig::get_instance();

		$result = array();
		$full_sec = '';
		$half_sec = '';

		$extension = "ffmpeg";
		$extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
		$extension_fullname = PHP_EXTENSION_DIR . "/" . $extension_soname;

		// Try to load extension
		// If extension is not loaded, don't try! Instead, grep for duration from shell output.

		if(extension_loaded($extension))
		{
			$video_info = @new ffmpeg_movie($path_new); //duration of new flv file.
			if ($video_info)
			{
				$full_sec = $video_info->getDuration(); // Gets the duration in secs.
			}
		}

		if(empty($full_sec) && $c->encoder == "MENCODER" && !empty($output))
		{
			if (preg_match('/Video stream:.*bytes..(.*?).sec/', $output, $regs))
			{
				$full_sec = $regs[1];
			}
		}

		if(empty($full_sec))
		{
			$cmd_input_ffmpeg = "$s->ffmpegpath -i $path_new";
			@exec("$sharedlib $cmd_input_ffmpeg 2>&1", $cmd_output_ffmpeg);
			$cmd_output_ffmpeg = implode($cmd_output_ffmpeg);

			if (@preg_match('/Duration:.(.*?),.start/', $cmd_output_ffmpeg, $regs))
			{
				$full_sec = hwd_vs_ConverterTools::hms2sec($regs[1]);
			}
		}

		if ($full_sec == "" || !is_numeric($full_sec)) {
			$full_sec = 2;
		}

		//get the middle of the movie (time; 00:00:00 format) for thumbnail
		$half_sec = $full_sec / 2;
		$half_sec = @round($half_sec);

		$result    = array();
		$result[0] = hwd_vs_ConverterTools::sec2hms($full_sec); // result of full duration
		$result[1] = hwd_vs_ConverterTools::sec2hms($half_sec); // result of mid-point duration
		$result[2] = 0;                                         // status of ffmpeg-php extension
		$result[3] = '';                                        // holder for output text

		if(extension_loaded($extension)) {
		    $result[2] = 1;
		}

		$result = hwd_vs_ExtractDuration::generateOutput($result);
		return $result;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function generateOutput($result) {

		$c          = hwd_vs_Config::get_instance();
		$output     = '';

		$output.= "<div class=\"box\"><div><h2>Retriving Video Length</h2></div>";

		if ($result[2] == "0") {
			$output.= "<div>Could not load ffmpeg-php extension. Using fallback method.</div>";
		}
		if ($result[1] !== "0:00:01") {
			$output.= "<div class=\"success\">SUCCESS: Duration is ".$result[0].". Thumbnail image will be taken at ".$result[1]."</div>";
		} else {
			$output.= "<div class=\"error\">ERROR: Could not determine video duration</div>";
		}
		$output.= "</div>";

		$result[3] = $output;
		return $result;

	}
}
?>