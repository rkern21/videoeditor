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
global $limitstart, $limit, $mainframe, $task, $cid;

// get general configuration data
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'directory.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'hwdvideoshare.class.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'admin.hwdvideoshare.html.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'js.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'access.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'initialise.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'file_management.class.php');

$c           = hwd_vs_Config::get_instance();
$db          = & JFactory::getDBO();
$my          = & JFactory::getUser();
$acl         = & JFactory::getACL();
$app         = & JFactory::getApplication();
$limit       = $app->getUserStateFromRequest("viewlistlimit", 'limit', 10);
$limitstart  = $app->getUserStateFromRequest("viewlimitstart", 'limitstart', 0);
$task        = JRequest::getCmd( 'task', 'frontpage' );

$request_array = JRequest::get( 'request' );
@$cid 	= $request_array['cid'];

hwdvsInitialise::getJVersion();
hwdvsInitialise::getMooVersion();
hwdvsInitialise::language('be');
if (!hwdvsInitialise::template('0')) {return;}
hwdvsInitialise::mysqlQuery();
hwdvsInitialise::definitions();

// Check for initialisation request
if ( $c->initialise_now == "1" && $task !== "initialise_now" )
{
	hwdvids_HTML::initialise($option);
	return;
}
else if ( $c->initialise_now == "1" && $task == "initialise_now" )
{
	hwdvsInitialise::initialiseSetup();
	return;
}

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Create the controller
$controller	= new UsersController( );

// Perform the Request task
$controller->execute( $task );
$controller->redirect();

?>