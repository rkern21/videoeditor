<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

jimport('joomla.application.module.helper');

class AriModuleHelper extends JModuleHelper
{
	function &getModuleById($moduleId)
	{
		$module	= null;
		$moduleId = intval($moduleId, 10);
		if ($moduleId < 1)
			return $module;

		$modules =& AriModuleHelper::getModules();
		if (isset($modules[$moduleId]))
			$module =& $modules[$moduleId]; 

		return $module;
	}
	
	function renderModule($module, $attribs = array())
	{
		return parent::renderModule($module, $attribs);
	}
	
	function &getModules()
	{
		static $modules;
		
		if (!is_null($modules))
			return $modules;

		$mainframe =& JFactory::getApplication();
			
		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();
		$aid = $user->get('aid', 0);

		$query = 'SELECT id, title, module, position, content, showtitle, control, params'
			. ' FROM #__modules AS m'
			. ' LEFT JOIN #__modules_menu AS mm ON mm.moduleid = m.id'
			. ' WHERE m.published = 1'
			. ' AND m.access <= '. (int)$aid
			. ' AND m.client_id = '. (int)$mainframe->getClientId()
			. ' ORDER BY position, ordering';
		$db->setQuery($query);
		$modules = $db->loadObjectList('id');

		if ($db->getErrorNum()) 
		{
			$modules = array();
		}

		foreach ($modules as $key => $mod)
		{
			$module =& $modules[$key];
			$file = $module->module;
			$custom = substr($file, 0, 4) == 'mod_' ? 0 : 1;
			$module->user = $custom;
			$module->name = $custom ? $module->title : substr( $file, 4 );
			$module->style	= null;
			$module->position = strtolower($module->position);
		}
			
		return $modules;
	}
}
?>