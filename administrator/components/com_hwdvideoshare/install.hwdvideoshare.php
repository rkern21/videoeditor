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

function com_install()
{
	$db =& JFactory::getDBO();
    jimport('joomla.filesystem.file');
	$doc =& JFactory::getDocument();

	jimport( 'joomla.version' );

	$version = new JVersion;
	$joomla = $version->getShortVersion();
	if(substr($joomla,0,3) == '1.6')
	{
		$j15 = false;
		$j16 = true;
	}
	else
	{
		$j15 = true;
		$j16 = false;
	}

	if ($j15)
	{
		$doc->addCustomTag('<link rel="stylesheet" href="'.JURI::root( true ).'/administrator/components/com_hwdvideoshare/assets/css/installer.css" type="text/css" />');
	}
	else
	{
		echo '<link rel="stylesheet" href="'.JURI::root( true ).'/administrator/components/com_hwdvideoshare/assets/css/installer.css" type="text/css" />';
	}
	?>
	<div class="installer_logo_box"><img src="../administrator/components/com_hwdvideoshare/assets/images/logo.png" border="0" alt="hwdVideoShare" title="hwdVideoShare" /></div>

	<div class="installer_box">
		<h2>hwdVideoShare [ Wainuiomata ]</h2>
		<p>An open source video sharing component developed by <a href="http://joomla.highwooddesign.co.uk" target="_blank">Highwood Design</a>.<br />
		Released under the terms and conditions of the <a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GNU General Public License</a>.<br />
		See the component homepage for more details after installation.</p>
	</div>

	<?php if ($j15) { ?>
	<div class="installer_box">
		<h3>Menu Configuration</h3>
		<?php
		$section_check=true;

		# Set up new icons for admin menu
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/hwdvideoshare.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=videos'");
		$iconresult[0] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/categories.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=categories'");
		$iconresult[1] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/groups.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=groups'");
		$iconresult[2] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/seversettings.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=serversettings'");
		$iconresult[3] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/generalsettings.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=generalsettings'");
		$iconresult[4] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/converter.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=converter'");
		$iconresult[5] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/approvals.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=approvals'");
		$iconresult[6] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/reported.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=reported'");
		$iconresult[7] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/plugins.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=plugins'");
		$iconresult[8] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/export.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=export'");
		$iconresult[9] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/import.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=import'");
		$iconresult[10] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/maintenance.png' WHERE admin_menu_link='option=com_hwdvideoshare&task=maintenance'");
		$iconresult[11] = $db->query();
		$db->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_hwdvideoshare/assets/images/menu/hwdvideoshare.png' WHERE admin_menu_link='option=com_hwdvideoshare'");
		$iconresult[12] = $db->query();

		foreach ($iconresult as $i=>$icresult)
		{
			if (!$icresult)
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Image of menu entry $i could not be set correctly.<br />';
				$section_check=false;
			}
		}

		if ($section_check==true)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/tick.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="success">SUCCESS</font><br />';
		}

		$db->setQuery("SELECT COUNT(*) FROM #__components WHERE link = 'option=com_hwdvideoshare'");
		$components =& $db->loadResult();
		if ($components > 1)
		{
			$db->setQuery("SELECT id FROM #__components WHERE link = 'option=com_hwdvideoshare' ORDER BY id DESC LIMIT 1");
			$comid =& $db->loadResult();
			$db->setQuery("DELETE FROM #__components WHERE link  = 'option=com_hwdvideoshare' AND id != $comid  ");
			$db->query();
			$db->setQuery("DELETE FROM #__components WHERE #__components.option = 'com_hwdvideoshare' AND parent != $comid AND id != $comid ");
			$db->query();
		}
	?>
	</div>
	<?php } ?>

	<?php if ($j16) { ?>
	<div class="installer_box">
		<h3>Setup Default Permission Rules</h3>
		<?php
		$section_check=true;

		$db = & JFactory::getDBO();
		$db->SetQuery("SELECT rules FROM #__assets WHERE name = \"com_hwdvideoshare\"");
		$rules = $db->loadResult();
		if (empty($rules) || $rules == "{}")
		{
			$db->setQuery('UPDATE #__assets SET rules = \'{"core.admin":{"7":1},"core.manage":[],"core.frontend.access":{"1":1},"core.frontend.upload.local":{"1":1},"core.frontend.upload.tp":{"6":1,"2":1},"core.frontend.download":{"1":1},"core.frontend.group":{"1":1},"core.frontend.moderator":{"6":1},"core.frontend.wysiwyg":{"6":1}}\' WHERE name = \'com_hwdvideoshare\'');
			if (!$db->query())
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" title="" class="icon" /><font class="fail">ERROR:</font> Could not set default permissions.<br />';
				$section_check=false;
			}
		}

		if ($section_check==true)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/tick.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="success">SUCCESS</font><br />';
		}
	?>
	</div>
	<?php } ?>

	<div class="installer_box">
		<h3>Database Upgrade & Patches</h3>
		<?php
		$section_check=true;

		$HWDUpgrades = array();
		// from 1.1.3 to 1.1.4 ALPHA RC1:
		$HWDUpgrades[0]['test'] = "SELECT `default` FROM #__hwdvidsvideos";
		$HWDUpgrades[0]['updates'][0] = "ALTER TABLE `#__hwdvidsvideos` ALTER COLUMN `rating_number_votes` SET DEFAULT 0";
		$HWDUpgrades[0]['updates'][1] = "ALTER TABLE `#__hwdvidsvideos` ALTER COLUMN `rating_total_points` SET DEFAULT 0";
		$HWDUpgrades[0]['updates'][2] = "ALTER TABLE `#__hwdvidsvideos` ALTER COLUMN `updated_rating` SET DEFAULT 0";
		$HWDUpgrades[0]['updates'][3] = "UPDATE `#__hwdvidsvideos` SET video_type='youtube' WHERE video_type='plugin.youtube'";
		$HWDUpgrades[0]['updates'][4] = "UPDATE `#__hwdvidsvideos` SET video_type='google' WHERE video_type='plugin.google'";
		$HWDUpgrades[0]['updates'][5] = "UPDATE `#__hwdvidsvideos` SET video_type='jumpcut' WHERE video_type='plugin.jumpcut'";
		$HWDUpgrades[0]['updates'][6] = "UPDATE `#__hwdvidsvideos` SET video_type='revver' WHERE video_type='plugin.revver'";
		$HWDUpgrades[0]['updates'][7] = "UPDATE `#__hwdvidsvideos` SET video_type='vimeo' WHERE video_type='plugin.vimeo'";
		// from 1.1.4 ALPHA RC3.4 to 1.1.4 Alpha RC3.5:
		$HWDUpgrades[0]['updates'][8] = "UPDATE `#__hwdvidsvideos` SET video_type='bliptv' WHERE video_type='plugin.bliptv'";
		$HWDUpgrades[0]['updates'][9] = "UPDATE `#__hwdvidsvideos` SET video_type='local' WHERE video_type='01'";
		$HWDUpgrades[0]['updates'][10] = "UPDATE `#__hwdvidsvideos` SET video_type='swf' WHERE video_type='SWF'";
		$HWDUpgrades[0]['updates'][11] = "ALTER TABLE `#__hwdvidsvideos` CHANGE `video_id` `video_id` text default NULL";
		// from 1.1.5 Alpha Build 11502 to 1.1.5 Alpha Build 11503:
		$HWDUpgrades[0]['updates'][12] = "SELECT * FROM #__hwdvidsvideos LIMIT 0, 1";
		$HWDUpgrades[0]['updates'][13] = "UPDATE `#__hwdvidsvideos` SET video_type='youtube.com' WHERE video_type='youtube'";
		$HWDUpgrades[0]['updates'][14] = "UPDATE `#__hwdvidsvideos` SET video_type='google.com' WHERE video_type='google'";
		$HWDUpgrades[0]['updates'][15] = "UPDATE `#__hwdvidsvideos` SET video_type='blip.tv' WHERE video_type='blip'";
		$HWDUpgrades[0]['updates'][16] = "UPDATE `#__hwdvidsvideos` SET video_type='dailymotion.com' WHERE video_type='dailymotion'";
		$HWDUpgrades[0]['updates'][17] = "UPDATE `#__hwdvidsvideos` SET video_type='gametrailers.com' WHERE video_type='gametrailers'";
		$HWDUpgrades[0]['updates'][18] = "UPDATE `#__hwdvidsvideos` SET video_type='godtube.com' WHERE video_type='godtube'";
		$HWDUpgrades[0]['updates'][19] = "UPDATE `#__hwdvidsvideos` SET video_type='jumpcut.com' WHERE video_type='jumpcut'";
		$HWDUpgrades[0]['updates'][20] = "UPDATE `#__hwdvidsvideos` SET video_type='megavideo.com' WHERE video_type='megavideo'";
		$HWDUpgrades[0]['updates'][21] = "UPDATE `#__hwdvidsvideos` SET video_type='revver.com' WHERE video_type='revver'";
		$HWDUpgrades[0]['updates'][22] = "UPDATE `#__hwdvidsvideos` SET video_type='vimeo.com' WHERE video_type='vimeo'";
		$HWDUpgrades[0]['updates'][23] = "UPDATE `#__hwdvidsvideos` SET video_type='zshare.net' WHERE video_type='zshare'";
		// from 2.1.2 Alpha Build 21202 to 2.1.3 Alpha Build 21301:
		$HWDUpgrades[0]['updates'][24] = "ALTER TABLE `#__hwdvidsvideos` CHANGE `updated_rating` `updated_rating` float(4,2) default 0";
		// from svn-r362 to svn-r363:
		$HWDUpgrades[0]['updates'][25] = "CREATE INDEX `idx_category_id` ON `#__hwdvidsvideos` (category_id)";
		$HWDUpgrades[0]['updates'][26] = "CREATE INDEX `idx_videoid` ON `#__hwdvidslogs_views` (videoid)";
		$HWDUpgrades[0]['updates'][27] = "CREATE INDEX `idx_date` ON `#__hwdvidslogs_views` (date)";
		$HWDUpgrades[0]['updates'][28] = "CREATE INDEX `idx_videoid` ON `#__hwdvidslogs_votes` (videoid)";
		$HWDUpgrades[0]['updates'][29] = "CREATE INDEX `idx_date` ON `#__hwdvidslogs_votes` (date)";
		$HWDUpgrades[0]['updates'][30] = "CREATE INDEX `idx_videoid` ON `#__hwdvidslogs_favours` (videoid)";
		$HWDUpgrades[0]['updates'][31] = "CREATE INDEX `idx_date` ON `#__hwdvidslogs_favours` (date)";
		// from svn-r495 to svn-r496:
		$HWDUpgrades[0]['updates'][32] = "CREATE INDEX `idx_user_id` ON `#__hwdvidsvideos` (user_id)";

		$HWDUpgrades[0]['message'] = "All Version Compatibility Check";
		// from 1.1.4 ALPHA RC2.12 to 1.1.4 ALPHA RC2.13:
		$HWDUpgrades[1]['test'] = "SELECT `access_b_v` FROM #__hwdvidscategories";
		$HWDUpgrades[1]['updates'][0] = "ALTER TABLE `#__hwdvidsvideos` CHANGE `video_id` `video_id` VARCHAR( 500 ) NOT NULL";
		$HWDUpgrades[1]['updates'][1] = "ALTER TABLE `#__hwdvidscategories` ADD `access_b_v` TINYINT ( 1 ) DEFAULT '0' NOT NULL AFTER `date`";
		$HWDUpgrades[1]['updates'][2] = "ALTER TABLE `#__hwdvidscategories` ADD `access_u_r` VARCHAR( 7 ) DEFAULT 'RECURSE' NOT NULL AFTER `date`";
		$HWDUpgrades[1]['updates'][3] = "ALTER TABLE `#__hwdvidscategories` ADD `access_v_r` VARCHAR( 7 ) DEFAULT 'RECURSE' NOT NULL AFTER `date`";
		$HWDUpgrades[1]['updates'][4] = "ALTER TABLE `#__hwdvidscategories` ADD `access_u` INT ( 11 ) DEFAULT '-2' NOT NULL AFTER `date`";
		$HWDUpgrades[1]['updates'][5] = "ALTER TABLE `#__hwdvidscategories` ADD `access_v` INT ( 11 ) DEFAULT '-2' NOT NULL AFTER `date`";
		$HWDUpgrades[1]['message'] = "1.1.4 ALPHA RC2.12 to 1.1.4 1.1.4 ALPHA RC2.13";
		// from 1.1.4 ALPHA RC3.2 to 1.1.4 Alpha RC3.4:
		$HWDUpgrades[2]['test'] = "SELECT `num_vids` FROM #__hwdvidscategories";
		$HWDUpgrades[2]['updates'][0] = "ALTER TABLE `#__hwdvidscategories` ADD `parent` INT ( 11 ) DEFAULT '0' NOT NULL AFTER `id`";
		$HWDUpgrades[2]['updates'][1] = "ALTER TABLE `#__hwdvidscategories` ADD `access_lev_u` VARCHAR( 250 ) DEFAULT '0,1' NOT NULL AFTER `access_u`";
		$HWDUpgrades[2]['updates'][2] = "ALTER TABLE `#__hwdvidscategories` ADD `access_lev_v` VARCHAR( 250 ) DEFAULT '0,1' NOT NULL AFTER `access_v`";
		$HWDUpgrades[2]['updates'][3] = "ALTER TABLE `#__hwdvidscategories` ADD `num_vids` INT ( 11 ) DEFAULT '0' NOT NULL AFTER `access_lev_v`";
		$HWDUpgrades[2]['updates'][4] = "ALTER TABLE `#__hwdvidscategories` ADD `num_subcats` INT ( 11 ) DEFAULT '0' NOT NULL AFTER `num_vids`";
		$HWDUpgrades[2]['updates'][5] = "ALTER TABLE `#__hwdvidscategories` ADD `ordering` INT ( 11 ) DEFAULT '0' NOT NULL AFTER `num_subcats`";
		$HWDUpgrades[2]['updates'][6] = "ALTER TABLE `#__hwdvidsfavorites` ADD `date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL AFTER `videoid`";
		$HWDUpgrades[2]['updates'][7] = "ALTER TABLE `#__hwdvidsflagged_videos` ADD `userid` INT ( 11 ) DEFAULT '0' NOT NULL AFTER `username`";
		$HWDUpgrades[2]['updates'][8] = "ALTER TABLE `#__hwdvidsflagged_videos` ADD `ignore` TINYINT ( 1 ) DEFAULT '0' NOT NULL AFTER `status`";
		$HWDUpgrades[2]['updates'][9] = "ALTER TABLE `#__hwdvidsflagged_groups` ADD `userid` INT ( 11 ) DEFAULT '0' NOT NULL AFTER `username`";
		$HWDUpgrades[2]['updates'][10] = "ALTER TABLE `#__hwdvidsflagged_groups` ADD `ignore` TINYINT ( 1 ) DEFAULT '0' NOT NULL AFTER `status`";
		$HWDUpgrades[2]['updates'][11] = "ALTER TABLE `#__hwdvidsgroups` ADD `total_members` INT ( 50 ) DEFAULT '0' NOT NULL AFTER `adminid`";
		$HWDUpgrades[2]['updates'][12] = "ALTER TABLE `#__hwdvidsgroups` ADD `total_videos` INT ( 50 ) DEFAULT '0' NOT NULL AFTER `total_members`";
		$HWDUpgrades[2]['updates'][13] = "ALTER TABLE `#__hwdvidsgroups` ADD `ordering` INT ( 50 ) DEFAULT '0' NOT NULL AFTER `total_videos`";
		$HWDUpgrades[2]['updates'][14] = "ALTER TABLE `#__hwdvidsrating` ADD `ip` VARCHAR ( 15 ) DEFAULT '192.168.100.1' NOT NULL AFTER `videoid`";
		$HWDUpgrades[2]['updates'][15] = "ALTER TABLE `#__hwdvidsrating` ADD `date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL AFTER `ip`";
		$HWDUpgrades[2]['updates'][16] = "ALTER TABLE `#__hwdvidsvideos` ADD `ordering` INT ( 50 ) DEFAULT '0' NOT NULL AFTER `featured`";
		$HWDUpgrades[2]['updates'][17] = "SELECT * FROM #__hwdvidsvideos LIMIT 0, 1";
		$HWDUpgrades[2]['updates'][18] = "SELECT * FROM #__hwdvidsvideos LIMIT 0, 1";
		$HWDUpgrades[2]['updates'][19] = "UPDATE `#__hwdvidsvideos` SET video_type='local' WHERE video_type='01'";
		$HWDUpgrades[2]['updates'][20] = "UPDATE `#__hwdvidsvideos` SET video_type='swf' WHERE video_type='SWF'";
		$HWDUpgrades[2]['updates'][21] = "ALTER TABLE `#__hwdvidsvideos` CHANGE `video_id` `video_id` text NOT NULL";
		$HWDUpgrades[2]['message'] = "1.1.4 ALPHA RC3.2 to 1.1.4 Alpha RC3.4";
		// from 1.1.4 ALPHA RC3.5 to 1.1.5 Alpha Build 11501:
		$HWDUpgrades[3]['test'] = "SELECT `favour` FROM #__hwdvidslogs_favours";
		$HWDUpgrades[3]['updates'][0] = "ALTER TABLE `#__hwdvidslogs_favours` ADD `favour` TINYINT ( 1 ) DEFAULT '0' NOT NULL AFTER `userid`";
		$HWDUpgrades[3]['message'] = "1.1.4 ALPHA RC3.4 to 1.1.4 Alpha RC3.5";
		// from 2.1.1 Alpha Build 21106 to 2.1.1 Alpha Build 21107:
		$HWDUpgrades[4]['test'] = "SELECT `count` FROM #__hwdvidsantileech";
		$HWDUpgrades[4]['updates'][0] = "ALTER TABLE `#__hwdvidsantileech` ADD `count` INT ( 3 ) DEFAULT '0' NOT NULL AFTER `expiration`";
		$HWDUpgrades[4]['message'] = "Build 21106 Build 21107";
		// from 2.1.2 Alpha Build 21201 to 2.1.2 Alpha Build 21202:
		$HWDUpgrades[5]['test'] = "SELECT `thumbnail` FROM #__hwdvidsvideos";
		$HWDUpgrades[5]['updates'][0] = "ALTER TABLE `#__hwdvidsvideos` ADD `thumbnail` text DEFAULT '' NOT NULL AFTER `public_private`";
		$HWDUpgrades[5]['updates'][1] = "ALTER TABLE `#__hwdvidscategories` ADD `thumbnail` text DEFAULT '' NOT NULL AFTER `access_lev_v`";
		$HWDUpgrades[5]['updates'][2] = "ALTER TABLE `#__hwdvidsgroups` ADD `thumbnail` text DEFAULT '' NOT NULL AFTER `adminid`";
		$HWDUpgrades[5]['message'] = "Build 21201 Build 21202 [Patch 1]";
		// from 2.1.2 Alpha Build 21201 to 2.1.2 Alpha Build 21202:
		$HWDUpgrades[6]['test'] = "SELECT `thumb_snap` FROM #__hwdvidsvideos";
		$HWDUpgrades[6]['updates'][0] = "ALTER TABLE `#__hwdvidsvideos` ADD `thumb_snap` VARCHAR(7) DEFAULT '0:00:00' NOT NULL AFTER `public_private`";
		$HWDUpgrades[6]['message'] = "Build 21201 Build 21202 [Patch 2]";
		// from 2.1.5 to 2.1.6:
		$HWDUpgrades[7]['test'] = "SELECT `order_by` FROM #__hwdvidscategories";
		$HWDUpgrades[7]['updates'][0] = "ALTER TABLE `#__hwdvidscategories` ADD `order_by` VARCHAR(15) DEFAULT '0' NOT NULL AFTER `num_subcats`";
		$HWDUpgrades[7]['message'] = "Build 2.1.5 to 2.1.6 [Patch 1]";
		// from 2.1.6 to 2.1.7:
		$HWDUpgrades[8]['test'] = "SELECT `number_of_comments` FROM #__hwdvidsvideos";
		$HWDUpgrades[8]['updates'][0] = "ALTER TABLE `#__hwdvidsvideos` ADD `number_of_comments` INT(50) DEFAULT '0' NOT NULL AFTER `number_of_views`";
		$HWDUpgrades[8]['message'] = "Build 2.1.6 to 2.1.7 [Patch 1]";
		// from 2.1.6 to 2.1.7:
		$HWDUpgrades[9]['test'] = "SELECT `age_check` FROM #__hwdvidsvideos";
		$HWDUpgrades[9]['updates'][0] = "ALTER TABLE `#__hwdvidsvideos` ADD `age_check` INT(50) DEFAULT '-1' NOT NULL AFTER `number_of_comments`";
		$HWDUpgrades[9]['message'] = "Build 2.1.6 to 2.1.7 [Patch 2]";
		// from 2.1.6 to 2.1.7:
		$HWDUpgrades[10]['test'] = "SELECT `password` FROM #__hwdvidsvideos";
		$HWDUpgrades[10]['updates'][0] = "ALTER TABLE `#__hwdvidsvideos` ADD `password` VARCHAR(100) DEFAULT '' NOT NULL AFTER `user_id`";
		$HWDUpgrades[10]['message'] = "Build 2.1.6 to 2.1.7 [Patch 4]";
		// from 2.1.10 to 2.1.11:
		$HWDUpgrades[11]['test'] = "SELECT `views` FROM #__hwdvidschannels";
		$HWDUpgrades[11]['updates'][0] = "ALTER TABLE `#__hwdvidschannels` ADD `views` INT(50) DEFAULT '0' NOT NULL AFTER `user_id`";
		$HWDUpgrades[11]['message'] = "Build 2.1.10 to 2.1.11 [Patch 1]";

		//Apply Upgrades
		foreach ($HWDUpgrades AS $HWDUpgrade)
		{
			$db->setQuery($HWDUpgrade['test']);
			//if it fails test then apply upgrade
			if (!$db->query())
			{
				foreach($HWDUpgrade['updates'] as $UPDT)
				{
					$db->setQuery($UPDT);
					if(!$db->query())
					{
						$pos = strpos($UPDT, "CREATE INDEX");
						if ($pos === false)
						{
							//Upgrade failed
							echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> '.$HWDUpgrade['message'].'... <b>Upgrade failed! SQL error: ' . $db->stderr(true).'</b><br />';
							$section_check=false;
						}
					}
				}
			}
		}

		if ($section_check==true)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/tick.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="success">SUCCESS</font><br />';
		}
	?>
	</div>

	<div class="installer_box">
		<h3>Update Configuration</h3>
		<?php
		$section_check=true;

		// Set up initialisation
		$db->setQuery("UPDATE #__hwdvidsgs SET value=1 WHERE setting='initialise_now'");
		$initialise[0] = $db->query();
		foreach ($initialise as $i=>$icresult)
		{
			if (!$icresult)
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to set initialisation<br />';
				$section_check=false;
			}
		}

		$updt_config = drawConfig();
		if ($updt_config == false)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to update general configuration file<br />';
			$section_check=false;
		}

		$updt_sconfig = drawServerConfig();
		if ($updt_sconfig == false)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to update server configuration file<br />';
			$section_check=false;
		}

		if ($section_check==true)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/tick.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="success">SUCCESS</font><br />';
		}
	?>
	</div>

	<div class="installer_box">
		<h3>File and Folder Permissions</h3>
		<?php
		$section_check=true;

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'xml'.DS;
		if (!makeDirectoryWritable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'xml'.DS.'xspf'.DS;
		if (!makeDirectoryWritable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_default_config.php';
		if (!makeDirectoryExecutable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_finished_lib.php';
		if (!makeDirectoryExecutable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_get_progress.php';
		if (!makeDirectoryExecutable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_image_lib.php';
		if (!makeDirectoryExecutable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_ini.php';
		if (!makeDirectoryExecutable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_lib.php';
		if (!makeDirectoryExecutable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_link_upload.php';
		if (!makeDirectoryExecutable($path))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_set_progress.php';
		if (!makeDirectoryExecutable($path))
		{
			$section_check=false;
		}

		if ($section_check==true)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/tick.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="success">SUCCESS</font><br />';
		}
	?>
	</div>

	<div class="installer_box">
		<h3>Directory Creation Process</h3>
		<?php
		$section_check=true;

		$path = JPATH_SITE.DS.'hwdvideos'.DS;
		if (!createDirectory($path))
		{
			$section_check=false;
		}

		if (file_exists($path))
		{
			$path = JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS;
			if (!createDirectory($path))
			{
				$section_check=false;
			}

			$path = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS;
			if (!createDirectory($path))
			{
				$section_check=false;
			}

			$path = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS;
			if (!createDirectory($path))
			{
				$section_check=false;
			}
		}
		else
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> The directory <b>'.$path.'</b> could not be created or could not be made writable<br /><p style="padding-left: 26px">Manually do the following:<br />1) Create the directory <b>'.$path.'</b><br />2) Make this directory writable (chmod 0777)<br />3) Re-install hwdVideoShare</p><br />';
		}

		@copy(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'index.html', JPATH_SITE.DS.'hwdvideos'.DS.'index.html');
		@copy(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'index.html', JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS.'index.html');
		@copy(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'index.html', JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'index.html');
		@copy(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'index.html', JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS.'index.html');

		if ($section_check==true)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/tick.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="success">SUCCESS</font><br />';
		}
	?>
	</div>

	<div class="installer_box">
		<h3>Perl Configuration</h3>
		<?php
		$section_check=true;

		$path = JPATH_SITE.DS.'cgi-bin'.DS;
		if (!@createDirectory($path, true))
		{
			$section_check=false;
		}

		$path = JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS;
		if (!@createDirectory($path, true))
		{
			$section_check=false;
		}

		@unlink(JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS.'uu_default_config.pm');
		@unlink(JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS.'uu_ini_status.pl');
		@unlink(JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS.'uu_lib.pm');
		@unlink(JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS.'uu_upload.pl');

		if (file_exists(JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS))
		{
			if(!file_exists(JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS.'ubr_upload.pl'))
			{
				$path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'uberuploader'.DS.'ubr_upload.pl';
				if(file_exists($path))
				{
					if(!JFile::copy($path, JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS.'ubr_upload.pl'))
					{
						echo '<img src="../administrator/components/com_hwdvideoshare/images/info.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="warn">WARNING:</font> Failed to copy '.$path.' to the cgi-bin<br />';
						$section_check=false;
					}
				}
			}
			else
			{
				if (!makeDirectoryExecutable( JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS.'ubr_upload.pl'))
				{
					if (!makeDirectoryWritable( JPATH_SITE.DS.'cgi-bin'.DS.'uu'.DS.'ubr_upload.pl'))
					{
						echo '<img src="../administrator/components/com_hwdvideoshare/images/info.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="warn">WARNING:</font> Failed to make '.$path.' executable<br />';
						$section_check=false;
					}
				}
			}
		} else
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/images/info.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="warn">WARNING:</font> Failed to create '.$path.'<br />';
		}

		if ($section_check==true) {
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/tick.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="success">SUCCESS</font><br />';
		}
	?>
	</div>

	<div class="installer_box">
		<h3>Plugin Configuration</h3>
		<?php
		$section_check=true;

		$plugdir = JPATH_PLUGINS.DS.'hwdvs-language';
		if (!JFolder::create($plugdir))
		{
			JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$plugdir.'"');
		}
		$plugdir = JPATH_PLUGINS.DS.'hwdvs-template';
		if (!JFolder::create($plugdir))
		{
			JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$plugdir.'"');
		}
		$plugdir = JPATH_PLUGINS.DS.'hwdvs-thirdparty';
		if (!JFolder::create($plugdir))
		{
			JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$plugdir.'"');
		}
		$plugdir = JPATH_PLUGINS.DS.'hwdvs-videoplayer';
		if (!JFolder::create($plugdir))
		{
			JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$plugdir.'"');
		}

		// youtube

			$file_original = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'install'.DS.'plugins'.DS.'youtube';
			if ($j15)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-thirdparty';
			}
			if ($j16)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-thirdparty'.DS.'youtube';
				if (!JFolder::create($file_destination))
				{
					JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$file_destination.'"');
				}
			}

			if (!copyDirectoryRecursive($file_original, $file_destination ))
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to install Youtube plugin. Check your Joomla <a href="'.JURI::root( true ).DS.'administrator'.DS.'index.php?option=com_admin&task=sysinfo">Directory Permissions</a> are writeable.<br />';
				$section_check=false;
			}

			@deleteDirectoryRecursive( $file_original );

			if ($j15)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__plugins WHERE element = 'youtube' AND folder = 'hwdvs-thirdparty'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__plugins` VALUES ('', 'Youtube', 'youtube', 'hwdvs-thirdparty', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
					$db->setQuery( $query );
					$db->query();
				}
			}
			if ($j16)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__extensions WHERE type = 'plugin' AND element = 'youtube' AND folder = 'hwdvs-thirdparty'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__extensions` VALUES ('', 'Youtube', 'plugin', 'youtube', 'hwdvs-thirdparty', 0, 1, 1, 0, '', '', '', '', 0, '0000-00-00 00:00:00', '', 0)";
					$db->setQuery( $query );
					$db->query();
				}
			}

		// google

			$file_original = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'install'.DS.'plugins'.DS.'google'.DS;
			if ($j15)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-thirdparty';
			}
			if ($j16)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-thirdparty'.DS.'google';
				if (!JFolder::create($file_destination))
				{
					JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$file_destination.'"');
				}
			}

			if (!copyDirectoryRecursive($file_original, $file_destination ))
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to install Google plugin. Check your Joomla <a href="'.JURI::root( true ).DS.'administrator'.DS.'index.php?option=com_admin&task=sysinfo">Directory Permissions</a> are writeable.<br />';
				$section_check=false;
			}

			@deleteDirectoryRecursive( $file_original );

			if ($j15)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__plugins WHERE element = 'google' AND folder = 'hwdvs-thirdparty'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__plugins` VALUES ('', 'Google', 'google', 'hwdvs-thirdparty', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
					$db->setQuery( $query );
					$db->query();
				}
			}
			if ($j16)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__extensions WHERE type = 'plugin' AND element = 'google' AND folder = 'hwdvs-thirdparty'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__extensions` VALUES ('', 'Google', 'plugin', 'google', 'hwdvs-thirdparty', 0, 1, 1, 0, '', '', '', '', 0, '0000-00-00 00:00:00', '', 0)";
					$db->setQuery( $query );
					$db->query();
				}
			}

		// remote

			$file_original = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'install'.DS.'plugins'.DS.'remote'.DS;
			if ($j15)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-thirdparty';
			}
			if ($j16)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-thirdparty'.DS.'remote';
				if (!JFolder::create($file_destination))
				{
					JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$file_destination.'"');
				}
			}

			if (!copyDirectoryRecursive($file_original, $file_destination ))
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to install Remote plugin. Check your Joomla <a href="'.JURI::root( true ).DS.'administrator'.DS.'index.php?option=com_admin&task=sysinfo">Directory Permissions</a> are writeable.<br />';
				$section_check=false;
			}

			@deleteDirectoryRecursive( $file_original );

			if ($j15)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__plugins WHERE element = 'remote' AND folder = 'hwdvs-thirdparty'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__plugins` VALUES ('', 'Remote', 'remote', 'hwdvs-thirdparty', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
					$db->setQuery( $query );
					$db->query();
				}
			}
			if ($j16)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__extensions WHERE type = 'plugin' AND element = 'remote' AND folder = 'hwdvs-thirdparty'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__extensions` VALUES ('', 'Remote', 'plugin', 'remote', 'hwdvs-thirdparty', 0, 1, 1, 0, '', '', '', '', 0, '0000-00-00 00:00:00', '', 0)";
					$db->setQuery( $query );
					$db->query();
				}
			}

		// flow player

			$file_original = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'install'.DS.'plugins'.DS.'flow'.DS;
			if ($j15)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-videoplayer';
			}
			if ($j16)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-videoplayer'.DS.'flow';
				if (!JFolder::create($file_destination))
				{
					JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$file_destination.'"');
				}
			}

			if (!copyDirectoryRecursive($file_original, $file_destination ))
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to install Flow Player plugin. Check your Joomla <a href="'.JURI::root( true ).DS.'administrator'.DS.'index.php?option=com_admin&task=sysinfo">Directory Permissions</a> are writeable.<br />';
				$section_check=false;
			}

			@deleteDirectoryRecursive( $file_original );

			if ($j15)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__plugins WHERE element = 'flow' AND folder = 'hwdvs-videoplayer'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__plugins` VALUES ('', 'Flow Player', 'flow', 'hwdvs-videoplayer', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
					$db->setQuery( $query );
					$db->query();
				}
			}
			if ($j16)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__extensions WHERE type = 'plugin' AND element = 'flow' AND folder = 'hwdvs-videoplayer'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__extensions` VALUES ('', 'Flow Player', 'plugin', 'flow', 'hwdvs-videoplayer', 0, 1, 1, 0, '', '', '', '', 0, '0000-00-00 00:00:00', '', 0)";
					$db->setQuery( $query );
					$db->query();
				}
			}

		// jw flv player

			$file_original = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'install'.DS.'plugins'.DS.'jwflv'.DS;
			if ($j15)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-videoplayer';
			}
			if ($j16)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-videoplayer'.DS.'jwflv';
				if (!JFolder::create($file_destination))
				{
					JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$file_destination.'"');
				}
			}

			if (!copyDirectoryRecursive($file_original, $file_destination ))
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to install JW FLV Player plugin. Check your Joomla <a href="'.JURI::root( true ).DS.'administrator'.DS.'index.php?option=com_admin&task=sysinfo">Directory Permissions</a> are writeable.<br />';
				$section_check=false;
			}

			@deleteDirectoryRecursive( $file_original );

			if ($j15)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__plugins WHERE element = 'jwflv' AND folder = 'hwdvs-videoplayer'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__plugins` VALUES ('', 'JW FLV (Version 4) Player', 'jwflv', 'hwdvs-videoplayer', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
					$db->setQuery( $query );
					$db->query();
				}
			}
			if ($j16)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__extensions WHERE type = 'plugin' AND element = 'jwflv' AND folder = 'hwdvs-videoplayer'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__extensions` VALUES ('', 'JW FLV (Version 4) Player', 'plugin', 'jwflv', 'hwdvs-videoplayer', 0, 1, 1, 0, '', '', '', '', 0, '0000-00-00 00:00:00', '', 0)";
					$db->setQuery( $query );
					$db->query();
				}
			}

		// english

			$file_original = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'install'.DS.'plugins'.DS.'english'.DS;
			if ($j15)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-language';
			}
			if ($j16)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-language'.DS.'english';
				if (!JFolder::create($file_destination))
				{
					JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$file_destination.'"');
				}
			}

			if (!copyDirectoryRecursive($file_original, $file_destination ))
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to install English plugin. Check your Joomla <a href="'.JURI::root( true ).DS.'administrator'.DS.'index.php?option=com_admin&task=sysinfo">Directory Permissions</a> are writeable.<br />';
				$section_check=false;
			}

			@deleteDirectoryRecursive( $file_original );

			if ($j15)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__plugins WHERE element = 'english' AND folder = 'hwdvs-language'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__plugins` VALUES ('', 'English Language', 'english', 'hwdvs-language', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
					$db->setQuery( $query );
					$db->query();
				}
			}
			if ($j16)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__extensions WHERE type = 'plugin' AND element = 'english' AND folder = 'hwdvs-language'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__extensions` VALUES ('', 'English Language', 'plugin', 'english', 'hwdvs-language', 0, 1, 1, 0, '', '', '', '', 0, '0000-00-00 00:00:00', '', 0)";
					$db->setQuery( $query );
					$db->query();
				}
			}

		// default template

			$file_original = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'install'.DS.'plugins'.DS.'default'.DS;
			if ($j15)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-template';
			}
			if ($j16)
			{
				$file_destination = JPATH_PLUGINS.DS.'hwdvs-template'.DS.'default';
				if (!JFolder::create($file_destination))
				{
					JError::raiseWarning(1, 'JInstaller::install: '.JText::_('Failed to create directory').' "'.$file_destination.'"');
				}
			}

			if (!copyDirectoryRecursive($file_original, $file_destination ))
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="fail">ERROR:</font> Failed to install Default Template plugin. Check your Joomla <a href="'.JURI::root( true ).DS.'administrator'.DS.'index.php?option=com_admin&task=sysinfo">Directory Permissions</a> are writeable.<br />';
				$section_check=false;
			}

			@deleteDirectoryRecursive( $file_original );

			if ($j15)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__plugins WHERE element = 'default' AND folder = 'hwdvs-template'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__plugins` VALUES ('', 'Default Template', 'default', 'hwdvs-template', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
					$db->setQuery( $query );
					$db->query();
				}
			}
			if ($j16)
			{
				$db->setQuery("SELECT COUNT(*) FROM #__extensions WHERE type = 'plugin' AND element = 'default' AND folder = 'hwdvs-template'");
				$count = $db->loadResult();
				if ($count == 0)
				{
					$query = "INSERT IGNORE INTO `#__extensions` VALUES ('', 'Default Template', 'plugin', 'default', 'hwdvs-template', 0, 1, 1, 0, '', '', '', '', 0, '0000-00-00 00:00:00', '', 0)";
					$db->setQuery( $query );
					$db->query();
				}
			}


		if ($section_check==true)
		{
			echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/tick.png" border="0" alt="" width="16" height="16" title="" class="icon" /><font class="success">SUCCESS</font><br />';
		}
	?>
	</div>
