<?php
/**
* Fade Javascript Image Gallery Joomla! 1.5 Native Component
* @version 1.2.5
* @author DesignCompass corp <admin@designcompasscorp.com>
* @link http://www.designcompasscorp.com
* @license GNU/GPL **/

// no direct access
defined('_JEXEC') or die('Restricted access');


$controllerName = JRequest::getCmd( 'controller', 'galleries' );


switch($controllerName)
{
	
	case 'docs';

		JSubMenuHelper::addEntry(JText::_('GALLERIES'), 'index.php?option=com_fadegallery&controller=galleries', false);
		JSubMenuHelper::addEntry(JText::_('DOCUMENTATION'), 'index.php?option=com_fadegallery&controller=docs', true);
		break;
	default:
	
		JSubMenuHelper::addEntry(JText::_('GALLERIES'), 'index.php?option=com_fadegallery&controller=galleries', true);
		JSubMenuHelper::addEntry(JText::_('DOCUMENTATION'), 'index.php?option=com_fadegallery&controller=docs', false);
		break;
}
require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );


$controllerName = 'FadeGalleryController'.$controllerName;
$controller	= new $controllerName( );


// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>