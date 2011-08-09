<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: installer.php 681 2011-06-01 08:50:04Z nikosdion $
 * @since 3.3
 */

defined('_JEXEC') or die('');

// Load framework base classes
jimport('joomla.application.component.controller');

/**
 * Our com_installer hijack view - Sssh! Don't tell anyone!
 */

class AkeebaControllerInstaller extends JController
{
	public function __construct($config = array()) {
		parent::__construct($config);
		
		/*
		 * Make sure the user is authorized to view this page
		 */
		$user = & JFactory::getUser();
		if (!$user->authorize('com_installer', 'installer')) {
			JFactory::getApplication()->redirect('index.php', JText::_('ALERTNOTAUTH'));
		}
		
		$this->registerDefaultTask('installform');
		
		// Load language file for com_installer
		$lang = JFactory::getLanguage();
		$lang->load('com_installer', JPATH_BASE, 'en-GB', true);
		$lang->load('com_installer', JPATH_BASE, null, true);
		
		// Joomla! 1.6 compatibility
		if(version_compare(JVERSION, '1.6', 'ge')) {
			require_once JPATH_ADMINISTRATOR.'/components/com_installer/helpers/installer.php';
			
			if (!JFactory::getUser()->authorise('core.manage', 'com_installer')) {
				$this->setRedirect('index.php');
				return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
			}
		}
	}
	
	public function installform()
	{
		$app = JFactory::getApplication();
		
		$model	= $this->getModel('Installer', 'AkeebaModel');
		$model->setState('install.directory', $app->getCfg('config.tmp_path'));

		$view	= &$this->getView('Installer', 'html', 'AkeebaView');
		
		jimport('joomla.client.helper');
		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);
		$document = JFactory::getDocument();
		$view->assignRef('document', $document);

		$view->setModel( $model, true );
		
		if(version_compare(JVERSION, '1.6', 'ge')) {
			InstallerHelper::addSubmenu('install');
		}
		
