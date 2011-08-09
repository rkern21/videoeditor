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

AriKernel::import('Module.Providers.InlineThumbnailProvider');
AriKernel::import('SexyLightbox.Models.SexyLightboxModel');

class AriSexyLightboxInlineimgModel extends AriSexyLightboxModel
{
	var $_params;
	
	function execute($modelParams, $params, $templatePath)
	{
		$inlineThumb = new AriInlineThumbnailProvider('arithumb', null, 'mod_arisexylightbox');
		$this->_params = $params;
		$modelParams['content'] = $inlineThumb->updateContent($modelParams['content'], $modelParams, array(&$this, 'inlineGalleryUpdateCallback'));
		
		parent::execute($modelParams, $params, $templatePath);
	}

	function inlineGalleryUpdateCallback($content, $images, $params)
	{
		$thumbCount = $params['thumbCount'];
		$single = $params['single'];
		$groupName = !$single ? AriUtils2::getParam($this->_params['_default'], 'groupName') : null;
		$modal = AriUtils2::parseValueBySample($this->_params['_default']['modal'], false);
		for ($i = 0; $i < count($images); $i++)
		{
			$relAttr = 'sexylightbox' . ($groupName ? '[' . $groupName . ']' : '');
			$image =& $images[$i];
			$image['image']['attributes']['rel'] = $relAttr;
			$image['image']['attributes']['src'] = $image['image']['src'] . ($modal ? '?modal=1' : '');
			if ($thumbCount > 0 && $i >= $thumbCount)
				$image['image']['attributes']['style'] = 'display: none;';
		}

		return AriInlineThumbnailProvider::updateCallback($content, $images, $params);
	}	
}
?>