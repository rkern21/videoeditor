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

// declare global variables
global $limitstart, $limit, $task;

// get general configuration data
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'directory.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'hwdvideoshare.class.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'hwdvideoshare.html.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'js.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'access.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'initialise.php');

$c           = hwd_vs_Config::get_instance();
$db          = & JFactory::getDBO();
$my          = & JFactory::getUser();
$acl         = & JFactory::getACL();
$usersConfig = & JComponentHelper::getParams( 'com_users' );
$limit       = JRequest::getInt( 'limit', 0 );
$limitstart  = JRequest::getInt( 'limitstart', 0 );
$task        = JRequest::getCmd( 'task', 'frontpage' );

hwdvsInitialise::getJVersion();
if ($task !== "deliverThumb")
{
	hwdvsInitialise::isModerator();
	hwdvsInitialise::itemid();
	hwdvsInitialise::mobiles();
	hwdvsInitialise::background();
	hwdvsInitialise::language();
	if (!hwdvsInitialise::template()) {return;}
	hwdvsInitialise::revenueManager();
	hwdvsInitialise::mysqlQuery();
	hwdvsInitialise::definitions();
	if (!hwd_vs_access::checkAccess($c->gtree_core, $c->gtree_core_child, 1, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORACCESS, _HWDVIDS_ALERT_NOT_AUTHORIZED, 'exclamation.png', 0)) {return;}
	if ($c->loadmootools == "on")
	{
		JHTML::_('behavior.mootools');
	}
}

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Create the controller
$controller = new UserController();

// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
?>