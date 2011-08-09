<?php
/**
 * RokCandy Macros RokCandy Macro View
 *
 * @package		Joomla
 * @subpackage	RokCandy Macros
 * @copyright Copyright (C) 2009 RocketTheme. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author RocketTheme, LLC
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

require_once (JPATH_COMPONENT.DS.'helpers'.DS.'categories.php' );

/**
 * HTML View class for the WebLinks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.0
 */
class RokCandyViewCandyMacro extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}

		//get the rokcandymacro
		$rokcandymacro =& $this->get('data');

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();
		$document   =& JFactory::getDocument();
		
		$document->addStyleSheet('components/'.$option.'/assets/rokcandy.css');


		$lists = array();

		//get the rokcandymacro
		$rokcandymacro	=& $this->get('data');
		$isNew		= ($rokcandymacro->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'ROKCANDY_MACRO' ), $rokcandymacro->macro );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout( $user->get('id') );
		}
		else
		{
			// initialise new record
			$rokcandymacro->published = 1;
			$rokcandymacro->order 	= 0;
			$rokcandymacro->catid 	= JRequest::getVar( 'catid', 0, 'post', 'int' );
		}

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, macro AS text'
			. ' FROM #__rokcandy'
			. ' WHERE catid = ' . (int) $rokcandymacro->catid
			. ' ORDER BY ordering';

		$lists['ordering'] 			= JHTML::_('list.specificordering',  $rokcandymacro, $rokcandymacro->id, $query );
		
		// build list of categories
		//$lists['catid'] 			= JHTML::_('list.category',  'catid', $option, intval( $rokcandymacro->catid ) );
		$lists['catid']             = JElementRokCandyList::getCategories('catid',  $option, intval( $rokcandymacro->catid ) );

		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $rokcandymacro->published );

		//clean rokcandymacro data
		JFilterOutput::objectHTMLSafe( $rokcandymacro, ENT_QUOTES, 'html' );

		$file 	= JPATH_COMPONENT.DS.'models'.DS.'candymacro.xml';
		$params = new JParameter( $rokcandymacro->params, $file );

		$this->assignRef('lists',		$lists);
		$this->assignRef('rokcandymacro',		$rokcandymacro);
		$this->assignRef('params',		$params);

		parent::display($tpl);
	}
}
