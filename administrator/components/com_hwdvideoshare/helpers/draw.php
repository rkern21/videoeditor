<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Process character encoding
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvsDrawFile {

	function generalConfig()
	{
		$db =& JFactory::getDBO();
    	jimport('joomla.filesystem.file');

		$config = "<?php\n";
		$config .= "class hwd_vs_Config{ \n\n";
		$config .= "  var \$instanceConfig = null;\n\n";
		$config .= "  // Member variables\n";
		// print out config
		$query  = 'SELECT *'
				. ' FROM #__hwdvidsgs'
				;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			if ($row->setting == "flvplay_width" && empty($row->value)) { $row->value = "450"; }
			if ($row->setting == "customencode") { $row->value = addslashes($row->value); }
			$config .= "  var \$".$row->setting." = '".$row->value."';\n";
		}
		$config .= "\n  function get_instance(){\n";
		$config .= "    \$instanceConfig = new hwd_vs_Config;\n";
		$config .= "    return \$instanceConfig;\n";
		$config .= "  }\n\n";
		$config .= "}\n";
		$config .= "?>";

		$configFile = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php';
		if (!JFile::write($configFile, $config)) {
			return false;
		}

		return true;
	}

	function serverConfig()
	{
		$db =& JFactory::getDBO();
    	jimport('joomla.filesystem.file');

		$config = "<?php\n";
		$config .= "class hwd_vs_SConfig{ \n\n";
		$config .= "  var \$instanceConfig = null;\n\n";
		$config .= "  // Member variables\n";
		// print out config
		$query  = 'SELECT *'
				. ' FROM #__hwdvidsss'
				;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$config .= "  var \$".$row->setting." = '".$row->value."';\n";
		}
		$config .= "\n  function get_instance(){\n";
		$config .= "    \$instanceConfig = new hwd_vs_SConfig;\n";
		$config .= "    return \$instanceConfig;\n";
		$config .= "  }\n\n";
		$config .= "}\n";
		$config .= "?>";

		$configFile = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php';
		if (!JFile::write($configFile, $config)) {
			return false;
		}

		return true;
	}
    /**
     * Make xml playlist datafile
     *
     * @return       True
     */
    function XMLDataFile($rows, $filename)
    {
		$db =& JFactory::getDBO();
		$c = hwd_vs_Config::get_instance();
    	jimport('joomla.filesystem.file');

		$config = null;
		$config .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$config .= "<playlist version=\"1\">\n";
		$config .= "<title>hwdVideoShare ".$filename." Playlist</title>\n";
		$config .= "<info>http:/xspf.org/xspf-v1.html</info>\n";
		$config .= "<date>".date('Y-m-d H:i:s')."</date>\n";
		$config .= "<trackList>\n";
		$config .= "\n";

		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			$row->title = hwdEncoding::charset_decode_utf_8($row->title);
			$row->title = hwdEncoding::XMLEntities($row->title);

			$row->description = hwdEncoding::charset_decode_utf_8($row->description);
			$row->description = hwdEncoding::XMLEntities($row->description);
			$row->description = hwd_vs_tools::truncateText($row->description, 1000);

			$video_code = explode(",", $row->video_id);
			if (empty($video_code[1]))
			{
				$row->video_id = hwdEncoding::XMLEntities($row->video_id);
			}
			else
			{
				$video_code[0] = hwdEncoding::XMLEntities($video_code[0]);
				$video_code[1] = urlencode($video_code[1]);
				$row->video_id = implode(",", $video_code);
			}

			if (empty($row->video_length))
			{
				$row->video_length = "0:00:00";
			}
			if (!isset($row->username) || empty($row->username))
			{
				$row->username = "_HWDVIDS_INFO_GUEST";
			}
			if (!isset($row->name) || empty($row->name))
			{
				$row->name = "_HWDVIDS_INFO_GUEST";
			}
			if ($row->user_id == 0 || !isset($row->username) || !isset($row->name))
			{
				$row->username = "_HWDVIDS_INFO_GUEST";
				$row->name = "_HWDVIDS_INFO_GUEST";
			}

			$config .= "  <track>\n";
			$config .= "    <id><![CDATA[".$row->id."]]></id>\n";
			$config .= "    <videotitle><![CDATA[".$row->title."]]></videotitle>\n";
			$config .= "    <videocode><![CDATA[".$row->video_id."]]></videocode>\n";
			$config .= "    <videotype><![CDATA[".$row->video_type."]]></videotype>\n";
			$config .= "    <thumbnail><![CDATA[".$row->thumbnail."]]></thumbnail>\n";
			$config .= "    <category><![CDATA[]]></category>\n";
			$config .= "    <category_id><![CDATA[".$row->category_id."]]></category_id>\n";
			$config .= "    <description><![CDATA[".$row->description."]]></description>\n";
			$config .= "    <views><![CDATA[".$row->number_of_views."]]></views>\n";
			$config .= "    <date><![CDATA[".$row->date_uploaded."]]></date>\n";
			$config .= "    <duration><![CDATA[".$row->video_length."]]></duration>\n";
			$config .= "    <rating><![CDATA[".$row->updated_rating."]]></rating>\n";
			if ($c->userdisplay == 1) {
				$config .= "    <uploader><![CDATA[".$row->username."]]></uploader>\n";
			} else {
				$config .= "    <uploader><![CDATA[".$row->name."]]></uploader>\n";
			}
			$config .= "    <uploader_id><![CDATA[".$row->user_id."]]></uploader_id>\n";
			if ($c->cbint !== "0" && !empty($row->avatar)) {
				$avatar = $row->avatar;
			} else {
				$avatar = "";
			}
			$config .= "    <avatar><![CDATA[".$avatar."]]></avatar>\n";
			$config .= "    <comments><![CDATA[".$row->number_of_comments."]]></comments>\n";
			$config .= "    <tags><![CDATA[".addslashes($row->tags)."]]></tags>\n";
			$config .= "  </track>\n";
			$config .= "\n";
		}
		$config .= "</trackList>\n";
		$config .= "</playlist>\n";

		$configFile = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'xml'.DS.$filename.'.xml';
		if (!JFile::write($configFile, $config)) {
			return false;
		}

		return true;
    }
    /**
     * Make xml playlist file
     *
     * @return       True
     */
    function XMLPlaylistFile($rows, $filename)
    {
		$db =& JFactory::getDBO();
		$c = hwd_vs_Config::get_instance();
    	jimport('joomla.filesystem.file');

		$config = null;
		$config .= "<playlist version=\"1\" xmlns=\"http://xspf.org/ns/0/\">\n";
		$config .= "<title>hwdVideoShare Playlist</title>\n";
		$config .= "<info>http:/xspf.org/xspf-v1.html</info>\n";
		$config .= "<date>".date('Y-m-d H:i:s')."</date>\n";
		$config .= "<trackList>\n";
		$config .= "\n";

		// print out playlist
		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			$type = "video";

			if (($row->video_type == "youtube.com" || ($row->video_type == "seyret" && substr($row->video_id, 0, 7) == "youtube")) && ($c->hwdvids_videoplayer_file == "jwflv" || $c->hwdvids_videoplayer_file == "jwflv_v5"))
			{
				$data = @explode(",", $row->video_id);
				if ($row->video_type == "seyret")
				{
					$YTID = $data[1];
				}
				else
				{
					$YTID = $data[0];
				}

				$location = "http://www.youtube.com/watch?v=".$YTID;
				$image = hwd_vs_tools::generatePlayerThumbnail($row);
				$type = "youtube";
			}
			else
			{
				$locations = hwd_vs_tools::generateVideoLocations($row);
				$location = $locations['url'];
				$image = hwd_vs_tools::generatePlayerThumbnail($row);
			}

			if (empty($location)) continue;

			//$title      = hwd_vs_tools::truncateText(strip_tags(hwdEncoding::UNXMLEntities($row->title)), 50);
			//$annotation = hwd_vs_tools::truncateText(strip_tags(hwdEncoding::UNXMLEntities($row->description)), 50);

			$title      = strip_tags(hwdEncoding::UNXMLEntities($row->title));
			$annotation = strip_tags(hwdEncoding::UNXMLEntities($row->description));
			$image = urldecode($image);

		    $config .= "  <track>\n";
		    $config .= "    <location><![CDATA[".$location."]]></location>\n";
		    $config .= "    <image><![CDATA[".$image."]]></image>\n";
		    $config .= "    <title><![CDATA[".$title."]]></title>\n";
		    $config .= "    <annotation><![CDATA[".$annotation."]]></annotation>\n";
			$config .= "	<meta rel='type'>".$type."</meta>\n";
		    $config .= "  </track>\n";
		    $config .= "\n";
		}

		$config .= "</trackList>\n";
		$config .= "</playlist>\n";

		$configFile = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'xml'.DS.'xspf'.DS.$filename.'.xml';
		if (!JFile::write($configFile, $config)) {
			return false;
		}

		return true;
    }
    /**
     * Make xml playlist file
     *
     * @return       True
     */
    function processDynamicCSS($css, $firstWrite=false)
    {
		global $mainframe, $option, $task, $Itemid;
		$db =& JFactory::getDBO();
		$doc = & JFactory::getDocument();
		$app = & JFactory::getApplication();
		$c = hwd_vs_Config::get_instance();
    	jimport('joomla.filesystem.file');

		if($doc->getType() != 'raw')
		{
			$template_element = $app->getUserState( "com_hwdvideoshare.template_element", "default" );
			if (!empty($template_element))
			{
				$c->hwdvids_template_file = $template_element;
			}

			$dynamicCssFile = JPATH_SITE.DS."cache".DS."hwdvs".$c->hwdvids_template_file.DS."hwdvs_".$option."_".$task."_".$Itemid.".css";

			if ($firstWrite)
			{
				/**
				 * $dynamicCssContentWithHeaders = "<?php
				 * header('Content-type: text/css');
				 * header('Cache-Control: no-cache, must-revalidate');
				 * header('Pragma: no-cache');
				 * ?>
				 * $css";
				 */
				if (!JFile::write($dynamicCssFile, $css))
				{
					$doc->addCustomTag("<style type=\"text/css\">$css</style>");
				}
				else
				{
					$doc->addCustomTag("<link rel=\"stylesheet\" href=\"".JURI::root( true )."/cache/hwdvs".$c->hwdvids_template_file."/hwdvs_".$option."_".$task."_".$Itemid.".css\" type=\"text/css\" />");
				}
			}
			else
			{
				if (file_exists($dynamicCssFile))
				{
					$dynamicCssContent = JFile::read($dynamicCssFile);
					$dynamicCssContent.= $css;
					if (!JFile::write($dynamicCssFile, $dynamicCssContent))
					{
						$doc->addCustomTag("<style type=\"text/css\">$css</style>");
					}
				}
				else
				{
					$doc->addCustomTag("<style type=\"text/css\">$css</style>");
				}
			}
		}
    }
}
?>