<?php
/**
 * @package gantry
 * @subpackage admin.ajax-models
 * @version        3.1.10 March 5, 2011
 * @author        RocketTheme http://www.rockettheme.com
 * @copyright     Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

global $gantry;

$action = JRequest::getString('action');
gantry_import('core.gantryjson');


switch ($action){
    case 'save':
    case 'apply':
        echo gantryAjaxSaveTemplate();
        break;
    default:
        echo "error";
}

	function gantryAjaxSaveTemplate()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize some variables
		$db			 = & JFactory::getDBO();

		$template	= JRequest::getVar('template', '', 'method', 'cmd');
		$option		= JRequest::getVar('option', '', '', 'cmd');
		$client		=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$menus		= JRequest::getVar('selections', array(), 'post', 'array');
		$params		= JRequest::getVar('params', array(), 'post', 'array');
		$default	= JRequest::getBool('default');
		JArrayHelper::toInteger($menus);

		if (!$template) {
			return 'error:' . JText::_('Operation Failed').': '.JText::_('No template specified.');
		}

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

		$file = $client->path.DS.'templates'.DS.$template.DS.'params.ini';

		jimport('joomla.filesystem.file');
		if (JFile::exists($file) && count($params))
		{
			$registry = new JRegistry();
			$registry->loadArray($params);
			$txt = $registry->toString();

			// Try to make the params file writeable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755')) {
                return 'error:' . JText::_('Could not make the template parameter file writable');
			}

			$return = JFile::write($file, $txt);

			// Try to make the params file unwriteable
			if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555')) {
                return 'error:' . JText::_('Could not make the template parameter file unwritable');
			}

			if (!$return) {
                return 'error:' . JText::_('Operation Failed').': '.JText::sprintf('Failed to open file for writing.', $file);
			}
		}

		// Reset all existing assignments
		$query = 'DELETE FROM #__templates_menu' .
				' WHERE client_id = 0' .
				' AND template = '.$db->Quote( $template );
		$db->setQuery($query);
		$db->query();

		if ($default) {
			$menus = array( 0 );
		}

		foreach ($menus as $menuid)
		{
			// If 'None' is not in array
			if ((int) $menuid >= 0)
			{
				// check if there is already a template assigned to this menu item
				$query = 'DELETE FROM #__templates_menu' .
						' WHERE client_id = 0' .
						' AND menuid = '.(int) $menuid;
				$db->setQuery($query);
				$db->query();

				$query = 'INSERT INTO #__templates_menu' .
						' SET client_id = 0, template = '. $db->Quote( $template ) .', menuid = '.(int) $menuid;
				$db->setQuery($query);
				$db->query();
			}
		}

		$task = JRequest::getCmd('task');
		if($task == 'apply') {
			return 'success: ' . JText::_('Template settings applied.');
		} else {
			return 'success: ' . JText::_('Template settings saved.');
		}
	}