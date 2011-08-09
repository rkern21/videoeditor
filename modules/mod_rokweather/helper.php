<?php
/**
 * @version   1.0 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'modules'.DS.'mod_rokweather'.DS.'googleweather.class.php');

class modRokWeatherHelper
{
	
	function getWeather($location,$icon_url,&$params) {
	    $output = "";
	    $w = new googleWeather();
       	$w->enable_cache = $params->get("enable_cache",1);
       	$w->cache_path = JPATH_CACHE;
        $ar_data = $w->get_weather_data($location); 
        
        
        $weather = new stdClass();
        $weather->icon = $icon_url."/blue/images/weather/unknown.png";
        $weather->current_temp_f = "?";
        $weather->current_temp_c = "?";

        
        
        if (isset($ar_data['error'])) { 
       	     $weather->error = $ar_data['error'];
       	 } else {
       	     
    	     $weather->city = $ar_data['forecast_info']['city'];
    	     $weather->zip = $ar_data['forecast_info']['zip'];
    	     $weather->date = $ar_data['forecast_info']['date'];
    	     $weather->date_time = $ar_data['forecast_info']['date_time'];
    	     $weather->units = $ar_data['forecast_info']['units'];
    	     
       	     $weather->current_temp_f = $ar_data['current_conditions']['temp_f'];
       	     $weather->current_temp_c = $ar_data['current_conditions']['temp_c'];
       	     $weather->current_condition = $ar_data['current_conditions']['condition'];
             $weather->current_humidity = $ar_data['current_conditions']['humidity'];
             $weather->current_wind = $ar_data['current_conditions']['wind'];
             if (strpos($ar_data['current_conditions']['icon'],".png")>0) {
     	         $weather->icon = $icon_url."/blue".$ar_data['current_conditions']['icon'];
     	     }
             
             if (is_array($ar_data['forecast'])) {
    	         $weather->forecast = $ar_data['forecast'];
    	     }
    	     
    	     if ($params->get("enable_location_cookie",1)==1) {
    	         setcookie ("rokweather_location", $location, time()+31536000, '/', false);   
    	     }

   	     }
   	     return $weather;
          
	}
	
	function getFTemp($temp,$units) {
        if ($units=="SI") {
            return intval((9/5)*intval($temp)+32);
        } else {
            return $temp;
        }
    }
	
	function getCTemp($temp,$units) {
	    if ($units=="SI") {
	        return $temp;
	    } else {
	        return intval((5/9)*(intval($temp)-32));
	    }
	}
	
	function testWeather($location,&$params) {
    	 $w = new googleWeather();
       	 $w->enable_cache = $params->get("enable_cache",1);
       	 $w->cache_path = JPATH_CACHE;
       	 $ar_data = $w->get_weather_data($location);
       	 var_dump($ar_data);
	}
	
	function _getJSVersion() {
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
