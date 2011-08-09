<?php
require_once('nusoap/nusoap.php');

define('INFIN_VIDEO_SERVICE_URL', 'http://manage.infinovation.com/soap/v2/video/');
define('INFIN_AUTHTOKEN_SERVICE_URL', 'https://secure.infinovation.com/soap/v1/authtoken/');
define('INFIN_COMMON_SERVICE_URL', 'https://secure.infinovation.com/soap/v1/common/');

class InfinovationAuthToken extends InfinovationSoapBase
{
	private $accountKey;
	private $authTokenKey;
		
	public function __construct($accountKey, $secretKey)
	{
		$this->accountKey = $accountKey;
		parent::__construct(INFIN_AUTHTOKEN_SERVICE_URL, $secretKey);
	}
	
	public function createAuthToken()
	{		
		$params = array('accountKey' => $this->accountKey, 'clientIP' => $_SERVER["REMOTE_ADDR"], 'expires' => $this->getExpireDate());
		$result = $this->doSoapCall('CreateAuthToken', $params);
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($result);
		
		$resultNode = $xmlDoc->getElementsByTagName('authToken')->item(0);
		
		if ($resultNode->getAttribute('authTokenKey') == '-1')
		{
			throw new Exception($resultNode->getAttribute('msg'));
		}
		
		$this->authTokenKey = $resultNode->getAttribute('authTokenKey');
		
		$authToken = array('accountKey' => $resultNode->getAttribute('accountKey'), 'authTokenKey' => $resultNode->getAttribute('authTokenKey'));

		return $authToken;
	}
	
	public function getCredentials()
	{
		$params = array('authTokenKey' => $this->authTokenKey);
		$result = $this->doSoapCall('GetCredentials', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($result);

		$resultNode = $xmlDoc->getElementsByTagName('account')->item(0);
		
		if ($resultNode->getAttribute('accountKey') == '-1')
		{
			throw new Exception($resultNode->getAttribute('msg'));
		}
		
		$acctInfo = array('accountKey' => $resultNode->getAttribute('accountKey'), 'secretKey' => $resultNode->getAttribute('secretKey'));
		
		return $acctInfo;
	}
}

class InfinovationCommon extends InfinovationSoapBase
{
	public function __construct()
	{
		parent::__construct(INFIN_COMMON_SERVICE_URL, null);
	}
	
	
	/**
	 * Generates an expiration date string for SOAP calls
	 *
	 * @param int $seconds	Number of seconds until expiration
	 * 
	 * @return Date string
	 */
	public function getExpireDate($seconds = 30)
	{
		$result = $this->doSoapCall('GetServerTime');
		
		return gmdate('c', $result + $seconds);
	}
}

class InfinovationVideo extends InfinovationSoapBase
{
	private $accountKey;
	
	public function __construct($accountKey, $secretKey)
	{
		$this->accountKey = $accountKey;
		parent::__construct(INFIN_VIDEO_SERVICE_URL, $secretKey);
	}
	
