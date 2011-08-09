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

global $hwdvsItemid;
$doc = & JFactory::getDocument();

//******************************************************************************************************
//   Set allowed video formats
//******************************************************************************************************

if ($c->requiredins == 1)
{
	$allowed_formats = "";
	if ($c->ft_mpg == "on") {$allowed_formats .= "mpg|";}
	if ($c->ft_mpeg == "on") {$allowed_formats .= "mpeg|";}
	if ($c->ft_avi == "on") {$allowed_formats .=  "avi|";}
	if ($c->ft_divx == "on") {$allowed_formats .=  "divx|";}
	if ($c->ft_mp4 == "on") {$allowed_formats .=  "mp4|";}
	if ($c->ft_flv == "on") {$allowed_formats .=  "flv|";}
	if ($c->ft_wmv == "on") {$allowed_formats .=  "wmv|";}
	if ($c->ft_rm == "on") {$allowed_formats .=  "rm|";}
	if ($c->ft_mov == "on") {$allowed_formats .=  "mov|";}
	if ($c->ft_moov == "on") {$allowed_formats .=  "moov|";}
	if ($c->ft_asf == "on") {$allowed_formats .=  "asf|";}
	if ($c->ft_swf == "on") {$allowed_formats .=  "swf|";}
	if ($c->ft_vob == "on") {$allowed_formats .=  "vob|";}

	$oformats = explode(",", $c->oformats);
	for ($i = 0, $n = count($oformats); $i < $n; $i++)
	{
		$oformat = $oformats[$i];
		$oformat = preg_replace("/[^a-zA-Z0-9s]/", "", $oformat);
		$allowed_formats .=  $oformat."|";
	}
}
else
{
	$allowed_formats = "";
	if ($c->ft_mp4 == "on") {$allowed_formats .=  "mp4|";}
	if ($c->ft_flv == "on") {$allowed_formats .=  "flv|";}
	if ($c->ft_swf == "on") {$allowed_formats .=  "swf|";}
}
if (substr($allowed_formats, -1) == "|") {$allowed_formats = substr($allowed_formats, 0, -1);}

//******************************************************************************************************
//   ATTENTION: THIS FILE HEADER MUST REMAIN INTACT. DO NOT DELETE OR MODIFY THIS FILE HEADER.
//
//   Name: ubr_file_upload.php
//   Revision: 1.5
//   Date: 3/2/2008 11:16:38 AM
//   Link: http://uber-uploader.sourceforge.net
//   Initial Developer: Peter Schmandra  http://www.webdice.org
//   Description: Select and submit upload files.
//
//   Licence:
//   The contents of this file are subject to the Mozilla Public
//   License Version 1.1 (the "License"); you may not use this file
//   except in compliance with the License. You may obtain a copy of
//   the License at http://www.mozilla.org/MPL/
//
//   Software distributed under the License is distributed on an "AS
//   IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
//   implied. See the License for the specific language governing
//   rights and limitations under the License.
//
//******************************************************************************************************

$THIS_VERSION = '1.5';

require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_ini.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_lib.php');

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . date('r'));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

if($MULTI_CONFIGS_ENABLED)
{
	$config_file = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_default_config.php';
}
else
{
	$config_file = $DEFAULT_CONFIG;
}

// Load config file
require $config_file;

//******************************************************************************************************
// The following possible query string formats are assumed
//
// 1. No query string
// 2. ?about=1
//******************************************************************************************************

if($DEBUG_PHP){ phpinfo(); exit(); }
elseif($DEBUG_CONFIG){ debug($_CONFIG['config_file_name'], $_CONFIG); exit(); }
elseif(isset($_GET['about']) && $_GET['about'] == 1){
	kak("<u><b>UBER UPLOADER FILE UPLOAD</b></u><br>UBER UPLOADER VERSION =  " . $UBER_VERSION . "<br>UBR_FILE_UPLOAD = " . $THIS_VERSION . "<br>\n", 1, __LINE__);
}

//******************************************************************************************************
//   Set custom head tags
//******************************************************************************************************

