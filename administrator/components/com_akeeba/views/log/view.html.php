<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: view.html.php 691 2011-06-02 19:59:30Z nikosdion $
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.view');

/**
 * MVC View for Log
 *
 */
class AkeebaViewLog extends JView
{
	public function display($tpl = null)
	{
		// Add toolbar buttons
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('VIEWLOG').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option='.JRequest::getCmd('option'));
		JToolBarHelper::spacer();
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'../media/com_akeeba/theme/akeebaui.css?'.AKEEBAMEDIATAG);

		// Add live help
		AkeebaHelperIncludes::addHelp();

		// Get a list of log names
		akimport('models.log',true);
		$model = new AkeebaModelLog();
		$this->assign('logs', $model->getLogList());

		$tag = JRequest::getCmd('tag',null);
		if(empty($tag)) $tag = null;
		$this->assign('tag', $tag);

		// Get profile ID
		$profileid = AEPlatform::get_active_profile();
		$this->assign('profileid', $profileid);

		// Get profile name
		akimport('models.profiles',true);
		$model = new AkeebaModelProfiles();
		$model->setId($profileid);
		$profile_data = $model->getProfile();
		$this->assign('profilename', $profile_data->description);

		AkeebaHelperIncludes::includeMedia(false);

		parent::display($tpl);
	}
}