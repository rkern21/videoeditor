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

defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__) . DS . 'libs' . DS . 'twitteroauth' . DS . 'twitteroauth.php';
require_once dirname(__FILE__) . DS . 'roktwittie.class.php';

/**
 * @package RocketTheme
 * @subpackage roktwittie
 */
class modRokTwittieHelper
{
	public static $jsinit = false;
	
	/**
	 * Load scripts
	 *
	 * @param JParameter $params
	 * @param stdClass $module
	 */
	public static function loadScripts(JParameter $params, stdClass $module)
	{
		if (self::$jsinit)
			return;
			
		self::$jsinit = true;	
			
		JHTML::_('behavior.mootools');
		$doc =& JFactory::getDocument();
		
		$js_file = JURI::root(true) . '/modules/mod_roktwittie/js/roktwittie'.modRokTwittieHelper::_getJSVersion().'.js';
		
		$enable_usernames = ($params->get("enable_usernames", "1") == "1") ? 1 : 0;
		$usernames = str_replace(" ", "", $params->get("usernames", "rockettheme"));
		$enable_usernames_avatar = ($params->get("enable_usernames_avatar", "1") == "1") ? 1 : 0;
		$usernames_avatar_size = $params->get("usernames_avatar_size", 48);
		$usernames_count_size = $params->get("usernames_count_size", 4);
		$enable_usernames_externals = ($params->get("enable_usernames_externals", "1") == "1") ? 1 : 0;
		$enable_usernames_source = ($params->get("enable_usernames_source", "1") == "1") ? 1 : 0;
		$enable_usernames_user = ($params->get("enable_usernames_user", "1") == "1") ? 1 : 0;
		$show_default_avatar = ($params->get("show_default_avatar","1") == "1") ? 1 : 0;
		$inactive_opacity = $params->get("inactive_opacity", 0.5);
		$usernames_count_merged = ($params->get("usernames_count_merged", "1") == "1") ? 1 : 0;
		
		if ($enable_usernames_avatar) $user_avatar = $usernames_avatar_size;
		else $user_avatar = 0;
		
		$enable_search = ($params->get("enable_search", "1") == "1") ? 1 : 0;
		$search = $params->get("search", "@rockettheme");
		$enable_search_avatar = ($params->get("enable_search_avatar", "1") == "1") ? 1 : 0;
		$search_avatar_size = $params->get("search_avatar_size", 48);
		$search_count_size = $params->get("search_count_size", 4);
		$enable_search_externals = ($params->get("enable_search_externals", "1") == "1") ? 1 : 0;
		$enable_search_source = ($params->get("enable_search_source", "1") == "1") ? 1 : 0;
		$enable_search_user = ($params->get("enable_search_user", "1") == "1") ? 1 : 0;
		$include_rts = ($params->get("include_rts", "1") == "1") ? 1 : 0;
		
		if ($enable_search_avatar) $search_avatar = $search_avatar_size;
		else $search_avatar = 0;
		if (!strlen($search)) $search = 0;
		else $search = '\''.$search.'\'';
		if (!$enable_search) $search = 0;
		
		$usernames = explode(",", $usernames);
		$usernames = "['".implode("', '", $usernames)."']";
		if (!$enable_usernames) $usernames = 0;
		
		$messages = self::request($params, $module, "messages");
		$messages = json_encode(is_array($messages) && count($messages) > 0 ? $messages : null);
					
		$document =& JFactory::getDocument();
		$document->addScript($js_file);
		$document->addScriptDeclaration("<!--//--><![CDATA[//><!--
		window.addEvent('domready', function() {
			new RokTwittie({
				username: $usernames,
				query: $search,
				defaultAvatar: $show_default_avatar,
				avatar: { user: {$user_avatar}, query: {$search_avatar} },
				count: { user: {$usernames_count_size}, query: {$search_count_size}, merge: {$usernames_count_merged} },
				external: {	user: {$enable_usernames_externals}, query: {$enable_search_externals} },
				showSource: { user: {$enable_usernames_source}, query: {$enable_search_source} },
				showUser: {	user: {$enable_usernames_user},	query: {$enable_search_user} },
				includeRts: { user: {$include_rts}},
				inactiveOpacity: $inactive_opacity,
				lang: {
					viewTweet: '".JText::_("VIEWTWEET")."',
					from: '".JText::_("FROM")."',
					lessThanAMin: '".JText::_("LESS_THAN_A_MIN")."',
					about: '".JText::_("ABOUT")."',
					aboutAMin: '".JText::_("ABOUT_A_MIN_AGO")."',
					minutesAgo: '".JText::_("MINS_AGO")."',
					aboutAHour: '".JText::_("ABOUT_A_HOUR_AGO")."',
					hoursAgo: '".JText::_("HOURS_AGO")."',
					oneDay: '".JText::_("ONE_DAY_AGO")."',
					daysAgo: '".JText::_("DAYS_AGO")."'
				},
				messages: {$messages}
			});
		});
		//--><!]]>");
	}
	
	/**
	 * Request data from twitter api
	 *
	 * @param JParameter $params
	 * @param stdClass $module
	 * @param string $type
	 * @return array
	 */
	public static function request(JParameter $params, stdClass $module, $type)
	{
	 	$roktwittie = new rokTwittie($params, $module->id);
		
		$output = $roktwittie->makeRequest($type);
		
		return $output;
	}
	
	/**
	 * Get Browser version
	 *
	 * @return string
	 */
	public static function getBrowser() 
	{
		$agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : false;
		$ie_version = false;
				
		if (preg_match("/msie/", $agent) && !preg_match("/opera/", $agent)){
            $val = explode(" ",stristr($agent, "msie"));
            $ver = explode(".", $val[1]);
			$ie_version = $ver[0];
			$ie_version = ereg_replace("[^0-9,.,a-z,A-Z]", "", $ie_version);
		}
		
		return $ie_version;
	}
	
	/**
	 * Get Oauth library
	 *
	 * @param JParameter $params
	 * @param string $oauth_token
	 * @param string $oauth_token_secret
	 * @return TwitterOAuth
	 */
	public static function getOauth(JParameter $params, $oauth_token = null, $oauth_token_secret = null)
	{
		$consumer_key = $params->get("consumer_key", '');
		$consumer_secret = $params->get("consumer_secret", '');
		
		$oauth_token = isset($oauth_token) ? $oauth_token : $params->get('oauth_token', '');
		$oauth_token_secret = isset($oauth_token_secret) ? $oauth_token_secret : $params->get('oauth_token_secret', '');
		
		if (!$consumer_key || !$consumer_secret || !$params->get("use_oauth", 0)) {
			return false;
		}

		$oauth = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
		
		return $oauth;
	}
	
	/**
	 * Get Javascript library version
	 *
	 * @return string
	 */
	private static function _getJSVersion()
	{
		if (version_compare(JVERSION, '1.5', '>=') && version_compare(JVERSION, '1.6', '<')){
			if (JPluginHelper::isEnabled('system', 'mtupgrade')){
				return "-mt1.2";
			} else {
				return "";
			}
		} else {
			return "";
		}
	}
}