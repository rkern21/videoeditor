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

class hwdvids_HTML_settings
{
   /**
	* show general settings
	*/
	function showlayoutsettings(&$gtree)
	{
		global $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db =& JFactory::getDBO();

		/** assign template variables **/
		$smartyvs->assign( "header_title", _HWDVIDS_SECTIONHEAD_GS );

		/** display template **/
		$smartyvs->display('admin_header.tpl');

  		if (is_writable(HWDVS_ADMIN_PATH.'/config.hwdvideoshare.php')) {
  			$config_file_status = "<span style=\"color:#458B00;\">"._HWDVIDS_INFO_CONFIGF2."</span>.";
  		} else {
  			$config_file_status = "<span style=\"color:#ff0000;\">"._HWDVIDS_INFO_CONFIGF3."</span>. (".HWDVS_ADMIN_PATH."/config.hwdvideoshare.php)";
  		}
  		?>
  		<div style="border: solid 1px #333;margin:5px 0 5px 0;padding:5px;text-align:left;font-weight:bold;">
  		  <ul id="submenu">
            <li>
              <a href="index.php?option=com_hwdvideoshare&task=generalsettings">General Settings</a>
            </li>
            <li>
              <a class="active" href="index.php?option=com_hwdvideoshare&task=layoutsettings">Layout Settings</a>
            </li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo _HWDVIDS_INFO_CONFIGF1." ".$config_file_status; ?></li>
          </ul>
        <div style="clear:both;"></div>
        </div>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_DISABLENAVTABS ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEVIDTAB ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_videos" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_videos == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_videos == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEVIDTAB_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLECATTAB ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_catego" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_catego == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_catego == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLECATTAB_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEGRPTAB ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_groups" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_groups == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_groups == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEGRPTAB_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEULDTAB ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_upload" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_upload == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_upload == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEULDTAB_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLESEARCHBAR ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_search" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_search == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_search == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLESEARCHBAR_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_DISABLEUSRTABS ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEUSERBAR ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_user" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_user == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_user == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEUSERBAR_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEYVLINK ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_user1" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_user1 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_user1 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEYVLINK_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEYFLINK ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_user2" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_user2 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_user2 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEYFLINK_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEYGLINK ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_user3" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_user3 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_user3 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEYGLINK_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEYMLINK ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_user4" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_user4 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_user4 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLEYMLINK_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLECGLINK ?></td>
				<td width="20%" align="left" valign="top">
				<select name="diable_nav_user5" size="1" class="inputbox">
					<option value="1"<?php if ($c->diable_nav_user5 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->diable_nav_user5 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLECGLINK_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_FRONTPAGE ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWWN ?></td>
				<td width="20%" align="left" valign="top">
				<select name="frontpage_watched" size="1" class="inputbox">
					<option value="1"<?php if ($c->frontpage_watched == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->frontpage_watched == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWWN_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_NBWNO ?></td>
				<td width="20%" align="left" valign="top">
				<select name="bwn_no" size="1" class="inputbox">
					<option value="1"<?php if ($c->bwn_no == 1) { ?> selected="selected"<?php } ?>>1</option>
					<option value="2"<?php if ($c->bwn_no == 2) { ?> selected="selected"<?php } ?>>2</option>
					<option value="3"<?php if ($c->bwn_no == 3) { ?> selected="selected"<?php } ?>>3</option>
					<option value="4"<?php if ($c->bwn_no == 4) { ?> selected="selected"<?php } ?>>4</option>
					<option value="5"<?php if ($c->bwn_no == 5) { ?> selected="selected"<?php } ?>>5</option>
					<option value="6"<?php if ($c->bwn_no == 6) { ?> selected="selected"<?php } ?>>6</option>
					<option value="7"<?php if ($c->bwn_no == 7) { ?> selected="selected"<?php } ?>>7</option>
					<option value="8"<?php if ($c->bwn_no == 8) { ?> selected="selected"<?php } ?>>8</option>
					<option value="9"<?php if ($c->bwn_no == 9) { ?> selected="selected"<?php } ?>>9</option>
					<option value="10"<?php if ($c->bwn_no == 10) { ?> selected="selected"<?php } ?>>10</option>
					<option value="11"<?php if ($c->bwn_no == 11) { ?> selected="selected"<?php } ?>>11</option>
					<option value="12"<?php if ($c->bwn_no == 12) { ?> selected="selected"<?php } ?>>12</option>
					<option value="13"<?php if ($c->bwn_no == 13) { ?> selected="selected"<?php } ?>>13</option>
					<option value="14"<?php if ($c->bwn_no == 14) { ?> selected="selected"<?php } ?>>14</option>
					<option value="15"<?php if ($c->bwn_no == 15) { ?> selected="selected"<?php } ?>>15</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_NBWNO_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWMV ?></td>
				<td width="20%" align="left" valign="top">
				<select name="frontpage_viewed" size="1" class="inputbox">
					<option value="0"<?php if ($c->frontpage_viewed == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
					<option value="today"<?php if ($c->frontpage_viewed == "today") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTODAY.' '; ?></option>
					<option value="thisweek"<?php if ($c->frontpage_viewed == "thisweek") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTW.' '; ?></option>
					<option value="thismonth"<?php if ($c->frontpage_viewed == "thismonth") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTM.' '; ?></option>
					<option value="alltime"<?php if ($c->frontpage_viewed == "alltime") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FAT.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWMV_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWMF ?></td>
				<td width="20%" align="left" valign="top">
				<select name="frontpage_favoured" size="1" class="inputbox">
					<option value="0"<?php if ($c->frontpage_favoured == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
					<option value="today"<?php if ($c->frontpage_favoured == "today") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTODAY.' '; ?></option>
					<option value="thisweek"<?php if ($c->frontpage_favoured == "thisweek") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTW.' '; ?></option>
					<option value="thismonth"<?php if ($c->frontpage_favoured == "thismonth") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTM.' '; ?></option>
					<option value="alltime"<?php if ($c->frontpage_favoured == "alltime") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FAT.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWMF_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWMP ?></td>
				<td width="20%" align="left" valign="top">
				<select name="frontpage_popular" size="1" class="inputbox">
					<option value="0"<?php if ($c->frontpage_popular == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
					<option value="today"<?php if ($c->frontpage_popular == "today") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTODAY.' '; ?></option>
					<option value="thisweek"<?php if ($c->frontpage_popular == "thisweek") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTW.' '; ?></option>
					<option value="thismonth"<?php if ($c->frontpage_popular == "thismonth") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FTM.' '; ?></option>
					<option value="alltime"<?php if ($c->frontpage_popular == "alltime") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_FAT.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWMP_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show Featured Player</td>
				<td width="20%" align="left" valign="top">
				<select name="feat_show" size="1" class="inputbox">
					<option value="1"<?php if ($c->feat_show == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->feat_show == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Show featured video player on homepage</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Featured Auto-Start</td>
				<td width="20%" align="left" valign="top">
				<select name="feat_as" size="1" class="inputbox">
					<option value="global"<?php if ($c->feat_as == "global") { ?> selected="selected"<?php } ?>>Global</option>
					<option value="yes"<?php if ($c->feat_as == "yes") { ?> selected="selected"<?php } ?>>Yes</option>
					<option value="no"<?php if ($c->feat_as == "no") { ?> selected="selected"<?php } ?>>No</option>
					<option value="first"<?php if ($c->feat_as == "first") { ?> selected="selected"<?php } ?>>First View of Day Only</option>

				</select>
				</td>
				<td width="60%" align="left" valign="top">Select the auto-start control for the featured video player on the homepage.</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show Random Featured Video</td>
				<td width="20%" align="left" valign="top">
				<select name="feat_rand" size="1" class="inputbox">
					<option value="1"<?php if ($c->feat_rand == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->feat_rand == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Show a random featured video each time or just display the first fetaured video.</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Featured Video Width</td>
				<td width="20%" align="left" valign="top">
				<select name="fvid_w" size="1" class="inputbox">
					<option value="0"<?php if ($c->fvid_w == 0) { ?> selected="selected"<?php } ?>>100%</option>
					<?php
					for ($i=80, $n=1501; $i < $n; $i++) {
						echo "<option value=\"".$i."\"";
						if ($c->fvid_w == $i) {
							echo " selected=\"selected\"";
						}
						echo ">".$i."px</option>";
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWMP_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Featured Video Height</td>
				<td width="20%" align="left" valign="top">
				<select name="fvid_h" size="1" class="inputbox">
					<?php
					for ($i=80, $n=1501; $i < $n; $i++) {
						echo "<option value=\"".$i."\"";
						if ($c->fvid_h == $i) {
							echo " selected=\"selected\"";
						}
						echo ">".$i."px</option>";
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWMP_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_VIDEOS ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_VPP ?></td>
				<td width="20%" align="left" valign="top">
				<select name="vpp" size="1" class="inputbox">
					<?php
					for ($i=5, $n=51; $i < $n; $i++) {
						echo "<option value=\"".$i."\"";
						if ($c->vpp == $i) {
							echo " selected=\"selected\"";
						}
						echo ">".$i."</option>";
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_VPP_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_FVIDS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="fpfeaturedvids" size="1" class="inputbox">
					<?php
					for ($i=1, $n=11; $i < $n; $i++) {
						echo "<option value=\"".$i."\"";
						if ($c->fpfeaturedvids == $i) {
							echo " selected=\"selected\"";
						}
						echo ">".$i."</option>";
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_FVIDS_DESC ?></td>
			  </tr>
			  <tr><td align="left" valign="top" colspan="3"><h3>Video Player Width</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Video Player Width</td>
				<td width="20%" align="left" valign="top">
				<select name="flvplay_width" size="1" class="inputbox">
					<?php
					for ($i=20, $n=1001; $i < $n; $i++) {
						echo "<option value=\"".$i."\"";
						if ($c->flvplay_width == $i) {
							echo " selected=\"selected\"";
						}
						echo ">".$i."px</option>";
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Select the width of the default video player</td>
			  </tr>
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_VAR ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CVAR ?></td>
				<td width="20%" align="left" valign="top">
				<select name="var_c" size="1" class="inputbox">
					<option value="1"<?php if ($c->var_c == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->var_c == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CVAR_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_VFR ?></td>
				<td width="20%" align="left" valign="top">
				<select name="var_fb" size="1" class="inputbox">
					<option value="0.75"<?php if ($c->var_fb == "0.75") { ?> selected="selected"<?php } ?>>4:3</option>
					<option value="0.66666666"<?php if ($c->var_fb == "0.66666666") { ?> selected="selected"<?php } ?>>3:2</option>
					<option value="0.5625"<?php if ($c->var_fb == "0.5625") { ?> selected="selected"<?php } ?>>16:9</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_VFR_DESC ?></td>
			  </tr>
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_VTC ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_THUMBW ?></td>
				<td align="left" valign="top" width="20%">
				<select name="thumbwidth" size="1" class="inputbox">
					<?php
					for ($i=20, $n=401; $i < $n; $i++) {
						echo "<option value=\"".$i."\"";
						if ($c->thumbwidth == $i) {
							echo " selected=\"selected\"";
						}
						echo ">".$i."px</option>";
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_THUMBW_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TAR ?></td>
				<td width="20%" align="left" valign="top">
				<select name="tar_fb" size="1" class="inputbox">
					<option value="0.75"<?php if ($c->tar_fb == "0.75") { ?> selected="selected"<?php } ?>>4:3</option>
					<option value="0.66"<?php if ($c->tar_fb == "0.66") { ?> selected="selected"<?php } ?>>3:2</option>
					<option value="0.56"<?php if ($c->tar_fb == "0.56") { ?> selected="selected"<?php } ?>>16:9</option>
					<option value="0.54"<?php if ($c->tar_fb == "0.54") { ?> selected="selected"<?php } ?>>1.85:1</option>
					<option value="0.42"<?php if ($c->tar_fb == "0.42") { ?> selected="selected"<?php } ?>>2.39:1</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TAR_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_UDT ?></td>
				<td width="20%" align="left" valign="top">
				<select name="udt" size="1" class="inputbox">
					<option value="1"<?php if ($c->udt == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->udt == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_UDT_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Include Thumbnail Timestamp</td>
				<td width="20%" align="left" valign="top">
				<select name="thumb_ts" size="1" class="inputbox">
					<option value="1"<?php if ($c->thumb_ts == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->thumb_ts == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">You can print the video timestamp on the thumbnail.</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show thumbnail tooltip</td>
				<td width="20%" align="left" valign="top">
				<select name="show_tooltip" size="1" class="inputbox">
					<option value="1"<?php if ($c->show_tooltip == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->show_tooltip == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">You can enable a tooltip which shows the title and description for the thumbnail.</td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_VPINFOOT ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show Information on Video Page?</td>
				<td width="20%" align="left" valign="top">
				<select name="show_vp_info" size="1" class="inputbox">
					<option value="1"<?php if ($c->show_vp_info == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->show_vp_info == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Show information or just the video player?</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWRAT ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showrate" size="1" class="inputbox">
					<option value="1"<?php if ($c->showrate == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showrate == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWRAT_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWATF ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showatfb" size="1" class="inputbox">
					<option value="1"<?php if ($c->showatfb == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showatfb == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWATF_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWRPB ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showrpmb" size="1" class="inputbox">
					<option value="1"<?php if ($c->showrpmb == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showrpmb == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWRPB_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWCOM ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showcoms" size="1" class="inputbox">
					<option value="1"<?php if ($c->showcoms == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showcoms == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWCOM_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWVUR ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showvurl" size="1" class="inputbox">
					<option value="1"<?php if ($c->showvurl == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showvurl == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWVUR_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWVEB ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showvebc" size="1" class="inputbox">
					<option value="1"<?php if ($c->showvebc == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showvebc == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWVEB_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWDSC ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showdesc" size="1" class="inputbox">
					<option value="1"<?php if ($c->showdesc == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showdesc == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWDSC_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWTAG ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showtags" size="1" class="inputbox">
					<option value="1"<?php if ($c->showtags == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showtags == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWTAG_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWSBK ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showscbm" size="1" class="inputbox">
					<option value="1"<?php if ($c->showscbm == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showscbm == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWSBK_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWULR ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showuldr" size="1" class="inputbox">
					<option value="1"<?php if ($c->showuldr == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showuldr == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWULR_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Number of videos to display</td>
				<td width="20%" align="left" valign="top">
				<select name="mbtu_no" size="1" class="inputbox">
					<option value="0"<?php if ($c->mbtu_no == 0) { ?> selected="selected"<?php } ?>>0</option>
					<option value="1"<?php if ($c->mbtu_no == 1) { ?> selected="selected"<?php } ?>>1</option>
					<option value="2"<?php if ($c->mbtu_no == 2) { ?> selected="selected"<?php } ?>>2</option>
					<option value="3"<?php if ($c->mbtu_no == 3) { ?> selected="selected"<?php } ?>>3</option>
					<option value="4"<?php if ($c->mbtu_no == 4) { ?> selected="selected"<?php } ?>>4</option>
					<option value="5"<?php if ($c->mbtu_no == 5) { ?> selected="selected"<?php } ?>>5</option>
					<option value="6"<?php if ($c->mbtu_no == 6) { ?> selected="selected"<?php } ?>>6</option>
					<option value="7"<?php if ($c->mbtu_no == 7) { ?> selected="selected"<?php } ?>>7</option>
					<option value="8"<?php if ($c->mbtu_no == 8) { ?> selected="selected"<?php } ?>>8</option>
					<option value="9"<?php if ($c->mbtu_no == 9) { ?> selected="selected"<?php } ?>>9</option>
					<option value="10"<?php if ($c->mbtu_no == 10) { ?> selected="selected"<?php } ?>>10</option>
					<option value="11"<?php if ($c->mbtu_no == 11) { ?> selected="selected"<?php } ?>>11</option>
					<option value="12"<?php if ($c->mbtu_no == 12) { ?> selected="selected"<?php } ?>>12</option>
					<option value="13"<?php if ($c->mbtu_no == 13) { ?> selected="selected"<?php } ?>>13</option>
					<option value="14"<?php if ($c->mbtu_no == 14) { ?> selected="selected"<?php } ?>>14</option>
					<option value="15"<?php if ($c->mbtu_no == 15) { ?> selected="selected"<?php } ?>>15</option>
					<option value="16"<?php if ($c->mbtu_no == 16) { ?> selected="selected"<?php } ?>>16</option>
					<option value="17"<?php if ($c->mbtu_no == 17) { ?> selected="selected"<?php } ?>>17</option>
					<option value="18"<?php if ($c->mbtu_no == 18) { ?> selected="selected"<?php } ?>>18</option>
					<option value="19"<?php if ($c->mbtu_no == 19) { ?> selected="selected"<?php } ?>>19</option>
					<option value="20"<?php if ($c->mbtu_no == 20) { ?> selected="selected"<?php } ?>>20</option>
					<option value="21"<?php if ($c->mbtu_no == 21) { ?> selected="selected"<?php } ?>>21</option>
					<option value="22"<?php if ($c->mbtu_no == 22) { ?> selected="selected"<?php } ?>>22</option>
					<option value="23"<?php if ($c->mbtu_no == 23) { ?> selected="selected"<?php } ?>>23</option>
					<option value="24"<?php if ($c->mbtu_no == 24) { ?> selected="selected"<?php } ?>>24</option>
					<option value="25"<?php if ($c->mbtu_no == 25) { ?> selected="selected"<?php } ?>>25</option>
					<option value="26"<?php if ($c->mbtu_no == 26) { ?> selected="selected"<?php } ?>>26</option>
					<option value="27"<?php if ($c->mbtu_no == 27) { ?> selected="selected"<?php } ?>>27</option>
					<option value="28"<?php if ($c->mbtu_no == 28) { ?> selected="selected"<?php } ?>>28</option>
					<option value="29"<?php if ($c->mbtu_no == 29) { ?> selected="selected"<?php } ?>>29</option>
					<option value="30"<?php if ($c->mbtu_no == 30) { ?> selected="selected"<?php } ?>>30</option>
					<option value="31"<?php if ($c->mbtu_no == 31) { ?> selected="selected"<?php } ?>>31</option>
					<option value="32"<?php if ($c->mbtu_no == 32) { ?> selected="selected"<?php } ?>>32</option>
					<option value="33"<?php if ($c->mbtu_no == 33) { ?> selected="selected"<?php } ?>>33</option>
					<option value="34"<?php if ($c->mbtu_no == 34) { ?> selected="selected"<?php } ?>>34</option>
					<option value="35"<?php if ($c->mbtu_no == 35) { ?> selected="selected"<?php } ?>>35</option>
					<option value="36"<?php if ($c->mbtu_no == 36) { ?> selected="selected"<?php } ?>>36</option>
					<option value="37"<?php if ($c->mbtu_no == 37) { ?> selected="selected"<?php } ?>>37</option>
					<option value="38"<?php if ($c->mbtu_no == 38) { ?> selected="selected"<?php } ?>>38</option>
					<option value="39"<?php if ($c->mbtu_no == 39) { ?> selected="selected"<?php } ?>>39</option>
					<option value="40"<?php if ($c->mbtu_no == 40) { ?> selected="selected"<?php } ?>>40</option>
					<option value="41"<?php if ($c->mbtu_no == 41) { ?> selected="selected"<?php } ?>>41</option>
					<option value="42"<?php if ($c->mbtu_no == 42) { ?> selected="selected"<?php } ?>>42</option>
					<option value="43"<?php if ($c->mbtu_no == 43) { ?> selected="selected"<?php } ?>>43</option>
					<option value="44"<?php if ($c->mbtu_no == 44) { ?> selected="selected"<?php } ?>>44</option>
					<option value="45"<?php if ($c->mbtu_no == 45) { ?> selected="selected"<?php } ?>>45</option>
					<option value="46"<?php if ($c->mbtu_no == 46) { ?> selected="selected"<?php } ?>>46</option>
					<option value="47"<?php if ($c->mbtu_no == 47) { ?> selected="selected"<?php } ?>>47</option>
					<option value="48"<?php if ($c->mbtu_no == 48) { ?> selected="selected"<?php } ?>>48</option>
					<option value="49"<?php if ($c->mbtu_no == 49) { ?> selected="selected"<?php } ?>>49</option>
					<option value="50"<?php if ($c->mbtu_no == 50) { ?> selected="selected"<?php } ?>>50</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Set the number of videos to display from this user</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show <b>Related Videos</b></td>
				<td width="20%" align="left" valign="top">
				<select name="showrevi" size="1" class="inputbox">
					<option value="1"<?php if ($c->showrevi == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showrevi == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Allow users to see other videos that are related to the one currently being watched</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Number of videos to display</td>
				<td width="20%" align="left" valign="top">
				<select name="revi_no" size="1" class="inputbox">
					<option value="0"<?php if ($c->revi_no == 0) { ?> selected="selected"<?php } ?>>0</option>
					<option value="1"<?php if ($c->revi_no == 1) { ?> selected="selected"<?php } ?>>1</option>
					<option value="2"<?php if ($c->revi_no == 2) { ?> selected="selected"<?php } ?>>2</option>
					<option value="3"<?php if ($c->revi_no == 3) { ?> selected="selected"<?php } ?>>3</option>
					<option value="4"<?php if ($c->revi_no == 4) { ?> selected="selected"<?php } ?>>4</option>
					<option value="5"<?php if ($c->revi_no == 5) { ?> selected="selected"<?php } ?>>5</option>
					<option value="6"<?php if ($c->revi_no == 6) { ?> selected="selected"<?php } ?>>6</option>
					<option value="7"<?php if ($c->revi_no == 7) { ?> selected="selected"<?php } ?>>7</option>
					<option value="8"<?php if ($c->revi_no == 8) { ?> selected="selected"<?php } ?>>8</option>
					<option value="9"<?php if ($c->revi_no == 9) { ?> selected="selected"<?php } ?>>9</option>
					<option value="10"<?php if ($c->revi_no == 10) { ?> selected="selected"<?php } ?>>10</option>
					<option value="11"<?php if ($c->revi_no == 11) { ?> selected="selected"<?php } ?>>11</option>
					<option value="12"<?php if ($c->revi_no == 12) { ?> selected="selected"<?php } ?>>12</option>
					<option value="13"<?php if ($c->revi_no == 13) { ?> selected="selected"<?php } ?>>13</option>
					<option value="14"<?php if ($c->revi_no == 14) { ?> selected="selected"<?php } ?>>14</option>
					<option value="15"<?php if ($c->revi_no == 15) { ?> selected="selected"<?php } ?>>15</option>
					<option value="16"<?php if ($c->revi_no == 16) { ?> selected="selected"<?php } ?>>16</option>
					<option value="17"<?php if ($c->revi_no == 17) { ?> selected="selected"<?php } ?>>17</option>
					<option value="18"<?php if ($c->revi_no == 18) { ?> selected="selected"<?php } ?>>18</option>
					<option value="19"<?php if ($c->revi_no == 19) { ?> selected="selected"<?php } ?>>19</option>
					<option value="20"<?php if ($c->revi_no == 20) { ?> selected="selected"<?php } ?>>20</option>
					<option value="21"<?php if ($c->revi_no == 21) { ?> selected="selected"<?php } ?>>21</option>
					<option value="22"<?php if ($c->revi_no == 22) { ?> selected="selected"<?php } ?>>22</option>
					<option value="23"<?php if ($c->revi_no == 23) { ?> selected="selected"<?php } ?>>23</option>
					<option value="24"<?php if ($c->revi_no == 24) { ?> selected="selected"<?php } ?>>24</option>
					<option value="25"<?php if ($c->revi_no == 25) { ?> selected="selected"<?php } ?>>25</option>
					<option value="26"<?php if ($c->revi_no == 26) { ?> selected="selected"<?php } ?>>26</option>
					<option value="27"<?php if ($c->revi_no == 27) { ?> selected="selected"<?php } ?>>27</option>
					<option value="28"<?php if ($c->revi_no == 28) { ?> selected="selected"<?php } ?>>28</option>
					<option value="29"<?php if ($c->revi_no == 29) { ?> selected="selected"<?php } ?>>29</option>
					<option value="30"<?php if ($c->revi_no == 30) { ?> selected="selected"<?php } ?>>30</option>
					<option value="31"<?php if ($c->revi_no == 31) { ?> selected="selected"<?php } ?>>31</option>
					<option value="32"<?php if ($c->revi_no == 32) { ?> selected="selected"<?php } ?>>32</option>
					<option value="33"<?php if ($c->revi_no == 33) { ?> selected="selected"<?php } ?>>33</option>
					<option value="34"<?php if ($c->revi_no == 34) { ?> selected="selected"<?php } ?>>34</option>
					<option value="35"<?php if ($c->revi_no == 35) { ?> selected="selected"<?php } ?>>35</option>
					<option value="36"<?php if ($c->revi_no == 36) { ?> selected="selected"<?php } ?>>36</option>
					<option value="37"<?php if ($c->revi_no == 37) { ?> selected="selected"<?php } ?>>37</option>
					<option value="38"<?php if ($c->revi_no == 38) { ?> selected="selected"<?php } ?>>38</option>
					<option value="39"<?php if ($c->revi_no == 39) { ?> selected="selected"<?php } ?>>39</option>
					<option value="40"<?php if ($c->revi_no == 40) { ?> selected="selected"<?php } ?>>40</option>
					<option value="41"<?php if ($c->revi_no == 41) { ?> selected="selected"<?php } ?>>41</option>
					<option value="42"<?php if ($c->revi_no == 42) { ?> selected="selected"<?php } ?>>42</option>
					<option value="43"<?php if ($c->revi_no == 43) { ?> selected="selected"<?php } ?>>43</option>
					<option value="44"<?php if ($c->revi_no == 44) { ?> selected="selected"<?php } ?>>44</option>
					<option value="45"<?php if ($c->revi_no == 45) { ?> selected="selected"<?php } ?>>45</option>
					<option value="46"<?php if ($c->revi_no == 46) { ?> selected="selected"<?php } ?>>46</option>
					<option value="47"<?php if ($c->revi_no == 47) { ?> selected="selected"<?php } ?>>47</option>
					<option value="48"<?php if ($c->revi_no == 48) { ?> selected="selected"<?php } ?>>48</option>
					<option value="49"<?php if ($c->revi_no == 49) { ?> selected="selected"<?php } ?>>49</option>
					<option value="50"<?php if ($c->revi_no == 50) { ?> selected="selected"<?php } ?>>50</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Set the number of related videos to display</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show <b>More Category Videos</b></td>
				<td width="20%" align="left" valign="top">
				<select name="showmftc" size="1" class="inputbox">
					<option value="1"<?php if ($c->showmftc == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showmftc == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Allow users to see other videos in the same category</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Number of videos to display</td>
				<td width="20%" align="left" valign="top">
				<select name="mftc_no" size="1" class="inputbox">
					<option value="0"<?php if ($c->mftc_no == 0) { ?> selected="selected"<?php } ?>>0</option>
					<option value="1"<?php if ($c->mftc_no == 1) { ?> selected="selected"<?php } ?>>1</option>
					<option value="2"<?php if ($c->mftc_no == 2) { ?> selected="selected"<?php } ?>>2</option>
					<option value="3"<?php if ($c->mftc_no == 3) { ?> selected="selected"<?php } ?>>3</option>
					<option value="4"<?php if ($c->mftc_no == 4) { ?> selected="selected"<?php } ?>>4</option>
					<option value="5"<?php if ($c->mftc_no == 5) { ?> selected="selected"<?php } ?>>5</option>
					<option value="6"<?php if ($c->mftc_no == 6) { ?> selected="selected"<?php } ?>>6</option>
					<option value="7"<?php if ($c->mftc_no == 7) { ?> selected="selected"<?php } ?>>7</option>
					<option value="8"<?php if ($c->mftc_no == 8) { ?> selected="selected"<?php } ?>>8</option>
					<option value="9"<?php if ($c->mftc_no == 9) { ?> selected="selected"<?php } ?>>9</option>
					<option value="10"<?php if ($c->mftc_no == 10) { ?> selected="selected"<?php } ?>>10</option>
					<option value="11"<?php if ($c->mftc_no == 11) { ?> selected="selected"<?php } ?>>11</option>
					<option value="12"<?php if ($c->mftc_no == 12) { ?> selected="selected"<?php } ?>>12</option>
					<option value="13"<?php if ($c->mftc_no == 13) { ?> selected="selected"<?php } ?>>13</option>
					<option value="14"<?php if ($c->mftc_no == 14) { ?> selected="selected"<?php } ?>>14</option>
					<option value="15"<?php if ($c->mftc_no == 15) { ?> selected="selected"<?php } ?>>15</option>
					<option value="16"<?php if ($c->mftc_no == 16) { ?> selected="selected"<?php } ?>>16</option>
					<option value="17"<?php if ($c->mftc_no == 17) { ?> selected="selected"<?php } ?>>17</option>
					<option value="18"<?php if ($c->mftc_no == 18) { ?> selected="selected"<?php } ?>>18</option>
					<option value="19"<?php if ($c->mftc_no == 19) { ?> selected="selected"<?php } ?>>19</option>
					<option value="20"<?php if ($c->mftc_no == 20) { ?> selected="selected"<?php } ?>>20</option>
					<option value="21"<?php if ($c->mftc_no == 21) { ?> selected="selected"<?php } ?>>21</option>
					<option value="22"<?php if ($c->mftc_no == 22) { ?> selected="selected"<?php } ?>>22</option>
					<option value="23"<?php if ($c->mftc_no == 23) { ?> selected="selected"<?php } ?>>23</option>
					<option value="24"<?php if ($c->mftc_no == 24) { ?> selected="selected"<?php } ?>>24</option>
					<option value="25"<?php if ($c->mftc_no == 25) { ?> selected="selected"<?php } ?>>25</option>
					<option value="26"<?php if ($c->mftc_no == 26) { ?> selected="selected"<?php } ?>>26</option>
					<option value="27"<?php if ($c->mftc_no == 27) { ?> selected="selected"<?php } ?>>27</option>
					<option value="28"<?php if ($c->mftc_no == 28) { ?> selected="selected"<?php } ?>>28</option>
					<option value="29"<?php if ($c->mftc_no == 29) { ?> selected="selected"<?php } ?>>29</option>
					<option value="30"<?php if ($c->mftc_no == 30) { ?> selected="selected"<?php } ?>>30</option>
					<option value="31"<?php if ($c->mftc_no == 31) { ?> selected="selected"<?php } ?>>31</option>
					<option value="32"<?php if ($c->mftc_no == 32) { ?> selected="selected"<?php } ?>>32</option>
					<option value="33"<?php if ($c->mftc_no == 33) { ?> selected="selected"<?php } ?>>33</option>
					<option value="34"<?php if ($c->mftc_no == 34) { ?> selected="selected"<?php } ?>>34</option>
					<option value="35"<?php if ($c->mftc_no == 35) { ?> selected="selected"<?php } ?>>35</option>
					<option value="36"<?php if ($c->mftc_no == 36) { ?> selected="selected"<?php } ?>>36</option>
					<option value="37"<?php if ($c->mftc_no == 37) { ?> selected="selected"<?php } ?>>37</option>
					<option value="38"<?php if ($c->mftc_no == 38) { ?> selected="selected"<?php } ?>>38</option>
					<option value="39"<?php if ($c->mftc_no == 39) { ?> selected="selected"<?php } ?>>39</option>
					<option value="40"<?php if ($c->mftc_no == 40) { ?> selected="selected"<?php } ?>>40</option>
					<option value="41"<?php if ($c->mftc_no == 41) { ?> selected="selected"<?php } ?>>41</option>
					<option value="42"<?php if ($c->mftc_no == 42) { ?> selected="selected"<?php } ?>>42</option>
					<option value="43"<?php if ($c->mftc_no == 43) { ?> selected="selected"<?php } ?>>43</option>
					<option value="44"<?php if ($c->mftc_no == 44) { ?> selected="selected"<?php } ?>>44</option>
					<option value="45"<?php if ($c->mftc_no == 45) { ?> selected="selected"<?php } ?>>45</option>
					<option value="46"<?php if ($c->mftc_no == 46) { ?> selected="selected"<?php } ?>>46</option>
					<option value="47"<?php if ($c->mftc_no == 47) { ?> selected="selected"<?php } ?>>47</option>
					<option value="48"<?php if ($c->mftc_no == 48) { ?> selected="selected"<?php } ?>>48</option>
					<option value="49"<?php if ($c->mftc_no == 49) { ?> selected="selected"<?php } ?>>49</option>
					<option value="50"<?php if ($c->mftc_no == 50) { ?> selected="selected"<?php } ?>>50</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Set the number of related videos to display</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWA2G ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showa2gb" size="1" class="inputbox">
					<option value="1"<?php if ($c->showa2gb == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showa2gb == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWA2G_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show <b>Download Original</b> Link</td>
				<td width="20%" align="left" valign="top">
				<select name="showdlor" size="1" class="inputbox">
					<option value="1"<?php if ($c->showdlor == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showdlor == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">For local videos you can allow users to download the original unconverted video</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show <b>Download FLV</b> Link</td>
				<td width="20%" align="left" valign="top">
				<select name="showdlfl" size="1" class="inputbox">
					<option value="1"<?php if ($c->showdlfl == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showdlfl == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">For local videos you can allow users to download the converted flv video</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show <b>Display Original</b> Link</td>
				<td width="20%" align="left" valign="top">
				<select name="showvuor" size="1" class="inputbox">
					<option value="1"<?php if ($c->showvuor == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showvuor == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">For third party videos you can give users a link to the original video page</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Show <b>Next & Previous</b> Video Links</td>
				<td width="20%" align="left" valign="top">
				<select name="showprnx" size="1" class="inputbox">
					<option value="1"<?php if ($c->showprnx == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->showprnx == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Allow users to scroll through category videos using text links</td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_VPINTMET ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_RATESYS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="ajaxratemeth" size="1" class="inputbox">
					<option value="1"<?php if ($c->ajaxratemeth == 1) { ?> selected="selected"<?php } ?>>AJAX</option>
					<option value="0"<?php if ($c->ajaxratemeth == 0) { ?> selected="selected"<?php } ?>>POST</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_RATESYS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_FAVSYS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="ajaxfavmeth" size="1" class="inputbox">
					<option value="1"<?php if ($c->ajaxfavmeth == 1) { ?> selected="selected"<?php } ?>>AJAX</option>
					<option value="0"<?php if ($c->ajaxfavmeth == 0) { ?> selected="selected"<?php } ?>>POST</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_FAVSYS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_REPORTSYS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="ajaxrepmeth" size="1" class="inputbox">
					<option value="1"<?php if ($c->ajaxrepmeth == 1) { ?> selected="selected"<?php } ?>>AJAX</option>
					<option value="0"<?php if ($c->ajaxrepmeth == 0) { ?> selected="selected"<?php } ?>>POST</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_REPORTSYS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_A2GSYS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="ajaxa2gmeth" size="1" class="inputbox">
					<option value="1"<?php if ($c->ajaxa2gmeth == 1) { ?> selected="selected"<?php } ?>>AJAX</option>
					<option value="0"<?php if ($c->ajaxa2gmeth == 0) { ?> selected="selected"<?php } ?>>POST</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_A2GSYS_DESC ?></td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_ORDERING ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CORDERING ?></td>
				<td width="20%" align="left" valign="top">
				<select name="cordering" size="1" class="inputbox">
					<option value="orderASC"<?php if ($c->cordering == "orderASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_ORDERING; ?> ASC</option>
					<option value="orderDESC"<?php if ($c->cordering == "orderDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_ORDERING; ?> DESC</option>
					<option value="nameASC"<?php if ($c->cordering == "nameASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_NAME; ?> ASC</option>
					<option value="nameDESC"<?php if ($c->cordering == "nameDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_NAME; ?> DESC</option>
					<option value="novidsASC"<?php if ($c->cordering == "novidsASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_NOVIDS; ?> ASC</option>
					<option value="novidsDESC"<?php if ($c->cordering == "novidsDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_NOVIDS; ?> DESC</option>
					<option value="nosubsASC"<?php if ($c->cordering == "nosubsASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_NOSUBS; ?> ASC</option>
					<option value="nosubsDESC"<?php if ($c->cordering == "nosubsDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_NOSUBS; ?> DESC</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CORDERING_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CVORDERING ?></td>
				<td width="20%" align="left" valign="top">
				<select name="cvordering" size="1" class="inputbox">
					<option value="orderASC"<?php if ($c->cvordering == "orderASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_ORDERING; ?> ASC</option>
					<option value="orderDESC"<?php if ($c->cvordering == "orderDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_ORDERING; ?> DESC</option>
					<option value="dateASC"<?php if ($c->cvordering == "dateASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_UPLDDATE; ?> ASC</option>
					<option value="dateDESC"<?php if ($c->cvordering == "dateDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_UPLDDATE; ?> DESC</option>
					<option value="nameASC"<?php if ($c->cvordering == "nameASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_NAME; ?> ASC</option>
					<option value="nameDESC"<?php if ($c->cvordering == "nameDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_NAME; ?> DESC</option>
					<option value="hitsASC"<?php if ($c->cvordering == "hitsASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_HITS; ?> ASC</option>
					<option value="hitsDESC"<?php if ($c->cvordering == "hitsDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_HITS; ?> DESC</option>
					<option value="voteASC"<?php if ($c->cvordering == "voteASC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_RATING; ?> ASC</option>
					<option value="voteDESC"<?php if ($c->cvordering == "voteDESC") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_RATING; ?> DESC</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CVORDERING_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CUSTORDERING ?></td>
				<td width="20%" align="left" valign="top">
				<select name="custordering" size="1" class="inputbox">
					<option value="1"<?php if ($c->custordering == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->custordering == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CUSTORDERING_DESC ?></td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Default Scroll Bar Options</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Number of videos</td>
				<td width="20%" align="left" valign="top">
				<select name="scroll_no" size="1" class="inputbox">
					<option value="1"<?php if ($c->scroll_no == "1") { ?> selected="selected"<?php } ?>>1</option>
					<option value="2"<?php if ($c->scroll_no == "2") { ?> selected="selected"<?php } ?>>2</option>
					<option value="3"<?php if ($c->scroll_no == "3") { ?> selected="selected"<?php } ?>>3</option>
					<option value="4"<?php if ($c->scroll_no == "4") { ?> selected="selected"<?php } ?>>4</option>
					<option value="5"<?php if ($c->scroll_no == "5") { ?> selected="selected"<?php } ?>>5</option>
					<option value="6"<?php if ($c->scroll_no == "6") { ?> selected="selected"<?php } ?>>6</option>
					<option value="7"<?php if ($c->scroll_no == "7") { ?> selected="selected"<?php } ?>>7</option>
					<option value="8"<?php if ($c->scroll_no == "8") { ?> selected="selected"<?php } ?>>8</option>
					<option value="9"<?php if ($c->scroll_no == "9") { ?> selected="selected"<?php } ?>>9</option>
					<option value="10"<?php if ($c->scroll_no == "10") { ?> selected="selected"<?php } ?>>10</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">The number of videos shown in the scroll bar</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Animation speed</td>
				<td width="20%" align="left" valign="top">
				<select name="scroll_as" size="1" class="inputbox">
					<option value="0.10"<?php if ($c->scroll_as == "0.05") { ?> selected="selected"<?php } ?>>Very Fast</option>
					<option value="0.10"<?php if ($c->scroll_as == "0.10") { ?> selected="selected"<?php } ?>>Fast</option>
					<option value="0.25"<?php if ($c->scroll_as == "0.25") { ?> selected="selected"<?php } ?>>Normal</option>
					<option value="0.50"<?php if ($c->scroll_as == "0.50") { ?> selected="selected"<?php } ?>>Slow</option>
					<option value="0.90"<?php if ($c->scroll_as == "0.90") { ?> selected="selected"<?php } ?>>Very Slow</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">The speed of the scrolling animation</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Auto Scroll</td>
				<td width="20%" align="left" valign="top">
				<select name="scroll_au" size="1" class="inputbox">
					<option value="0"<?php if ($c->scroll_au == 0) { ?> selected="selected"<?php } ?>>Off</option>
					<option value="1000"<?php if ($c->scroll_au == 1000) { ?> selected="selected"<?php } ?>>Very Fast</option>
					<option value="2000"<?php if ($c->scroll_au == 2000) { ?> selected="selected"<?php } ?>>Fast</option>
					<option value="3000"<?php if ($c->scroll_au == 3000) { ?> selected="selected"<?php } ?>>Normal</option>
					<option value="4000"<?php if ($c->scroll_au == 4000) { ?> selected="selected"<?php } ?>>Slow</option>
					<option value="5000"<?php if ($c->scroll_au == 5000) { ?> selected="selected"<?php } ?>>Very Slow</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">The time between auto scrolling</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top">Continous Scrolling</td>
				<td width="20%" align="left" valign="top">
				<select name="scroll_wr" size="1" class="inputbox">
					<option value="true"<?php if ($c->scroll_wr == "true") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="false"<?php if ($c->scroll_wr == "false") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">When the scroll bar reaches hte end wrap the scoller back to the start</td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Category Settings</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Hide empty categories</td>
				<td width="20%" align="left" valign="top">
				<select name="cat_he" size="1" class="inputbox">
					<option value="1"<?php if ($c->cat_he == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES.' '; ?></option>
					<option value="0"<?php if ($c->cat_he == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Hide categories that contain no videos from the main category list.</td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_GROUPS ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_GPP ?></td>
				<td width="20%" align="left" valign="top">
				<select name="gpp" size="1" class="inputbox">
					<option value="5"<?php if ($c->gpp == 5) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_05.' '; ?></option>
					<option value="10"<?php if ($c->gpp == 10) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_10.' '; ?></option>
					<option value="15"<?php if ($c->gpp == 15) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_15.' '; ?></option>
					<option value="20"<?php if ($c->gpp == 20) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_20.' '; ?></option>
					<option value="25"<?php if ($c->gpp == 25) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_25.' '; ?></option>
					<option value="30"<?php if ($c->gpp == 30) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_30.' '; ?></option>
					<option value="35"<?php if ($c->gpp == 35) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_35.' '; ?></option>
					<option value="40"<?php if ($c->gpp == 40) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_40.' '; ?></option>
					<option value="45"<?php if ($c->gpp == 45) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_45.' '; ?></option>
					<option value="50"<?php if ($c->gpp == 50) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_50.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_GPP_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_FGROUPS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="fpfeaturedgroups" size="1" class="inputbox">
					<option value="1"<?php if ($c->fpfeaturedgroups == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_01.' '; ?></option>
					<option value="2"<?php if ($c->fpfeaturedgroups == 2) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_02.' '; ?></option>
					<option value="3"<?php if ($c->fpfeaturedgroups == 3) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_03.' '; ?></option>
					<option value="4"<?php if ($c->fpfeaturedgroups == 4) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_04.' '; ?></option>
					<option value="5"<?php if ($c->fpfeaturedgroups == 5) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_05.' '; ?></option>
					<option value="6"<?php if ($c->fpfeaturedgroups == 6) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_06.' '; ?></option>
					<option value="7"<?php if ($c->fpfeaturedgroups == 7) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_07.' '; ?></option>
					<option value="8"<?php if ($c->fpfeaturedgroups == 8) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_08.' '; ?></option>
					<option value="9"<?php if ($c->fpfeaturedgroups == 9) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_09.' '; ?></option>
					<option value="10"<?php if ($c->fpfeaturedgroups == 10) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_10.' '; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_FGROUPS_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_TRUNCATE ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TRUNTITLE ?></td>
				<td align="left" valign="top" width="20%"><input type="text" name="truntitle" value="<?php echo $c->truntitle; ?>" size="7" maxlength="100"></td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TRUNTITLE_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TRUNVDESC ?></td>
				<td align="left" valign="top" width="20%"><input type="text" name="trunvdesc" value="<?php echo $c->trunvdesc; ?>" size="7" maxlength="100"></td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TRUNVDESC_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TRUNCDESC ?></td>
				<td align="left" valign="top" width="20%"><input type="text" name="truncdesc" value="<?php echo $c->truncdesc; ?>" size="7" maxlength="100"></td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TRUNCDESC_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TRUNGDESC ?></td>
				<td align="left" valign="top" width="20%"><input type="text" name="trungdesc" value="<?php echo $c->trungdesc; ?>" size="7" maxlength="100"></td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TRUNGDESC_DESC ?></td>
			  </tr>
			</table>

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="layoutsettings" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<div style="clear:both;"></div>
		<?php
		/** display template **/
		$smartyvs->display('admin_footer.tpl');
	}
}
?>