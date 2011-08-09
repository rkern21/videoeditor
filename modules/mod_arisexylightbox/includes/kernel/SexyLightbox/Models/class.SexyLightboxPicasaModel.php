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

AriKernel::import('Module.Providers.PicasaProvider');
AriKernel::import('Module.Lightbox.Models.GalleryModel');
AriKernel::import('SexyLightbox.Models.Templates.SimpleGalleryTemplate');

class AriSexyLightboxPicasaModel extends AriGalleryModel
{
	var $_prefix = 'AriSexyLightbox';
	
	function execute($modelParams, $params, $templatePath)
	{
		if (!$this->checkCompatibility())
			return ;
		
		$picasaProvider = new AriPicasaProvider($modelParams, null, 'mod_arisexylightbox');
		
		if ($modelParams['type'] == 'customtext')
		{
			$cId = uniqid('asb_', false);
			$modelParams['customtemplate'] = '<div id="' . $cId . '" style="cursor: pointer;">' . $modelParams['customtemplate'] . '</div>';
			$document =& JFactory::getDocument();
			$document->addScriptDeclaration(sprintf('jQuery(document).ready(function($){ $("#%1$s").click(function(event) { $("#%1$s").closest(".ari_lightbox_container").find("a[rel^=\'sexylightbox\']").eq(0).click(); }); });',
				$cId));			
		}
		
		$picasaData = $picasaProvider->getData($modelParams);
		if ($picasaProvider->getError())
		{
			echo '<div style="color: red; font-weight: bold;font-size:18px;">ARI Sexy Lightbox: ' . $picasaProvider->getError() . '</div>';
			return ;
		}

		parent::execute(
			$this->getData(
				$picasaData,
				$modelParams, 
				$params), 
			array(
				'simpleGallery' => ASEXYBOX_SIMPLEGALLERYTEMPLATE,
				'singleGallery' => ASEXYBOX_SINGLEIMAGEGALLERYTEMPLATE,
				'hiddenItems' => ASEXYBOX_HIDDENITEMSTEMPLATE,
				'slickGallery' => ASEXYBOX_SLICKGALLERYTEMPLATE),
			$modelParams,
			$params,
			$templatePath);
	}
	
	function getData($picasaData, $modelParams, $params)
	{
		$data = array();
		if (empty($picasaData))
			return $data;
		
		$group = $params['_default']['groupName'];
		if (empty($group)) $group = uniqid('asexy_');
		$modal = AriUtils2::parseValueBySample($params['_default']['modal'], false);
		$keepSize = AriUtils2::getParam($modelParams, 'keepSize', false);
		
		$captionTemplate = AriUtils2::getParam($modelParams, 'caption', '{$Title}');
		$needParseTemplate = strpos($captionTemplate, '{$') !== false;
		
		foreach ($picasaData as $key => $value)
		{
			$dataItem = $value;

			$dataItem['Title'] = AriUtils2::getParam($dataItem, 'summary', '');
			$title = $needParseTemplate
				? AriSimpleTemplate::parse($captionTemplate, $dataItem, true)
				: $captionTemplate;
			$img = $dataItem['image'];
			$thumb = $dataItem['thumb'];
			$aAttrs = array('href' => $img['url'] . ($modal ? '?modal=1' : ''), 'rel' => 'sexylightbox[' . $group . ']', 'title' => $title);
			$imgAttrs = array('src' => $thumb['url'], 'border' => '0', 'alt' => $dataItem['Title'], 'width' => $thumb['w'], 'height' => $thumb['h']);
			
			if ($keepSize)
			{
				$aAttrs['href'] .= $modal ? '&' : '?';
				$aAttrs['href'] .= 'width=' . $img['w'] . '&height=' . $img['h'];
			}

			$dataItem['sexyimage'] = sprintf('<a%1$s><img%2$s/></a>',
					AriHtmlHelper::getAttrStr($aAttrs),
					AriHtmlHelper::getAttrStr($imgAttrs));
					
			$data[$key] = $dataItem;
		}

		return $data;
	}
	
	function checkCompatibility()
	{
		if (version_compare(PHP_VERSION, '5.0.0') >= 0)
			return true;

		echo '<b style="color: red;">Picasa provider requires PHP v. 5.0.0 or above.</b>';
		
		return false;
	}
}
?>