<?php
}
/**
 * Draws the general configuration file
 */
function drawConfig()
{
	global $database;
	$db =& JFactory::getDBO();

	$config = "<?php\n";
	$config .= "class hwd_vs_Config{ \n\n";
	$config .= "  // Stores the only allowable instance of this class.\n";
	$config .= "  var \$instanceConfig = null;\n\n";
	$config .= "  // Member variables\n";

	// print out config
	$query  = 'SELECT * FROM #__hwdvidsgs';
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	for ($i=0, $n=count($rows); $i < $n; $i++)
	{
		$row = $rows[$i];
		if ($row->setting == "flvplay_width" && empty($row->value)) { $row->value = "450"; }
		if ($row->setting == "customencode") { $row->value = addslashes($row->value); }
		$config .= "  var \$".$row->setting." = '".$row->value."';\n";
	}

	$config .= "\n/**\n";
	$config .= "  * get_instance\n";
	$config .= "  *	Description:\n";
	$config .= "  *		This function is used to instantiate the object\n";
	$config .= "  * 		and ensure only one of this type exists at any\n";
	$config .= "  *		time. It returns a reference to the only Config\n";
	$config .= "  *		instance.\n";
	$config .= "  *	Parameters:\n";
	$config .= "  *		none\n";
	$config .= "  *	Returns:\n";
	$config .= "  *		Config\n";
	$config .= "  **/\n\n";
	$config .= "  function get_instance(){\n";
	$config .= "    \$instanceConfig = new hwd_vs_Config;\n";
	$config .= "    return \$instanceConfig;\n";
	$config .= "  }\n\n";
	$config .= "}\n";
	$config .= "?>";

	$configFile = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php';
	if (!JFile::write($configFile, $config)) {
		return false;
	}

	return true;
}
/**
 * Draws the server configuration file
 */
