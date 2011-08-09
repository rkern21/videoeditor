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

AriKernel::import('Module.Providers.FlickrProvider');
AriKernel::import('Module.Lightbox.Models.GalleryModel');
AriKernel::import('SexyLightbox.Models.Templates.SimpleGalleryTemplate');
AriKernel::import('Template.Template');

class AriSexyLightboxFlickrModel extends AriGalleryModel
{
	var $_prefix = 'AriSexyLightbox';

	function execute($modelParams, $params, $templatePath)
	{
		$flickrProvider = new AriFlickrProvider($modelParams, null, 'mod_arisexylightbox');
		$flickrData = $this->getData(
			$flickrProvider->getData($modelParams),
			$modelParams, 
			$params);
		
		if ($modelParams['type'] == 'customtext')
		{
			$cId = uniqid('asb_', false);
			$modelParams['customtemplate'] = '<div id="' . $cId . '" style="cursor: pointer;">' . $modelParams['customtemplate'] . '</div>';
			$document =& JFactory::getDocument();
			$document->addScriptDeclaration(sprintf('jQuery(document).ready(function($){ $("#%1$s").click(function(event) { $("#%1$s").closest(".ari_lightbox_container").find("a[rel^=\'sexylightbox\']").eq(0).click(); }); });',
				$cId));			
		} 
		else if ($modelParams['type'] == 'flickrimage')
		{
			$photoId = $modelParams['flickrimage']['photoId'];
			$photo = AriUtils::getParam($flickrData['photos'], $photoId);
			$repeater = new AriRepeaterWebControl(ASEXYBOX_SINGLEIMAGEGALLERYTEMPLATE, $flickrData['photos']);

			AriTemplate::display(
				$templatePath . 'flickrimage.html.php', 
				array(
					'repeater' => $repeater, 
					'template' => AriSimpleTemplate::parse($modelParams['flickrimage']['template'], array('data' => $photo))
				)
			);
			return ;
		}
		else if ($modelParams['type'] == 'flickrphotosets')
		{
			$captionTemplate = AriUtils2::getParam($modelParams, 'caption', '{$Title}');
			$needParseTemplate = strpos($captionTemplate, '{$') !== false;
			
			$firstPhotosetsPhoto = array();
			$photosetsPhoto = array();
			$modal = AriUtils2::parseValueBySample($params['_default']['modal'], false);
			
			foreach ($flickrData['photos'] as $dataItem)
			{
				$photosetId = $dataItem['photosetId'];
				$title = $needParseTemplate
					? AriSimpleTemplate::parse($captionTemplate, $dataItem, true)
					: $captionTemplate;
				if (!isset($firstPhotosetsPhoto[$photosetId]))
				{
					$photoset = AriUtils2::getParam($flickrData['photosets'], $photosetId);
					$aAttrs = array('href' => $dataItem['imgUrl'] . ($modal ? '?modal=1' : ''), 'rel' => 'sexylightbox[ps_' . $photosetId . ']', 'title' => $title);
					$imgAttrs = array('src' => $dataItem['thumbUrl'], 'border' => '0', 'alt' => $dataItem['Title']);
					$dataItem['Caption'] = AriUtils2::getParam($photoset, 'title', '');
					$dataItem['sexyimage'] = sprintf('<a%1$s><img%2$s/></a>',
						AriHtmlHelper::getAttrStr($aAttrs),
						AriHtmlHelper::getAttrStr($imgAttrs));
					$firstPhotosetsPhoto[$photosetId] = $dataItem;
				}
				else
				{
					$aAttrs = array('href' => $dataItem['imgUrl'] . ($modal ? '?modal=1' : ''), 'rel' => 'sexylightbox[ps_' . $photosetId . ']', 'title' => $title);
					$dataItem['sexyimage'] = sprintf('<a%1$s></a>',
						AriHtmlHelper::getAttrStr($aAttrs));
					$photosetsPhoto[] = $dataItem;
				}
			}
			
			$showTitle = (bool)$modelParams['flickrphotosets']['showTitle'];
			$rptParams = $modelParams['flickrphotosets'];
			$rptParams['GalleryCaption'] = $showTitle ? '{$data:Caption}' : '';
			$repeater = new AriRepeaterWebControl(
				AriSimpleTemplate::parse(
					ASEXYBOX_SIMPLEGALLERYTEMPLATE,
					$rptParams
				), $firstPhotosetsPhoto);
			AriTemplate::display(
				$templatePath . 'flickrphotosets.html.php', 
				array(
					'repeater' => $repeater,
					'photos' => $photosetsPhoto
				)
			);
			return ;
		}
		
		parent::execute(
			$flickrData['photos'], 
			array(
				'simpleGallery' => ASEXYBOX_SIMPLEGALLERYTEMPLATE,
				'singleGallery' => ASEXYBOX_SINGLEIMAGEGALLERYTEMPLATE,
				'hiddenItems' => ASEXYBOX_HIDDENITEMSTEMPLATE,
				'slickGallery' => ASEXYBOX_SLICKGALLERYTEMPLATE),
			$modelParams,
			$params,
			$templatePath);
	}
	
	function getData($flickrData, $modelParams, $params)
	{
		$data = array('photos' => array(), 'photosets' => array());
		if (empty($flickrData))
			return $data;
		
		$group = $params['_default']['groupName'];
		if (empty($group)) $group = uniqid('asexy_');
		$modal = AriUtils2::parseValueBySample($params['_default']['modal'], false);
		
		$captionTemplate = AriUtils2::getParam($modelParams, 'caption', '{$Title}');
		$needParseTemplate = strpos($captionTemplate, '{$') !== false;

		$photos = AriUtils2::getParam($flickrData, 'photos', $flickrData);
		foreach ($photos as $key => $value)
		{
			$dataItem = $value;

			$title = $needParseTemplate
				? AriSimpleTemplate::parse($captionTemplate, $dataItem, true)
				: $captionTemplate;
			$aAttrs = array('href' => $dataItem['imgUrl'] . ($modal ? '?modal=1' : ''), 'rel' => 'sexylightbox[' . $group . ']', 'title' => $title);
			$imgAttrs = array('src' => $dataItem['thumbUrl'], 'border' => '0', 'alt' => $dataItem['Title']);

			$dataItem['thumb'] = $dataItem['thumbUrl'];
			$dataItem['sexyimage'] = sprintf('<a%1$s><img%2$s/></a>',
					AriHtmlHelper::getAttrStr($aAttrs),
					AriHtmlHelper::getAttrStr($imgAttrs));
					
			$data['photos'][$key] = $dataItem;
		}
		
		$data['photosets'] = AriUtils2::getParam($flickrData, 'photosets', array());

		return $data;
	}
}
?>