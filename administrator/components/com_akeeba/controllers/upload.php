<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id$
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.controller');

class AkeebaControllerUpload extends JController
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
	 * This controller does not support a default task, thank you.
	 * 
	 * @see libraries/joomla/application/component/JController#display($cachable)
	 */
	public function display()
	{
		JError::raiseError(500, 'Invalid task');
		return false;
	}

	public function upload()
	{
		// Get the parameters
		$id = $this->getAndCheckId();
		$part = JRequest::getInt('part', 0);
		$frag = JRequest::getInt('frag', 0);

		// Check the backup stat ID
		if($id === false) {
			$url = 'index.php?option=com_akeeba&view=upload&tmpl=component&task=cancelled&id='.$id;
			$this->setRedirect($url, JText::_('AKEEBA_TRANSFER_ERR_INVALIDID'), 'error');
			return;
		}
		
		// Calculate the filenames
		$stat = AEPlatform::get_statistics($id);
		$local_filename = $stat['absolute_path'];
		$basename = basename($local_filename);
		$extension = strtolower(str_replace(".", "", strrchr($basename, ".")));
		
		if($part > 0) {
			$new_extension = substr($extension,0,1) . sprintf('%02u', $part); 
		} else {
			$new_extension = $extension;
		}
		
		$filename = $basename.'.'.$new_extension;
		$local_filename = substr($local_filename, 0, -strlen($extension)).$new_extension;
		
		// Load the post-processing engine
		AEPlatform::load_configuration($stat['profile_id']);
		$config = AEFactory::getConfiguration();
		
		$session = JFactory::getSession();
		$engine = null;
		if(!empty($savedEngine) && ($frag != -1)) {
			// If it's not the first fragment, try to revive the saved engine
			$savedEngine = $session->get('postproc_engine', null, 'akeeba');
			$engine = unserialize($savedEngine);
		}
		if(empty($engine)) {
			$engine_name = $config->get('akeeba.advanced.proc_engine');
			$engine = AEFactory::getPostprocEngine($engine_name);
		}
		
		// Start uploading
		$result = $engine->processPart($local_filename);
		switch($result) {
			case true:
				$part++;
				break;
			
			case 1:
				$frag++;
				$savedEngine = serialize($engine);
				$session->set('postproc_engine', null, 'akeeba');
				break;
			
			case false;
				$part = -1;
				return;
				break;
		}
		
		if(version_compare(JVERSION, '1.6.0', 'ge')) {
			$view = & $this->getView( 'upload', 'html', '', array('base_path' => $this->basePath));
		} else {
			$view = & $this->getView( 'upload', 'html', '', array( 'base_path'=>$this->_basePath));
		}

		if($part >= 0) {
			if($part < $stat['multipart']) {
				$view->setLayout('uploading');
				$view->assign('parts',$stat['multipart']);
				$view->assign('part', $part);
				$view->assign('frag', $frag);
				$view->assign('id', $id);
			} else {
				// Update stats with remote filename
				$remote_filename = $config->get('akeeba.advanced.proc_engine','').'://';
				$remote_filename .= $engine->remote_path;
				$data = array(
					'remote_filename'	=> $remote_filename
				);
				AEPlatform::set_or_update_statistics($id, $data, $engine);
				
				$view->setLayout('done');
			}
		} else {
			$view->setLayout('error');
		}
		$view->display();
	}
	
	public function cancelled()
	{
		if(version_compare(JVERSION, '1.6.0', 'ge')) {
			$view = & $this->getView( 'upload', 'html', '', array('base_path' => $this->basePath));
		} else {
			$view = & $this->getView( 'upload', 'html', '', array( 'base_path'=>$this->_basePath));
		}

		$view->setLayout('error');
		$view->display();
	}

	public function start()
	{
		$id = $this->getAndCheckId();
		
		// Check the backup stat ID
		if($id === false) {
			$url = 'index.php?option=com_akeeba&view=upload&tmpl=component&task=cancelled&id='.$id;
			$this->setRedirect($url, JText::_('AKEEBA_TRANSFER_ERR_INVALIDID'), 'error');
			return;
		}
		
		if(version_compare(JVERSION, '1.6.0', 'ge')) {
			$view = & $this->getView( 'upload', 'html', '', array('base_path' => $this->basePath));
		} else {
			$view = & $this->getView( 'upload', 'html', '', array( 'base_path'=>$this->_basePath));
		}

		$view->assign('id', $id);
		$view->setLayout('default');
		$view->display();
	}

	/**
	 * Gets the stats record ID from the request and checks that it does exist
	 * 
	 * @return bool|int False if an invalid ID is found, the numeric ID if it's valid
	 */
	private function getAndCheckId()
	{
		$id = JRequest::getInt('id',0);
		
		if($id <= 0) return false;

		$statObject = AEPlatform::get_statistics($id);
		if(empty($statObject) || !is_array($statObject)) return false;

		return $id;
	}
}