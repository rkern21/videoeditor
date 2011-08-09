<?php
/**
 * RokCandy Macros RokCandy Macro Model
 *
 * @package		Joomla
 * @subpackage	RokCandy Macros
 * @copyright Copyright (C) 2009 RocketTheme. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author RocketTheme, LLC
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

$controller	= new RokCandyController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();