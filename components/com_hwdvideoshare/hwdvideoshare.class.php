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
define( '_HWD_VS_PLUGIN_COMPS', 214 );

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
class hwdvids_video extends JTable
{
    var $id = null;
  	var $video_type = null;
  	var $video_id = null;
  	var $title = null;
  	var $description = null;
  	var $tags = null;
  	var $category_id = null;
    var $date_uploaded = null;
  	var $video_length = null;
  	var $allow_comments = null;
  	var $allow_embedding = null;
  	var $allow_ratings = null;
  	var $rating_number_votes = null;
  	var $rating_total_points = null;
  	var $updated_rating = null;
  	var $public_private = null;
  	var $thumb_snap = null;
  	var $thumbnail = null;
  	var $approved = null;
  	var $number_of_views = null;
  	var $number_of_comments = null;
  	var $age_check = null;
  	var $user_id = null;
  	var $password = null;
  	var $featured = null;
  	var $ordering = null;
  	var $checked_out = null;
  	var $checked_out_time = null;
  	var $published = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_video(&$db){
        parent::__construct( '#__hwdvidsvideos', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvids_favs extends JTable
{
	var $id = null;
	var $userid = null;
	var $videoid = null;
	var $date = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_favs(&$db){
        parent::__construct( '#__hwdvidsfavorites', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvids_flagvid extends JTable
{
 	var $id = null;
 	var $userid = null;
 	var $videoid = null;
 	var $status = null;
 	var $ignore = null;
 	var $date = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_flagvid(&$db){
        parent::__construct( '#__hwdvidsflagged_videos', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC2.13
 */
class hwdvids_channel extends JTable
{
 	var $id = null;
 	var $channel_name = null;
 	var $channel_description = null;
 	var $channel_thumbnail = null;
 	var $public_private = null;
 	var $date_created = null;
 	var $date_modified = null;
 	var $user_id = null;
 	var $views = null;
 	var $checked_out = null;
 	var $checked_out_time = null;
 	var $featured = null;
 	var $published = null;
  	var $params = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_channel(&$db){
        parent::__construct( '#__hwdvidschannels', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC2.13
 */
class hwdvids_cats extends JTable
{
 	var $id = null;
 	var $parent = null;
 	var $category_name = null;
 	var $category_description = null;
 	var $date = null;
 	var $access_b_v = null;
 	var $access_u_r = null;
 	var $access_v_r = null;
 	var $access_u = null;
 	var $access_lev_u = null;
 	var $access_v = null;
 	var $access_lev_v = null;
  	var $thumbnail = null;
 	var $num_vids = null;
 	var $num_subcats = null;
    var $order_by = null;
    var $ordering = null;
    var $checked_out = null;
 	var $checked_out_time = null;
 	var $published = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_cats(&$db){
        parent::__construct( '#__hwdvidscategories', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvids_flaggroup extends JTable
{
 	var $id = null;
 	var $userid = null;
 	var $groupid = null;
 	var $status = null;
 	var $ignore = null;
 	var $date = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_flaggroup(&$db){
        parent::__construct( '#__hwdvidsflagged_groups', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvids_groupmember extends JTable
{
 	var $id = null;
 	var $memberid = null;
 	var $date = null;
 	var $group_admin = null;
 	var $groupid = null;
 	var $approved = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_groupmember(&$db){
        parent::__construct( '#__hwdvidsgroup_membership', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvids_group extends JTable
{
 	var $id = null;
 	var $group_name = null;
 	var $public_private = null;
 	var $date = null;
 	var $allow_comments = null;
 	var $require_approval = null;
 	var $group_description = null;
 	var $featured = null;
 	var $adminid = null;
  	var $thumbnail = null;
 	var $total_members = null;
 	var $total_videos = null;
 	var $ordering = null;
 	var $checked_out = null;
 	var $checked_out_time = null;
 	var $published = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_group(&$db){
        parent::__construct( '#__hwdvidsgroups', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvids_playlist extends JTable
{
 	var $id = null;
 	var $playlist_name = null;
 	var $playlist_description = null;
 	var $playlist_data = null;
 	var $public_private = null;
 	var $date_created = null;
 	var $date_modified = null;
 	var $allow_comments = null;
 	var $user_id = null;
 	var $thumbnail = null;
 	var $total_videos = null;
 	var $checked_out = null;
 	var $checked_out_time = null;
 	var $featured = null;
 	var $published = null;
 	var $params = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_playlist(&$db){
        parent::__construct( '#__hwdvidsplaylists', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvids_groupvideo extends JTable
{
 	var $id = null;
 	var $videoid = null;
 	var $groupid = null;
 	var $memberid = null;
 	var $date = null;
 	var $published = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_groupvideo(&$db){
        parent::__construct( '#__hwdvidsgroup_videos', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvids_rating extends JTable
{
 	var $id = null;
 	var $userid = null;
 	var $videoid = null;
 	var $ip = null;
 	var $date = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvids_rating(&$db){
        parent::__construct( '#__hwdvidsrating', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvidslogs_views extends JTable
{
 	var $id = null;
 	var $videoid = null;
 	var $userid = null;
 	var $date = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvidslogs_views(&$db){
        parent::__construct( '#__hwdvidslogs_views', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvidslogs_votes extends JTable
{
 	var $id = null;
 	var $videoid = null;
 	var $userid = null;
 	var $vote = null;
 	var $date = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvidslogs_votes(&$db){
        parent::__construct( '#__hwdvidslogs_votes', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvidslogs_favours extends JTable
{
 	var $id = null;
 	var $videoid = null;
 	var $userid = null;
 	var $favour = null;
 	var $date = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvidslogs_favours(&$db){
        parent::__construct( '#__hwdvidslogs_favours', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvidslogs_archive extends JTable
{
 	var $id = null;
 	var $videoid = null;
 	var $views = null;
 	var $number_of_votes = null;
 	var $sum_of_votes = null;
 	var $rating = null;
 	var $favours = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvidslogs_archive(&$db){
        parent::__construct( '#__hwdvidslogs_archive', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvidsantileech extends JTable
{
 	var $index = null;
 	var $expiration = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvidsantileech(&$db){
        parent::__construct( '#__hwdvidsantileech', 'index', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdvidssubs extends JTable
{
 	var $id = null;
 	var $userid = null;
 	var $memberid = null;
 	var $date = null;

    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvidssubs(&$db){
        parent::__construct( '#__hwdvidssubs', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4
 */
class hwdvidsplugin extends JTable
{
	/** @var int */
	var $id=null;
	/** @var varchar */
	var $name=null;
	/** @var varchar */
	var $element=null;
	/** @var varchar */
	var $type=null;
	/** @var varchar */
	var $folder=null;
	/** @var varchar */
	var $access=null;
	/** @var int */
	var $ordering=null;
	/** @var tinyint */
	var $published=null;
	/** @var tinyint */
	var $iscore=null;
	/** @var tinyint */
	var $client_id=null;
	/** @var int unsigned */
	var $checked_out=null;
	/** @var datetime */
	var $checked_out_time=null;
	/** @var string */
	var $website=null;
	/** @var int */
	var $playlist_compat=null;
	/** @var text */
	var $params=null;
    /**
     * Constructor
     * @param database A database connector object
     */
	function hwdvidsplugin(&$db){
        parent::__construct( '#__hwdvidsplugin', 'id', $db );
	}
}

/**
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC3.5
 */
class hwd_vs_tools {
    /**
     * Truncates a php string to $length with a suffix
     *
     * @param string $text  the php input string
     * @param int    $length  the truncation length
     * @param string $suffix(optional)  the string to add to the trucated string
     * @return       $code  the trucated string
     */
	function truncateText( $text, $length, $suffix = "...")
	{
		$text = stripslashes($text);
		if (strlen($text) < $length )
		{
			$code = $text;
		}
		else
		{
			$code = stripslashes($text);
			$code = substr($code,0,$length);

			$gap = strrpos($code,' ');
			if (!empty($gap) && $gap <= $length)
			{
				$code = substr($code,0,$gap);
			}

			$pos = strrpos($code, "&#");
			$acc = strlen($code)-6;

			if ($pos === false)
			{
				$code = $code.$suffix;
			}
			else
			{
				if ($pos > $acc)
				{
					$code = substr($code,0,$pos);
					$code = $code.$suffix;
				}
				else
				{
					$code = $code.$suffix;
				}
			}
		}
		return $code;
	}
    /**
     * Outputs a stop message for frontend user, generally
     * used for error/success messages
     *
     * @param int    $active_menu  the number of the current active menu (1/2/3/4)
     * @param int    $active_usermenu  the number of the current active user navigation menu (0)
     * @param string $title  the title of the message page
     * @param string $message  the body of the message page
     * @param string $icon(optional)  the name of the icon to display
     * @param int    $backlink(optional) display javascript backlink (1/0)
     * @return       Nothing
     */
	function infoMessage( $active_menu, $active_usermenu, $title=_HWDVIDS_TITLE_ERROR, $message ,$icon=null, $backlink=0, $full=1)
	{
		global $smartyvs, $hwdvsAjaxPlayer;

        if ($hwdvsAjaxPlayer)
        {
        	$full = "0";
        }

		hwd_vs_tools::generateActiveLink($active_menu);
		$smartyvs->assign("title", $title);
		$smartyvs->assign("message", $message);
		if ($full == 1) { $smartyvs->assign("full", $message); }
		$smartyvs->assign("icon", URL_HWDVS_IMAGES."icons/".$icon);
		if ($backlink) {
		$smartyvs->assign("backlink", "<a href=\"javascript: history.go(-1)\">"._HWDVIDS_BACKLINK."</a><br /><br />");
		}

		$uri = JFactory::getURI();
		$url = $uri->toString(array('path', 'query', 'fragment'));
		$smartyvs->assign("session_token", JHTML::_( 'form.token' ));
		$smartyvs->assign("session_return", base64_encode($url));

		$smartyvs->display('infomessage.tpl');
		return;
    }
    /**
     * Generates a link to category using $cat_id, and generates the
     * category name if necessary
     *
     * @param int    $cat_id  the category id
     * @param string $category(optional)  the name of the category
     * @return       $code  the html category link
     */
	function generateCategoryLink( $cat_id, $category=null, $hwd_vs_itemid=null )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$code = null;

		if ($hwd_vs_itemid == null) { $hwd_vs_itemid=$hwdvsItemid; }

		if ($cat_id == 0) {
			return _HWDVIDS_TEXT_NONE;
		}
		$code.= "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewcategory&Itemid=".$hwd_vs_itemid."&cat_id=".$cat_id)."\">";
		if (isset($category)) {
			$code.= hwd_vs_tools::truncateText($category, $c->truntitle);
		} else {
			$code.= hwd_vs_tools::generateCategory( $cat_id );
		}
		$code.= "</a>";
		return $code;
    }
    /**
     * Generates a link to category using $cat_id, and generates the
     * category name if necessary
     *
     * @param int    $video_id  the category id
     * @param string $video(optional)  the name of the video
     * @return       $code  the html video link
     */
	function generateVideoLink( $video_id, $video=null, $hwdvs_itemid=null, $onclick_js=null, $truntitle=null )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		if ($hwdvs_itemid == null) { $hwdvs_itemid = $hwdvsItemid; }

		if (!empty($onclick_js)) {
			$onclick_txt="onclick=\"".$onclick_js."(".$video_id.");return false;\"";
			$link="#video";
		} else {
			$onclick_txt="";
			$link=JRoute::_("index.php?option=com_hwdvideoshare&task=viewvideo&Itemid=".$hwdvs_itemid."&video_id=".$video_id);
		}

		$code = null;
		$code.= "<a href=\"".$link."\" ".$onclick_txt.">";
		if (isset($video)) {
			$code.= hwd_vs_tools::truncateText($video, $truntitle);
		} else {
			$code.= "0";
		}
		$code.= "</a>";
		return $code;
    }
    /**
     * Generates the name of a category from the $cat_id
     *
     * @param int    $cat_id  the joomla component name
     * @return       $code  the name of the category
     */
	function generateCategory( $cat_id ) {
		global $catnames;
		$c = hwd_vs_Config::get_instance();
  		$db =& JFactory::getDBO();

		if ($cat_id == 0) {
			$code = _HWDVIDS_TEXT_NONE;
		}
		if (!isset($catnames)) {
			// get category name
			$query = 'SELECT id, category_name FROM #__hwdvidscategories';
			$db->SetQuery( $query );
			$catnames = $db->loadObjectList();
 		}
		$code = _HWDVIDS_TEXT_NONE;
		for ($i=0, $n=count($catnames); $i < $n; $i++) {
			$row = $catnames[$i];
			if ($row->id == $cat_id) {
				$code = $row->category_name;
				break;
			}
		}
		return $code;
    }
    /**
     * Generates a linked thumbnail for category id $row->id
     *
     * @param array  $row  the category details from sql
     * @param int    $k  current css tag
     * @param int    $width  width of the thumbnail
     * @param int    $height  height of the thumbnail
     * @param string $class  class for thumbnail (not link)
     * @param string $target(optional)  the target for the link
     * @return       $code
     */
	function generateCategoryThumbnailLink( $row, $k, $width, $height, $class, $target="_top", $passedItemid=null)
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();

		if (!empty($passedItemid))
		{
			$hwdvsItemid = $passedItemid;
		}

		if ($row->thumbnail == '') {
			$query = 'SELECT *'
						. ' FROM #__hwdvidsvideos'
						. ' WHERE category_id = '.$row->id
						. ' AND published = 1'
						. ' AND approved = "yes"'
						. ' ORDER BY date_uploaded DESC'
						. ' LIMIT 0, 1'
						;
			$db->SetQuery($query);
			$latestcatvid = $db->loadObject();
			if (empty($latestcatvid->id)) {$latestcatvid->id=null;}
			if (empty($latestcatvid->video_id)) {$latestcatvid->video_id=null;}
			if (empty($latestcatvid->video_type)) {$latestcatvid->video_type=null;}
			if (empty($latestcatvid->thumbnail)) {$latestcatvid->thumbnail=null;}
			$code = null;
			$code.= "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewcategory&Itemid=".$hwdvsItemid."&cat_id=".$row->id)."\">";
			$code.= hwd_vs_tools::generateThumbnail( $latestcatvid->id, $latestcatvid->video_id, $latestcatvid->video_type, $latestcatvid->thumbnail, $k, $width, $height, $class );
			$code.= "</a>";
		} else {
			$code = null;
			$code.= "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewcategory&Itemid=".$hwdvsItemid."&cat_id=".$row->id)."\">";
			$code.= hwd_vs_tools::generateThumbnail( null, null, "category", $row->thumbnail, $k, $width, $height, $class );
			$code.= "</a>";
		}
		return $code;
    }
    /**
     * Generates a linked thumbnail for group id $row->id
     *
     * @param array  $row  the group details from sql
     * @param int    $k  current css tag
     * @param int    $width  width of the thumbnail
     * @param int    $height  height of the thumbnail
     * @param string $class  class for thumbnail (not link)
     * @param string $target(optional)  the target for the link
     * @return       $code
     */
	function generateGroupThumbnailLink( $row, $k, $width, $height, $class, $target="_top")
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();

		$query = 'SELECT a.video_id, a.id, a.video_type, a.thumbnail'
				. ' FROM #__hwdvidsvideos AS a'
				. ' LEFT JOIN #__hwdvidsgroup_videos AS l ON l.videoid = a.id'
				. ' WHERE l.groupid = '.$row->id
				. ' AND a.published = 1'
				. ' AND a.approved = "yes"'
				. ' ORDER BY a.date_uploaded'
				. ' LIMIT 0, 1'
				;
		$db->SetQuery($query);
		$group_video = $db->loadObject();
		if (empty($group_video->id)) { $group_video->id=null; }
		if (empty($group_video->video_id)) { $group_video->video_id=null; }
		if (empty($group_video->video_type)) { $group_video->video_type=null; }
		if (empty($group_video->thumbnail)) { $group_video->thumbnail=null; }
		$code = null;
		$code.= "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewgroup&Itemid=".$hwdvsItemid."&group_id=".$row->id)."\">";
		$code.= hwd_vs_tools::generateThumbnail( $group_video->id, $group_video->video_id, $group_video->video_type, $group_video->thumbnail, $k, $width, $height, $class );
		$code.= "</a>";
		return $code;
		return $code;
    }
    /**
     * Generates a linked thumbnail for group id $row->id
     *
     * @param array  $row  the group details from sql
     * @param int    $k  current css tag
     * @param int    $width  width of the thumbnail
     * @param int    $height  height of the thumbnail
     * @param string $class  class for thumbnail (not link)
     * @param string $target(optional)  the target for the link
     * @return       $code
     */
	function generatePlaylistThumbnailLink( $row, $k, $width, $height, $class, $target="_top")
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();

		if (!empty ($row->playlist_data))
		{
			$videos = explode(",", $row->playlist_data);
			$video_id = intval(@$videos[0]);

			$query = "SELECT video_id, id, video_type, thumbnail"
					. " FROM #__hwdvidsvideos"
					. " WHERE id = $video_id"
					. " AND published = 1"
					. " AND approved = \"yes\""
					;
			$db->SetQuery($query);
			$playlist_video = $db->loadObject();
		}
		else
		{
			$playlist_video = null;
		}

		if (empty($playlist_video->id)) { $playlist_video->id=null; }
		if (empty($playlist_video->video_id)) { $playlist_video->video_id=null; }
		if (empty($playlist_video->video_type)) { $playlist_video->video_type=null; }
		if (empty($playlist_video->thumbnail)) { $playlist_video->thumbnail=null; }

		$code = null;
		$code.= "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewPlaylist&Itemid=".$hwdvsItemid."&playlist_id=".$row->id)."\">";
		$code.= hwd_vs_tools::generateThumbnail( $playlist_video->id, $playlist_video->video_id, $playlist_video->video_type, $playlist_video->thumbnail, $k, $width, $height, $class );
		$code.= "</a>";
		return $code;
		return $code;
    }
    /**
     * Generates a linked thumbnail for video id $video_id
     *
     * @param int    $video_id  the id of the video
     * @param string $video_code  the name of the video file (excluding ext)
     * @param string $video_type  the video type tag
     * @param int    $k  current css tag
     * @param int    $width  width of the thumbnail
     * @param int    $height  height of the thumbnail
     * @param string $class  class for thumbnail (not link)
     * @param string $target(optional)  the target for the link
     * @return       $code
     */
	function generateVideoThumbnailLink( $video_id, $video_code, $video_type, $video_thumbnail, $k, $width, $height, $class, $target="_top", $hwdvs_itemid=null, $onclick_js=null, $tooltip_data=null, $lightbox=false, $video_duration=null)
	{
		global $option, $hwdvsItemid, $mainframe, $smartyvs;
		$doc = & JFactory::getDocument();
		$c = hwd_vs_Config::get_instance();

		if ($hwdvs_itemid == null) { $hwdvs_itemid = $hwdvsItemid; }

		if (!empty($onclick_js))
		{
			$onclick_txt="onclick=\"".$onclick_js."(".$video_id.");return false;\"";
			$link="#video";
		}
		else
		{
			$onclick_txt="";
			$link=JRoute::_("index.php?option=com_hwdvideoshare&task=viewvideo&Itemid=".$hwdvs_itemid."&video_id=".$video_id);
		}

		if (!empty($lightbox)) {

			$link=JRoute::_("index.php?option=com_hwdvideoshare&task=grabajaxplayer&Itemid=".$hwdvs_itemid."&video_id=".$video_id."&template=playeronly");

			if (!defined( 'HWDVS_LB' )) {
				define( 'HWDVS_LB', 1 );
				$doc->addCustomTag('<script type="text/javascript" src="'.JURI::root(true).'/components/com_hwdvideoshare/assets/js/overlay.js"></script>');
				$doc->addCustomTag('<script type="text/javascript" src="'.JURI::root(true).'/components/com_hwdvideoshare/assets/js/multibox.js"></script>');
				$doc->addCustomTag('<link rel="stylesheet" href="'.JURI::root(true).'/components/com_hwdvideoshare/assets/css/multibox.css" media="screen,projection" type="text/css" />');

				if (substr($lightbox, 0, 2) == "mb")
				{
					$smartyvs->assign("print_mb_initialize", 1);
					$smartyvs->assign("mb_id", $lightbox);
				}
			}

			$v_width = $c->flvplay_width;
			$v_height = intval($v_width*$c->var_fb)+20;

			$code = null;
			$code.= '<a href="'.$link.'" class="'.$lightbox.'" title="" rel="width:'.$v_width.',height:'.$v_height.'">';
			$code.= hwd_vs_tools::generateThumbnail( $video_id, $video_code, $video_type, $video_thumbnail, $k, $width, $height, $class, $tooltip_data, $video_duration );
			$code.= '</a>';

  			return $code;

		}

		$code = null;
		$code.= "<a href=\"".$link."\" ".$onclick_txt.">";
		$code.= hwd_vs_tools::generateThumbnail( $video_id, $video_code, $video_type, $video_thumbnail, $k, $width, $height, $class, $tooltip_data, $video_duration );
		$code.= "</a>";

		return $code;
    }
    /**
     * Generates the video url for the Permalink
     *
     * @param array  $row  the group details from sql
     * @return       $code  the Permalink
     */
	function generateVideoUrl( $row )
	{
		global $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();

		$code = null;
		if ($c->showvurl == "1") {
			$code.= JURI::root()."index.php?option=com_hwdvideoshare&amp;task=viewvideo&amp;Itemid=".$hwdvsItemid."&amp;video_id=".$row->id;
			$smartyvs->assign("showShareButton", 1);
			$smartyvs->assign("print_videourl", 1);
		}

		return $code;
    }
    /**
     * Generates the array of information for a standard group list from sql queries
     *
     * @param array  $rows  the list from a standard sql queries
     * @return       $code  the array prepared for Smarty template
     */
	function generateGroupListFromSql( $rows )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code = array();
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			if (!isset($row->avatar)) { $row->avatar=null; }

			$code[$i]->thumbnail = hwd_vs_tools::generateGroupThumbnailLink($row, $k, null, null, null);
			$code[$i]->avatar = hwd_vs_tools::generateAvatar($row->adminid, $row->avatar, $k, null, null, null);
			$code[$i]->grouptitle = hwd_vs_tools::generateGroupLink($row->id, $row->group_name);
			$code[$i]->groupdescription = hwd_vs_tools::truncateText(strip_tags($row->group_description), $c->trunvdesc);
			$code[$i]->totalmembers = $row->total_members;
			$code[$i]->totalvideos = $row->total_videos;
			$code[$i]->administrator = hwd_vs_tools::generateUserFromID($row->adminid, $row->username, $row->name);
			$code[$i]->groupmembership = hwd_vs_tools::generateGroupMembershipStatus($row);
			$code[$i]->reportgroup = hwd_vs_tools::generateReportGroupButton($row);
			$code[$i]->datecreated = $row->date;
			$code[$i]->deletegroup = hwd_vs_tools::generateDeleteGroupLink($row);
			$code[$i]->editgroup = hwd_vs_tools::generateEditGroupLink($row);
			$code[$i]->k = $k;
			$k = 1 - $k;
		}
		return $code;
    }
    /**
     * Generates the array of information for a standard group list from sql queries
     *
     * @param array  $rows  the list from a standard sql queries
     * @return       $code  the array prepared for Smarty template
     */
	function generateChannelListFromSql( $rows )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code = array();
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$code[$i]->channel_name = $row->channel_name;
			$code[$i]->channel_link = JRoute::_("index.php?option=com_hwdvideoshare&Itemid=$hwdvsItemid&task=viewchannel&user_id=$row->user_id");
			$code[$i]->channel_description = $row->channel_description;
			$code[$i]->deletechannel = null;
			$code[$i]->editchannel = hwd_vs_tools::generateEditChannelLink($row);
			$code[$i]->k = $k;
			$k = 1 - $k;
		}
		return $code;
    }
    /**
     * Generates the array of information for a standard group list from sql queries
     *
     * @param array  $rows  the list from a standard sql queries
     * @return       $code  the array prepared for Smarty template
     */
	function generatePlaylistListFromSql( $rows )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code = array();
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			if (!isset($row->avatar)) { $row->avatar=null; }

			$code[$i]->thumbnail = hwd_vs_tools::generatePlaylistThumbnailLink($row, $k, null, null, null);
			$code[$i]->avatar = hwd_vs_tools::generateAvatar($row->user_id, $row->avatar, $k, null, null, null);
			$code[$i]->playlisttitle = hwd_vs_tools::generatePlaylistLink($row->id, $row->playlist_name);
			$code[$i]->playlistdescription = hwd_vs_tools::truncateText(strip_tags($row->playlist_description), $c->trunvdesc);
			$code[$i]->totalvideos = $row->total_videos;
			//$code[$i]->user = hwd_vs_tools::generateUserFromID($row->user_id, $row->username, $row->name);
			$code[$i]->datecreated = $row->date_created;
			$code[$i]->deleteplaylist = hwd_vs_tools::generateDeletePlaylistLink($row);
			$code[$i]->editplaylist = hwd_vs_tools::generateEditPlaylistLink($row);
			$code[$i]->k = $k;
			$k = 1 - $k;
		}
		return $code;
    }
    /**
     * Generates the array of information for a standard video list from sql queries
     *
     * @param array  $rows  the list from a standard sql queries
     * @param string $thumbclass(optional)  the class for the thumbnail images
     * @param int    $thumbwidth(optional)  the thumbnail width
     * @param int    $thumbheight(optional)  the thumbnail height
     * @return       $code  the array prepared for Smarty template
     */
    function generateVideoListFromSql( $rows, $thumbclass=null, $thumbwidth=null, $thumbheight=null, $hwdvs_itemid=null, $onclick_js=null, $tooltip=null, $or_title_trunc=null, $or_descr_trunc=null, $lightbox=false)
    {
		global $hwdvsTemplateOverride;
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		$c = hwd_vs_Config::get_instance();

		if (isset($tooltip) && !$tooltip)
		{
			$tooltip = 0;
		}
		else if ($tooltip || $c->show_tooltip == "1")
		{
			$tooltip = 1;
		}

		$code = array();
		$k = 0;
		if (isset($thumbwidth)) { $twidth = $thumbwidth; } else { $twidth = null; }
		if (isset($thumbheight)) { $theight = $thumbheight; } else { $theight = null; }
		if (isset($thumbclass)) { $tclass = $thumbclass; } else { $tclass = null; }
		if (isset($or_title_trunc) && !empty($or_title_trunc)) { $truntitle = $or_title_trunc; } else { $truntitle = $c->truntitle; }
		if (isset($or_descr_trunc) && !empty($or_descr_trunc)) { $trunvdesc = $or_descr_trunc; } else { $trunvdesc = $c->trunvdesc; }
		$width = null;
		$height = null;
		$class = null;
		$tooltip_data = null;

		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			if ($c->bviic == 1)
			{
				if (!hwd_vs_tools::validateVideoAccess($row, false))
				{
					continue;
				}
			}

			if (!isset($row->avatar)) {                                $row->avatar = null; }
			if (!isset($row->username)) {                              $row->username = ''; }
			if (!isset($row->name)) {                                  $row->name = ''; }

			if ($hwdvsTemplateOverride['show_avatar'] == 1) {          $code[$i]->avatar = hwd_vs_tools::generateAvatar($row->user_id, $row->avatar, $k, $width, $height, $class); }
			if ($hwdvsTemplateOverride['show_title']) {                $code[$i]->title = hwd_vs_tools::generateVideoLink( $row->id, $row->title, $hwdvs_itemid, $onclick_js, $truntitle); }
			if ($hwdvsTemplateOverride['show_category']) {             $code[$i]->category = hwd_vs_tools::generateCategoryLink($row->category_id); }
			if ($hwdvsTemplateOverride['show_description']) {          $code[$i]->description = hwd_vs_tools::truncateText(strip_tags(stripslashes($row->description)), $trunvdesc); }

			$tooltip_data[0] = $tooltip;
			$tooltip_data[1] = htmlspecialchars(strip_tags(stripslashes($row->title)));
			$tooltip_data[2] = hwd_vs_tools::truncateText(htmlspecialchars(strip_tags(stripslashes($row->description))), $trunvdesc);

			if ($hwdvsTemplateOverride['show_rating'] == 1 && $row->allow_ratings == 1 && $c->showrate == 1)
			{
				$code[$i]->rating = hwd_vs_tools::generateRatingImg($row->updated_rating);
				$code[$i]->showrating = 1;
			}

			if ($hwdvsTemplateOverride['show_thumbnail'] == 1) {       $code[$i]->thumbnail = hwd_vs_tools::generateVideoThumbnailLink($row->id, $row->video_id, $row->video_type, $row->thumbnail, $k, $twidth, $theight, $tclass, null, $hwdvs_itemid, $onclick_js, $tooltip_data, $lightbox, $row->video_length); }
			if ($hwdvsTemplateOverride['show_views']) {                $code[$i]->views = $row->number_of_views; }
			if ($hwdvsTemplateOverride['show_comments']) {             $code[$i]->comments = $row->number_of_comments; }
			if ($hwdvsTemplateOverride['show_duration']) {             $code[$i]->duration = $row->video_length; }
			if ($hwdvsTemplateOverride['show_uploader']) {             $code[$i]->uploader = hwd_vs_tools::generateUserFromID($row->user_id, $row->username, $row->name); }
			if ($hwdvsTemplateOverride['show_timesince']) {            $code[$i]->timesince = hwd_vs_tools::generateTimeSinceUpload($row->date_uploaded); }
			if ($hwdvsTemplateOverride['show_upload_date']) {          $code[$i]->upload_date = strftime("%l%P - %b %e, %Y", strtotime($row->date_uploaded)); }
			if ($hwdvsTemplateOverride['show_tags']) {                 $code[$i]->tags	= hwd_vs_tools::generateTagListString($row->tags); }

			$code[$i]->deletevideo = hwd_vs_tools::generateDeleteVideoLink($row);
			$code[$i]->editvideo = hwd_vs_tools::generateEditVideoLink($row);
			$code[$i]->publishvideo = hwd_vs_tools::generatePublishVideoLink($row);
			$code[$i]->approvevideo = hwd_vs_tools::generateApproveVideoLink($row);

			$code[$i]->counter = $i;
			$code[$i]->k = $k;
			$k = 1 - $k;
		}
		return $code;
    }
    /**
     * Generates the array of information for a standard video list from parsed xml files
     *
     * @param array  $rows  the list from an xml file
     * @return       $code  the array prepared for Smarty template
     */
	function generateVideoListFromXml( $rows, $thumbwidth=null, $hwdvs_itemid=null, $tooltip=null, $or_title_trunc=null, $or_descr_trunc=null, $onclick_js=null, $lightbox=false )
	{
		global $hwdvsTemplateOverride;
		$c = hwd_vs_Config::get_instance();

		if ($tooltip == 1 || $c->show_tooltip == "1")
		{
			$tooltip = 1;
		}
		else
		{
			$tooltip = 0;
		}

		$code = array();
		$k = 0;

		if (isset($thumbwidth)) { $twidth = $thumbwidth; } else { $twidth = $c->thumbwidth; }
		$theight = $twidth*$c->tar_fb;

		if (isset($or_title_trunc) && !empty($or_title_trunc)) { $truntitle = $or_title_trunc; } else { $truntitle = $c->truntitle; }
		if (isset($or_descr_trunc) && !empty($or_descr_trunc)) { $trunvdesc = $or_descr_trunc; } else { $trunvdesc = $c->trunvdesc; }
		$class=null;
		$width=null;
		$height=null;

		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			if (empty($rows[$i]["id"])) {$rows[$i]["id"] = null;}
			if (empty($rows[$i]["videotitle"])) {$rows[$i]["videotitle"] = null;}
			if (empty($rows[$i]["videocode"])) {$rows[$i]["videocode"] = null;}
			if (empty($rows[$i]["videotype"])) {$rows[$i]["videotype"] = null;}
			if (empty($rows[$i]["thumbnail"])) {$rows[$i]["thumbnail"] = null;}
			if (empty($rows[$i]["location"])) {$rows[$i]["location"] = null;}
			if (empty($rows[$i]["category"])) {$rows[$i]["category"] = null;}
			if (empty($rows[$i]["category_id"])) {$rows[$i]["category_id"] = null;}
			if (empty($rows[$i]["description"])) {$rows[$i]["description"] = null;}
			if (empty($rows[$i]["views"])) {$rows[$i]["views"] = null;}
			if (empty($rows[$i]["date"])) {$rows[$i]["date"] = null;}
			if (empty($rows[$i]["duration"])) {$rows[$i]["duration"] = null;}
			if (empty($rows[$i]["avatar"])) {$rows[$i]["avatar"] = null;}
			if (empty($rows[$i]["rating"])) {$rows[$i]["rating"] = null;}
			if (empty($rows[$i]["uploader"])) {$rows[$i]["uploader"] = null;}
			if (empty($rows[$i]["uploader_id"])) {$rows[$i]["uploader_id"] = null;}
			if (empty($rows[$i]["description"])) {$rows[$i]["description"] = null;}
			if (empty($rows[$i]["comments"])) {$rows[$i]["comments"]= "0";}
			if (empty($rows[$i]["tags"])) {$rows[$i]["tags"] = null;}

			$video_code = explode(",", $rows[$i]["videocode"]);
			if (!empty($video_code[1]))
			{
				$video_code[1] = urldecode($video_code[1]);
				$rows[$i]["videocode"] = implode(",", $video_code);
			}

			$tooltip_data[0] = $tooltip;
			$tooltip_data[1] = addslashes(strip_tags($rows[$i]["videotitle"]));
			$tooltip_data[2] = addslashes(hwd_vs_tools::truncateText(strip_tags($rows[$i]["description"]), $trunvdesc));

			if ($hwdvsTemplateOverride['show_avatar'] == 1 && ($c->cbint == "1" || $c->cbint == "2" || $c->cbint == "3"))
			{
				$code[$i]->avatar = hwd_vs_tools::generateAvatar($rows[$i]["uploader_id"], $rows[$i]["avatar"], $k, $width, $height, $class);
			}
			if ($hwdvsTemplateOverride['show_title'])
			{
				$title = stripslashes($rows[$i]["videotitle"]);
				$title = hwdEncoding::charset_encode_utf_8($title);
				$code[$i]->title = hwd_vs_tools::generateVideoLink( $rows[$i]["id"], $title, $hwdvs_itemid, $onclick_js, $truntitle);
			}
			if ($hwdvsTemplateOverride['show_category']) {             $code[$i]->category = hwd_vs_tools::generateCategoryLink($rows[$i]["category_id"], $rows[$i]["category"], $hwdvs_itemid); }
			if ($hwdvsTemplateOverride['show_description'])
			{
				$description = stripslashes($rows[$i]["description"]);
				$description = hwdEncoding::charset_encode_utf_8($description);
				$code[$i]->description = hwd_vs_tools::truncateText(strip_tags(hwdEncoding::UNXMLEntities($description)), $trunvdesc);
			}

			if ($hwdvsTemplateOverride['show_rating'] == 1 && $c->showrate == 1)
			{
				$code[$i]->rating = hwd_vs_tools::generateRatingImg($rows[$i]["rating"]);
				$code[$i]->showrating = 1;
			}

			if ($hwdvsTemplateOverride['show_thumbnail'] == 1) {       $code[$i]->thumbnail = hwd_vs_tools::generateVideoThumbnailLink($rows[$i]["id"], $rows[$i]["videocode"], $rows[$i]["videotype"], $rows[$i]["thumbnail"], $k, $twidth, $theight, $class, null, $hwdvs_itemid, $onclick_js, $tooltip_data, $lightbox, $rows[$i]["duration"]); }
			if ($hwdvsTemplateOverride['show_views']) {                $code[$i]->views = $rows[$i]["views"]; }
			if ($hwdvsTemplateOverride['show_comments']) {             $code[$i]->comments = $rows[$i]["comments"]; }
			if ($hwdvsTemplateOverride['show_duration']) {             $code[$i]->duration = $rows[$i]["duration"]; }
			if ($hwdvsTemplateOverride['show_uploader']) {             $code[$i]->uploader = hwd_vs_tools::generateUserFromID($rows[$i]["uploader_id"], $rows[$i]["uploader"], $rows[$i]["uploader"]); }
			if ($hwdvsTemplateOverride['show_timesince']) {            $code[$i]->timesince = hwd_vs_tools::generateTimeSinceUpload($rows[$i]["date"]); }
			if ($hwdvsTemplateOverride['show_upload_date']) {          $code[$i]->upload_date = strftime("%l%P - %b %e, %Y", strtotime($rows[$i]["date"])); }
			if ($hwdvsTemplateOverride['show_tags']) {                 $code[$i]->tags	= hwd_vs_tools::generateTagListString($rows[$i]["tags"]); }

			$code[$i]->k = $k;
			$k = 1 - $k;
		}

		if (!isset($code)) { $code = null; }
		return $code;
    }
    /**
     * Generates the array of information for a standard group member list
     *
     * @param array  $rows  the list from a standard sql queries
     * @return       $code  the array prepared for Smarty template
     */
    function validateVideoAccess($row, $message=true)
    {
		global $mainframe, $hwdvsItemid, $smartyvs, $isModerator;
		$c = hwd_vs_Config::get_instance();
  		$db =& JFactory::getDBO();
		$my = & JFactory::getUser();

        if (count($row) < 1)
        {
        	hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_VIDNOEXIST, "exclamation.png", 0);
			return false;
        }

        if (!$isModerator && $row->published !== "1")
        {
        	hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, "This video is not published", "exclamation.png", 0);
			return false;
        }

        if ($row->approved == "deleted")
        {
        	hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, "This video has been deleted", "exclamation.png", 0);
			return false;
        }

        if (!$isModerator && $row->approved == "pending")
        {
        	hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, "This video is pending approval", "exclamation.png", 0);
			return false;
        }

        if (preg_match("/queued/i", $row->approved))
        {
        	hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, "This video is queued for video conversion", "exclamation.png", 0);
			return false;
        }

        if (preg_match("/converting/i", $row->approved))
        {
        	hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, "This video is currently being processed", "exclamation.png", 0);
			return false;
        }

        if ($isModerator && $row->approved == "pending")
        {
        	// OK
        }
        else if ($row->approved !== "yes")
        {
        	hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, "This video is not approved", "exclamation.png", 0);
			return false;
        }

        if ( $row->public_private == "registered" && $my->id == 0 )
        {
        	if ($message)
        	{
					if (!$my->id)
					{
						$smartyvs->assign("showconnectionbox", 1);
					}
					hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ORUCAV, "exclamation.png", 0);
        	}
			return false;
        }

        if ( $row->public_private == "me" && $my->id !== $row->user_id )
        {
        	if ($message)
        	{
					if (!$my->id)
					{
						$smartyvs->assign("showconnectionbox", 1);
					}
					hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_OOCAV, "exclamation.png", 0);
        	}
			return false;
        }

        if ( $row->public_private == "password" )
        {
			$password = Jrequest::getVar( 'password', '' );
			$pass_check_variable = $mainframe->getUserState( "hwdvs_pw_$row->id", "notset" );
			$link = JRoute::_("index.php?option=com_hwdvideoshare&task=viewvideo&Itemid=$hwdvsItemid&video_id=".$row->id);

			if ($pass_check_variable == "notset")
			{
				if (!empty($password))
				{
					if (md5($password) == $row->password)
					{
						$mainframe->setUserState( "hwdvs_pw_$row->id", $password );
					}
					else
					{
						return false;
					}
				}
				else
				{
					if ($message)
					{
						$message = '<p>'._HWDVIDS_TVPP.'</p><br /><form action="'.$link.'" method="post">
						'._HWDVIDS_PASSWORD.'&nbsp;&nbsp;<input name="password" value="" type="password" class="inputbox" size="20" maxlength="500" style="width: 200px;" />
						<input type="submit" value="'._HWDVIDS_BUTTON_VIEW.'">
						</form>';

						hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, $message, null, 0);
					}
        			return false;
				}
			}
			else
			{
				if (md5($password) == $row->password)
				{
					$mainframe->setUserState( "hwdvs_pw_$row->id", $password );
				}
				else
				{
					if ($message)
					{
						$message = '<p>'._HWDVIDS_IPW.'</p><br /><form action="'.$link.'" method="post">
						'._HWDVIDS_PASSWORD.'&nbsp;&nbsp;<input name="password" value="" type="password" class="inputbox" size="20" maxlength="500" style="width: 200px;" />
						<input type="submit" value="'._HWDVIDS_BUTTON_VIEW.'">
						</form>';

						hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, $message, null, 0);
					}
					return false;
				}
			}
        }

        if ( $row->public_private == "group" )
        {
			if (!hwd_vs_access::allowAccess( $row->password, 'RECURSE', hwd_vs_access::userGID( $my->id )))
			{
				if ($message)
				{
					if (!$my->id)
					{
						$smartyvs->assign("showconnectionbox", 1);
					}
					hwd_vs_tools::infomessage(1, 0, _HWDVIDS_TITLE_NOACCESS, "You do not have permission to view this video, you do not have the necessary access group.", "exclamation.png", 1);
				}
				return false;
			}
        }

        if ( $row->public_private == "level" )
        {
			if (!hwd_vs_access::allowLevelAccess( $row->password, $my->get('aid', 0)))
			{
				if ($message)
				{
					if (!$my->id)
					{
						$smartyvs->assign("showconnectionbox", 1);
					}
					hwd_vs_tools::infomessage(2, 0,  _HWDVIDS_TITLE_NOACCESS, "You do not have permission to view this video, you do not how the necessary access level.", "exclamation.png", 0);
				}
				return false;
			}
        }

		if ($row->category_id !== "0")
		{
			$usersConfig = &JComponentHelper::getParams( 'com_users' );
			$acl= & JFactory::getACL();

			$query = "SELECT access_v, access_v_r FROM #__hwdvidscategories WHERE id = $row->category_id";
			$db->SetQuery($query);
			$category = $db->loadObject();

        	if (isset($category))
        	{
				if (!hwd_vs_access::allowAccess( $category->access_v, $category->access_v_r, hwd_vs_access::userGID( $my->id )))
				{
					if ( ($my->id < 1) && (!$usersConfig->get( 'allowUserRegistration' ) == '0' && hwd_vs_access::allowAccess( $category->access_v, 'RECURSE', $acl->get_group_id('Registered','ARO') ) ) )
					{
						if ($message)
						{
							if (!$my->id)
							{
								$smartyvs->assign("showconnectionbox", 1);
							}
							hwd_vs_tools::infomessage(2, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORVCAT, "exclamation.png", 0);
						}
						return false;
					}
					else
					{
						if ($message)
						{
							if (!$my->id)
							{
								$smartyvs->assign("showconnectionbox", 1);
							}
							hwd_vs_tools::infomessage(2, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_VCAT_NOT_AUTHORIZED, "exclamation.png", 0);
						}
						return false;
					}
				}
			}
		}
		return true;
    }
    /**
     * Generates the array of information for a standard group member list
     *
     * @param array  $rows  the list from a standard sql queries
     * @return       $code  the array prepared for Smarty template
     */
    function generateGroupMemberList( $rows )
    {
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code = array();
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$code[$i]->member_id = $row->id;
			$code[$i]->member_username = hwd_vs_tools::generateUserFromID($row->memberid, $row->username, $row->name);
			$code[$i]->k = $k;
			$k = 1 - $k;
		}
		return $code;
    }
    /**
     * Generates the human readable status of a video from the raw sql data
     *
     * @param string $status  the raw sql format
     * @return       $code  the multilingual human readable text
     */
	function generateVideoStatus( $status ) {

		$code = null;
		if ($status == "yes") {
			$code.= _HWDVIDS_DETAILS_VIDSTATUS_Y;
		} else if ($status == "queuedforconversion") {
			$code.= _HWDVIDS_DETAILS_VIDSTATUS_QFC;
		} else if ($status == "queuedforthumbnail") {
			$code.= _HWDVIDS_DETAILS_VIDSTATUS_QFT;
		} else if ($status == "queuedforswf") {
			$code.= _HWDVIDS_DETAILS_VIDSTATUS_QFSWF;
		} else if ($status == "queuedformp4") {
			$code.= _HWDVIDS_DETAILS_VIDSTATUS_QFMP4;
		} else if ($status == "deleted") {
			$code.= "<a href=\"index.php?option=com_hwdvideoshare&task=maintenance\">"._HWDVIDS_DETAILS_VIDSTATUS_D."</a>";
		} else if ($status == "pending") {
			$code.= _HWDVIDS_DETAILS_VIDSTATUS_P;
		} else {
			$code.= $status;
		}
		return $code;
    }
    /**
     * Generates the human readable access level of a video from the raw sql data
     *
     * @param string $status  the raw sql format
     * @return       $code  the multilingual human readable text
     */
	function generateVideoAccess( $status ) {

		$code = null;
		if ($status == "public") {
			$code.= _HWDVIDS_SELECT_PUBLIC;
		} else if ($status == "registered") {
			$code.= _HWDVIDS_SELECT_REG;
		} else {
			$code.= $status;
		}
		return $code;
    }
    /**
     * Generates the embed code of a video
     *
     * @param array  $row  the video information
     * @return       $code
     */
	function generateEmbedCode( $row )
	{
		global $hwdvsItemid, $mainframe, $option, $task, $smartyvs, $show_video_ad, $pre_url, $post_url;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();

		$code = "";
		if ($c->showvebc == "1")
		{
			$smartyvs->assign("showEmbedButton", 1);
			$smartyvs->assign("print_embedcode", 1);

			if ( $row->allow_embedding == "0" )
			{
				$code.= _HWDVIDS_INFO_EMBEDDISABLED;
				return $code;
			}

			$code = hwd_vs_tools::generateVideoPlayer( $row, "", "", "", "sd", true );
			//$code = htmlentities($code);
		}
		return $code;
    }
    /**
     * Generates the category list with formatted subcategories
     *
     * @param string $header  the joomla component name
     * @param int    $selid  array of video data
     * @param string $nocatsmess  no category message
     * @param int    $pub(optional)  only list published categories (0/1)
     * @param int    $cname(optional)  category select list name value
     * @param int    $checkaccess(optional)  only list accessible categories for current user (0/1)
     * @return       $code
     */
	function categoryList( $header, $selid, $nocatsmess, $pub = 0, $cname = "category_id", $checkaccess = 1, $tag_attribs = 'class="inputbox"', $show_uncategorised=false)
	{
		global $mainframe;
  		$db =& JFactory::getDBO();
		$my = & JFactory::getUser();
        $c = hwd_vs_Config::get_instance();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'access.php');

		if ($pub) { $where = "\nWHERE published = 1"; } else { $where = null; }
		$db->setQuery("SELECT id ,parent,category_name, access_u, access_lev_u, access_u_r from #__hwdvidscategories"
		                . $where
		                . "\nORDER BY category_name"
		                );
		$mitems = $db->loadObjectList();
		// establish the hierarchy of the menu
		$children = array ();

		$nocats = 0;
		// first pass - collect children
		foreach ($mitems as $v)
		{
			$pt = $v->parent;

			$nocats = 1;
			$list = @$children[$pt] ? $children[$pt] : array ();
			array_push($list, $v);
			$children[$pt] = $list;
		}

		// second pass - get an indent list of the items
		$list = hwd_vs_tools::catTreeRecurse(0, '', array (), $children);
		// assemble menu items to the array
		$mitems = array ();
		if ($nocats == 0) {
			$mitems[] = JHTML::_('select.option', '0', $nocatsmess);
		} else {
			$mitems[] = JHTML::_('select.option', '0', $header);
			if ($show_uncategorised) {
				$mitems[] = JHTML::_('select.option', 'none', 'Uncategorized');
			}
		}
		$this_treename = '';

		foreach ($list as $item)
		{
			if ($checkaccess)
			{
				if (!hwd_vs_access::allowAccess( $item->access_u, $item->access_u_r, hwd_vs_access::userGID( $my->id )))
				{
					continue;
				}
			}

			if ($this_treename)
			{
				if ($item->id != $mitems && strpos($item->treename, $this_treename) === false)
				{
					$mitems[] = JHTML::makeOption($item->id, $item->treename);
				}
			}
			else
			{
				if ($item->id != $mitems)
				{
					$mitems[] = JHTML::_('select.option', $item->id, $item->treename);
				}
				else
				{
					$this_treename = "$item->treename/";
				}
			}
		}

		// build the html select list
		$code = hwd_vs_tools::selectList2($mitems, $cname, $tag_attribs, 'value', 'text', $selid);
		return $code;
    }
    /**
     * Generates a thumbnail image
     *
     * @param int    $video_id  the video sql id
     * @param string $video_code  the video uid
     * @param string $video_type  the video type
     * @param int    $k  the css variable
     * @param int    $width(optional)  the width of the thumbnail image
     * @param int    $height(optional)  the height of the thumbnail image
     * @param string $class(optional)  the class of the thumbnail image
     * @return       $code
     */
	function generateThumbnail( $video_id, $video_code, $video_type, $video_thumbnail, $k, $width=null, $height=null, $class=null, $tooltip_data=null, $video_duration=null)
	{
		global $hwdvsItemid, $mainframe, $hwdvsTemplateOverride;
		$c = hwd_vs_Config::get_instance();
  		$db =& JFactory::getDBO();
		$doc = & JFactory::getDocument();

		if (!isset($width))
		{
			$width = $c->thumbwidth;
		}
		if (!isset($height))
		{
			$height = $width*$c->tar_fb;
		}
		if (!isset($class) || empty($class))
		{
			$class = "thumb".$k;
		}
		if ($tooltip_data[0])
		{
			JHTML::_('behavior.tooltip');
			$tt_title = $tooltip_data[1]." :: ".$tooltip_data[2];
			$thumb_title = "";
			$thumb = "<span class=\"hasTip\" title=\"".$tt_title."\">";
		}
		else
		{
			$thumb = "";
			$thumb_title = $tooltip_data[1];
		}

		if ($c->thumb_ts == 1)
		{
			$thumb.= "<div class=\"watermark_box\">";
		}

		$thumbnailURL = hwd_vs_tools::generateThumbnailURL( $video_id, $video_code, $video_type, $video_thumbnail );

		// assume local for following variables
		$path_ext = (!empty($video_thumbnail) ? $video_thumbnail : "jpg");
		$path_thumb = PATH_HWDVS_DIR.DS."thumbs".DS.$video_code.".".$path_ext;
		$path_thumbd = PATH_HWDVS_DIR.DS."thumbs".DS.$video_code.".gif";

		if (($video_type == "local" || $video_type == "mp4" || $video_type == "swf") && (file_exists($path_thumb) && (filesize($path_thumb) > 0)))
		{
			if ($c->udt == 1 && file_exists($path_thumbd) && (filesize($path_thumbd) > 0))
			{
				if (!defined( '_HWD_VS_DTFLAG' ))
				{
					define( '_HWD_VS_DTFLAG', 1 );
					$doc->addCustomTag("<script type='text/javascript'>function roll_over(img_name, img_src) { document[img_name].src = img_src; }</script>");
				}

				$url_thumbd = URL_HWDVS_DIR."/thumbs/".$video_code.".gif";
				$rand = rand();

				$thumb.= "<img src=\"".$thumbnailURL."\" border=\"0\" alt=\""._HWDVIDS_DETAILS_VIEWVID."\" width=\"".$width."\" height=\"".$height."\" title=\"".$thumb_title."\" class=\"".$class."\" name=\"".$video_code.$rand."\" onmouseover=\"roll_over('".$video_code.$rand."', '".$url_thumbd."')\" onmouseout=\"roll_over('".$video_code.$rand."', '".$thumbnailURL."')\" />";
			}
			else
			{
				$thumb.= "<img src=\"".$thumbnailURL."\" border=\"0\" alt=\""._HWDVIDS_DETAILS_VIEWVID."\" width=\"".$width."\" height=\"".$height."\" title=\"".$thumb_title."\" class=\"".$class."\" />";
			}
		}
		else
		{
			$thumb.= "<img src=\"".$thumbnailURL."\" alt=\""._HWDVIDS_DETAILS_VIEWVID."\" border=\"0\" width=\"".$width."\" height=\"".$height."\" title=\"".$thumb_title."\" class=\"".$class."\" />";
		}

		if ($c->thumb_ts == 1)
		{
			if (!isset($video_duration))
			{
				$video_duration = "N/A";
			}
			else
			{
				$video_duration = hwd_vs_tools::validateDuration($video_duration);
			}
			$thumb.= "<img src=\"".URL_HWDVS_IMAGES."overlay.png\" class=\"watermark\" alt=\"Watermark image\" />
					  <span class=\"timestamp\">".$video_duration."</span>
					  </div>";
		}

		if ($tooltip_data[0])
		{
			$thumb.= "</span>";
		}

		return $thumb;
    }
    /**
     * Generates a thumbnail image
     *
     * @param int    $video_id  the video sql id
     * @param string $video_code  the video uid
     * @param string $video_type  the video type
     * @param int    $k  the css variable
     * @param int    $width(optional)  the width of the thumbnail image
     * @param int    $height(optional)  the height of the thumbnail image
     * @param string $class(optional)  the class of the thumbnail image
     * @return       $code
     */
	function generateThumbnailURL( $video_id, $video_code, $video_type, $video_thumbnail, $type="normal" )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
  		$db =& JFactory::getDBO();

		if ($video_type == "category")
		{
			$thumb = $video_thumbnail;
		}

		$ext = (!empty($video_thumbnail) ? $video_thumbnail : "jpg");
		if ($type == "large" && file_exists(PATH_HWDVS_DIR.DS."thumbs".DS."l_".$video_code.".".$ext))
		{
			$thumb = URL_HWDVS_DIR."/thumbs/l_".$video_code.".".$ext;
			return $thumb;
		}

		if (file_exists(PATH_HWDVS_DIR.DS."thumbs".DS.$video_code.".".$ext))
		{
			$thumb = URL_HWDVS_DIR."/thumbs/".$video_code.".".$ext;
			return $thumb;
		}

		// assume local for following variables
		$path_ext = (!empty($video_thumbnail) ? $video_thumbnail : "jpg");
		$path_thumb = PATH_HWDVS_DIR.DS."thumbs".DS.$video_code.".".$path_ext;
		$path_thumbd = PATH_HWDVS_DIR.DS."thumbs".DS.$video_code.".gif";

        if (($video_type == "local" || $video_type == "mp4" || $video_type == "swf") && (file_exists($path_thumb) && (filesize($path_thumb) > 0)))
		{
			$thumb = URL_HWDVS_DIR."/thumbs/".$video_code.".".$path_ext;
		}
		else if (($video_type == "local" || $video_type == "mp4" ||  $video_type == "swf") && (!file_exists($path_thumb) || (filesize($path_thumb) <= 0)))
		{
			$thumb = URL_HWDVS_IMAGES.'default_thumb.jpg';
		}
		else if (!empty($video_thumbnail))
		{
			$pos = strpos($video_thumbnail, "http://");
			if ($pos === false)
			{
				$thumb = basename($video_thumbnail);
				$path_thumb = PATH_HWDVS_DIR.DS."thumbs".DS.$thumb;
				if (file_exists($path_thumb) && (filesize($path_thumb) > 0))
				{
					$thumb = URL_HWDVS_DIR."/thumbs/".$thumb;
				}
				else
				{
					$thumb = URL_HWDVS_IMAGES.'default_thumb.jpg';
				}
			}
			else
			{
				$thumb = $video_thumbnail;
			}
		}
		else if ($video_type == "seyret")
		{
			$data = @explode(",", $video_code);
			if ($data[0] == "local")
			{
				$pos = strpos($data[2], "http://");
				if ($pos === false)
				{
					$thumb = JURI::root().$data[2];
				}
				else
				{
					$thumb = $data[2];
				}
			}
			else
			{
				$plugin = hwd_vs_tools::getPluginDetails($data[0]);
				if (!$plugin)
				{
					$thumb = URL_HWDVS_IMAGES.'default_thumb.jpg';
				}
				else
				{
					$preparethumb = preg_replace("/[^a-zA-Z0-9s_-]/", "", $data[0])."PrepareThumbURL";
					if (!empty($data[2]))
					{
						$new_video_code = @$data[1].",".@$data[2];
					}
					else
					{
						$new_video_code = @$data[1];
					}
					if ($thumbcode = $preparethumb($new_video_code, $video_id))
					{
						$thumb = $thumbcode;
					}
					else
					{
						$thumb = URL_HWDVS_IMAGES.'default_thumb.jpg';
					}
				}
			}
		}
		else
		{
			$plugin = hwd_vs_tools::getPluginDetails($video_type);
			if (!$plugin)
			{
				$thumb = URL_HWDVS_IMAGES.'default_thumb.jpg';
			}
			else
			{
				$preparethumb = preg_replace("/[^a-zA-Z0-9s_-]/", "", $video_type)."PrepareThumbURL";

				if ($thumbcode = $preparethumb($video_code, $video_id))
				{
					$thumbcode = $thumbcode;
				}
				else
				{
					$thumbcode = URL_HWDVS_IMAGES.'default_thumb.jpg';
				}

				$thumb = $thumbcode;
			}
		}
		return $thumb;
    }
    /**
     * Generates the CB avatar thumbnail image from user id
     *
     * @param string $user_id  the joomla user's id
     * @param array  $k  the css variable
     * @param array  $width  the width of the avatar image
     * @param object $height  the height of the avatar image
     * @param int    $class  the class of the avatar image
     * @param int    $target(optional)  the target of the link
     * @return       $code
     */
	function generateAvatar( $user_id, $avatar=null, $k=null, $width=null, $height=null, $class=null, $target="_top" )
	{
		global $hwdvsItemid, $rows_avatars;
		$c = hwd_vs_Config::get_instance();
  		$db =& JFactory::getDBO();

		if ($user_id == 0)
			return;

		$code = null;
		if ($c->cbavatar == 1 || $c->cbavatar == 2)
		{
			if ($c->cbint == 3)
			{
				$juserini = parse_ini_file(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_juser'.DS.'config.ini');

				if (file_exists(JPATH_SITE.DS.$juserini['general::avatars_dir'].DS.$avatar.'.jpg'))
				{
					$avatar_path = JURI::root().DS.$juserini['general::avatars_dir'].DS.$avatar.'.jpg';
				}
				else
				{
					return;
				}
				if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
				$code = "<a href=\"".JRoute::_("index.php?option=com_community&controller=profile".$c->cbitemid."&user_id=".$user_id)."\"><img src=\"".$avatar_path."\" width=\"".$c->avatarwidth."\" border=\"0\" alt=\""._HWDVIDS_ALT_USRPRO."\" class=\"thumb".$k."\" /></a><br />";
			}
			else if ($c->cbint == 2)
			{
				if (isset($avatar))
				{
					$avatar_path = JURI::root().$avatar;
				}
				else
				{
					$avatar_path = JURI::root()."/components/com_community/assets/default.jpg";
				}
				if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
				$code = "<a href=\"".JRoute::_("index.php?option=com_community".$c->cbitemid."&view=profile&userid=".$user_id)."\"><img src=\"".$avatar_path."\" width=\"".$c->avatarwidth."\" border=\"0\" alt=\""._HWDVIDS_ALT_USRPRO."\" class=\"thumb".$k."\" /></a><br />";
			}
			else if ($c->cbint == 1)
			{
				if (isset($avatar))
				{
					$atype = strpos($avatar, "gallery/");
					if ($atype === false)
					{
				        $avatar_path = JURI::root()."images/comprofiler/tn".$avatar;
					}
					else
					{
				        $avatar_path = JURI::root()."images/comprofiler/".$avatar;
				    }
				}
				else
				{
					if (@file_exists(JPATH_SITE."/components/com_comprofiler/plugin/language/default_language/images/tnnophoto.jpg"))
					{
						$avatar_path = JURI::root()."/components/com_comprofiler/plugin/language/default_language/images/tnnophoto.jpg";
					}
					else
					{
						$avatar_path = JURI::root()."/components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png";
					}
				}
				if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
				$code = "<a href=\"".JRoute::_("index.php?option=com_comprofiler".$c->cbitemid."&task=userProfile&user=".$user_id)."\"><img src=\"".$avatar_path."\" width=\"".$c->avatarwidth."\" border=\"0\" alt=\""._HWDVIDS_ALT_USRPRO."\" class=\"thumb".$k."\" /></a><br />";
			}
			else if ($c->cbint == 4)
			{
				if (file_exists(JPATH_SITE.DS.'images'.DS.'fbfiles'.DS.'avatars'.DS.'s_'.$user_id.'.jpg'))
				{
					$avatar_path = JURI::root().'images/fbfiles/avatars/s_'.$user_id.'.jpg';
				}
				else
				{
					$avatar_path = JURI::root().'images/fbfiles/avatars/s_nophoto.jpg';
				}
				if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
				$code = "<a href=\"".JRoute::_("index.php?option=com_kunena&func=fbprofile&Itemid=".$c->cbitemid."&userid=".$user_id)."\"><img src=\"".$avatar_path."\" width=\"".$c->avatarwidth."\" border=\"0\" alt=\""._HWDVIDS_ALT_USRPRO."\" class=\"thumb".$k."\" /></a><br />";
			}
			else if ($c->cbint == 5)
			{
				if (file_exists(JPATH_SITE.DS."hwdvideos".DS."thumbs".DS.$avatar))
				{
					$avatar_path = JURI::root()."hwdvideos/thumbs/".$avatar;
				}
				else
				{
					$avatar_path = null;
				}
				$code = "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewChannel&user_id=".$user_id."&Itemid=".$hwdvsItemid)."\"><img src=\"".$avatar_path."\" width=\"".$c->avatarwidth."\" border=\"0\" alt=\""._HWDVIDS_ALT_USRPRO."\" class=\"thumb".$k."\" /></a><br />";
			}
			else if ($c->cbint == 6)
			{
				if (file_exists(JPATH_SITE.DS.'media'.DS.'kunena'.DS.'avatars'.DS.'s_'.$user_id.'.jpg'))
				{
					$avatar_path = JURI::root().'media/kunena/avatars/s_'.$user_id.'.jpg';
				}
				else
				{
					$avatar_path = JURI::root().'media/kunena/avatars/s_nophoto.jpg';
				}
				if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
				$code = "<a href=\"".JRoute::_("index.php?option=com_kunena&func=fbprofile&Itemid=".$c->cbitemid."&userid=".$user_id)."\"><img src=\"".$avatar_path."\" width=\"".$c->avatarwidth."\" border=\"0\" alt=\""._HWDVIDS_ALT_USRPRO."\" class=\"thumb".$k."\" /></a><br />";
			}
		}
		return $code;
    }
    /**
     * Generates a link to group using $group_id, and generates the
     * group name if necessary
     *
     * @param int    $group_id  the category id
     * @param string $group(optional)  the name of the category
     * @return       $code
     */
	function generateGroupLink( $group_id, $group=null )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code = null;
		$code.= "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewgroup&Itemid=".$hwdvsItemid."&group_id=".$group_id)."\">";
		if (isset($group)) {
			$code.= hwd_vs_tools::truncateText($group, $c->truntitle);
		} else {
			$code.= hwd_vs_tools::generateCategory( $cat_id );
		}
		$code.= "</a>";
		return $code;
    }
    /**
     * Generates a link to group using $group_id, and generates the
     * group name if necessary
     *
     * @param int    $group_id  the category id
     * @param string $group(optional)  the name of the category
     * @return       $code
     */
	function generatePlaylistLink( $playlist_id, $playlist=null )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code = null;
		$code.= "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewplaylist&Itemid=".$hwdvsItemid."&playlist_id=".$playlist_id)."\">";
		if (isset($playlist)) {
			$code.= hwd_vs_tools::truncateText($playlist, $c->truntitle);
		} else {
			$code.= hwd_vs_tools::generatePlaylist( $playlist_id );
		}
		$code.= "</a>";
		return $code;
    }
    /**
     * Generates the array of information for a standard video list from sql queries
     *
     * @param array  $rows  the list from a standard sql queries
     * @param string $thumbclass(optional)  the class for the thumbnail images
     * @return       $code  the array prepared for Smarty template
     */
    function generateTagListString( $tags, $layout_type=0, $link_type=0 )
    {
		global $smartyvs, $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code = null;
		if ($c->showtags == "1")
		{
			$smartyvs->assign("print_tags", 1);
			$tags = explode(",", stripslashes($tags));

			$j=count($tags);
			for ($i=0, $j; $i < $j; $i++)
			{
				$tag = $tags[$i];

				$tag = trim($tag);

				if ($tag != "")
				{
					$url = JRoute::_("index.php?option=com_hwdvideoshare&task=search&Itemid=$hwdvsItemid");
					$url = str_replace("&amp;", "&", $url);

					$pos = strpos($url, "?");
					if ($pos === false)
					{
						$url = $url."?pattern=$tag";
					}
					else
					{
						$url = $url."&pattern=$tag";

					}
					$code.= "<a href=\"$url\">".$tag."</a>, ";
				}
			}

			if (substr($code, -2) == ", ") {$code = substr($code, 0, -2);}
		}
		return $code;
    }
    /**
     * Generates the Add/Remove favourite video button
     *
     * @param array  $row  the video sql data
     * @return       $code
     */
	function generateFavouriteButton($row)
	{
		global $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		if ($c->showatfb == "1")
		{
			// setup ajax tags
			if ($c->ajaxfavmeth == 1)
			{
				$ajaxremfav = "onsubmit=\"ajaxFunctionRFF();return false;\"";
				$ajaxaddfav = "onsubmit=\"ajaxFunctionATF();return false;\"";
			}
			else
			{
				$ajaxremfav = null;
				$ajaxaddfav = null;
			}

			$code = null;

			$userid = $my->id;
			$videoid = $row->id;

			$where = ' WHERE a.userid = '.$userid;
			$where .= ' AND a.videoid = '.$videoid;

			$db->SetQuery( 'SELECT count(*)'
						. ' FROM #__hwdvidsfavorites AS a'
						. $where
						);
			$total = $db->loadResult();

			if ($my->id)
			{
				$remfav = "<form name=\"favourite1\" style=\"display: inline;\" onsubmit=\"ajaxFunctionRFF();return false;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=removefavourite")."\" method=\"post\"><input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_remove_fav.png\" alt=\""._HWDVIDS_DETAILS_REMFAV."\" /></form>";
				$addfav = "<form name=\"favourite2\" style=\"display: inline;\" onsubmit=\"ajaxFunctionATF();return false;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=addfavourite")."\" method=\"post\"><input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_add_fav.png\" alt=\""._HWDVIDS_DETAILS_ADDFAV."\" /></form>";
			}
			else
			{
				$remfav = "<form name=\"favourite2\" style=\"display: inline;\" onsubmit=\"ajaxFunctionATF();return false;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=addfavourite")."\" method=\"post\"><input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_remove_fav.png\" alt=\""._HWDVIDS_DETAILS_REMFAV."\" /></form>";
				$addfav = "<form name=\"favourite2\" style=\"display: inline;\" onsubmit=\"ajaxFunctionATF();return false;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=addfavourite")."\" method=\"post\"><input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_add_fav.png\" alt=\""._HWDVIDS_DETAILS_ADDFAV."\" /></form>";
			}
			hwd_vs_javascript::ajaxaddtofav($row, $remfav, $addfav);

			if ( $total>0 )
			{
				$code.= "<form name=\"favourite1\" style=\"display: inline;\" ".$ajaxremfav." action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=removefavourite")."\" method=\"post\">
						 <input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />
						 <input type=\"hidden\" name=\"videoid\" value=\"".$row->id."\" />
						 <input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_remove_fav.png\" alt=\""._HWDVIDS_DETAILS_REMFAV."\" />
						 </form>";
			}
			else
			{
				$code.= "<form name=\"favourite2\" style=\"display: inline;\" ".$ajaxaddfav." action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=addfavourite")."\" method=\"post\">
						 <input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />
						 <input type=\"hidden\" name=\"videoid\" value=\"".$row->id."\" />
						 <input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_add_fav.png\" alt=\""._HWDVIDS_DETAILS_ADDFAV."\" />
						 </form>";
			}

			$code = "<div id=\"addremfav\" style=\"display:inline;\">$code</div>";
		}
		return $code;
    }
    /**
     * Generates the Add/Remove favourite video button
     *
     * @param array  $row  the video sql data
     * @return       $code
     */
	function generateSwitchQuality($row)
	{
		global $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$code = null;
		if ($row->video_type == "local" || $row->video_type == "mp4"  || $row->video_type == "swf"  || $row->video_type == "seyret" || ($row->video_type == "remote" && substr($row->video_id, 0, 6) !== "embed|"))
		{
			if ($c->usehq == "1" || $c->usehq == "2" && (file_exists(PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".mp4") && file_exists(PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".flv")))
			{
				$ajax_sq = "onsubmit=\"ajaxSwitchStandardQuality();return false;\"";
				$ajax_hq = "onsubmit=\"ajaxSwitchHighQuality();return false;\"";

				$sq_button = "<form name=\"switchQuality\" style=\"display: inline;\" onsubmit=\"ajaxSwitchStandardQuality();return false;\" action=\"#\" method=\"post\"><input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_hdon.png\" alt=\"SD\" /><form>";
				$hq_button = "<form name=\"switchQuality\" style=\"display: inline;\" onsubmit=\"ajaxSwitchHighQuality();return false;\" action=\"#\" method=\"post\"><input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_hdoff.png\" alt=\"HD\" /></form>";

				hwd_vs_javascript::ajaxSwitchQuality($row, $sq_button, $hq_button);

				$code.= "<div id=\"switchQuality\" style=\"display: inline;\">";
				if ($c->usehq == "1")
				{
					$code.= "<form name=\"switchQuality\" style=\"display: inline;\" ".$ajax_sq." action=\"#\" method=\"post\">
							 <input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_hdon.png\" alt=\"SD\" />
							 </form>";
				}
				else if ($c->usehq == "2")
				{
					$code.= "<form name=\"switchQuality\" style=\"display: inline;\" ".$ajax_hq." action=\"#\" method=\"post\">
							 <input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_hdoff.png\" alt=\"HD\" />
							 </form>";
				}
				$code.= "</div>";
			}
		}
		return $code;
    }
    /**
     * Generates the video Report Media button
     *
     * @param array  $row  the video sql data
     * @return       $code
     */
	function generateReportMediaButton($row)
	{
		global $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$code = null;
		if ($c->showrpmb == "1")
		{
			hwd_vs_javascript::ajaxreportmedia($row);

			// setup ajax tags
			if ($c->ajaxrepmeth == 1) { $ajaxrep = "onsubmit=\"ajaxFunctionRV();return false;\""; } else { $ajaxrep = null; }

			$code.= "<form name=\"report\" style=\"display: inline;\" ".$ajaxrep." action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=reportvideo")."\" method=\"post\">
					 <input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />
					 <input type=\"hidden\" name=\"videoid\" value=\"".$row->id."\" />
					 <input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_report.png\" alt=\""._HWDVIDS_DETAILS_FLAGVID."\" id=\"reportvidbutton\" />
					 </form>";
		}
		return $code;
    }
    /**
     * Generates the video Rating System
     *
     * @param array  $row  the video sql data
     * @return       $code
     */
	function generateRatingSystem($row)
	{
		global $mainframe, $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'js.php');

		$rand = rand();
		$code = null;

		hwd_vs_javascript::ajaxRate($row);

		if ( $row->allow_ratings == 1 && $c->showrate == 1)
		{
			if ($row->rating_number_votes < 1)
			{
				$count = 0;
			}
			else
			{
				$count = $row->rating_number_votes; //how many votes total
			}
			$tense = ($count==1) ? _HWDVIDS_INFO_M_VOTE : _HWDVIDS_INFO_M_VOTES; //plural form votes/vote

			$rating0 = @number_format($row->rating_total_points/$count,0);
			$rating1 = @number_format($row->rating_total_points/$count,1);

			$code='<div id="hwdvsrb'.$rand.'">
			         <ul id="1001" class="rating rated'.$rating0.'star">
			           <li id="1" class="rate one"><a href="'.JRoute::_("index.php?option=com_hwdvideoshare&task=rate&videoid='.$row->id.'&rating=1").'" onclick="ajaxFunctionRate(1, '.$row->id.', '.$rand.');return false;" title="'._HWDVIDS_RATE_1STAR.'" rel="nofollow">1</a></li>
			           <li id="2" class="rate two"><a href="'.JRoute::_("index.php?option=com_hwdvideoshare&task=rate&videoid='.$row->id.'&rating=2").'" onclick="ajaxFunctionRate(2, '.$row->id.', '.$rand.');return false;" title="'._HWDVIDS_RATE_2STAR.'" rel="nofollow">2</a></li>
			           <li id="3" class="rate three"><a href="'.JRoute::_("index.php?option=com_hwdvideoshare&task=rate&videoid='.$row->id.'&rating=3").'" onclick="ajaxFunctionRate(3, '.$row->id.', '.$rand.');return false;" title="'._HWDVIDS_RATE_3STAR.'" rel="nofollow">3</a></li>
			           <li id="4" class="rate four"><a href="'.JRoute::_("index.php?option=com_hwdvideoshare&task=rate&videoid='.$row->id.'&rating=4").'" onclick="ajaxFunctionRate(4, '.$row->id.', '.$rand.');return false;" title="'._HWDVIDS_RATE_4STAR.'" rel="nofollow">4</a></li>
			           <li id="5" class="rate five"><a href="'.JRoute::_("index.php?option=com_hwdvideoshare&task=rate&videoid='.$row->id.'&rating=5").'" onclick="ajaxFunctionRate(5, '.$row->id.', '.$rand.');return false;" title="'._HWDVIDS_RATE_5STAR.'" rel="nofollow">5</a></li>
			         </ul>
			       <div>'._HWDVIDS_INFO_RATED.'<strong> '.$rating1.'</strong> ('.$count.' '.$tense.')</div>
			       <!--<script>
                   $$(\'.rate\').each(function(element,i){
                   element.addEvent(\'click\', function(){
                   var myStyles = [\'0star\', \'1star\', \'2star\', \'3star\', \'4star\', \'5star\'];
                   myStyles.each(function(myStyle){
                   if(element.getParent().hasClass(myStyle)){
                   element.getParent().removeClass(myStyle)
                   }
                   });
                   myStyles.each(function(myStyle, index){
                   if(index == element.id){
                   element.getParent().toggleClass(myStyle);
                   }
                   });
                   });
                   });
                   </script>-->
			       </div>';

		}
		return $code;
    }
    /**
     * Generates the social bookmark links
     *
     * @return       $code
     */
    function generateSocialBookmarks()
	{
		global $mainframe, $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$doc = & JFactory::getDocument();

		$code = null;
		if ($c->showscbm == "1")
		{
			$video_id = JRequest::getInt( 'video_id', 0 );
			$sbtitle = rawurlencode($doc->getTitle());
            $sburl = "http://".$_SERVER['HTTP_HOST'].rawurlencode(JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=viewvideo&video_id=".$video_id));
			$jrandom = rand(1000, 9999);
			$bmhtml = null;

				//facebook
				if ($c->sb_facebook == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.facebook.com/share.php?u='. $sburl .'&amp;t='. $sbtitle .'" title="Facebook!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/facebook.png" alt="Facebook!" title="Facebook!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}

				$temphtml = '<a rel="nofollow" href="http://twitter.com/home?status='. $sburl .'&amp;title='. $sbtitle .'" title="Digg!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/twitter.png" alt="Twitter" title="Twitter" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";

				//digg
				if ($c->sb_digg == "on") {
				$temphtml = '<a rel="nofollow" href="http://digg.com/submit?phase=2&amp;url='. $sburl .'&amp;title='. $sbtitle .'" title="Digg!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/digg.png" alt="Digg!" title="Digg!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//reddit
				if ($c->sb_reddit == "on") {
				$temphtml = '<a rel="nofollow" href="http://reddit.com/submit?url='. $sburl .'&amp;title='. $sbtitle .'" title="Reddit!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/reddit.png" alt="Reddit!" title="Reddit!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//delicious
				if ($c->sb_delicious == "on") {
				$temphtml = '<a rel="nofollow" href="http://del.icio.us/post?url='. $sburl .'&amp;title='. $sbtitle .'" title="Del.icio.us!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/delicious.png" alt="Del.icio.us!" title="Del.icio.us!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//google
				if ($c->sb_google == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk='. $sburl .'&amp;title='. $sbtitle .'" title="Google!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/google.png" alt="Google!" title="Google!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//live
				if ($c->sb_live == "on") {
				$temphtml = '<a rel="nofollow" href="https://favorites.live.com/quickadd.aspx?marklet=1&amp;mkt=en-us&amp;top=0&amp;url='. $sburl .'&amp;title='. $sbtitle .'" title="Live!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/live.png" alt="Live!" title="Live!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//slashdot
				if ($c->sb_slashdot == "on") {
				$temphtml = '<a rel="nofollow" href="http://slashdot.org/bookmark.pl?url='. $sburl .'&amp;title='. $sbtitle .'" title="Slashdot!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/slashdot.png" alt="Slashdot!" title="Slashdot!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//netscape
				if ($c->sb_netscape == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.netscape.com/submit/?U='. $sburl .'&amp;T='. $sbtitle .'" title="Netscape!" target="_blank"><img height="18" width="18" src="'.URL_HWDVS_IMAGES.'socialbookmarker/netscape.png" alt="Netscape!" title="Netscape!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//technorati
				if ($c->sb_technorati == "on") {
				$temphtml = '<a rel="nofollow" href="http://technorati.com/faves/?add='. $sburl .'" title="Technorati!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/technorati.png" alt="Technorati!" title="Technorati!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//stumbleupon
				if ($c->sb_stumbleupon == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.stumbleupon.com/submit?url='. $sburl .'&amp;title='. $sbtitle .'" title="StumbleUpon!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/stumbleupon.png" alt="StumbleUpon!" title="StumbleUpon!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//spurl
				if ($c->sb_spurl == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.spurl.net/spurl.php?url='. $sburl .'&amp;title='. $sbtitle .'" title="Spurl!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/spurl.png" alt="Spurl!" title="Spurl!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//wists
				if ($c->sb_wists == "on") {
				$temphtml = '<a rel="nofollow" href="http://wists.com/r.php?r='. $sburl .'&amp;title='. $sbtitle .'" title="Wists!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/wists.png" alt="Wists!" title="Wists!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//simpy
				if ($c->sb_simpy == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.simpy.com/simpy/LinkAdd.do?href='. $sburl .'&amp;title='. $sbtitle .'" title="Simpy!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/simpy.png" alt="Simpy!" title="Simpy!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//newsvine
				if ($c->sb_newsvine == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.newsvine.com/_tools/seed&amp;save?u='. $sburl .'&amp;h='. $sbtitle .'" title="Newsvine!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/newsvine.png" alt="Newsvine!" title="Newsvine!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//blinklist
				if ($c->sb_blinklist == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url='. $sburl .'&amp;Title='. $sbtitle .'" title="Blinklist!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/blinklist.png" alt="Blinklist!" title="Blinklist!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//furl
				if ($c->sb_furl == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.furl.net/storeIt.jsp?u='. $sburl .'&amp;t='. $sbtitle .'" title="Furl!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/furl.png" alt="Furl!" title="Furl!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//fark
				if ($c->sb_fark == "on") {
				$temphtml = '<a rel="nofollow" href="http://cgi.fark.com/cgi/fark/submit.pl?new_url='. $sburl .'" title="Fark!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/fark.png" alt="Fark!" title="Fark!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//blogmarks
				if ($c->sb_blogmarks == "on") {
				$temphtml = '<a rel="nofollow" href="http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url='. $sburl .'&amp;title='. $sbtitle .'" title="Blogmarks!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/blogmarks.png" alt="Blogmarks!" title="Blogmarks!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//yahoo
				if ($c->sb_yahoo == "on") {
				$temphtml = '<a rel="nofollow" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u='. $sburl .'&amp;t='. $sbtitle .'" title="Yahoo!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/yahoo.png" alt="Yahoo!" title="Yahoo!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//smarking
				if ($c->sb_smarking == "on") {
				$temphtml = '<a rel="nofollow" href="http://smarking.com/editbookmark/?url='. $sburl .'" title="Smarking!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/smarking.png" alt="Smarking!" title="Smarking!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//netvouz
				if ($c->sb_netvouz == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.netvouz.com/action/submitBookmark?url='. $sburl .'&amp;title='. $sbtitle .'" title="Smarking!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/netvouz.png" alt="Netvouz!" title="Netvouz!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//shadows
				if ($c->sb_shadows == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.shadows.com/bookmark/saveLink.rails?page='. $sburl .'&amp;title='. $sbtitle .'" title="Shadows!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/shadows.png" alt="Shadows!" title="Shadows!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//rawsugar
				if ($c->sb_rawsugar == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.rawsugar.com/tagger/?turl='. $sburl .'&amp;title='. $sbtitle .'" title="RawSugar!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/rawsugar.png" alt="RawSugar!" title="RawSugar!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//magnolia
				if ($c->sb_magnolia == "on") {
				$temphtml = '<a rel="nofollow" href="http://ma.gnolia.com/beta/bookmarklet/add?url='. $sburl .'&amp;title='. $sbtitle .'" title="Ma.gnolia!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/magnolia.png" alt="Ma.gnolia!" title="Ma.gnolia!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//plugim
				if ($c->sb_plugim == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.plugim.com/submit?url='. $sburl .'&amp;title='. $sbtitle .'" title="PlugIM!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/plugim.png" alt="PlugIM!" title="PlugIM!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//squidoo
				if ($c->sb_squidoo == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.squidoo.com/lensmaster/bookmark?'. $sburl .'" title="Squidoo!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/squidoo.png" alt="Squidoo!" title="Squidoo!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//blogmemes
				if ($c->sb_blogmemes == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.blogmemes.net/post.php?url='. $sburl .'&amp;title='. $sbtitle .'" title="BlogMemes!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/blogmemes.png" alt="BlogMemes!" title="BlogMemes!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//feedmelinks
				if ($c->sb_feedmelinks == "on") {
				$temphtml = '<a rel="nofollow" href="http://feedmelinks.com/categorize?from=toolbar&amp;op=submit&amp;url='. $sburl .'&amp;name='. $sbtitle .'" title="FeedMeLinks!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/feedmelinks.png" alt="FeedMeLinks!" title="FeedMeLinks!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//blinkbits
				if ($c->sb_blinkbits == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.blinkbits.com/bookmarklets/save.php?v=1&amp;source_url='. $sburl .'&amp;title='. $sbtitle .'" title="BlinkBits!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/blinkbits.png" alt="BlinkBits!" title="BlinkBits!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//tailrank
				if ($c->sb_tailrank == "on") {
				$temphtml = '<a rel="nofollow" href="http://tailrank.com/share/?text=&amp;link_href='. $sburl .'&amp;title='. $sbtitle .'" title="Tailrank!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/tailrank.png" alt="Tailrank!" title="Tailrank!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}
				//linkagogo
				if ($c->sb_linkagogo == "on") {
				$temphtml = '<a rel="nofollow" href="http://www.linkagogo.com/go/AddNoPopup?url='. $sburl .'&amp;title='. $sbtitle .'" title="linkaGoGo!" target="_blank"><img src="'.URL_HWDVS_IMAGES.'socialbookmarker/linkagogo.png" alt="linkaGoGo!" title="linkaGoGo!" class="sblinks" /></a>';
				$bmhtml = $bmhtml . $temphtml ."\n";
				}

			$code = $bmhtml;
		}

		if (!empty($code))
		{
			$smartyvs->assign("showShareButton", 1);
		}

		return $code;
	}
    /**
     * Generates the group membership status of a user
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateGroupMembershipStatus( $row )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$code = null;
		if ( !$my->id ){ return $code; }

		$url = JRoute::_($_SERVER['REQUEST_URI']);

		$db->SetQuery( 'SELECT count(*)'
				. ' FROM #__hwdvidsgroup_membership'
				. ' WHERE groupid = '.$row->id
				. ' AND memberid = '.$my->id
				);
		$total = $db->loadResult();
		echo $db->getErrorMsg();

		if ($total > 0) {
			$code.="<form name=\"leavegroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=leavegroup")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"memberid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"groupid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/group_delete.png\" alt=\""._HWDVIDS_DETAILS_LEAVEG."\">&nbsp;";
			$code.="<input type=\"submit\" value=\""._HWDVIDS_DETAILS_LEAVEG."\" class=\"interactbutton\">";
			$code.="</form>";
		} else {
			$code.="<form name=\"joingroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=joingroup")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"memberid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"groupid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/group_add.png\" alt=\""._HWDVIDS_DETAILS_JOING."\">&nbsp;";
			$code.="<input type=\"submit\" value=\""._HWDVIDS_DETAILS_JOING."\" class=\"interactbutton\">";
			$code.="</form>";
		}
		return $code;
    }
    /**
     * Generates the group membership status of a user
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateChannelSubscriptionStatus( $row )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$code = null;
		if ( !$my->id ){ return $code; }

		$url = JRoute::_($_SERVER['REQUEST_URI']);

		$db->SetQuery( 'SELECT count(*)'
				. ' FROM #__hwdvidssubs'
				. ' WHERE memberid = '.$my->id
				. ' AND userid = '.$row->user_id
				);
		$total = $db->loadResult();
		echo $db->getErrorMsg();

		if ($total > 0) {
			$code.="<form name=\"unsubscribe\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=unsubscribeChannel")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"memberid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$row->user_id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_unsubscribe.png\" alt=\""._HWDVIDS_UNSUBSCRIBE."\">&nbsp;";
			$code.="</form>";
		} else {
			$code.="<form name=\"subscribe\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=subscribeChannel")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"memberid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$row->user_id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."button_subscribe.png\" alt=\""._HWDVIDS_SUBSCRIBE."\">&nbsp;";
			$code.="</form>";
		}
		return $code;
    }
    /**
     * Generates the delete group button
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateDeleteGroupButton( $row )
	{
		global $hwdvsItemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);
		if ( $my->id == $row->adminid ){
			hwd_vs_javascript::confirmDelete();
			$code.="<form name=\"deletegroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=deletegroup")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"groupid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/delete.png\" alt=\""._HWDVIDS_DETAILS_DELETEG."\" onClick=\"return confirmDelete()\">";
			$code.="<input type=\"submit\" value=\""._HWDVIDS_DETAILS_DELETEG."\" class=\"interactbutton\" onClick=\"return confirmDelete()\">";
			$code.="</form>";
		}
		return $code;
    }
    /**
     * Generates the delete group link
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateDeleteGroupLink( $row )
	{
		global $hwdvsItemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);
		if ( $my->id == $row->adminid ){
			hwd_vs_javascript::confirmDelete();
			$code.="<form name=\"deletegroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=deletegroup")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"groupid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/delete.png\" alt=\""._HWDVIDS_DETAILS_DELETEG."\"  onClick=\"return confirmDelete()\">";
			$code.="</form>";
		}
		return $code;
    }
    /**
     * Generates the delete group link
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateDeletePlaylistLink( $row )
	{
		global $hwdvsItemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);
		if ( $my->id == $row->user_id ){
			hwd_vs_javascript::confirmDelete();
			$code.="<form name=\"deletegroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=deletePlaylist")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"playlistid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/delete.png\" alt=\""._HWDVIDS_DETAILS_DELETEG."\"  onClick=\"return confirmDelete()\">";
			$code.="</form>";
		}
		return $code;
    }
    /**
     * Generates the edit group button
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateEditGroupButton( $row )
	{
		global $hwdvsItemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);
		if ( $my->id == $row->adminid ){
			$code.="<form name=\"editgroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=editgroup")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"groupid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/edit.png\" alt=\""._HWDVIDS_DETAILS_EDITG."\">";
			$code.="<input type=\"submit\" value=\""._HWDVIDS_DETAILS_EDITG."\" class=\"interactbutton\">";
			$code.="</form>";
		}
		return $code;
    }
    /**
     * Generates the edit group link
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateEditGroupLink( $row )
	{
		global $hwdvsItemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);
		if ( $my->id == $row->adminid ){
			$code.="<form name=\"editgroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=editgroup")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"groupid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/edit.png\" alt=\""._HWDVIDS_DETAILS_EDITG."\">";
			$code.="</form>";
		}
		return $code;
    }
    /**
     * Generates the edit group link
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateEditPlaylistLink( $row )
	{
		global $hwdvsItemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);
		if ( $my->id == $row->user_id ){
			$code.="<form name=\"editgroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=editPlaylist")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"playlistid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/edit.png\" alt=\""._HWDVIDS_DETAILS_EDITG."\">";
			$code.="</form>";
		}
		return $code;
    }
    /**
     * Generates the delete video button
     *
     * @param string $row  the video sql data
     * @return       $code
     */
	function generateDeleteVideoLink( $row )
	{
		global $hwdvsItemid, $mainframe, $isModerator;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'js.php');

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);

		// check component access settings and deny those without privileges
		if (!$isModerator)
		{
			if ($my->id == $row->user_id)
			{
				if ($my->id == "0")
				{
					return $code;
				}
				if ($c->allowviddel == "0")
				{
					return $code;
				}
			}
			else
			{
				return $code;
			}
		}

		hwd_vs_javascript::confirmDelete();

		$code.="<form name=\"deletevideo\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=deletevideo&video_id=".$row->id)."\" method=\"post\">";
		$code.="<input type=\"hidden\" name=\"videoid\" value=\"".$row->id."\" />";
		$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
		$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/delete.png\" alt=\""._HWDVIDS_DETAILS_DELETEVID."\" onClick=\"return confirmDelete()\">";
		$code.="</form>";

		return $code;
    }
    /**
     * Generates the delete video button
     *
     * @param string $row  the video sql data
     * @return       $code
     */
	function generateBreadcrumbs($row=null, $title=null) {

		global $mainframe, $task, $hwdvsItemid;
		$db = & JFactory::getDBO();

		jimport( 'joomla.application.pathway' );
		$breadcrumbs =& JFactory::getApplication()->getPathway();

		jimport( 'joomla.application.menu' );
		$menu   = &JMenu::getInstance('site');
		$menu_details = &$menu->getActive();

		$crumbs = array();

		if (!empty($task))
		{
			if (@$menu_details->link !== "index.php?option=com_hwdvideoshare&task=frontpage")
			{
				array_pop( $breadcrumbs->_pathway );
				array_pop( $breadcrumbs->_pathway );
				array_pop( $breadcrumbs->_pathway );
			}

			if (@$menu_details->parent !== "0")
			{
				array_pop( $breadcrumbs->_pathway );
				array_pop( $breadcrumbs->_pathway );
				array_pop( $breadcrumbs->_pathway );
			}

			$index = 0;
			$insertVideoBreadcrumb = true;

			if ($insertVideoBreadcrumb)
			{
				$crumbs[$index][0] = _HWDVIDS_META_DEFAULT;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=frontpage');
				$index++;
			}

			if ($task == "frontpage")
			{
				$crumbs[$index][0] = _HWDVIDS_META_FP;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=frontpage');
			}
			else if ($task == "search" || $task == "displayresults")
			{
				$crumbs[$index][0] = _HWDVIDS_META_SR;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=search');
			}
			else if ($task == "upload")
			{
				$crumbs[$index][0] = _HWDVIDS_META_UPLD;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=uploadconfirm');
			}
			else if ($task == "uploadconfirm" || $task == "addconfirm")
			{
				$crumbs[$index][0] = _HWDVIDS_META_UPLDSUC;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=uploadconfirm');
			}
			else if ($task == "viewvideo")
			{
				$crumbs[$index][0] = _HWDVIDS_META_CATS;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=categories');
				$index++;

				$query = 'SELECT parent, category_name FROM #__hwdvidscategories WHERE id = '.$row->category_id;
				$db->SetQuery($query);
				$videoCategory = $db->loadObject();

				if ($videoCategory->parent == 0)
				{
					$crumbs[$index][0] = $videoCategory->category_name;
					$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$row->category_id);
					$index++;
				}
				else
				{
					$query = 'SELECT * FROM #__hwdvidscategories WHERE id = '.$videoCategory->parent;
					$db->SetQuery($query);
					$parent_category = $db->loadObject();

					if ($parent_category->parent == 0)
					{
						$crumbs[$index][0] = $parent_category->category_name;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$parent_category->id);
						$index++;

						$crumbs[$index][0] = $title;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$row->id);
						$index++;
					}
					else
					{
						$query = 'SELECT * FROM #__hwdvidscategories WHERE id = '.$parent_category->parent;
						$db->SetQuery($query);
						$top_category = $db->loadObject();

						$crumbs[$index][0] = $top_category->category_name;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$top_category->id);
						$index++;

						$crumbs[$index][0] = $parent_category->category_name;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$parent_category->id);
						$index++;

						$crumbs[$index][0] = $title;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$row->id);
						$index++;
					}
				}

				$crumbs[$index][0] = $title;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewvideo&video_id='.$row->id);
			}
			else if ($task == "categories")
			{
				$crumbs[$index][0] = _HWDVIDS_META_CATS;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=categories');
			}
			else if ($task == "viewcategory")
			{
				$crumbs[$index][0] = _HWDVIDS_META_CATS;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=categories');
				$index++;

				$query = 'SELECT parent FROM #__hwdvidscategories WHERE id = '.$row->id;
				$db->SetQuery($query);
				$parent = $db->loadResult();

				if ($parent == 0)
				{
					$crumbs[$index][0] = $title;
					$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$row->id);
				}
				else
				{

					$query = 'SELECT * FROM #__hwdvidscategories WHERE id = '.$parent;
					$db->SetQuery($query);
					$parent_category = $db->loadObject();

					if ($parent_category->parent == 0)
					{
						$crumbs[$index][0] = $parent_category->category_name;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$parent_category->id);
						$index++;

						$crumbs[$index][0] = $title;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$row->id);
					}
					else
					{
						$query = 'SELECT * FROM #__hwdvidscategories WHERE id = '.$parent_category->parent;
						$db->SetQuery($query);
						$top_category = $db->loadObject();

						$crumbs[$index][0] = $top_category->category_name;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$top_category->id);
						$index++;

						$crumbs[$index][0] = $parent_category->category_name;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$parent_category->id);
						$index++;

						$crumbs[$index][0] = $title;
						$crumbs[$index][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=viewcategory&cat_id='.$row->id);
					}
				}
			}
		}
		else
		{
			$crumbs[0][0] = _HWDVIDS_META_DEFAULT;
			$crumbs[0][1] = JRoute::_('index.php?option=com_hwdvideoshare&Itemid='.$hwdvsItemid.'&task=frontpage');
		}

		for ($i=0, $n=count($crumbs); $i < $n; $i++)
		{
			$breadcrumbs->addItem($crumbs[$i][0], $crumbs[$i][1]);
		}

		return;
    }
    /**
     * Generates the delete video button
     *
     * @param string $row  the video sql data
     * @return       $code
     */
	function generatePublishVideoLink( $row )
	{
		global $hwdvsItemid, $mainframe, $isModerator;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'js.php');

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);

		// check component access settings and deny those without privileges
		if (!$isModerator)
		{
			return $code;
		}

		$publish_task = $row->published ? '0' : '1';
		$publish_text = $row->published ? 'Unpublish' : 'Publish';
		$publish_img = $row->published ? 'unpublish.png' : 'publish.png';

		$code.="<form name=\"publishvideo\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=publishvideo&video_id=".$row->id."&publish=".$publish_task)."\" method=\"post\">";
		$code.="<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
		$code.="<input type=\"hidden\" name=\"videoid\" value=\"".$row->id."\" />";
		$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
		$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/".$publish_img."\" alt=\"".$publish_text."\">";
		$code.="</form>";

		return $code;
    }
       /**
     * Generates the delete video button
     *
     * @param string $row  the video sql data
     * @return       $code
     */
	function generateApproveVideoLink( $row )
	{
		global $hwdvsItemid, $mainframe, $isModerator;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'js.php');

		$code = null;

		// check component access settings and deny those without privileges
		if (!$isModerator)
		{
			return $code;
		}

		if ($row->approved == "pending")
		{
			$code.="<form name=\"approvevideo\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=approvevideo&video_id=".$row->id)."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"videoid\" value=\"".$row->id."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/tick.png\" alt=\"Approve\">";
			$code.="</form>";
		}

		return $code;
    }
    /**
     * Generates the delete video link
     *
     * @param string $row  the video sql data
     * @return       $code
     */
	function generateEditVideoLink( $row )
	{
		global $hwdvsItemid, $mainframe, $isModerator;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$doc = & JFactory::getDocument();

		$code = null;
		$url = JRoute::_($_SERVER['REQUEST_URI']);

		// check component access settings and deny those without privileges
		if (!$isModerator)
		{
			if ($my->id == $row->user_id)
			{
				if ($my->id == "0")
				{
					return $code;
				}
				if ($c->allowvidedit == "0")
				{
					return $code;
				}
			}
			else
			{
				return $code;
			}
		}

		$code.="<form name=\"editvideo\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=editvideo")."\" method=\"post\">";
		$code.="<input type=\"hidden\" name=\"user_id\" value=\"".$my->id."\" />";
		$code.="<input type=\"hidden\" name=\"video_id\" value=\"".$row->id."\" />";
		$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
		$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/edit.png\" alt=\""._HWDVIDS_DETAILS_EDITVID."\">";
		$code.="</form>";

		return $code;
    }

    /**
     * Generates the delete video link
     *
     * @param string $row  the video sql data
     * @return       $code
     */
	function generateEditChannelLink( $row )
	{
		global $hwdvsItemid, $mainframe;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();

		$code = null;

		if ($my->id == "0")
		{
			return $code;
		}

		$code.="<form name=\"editChannel\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=editChannel")."\" method=\"post\">";
		$code.="<input type=\"hidden\" name=\"channel_id\" value=\"".$row->id."\" />";
		$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/edit.png\" alt=\""._HWDVIDS_EDITCHANNEL."\">";
		$code.="</form>";

		return $code;
    }

    /**
     * Generates the report group button
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateReportGroupButton( $row )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$code = null;
		if ( !$my->id ){ return $code; }

		$url = JRoute::_($_SERVER['REQUEST_URI']);

			$code.="<form name=\"reportgroup\" style=\"display: inline;\" action=\"".JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=reportgroup")."\" method=\"post\">";
			$code.="<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
			$code.="<input type=\"hidden\" name=\"groupid\" value=\"".$row->id."\" />";
			$code.="<input type=\"hidden\" name=\"url\" value=\"".$url."\" />";
			$code.="<input type=\"image\" src=\"".URL_HWDVS_IMAGES."icons/flag.png\" alt=\""._HWDVIDS_DETAILS_REPORTG."\">&nbsp;";
			$code.="<input type=\"submit\" value=\""._HWDVIDS_DETAILS_REPORTG."\" class=\"interactbutton\">";
			$code.="</form>";

		return $code;
    }
    /**
     * Generates the 'add video to group' button
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateAddToGroupButton($row)
	{
		global $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$code = null;
		if ($my->id && $c->showa2gb == "1")
		{
			$smartyvs->assign("showAddButton", 1);
			$smartyvs->assign("print_addtogroup", 1);
			hwd_vs_javascript::ajaxaddtogroup($row);

			// Setup ajax tags
			if ($c->ajaxa2gmeth == 1) { $ajaxa2g = "onsubmit=\"ajaxFunctionA2G();return false;\""; } else { $ajaxa2g = null; }

			$db->SetQuery( 'SELECT count(*)'
								. ' FROM #__hwdvidsgroup_membership AS a'
								. ' LEFT JOIN #__hwdvidsgroups AS l ON l.id = a.groupid'
								. ' WHERE a.memberid = '.$my->id
								);
			$total = $db->loadResult();
			echo $db->getErrorMsg();

			if ($total > 0)
			{
				$query = 'SELECT a.*, l.*'
									. ' FROM #__hwdvidsgroup_membership AS a'
									. ' LEFT JOIN #__hwdvidsgroups AS l ON l.id = a.groupid'
									. ' WHERE a.memberid = '.$my->id
									. ' ORDER BY a.memberid'
									;

				$db->SetQuery($query);
				$grows = $db->loadObjectList();

				$code.= "<form name=\"add2group\" ".$ajaxa2g." action=\"".JURI::root( true )."/index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=addvideotogroup\" method=\"post\">";
				$code.= "<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
				$code.= "<input type=\"hidden\" name=\"videoid\" value=\"".$row->id."\" />";
				$code.= "<select name=\"groupid\" class=\"add2gselect\">";
				$code.= "<option value=\"0\">"._HWDVIDS_DETAILS_A2G."</option>";
					$n=count($grows);
					for ($i=0, $n=count($grows); $i < $n; $i++) {
						$grow = $grows[$i];
						$code.= "<option value =\"".$grow->id."\">".$grow->group_name."</option>";
					}
				$code.= "</select>&nbsp;";
				$code.= "<input type=\"submit\" value=\""._HWDVIDS_BUTTON_ADD."\" id=\"add2groupbutton\" class=\"interactbutton\" />";
				$code.= "</form>";
			}
		}
		return $code;
    }
    /**
     * Generates the 'add video to group' button
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateAddToPlaylistButton($row)
	{
		global $smartyvs, $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		hwd_vs_javascript::ajaxaddtoplaylist($row);

		// setup ajax tags
	    if ($c->ajaxa2gmeth == 1) { $ajaxa2g = "onsubmit=\"ajaxFunctionA2PL();return false;\""; } else { $ajaxa2g = null; }

		$code = null;

		$db->SetQuery( 'SELECT count(*)'
							. ' FROM #__hwdvidsplaylists'
							. ' WHERE user_id = '.$my->id
							);
		$total = $db->loadResult();
		echo $db->getErrorMsg();

		if ($total > 0) {

			$smartyvs->assign("showAddButton", 1);
			$smartyvs->assign("print_addtoplaylist", 1);

			$query = 'SELECT * FROM #__hwdvidsplaylists WHERE user_id = '.$my->id;
			$db->SetQuery($query);
			$rows = $db->loadObjectList();

			$code.= "<form name=\"add2playlist\" ".$ajaxa2g." action=\"".JURI::root( true )."/index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=addvideotoplaylist\" method=\"post\">";
			$code.= "<input type=\"hidden\" name=\"userid\" value=\"".$my->id."\" />";
			$code.= "<input type=\"hidden\" name=\"videoid\" value=\"".$row->id."\" />";
			$code.= "<select name=\"playlistid\" class=\"add2gselect\">";
			$code.= "<option value=\"0\">Add to playlist</option>";
				$n=count($rows);
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$code.= "<option value =\"".$row->id."\">".$row->playlist_name."</option>";
				}
			$code.= "</select>&nbsp;";
			$code.= "<input type=\"submit\" value=\""._HWDVIDS_BUTTON_ADD."\" id=\"add2groupbutton\" class=\"interactbutton\" />";
			$code.= "</form>";

		}

		return $code;
    }
    /**
     * Generates the 'video comments' system
     *
     * @param string $row  the video sql data
     * @return       $code
     */
	function generateVideoComments($row)
	{
		global $mainframe, $hwdvsItemid, $smartyvs, $botDisplay;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();

		$code = null;

		if ( $c->showcoms == "1" && $row->allow_comments == "1" && $c->commssys !== "99" )
		{
			$smartyvs->assign("print_comments", 1);
			if ( $c->commssys == 0 )
			{
				if (!file_exists(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS))
				{
					$code.= "<div class=\"padding\">"._HWDVIDS_INFO_NOINS_JCOMMENTS."</div>";
				}
				else
				{
					$comments = JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php';
					if (file_exists( $comments ))
					{
						require_once( $comments );
						$comments = JComments::showComments( $row->id, 'com_hwdvideoshare_v', $row->title );
			            $code.= "<div class=\"padding\">".$comments."</div>";
					}
				}
			}
			else if ( $c->commssys == 2 )
			{
				if (!file_exists(JPATH_SITE. DS . 'administrator' . DS . 'components' . DS . 'com_comment' . DS . 'plugin' . DS . 'com_hwdvideoshare' . DS . 'josc_com_hwdvideoshare.php'))
				{
					$code.= "<div class=\"padding\">"._HWDVIDS_INFO_NOINS_JOOMLACOMMENT."</div>";
				}
				else
				{
					require_once(JPATH_SITE. DS . 'administrator' . DS . 'components' . DS . 'com_comment' . DS . 'plugin' . DS . 'com_hwdvideoshare' . DS . 'josc_com_hwdvideoshare.php');
					$code = output($row, '');
				}
			}
			else if ( $c->commssys == 3 )
			{
				if (!file_exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php'))
				{
					$code.= "<div class=\"padding\">"._HWDVIDS_INFO_NOINS_JOMCOMMENTS."</div>";
				}
				else
				{
					include_once(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php');
					$comments = jomcomment( $row->id, 'com_hwdvideoshare_v');
					$code.= "<div class=\"padding\">".$comments."</div>";
				}
			}
			else if ( $c->commssys == 7 )
			{
				if (!file_exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'kunenadiscuss.php'))
				{
					$code.= "<div class=\"padding\">Kunena DicsussBot is not installed.</div>";
				}
				else
				{
					$db_catid = 1;

					include_once(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'kunenadiscuss.php');
					$dispatcher	=& JDispatcher::getInstance();
					JPluginHelper::importPlugin('content');
					$db_comments->id = $row->id;
					$db_comments->sectionid = $row->category_id;
					$db_comments->catid = $row->category_id;
					$db_comments->state = $row->published;
					$db_comments->title = $row->title;
					$db_comments->created_by = $row->user_id;
					$db_comments->text = '{mos_fb_discuss:'.$db_catid.'}';
					$db_results = $dispatcher->trigger('onPrepareContent', array (&$db_comments, null, 0));
					//print_r($db_comments);
					//print_r($botDisplay);
					//exit;
					$code.= "<div class=\"padding\">".$botDisplay[$row->id]."</div>";
				}
			}

			else if ( $c->commssys == 8 )
			{
				if (!file_exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'kunenadiscuss.php'))
				{
					$code.= "<div class=\"padding\">Kunena DicsussBot is not installed.</div>";
				}
				else
				{
					$db_catid = 5551;

					$item                 = JTable::getInstance('content');
					$dispatcher           = JDispatcher::getInstance();
					$params               = new JParameter('');
					JPluginHelper::importPlugin('content');
					$item->parameters     = new JParameter('');
					$item->id             = $row->id;
					$item->state          = $row->published;
					$item->catid          = "$db_catid";
					$item->sectionid      = null;
					$item->title          = stripslashes($row->title);
					$item->text           = "{kunena_discuss}";

					// Apply content plugins to custom text
					$results              = $dispatcher->trigger('onPrepareContent', array ($item, $params, 0));

					$code.= "<div class=\"padding\">".plgContentKunenaDiscuss::$botDisplay[$row->id]."</div><div style=\"clear:both;\"></div>";
				}
			}
			else if ( $c->commssys == 9 )
			{
				if (!file_exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jacomment.php'))
				{
					$code.= "<div class=\"padding\">JA Comment plugin is not installed.</div>";
				}
				else
				{
					$code.= "<div class=\"padding\">{jacomment contentid=".$row->id." option=com_hwdvideoshare_v contenttitle=".stripslashes($row->title)."}</div>";
				}
			}
		}
		$smartyvs->assign("comment_code", $code);
		return $code;
    }
    /**
     * Generates the 'group comments' system
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function generateGroupComments($row)
	{
		global $hwdvsItemid, $smartyvs, $botDisplay;
		$c = hwd_vs_Config::get_instance();

		$code = null;

		if ( $c->showcoms ==1 && $row->allow_comments == 1 )
		{
			$smartyvs->assign("print_comments", 1);
			if ( $c->commssys == 0 )
			{
				if (!file_exists(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS))
				{
					$code.= "<div class=\"padding\">"._HWDVIDS_INFO_NOINS_JCOMMENTS."</div>";
				}
				else
				{
					$comments = JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php';
					if (file_exists( $comments ))
					{
						require_once( $comments );
						$comments = JComments::showComments( $row->id, 'com_hwdvideoshare_g', $row->group_name );
			            $code.= "<div class=\"padding\">".$comments."</div>";
					}
				}
			}
			else if ( $c->commssys == 2 )
			{
				if (!file_exists(JPATH_SITE. DS . 'administrator' . DS . 'components' . DS . 'com_comment' . DS . 'plugin' . DS . 'com_hwdvideoshareGroup' . DS . 'josc_com_hwdvideoshareGroup.php'))
				{
					$code.= "<div class=\"padding\">"._HWDVIDS_INFO_NOINS_JOOMLACOMMENT."</div>";
				}
				else
				{
					require_once(JPATH_SITE . DS  . 'administrator' . DS . 'components' . DS . 'com_comment' . DS . 'plugin' . DS . 'com_hwdvideoshareGroup' . DS . 'josc_com_hwdvideoshareGroup.php');
					$code = output($row, '');
				}
			}
			else if ( $c->commssys == 3 )
			{
				if (!file_exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php'))
				{
					$code.= "<div class=\"padding\">"._HWDVIDS_INFO_NOINS_JOMCOMMENTS."</div>";
				}
				else
				{
					include_once(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php');
					$comments = jomcomment( $row->id, 'com_hwdvideoshare_g');
					$code.= "<div class=\"padding\">".$comments."</div>";
				}
			}
			else if ( $c->commssys == 8 )
			{
				if (!file_exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'kunenadiscuss.php'))
				{
					$code.= "<div class=\"padding\">Kunena DicsussBot is not installed.</div>";
				}
				else
				{
					$db_catid = 5552;

					$item                 = JTable::getInstance('content');
					$dispatcher           = JDispatcher::getInstance();
					$params               = new JParameter('');
					JPluginHelper::importPlugin('content');
					$item->parameters     = new JParameter('');
					$item->id             = $row->id;
					$item->state          = $row->published;
					$item->catid          = "$db_catid";
					$item->sectionid      = null;
					$item->title          = stripslashes($row->group_name);
					$item->text           = "{kunena_discuss}";

					// Apply content plugins to custom text
					$results              = $dispatcher->trigger('onPrepareContent', array ($item, $params, 0));

					$code.= "<div class=\"padding\">".plgContentKunenaDiscuss::$botDisplay[$row->id]."</div><div style=\"clear:both;\"></div>";
				}
			}
			else if ( $c->commssys == 9 )
			{
				if (!file_exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jacomment.php'))
				{
					$code.= "<div class=\"padding\">JA Comment plugin is not installed.</div>";
				}
				else
				{
					$code.= "<div class=\"padding\">{jacomment contentid=".$row->id." option=com_hwdvideoshare_g contenttitle=".stripslashes($row->title)."}</div>";
				}
			}
		}
		$smartyvs->assign("comment_code", $code);
		return $code;
    }
    /**
     * Generates the human readable allowed video formats string
     *
     * @return       $code
     */
	function generateVideoDetails($row, $player_width=null, $player_height=null, $thumb_width=null, $hwdvsItemid=null, $tooltip=null, $lightbox=null, $autoplay=null)
	{
		global $hwdvsItemid, $option, $mainframe, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();

		if (!isset($row->username)) { $row->username = ""; }
		if (!isset($row->name)) { $row->name = ""; }
		if (!isset($row->avatar)) { $row->avatar = null; }
		if ($c->cbint == 3) { $row->avatar = $row->username; }

		$quality = JRequest::getWord( 'quality', '' );
		if (empty($quality))
		{
			if ($c->usehq == "1" || $c->usehq == "3")
			{
				$quality = "hd";
			}
			else if ($c->usehq == "0" || $c->usehq == "2")
			{
				$quality = "sd";
			}
		}

		$details->id = intval($row->id);
		$details->titleText = stripslashes($row->title);
		$details->title = hwd_vs_tools::generateVideoLink( $row->id, $row->title, $hwdvsItemid, null, 10000 );
		$details->player = hwd_vs_tools::generateVideoPlayer($row, $player_width, $player_height, $autoplay, $quality);
		$details->videourl = hwd_vs_tools::generateVideoUrl($row);
		$details->embedcode = hwd_vs_tools::generateEmbedCode($row);
		$details->socialbmlinks = hwd_vs_tools::generateSocialBookmarks();
		$details->duration = $row->video_length;
		$details->ratingsystem = hwd_vs_tools::generateRatingSystem($row);
		$details->favouritebutton = hwd_vs_tools::generateFavouriteButton($row);
		$details->thumbnail = hwd_vs_tools::generateVideoThumbnailLink($row->id, $row->video_id, $row->video_type, $row->thumbnail, 0, $thumb_width, null, null, null, $hwdvsItemid, null, $tooltip, $lightbox);
		$details->avatar = hwd_vs_tools::generateAvatar($row->user_id, $row->avatar, 0);
		$details->category = hwd_vs_tools::generateCategoryLink($row->category_id);
		$details->description_truncated = hwd_vs_tools::truncateText($row->description, $c->trunvdesc);
		$details->rating = hwd_vs_tools::generateRatingImg($row->updated_rating);
		$details->deletevideo = hwd_vs_tools::generateDeleteVideoLink($row);
		$details->editvideo = hwd_vs_tools::generateEditVideoLink($row);
		$details->publishvideo = hwd_vs_tools::generatePublishVideoLink($row);
		$details->approvevideo = hwd_vs_tools::generateApproveVideoLink($row);
		$details->views = intval($row->number_of_views);
		$details->upload_date = strftime("%l%P - %b %e, %Y", strtotime($row->date_uploaded));
		$details->sendToFriend = hwd_vs_tools::sendToFriend($row);
		$details->uploader = hwd_vs_tools::generateUserFromID($row->user_id, $row->username, $row->name);
		$details->k = 0;

		$details->addtogroup = hwd_vs_tools::generateAddToGroupButton($row);
		$details->nextprev = hwd_vs_tools::generateNextPrevLinks($row);
		$details->switchquality = hwd_vs_tools::generateSwitchQuality($row);
		$details->downloadoriginal = hwd_vs_tools::generateDownloadVideoLink($row);
		$details->vieworiginal = hwd_vs_tools::generateViewOriginalLink($row);
		$details->reportmedia = hwd_vs_tools::generateReportMediaButton($row);
		$details->tags = hwd_vs_tools::generateTagListString($row->tags);
		$details->favourties = hwd_vs_tools::generateFavouriteButton($row);
		$details->addtoplaylist = hwd_vs_tools::generateAddToPlaylistButton($row);

		if ($option == "com_hwdvideoshare")
		{
			$details->comments = hwd_vs_tools::generateVideoComments($row);
		}

		if ($c->showdesc == "1")
		{
			$smartyvs->assign("print_description", 1);
			$details->description = stripslashes($row->description);

			//$item                 = JTable::getInstance('content');
			//$dispatcher           = JDispatcher::getInstance();
			//$params               = new JParameter('');
			//JPluginHelper::importPlugin('content');
			//$item->parameters     = new JParameter('');
			//$item->text           = $details->description;
			//// Apply content plugins to custom text
			//$results              = $dispatcher->trigger('onPrepareContent', array ($item, $params, 0));
			//$details->description = $item->text;
		}
		return $details;
    }
    /**
     * Generates the human readable allowed video formats string
     *
     * @return       $code
     */
	function generateAllowedFormats()
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code = null;
		if ($c->requiredins == 1) {
			if ($c->ft_mpg == "on") {$code .= "<b>mpg</b>, ";}
			if ($c->ft_mpeg == "on") {$code .= "<b>mpeg</b>, ";}
			if ($c->ft_avi == "on") {$code .=  "<b>avi</b>, ";}
			if ($c->ft_divx == "on") {$code .=  "<b>divx</b>, ";}
			if ($c->ft_mp4 == "on") {$code .=  "<b>mp4</b>, ";}
			if ($c->ft_flv == "on") {$code .=  "<b>flv</b>, ";}
			if ($c->ft_wmv == "on") {$code .=  "<b>wmv</b>, ";}
			if ($c->ft_rm == "on") {$code .=  "<b>rm</b>, ";}
			if ($c->ft_mov == "on") {$code .=  "<b>mov</b>, ";}
			if ($c->ft_moov == "on") {$code .=  "<b>moov</b>, ";}
			if ($c->ft_asf == "on") {$code .=  "<b>asf</b>, ";}
			if ($c->ft_swf == "on") {$code .=  "<b>swf</b>, ";}
			if ($c->ft_vob == "on") {$code .=  "<b>vob</b>, ";}

			$oformats = explode(",", $c->oformats);
			for ($i = 0, $n = count($oformats); $i < $n; $i++)
			{
				$oformat = $oformats[$i];
				$oformat = preg_replace("/[^a-zA-Z0-9s]/", "", $oformat);
				$code .=  "<b>".$oformat."</b>, ";
			}

		} else {

			if ($c->ft_flv == "on") {$code .=  "<b>flv</b>, ";}
			if ($c->ft_mp4 == "on") {$code .=  "<b>mp4</b>, ";}
			if ($c->ft_swf == "on") {$code .=  "<b>swf</b>, ";}

		}
		if (substr($code, -2) == ", ") {$code = substr($code, 0, -2);}
		return $code;
    }
    /**
     * Generates a username from user id with CB link if CB integration is avtivated
     *
     * @param int    $user_id  the user id
     * @return       $code
     */
	function generateUserFromID( $user_id=null, $username=null, $name=null )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
  		$db =& JFactory::getDBO();

		if (!isset($user_id) || $user_id == 0)
			return _HWDVIDS_INFO_GUEST;

		if ($c->userdisplay == 1)
		{
			if (!isset($username) || empty($username))
			{
				$query = 'SELECT username FROM #__users WHERE id = '.$user_id;
				$db->SetQuery( $query );
				$displayname = $db->loadResult();
			}
			else
			{
				$displayname = $username;
			}
		}
		else
		{
			if (!isset($name) || empty($name))
			{
				$query = 'SELECT name FROM #__users WHERE id = '.$user_id;
				$db->SetQuery( $query );
				$displayname = $db->loadResult();
			}
			else
			{
				$displayname = $name;
			}
		}

		$code = null;

		if ($c->cbint == 1)
		{
			if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
			$code = "<a href=\"".JRoute::_("index.php?option=com_comprofiler".$c->cbitemid."&task=userProfile&user=".$user_id)."\">".$displayname."</a>";
		}
		else if ($c->cbint == 2)
		{
			if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
			$code = "<a href=\"".JRoute::_("index.php?option=com_community".$c->cbitemid."&view=profile&userid=".$user_id)."\">".$displayname."</a>";
		}
		else if ($c->cbint == 3)
		{
			if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
			$code = "<a href=\"".JRoute::_("index.php?option=com_community&controller=profile".$c->cbitemid."&user_id=".$user_id)."\">".$displayname."</a>";
		}
		else if ($c->cbint == 4 || $c->cbint == 6)
		{
			if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
			$code = "<a href=\"".JRoute::_("index.php?option=com_kunena&func=fbprofile&Itemid=".$c->cbitemid."&userid=".$user_id)."\">".$displayname."</a>";
		}
		else if ($c->cbint == 5)
		{
			$code = "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=viewChannel&user_id=".$user_id."&Itemid=".$hwdvsItemid)."\">".$displayname."</a>";
		}
		else
		{
			$code = $displayname;
		}
		return $code;
    }
	/**
	 * get_redirect_url()
	 * Gets the address that the provided URL redirects to,
	 * or FALSE if there's no redirect.
	 *
	 * @param string $url
	 * @return string
	 */
	function generateLanguageDefinition($text)
	{
		if(defined($text)) $returnText = constant($text);
		else $returnText = $text;
		return $returnText;
	}
    /**
     * Verifies an URL is valid
     *
     * @param string $url  the url to check
     * @return       true/false
     */
	function is_valid_url ( $url )
	{
		if( preg_match('/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}'.'((:[0-9]{1,5})?\/.*)?$/i' ,$url))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
    /**
     * Verifies an URL is valid
     *
     * @param string $url  the url to check
     * @return       true/false
     */
	function validateUrl( $url )
	{
		if ($parseUrl = parse_url($url))
		{
			$parsedUrl = '';

			if (!empty($parseUrl['scheme']))
			{
				$parsedUrl.= $parseUrl['scheme'].'://';
			}
			else
			{
				return false;
			}

			if (!empty($parseUrl['host']))
			{
				$parsedUrl.= $parseUrl['host'];
			}
			else
			{
				return false;
			}

			if (!empty($parseUrl['path']))
			{
				$parsedUrl.= $parseUrl['path'];
			}

			if (!empty($parseUrl['query']))
			{
				$parsedUrl.= '?'.$parseUrl['query'];
			}
			//$parsedUrl = hwd_vs_tools::get_final_url( $parsedUrl );

			return $parsedUrl;
		}
	}
    /**
     * Generates a username from user id and creates a Joomla Backend link to either
     * core or CB profile page
     *
     * @param int    $user_id  the user id
     * @return       $code;
     */
	function generateBEUserFromID( $user_id=null )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db =& JFactory::getDBO();
		$code = null;
		if (!isset($user_id)) {
			$code = _HWDVIDS_INFO_GUEST;
		} else {
			// find user
			$query = 'SELECT username FROM #__users WHERE id = '.$user_id;
			$db->SetQuery( $query );
			$user = $db->loadResult();
			if ($c->cbint == 1) {
				if ($c->cbitemid !== "") { $c->cbitemid = "&Itemid=".$c->cbitemid; }
				$code = "<a href=\"index.php?option=com_comprofiler&task=edit&cid=".$user_id."\">".$user."</a>";
			} else {
				$code = "<a href=\"index.php?option=com_users&task=editA&id=".$user_id."&hidemainmenu=1\">".$user."</a>";
			}
		}
		return $code;
    }
    /**
     * Generates a new random video id
     *
     * @param int    $length  the length of new string
     * @return       $code;
     *
     * FUTURE: Check does not already exist
     */
	function generateNewVideoid( $length=13 )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code =null;
		// set default rating values
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;

		while ($i <= 13) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$code = $code . $tmp;
			$i++;
		}

		return $code;
	}
    /**
     * Generates the exact rating of a video
     *
     * @param array  $row  the video sql data
     * @return       $code;
     */
	function generateExactRating( $row )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code =null;
		if ((isset($row->rating_total_points) && $row->rating_total_points !== "0") && (isset($row->rating_number_votes) && $row->rating_number_votes !== "0") ) {
			$code = $row->rating_total_points/$row->rating_number_votes;
		} else {
			$code = "0";
		}
		return $code;
	}
    /**
     * Generates the rating star image for current rating
     *
     * @param int    $rating  the video rating
     * @return       $code;
     */
	function generateRatingImg( $rating )
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();

		$code =null;

		if (!isset($rating) || $rating > 5 || $rating < 0) { $rating = "0"; }
		$rating = round($rating, 0);

		$code = "<img src=\"".URL_HWDVS_IMAGES."ratings/stars".intval($rating)."0.png\" width=\"80\" height=\"16\" alt=\""._HWDVIDS_ALT_RATED." ".$rating."\" />";
		return $code;
	}
    /**
     * Appends the current avtive main navigation link with new id
     *
     * @param int    $active  the navigation link that is currently active
     * @return       Nothing
     */
	function generateActiveLink( $active ) {
		global $smartyvs;
		if ($active == 1) { $smartyvs->assign("von", " id=\"active\""); $smartyvs->assign("vact", 1); } else { $smartyvs->assign("von", ""); }
		if ($active == 2) { $smartyvs->assign("con", " id=\"active\""); $smartyvs->assign("cact", 1); } else { $smartyvs->assign("con", ""); }
		if ($active == 3) { $smartyvs->assign("gon", " id=\"active\""); $smartyvs->assign("gact", 1); } else { $smartyvs->assign("gon", ""); }
		if ($active == 4) { $smartyvs->assign("uon", " id=\"active\""); $smartyvs->assign("uact", 1); } else { $smartyvs->assign("uon", ""); }
		return;
	}
    /**
     * Generates the video player
     *
     * @param array  $row  the video sql data
     * @param int    $width(optional)  the video player width
     * @param int    $height(optional)  the video player width
     * @return       $code
     */
	function generateVideoPlayer( $row, $width=null, $height=null, $autostart=null, $quality="hd", $embedcode=false )
	{
		global $hwdvsItemid, $mainframe, $option, $task, $smartyvs, $show_video_ad, $pre_url, $post_url, $j15, $j16;
		$c = hwd_vs_Config::get_instance();
  		$db =& JFactory::getDBO();

		if (!isset($row->age_check)) { $row->age_check = "-1"; }
		if (($c->age_check > 0 && $row->age_check == "-1") || $row->age_check > 0)
		{
			$age_response = hwd_vs_tools::generateAgeCheck($row);
			if ($age_response !== true)
			{
				if ($embedcode == true)
				{
				}
				else
				{
					if ($age_response !== true)
					{
						return $age_response;
					}
				}
			}
		}

		if ($j16)
		{
			$vp_plugin_path = JPATH_SITE.DS."plugins".DS."hwdvs-videoplayer".DS.$c->hwdvids_videoplayer_file.DS.$c->hwdvids_videoplayer_file.".view.php";
		}
		else
		{
			$vp_plugin_path = JPATH_SITE.DS."plugins".DS."hwdvs-videoplayer".DS.$c->hwdvids_videoplayer_file.".view.php";
		}

		if (file_exists($vp_plugin_path))
		{
			require_once($vp_plugin_path);
		}
		else if (file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-videoplayer".DS."flow.view.php"))
		{
			require_once(JPATH_SITE.DS."plugins".DS."hwdvs-videoplayer".DS."flow.view.php");
		}
		else if (file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-videoplayer".DS."flow".DS."flow.view.php"))
		{
			require_once(JPATH_SITE.DS."plugins".DS."hwdvs-videoplayer".DS."flow".DS."flow.view.php");
		}
		else
		{
        	return "This video can not be displayed because there are no video players installed.";
		}

		$player = new hwd_vs_videoplayer();
		$flv_url = null;
		$flv_path = null;
		$thumb_url = null;
		$use_xMoovphp = false;
		$code = null;

		$location = hwd_vs_tools::generateVideoLocations($row, $quality);
		$thumb_url = hwd_vs_tools::generateThumbnailURL($row->id, @$row->video_id, $row->video_type, @$row->thumbnail, "large");

		if ($row->video_type == "local" || $row->video_type == "mp4" || $row->video_type == "swf")
		{
			// temporary html5 player fix
			if ($c->hwdvids_videoplayer_file == "jwflv_html5")
			{
				$c->use_protection = 0;
			}

			if ($c->storagetype !== "0")
			{
				$flv_url = $location['url'];
				$flv_path = $location['path'];
				$use_xMoovphp = $location['use_xMoovphp'];
				$dlink = $location['url'];
			}
			else if ($c->use_protection == 0)
			{
				$flv_url = $location['url'];
				$flv_path = $location['path'];
				$use_xMoovphp = $location['use_xMoovphp'];
				$dlink = $location['url'];
			}
			else
			{
				$flv_url = $location['url'];
				$flv_path = $location['path'];
				$use_xMoovphp = $location['use_xMoovphp'];
				$dlink = hwd_vs_tools::generateAntileechExpiration($row->id, $row->video_type, 'player', $quality);
				$dlink = urlencode($dlink);
			}

			if ($use_xMoovphp)
			{
				$pluginPlayer =& JPluginHelper::getPlugin("hwdvs-videoplayer", "$c->hwdvids_videoplayer_file");
				$pluginPlayerParams = new JParameter( $pluginPlayer->params );
				$pluginPlayerStreamer = $pluginPlayerParams->get('pseudostreaming', 0);

				if ($pluginPlayerStreamer == "1" && ($c->hwdvids_videoplayer_file == "jwflv" || $c->hwdvids_videoplayer_file == "jwflv_v5"))
				{
					$dlink = $row->video_id.".flv";
				}
			}

			if ( $row->video_type == "swf" && $c->standaloneswf == 1 )
			{
				$width = $c->flvplay_width;
				$height = $width*$c->var_fb;
				$smartyvs->assign("player_width", $width);
				$code.= "<div style=\"text-align: inherit;width:".$width."px!important;height:".$height."px!important;\">";
				$code.= "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" width=\"".$width."\" height=\"".$height."\" codebase=\"http://active.macromedia.com/flash7/cabs/ swflash.cab#version=9,0,0,0\">
						 <param name=\"movie\" value=\"".$flv_url."\">
						 <param name=\"play\" value=\"true\">
						 <param name=\"loop\" value=\"true\">
						 <param name=\"width\" value=\"".$width."\">
						 <param name=\"height\" value=\"".$height."\">
						 <param name=\"quality\" value=\"high\">
						 <param name=\"allowscale\" value=\"true\">
						 <param name=\"scale\" value=\"showall\">
						 <embed src=\"".$flv_url."\" width=\"".$width."\" height=\"".$height."\" play=\"true\" scale=\"showall\" loop=\"true\" quality=\"high\" pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" swLiveConnect=\"true\">
						 </embed>
						 </object>";
				return $code;
			}

			if ($show_video_ad == 1 && !$embedcode)
			{
				if ($c->hwdvids_videoplayer_file == "flow")
				{
					$flv_tracks = array();
					$flv_tracks[0] = $pre_url;
					$flv_tracks[1] = $dlink;
					$flv_tracks[2] = $post_url;
					$code.= $player->prepareEmbeddedPlayer($flv_tracks, $width, $height, rand(100, 999), "playlist", $flv_path, null, $autostart);
					return $code;
				}
				else
				{
					$xspf_playlist = JPATH_SITE.DS."components".DS."com_hwdvideoshare".DS."xml".DS."xspf".DS.$row->id.".xml";
					@unlink($xspf_playlist);
					require_once(JPATH_SITE.DS."administrator".DS."components".DS."com_hwdrevenuemanager".DS."redrawplaylist.class.php");
					hwd_rm_playlist::writeFile($row, $dlink, $pre_url, $post_url, $thumb_url, $use_xMoovphp);

					if (file_exists($xspf_playlist))
					{
						$flv_url = JURI::root(true).'/components/com_hwdvideoshare/xml/xspf/'.$row->id.'.xml';
						$flv_path = JPATH_SITE.DS."components".DS."com_hwdvideoshare".DS."xml".DS."xspf".DS.$row->id.".xml";

						if ($c->loadswfobject == "on" && $task !=="grabjomsocialplayer")
						{
							$code.= $player->prepareplayer($flv_url, $width, $height, rand(100, 999), "playlist", $flv_path, null, $autostart, $row->id);
						}
						else
						{
							$code.= $player->prepareEmbeddedPlayer($flv_url, $width, $height, rand(100, 999), "playlist", $flv_path, null, $autostart, $row->id);
						}
					}
				}
			}
			else
			{
				if ($c->loadswfobject == "on" && $task !=="grabjomsocialplayer" && !$embedcode)
				{
					$code.= $player->prepareplayer($dlink, $width, $height, rand(100, 999), "video", $flv_path, $thumb_url, $autostart, $row->id);
				}
				else if (!$embedcode)
				{
					$code.= $player->prepareEmbeddedPlayer($dlink, $width, $height, rand(100, 999), "video", $flv_path, $thumb_url, $autostart, $row->id);
				}
				else
				{
					$code.= $player->prepareEmbedCode($dlink, $width, $height, rand(100, 999), "video", $flv_path, $thumb_url, $autostart, $row->id);
				}
			}
		}
		else if ( $row->video_type == "playlist" )
		{
			$flv_path = $row->playlist;
			if ($c->loadswfobject == "on")
			{
				$code.= $player->prepareplayer($flv_path, $width, $height, rand(100, 999), "playlist", null, null, $autostart, null);
			}
			else
			{
				$code.= $player->prepareEmbeddedPlayer($flv_path, $width, $height, rand(100, 999), "playlist", null, null, $autostart, null);
			}
		}
		else if ( $row->video_type == "direct" )
		{
			$code.= $player->prepareEmbeddedPlayer($row->video_url, $width, $height, rand(100, 999), "video", $flv_path, $thumb_url, $autostart, $row->id);
		}
		else if ($row->video_type == "seyret")
		{
			if (@explode(",", $video_code))
			{
				$data = explode(",", $row->video_id);
			}
			else
			{
				return;
			}
			if ($data[0] == "local")
			{
				$file->id = $row->id;
				$file->video_type = "remote";
				if (!empty($data[2]))
				{
					$file->video_id = @$data[1].",".@$data[2];
				}
				else
				{
					$file->video_id = @$data[1];
				}
				$file->thumbnail = $row->thumbnail;
				$code.= hwd_vs_tools::generateVideoPlayer($file, $width, $height, $autostart, $quality, $embedcode);
			}
			else
			{
				$file->id = $row->id;
				$file->video_type = $data[0];
				if (!empty($data[2]))
				{
					$file->video_id = @$data[1].",".@$data[2];
				}
				else
				{
					$file->video_id = @$data[1];
				}
				$file->thumbnail = $row->thumbnail;
				$code.= hwd_vs_tools::generateVideoPlayer($file, $width, $height, $autostart, $quality, $embedcode);
			}
		}
		else
		{
			$plugin = hwd_vs_tools::getPluginDetails($row->video_type);
			if (!$plugin)
			{
				if ($width==null)
				{
					$smartyvs->assign("player_width", $c->tpwidth);
				}
				else
				{
					$smartyvs->assign("player_width", $width);
				}
				$code.= _HWDVIDS_INFO_NOPLUGIN." "._HWDVIDS_WMIP_01.$row->video_type._HWDVIDS_WMIP_02;
			}
			else
			{
				if (!$embedcode)
				{
					$preparevid = preg_replace("/[^a-zA-Z0-9s_-]/", "", $row->video_type)."PrepareVideo";
					$code.= $preparevid($row, $width, $height, $autostart);
				}
				else
				{
					$preparevid = preg_replace("/[^a-zA-Z0-9s_-]/", "", $row->video_type)."PrepareVideoEmbed";
					$code.= $preparevid($row->video_id, $row->id, $hwdvsItemid, $row);
				}
			}
		}

		if (!$embedcode)
		{
			return "<div id=\"hwdvsplayer\">".$code."</div>";
		}
		else
		{
			return $code;
		}
	}
    /**
     * Multiple select list
     *
     * @param int    $arr
     * @param int    $tag_name
     * @param int    $tag_attribs
     * @param int    $key
     * @param int    $text
     * @param int    $selected
     * @return       $html
     */
	function selectList2(&$arr, $tag_name, $tag_attribs, $key, $text, $selected)
	{
		reset ($arr);
		$html = "\n<select name=\"$tag_name\" $tag_attribs>";

		for ($i = 0, $n = count($arr); $i < $n; $i++)
		{
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = @$arr[$i]->id;
			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';

			if (is_array($selected))
			{
				foreach ($selected as $obj)
				{
					$k2 = $obj;

					if ($k == $k2)
					{
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			}
			else {
				$extra .= ($k == $selected ? " selected=\"selected\"" : '');
			}

			$html .= "\n\t<option value=\"" . $k . "\"$extra>" . $t . "</option>";
		}

		$html .= "\n</select>\n";
		return $html;
	}
    /**
     * catTreeRecurse
     *
     * @param int    $id
     * @param int    $indent
     * @param int    $list
     * @param int    $children
     * @param int    $maxlevel
     * @param int    $level
     * @param int    $seperator
     * @return       Nothing
     */
	function catTreeRecurse($id, $indent = "&nbsp;&nbsp;&nbsp;", $list, &$children, $maxlevel = 9999, $level = 0, $seperator = " >> ")
	{
		if (@$children[$id] && $level <= $maxlevel)
		{
			foreach ($children[$id] as $v)
			{
				$id = $v->id;
				$txt = $v->category_name;
				$pt = $v->parent;
				$list[$id] = $v;
				$list[$id]->treename = "$indent$txt";
				$list[$id]->children = count(@$children[$id]);
				$list = hwd_vs_tools::catTreeRecurse($id, "$indent$txt$seperator", $list, $children, $maxlevel, $level + 1);
			//$list = hwd_vs_tools::catTreeRecurse( $id, "*", $list, $children, $maxlevel, $level+1 );
			}
		}

		return $list;
	}
    /**
     * Generate select list for JACL access levels
     *
     * @param int    $jaclplus
     * @param int    $selectname
     * @return       $access
     */
	function generatePlayerThumbnail( $row )
	{
		$thumb_url = "";
		if ($row->video_type == "youtube.com")
		{
			$thumb_path = "http://i.ytimg.com/vi/".$row->video_id."/0.jpg";
		}
		else if ($row->video_type == "local" || $row->video_type == "mp4" || $row->video_type == "swf")
		{
			$row->thumbnail = (empty($row->thumbnail) ? "jpg" : $row->thumbnail);
			$thumb_path_l = PATH_HWDVS_DIR.DS."thumbs".DS."l_".$row->video_id.".".$row->thumbnail;
			$thumb_path_n = PATH_HWDVS_DIR.DS."thumbs".DS.$row->video_id.".".$row->thumbnail;

			if (file_exists($thumb_path_l) && (filesize($thumb_path_l) > 0))
			{
				$thumb_url = URL_HWDVS_DIR."/thumbs/l_".$row->video_id.".".$row->thumbnail;
			}
			else if (file_exists($thumb_path_n) && (filesize($thumb_path_n) > 0))
			{
				$thumb_url = URL_HWDVS_DIR."/thumbs/".$row->video_id.".".$row->thumbnail;
			}
		}
		else
		{
			if (!empty($row->thumbnail))
			{
				$thumb_base = basename($row->thumbnail);
				$thumb_path_l = PATH_HWDVS_DIR.DS."thumbs".DS."l_".$thumb_base;
				$thumb_path_n = PATH_HWDVS_DIR.DS."thumbs".DS.$thumb_base;

				if (file_exists($thumb_path_l) && (filesize($thumb_path_l) > 0))
				{
					$thumb_url = URL_HWDVS_DIR."/thumbs/l_".$thumb_base;
				}
				else if (file_exists($thumb_path_n) && (filesize($thumb_path_n) > 0))
				{
					$thumb_url = URL_HWDVS_DIR."/thumbs/".$thumb_base;
				}
			}
			if (empty($thumb_url))
			{
				$data = explode(",", $row->video_id);
				if ($row->video_type == "seyret")
				{
					$thumb_url = @$data[2];
				}
				else
				{
					$thumb_url = @$data[1];
				}
			}
		}
		return $thumb_url;
	}
    /**
     * Generate select list for JACL access levels
     *
     * @param int    $jaclplus
     * @param int    $selectname
     * @return       $access
     */
	function validateDuration( $duration )
	{
		$code = preg_replace("/[^0-9s:]/", "", $duration);

		if (empty($code) || !isset($code))
		{
			$code = "N/A";
		}

		return $code;
	}
	/**
     * Generate select list for JACL access levels
     *
     * @param int    $jaclplus
     * @param int    $selectname
     * @return       $access
     */
	function hwdvsMultiAccess( $jaclplus, $selectname='access' ) {
		global $database;
		$db =& JFactory::getDBO();

		$jaclplusarray = explode( ",", $jaclplus );
		$i = 0;
		$result = count($jaclplusarray);
		while($i < $result){
			$jaclpluslists[$i] = new stdClass();
			$jaclpluslists[$i]->value = $jaclplusarray[$i];
			$i++;
		}

		$query = "SELECT id AS value, name AS text"
		. "\n FROM #__groups"
		. "\n ORDER BY id"
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();
		$access = JHTML::_('select.genericlist', $groups, $selectname, 'class="inputbox" size="6" multiple="true"', 'value', 'text', $jaclpluslists);

		return $access;
	}
    /**
     * Checks that the video upload form is complete and valid
     *
     * @param string $title
     * @param string $description
     * @param int    $category_id
     * @param string $tags
     * @param string $public_private
     * @param int    $allow_comments
     * @param int    $allow_embedding
     * @param int    $allow_ratings
     * @return       true/false
     */
	function generateVideoLocations( $row, $quality="hd" )
	{
		$c = hwd_vs_Config::get_instance();
		$use_xMoovphp = false;

		if ($row->video_type == "local" || $row->video_type == "mp4")
		{
			$remoteFail = false;
			$useRemoteFlv = false;
			if ($c->storagetype == "amazons3")
			{
				$plugin =& JPluginHelper::getPlugin('hwdvs-storage', 'amazons3');
				$pluginParams = new JParameter( $plugin->params );
				$bucketName  = $pluginParams->get('awsBucket', 'hwdvs');
				$bucketAlias  = $pluginParams->get('awsAlias', '');
				$cloudfront  = $pluginParams->get('cloudfront', '0');
				$HttpResourceUrl  = $pluginParams->get('HttpCloudfrontResourceUrl', '');
				$rtmpDelivery  = $pluginParams->get('rtmpDelivery', '0');
				$RtmpResourceUrl  = $pluginParams->get('RtmpCloudfrontResourceUrl', '');

				if ($cloudfront == "1" && !empty($HttpResourceUrl))
				{
					if ($rtmpDelivery == "1")
					{
						$plugin = hwd_vs_tools::getPluginDetails("rtmp");
						if (!$plugin)
						{
							//skip
						}
						else
						{
							$row->video_type = "rtmp";
							if (($c->usehq == "1" || $c->usehq == "2" || $c->usehq == "3") && $quality == "hd")
							{
								$row->video_id = $RtmpResourceUrl."/cfx/st:uploads/".$row->video_id.".mp4";
							}
							else
							{
								$row->video_id = $RtmpResourceUrl."/cfx/st:uploads/".$row->video_id.".flv";
							}
							$location['url'] = "";
							$location['path'] = "";
							$location['use_xMoovphp'] = $use_xMoovphp;
							return $location;
						}
					}
					$baseUrl = $HttpResourceUrl."/uploads/";
				}
				else
				{
					if (!empty($bucketAlias))
					{
						$baseUrl  = $bucketAlias."/uploads/";
					}
					else
					{
						$baseUrl  = "http://$bucketName.s3.amazonaws.com/uploads/";
					}
				}

				if (($c->usehq == "1" || $c->usehq == "2" || $c->usehq == "3") && $quality == "hd")
				{
					$url  = $baseUrl.$row->video_id.".mp4";
					$path = "";

					$useragent = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1)";
					$curl_handle=curl_init();
					curl_setopt($curl_handle,CURLOPT_URL,$url);
					curl_setopt($curl_handle,CURLOPT_HEADER,true);
					curl_setopt($curl_handle,CURLOPT_NOBODY,true);
					curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,true);
					curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,10);
					curl_setopt($curl_handle,CURLOPT_TIMEOUT,10);
					curl_setopt($curl_handle,CURLOPT_USERAGENT,$useragent);
					$buffer = curl_exec($curl_handle);
					curl_close($curl_handle);

					if (preg_match("/Not Found/i", $buffer))
					{
						$remoteFail = true;
						if (!file_exists(PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".mp4"))
						{
							$useRemoteFlv = true;
						}
					}
				}

				if (!isset($url) || $useRemoteFlv)
				{
					$url  = $baseUrl.$row->video_id.".flv";
					$path = "";

					$useragent = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1)";
					$curl_handle=curl_init();
					curl_setopt($curl_handle,CURLOPT_URL,$url);
					curl_setopt($curl_handle,CURLOPT_HEADER,true);
					curl_setopt($curl_handle,CURLOPT_NOBODY,true);
					curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,true);
					curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,10);
					curl_setopt($curl_handle,CURLOPT_TIMEOUT,10);
					curl_setopt($curl_handle,CURLOPT_USERAGENT,$useragent);
					$buffer = curl_exec($curl_handle);
					curl_close($curl_handle);

					if (preg_match("/Not Found/i", $buffer))
					{
						$remoteFail = true;
					}
					else
					{
						$remoteFail = false;
					}
				}
			}

			if ($c->storagetype == "0" || $remoteFail)
			{
				if (file_exists(PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".mp4") && $c->usehq == "3")
				{
					$url = URL_HWDVS_DIR."/uploads/".$row->video_id.".mp4";
					$path = PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".mp4";
					$use_xMoovphp = false;
				}
				else if (file_exists(PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".flv") && $c->usehq == "0")
				{
					$url = URL_HWDVS_DIR."/uploads/".$row->video_id.".flv";
					$path = PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".flv";
					$use_xMoovphp = true;
				}
				else if (file_exists(PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".mp4") && $quality == "hd")
				{
					$url = URL_HWDVS_DIR."/uploads/".$row->video_id.".mp4";
					$path = PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".mp4";
					$use_xMoovphp = false;
				}
				else if (file_exists(PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".flv"))
				{
					$url = URL_HWDVS_DIR."/uploads/".$row->video_id.".flv";
					$path = PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".flv";
					$use_xMoovphp = true;
				}
				else if (file_exists(PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".mp4"))
				{
					$url = URL_HWDVS_DIR."/uploads/".$row->video_id.".mp4";
					$path = PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".mp4";
					$use_xMoovphp = false;
				}
				else
				{
					$url = "";
					$path = "";
					$use_xMoovphp = false;
				}
			}
		}
		else if ($row->video_type == "swf")
		{
			$url = URL_HWDVS_DIR."/uploads/".$row->video_id.".swf";
			$path = PATH_HWDVS_DIR.DS."uploads".DS.$row->video_id.".swf";
		}
		else if ($row->video_type == "seyret")
		{
			$data = explode(",", $row->video_id);
			$path = '';

			if ($data[0] == "local")
			{
				$url = $data[1];
			}
			else
			{
				$plugin = hwd_vs_tools::getPluginDetails($data[0]);
				if (!$plugin)
				{
					$url = '';
				}
				else
				{
					$prepareurl = preg_replace("/[^a-zA-Z0-9s_-]/", "", $data[0])."PrepareFlvURL";
					if (!empty($data[2]))
					{
						$video_data = @$data[1].",".@$data[2];
					}
					else
					{
						$video_data = @$data[1];
					}
					$url = $prepareurl($video_data);
					$url = urldecode($url);
				}
			}
		}
		else
		{
			$path = '';
			$plugin = hwd_vs_tools::getPluginDetails($row->video_type);
			if (!$plugin)
			{
				$url = '';
			}
			else
			{
				$prepareurl = preg_replace("/[^a-zA-Z0-9s_-]/", "", $row->video_type)."PrepareFlvURL";
				$url = $prepareurl($row->video_id);
				$url = urldecode($url);
			}
		}

		$location['url'] = $url;
		$location['path'] = $path;
		$location['use_xMoovphp'] = $use_xMoovphp;

		return $location;
	}
    /**
     * Checks that the video upload form is complete and valid
     *
     * @param string $title
     * @param string $description
     * @param int    $category_id
     * @param string $tags
     * @param string $public_private
     * @param int    $allow_comments
     * @param int    $allow_embedding
     * @param int    $allow_ratings
     * @return       true/false
     */
	function checkFormComplete( $title, $description, $category_id, $tags, $public_private, $allow_comments, $allow_embedding, $allow_ratings ) {
		global $database;

		if ($title == "" || !isset($title)) {
        	hwd_vs_tools::infoMessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_UPLD_FORMERR01, "exclamation.png", 0);
			return false;
		} else if ($description == "" || !isset($description)) {
        	hwd_vs_tools::infoMessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_UPLD_FORMERR02, "exclamation.png", 0);
			return false;
		} else if ($category_id == "" || $category_id == 0 || !isset($category_id)) {
        	hwd_vs_tools::infoMessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_UPLD_FORMERR03, "exclamation.png", 0);
			return false;
		} else if ($tags == "" || !isset($tags)) {
        	hwd_vs_tools::infoMessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_UPLD_FORMERR04, "exclamation.png", 0);
			return false;
		} else if ($public_private == "" || !isset($public_private)) {
        	hwd_vs_tools::infoMessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_UPLD_FORMERR05, "exclamation.png", 0);
			return false;
		} else if (!isset($allow_comments)) {
        	hwd_vs_tools::infoMessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_UPLD_FORMERR06, "exclamation.png", 0);
			return false;
		} else if (!isset($allow_embedding)) {
        	hwd_vs_tools::infoMessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_UPLD_FORMERR07, "exclamation.png", 0);
			return false;
		} else if (!isset($allow_ratings)) {
        	hwd_vs_tools::infoMessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_UPLD_FORMERR08, "exclamation.png", 0);
			return false;
		} else {
			return true;
		}
	}
    /**
     * Checks that the video upload form is complete and valid
     *
     * @param string $group_name
     * @param string $public_private
     * @param int    $allow_comments
     * @param string $group_description
     * @return       true/false
     */
	function checkGroupFormComplete( $group_name, $public_private, $allow_comments, $group_description ) {
		global $database;

		if ($group_name == "" || !isset($group_name)) {
        	hwd_vs_tools::infoMessage(3, 0, _HWDVIDS_TITLE_GCF, _HWDVIDS_UPLD_GFORMERR01, "exclamation.png", 0);
			return false;
		} else if ($public_private == "" || !isset($public_private)) {
        	hwd_vs_tools::infoMessage(3, 0, _HWDVIDS_TITLE_GCF, _HWDVIDS_UPLD_GFORMERR02, "exclamation.png", 0);
			return false;
		} else if (!isset($allow_comments)) {
        	hwd_vs_tools::infoMessage(3, 0, _HWDVIDS_TITLE_GCF, _HWDVIDS_UPLD_GFORMERR03, "exclamation.png", 0);
			return false;
		} else if ($group_description == "" || !isset($group_description)) {
        	hwd_vs_tools::infoMessage(3, 0, _HWDVIDS_TITLE_GCF, _HWDVIDS_UPLD_GFORMERR04, "exclamation.png", 0);
			return false;
		} else {
			return true;
		}
	}
    /**
     * Checks that the video upload form is complete and valid
     *
     * @param string $group_name
     * @param string $public_private
     * @param int    $allow_comments
     * @param string $group_description
     * @return       true/false
     */
	function generatePostTitle($title=null)
	{
		if (empty($title))
		{
			$title = Jrequest::getVar( 'title', _HWDVIDS_UNKNOWN );
		}
		$title = stripslashes($title);
		$title = stripslashes($title);
		$title = hwdEncoding::charset_decode_utf_8($title);
		$title = hwdEncoding::charset_encode_utf_8($title);
		$title = htmlspecialchars_decode($title);
		$title = addslashes($title);

		return $title;
	}
	function generatePostDescription($description=null)
	{
		$c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();

		if (empty($description))
		{
			if (!hwd_vs_access::allowAccess( $c->gtree_edtr, $c->gtree_edtr_child, hwd_vs_access::userGID( $my->id )))
			{
				$description = Jrequest::getVar( 'description', _HWDVIDS_UNKNOWN );
			}
			else
			{
				$requestarray = JRequest::get( 'default', 2 );
				$description = trim(@$requestarray['description']);
			}
		}
		$description = stripslashes($description);
		$description = stripslashes($description);
		$description = hwdEncoding::charset_decode_utf_8($description);
		$description = hwdEncoding::charset_encode_utf_8($description);
		$description = htmlspecialchars_decode($description);
		$description = addslashes($description);

		return $description;
	}
	function generatePostTags($raw_tags=null)
	{
		if (empty($raw_tags))
		{
			$raw_tags = Jrequest::getVar( 'tags', _HWDVIDS_UNKNOWN );
		}
		$tags = '';
		$tag_arr_co = explode(",", $raw_tags);

		for ($j=0, $m=count($tag_arr_co); $j < $m; $j++)
		{
			$row_co = $tag_arr_co[$j];
			$row_co = hwdEncoding::charset_decode_utf_8($row_co);
			$row_co = preg_replace("/[^a-zA-Z0-9s_&#; -]/", "", $row_co);
			$row_co = hwdEncoding::charset_encode_utf_8($row_co);
			$row_co = htmlspecialchars_decode($row_co);
			$row_co = addslashes($row_co);

			if (!empty($row_co))
			{
				$tags.= $row_co.",";
			}

		}
		if (substr($tags, -1) == ",") {$tags = substr($tags, 0, -1);}

		return $tags;
	}
    /**
     * Trys to get the flv dimensions
     *
     * @param string $flv
     * @return
     */
	function getflvsize( $flv ) {
		require_once(HWDVIDSPATH.'/mvc/controller/id3/getid3.php');
		$getID3 = new getID3;
		$fileinfo = $getID3->analyze($flv);
		if(!($fileinfo['meta']['onMetaData']['width'] && $fileinfo['meta']['onMetaData']['height']))
			return false;
		$width = $fileinfo['meta']['onMetaData']['width'];
		$height = $fileinfo['meta']['onMetaData']['height'];
		return array($width, $height);
	}
    /**
     * Trys to get the flv dimensions
     *
     * @param string $flv
     * @return
     */
	function sendToFriend($row)
	{
		global $hwdvsItemid;

		$uri	=& JURI::getInstance();
		$base	= $uri->toString( array('scheme', 'host', 'port'));
		$link	= $base.JRoute::_("index.php?option=com_hwdvideoshare&task=viewvideo&video_id=".$row->id."&Itemid=$hwdvsItemid");
		$link	= str_replace("&amp;", "&", $link);
		$url	= 'index.php?option=com_mailto&tmpl=component&link='.base64_encode( $link );

		$status = 'width=400,height=350,menubar=yes,resizable=yes';

		$image = JHTML::_('image.site', 'emailButton.png', '/images/M_images/', NULL, NULL, JText::_('Email'));
		$text  = _HWDVIDS_SENDFRIEND;

		$attribs['title']	= JText::_( 'Email' );
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";

		$output = JHTML::_('link', JRoute::_($url), $image, $attribs);
		$output.= '&nbsp;';
		$output.= JHTML::_('link', JRoute::_($url), $text, $attribs);

		return $output;
	}
    /**
     * Decodes parsed XML data
     *
     * @param string $string  the parsed xml string
     * @return       $string
     */
	function xmlDecode($string) {
		$string = str_replace("&#38;", "&", $string);
		$string = str_replace("&#60;", "<", $string);
		$string = str_replace("&#62;", ">", $string);
		$string = str_replace("&#39;", "\"", $string);
		$string = str_replace("&#39;", "'", $string);
		$string = str_replace("&#169;", "©", $string);
		$string = str_replace("&#174;", "®", $string);
		return $string;
	}
    /**
     * Generates the captcha security code
     *
     * @return       $code
     */
	function generateCaptcha( ) {
		global $database, $smartyvs;
		$c = hwd_vs_Config::get_instance();

		$code = null;
		if ($c->disablecaptcha == 0) {

			$jversion = hwd_vs_access::checkJversion();

			$code.="<script language=\"javascript\">
					window.onload=refresh_hwd_Captcha;
					var image=\"".JURI::root( true )."/components/com_hwdvideoshare/assets/captcha/CaptchaSecurityImages.php?width=120&height=40&jversion=".$jversion."&characters=6&uid=".rand()."\";
						function refresh_hwd_Captcha()
						{
							document.images[\"hwdCaptchaPic\"].src=image+\"?\"+new Date();
						}
					</script>
					<img src=\"".JURI::root( true )."/components/com_hwdvideoshare/assets/images/loadingCaptcha.png\" alt=\"Security Code\" name=\"hwdCaptchaPic\" id=\"hwdCaptchaPic\" width=\"120\" height=\"40\" style=\"border: 1px solid Black; width: 120px; height: 40px;\" />
					<script language=\"javascript\">
					document.write('<div style=\"cursor:pointer;padding:3px;\" onclick=\"refresh_hwd_Captcha()\" >"._HWDVIDS_INFO_NEWCODE."</a>');
					</script>";
		$smartyvs->assign("print_captcha", 1);
		}

	return $code;
	}
    /**
     * Generates the human readable supported 'third party' website list
     *
     * @return       $code
     */
	function generateSupportedWebsiteList()
	{
		global $j15, $j16;
		$db = & JFactory::getDBO();

		if ($j16)
		{
			$db->SetQuery( 'SELECT * FROM #__extensions WHERE type = "plugin" AND folder = "hwdvs-thirdparty" AND enabled = 1 ORDER BY name ASC');
			$iniFile = JPATH_SITE.'/plugins/hwdvs-thirdparty/thirdpartysupportpack/support.ini';
		}
		else
		{
			$db->SetQuery( 'SELECT * FROM #__plugins WHERE published = 1 AND folder = "hwdvs-thirdparty" ORDER BY name ASC');
			$iniFile = JPATH_SITE.'/plugins/hwdvs-thirdparty/support.ini';
		}

    	$rows = $db->loadObjectList();

		$code = null;
		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			if ($row->element == "thirdpartysupportpack")
			{
				if (file_exists($iniFile))
				{
					require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'csv_iterator.class.php');
					$csvIterator = new CsvIterator($iniFile, true, ",", "\"");

					while ($csvIterator->next())
					{
						$row = $csvIterator->current();

						if (!isset($row['Name']) || empty($row['Name'])) { continue; }
						if (!isset($row['Website']) || empty($row['Website'])) { continue; }

						$code.= "<a href=\"".$row['Website']."\" target=\"_blank\">".$row['Name']."</a>, ";
					}
				}
			}
			else if ($row->element == "youtube")
			{
				$code.= "<a href=\"http://www.youtube.com\" target=\"_blank\">Youtube.com</a>, ";
			}
			else if ($row->element == "google")
			{
				$code.= "<a href=\"http://video.google.com\" target=\"_blank\">Google.com</a>, ";
			}
		}
		if (substr($code, -2) == ", ") {$code = substr($code, 0, -2);}
		return $code;
	}
	/**
     * Generates the Download Video Button
     *
     * @param array  $row  the video sql data
     * @param int    $original  link to original video or converted flv video (0/1)
     * @return       $code
     */
	function generateDownloadVideoLink( $row )
	{
		global $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		$code = null;
		if ($row->video_type == "local" || $row->video_type == "mp4"  || $row->video_type == "swf"  || $row->video_type == "seyret" || ($row->video_type == "remote" && substr($row->video_id, 0, 6) !== "embed|"))
		{
			if ($c->showdlor == "1")
			{
				$smartyvs->assign("showDownloadButton", 1);
				$smartyvs->assign("print_downloadOption", 1);

				if (!hwd_vs_access::checkAccess($c->gtree_dnld, $c->gtree_dnld_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, "You need to login to download videos.", "You do not have permission to download videos.", "exclamation.png", 0, "core.frontend.download", 1))
				{
					return "You do not have permission to download videos";
				}

				// setup antileech system expiration
				$dlink_generic = hwd_vs_tools::generateAntileechExpiration($row->id, 'local', '');

				$code.= "<form name=\"downloadVideo\" action=\"$dlink_generic\" method=\"post\">
						 <select name=\"deliver\">
						 <option value=\"original\">Original Video</option>
						 <option value=\"flv\">Standard Definition (FLV)</option>";
				if ($c->uselibx264 == "1")
				{
				$code.= "<option value=\"h264\">High Definition (MP4)</option>";
				}
				if ($c->ipod320 == "on")
				{
				$code.= "<option value=\"ipod340\">iPod 320 (MP4)</option>";
				}
				if ($c->ipod640 == "on")
				{
				$code.= "<option value=\"ipod620\">iPod 640 (MP4)</option>";
				}
//				if ($c->ogg == "on")
//				{
//				$code.= "<option value=\"ogg\">Ogg Theora (OGG)</option>";
//				}
				$code.= "</select>
						 <input type=\"submit\" value=\"Download\" />
						 </form>";
			}
		}
		return $code;
	}
	/**
     * Generates the View Original Video Button
     *
     * @param array  $row  the video sql data
     * @return       $code
     */
	function generateViewOriginalLink( $row )
	{
		global $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();

		$code = null;
		if ($row->video_type == "local" || $row->video_type == "mp4"  || $row->video_type == "swf"  || $row->video_type == "seyret" || ($row->video_type == "remote" && substr($row->video_id, 0, 6) !== "embed|"))
		{
		}
		else
		{
			if ($c->showvuor == "1")
			{
				$smartyvs->assign("showDownloadButton", 1);
				$smartyvs->assign("print_downloadOption", 1);

				$plugin = hwd_vs_tools::getPluginDetails($row->video_type);
				if (!$plugin)
				{
					$code.= "";
				}
				else
				{
					// print third party thumbnail
					$prepareurl = preg_replace("/[^a-zA-Z0-9s_-]/", "", $row->video_type)."PrepareVideoURL";
					if ($url = $prepareurl($row->video_id))
					{
						$code.= "<a href=\"$url\" title=\""._HWDVIDS_VOV."\" target=\"_blank\"><strong>"._HWDVIDS_VOV."</strong></a>";
					}
					else
					{
						$code.= "";
					}
				}
			}
		}
		return $code;
	}
	/**
     * Generates the View Original Video Button
     *
     * @param array  $row  the video sql data
     * @return       $code
     */
	function generateAgeCheck($row)
	{
		global $mainframe, $smartyvs, $hwdvsItemid;

		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		if (strpos(JURI::base(true), "/administrator") === false)
		{
			if ($row->age_check > 0)
			{
				$age = $row->age_check;
			}
			else if ($c->age_check > 0 && $row->age_check == "-1")
			{
				$age = $c->age_check;
			}
			else
			{
                return true;
			}

			$age_check_variable = $mainframe->getUserState( "hwdvsAge", "notset" );

			$code = null;

			if ($age_check_variable == "notset" || empty($age_check_variable))
			{
				if (isset($_POST['year']) && isset($_POST['month']) && isset($_POST['day']))
				{
					$day = intval($_POST['day']);
					$month = intval($_POST['month']);
					$year = intval($_POST['year']);

					if ($day == 0 || $month == 0 || $year == 0)
					{
						$smartyvs->assign("player_width", $c->flvplay_width);
						$code.= _HWDVIDS_AGEGATE." "._HWDVIDS_AGEGATE_INVALID;
					}
					else
					{
						$mainframe->setUserState( "hwdvsAge", "$day:$month:$year" );
						if (hwd_vs_tools::w3_checkbirthdate($month,$day,$year,$age))
						{
							return true;
						}
						else
						{
							$smartyvs->assign("player_width", $c->flvplay_width);
							$code.= _HWDVIDS_AGEGATE." "._HWDVIDS_AGEGATE_TOOYOUNG." ($day/$month/$year)";
							return "<div id=\"hwdvsplayer\">".$code."</div>";
						}
					}
				}
				else
				{
					$smartyvs->assign("player_width", $c->flvplay_width);
					$code.= _HWDVIDS_AGEGATE." "._HWDVIDS_AGEGATE_ENTER;
				}
			}
			else
			{
				// we already know there age, just verify it.
				$dateInfo = explode(':',$age_check_variable);

				$day = intval(@$dateInfo[0]);
				$month = intval(@$dateInfo[1]);
				$year = intval(@$dateInfo[2]);

				if (hwd_vs_tools::w3_checkbirthdate($month,$day,$year,$age))
				{
					return true;
				}
				else
				{
					$smartyvs->assign("player_width", $c->flvplay_width);
					$code.= _HWDVIDS_AGEGATE." "._HWDVIDS_AGEGATE_TOOYOUNG." ($day/$month/$year)";
					return "<div id=\"hwdvsplayer\">".$code."</div>";
				}
			}

			$code.= "<br /><br />";

			$link = JRoute::_("index.php?option=com_hwdvideoshare&task=viewvideo&Itemid=$hwdvsItemid&video_id=".$row->id);
			$ageForm = '<form action="#" method="post">
			<select name="month">
			<option value="na">'.JText::_('MONTH').'</option>
			<option value="1">'.JText::_('JANUARY').'</option>
			<option value="2">'.JText::_('FEBRUARY').'</option>
			<option value="3">'.JText::_('MARCH').'</option>
			<option value="4">'.JText::_('APRIL').'</option>
			<option value="5">'.JText::_('MAY').'</option>
			<option value="6">'.JText::_('JUNE').'</option>
			<option value="7">'.JText::_('JULY').'</option>
			<option value="8">'.JText::_('AUGUST').'</option>
			<option value="9">'.JText::_('SEPTEMBER').'</option>
			<option value="10">'.JText::_('OCTOBER').'</option>
			<option value="11">'.JText::_('NOVEMBER').'</option>
			<option value="12">'.JText::_('DECEMBER').'</option>
			</select>
			&nbsp;
			<select name="day">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
			</select>
			&nbsp;
			<select name="year">
			<option value="na">'.JText::_('YEAR').'</option>
			<option value="2009">2009</option>
			<option value="2008">2008</option>
			<option value="2007">2007</option>
			<option value="2006">2006</option>
			<option value="2005">2005</option>
			<option value="2004">2004</option>
			<option value="2003">2003</option>
			<option value="2002">2002</option>
			<option value="2001">2001</option>
			<option value="2000">2000</option>
			<option value="1999">1999</option>
			<option value="1998">1998</option>
			<option value="1997">1997</option>
			<option value="1996">1996</option>
			<option value="1995">1995</option>
			<option value="1994">1994</option>
			<option value="1993">1993</option>
			<option value="1992">1992</option>
			<option value="1991">1991</option>
			<option value="1990">1990</option>
			<option value="1989">1989</option>
			<option value="1988">1988</option>
			<option value="1987">1987</option>
			<option value="1986">1986</option>
			<option value="1985">1985</option>
			<option value="1984">1984</option>
			<option value="1983">1983</option>
			<option value="1982">1982</option>
			<option value="1981">1981</option>
			<option value="1980">1980</option>
			<option value="1979">1979</option>
			<option value="1978">1978</option>
			<option value="1977">1977</option>
			<option value="1976">1976</option>
			<option value="1975">1975</option>
			<option value="1974">1974</option>
			<option value="1973">1973</option>
			<option value="1972">1972</option>
			<option value="1971">1971</option>
			<option value="1970">1970</option>
			<option value="1969">1969</option>
			<option value="1968">1968</option>
			<option value="1967">1967</option>
			<option value="1966">1966</option>
			<option value="1965">1965</option>
			<option value="1964">1964</option>
			<option value="1963">1963</option>
			<option value="1962">1962</option>
			<option value="1961">1961</option>
			<option value="1960">1960</option>
			<option value="1959">1959</option>
			<option value="1958">1958</option>
			<option value="1957">1957</option>
			<option value="1956">1956</option>
			<option value="1955">1955</option>
			<option value="1954">1954</option>
			<option value="1953">1953</option>
			<option value="1952">1952</option>
			<option value="1951">1951</option>
			<option value="1950">1950</option>
			<option value="1949">1949</option>
			<option value="1948">1948</option>
			<option value="1947">1947</option>
			<option value="1946">1946</option>
			<option value="1945">1945</option>
			<option value="1944">1944</option>
			<option value="1943">1943</option>
			<option value="1942">1942</option>
			<option value="1941">1941</option>
			<option value="1940">1940</option>
			<option value="1939">1939</option>
			<option value="1938">1938</option>
			<option value="1937">1937</option>
			<option value="1936">1936</option>
			<option value="1935">1935</option>
			<option value="1934">1934</option>
			<option value="1933">1933</option>
			<option value="1932">1932</option>
			<option value="1931">1931</option>
			<option value="1930">1930</option>
			<option value="1929">1929</option>
			<option value="1928">1928</option>
			<option value="1927">1927</option>
			<option value="1926">1926</option>
			<option value="1925">1925</option>
			<option value="1924">1924</option>
			<option value="1923">1923</option>
			<option value="1922">1922</option>
			<option value="1921">1921</option>
			<option value="1920">1920</option>
			<option value="1919">1919</option>
			<option value="1918">1918</option>
			<option value="1917">1917</option>
			<option value="1916">1916</option>
			<option value="1915">1915</option>
			<option value="1914">1914</option>
			<option value="1913">1913</option>
			<option value="1912">1912</option>
			<option value="1911">1911</option>
			<option value="1910">1910</option>
			<option value="1909">1909</option>
			&nbsp;
			<input type="submit" value="Submit">
			</form>';

			$code.= $ageForm;
			$code = "<div id=\"hwdvsplayer\">".$code."</div>";

			return $code;
		}
		else
		{
			return true;
		}
	}
	/**
     * Log a video view
     *
     * @param int    $videoid  the video id
     * @return       true/false
     */
	function w3_checkbirthdate($month,$day,$year,$age)
	{
		global $mainframe;

		$c = hwd_vs_Config::get_instance();

		$min_age = $age;

		if (!checkdate($month,$day,$year)) {
			return false;
		}

		list($this_year,$this_month,$this_day) = explode(',',date('Y,m,d'));

		$max_year = $this_year - $min_age;

		if ($year < $max_year)
		{
			return true;
		}
		elseif (($year == $max_year) && (($month < $this_month) || (($month == $this_month) && ($day <= $this_day))))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/**
     * Log a video view
     *
     * @param int    $videoid  the video id
     * @return       true/false
     */
	function logViewing( $videoid )
	{
		global $mainframe;
		$app = & JFactory::getApplication();

		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$view_check = $app->getUserState( "hwdvs_viewed_$videoid", "notviewed" );

		if ($view_check !== "viewed") {

			$app->setUserState( "hwdvs_viewed_$videoid", "viewed" );

			$row = new hwdvidslogs_views($db);

			$_POST['videoid'] 	= $videoid;
			$_POST['userid'] 	= $my->id;
			$_POST['date'] 		= date('Y-m-d H:i:s');

			// bind it to the table
			if (!$row -> bind($_POST)) {
				echo "<script> alert('"
					.$row -> getError()
					."'); window.history.go(-1); </script>\n";
				exit();
			}

			// store it in the db
			if (!$row -> store()) {
				echo "<script> alert('"
					.$row -> getError()
					."'); window.history.go(-1); </script>\n";
				exit();
			}

			unset($_POST['videoid']);
			unset($_POST['userid']);
			unset($_POST['date']);

		}

		return true;
	}
	/**
     * Log a rate
     *
     * @param int    $videoid  the video id
     * @param int    $vote  the rating value
     * @return       true/false
     */
	function logRating( $videoid, $vote ) {
		global $database, $my;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$row = new hwdvidslogs_votes($db);

		$_POST['videoid'] 	= $videoid;
		$_POST['userid'] 	= $my->id;
		$_POST['vote'] 		= $vote;
		$_POST['date'] 		= date('Y-m-d H:i:s');

		// bind it to the table
		if (!$row -> bind($_POST)) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// store it in the db
		if (!$row -> store()) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		return true;
	}
	/**
     * Log a favour
     *
     * @param int    $videoid  the video id
     * @param int    $favour  adding or removing favourite
     * @return       true/false
     */
	function logFavour( $videoid, $favour=1 ) {
		global $database, $my;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();

		$row = new hwdvidslogs_favours($db);

		$_POST['videoid'] 	= $videoid;
		$_POST['userid'] 	= $my->id;
		$_POST['favour'] 	= $favour;
		$_POST['date'] 		= date('Y-m-d H:i:s');

		// bind it to the table
		if (!$row -> bind($_POST)) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// store it in the db
		if (!$row -> store()) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		return true;
	}
	/**
     * Uploads a file from form
     *
     * @param int    $sec  the total number of seconds
     * @param int    $padHours(optional)
     * @return       $hms
     */
	function uploadFile( $input_name, $file_name, $base_Dir, $sizelimit=2, $allowed_formats='', $overwrite=0 ) {
		global $database, $my;
		$c = hwd_vs_Config::get_instance();

		$report = array();

		$file_name_tmp      = $_FILES[$input_name]['tmp_name'];
		$file_name_org      = $_FILES[$input_name]['name'];
		$file_size          = $_FILES[$input_name]['size'];
		$file_size_limit    = $sizelimit*1024*1024; //size limit in mb
		$file_ext[0]        = substr($file_name_org, strrpos($file_name_org, '.') + 1);
		$allowed_ext        = explode(",", $allowed_formats);
		$allowed_ext_compare = array_intersect($file_ext, $allowed_ext);
		$allowed_ext_result=false;
		if (count($allowed_ext_compare) > 0) {$allowed_ext_result=true;}

		if (empty($_FILES[$input_name]['name'])) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR00;
			return $report;
		} else if (!isset($_FILES[$input_name]['error'])) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR00;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 8) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR08;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 7) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR07;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 6) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR06;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 5) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR05;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 4) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR04;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 3) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR03;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 2) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR02;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 1) {
        	$report[0] = "0";
        	$report[1] = _HWDVIDS_PHPUPLD_ERR01;
			return $report;
		} else if ($_FILES[$input_name]['error'] == 0) {

			if (empty($file_name)) {
				// generate random filename
				$file_name = hwd_vs_tools::generateNewVideoid().".".$file_ext[0];
			} else {
				$file_name = $file_name.".".$file_ext[0];
			}

			if ($file_size > $file_size_limit) {
				$report[0] = "0";
				$report[1] = "File is too big";
				return $report;
			}

			if (!$allowed_ext_result) {
				$report[0] = "0";
				$report[1] = _HWDVIDS_ERROR_UPLDERR04." (".$allowed_formats.")";
				return $report;
			}

			if (!$overwrite && file_exists($base_Dir.$file_name)) {
				$report[0] = "0";
				$report[1] = _HWDVIDS_ERROR_UPLDERR05;
				return $report;
			}
			if (!move_uploaded_file ($_FILES[$input_name]['tmp_name'],$base_Dir.$file_name)) {
				$report[0] = "0";
				$report[1] = _HWDVIDS_ERROR_UPLDERR06;
				return $report;
			}

			@chmod($base_Dir.$file_name, 0755);

			$report[0] = "1";
			$report[1] = "Success";
			return $report;
		}
	}
	/**
	 * get_redirect_url()
	 * Gets the address that the provided URL redirects to,
	 * or FALSE if there's no redirect.
	 *
	 * @param string $url
	 * @return string
	 */
	function get_redirect_url($url){
		$redirect_url = null;

		$url_parts = @parse_url($url);
		if (!$url_parts) return false;
		if (!isset($url_parts['host'])) return false; //can't process relative URLs
		if (!isset($url_parts['path'])) $url_parts['path'] = '/';

		$sock = @fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
		if (!$sock) return false;

		$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n";
		$request .= 'Host: ' . $url_parts['host'] . "\r\n";
		$request .= "Connection: Close\r\n\r\n";
		fwrite($sock, $request);
		$response = '';
		while(!feof($sock)) $response .= fread($sock, 8192);
		fclose($sock);

		if (preg_match('/^Location: (.+?)$/m', $response, $matches)){
			return trim($matches[1]);
		} else {
			return false;
		}

	}


    /**
     * Convert seconds to HOURS:MINUTES:SECONDS format
     * @param database A database connector object
     */
	function sec2hms ($sec, $padHours = false)
	{
		// holds formatted string
		$hms = "";

		// there are 3600 seconds in an hour, so if we
		// divide total seconds by 3600 and throw away
		// the remainder, we've got the number of hours
		$hours = intval(intval($sec) / 3600);

		// add to $hms, with a leading 0 if asked for
		$hms .= ($padHours)
			  ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
			  : $hours. ':';


		// dividing the total seconds by 60 will give us
		// the number of minutes, but we're interested in
		// minutes past the hour: to get that, we need to
		// divide by 60 again and keep the remainder
		$minutes = intval(($sec / 60) % 60);

		// then add to $hms (with a leading 0 if needed)
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

		// seconds are simple - just divide the total
		// seconds by 60 and keep the remainder
		$seconds = intval($sec % 60);

		// add to $hms, again with a leading 0 if needed
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

		// done!
		return $hms;
	}
   /**
	* Convert seconds to HOURS:MINUTES:SECONDS format
	**/
	function hms2sec ($time, $padHours = false)
	{
		$temp = explode(":",$time);

		if (is_numeric(@$temp[0])) {
			$hour = @$temp[0];
		} else { $hour = 0; }
		if (is_numeric(@$temp[0])) {
			$minute = @$temp[1];
		} else { $minute = 0; }
		if (is_numeric(@$temp[0])) {
			$second = @$temp[2];
		} else { $second = 0; }

 		$sec = ($hour*3600) + ($minute*60) + ($second);
		return $sec;
	}

	/**
	 * get_all_redirects()
	 * Follows and collects all redirects, in order, for the given URL.
	 *
	 * @param string $url
	 * @return array
	 */
	function get_all_redirects($url){
		$redirects = array();
		while ($newurl = hwd_vs_tools::get_redirect_url($url)){
			if (in_array($newurl, $redirects)){
				break;
			}
			$redirects[] = $newurl;
			$url = $newurl;
		}
		return $redirects;
	}
	/**
	 * get_final_url()
	 * Gets the address that the URL ultimately leads to.
	 * Returns $url itself if it isn't a redirect.
	 *
	 * @param string $url
	 * @return string
	 */
	function get_final_url($url){
		$redirects = hwd_vs_tools::get_all_redirects($url);
		if (count($redirects)>0){
			return array_pop($redirects);
		} else {
			return $url;
		}
	}
	/**
	 * get_final_url()
	 * Gets the address that the URL ultimately leads to.
	 * Returns $url itself if it isn't a redirect.
	 *
	 * @param string $url
	 * @return string
	 */
	function checkRemoteFileExists($url){
		if (@fopen($url, "r")) {
			return true;
		} else {
			return false;
		}
	}

