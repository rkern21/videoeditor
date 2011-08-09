<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Image.Asido.AsidoHelper');
AriKernel::import('Utils.Utils');

class AriImageHelper extends AriObject
{
	function getThumbnailDimension($path, $w, $h, $type = null)
	{
		$dim = array('w' => $w, 'h' => $h);
		if (($w && $h && $type != 'fit') || (!$w && !$h)) return $dim;

		if (@is_readable($path) && function_exists('getimagesize'))
		{
			$info = @getimagesize($path);
			if (!empty($info) && count($info) > 1)
			{
				if (empty($w))
				{
					$w = round($h * $info[0] / $info[1]);
					$dim['w'] = $w;
				}
				else if (empty($h))
				{
					$h = round($w * $info[1] / $info[0]);
					$dim['h'] = $h;
				}
				else if ($type == 'fit')
				{
					$wSize = AriImageHelper::getThumbnailDimension($path, $w, 0);
					$hSize = AriImageHelper::getThumbnailDimension($path, 0, $h);

					$dim = ($wSize['h'] > $h) ? $hSize : $wSize;
				}
			}
		}
		
		return $dim;
	}
	
	function generateThumbnailFileName($prefix, $originalImgPath, $width, $height, $type = 'resize')
	{
		if ($type == 'resize')
			$type = '';
		
		$path_parts = pathinfo($originalImgPath);
		return sprintf('%s_%s_%s_%s.%s',
					$prefix,
					md5($originalImgPath . $type),
					$width,
					$height,
					$path_parts['extension']);
	}
	
	function generateThumbnail($originalImgPath, $thumbDir, $prefix, $thumbWidth = 0, $thumbHeight = 0, $type = 'resize', $typeParams = array(), $filters = array())
	{
		$thumbBehavior = $type == 'resize' ? AriUtils::getParam($typeParams, 'behavior') : null;
		$thumbSize = AriImageHelper::getThumbnailDimension($originalImgPath, $thumbWidth, $thumbHeight, $thumbBehavior);
		if (!$thumbSize['w'] || !$thumbSize['h'] || !@is_readable($originalImgPath))
			return null;

		$width = $thumbSize['w'];
		$height = $thumbSize['h'];
		$path_parts = pathinfo($originalImgPath);

		$thumbName = AriImageHelper::generateThumbnailFileName($prefix, $originalImgPath, $width, $height, $type);
		$thumbImgPath = $thumbDir . DS . $thumbName;
		if (@file_exists($thumbImgPath) && @filemtime($thumbImgPath) > @filemtime($originalImgPath))
			return $thumbName;

		if (!AriImageHelper::initAsido())
			return ;
		$thumbImg = Asido::image($originalImgPath, $thumbImgPath);
		$needResize = true;		
		if ($type == 'resize')
		{
			if ($thumbWidth > 0 && $thumbHeight > 0)
			{
				$behavior = AriUtils::getParam($typeParams, 'behavior');
				if ($behavior == 'crop')
				{
					$wSize = AriImageHelper::getThumbnailDimension($originalImgPath, $thumbWidth, 0);
					$hSize = AriImageHelper::getThumbnailDimension($originalImgPath, 0, $thumbHeight);

					$thumbSize = ($wSize['h'] > $thumbHeight) ? $wSize : $hSize;
					Asido::resize($thumbImg, $thumbSize['w'], $thumbSize['h'], ASIDO_RESIZE_STRETCH);
					Asido::crop(
						$thumbImg,
						floor(($thumbSize['w'] - $thumbWidth) / 2),
						floor(($thumbSize['h'] - $thumbHeight) / 2),  
						$thumbWidth,
						$thumbHeight
					);

					$needResize = false;
				}
			}
		}
		else if ($type == 'crop')
		{
			Asido::crop(
				$thumbImg,
				intval(AriUtils::getParam($typeParams, 'x', 0), 10),
				intval(AriUtils::getParam($typeParams, 'y', 0), 10),
				$width ? $width : $height,
				$height ? $height : $width
			);
			$needResize = false;
		}
		else if ($type == 'cropresize')
		{
			Asido::crop(
				$thumbImg,
				intval(AriUtils::getParam($typeParams, 'x', 0), 10),
				intval(AriUtils::getParam($typeParams, 'y', 0), 10),
				intval(AriUtils::getParam($typeParams, 'width', 0), 10),
				intval(AriUtils::getParam($typeParams, 'height', 0), 10)
			);
		}

		if ($filters)
		{
			if (AriUtils::parseValueBySample(
					AriUtils::getParam($filters, 'grayscale', false),
					false)
				)
			{
				Asido::grayscale($thumbImg);
			}
			
			$rotateFilter = AriUtils::getParam($filters, 'rotate');
			if (is_array($rotateFilter) && 
				AriUtils::parseValueBySample(
					AriUtils::getParam($rotateFilter, 'enable', false),
					false)
				)
			{
				$angle = 0;
				$rotateType = AriUtils::getParam($rotateFilter, 'type', 'fixed');
				if ($rotateType == 'random')
				{
					$startAngle = intval(AriUtils::getParam($rotateFilter, 'startAngle', 0), 10);
					$endAngle = intval(AriUtils::getParam($rotateFilter, 'endAngle', 0), 10);
					$angle = rand($startAngle, $endAngle);
				}
				else
				{
					$angle = intval(AriUtils::getParam($rotateFilter, 'angle', 0), 10);
				}

				$angle = $angle % 360;
				if ($angle != 0)
				{
					Asido::rotate($thumbImg, $angle);
				}
			}
		}

		if ($needResize)
		{
			if (!$width)
				Asido::height($thumbImg, $height);
			else if (!$height)
				Asido::width($thumbImg, $width);
			else
				Asido::resize($thumbImg, $width, $height, ASIDO_RESIZE_STRETCH);
		}

		$thumbImg->save(ASIDO_OVERWRITE_ENABLED);
		
		return $thumbName;
	}
	
	function initAsido()
	{
		static $initialized;
		
		if (is_null($initialized))
			$initialized = AriAsidoHelper::init();

		return $initialized;
	}
}
?>