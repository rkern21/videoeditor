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
	function showgeneralsettings(&$gtree)
	{
		global $j15, $j16, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db =& JFactory::getDBO();
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs');
		$startpane = $pane->startPane( 'content-pane' );
		$endtab = $pane->endPanel();
		$endpane = $pane->endPane();
		$starttab1 = $pane->startPanel( _HWDVIDS_TAB_SETUP, 'panel1' );
		$starttab2 = $pane->startPanel( _HWDVIDS_TAB_SETTS, 'panel2' );
		$starttab3 = $pane->startPanel( _HWDVIDS_TAB_UPLDS, 'panel3' );
		$starttab4 = $pane->startPanel( _HWDVIDS_TAB_TP, 'panel4' );
		$starttab5 = $pane->startPanel( _HWDVIDS_TAB_SHARES, 'panel5' );
		$starttab6 = $pane->startPanel( _HWDVIDS_TAB_APVLS, 'panel6' );
		$starttab7 = $pane->startPanel( _HWDVIDS_TAB_CONVNS, 'panel7' );
		$starttab8 = $pane->startPanel( _HWDVIDS_TAB_NOTFY, 'panel8' );
		$starttab9 = $pane->startPanel( _HWDVIDS_TAB_XML, 'panel9' );
		$starttab10 = $pane->startPanel( _HWDVIDS_TAB_INTGTN, 'panel10' );
		$starttab11 = $pane->startPanel( _HWDVIDS_TAB_ACCESS, 'panel11' );

		/** assign template variables **/
		$smartyvs->assign( "header_title", _HWDVIDS_SECTIONHEAD_GS );

		/** display template **/
		$smartyvs->display('admin_header.tpl');
		include(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');

  		?>
  		<div style="border: solid 1px #333;margin:5px 0 5px 0;padding:5px;text-align:left;font-weight:bold;">
  		  <ul id="submenu">
            <li>
              <a class="active" href="index.php?option=com_hwdvideoshare&task=generalsettings">General Settings</a>
            </li>
            <li>
              <a href="index.php?option=com_hwdvideoshare&task=layoutsettings">Layout Settings</a>
            </li>
            <li>
              <a href="index.php?option=com_plugins&filter_type=hwdvs-template">Template Settings</a>
            </li>
            <?php
			$jconfig = new jconfig();
			if ($jconfig->ftp_enable != 1)
			{
				if (is_writable(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php'))
				{
					$config_file_status = "<span style=\"color:#458B00;\">"._HWDVIDS_INFO_CONFIGF2."</span>.";
				}
				else
				{
					$config_file_status = '<span style="color:#ff0000;">'._HWDVIDS_INFO_CONFIGF3.'</span> ('.JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php)';
				}
            	echo "<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"._HWDVIDS_INFO_CONFIGF1." ".$config_file_status."</li>";
			}
			?>
          </ul>
        <div style="clear:both;"></div>
        </div>
		<?php
		$upload_max = preg_replace("/[^0-9s]/", "", ini_get("upload_max_filesize"));
		$post_max = preg_replace("/[^0-9s]/", "", ini_get("post_max_size"));

		// small test to check if auto-convert
		$check_direct=hwd_vs_check_autoconversion::checkDirectExecution();
		$check_wget01=hwd_vs_check_autoconversion::checkWget01Execution();
		$check_wget02=hwd_vs_check_autoconversion::checkWget02Execution();
		?>
  		<div style="width:100%;margin-top:5px;text-align:left;">
			<?php
			echo $startpane;
			echo $starttab1;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr>
			    <td align="left" valign="top">
			    <?php
			    if (is_callable('exec') && function_exists('exec')) {
			    	echo "<img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The exec() function <font color=\"green\"><b>is available</b></font><br />";
			    } else {
			    	echo "<img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The exec() function <font color=\"red\"><b>is not available</b></font><br />";
			    }
			    if (is_callable('curl_init') && is_callable('curl_exec') && function_exists('curl_init') && function_exists('curl_exec')) {
			    	echo "<img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The cURL library <font color=\"green\"><b>is available</b></font><br />";
			    } else {
			    	echo "<img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The cURL library <font color=\"red\"><b>is not available</b></font><br />";
			    }
			    if (is_callable('fsockopen') && function_exists('fsockopen')) {
			    	echo "<img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The fsockopen() function <font color=\"green\"><b>is available</b></font><br />";
			    } else {
			    	echo "<img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The fsockopen() function <font color=\"red\"><b>is not available</b></font><br />";
			    }
			    if (ini_get('safe_mode')) {
			    	echo "<img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The PHP safe mode is <font color=\"red\"><b>On</b></font><br />";
			    } else {
			    	echo "<img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" />The PHP safe mode is <font color=\"green\"><b>Off</b></font><br />";
			    }
			    ?>
			    </td>
			  </tr>
			</table>
			<div style="border:1px solid #333333;margin: 0 0 5px 0;padding: 5px;font-weight: bold;">
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_SOFTINS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="requiredins" size="1" class="inputbox">
					<option value="1"<?php if ($c->requiredins == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->requiredins == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SOFTINS_DESC ?></td>
			  </tr>
			</table>
			<div style="border:1px solid #458B00;color:#333333;background:#dfead5;margin: 5px 0 5px 0;padding: 5px;font-weight: bold;"><?php echo _ADMIN_HWDVIDS_SETT_SOFTINS_YES ?></div>
			<div style="border:1px solid #c30;color:#333333;background:#e9ddd9;margin: 5px 0 5px 0;padding: 5px;font-weight: bold;"><?php echo _ADMIN_HWDVIDS_SETT_SOFTINS_NO ?></div>
			</div>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_PLUGINS ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_TEMPLATE ?></td>
				<td width="20%" align="left" valign="top">
				<select name="template" size="1" class="inputbox">
				<option value=""><?php echo _ADMIN_HWDVIDS_SELECT_TEMPLATE ?></option>
				<?php
				$query = 'SELECT *'
					. ' FROM #__plugins'
					. ' WHERE published = 1'
					. ' AND folder = "hwdvs-template"'
					. ' ORDER BY name ASC'
					;
				$db->setQuery( $query );
				$rows = $db->loadObjectList();
					for ($i=0, $n=count($rows); $i < $n; $i++) {
						$row = $rows[$i];
						?>
						<option value="<?php echo $row->element."|".$row->folder; ?>" <?php if ($c->hwdvids_template_file == $row->element) echo "selected=\"selected\"" ?>><?php echo $row->name; ?></option>
						<?php
					}
				?>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TEMPLATE_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_VPLAYER ?></td>
				<td width="20%" align="left" valign="top">
				<select name="videoplayer" size="1" class="inputbox">
				<option value=""><?php echo _ADMIN_HWDVIDS_SELECT_PLAYER ?></option>
				<?php
				$query = 'SELECT *'
					. ' FROM #__plugins'
					. ' WHERE published = 1'
					. ' AND folder = "hwdvs-videoplayer"'
					. ' ORDER BY name ASC'
					;
				$db->setQuery( $query );
				$rows = $db->loadObjectList();
					for ($i=0, $n=count($rows); $i < $n; $i++) {
						$row = $rows[$i];
						?>
						<option value="<?php echo $row->element."|".$row->folder; ?>" <?php if ($c->hwdvids_videoplayer_file == $row->element) echo "selected=\"selected\"" ?>><?php echo $row->name; ?></option>
						<?php
					}
				?>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_VPLAYER_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_LANGUAGE ?></td>
				<td width="20%" align="left" valign="top">
				<select name="language" size="1" class="inputbox">
				<option value=""><?php echo _ADMIN_HWDVIDS_SELECT_LANGUAGE ?></option>
				<option value="joomfish|joomfish" <?php if ($c->hwdvids_language_path == "joomfish") echo "selected=\"selected\"" ?>><?php echo _ADMIN_HWDVIDS_LANGUAGE_JOOMFISH ?></option>
				<?php
				$query = 'SELECT *'
					. ' FROM #__plugins'
					. ' WHERE folder = "hwdvs-language"'
					. ' ORDER BY name ASC'
					;
				$db->setQuery( $query );
				$rows = $db->loadObjectList();
					for ($i=0, $n=count($rows); $i < $n; $i++) {
						$row = $rows[$i];
						?>
						<option value="<?php echo $row->element."|".$row->folder; ?>" <?php if ($c->hwdvids_language_file == $row->element) echo "selected=\"selected\"" ?>><?php echo $row->name; ?></option>
						<?php
					}
				?>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_LANGUAGE_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b>Select Storage</b></td>
				<td width="20%" align="left" valign="top">
				<select name="storagetype" size="1" class="inputbox">
				<option value="">Select Storage</option>
				<option value="0" <?php if ($c->storagetype == "0") echo "selected=\"selected\"" ?>>Local Server Storage</option>
				<?php
				$query = 'SELECT *'
					. ' FROM #__plugins'
					. ' WHERE published = 1'
					. ' AND folder = "hwdvs-storage"'
					. ' ORDER BY name ASC'
					;
				$db->setQuery( $query );
				$rows = $db->loadObjectList();
					for ($i=0, $n=count($rows); $i < $n; $i++) {
						$row = $rows[$i];
						?>
						<option value="<?php echo $row->element; ?>" <?php if ($c->storagetype == $row->element) echo "selected=\"selected\"" ?>><?php echo $row->name; ?></option>
						<?php
					}
				?>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Choose the hwdVideoShare storage plugin. To add more storage solutions, install language plugins through the plugin manager.</td>
			  </tr>
			</table>
			<?php
			if (empty($c->vsdirectory))
			{
			$c->vsdirectory = JPATH_SITE.DS."hwdvideos".DS;
			}

			if (!file_exists($c->vsdirectory))
			{
				echo '<div style="border:1px solid #c30;color:#333333;background:#e9ddd9;margin: 0;padding: 5px;font-weight: bold;">Your selected Local Storage directory is missing. (Using default location instead: '.JPATH_SITE.DS.'hwdvideos'.DS.')</div>';
			}
			?>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b>Video Directory Location</td>
				<td align="left" valign="top" width="20%"><input type="text" name="vsdirectory" value="<?php echo $c->vsdirectory; ?>" size="40" maxlength="100"></td>
				<td width="60%" align="left" valign="top">Full server path to the local video directory</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b>Use Anti-Leech Protection</td>
				<td width="20%" align="left" valign="top">
				<select name="use_protection" size="1" class="inputbox">
					<option value="1"<?php if ($c->use_protection == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->use_protection == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Use the anti-leech system to prevent hotlinking and hide your video files.</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b>Protection Level</td>
				<td width="20%" align="left" valign="top">
				<select name="protection_level" size="1" class="inputbox">
					<option value="1"<?php if ($c->protection_level == 1) { ?> selected="selected"<?php } ?>>More Secure</option>
					<option value="3"<?php if ($c->protection_level == 3) { ?> selected="selected"<?php } ?>>Standard</option>
					<option value="10"<?php if ($c->protection_level == 10) { ?> selected="selected"<?php } ?>>Less Secure</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Choose the level of protection you wish to use secure your video files.</td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab2;
			?>
			<div style="margin:1px;padding:1px;"><br />

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>High/Standard Definition Videos</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Use HD Videos?</td>
				<td width="20%" align="left" valign="top">
				<select name="usehq" size="1" class="inputbox">
					<!--<option value="3"<?php if ($c->usehq == 3) { ?> selected="selected"<?php } ?>>Yes, only use HD videos</option>-->
					<option value="3"<?php if ($c->usehq == 3) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?> (HD Only)</option>
					<option value="2"<?php if ($c->usehq == 2) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?> (Switchable/SD Default)</option>
					<option value="1"<?php if ($c->usehq == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?> (Switchable/HD Default)</option>
					<option value="0"<?php if ($c->usehq == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?> (SD Only)</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Select the playback options for video quality, where HD videos are avaliable.</td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_SOCBM ?></h3></td></tr>
			  <tr><td align="left" valign="top" colspan="3">
				<input type="checkbox" name="sb_digg" value="on" <?php if ($c->sb_digg == "on") { ?> checked="checked"<?php } ?> />Digg!&#160;
				<input type="checkbox" name="sb_reddit" value="on" <?php if ($c->sb_reddit == "on") { ?> checked="checked"<?php } ?> />Reddit!&#160;
				<input type="checkbox" name="sb_delicious" value="on" <?php if ($c->sb_delicious == "on") { ?> checked="checked"<?php } ?> />Del.icio.us!&#160;
				<input type="checkbox" name="sb_google" value="on" <?php if ($c->sb_google == "on") { ?> checked="checked"<?php } ?> />Google!&#160;
				<input type="checkbox" name="sb_live" value="on" <?php if ($c->sb_live == "on") { ?> checked="checked"<?php } ?> />Live!&#160;
				<input type="checkbox" name="sb_facebook" value="on" <?php if ($c->sb_facebook == "on") { ?> checked="checked"<?php } ?> />Facebook!&#160;
				<input type="checkbox" name="sb_slashdot" value="on" <?php if ($c->sb_slashdot == "on") { ?> checked="checked"<?php } ?> />Slashdot!&#160;
				<input type="checkbox" name="sb_netscape" value="on" <?php if ($c->sb_netscape == "on") { ?> checked="checked"<?php } ?> />Netscape!&#160;
				<input type="checkbox" name="sb_technorati" value="on" <?php if ($c->sb_technorati == "on") { ?> checked="checked"<?php } ?> />Technorati!&#160;
				<input type="checkbox" name="sb_stumbleupon" value="on" <?php if ($c->sb_stumbleupon == "on") { ?> checked="checked"<?php } ?> />StumbleUpon!&#160;
				<input type="checkbox" name="sb_spurl" value="on" <?php if ($c->sb_spurl == "on") { ?> checked="checked"<?php } ?> />Spurl!&#160;
				<input type="checkbox" name="sb_wists" value="on" <?php if ($c->sb_wists == "on") { ?> checked="checked"<?php } ?> />Wists!&#160;
				<input type="checkbox" name="sb_simpy" value="on" <?php if ($c->sb_simpy == "on") { ?> checked="checked"<?php } ?> />Simpy!&#160;
				<input type="checkbox" name="sb_newsvine" value="on" <?php if ($c->sb_newsvine == "on") { ?> checked="checked"<?php } ?> />Newsvine!&#160;
				<input type="checkbox" name="sb_blinklist" value="on" <?php if ($c->sb_blinklist == "on") { ?> checked="checked"<?php } ?> />Blinklist!&#160;
				<input type="checkbox" name="sb_furl" value="on" <?php if ($c->sb_furl == "on") { ?> checked="checked"<?php } ?> />Furl!&#160;
				<input type="checkbox" name="sb_fark" value="on" <?php if ($c->sb_fark == "on") { ?> checked="checked"<?php } ?> />Fark!&#160;
				<input type="checkbox" name="sb_blogmarks" value="on" <?php if ($c->sb_blogmarks == "on") { ?> checked="checked"<?php } ?> />Blogmarks!&#160;
				<input type="checkbox" name="sb_yahoo" value="on" <?php if ($c->sb_yahoo == "on") { ?> checked="checked"<?php } ?> />Yahoo!&#160;
				<input type="checkbox" name="sb_smarking" value="on" <?php if ($c->sb_smarking == "on") { ?> checked="checked"<?php } ?> />Smarking!&#160;
				<input type="checkbox" name="sb_netvouz" value="on" <?php if ($c->sb_netvouz == "on") { ?> checked="checked"<?php } ?> />Netvouz!&#160;
				<input type="checkbox" name="sb_shadows" value="on" <?php if ($c->sb_shadows == "on") { ?> checked="checked"<?php } ?> />Shadows!&#160;
				<input type="checkbox" name="sb_rawsugar" value="on" <?php if ($c->sb_rawsugar == "on") { ?> checked="checked"<?php } ?> />RawSugar!&#160;
				<input type="checkbox" name="sb_magnolia" value="on" <?php if ($c->sb_magnolia == "on") { ?> checked="checked"<?php } ?> />Ma.gnolia!&#160;
				<input type="checkbox" name="sb_plugim" value="on" <?php if ($c->sb_plugim == "on") { ?> checked="checked"<?php } ?> />PlugIM!&#160;
				<input type="checkbox" name="sb_squidoo" value="on" <?php if ($c->sb_squidoo == "on") { ?> checked="checked"<?php } ?> />Squidoo!&#160;
				<input type="checkbox" name="sb_blogmemes" value="on" <?php if ($c->sb_blogmemes == "on") { ?> checked="checked"<?php } ?> />BlogMemes!&#160;
				<input type="checkbox" name="sb_feedmelinks" value="on" <?php if ($c->sb_feedmelinks == "on") { ?> checked="checked"<?php } ?> />FeedMeLinks!&#160;
				<input type="checkbox" name="sb_blinkbits" value="on" <?php if ($c->sb_blinkbits == "on") { ?> checked="checked"<?php } ?> />BlinkBits!&#160;
				<input type="checkbox" name="sb_tailrank" value="on" <?php if ($c->sb_tailrank == "on") { ?> checked="checked"<?php } ?> />Tailrank!&#160;
				<input type="checkbox" name="sb_linkagogo" value="on" <?php if ($c->sb_linkagogo == "on") { ?> checked="checked"<?php } ?> />linkaGoGo!&#160;
			  </td></tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3">
			  <h3>Javascript Conflicts</h3>
			  <p>hwdVideoShare uses popular javascript frameworks such as Mootools and Prototype.
			  Many other Joomla extensions and templates also use these frameworks. Unfortunately,
			  when different versions of these frameworks are loaded into a webpage, conflicts can occur that cause
			  website features to stop working. You can avoid this problem by only loading one version of each javascript framework.
			  To help you achieve this more easily, use the options below to stop hwdVideoShare loading a new version of
			  each javascript.</p></td></tr>
			  <tr><td align="left" valign="top" colspan="3">
				<input type="checkbox" name="loadmootools" value="on" <?php if ($c->loadmootools == "on") { ?> checked="checked"<?php } ?> />Load <a href="http://mootools.net/" target="_blank">MooTools</a>&#160;<br />
				<input type="checkbox" name="loadprototype" value="on" <?php if ($c->loadprototype == "on") { ?> checked="checked"<?php } ?> />Load <a href="http://www.prototypejs.org/" target="_blank">Prototype</a>&#160;<br />
				<input type="checkbox" name="loadscriptaculous" value="on" <?php if ($c->loadscriptaculous == "on") { ?> checked="checked"<?php } ?> />Load <a href="http://script.aculo.us/" target="_blank">Script.aculo.us</a>&#160;<br />
				<input type="checkbox" name="loadswfobject" value="on" <?php if ($c->loadswfobject == "on") { ?> checked="checked"<?php } ?> />Load <a href="http://code.google.com/p/swfobject/" target="_blank">SWFObject</a>&#160;<br />
			  </td></tr>
			  <tr><td align="left" valign="top" colspan="3"><h3>SWFObject Framework</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">SWFObject Version</td>
				<td width="20%" align="left" valign="top">
				<select name="swfobject" size="1" class="inputbox">
					<option value="1"<?php if ($c->swfobject == 1) { ?> selected="selected"<?php } ?>>1.5</option>
					<option value="2"<?php if ($c->swfobject == 2) { ?> selected="selected"<?php } ?>>2.1</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Use SWFObject1.5 or SWFObject2.1.</td>
			  </tr>
			  <tr><td align="left" valign="top" colspan="3"><h3>Internet Explorer "Operation Aborted Error" Fix</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Apply fix?</td>
				<td width="20%" align="left" valign="top">
				<select name="ieoa_fix" size="1" class="inputbox">
					<option value="1"<?php if ($c->ieoa_fix == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->ieoa_fix == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Apply a fix to prevent the Operation Aborted error in Internet Explorer.</td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Search Operations</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Search Method</td>
				<td width="20%" align="left" valign="top">
				<select name="search_method" size="1" class="inputbox">
					<option value="1"<?php if ($c->search_method == 1) { ?> selected="selected"<?php } ?>>Full-text Searching (Faster)</option>
					<option value="2"<?php if ($c->search_method == 2) { ?> selected="selected"<?php } ?>>Pattern Matching (Slower)</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">This option will add a return text link to your website that will display under all embedded videos.</td>
			  </tr>
			  <tr><td align="left" valign="top" colspan="3">
				<input type="checkbox" name="search_title" value="on" <?php if ($c->search_title == "on") { ?> checked="checked"<?php } ?> />Search Titles<br />
				<input type="checkbox" name="search_descr" value="on" <?php if ($c->search_descr == "on") { ?> checked="checked"<?php } ?> />Search Descriptions<br />
				<input type="checkbox" name="search_keywo" value="on" <?php if ($c->search_keywo == "on") { ?> checked="checked"<?php } ?> />Search Keywords<br />
			  </td></tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>User Display Details</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Display Username or Real Name</td>
				<td width="20%" align="left" valign="top">
				<select name="userdisplay" size="1" class="inputbox">
					<option value="1"<?php if ($c->userdisplay == 1) { ?> selected="selected"<?php } ?>>Username</option>
					<option value="0"<?php if ($c->userdisplay == 0) { ?> selected="selected"<?php } ?>>Real Name</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">This option will add a return text link to your website that will display under all embedded videos.</td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Embed Options</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Add return link to embed code</td>
				<td width="20%" align="left" valign="top">
				<select name="embedreturnlink" size="1" class="inputbox">
					<option value="1"<?php if ($c->embedreturnlink == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->embedreturnlink == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">This option will add a return text link to your website that will display under all embedded videos.</td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_CAPTCHA ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLECAPTCHA ?></td>
				<td width="20%" align="left" valign="top">
				<select name="disablecaptcha" size="1" class="inputbox">
					<option value="1"<?php if ($c->disablecaptcha == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->disablecaptcha == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLECAPTCHA_DESC ?></td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_CONVERTSWF ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SWFSA ?></td>
				<td width="20%" align="left" valign="top">
				<select name="standaloneswf" size="1" class="inputbox">
					<option value="1"<?php if ($c->standaloneswf == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->standaloneswf == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SWFSA_DESC ?></td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Maintenance Actions</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Send maintenance to background?</td>
				<td width="20%" align="left" valign="top">
				<select name="maintenance_bkgd" size="1" class="inputbox">
					<option value="direct"<?php if ($c->maintenance_bkgd == "direct") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH1; ?></option>
					<option value="wget1"<?php if ($c->maintenance_bkgd == "wget1") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH2; ?></option>
					<option value="wget2"<?php if ($c->maintenance_bkgd == "wget2") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH3; ?></option>
					<option value="none"<?php if ($c->maintenance_bkgd == "none") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Automatically perform maintenance actions in the background when necessary? Maintennace keeps hwdVideoShare efficient.</td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_direct == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH1." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH1." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_wget01 == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH2." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH2." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_wget02 == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH3." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH3." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_SHOWCOPYRIGHT ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWCREDIT ?></td>
				<td width="20%" align="left" valign="top">
				<select name="showcredit" size="1" class="inputbox">
					<option value="1"<?php if ($c->showcredit == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->showcredit == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWCREDIT_DESC ?></td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab3;
			?>
			<div style="margin:1px;padding:1px;"><br />
				<script language="javascript">
				function disableLocalUpload()
				{
					box = document.forms[0].disablelocupld;
					uploadstatus = box.options[box.selectedIndex].value;
					if (uploadstatus == 0)
					{
						document.getElementById('locupldmeth').disabled=false;
						document.getElementById("upldSettings").style.visibility="visible";
						document.getElementById("upldSettings").style.height="auto";
					}
					else
					{
						document.getElementById('locupldmeth').disabled=true;
						document.getElementById("upldSettings").style.visibility="hidden";
						document.getElementById("upldSettings").style.height="0px";
					}
				}
				function showWarp()
				{
					box = document.forms[0].locupldmeth;
					uploadstatus = box.options[box.selectedIndex].value;
					if (uploadstatus == 4)
					{
						document.getElementById("warpSettings").style.visibility="visible";
						document.getElementById("warpSettings").style.height="auto";
						document.getElementById("localSettings").style.visibility="hidden";
						document.getElementById("localSettings").style.height="0px";
					}
					else
					{
						document.getElementById("warpSettings").style.visibility="hidden";
						document.getElementById("warpSettings").style.height="0px";
						document.getElementById("localSettings").style.visibility="visible";
						document.getElementById("localSettings").style.height="auto";
					}
				}
				</script>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
				  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_LOCALUPLD ?></h3></td></tr>
				  <tr>
					<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLELCOAL ?></td>
					<td width="20%" align="left" valign="top">
					<select name="disablelocupld" size="1" class="inputbox" onChange="disableLocalUpload()">
						<option value="1"<?php if ($c->disablelocupld == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
						<option value="0"<?php if ($c->disablelocupld == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
					</select>
					</td>
					<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DISABLELCOAL_DESC ?></td>
				  </tr>
				</table>
				<div style="border:1px solid #333;margin: 0 0 5px 0;padding: 5px;font-weight: bold;">
				<table cellpadding="0" cellspacing="3" border="0" width="100%">
				  <tr>
					<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_UPLDMETH ?></td>
					<td width="20%" align="left" valign="top">
					<select name="locupldmeth" size="1" class="inputbox" id="locupldmeth" <?php if ($c->disablelocupld == 1) { ?> disabled="disabled"<?php } ?> onChange="showWarp()">
						<option value="3"<?php if ($c->locupldmeth == 3) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_ADVPERL; ?></option>
						<option value="2"<?php if ($c->locupldmeth == 2) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_ADVFLASH; ?></option>
						<option value="0"<?php if ($c->locupldmeth == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_BASPHP; ?></option>
						<option value="4"<?php if ($c->locupldmeth == 4) { ?> selected="selected"<?php } ?>>Remote (WARP HD)</option>
					</select>
					</td>
					<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_UPLDMETH_DESC ?></td>
				  </tr>
				</table>
				</div>
				<div style="border:1px solid #458B00;color:#333333;background:#e9efe4;margin: 0 0 5px 0;padding: 5px;"><?php echo _ADMIN_HWDVIDS_SETT_UPLDMETH_ADVPERL ?></div>
				<div style="border:1px solid #458B00;color:#333333;background:#e9efe4;margin: 0 0 5px 0;padding: 5px;"><?php echo _ADMIN_HWDVIDS_SETT_UPLDMETH_ADVFLASH ?></div>
				<div style="border:1px solid #458B00;color:#333333;background:#e9efe4;margin: 0 0 5px 0;padding: 5px;"><?php echo _ADMIN_HWDVIDS_SETT_UPLDMETH_BASPHP ?></div>
				<div style="border:1px solid #458B00;color:#333333;background:#e9efe4;margin: 0 0 5px 0;padding: 5px;"><?php echo _ADMIN_HWDVIDS_SETT_UPLDMETH_REMWARP ?></div>
				<div id="upldSettings" <?php if ($c->disablelocupld == "1") { ?> style="visibility: hidden; height: 0px;"<?php } ?>>
					<div id="localSettings" <?php if ($c->locupldmeth == "4") { ?> style="visibility: hidden; height: 0px;"<?php } ?>>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
					  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_INFORMUPLDRESTRICT ?></h3></td></tr>
					  <tr>
						<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INFORMUPLDTYPE ?></td>
						<td width="20%" align="left" valign="top">
						<select name="uploadcriteria" size="1" class="inputbox">
							<option value="1"<?php if ($c->uploadcriteria == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
							<option value="0"<?php if ($c->uploadcriteria == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
						</select>
						</td>
						<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INFORMUPLDTYPE_DESC ?></td>
					  </tr>
					</table>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
					  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_ALLOWFT ?></h3></td></tr>
					  <tr><td align="left" valign="top" colspan="3">
						<?php
						echo "<p>";
						  if ($c->requiredins == 1) {
							echo _ADMIN_HWDVIDS_SETT_ALLOWFT_CHECK;
						  } else {
							echo "<span style=\"color:#ff0000\">"._ADMIN_HWDVIDS_SETT_ALLOWFT_NOCHECK."</span>";
						  }
						echo "</p>";
						?>
						<input type="checkbox" name="ft_mpg" value="on" <?php if ($c->ft_mpg == "on") { ?> checked="checked"<?php } ?>  />mpg&#160;
						<input type="checkbox" name="ft_mpeg" value="on" <?php if ($c->ft_mpeg == "on") { ?> checked="checked"<?php } ?>  />mpeg&#160;
						<input type="checkbox" name="ft_avi" value="on" <?php if ($c->ft_avi == "on") { ?> checked="checked"<?php } ?>  />avi&#160;
						<input type="checkbox" name="ft_divx" value="on" <?php if ($c->ft_divx == "on") { ?> checked="checked"<?php } ?>  />divx&#160;
						<input type="checkbox" name="ft_mp4" value="on" <?php if ($c->ft_mp4 == "on") { ?> checked="checked"<?php } ?>  />mp4&#160;
						<input type="checkbox" name="ft_flv" value="on" <?php if ($c->ft_flv == "on") { ?> checked="checked"<?php } ?>  />flv&#160;
						<input type="checkbox" name="ft_wmv" value="on" <?php if ($c->ft_wmv == "on") { ?> checked="checked"<?php } ?>  />wmv&#160;
						<input type="checkbox" name="ft_rm" value="on" <?php if ($c->ft_rm == "on") { ?> checked="checked"<?php } ?>  />rm&#160;
						<input type="checkbox" name="ft_mov" value="on" <?php if ($c->ft_mov == "on") { ?> checked="checked"<?php } ?>  />mov&#160;
						<input type="checkbox" name="ft_moov" value="on" <?php if ($c->ft_moov == "on") { ?> checked="checked"<?php } ?>  />moov&#160;
						<input type="checkbox" name="ft_asf" value="on" <?php if ($c->ft_asf == "on") { ?> checked="checked"<?php } ?>  />asf&#160;
						<input type="checkbox" name="ft_swf" value="on" <?php if ($c->ft_swf == "on") { ?> checked="checked"<?php } ?>  />swf&#160;
						<input type="checkbox" name="ft_vob" value="on" <?php if ($c->ft_vob == "on") { ?> checked="checked"<?php } ?>  />vob&#160;
					  </td></tr>
					  <tr>
						<td align="left" valign="top" width="20%"><?php echo _ADMIN_HWDVIDS_SETT_OFORMATS ?></td>
						<td align="left" valign="top" width="20%"><input type="text" name="oformats" value="<?php echo $c->oformats; ?>" size="30" maxlength="100"></td>
						<td align="left" valign="top" width="60%"><?php echo _ADMIN_HWDVIDS_SETT_OFORMATS_DESC ?></td>
					  </tr>
					</table>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
					  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_MAXFS ?></h3></td></tr>
					  <tr>
						<td align="left" valign="top" width="20%"><?php echo _ADMIN_HWDVIDS_SETT_MAXUPLD ?></td>
						<td align="left" valign="top" width="20%"><input type="text" name="maxupld" value="<?php echo $c->maxupld; ?>" size="7" maxlength="100"></td>
						<td align="left" valign="top" width="60%"><?php echo _ADMIN_HWDVIDS_SETT_MAXUPLD_DESC ?></td>
					  </tr>
					  <tr><td align="left" valign="top" colspan="3">
						<?php
						$phpmax = min($upload_max,$post_max);
						$flashmax = 100;
						$perlmax = 2000;
						if ($phpmax < $flashmax) { $flashmax = $phpmax; }
						?>
						<div style="border:1px solid #458B00;margin: 2px;padding: 2px;">
							<?php
								echo "<p>"._ADMIN_HWDVIDS_SETT_MAXUPLD_PHP."</p>";
								echo "<span style=\"font-size: 150%;font-weight: bold;\">";
								echo _ADMIN_HWDVIDS_SETT_BASPHP." = " . $phpmax . "MB<br />";
								echo _ADMIN_HWDVIDS_SETT_ADVFLASH." = " . $flashmax . "MB<br />";
								echo _ADMIN_HWDVIDS_SETT_ADVPERL." = " . $perlmax . "MB<br />";
								echo "</span>";
							?>
						</div>
					  </td></tr>
					</table>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
					  <tr><td align="left" valign="top" colspan="3"><h3>Advanced Perl Upload Method</h3></td></tr>
					  <tr>
						<td align="left" valign="top" width="20%">URL to ubr_upload.pl</td>
						<td align="left" valign="top" width="20%"><input type="text" name="pathubr_upload" value="<?php if (empty($c->pathubr_upload)) { echo JURI::root() . 'cgi-bin/uu/ubr_upload.pl'; } else { echo $c->pathubr_upload; } ?>" size="60" maxlength="100"></td>
						<td align="left" valign="top" width="60%">Update the full URL to the file called ubr_upload.pl, see below for more details.</td>
					  </tr>
					  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_PERLSCRIPTSETUP ?></h3></td></tr>
					  <tr><td align="left" valign="top" colspan="3">Visit our <a href="http://documentation.hwdmediashare.co.uk/wiki/Setting_up_the_Advanced_Perl_Upload_Method" target="_blank">documentation website</a> for instructions on settings up the Advanced Perl Upload method.<br /><a href="http://documentation.hwdmediashare.co.uk/wiki/Setting_up_the_Advanced_Perl_Upload_Method" target="_blank">http://documentation.hwdmediashare.co.uk/wiki/Setting_up_the_Advanced_Perl_Upload_Method</a></td></tr>
					</table>
					</div>
					<div id="warpSettings" <?php if ($c->locupldmeth !== "4") { ?> style="visibility: hidden; height: 0px;"<?php } ?>>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
					  <tr><td align="left" valign="top" colspan="3"><h3>WARP HD Account</h3></td></tr>
					  <tr>
						<td align="left" valign="top" width="20%">Account Key</td>
						<td align="left" valign="top" width="20%"><input type="text" name="warpAccountKey" value="<?php echo $c->warpAccountKey; ?>" size="60" maxlength="100"></td>
						<td align="left" valign="top" width="60%">Get your account key from the WARP HD console.</td>
					  </tr>
					  <tr>
						<td align="left" valign="top" width="20%">Secret Key</td>
						<td align="left" valign="top" width="20%"><input type="password" name="warpSecretKey" value="<?php echo $c->warpSecretKey; ?>" size="60" maxlength="100"></td>
						<td align="left" valign="top" width="60%">Get your secret key from the WARP HD console.</td>
					  </tr>
					  </table>
					</div>
				</div>
			</div>
			<?php
			echo $endtab;
			echo $starttab4;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_TPMETHOD ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_PTHL ?></td>
				<td width="20%" align="left" valign="top">
				<select name="playlocal" size="1" class="inputbox">
					<option value="1"<?php if ($c->playlocal == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->playlocal == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_PTHL_DESC ?></td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab5;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_DEFAULTSO ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DSACCESS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="shareoption1" size="1" class="inputbox">
					<option value="1"<?php if ($c->shareoption1 == 1) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_PUBLIC; ?></option>
					<option value="0"<?php if ($c->shareoption1 == 0) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_REG; ?></option>
					<option value="2"<?php if ($c->shareoption1 == 2) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_ME; ?></option>
					<option value="3"<?php if ($c->shareoption1 == 3) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_PASSWORD; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DSACCESS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_USACCESS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="usershare1" size="1" class="inputbox">
					<option value="1"<?php if ($c->usershare1 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->usershare1 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_USACCESS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DSCOMMS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="shareoption2" size="1" class="inputbox">
					<option value="1"<?php if ($c->shareoption2 == 1) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_ALLOWCOMMS; ?></option>
					<option value="0"<?php if ($c->shareoption2 == 0) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_DONTALLOWCOMMS; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DSCOMMS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_USCOMMS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="usershare2" size="1" class="inputbox">
					<option value="1"<?php if ($c->usershare2 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->usershare2 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_USCOMMS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DSEMBED ?></td>
				<td width="20%" align="left" valign="top">
				<select name="shareoption3" size="1" class="inputbox">
					<option value="1"<?php if ($c->shareoption3 == 1) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_ALLOWEMB; ?></option>
					<option value="0"<?php if ($c->shareoption3 == 0) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_DONTALLOWEMB; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DSEMBED_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_USEMBED ?></td>
				<td width="20%" align="left" valign="top">
				<select name="usershare3" size="1" class="inputbox">
					<option value="1"<?php if ($c->usershare3 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->usershare3 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_USEMBED_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DSRATE ?></td>
				<td width="20%" align="left" valign="top">
				<select name="shareoption4" size="1" class="inputbox">
					<option value="1"<?php if ($c->shareoption4 == 1) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_ALLOWRATE; ?></option>
					<option value="0"<?php if ($c->shareoption4 == 0) { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_SELECT_DONTALLOWRATE; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DSRATE_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_USRATE ?></td>
				<td width="20%" align="left" valign="top">
				<select name="usershare4" size="1" class="inputbox">
					<option value="1"<?php if ($c->usershare4 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->usershare4 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_USRATE_DESC ?></td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab6;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_APPROVALS ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_AAVIDS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="aav" size="1" class="inputbox">
					<option value="1"<?php if ($c->aav == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->aav == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_AAVIDS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_AA3VIDS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="aa3v" size="1" class="inputbox">
					<option value="1"<?php if ($c->aa3v == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->aa3v == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_AA3VIDS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_AAGROUPS ?></td>
				<td width="20%" align="left" valign="top">
				<select name="aag" size="1" class="inputbox">
					<option value="1"<?php if ($c->aag == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->aag == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_AAGROUPS_DESC ?></td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab7;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h2><?php echo _ADMIN_HWDVIDS_SETT_AUTOCON ?></h2></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SAUPLD ?></td>
				<td width="20%" align="left" valign="top">
				<select name="autoconvert" size="1" class="inputbox">
					<option value="direct"<?php if ($c->autoconvert == "direct") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH1; ?></option>
					<option value="wget1"<?php if ($c->autoconvert == "wget1") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH2; ?></option>
					<option value="wget2"<?php if ($c->autoconvert == "wget2") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH3; ?></option>
					<option value="0"<?php if ($c->autoconvert == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"></td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_direct == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH1." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH1." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_wget01 == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH2." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH2." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_wget02 == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH3." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH3." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h2><?php echo _ADMIN_HWDVIDS_SETT_CNVNSET ?></h2></td></tr>
			  <!--<tr><td align="left" valign="top" colspan="3"><?php echo _ADMIN_HWDVIDS_SETT_CNVNSET_WARNING ?></td></tr>-->
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_CNVTPROG ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="encoder" size="1" class="inputbox">
					<option value="MENCODER"<?php if ($c->encoder == "MENCODER") { ?> selected="selected"<?php } ?>>MENCODER</option>
					<option value="FFMPEG"<?php if ($c->encoder == "FFMPEG") { ?> selected="selected"<?php } ?>>FFMPEG</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CNVTPROG_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b>Video Keyframes</b></td>
				<td width="20%" align="left" valign="top">
				<select name="cnvt_keyf" size="1" class="inputbox">
					<option value="20"<?php if ($c->cnvt_keyf == 20) { ?> selected="selected"<?php } ?>>20 Seconds</option>
					<option value="19"<?php if ($c->cnvt_keyf == 19) { ?> selected="selected"<?php } ?>>19 Seconds</option>
					<option value="18"<?php if ($c->cnvt_keyf == 18) { ?> selected="selected"<?php } ?>>18 Seconds</option>
					<option value="17"<?php if ($c->cnvt_keyf == 17) { ?> selected="selected"<?php } ?>>17 Seconds</option>
					<option value="16"<?php if ($c->cnvt_keyf == 16) { ?> selected="selected"<?php } ?>>16 Seconds</option>
					<option value="15"<?php if ($c->cnvt_keyf == 15) { ?> selected="selected"<?php } ?>>15 Seconds</option>
					<option value="14"<?php if ($c->cnvt_keyf == 14) { ?> selected="selected"<?php } ?>>14 Seconds</option>
					<option value="13"<?php if ($c->cnvt_keyf == 13) { ?> selected="selected"<?php } ?>>13 Seconds</option>
					<option value="12"<?php if ($c->cnvt_keyf == 12) { ?> selected="selected"<?php } ?>>12 Seconds</option>
					<option value="11"<?php if ($c->cnvt_keyf == 11) { ?> selected="selected"<?php } ?>>11 Seconds</option>
					<option value="10"<?php if ($c->cnvt_keyf == 10) { ?> selected="selected"<?php } ?>>10 Seconds</option>
					<option value="9"<?php if ($c->cnvt_keyf == 9) { ?> selected="selected"<?php } ?>>9 Seconds</option>
					<option value="8"<?php if ($c->cnvt_keyf == 8) { ?> selected="selected"<?php } ?>>8 Seconds</option>
					<option value="7"<?php if ($c->cnvt_keyf == 7) { ?> selected="selected"<?php } ?>>7 Seconds</option>
					<option value="6"<?php if ($c->cnvt_keyf == 6) { ?> selected="selected"<?php } ?>>6 Seconds (Default)</option>
					<option value="5"<?php if ($c->cnvt_keyf == 5) { ?> selected="selected"<?php } ?>>5 Seconds</option>
					<option value="4"<?php if ($c->cnvt_keyf == 4) { ?> selected="selected"<?php } ?>>4 Seconds</option>
					<option value="3"<?php if ($c->cnvt_keyf == 3) { ?> selected="selected"<?php } ?>>3 Seconds</option>
					<option value="2"<?php if ($c->cnvt_keyf == 2) { ?> selected="selected"<?php } ?>>2 Seconds</option>
					<option value="1"<?php if ($c->cnvt_keyf == 1) { ?> selected="selected"<?php } ?>>1 Seconds</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Set the frequency of keyframes in your video.</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b>Maintain Aspect Ratio</b></td>
				<td width="20%" align="left" valign="top">
				<select name="keep_ar" size="1" class="inputbox">
					<option value="1"<?php if ($c->keep_ar == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->keep_ar == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Try to maintain the original aspect ratio when creating video files.</td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h2>High Definition (HD) Conversion Settings</h2></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b>Create HQ Videos</b></td>
				<td width="20%" align="left" valign="top">
				<select name="uselibx264" size="1" class="inputbox">
					<option value="1"<?php if ($c->uselibx264 == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->uselibx264 == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Create high quality MP4 videos with the libx264 codec.</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b>Preset Choices</b></td>
				<td width="20%" align="left" valign="top">
				<select name="cnvt_hd_preset" size="1" class="inputbox">
					<option value="0"<?php if ($c->cnvt_hd_preset == "0") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Custom</option>
					<option value="1"<?php if ($c->cnvt_hd_preset == "1") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Default</option>
					<option value="2"<?php if ($c->cnvt_hd_preset == "2") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Very slow</option>
					<option value="3"<?php if ($c->cnvt_hd_preset == "3") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Slower</option>
					<option value="4"<?php if ($c->cnvt_hd_preset == "4") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Slow</option>
					<option value="5"<?php if ($c->cnvt_hd_preset == "5") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Medium</option>
					<option value="6"<?php if ($c->cnvt_hd_preset == "6") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Fast</option>
					<option value="7"<?php if ($c->cnvt_hd_preset == "7") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Faster</option>
					<option value="8"<?php if ($c->cnvt_hd_preset == "8") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Very fast</option>
					<option value="9"<?php if ($c->cnvt_hd_preset == "9") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Super fast</option>
					<option value="10"<?php if ($c->cnvt_hd_preset == "10") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Ultra fast</option>
					<option value="11"<?php if ($c->cnvt_hd_preset == "11") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Placebo</option>
					<option value="12"<?php if ($c->cnvt_hd_preset == "12") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Lossless Max</option>
					<option value="13"<?php if ($c->cnvt_hd_preset == "13") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Lossless Slow</option>
					<option value="14"<?php if ($c->cnvt_hd_preset == "14") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Lossless Slower</option>
					<option value="15"<?php if ($c->cnvt_hd_preset == "15") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Lossless Medium</option>
					<option value="16"<?php if ($c->cnvt_hd_preset == "16") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Lossless Fast</option>
					<option value="17"<?php if ($c->cnvt_hd_preset == "17") { ?> selected="selected"<?php } ?>>[libx264] (Single Pass) Lossless Ultra Fast</option>
					<option value="20"<?php if ($c->cnvt_hd_preset == "20") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Very slow</option>
					<option value="21"<?php if ($c->cnvt_hd_preset == "21") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Slower</option>
					<option value="22"<?php if ($c->cnvt_hd_preset == "22") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Slow</option>
					<option value="23"<?php if ($c->cnvt_hd_preset == "23") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Medium</option>
					<option value="24"<?php if ($c->cnvt_hd_preset == "24") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Fast</option>
					<option value="25"<?php if ($c->cnvt_hd_preset == "25") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Faster</option>
					<option value="26"<?php if ($c->cnvt_hd_preset == "26") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Very fast</option>
					<option value="27"<?php if ($c->cnvt_hd_preset == "27") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Super fast</option>
					<option value="28"<?php if ($c->cnvt_hd_preset == "28") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Ultra fast</option>
					<option value="29"<?php if ($c->cnvt_hd_preset == "29") { ?> selected="selected"<?php } ?>>[libx264] (Double Pass) Placebo</option>
					<option value="30"<?php if ($c->cnvt_hd_preset == "30") { ?> selected="selected"<?php } ?>>[libx264] (Profile Constraint) Baseline</option>
					<option value="31"<?php if ($c->cnvt_hd_preset == "31") { ?> selected="selected"<?php } ?>>[libx264] (Profile Constraint) Main</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">High Definition (HD) video conversion presets.</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_FSIZE; ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="cnvt_fsize_hd" size="1" class="inputbox">
					<option value="0"<?php if ($c->cnvt_fsize_hd == "0") { ?> selected="selected"<?php } ?>>ORIGINAL</option>
					<option value="160x128"<?php if ($c->cnvt_fsize_hd == "160x128") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_DF; ?></option>
					<option value="320x240"<?php if ($c->cnvt_fsize_hd == "320x240") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_QVGA; ?></option>
					<option value="320x200"<?php if ($c->cnvt_fsize_hd == "320x200") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_CGA; ?></option>
					<option value="640x480"<?php if ($c->cnvt_fsize_hd == "640x480") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_VGA; ?></option>
					<option value="640x360"<?php if ($c->cnvt_fsize_hd == "640x360") { ?> selected="selected"<?php } ?>>CUSTOM 640x360 [16:9]</option>
					<option value="720x480"<?php if ($c->cnvt_fsize_hd == "720x480") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_NTSC; ?></option>
					<option value="768x576"<?php if ($c->cnvt_fsize_hd == "768x576") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_PAL; ?></option>
					<option value="800x600"<?php if ($c->cnvt_fsize_hd == "800x600") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_SVGA; ?></option>
					<option value="800x480"<?php if ($c->cnvt_fsize_hd == "800x480") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WVGA1; ?></option>
					<option value="854x480"<?php if ($c->cnvt_fsize_hd == "854x480") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WVGA2; ?></option>
					<option value="1024x600"<?php if ($c->cnvt_fsize_hd == "1024x600") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WSVGA; ?></option>
					<option value="1280x1024"<?php if ($c->cnvt_fsize_hd == "1280x1024") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_SXGA; ?></option>
					<option value="1280x720"<?php if ($c->cnvt_fsize_hd == "1280x720") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_HD720; ?></option>
					<option value="1280x768"<?php if ($c->cnvt_fsize_hd == "1280x768") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WXGA1; ?></option>
					<option value="1280x800"<?php if ($c->cnvt_fsize_hd == "1280x800") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WXGA2; ?></option>
					<option value="1400x1050"<?php if ($c->cnvt_fsize_hd == "1400x1050") { ?> selected="selected"<?php } ?>>SXGA+</option>
					<option value="1680x1050"<?php if ($c->cnvt_fsize_hd == "1680x1050") { ?> selected="selected"<?php } ?>>WSXGA+</option>
					<option value="1920x1200"<?php if ($c->cnvt_fsize_hd == "1920x1200") { ?> selected="selected"<?php } ?>>HD1080</option>
					<option value="2048x1536"<?php if ($c->cnvt_fsize_hd == "2048x1536") { ?> selected="selected"<?php } ?>>QXGA</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_FSIZE_DESC; ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b>Create iPod Files</b></td>
				<td width="20%" align="left" valign="top">
				<input type="checkbox" name="ipod320" value="on" <?php if ($c->ipod320 == "on") { ?> checked="checked"<?php } ?> />iPod 320&#160;
				<input type="checkbox" name="ipod640" value="on" <?php if ($c->ipod640 == "on") { ?> checked="checked"<?php } ?> />iPod 640&#160;
				</td>
				<td width="60%" align="left" valign="top">Create high quality MP4 videos with the libx264 codec.</td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h2>Standard Definition (SD) Conversion Settings</h2></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_VBITRATE; ?></b></td>
				<td width="20%" align="left" valign="top" colspan="2">
				<select name="cnvt_vbitrate" size="1" class="inputbox">
					<option value="16"<?php if ($c->cnvt_vbitrate == 16) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VP; ?></option>
					<option value="200"<?php if ($c->cnvt_vbitrate == 200) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC2; ?></option>
					<option value="300"<?php if ($c->cnvt_vbitrate == 300) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC3; ?></option>
					<option value="400"<?php if ($c->cnvt_vbitrate == 400) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC4; ?></option>
					<option value="500"<?php if ($c->cnvt_vbitrate == 500) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC5; ?></option>
					<option value="600"<?php if ($c->cnvt_vbitrate == 600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC6; ?></option>
					<option value="700"<?php if ($c->cnvt_vbitrate == 700) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC7; ?></option>
					<option value="800"<?php if ($c->cnvt_vbitrate == 800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC8; ?></option>
					<option value="900"<?php if ($c->cnvt_vbitrate == 900) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC9; ?></option>
					<option value="1000"<?php if ($c->cnvt_vbitrate == 1000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VC10; ?></option>
					<option value="1250"<?php if ($c->cnvt_vbitrate == 1250) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VCD; ?></option>

					<option value="1500"<?php if ($c->cnvt_vbitrate == 1500) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VCD15; ?></option>
					<option value="2000"<?php if ($c->cnvt_vbitrate == 2000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VCD20; ?></option>
					<option value="2500"<?php if ($c->cnvt_vbitrate == 2500) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VCD25; ?></option>
					<option value="3000"<?php if ($c->cnvt_vbitrate == 3000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VCD30; ?></option>
					<option value="3500"<?php if ($c->cnvt_vbitrate == 3500) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VCD35; ?></option>
					<option value="4000"<?php if ($c->cnvt_vbitrate == 4000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VCD40; ?></option>
					<option value="4500"<?php if ($c->cnvt_vbitrate == 4500) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_VCD45; ?></option>

					<!--
					<option value="5000"<?php if ($c->cnvt_vbitrate == 5000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_DVD; ?></option>
					<option value="15000"<?php if ($c->cnvt_vbitrate == 15000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_VBITRATE_HDTV; ?></option>
					-->
				</select>
				</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"></td>
				<td width="80%" align="left" valign="top" colspan="2"><?php echo _ADMIN_HWDVIDS_VBITRATE_DESC; ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_ABITRATE; ?></b></td>
				<td width="20%" align="left" valign="top" colspan="2">
				<select name="cnvt_abitrate" size="1" class="inputbox">
					<option value="32"<?php if ($c->cnvt_abitrate == 32) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ABITRATE_AM; ?></option>
					<option value="64"<?php if ($c->cnvt_abitrate == 64) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ABITRATE_DF; ?></option>
					<option value="96"<?php if ($c->cnvt_abitrate == 96) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ABITRATE_FM; ?></option>
					<option value="128"<?php if ($c->cnvt_abitrate == 128) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ABITRATE_ST; ?></option>
					<option value="192"<?php if ($c->cnvt_abitrate == 192) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ABITRATE_DBA; ?></option>
					<option value="224"<?php if ($c->cnvt_abitrate == 244) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ABITRATE_CD; ?></option>
				</select>
				</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"></td>
				<td width="80%" align="left" valign="top" colspan="2"><?php echo _ADMIN_HWDVIDS_ABITRATE_DESC; ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_ASR; ?></b></td>
				<td width="20%" align="left" valign="top" colspan="2">
				<select name="cnvt_asr" size="1" class="inputbox">
					<option value="11025"<?php if ($c->cnvt_asr == 11025) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ASR_QURT; ?></option>
					<option value="22050"<?php if ($c->cnvt_asr == 22050) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ASR_HALF; ?></option>
					<option value="44100"<?php if ($c->cnvt_asr == 44100) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_ASR_CD; ?></option>
				</select>
				</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"></td>
				<td width="80%" align="left" valign="top" colspan="2"><?php echo _ADMIN_HWDVIDS_ASR_DESC; ?></td>
			  </tr>
			  <!--
			  <tr>
				<td align="left" valign="top" width="20%"><b><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMENCODEOPT ?></b></td>
				<td width="20%" align="left" valign="top" colspan="2"><input type="text" name="customencode" value="<?php echo $c->customencode; ?>" size="40" maxlength="100"></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"></td>
				<td width="80%" align="left" valign="top" colspan="2"><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMENCODEOPT_DESC ?></td>
			  </tr>
			  -->
			  <input type="hidden" name="customencode" value="">
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_FSIZE; ?></b></td>
				<td width="80%" align="left" valign="top" colspan="2">
				<select name="cnvt_fsize" size="1" class="inputbox">
					<option value="0"<?php if ($c->cnvt_fsize == "0") { ?> selected="selected"<?php } ?>>ORIGINAL</option>
					<option value="160x128"<?php if ($c->cnvt_fsize == "160x128") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_DF; ?></option>
					<option value="320x240"<?php if ($c->cnvt_fsize == "320x240") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_QVGA; ?></option>
					<option value="320x200"<?php if ($c->cnvt_fsize == "320x200") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_CGA; ?></option>
					<option value="640x480"<?php if ($c->cnvt_fsize == "640x480") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_VGA; ?></option>
					<option value="640x360"<?php if ($c->cnvt_fsize == "640x360") { ?> selected="selected"<?php } ?>>CUSTOM 640x360 [16:9]</option>
					<option value="720x480"<?php if ($c->cnvt_fsize == "720x480") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_NTSC; ?></option>
					<option value="768x576"<?php if ($c->cnvt_fsize == "768x576") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_PAL; ?></option>
					<option value="800x600"<?php if ($c->cnvt_fsize == "800x600") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_SVGA; ?></option>
					<option value="800x480"<?php if ($c->cnvt_fsize == "800x480") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WVGA1; ?></option>
					<option value="854x480"<?php if ($c->cnvt_fsize == "854x480") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WVGA2; ?></option>
					<option value="1024x600"<?php if ($c->cnvt_fsize == "1024x600") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WSVGA; ?></option>
					<option value="1280x1024"<?php if ($c->cnvt_fsize == "1280x1024") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_SXGA; ?></option>
					<option value="1280x720"<?php if ($c->cnvt_fsize == "1280x720") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_HD720; ?></option>
					<option value="1280x768"<?php if ($c->cnvt_fsize == "1280x768") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WXGA1; ?></option>
					<option value="1280x800"<?php if ($c->cnvt_fsize == "1280x800") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_FSIZE_WXGA2; ?></option>
				</select>
				</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"></td>
				<td width="80%" align="left" valign="top" colspan="2"><?php echo _ADMIN_HWDVIDS_FSIZE_DESC; ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_WMVFIX ?></b></td>
				<td width="20%" align="left" valign="top" colspan="2">
				<select name="applywmvfix" size="1" class="inputbox">
					<option value="1"<?php if ($c->applywmvfix == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->applywmvfix == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"></td>
				<td width="80%" align="left" valign="top" colspan="2"><?php echo _ADMIN_HWDVIDS_SETT_WMVFIX_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_LOGCONVERT ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="logconvert" size="1" class="inputbox">
					<option value="1"<?php if ($c->logconvert == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->logconvert == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_LOGCONVERT_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_RECONFLV ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="reconvertflv" size="1" class="inputbox">
					<option value="1"<?php if ($c->reconvertflv == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->reconvertflv == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_RECONFLV_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_PATHSL ?></b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="sharedlibrarypath" value="<?php echo $c->sharedlibrarypath; ?>" size="40" maxlength="100"></td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_PATHSL_DESC ?></td>
			  </tr>
			  <!--
			  <tr>
				<td width="20%" align="left" valign="top"><b>Conversion Priority</b></td>
				<td width="20%" align="left" valign="top">
				<select name="nicepriority" size="1" class="inputbox">
					<option value="19"<?php if ($c->nicepriority == "19") { ?> selected="selected"<?php } ?>>Extremely Low Priority</option>
					<option value="15"<?php if ($c->nicepriority == "15") { ?> selected="selected"<?php } ?>>Low Priority</option>
					<option value="10"<?php if ($c->nicepriority == "10") { ?> selected="selected"<?php } ?>>Normal</option>
					<option value="5"<?php if ($c->nicepriority == "5") { ?> selected="selected"<?php } ?>>High Priority</option>
					<option value="1"<?php if ($c->nicepriority == "1") { ?> selected="selected"<?php } ?>>Very High Priority</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">You can increase or decrease the cpu priority when converting videos using the <i>nice</i> command.</td>
			  </tr>
			  -->
			  <tr>
				<td align="left" valign="top" width="20%"><b>Normal Thumbnail Width</b></td>
				<td width="20%" align="left" valign="top">
				<select name="con_thumb_n" size="1" class="inputbox">
					<?php
					for ($i=20, $n=401; $i < $n; $i++) {
						echo "<option value=\"".$i."\"";
						if ($c->con_thumb_n == $i) {
							echo " selected=\"selected\"";
						}
						echo ">".$i."</option>";
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Normal thumbnails are displayed in video lists.</td>
			  </tr>
			  <tr>
				<td align="left" valign="top" width="20%"><b>Large Thumbnail Width</b></td>
				<td width="20%" align="left" valign="top">
				<select name="con_thumb_l" size="1" class="inputbox">
					<?php
					for ($i=20, $n=1001; $i < $n; $i++) {
						echo "<option value=\"".$i."\"";
						if ($c->con_thumb_l == $i) {
							echo " selected=\"selected\"";
						}
						echo ">".$i."</option>";
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Large thumbnails are displayed in the video player.</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_THUMBFAIL ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="abortthumbfail" size="1" class="inputbox">
					<option value="1"<?php if ($c->abortthumbfail == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->abortthumbfail == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_THUMBFAIL_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_DELETEO ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="deleteoriginal" size="1" class="inputbox">
					<option value="1"<?php if ($c->deleteoriginal == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->deleteoriginal == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DELETEO_DESC ?></td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab8;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_NOTIFICATIONS ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_NOTIFYVID ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="mailvideonotification" size="1" class="inputbox">
					<option value="1"<?php if ($c->mailvideonotification == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->mailvideonotification == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_NOTIFYVID_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_NOTIFYG ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="mailgroupnotification" size="1" class="inputbox">
					<option value="1"<?php if ($c->mailgroupnotification == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->mailgroupnotification == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_NOTIFYG_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_NOTIFYR ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="mailreportnotification" size="1" class="inputbox">
					<option value="1"<?php if ($c->mailreportnotification == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->mailreportnotification == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_NOTIFYR_DESC ?></td>
			  </tr>
			  <tr>
				<td align="left" valign="top" width="20%"><b><?php echo _ADMIN_HWDVIDS_SETT_SENTTO ?></b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="mailnotifyaddress" value="<?php if ($c->mailnotifyaddress == "") { echo $mosConfig_mailfrom; } else { echo $c->mailnotifyaddress; } ?>" size="40" maxlength="100"></td>
				<td align="left" valign="top" width="60%"><?php echo _ADMIN_HWDVIDS_SETT_SENDTO_DESC ?></td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab9;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Creating the XML playlists in the background will reduce load times and is highly recommended.</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Generate XML Playlists in Background?</td>
				<td width="20%" align="left" valign="top">
				<select name="playlist_bkgd" size="1" class="inputbox">
					<option value="direct"<?php if ($c->playlist_bkgd == "direct") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH1; ?></option>
					<option value="wget1"<?php if ($c->playlist_bkgd == "wget1") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH2; ?></option>
					<option value="wget2"<?php if ($c->playlist_bkgd == "wget2") { ?> selected="selected"<?php } ?>><?php echo _HWDVIDS_ALERT_ACMETH3; ?></option>
					<!--<option value="none"<?php if ($c->playlist_bkgd == "none") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?> [In-line Execution]</option>-->
					<option value="disable"<?php if ($c->playlist_bkgd == "disable") { ?> selected="selected"<?php } ?>>Disable Playlists</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"></td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_direct == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH1." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH1." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_wget01 == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH2." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH2." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			  <tr>
			    <td align="left" valign="top" colspan="3">
				<?php
				if ($check_wget02 == true) {
					echo "<div style=\"border:1px solid #458B00;color:#458B00;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/tick.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH3." "._HWDVIDS_ALERT_AC_YES."</div>";
				} else {
					echo "<div style=\"border:1px solid #c30;color:#c30;margin: 0;padding: 5px;font-weight: bold;\"><img src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\" style=\"vertical-align:center\" />&nbsp;"._HWDVIDS_ALERT_ACMETH3." "._HWDVIDS_ALERT_AC_NO."</div>";
				}
				?>
			    </td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_XMLPL ?></h3><p><?php echo _ADMIN_HWDVIDS_SETT_XMLPL_DESC ?></p></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_XMLPLCACHE_TODAY ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="xmlcache_today" size="1" class="inputbox">
					<option value="0"<?php if ($c->xmlcache_today == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHENO; ?></option>
					<option value="60"<?php if ($c->xmlcache_today == 60) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE60; ?></option>
					<option value="300"<?php if ($c->xmlcache_today == 300) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE300; ?></option>
					<option value="600"<?php if ($c->xmlcache_today == 600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE600; ?></option>
					<option value="1800"<?php if ($c->xmlcache_today == 1800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE1800; ?></option>
					<option value="3600"<?php if ($c->xmlcache_today == 3600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE3600; ?></option>
					<option value="18000"<?php if ($c->xmlcache_today == 18000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE18000; ?></option>
					<option value="36000"<?php if ($c->xmlcache_today == 36000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE36000; ?></option>
					<option value="86400"<?php if ($c->xmlcache_today == 86400) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE86400; ?></option>
					<option value="604800"<?php if ($c->xmlcache_today == 604800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE604800; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_XMLPLCACHE_TODAY_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_XMLPLCACHE_TW ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="xmlcache_thisweek" size="1" class="inputbox">
					<option value="0"<?php if ($c->xmlcache_thisweek == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHENO; ?></option>
					<option value="60"<?php if ($c->xmlcache_thisweek == 60) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE60; ?></option>
					<option value="300"<?php if ($c->xmlcache_thisweek == 300) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE300; ?></option>
					<option value="600"<?php if ($c->xmlcache_thisweek == 600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE600; ?></option>
					<option value="1800"<?php if ($c->xmlcache_thisweek == 1800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE1800; ?></option>
					<option value="3600"<?php if ($c->xmlcache_thisweek == 3600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE3600; ?></option>
					<option value="18000"<?php if ($c->xmlcache_thisweek == 18000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE18000; ?></option>
					<option value="36000"<?php if ($c->xmlcache_thisweek == 36000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE36000; ?></option>
					<option value="86400"<?php if ($c->xmlcache_thisweek == 86400) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE86400; ?></option>
					<option value="604800"<?php if ($c->xmlcache_thisweek == 604800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE604800; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_XMLPLCACHE_TW_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_XMLPLCACHE_TM ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="xmlcache_thismonth" size="1" class="inputbox">
					<option value="0"<?php if ($c->xmlcache_thismonth == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHENO; ?></option>
					<option value="60"<?php if ($c->xmlcache_thismonth == 60) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE60; ?></option>
					<option value="300"<?php if ($c->xmlcache_thismonth == 300) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE300; ?></option>
					<option value="600"<?php if ($c->xmlcache_thismonth == 600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE600; ?></option>
					<option value="1800"<?php if ($c->xmlcache_thismonth == 1800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE1800; ?></option>
					<option value="3600"<?php if ($c->xmlcache_thismonth == 3600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE3600; ?></option>
					<option value="18000"<?php if ($c->xmlcache_thismonth == 18000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE18000; ?></option>
					<option value="36000"<?php if ($c->xmlcache_thismonth == 36000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE36000; ?></option>
					<option value="86400"<?php if ($c->xmlcache_thismonth == 86400) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE86400; ?></option>
					<option value="604800"<?php if ($c->xmlcache_thismonth == 604800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE604800; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_XMLPLCACHE_TM_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_XMLPLCACHE_AT ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="xmlcache_alltime" size="1" class="inputbox">
					<option value="0"<?php if ($c->xmlcache_alltime == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHENO; ?></option>
					<option value="60"<?php if ($c->xmlcache_alltime == 60) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE60; ?></option>
					<option value="300"<?php if ($c->xmlcache_alltime == 300) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE300; ?></option>
					<option value="600"<?php if ($c->xmlcache_alltime == 600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE600; ?></option>
					<option value="1800"<?php if ($c->xmlcache_alltime == 1800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE1800; ?></option>
					<option value="3600"<?php if ($c->xmlcache_alltime == 3600) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE3600; ?></option>
					<option value="18000"<?php if ($c->xmlcache_alltime == 18000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE18000; ?></option>
					<option value="36000"<?php if ($c->xmlcache_alltime == 36000) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE36000; ?></option>
					<option value="86400"<?php if ($c->xmlcache_alltime == 86400) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE86400; ?></option>
					<option value="604800"<?php if ($c->xmlcache_alltime == 604800) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_CACHE604800; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_XMLPLCACHE_AT_DESC ?></td>
			  </tr>
			  <tr>
				<td align="left" valign="top" width="20%"><b><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML ?> 1</b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="xmlcustom01" value="<?php echo $c->xmlcustom01; ?>" size="40" maxlength="100"></td>
				<td align="left" valign="top" width="60%"><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML_DESC ?></td>
			  </tr>
			  <tr>
				<td align="left" valign="top" width="20%"><b><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML ?> 2</b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="xmlcustom02" value="<?php echo $c->xmlcustom02; ?>" size="40" maxlength="100"></td>
				<td align="left" valign="top" width="60%"><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML_DESC ?></td>
			  </tr>
			  <tr>
				<td align="left" valign="top" width="20%"><b><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML ?> 3</b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="xmlcustom03" value="<?php echo $c->xmlcustom03; ?>" size="40" maxlength="100"></td>
				<td align="left" valign="top" width="60%"><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML_DESC ?></td>
			  </tr>
			  <tr>
				<td align="left" valign="top" width="20%"><b><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML ?> 4</b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="xmlcustom04" value="<?php echo $c->xmlcustom04; ?>" size="40" maxlength="100"></td>
				<td align="left" valign="top" width="60%"><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML_DESC ?></td>
			  </tr>
			  <tr>
				<td align="left" valign="top" width="20%"><b><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML ?> 5</b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="xmlcustom05" value="<?php echo $c->xmlcustom05; ?>" size="40" maxlength="100"></td>
				<td align="left" valign="top" width="60%"><?php echo _ADMIN_HWDVIDS_SETT_CUSTOMXML_DESC ?></td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab10;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_INTEGRATIONSCB ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INTCB ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="cbint" size="1" class="inputbox">
					<option value="0"<?php if ($c->cbint == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
					<option value="5"<?php if ($c->cbint == 5) { ?> selected="selected"<?php } ?>>hwdVideoShare Channel</option>
					<option value="2"<?php if ($c->cbint == 2) { ?> selected="selected"<?php } ?>>Jom Social</option>
					<option value="1"<?php if ($c->cbint == 1) { ?> selected="selected"<?php } ?>>Community Builder</option>
					<option value="3"<?php if ($c->cbint == 3) { ?> selected="selected"<?php } ?>>People Touch</option>
					<option value="4"<?php if ($c->cbint == 4) { ?> selected="selected"<?php } ?>>Kunena Forum 1.5</option>
					<option value="6"<?php if ($c->cbint == 6) { ?> selected="selected"<?php } ?>>Kunena Forum 1.6</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INTCB_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_SHOWCBAVA ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="cbavatar" size="1" class="inputbox">
					<option value="2"<?php if ($c->cbavatar == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES." (Channel Logo Also)"; ?></option>
					<option value="1"<?php if ($c->cbavatar == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->cbavatar == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_SHOWCBAVA_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_CBAVAWIDTH ?></b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="avatarwidth" value="<?php echo $c->avatarwidth; ?>" size="7" maxlength="100"></td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CBAVAWIDTH_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_CBITEMID ?></b></td>
				<td align="left" valign="top" width="20%"><input type="text" name="cbitemid" value="<?php echo $c->cbitemid; ?>" size="7" maxlength="100"></td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_CBITEMID_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_COMMSYS ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_COMMCOM ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="commssys" size="1" class="inputbox">
					<option value="99"<?php if ($c->commssys == 99) { ?> selected="selected"<?php } ?>>None</option>
					<option value="0"<?php if ($c->commssys == 0) { ?> selected="selected"<?php } ?>>JComments</option>
					<!-- <option value="1"<?php if ($c->commssys == 1) { ?> selected="selected"<?php } ?>>mXcomment</option> -->
					<option value="2"<?php if ($c->commssys == 2) { ?> selected="selected"<?php } ?>>CompoJoomComment (!joomlaComment)</option>
					<option value="3"<?php if ($c->commssys == 3) { ?> selected="selected"<?php } ?>>Jom Comment</option>
					<!-- <option value="4"<?php if ($c->commssys == 4) { ?> selected="selected"<?php } ?>>Akocomment</option> -->
					<!-- <option value="5"<?php if ($c->commssys == 5) { ?> selected="selected"<?php } ?>>yvComment</option> -->
					<!-- <option value="6"<?php if ($c->commssys == 6) { ?> selected="selected"<?php } ?>>EasyComments</option> -->
					<option value="7"<?php if ($c->commssys == 7) { ?> selected="selected"<?php } ?>>Kunena Forum 1.5</option>
					<option value="8"<?php if ($c->commssys == 8) { ?> selected="selected"<?php } ?>>Kunena Forum 1.6</option>
					<option value="9"<?php if ($c->commssys == 9) { ?> selected="selected"<?php } ?>>Joomlart (JA Comment)</option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_COMMCOM_DESC ?></td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $starttab11;
			?>
			<div style="margin:1px;padding:1px;"><br />
			<?php
			if ($j16)
			{
			?>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td align="left" valign="top"><b>Joomla 1.6 Permissions</b><br />To set permissions in Joomla 1.6, use the <strong>Options</strong> button above</td>
			  </tr>
			</table>
			<?php
			}
			else
			{
			?>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_MAINCOMACCESS ?></b></td>
				<td width="20%" align="left" valign="top">
				<?php
				$gtree_core = JHTML::_('select.genericlist', $gtree, 'gtree_core', 'size="4"', 'value', 'text', $c->gtree_core);
				echo $gtree_core;
				?>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_MAINCOMACCESS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="gtree_core_child" size="1" class="inputbox">
					<option value="RECURSE"<?php if ($c->gtree_core_child == "RECURSE") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->gtree_core_child == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_UPLOADACCESS ?></b></td>
				<td width="20%" align="left" valign="top">
				<?php
				$gtree_upld = JHTML::_('select.genericlist', $gtree, 'gtree_upld', 'size="4"', 'value', 'text', $c->gtree_upld);
				echo $gtree_upld;
				?>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_UPLOADACCESS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="gtree_upld_child" size="1" class="inputbox">
					<option value="RECURSE"<?php if ($c->gtree_upld_child == "RECURSE") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->gtree_upld_child == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_TPUPLOADACCESS ?></b></td>
				<td width="20%" align="left" valign="top">
				<?php
				$gtree_ultp = JHTML::_('select.genericlist', $gtree, 'gtree_ultp', 'size="4"', 'value', 'text', $c->gtree_ultp);
				echo $gtree_ultp;
				?>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_TPUPLOADACCESS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="gtree_ultp_child" size="1" class="inputbox">
					<option value="RECURSE"<?php if ($c->gtree_ultp_child == "RECURSE") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->gtree_ultp_child == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_DNLDACCESS ?></b></td>
				<td width="20%" align="left" valign="top">
				<?php
				$gtree_dnld = JHTML::_('select.genericlist', $gtree, 'gtree_dnld', 'size="4"', 'value', 'text', $c->gtree_dnld);
				echo $gtree_dnld;
				?>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_DNLDACCESS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="gtree_dnld_child" size="1" class="inputbox">
					<option value="RECURSE"<?php if ($c->gtree_dnld_child == "RECURSE") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->gtree_dnld_child == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_PLAYERACCESS ?></b></td>
				<td width="20%" align="left" valign="top">
				<?php
				$gtree_plyr = JHTML::_('select.genericlist', $gtree, 'gtree_plyr', 'size="4"', 'value', 'text', $c->gtree_plyr);
				echo $gtree_plyr;
				?>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_PLAYERACCESS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="gtree_plyr_child" size="1" class="inputbox">
					<option value="RECURSE"<?php if ($c->gtree_plyr_child == "RECURSE") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->gtree_plyr_child == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_GROUPACCESS ?></b></td>
				<td width="20%" align="left" valign="top">
				<?php
				$gtree_grup = JHTML::_('select.genericlist', $gtree, 'gtree_grup', 'size="4"', 'value', 'text', $c->gtree_grup);
				echo $gtree_grup;
				?>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_GROUPACCESS_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="gtree_grup_child" size="1" class="inputbox">
					<option value="RECURSE"<?php if ($c->gtree_grup_child == "RECURSE") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->gtree_grup_child == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b>Access to Moderator Features</b></td>
				<td width="20%" align="left" valign="top">
				<?php
				$gtree_mdrt = JHTML::_('select.genericlist', $gtree, 'gtree_mdrt', 'size="4"', 'value', 'text', $c->gtree_mdrt);
				echo $gtree_mdrt;
				?>
				</td>
				<td width="60%" align="left" valign="top">Set the moderator group</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="gtree_mdrt_child" size="1" class="inputbox">
					<option value="RECURSE"<?php if ($c->gtree_mdrt_child == "RECURSE") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->gtree_mdrt_child == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr>
				<td width="20%" align="left" valign="top"><b>Access to WYSIWYG Editor</b></td>
				<td width="20%" align="left" valign="top">
				<?php
				$gtree_edtr = JHTML::_('select.genericlist', $gtree, 'gtree_edtr', 'size="4"', 'value', 'text', $c->gtree_edtr);
				echo $gtree_edtr;
				?>
				</td>
				<td width="60%" align="left" valign="top">Set the WYSIWYG group</td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><b><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD ?></b></td>
				<td width="20%" align="left" valign="top">
				<select name="gtree_edtr_child" size="1" class="inputbox">
					<option value="RECURSE"<?php if ($c->gtree_edtr_child == "RECURSE") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->gtree_edtr_child == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_INCLUDECHILD_DESC ?></td>
			  </tr>
			</table>
			<?php
			}
			?>
			<table cellpadding="0" cellspacing="0" border="0" width="95%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3><?php echo _ADMIN_HWDVIDS_SETT_VIDEDIT ?></h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_ALLOWEDIT ?></td>
				<td width="20%" align="left" valign="top">
				<select name="allowvidedit" size="1" class="inputbox">
					<option value="1"<?php if ($c->allowvidedit == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->allowvidedit == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_ALLOWEDIT_DESC ?></td>
			  </tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_ALLOWDEL ?></td>
				<td width="20%" align="left" valign="top">
				<select name="allowviddel" size="1" class="inputbox">
					<option value="1"<?php if ($c->allowviddel == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->allowviddel == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_ALLOWDEL_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Guest Privileges</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Allow Guest Rating</td>
				<td width="20%" align="left" valign="top">
				<select name="allowgr" size="1" class="inputbox" onChange="ShowAccessPane()">
					<option value="1"<?php if ($c->allowgr == 1) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
					<option value="0"<?php if ($c->allowgr == 0) { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Allow guests to rate videos. One rate will be allow from each IP address.</td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Restricted Videos</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_BVIIC ?></td>
				<td width="20%" align="left" valign="top">
					<select name="bviic" size="1" class="inputbox">
						<option value="1"<?php if ($c->bviic == "1") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_YES; ?></option>
						<option value="0"<?php if ($c->bviic == "0") { ?> selected="selected"<?php } ?>><?php echo _ADMIN_HWDVIDS_SETT_NO; ?></option>
					</select>
				</td>
				<td width="60%" align="left" valign="top"><?php echo _ADMIN_HWDVIDS_SETT_BVIIC_DESC ?></td>
			  </tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
			  <tr><td align="left" valign="top" colspan="3"><h3>Age Check</h3></td></tr>
			  <tr>
				<td width="20%" align="left" valign="top">Verify Age</td>
				<td width="20%" align="left" valign="top">
				<select name="age_check" size="1" class="inputbox">
					<option value="0"<?php if ($c->age_check == 0) { ?> selected="selected"<?php } ?>>Off</option>
					<?php
					for ($i=1, $n=100; $i < $n; $i++) {
						?>
						<option value="<?php echo $i; ?>" <?php if ($c->age_check == $i) echo "selected=\"selected\"" ?>><?php echo $i; ?></option>
						<?php
					}
					?>
				</select>
				</td>
				<td width="60%" align="left" valign="top">Check user's age before displaying the video player.</td>
			  </tr>
			</table>
			</div>
			<?php
			echo $endtab;
			echo $endpane;
			?>
		</div>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="generalsettings" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<div style="clear:both;"></div>
		<?php
		/** display template **/
		$smartyvs->display('admin_footer.tpl');
	}
}

class hwd_vs_check_autoconversion
{
   /**
	* show general settings
	*/
	function checkDirectExecution()
	{
		$s = hwd_vs_SConfig::get_instance();

		$filename = JPATH_SITE."/cache/check_direct.file";
		if (file_exists($filename)) { unlink($filename); }

		if(substr(PHP_OS, 0, 3) != "WIN") {
			@exec("env -i $s->phppath ".HWDVS_ADMIN_PATH."/../../../components/com_hwdvideoshare/converters/ac/check_direct.php &>/dev/null &");
		} else {
			@exec("$s->phppath ".HWDVS_ADMIN_PATH."/../../../components/com_hwdvideoshare/converters/ac/check_direct.php NUL");
		}
		usleep(800000);

		if (file_exists($filename)) {
			unlink($filename);
			return true;
		} else {
			return false;
		}
	}
   /**
	* show general settings
	*/
	function checkWget01Execution()
	{
		global $mosConfig_live_site;
		$s = hwd_vs_SConfig::get_instance();
		$filename = JPATH_SITE."/cache/check_wget1.file";
		if (file_exists($filename)) { unlink($filename); }

		if(substr(PHP_OS, 0, 3) != "WIN") {
			@exec("env -i $s->wgetpath -O - -q ".JURI::root()."components/com_hwdvideoshare/converters/ac/check_wget1.php &>/dev/null &");
		} else {
			@exec("$s->wgetpath \"".JURI::root()."components/com_hwdvideoshare/converters/ac/check_wget.php\" NUL");
		}
		usleep(800000);

		if (file_exists($filename)) {
			unlink($filename);
			return true;
		} else {
			return false;
		}
	}
   /**
	* show general settings
	*/
	function checkWget02Execution()
	{
		global $mosConfig_live_site;
		$s = hwd_vs_SConfig::get_instance();
		$filename = JPATH_SITE."/cache/check_wget2.file";
		if (file_exists($filename)) { unlink($filename); }

		if(substr(PHP_OS, 0, 3) != "WIN") {
			@exec("env -i $s->wgetpath -O - -q ".JURI::root()."components/com_hwdvideoshare/converters/ac/check_wget2.php >/dev/null &");
		} else {
			@exec("$s->wgetpath \"".JURI::root()."components/com_hwdvideoshare/converters/ac/check_wget.php\" NUL");
		}
		usleep(800000);

		if (file_exists($filename)) {
			unlink($filename);
			return true;
		} else {
			return false;
		}
	}
}
?>