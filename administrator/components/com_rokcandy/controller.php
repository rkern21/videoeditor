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
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * RokCandy Macros RokCandy Macro Controller
 *
 * @package		Joomla
 * @subpackage	RokCandy Macros
 * @since 1.5
 */
class RokCandyController extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);

		// Register Extra tasks
		$this->registerTask( 'add',  'display' );
		$this->registerTask( 'edit', 'display' );
		$this->registerTask( 'list', 'display' );
	}

	function display( )
	{
		switch($this->getTask())
		{
			case 'add'     :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'candymacro');
				JRequest::setVar( 'edit', false );

				// Checkout the RokCandy Macro
				$model = $this->getModel('candymacro');
				$model->checkout();
			} break;
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view'  , 'candymacro');
				JRequest::setVar( 'edit', true );

				// Checkout the RokCandy Macro
				$model = $this->getModel('candymacro');
				$model->checkout();
			} break;
			case 'list'     :
			{
			    JRequest::setVar( 'layout', 'list' );
			    JRequest::setVar( 'view'  , 'rokcandy' );
			}
			
		}

		parent::display();
	}

	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['html'] = JRequest::getVar( 'html', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('candymacro');
		
		if ($model->store($post)) {
			$msg = JText::_( 'MACRO_SAVED' );
			$link = 'index.php?option=com_rokcandy';
		} else {
		    $msg = JText::sprintf('MACRO_SAVE_ERROR', $model->getError());
			$link = 'index.php?option=com_rokcandy&task=edit&cid[]='.$cid[0];
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT_ITEM_DELETE' ) );
		}

		$model = $this->getModel('candymacro');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_rokcandy' );
	}


	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT_ITEM_PUBLISH' ) );
		}

		$model = $this->getModel('candymacro');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_rokcandy' );
	}


	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT_ITEM_UNPUBLISH' ) );
		}

		$model = $this->getModel('candymacro');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_rokcandy' );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Checkin the RokCandy Macro
		$model = $this->getModel('candymacro');
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_rokcandy' );
	}


	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('candymacro');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_rokcandy');
	}

	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('candymacro');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_rokcandy');
	}

	function saveorder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('candymacro');
		$model->saveorder($cid, $order);

		$msg = JText::_( 'NEW_ORDER_SAVED' );
		$this->setRedirect( 'index.php?option=com_rokcandy', $msg );
	}
}