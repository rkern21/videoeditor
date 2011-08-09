<?php
/**
 * @version   1.0 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

 
	/**
 	 * @package RocketTheme
     * @subpackage rokweather
	 * @example
	 * $w = new googleWeather();
	 * $w->enable_cache = 1;
	 * $w->cache_path = '/var/www/mysite.com/cache';
	 * $ar_data = $w->get_weather_data(10027);
	 * print_r($ar_data);
	 * echo $ar_data['forecast'][0]['day_of_week'];
	 *
	 */
	class googleWeather{
 
		/**
		 * Location
		 *
		 * @var string
		 */
		var $location;
 
		/**
		 * Disable or enable caching
		 *
		 * @var boolean
		 */
		var $enable_cache = 0;
 
		/**
		 * Path to your cache directory
		 * eg. /www/website.com/cache
		 *
		 * @var string
		 */
		var $cache_path = '';
 
		/**
		 * Cache expiration time in seconds
		 * Default: 3600 = 1 Hour
		 * If the cached file is older than 1 hour, new data is fetched
		 *
		 * @var int
		 */
		var $cache_time = 900; // 15mins
 
		/**
		 * Full location of the cache file
		 *
		 * @var string
		 */
		var $cache_file;
 
		/**
		 * Location of the google weather api
		 *
		 * @var string
		 */
		var $gweather_api_url = 'http://www.google.com/ig/api?weather=';
 
		/**
		 * Storage var for data returned from curl request to the google api
		 *
		 * @var string
		 */
		var $raw_data;
 
		/**
		 * Pull weather information for 'location' passed in
		 * If enable_cache = true, data is cached and refreshed every hour
		 * Weather data is returned in an associative array
		 *
		 * @param string $location
		 * @return array
		 */
		function get_weather_data($location = ''){
		    
		    $return_array = array();
 
		    $this->location = $location;
			
			$lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			list($lang) = explode(",", $lang);
			list($lang) = explode("-", $lang);
			
			if (!isset($lang) || $lang=="") $lang="en";
 
			if ($this->enable_cache && !empty($this->cache_path)){
				$this->cache_file = $this->cache_path . '/' . $this->filename_safe($this->location."_".$lang);
				if (strlen($this->location)>0 && file_exists($this->cache_file)){
				    $cached_data = $this->load_from_cache();
				    if($cached_data) return $cached_data;
				}
			}
            
			
			// portoguese fix
			if ($lang == 'pt') {
				$this->gweather_api_url = str_replace(".com", ".pt", $this->gweather_api_url);
				$hl = "";
			} else $hl = "&hl=" . urlencode($lang);
			
			// build the url
			$this->gweather_api_url = $this->gweather_api_url . urlencode($this->location) . $hl;
 
			if ($this->make_request()){
			    
			    
				$xml = new JSimpleXML();
				$xml->loadString($this->raw_data);
				
				if (isset($xml->document->weather[0]->problem_cause) || 
				    !isset($xml->document->weather[0]->forecast_information)) {
				    $return_array['error'] = "invalid location provided";
				    return $return_array;
				}
				

				
				$forecast_information = $xml->document->weather[0]->forecast_information[0];
				$current_conditions = $xml->document->weather[0]->current_conditions[0];
				$forecast_conditions = $xml->document->weather[0]->forecast_conditions;
			 
 
				$return_array['forecast_info']['city'] = $forecast_information->city[0]->attributes('data');
				$return_array['forecast_info']['zip'] = $forecast_information->postal_code[0]->attributes('data');
				$return_array['forecast_info']['date'] = $forecast_information->forecast_date[0]->attributes('data');
				$return_array['forecast_info']['date_time'] = $forecast_information->current_date_time[0]->attributes('data');
				$return_array['forecast_info']['units'] = $forecast_information->unit_system[0]->attributes('data');
 
				$return_array['current_conditions']['condition'] = $current_conditions->condition[0]->attributes('data');
				$return_array['current_conditions']['temp_f'] = $current_conditions->temp_f[0]->attributes('data');
				$return_array['current_conditions']['temp_c'] = $current_conditions->temp_c[0]->attributes('data');
				$return_array['current_conditions']['humidity'] = $current_conditions->humidity[0]->attributes('data');
				$return_array['current_conditions']['icon'] = $this->fix_icon($current_conditions->icon[0]->attributes('data'));
				$return_array['current_conditions']['wind'] = $current_conditions->wind_condition[0]->attributes('data');
 
				for ($i = 0; $i < count($forecast_conditions); $i++){
					$data = $forecast_conditions[$i];
					$return_array['forecast'][$i]['day_of_week'] = $data->day_of_week[0]->attributes('data');
					$return_array['forecast'][$i]['low'] = $data->low[0]->attributes('data');
					$return_array['forecast'][$i]['high'] = $data->high[0]->attributes('data');
					$return_array['forecast'][$i]['icon'] = $this->fix_icon($data->icon[0]->attributes('data'));
					$return_array['forecast'][$i]['condition'] = $data->condition[0]->attributes('data');
				}
 
			}
 
			if (count($return_array)>1 && $this->enable_cache && !empty($this->cache_path)){
				$this->write_to_cache($return_array);
			}
 
			return $return_array;
 
		}
		
		function fix_icon($icon) {
		    
		    $different_images = array(
				"rainysometimescloudy"	=> "chance_of_rain",
				"rainy"	=> "rain",
				"snowy" => "snow",
				"sand" => "dust"
			);
			
			if (strpos($icon,"/ig")!==false) {
			    $icon = str_replace("/ig","",$icon);
			}
			
		    foreach ($different_images as $key=>$value) {
		        if (strpos($icon,$key)!==false) {
		            $icon = str_replace($key,$value,$icon);
		        }
		    }
		    
		    $icon = str_replace('.gif','.png',$icon);
		    $icon = str_replace(array('jp_'),'',$icon);
		    return $icon;
		}
 
		function load_from_cache(){
 
			
 
			$file_time = filectime($this->cache_file);
			$now = time();
			$diff = ($now-$file_time);

			if ($diff <= $this->cache_time){
				return unserialize(JFile::read($this->cache_file));
			} else {
			    JFile::delete($this->cache_file);
			    return false;
			}
	
 
		}
 
		function write_to_cache($return_array){

			if (!JFile::exists($this->cache_path)){
				// attempt to make the dir
				JFolder::create($this->cache_path, 0777);
			}
 
			if (!JFile::write($this->cache_file, serialize($return_array))){
				echo "<br />Could not save data to cache. Please make sure your cache directory exists and is writable.<br />";
			}
		}

 
		function make_request(){
 
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_URL, $this->gweather_api_url);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 60);

			$this->raw_data = curl_exec ($ch);
			curl_close ($ch);
 
			if (empty($this->raw_data)){
				return false;
			}else{
				return true;
			}
 
		}
		
		#
        function filename_safe($filename) {
            $temp = $filename;
            $temp = strtolower($temp);
            $temp = str_replace(" ", "_", $temp);
            $result = '';
            for ($i=0; $i<strlen($temp); $i++) {
                if (preg_match('([0-9]|[a-z]|_)', $temp[$i])) {
                    $result = $result . $temp[$i];
                }
            }
            return $result;
        }
 
	}
 
?>