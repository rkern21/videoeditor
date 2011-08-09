<?php
/*
 * ARI Framework Lite
 *
 * @package		ARI Framework Lite
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

class AriJoomlaUtils
{
	function isJoomla15()
	{
		return defined('_JEXEC');
	}
	
	function &getDBO()
	{
		$db = null;
		if (AriJoomlaUtils::isJoomla15())
		{
			$db = &JFactory::getDBO();
		}
		else
		{
			global $database;
			
			$db =& $database;
		}
		
		return $db;
	}
	
	function getSiteUrl()
	{
		if (AriJoomlaUtils::isJoomla15())
		{
			$mainframe = & JFactory::getApplication();
			
			if ($mainframe->isAdmin()) 
	    	{
	        	return substr_replace($mainframe->getSiteURL(), '', -1, 1);
	    	} 
	    	else 
	    	{
	        	return substr_replace(JURI::base(), '', -1, 1);
	    	}
		}
		else
		{
			global $mosConfig_live_site;
			
			return $mosConfig_live_site;
		}
	}
	
	function getUserId()
	{
		$userId = 0;
		if (AriJoomlaUtils::isJoomla15())
		{
			$user =& JFactory::getUser();
			if ($user) $userId = $user->get('id');
		}
		else
		{
			global $my;
			
			if ($my) $userId = $my->get('id');
		}
		
		return $userId;
	}
	
	function isRegistered()
	{
		$userId = AriJoomlaUtils::getUserId();
		
		return ($userId > 0);
	}

	function getLink($link, $xhtml = false, $clearItemId = true)
	{
		if (!AriJoomlaUtils::isJoomla15())
		{
			if (function_exists('sefRelToAbs')) $link = sefRelToAbs($link);
			if (!$xhtml) $link = str_replace('&amp;', '&', $link);
		}
		else 
		{
			$app = &JFactory::getApplication();
			$router = &$app->getRouter();

			if($router->getMode() == JROUTER_MODE_SEF && $clearItemId) 
			{
				$itemidPos = strpos($link, 'Itemid');
				if ($itemidPos !== false)
				{
					$link = preg_replace('/Itemid(?:=[^&;]*)?/', '', $link);
				}
			}

			$link = JRoute::_($link, $xhtml);
		}
		
		return $link;
	}
}
?>