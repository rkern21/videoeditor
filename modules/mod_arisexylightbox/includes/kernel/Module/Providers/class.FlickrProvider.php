<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Flickr.Flickr');

class AriFlickrProvider extends AriObject
{
	var $_cacheDir;
	var $_flickr;
	var $_typeMapping = array(
		'thumbnail' => 't',
		'square' => 'sq',
		'small' => 's',
		'medium' => 'm',
		'large' => 'l'
	);
	
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
		$apiKey = $params['apikey'];
		$secretKey = !empty($params['secret']) ? $params['secret'] : null;
		$cachePeriod = @intval($params['cachePeriod'], 10);
		$flickr = new phpFlickr($apiKey, $secretKey);
		
		if (!empty($params['token']))
			$flickr->setToken($params['token']);
		
		if ($cachePeriod > 0 && $this->_cacheDir)
			$flickr->enableCache('fs', $this->_cacheDir, $cachePeriod);

		$this->_flickr =& $flickr;
	}
	
	function getData($params)
	{
		$data = array();
		$src = AriUtils2::getParam($params, 'source');

		switch ($src)
		{
			case 'collection':
				$collectionIds = explode(';', $params['colsource']['collectionId']);
				$data = $this->getCollectionsData($collectionIds, $params['colsource']['userId'], $params);
				break;
				
			case 'group':
				$groupIds = explode(';', $params['grsource']['groupId']);
				$data = $this->getGroupData($groupIds, $params);
				break;
			
			case 'photoset':
				$photosetIds = explode(';', $params['pssource']['photosetId']);
				$data = $this->getPhotosetsData($photosetIds, $params);
				break;

			case 'user':
				$userIds = explode(';', $params['usersource']['userId']);
				$data = $this->getUsersData($userIds, $params);
				break;
				
			case 'recentphotos':
				$userIds = explode(';', $params['recentphotos']['userId']);
				$data = $this->getRecentPhotoData($userIds, $params);
				break;
		}

		return $data;
	}
	
	function getCount($params, $checkRandom = true)
	{
		if ($checkRandom)
		{
			$random = !empty($params['random']);
			if ($random)
				return null;
		}

		$count = intval($params['count'], 10);
		if ($count < 1) $count = null;
		
		return $count;
	}
	
	function getRandomItems($data, $params)
	{
		$random = !empty($params['random']);
		if (!$random)
			return $data;
			
		$count = intval($params['count'], 10);
		$keys = array_keys($data);
		shuffle($keys);
		if ($count > 0)
			$keys = array_slice($keys, 0, $count);
			
		$randData = array();
		foreach ($keys as $key)
		{
			$randData[$key] = $data[$key];
		}

		return $randData;
	}
	
	function getGroupData($groupIds, $params)
	{
		$data = array('photos' => array());
		$photos = array();
		$flickr =& $this->_flickr;
		
		$count = $this->getCount($params);
		foreach ($groupIds as $groupId)
		{
			if (empty($groupId))
				continue ;
				
			$groupPhotos = $flickr->groups_pools_getPhotos($groupId, null, null, 'date_upload,last_update, url_sq, url_t, url_s, url_m, url_o, url_l', $count);
			$photosetData = $this->getPhotosetData($groupPhotos, $params);
			if (!empty($photosetData))
			{
				foreach ($photosetData as $key => $value)
				{
					$photos[$key] = $value;
				}
			}
		}
		
		$data['photos'] = $photos;
		
		return $data;
	}
	
	function getCollectionsData($collectionIds, $userId, $params)
	{
		$data = array();
		$flickr =& $this->_flickr;
		$photosets = array();
		$photosetsData = array();
		
		foreach ($collectionIds as $collectionId)
		{
			if (empty($collectionId))
				continue ;

			$collection = $flickr->collections_getTree($collectionId, $userId);
			if (empty($collection['collections']['collection'][0]['set']))
				continue ;

			$sets = $collection['collections']['collection'][0]['set'];
			foreach ($sets as $photoset)
			{
				$photosetId = $photoset['id'];
				$photosets[] = $photoset['id'];
				$photosetsData[$photosetId] = $photoset;
			}
		}

		if (count($photosets) > 0)
			$data = $this->getPhotosetsData($photosets, $params, $photosetsData);
		
		return $data;
	}
	
	function getUsersData($userIds, $params)
	{
		$data = array();
		$flickr =& $this->_flickr;
		$photosets = array();
		$photosetsData = array();

		foreach ($userIds as $userId)
		{
			if (empty($userId))
				continue ;
				
			$flickrPhotosets = $flickr->photosets_getList($userId);
			if (!empty($flickrPhotosets['photoset']))
			{
				foreach ($flickrPhotosets['photoset'] as $photoset)
				{
					$photosetId = $photoset['id'];
					$photosets[] = $photoset['id'];
					$photosetsData[$photosetId] = $photoset;
				}
			}
		}
		
		if (count($photosets) > 0)
			$data = $this->getPhotosetsData($photosets, $params, $photosetsData);

		return $data;
	}
	
	function getRecentPhotoData($userIds, $params)
	{
		$data = array();
		$photos = array();
		$flickr =& $this->_flickr;

		$count = $this->getCount($params);
		foreach ($userIds as $userId)
		{
			if (empty($userId))
				continue ;

			$flickrPhotos = $this->searchPhotos(array('user_id' => $userId, 'per_page' => $count, 'media' => 'photos', 'extras' => 'date_upload,last_update, url_sq, url_t, url_s, url_m, url_o, url_l'), $params);
			$flickrPhotos = $this->getRandomItems($flickrPhotos, $params);
			if (count($flickrPhotos) > 0) $photos = array_merge($photos, $flickrPhotos);
		}

		$data['photos'] = $photos;

		return $data;
	}
	
	function searchPhotos($args, $params)
	{
		$photos = array();
		$flickr =& $this->_flickr;
		
		$flickrData = $flickr->photos_search($args);
		$flickrData = $this->getPhotosetData($flickrData, $params);
		if (!empty($flickrData))
		{
			foreach ($flickrData as $key => $value)
			{
				$photos[$key] = $value;
			}
		}
		
		return $photos;
	}
	
	function getPhotosetsData($photosetIds, $params, $photosets = null)
	{
		$data = array('photos' => array(), 'photosets' => array());
		$photos = array();
		$flickr =& $this->_flickr;

		$count = $this->getCount($params);
		foreach ($photosetIds as $photosetId)
		{
			if (empty($photosetId))
				continue ;

			$flickrPhotoset = $flickr->photosets_getPhotos($photosetId, 'date_upload, last_update, url_sq, url_t, url_s, url_m, url_l,url_o, url_l', null, $count);
			$photosetData = $this->getPhotosetData($flickrPhotoset, $params);
			$photosetData = $this->getRandomItems($photosetData, $params);
			if (!empty($photosetData))
			{
				foreach ($photosetData as $key => $value)
				{
					$photos[$key] = $value;
				}
			}

			$data['photosets'][$photosetId] = isset($photosets[$photosetId])
				? $photosets[$photosetId]
				: $flickr->photosets_getInfo($photosetId);
		}
		
		$data['photos'] = $photos;

		return $data;
	}
	
	function getPhotosetData($photoset, $params)
	{
		$data = array();
		if (empty($photoset))
			return $data;
		
		$photos = null;
		$photoset = AriUtils2::getParam($photoset, 'photoset', $photoset);
		if ($photoset) $photos = AriUtils2::getParam($photoset, 'photo');
		if (empty($photos))
			return $data;

		$photosetId = AriUtils2::getParam($photoset, 'id', null);
		$thumbSize = AriUtils2::getParam($params, 'thumbSize', 'thumbnail');
		$imgSize = $params['imgSize'];

		foreach ($photos as $photo)
		{
			$thumbUrl = @phpFlickr::buildPhotoURL($photo, $thumbSize);
			$title = str_replace('"', '&quot;', AriUtils2::getParam($photo, 'title', ''));

			$data[$photo['id']] = array(
				'photosetId' => $photosetId,
				'dateUpload' => AriUtils2::getParam($photo, 'dateupload'),
				'lastUpdate' => AriUtils2::getParam($photo, 'lastupdate'),
				'imgUrl' => @phpFlickr::buildPhotoURL($photo, $imgSize),
				'thumbUrl' => @phpFlickr::buildPhotoURL($photo, $thumbSize),
				'Title' => $title,
				'w' => $this->getPhotoWidth($photo, $thumbSize),
				'h' => $this->getPhotoHeight($photo, $thumbSize)
			);
		}

		return $data;
	}
	
	function getShortImageType($type)
	{		
		return isset($this->_typeMapping[$type]) ? $this->_typeMapping[$type] : $type;
	}
	
	function getPhotoWidth($data, $type)
	{
		$type = $this->getShortImageType($type);

		return intval(AriUtils2::getParam($data, 'width_' . $type), 10);
	}
	
	function getPhotoHeight($data, $type)
	{
		$type = $this->getShortImageType($type);
		
		return intval(AriUtils2::getParam($data, 'height_' . $type), 10);
	}
}
?>