function isSSL()
{
	if(@$_SERVER['https'] == 1) /* Apache */
	{
		return TRUE;
	}
	elseif (@$_SERVER['https'] == 'on') /* IIS */
	{
		return TRUE;
	}
	elseif (@$_SERVER['SERVER_PORT'] == 443) /* others */
	{
		return TRUE;
	}
	else
	{
		return FALSE; /* just using http */
	}
}
	/**
	 * get_final_url()
	 * Gets the address that the URL ultimately leads to.
	 * Returns $url itself if it isn't a redirect.
	 *
	 * @param string $url
	 * @return string
	 */
	function getSelfURL(){
		$s = empty($_SERVER["HTTPS"]) ? ''
			: ($_SERVER["HTTPS"] == "on") ? "s"
			: "";
		$protocol = hwd_vs_tools::strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
			: (":".$_SERVER["SERVER_PORT"]);
		return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}
	function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }
	/**
	 * Legacy function, deprecated
	 *
	 * @deprecated    As of version 1.5
	 */
	function yesnoSelectList( $tag_name, $tag_attribs, $selected, $yes='yes', $no='no' )
	{
		$arr = array(
			JHTML::_('select.option', 0, JText::_( $no )),
			JHTML::_('select.option', 1, JText::_( $yes )),
		);

		return JHTML::_('select.genericlist', $arr, $tag_name, $tag_attribs, 'value', 'text', (int) $selected);
	}
	/**
	 * get_final_url()
	 * Gets the address that the URL ultimately leads to.
	 * Returns $url itself if it isn't a redirect.
	 *
	 * @param string $url
	 * @return string
	 */
	function getPluginDetails($type)
	{
		global $j15, $j16;

		if ($j16)
		{
			if ($type == 'youtube.com' && file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."youtube".DS."youtube.view.php"))
			{
				require_once(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."youtube".DS."youtube.view.php");
			}
			else if ($type == 'google.com' && file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."google".DS."google.view.php"))
			{
				require_once(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."google".DS."google.view.php");
			}
			else if ($type == 'remote' && file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."remote".DS."remote.view.php"))
			{
				require_once(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."remote".DS."remote.view.php");
			}
			else if (file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."$type".DS."$type.view.php"))
			{
				require_once(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."$type".DS."$type.view.php");
			}
			else
			{
				return false;
			}
		}
		else
		{
			if ($type == 'youtube.com' && file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."youtube.view.php"))
			{
				require_once(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."youtube.view.php");
			}
			else if ($type == 'google.com' && file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."google.view.php"))
			{
				require_once(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."google.view.php");
			}
			else if (file_exists(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."$type.view.php"))
			{
				require_once(JPATH_SITE.DS."plugins".DS."hwdvs-thirdparty".DS."$type.view.php");
			}
			else
			{
				return false;
			}
		}

		return true;
	}
    /**
     * addslashes() and prevents double-quoting
     *
     * @param array  $rows  the list from an xml file
     * @return       $code  the array prepared for Smarty template
     */
	function generateMetaText($receive) {

		$output = $receive;
		$output = strip_tags($output);
		$output = hwdEncoding::charset_encode_utf_8($output);
		while(strchr($output,'\"')) {
			$output = stripslashes($output);
		}
		$output = str_replace("\r", "", $output);
		$output = str_replace("\n", "", $output);
		$output = str_replace('"', "'", $output);

		return $output;

	}
    /**
     * Generates the array of information for a standard video list from parsed xml files
     *
     * @param array  $rows  the list from an xml file
     * @return       $code  the array prepared for Smarty template
     */
	function generateStaticModuleDisplay( $rows, $hwdvids_params )
	{
		global $hwdvsItemid, $option, $mainframe;

		$code =null;

		if ($hwdvids_params['talignment'] == 1) { $talign = "text-align:left;"; }
		if ($hwdvids_params['talignment'] == 2) { $talign = "text-align:center;"; }
		if ($hwdvids_params['talignment'] == 3) { $talign = "text-align:right;"; }
		if ($hwdvids_params['malignment'] == 1) { $malign = "float:left;"; }
		if ($hwdvids_params['malignment'] == 2) { $malign = "float:left;"; }
		if ($hwdvids_params['malignment'] == 3) { $malign = "float:right;"; }
		if (empty($hwdvids_params['novpr']) || $hwdvids_params['novpr'] == '') { $hwdvids_params['novpr'] = 3; }

		$n = min($hwdvids_params['novtd'],count($rows));

		for ($i=0, $n; $i < $n; $i++)
		{
			$row = $rows[$i];
			$code.="<div style=\"display:block;".$malign."padding:5px;overflow:hidden;".$talign."width:".$hwdvids_params['thumb_width']."px;\">".$row->thumbnail;
			if ($hwdvids_params['showtitle'] == 1) {$code.= '<br />'.$row->title;}
			if ($hwdvids_params['showcategory'] == 1) {$code.= '<br />'.$row->category;}
			if ($hwdvids_params['showdescription'] == 1) {$code.= '<br />'.$row->description;}
			if ($hwdvids_params['showrating'] == 1) {$code.= '<br />'.$row->rating;}
			if ($hwdvids_params['shownov'] == 1) {$code.= '<br />'._HWDVIDS_DETAILS_VIEWED.' '.$row->views.' '._HWDVIDS_DETAILS_TIMES;}
			if ($hwdvids_params['showduration'] == 1) {$code.= '<br />'.$row->duration;}
			if ($hwdvids_params['showuser'] == 1) {$code.= '<br />'.$row->uploader;}
			if ($hwdvids_params['showtime'] == 1) {$code.= '<br />'.$row->timesince;}
			$code.="</div>";

			if (($i-($hwdvids_params['novpr']-1)) % $hwdvids_params['novpr'] == 0) {
				$code.="<div style=\"clear:both;\"></div>";
			}
		}
		return $code;
    }
    /**
     * Generates the array of information for a standard video list from parsed xml files
     *
     * @param array  $rows  the list from an xml file
     * @return       $code  the array prepared for Smarty template
     */
	function generateNextPrevLinks( $row )
	{
		global $hwdvsItemid, $smartyvs;
		$c = hwd_vs_Config::get_instance();

		$code = null;
		if ($c->showprnx == "1")
		{
			$smartyvs->assign("print_nextprev", 1);
			$code.= "<a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=previousvideo&category_id=".$row->category_id."&video_id=".$row->id)."\" class=\"swap\">"._HWDVIDS_PREV."</a> | <a href=\"".JRoute::_("index.php?option=com_hwdvideoshare&task=nextvideo&category_id=".$row->category_id."&video_id=".$row->id)."\" class=\"swap\">"._HWDVIDS_NEXT."</a>";
		}
		return $code;
	}
    /**
     * Generates the array of information for a standard video list from parsed xml files
     *
     * @param array  $rows  the list from an xml file
     * @return       $code  the array prepared for Smarty template
     */
	function generateTimeSinceUpload( $date_uploaded ) {

		$code =null;

		// get time since upload
		$hour = substr($date_uploaded, 11, 2);
		$minutes = substr($date_uploaded, 14, 2);
		$seconds = substr($date_uploaded, 17, 2);
		$month = substr($date_uploaded, 5, 2);
		$date = substr($date_uploaded, 8, 2);
		$year = substr($date_uploaded, 0, 4);
		$upld_date = @mktime($hour, $minutes, $seconds, $month, $date, $year);
		$today = time();
		$difference = $today - $upld_date;

		if ($difference < 60) {
			$code = $difference." "._HWDVIDS_MP_SAGO;
		} else if ($difference < 3600) {
			$code = floor($difference / 60)." "._HWDVIDS_MP_MAGO;
		} else if ($difference < 86400) {
			$code = round($difference / 3600, 0)." "._HWDVIDS_MP_HAGO;
		} else {
			$code = round($difference / 86400, 0)." "._HWDVIDS_MP_DAGO;
		}

		return $code;
	}
	/**
     * readfile_chunked
     * Read the contents of a file in chunks
     * @param array  $row  the video sql data
     * @param int    $original  link to original video or converted flv video (0/1)
     * @return       $code
     */
	function readfile_chunked($filename,$retbytes=true)
	{
	   $chunksize = 1*(1024*1024); // how many bytes per chunk
	   $buffer = '';
	   $cnt =0;

	   $handle = fopen($filename, 'rb');
	   if ($handle === false)
	   {
		   return false;
	   }

	   while (!feof($handle))
	   {
		   $buffer = fread($handle, $chunksize);
		   echo $buffer;
		   ob_flush();
		   flush();
		   if ($retbytes)
		   {
			   $cnt += strlen($buffer);
		   }
	   }

	   $status = fclose($handle);
	   if ($retbytes && $status)
	   {
		   return $cnt; // return num. bytes delivered like readfile() does.
	   }
	   return $status;

	}
	/**
     * Generates the Download Video Button
     *
     * @param array  $row  the video sql data
     * @param int    $original  link to original video or converted flv video (0/1)
     * @return       $code
     */
	function generateAntileechExpiration($fid, $media, $deliver, $quality='hd')
	{
		global $hwdvsItemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();

		// setup antileech system expiration
		$download_exp_key = md5(microtime());

		$leech = new hwdvidsantileech($db);

		$_POST['expiration'] 		= $download_exp_key;

		// bind it to the table
		if (!$leech -> bind($_POST)) {
			echo "<script> alert('"
				.$leech -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		// store it in the db
		if (!$leech -> store()) {
			echo "<script> alert('"
				.$leech -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		unset($_POST['expiration']);

		$dlink = JURI::root()."index.php?option=com_hwdvideoshare&task=downloadfile&file=".$fid."&evp=".$download_exp_key."&media=".$media."&deliver=".$deliver."&quality=".$quality."&tmpl=component";

		return $dlink;
	}
    /**
     * Generates the edit group link
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function getChildCategories( $cat_id ) {
		global $mainframe;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();

        $query = 'SELECT id FROM #__hwdvidscategories WHERE parent = '.$cat_id;
        $db->SetQuery( $query );
        $children = $db->loadObjectList();

		$code = $cat_id;
		for ($i=0, $n=count($children); $i < $n; $i++) {
			$row = $children[$i];

			$code.= ','.$row->id;
			$query = 'SELECT id FROM #__hwdvidscategories WHERE parent = '.$row->id;
			$db->SetQuery( $query );
			$grandchildren = $db->loadObjectList();

			for ($j=0, $m=count($grandchildren); $j < $m; $j++) {
				$bow = $grandchildren[$j];

				$code.= ','.$bow->id;

			}

		}

		return $code;
    }
    /**
     * Generates the edit group link
     *
     * @param string $row  the group sql data
     * @return       $code
     */
	function getCurrentURL() {
	 $pageURL = 'http';
	 if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}


	//Legacy
	/**
	 * get_redirect_url()
	 * Gets the address that the provided URL redirects to,
	 * or FALSE if there's no redirect.
	 *
	 * @param string $url
	 * @return string
	 */
	function generateValidItemid($current=null)
	{
  		$db =& JFactory::getDBO();

		if (isset($current))
		{
			$db->SetQuery( 'SELECT id FROM #__menu WHERE `link` LIKE "%com_hwdvideoshare%" AND id = '.$current );
			$Itemid = $db->loadResult();
			if (!empty($Itemid))
			{
				return $Itemid;
			}
		}

		$db->SetQuery( 'SELECT id FROM #__menu WHERE `link` = "index.php?option=com_hwdvideoshare"' );
		$Itemid = $db->loadResult();

		if (empty($Itemid))
		{
			$db->SetQuery( 'SELECT id FROM #__menu WHERE `link` LIKE "%com_hwdvideoshare%"' );
			$Itemid = $db->loadResult();
		}

		if (empty($Itemid))
		{
			$Itemid = "0";
		}

		return $Itemid;
	}
}
/**
 * Tab Creation handler
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdTabs {
	/** @var int Use cookies */
	var $useCookies = 0;
	/**
	* Constructor
	* Includes files needed for displaying tabs and sets cookie options
	* @param int useCookies, if set to 1 cookie will hold last used tab between page refreshes
	*/
	function hwdTabs( $useCookies, $xhtml=NULL ) {
		global $mainframe;
		$doc = & JFactory::getDocument();
		$html = null;

		if ( $xhtml ) {
			$doc->addCustomTag( '<link rel="stylesheet" type="text/css" media="all" href="includes/js/tabs/tabpane.css" id="luna-tab-style-sheet" />' );
		} else {
			echo "<link id=\"luna-tab-style-sheet\" type=\"text/css\" rel=\"stylesheet\" href=\"" . JURI::root( true ). "/includes/js/tabs/tabpane.css\" />";
		}

		echo "<script type=\"text/javascript\" src=\"". JURI::root( true ) . "/includes/js/tabs/tabpane_mini.js\"></script>";

		$this->useCookies = $useCookies;
	}
	/**
	* creates a tab pane and creates JS obj
	* @param string The Tab Pane Name
	*/
	function startPane($id){
		$html = null;

		$html.= "<div class=\"tab-page\" id=\"".$id."\">";
		$html.= "<script type=\"text/javascript\">\n";
		$html.= "	var tabPane1 = new WebFXTabPane( document.getElementById( \"".$id."\" ), ".$this->useCookies." )\n";
		$html.= "</script>\n";
		return $html;
	}
	/**
	* Ends Tab Pane
	*/
	function endPane() {
		$html = null;
		$html.= "</div>";
		return $html;
	}
	/*
	* Creates a tab with title text and starts that tabs page
	* @param tabText - This is what is displayed on the tab
	* @param paneid - This is the parent pane to build this tab on
	*/
	function startTab( $tabText, $paneid ) {
		$html = null;
		$html.= "<div class=\"tab-page\" id=\"".$paneid."\">";
		$html.= "<h2 class=\"tab\">".$tabText."</h2>";
		$html.= "<script type=\"text/javascript\">\n";
		$html.= "  tabPane1.addTabPage( document.getElementById( \"".$paneid."\" ) );";
		$html.= "</script>";
		return $html;
	}
	/*
	* Ends a tab page
	*/
	function endTab() {
		$html = null;
		$html.= "</div>";
		return $html;
	}
}
/**
 * Process character encoding
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.3
 */
