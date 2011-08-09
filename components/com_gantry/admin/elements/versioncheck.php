<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementVersionCheck extends JElement
{
	var	$_name = 'VersionCheck';
    var $_error = null;

	function fetchElement($name, $value, &$node, $control_name)
	{
		
		// curl check
		if (!function_exists('curl_version')) {
			$upd  = "<span class='download-update'>";
			$upd .= "		<a href='http://www.php.net/manual/en/book.curl.php'>";
			$upd .= "			<span>Learn more about cURL</span>";
			$upd .= "		</a>";
			$upd .= "</span>";

			return "
			<div id='versioncheck' class='update-available'>
				<div id='versioncheck-bar' class='g-title'><b>ERROR - </b> cURL is required in order to check Gantry updates.".$upd."</div>
			</div>";
		}
		
        gantry_import('core.gantryini');
        gantry_import('core.utilities.gantryxml');
        
		global $gantry;

        $klass = "no-updates";
        $output = "";
        $statusText = "";

        $now = &JFactory::getDate();
        $cache_file = $gantry->custom_dir.DS.'gantry_version';


        if (file_exists($cache_file) && is_file($cache_file) && is_readable($cache_file)) {
            $old_cache_data = GantryINI::read($cache_file, $this->_name, 'check');
        }
        else {
            $old_cache_data['version'] = GANTRY_VERSION;
            $old_cache_data['date'] = 1;
            $old_cache_data['link'] = '';
        }

         $old_cache_date = &JFactory::getDate($old_cache_data['date']);

        // only grab from the web if its been more the 24 hours since the last check        
        if (($old_cache_date->toUNIX()+(24*60*60)) < $now->toUNIX()) {
            $data = $this->_get_url_contents('http://code.google.com/feeds/p/gantry-framework/downloads/basic');

            if (!empty($this->_error)){
                $output = "<span id='version-warning'> [ <strong>Error checking version:</strong> ".$this->_error."  ]</span>";
                $latest_version = GANTRY_VERSION;
            }
            else {
                $xml = new GantryXML();
                $xml->loadString($data);

                foreach ($xml->document->entry as $entry) {
                    $title = (string) $entry->title[0]->data();
                    if (preg_match('/gantry_joomla_framework-(.*).zip/', $title, $matches)){
                        $linkattribs = $entry->link[0]->attributes();
                        $link = (string) $linkattribs['href'];
                        $latest_version = $matches[1];
                        $cache_data[$this->_name]['check']['version'] =  $latest_version;
                        $cache_data[$this->_name]['check']['link'] =  $link;
                        $cache_data[$this->_name]['check']['date'] =  $now->toUNIX();
                        GantryINI::write($cache_file,$cache_data,false);
                        break;
                    }
                }
            }
        }
        else {
            $latest_version = $old_cache_data['version'];
            $link = $old_cache_data['link'];
        }

		$upd = "";
       	if ($latest_version != GANTRY_VERSION){
            $klass="update-available";
            $statusText = "- Notice: <b>New Version Available!</b>";

			$upd  = "<span class='download-update'>";
			$upd .= "		<a href='".$link."'>";
			$upd .= "			<span>Download Gantry ".$latest_version."</span>";
			$upd .= "		</a>";
			$upd .= "</span>";
        } else {
			$upd  = "<span class='download-update'>";
			$upd .= "		<span>No update available</span>";
			$upd .= "</span>";
		}

		return "
		<div id='versioncheck' class='".$klass."'>
			<div id='versioncheck-bar' class='g-title'>Gantry v".GANTRY_VERSION." ".JText::_($statusText).$upd."</div>
		</div>";		
	}
    /**
     * Call a URL
     */
    function _get_url_contents($url) {
        $crl = curl_init();
        $timeout = 5;
        curl_setopt($crl, CURLOPT_URL, $url);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($crl, CURLOPT_USERAGENT, "Mozilla/4.0");
        curl_setopt($crl, CURLOPT_FAILONERROR, true);
        $ret = curl_exec($crl);
        if($ret === false || strlen($ret) == 0)
        {
            $this->_error = curl_error($crl);
            if (empty($this->_error)) {
                $this->_error = "Unable to get Gantry version feed from url " . $url;
            }
        }
        curl_close($crl);
        return $ret;
    }
}