$uutemplate_css = "<style type=\"text/css\">
.ubr_alert {font:18px Arial;}
.debug {font:16px Arial; background-color:#FFFFFF; border:1px solid #898989; width:700px; height:100px; overflow:auto;}
.data {background-color:#505050; border:1px solid #313131; width:90%; padding: 0; margin 0;}
.data tr td {background-color:#fffafa; font-size: 90%; font-weight: bold; color: #505050; width:50%; padding: 2px; margin 0;}
.bar1 {background-color:#efefef; position:relative; text-align:left; height:20px; width:".$_CONFIG['progress_bar_width']."px; border:1px solid #505050;margin: 10px 0 0 0;}
.bar2 {background-color:#333333!important; position:relative; text-align:left; height:20px; width:0%;}
</style>";

$uu_js =  "<script language=\"javascript\" type=\"text/javascript\" src=\"".$PATH_TO_JS_SCRIPT."\"></script>
		   <script language=\"javascript\" type=\"text/javascript\">
			var path_to_link_script = \"".$PATH_TO_LINK_SCRIPT."\";
			var path_to_set_progress_script = \"".$PATH_TO_SET_PROGRESS_SCRIPT."\";
			var path_to_get_progress_script = \"".$PATH_TO_GET_PROGRESS_SCRIPT."\";
			var path_to_upload_script = \"".$PATH_TO_UPLOAD_SCRIPT."\";
			var multi_configs_enabled = ".$MULTI_CONFIGS_ENABLED.";";
if($MULTI_CONFIGS_ENABLED)
{
  $uu_js.= "var config_file = \"$config_file\";\n";
}
$uu_js.=   "var check_allow_extensions_on_client = ".$_CONFIG['check_allow_extensions_on_client'].";
			var check_disallow_extensions_on_client = ".$_CONFIG['check_disallow_extensions_on_client'].";";
if($_CONFIG['check_allow_extensions_on_client'])
{
  $uu_js.= "var allow_extensions = /(" . $allowed_formats . ")$/i;\n";
}
if($_CONFIG['check_disallow_extensions_on_client'])
{
  $uu_js.= "var disallow_extensions = /" . $_CONFIG['disallow_extensions'] . "$/i;\n";
}
$uu_js.=   "var check_file_name_format = ".$_CONFIG['check_file_name_format'].";
			var check_null_file_count = ".$_CONFIG['check_null_file_count'].";
			var check_duplicate_file_count = ".$_CONFIG['check_duplicate_file_count'].";
			var max_upload_slots = ".$_CONFIG['max_upload_slots'].";
			var cedric_progress_bar = ".$_CONFIG['cedric_progress_bar'].";
			var progress_bar_width = ".$_CONFIG['progress_bar_width'].";
			var show_percent_complete = ".$_CONFIG['show_percent_complete'].";
			var show_files_uploaded = ".$_CONFIG['show_files_uploaded'].";
			var show_current_position = ".$_CONFIG['show_current_position'].";
			var show_elapsed_time = ".$_CONFIG['show_elapsed_time'].";
			var show_est_time_left = ".$_CONFIG['show_est_time_left'].";
			var show_est_speed = ".$_CONFIG['show_est_speed'].";
		   </script>";

$doc->addCustomTag($uutemplate_css);
$doc->addCustomTag($uu_js);
$doc->addCustomTag('<meta http-equiv="pragma" content="no-cache">');
$doc->addCustomTag('<meta http-equiv="cache-control" content="no-cache">');
$doc->addCustomTag('<meta http-equiv="expires" content="-1">');
$doc->addCustomTag('<meta name="robots" content="index,nofollow">');
$doc->addCustomTag('<meta name="robots" content="index,nofollow">');

//******************************************************************************************************
//   Define progress table
//******************************************************************************************************

	$uu_progress_info = null;

	if($_CONFIG['show_percent_complete'] || $_CONFIG['show_files_uploaded'] || $_CONFIG['show_current_position'] || $_CONFIG['show_elapsed_time'] || $_CONFIG['show_est_time_left'] || $_CONFIG['show_est_speed']){

	$uu_progress_info.=  "<br><table class=\"data\" cellpadding='1' cellspacing='1'>";
	if($_CONFIG['show_percent_complete']){
	   $uu_progress_info.= "<tr>
				            <td align=\"left\"><b>"._HWDVIDS_UU_PC."</b></td>
				            <td align=\"center\"><span id=\"percent\">0%</span></td>
                            </tr>";
	}
	if($_CONFIG['show_files_uploaded']){
	   $uu_progress_info.= "<tr>
                            <td align=\"left\"><b>"._HWDVIDS_UU_FU."</b></td>
                            <td align=\"center\"><span id=\"uploaded_files\">0</span> of <span id=\"total_uploads\"></span></td>
                            </tr>";
	}
	if($_CONFIG['show_current_position']){
	   $uu_progress_info.= "<tr>
                            <td align=\"left\"><b>"._HWDVIDS_UU_CP."</b></td>
                            <td align=\"center\"><span id=\"currentupld\">0</span> / <span id=\"total_kbytes\"></span> KBytes</td>
                            </tr>";
	}
	if($_CONFIG['show_elapsed_time']){
	   $uu_progress_info.= "<tr>
                            <td align=\"left\"><b>"._HWDVIDS_UU_ET."</b></td>
                            <td align=\"center\"><span id=\"time\">0</span></td>
                            </tr>";
	}
	if($_CONFIG['show_est_time_left']){
	   $uu_progress_info.= "<tr>
                            <td align=\"left\"><b>"._HWDVIDS_UU_ETL."</b></td>
                            <td align=\"center\"><span id=\"remain\">0</span></td>
                            </tr>";
	}
	if($_CONFIG['show_est_speed']){
	   $uu_progress_info.= "<tr>
                            <td align=\"left\"><b>"._HWDVIDS_UU_ES."</b></td>
                            <td align=\"center\"><span id=\"speed\">0</span> KB/s.</td>
                            </tr>";
	}
	$uu_progress_info.= "</table>";
	}
	$smartyvs->assign("uu_progress_info", $uu_progress_info);

//******************************************************************************************************
//   Define additional required code
//******************************************************************************************************

$uu_extra_code = null;
if($_CONFIG['embedded_upload_results'] || $_CONFIG['opera_browser'] || $_CONFIG['safari_browser'])
{
	$uu_extra_code = "<div id=\"upload_div\" style=\"display:none;\"><iframe name=\"upload_iframe\" frameborder=\"0\" width=\"800\" height=\"200\" scrolling=\"auto\"></iframe></div>";
}
$smartyvs->assign("uu_extra_code", $uu_extra_code);

//******************************************************************************************************
//   Define main uu form
//******************************************************************************************************

$uu_upload_form = null;

$addon0=null;
$addon1=null;

if($_CONFIG['embedded_upload_results'] || $_CONFIG['opera_browser'] || $_CONFIG['safari_browser'])
{
	$addon0 = "target=\"upload_iframe\"";
}
if($_CONFIG['multi_upload_slots'])
{
	$addon1 = "onChange=\"addUploadSlot(1)\"";
}

	$uu_upload_form.= "<form name=\"form_upload\" id=\"form_upload\" ".$addon0." method=\"post\" enctype=\"multipart/form-data\" action=\"#\" style=\"margin: 0px; padding: 0px;\">
				  <noscript><font color='red'>"._HWDVIDS_WARNING."</font>"._HWDVIDS_WARNING_EJFU."<br /><br /></noscript>
				  <!-- Include extra values you want passed to the upload script here. -->
					<input type=\"hidden\" name=\"videotype\" value=\"".htmlspecialchars($videotype)."\" />
					<input type=\"hidden\" name=\"title\" value=\"".htmlspecialchars($title)."\" />
					<input type=\"hidden\" name=\"description\" value=\"".htmlspecialchars($description)."\" />
					<input type=\"hidden\" name=\"category_id\" value=\"".htmlspecialchars($category_id)."\" />
					<input type=\"hidden\" name=\"tags\" value=\"".htmlspecialchars($tags)."\" />
					<input type=\"hidden\" name=\"public_private\" value=\"".htmlspecialchars($public_private)."\" />
					<input type=\"hidden\" name=\"allow_comments\" value=\"".htmlspecialchars($allow_comments)."\" />
					<input type=\"hidden\" name=\"allow_embedding\" value=\"".htmlspecialchars($allow_embedding)."\" />
					<input type=\"hidden\" name=\"allow_ratings\" value=\"".htmlspecialchars($allow_ratings)."\" />
					<input type=\"hidden\" name=\"Itemid\" value=\"".htmlspecialchars($hwdvsItemid)."\" />
					<input type=\"hidden\" name=\"livesite\" value=\"".htmlspecialchars(JURI::base())."\" />
				  <div id=\"upload_slots\"><input type=\"file\" name=\"upfile_0\" size=\"45\" ".$addon1." onkeypress=\"return handleKey(event)\" value=\"\"></div>
				  <br>
				  <input type=\"button\" id=\"reset_button\" name=\"reset_button\" value=\""._HWDVIDS_BUTTON_RESET."\" onClick=\"resetForm();\">&nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"upload_button\" name=\"upload_button\" value=\""._HWDVIDS_BUTTON_UPLOAD."\" onClick=\"linkUpload();\">
				</form>";

$smartyvs->assign("uu_upload_form", $uu_upload_form);

?>