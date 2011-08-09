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
 */
class hwd_vs_ConvertVideo
{
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function convert($path_original, $path_new_flv, $filename_ext, $path_new_mp4, $gen_flv=1, $gen_mp4=1)
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

		// get joomla configuration
		include_once(JPATH_SITE.DS.'configuration.php');

		// get hwdVideoShare general settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
		$c = hwd_vs_Config::get_instance();

		// get hwdVideoShare server settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
		$s = hwd_vs_SConfig::get_instance();

		$wmvfix = null;
		//if ($filename_ext == "wmv")
		//{
			if ($c->applywmvfix == "1")
			{
				$wmvfix = ",harddup -ofps 25";
			}
		//}

		// shared library
		$sharedlib = null;
		if ($c->sharedlibrarypath !== "")
		{
			$sharedlib = "export LD_LIBRARY_PATH=$c->sharedlibrarypath;";
		}

		if ($c->cnvt_fsize == "0")
		{
			$ffmpeg_size = "";
			$mencoder_size = "";
		}
		else
		{
			if ($c->keep_ar == "1")
			{
				$calculatedAspect = hwd_vs_ConvertVideo::generateCalculatedAspect($path_original);
			}

			if (isset($calculatedAspect) && $calculatedAspect > 0)
			{
				$cnvt_fsize = explode("x", $c->cnvt_fsize);
				$width = $cnvt_fsize[0];
				$height = intval($cnvt_fsize[0]/$calculatedAspect);
				if ($height%2)
				 $height = $height+1;
				$mencoder_size = "-vf scale=".$width.":".$height."$wmvfix";
				$ffmpeg_size = "-s ".$width."x".$height;
			}
			else
			{
				$fsize = str_replace("x", ":", $c->cnvt_fsize);
				$mencoder_size = "-vf scale=$fsize$wmvfix";
				$ffmpeg_size = "-s $c->cnvt_fsize";
			}
		}

        $c->customencode = stripslashes($c->customencode);

		$o_path_new_mp4  = $path_new_mp4;

		if(substr(PHP_OS, 0, 3) == "WIN")
		{
			$path_original = '"'.$path_original.'"';
			$path_new_flv  = '"'.$path_new_flv.'"';
			$path_new_mp4  = '"'.$path_new_mp4.'"';
		}

		$men_keyframes = ":keyint=".$c->cnvt_keyf*25;
		$ffm_keyframes = $c->cnvt_keyf*25;

