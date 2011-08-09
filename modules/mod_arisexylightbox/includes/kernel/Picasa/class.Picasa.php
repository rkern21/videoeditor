<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

define('ARI_PICASA_ERROR_NOALBUM', 'No album found.');

class AriPicasa extends JObject
{
	var $_picasaUrl = 'http://picasaweb.google.com/data/feed/api/';
	var $_cacheDir = null;
	var $_cacheEnabled = false;
	var $_cachePeriod = 0;
	
	function AriPicasa()
	{
	}
	
	function enableCache($cacheDir, $cachePeriod)
	{
		$this->_cacheDir = $cacheDir;
		$this->_cachePeriod = $cachePeriod;
		$this->_cacheEnabled = true;
		
		global $_ARI_PICASA_CACHE;
		
		if (!isset($_ARI_PICASA_CACHE))
			$GLOBALS['_ARI_PICASA_CACHE'] = array();
	}
	
	function disableCache()
	{
		$this->_cacheEnabled = false;
	}
	
	function getUserAlbumData($user, $album, $options = array())
	{
		$params = array(
			'user' => $user, 
			'album' => $this->_prepareAlbumName($album));
		
		if (!isset($options['kind'])) $options['kind'] = 'photo';
		if (!isset($options['access'])) $options['access'] = 'public';

		return $this->_sendRequest($params, $options);
	}
	
	function _prepareAlbumName($album)
	{
		if (function_exists('iconv'))
		{
			setlocale(LC_ALL, 'en_US.UTF8');
			$cleanAlbum = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $album);
			if (!empty($cleanAlbum))
				$album = $cleanAlbum;
		}

		$album = preg_replace('/[^A-z0-9]/i', '', $album);

		return $album;
	}
	
	function _getCachedData($url)
	{
		$data = null;
		
		if (!$this->_cacheEnabled || empty($url))
			return $data;

		$cacheFile= $this->_cacheDir . DS . $this->_cacheFileName($url);
		if (!@file_exists($cacheFile) || !@is_readable($cacheFile))
			return $data;
			
		if ($this->_cachePeriod < 1 || time() - filemtime($cacheFile) > $this->_cachePeriod)
		{
			@unlink($cacheFile);
			return $data;
		}
 
		global $_ARI_PICASA_CACHE;

		require_once $cacheFile;

		$cacheKey = $this->_cacheKey($url);
		if (isset($_ARI_PICASA_CACHE[$cacheKey]))
			$data = $_ARI_PICASA_CACHE[$cacheKey];

		return $data;
	}
	
	function _cacheData($url, $data)
	{
		$cacheFile = $this->_cacheFileName($url);
		$cacheKey = $this->_cacheKey($url);

		$fh = fopen($this->_cacheDir . DS . $cacheFile, 'w');
		fwrite($fh, sprintf('<?php global $_ARI_PICASA_CACHE; $_ARI_PICASA_CACHE["%s"] = %s; ?>',
			$cacheKey,
			var_export($data, true)));
		fclose($fh);
	}
	
	function _cacheFileName($url)
	{
		return md5($url) . '.php';
	}
	
	function _cacheKey($url)
	{
		return md5($url);
	}
	
	function _buildRequestUrl($params = array(), $options = array())
	{
		$url = $this->_picasaUrl;
		
		$urlOptions = array();
		foreach ($params as $key => $value)
		{
			$urlOptions[] = sprintf('%s/%s',
				$key,
				urlencode($value));
		}
		
		$url .= join('/', $urlOptions);
		
		if (!empty($options))
		{
			$urlOptions = array();
			foreach ($options as $key => $value)
			{
				$urlOptions[] = $key . '=' . urlencode($value);
			}

			$url .= '?' . join('&', $urlOptions);
		}

		return $url;
	}
	
	function _sendRequest($params = array(), $options = array())
	{
		$url = $this->_buildRequestUrl($params, $options);
		$data = $this->_getCachedData($url);

		if (!is_null($data))
			return $data;

		$response = '';
		if (function_exists('curl_init'))
		{
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			$response = @file_get_contents($url);
		}

		$data = $this->_parseFeed($response, AriUtils2::getParam($options, 'kind'));
		if (empty($data))
		{
			$this->_handleError($response, $params, $options);
		}
		
		if ($this->_cacheEnabled && !empty($data))
			$this->_cacheData($url, $data);

		return $data;
	}
	
	function _handleError($response, $params, $options)
	{
		$error = null;
		switch ($response)
		{
			case ARI_PICASA_ERROR_NOALBUM:
				$error = sprintf('Picasa album "%1$s" not found.',
					AriUtils2::getParam($params, 'album'));
				break;
		}
		
		if ($error)
			$this->setError($error);
	}
	
	function _parseFeed($source, $type)
	{
		$data = null;
		switch ($type)
		{
			case 'photo':
				$data = $this->_parsePhotoFeed($source);
				break;
		}
		
		return $data;
	}
	
	function _parsePhotoFeed($source)
	{
		$data = array();

		$xml = @simplexml_load_string($source); 

		if (empty($xml->entry))
		{			
			return $data;
		}
			
		$namespace = $xml->getDocNamespaces();
		foreach ($xml->entry as $entry)
		{
			$mediaNode =& $entry->children($namespace['media']);
			$imgAttrs = $mediaNode->group->content->attributes();
			$thumbAttrs = $mediaNode->group->thumbnail->attributes();

			$dataItem = array(
				'title' => (string)$entry->title,
				'summary' => (string)$entry->summary,
				'image' => array(
					'url' => (string)$imgAttrs['url'],
					'w' => (int)$imgAttrs['width'],
					'h' => (int)$imgAttrs['height'],
				),
				'thumb' => array(
					'url' => (string)$thumbAttrs['url'],
					'w' => (int)$thumbAttrs['width'],
					'h' => (int)$thumbAttrs['height'],
				)
			);
			
			$data[] = $dataItem;
		}

		return $data;
	}
}
?>