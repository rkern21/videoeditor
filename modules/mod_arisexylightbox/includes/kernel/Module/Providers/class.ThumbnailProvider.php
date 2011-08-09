<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Image.ImageHelper');
AriKernel::import('CSV.CSVParser');
AriKernel::import('SimpleTemplate.SimpleTemplate');
AriKernel::import('Utils.AppUtils');

jimport('joomla.filesystem.path');
jimport('joomla.filesystem.folder');

class AriThumbnailProvider extends AriObject
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
	
	function getStoredData($params)
	{
		$correctedParams = $this->getCorrectedParameters($params);
		$cachePeriod = $correctedParams['cachePeriod'];
		$data = null;

		if ($cachePeriod > 0)
		{
			$needReCache = true;
			$cacheDir = $this->_cacheDir;
			$key = $correctedParams['key'];
			$checkSum = $correctedParams['checkSum'];
			
			$cacheCheckFile = $cacheDir . DS . $key . '.txt';
			$cacheDataFile = $cacheDir . DS . $key . '.php';
			if (@file_exists($cacheCheckFile) && @file_exists($cacheDataFile))
			{
				$oldCheckSum = trim(@file_get_contents($cacheCheckFile));
				if ($oldCheckSum == $checkSum)
				{
					$needReCache = (filemtime($cacheCheckFile) + $cachePeriod * 60 < time());
				}
			}
			
			global $_THUMB_CACHED_DATA;
			if ($needReCache)
			{
				$this->prepare($correctedParams);
				$data = $this->getData($correctedParams);
				
				$h = fopen($cacheCheckFile, 'w');
				fwrite($h, $checkSum);
				fclose($h);
				
				if (!isset($_THUMB_CACHED_DATA)) $_THUMB_CACHED_DATA = array();
				
				$cachedData = var_export($data, true);
				$h = fopen($cacheDataFile, 'w');
				fwrite($h, sprintf('<?php%1$sdefined("ARI_FRAMEWORK_LOADED") or die("Direct Access to this location is not allowed.");%1$s$_THUMB_CACHED_DATA["%2$s"] = %3$s;?>',
					"\n",
					$key,
					$cachedData));
				fclose($h);
			}
			else 
			{
				require_once $cacheDataFile;

				$data = $_THUMB_CACHED_DATA[$key];
			}
		}
		else 
		{
			$this->prepare($correctedParams);
			$data = $this->getData($correctedParams);
		}

		if ($correctedParams['sortBy'] == 'random')
			shuffle($data);

		return $data;
	}
	
	function prepare($params)
	{		
		if ($params['generateThumbs'])
			$this->generateThumbnails($params);
	}

	function getData($params)
	{
		$data = array();
		
		$prefix = $this->_prefix;
		$descrFile = $params['descrFile'];
		$thumbPath = $params['thumbPath'];
		$cacheDir = $this->_cacheDir;
		$cacheUri = $cacheDir;
		if (strpos($cacheUri, JPATH_ROOT . DS) === 0)
			$cacheUri = substr($cacheUri, strlen(JPATH_ROOT . DS));
		$cacheUri = str_replace(DS, '/', $cacheUri);
		
		$folders = $params['folders'];
		$thumbWidth = $params['thumbWidth'];
		$thumbHeight = $params['thumbHeight'];
		$sortBy = $params['sortBy'];
		$sortDir = $params['sortDir'];
		$thumbType = $params['thumbType'];
		$thumbTypeParams = $params['thumbTypeParams'];
		$thumbBehavior = $thumbType == 'resize' ? AriUtils::getParam($thumbTypeParams, 'behavior') : null;
		$emptyTitle = $params['emptyTitle'];
		$needProcessEmptyTitle = ($emptyTitle && strpos($emptyTitle, '{$') !== false);

		foreach ($folders as $folder)
		{
			$descriptions = $this->getDescriptions($descrFile, $folder);
			$files = $this->getImageFiles($folder, $params['fileFilter']);
			$inCSV = false;
			$folderData = array();
			foreach ($files as $file)
			{
				$dataItem = null;
				$fileUri = str_replace(DS, '/', $file);
				$filePath = JPATH_ROOT . DS . $file;
				$baseFileName = basename($file);
				$thumbImagePath = $thumbPath
					? JPATH_ROOT . DS . $folder . DS . str_replace('{$fileName}', $baseFileName, $thumbPath)
					: null;
				if ($thumbImagePath && file_exists($thumbImagePath) && is_readable($thumbImagePath))
				{
					$thumbSize = getimagesize($thumbImagePath);
					$dataItem = array(
						'image' => $fileUri,
						'thumb' => str_replace(DS, '/', $folder . DS . str_replace('{$fileName}', $baseFileName, $thumbPath)),
						'w' => $thumbSize[0],
						'h' => $thumbSize[1]
					);
				}
				else
				{
					$thumbSize = AriImageHelper::getThumbnailDimension($filePath, $thumbWidth, $thumbHeight, $thumbBehavior);
					$dataItem = array(
						'image' => $fileUri,
						'thumb' => $fileUri,
						'w' => $thumbSize['w'],
						'h' => $thumbSize['h']
					);
					
					if ($thumbSize['w'] && $thumbSize['h'])
					{
						$thumbFile = AriImageHelper::generateThumbnailFileName($prefix, $filePath, $thumbSize['w'], $thumbSize['h'], $thumbType);
						if (@file_exists($cacheDir . DS . $thumbFile))
							$dataItem['thumb'] = $cacheUri . '/' . $thumbFile;
					}
				}

				if (isset($descriptions[$baseFileName]))
				{
					$dataItem = array_merge($descriptions[$baseFileName], $dataItem);
					$inCSV = true;
				}
				
				if ($emptyTitle && empty($dataItem['Title']))
				{
					$title = $emptyTitle;
					if ($needProcessEmptyTitle)
					{
						$pathInfo = pathinfo($baseFileName);
						
						$title = AriSimpleTemplate::parse(
							$title, 
							array(
								'fileName' => $baseFileName,
								'baseFileName' => basename($baseFileName, '.' . $pathInfo['extension'])
							)
						);
					}
					
					$dataItem['Title'] = $title;
				}
				
				$key = $this->getDataItemKey($filePath, $baseFileName, $sortBy, $inCSV);
				if (empty($key))
					$folderData[$baseFileName] = $dataItem;
				else
					$folderData[$key] = $dataItem;
			}
			
			if (count($folderData) == 0)
				continue ;
				
			if ($inCSV && $sortBy == 'csv')
			{
				$tempData = array();
				
				foreach ($descriptions as $fileName => $value)
				{
					if (!isset($folderData[$fileName]))
						continue ;
						
					$tempData[] = $folderData[$fileName];
					unset($folderData[$fileName]);
				}
				
				if ($sortDir == 'desc')
					$tempData = array_reverse($tempData);

				$folderData = array_values($folderData);
				$folderData = array_merge($tempData, array_values($folderData));
			}
			else if ($sortBy != 'filename' && $sortBy != 'modified')
			{
				$folderData = array_values($folderData);
			}
				
			$data = array_merge($data, $folderData);
		}
		
		if ($sortBy && $sortBy != 'random' && $sortBy != 'csv')
		{
			if ($sortDir == 'asc')
				ksort($data);
			else
				krsort($data); 
		}

		return $data;
	}
	
	function getDataItemKey($file, $baseFileName, $sortBy)
	{
		$key = null;
		switch ($sortBy)
		{
			case 'filename':
				$key = $baseFileName . md5($file);
				break;
				
			case 'modified':
				$key = filemtime($file) . md5($file);
				break;
		}
		
		return $key;
	}
	
	function getDescriptions($fileName, $path)
	{
		$data = array();
		$filePath = AriAppUtils::getLocalizedFileName($path . DS . $fileName);

		if (empty($fileName) || @!file_exists($filePath) || !@is_readable($filePath))
			return $data;

		$csvParser = new AriCSVParser();
		$csvParser->auto($filePath);
		$csvData = $csvParser->data;
		
		if (!empty($csvData))
		{
			foreach ($csvData as $csvDataItem)
			{
				if (isset($csvDataItem['File']))
					$data[$csvDataItem['File']] = $csvDataItem;
			}
		}

		return $data;
	}

	function generateThumbnails($params)
	{
		$thumbWidth = $params['thumbWidth'];
		$thumbHeight = $params['thumbHeight'];
		$folders = $params['folders'];
		$cacheDir = $this->_cacheDir;
		$prefix = $this->_prefix;
		$thumbPath = $params['thumbPath'];
		$thumbType = $params['thumbType'];
		$thumbTypeParams = $params['thumbTypeParams'];
		$thumbFilters = $params['thumbFilters'];

		foreach ($folders as $folder)
		{
			$files = $this->getImageFiles($folder, $params['fileFilter']);
			foreach ($files as $file)
			{
				$filePath = JPATH_ROOT . DS . $file;
				if ($thumbPath)
				{
					$thumbImagePath = JPATH_ROOT . DS . $folder . DS . str_replace('{$fileName}', basename($file), $thumbPath);
					if (file_exists($thumbImagePath))
						continue ;
				}

				AriImageHelper::generateThumbnail(
					$filePath, 
					$cacheDir, 
					$prefix, 
					$thumbWidth, 
					$thumbHeight,
					$thumbType,
					$thumbTypeParams,
					$thumbFilters);
			}
		}
	}
	
	function getImageFiles($folder, $filter = '\.(jpg|gif|jpeg|png|bmp|JPG|GIF|JPEG|BMP)$')
	{
		return JFolder::files($folder, $filter, false, true);
	}

	function getCorrectedParameters($params)
	{
		$thumbWidth = intval(AriUtils2::getParam($params, 'thumbWidth', 0), 10);
		if ($thumbWidth < 0) $thumbWidth = 0;

		$thumbHeight = intval(AriUtils2::getParam($params, 'thumbHeight', 0), 10);
		if ($thumbHeight < 0) $thumbHeight = 0;
		
		$cachePeriod = intval(AriUtils2::getParam($params, 'cachePeriod', 0), 10);
		if ($cachePeriod < 0) $cachePeriod = 0;
		
		$generateThumbs = (($thumbWidth || $thumbHeight) && AriAsidoHelper::isExtensionLoaded())
			? AriUtils2::parseValueBySample(AriUtils2::getParam($params, 'generateThumbs'), false)
			: false;

		$scanSubFolders = AriUtils2::parseValueBySample(AriUtils2::getParam($params, 'subdir'), false);
		$folders = $this->getFolders(trim(AriUtils2::getParam($params, 'dir', '')), $scanSubFolders);
		
		$thumbPath = trim(AriUtils2::getParam($params, 'thumbPath', ''));
		if ($thumbPath)
			$thumbPath = preg_replace('#^[/\\\\]+|[/\\\\]+$#', '', JPath::clean($thumbPath));
			
		$sortDir = strtolower(AriUtils2::getParam($params, 'sortDir', 'asc'));
		if (!in_array($sortDir, array('asc', 'desc')))
			$sortDir = 'asc';

		$thumbType = strtolower(AriUtils2::getParam($params, 'thumbType', 'resize'));
		if (!in_array($thumbType, array('resize', 'crop', 'cropresize')))
			$thumbType = 'resize';
			
		$thumbTypeParamKey = 'thumbType' . ucfirst($thumbType);
		$thumbTypeParams = AriUtils2::getParam($params, $thumbTypeParamKey, array());

		return array(
			'key' => $params['key'],
			'checkSum' => $params['checkSum'],
			'descrFile' => trim(AriUtils2::getParam($params, 'descrFile', '')),
			'fileFilter' => $params['fileFilter'],
			'thumbWidth' => $thumbWidth,
			'thumbHeight' => $thumbHeight,
			'generateThumbs' => $generateThumbs,
			'thumbType' => $thumbType,
			'thumbTypeParams' => $thumbTypeParams,
			'thumbFilters' => AriUtils2::getParam($params, 'thumbFilters', array()),
			'folders' => $folders,
			'cachePeriod' => $cachePeriod,
			'thumbPath' => $thumbPath,
			'sortDir' => $sortDir,
			'sortBy' => strtolower(AriUtils2::getParam($params, 'sortBy', '')),
			'emptyTitle' => AriUtils2::getParam($params['simplegallery'], 'emptyTitle', '')
		);
	}

	function getFolders($folders, $scanSubFolders = true)
	{
		$findFolders = array();
		if (empty($folders))
			return $findFolders;
			
		$folders = str_replace("\n", ';', $folders);
		$folders = explode(';', $folders);
		array_walk($folders, 'trim');
		foreach ($folders as $folder)
		{
			if (empty($folder) || !@file_exists($folder) || !@is_dir($folder))
				continue ;

			$folder = preg_replace('#^[/\\\\]+|[/\\\\]+$#', '', JPath::clean($folder));
			$findFolders[] = $folder;
			if ($scanSubFolders)
			{
				$subFolders = JFolder::folders($folder, '.', true, true);
				if (!empty($subFolders) && count($subFolders) > 0) $findFolders = array_merge($findFolders, $subFolders);
			}
		}

		return array_unique($findFolders);
	}
}
?>