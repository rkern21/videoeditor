<?php
/**
* Fade Javascript Image Gallery Joomla! 1.5 Native Component
* @version 1.2.5
* @author DesignCompass corp <admin@designcompasscorp.com>
* @link http://www.designcompasscorp.com
* @license GNU/GPL **/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class FadeGalleryControllerDocs extends JController
{
	/**
	 * New option item wizard
	 */
	function display()
	{
		JRequest::setVar( 'view', 'docs');
		
		parent::display();
	}

	
}
