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
class hwd_vs_GenerateThumbnail
{
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function draw($path_base, $path_new, $filename_noext, $filename_ext, $thumb_position, $full_position = "0:00:00") {

		defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
		defined('CONVERTPATH') ? null : define('CONVERTPATH', dirname(__FILE__));
		$cmd_sinput = '';
		$cmd_soutput = '';
		$cmd_linput = '';
		$cmd_loutput = '';
		$cmd_dinput = '';
		$cmd_doutput = '';

		if(substr(PHP_OS, 0, 3) == "WIN") {

			defined('JPATH_SITE') ? null : define('JPATH_SITE', str_replace("\components\com_hwdvideoshare\converters", "", CONVERTPATH) );

		} else {

			defined('JPATH_SITE') ? null : define('JPATH_SITE', str_replace("/components/com_hwdvideoshare/converters", "", CONVERTPATH) );

		}

		// get joomla configuration
		include_once(JPATH_SITE.DS.'configuration.php');

		// get hwdVideoShare general settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
		$c = hwd_vs_Config::get_instance();

		// get hwdVideoShare server settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
		$s = hwd_vs_SConfig::get_instance();

		// shared library
		$sharedlib = null;
		if ($c->sharedlibrarypath !== "") {
			$sharedlib = "export LD_LIBRARY_PATH=$c->sharedlibrarypath;";
		}

		$path_sthumb = $path_base.DS.'thumbs'.DS.'temp_'.$filename_noext.'.jpg';
		$path_lthumb = $path_base.DS.'thumbs'.DS.'temp_l_'.$filename_noext.'.jpg';
		$path_dthumb = $path_base.DS.'thumbs'.DS.'temp_'.$filename_noext.'.gif';

		$path_sthumb_orig = $path_base.DS.'thumbs'.DS.$filename_noext.'.jpg';
		$path_lthumb_orig = $path_base.DS.'thumbs'.DS.'l_'.$filename_noext.'.jpg';
		$path_dthumb_orig = $path_base.DS.'thumbs'.DS.$filename_noext.'.gif';
		$path_seqthumb_orig = $path_base.DS.'thumbs'.DS.$filename_noext;

		$nthumbwidth = intval($c->con_thumb_n);
		$nwtype = gettype($nthumbwidth/2);
		if($nwtype !== "integer"){
			$nthumbwidth = intval($nthumbwidth+1);
		}
		$nthumbheight = intval($c->con_thumb_n*$c->tar_fb);
		$nhtype = gettype($nthumbheight/2);
		if($nhtype !== "integer"){
			$nthumbheight = intval($nthumbheight+1);
		}
		$lthumbwidth = intval($c->con_thumb_l);
		$lwtype = gettype($lthumbwidth/2);
		if($lwtype !== "integer"){
			$lthumbwidth = intval($lthumbwidth+1);
		}
		$lthumbheight = intval($c->con_thumb_l*$c->var_fb);
		$lttype = gettype($lthumbheight/2);
		if($lttype !== "integer"){
			$lthumbheight = intval($lthumbheight+1);
		}

		if(substr(PHP_OS, 0, 3) == "WIN") {

			$path_cmd_new      = '"'.$path_new.'"';
			$path_cmd_sthumb   = '"'.$path_sthumb.'"';
			$path_cmd_lthumb   = '"'.$path_lthumb.'"';
			$path_cmd_dthumb   = '"'.$path_dthumb.'"';
			$path_cmd_seqthumb = '"'.$path_seqthumb_orig.'"';

		} else {

			$path_cmd_new      = $path_new;
			$path_cmd_sthumb   = $path_sthumb;
			$path_cmd_lthumb   = $path_lthumb;
			$path_cmd_dthumb   = $path_dthumb;
			$path_cmd_seqthumb = $path_seqthumb_orig;

		}

		//Static
		clearstatcache();
		if ( @!file_exists($path_sthumb) || (@filesize($path_sthumb) == 0) ) {
			$cmd_sinput = "$s->ffmpegpath -y -i $path_cmd_new -ss $thumb_position -t 00:00:01 -s ".$nthumbwidth."x".$nthumbheight." -r 1 -f mjpeg $path_cmd_sthumb";
			@exec("$sharedlib $cmd_sinput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( @!file_exists($path_sthumb) || (@filesize($path_sthumb) == 0) ) {
			$cmd_sinput = "$s->ffmpegpath -y -i $path_cmd_new -ss $thumb_position -t 00:00:01 -s ".$nthumbwidth."x".$nthumbheight." -r 1 -f image2 $path_cmd_sthumb";
			@exec("$sharedlib $cmd_sinput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( !file_exists($path_sthumb) || (filesize($path_sthumb) == 0) ) {
			$cmd_sinput = "$s->ffmpegpath -ss $thumb_position -t 00:00:01 -i $path_cmd_new -an -r 1 -y -s ".$nthumbwidth."x".$nthumbheight." ".$path_cmd_sthumb."";
			@exec("$sharedlib $cmd_sinput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( !file_exists($path_sthumb) || (filesize($path_sthumb) == 0) ) {
			$thumb_position = "0:00:01";
			$cmd_sinput = "$s->ffmpegpath -y -i $path_cmd_new -ss $thumb_position -t 00:00:01 -s ".$nthumbwidth."x".$nthumbheight." -r 1 -f mjpeg $path_cmd_sthumb";
		    @exec("$sharedlib $cmd_sinput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( @!file_exists($path_sthumb) || (@filesize($path_sthumb) == 0) ) {
			$thumb_position = "0:00:01";
			$cmd_sinput = "$s->ffmpegpath -y -i $path_cmd_new -ss $thumb_position -t 00:00:01 -s ".$nthumbwidth."x".$nthumbheight." -r 1 -f image2 $path_cmd_sthumb";
			@exec("$sharedlib $cmd_sinput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( @!file_exists($path_sthumb) || (@filesize($path_sthumb) == 0) ) {
			$thumb_position = "0:00:01";
			$cmd_sinput = "$s->ffmpegpath -y -ss $thumb_position -t 00:00:01 -i $path_cmd_new -an -r 1 -y -s ".$nthumbwidth."x".$nthumbheight." ".$path_cmd_sthumb."";
			@exec("$sharedlib $cmd_sinput 2>&1", $cmd_soutput);
		}

		//Large
		clearstatcache();
		if ( @!file_exists($path_lthumb) || (@filesize($path_lthumb) == 0) ) {
			$cmd_linput = "$s->ffmpegpath -y -i $path_cmd_new -ss $thumb_position -t 00:00:01 -s ".$lthumbwidth."x".$lthumbheight." -r 1 -f mjpeg $path_cmd_lthumb";
			@exec("$sharedlib $cmd_linput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( @!file_exists($path_lthumb) || (@filesize($path_lthumb) == 0) ) {
			$cmd_linput = "$s->ffmpegpath -y -i $path_cmd_new -ss $thumb_position -t 00:00:01 -s ".$lthumbwidth."x".$lthumbheight." -r 1 -f image2 $path_cmd_lthumb";
			@exec("$sharedlib $cmd_linput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( !file_exists($path_lthumb) || (filesize($path_lthumb) == 0) ) {
			$cmd_linput = "$s->ffmpegpath -ss $thumb_position -t 00:00:01 -i $path_cmd_new -an -r 1 -y -s ".$lthumbwidth."x".$lthumbheight." ".$path_cmd_lthumb."";
			@exec("$sharedlib $cmd_linput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( !file_exists($path_lthumb) || (filesize($path_lthumb) == 0) ) {
			$thumb_position = "0:00:01";
			$cmd_linput = "$s->ffmpegpath -y -i $path_cmd_new -ss $thumb_position -t 00:00:01 -s ".$lthumbwidth."x".$lthumbheight." -r 1 -f mjpeg $path_cmd_lthumb";
		    @exec("$sharedlib $cmd_linput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( @!file_exists($path_lthumb) || (@filesize($path_lthumb) == 0) ) {
			$thumb_position = "0:00:01";
			$cmd_linput = "$s->ffmpegpath -y -i $path_cmd_new -ss $thumb_position -t 00:00:01 -s ".$lthumbwidth."x".$lthumbheight." -r 1 -f image2 $path_cmd_lthumb";
			@exec("$sharedlib $cmd_linput 2>&1", $cmd_soutput);
		}
		clearstatcache();
		if ( @!file_exists($path_lthumb) || (@filesize($path_lthumb) == 0) ) {
			$thumb_position = "0:00:01";
			$cmd_linput = "$s->ffmpegpath -y -ss $thumb_position -t 00:00:01 -i $path_cmd_new -an -r 1 -y -s ".$lthumbwidth."x".$lthumbheight." ".$path_cmd_lthumb."";
			@exec("$sharedlib $cmd_linput 2>&1", $cmd_soutput);
		}

		//Dynamic
		if ( @!file_exists($path_dthumb) || (@filesize($path_dthumb) == 0) )
		{
			if (function_exists('imagecreatefromjpeg'))
			{
				$cmd_dinput = "$s->ffmpegpath -i $path_cmd_new -an -r 0.2 -t 45 -y -s ".$nthumbwidth."x".$nthumbheight." ".$path_cmd_seqthumb."_%d.jpg";
				@exec("$sharedlib $cmd_dinput 2>&1", $cmd_doutput);

				include_once JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'GIFEncoder.class.php';

				$frames = null;
				$time = null;

				for($i=1;$i<9;$i++){

					if (file_exists($path_seqthumb_orig.'_'.$i.'.jpg')) {

						$imgname = $path_seqthumb_orig.'_'.$i.'.jpg';
						$im = @imagecreatefromjpeg($imgname); /* Attempt to open */
						imagegif($im, $path_seqthumb_orig.'_'.$i.'.gif');
						$frames[] = $path_seqthumb_orig.'_'.$i.'.gif';
						$time[] = 100;

					}

				}

				if (is_array($frames)) {

					$gif = new GIFEncoder    (
						$frames, // frames array
						$time, // elapsed time array
						0, // loops (0 = infinite)
						2, // disposal
						0, 0, 0, // rgb of transparency
						"url" // source type
					);

					$fh = fopen($path_dthumb, 'w') or die("can't open file");
					fwrite($fh, $gif->GetAnimation());
					fclose($fh);

					@imagedestroy($im);

				}

				for($i=1;$i<9;$i++){
					@unlink($path_seqthumb_orig.'_'.$i.'.gif');
					@unlink($path_seqthumb_orig.'_'.$i.'.jpg');
				}
			}
			else
			{
				$cmd_dinput = "Could not use image manupulation functions. Check the GD image library has been installed";
				$cmd_doutput = "";
			}
		}

		$result = array();
		$result[0] = 0;
		$result[1] = 0;
		$result[2] = $cmd_sinput;
		$result[3] = $cmd_soutput;
		$result[4] = $cmd_dinput;
		$result[5] = $cmd_doutput;
		$result[6] = 0;
		$result[7] = $cmd_linput;
		$result[8] = $cmd_loutput;

		if(file_exists($path_sthumb) && (filesize($path_sthumb) > 0)) {

			@unlink($path_sthumb_orig);
			if (!@rename($path_sthumb, $path_sthumb_orig)) {
				@copy($path_sthumb, $path_sthumb_orig);
			}

			if(file_exists($path_sthumb_orig) && (filesize($path_sthumb_orig) > 0)) {

				$result[0] = 1;
				@unlink($path_sthumb);

			}
		}
		if(file_exists($path_dthumb) && (filesize($path_dthumb) > 0)) {

			@unlink($path_dthumb_orig);
			if (!@rename($path_dthumb, $path_dthumb_orig)) {
				@copy($path_dthumb, $path_dthumb_orig);
			}

			if(file_exists($path_dthumb_orig) && (filesize($path_dthumb_orig) > 0)) {

				$result[1] = 1;
				@unlink($path_dthumb);

			}
		}
		if(file_exists($path_lthumb) && (filesize($path_lthumb) > 0)) {

			@unlink($path_lthumb_orig);
			if (!@rename($path_lthumb, $path_lthumb_orig)) {
				@copy($path_lthumb, $path_lthumb_orig);
			}

			if(file_exists($path_lthumb_orig) && (filesize($path_lthumb_orig) > 0)) {

				$result[6] = 1;
				@unlink($path_lthumb);

			}
		}

		$result = hwd_vs_GenerateThumbnail::generateOutput($result);
		return $result;

	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function generateOutput($result) {

		$c          = hwd_vs_Config::get_instance();
		$output     = '';

		$output.= "<div class=\"box\"><div><h2>Generating Static Thumbnail</h2></div>";
		if ($result[0] == 0) {
			$output.= "<div class=\"error\">ERROR: Thumbnail image could not be generated.</div>";
		} else if ($result[0] == 1) {
			$output.= "<div class=\"success\">SUCCESS: Thumbnail image successfully generated.</div>";
		}

		$output.= "<div><b>FFMPEG INPUT</b></div>
			  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$result[2]."</textarea></div>
			  <div><b>FFMPEG OUTPUT</b></div>
			  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($result[3])."</textarea></div>";
		$output.= "</textarea></div></div>";


		$output.= "<div class=\"box\"><div><h2>Generating Dynamic Thumbnail</h2></div>";
		if ($result[1] == 0) {
			$output.= "<div class=\"error\">ERROR: Thumbnail image could not be generated.</div>";
		} else if ($result[1] == 1) {
			$output.= "<div class=\"success\">SUCCESS: Thumbnail image successfully generated.</div>";
		}

		$output.= "<div><b>FFMPEG INPUT</b></div>
			  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$result[4]."</textarea></div>
			  <div><b>FFMPEG OUTPUT</b></div>
			  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($result[5])."</textarea></div>";
		$output.= "</textarea></div></div>";

		$result[9] = $output;
		return $result;

	}
}
?>
