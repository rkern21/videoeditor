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
 * ACL functions: original code from com_comprofiler
 *
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC2.13
 */
class hwd_vs_templates
{
    /**
     * Grants or prevents access based on group id
     *
     * @param int    $accessgroupid  the group id to check against
     * @param string $recurse  the switch for recursive access check
     * @param int    $usersgroupid  the user's group id
     * @return       True or false
     */
	function frontend()
	{
		global $smartyvs, $print_ulink, $print_glink, $hwdvsTemplateOverride, $hwdvsItemid, $isModerator;

		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'access.php');
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'initialise.php');

		$smartyvs->assign("JURL", JURI::root( true ));
		$smartyvs->assign("HWDVSURL", JURI::root( true )."/index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid);
		$smartyvs->assign("Itemid", $hwdvsItemid );

		$searchterm = Jrequest::getVar( 'pattern', _HWDVIDS_SEARCHBAR );
		$smartyvs->assign("form_search", JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=search"));
		$smartyvs->assign("form_tp", JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=addconfirm"));
		$smartyvs->assign("form_upload", JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=upload"));
		$smartyvs->assign("searchinput", "<input type=\"text\" name=\"pattern\" value=\"".$searchterm."\" class=\"inputbox\" onchange=\"document.adminForm.submit();\"  onblur=\"if(this.value=='') this.value='"._HWDVIDS_SEARCHBAR."';\" onfocus=\"if(this.value=='"._HWDVIDS_SEARCHBAR."') this.value='';\"/>");

		// define config variables
		if ($c->diable_nav_videos == 0 || $c->diable_nav_catego == 0 || $c->diable_nav_groups == 0 || $c->diable_nav_upload == 0) { $smartyvs->assign("print_nav", 1); }
		if ($c->diable_nav_search == 0) { $smartyvs->assign("print_search", 1); }
		if ($my->id && $c->diable_nav_user == 0 && ($c->diable_nav_user1 == 0 || $c->diable_nav_user2 == 0 || $c->diable_nav_user3 == 0 || $c->diable_nav_user4 == 0 || $c->diable_nav_user5 == 0)) { $smartyvs->assign("print_usernav", 1); }
		if ($c->diable_nav_videos == 0) { $smartyvs->assign("print_vlink", 1); $smartyvs->assign("vlink", "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=frontpage")."\">"._HWDVIDS_NAV_VIDEOS."</a>"); }
		if ($c->diable_nav_catego == 0) { $smartyvs->assign("print_clink", 1); $smartyvs->assign("clink", "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=categories")."\">"._HWDVIDS_NAV_CATEGORIES."</a>"); }
		if (hwd_vs_access::allowAccess( $c->gtree_grup, $c->gtree_grup_child, hwd_vs_access::userGID( $my->id )))
		{
			if ($c->diable_nav_groups == 0)
			{
				$smartyvs->assign("print_glink", 1);
				$smartyvs->assign("glink", "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=groups")."\">"._HWDVIDS_NAV_GROUPS."</a>");
				$print_glink = true;
			}
		}
 		if ($c->diable_nav_upload == 0)
		{
			$smartyvs->assign("print_ulink", 1);
			$smartyvs->assign("ulink", "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=upload")."\">"._HWDVIDS_NAV_UPLOAD."</a>");
			$print_ulink = true;

		}
		else if ($c->diable_nav_upload == 2)
		{
			if (hwd_vs_access::allowAccess( $c->gtree_upld, $c->gtree_upld_child, hwd_vs_access::userGID( $my->id )) || hwd_vs_access::allowAccess( $c->gtree_ultp, $c->gtree_ultp_child, hwd_vs_access::userGID( $my->id )))
			{
				$smartyvs->assign("print_ulink", 1);
				$smartyvs->assign("ulink", "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=upload")."\">"._HWDVIDS_NAV_UPLOAD."</a>");
				$print_ulink = true;
				$smartyvs->assign("print_nav", 1);
			}
		}

		if ($c->diable_nav_user1 == 0) { $smartyvs->assign("yv", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=viewChannel&user_id=".$my->id."&sort=uploads")."\">"._HWDVIDS_NAV_YOURVIDS."</a>&nbsp;"); }
		if ($c->diable_nav_user2 == 0) { $smartyvs->assign("yf", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=viewChannel&user_id=".$my->id."&sort=favourites")."\">"._HWDVIDS_NAV_YOURFAVS."</a>&nbsp;"); }
		if ($c->diable_nav_groups == 0 && $c->diable_nav_user3 == 0) { $smartyvs->assign("yg", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=viewChannel&user_id=".$my->id."&sort=groups")."\">"._HWDVIDS_NAV_YOURGROUPS."</a>&nbsp;"); }
		if ($c->diable_nav_groups == 0 && $c->diable_nav_user4 == 0) { $smartyvs->assign("ym", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=viewChannel&user_id=".$my->id."&sort=memberships")."\">"._HWDVIDS_NAV_YOURMEMBERSHIPS."</a>&nbsp;"); }
		if ($c->diable_nav_groups == 0 && $c->diable_nav_user5 == 0) { $smartyvs->assign("cg", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=creategroup")."\">"._HWDVIDS_NAV_CREATEGROUP."</a>&nbsp;"); }

		if ($c->disable_nav_playlist == 0) { $smartyvs->assign("cp", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=createPlaylist")."\">"._HWDVIDS_NAV_CREATEPL."</a>&nbsp;"); }
		if ($c->disable_nav_playlist == 0) { $smartyvs->assign("yp", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=viewChannel&user_id=".$my->id."&sort=playlists")."\">"._HWDVIDS_NAV_YOURPL."</a>&nbsp;"); }
		if ($c->disable_nav_channel == 0) { $smartyvs->assign("yc", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=viewChannel&user_id=".$my->id)."\">"._HWDVIDS_NAV_YOURCHANNEL."</a>&nbsp;"); }

		if ($c->showcredit == 1) { $smartyvs->assign("sc", "1"); $smartyvs->assign("cl", hwd_vs_templates::copyright_FE()); }

		$smartyvs->assign("help_link", "<a href=\"http://documentation.hwdmediashare.co.uk/wiki/Category:HwdVideoShare_User%27s_Manual\" target=\"_blank\">Help</a>");
		$smartyvs->assign("personalise_link", "<a href=\"http://documentation.hwdmediashare.co.uk/wiki/Category:HwdVideoShare_User%27s_Manual\">Personalise</a>");

		if ($c->fporder == "recent")
		{
			$smartyvs->assign("fpheader", _HWDVIDS_TITLE_RECENTUPLOADS);
			$smartyvs->assign("fpempty", _HWDVIDS_INFO_NRV);
		}
		else if ($c->fporder == "popular")
		{
			$smartyvs->assign("fpheader", _HWDVIDS_TITLE_POPULARVIDEOS);
			$smartyvs->assign("fpempty", _HWDVIDS_INFO_NVTD);
		}
		else if ($c->fporder == "viewed")
		{
			$smartyvs->assign("fpheader", _HWDVIDS_TITLE_MOSTVIEWEDVIDS);
			$smartyvs->assign("fpempty", _HWDVIDS_INFO_NVTD);
		}

		$smartyvs->assign("usershare1", $c->usershare1);
		$smartyvs->assign("usershare2", $c->usershare2);
		$smartyvs->assign("usershare3", $c->usershare3);
		$smartyvs->assign("usershare4", $c->usershare4);

		if ($c->usershare1 == 1 || $c->usershare2 == 1 || $c->usershare3 == 1 || $c->usershare4 == 1)
		{
			$smartyvs->assign("print_sharing", 1);
		}

		if ($c->shareoption1 == 0)
		{
			$smartyvs->assign("so1p", "");
			$smartyvs->assign("so1r", " selected=\"selected\"");
			$smartyvs->assign("so1m", "");
			$smartyvs->assign("so1w", "");
			$smartyvs->assign("so1value", "registered");
		}
		else if ($c->shareoption1 == 1)
		{
			$smartyvs->assign("so1p", " selected=\"selected\"");
			$smartyvs->assign("so1r", "");
			$smartyvs->assign("so1m", "");
			$smartyvs->assign("so1w", "");
			$smartyvs->assign("so1value", "public");
		}
		else if ($c->shareoption1 == 2)
		{
			$smartyvs->assign("so1p", "");
			$smartyvs->assign("so1r", "");
			$smartyvs->assign("so1m", " selected=\"selected\"");
			$smartyvs->assign("so1w", "");
			$smartyvs->assign("so1value", "me");
		}
		else if ($c->shareoption1 == 3)
		{
			$smartyvs->assign("so1p", "");
			$smartyvs->assign("so1r", "");
			$smartyvs->assign("so1m", "");
			$smartyvs->assign("so1w", " selected=\"selected\"");
			$smartyvs->assign("so1value", "password");
		}

		if ($c->shareoption2 == 0)
		{
			$smartyvs->assign("so21", "");
			$smartyvs->assign("so20", " selected=\"selected\"");
			$smartyvs->assign("so2value", "0");
		}
		else if ($c->shareoption2 == 1)
		{
			$smartyvs->assign("so21", " selected=\"selected\"");
			$smartyvs->assign("so20", "");
			$smartyvs->assign("so2value", "1");
		}

		if ($c->shareoption3 == 0)
		{
			$smartyvs->assign("so31", "");
			$smartyvs->assign("so30", " selected=\"selected\"");
			$smartyvs->assign("so3value", "0");
		}
		else if ($c->shareoption3 == 1)
		{
			$smartyvs->assign("so31", " selected=\"selected\"");
			$smartyvs->assign("so30", "");
			$smartyvs->assign("so3value", "1");
		}

		if ($c->shareoption4 == 0)
		{
			$smartyvs->assign("so41", "");
			$smartyvs->assign("so40", " selected=\"selected\"");
			$smartyvs->assign("so4value", "0");
		}
		else if ($c->shareoption4 == 1)
		{
			$smartyvs->assign("so41", " selected=\"selected\"");
			$smartyvs->assign("so40", "");
			$smartyvs->assign("so4value", "1");
		}

		$smartyvs->assign("categoryselect", hwd_vs_tools::categoryList(_HWDVIDS_INFO_CHOOSECAT, 0, _HWDVIDS_INFO_NOCATS, 1));

		$smartyvs->assign("rss_recent", JRoute::_("index.php?Itemid=".$hwdvsItemid."&option=com_hwdvideoshare&task=rss&feed=recent"));

		if ($isModerator)
		{
			$smartyvs->assign("print_moderation", 1);
			$smartyvs->assign("pending", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=pending")."\">"._HWDVIDS_MODPA."</a>&nbsp;");
			$smartyvs->assign("reportedvideos", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=reportedvideos")."\">"._HWDVIDS_MODRV."</a>&nbsp;");
			$smartyvs->assign("reportedgroups", "&nbsp;<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=reportedgroups")."\">"._HWDVIDS_MODRG."</a>&nbsp;");
		}

		jimport( 'joomla.application.menu' );
		$menu   = &JMenu::getInstance('site');
		$mparams = &$menu->getParams($hwdvsItemid);

		$smartyvs->assign("pageclass_sfx", $mparams->get( 'pageclass_sfx', ''));
		$smartyvs->assign("page_title", $mparams->get( 'page_title', ''));
		$smartyvs->assign("show_page_title", $mparams->get( 'show_page_title', ''));

		if ($c->cbint == "1")
		{
			$smartyvs->assign("url_register", JRoute::_("index.php?Itemid=".$hwdvsItemid."&option=com_comprofiler&task=registers"));
			$smartyvs->assign("url_reset", JRoute::_("index.php?Itemid=".$hwdvsItemid."&option=com_comprofiler&task=lostPassword"));
			$smartyvs->assign("url_remind", JRoute::_("index.php?Itemid=".$hwdvsItemid."&option=com_comprofiler&task=lostPassword"));
		}
		else
		{
			$smartyvs->assign("url_register", JRoute::_("index.php?Itemid=".$hwdvsItemid."&option=com_user&view=register"));
			$smartyvs->assign("url_reset", JRoute::_("index.php?Itemid=".$hwdvsItemid."&option=com_user&view=reset"));
			$smartyvs->assign("url_remind", JRoute::_("index.php?Itemid=".$hwdvsItemid."&option=com_user&view=remind"));
		}

        ////
        // here you can call any Joomla module positions you like and insert them into the hwdVideoShare template system
        ////
		//
		//  $document = &JFactory::getDocument();
		//  $renderer = $document->loadRenderer('modules');
		//  $options = array('style' => 'xhtml');
		//  $position = 'left';
		//  $smartyvs->assign("modules_left", $renderer->render($position, $options, null));
		//  // now you can use the {$modules_left} tag in any template file to display this module position in hwdVideoShare
		//

		if (!isset($hwdvsTemplateOverride['show_thumbnail'])) { $hwdvsTemplateOverride['show_thumbnail'] = 1; }
		if (!isset($hwdvsTemplateOverride['show_title'])) { $hwdvsTemplateOverride['show_title'] = 1; }
		if (!isset($hwdvsTemplateOverride['show_views'])) { $hwdvsTemplateOverride['show_views'] = 1; }
		if (!isset($hwdvsTemplateOverride['show_category'])) { $hwdvsTemplateOverride['show_category'] = 1; }
		if (!isset($hwdvsTemplateOverride['show_rating'])) { $hwdvsTemplateOverride['show_rating'] = 1; }
		if (!isset($hwdvsTemplateOverride['show_uploader'])) { $hwdvsTemplateOverride['show_uploader'] = 1; }
		if (!isset($hwdvsTemplateOverride['show_description'])) { $hwdvsTemplateOverride['show_description'] = 0; }
		if (!isset($hwdvsTemplateOverride['show_duration'])) { $hwdvsTemplateOverride['show_duration'] = 0; }
		if (!isset($hwdvsTemplateOverride['show_upload_date'])) { $hwdvsTemplateOverride['show_upload_date'] = 0; }
		if (!isset($hwdvsTemplateOverride['show_avatar'])) { $hwdvsTemplateOverride['show_avatar'] = 1; }
		if (!isset($hwdvsTemplateOverride['show_comments'])) { $hwdvsTemplateOverride['show_comments'] = 0; }
		if (!isset($hwdvsTemplateOverride['show_tags'])) { $hwdvsTemplateOverride['show_tags'] = 0; }
		if (!isset($hwdvsTemplateOverride['show_timesince'])) { $hwdvsTemplateOverride['show_timesince'] = 0; }

		// LEGACY
		$smartyvs->assign("mosConfig_live_site", JURI::root( true ));
		$smartyvs->assign("link_home", JURI::root( true ));
		$smartyvs->assign("link_home_hwd_vs", JURI::root( true )."/index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid);
		$smartyvs->assign("thumbwidth", $c->thumbwidth);
	}
    /**
     * Grants or prevents access based on group id
     *
     * @param int    $accessgroupid  the group id to check against
     * @param string $recurse  the switch for recursive access check
     * @param int    $usersgroupid  the user's group id
     * @return       True or false
     */
	function backend()
	{
		global $smartyvs, $print_ulink, $print_glink, $hwdvsTemplateOverride;

		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		if (file_exists(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdphotoshare'.DS.'version.php'))
		{
			$smartyvs->assign("hwdIntegrate", 1);
		}
		else
		{
			$smartyvs->assign("hwdIntegrate", 0);
		}

		$smartyvs->assign("backEndCopyright", hwd_vs_templates::copyright_BE());
		$smartyvs->assign("mosConfig_live_site", JURI::root(true));
		$smartyvs->assign("mosConfig_absolute_path", JPATH_SITE);
		$smartyvs->assign("usershare1", $c->usershare1);
		$smartyvs->assign("usershare2", $c->usershare2);
		$smartyvs->assign("usershare3", $c->usershare3);
		$smartyvs->assign("usershare4", $c->usershare4);


		$hidemainmenu = JRequest::getInt( 'hidemainmenu', 0, 'request' );


		if ($hidemainmenu == 1) {
			$smartyvs->assign("hidemainmenu", 1);
		} else {
			$smartyvs->assign("hidemainmenu", 0);
		}





		$categoryselectlist = hwd_vs_tools::categoryList(_HWDVIDS_INFO_CHOOSECAT, 0, _HWDVIDS_INFO_NOCATS, 1);
		$smartyvs->assign("categoryselect", $categoryselectlist);

		if ($c->usershare1 == 1 || $c->usershare2 == 1 || $c->usershare3 == 1 || $c->usershare4 == 1) {
			$smartyvs->assign("print_sharing", 1);
		}

		if ($c->shareoption1 == 0)
		{
			$smartyvs->assign("so1p", "");
			$smartyvs->assign("so1r", " selected=\"selected\"");
			$smartyvs->assign("so1m", "");
			$smartyvs->assign("so1w", "");
			$smartyvs->assign("so1value", "registered");
		}
		else if ($c->shareoption1 == 1)
		{
			$smartyvs->assign("so1p", " selected=\"selected\"");
			$smartyvs->assign("so1r", "");
			$smartyvs->assign("so1m", "");
			$smartyvs->assign("so1w", "");
			$smartyvs->assign("so1value", "public");
		}
		else if ($c->shareoption1 == 2)
		{
			$smartyvs->assign("so1p", "");
			$smartyvs->assign("so1r", "");
			$smartyvs->assign("so1m", " selected=\"selected\"");
			$smartyvs->assign("so1w", "");
			$smartyvs->assign("so1value", "me");
		}
		else if ($c->shareoption1 == 3)
		{
			$smartyvs->assign("so1p", "");
			$smartyvs->assign("so1r", "");
			$smartyvs->assign("so1m", "");
			$smartyvs->assign("so1w", " selected=\"selected\"");
			$smartyvs->assign("so1value", "password");
		}

		if ($c->shareoption2 == 0)
		{
			$smartyvs->assign("so21", "");
			$smartyvs->assign("so20", " selected=\"selected\"");
			$smartyvs->assign("so2value", "0");
		}
		else if ($c->shareoption2 == 1)
		{
			$smartyvs->assign("so21", " selected=\"selected\"");
			$smartyvs->assign("so20", "");
			$smartyvs->assign("so2value", "1");
		}

		if ($c->shareoption3 == 0)
		{
			$smartyvs->assign("so31", "");
			$smartyvs->assign("so30", " selected=\"selected\"");
			$smartyvs->assign("so3value", "0");
		}
		else if ($c->shareoption3 == 1)
		{
			$smartyvs->assign("so31", " selected=\"selected\"");
			$smartyvs->assign("so30", "");
			$smartyvs->assign("so3value", "1");
		}

		if ($c->shareoption4 == 0)
		{
			$smartyvs->assign("so41", "");
			$smartyvs->assign("so40", " selected=\"selected\"");
			$smartyvs->assign("so4value", "0");
		}
		else if ($c->shareoption4 == 1)
		{
			$smartyvs->assign("so41", " selected=\"selected\"");
			$smartyvs->assign("so40", "");
			$smartyvs->assign("so4value", "1");
		}
	}
	function copyright_BE()
	{
		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'version.php');
		$version = new hwdVideoShareVersion();
		$LongVersion = $version->getLongVersion();
		return "<div>".$LongVersion."<br />hwdMediaShare is created by <a href=\"http://hwdmediashare.co.uk\" target=\"_blank\">Highwood Design</a></div>";
	}
	function copyright_FE()
	{
		$cr = array();
		$cr[] = _HWDVIDS_DETAILS_PB." <a href=\"http://hwdmediashare.co.uk\" target=\"_blank\">hwdMediaShare</a>";
		$cr[] = "<a href=\"http://hwdmediashare.co.uk/hwdvideoshare\" target=\"_blank\">Joomla Video</a> by <a href=\"http://hwdmediashare.co.uk\" target=\"_blank\">hwdVideoShare</a>";
		$cr[] = "<a href=\"http://hwdmediashare.co.uk/hwdvideoshare\" target=\"_blank\">Joomla Video Component</a> by <a href=\"http://hwdmediashare.co.uk\" target=\"_blank\">hwdVideoShare</a>";
		$cr[] = "<a href=\"http://hwdmediashare.co.uk/hwdvideoshare\" target=\"_blank\">Joomla Video Gallery</a> by <a href=\"http://hwdmediashare.co.uk\" target=\"_blank\">hwdVideoShare</a>";
		$selection = rand(0,count($cr)-1);
		return $cr[$selection];
	}
}
?>