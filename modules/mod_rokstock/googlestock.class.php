<?php
/**
 * RokStock Module
 *
 * @package RocketTheme
 * @subpackage rokstock
 * @version   0.7 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/* PHP 4 fix */
if(!function_exists('json_encode') || !function_exists('json_decode')) include_once(JPATH_SITE.DS.'modules'.DS.'mod_rokstock'.DS.'JSON.php'); 

class googleStock
{
	var $raw_data;
	var $url = array(
		"data" => "http://www.google.com/finance/info?client=ig&q=",
		"charts" => "http://www.google.com/finance/chart?cht=c&q=",
		"match" => "http://www.google.com/finance/match?matchtype=matchall&q="
	);
	var $DB = array();
	var $DBFolder = "";
	var $DBFile = "rokstock-names.db";
	
	function googleStock($db_folder)
	{
		$this->DBFolder = $db_folder;
		$this->DBFile = $db_folder . DS . $this->DBFile;
		$this->DB = $this->load_db();
	}
	
	function makeRequest($stocks) {
		$httpinfo = $this->curl($this->url["data"].urlencode($stocks), $this->raw_data, true);
		
		if ($httpinfo == 400) return false;
		else {
		   	$this->raw_data = json_decode($this->raw_data, true);
			
			foreach($this->raw_data as $data) {
				if (!array_key_exists($data["id"], $this->DB)) $this->get_ticker_name($data);
				
				$data_key = key($this->array_search_recursive($data["id"], $this->raw_data));
				$this->raw_data[$data_key]["n"] = $this->DB[$data["id"]]["n"];
			}
			
			return $this->raw_data;
		}
	}
	
	function curl($url, &$output, $strip = false)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		$output = curl_exec($ch);
		if ($strip) $output = substr($output, 4, -1);
		$httpinfo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);
		
		return $httpinfo;
	}
	
	function load_db(){
		if (file_exists($this->DBFile)) return unserialize(JFile::read($this->DBFile));
		else return $this->write_db();
	}

	function write_db($return_array = array()){

		if (!JFile::exists($this->DBFolder)){
			// attempt to make the dir
			JFolder::create($this->DBFolder, 0777);
		}

		if (!JFile::write($this->DBFile, serialize($return_array))){
			echo "<br />Could not save data to cache. Please make sure your cache directory exists and is writable.<br />";
		}
		
		return $return_array;
	}
	
	function get_ticker_name($data)
	{
		$this->curl($this->url["match"].$data["t"], $ticker);

		$patterns = array("\\x26", "\\x2F",	"\\x3B", "\\x27");
		$replace = array("&", "/", ";", "'");

		$ticker = str_replace($patterns, $replace, $ticker);
		$ticker = json_decode($ticker, true);

		foreach($ticker["matches"] as $tickers)
		{
			if (!array_key_exists($tickers["id"], $this->DB)) {
				$this->DB[$tickers["id"]] = array(
					"t" => $tickers["t"],
					"n" => $tickers["n"],
					"e" => $tickers["e"]
				);
			}
		}
		
		$this->write_db($this->DB);
	}
	 
	function array_search_recursive($needle, $haystack){
	    $path = array();
	    foreach($haystack as $id => $val)
	    {
			if ($val === $needle) {
				$path[] = $id;
				break;
	         } else if (is_array($val)) {
				$found = $this->array_search_recursive($needle, $val);
				if(count($found) > 0) {
					$path[$id]=$found;
					break;
				}
			}
		}
		return $path;
	}
}