function drawServerConfig()
{
	global $database;
	$db =& JFactory::getDBO();

	$config = "<?php\n";
	$config .= "class hwd_vs_SConfig{ \n\n";
	$config .= "  // Stores the only allowable instance of this class.\n";
	$config .= "  var \$instanceConfig = null;\n\n";
	$config .= "  // Member variables\n";

	// print out config
	$query  = 'SELECT * FROM #__hwdvidsss';
	$db->setQuery($query);
	$rows = $db->loadObjectList();
	for ($i=0, $n=count($rows); $i < $n; $i++)
	{
		$row = $rows[$i];
		$config .= "  var \$".$row->setting." = '".$row->value."';\n";
	}

	$config .= "\n/**\n";
	$config .= "  * get_instance\n";
	$config .= "  *	Description:\n";
	$config .= "  *		This function is used to instantiate the object\n";
	$config .= "  * 		and ensure only one of this type exists at any\n";
	$config .= "  *		time. It returns a reference to the only Config\n";
	$config .= "  *		instance.\n";
	$config .= "  *	Parameters:\n";
	$config .= "  *		none\n";
	$config .= "  *	Returns:\n";
	$config .= "  *		Config\n";
	$config .= "  **/\n\n";
	$config .= "  function get_instance(){\n";
	$config .= "    \$instanceConfig = new hwd_vs_SConfig;\n";
	$config .= "    return \$instanceConfig;\n";
	$config .= "  }\n\n";
	$config .= "}\n";
	$config .= "?>";

	$configFile = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php';
	if (!JFile::write($configFile, $config)) {
		return false;
	}

	return true;
}
/**
 * Draws the server configuration file
 */