		if ($gen_flv == 1)
		{
			if ($c->encoder == "MENCODER")
			{
				if(!file_exists($path_new_flv) || (filesize($path_new_flv) == 0))
				{
					$cmd_input_flv = "$s->mencoderpath $path_original -o $path_new_flv -of lavf -oac mp3lame -lameopts abr:br=$c->cnvt_abitrate -ovc lavc -lavcopts vcodec=flv:vbitrate=$c->cnvt_vbitrate:mbd=2:mv0:trell:v4mv:cbp:last_pred=3$men_keyframes $mencoder_size -srate $c->cnvt_asr $c->customencode";
					@exec("$sharedlib $cmd_input_flv 2>&1", $cmd_output_flv);
				}
				if(!file_exists($path_new_flv) || (filesize($path_new_flv) == 0))
				{
					$cmd_input_flv = "$s->mencoderpath $path_original -o $path_new_flv -of lavf -oac mp3lame -lameopts abr:br=$c->cnvt_abitrate -ovc lavc -lavcopts vcodec=flv:vbitrate=$c->cnvt_vbitrate:mbd=2:mv0:trell:v4mv:cbp:last_pred=3$men_keyframes -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames $mencoder_size -srate $c->cnvt_asr $c->customencode";
					@exec("$sharedlib $cmd_input_flv 2>&1", $cmd_output_flv);
				}
				if(!file_exists($path_new_flv) || (filesize($path_new_flv) == 0))
				{
					$cmd_input_flv = "$s->ffmpegpath -y -i $path_original -ab $c->cnvt_abitrate*1000 -ar $c->cnvt_asr -b $c->cnvt_vbitrate*1000 $ffmpeg_size -g $ffm_keyframes -keyint_min 25 $c->customencode $path_new_flv";
					@exec("$sharedlib $cmd_input_flv 2>&1", $cmd_output_flv);
				}
			}
			else if ($c->encoder == "FFMPEG")
			{
				if(!file_exists($path_new_flv) || (filesize($path_new_flv) == 0))
				{
					$cmd_input_flv = "$s->ffmpegpath -y -i $path_original -ab $c->cnvt_abitrate*1000 -ar $c->cnvt_asr -b $c->cnvt_vbitrate*1000 $ffmpeg_size -g $ffm_keyframes -keyint_min 25 $c->customencode $path_new_flv";
					@exec("$sharedlib $cmd_input_flv 2>&1", $cmd_output_flv);
				}
				if(!file_exists($path_new_flv) || (filesize($path_new_flv) == 0))
				{
					$cmd_input_flv = "$s->mencoderpath $path_original -o $path_new_flv -of lavf -oac mp3lame -lameopts abr:br=$c->cnvt_abitrate -ovc lavc -lavcopts vcodec=flv:vbitrate=$c->cnvt_vbitrate:mbd=2:mv0:trell:v4mv:cbp:last_pred=3$men_keyframes -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames $mencoder_size -srate $c->cnvt_asr $c->customencode";
					@exec("$sharedlib $cmd_input_flv 2>&1", $cmd_output_flv);
				}
				if(!file_exists($path_new_flv) || (filesize($path_new_flv) == 0))
				{
					$cmd_input_flv = "$s->mencoderpath $path_original -o $path_new_flv -of lavf -oac mp3lame -lameopts abr:br=$c->cnvt_abitrate -ovc lavc -lavcopts vcodec=flv:vbitrate=$c->cnvt_vbitrate:mbd=2:mv0:trell:v4mv:cbp:last_pred=3$men_keyframes $mencoder_size -srate $c->cnvt_asr $c->customencode";
					@exec("$sharedlib $cmd_input_flv 2>&1", $cmd_output_flv);
				}
			}
		}
		else
		{
			$cmd_input_flv = '';
			$cmd_output_flv = '';
		}

