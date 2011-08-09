<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Picasa.Picasa');

class AriPicasaProvider extends AriObject
{
	var $_cacheDir;
	var $_picasa;

	function __construct($params, $cacheDir = null, $ext = null)
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

		$this->_cacheDir = $cacheDir;

		$cachePeriod = @intval($params['cachePeriod'], 10);
		$picasa = new AriPicasa();
		if ($cachePeriod > 0 && $this->_cacheDir)
			$picasa->enableCache($this->_cacheDir, $cachePeriod);

		$this->_picasa =& $picasa;
	}
	
	function getData($params)
	{
		$data = array();

		$src = $params['albumsource'];
		$data = $this->getUserAlbumData($src['user'], $src['album'], $params);

		return $data;
	}
	
	function getUserAlbumData($user, $album, $params)
	{
		$data = array();
		$options = array(
			'thumbsize' => AriUtils::getParam($params, 'thumbSize', 128),
			'imgmax' => AriUtils::getParam($params, 'imgSize', 1600));
		
		$maxResults = intval(AriUtils::getParam($params, 'count'), 10);
		if ($maxResults > 0) $options['max-results'] = $maxResults;
		
		$offset = intval(AriUtils::getParam($params, 'offset'), 10);
		if ($offset > 0) $options['start-index'] = $offset;
		
		$picasa =& $this->_picasa;
		
		$data = $picasa->getUserAlbumData($user, $album, $options);
		
		return $data;
	}
	
	function getError($i = null, $toString = true)
	{
		return $this->_picasa->getError($i, $toString);
	}
}
?>