function createDirectory($path, $suppress=false)
{
	if(!file_exists($path))
	{
		if(JFolder::create($path, "0755"))
		{
			@JPath::setPermissions($path, "0644", "0755");
		}
		else
		{
			if (!$suppress)
			{
				echo '<img src="../administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="" title="" class="icon" /><font class="fail">ERROR:</font> Failed to create '.$path.'<br />';
			}
			return false;
		}
	}
	return true;
}
/**
 * Draws the server configuration file
 */
function makeDirectoryWritable($path)
{
	@JPath::setPermissions($path, "0644", "0755");
	return true;
}
/**
 * Makes a directory executable using Joomla
 *
 * @return       Nothing
 */
function makeDirectoryExecutable($path)
{
	JPath::setPermissions($path, "0755", "0755");
	return true;
}
/**
 * Copy a directory
 */
function copyDirectoryRecursive($source, $dest)
{
	$result = false;

	if (is_file($source))
	{
		if ($dest[strlen($dest)-1] == '/')
		{
			if (!file_exists($dest))
			{
				cmfcDirectory::makeAll($dest, "0755", true);
			}
			$__dest = $dest."/".basename($source);
		}
		else
		{
			$__dest = $dest;
		}
		$result = JFile::copy($source, $__dest);
		@JPath::setPermissions($__dest, "0644", "0755");
	}
	elseif(is_dir($source))
	{
		if ($dest[strlen($dest)-1] == '/')
		{
			if ($source[strlen($source)-1] == '/')
			{
				//Copy only contents
			}
			else
			{
				//Change parent itself and its contents
				$dest=$dest.basename($source);
				JFolder::create($dest);
				@JPath::setPermissions($dest, "0644", "0755");
			}
		}
		else
		{
			if ($source[strlen($source)-1]=='/')
			{
				//Copy parent directory with new name and all its content
				JFolder::create($dest, "0755");
				@JPath::setPermissions($dest, "0644", "0755");
			}
			else
			{
				//Copy parent directory with new name and all its content
				JFolder::create($dest, "0755");
				@JPath::setPermissions($dest, "0644", "0755");
			}
		}

		$dirHandle = opendir($source);
		while($file = readdir($dirHandle))
		{
			if($file != "." && $file != "..")
			{
				if(!is_dir($source."/".$file))
				{
					$__dest = $dest."/".$file;
				}
				else
				{
					$__dest = $dest."/".$file;
				}
				//echo "$source/$file ||| $__dest<br />";
				$result=copyDirectoryRecursive($source."/".$file, $__dest);
			}
		}
		closedir($dirHandle);
	}
	else
	{
		$result=false;
	}
	return $result;
}

/**
 * Delete a directory
 */
function deleteDirectoryRecursive($directory, $empty=FALSE)
   {
     if(substr($directory,-1) == '/')
     {
         $directory = substr($directory,0,-1);
     }
     if(!file_exists($directory) || !is_dir($directory))
     {
        return FALSE;
     }elseif(is_readable($directory))
     {
         $handle = opendir($directory);
         while (FALSE !== ($item = readdir($handle)))
         {
             if($item != '.' && $item != '..')
             {
                 $path = $directory.'/'.$item;
                 if(is_dir($path))
                 {
                     deleteDirectoryRecursive($path);
                 }else{
                     unlink($path);
                 }
             }
         }
         closedir($handle);
         if($empty == FALSE)
         {
             if(!rmdir($directory))
             {
                 return FALSE;
             }
         }
     }
     return TRUE;
 }
?>