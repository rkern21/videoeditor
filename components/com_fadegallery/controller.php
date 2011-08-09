<?php
/**
 * FadeGallery Joomla! 1.5 Native Component
 * @version 1.2.5
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
class FadeGalleryController extends JController {
	function display() {
		// Make sure we have a default view
		
		JRequest::setVar('view', 'gallery' );
		if(!JRequest::getVar('layout'))
			JRequest::setVar('layout', 'default' );
			
		parent::display();
		
	}
}
?>