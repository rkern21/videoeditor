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
 * This class is the HTML generator for hwdVideoShare frontend
 *
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
class HWDVS_xmlParse
{
    /**
     *
     */
    function parse($fname)
    {
		global $usercount, $userdata;
    	$xml_file = JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'xml'.DS.$fname.'.xml';

		//if (!($fp=@fopen($xml_file, "r"))) die ("Couldn't open XML.");
		if (!($fp=@fopen($xml_file, "r"))) { return; }

		$usercount=0;
		$userdata=array();
		$state='';

		if (!($xml_parser = xml_parser_create())) die("Couldn't create parser.");

		xml_set_object($xml_parser, $this);
		xml_set_element_handler( $xml_parser, "startElementHandler", "endElementHandler");
		xml_set_character_data_handler( $xml_parser, "characterDataHandler");

		//while( $data = fread($fp, 4096)){
		while( $data = fread($fp, 8192))
		{
			if(!xml_parse($xml_parser, $data, feof($fp)))
			{
				break;
			}
		}
		xml_parser_free($xml_parser);

		return $userdata;
	}
    /**
     *
     */
	function startElementHandler ($parser,$name,$attrib)
	{
		global $usercount;
		global $userdata;
		global $state;

		switch ($name)
		{
			case $name=="NAME" : {
				$userdata[$usercount]["first"] = $attrib["FIRST"];
				$userdata[$usercount]["last"] = $attrib["LAST"];
				$userdata[$usercount]["nick"] = $attrib["NICK"];
				$userdata[$usercount]["title"] = $attrib["TITLE"];
				break;
			}

			default : {
				$state=$name;
				break;
			}
		}
	}
    /**
     *
     */
    function endElementHandler ($parser,$name)
    {
		global $usercount;
		global $userdata;
		global $state;
		$state='';
		if($name=="TRACK") {$usercount++;}
	}
    /**
     *
     */
	function characterDataHandler ($parser, $data)
	{
		global $usercount;
		global $userdata;
		global $state;

		if (!$state) {return;}
		if ($state=="ID") { $userdata[$usercount]["id"] = $data;}
		if ($state=="VIDEOTITLE") { $userdata[$usercount]["videotitle"] = $data;}
		if ($state=="VIDEOCODE") { $userdata[$usercount]["videocode"] = $data;}
		if ($state=="VIDEOTYPE") { $userdata[$usercount]["videotype"] = $data;}
		if ($state=="THUMBNAIL") { $userdata[$usercount]["thumbnail"] = $data;}
		if ($state=="LOCATION") { $userdata[$usercount]["location"] = $data;}
		if ($state=="CATEGORY") { $userdata[$usercount]["category"] = $data;}
		if ($state=="CATEGORY_ID") { $userdata[$usercount]["category_id"] = $data;}
		if ($state=="DESCRIPTION") { $userdata[$usercount]["description"] = $data;}
		if ($state=="VIEWS") { $userdata[$usercount]["views"] = $data;}
		if ($state=="DATE") { $userdata[$usercount]["date"] = $data;}
		if ($state=="DURATION") { $userdata[$usercount]["duration"] = $data;}
		if ($state=="RATING") { $userdata[$usercount]["rating"] = $data;}
		if ($state=="UPLOADER") { $userdata[$usercount]["uploader"] = $data;}
		if ($state=="UPLOADER_ID") {$userdata[$usercount]["uploader_id"] = $data;}
		if ($state=="COMMENTS") {$userdata[$usercount]["comments"] = $data;}
		if ($state=="TAGS") {$userdata[$usercount]["tags"] = $data;}
	}
}
?>
