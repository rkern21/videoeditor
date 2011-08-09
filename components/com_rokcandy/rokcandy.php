<?php
/**
 * RokCandy Macros RokCandy Macro Controller
 *
 * @package		Joomla
 * @subpackage	RokCandy Macros
 * @copyright Copyright (C) 2009 RocketTheme. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author RocketTheme, LLC
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Make sure the user is authorized to view this page
$user = & JFactory::getUser();


// Get the media component configuration settings
$params =& JComponentHelper::getParams('com_rokcandy');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

$cmd = JRequest::getCmd('task', null);


$controller = new RokCandyController();

// Set the model and view paths to the administrator folders
$controller->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views');
$controller->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');

// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
