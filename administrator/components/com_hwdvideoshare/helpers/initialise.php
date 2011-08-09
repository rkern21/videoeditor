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

/**
 * Process character encoding
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvsInitialise
{
    function getJVersion()
    {
		global $j15, $j16;

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
	}

    function getMooVersion()
    {
		global $j15, $j16, $mooVersion;

		if ($j15)
		{
			$pluginMooUpgrade =& JPluginHelper::getPlugin('system', 'mtupgrade');
			if ($pluginMooUpgrade)
			{
				$mooVersion = "1.2";
			}
			else
			{
				$mooVersion = "1.1";
			}
		}
		if ($j16)
		{
			$mooVersion = "1.3";
		}
	}

    function coreRequire()
    {
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'access.php');
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'directory.php');
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'initialise.php');
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'carousel.php');
		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'hwdvideoshare.class.php');
        $c = hwd_vs_Config::get_instance();
		if ($c->loadmootools == "on") {
			JHTML::_('behavior.mootools');
		}
    }

    function language($type='fe')
    {
		global $mainframe, $langConversionArray;
        $c = hwd_vs_Config::get_instance();
		$app = & JFactory::getApplication();

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'languageCode.php');
		$langugeCode = strtolower($app->getCfg('language'));

		if ($c->hwdvids_language_path == "joomfish" && array_key_exists($langugeCode, $langConversionArray) && file_exists(JPATH_SITE.DS.'plugins'.DS.'hwdvs-language'.DS.$langConversionArray[$langugeCode].'.'.$type.'.php'))
		{
			require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-language'.DS.$langConversionArray[$langugeCode].'.'.$type.'.php');
		}
		else if (file_exists(JPATH_PLUGINS.DS.$c->hwdvids_language_path.DS.$c->hwdvids_language_file.'.'.$type.'.php'))
		{
			require_once(JPATH_PLUGINS.DS.$c->hwdvids_language_path.DS.$c->hwdvids_language_file.'.'.$type.'.php');
		}
		else if (file_exists(JPATH_PLUGINS.DS.'hwdvs-language'.DS.'english.'.$type.'.php'))
		{
			require_once(JPATH_PLUGINS.DS.'hwdvs-language'.DS.'english.'.$type.'.php');
		}
		else if (file_exists(JPATH_PLUGINS.DS.'hwdvs-language'.DS.'english'.DS.'english.'.$type.'.php'))
		{
			require_once(JPATH_PLUGINS.DS.'hwdvs-language'.DS.'english'.DS.'english.'.$type.'.php');
		}
		else
		{
			echo "You must install the hwdVideoShare English language plugin. It is included in the hwdMediaShare package.";
			return;
		}
    }

    function template($fe=true)
    {
		global $mainframe, $option, $smartyvs;
        $c = hwd_vs_Config::get_instance();
		$app = & JFactory::getApplication();

		// setup template system
		if (!class_exists('smarty'))
		{
			require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'smarty'.DS.'Smarty.class.php');
		}

		if (!defined( '_HWD_VS_TEMPLATE_SETUP' ))
		{
			define( '_HWD_VS_TEMPLATE_SETUP', 1 );

			$smartyvs = new Smarty;

			if (!$fe)
			{
				$smartyvs->template_dir = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'templates';
				$vs_temp_cache = JPATH_SITE.DS.'administrator'.DS.'cache'.DS.'hwdvs';
				require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'templates.php');
				hwd_vs_templates::backend();
			}
			else
			{
				$template_folder = $app->getUserState( "com_hwdvideoshare.template_folder", '' );
				$template_element = $app->getUserState( "com_hwdvideoshare.template_element", '' );

				if (!empty($template_folder) && !empty($template_element))
				{
					$c->hwdvids_template_path = $template_folder;
					$c->hwdvids_template_file = $template_element;
				}

				if (file_exists(JPATH_PLUGINS.DS.$c->hwdvids_template_path.DS.$c->hwdvids_template_file.DS.'templates'.DS.'index.tpl'))
				{
					$smartyvs->template_dir = JPATH_PLUGINS.DS.$c->hwdvids_template_path.DS.$c->hwdvids_template_file.DS.'templates';
				}
				else if (file_exists(JPATH_PLUGINS.DS.'hwdps-template'.DS.'default'.DS.'templates'.DS.'index.tpl'))
				{
					$smartyvs->template_dir = JPATH_PLUGINS.DS.'hwdvs-template'.DS.'default'.DS.'templates';
				}
				else if (file_exists(JPATH_PLUGINS.DS.'hwdps-template'.DS.'default'.DS.'default'.DS.'templates'.DS.'index.tpl'))
				{
					$smartyvs->template_dir = JPATH_PLUGINS.DS.'hwdvs-template'.DS.'default'.DS.'default'.DS.'templates';
				}

				$vs_temp_cache = JPATH_SITE.DS.'cache'.DS.'hwdvs'.$c->hwdvids_template_file;
				require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'templates.php');
				hwd_vs_templates::frontend();
			}

			JFolder::create($vs_temp_cache, "0777");
			JPath::setPermissions($vs_temp_cache, "0777", "0777");

			if (!file_exists($vs_temp_cache) || !is_writable($vs_temp_cache))
			{
				echo "<div style=\"border:2px solid #c30;margin: 0 0 5px 0;padding:5px;text-align:left;\">hwdVideoShare can not load until the following directory has been made writeable:<br />".$vs_temp_cache."</div>";
				echo "<div style=\"border:2px solid #c30;margin: 0 0 5px 0;padding:5px;text-align:left;\">Ensure all your <a href=\"".JURI::root( true )."/administrator/index.php?option=com_admin&task=sysinfo\">Joomla Cache Directory Permissions</a> are writeable before attempting to use hwdVideoShare</div>";
				return false;
			}

			$smartyvs->compile_check = true;
			$smartyvs->debugging = false;
			$smartyvs->compile_dir = $vs_temp_cache;
			$smartyvs->cache_dir = $vs_temp_cache;
			$smartyvs->config_dir = $vs_temp_cache;
			//$smartyvs->clear_compiled_tpl();

			if ($fe)
			{
				$template_folder = $app->getUserState( "com_hwdvideoshare.template_folder", '' );
				$template_element = $app->getUserState( "com_hwdvideoshare.template_element", '' );

				if (!empty($template_folder) && !empty($template_element))
				{
					$c->hwdvids_template_path = $template_folder;
					$c->hwdvids_template_file = $template_element;
				}

				if (file_exists(JPATH_PLUGINS.DS.$c->hwdvids_template_path.DS.$c->hwdvids_template_file.'.php'))
				{
					$template_css = include_once(JPATH_PLUGINS.DS.$c->hwdvids_template_path.DS.$c->hwdvids_template_file.'.php');
				}
				else if (file_exists(JPATH_PLUGINS.DS.'hwdvs-template'.DS.'default.php'))
				{
					$template_css = include_once(JPATH_PLUGINS.DS.'hwdvs-template'.DS.'default.php');
				}
				else if (file_exists(JPATH_PLUGINS.DS.'hwdvs-template'.DS.'default'.DS.'default.php'))
				{
					$template_css = include_once(JPATH_PLUGINS.DS.'hwdvs-template'.DS.'default'.DS.'default.php');
				}
				else
				{
					echo "You must install the hwdVideoShare Default Template plugin. It is included in the package.";
					return;
				}
			}
		}
		return true;
	}

    function itemid($set=true)
    {
		global $hwdvsItemid;
  		$db = & JFactory::getDBO();

		$Itemid = JRequest::getInt('Itemid');

		$db->SetQuery( 'SELECT count(*) FROM #__menu WHERE id = '.$Itemid.' AND link LIKE "%com_hwdvideoshare%"');
		$total = $db->loadResult();

		if (empty($total) || $total == '0')
		{
			$query = "SELECT id FROM #__menu WHERE link LIKE '%com_hwdvideoshare%' LIMIT 0, 1";
			$db->SetQuery($query);
			$row = $db->loadResult();

			if (!empty($row))
			{
				if ($set)
				{
					$Itemid = $row;
					$hwdvsItemid = $row;
					return;
				}
				else
				{
					$hwdvsItemid = $row;
					return $row;
				}
			}
		}
		$hwdvsItemid = $Itemid;
		return $Itemid;
    }

    function isModerator()
    {
		global $isModerator;
        $c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();

		if ($my->id == 0)
		  return false;

		$isModerator = false;
		if (isset($c->gtree_mdrt) && !empty($c->gtree_mdrt))
		{
			if (hwd_vs_access::allowAccess($c->gtree_mdrt, $c->gtree_mdrt_child, hwd_vs_access::userGID( $my->id )))
			{
				$isModerator = true;
			}
		}

		return;
    }

    function definitions()
    {
		global $mainframe, $option, $smartyvs;
        $c = hwd_vs_Config::get_instance();
        $app = & JFactory::getApplication();

		defined('_HWD_VS_PLUGIN_COMPS') ? null : define('_HWD_VS_PLUGIN_COMPS', 214);

		$template_folder = $app->getUserState( "$option.template_folder", "" );
		$template_element = $app->getUserState( "$option.template_element", "" );

		if (!empty($template_folder) && !empty($template_element))
		{
			$c->hwdvids_template_path = $template_folder;
			$c->hwdvids_template_file = $template_element;
		}

		if (file_exists(JPATH_PLUGINS.DS.$c->hwdvids_template_path.DS.$c->hwdvids_template_file.DS.'images'.DS.'core'.DS))
		{
			defined('URL_HWDVS_IMAGES') ? null : define('URL_HWDVS_IMAGES', JURI::root( true ).DS.'plugins'.DS.$c->hwdvids_template_path.DS.$c->hwdvids_template_file.DS.'images'.DS.'core'.DS);
		}
		else
		{
			defined('URL_HWDVS_IMAGES') ? null : define('URL_HWDVS_IMAGES', JURI::root( true ).'/components/com_hwdvideoshare/assets/images/');
		}
		$smartyvs->assign("URL_HWDVS_IMAGES", URL_HWDVS_IMAGES);
    }

    function mysqlQuery()
    {
		global $hwdvs_joinv, $hwdvs_joing, $hwdvs_joinc, $hwdvs_selectv, $hwdvs_selectg, $hwdvs_selectc;
        $c = hwd_vs_Config::get_instance();

		// set core sql variables
		$hwdvs_joinv = ' LEFT JOIN #__users AS u ON u.id = video.user_id';
		$hwdvs_joing = ' LEFT JOIN #__users AS u ON u.id = g.adminid';
		$hwdvs_joinc = ' LEFT JOIN #__users AS u ON u.id = c.user_id';
		$hwdvs_selectv = ' video.*, u.name, u.username';
		$hwdvs_selectg = ' g.*, u.name, u.username';
		$hwdvs_selectc = ' c.*, u.name, u.username';
		if ($c->cbint == 1)
		{
			$hwdvs_joinv.= ' LEFT JOIN #__comprofiler AS p ON p.id = video.user_id';
			$hwdvs_joing.= ' LEFT JOIN #__comprofiler AS p ON p.id = g.adminid';
			$hwdvs_joinc.= ' LEFT JOIN #__comprofiler AS p ON p.id = c.user_id';
			$hwdvs_selectv.= ', p.avatar';
			$hwdvs_selectg.= ', p.avatar';
			$hwdvs_selectc.= ', p.avatar';
		}
		else if ($c->cbint == 2)
		{
			$hwdvs_joinv.= ' LEFT JOIN #__community_users AS p ON p.userid = video.user_id';
			$hwdvs_joing.= ' LEFT JOIN #__community_users AS p ON p.userid = g.adminid';
			$hwdvs_joinc.= ' LEFT JOIN #__community_users AS p ON p.userid = c.user_id';
			$hwdvs_selectv.= ', p.avatar';
			$hwdvs_selectg.= ', p.avatar';
			$hwdvs_selectc.= ', p.avatar';
		}
		else if ($c->cbint == 5)
		{
			$hwdvs_joinv.= ' LEFT JOIN #__hwdvidschannels AS p ON p.user_id = video.user_id';
			$hwdvs_joing.= ' LEFT JOIN #__hwdvidschannels AS p ON p.user_id = g.adminid';
			$hwdvs_joinc.= ' LEFT JOIN #__hwdvidschannels AS p ON p.user_id = c.user_id';
			$hwdvs_selectv.= ', p.channel_thumbnail AS avatar';
			$hwdvs_selectg.= ', p.channel_thumbnail AS avatar';
			$hwdvs_selectc.= ', p.channel_thumbnail AS avatar';
		}
    }

    function coreAccess()
    {
		global $mainframe;
        $c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = & JComponentHelper::getParams( 'com_users' );

		require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'mvc'.DS.'controller'.DS.'access.php');

		if ($c->access_method == 0) {
			if (!hwd_vs_access::allowAccess( $c->gtree_core, $c->gtree_core_child, hwd_vs_access::userGID( $my->id ))) {
				if ( ($my->id < 1) && (!$usersConfig->get( 'allowUserRegistration' ) == '0' && hwd_vs_access::allowAccess( $c->gtree_upld, 'RECURSE', $acl->get_group_id('Registered','ARO') ) ) ) {
					hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORACCESS, "exclamation.png", 0);
					return;
				} else {
					hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_NOT_AUTHORIZED, "exclamation.png", 0);
					return;
				}
			}
		} else if ($c->access_method == 1) {
			if (!hwd_vs_access::allowLevelAccess( $c->accesslevel_upld, hwd_vs_access::userGID( $my->id ))) {
				hwd_vs_tools::infomessage(1, 0,  _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_NOT_AUTHORIZED, "exclamation.png", 0);
				return;
			}
		}
    }

    function revenueManager()
    {
		$revenue_manager = JPATH_SITE.DS.'components'.DS.'com_hwdrevenuemanager';

		if (file_exists($revenue_manager)) {

			require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'adverts.php');

		}
    }

    function mobiles()
    {
		global $mainframe, $option;

		$isMobile = false;

		$op = strtolower(@$_SERVER['HTTP_X_OPERAMINI_PHONE']);
		$ua = strtolower(@$_SERVER['HTTP_USER_AGENT']);
		$ac = strtolower(@$_SERVER['HTTP_ACCEPT']);
		$ip = $_SERVER['REMOTE_ADDR'];

		$isMobile = strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
				|| $op != ''
				|| strpos($ua, 'sony') !== false
				|| strpos($ua, 'symbian') !== false
				|| strpos($ua, 'nokia') !== false
				|| strpos($ua, 'samsung') !== false
				|| strpos($ua, 'mobile') !== false
				|| strpos($ua, 'windows ce') !== false
				|| strpos($ua, 'epoc') !== false
				|| strpos($ua, 'opera mini') !== false
				|| strpos($ua, 'nitro') !== false
				|| strpos($ua, 'j2me') !== false
				|| strpos($ua, 'midp-') !== false
				|| strpos($ua, 'cldc-') !== false
				|| strpos($ua, 'netfront') !== false
				|| strpos($ua, 'mot') !== false
				|| strpos($ua, 'up.browser') !== false
				|| strpos($ua, 'up.link') !== false
				|| strpos($ua, 'audiovox') !== false
				|| strpos($ua, 'blackberry') !== false
				|| strpos($ua, 'ericsson,') !== false
				|| strpos($ua, 'panasonic') !== false
				|| strpos($ua, 'philips') !== false
				|| strpos($ua, 'sanyo') !== false
				|| strpos($ua, 'sharp') !== false
				|| strpos($ua, 'sie-') !== false
				|| strpos($ua, 'portalmmm') !== false
				|| strpos($ua, 'blazer') !== false
				|| strpos($ua, 'avantgo') !== false
				|| strpos($ua, 'danger') !== false
				|| strpos($ua, 'palm') !== false
				|| strpos($ua, 'series60') !== false
				|| strpos($ua, 'palmsource') !== false
				|| strpos($ua, 'pocketpc') !== false
				|| strpos($ua, 'smartphone') !== false
				|| strpos($ua, 'rover') !== false
				|| strpos($ua, 'ipaq') !== false
				|| strpos($ua, 'au-mic,') !== false
				|| strpos($ua, 'alcatel') !== false
				|| strpos($ua, 'ericy') !== false
				|| strpos($ua, 'up.link') !== false
				|| strpos($ua, 'vodafone/') !== false
				|| strpos($ua, 'wap1.') !== false
				|| strpos($ua, 'wap2.') !== false
				|| strpos($ua, 'android') !== false;

				//$isMobile = true;

		if ($option == "com_hwdvideoshare" && $isMobile)
		{
			$mainframe->addCustomHeadTag("<script type=\"text/javascript\">
											<!--
											onload = function ()
											{
												for (var i = 0; i < document.links.length; i++)
												{
													if (document.links[i].href.contains('?'))
													{
														document.links[i].href = document.links[i].href + '&tmpl=component'
													}
													else
													{
														document.links[i].href = document.links[i].href + '?tmpl=component'
													}
												}
												for (var i = 0; i < document.forms.length; i++)
												{
													if (document.forms[i].action.contains('?'))
													{
														document.forms[i].action = document.forms[i].action + '&tmpl=component'
													}
													else
													{
														document.forms[i].action = document.forms[i].action + '?tmpl=component'
													}
												}
											}
											// -->
										  </script>");
		}
    }

    function background()
    {
		global $mainframe;
        $c = hwd_vs_Config::get_instance();

		$task        = JRequest::getCmd( 'task' );
        $maintenance = JRequest::getCmd( 'maintenance' );

		switch ($maintenance)
		{
			/** pre execute functions */
			case 'generateplaylists':

				//$currentSession = JSession::getInstance('none',array());
				//$currentSession->destroy();

				require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'xml'.DS.'xmloutput.class.php');
				HWDVS_xmlOutput::checkCacheThenWrite();
				echo "XML Playlists Generated";
				exit;

			case 'full':

				//$currentSession = JSession::getInstance('none',array());
				//$currentSession->destroy();

				$cachedir = JPATH_SITE .DS.'administrator'.DS.'cache'.DS;
				$cachetime = 86400;
				$cacheext = 'cache';
				$page = 'http://archivefile';
				$cachefile = $cachedir . md5($page) . '.' . $cacheext;

				$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;
				if (time() - $cachetime > $cachefile_created)
				{
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_fixerrors.class.php');
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_archivelogs.class.php');

					hwd_vs_fixerrors::initiate(2);
					hwd_vs_recount::initiate(2);
					//hwd_vs_logs::initiate(2);
				}
				else
				{
					echo "Maintenance recently executed... aborting";
				}
				exit;

 			case 'storage':

				if ($c->storagetype == "amazons3")
				{
					if (file_exists(JPATH_SITE.DS.'plugins'.DS.'hwdvs-storage'.DS.'amazons3.php'))
					{
						require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-storage'.DS.'amazons3.php');
						HWDVS_storage::initialise();
					}
					else
					{
						echo "Storage plugin is not installed.";
					}
				}
				exit;

			default:
            break;
		}

		// generate xml playlists
		if ($maintenance !== "generateplaylists" && $c->playlist_bkgd !== "disable") {

			$cachedir = JPATH_SITE.DS.'cache'.DS;
			$cachetime = 3600;
			$cacheext = 'cache';
			$page = 'http://xmlplaylists_today';
			$cachefile = $cachedir . md5($page) . '.' . $cacheext;

			$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;

			if (time() - $cachetime > $cachefile_created) {

				if ($c->playlist_bkgd == "none") {
					require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'xml'.DS.'xmloutput.class.php');
					HWDVS_xmlOutput::checkCacheThenWrite();
				} else if ($c->playlist_bkgd == "direct") {
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
					$s = hwd_vs_SConfig::get_instance();
					@exec("env -i $s->phppath ".JPATH_SITE.DS."components".DS."com_hwdvideoshare".DS."xml".DS."autogenerate.php ".JURI::root()." &>/dev/null &");
				} else if ($c->playlist_bkgd == "wget1") {
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
					$s = hwd_vs_SConfig::get_instance();
					@exec("env -i $s->wgetpath -O - -q \"".JURI::root()."index.php?option=com_hwdvideoshare&maintenance=generateplaylists\" &>/dev/null &");
				} else if ($c->playlist_bkgd == "wget2") {
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
					$s = hwd_vs_SConfig::get_instance();
					@exec("env -i $s->wgetpath -O - -q \"".JURI::root()."index.php?option=com_hwdvideoshare&maintenance=generateplaylists\" >/dev/null &");
				}

			}
		}

		// maintenance
		if ($maintenance !== "full" && $c->maintenance_bkgd !== "none") {

			$cachedir = JPATH_SITE .DS.'administrator'.DS.'cache'.DS;
			$cachetime = 86400;
			$cacheext = 'cache';
			$page = 'http://archivefile';
			$cachefile = $cachedir . md5($page) . '.' . $cacheext;

			$cachefile_created = (@file_exists($cachefile)) ? @filemtime($cachefile) : 0;

			if (time() - $cachetime > $cachefile_created) {

				if ($c->maintenance_bkgd == "direct") {
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
					$s = hwd_vs_SConfig::get_instance();
					//@exec("env -i $s->phppath ".JPATH_SITE ."/components/com_hwdvideoshare/xml/autogenerate.php ".JURI::root()." &>/dev/null &");
				} else if ($c->maintenance_bkgd == "wget1") {
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
					$s = hwd_vs_SConfig::get_instance();
					@exec("env -i $s->wgetpath -O - -q \"".JURI::root()."index.php?option=com_hwdvideoshare&maintenance=full\" &>/dev/null &");
				} else if ($c->playlist_bkgd == "wget2") {
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
					$s = hwd_vs_SConfig::get_instance();
					@exec("env -i $s->wgetpath -O - -q \"".JURI::root()."index.php?option=com_hwdvideoshare&maintenance=full\" >/dev/null &");
				}

			}
		}
    }

	function initialiseSetup()
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$cats    		= JRequest::getInt( 'cats', 0, 'post' );
		$youtube 		= JRequest::getInt( 'youtube', 0, 'post' );
		$google  		= JRequest::getInt( 'google', 0, 'post' );
		$jwflv_license  = JRequest::getInt( 'jwflv_license', 0, 'post' );

		if ($cats == 1) {
			$db->setQuery( 'INSERT IGNORE INTO `#__hwdvidscategories` (`id`, `parent`, `category_name`, `category_description`, `date`, `access_b_v`, `access_u_r`, `access_v_r`, `access_u`, `access_v`, `ordering`, `num_vids`, `num_subcats`, `checked_out`, `checked_out_time`, `published`)'
								.'VALUES (1, 0, \'Cars &amp; Vehicles\', \'Cars, Classic cars, Motorcycles &amp; Scooters, Campers, Caravans &amp; Motorhomes, Commercial Vehicles, Aircraft &amp; Aviation, Boats &amp; Watercraft, Other Vehicles\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 0, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(2, 0, \'Comedy\', \'Sketches, Stand-up &amp; Spoofs\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 1, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(3, 0, \'Entertainment\', \'Films &amp; Television\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 2, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(4, 0, \'Film &amp; Animation\', \'Short Films, Stop-motion &amp; Animation\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 3, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(5, 0, \'How To &amp; Style\', \'Instruction, Ideas &amp; Training\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 4, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(6, 0, \'Music\', \'Bands, Singers &amp; Songwriters\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 5, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(7, 0, \'News &amp; Politics\', \'Current Events &amp; Commentaries\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 6, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(8, 0, \'People &amp; Blogs\', \'Personalities, Biographies &amp; Artists\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 7, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(9, 0, \'Pets &amp; Animals\', \'Dogs &amp; Cats\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 8, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(10, 0, \'Sport\', \'Extreme, Competitions &amp; Skateboarding\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 9, 0, 0, 0, \'0000-00-00 00:00:00\', 1),'
								.'(11, 0, \'Travel &amp; Events\', \'Holidays, Nature &amp; Monuments\', \'0000-00-00 00:00:00\', 0, \'RECURSE\', \'RECURSE\', -2, -2, 10, 0, 0, 0, \'0000-00-00 00:00:00\', 1);'
								);
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}

		$db->SetQuery("UPDATE #__hwdvidsgs SET value = 0 WHERE setting = \"initialise_now\"");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'draw.php');
		hwdvsDrawFile::generalConfig();

		$app->enqueueMessage('Setup Completed! Please run the maintenance tools before proceeding.');
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=maintenance' );
	}
}
?>