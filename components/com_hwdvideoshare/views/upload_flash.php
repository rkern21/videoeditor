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

$c = hwd_vs_Config::get_instance();

if ($c->requiredins == 1) {
	$allowedft = "";
	if ($c->ft_mpg == "on") {$allowedft .= " *.mpg;";}
	if ($c->ft_mpeg == "on") {$allowedft .= " *.mpeg;";}
	if ($c->ft_avi == "on") {$allowedft .=  " *.avi;";}
	if ($c->ft_divx == "on") {$allowedft .=  " *.divx;";}
	if ($c->ft_mp4 == "on") {$allowedft .=  " *.mp4;";}
	if ($c->ft_flv == "on") {$allowedft .=  " *.flv;";}
	if ($c->ft_wmv == "on") {$allowedft .=  " *.wmv;";}
	if ($c->ft_rm == "on") {$allowedft .=  " *.rm;";}
	if ($c->ft_mov == "on") {$allowedft .=  " *.mov;";}
	if ($c->ft_moov == "on") {$allowedft .=  " *.moov;";}
	if ($c->ft_asf == "on") {$allowedft .=  " *.asf;";}
	if ($c->ft_swf == "on") {$allowedft .=  " *.swf;";}
	if ($c->ft_vob == "on") {$allowedft .=  " *.vob;";}

	$oformats = explode(",", $c->oformats);
	for ($i = 0, $n = count($oformats); $i < $n; $i++)
	{
		$oformat = $oformats[$i];
		$oformat = preg_replace("/[^a-zA-Z0-9s]/", "", $oformat);
		$allowedft .=  " *.".$oformat.";";
	}

} else {

	$allowedft = "";
	if ($c->ft_mp4 == "on") {$allowedft .=  " *.mp4;";}
	if ($c->ft_flv == "on") {$allowedft .=  " *.flv;";}
	if ($c->ft_swf == "on") {$allowedft .=  " *.swf;";}

}

$form_upld_flash = JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$Itemid."&task=uploadconfirmflash");

$hidden_inputs="<input type=\"hidden\" name=\"videotype\" value=\"".htmlspecialchars($videotype)."\" />
				<input type=\"hidden\" name=\"title\" value=\"".htmlspecialchars($title)."\" />
				<input type=\"hidden\" name=\"description\" value=\"".htmlspecialchars($description)."\" />
				<input type=\"hidden\" name=\"category_id\" value=\"".htmlspecialchars($category_id)."\" />
				<input type=\"hidden\" name=\"tags\" value=\"".htmlspecialchars($tags)."\" />
				<input type=\"hidden\" name=\"public_private\" value=\"".htmlspecialchars($public_private)."\" />
				<input type=\"hidden\" name=\"allow_comments\" value=\"".htmlspecialchars($allow_comments)."\" />
				<input type=\"hidden\" name=\"allow_embedding\" value=\"".htmlspecialchars($allow_embedding)."\" />
				<input type=\"hidden\" name=\"allow_ratings\" value=\"".htmlspecialchars($allow_ratings)."\" />";

$smartyvs->assign("allowedft", $allowedft);
$smartyvs->assign("form_upld_flash", $form_upld_flash);
$smartyvs->assign("hidden_inputs", $hidden_inputs);

$smartyvs->assign("slashed_flashconfirm", addslashes(_HWDVIDS_FLASHUPDL_FUPLD));
$smartyvs->assign("slashed_allowedExtDescr", addslashes(_HWDVIDS_FLASHUPDL_allowedExtDescr));
$smartyvs->assign("slashed_validFileMessage", addslashes(_HWDVIDS_FLASHUPDL_validFileMessage));
$smartyvs->assign("slashed_startMessage", addslashes(_HWDVIDS_FLASHUPDL_startMessage));
$smartyvs->assign("slashed_errorSizeMessage", addslashes(_HWDVIDS_FLASHUPDL_errorSizeMessage));
$smartyvs->assign("slashed_progressMessage", addslashes(_HWDVIDS_FLASHUPDL_progressMessage));
$smartyvs->assign("slashed_endMessage", addslashes(_HWDVIDS_FLASHUPDL_endMessage));

$max_upld = $c->maxupld*1024*1024;
$smartyvs->assign("max_upld", $max_upld);
