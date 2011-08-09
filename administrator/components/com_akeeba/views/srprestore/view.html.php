<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: view.html.php 691 2011-06-02 19:59:30Z nikosdion $
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Load framework base classes
jimport('joomla.application.component.view');

/**
 * Multiple databases definition View
 *
 */
class AkeebaViewSrprestore extends JView
{
	/**
	 * Modified constructor to enable loading layouts from the plug-ins folder
	 * @param $config
	 */
	public function __construct( $config = array() )
	{
		parent::__construct( $config );
		$tmpl_path = dirname(__FILE__).DS.'tmpl';
		$this->addTemplatePath($tmpl_path);
	}

	public function display()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('SRPRESTORATION').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option='.JRequest::getCmd('option'));
		
		// Add references to scripts and CSS
		AkeebaHelperIncludes::includeMedia(true);

		$task = JRequest::getVar('task','');
		$model =& $this->getModel();
		if($task == 'start')
		{
			$password = JRequest::getVar('password','','default','none',2);
			$this->assign('password', $password );	
			$this->setLayout('restore');
		}
		else
		{
			$model->validateRequest();
			$id					= $model->getId();
			$ftpparams			= $model->getFTPParams();
			$extractionmodes	= $model->getExtractionModes();
			
			$this->assign('id', $id);
			$this->assign('ftpparams', $ftpparams);
			$this->assign('extractionmodes', $extractionmodes);
			$this->assignRef('info', $model->info);
		}

		// Add live help
		AkeebaHelperIncludes::addHelp();

		parent::display();
	}

}