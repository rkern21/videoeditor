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
class hwd_vs_InjectMetaData
{
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function inject($path_flv) {

		defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
		defined('CONVERTPATH') ? null : define('CONVERTPATH', dirname(__FILE__));

		if(substr(PHP_OS, 0, 3) == "WIN") {

			defined('JPATH_SITE') ? null : define('JPATH_SITE', str_replace("\components\com_hwdvideoshare\converters", "", CONVERTPATH) );

		} else {

			defined('JPATH_SITE') ? null : define('JPATH_SITE', str_replace("/components/com_hwdvideoshare/converters", "", CONVERTPATH) );

		}

		// get hwdVideoShare general settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
		$c = hwd_vs_Config::get_instance();

		// get hwdVideoShare server settings
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
		$s = hwd_vs_SConfig::get_instance();

		$result = array();

		if(substr(PHP_OS, 0, 3) != "WIN") {
			$cmd_input = $s->flvtool2path.' -U '.$path_flv;
		} else {
			$cmd_input = $s->flvtool2path.' -U "'.$path_flv.'"';
		}

		@exec("$sharedlib $cmd_input 2>&1", $cmd_output);

		$result[0] = 0;              // result of flvtool2 execution
		$result[1] = null;           // ?
		$result[2] = $cmd_input;     // input of flvtool2 execution
		$result[3] = $cmd_output;    // output of flvtool2 execution
		$result[4] = '';             // holder for output text

		if(empty($cmd_output)) {

			$result[0] = 1;

		}

		$result = hwd_vs_InjectMetaData::generateOutput($result);
		return $result;

	}
    /**
     * CONVERT VIDEOS TO FLV FORMAT
     * @param database A database connector object
     */
	function generateOutput($result) {

		$c          = hwd_vs_Config::get_instance();
		$output     = '';

		$output.= "<div class=\"box\"><div><h2>Injecting Meta Data</h2></div>";

		if ($result[0] == 1) {
			$output.= "<div class=\"success\">SUCCESS: Executed FLVTOOL2</div>";
		} else if ($result[0] == 0) {
			$output.= "<div class=\"error\">ERROR: Could Not Execute FLVTOOL2</div>";
		}

		$output.= "<div><b>FLVTOOL2 INPUT</b></div>
			  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".$result[2]."</textarea></div>
			  <div><b>FLVTOOL2 OUTPUT (Empty output generally indicates success)</b></div>
			  <div><textarea rows=\"3\" cols=\"50\" style=\"width:90%\">".hwd_vs_ConverterTools::processOutput($result[3])."</textarea></div>";
		$output.= "</textarea></div></div>";

		$result[4] = $output;
		return $result;

	}
}
?>