class hwdEncoding {

    function XMLEntities($string)
    {

		// Try to tidy up double encodings
		$string = str_replace("&#38;#38;#", "&#", $string);
		$string = str_replace("&#38;#", "&#", $string);
		$string = str_replace("&#38;amp;#", "&#", $string);
		$string = str_replace("&amp;#", "&#", $string);

		$string = str_replace("&", "&#38;", $string);
		$string = str_replace("<", "&#60;", $string);
		$string = str_replace(">", "&#62;", $string);
		$string = str_replace("\"", "&#34;", $string);
		$string = str_replace("'", "&#39;", $string);

		// Try to tidy up double encodings
		$string = str_replace("&#38;#38;#", "&#", $string);
		$string = str_replace("&#38;#", "&#", $string);
		$string = str_replace("&#38;amp;#", "&#", $string);
		$string = str_replace("&amp;#", "&#", $string);

		return $string;
    }

    function UNXMLEntities($string)
    {

		$string = str_replace("&#38;", "&", $string);
		$string = str_replace("&#60;", "<", $string);
		$string = str_replace("&#62;", ">", $string);
		$string = str_replace("&#34;", "\"", $string);
		$string = str_replace("&#39;", "'", $string);

		return $string;
    }

    function fixDoubleEncodings($string)
    {

		// Try to tidy up double encodings
		$string = str_replace("&#38;#38;#", "&#", $string);
		$string = str_replace("&#38;#", "&#", $string);
		$string = str_replace("&#38;amp;#", "&#", $string);
		$string = str_replace("&amp;#", "&#", $string);

		// Try to tidy up double encodings
		$string = str_replace("&#38;#38;#", "&#", $string);
		$string = str_replace("&#38;#", "&#", $string);
		$string = str_replace("&#38;amp;#", "&#", $string);
		$string = str_replace("&amp;#", "&#", $string);

		return $string;
    }

