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
class hwd_vs_MoovAtom
{
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function move($path_mp4) {

		defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
		defined('CONVERTPATH') ? null : define('CONVERTPATH', dirname(__FILE__));

		if(substr(PHP_OS, 0, 3) == "WIN") {

			defined('JPATH_SITE') ? null : define('JPATH_SITE', str_replace("\components\com_hwdvideoshare\converters", "", CONVERTPATH) );

		} else {

			defined('JPATH_SITE') ? null : define('JPATH_SITE', str_replace("/components/com_hwdvideoshare/converters", "", CONVERTPATH) );

		}

		// get hwdVideoShare server settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
		$s = hwd_vs_SConfig::get_instance();

		$path_mp4_temp = $path_mp4.".temp";

		if(substr(PHP_OS, 0, 3) == "WIN")
		{
			$path_cmd_mp4 = '"'.$path_mp4.'"';
			$path_cmd_mp4_temp  = '"'.$path_mp4_temp.'"';
		}
		else
		{
			$path_cmd_mp4 = $path_mp4;
			$path_cmd_mp4_temp  = $path_mp4_temp;
		}

		$cmd_input = "$s->qtfaststart $path_cmd_mp4 $path_cmd_mp4_temp";
		@exec("$sharedlib $cmd_input 2>&1", $cmd_output);

		$result = array();
		$result[0] = 0;
		$result[1] = $cmd_input;
		$result[2] = $cmd_output;

		if(file_exists($path_mp4_temp) && (filesize($path_mp4_temp) > 0)) {

			@unlink($path_mp4);
			@rename($path_mp4_temp, $path_mp4);

			if(file_exists($path_mp4) && (filesize($path_mp4) > 0)) {

				@unlink($path_mp4_temp);

			}

			$check_string = implode(",", $cmd_output);

			if (strpos($check_string, "writing moov atom") === false) {

				$result[0] = 0;

			} else {

				$result[0] = 1;

			}

		}

		$result = hwd_vs_MoovAtom::generateOutput($result);
		return $result;

	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function generateOutput($result) {

		$c          = hwd_vs_Config::get_instance();
		$output     = '';

		$output.= "<div class=\"box\"><div><h2>Moving Moov Atom</h2></div>";

		if ($result[0] == 1) {
			$output.= "<div class=\"success\">SUCCESS: GT-QUICKSTART moved the Moov Atom</div>";
		} else if ($result[0] == 0) {
			$output.= "<div class=\"error\">ERROR: GT-QUICKSTART did not move the Moov Atom</div>";
		}

		$output.= "<div><b>GT-QUICKSTART INPUT</b></div>
			  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$result[1]."</textarea></div>
			  <div><b>GT-QUICKSTART OUTPUT</b></div>
			  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($result[2])."</textarea></div>";
		$output.= "</textarea></div></div>";

		$result[3] = $output;
		return $result;

	}
}
?>