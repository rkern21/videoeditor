<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: srprestore.php 681 2011-06-01 08:50:04Z nikosdion $
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.controller');

/**
 * Restoration of System Restore Points
 */
class AkeebaControllerSrprestore extends JController
{
	public function  __construct($config = array()) {
		parent::__construct($config);
		if(AKEEBA_JVERSION=='16')
		{
			// Access check, Joomla! 1.6 style.
			$user = JFactory::getUser();
			if (!$user->authorise('akeeba.download', 'com_akeeba')) {
				$this->setRedirect('index.php?option=com_akeeba');
				return JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
				$this->redirect();
			}
		} else {
			// Custom ACL for Joomla! 1.5
			$aclModel = JModel::getInstance('Acl','AkeebaModel');
			if(!$aclModel->authorizeUser('configure')) {
				$this->setRedirect('index.php?option=com_akeeba');
				return JError::raiseWarning(403, JText::_('Access Forbidden'));
				$this->redirect();
			}
		}
	}

	function display()
	{
		$model =& $this->getModel('Srprestore','AkeebaModel');
		$message = $model->validateRequest();
		if( $message !== true )
		{
			$this->setRedirect('index.php?option=com_akeeba&view=buadmin', $message, 'error');
			$this->redirect();
			return;
		}
		
		parent::display();
	}
	
	function start()
	{
		// CSRF prevention
		if(!JRequest::getVar(JUtility::getToken(), false, 'POST')) {
			JError::raiseError('403', JText::_(version_compare(JVERSION, '1.6.0', 'ge') ? 'JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN' : 'Request Forbidden'));
		}
		
		$model =& $this->getModel('Srprestore','AkeebaModel');
		$message = $model->validateRequest();
		if( $message !== true )
		{
			$this->setRedirect('index.php?option=com_akeeba&view=buadmin', $message, 'error');
			$this->redirect();
			return;
		}
		
		$status = $model->setRestorationParameters();
		
		parent::display();
	}
	
	function ajax()
	{
		$ajax = JRequest::getCmd('ajax');
		$model = $this->getModel('Srprestore','AkeebaModel');
		$model->setState('ajax', $ajax);
		
		$ret = $model->doAjax();
		
		@ob_end_clean();
		echo '###'.json_encode($ret).'###';
		flush();
		JFactory::getApplication()->close();
	}
}