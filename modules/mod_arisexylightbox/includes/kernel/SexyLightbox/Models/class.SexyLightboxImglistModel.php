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

AriKernel::import('Module.Providers.ThumbnailProvider');
AriKernel::import('Module.Lightbox.Models.GalleryModel');
AriKernel::import('SexyLightbox.Models.Templates.SimpleGalleryTemplate');

class AriSexyLightboxImglistModel extends AriGalleryModel
{
	var $_prefix = 'AriSexyLightbox';

	function execute($modelParams, $params, $templatePath)
	{
		$thumbProvider = new AriThumbnailProvider('arithumb', null, 'mod_arisexylightbox');
		$data = $thumbProvider->getStoredData($this->getThumbProviderParams($modelParams, $params));
		
		$this->modifyData($data, $params, $modelParams);
		
		if ($modelParams['type'] == 'customtext')
		{
			$cId = uniqid('asb_', false);
			$modelParams['customtemplate'] = '<div id="' . $cId . '" style="cursor: pointer;">' . $modelParams['customtemplate'] . '</div>';
			$document =& JFactory::getDocument();
			$document->addScriptDeclaration(sprintf('jQuery(document).ready(function($){ $("#%1$s").click(function(event) { $("#%1$s").closest(".ari_lightbox_container").find("a[rel^=\'sexylightbox\']").eq(0).click(); }); });',
				$cId));			
		}

		parent::execute(
			$data, 
			array(
				'simpleGallery' => ASEXYBOX_SIMPLEGALLERYTEMPLATE,
				'singleGallery' => ASEXYBOX_SINGLEIMAGEGALLERYTEMPLATE,
				'hiddenItems' => ASEXYBOX_HIDDENITEMSTEMPLATE,
				'slickGallery' => ASEXYBOX_SLICKGALLERYTEMPLATE),
			$modelParams,
			$params,
			$templatePath);
	}
	
	function getThumbProviderParams($modelParams, $params)
	{
		$thumbProviderParams = $modelParams;
		$thumbProviderParams['key'] = $params['_default']['key'];
		$thumbProviderParams['checkSum'] = $params['_default']['checkSum'];
		
		return $thumbProviderParams;
	}

	function modifyData(&$data, $params, $modelParams)
	{
		$rootUri = JURI::root(true) . '/';
		$keepSize = AriUtils2::getParam($modelParams, 'keepSize', false);
		$target = AriUtils2::getParam($modelParams, 'target');
		if ($target == '_self')
			$target = null;
		
		$group = $params['_default']['groupName'];
		if (empty($group)) $group = uniqid('asexy_');
		$modal = AriUtils2::parseValueBySample($params['_default']['modal'], false);
		
		$captionTemplate = AriUtils2::getParam($modelParams, 'caption', '{$Title}');
		$needParseTemplate = strpos($captionTemplate, '{$') !== false;
		
		foreach ($data as $key => $value)
		{
			$dataItem =& $data[$key];
			
			$img = $dataItem['image'];
			$title = $needParseTemplate
				? AriSimpleTemplate::parse($captionTemplate, $dataItem, true)
				: $captionTemplate;
			$aAttrs = array('href' => $rootUri . $img . ($modal ? '?modal=1' : ''), 'rel' => 'sexylightbox[' . $group . ']', 'title' => $title);
			if ($target)
				$aAttrs['target'] = $target;
			if ($keepSize)
			{
				$size = @getimagesize(JPATH_ROOT . DS . $img);
				if (!empty($size) &&  count($size) > 1)
				{
					 $aAttrs['href'] .= $modal ? '&' : '?';
					 $aAttrs['href'] .= 'width=' . $size[0] . '&height=' . $size[1];
				}
			}
			
			$imgAttrs = array('src' => $rootUri . $dataItem['thumb'], 'border' => '0', 'alt' => str_replace('"', '&qout;', AriUtils2::getParam($dataItem, 'Title', '')));
			if ($dataItem['w']) $imgAttrs['width'] = $dataItem['w'];
			if ($dataItem['h']) $imgAttrs['height'] = $dataItem['h'];
			if (!empty($dataItem['Link'])) $imgAttrs['longdesc'] = $dataItem['Link'];
			$dataItem['sexyimage'] = sprintf('<a%1$s><img%2$s/></a>',
				AriHtmlHelper::getAttrStr($aAttrs),
				AriHtmlHelper::getAttrStr($imgAttrs));
		}
	}	
}
?>