<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: stw.php 632 2011-05-22 20:44:46Z nikosdion $
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.controller');

class AkeebaControllerStw extends JController
{
	public function  __construct($config = array()) {
		parent::__construct($config);
		if(AKEEBA_JVERSION=='16')
		{
			// Access check, Joomla! 1.6 style.
			$user = JFactory::getUser();
			if (!$user->authorise('akeeba.backup', 'com_akeeba')) {
				$this->setRedirect('index.php?option=com_akeeba');
				return JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
				$this->redirect();
			}
		} else {
			// Custom ACL for Joomla! 1.5
			$aclModel = JModel::getInstance('Acl','AkeebaModel');
			if(!$aclModel->authorizeUser('backup')) {
				$this->setRedirect('index.php?option=com_akeeba');
				return JError::raiseWarning(403, JText::_('Access Forbidden'));
				$this->redirect();
			}
		}
	}
	
	/**
	 * Step 1 - select profile
	 * @param type $cachable 
	 */
	public function display($cachable = false) {
		JRequest::setVar('layout','step1');		
		parent::display($cachable);
	}
	
	/**
	 * Applies the profile creation preferences and displays the transfer setup
	 * page.
	 * 
	 * @return void
	 */
	public function step2()
	{
		$method = JRequest::getCmd('method','none');
		$oldprofile = JRequest::getInt('oldprofile', 0);
		
		$model = $this->getModel('Stw','AkeebaModel');
		$model->setState('method', $method);
		$model->setState('oldprofile', $oldprofile);
		$result = $model->makeOrUpdateProfile();
		
		if($result == false) {
			$url = 'index.php?option=com_akeeba&view=stw';
			$this->setRedirect($url, JText::_('STW_PROFILE_ERR_COULDNOTCREATESTWPROFILE'), 'error');
			return;
		}
		
		JRequest::setVar('layout','step2');
		parent::display();
	}
	
	/**
	 * Apply the site transfer settings, test the connection, upload a test file
	 * and show the last step's page.
	 */
	public function step3()
	{
		$model = $this->getModel('Stw','AkeebaModel');
		$model->setState('method',		JRequest::getCmd('method','ftp'));
		$model->setState('hostname',	JRequest::getVar('hostname',''));
		$model->setState('port',		JRequest::getInt('port',''));
		$model->setState('username',	JRequest::getVar('username',''));
		$model->setState('password',	JRequest::getVar('password',''));
		$model->setState('directory',	JRequest::getVar('directory',''));
		$model->setState('passive',		JRequest::getBool('passive',false));
		$model->setState('livesite',	JRequest::getVar('livesite',''));
		$result = $model->applyTransferSettings();
		
		if($result != true) {
			$url = 'index.php?option=com_akeeba&view=stw&task=step2&method=none';
			$this->setRedirect($url, $result, 'error');
			return;
		}
		
		JRequest::setVar('layout','step3');
		parent::display();
	}	
}