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
 
jimport( 'joomla.application.component.controller' ); 
 
class RokCandyController extends JController
{
  
	function display()
	{
		global $mainframe;

		$vName = JRequest::getCmd('task', 'list');
		switch ($vName)
		{

			case 'list':
			default:
				$vLayout = JRequest::getCmd( 'layout', 'list' );
				$mName = 'rokcandy';
				$vName = 'rokcandy';

				break;
		}

		$document = &JFactory::getDocument();
		$vType		= $document->getType();

		// Get/Create the view
		$view = &$this->getView( $vName, $vType);
		$view->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.strtolower($vName).DS.'tmpl');

		// Get/Create the model
		if ($model = &$this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}

		// Set the layout
		$view->setLayout($vLayout);

		// Display the view
		$view->display();
	}
    
    
}