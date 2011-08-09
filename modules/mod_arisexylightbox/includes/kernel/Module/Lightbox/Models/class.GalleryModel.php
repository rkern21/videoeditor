<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Module.Model');
AriKernel::import('Web.Controls.Repeater');
AriKernel::import('Utils.Utils');
AriKernel::import('Web.HtmlHelper');

class AriGalleryModel extends AriModuleModelBase
{
	function execute($data, $rptTemplates, $modelParams, $params, $templatePath)
	{
		$rptTemplate = '';
		$templatePath = dirname(__FILE__) . DS . 'templates' . DS . 'gallery' . DS;
		$invisibleData = null;
		switch ($modelParams['type'])
		{
			case 'gallery':
				$templateParams = $modelParams['simplegallery'];
				$visibleItemCount = intval(AriUtils::getParam($templateParams, 'visibleItemCount', 0), 10);
				if ($visibleItemCount > 0)
				{
					$data = array_values($data);
					$invisibleData = array_slice($data, $visibleItemCount);
					$data = array_slice($data, 0, $visibleItemCount);
				}
				
				$id = uniqid('sg_', false);
				$templateParams['id'] = $id;

				$cssStyles = '';
				$galleryTitle = AriUtils::getParam($templateParams, 'titleParam', 'Title');
				if ($galleryTitle)
				{
					if (strpos($galleryTitle, '{$') === false)
						$galleryTitle = '{$data:' . $galleryTitle . '}';
					else
						$galleryTitle = preg_replace('/\{\$([^}]+)\}/i', '{$data:$1}', $galleryTitle);
				}
				else 
					$galleryTitle = '';

				$templateParams['GalleryCaption'] = !isset($templateParams['showTitle']) || AriUtils::parseValueBySample($templateParams['showTitle'], false) 
					? $galleryTitle
					: '';
				
				if (isset($templateParams['showTitle']) && empty($templateParams['showTitle']))
				{
					$cssStyles .= '#' . $id . ' .afancybox-title{display:none;}';
				}

				$document =& JFactory::getDocument();
				if ($cssStyles)
				{
					$document->addStyleDeclaration($cssStyles);
				}

				$theme = AriUtils2::getParam($templateParams, 'theme');
				if (!empty($theme) && $theme != 'none')
				{
					if (empty($templateParams['mainClass']))
						$templateParams['mainClass'] = '';

					$theme = explode(' ', $theme);
					foreach ($theme as $partTheme)
					{  
						$templateParams['mainClass'] .= ' ari-theme-' . $partTheme;
					}
				}

				$rptTemplate = AriSimpleTemplate::parse(
					AriUtils2::getParam($rptTemplates, 'simpleGallery'), 
					$templateParams);
				break;
				
			case 'advgallery':
				$rptTemplate = str_replace(chr(194) . chr(160), ' ', $modelParams['advgallerytemplate']);
				break;
				
			case 'singleimage':
				$rptTemplate = AriUtils2::getParam($rptTemplates, 'singleGallery');
				break;
				
			case 'customtext':
				$rptTemplate = AriUtils2::getParam($rptTemplates, 'singleGallery');
				break;
				
			case 'slickgallery':
				$rptTemplate = AriUtils2::getParam($rptTemplates, 'slickGallery');
				break;
		}

		if ($modelParams['type'] == 'singleimage')
		{
			$template = $modelParams['singletemplate'];
			$firstDataItem = array_shift($data);

			$repeater = new AriRepeaterWebControl($rptTemplate, $data);
			AriTemplate::display(
				$templatePath . 'singleimage.html.php', 
				array('repeater' => $repeater, 'firstImage' => AriSimpleTemplate::parse($template, array('data' => $firstDataItem))));
		}
		else if ($modelParams['type'] == 'customtext')
		{
			$repeater = new AriRepeaterWebControl($rptTemplate, $data);
			AriTemplate::display(
				$templatePath . 'customtext.html.php', 
				array('repeater' => $repeater, 'template' => $modelParams['customtemplate']));
		}
		else if ($modelParams['type'] == 'slickgallery')
		{
			$slickGalleryPath = JPATH_ROOT . DS . 'modules' . DS . 'mod_arislickgallery' . DS . 'mod_arislickgallery' . DS . 'kernel' . DS . 'class.AriKernel.php';
			if (@!file_exists($slickGalleryPath))
			{
				echo "<div style='color:red;font-weight:bold;'>Install 'ARI Slick Gallery' extension that use 'Slick gallery' layout type.</div>";
				return ;
			}
			
			$slickParams = $modelParams['slickgallery'];
			$repeater = new AriRepeaterWebControl(
				$rptTemplate, 
				AriGalleryModel::prepareForSlickGallery(
					$data,
					$slickParams
				)
			);
			AriTemplate::display(
				$templatePath . 'slickgallery.html.php', 
				array(
					'repeater' => $repeater,
					'params' => $slickParams
				));
		}
		else 
		{
			$repeater = new AriRepeaterWebControl($rptTemplate, $data);
			AriTemplate::display(
				$templatePath . 'gallery.html.php', 
				array('repeater' => $repeater));
		}
		
		if ($invisibleData)
		{
			$repeater = new AriRepeaterWebControl(AriUtils2::getParam($rptTemplates, 'hiddenItems'), $invisibleData);
			$repeater->render();
		}
	}
	
	function prepareForSlickGallery($data, $params)
	{
		if (empty($data))
			return $data;

		$startDegree = intval($params['startDegree'], 10); 
		$endDegree = intval($params['endDegree'], 10);
		$operaSupport = (bool)$params['operaSupport'];
		
		$width = intval($params['width'], 10);
		$height = intval($params['height'], 10);
			
		foreach ($data as $key => $dataItem)
		{
			$isComplexThumb = is_object($dataItem['thumb']) || is_array($dataItem['thumb']);
			$thumb = $isComplexThumb ? $dataItem['thumb']['url'] : $dataItem['thumb'];
			$thumbSize = $isComplexThumb ? $dataItem['thumb'] : $dataItem;			
			
			$slideWidth = !empty($thumbSize['w']) ? $thumbSize['w'] : 0;
			$slideHeight = !empty($thumbSize['h']) ? $thumbSize['h'] : 0;
			
			$left = rand(0, $width - ($slideWidth ? 1.42 * $slideWidth : 0));
        	$top = rand(0, $height - ($slideHeight ? 1.42 * $slideHeight : 0));
        	$rotateDegree = rand($startDegree, $endDegree);
			
			$style = array(
				'background-image' => 'url(' . $thumb . ')',
				'top' => $top . 'px',
				'left' => $left . 'px',
				'-moz-transform' => 'rotate(' . $rotateDegree . 'deg)',
				'-webkit-transform' => 'rotate(' . $rotateDegree . 'deg)',
				'transform' => 'rotate(' . $rotateDegree . 'deg)'
			);
			if ($operaSupport) 
				$style['-o-transform'] = 'rotate(' . $rotateDegree . 'deg)';
				
			if ($slideWidth) 
				$style['width'] = $slideWidth . 'px';
			if ($slideHeight) 
				$style['height'] = $slideHeight . 'px';

			$data[$key]['style'] = AriHtmlHelper::getAttrStr(array('style' => $style));
		}
		
		return $data;
	}
}
?>