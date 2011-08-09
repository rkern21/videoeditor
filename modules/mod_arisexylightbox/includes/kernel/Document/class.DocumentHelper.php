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

AriKernel::import('Joomla.JoomlaUtils');
AriKernel::import('Web.HtmlHelper');

class AriDocumentHelper
{
	function includeJsFile($fileUrl)
	{
		if (AriJoomlaUtils::isJoomla15())
		{
			$document =& JFactory::getDocument();
			$document->addScript($fileUrl);
		}
		else
		{
			$tag = sprintf('<script src="%s" type="text/javascript"></script>', $fileUrl);
			AriDocumentHelper::includeCustomHeadTag($tag);		
		}
	}
	
	function includeCssFile($cssUrl, $type = 'text/css', $media = null, $attrs = array())
	{
		if (AriJoomlaUtils::isJoomla15())
		{
			$document =& JFactory::getDocument();
			$document->addStyleSheet($cssUrl, $type, $media, $attrs);
		}
		else
		{
			if (is_null($media)) $media = 'screen';
			$tag = sprintf('<link rel="stylesheet" href="%s" type="%s" media="%s"%s />', 
				$cssUrl,
				$type,
				$media,
				AriHtmlHelper::getAttrStr($attrs));
			AriDocumentHelper::includeCustomHeadTag($tag);
		}
	}
	
	function includeCustomHeadTag($tag)
	{
		if (AriJoomlaUtils::isJoomla15())
		{
			$document =& JFactory::getDocument();
			$document->addCustomTag($tag);
		}
		else
		{			
			$mainframe =& JFactory::getApplication();
			$mainframe->addCustomHeadTag($tag);
		}
	}
	
	function addCustomTagsToDocument($tags)
	{
		if (empty($tags)) return ;
		
		$content = '';
		if (AriJoomlaUtils::isJoomla15())
		{
			$content = JResponse::getBody();
		}
		else
		{
			$content = @ob_get_contents();
			@ob_clean();
		}

		$content = preg_replace('/(<\/head\s*>)/i', join('', $tags) . '$1', $content);
		
		if (AriJoomlaUtils::isJoomla15())
		{
			JResponse::setBody($content); 
		}
		else
		{
			echo $content;
		}
	}
}
?>