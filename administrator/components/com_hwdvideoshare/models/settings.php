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

class hwdvids_BE_settings
{
   /**
	*/
	function showserversettings()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		hwdvsDrawFile::serverConfig();
		hwdvids_HTML::showserversettings();
	}
   /**
	*/
	function showgeneralsettings()
	{
		global $j15;
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		hwdvsDrawFile::generalConfig();

		if ($j15)
		{
			jimport('joomla.user.authorization');
			$acl=& JFactory::getACL();

			$gtree=array();
			$gtree[] = JHTML::_('select.option', -2 , '- ' ._HWDVIDS_SELECT_EVERYONE . ' -');
			$gtree[] = JHTML::_('select.option', -1, '- ' . _HWDVIDS_SELECT_ALLREGUSER . ' -');
			$gtree = array_merge( $gtree, $acl->get_group_children_tree( null, 'USERS', false  ) );
		}
		else
		{
			$gtree = null;
		}

		hwdvids_HTML::showgeneralsettings($gtree);
	}
   /**
	*/
	function showlayoutsettings()
	{
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		hwdvsDrawFile::generalConfig();
    	hwdvids_HTML::showlayoutsettings($gtree);
	}
   /**
	*/
	function saveserver($ffmpegpath, $flvtool2path, $mencoderpath, $phppath)
	{
		$db = & JFactory::getDBO();
		$app = & JFactory::getApplication();

		//register globals = off
		if (!empty($_POST)) {
			extract($_POST);
		}

		if (($ffmpegpath[strlen($ffmpegpath)-1]) == "\\") { $ffmpegpath = substr_replace($ffmpegpath ,"",-2); }
        if (($flvtool2path[strlen($flvtool2path)-1]) == "\\") { $flvtool2path = substr_replace($flvtool2path ,"",-2); }
		if (($mencoderpath[strlen($mencoderpath)-1]) == "\\") { $mencoderpath = substr_replace($mencoderpath ,"",-2); }
		if (($phppath[strlen($phppath)-1]) == "\\") { $phppath = substr_replace($phppath ,"",-2); }
		if (($wgetpath[strlen($wgetpath)-1]) == "\\") { $wgetpath = substr_replace($wgetpath ,"",-2); }

		// update server settings db
		$HWDSS['updates'][0] = "UPDATE #__hwdvidsss SET value = '$ffmpegpath' WHERE setting = 'ffmpegpath'";
		$HWDSS['updates'][1] = "UPDATE #__hwdvidsss SET value = '$flvtool2path' WHERE setting = 'flvtool2path'";
		$HWDSS['updates'][2] = "UPDATE #__hwdvidsss SET value = '$mencoderpath' WHERE setting = 'mencoderpath'";
		$HWDSS['updates'][3] = "UPDATE #__hwdvidsss SET value = '$phppath' WHERE setting = 'phppath'";
		$HWDSS['updates'][4] = "UPDATE #__hwdvidsss SET value = '$wgetpath' WHERE setting = 'wgetpath'";
		$HWDSS['updates'][5] = "UPDATE #__hwdvidsss SET value = '$qtfaststart' WHERE setting = 'qtfaststart'";
		$HWDSS['message'] = "Saving server settings to database";
		// apply
		foreach($HWDSS['updates'] as $UPDT) {
			$db->setQuery($UPDT);
			if(!$db->query()) {
				//Save failed
				print("<font color=red>".$HWDSS['message']." failed! SQL error:" . $db->stderr(true)."</font><br />");
				return;
			}
		}

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		$updt_config = hwdvsDrawFile::serverConfig();
		if ($updt_config) {
			$app->enqueueMessage(_HWDVIDS_ALERT_ADMIN_SETSAVED);
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=serversettings' );
		} else {
			$app->enqueueMessage(_HWDVIDS_ALERT_ADMIN_SETNOTSAVED);
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=serversettings' );
		}
	}
   /**
	*/
	function savegeneral()
	{
		$app = & JFactory::getApplication();

		hwdvids_BE_settings::updateFromPost();

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		$updt_config = hwdvsDrawFile::generalConfig();
		if ($updt_config) {
			$app->enqueueMessage(_HWDVIDS_ALERT_ADMIN_SETSAVED);
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=generalsettings' );
		} else {
			$app->enqueueMessage(_HWDVIDS_ALERT_ADMIN_SETNOTSAVED);
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=generalsettings' );
		}
	}
   /**
	* Save general settings
	*/
	function saveLayout()
	{
		$app = & JFactory::getApplication();

		hwdvids_BE_settings::updateFromPost();

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		$updt_config = hwdvsDrawFile::generalConfig();
		if ($updt_config) {
			$app->enqueueMessage(_HWDVIDS_ALERT_ADMIN_SETSAVED);
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=layoutsettings' );
		} else {
			$app->enqueueMessage(_HWDVIDS_ALERT_ADMIN_SETNOTSAVED);
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=settings' );
		}
	}
   /**
	*/
	function updateFromPost()
	{
		$db = & JFactory::getDBO();

		//register globals = off
		if (!empty($_POST)) {
			extract($_POST);
		}

		if (isset($ad1custom) && $ad1custom !== '') { $ad1custom = trim( $ad1custom ); }
		if (isset($customencode) && $customencode !== '') {
			$customencode = stripslashes($customencode);
			$customencode = preg_replace("/[^A-Za-z0-9-.'_\s\s+]/", "", $customencode);
			$customencode = addslashes($customencode);

		}
		if (isset($cnvt_vbitrate) && $cnvt_vbitrate !== '') { $cnvt_vbitrate = intval($cnvt_vbitrate); }
		if (isset($cnvt_abitrate) && $cnvt_abitrate !== '') { $cnvt_abitrate = intval($cnvt_abitrate); }
		if (isset($cnvt_asr) && $cnvt_asr !== '') { $cnvt_asr = intval($cnvt_asr); }
		if (isset($cnvt_fsize) && $cnvt_fsize !== '') { $cnvt_fsize = ereg_replace("[^A-Za-z0-9]", "", $cnvt_fsize); }

		if (isset($cbitemid) && $cbitemid !== '') { $cbitemid = intval($cbitemid); }
		if (isset($language) && $language !== '') {
			$hwdvids_language = explode("|", $language);
			$hwdvids_language_file = $hwdvids_language[0];
			$hwdvids_language_path = $hwdvids_language[1];
		}
		if (isset($template) && $template !== '') {
			$hwdvids_template = explode("|", $template);
			$hwdvids_template_file = $hwdvids_template[0];
			$hwdvids_template_path = $hwdvids_template[1];
		}
		if (isset($videoplayer) && $videoplayer !== '') {
			$hwdvids_videoplayer = explode("|", $videoplayer);
			$hwdvids_videoplayer_file = $hwdvids_videoplayer[0];
			$hwdvids_videoplayer_path = $hwdvids_videoplayer[1];
		}
		if (isset($accesslevel_main) && $accesslevel_main !== '') { $accesslevel_main = @implode(",", $accesslevel_main); }
		if (isset($accesslevel_upld) && $accesslevel_upld !== '') { $accesslevel_upld = @implode(",", $accesslevel_upld); }
		if (isset($accesslevel_plyr) && $accesslevel_plyr !== '') { $accesslevel_plyr = @implode(",", $accesslevel_plyr); }
		if (isset($accesslevel_grps) && $accesslevel_grps !== '') { $accesslevel_grps = @implode(",", $accesslevel_grps); }
		if (isset($accesslevel_dnld) && $accesslevel_dnld !== '') { $accesslevel_dnld = @implode(",", $accesslevel_dnld); }
		if (isset($accesslevel_ultp) && $accesslevel_ultp !== '') { $accesslevel_ultp = @implode(",", $accesslevel_ultp); }

		$plarray01 = explode(",", $xmlcustom01);
		$xmlcustom01 = null;
		for ($i=0, $n=count($plarray01); $i < $n; $i++) {
			if ($i == ($n-1)) { $xmlcustom01.= intval($plarray01[$i]); } else { $xmlcustom01.= intval($plarray01[$i]).","; }
		}

		$plarray02 = explode(",", $xmlcustom02);
		$xmlcustom02 = null;
		for ($i=0, $n=count($plarray02); $i < $n; $i++) {
			if ($i == ($n-1)) { $xmlcustom02.= intval($plarray02[$i]); } else { $xmlcustom02.= intval($plarray02[$i]).","; }
		}

		$plarray03 = explode(",", $xmlcustom03);
		$xmlcustom03 = null;
		for ($i=0, $n=count($plarray03); $i < $n; $i++) {
			if ($i == ($n-1)) { $xmlcustom03.= intval($plarray03[$i]); } else { $xmlcustom03.= intval($plarray03[$i]).","; }
		}

		$plarray04 = explode(",", $xmlcustom04);
		$xmlcustom04 = null;
		for ($i=0, $n=count($plarray04); $i < $n; $i++) {
			if ($i == ($n-1)) { $xmlcustom04.= intval($plarray04[$i]); } else { $xmlcustom04.= intval($plarray04[$i]).","; }
		}

		$plarray05 = explode(",", $xmlcustom05);
		$xmlcustom05 = null;
		for ($i=0, $n=count($plarray05); $i < $n; $i++) {
			if ($i == ($n-1)) { $xmlcustom05.= intval($plarray05[$i]); } else { $xmlcustom05.= intval($plarray05[$i]).","; }
		}

		//linux
		if (substr($vsdirectory, -1) == "/")
		{
			$vsdirectory = substr($vsdirectory, 0, -1);
		}
		//windows
		if (($vsdirectory[strlen($vsdirectory)-1]) == "\\") { $vsdirectory = substr_replace($vsdirectory ,"",-2); }
		if (($vsdirectory[strlen($vsdirectory)-1]) == "\\") { $vsdirectory = substr_replace($vsdirectory ,"",-2); }
		$vsdirectory = str_replace("\\", "\\\\", $vsdirectory);

		// update server settings db
		if (isset($vpp) && $vpp !== '') { $HWDGS['updates'][0] = "UPDATE #__hwdvidsgs SET value = '$vpp' WHERE setting = 'vpp'"; }
		if (isset($fpfeaturedvids) && $fpfeaturedvids !== '') { $HWDGS['updates'][1] = "UPDATE #__hwdvidsgs SET value = '$fpfeaturedvids' WHERE setting = 'fpfeaturedvids'"; }
		if (isset($gpp) && $gpp !== '') { $HWDGS['updates'][2] = "UPDATE #__hwdvidsgs SET value = '$gpp' WHERE setting = 'gpp'"; }
		if (isset($fpfeaturedgroups) && $fpfeaturedgroups !== '') { $HWDGS['updates'][3] = "UPDATE #__hwdvidsgs SET value = '$fpfeaturedgroups' WHERE setting = 'fpfeaturedgroups'"; }
		if (isset($aav) && $aav !== '') { $HWDGS['updates'][4] = "UPDATE #__hwdvidsgs SET value = '$aav' WHERE setting = 'aav'"; }
		if (isset($aag) && $aag !== '') { $HWDGS['updates'][5] = "UPDATE #__hwdvidsgs SET value = '$aag' WHERE setting = 'aag'"; }
		if (isset($aac) && $aac !== '') { $HWDGS['updates'][6] = "UPDATE #__hwdvidsgs SET value = '$aac' WHERE setting = 'aac'"; }
		if (isset($hwdvids_language_path) && $hwdvids_language_path !== '') { $HWDGS['updates'][7] = "UPDATE #__hwdvidsgs SET value = '$hwdvids_language_path' WHERE setting = 'hwdvids_language_path'"; }
		if (isset($hwdvids_language_file) && $hwdvids_language_file !== '') { $HWDGS['updates'][8] = "UPDATE #__hwdvidsgs SET value = '$hwdvids_language_file' WHERE setting = 'hwdvids_language_file'"; }
		if (isset($hwdvids_template_path) && $hwdvids_template_path !== '') { $HWDGS['updates'][9] = "UPDATE #__hwdvidsgs SET value = '$hwdvids_template_path' WHERE setting = 'hwdvids_template_path'"; }
		if (isset($hwdvids_template_file) && $hwdvids_template_file !== '') { $HWDGS['updates'][10] = "UPDATE #__hwdvidsgs SET value = '$hwdvids_template_file' WHERE setting = 'hwdvids_template_file'"; }
		if (isset($diable_nav_videos) && $diable_nav_videos !== '') { $HWDGS['updates'][11] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_videos' WHERE setting = 'diable_nav_videos'"; }
		if (isset($diable_nav_catego) && $diable_nav_catego !== '') { $HWDGS['updates'][12] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_catego' WHERE setting = 'diable_nav_catego'"; }
		if (isset($diable_nav_groups) && $diable_nav_groups !== '') { $HWDGS['updates'][13] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_groups' WHERE setting = 'diable_nav_groups'"; }
		if (isset($diable_nav_upload) && $diable_nav_upload !== '') { $HWDGS['updates'][14] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_upload' WHERE setting = 'diable_nav_upload'"; }
		if (isset($radiusrc) && $radiusrc !== '') { $HWDGS['updates'][15] = "UPDATE #__hwdvidsgs SET value = '$radiusrc' WHERE setting = 'radiusrc'"; }
		if (isset($logconvert) && $logconvert !== '') { $HWDGS['updates'][16] = "UPDATE #__hwdvidsgs SET value = '$logconvert' WHERE setting = 'logconvert'"; }
		if (isset($debugconvert) && $debugconvert !== '') { $HWDGS['updates'][17] = "UPDATE #__hwdvidsgs SET value = '$debugconvert' WHERE setting = 'debugconvert'"; }
		if (isset($deleteoriginal) && $deleteoriginal !== '') { $HWDGS['updates'][18] = "UPDATE #__hwdvidsgs SET value = '$deleteoriginal' WHERE setting = 'deleteoriginal'"; }
		if (isset($mailvideonotification) && $mailvideonotification !== '') { $HWDGS['updates'][19] = "UPDATE #__hwdvidsgs SET value = '$mailvideonotification' WHERE setting = 'mailvideonotification'"; }
		if (isset($mailgroupnotification) && $mailgroupnotification !== '') { $HWDGS['updates'][20] = "UPDATE #__hwdvidsgs SET value = '$mailgroupnotification' WHERE setting = 'mailgroupnotification'"; }
		if (isset($mailnotifyaddress) && $mailnotifyaddress !== '') { $HWDGS['updates'][21] = "UPDATE #__hwdvidsgs SET value = '$mailnotifyaddress' WHERE setting = 'mailnotifyaddress'"; }
		if (isset($cbint) && $cbint !== '') { $HWDGS['updates'][23] = "UPDATE #__hwdvidsgs SET value = '$cbint' WHERE setting = 'cbint'"; }
		if (isset($disablelocupld) && $disablelocupld !== '') { $HWDGS['updates'][24] = "UPDATE #__hwdvidsgs SET value = '$disablelocupld' WHERE setting = 'disablelocupld'"; }
		if (isset($flvplay_width) && $flvplay_width !== '') { $HWDGS['updates'][25] = "UPDATE #__hwdvidsgs SET value = '$flvplay_width' WHERE setting = 'flvplay_width'"; }
		if (isset($flvplay_height) && $flvplay_height !== '') { $HWDGS['updates'][26] = "UPDATE #__hwdvidsgs SET value = '$flvplay_height' WHERE setting = 'flvplay_height'"; }
		if (isset($disablecaptcha) && $disablecaptcha !== '') { $HWDGS['updates'][27] = "UPDATE #__hwdvidsgs SET value = '$disablecaptcha' WHERE setting = 'disablecaptcha'"; }
		if (isset($aa3v) && $aa3v !== '') { $HWDGS['updates'][28] = "UPDATE #__hwdvidsgs SET value = '$aa3v' WHERE setting = 'aa3v'"; }
		if (isset($showcredit) && $showcredit !== '') { $HWDGS['updates'][29] = "UPDATE #__hwdvidsgs SET value = '$showcredit' WHERE setting = 'showcredit'"; }
		if (isset($allowvidedit) && $allowvidedit !== '') { $HWDGS['updates'][30] = "UPDATE #__hwdvidsgs SET value = '$allowvidedit' WHERE setting = 'allowvidedit'"; }
		if (isset($allowviddel) && $allowviddel !== '') { $HWDGS['updates'][31] = "UPDATE #__hwdvidsgs SET value = '$allowviddel' WHERE setting = 'allowviddel'"; }
		if (isset($locupldmeth) && $locupldmeth !== '') { $HWDGS['updates'][32] = "UPDATE #__hwdvidsgs SET value = '$locupldmeth' WHERE setting = 'locupldmeth'"; }
		if (isset($requiredins) && $requiredins !== '') { $HWDGS['updates'][33] = "UPDATE #__hwdvidsgs SET value = '$requiredins' WHERE setting = 'requiredins'"; }
		if ($task == "savegeneral") {
			$HWDGS['updates'][34] = "UPDATE #__hwdvidsgs SET value = '$ft_mpg' WHERE setting = 'ft_mpg'";
			$HWDGS['updates'][35] = "UPDATE #__hwdvidsgs SET value = '$ft_mpeg' WHERE setting = 'ft_mpeg'";
			$HWDGS['updates'][36] = "UPDATE #__hwdvidsgs SET value = '$ft_avi' WHERE setting = 'ft_avi'";
			$HWDGS['updates'][37] = "UPDATE #__hwdvidsgs SET value = '$ft_divx' WHERE setting = 'ft_divx'";
			$HWDGS['updates'][38] = "UPDATE #__hwdvidsgs SET value = '$ft_mp4' WHERE setting = 'ft_mp4'";
			$HWDGS['updates'][39] = "UPDATE #__hwdvidsgs SET value = '$ft_flv' WHERE setting = 'ft_flv'";
			$HWDGS['updates'][40] = "UPDATE #__hwdvidsgs SET value = '$ft_wmv' WHERE setting = 'ft_wmv'";
			$HWDGS['updates'][41] = "UPDATE #__hwdvidsgs SET value = '$ft_rm' WHERE setting = 'ft_rm'";
			$HWDGS['updates'][42] = "UPDATE #__hwdvidsgs SET value = '$ft_mov' WHERE setting = 'ft_mov'";
			$HWDGS['updates'][43] = "UPDATE #__hwdvidsgs SET value = '$ft_moov' WHERE setting = 'ft_moov'";
			$HWDGS['updates'][44] = "UPDATE #__hwdvidsgs SET value = '$ft_asf' WHERE setting = 'ft_asf'";
			$HWDGS['updates'][45] = "UPDATE #__hwdvidsgs SET value = '$ft_swf' WHERE setting = 'ft_swf'";
			$HWDGS['updates'][46] = "UPDATE #__hwdvidsgs SET value = '$ft_vob' WHERE setting = 'ft_vob'";
		}
		if (isset($maxupld) && $maxupld !== '') { $HWDGS['updates'][47] = "UPDATE #__hwdvidsgs SET value = '$maxupld' WHERE setting = 'maxupld'"; }
		if (isset($flvplayer) && $flvplayer !== '') { $HWDGS['updates'][48] = "UPDATE #__hwdvidsgs SET value = '$flvplayer' WHERE setting = 'flvplayer'"; }
		if (isset($flvalign) && $flvalign !== '') { $HWDGS['updates'][49] = "UPDATE #__hwdvidsgs SET value = '$flvalign' WHERE setting = 'flvalign'"; }
		if (isset($infoalign) && $infoalign !== '') { $HWDGS['updates'][50] = "UPDATE #__hwdvidsgs SET value = '$infoalign' WHERE setting = 'infoalign'"; }
		if (isset($usershare1) && $usershare1 !== '') { $HWDGS['updates'][51] = "UPDATE #__hwdvidsgs SET value = '$usershare1' WHERE setting = 'usershare1'"; }
		if (isset($shareoption1) && $shareoption1 !== '') { $HWDGS['updates'][52] = "UPDATE #__hwdvidsgs SET value = '$shareoption1' WHERE setting = 'shareoption1'"; }
		if (isset($usershare2) && $usershare2 !== '') { $HWDGS['updates'][53] = "UPDATE #__hwdvidsgs SET value = '$usershare2' WHERE setting = 'usershare2'"; }
		if (isset($shareoption2) && $shareoption2 !== '') { $HWDGS['updates'][54] = "UPDATE #__hwdvidsgs SET value = '$shareoption2' WHERE setting = 'shareoption2'"; }
		if (isset($usershare3) && $usershare3 !== '') { $HWDGS['updates'][55] = "UPDATE #__hwdvidsgs SET value = '$usershare3' WHERE setting = 'usershare3'"; }
		if (isset($shareoption3) && $shareoption3 !== '') { $HWDGS['updates'][56] = "UPDATE #__hwdvidsgs SET value = '$shareoption3' WHERE setting = 'shareoption3'"; }
		if (isset($usershare4) && $usershare4 !== '') { $HWDGS['updates'][57] = "UPDATE #__hwdvidsgs SET value = '$usershare4' WHERE setting = 'usershare4'"; }
		if (isset($shareoption4) && $shareoption4 !== '') { $HWDGS['updates'][58] = "UPDATE #__hwdvidsgs SET value = '$shareoption4' WHERE setting = 'shareoption4'"; }
		if (isset($cbavatar) && $cbavatar !== '') { $HWDGS['updates'][59] = "UPDATE #__hwdvidsgs SET value = '$cbavatar' WHERE setting = 'cbavatar'"; }
		if (isset($avatarwidth) && $avatarwidth !== '') { $HWDGS['updates'][60] = "UPDATE #__hwdvidsgs SET value = '$avatarwidth' WHERE setting = 'avatarwidth'"; }
		if (isset($gtree_core) && $gtree_core !== '') { $HWDGS['updates'][61] = "UPDATE #__hwdvidsgs SET value = '$gtree_core' WHERE setting = 'gtree_core'"; }
		if (isset($gtree_core_child) && $gtree_core_child !== '') { $HWDGS['updates'][62] = "UPDATE #__hwdvidsgs SET value = '$gtree_core_child' WHERE setting = 'gtree_core_child'"; }
		if (isset($gtree_upld) && $gtree_upld !== '') { $HWDGS['updates'][63] = "UPDATE #__hwdvidsgs SET value = '$gtree_upld' WHERE setting = 'gtree_upld'"; }
		if (isset($gtree_upld_child) && $gtree_upld_child !== '') { $HWDGS['updates'][64] = "UPDATE #__hwdvidsgs SET value = '$gtree_upld_child' WHERE setting = 'gtree_upld_child'"; }
		if (isset($gtree_grup) && $gtree_grup !== '') { $HWDGS['updates'][65] = "UPDATE #__hwdvidsgs SET value = '$gtree_grup' WHERE setting = 'gtree_grup'"; }
		if (isset($gtree_grup_child) && $gtree_grup_child !== '') { $HWDGS['updates'][66] = "UPDATE #__hwdvidsgs SET value = '$gtree_grup_child' WHERE setting = 'gtree_grup_child'"; }
		if (isset($thumbwidth) && $thumbwidth !== '') { $HWDGS['updates'][67] = "UPDATE #__hwdvidsgs SET value = '$thumbwidth' WHERE setting = 'thumbwidth'"; }
		if (isset($reconvertflv) && $reconvertflv !== '') { $HWDGS['updates'][68] = "UPDATE #__hwdvidsgs SET value = '$reconvertflv' WHERE setting = 'reconvertflv'"; }
		if (isset($abortthumbfail) && $abortthumbfail !== '') { $HWDGS['updates'][69] = "UPDATE #__hwdvidsgs SET value = '$abortthumbfail' WHERE setting = 'abortthumbfail'"; }
		if (isset($diable_nav_search) && $diable_nav_search !== '') { $HWDGS['updates'][70] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_search' WHERE setting = 'diable_nav_search'"; }
		if (isset($diable_nav_user) && $diable_nav_user !== '') { $HWDGS['updates'][71] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_user' WHERE setting = 'diable_nav_user'"; }
		if (isset($trunvdesc) && $trunvdesc !== '') { $HWDGS['updates'][72] = "UPDATE #__hwdvidsgs SET value = '$trunvdesc' WHERE setting = 'trunvdesc'"; }
		if (isset($truncdesc) && $truncdesc !== '') { $HWDGS['updates'][73] = "UPDATE #__hwdvidsgs SET value = '$truncdesc' WHERE setting = 'truncdesc'"; }
		if (isset($trungdesc) && $trungdesc !== '') { $HWDGS['updates'][74] = "UPDATE #__hwdvidsgs SET value = '$trungdesc' WHERE setting = 'trungdesc'"; }
		if (isset($truntitle) && $truntitle !== '') { $HWDGS['updates'][75] = "UPDATE #__hwdvidsgs SET value = '$truntitle' WHERE setting = 'truntitle'"; }
		if ($task == "savegeneral") {
			$HWDGS['updates'][76] = "UPDATE #__hwdvidsgs SET value = '$sb_digg' WHERE setting = 'sb_digg'";
			$HWDGS['updates'][77] = "UPDATE #__hwdvidsgs SET value = '$sb_reddit' WHERE setting = 'sb_reddit'";
			$HWDGS['updates'][78] = "UPDATE #__hwdvidsgs SET value = '$sb_delicious' WHERE setting = 'sb_delicious'";
			$HWDGS['updates'][79] = "UPDATE #__hwdvidsgs SET value = '$sb_google' WHERE setting = 'sb_google'";
			$HWDGS['updates'][80] = "UPDATE #__hwdvidsgs SET value = '$sb_live' WHERE setting = 'sb_live'";
			$HWDGS['updates'][81] = "UPDATE #__hwdvidsgs SET value = '$sb_facebook' WHERE setting = 'sb_facebook'";
			$HWDGS['updates'][82] = "UPDATE #__hwdvidsgs SET value = '$sb_slashdot' WHERE setting = 'sb_slashdot'";
			$HWDGS['updates'][83] = "UPDATE #__hwdvidsgs SET value = '$sb_netscape' WHERE setting = 'sb_netscape'";
			$HWDGS['updates'][84] = "UPDATE #__hwdvidsgs SET value = '$sb_technorati' WHERE setting = 'sb_technorati'";
			$HWDGS['updates'][85] = "UPDATE #__hwdvidsgs SET value = '$sb_stumbleupon' WHERE setting = 'sb_stumbleupon'";
			$HWDGS['updates'][86] = "UPDATE #__hwdvidsgs SET value = '$sb_spurl' WHERE setting = 'sb_spurl'";
			$HWDGS['updates'][87] = "UPDATE #__hwdvidsgs SET value = '$sb_wists' WHERE setting = 'sb_wists'";
			$HWDGS['updates'][88] = "UPDATE #__hwdvidsgs SET value = '$sb_simpy' WHERE setting = 'sb_simpy'";
			$HWDGS['updates'][89] = "UPDATE #__hwdvidsgs SET value = '$sb_newsvine' WHERE setting = 'sb_newsvine'";
			$HWDGS['updates'][90] = "UPDATE #__hwdvidsgs SET value = '$sb_blinklist' WHERE setting = 'sb_blinklist'";
			$HWDGS['updates'][91] = "UPDATE #__hwdvidsgs SET value = '$sb_furl' WHERE setting = 'sb_furl'";
			$HWDGS['updates'][92] = "UPDATE #__hwdvidsgs SET value = '$sb_fark' WHERE setting = 'sb_fark'";
			$HWDGS['updates'][93] = "UPDATE #__hwdvidsgs SET value = '$sb_blogmarks' WHERE setting = 'sb_blogmarks'";
			$HWDGS['updates'][94] = "UPDATE #__hwdvidsgs SET value = '$sb_yahoo' WHERE setting = 'sb_yahoo'";
			$HWDGS['updates'][95] = "UPDATE #__hwdvidsgs SET value = '$sb_smarking' WHERE setting = 'sb_smarking'";
			$HWDGS['updates'][96] = "UPDATE #__hwdvidsgs SET value = '$sb_netvouz' WHERE setting = 'sb_netvouz'";
			$HWDGS['updates'][97] = "UPDATE #__hwdvidsgs SET value = '$sb_shadows' WHERE setting = 'sb_shadows'";
			$HWDGS['updates'][98] = "UPDATE #__hwdvidsgs SET value = '$sb_rawsugar' WHERE setting = 'sb_rawsugar'";
			$HWDGS['updates'][99] = "UPDATE #__hwdvidsgs SET value = '$sb_magnolia' WHERE setting = 'sb_magnolia'";
			$HWDGS['updates'][100] = "UPDATE #__hwdvidsgs SET value = '$sb_plugim' WHERE setting = 'sb_plugim'";
			$HWDGS['updates'][101] = "UPDATE #__hwdvidsgs SET value = '$sb_squidoo' WHERE setting = 'sb_squidoo'";
			$HWDGS['updates'][102] = "UPDATE #__hwdvidsgs SET value = '$sb_blogmemes' WHERE setting = 'sb_blogmemes'";
			$HWDGS['updates'][103] = "UPDATE #__hwdvidsgs SET value = '$sb_feedmelinks' WHERE setting = 'sb_feedmelinks'";
			$HWDGS['updates'][104] = "UPDATE #__hwdvidsgs SET value = '$sb_blinkbits' WHERE setting = 'sb_blinkbits'";
			$HWDGS['updates'][105] = "UPDATE #__hwdvidsgs SET value = '$sb_tailrank' WHERE setting = 'sb_tailrank'";
			$HWDGS['updates'][106] = "UPDATE #__hwdvidsgs SET value = '$sb_linkagogo' WHERE setting = 'sb_linkagogo'";
		}
		if (isset($showrating) && $showrating !== '') { $HWDGS['updates'][107] = "UPDATE #__hwdvidsgs SET value = '$showrating' WHERE setting = 'showrating'"; }
		if (isset($showviews) && $showviews !== '') { $HWDGS['updates'][108] = "UPDATE #__hwdvidsgs SET value = '$showviews' WHERE setting = 'showviews'"; }
		if (isset($showduration) && $showduration !== '') { $HWDGS['updates'][109] = "UPDATE #__hwdvidsgs SET value = '$showduration' WHERE setting = 'showduration'"; }
		if (isset($showuplder) && $showuplder !== '') { $HWDGS['updates'][110] = "UPDATE #__hwdvidsgs SET value = '$showuplder' WHERE setting = 'showuplder'"; }
		if (isset($autoconvert) && $autoconvert !== '') { $HWDGS['updates'][111] = "UPDATE #__hwdvidsgs SET value = '$autoconvert' WHERE setting = 'autoconvert'"; }
		if (isset($commssys) && $commssys !== '') { $HWDGS['updates'][112] = "UPDATE #__hwdvidsgs SET value = '$commssys' WHERE setting = 'commssys'"; }
		if (isset($gjint) && $gjint !== '') { $HWDGS['updates'][113] = "UPDATE #__hwdvidsgs SET value = '$gjint' WHERE setting = 'gjint'"; }
		if (isset($uploadcriteria) && $uploadcriteria !== '') { $HWDGS['updates'][114] = "UPDATE #__hwdvidsgs SET value = '$uploadcriteria' WHERE setting = 'uploadcriteria'"; }
		if (isset($ad1show) && $ad1show !== '') { $HWDGS['updates'][115] = "UPDATE #__hwdvidsgs SET value = '$ad1show' WHERE setting = 'ad1show'"; }
		if (isset($ad1_ad_client) && $ad1_ad_client !== '') { $HWDGS['updates'][116] = "UPDATE #__hwdvidsgs SET value = '$ad1_ad_client' WHERE setting = 'ad1_ad_client'"; }
		if (isset($ad1_ad_channel) && $ad1_ad_channel !== '') { $HWDGS['updates'][117] = "UPDATE #__hwdvidsgs SET value = '$ad1_ad_channel' WHERE setting = 'ad1_ad_channel'"; }
		if (isset($ad1_ad_type) && $ad1_ad_type !== '') { $HWDGS['updates'][118] = "UPDATE #__hwdvidsgs SET value = '$ad1_ad_type' WHERE setting = 'ad1_ad_type'"; }
		if (isset($ad1_ad_uifeatures) && $ad1_ad_uifeatures !== '') { $HWDGS['updates'][119] = "UPDATE #__hwdvidsgs SET value = '$ad1_ad_uifeatures' WHERE setting = 'ad1_ad_uifeatures'"; }
		if (isset($ad1_ad_format) && $ad1_ad_format !== '') { $HWDGS['updates'][120] = "UPDATE #__hwdvidsgs SET value = '$ad1_ad_format' WHERE setting = 'ad1_ad_format'"; }
		if (isset($ad1_color_border1) && $ad1_color_border1 !== '') { $HWDGS['updates'][121] = "UPDATE #__hwdvidsgs SET value = '$ad1_color_border1' WHERE setting = 'ad1_color_border1'"; }
		if (isset($ad1_color_bg1) && $ad1_color_bg1 !== '') { $HWDGS['updates'][122] = "UPDATE #__hwdvidsgs SET value = '$ad1_color_bg1' WHERE setting = 'ad1_color_bg1'"; }
		if (isset($ad1_color_link1) && $ad1_color_link1 !== '') { $HWDGS['updates'][123] = "UPDATE #__hwdvidsgs SET value = '$ad1_color_link1' WHERE setting = 'ad1_color_link1'"; }
		if (isset($ad1_color_text1) && $ad1_color_text1 !== '') { $HWDGS['updates'][124] = "UPDATE #__hwdvidsgs SET value = '$ad1_color_text1' WHERE setting = 'ad1_color_text1'"; }
		if (isset($ad1_color_url1) && $ad1_color_url1 !== '') { $HWDGS['updates'][125] = "UPDATE #__hwdvidsgs SET value = '$ad1_color_url1' WHERE setting = 'ad1_color_url1'"; }
		if (isset($ad1custom) && $ad1custom !== '') { $HWDGS['updates'][126] = "UPDATE #__hwdvidsgs SET value = '$ad1custom' WHERE setting = 'ad1custom'"; }
		if ($task == "savegeneral") {
			$HWDGS['updates'][127] = "UPDATE #__hwdvidsgs SET value = '$customencode' WHERE setting = 'customencode'";
		}
		if (isset($customencode) && $customencode !== '') {  }
		if (isset($encoder) && $encoder !== '') { $HWDGS['updates'][128] = "UPDATE #__hwdvidsgs SET value = '$encoder' WHERE setting = 'encoder'"; }
		if (isset($flvplay_autostart) && $flvplay_autostart !== '') { $HWDGS['updates'][129] = "UPDATE #__hwdvidsgs SET value = '$flvplay_autostart' WHERE setting = 'flvplay_autostart'"; }
		if (isset($flvplay_overstretch) && $flvplay_overstretch !== '') { $HWDGS['updates'][130] = "UPDATE #__hwdvidsgs SET value = '$flvplay_overstretch' WHERE setting = 'flvplay_overstretch'"; }
		if (isset($flvplay_logo) && $flvplay_logo !== '') { $HWDGS['updates'][131] = "UPDATE #__hwdvidsgs SET value = '$flvplay_logo' WHERE setting = 'flvplay_logo'"; }
		if (isset($flvplay_volume) && $flvplay_volume !== '') { $HWDGS['updates'][132] = "UPDATE #__hwdvidsgs SET value = '$flvplay_volume' WHERE setting = 'flvplay_volume'"; }
		if (isset($flvplay_fg) && $flvplay_fg !== '') { $HWDGS['updates'][133] = "UPDATE #__hwdvidsgs SET value = '$flvplay_fg' WHERE setting = 'flvplay_fg'"; }
		if (isset($flvplay_bg) && $flvplay_bg !== '') { $HWDGS['updates'][134] = "UPDATE #__hwdvidsgs SET value = '$flvplay_bg' WHERE setting = 'flvplay_bg'"; }
		if (isset($fporder) && $fporder !== '') { $HWDGS['updates'][135] = "UPDATE #__hwdvidsgs SET value = '$fporder' WHERE setting = 'fporder'"; }
		if (isset($pathubr_upload) && $pathubr_upload !== '') { $HWDGS['updates'][136] = "UPDATE #__hwdvidsgs SET value = '$pathubr_upload' WHERE setting = 'pathubr_upload'"; }
		if (isset($cnvt_vbitrate) && $cnvt_vbitrate !== '') { $HWDGS['updates'][137] = "UPDATE #__hwdvidsgs SET value = '$cnvt_vbitrate' WHERE setting = 'cnvt_vbitrate'"; }
		if (isset($cnvt_abitrate) && $cnvt_abitrate !== '') { $HWDGS['updates'][138] = "UPDATE #__hwdvidsgs SET value = '$cnvt_abitrate' WHERE setting = 'cnvt_abitrate'"; }
		if (isset($cnvt_asr) && $cnvt_asr !== '') { $HWDGS['updates'][139] = "UPDATE #__hwdvidsgs SET value = '$cnvt_asr' WHERE setting = 'cnvt_asr'"; }
		if (isset($cnvt_fsize) && $cnvt_fsize !== '') { $HWDGS['updates'][140] = "UPDATE #__hwdvidsgs SET value = '$cnvt_fsize' WHERE setting = 'cnvt_fsize'"; }
		if (isset($usegetheaders) && $usegetheaders !== '') { $HWDGS['updates'][141] = "UPDATE #__hwdvidsgs SET value = '$usegetheaders' WHERE setting = 'usegetheaders'"; }
		if (isset($ajaxratemeth) && $ajaxratemeth !== '') { $HWDGS['updates'][142] = "UPDATE #__hwdvidsgs SET value = '$ajaxratemeth' WHERE setting = 'ajaxratemeth'"; }
		if (isset($ajaxfavmeth) && $ajaxfavmeth !== '') { $HWDGS['updates'][143] = "UPDATE #__hwdvidsgs SET value = '$ajaxfavmeth' WHERE setting = 'ajaxfavmeth'"; }
		if (isset($ajaxrepmeth) && $ajaxrepmeth !== '') { $HWDGS['updates'][144] = "UPDATE #__hwdvidsgs SET value = '$ajaxrepmeth' WHERE setting = 'ajaxrepmeth'"; }
		if (isset($ajaxa2gmeth) && $ajaxa2gmeth !== '') { $HWDGS['updates'][145] = "UPDATE #__hwdvidsgs SET value = '$ajaxa2gmeth' WHERE setting = 'ajaxa2gmeth'"; }
		if (isset($cbitemid) && $cbitemid !== '') { $HWDGS['updates'][146] = "UPDATE #__hwdvidsgs SET value = '$cbitemid' WHERE setting = 'cbitemid'"; }
		if (isset($applywmvfix) && $applywmvfix !== '') { $HWDGS['updates'][147] = "UPDATE #__hwdvidsgs SET value = '$applywmvfix' WHERE setting = 'applywmvfix'"; }
		if (isset($tpfunc) && $tpfunc !== '') { $HWDGS['updates'][148] = "UPDATE #__hwdvidsgs SET value = '$tpfunc' WHERE setting = 'tpfunc'"; }
		if (isset($diable_nav_user1) && $diable_nav_user1 !== '') { $HWDGS['updates'][149] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_user1' WHERE setting = 'diable_nav_user1'"; }
		if (isset($diable_nav_user2) && $diable_nav_user2 !== '') { $HWDGS['updates'][150] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_user2' WHERE setting = 'diable_nav_user2'"; }
		if (isset($diable_nav_user3) && $diable_nav_user3 !== '') { $HWDGS['updates'][151] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_user3' WHERE setting = 'diable_nav_user3'"; }
		if (isset($diable_nav_user4) && $diable_nav_user4 !== '') { $HWDGS['updates'][152] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_user4' WHERE setting = 'diable_nav_user4'"; }
		if (isset($diable_nav_user5) && $diable_nav_user5 !== '') { $HWDGS['updates'][153] = "UPDATE #__hwdvidsgs SET value = '$diable_nav_user5' WHERE setting = 'diable_nav_user5'"; }
		if (isset($showrate) && $showrate !== '') { $HWDGS['updates'][154] = "UPDATE #__hwdvidsgs SET value = '$showrate' WHERE setting = 'showrate'"; }
		if (isset($showatfb) && $showatfb !== '') { $HWDGS['updates'][155] = "UPDATE #__hwdvidsgs SET value = '$showatfb' WHERE setting = 'showatfb'"; }
		if (isset($showrpmb) && $showrpmb !== '') { $HWDGS['updates'][156] = "UPDATE #__hwdvidsgs SET value = '$showrpmb' WHERE setting = 'showrpmb'"; }
		if (isset($showcoms) && $showcoms !== '') { $HWDGS['updates'][157] = "UPDATE #__hwdvidsgs SET value = '$showcoms' WHERE setting = 'showcoms'"; }
		if (isset($showvurl) && $showvurl !== '') { $HWDGS['updates'][158] = "UPDATE #__hwdvidsgs SET value = '$showvurl' WHERE setting = 'showvurl'"; }
		if (isset($showvebc) && $showvebc !== '') { $HWDGS['updates'][159] = "UPDATE #__hwdvidsgs SET value = '$showvebc' WHERE setting = 'showvebc'"; }
		if (isset($showdesc) && $showdesc !== '') { $HWDGS['updates'][160] = "UPDATE #__hwdvidsgs SET value = '$showdesc' WHERE setting = 'showdesc'"; }
		if (isset($showtags) && $showtags !== '') { $HWDGS['updates'][161] = "UPDATE #__hwdvidsgs SET value = '$showtags' WHERE setting = 'showtags'"; }
		if (isset($showscbm) && $showscbm !== '') { $HWDGS['updates'][162] = "UPDATE #__hwdvidsgs SET value = '$showscbm' WHERE setting = 'showscbm'"; }
		if (isset($showuldr) && $showuldr !== '') { $HWDGS['updates'][163] = "UPDATE #__hwdvidsgs SET value = '$showuldr' WHERE setting = 'showuldr'"; }
		if (isset($showa2gb) && $showa2gb !== '') { $HWDGS['updates'][164] = "UPDATE #__hwdvidsgs SET value = '$showa2gb' WHERE setting = 'showa2gb'"; }
		if (isset($gtree_plyr_child) && $gtree_plyr_child !== '') { $HWDGS['updates'][165] = "UPDATE #__hwdvidsgs SET value = '$gtree_plyr_child' WHERE setting = 'gtree_plyr_child'"; }
		if (isset($gtree_plyr) && $gtree_plyr !== '') { $HWDGS['updates'][166] = "UPDATE #__hwdvidsgs SET value = '$gtree_plyr' WHERE setting = 'gtree_plyr'"; }
		if (isset($hwdvids_videoplayer_file) && $hwdvids_videoplayer_file !== '') { $HWDGS['updates'][167] = "UPDATE #__hwdvidsgs SET value = '$hwdvids_videoplayer_file' WHERE setting = 'hwdvids_videoplayer_file'"; }
		if (isset($hwdvids_videoplayer_path) && $hwdvids_videoplayer_path !== '') { $HWDGS['updates'][168] = "UPDATE #__hwdvidsgs SET value = '$hwdvids_videoplayer_path' WHERE setting = 'hwdvids_videoplayer_path'"; }
		if (isset($accesslevel_main) && $accesslevel_main !== '') { $HWDGS['updates'][169] = "UPDATE #__hwdvidsgs SET value = '$accesslevel_main' WHERE setting = 'accesslevel_main'"; }
		if (isset($accesslevel_upld) && $accesslevel_upld !== '') { $HWDGS['updates'][170] = "UPDATE #__hwdvidsgs SET value = '$accesslevel_upld' WHERE setting = 'accesslevel_upld'"; }
		if (isset($accesslevel_plyr) && $accesslevel_plyr !== '') { $HWDGS['updates'][171] = "UPDATE #__hwdvidsgs SET value = '$accesslevel_plyr' WHERE setting = 'accesslevel_plyr'"; }
		if (isset($accesslevel_grps) && $accesslevel_grps !== '') { $HWDGS['updates'][172] = "UPDATE #__hwdvidsgs SET value = '$accesslevel_grps' WHERE setting = 'accesslevel_grps'"; }
		if (isset($access_method) && $access_method !== '') { $HWDGS['updates'][173] = "UPDATE #__hwdvidsgs SET value = '$access_method' WHERE setting = 'access_method'"; }
		if (isset($xmlcache_today) && $xmlcache_today !== '') { $HWDGS['updates'][174] = "UPDATE #__hwdvidsgs SET value = '$xmlcache_today' WHERE setting = 'xmlcache_today'"; }
		if (isset($xmlcache_thisweek) && $xmlcache_thisweek !== '') { $HWDGS['updates'][175] = "UPDATE #__hwdvidsgs SET value = '$xmlcache_thisweek' WHERE setting = 'xmlcache_thisweek'"; }
		if (isset($xmlcache_thismonth) && $xmlcache_thismonth !== '') { $HWDGS['updates'][176] = "UPDATE #__hwdvidsgs SET value = '$xmlcache_thismonth' WHERE setting = 'xmlcache_thismonth'"; }
		if (isset($xmlcache_alltime) && $xmlcache_alltime !== '') { $HWDGS['updates'][177] = "UPDATE #__hwdvidsgs SET value = '$xmlcache_alltime' WHERE setting = 'xmlcache_alltime'"; }
		if (isset($xmlcustom01) && $xmlcustom01 !== '') { $HWDGS['updates'][178] = "UPDATE #__hwdvidsgs SET value = '$xmlcustom01' WHERE setting = 'xmlcustom01'"; }
		if (isset($xmlcustom02) && $xmlcustom02 !== '') { $HWDGS['updates'][179] = "UPDATE #__hwdvidsgs SET value = '$xmlcustom02' WHERE setting = 'xmlcustom02'"; }
		if (isset($xmlcustom03) && $xmlcustom03 !== '') { $HWDGS['updates'][180] = "UPDATE #__hwdvidsgs SET value = '$xmlcustom03' WHERE setting = 'xmlcustom03'"; }
		if (isset($xmlcustom04) && $xmlcustom04 !== '') { $HWDGS['updates'][181] = "UPDATE #__hwdvidsgs SET value = '$xmlcustom04' WHERE setting = 'xmlcustom04'"; }
		if (isset($xmlcustom05) && $xmlcustom05 !== '') { $HWDGS['updates'][182] = "UPDATE #__hwdvidsgs SET value = '$xmlcustom05' WHERE setting = 'xmlcustom05'"; }
		if (isset($mailreportnotification) && $mailreportnotification !== '') { $HWDGS['updates'][183] = "UPDATE #__hwdvidsgs SET value = '$mailreportnotification' WHERE setting = 'mailreportnotification'"; }
		if (isset($sharedlibrarypath) && $sharedlibrarypath !== '') { $HWDGS['updates'][184] = "UPDATE #__hwdvidsgs SET value = '$sharedlibrarypath' WHERE setting = 'sharedlibrarypath'"; }
		if (isset($standaloneswf) && $standaloneswf !== '') { $HWDGS['updates'][185] = "UPDATE #__hwdvidsgs SET value = '$standaloneswf' WHERE setting = 'standaloneswf'"; }
		if (isset($playlocal) && $playlocal !== '') { $HWDGS['updates'][186] = "UPDATE #__hwdvidsgs SET value = '$playlocal' WHERE setting = 'playlocal'"; }
		if (isset($frontpage_watched) && $frontpage_watched !== '') { $HWDGS['updates'][187] = "UPDATE #__hwdvidsgs SET value = '$frontpage_watched' WHERE setting = 'frontpage_watched'"; }
		if (isset($frontpage_viewed) && $frontpage_viewed !== '') { $HWDGS['updates'][188] = "UPDATE #__hwdvidsgs SET value = '$frontpage_viewed' WHERE setting = 'frontpage_viewed'"; }
		if (isset($frontpage_favoured) && $frontpage_favoured !== '') { $HWDGS['updates'][189] = "UPDATE #__hwdvidsgs SET value = '$frontpage_favoured' WHERE setting = 'frontpage_favoured'"; }
		if (isset($frontpage_popular) && $frontpage_popular !== '') { $HWDGS['updates'][190] = "UPDATE #__hwdvidsgs SET value = '$frontpage_popular' WHERE setting = 'frontpage_popular'"; }
		if (isset($jaclint) && $jaclint !== '') { $HWDGS['updates'][191] = "UPDATE #__hwdvidsgs SET value = '$jaclint' WHERE setting = 'jaclint'"; }
		if ($task == "savegeneral") {
			$HWDGS['updates'][192] = "UPDATE #__hwdvidsgs SET value = '$loadmootools' WHERE setting = 'loadmootools'";
			$HWDGS['updates'][193] = "UPDATE #__hwdvidsgs SET value = '$loadprototype' WHERE setting = 'loadprototype'";
			$HWDGS['updates'][194] = "UPDATE #__hwdvidsgs SET value = '$loadscriptaculous' WHERE setting = 'loadscriptaculous'";
			$HWDGS['updates'][195] = "UPDATE #__hwdvidsgs SET value = '$loadswfobject' WHERE setting = 'loadswfobject'";
		}
		if (isset($embedreturnlink) && $embedreturnlink !== '') { $HWDGS['updates'][196] = "UPDATE #__hwdvidsgs SET value = '$embedreturnlink' WHERE setting = 'embedreturnlink'"; }
		if (isset($tpwidth) && $tpwidth !== '') { $HWDGS['updates'][197] = "UPDATE #__hwdvidsgs SET value = '$tpwidth' WHERE setting = 'tpwidth'"; }
		if (isset($nicepriority) && $nicepriority !== '') { $HWDGS['updates'][198] = "UPDATE #__hwdvidsgs SET value = '$nicepriority' WHERE setting = 'nicepriority'"; }
		if (isset($showdlor) && $showdlor !== '') { $HWDGS['updates'][199] = "UPDATE #__hwdvidsgs SET value = '$showdlor' WHERE setting = 'showdlor'"; }
		if (isset($showvuor) && $showvuor !== '') { $HWDGS['updates'][200] = "UPDATE #__hwdvidsgs SET value = '$showvuor' WHERE setting = 'showvuor'"; }
		if (isset($mbtu_no) && $mbtu_no !== '') { $HWDGS['updates'][201] = "UPDATE #__hwdvidsgs SET value = '$mbtu_no' WHERE setting = 'mbtu_no'"; }
		if (isset($showprnx) && $showprnx !== '') { $HWDGS['updates'][202] = "UPDATE #__hwdvidsgs SET value = '$showprnx' WHERE setting = 'showprnx'"; }
		if (isset($showdlfl) && $showdlfl !== '') { $HWDGS['updates'][203] = "UPDATE #__hwdvidsgs SET value = '$showdlfl' WHERE setting = 'showdlfl'"; }
		if (isset($maintenance_bkgd) && $maintenance_bkgd !== '') { $HWDGS['updates'][204] = "UPDATE #__hwdvidsgs SET value = '$maintenance_bkgd' WHERE setting = 'maintenance_bkgd'"; }
		if (isset($playlist_bkgd) && $playlist_bkgd !== '') { $HWDGS['updates'][205] = "UPDATE #__hwdvidsgs SET value = '$playlist_bkgd' WHERE setting = 'playlist_bkgd'"; }
		if (isset($showrevi) && $showrevi !== '') { $HWDGS['updates'][206] = "UPDATE #__hwdvidsgs SET value = '$showrevi' WHERE setting = 'showrevi'"; }
		if (isset($revi_no) && $revi_no !== '') { $HWDGS['updates'][207] = "UPDATE #__hwdvidsgs SET value = '$revi_no' WHERE setting = 'revi_no'"; }
		if (isset($fvid_w) && $fvid_w !== '') { $HWDGS['updates'][208] = "UPDATE #__hwdvidsgs SET value = '$fvid_w' WHERE setting = 'fvid_w'"; }
		if (isset($fvid_h) && $fvid_h !== '') { $HWDGS['updates'][209] = "UPDATE #__hwdvidsgs SET value = '$fvid_h' WHERE setting = 'fvid_h'"; }
		if (isset($var_c) && $var_c !== '') { $HWDGS['updates'][210] = "UPDATE #__hwdvidsgs SET value = '$var_c' WHERE setting = 'var_c'"; }
		if (isset($var_fb) && $var_fb !== '') { $HWDGS['updates'][211] = "UPDATE #__hwdvidsgs SET value = '$var_fb' WHERE setting = 'var_fb'"; }
		if (isset($tar_fb) && $tar_fb !== '') { $HWDGS['updates'][212] = "UPDATE #__hwdvidsgs SET value = '$tar_fb' WHERE setting = 'tar_fb'"; }
		if (isset($udt) && $udt !== '') { $HWDGS['updates'][213] = "UPDATE #__hwdvidsgs SET value = '$udt' WHERE setting = 'udt'"; }
		if (isset($oformats) && $oformats !== '') { $HWDGS['updates'][214] = "UPDATE #__hwdvidsgs SET value = '$oformats' WHERE setting = 'oformats'"; }
		if (isset($bwn_no) && $bwn_no !== '') { $HWDGS['updates'][215] = "UPDATE #__hwdvidsgs SET value = '$bwn_no' WHERE setting = 'bwn_no'"; }
		if (isset($cordering) && $cordering !== '') { $HWDGS['updates'][216] = "UPDATE #__hwdvidsgs SET value = '$cordering' WHERE setting = 'cordering'"; }
		if (isset($cvordering) && $cvordering !== '') { $HWDGS['updates'][217] = "UPDATE #__hwdvidsgs SET value = '$cvordering' WHERE setting = 'cvordering'"; }
		if (isset($custordering) && $custordering !== '') { $HWDGS['updates'][218] = "UPDATE #__hwdvidsgs SET value = '$custordering' WHERE setting = 'custordering'"; }
		if (isset($userdisplay) && $userdisplay !== '') { $HWDGS['updates'][219] = "UPDATE #__hwdvidsgs SET value = '$userdisplay' WHERE setting = 'userdisplay'"; }
		if (isset($gtree_dnld) && $gtree_dnld !== '') { $HWDGS['updates'][220] = "UPDATE #__hwdvidsgs SET value = '$gtree_dnld' WHERE setting = 'gtree_dnld'"; }
		if (isset($gtree_dnld_child) && $gtree_dnld_child !== '') { $HWDGS['updates'][221] = "UPDATE #__hwdvidsgs SET value = '$gtree_dnld_child' WHERE setting = 'gtree_dnld_child'"; }
		if (isset($gtree_ultp) && $gtree_ultp !== '') { $HWDGS['updates'][222] = "UPDATE #__hwdvidsgs SET value = '$gtree_ultp' WHERE setting = 'gtree_ultp'"; }
		if (isset($gtree_ultp_child) && $gtree_ultp_child !== '') { $HWDGS['updates'][223] = "UPDATE #__hwdvidsgs SET value = '$gtree_ultp_child' WHERE setting = 'gtree_ultp_child'"; }
		if (isset($bviic) && $bviic !== '') { $HWDGS['updates'][224] = "UPDATE #__hwdvidsgs SET value = '$bviic' WHERE setting = 'bviic'"; }
		if (isset($accesslevel_dnld) && $accesslevel_dnld !== '') { $HWDGS['updates'][225] = "UPDATE #__hwdvidsgs SET value = '$accesslevel_dnld' WHERE setting = 'accesslevel_dnld'"; }
		if (isset($accesslevel_ultp) && $accesslevel_ultp !== '') { $HWDGS['updates'][226] = "UPDATE #__hwdvidsgs SET value = '$accesslevel_ultp' WHERE setting = 'accesslevel_ultp'"; }
		if (isset($ieoa_fix) && $ieoa_fix !== '') { $HWDGS['updates'][227] = "UPDATE #__hwdvidsgs SET value = '$ieoa_fix' WHERE setting = 'ieoa_fix'"; }
		if (isset($swfobject) && $swfobject !== '') { $HWDGS['updates'][228] = "UPDATE #__hwdvidsgs SET value = '$swfobject' WHERE setting = 'swfobject'"; }
		if (isset($allowgr) && $allowgr !== '') { $HWDGS['updates'][229] = "UPDATE #__hwdvidsgs SET value = '$allowgr' WHERE setting = 'allowgr'"; }
		if (isset($con_thumb_n) && $con_thumb_n !== '') { $HWDGS['updates'][230] = "UPDATE #__hwdvidsgs SET value = '$con_thumb_n' WHERE setting = 'con_thumb_n'"; }
		if (isset($con_thumb_l) && $con_thumb_l !== '') { $HWDGS['updates'][231] = "UPDATE #__hwdvidsgs SET value = '$con_thumb_l' WHERE setting = 'con_thumb_l'"; }
		if (isset($con_gen_hd) && $con_gen_hd !== '') { $HWDGS['updates'][232] = "UPDATE #__hwdvidsgs SET value = '$con_gen_hd' WHERE setting = 'con_gen_hd'"; }
		if (isset($showmftc) && $showmftc !== '') { $HWDGS['updates'][233] = "UPDATE #__hwdvidsgs SET value = '$showmftc' WHERE setting = 'showmftc'"; }
		if (isset($mftc_no) && $mftc_no !== '') { $HWDGS['updates'][234] = "UPDATE #__hwdvidsgs SET value = '$mftc_no' WHERE setting = 'mftc_no'"; }
		if (isset($feat_show) && $feat_show !== '') { $HWDGS['updates'][235] = "UPDATE #__hwdvidsgs SET value = '$feat_show' WHERE setting = 'feat_show'"; }
		if (isset($feat_as) && $feat_as !== '') { $HWDGS['updates'][236] = "UPDATE #__hwdvidsgs SET value = '$feat_as' WHERE setting = 'feat_as'"; }
		if (isset($feat_rand) && $feat_rand !== '') { $HWDGS['updates'][237] = "UPDATE #__hwdvidsgs SET value = '$feat_rand' WHERE setting = 'feat_rand'"; }
		if (isset($scroll_no) && $scroll_no !== '') { $HWDGS['updates'][238] = "UPDATE #__hwdvidsgs SET value = '$scroll_no' WHERE setting = 'scroll_no'"; }
		if (isset($scroll_as) && $scroll_as !== '') { $HWDGS['updates'][239] = "UPDATE #__hwdvidsgs SET value = '$scroll_as' WHERE setting = 'scroll_as'"; }
		if (isset($scroll_au) && $scroll_au !== '') { $HWDGS['updates'][240] = "UPDATE #__hwdvidsgs SET value = '$scroll_au' WHERE setting = 'scroll_au'"; }
		if (isset($scroll_wr) && $scroll_wr !== '') { $HWDGS['updates'][241] = "UPDATE #__hwdvidsgs SET value = '$scroll_wr' WHERE setting = 'scroll_wr'"; }
		if (isset($cat_he) && $cat_he !== '') { $HWDGS['updates'][242] = "UPDATE #__hwdvidsgs SET value = '$cat_he' WHERE setting = 'cat_he'"; }
		if (isset($thumb_ts) && $thumb_ts !== '') { $HWDGS['updates'][243] = "UPDATE #__hwdvidsgs SET value = '$thumb_ts' WHERE setting = 'thumb_ts'"; }
		if (isset($gtree_mdrt) && $gtree_mdrt !== '') { $HWDGS['updates'][244] = "UPDATE #__hwdvidsgs SET value = '$gtree_mdrt' WHERE setting = 'gtree_mdrt'"; }
		if (isset($gtree_mdrt_child) && $gtree_mdrt_child !== '') { $HWDGS['updates'][245] = "UPDATE #__hwdvidsgs SET value = '$gtree_mdrt_child' WHERE setting = 'gtree_mdrt_child'"; }
		if (isset($show_vp_info) && $show_vp_info !== '') { $HWDGS['updates'][246] = "UPDATE #__hwdvidsgs SET value = '$show_vp_info' WHERE setting = 'show_vp_info'"; }
		if (isset($show_tooltip) && $show_tooltip !== '') { $HWDGS['updates'][247] = "UPDATE #__hwdvidsgs SET value = '$show_tooltip' WHERE setting = 'show_tooltip'"; }
		if (isset($usehq) && $usehq !== '') { $HWDGS['updates'][248] = "UPDATE #__hwdvidsgs SET value = '$usehq' WHERE setting = 'usehq'"; }
		if (isset($uselibx264) && $uselibx264 !== '') { $HWDGS['updates'][249] = "UPDATE #__hwdvidsgs SET value = '$uselibx264' WHERE setting = 'uselibx264'"; }
		if (isset($countcvids) && $countcvids !== '') { $HWDGS['updates'][250] = "UPDATE #__hwdvidsgs SET value = '$countcvids' WHERE setting = 'countcvids'"; }
		if (isset($search_method) && $search_method !== '') { $HWDGS['updates'][251] = "UPDATE #__hwdvidsgs SET value = '$search_method' WHERE setting = 'search_method'"; }
		if ($task == "savegeneral") {
			$HWDGS['updates'][252] = "UPDATE #__hwdvidsgs SET value = '$search_title' WHERE setting = 'search_title'";
			$HWDGS['updates'][253] = "UPDATE #__hwdvidsgs SET value = '$search_descr' WHERE setting = 'search_descr'";
			$HWDGS['updates'][254] = "UPDATE #__hwdvidsgs SET value = '$search_keywo' WHERE setting = 'search_keywo'";
		}
		if (isset($vsdirectory) && $vsdirectory !== '') { $HWDGS['updates'][255] = "UPDATE #__hwdvidsgs SET value = '$vsdirectory' WHERE setting = 'vsdirectory'"; }
		if (isset($use_protection) && $use_protection !== '') { $HWDGS['updates'][256] = "UPDATE #__hwdvidsgs SET value = '$use_protection' WHERE setting = 'use_protection'"; }
		if (isset($protection_level) && $protection_level !== '') { $HWDGS['updates'][257] = "UPDATE #__hwdvidsgs SET value = '$protection_level' WHERE setting = 'protection_level'"; }
		if (isset($cnvt_keyf) && $cnvt_keyf !== '') { $HWDGS['updates'][258] = "UPDATE #__hwdvidsgs SET value = '$cnvt_keyf' WHERE setting = 'cnvt_keyf'"; }
		if (isset($age_check) && $age_check !== '') { $HWDGS['updates'][259] = "UPDATE #__hwdvidsgs SET value = '$age_check' WHERE setting = 'age_check'"; }
		if (isset($gtree_edtr) && $gtree_edtr !== '') { $HWDGS['updates'][260] = "UPDATE #__hwdvidsgs SET value = '$gtree_edtr' WHERE setting = 'gtree_edtr'"; }
		if (isset($gtree_edtr_child) && $gtree_edtr_child !== '') { $HWDGS['updates'][261] = "UPDATE #__hwdvidsgs SET value = '$gtree_edtr_child' WHERE setting = 'gtree_edtr_child'"; }
		if (isset($disable_nav_playlist) && $disable_nav_playlist !== '') { $HWDGS['updates'][262] = "UPDATE #__hwdvidsgs SET value = '$disable_nav_playlist' WHERE setting = 'disable_nav_playlist'"; }
		if (isset($disable_nav_channel) && $disable_nav_channel !== '') { $HWDGS['updates'][263] = "UPDATE #__hwdvidsgs SET value = '$disable_nav_channel' WHERE setting = 'disable_nav_channel'"; }
		if (isset($storagetype) && $storagetype !== '') { $HWDGS['updates'][264] = "UPDATE #__hwdvidsgs SET value = '$storagetype' WHERE setting = 'storagetype'"; }
		if (isset($cnvt_fsize_hd) && $cnvt_fsize_hd !== '') {                               $HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '$cnvt_fsize_hd' WHERE setting = 'cnvt_fsize_hd'"; }
		if (isset($cnvt_hd_preset) && $cnvt_hd_preset !== '') {                             $HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '$cnvt_hd_preset' WHERE setting = 'cnvt_hd_preset'"; }
		if (isset($keep_ar) && $keep_ar !== '') {                                           $HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '$keep_ar' WHERE setting = 'keep_ar'"; }
		if (isset($warpAccountKey) && $warpAccountKey !== '') {                             $HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '$warpAccountKey' WHERE setting = 'warpAccountKey'"; }
		if (isset($warpSecretKey) && $warpSecretKey !== '') {                               $HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '$warpSecretKey' WHERE setting = 'warpSecretKey'"; }
		if (isset($cpp) && $cpp !== '') {                                                   $HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '$cpp' WHERE setting = 'cpp'"; }
		if ($task == "savegeneral") {
			$HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '$ipod320' WHERE setting = 'ipod320'";
			$HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '$ipod640' WHERE setting = 'ipod640'";
		}

		$HWDGS['message'] = "Saving general settings to database";
		// apply
		foreach($HWDGS['updates'] as $UPDT) {
			$db->setQuery($UPDT);
			if(!$db->query()) {
				//Save failed
				print("<font color=red>".$HWDGS['message']." failed! SQL error:" . $db->stderr(true)."</font><br />");
				return;
			}
		}
	}
   /**
	*/
	function restoreDefaults()
	{
		$db = & JFactory::getDBO();
		$app = & JFactory::getApplication();

		$HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '6' WHERE setting = 'vpp'";
		$HWDGS['updates'][] = "UPDATE #__hwdvidsgs SET value = '6' WHERE setting = 'vpp'";


		$HWDGS['message'] = "Saving general settings to database";

		foreach($HWDGS['updates'] as $UPDT)
		{
			$db->setQuery($UPDT);
			if(!$db->query())
			{
				print("<font color=red>".$HWDGS['message']." failed! SQL error:" . $db->stderr(true)."</font><br />");
				return;
			}
		}

		$app->enqueueMessage("Default Settings Restored");
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=generalsettings' );
	}
}
?>