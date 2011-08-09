<?php
/*
 * ARI Sexy Lightbox
 *
 * @package		ARI Sexy Lightbox
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2010 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Utils.Utils2');
AriKernel::import('Document.DocumentHelper');
jimport('joomla.filter.filterinput');

class AriSexyLightboxHelper
{
	function includeAssets($loadJQuery, $noConflict, $jQueryVer = '1.4.4', $options = array())
	{
		static $loaded;

		if ($loaded)
			return ;

		$baseUri = JURI::root(true) . '/modules/mod_arisexylightbox/includes/js/';
		if ($loadJQuery) 
		{
			AriDocumentHelper::includeJsFile('http://ajax.googleapis.com/ajax/libs/jquery/' . $jQueryVer . '/jquery.min.js');
			
			if ($noConflict)
			{
				AriDocumentHelper::includeJsFile($baseUri . 'jquery.noconflict.js');
			}
		}
			
		AriDocumentHelper::includeJsFile($baseUri . 'jquery.easing.js');
		AriDocumentHelper::includeJsFile($baseUri . 'jquery.sexylightbox.min.js');
		
		$lang =& JFactory::getLanguage();
		if ($lang->isRTL())
			AriDocumentHelper::includeCssFile($baseUri . 'sexylightbox_rtl.css');
		
		AriDocumentHelper::includeCustomHeadTag(sprintf('<!--[if lt IE 7]><link rel="stylesheet" href="%s" type="text/css" /><![endif]-->',
			$baseUri . 'sexylightbox.ie6.css'));
		AriDocumentHelper::includeCustomHeadTag(sprintf('<link rel="stylesheet" href="%s" type="text/css" />',
			$baseUri . 'sexylightbox.css'));

		AriDocumentHelper::includeCustomHeadTag(sprintf('<!--[if IE]><link rel="stylesheet" href="%s" type="text/css" /><![endif]-->',
			$baseUri . 'sexylightbox.ie.css'));			

		$jsOptions = AriSexyLightboxHelper::getJsOptions($options);
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration(sprintf(';(window["jQueryASL"] || jQuery)(document).ready(function(){ SexyLightbox.initialize(%s); });',
			!empty($jsOptions) ? AriJSONHelper::encode($jsOptions) : ''));
			
		$loaded = true;
	}
	
	function includeTools()
	{
		$baseUri = JURI::root(true) . '/modules/mod_arisexylightbox/includes/js/';
		
		AriDocumentHelper::includeJsFile($baseUri . 'tools.js');
	}
	
	function activateAutoShow($group = '', $autoShow = true, $uniqueKey = false, $cookie = null)
	{
		if ($autoShow != 'first')
			$autoShow = AriUtils2::parseValueBySample($autoShow, false);
		
		if (!$autoShow)
			return ;
			
		static $loaded;
		
		if ($loaded)
			return ;

		if ('first' === $autoShow && !empty($uniqueKey))
		{
			$cookieKey = 'asl_' . $uniqueKey;
			$cookieDuration = intval(AriUtils2::getParam($cookie, 'duration', 365), 10) * 24 * 60 * 60;

			if ($cookieDuration == 0)
			{
			 	if (!empty($_SESSION[$cookieKey]))
					return ;
					
				$_SESSION[$cookieKey] = true;
			}	
			else
			{
				if (!empty($_COOKIE[$cookieKey]))
					return ;

				setcookie($cookieKey, '1', time() + $cookieDuration);
			}
		}
		
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration(sprintf(';(window["jQueryASL"] || jQuery)(document).ready(function($){ $("a[rel^=\'%1$s\']").eq(0).click(); });',
			$group 
				? 'sexylightbox[' . $group . ']'
				: 'sexylightbox'));
		
		$loaded = true;
	}
	
	function activateAutoShowOnClose($group = '', $message = '', $autoShow = true, $uniqueKey = false, $cookie = null)
	{
		if ($autoShow != 'first')
			$autoShow = AriUtils2::parseValueBySample($autoShow, false);
		
		if (!$autoShow)
			return ;
			
		static $loaded;
		
		if ($loaded)
			return ;

		if ('first' === $autoShow && !empty($uniqueKey))
		{
			$cookieKey = 'asl_' . $uniqueKey;
			$cookieDuration = intval(AriUtils2::getParam($cookie, 'duration', 365), 10) * 24 * 60 * 60;

			if ($cookieDuration == 0)
			{
			 	if (!empty($_SESSION[$cookieKey]))
					return ;
					
				$_SESSION[$cookieKey] = true;
			}	
			else
			{
				if (!empty($_COOKIE[$cookieKey]))
					return ;

				setcookie($cookieKey, '1', time() + $cookieDuration);
			}
		}
		
		AriSexyLightboxHelper::includeTools();
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration(sprintf('ARISexyLightboxTools.initBeforeUnload("a[rel^=\'%1$s\']:eq(0)", "%2$s");',
			$group 
				? 'sexylightbox[' . $group . ']'
				: 'sexylightbox',
			addcslashes($message, '"')));
		
		$loaded = true;
	}
	
	function getJsOptions($overrideOptions = array())
	{
		$defOptions = array(
			'showDownloadLink' => false,
			'hoverDownloadLink' => true,
			'downloadLinkTitle' => 'Open the image in new window',
			'disableRightClick' => false,
			'enableShow' => false,
			'pauseDuration' => 5000,
			'autoStart' => false,
			'continiousShow' => false,
			'continious' => false,
			'find' => 'sexylightbox',
			'zIndex' => 32000,
			'color' => 'black',
			'emergefrom' => 'top',
			'showDuration' => 200,
			'closeDuration' => 400,
			'moveDuration' => 1000,
			'moveEffect' => 'easeInOutBack',
			'resizeDuration' => 1000,
			'resizeEffect' => 'easeInOutBack',
			'movieAutoPlay' => false,
			'wMode' => 'transparent',
			'shake' => array(
				'distance' => 10,
                'duration' => 100,
                'loops' => 2,
                'transition' => 'easeInOutBack'
            )
		);

		$jsOptions = AriParametersHelper::getUniqueOverrideParameters($defOptions, $overrideOptions);
		$jsOptions['dir'] = str_replace(' ', '%20', JURI::root(true)) . '/modules/mod_arisexylightbox/includes/js/sexyimages';
		
		if (!empty($overrideOptions['overlayStyle']))
		{
			$fixOverlayStyle = array();
			$overlayStyle = $overrideOptions['overlayStyle'];
			foreach ($overlayStyle as $key => $value)
			{
				if (!is_null($value) && strlen($value) > 0)
					$fixOverlayStyle[$key] = $value;
			}
			
			$jsOptions['overlayStyle'] = $fixOverlayStyle;
		}

		return $jsOptions;
	}
	
	function executeModel($model, $params, $templatePath)
	{
		$modelParams = isset($params[$model]) ? $params[$model] : null;
		$filter = JFilterInput::getInstance();
		$model = ucfirst($filter->clean($model, 'WORD'));
		if (empty($model))
		{
			AriSexyLightboxHelper::executeErrorModel($params, $templatePath);
			return ;
		}
		else
		{
			$modelName = 'SexyLightbox' . $model . 'Model';
			$modelPath = dirname(__FILE__) . DS . 'Models' . DS . 'class.' . $modelName . '.php';

			if (!@file_exists($modelPath))
			{
				AriSexyLightboxHelper::executeErrorModel($params, $templatePath);
				return ;
			}
			else
			{
				AriKernel::import('SexyLightbox.Models.' . $modelName);

				$modelClass = 'Ari' . $modelName;
				$oModel = new $modelClass();
				$oModel->execute($modelParams, $params, $templatePath);
			}
		}
	}
	
	function executeErrorModel($params, $templatePath)
	{
		AriKernel::import('SexyLightbox.Models.SexyLightboxErrorModel');
		
		$errorModel = new AriSexyLightboxErrorModel();
		$errorModel->execute(null, $params, $templatePath);
	}
}
?>