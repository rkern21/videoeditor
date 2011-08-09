<?php
/**
 * RokTwittie Module
 *
 * @package RocketTheme
 * @subpackage roktwittie
 * @version   2.0 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * @package RocketTheme
 * @subpackage roktwittie
 */
class rokTwittie
{
	/**
	 * Twitter API methods
	 *
	 * @var array 
	 */
	private $url = array(
		"status" =>		"users/show",
		"friends" =>	"statuses/friends",
		"messages" =>	"statuses/user_timeline",
	);
	
	/**
	 * Oauth connection
	 *
	 * @var TwitterOAuth
	 */
	private $connection = null;
	
	/**
	 * @var array
	 */
	private $usernames = array();
	
	/**
	 * @var int
	 */
	private $id = 0;
	
	/**
	 * @var boolean
	 */
	private $oauth_setup = false;
	
	/**
	 * @var string
	 */
	private $useragent = 'RokTwittie 2.0';
	
	/**
	 * @var string
	 */
	private $host = "https://api.twitter.com/1/";
	
	/**
	 * @var int
	 */
	private $timeout = 30;
	
	/**
	 * @var int
	 */
	private $connecttimeout = 30; 
	
	/**
	 * Request data from Twitter api
	 * 
	 * @param JParameter $params
	 * @param int $id
	 */
	public function __construct(JParameter $params, $id)
	{
		$this->params = $params;
		$this->id = $id;
		
		$this->usernames = explode(",", str_replace(" ", "", $params->get("usernames", "rockettheme")));
		$this->timeout = $params->get("timeout_response", 5);
		$this->connecttimeout = $params->get("timeout_connect", 5);
	}
	
	/**
	 * Request data from Twitter api
	 * 
	 * @param string $type
	 * @return array
	 */
	public function makeRequest($type)
	{
		if ($ready_for_cache = ($this->params->get("enable_cache", "1") == "1")) {
			$user = JFactory::getUser();
			$cache = JFactory::getCache('mod_roktwittie', 'output');
			$cache->setCaching(true);
			$cache->setLifeTime(((int)$this->params->get("enable_cache_time", 5)) * 60);
			
			$cache_key = $type . '|' . $this->id;
		}

		if ($ready_for_cache && false != ($data = $cache->get($cache_key))) {
			return unserialize($data);			
		}
		
		$output = array();
		
		if ($type != 'messages') {
			foreach ($this->usernames as $user)
			{
				if (false === ($output[$user] = $this->_get($this->url[$type], array('screen_name' => $user))))
					return false;
			}
		}
		else {
			foreach ($this->usernames as $user)
			{
				 if (false === ($data = $this->_get($this->url[$type], array('screen_name' => $user, 'count' => $this->params->get("usernames_count_size", 4), 'since_id' => 1, 'include_rts' => $this->params->get("include_rts", 1)))))
					return false;
				 
				 $output[$user] = $this->_parseMessages($data);
			}
		}
		
		if ($ready_for_cache) {
			$cache->store(serialize($output), $cache_key);
		}
		
		return $output;
	}
	
	/**
	 * Parse messages
	 *
	 * @param array $json
	 * @return array
	 */
	private function _parseMessages(array $json)
	{
		$messages = array();
		
		$count = 0;
		foreach ($json as $message)
		{
			$message_ = array(
				'created_at' => $message->created_at, 
				'source' => $message->source,
				'text' => $message->text,
				);
			
			// profile image is needed only for first tweet
			if ($count++ == 0)
			{
				$message_['user'] = array('profile_image_url' => $message->user->profile_image_url);
			}
				
			$messages[] = $message_;
		}
		
		return $messages;
	}
	
	/**
	 * Get data by twitter api url
	 *
	 * @param string $url
	 * @param array $parameters
	 * @param string $format
	 * @return mixed
	 */
	private function _get($url, $parameters = array(), $format = 'json')
	{
		// Check for curl extension
		if (!extension_loaded('curl')) {
			return false;
		}
		
		if (!$this->oauth_setup)	{
			$this->oauth_setup = true;
			
			if (!$this->params->get('oauth_token', '') || !$this->params->get('oauth_token_secret', '')) {
				$this->connection = false;
			}
			elseif (false != ($this->connection = modRokTwittieHelper::getOauth($this->params))) {
				$this->connection->useragent = $this->useragent;
				$this->connection->connecttimeout = $this->connecttimeout;
				$this->connection->timeout = $this->timeout;
				
				// verify credentials
				$this->connection->get('account/verify_credentials');
				
				if ($this->connection->http_code != 200) {
					$this->connection = false;
				}
			}
		}
		
		if ($this->connection) {
			$this->connection->format = $format;

			$result = $this->connection->get($url, $parameters);
			
			if ($this->connection->http_code != 200) {
				return false;
			}
		}
		else {
			$ci = curl_init();
			curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
			curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
			curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ci, CURLOPT_HEADER, false);

			curl_setopt($ci, CURLOPT_URL, $this->host . $url . '.' . $format. '?' . OAuthUtil::build_http_query($parameters));
			$result = curl_exec($ci);
			$http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
			curl_close ($ci);
			
			if ($http_code != 200) {
				return false;
			}
			
			if ($format == 'json') {
				$result = json_decode($result);
			}
		}
		
		
		return $result;
	}
}