	/**
	 * Gets a new video identifier
	 *
	 * @return string	New video identifier
	 */
	public function getNewVideoGuid()
	{
		$params = array('accountKey' => $this->accountKey, 'expires' => $this->getExpireDate());
		$result = $this->doSoapCall('GetNewVideoGuid', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($result);
		$resultNode = $xmlDoc->getElementsByTagName('result')->item(0);
		
		if ($resultNode->getAttribute('success') != '1')
		{
			throw new Exception($resultNode->getAttribute('msg'));
		}
		
		return $resultNode->getAttribute('videoGuid');
	}

	/**
	 * Locates and starts (if necessary) a valid upload server for a specified videoguid
	 *
	 * @param string $videoGuid	Video guid
	 *
	 * @return string	Full url to the upload page
	 */	
	public function getUploadUrl($videoGuid)
	{
		$params = array('accountKey' => $this->accountKey, 'videoGuid' => $videoGuid);
		$result = $this->doSoapCall('GetUploadUrl', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($result);
		$resultNode = $xmlDoc->getElementsByTagName('result')->item(0);
		
		$status = intval($resultNode->getAttribute('status'));
		
		switch ($status)
		{
			case InfinovationServerStatus::Pending:
				throw new InfinovationServerPendingException();
				break;
			
			case InfinovationServerStatus::Error:
				throw new Exception($resultNode->getAttribute('msg'));
				break;
		}
		
		return $resultNode->getAttribute('msg');		
	}
	
	public function generateUploadSignature($videoGuid)
	{
		$params = array('accountKey' => $this->accountKey, 'videoGuid' => $videoGuid);

		return $this->generateSignature('GetUploadUrl', $params);
	}
	
	/**
	 * Gets all videos that have been completed since the supplied videoguid 
	 *
	 * @param string $lastVideoGuid	Last successful video guid
	 *
	 * @return array	Array of InfinovationVideoInfo objects
	 */
	public function getCompletedVideos($lastVideoGuid = null)
	{
		$params = array('accountKey' => $this->accountKey, 'lastVideoGuid' => $lastVideoGuid, 'expires' => $this->getExpireDate());
		$resultXml = $this->doSoapCall('GetCompletedVideos', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($resultXml);
		$videosNode = $xmlDoc->getElementsByTagName('videos');
		
		if ($videosNode->length > 0)
		{
			$videosNode = $videosNode->item(0);
			
			if ($videosNode->getAttribute('success') == '1')
			{
				$videoNodes = $xmlDoc->getElementsByTagName('video');
				
				$videos = array();
				
				for ($i = 0; $i < $videoNodes->length; $i++)
				{
					$videoNode = $videoNodes->item($i);
					
					$video = new InfinovationVideoInfo();
					$video->videoGuid = $videoNode->getAttribute('videoGuid');
					$video->duration = $videoNode->getAttribute('duration');
					$video->conversionEndDate = $videoNode->getAttribute('conversionEndDate');
					$video->title = $videoNode->getAttribute('title');
					$video->description = $videoNode->getAttribute('description');
					$video->ownerRef = $videoNode->getAttribute('ownerRef');
					$video->tags = $videoNode->getAttribute('tags');
					$video->status = $videoNode->getAttribute('status');
					$video->url = $videoNode->getAttribute('url');
					
					if ($videoNode->hasChildNodes())
					{
						for ($j = 0; $j < $videoNode->childNodes->length; $j++)
						{
							$thumbNode = $videoNode->childNodes->item($j);
							if ($thumbNode->nodeName == 'thumbnail')
							{
								$thumb = new InfinovationThumbInfo();
								$thumb->url = $thumbNode->getAttribute('url');
								$thumb->timeIndex = $thumbNode->getAttribute('timeIndex');
								$thumb->width = $thumbNode->getAttribute('width');
								$thumb->height = $thumbNode->getAttribute('height');
								$video->thumbs[] = $thumb;
							}
						}
					}
					
					$videos[] = $video;
				}
				
				return $videos;
			}
			else
			{
				throw new Exception($videosNode->getAttribute('msg'));
			}
		}

		throw new Exception('Unexpected response: ' . $resultXml);
	}
	
	/**
	 * Searches all videos by title and description 
	 *
	 * @param string $query	Search query
	 * @param int $pageNumber Requested results page number
	 * @param int $videosPerPage Maximum videos per page
	 *
	 * @return array	Array of InfinovationVideoInfo objects
	 */
	public function searchVideos($query, $pageNumber, $videosPerPage)
	{
		$params = array('accountKey' => $this->accountKey, 'query' => $query, 'page' => $pageNumber, 'count' => $videosPerPage, 'expires' => $this->getExpireDate());
		$resultXml = $this->doSoapCall('SearchVideos', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($resultXml);
		$resultsNode = $xmlDoc->getElementsByTagName('results');
		
		if ($resultsNode->length > 0)
		{
			$resultsNode = $resultsNode->item(0);
			
			if ($resultsNode->getAttribute('success') == '1')
			{
				$videoNodes = $xmlDoc->getElementsByTagName('video');
				
				$videos = array();
				
				for ($i = 0; $i < $videoNodes->length; $i++)
				{
					$videoNode = $videoNodes->item($i);
					
					$video = new InfinovationVideoInfo();
					$video->videoGuid = $videoNode->getAttribute('videoGuid');
					$video->duration = $videoNode->getAttribute('duration');
					$video->conversionEndDate = $videoNode->getAttribute('conversionEndDate');
					$video->title = $videoNode->getAttribute('title');
					$video->description = $videoNode->getAttribute('description');
					$video->ownerRef = $videoNode->getAttribute('ownerRef');
					$video->tags = $videoNode->getAttribute('tags');
					$video->status = $videoNode->getAttribute('status');
					$video->url = $videoNode->getAttribute('url');
					
					if ($videoNode->hasChildNodes())
					{
						for ($j = 0; $j < $videoNode->childNodes->length; $j++)
						{
							$thumbNode = $videoNode->childNodes->item($j);
							if ($thumbNode->nodeName == 'thumbnail')
							{
								$thumb = new InfinovationThumbInfo();
								$thumb->url = $thumbNode->getAttribute('url');
								$thumb->timeIndex = $thumbNode->getAttribute('timeIndex');
								$thumb->width = $thumbNode->getAttribute('width');
								$thumb->height = $thumbNode->getAttribute('height');
								$video->thumbs[] = $thumb;
							}
						}
					}
					
					$videos[] = $video;
				}
				
				return $videos;
			}
			else
			{
				throw new Exception($resultsNode->getAttribute('msg'));
			}
		}

		throw new Exception('Unexpected response: ' . $resultXml);
	}	
	
	/**
	 * Searches all videos by tag 
	 *
	 * @param string $query	Search query
	 * @param int $pageNumber Requested results page number
	 * @param int $videosPerPage Maximum videos per page
	 *
	 * @return array	Array of InfinovationVideoInfo objects
	 */
	public function searchVideosByTag($query, $pageNumber, $videosPerPage)
	{
		$params = array('accountKey' => $this->accountKey, 'tag' => $query, 'page' => $pageNumber, 'count' => $videosPerPage, 'expires' => $this->getExpireDate());
		$resultXml = $this->doSoapCall('SearchVideoTag', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($resultXml);
		$resultsNode = $xmlDoc->getElementsByTagName('results');
		
		if ($resultsNode->length > 0)
		{
			$resultsNode = $resultsNode->item(0);
			
			if ($resultsNode->getAttribute('success') == '1')
			{
				$videoNodes = $xmlDoc->getElementsByTagName('video');
				
				$videos = array();
				
				for ($i = 0; $i < $videoNodes->length; $i++)
				{
					$videoNode = $videoNodes->item($i);
					
					$video = new InfinovationVideoInfo();
					$video->videoGuid = $videoNode->getAttribute('videoGuid');
					$video->duration = $videoNode->getAttribute('duration');
					$video->conversionEndDate = $videoNode->getAttribute('conversionEndDate');
					$video->title = $videoNode->getAttribute('title');
					$video->description = $videoNode->getAttribute('description');
					$video->ownerRef = $videoNode->getAttribute('ownerRef');
					$video->tags = $videoNode->getAttribute('tags');
					$video->status = $videoNode->getAttribute('status');
					$video->url = $videoNode->getAttribute('url');
					
					if ($videoNode->hasChildNodes())
					{
						for ($j = 0; $j < $videoNode->childNodes->length; $j++)
						{
							$thumbNode = $videoNode->childNodes->item($j);
							if ($thumbNode->nodeName == 'thumbnail')
							{
								$thumb = new InfinovationThumbInfo();
								$thumb->url = $thumbNode->getAttribute('url');
								$thumb->timeIndex = $thumbNode->getAttribute('timeIndex');
								$thumb->width = $thumbNode->getAttribute('width');
								$thumb->height = $thumbNode->getAttribute('height');
								$video->thumbs[] = $thumb;
							}
						}
					}
					
					$videos[] = $video;
				}
				
				return $videos;
			}
			else
			{
				throw new Exception($resultsNode->getAttribute('msg'));
			}
		}

		throw new Exception('Unexpected response: ' . $resultXml);
	}	
		
	/**
	 * Gets all videos
	 *
	 * @return array	Array of InfinovationVideoInfo objects
	 */
	public function getVideos()
	{
		$params = array('accountKey' => $this->accountKey, 'expires' => $this->getExpireDate());
		$resultXml = $this->doSoapCall('GetVideos', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($resultXml);
		$videosNode = $xmlDoc->getElementsByTagName('videos');
		
		if ($videosNode->length > 0)
		{
			$videosNode = $videosNode->item(0);
			
			if ($videosNode->getAttribute('success') == '1')
			{
				$videoNodes = $xmlDoc->getElementsByTagName('video');
				
				$videos = array();
				
				for ($i = 0; $i < $videoNodes->length; $i++)
				{
					$videoNode = $videoNodes->item($i);
					
					$video = new InfinovationVideoInfo();
					$video->videoGuid = $videoNode->getAttribute('videoGuid');
					$video->duration = $videoNode->getAttribute('duration');
					$video->conversionEndDate = $videoNode->getAttribute('conversionEndDate');
					$video->title = $videoNode->getAttribute('title');
					$video->description = $videoNode->getAttribute('description');
					$video->ownerRef = $videoNode->getAttribute('ownerRef');
					$video->tags = $videoNode->getAttribute('tags');
					$video->status = $videoNode->getAttribute('status');
					$video->url = $videoNode->getAttribute('url');
					
					if ($videoNode->hasChildNodes())
					{
						for ($j = 0; $j < $videoNode->childNodes->length; $j++)
						{
							$thumbNode = $videoNode->childNodes->item($j);
							if ($thumbNode->nodeName == 'thumbnail')
							{
								$thumb = new InfinovationThumbInfo();
								$thumb->url = $thumbNode->getAttribute('url');
								$thumb->timeIndex = $thumbNode->getAttribute('timeIndex');
								$thumb->width = $thumbNode->getAttribute('width');
								$thumb->height = $thumbNode->getAttribute('height');
								$video->thumbs[] = $thumb;
							}
						}
					}
					
					$videos[] = $video;
				}
				
				return $videos;
			}
			else
			{
				throw new Exception($videosNode->getAttribute('msg'));
			}
		}

		throw new Exception('Unexpected response: ' . $resultXml);
	}
	
	/**
	 * Gets all the video data for a specific video 
	 *
	 * @param string $videoGuid	videoGuid
	 *
	 * @return InfinovationVideoInfo	Video info object
	 */
	public function getVideoInfo($videoGuid)
	{
		$params = array('accountKey' => $this->accountKey, 'videoGuid' => $videoGuid); // , 'expires' => $this->getExpireDate()
		$resultXml = $this->doSoapCall('GetVideoInfo', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($resultXml);
		
		$videoNode = $xmlDoc->getElementsByTagName('video');
		
		if ($videoNode->length > 0)
		{
			$videoNode = $videoNode->item(0);
			
			if ($videoNode->getAttribute('status') != '0')
			{
				$video = new InfinovationVideoInfo();
				$video->videoGuid = $videoNode->getAttribute('videoGuid');
				$video->duration = $videoNode->getAttribute('duration');
				$video->conversionEndDate = $videoNode->getAttribute('conversionEndDate');
				$video->title = $videoNode->getAttribute('title');
				$video->description = $videoNode->getAttribute('description');
				$video->ownerRef = $videoNode->getAttribute('ownerRef');
				$video->tags = $videoNode->getAttribute('tags');
				$video->status = $videoNode->getAttribute('status');
				$video->url = $videoNode->getAttribute('url');
				
				if ($videoNode->hasChildNodes())
				{
					for ($j = 0; $j < $videoNode->childNodes->length; $j++)
					{
						$thumbNode = $videoNode->childNodes->item($j);
						if ($thumbNode->nodeName == 'thumbnail')
						{
							$thumb = new InfinovationThumbInfo();
							$thumb->url = $thumbNode->getAttribute('url');
							$thumb->timeIndex = $thumbNode->getAttribute('timeIndex');
							$thumb->width = $thumbNode->getAttribute('width');
							$thumb->height = $thumbNode->getAttribute('height');
							$video->thumbs[] = $thumb;
						}
					}
				}
				
				return $video;
			}
			else
			{
				throw new Exception($videoNode->getAttribute('msg'));
			}
		}

		throw new Exception('Unexpected response: ' . $resultXml);
	}
	
	/**
	 * Updates the properties of a video
	 *
	 * @param string $videoGuid
	 * @param string $title
	 * @param string $description
	 * @param string $ownerRef
	 * @param string $tags
	 */
	public function updateVideo($videoGuid, $title, $description, $ownerRef, $tags, $url)
	{
		$params = array('accountKey' => $this->accountKey,
						'videoGuid' => $videoGuid,
						'title' => $title,
						'description' => preg_replace('#\r#', '', $description),
						'ownerRef' => (int) $ownerRef,
						'tags' => $tags,
						'url' => $url,
						'expires' => $this->getExpireDate());
				
		$resultXml = $this->doSoapCall('UpdateVideo', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($resultXml);
		
		$resultNode = $xmlDoc->getElementsByTagName('result');
		
		if ($resultNode->length > 0)
		{
			$resultNode = $resultNode->item(0);
			
			if ($resultNode->getAttribute('success') == '0')
			{
				throw new Exception($resultNode->getAttribute('msg'));
			}
		}
		else
		{
			throw new Exception('Unexpected response: ' + $resultXml);
		}				
	}
	
	/**
	 * Deletes a video
	 *
	 * @param string $videoGuid
	 */
	public function deleteVideo($videoGuid)
	{
		$params = array('accountKey' => $this->accountKey,
						'videoGuid' => $videoGuid,
						'expires' => $this->getExpireDate());
		$resultXml = $this->doSoapCall('DeleteVideo', $params);
		
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($resultXml);
		
		$resultNode = $xmlDoc->getElementsByTagName('result');
		
		if ($resultNode->length > 0)
		{
			$resultNode = $resultNode->item(0);
			
			if ($resultNode->getAttribute('success') == '0')
			{
				throw new Exception($resultNode->getAttribute('msg'));
			}
		}
		else
		{
			throw new Exception('Unexpected response: ' + $resultXml);
		}				
	}
}


class InfinovationSoapBase
{
	private $serviceUrl;
	protected $secretKey;
	private $enableProxy = false;
	private $proxyParams = null;
	
	
	public function __construct($serviceUrl, $secretKey)
	{
		$this->serviceUrl = $serviceUrl;
		$this->secretKey = $secretKey;
	}
	
	public function setServiceUrl($serviceUrl)
	{
		$this->serviceUrl = $serviceUrl;
	}
	
	public function enableProxy()
	{
		$this->enableProxy = true;
	}
	
	public function disableProxy()
	{
		$this->enableProxy = false;
	}
	
	public function setProxyParams($proxyHost = false, $proxyPort = false, $proxyUsername = false
								 , $proxyPassword = false, $timeout=0, $responseTimeout = 30)
	{
		$this->proxyParams = array('proxyHost' => $proxyHost
							,'proxyPort' => $proxyPort
							,'proxyUsername' => $proxyUsername
							,'proxyPassword' => $proxyPassword
							,'timeout' => $timeout
							,'responseTimeout' => $responseTimeout);
	}
	
	/**
	 * Performs the SOAP call
	 *
	 * @param string $method
	 * @param array $params
	 * 
	 * @return string	Raw SOAP response
	 */
	protected function doSoapCall($method, array $params = null)
	{
		if ($params != null)
		{
			$params['signature'] = $this->generateSignature($method, $params);
		}
		else
		{
			$params = array();
		}
		
		if ((!$this->enableProxy && !is_null($this->proxyParams)) || is_null($this->proxyParams)) {
			$soapClient = new nusoap_client($this->serviceUrl);
		} else {
			$soapClient = new nusoap_client(
				$this->serviceUrl
				, null
				, array_key_exists('proxyHost', $this->proxyParams) ? $this->proxyParams['proxyHost'] == "" ? null : $this->proxyParams["proxyHost"] : null 
				, array_key_exists('proxyPort', $this->proxyParams) ? $this->proxyParams['proxyPort'] == "" ? null : $this->proxyParams["proxyPort"] : null 
				, array_key_exists('proxyUsername', $this->proxyParams) ? $this->proxyParams['proxyUsername'] == "" ? null : $this->proxyParams["proxyUsername"] : null  
				, array_key_exists('proxyPassword', $this->proxyParams) ? $this->proxyParams['proxyPassword'] == "" ? null : $this->proxyParams["proxyPassword"] : null 
				, array_key_exists('proxyTimeout', $this->proxyParams) ? $this->proxyParams['proxyTimeout']  == "" ? null : $this->proxyParams["proxyTimeout"] : null 
				, array_key_exists('proxyResponseTimeout', $this->proxyParams) ? $this->proxyParams['proxyResponseTimeout'] == "" ? null : $this->proxyParams["proxyResponseTimeout"] : null
			);
		}
		
		$result = $soapClient->call($method, $params);
		$this->checkError($soapClient);
		
		return $result;
	}
	
	/**
	 * Generates an expiration date string for SOAP calls
	 *
	 * @param int $seconds	Number of seconds until expiration
	 * 
	 * @return Date string
	 */
	protected function getExpireDate($seconds = 30)
	{
		$soapCommon = new InfinovationCommon();
		return $soapCommon->getExpireDate($seconds);
	}
	
	/**
	 * Generates a SOAP method signature
	 *
	 * @param string	$method	SOAP method being called
	 * @param array		$params Array of parameters to SOAP method
	 */
	protected function generateSignature($method, array $params)
	{
		uksort($params, 'strcasecmp');

		$sigText = $method;
		foreach ($params as $key => $value) $sigText .= $key . $value;
		$sig = base64_encode(hash_hmac('sha1', $sigText, $this->secretKey, true));
		
		return $sig;
	}
	
	/**
	 * Checks SOAP client for errors
	 *
	 * @param nusoap_client $soapClient
	 */
	private function checkError(nusoap_client $soapClient)
	{
		if ($error = $soapClient->getError())
		{
			throw new Exception($error . "\n\n" . $soapClient->response);
		}
	}
}


class InfinovationServerStatus
{
	const Running = 1;
	const Pending = 2;
	const Error = 3;
}

class InfinovationVideoInfo
{
	public $videoGuid;
	public $duration;
	public $conversionEndDate;
	public $title;
	public $description;
	public $ownerRef;
	public $tags;
	public $thumbs = array();
	public $status;
}

class InfinovationThumbInfo
{
	public $url;
	public $timeIndex;
	public $width;
	public $height;
}

class InfinovationServerPendingException extends Exception
{
	public function __construct()
	{
		parent::__construct('Server startup pending');
	}
}
