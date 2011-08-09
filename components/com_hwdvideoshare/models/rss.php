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
 * @version    1.1.4 Alpha RC2.13
 */
class hwd_vs_rss
{
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function feeds()
	{
	global $mainframe, $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();

		$feed = JRequest::getCmd( 'feed' );

		// switch for feed function
		switch ($feed)
		{
			case 'recent':
				hwd_vs_rss::recent();
			break;

			default:
				hwd_vs_rss::recent();
			break;
		}
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function recent()
	{
	global $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$jconfig = new jconfig();
		$my = & JFactory::getUser();

        // sql search filters
        $where = ' WHERE a.published = 1';
        $where .= ' AND a.approved = "yes"';
        if (!$my->id) {
        $where .= ' AND a.public_private = "public"';
        }

        // get videos
        $query = 'SELECT a.*'
                . ' FROM #__hwdvidsvideos AS a'
                . $where
                . ' ORDER BY a.date_uploaded DESC'
                . ' LIMIT 0, 50'
                ;
        $db->SetQuery($query);
        $rows = $db->loadObjectList();

		$link_rss = JURI::root().'index.php?option=com_hwdvideoshare&task=rss&feed=recent';
		$link_rss = str_replace("&", "&amp;", $link_rss);

echo '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>

    <title>'.$jconfig->sitename.'</title>
    <link>'.JURI::root().'</link>
    <description>Recent Videos</description>
    <category>Video</category>
    <atom:link href="'.$link_rss.'" rel="self" type="application/rss+xml" />
    ';

	for ($i=0, $n=count($rows); $i < $n; $i++) {
		$row = $rows[$i];
		$title = stripslashes($row->title);
		$description = stripslashes($row->description);
		$category = html_entity_decode(hwd_vs_tools::generateCategory($row->category_id));

		$link_video = JURI::root()."index.php?option=com_hwdvideoshare&task=viewvideo&Itemid=".$Itemid."&video_id=".$row->id;
		$link_video = 'http://'.$_SERVER['HTTP_HOST'].JRoute::_("index.php?option=com_hwdvideoshare&task=viewvideo&Itemid=$Itemid&video_id=$row->id");

		$thumbnailURL = hwd_vs_tools::generateThumbnailURL( $row->id, $row->video_id, $row->video_type, $row->thumbnail );

		if ($row->video_type == 'local') {

		    $thumbnailURL = 'http://'.$_SERVER['HTTP_HOST'].$thumbnailURL;

		} else {

			$pos = strpos($thumbnailURL, "http");
			if ($pos === false) {
				$thumbnailURL = 'http://'.$_SERVER['HTTP_HOST'].$thumbnailURL;
			} else {
				$thumbnailURL = $thumbnailURL;
			}

	    }

		$thumbnailURL = str_replace("&", "&amp;", $thumbnailURL);
		$downloadURL = $thumbnailURL;

		$downloadSIZE = "999";

		date_default_timezone_set('GMT');

echo '<item>
      <title><![CDATA['.stripslashes($title).']]></title>
      <link><![CDATA['.$link_video.']]></link>
      <description><![CDATA[<img src="'.$thumbnailURL.'" style="float:right;padding:10px;" width="120" height="90" />&#160;'.stripslashes($description).']]></description>
      <category><![CDATA['.stripslashes($category).']]></category>
      <pubDate>'.date('D, d M Y H:i:s e', strtotime($row->date_uploaded)).'</pubDate>
      <guid>'.$link_video.'</guid>
      <enclosure url="'.$downloadURL.'" length="'.$downloadSIZE.'" type="image/jpeg" />
    </item>
    ';

	}
echo '
  </channel>
</rss>';
		exit;
        // <enclosure url="'.$downloadURL.'" length="'.$downloadSIZE.'" type="image/jpeg" />
		// <enclosure url="'.$downloadURL.'" length="'.$downloadSIZE.'" type="video/x-flv" />
	}
}
?>