	function charset_decode_utf_8 ($string) {
		/* Only do the slow convert if there are 8-bit characters */
		/* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
		// if (! ereg("[\200-\237]", $string) and ! ereg("[\241-\377]", $string))
		//	  return $string;

		if (! preg_match("/[\200-\237]/", $string) and ! preg_match("/[\241-\377]/", $string))
			return $string;

		// decode three byte unicode characters
		$string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
		"'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'",
		$string);

		// decode two byte unicode characters
		$string = preg_replace("/([\300-\337])([\200-\277])/e",
		"'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'",
		$string);

		return $string;

	}

	function charset_encode_utf_8($string)
	{
		static $trans_tbl;

		// replace numeric entities
		$string = preg_replace('~&#([0-9]+);~e', 'hwdEncoding::unicodetoutf8(\\1)', $string);

		// replace literal entities
		if (!isset($trans_tbl))
		{
			$trans_tbl = array();

			foreach (get_html_translation_table(HTML_ENTITIES) as $val=>$key)
				$trans_tbl[$key] = utf8_encode($val);
		}

		return strtr($string, $trans_tbl);
	}

	/**
	 * Return utf8 symbol when unicode character number is provided
	 *
	 */
	function unicodetoutf8($var) {

		if ($var < 128) {

			$ret = chr ($var);

		} else if ($var < 2048) {

			// Two byte utf-8
			$binVal = str_pad (decbin ($var), 11, "0", STR_PAD_LEFT);
			$binPart1 = substr ($binVal, 0, 5);
			$binPart2 = substr ($binVal, 5);

			$char1 = chr (192 + bindec ($binPart1));
			$char2 = chr (128 + bindec ($binPart2));
			$ret = $char1 . $char2;

		} else if ($var < 65536) {

	        // Three byte utf-8
	        $binVal = str_pad (decbin ($var), 16, "0", STR_PAD_LEFT);
	        $binPart1 = substr ($binVal, 0, 4);
	        $binPart2 = substr ($binVal, 4, 6);
	        $binPart3 = substr ($binVal, 10);

	        $char1 = chr (224 + bindec ($binPart1));
	        $char2 = chr (128 + bindec ($binPart2));
	        $char3 = chr (128 + bindec ($binPart3));
	        $ret = $char1 . $char2 . $char3;

	    } else if ($var < 2097152) {

	        // Four byte utf-8
	        $binVal = str_pad (decbin ($var), 21, "0", STR_PAD_LEFT);
	        $binPart1 = substr ($binVal, 0, 3);
	        $binPart2 = substr ($binVal, 3, 6);
	        $binPart3 = substr ($binVal, 9, 6);
	        $binPart4 = substr ($binVal, 15);

	        $char1 = chr (240 + bindec ($binPart1));
	        $char2 = chr (128 + bindec ($binPart2));
	        $char3 = chr (128 + bindec ($binPart3));
	        $char4 = chr (128 + bindec ($binPart4));
	        $ret = $char1 . $char2 . $char3 . $char4;

	    } else if ($var < 67108864) {

	        // Five byte utf-8
	        $binVal = str_pad (decbin ($var), 26, "0", STR_PAD_LEFT);
	        $binPart1 = substr ($binVal, 0, 2);
	        $binPart2 = substr ($binVal, 2, 6);
	        $binPart3 = substr ($binVal, 8, 6);
	        $binPart4 = substr ($binVal, 14,6);
	        $binPart5 = substr ($binVal, 20);

	        $char1 = chr (248 + bindec ($binPart1));
	        $char2 = chr (128 + bindec ($binPart2));
	        $char3 = chr (128 + bindec ($binPart3));
	        $char4 = chr (128 + bindec ($binPart4));
	        $char5 = chr (128 + bindec ($binPart5));
	        $ret = $char1 . $char2 . $char3 . $char4 . $char5;

	    } else if ($var < 2147483648) {

	        // Six byte utf-8
	        $binVal = str_pad (decbin ($var), 31, "0", STR_PAD_LEFT);
	        $binPart1 = substr ($binVal, 0, 1);
	        $binPart2 = substr ($binVal, 1, 6);
	        $binPart3 = substr ($binVal, 7, 6);
	        $binPart4 = substr ($binVal, 13,6);
	        $binPart5 = substr ($binVal, 19,6);
	        $binPart6 = substr ($binVal, 25);

	        $char1 = chr (252 + bindec ($binPart1));
	        $char2 = chr (128 + bindec ($binPart2));
	        $char3 = chr (128 + bindec ($binPart3));
	        $char4 = chr (128 + bindec ($binPart4));
	        $char5 = chr (128 + bindec ($binPart5));
	        $char6 = chr (128 + bindec ($binPart6));
	        $ret = $char1 . $char2 . $char3 . $char4 . $char5 . $char6;

	    } else {

	        // there is no such symbol in utf-8
	        $ret='?';

	    }

		return $ret;

	}
}

?>