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

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'toolbar.hwdvideoshare.html.php');

switch ( $task )
{
	case "homepage":
	hwdvidmenu::HOMEPAGE_MENU();
	break;

	case "videos":
	hwdvidmenu::VIDEO_MENU();
	break;

	case "editvidsA":
	case "editvids":
	hwdvidmenu::EDITVID_MENU();
	break;

	case "categories":
	hwdvidmenu::CAT_MENU();
	break;

	case "editcatA":
	case "editcat":
	case "newcat":
	hwdvidmenu::EDITCAT_MENU();
	break;

	case "groups":
	hwdvidmenu::GROUPS_MENU();
	break;

	case "editgrpA":
	case "editgrp":
	hwdvidmenu::EDITGRP_MENU();
	break;

	case "approvals":
	case "watchvideo":
	hwdvidmenu::APPROVE_MENU();
	break;

	case "watchflaggedvideo":
	case "flagged":
	case "remoteupload":
	case "ftpupload":
	case "ftpupload_result":
	case "botJombackup":
	case 'plugins':
	hwdvidmenu::INFO_MENU();
	break;

	case "serversettings":
	hwdvidmenu::SSETTINGS_MENU();
	break;

	case "generalsettings":
	hwdvidmenu::GSETTINGS_MENU();
	break;

	case "layoutsettings":
	hwdvidmenu::LSETTINGS_MENU();
	break;

	case "export":
	hwdvidmenu::EXPORT_MENU();
	break;

	case "import":
	hwdvidmenu::IMPORT_MENU();
	break;

	case "runmaintenance":
	case "maintenance":
	hwdvidmenu::MAINTENANCE_MENU();
	break;

	default:
	hwdvidmenu::HOMEPAGE_MENU();
	break;
}
?>