		$cmd_faststart_output = '';
		if ($gen_mp4 == 1 && $c->uselibx264 == 1)
		{
			$crf = "20";
			$ab = "128k";
			$b = "500k";
			$bt = "500k";
			$threads = "0";

			if ($c->cnvt_fsize_hd == "0")
			{
				$ffmpeg_size = "";
			}
			else
			{
				if ($c->keep_ar == "1")
				{
					$calculatedAspect = hwd_vs_ConvertVideo::generateCalculatedAspect($path_original);
				}

				if (isset($calculatedAspect) && $calculatedAspect > 0)
				{
					$cnvt_fsize_hd = explode("x", $c->cnvt_fsize_hd);
					$width = $cnvt_fsize_hd[0];
					$height = intval($cnvt_fsize_hd[0]/$calculatedAspect);
					if ($height%2)
					 $height = $height+1;
					$ffmpeg_size = "-s ".$width."x".$height;
				}
				else
				{
					$ffmpeg_size = "-s $c->cnvt_fsize_hd";
				}
			}

			$support_flag_rc_lookahead = false;
			$support_flag_aq_mode = false;

			switch ($c->cnvt_hd_preset)
			{
				case "0":
					// custom double
					$pass = "double";
					$vpre1 = "-flags +loop -cmp +chroma -partitions 0 -me_method epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g $ffm_keyframes -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1";
					$vpre2 = "-flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me_method umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g $ffm_keyframes -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9";
					break;
				case "1":
					// default
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 3 -directpred 1 -trellis 1 -flags2 +mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					$vpre2 = "";
					break;
				case "2":
					// very slow
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4+partb8x8 -me_method umh -subq 10 -me_range 24 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 8 -refs 16 -directpred 3 -trellis 2 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 60";
					}
					$vpre2 = "";
					break;
				case "3":
					// slower
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4+partb8x8 -me_method umh -subq 9 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 8 -directpred 3 -trellis 2 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 60";
					}
					$vpre2 = "";
					break;
				case "4":
					// slow
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method umh -subq 8 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 5 -directpred 3 -trellis 1 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 50";
					}
					$vpre2 = "";
					break;
				case "5":
					// medium
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 3 -directpred 1 -trellis 1 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					$vpre2 = "";
					break;
				case "6":
					// fast
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 6 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 2 -directpred 1 -trellis 1 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 30";
					}
					$vpre2 = "";
					break;
				case "7":
					// faster
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 4 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 2 -directpred 1 -trellis 1 -flags2 +bpyramid-mixed_refs+wpred+dct8x8+fastpskip -wpredp 1";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 20";
					}
					$vpre2 = "";
					break;
				case "8":
					// very fast
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 2 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred+dct8x8+fastpskip-mbtree -wpredp 0";
					$vpre2 = "";
					break;
				case "9":
					// super fast
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4-partp8x8-partb8x8 -me_method dia -subq 1 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred+dct8x8+fastpskip-mbtree -wpredp 0";
					$vpre2 = "";
					break;
				case "10":
					// ultra fast
					$pass = "single";
					$vpre1 = "-coder 0 -flags -loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 0 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 0 -i_qfactor 0.71 -b_strategy 0 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 0 -refs 1 -directpred 1 -trellis 0 -flags2 -bpyramid-mixed_refs-wpred-dct8x8+fastpskip-mbtree -wpredp 0";
					if ($support_flag_aq_mode)
					{
						$vpre1.= " -aq_mode 0";
					}
					$vpre2 = "";
					break;
				case "11":
					// placebo
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4+partb8x8 -me_method tesa -subq 10 -me_range 24 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 16 -refs 16 -directpred 3 -trellis 2 -flags2 +bpyramid+mixed_refs+wpred+dct8x8-fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 60";
					}
					$vpre2 = "";
					break;
				case "12":
					// lossless max
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4-partb8x8 -me_method esa -subq 8 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -refs 16 -directpred 1 -flags2 +mixed_refs+dct8x8+fastpskip -cqp 0 -wpredp 2";
					$vpre2 = "";
					break;
				case "13":
					// lossless slow
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4-partb8x8 -me_method umh -subq 6 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -refs 2 -directpred 1 -flags2 +dct8x8+fastpskip -cqp 0 -wpredp 2";
					$vpre2 = "";
					break;
				case "14":
					// lossless slower
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4-partb8x8 -me_method umh -subq 8 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -refs 4 -directpred 1 -flags2 +mixed_refs+dct8x8+fastpskip -cqp 0 -wpredp 2";
					$vpre2 = "";
					break;
				case "15":
					// lossless medium
					$pass = "single";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8+parti4x4+partp8x8+partp4x4-partb8x8 -me_method hex -subq 5 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -directpred 1 -flags2 +fastpskip -cqp 0 -wpredp 2";
					$vpre2 = "";
					break;
				case "16":
					// lossless fast
					$pass = "single";
					$vpre1 = "-coder 0 -flags +loop -cmp +chroma -partitions -parti8x8+parti4x4+partp8x8-partp4x4-partb8x8 -me_method hex -subq 3 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -directpred 1 -flags2 +fastpskip -cqp 0 -wpredp 0";
					$vpre2 = "";
					break;
				case "17":
					// lossless ultra fast
					$pass = "single";
					$vpre1 = "-coder 0 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partp4x4-partb8x8 -me_method dia -subq 0 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -directpred 1 -flags2 +fastpskip -cqp 0";
					$vpre2 = "";
					break;
				case "20":
					// very slow
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 2 -me_range 24 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 8 -refs 1 -directpred 3 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred-dct8x8+fastpskip -wpredp 2";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4+partb8x8 -me_method umh -subq 10 -me_range 24 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 8 -refs 16 -directpred 3 -trellis 2 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 60";
						$vpre2.= " -rc_lookahead 60";
					}
					break;
				case "21":
					// slower
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 2 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 3 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred-dct8x8+fastpskip -wpredp 2";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4+partb8x8 -me_method umh -subq 9 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 8 -directpred 3 -trellis 2 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 60";
						$vpre2.= " -rc_lookahead 60";
					}
					break;
				case "22":
					// slow
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 2 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 3 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred-dct8x8+fastpskip -wpredp 2";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method umh -subq 8 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 5 -directpred 3 -trellis 1 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 50";
						$vpre2.= " -rc_lookahead 50";
					}
					break;
				case "23":
					// medium
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 2 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred-dct8x8+fastpskip -wpredp 2";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 3 -directpred 1 -trellis 1 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					break;
				case "24":
					// fast
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 2 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred-dct8x8+fastpskip -wpredp 2";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 6 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 2 -directpred 1 -trellis 1 -flags2 +bpyramid+mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 30";
						$vpre2.= " -rc_lookahead 30";
					}
					break;
				case "25":
					// faster
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 2 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred-dct8x8+fastpskip -wpredp 1";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 4 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 2 -directpred 1 -trellis 1 -flags2 +bpyramid-mixed_refs+wpred+dct8x8+fastpskip -wpredp 1";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 20";
						$vpre2.= " -rc_lookahead 20";
					}
					break;
				case "26":
					// very fast
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 2 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred-dct8x8+fastpskip-mbtree -wpredp 0";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 2 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred+dct8x8+fastpskip-mbtree -wpredp 0";
					break;
				case "27":
					// super fast
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 1 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred-dct8x8+fastpskip-mbtree -wpredp 0";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4-partp8x8-partb8x8 -me_method dia -subq 1 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid-mixed_refs+wpred+dct8x8+fastpskip-mbtree -wpredp 0";
					break;
				case "28":
					// ultra fast
					$pass = "double";
					$vpre1 = "-coder 0 -flags -loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 0 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 0 -i_qfactor 0.71 -b_strategy 0 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 0 -refs 1 -directpred 1 -trellis 0 -flags2 -bpyramid-mixed_refs-wpred-dct8x8+fastpskip-mbtree -wpredp 0";
					$vpre2 = "-coder 0 -flags -loop -cmp +chroma -partitions -parti8x8-parti4x4-partp8x8-partb8x8 -me_method dia -subq 0 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 0 -i_qfactor 0.71 -b_strategy 0 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 0 -refs 1 -directpred 1 -trellis 0 -flags2 -bpyramid-mixed_refs-wpred-dct8x8+fastpskip-mbtree -wpredp 0";
					if ($support_flag_aq_mode)
					{
						$vpre1.= " -aq_mode 0";
						$vpre2.= " -aq_mode 0";
					}
					break;
				case "29":
					// placebo
					$pass = "double";
					$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4+partb8x8 -me_method tesa -subq 10 -me_range 24 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 16 -refs 16 -directpred 3 -trellis 2 -flags2 +bpyramid+mixed_refs+wpred+dct8x8-fastpskip -wpredp 2";
					$vpre2 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partp4x4+partb8x8 -me_method tesa -subq 10 -me_range 24 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 2 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 16 -refs 16 -directpred 3 -trellis 2 -flags2 +bpyramid+mixed_refs+wpred+dct8x8-fastpskip -wpredp 2";
					if ($support_flag_rc_lookahead)
					{
						$vpre1.= " -rc_lookahead 60";
						$vpre2.= " -rc_lookahead 60";
					}
					break;
				case "30":
					// baseline
					$pass = "single";
					$vpre1 = "";
					$vpre2 = "";
					break;
				case "31":
					// main
					$pass = "single";
					$vpre1 = "";
					$vpre2 = "";
					break;
			}

			if ($pass == "double")
			{
				$cmd_input_mp4 = "$s->ffmpegpath -y -i $path_original $ffmpeg_size -an -pass 1 -vcodec libx264 $vpre1 -b $b -bt $bt -threads $threads $path_new_mp4";
				@exec("$sharedlib $cmd_input_mp4 2>&1", $cmd_output_mp4_p1);

				$cmd_input_mp4 = "$s->ffmpegpath -y -i $path_original $ffmpeg_size -acodec libfaac -ab $ab -pass 2 -vcodec libx264 $vpre2 -b $b -bt $bt -threads $threads $path_new_mp4";
				@exec("$sharedlib $cmd_input_mp4 2>&1", $cmd_output_mp4_p2);

				$cmd_output_mp4 = array_merge((array)$cmd_output_mp4_p1, (array)$cmd_output_mp4_p2);
			}
			else
			{
				$cmd_input_mp4 = "$s->ffmpegpath -y -i $path_original $ffmpeg_size -acodec libfaac -vcodec libx264 -ab $ab $vpre1 -crf $crf -threads $threads $path_new_mp4";
				@exec("$sharedlib $cmd_input_mp4 2>&1", $cmd_output_mp4);
			}

			if ($c->ipod320 == "on")
			{
				$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 3 -directpred 1 -trellis 1 -flags2 +mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
				$info = pathinfo($path_new_mp4);
				$file_name = basename($path_new_mp4,'.'.$info['extension']);
				$path_new_ipod320 = JPATH_SITE.DS."hwdvideos".DS."uploads".DS.$file_name.".ipod320.mp4";
				$cmd_input_ipod320 = "$s->ffmpegpath -y -i $path_original -s 320x240 -r 30000/1001 -b 200k -bt 240k -vcodec libx264 $vpre1 -coder 0 -bf 0 -flags2 -wpred-dct8x8 -level 13 -maxrate 768k -bufsize 3M -acodec libfaac -ac 2 -ar 48000 -ab 192k $path_new_ipod320";
				@exec("$sharedlib $cmd_input_ipod320 2>&1", $cmd_output_mp4);
			}

			if ($c->ipod640 == "on")
			{
				$vpre1 = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 3 -directpred 1 -trellis 1 -flags2 +mixed_refs+wpred+dct8x8+fastpskip -wpredp 2";
				$info = pathinfo($path_new_mp4);
				$file_name = basename($path_new_mp4,'.'.$info['extension']);
				$path_new_ipod640 = JPATH_SITE.DS."hwdvideos".DS."uploads".DS.$file_name.".ipod640.mp4";
				$cmd_input_ipod640 = "$s->ffmpegpath -y -i $path_original -s 640x480 -r 30000/1001 -b 200k -bt 240k -vcodec libx264 $vpre1 -coder 0 -bf 0 -refs 1 -flags2 -wpred-dct8x8 -level 30 -maxrate 10M -bufsize 10M -acodec libfaac -ac 2 -ar 48000 -ab 192k $path_new_ipod640";
				@exec("$sharedlib $cmd_input_ipod640 2>&1", $cmd_output_mp4);
			}

			if (file_exists($o_path_new_mp4))
			{
				$cmd_faststart_output = hwd_vs_MoovAtom::move($o_path_new_mp4);
			}
		}
		else
		{
			$cmd_input_mp4 = '';
			$cmd_output_mp4 = '';
		}

		$result = array();
		$result[0] = 0;                         // result of flv conversion [0 = fail, 1 = fail, 2 = success]
		$result[1] = 0;                         // result of mp4 conversion [0 = fail, 1 = fail, 2 = success]
		$result[2] = $cmd_input_flv;            // input of flv conversion
		$result[3] = $cmd_output_flv;           // output of flv conversion
		$result[4] = $cmd_input_mp4;            // input of mp4 conversion
		$result[5] = $cmd_output_mp4;           // output of mp4 conversion
		$result[6] = '';                        // holder for output text
		$result[7] = $cmd_faststart_output;     // holder for output text

		if(substr(PHP_OS, 0, 3) == "WIN")
		{
			$path_original = str_replace('"', '', $path_original);;
			$path_new_flv  = str_replace('"', '', $path_new_flv);;
			$path_new_mp4  = str_replace('"', '', $path_new_mp4);;
		}

		@list($filename_noext, $filename_ext) = @split('\.', basename($path_new_flv));
		$path_new_flv  = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.$filename_noext.'.flv';

		if(!file_exists($path_new_flv))
		{
			$result[0] = 0;
		}
		else if(filesize($path_new_flv) == 0)
		{
			$result[0] = 1;
		}
		else if(file_exists($path_new_flv) && (filesize($path_new_flv) > 0))
		{
			$result[0] = 2;
		}

		if(!file_exists($path_new_mp4))
		{
			$result[1] = 0;
		}
		else if(filesize($path_new_mp4) == 0)
		{
			$result[1] = 1;
		}
		else if(file_exists($path_new_mp4) && (filesize($path_new_mp4) > 0))
		{
			$result[1] = 2;
		}

		$result = hwd_vs_ConvertVideo::generateOutput($result, $gen_flv, $gen_mp4);
		return $result;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function generateOutput($result, $gen_flv, $gen_mp4)
	{
		$c          = hwd_vs_Config::get_instance();
		$output     = '';

		if ($gen_flv == 1)
		{
			$output.= "<div class=\"box\"><div><h2>Converting FLV Video</h2></div>";
			if ($result[0] == 0) {
				$output.= "<div class=\"error\">ERROR: Problem with ".$c->encoder." - No Videos converted.</div>";
			} else if ($result[0] == 1) {
				$output.= "<div class=\"error\">ERROR: Problem with ".$c->encoder." - Output video has zero filesize.</div>";
			} else if ($result[0] == 2) {
				$output.= "<div class=\"success\">SUCCESS: FLV File Created</div>";
			}

			$output.= "<div><b>".$c->encoder." INPUT</b></div>
				  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$result[2]."</textarea></div>
				  <div><b>".$c->encoder." OUTPUT</b></div>
				  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($result[3])."</textarea></div>";
			$output.= "</textarea></div></div>";
		}

		if ($gen_mp4 == 1)
		{
			$output.= "<div class=\"box\"><div><h2>Converting MP4 Video</h2></div>";
			if ($result[1] == 0) {
				$output.= "<div class=\"error\">ERROR: Problem with ".$c->encoder." - No Videos converted.</div>";
			} else if ($result[1] == 1) {
				$output.= "<div class=\"error\">ERROR: Problem with ".$c->encoder." - Output video has zero filesize.</div>";
			} else if ($result[1] == 2) {
				$output.= "<div class=\"success\">SUCCESS: MP4 File Created</div>";
			}

			$output.= "<div><b>".$c->encoder." INPUT</b></div>
				  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$result[4]."</textarea></div>
				  <div><b>".$c->encoder." OUTPUT</b></div>
				  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($result[5])."</textarea></div>";
			$output.= "</textarea></div></div>";
		}

		$output.= @$result[7][3];

		$result[6] = $output;
		return $result;
	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function generateCalculatedAspect($path_original)
	{
		$c = hwd_vs_Config::get_instance();
		$s = hwd_vs_SConfig::get_instance();

		$cmd_input_info = "$s->ffmpegpath -i $path_original";
		@exec("$sharedlib $cmd_input_info 2>&1", $cmd_output_info);

		$info_data = implode($cmd_output_info);
		preg_match('/DAR ([^\]]+)/', $info_data, $match);
		if (isset($match[1]))
		{
			$dar_elements = explode(":", $match[1]);
			$calculatedAspect = intval($dar_elements[0])/intval($dar_elements[1]);
			return $calculatedAspect;
		}
		else
		{
			return false;
		}
	}
}
?>