<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Image.ImageHelper');
AriKernel::import('Utils.Utils2');
AriKernel::import('Web.HtmlHelper');

class AriInlineThumbnailProvider extends AriObject
{
	var $_prefix;
	var $_cacheDir;

	function __construct($prefix = 'arithumb', $cacheDir = null, $ext = null)
	{
		if (is_null($cacheDir))
		{
			$cacheDir = JPATH_ROOT . DS . 'cache';
			
			if (!is_null($ext))
			{
				$extCacheDir = $cacheDir . DS . $ext;
			
				if (file_exists($extCacheDir) && is_dir($extCacheDir))
					$cacheDir = $extCacheDir;
			}
		}
		
		$this->_prefix = $prefix;
		$this->_cacheDir = $cacheDir;
	}
	
	function updateContent($content, $params, $updateCallback = null)
	{
		$params = $this->getCorrectedParameters($params);
		$images = $this->getImages($content, $params);
		if (is_null($updateCallback))
			$updateCallback = array(&$this, 'updateCallback');
			
		return call_user_func($updateCallback, $content, $images, $params);
	}

	function updateCallback($content, $images, $params)
	{
		$originalImages = array();
		$updatedImages = array();
		
		foreach ($images as $image)
		{
			$originalImage = $image['image'];
			$thumbImage = $image['thumb'];
			
			$originalImages[] = $originalImage['original'];
			$updatedImages[] = sprintf('<a %1$s><img %2$s /></a>',
				AriHtmlHelper::getAttrStr($originalImage['attributes']),
				AriHtmlHelper::getAttrStr($thumbImage['attributes']));
		}

		return str_replace($originalImages, $updatedImages, $content);
	}
	