		$view->display();
	}
	
	
	public function doInstall()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( version_compare(JVERSION, '1.6.0', 'ge') ? 'JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN' : 'Request Forbidden' );

		$model	= &$this->getModel('Installer', 'AkeebaModel');
		$view	= &$this->getView('Installer', 'html', 'AkeebaView');
		$token = JUtility::getToken();

		$ftp =& $this->applyFTPCredentials();
		$view->assignRef('ftp', $ftp);

		$installtype = JRequest::getCmd('installtype','upload');
		$installable = true;
		switch($installtype) {
			case 'url':
				$installable = $model->download();
				if($installable) $this->setRedirect('index.php?option=com_akeeba&view=installer&task=extract&'.$token.'=1');
				break;

			case 'upload':
				$installable = $model->upload();
				if($installable) $this->setRedirect('index.php?option=com_akeeba&view=installer&task=extract&'.$token.'=1');
				break;
			
			case 'folder':
				$installable = $model->fromDirectory();
				if($installable) {
					// Try to get the SRP URL
					$srpurl = $this->getSRPURL();
					if($srpurl === false) {
						$this->setRedirect('index.php?option=com_akeeba&view=installer&task=realinstall');
					} else {
						$this->setRedirect($srpurl.'&'.$token.'=1');
					}
				}
				break;
			
			default:
				$this->setState('message', 'No Install Type Found');
				$installable = false;
				break;
		}
		
		// If it is not something installable, go back to the installer view
		if(!$installable) {
			$view->setModel( $model, true );
			if(version_compare(JVERSION, '1.6', 'ge')) {
				InstallerHelper::addSubmenu('install');
			}
			$document = JFactory::getDocument();
			$view->assignRef('document', $document);
			$view->display();
		}

	}
	
	public function extract()
	{
		JRequest::checkToken('get') or jexit( version_compare(JVERSION, '1.6.0', 'ge') ? 'JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN' : 'Request Forbidden' );
		
		$model	= &$this->getModel('Installer', 'AkeebaModel');
		$view	= &$this->getView('Installer', 'html', 'AkeebaView');
		$token = JUtility::getToken();
		
		if($model->extract()) {
			// Try to get the SRP URL
			$srpurl = $this->getSRPURL();

			if($srpurl === false) {
				$this->setRedirect('index.php?option=com_akeeba&view=installer&task=realinstall&'.$token.'=1');
			} else {
				$this->setRedirect($srpurl);
			}
		} else {
			$ftp =& $this->applyFTPCredentials();
			$view->assignRef('ftp', $ftp);
		
			$view->setModel( $model, true );
			if(version_compare(JVERSION, '1.6', 'ge')) {
				InstallerHelper::addSubmenu('install');
			}
			$document = JFactory::getDocument();
			$view->assignRef('document', $document);
			$view->display();
		}
	}
	
	public function realinstall()
	{
		$model	= &$this->getModel('Installer', 'AkeebaModel');
		$view	= &$this->getView('Installer', 'html', 'AkeebaView');
		$token = JUtility::getToken();
		
		if ($model->realInstall()) {
			$cache = &JFactory::getCache('mod_menu');
			$cache->clean();
		}
		
		$ftp =& $this->applyFTPCredentials();
		$view->assignRef('ftp', $ftp);
		
		if(version_compare(JVERSION, '1.6.0', 'ge')) {
			$app = JFactory::getApplication();
			$redirect_url = $app->getUserState('com_installer.redirect_url');
			if(!empty($redirect_url)) {
				$this->setRedirect($redirect_url);
				return;
			}
		}		
		
		$view->setModel( $model, true );
		if(version_compare(JVERSION, '1.6', 'ge')) {
			InstallerHelper::addSubmenu('install');
		}
		$document = JFactory::getDocument();
		$view->assignRef('document', $document);
		$view->display();
	}
	
	/**
	 * Applies the FTP connection credentials, either supplied directly by the
	 * user in his request, or by reusing those saved in the session the first
	 * time the user supplied them in the request.
	 * 
	 * It can be used instead of JClientHelper::setCredentialsFromRequest('ftp')
	 * 
	 * @return bool|JException
	 */
	private function applyFTPCredentials()
	{
		// Try to get FTP credentials from the session
		$session = JFactory::getSession();
		$user = $session->get('ftp.user', null, 'akeeba');
		$pass = $session->get('ftp.pass', null, 'akeeba');
		
		// Is this the first use? Try to fetch and save the FTP credentials.
		if(is_null($user) && is_null($pass)) {
			$user = JRequest::getString('username', null, 'POST', JREQUEST_ALLOWRAW);
			$pass = JRequest::getString('password', null, 'POST', JREQUEST_ALLOWRAW);
			$session->set('ftp.user', $user, 'akeeba');
			$session->set('ftp.pass', $user, 'akeeba');
		}
		
		JRequest::setVar('username', $user, 'POST');
		JRequest::setVar('password', $pass, 'POST');
		
		jimport('joomla.client.helper');
		return JClientHelper::setCredentialsFromRequest('ftp');
	}

	private function getSRPURL()
	{
		$session = JFactory::getSession();
		$package = $session->get('package', array(), 'akeeba');
		
		$model	= $this->getModel('Installer', 'AkeebaModel');
		$name = $model->getExtensionName($package['dir']);
		
		if($name !== false) {
			// If SRPs are supported, get the SRP URL
			$type = $package['type'];
			$url = 'index.php?option=com_akeeba&view=backup&tag=restorepoint&type='.$type.'&name='.urlencode($name['name']);
			switch($type) {
				case 'module':
				case 'template':
					$url .= '&group='.$name['client'];
					break;
				case 'plugin':
					$url .= '&group='.$name['group'];
					break;
			}
			$url .= '&returnurl='.urlencode('index.php?option=com_akeeba&view=installer&task=realinstall');
			return $url;
		} else {
			// If they're not supported, return false
			return false;
		}
	}
}