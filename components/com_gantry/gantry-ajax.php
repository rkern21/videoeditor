<?php
/**
 * @package		gantry
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

global  $gantry;

$mainframe = JFactory::getApplication();

// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

// comment out the following 2 lines for debugging
$request = @$_SERVER['HTTP_X_REQUESTED_WITH'];
if(!isset($request) || strtolower($request) != 'xmlhttprequest') die("Direct access not allowed.");

// get current template
$template = &JFactory::getApplication()->getTemplate();

// load and inititialize gantry class
$gantry_path_j15 = JPATH_SITE . '/components/com_gantry/gantry.php';
$gantry_path_j16 = JPATH_SITE . '/libraries/gantry/gantry.php';
if (version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.6', '<')) {
    $gantry_path = $gantry_path_j15;
}
else if (version_compare(JVERSION, '1.6', '>=')) {
    $gantry_path = $gantry_path_j16;
}

if (file_exists($gantry_path)) {
    require_once($gantry_path);
}
else {
    echo "error " . JText::_('Unable to find Gantry library.  Please make sure you have it installed.');
    die;
}

$model = $gantry->getAjaxModel(JRequest::getString('model'));
if ($model === false) die();
include_once($model);


/* 
	- USAGE EXAMPLE -

	new Ajax('http://url/template/?tmpl=gantry-ajax', {
		onSuccess: function(response) {console.log(response);}
	}).request({
	   	'model': 'example', // <- mandatory, see "ajax-models" folder
	   	'example': 'example1', // <-- from here are all custom query posts you can use
	   	'name': 'w00fz',
	   	'message': 'Hello World!'
	});
*/


?>