	function getImages($content, $params)
	{
		$images = array();
		$matches = array();
		$clearContent = strip_tags($content, '<img>');
		preg_match_all('/<img.*?>/i', $clearContent, $matches);
 		if (!empty($matches[0]))
 		{
 			$prefix = $this->_prefix;
 			$cacheDir = $this->_cacheDir;
 			$cacheUri = $cacheDir;
			if (strpos($cacheUri, JPATH_ROOT . DS) === 0)
				$cacheUri = substr($cacheUri, strlen(JPATH_ROOT . DS));
			$cacheUri = str_replace(DS, '/', $cacheUri) . '/';
 			
			$i = 0;
			$thumbCount = $params['thumbCount'];
 			$generateThumbs = $params['generateThumbs'];
 			$thumbType = $params['thumbType'];
			$thumbTypeParams = $params['thumbTypeParams'];
			$thumbFilters = $params['thumbFilters'];
			$ignoreEmptyDim = $params['ignoreEmptyDim'];
			$ignoreRemote = $params['ignoreRemote'];
 			$thumbAttrs = null;
 			foreach ($matches[0] as $match)
 			{
 				$attrs = AriHtmlHelper::extractAttrs($match);
 				$src = AriUtils2::getParam($attrs, 'src', '');
 				if (empty($src))
 					continue ;

 				if ($ignoreRemote && strpos($src, 'http') === 0 && strpos($src, JURI::root()) === false)
 					continue ;

 				$thumbWidth = $params['thumbWidth'];
 				$thumbHeight = $params['thumbHeight'];
 				
 				if (!empty($attrs['style']) || !empty($attrs['width']) || !empty($attrs['height']))
 				{
 					$imgStyles = !empty($attrs['style']) ? AriHtmlHelper::extractInlineStyles($attrs['style']) : null;
 					$styleWidth = isset($imgStyles['width']) ? intval($imgStyles['width'], 10) : 0;
 					$styleHeight = isset($imgStyles['height']) ? intval($imgStyles['height'], 10) : 0;
 					if (!empty($styleWidth))
 						$thumbWidth = $styleWidth;
 					else if (!empty($attrs['width']))
 						$thumbWidth = @intval($attrs['width'], 10);
 						
 					if (!empty($styleHeight))
 						$thumbHeight = $styleHeight;
 					else if (!empty($attrs['height']))
 						$thumbHeight = @intval($attrs['height'], 10);
 				}

 				$title = AriUtils2::getParam($attrs, 'alt',
 					AriUtils2::getParam($attrs, 'title', ''));
 				$thumbAttrs = array('alt' => $title);
 				$imgAttrs = array('title' => $title, 'href' => $src);
 				if ($params['class'])
 					$imgAttrs['class'] = $params['class'];
 				$image = array(
 					'image' => array(
 						'original' => $match,
 						'originalAttributes' => $attrs,
 						'attributes' => null,
 						'title' => $title,
 						'src' => $src),
 					'thumb' => array(
 						'src' => $src,
 						'width' => $thumbWidth,
 						'height' => $thumbHeight,
 						'atttributes' => null,
 						'asOriginal' => false
 					)
 				);

 				$thumbSrc = $src;
 				if ($generateThumbs && ($thumbCount < 1 || $i < $thumbCount))
 				{
	 				$imgPath = $src;
	 				$baseUrl = strtolower(JURI::base());
	 				if (strpos(strtolower($imgPath), $baseUrl) === 0)
	 					$imgPath = substr($imgPath, strlen($baseUrl));

	 				if (!preg_match('/^(http|https|ftp):\/\//i', $imgPath))
	 				{
	 					$imgPath = JPATH_ROOT . DS . str_replace('/', DS, $imgPath);
	 					$originalSize = @getimagesize($imgPath);
	 					if ((!$ignoreEmptyDim || isset($attrs['width']) || isset($attrs['height'])) &&
	 						(!is_array($originalSize) || count($originalSize) < 2 || 
	 						(($thumbWidth > 0 && $originalSize[0] != $thumbWidth) ||
	 						($thumbHeight > 0 && $originalSize[1] != $thumbHeight))))
	 					{
		 					$thumbFile = AriImageHelper::generateThumbnail(
		 						$imgPath, 
		 						$cacheDir, 
		 						$prefix, 
		 						$thumbWidth, 
		 						$thumbHeight,
		 						$thumbType,
		 						$thumbTypeParams,
		 						$thumbFilters);
		 					if ($thumbFile)
		 					{
		 						$size = @getimagesize($cacheDir . DS . $thumbFile);
		 						if (is_array($size) && count($size) > 1)
		 						{
		 							$image['thumb']['width'] = $size[0];
		 							$image['thumb']['height'] = $size[1];
		 						}
	
		 						$image['thumb']['src'] = $cacheUri . $thumbFile;
		 					}
	 					}
	 					else
	 					{
	 						$image['thumb']['asOriginal'] = true;
	 					}
	 				}
 				}
 				
 				$thumbAttrs['src'] = $image['thumb']['src'];
 				if ($image['thumb']['width'] > 0)
 					$thumbAttrs['width'] = $image['thumb']['width'];
 				if ($image['thumb']['height'] > 0)
 					$thumbAttrs['height'] = $image['thumb']['height'];
 				if (isset($attrs['border']))
 					$thumbAttrs['border'] = $attrs['border'];
 				$image['thumb']['attributes'] = $thumbAttrs;
 				$image['image']['attributes'] = $imgAttrs;
 				
 				$images[] = $image;
 				++$i;
 			}
 		}

 		return $images;
	}
	
	function getCorrectedParameters($params)
	{
		$clearParams = array(
			'thumbWidth' => 150,
			'thumbHeight' => 0,
			'generateThumbs' => true,
			'thumbCount' => 0,
			'single' => false,
			'class' => '',
			'groupName' => uniqid('app_', false),
			'ignoreEmptyDim' => false,
			'ignoreRemote' => false
		);
		
		$correctedParams = array();
		foreach ($clearParams as $key => $value)
		{
			$correctedParams[$key] = AriUtils2::parseValueBySample(
				AriUtils2::getParam($params, $key, $value),
				$value);			
		}
		
		$correctedParams = array_merge($correctedParams, array_diff($params, $correctedParams));
		unset($correctedParams['content']);
		
		$thumbType = strtolower(AriUtils2::getParam($params, 'thumbType', 'resize'));
		if (!in_array($thumbType, array('resize', 'crop', 'cropresize')))
			$thumbType = 'resize';
			
		$thumbTypeParamKey = 'thumbType' . ucfirst($thumbType);
		$correctedParams['thumbTypeParams'] = AriUtils2::getParam($params, $thumbTypeParamKey, array());
		$correctedParams['thumbType'] = $thumbType;
		if (!isset($correctedParams['thumbFilters']))
			$correctedParams['thumbFilters'] = array();
		
		return $correctedParams;
	}
}
?>