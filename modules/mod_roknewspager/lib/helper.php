<?php
/**
 * RokNewsPager Module
 *
 * @package rockettheme
 * @subpackage roknewspager.lib
 * @version   1.7 August 26, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPath::clean(JPATH_SITE.'/components/com_content/helpers/route.php'));
require_once (JPath::clean(JPATH_SITE.'/libraries/joomla/html/html/content.php'));

/**
 * @package RocketTheme
 * @subpackage roknewspager.lib
 */
class modRokNewsPagerHelper
{
	
	function loadScripts()
	{
		JHTML::_('behavior.mootools');
		
		if (!defined('ROKNEWSPAGER_JS')) {
			$doc =& JFactory::getDocument();
			$doc->addScript(JURI::Root(true).'/modules/mod_roknewspager/tmpl/js/roknewspager'.modRokNewsPagerHelper::_getJSVersion().'.js');
		}
	}

    function getRowCount(&$params)
    {
		global $mainframe;
		
		$cparams	=& $mainframe->getParams('com_content');

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');
		
		$catid		    = trim( $params->get('catid') );
		$secid		    = trim( $params->get('secid') );
		$show_front	    = $params->get('show_front', 1);
		$aid		    = $user->get('aid', 0);
		$content_type   = $params->get('content_type','joomla');
		$cid            = $params->get('category_id', NULL);


		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('show_noauth');
		
		$offset     = JRequest::getInt('offset',0);

		$nullDate	= $db->getNullDate();
		
		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		$where = '';
		
		// ensure should be published
		$where .= " AND ( a.publish_up = ".$db->Quote($nullDate)." OR a.publish_up <= ".$db->Quote($now)." )";
		$where .= " AND ( a.publish_down = ".$db->Quote($nullDate)." OR a.publish_down >= ".$db->Quote($now)." )";
		
		// content specific stuff
        if ($content_type=='joomla') {
            // start Joomla specific
            
            $catCondition = '';
            $secCondition = '';

            if ($show_front != 2) {
        		if ($catid)
        		{
        			$ids = explode( ',', $catid );
        			JArrayHelper::toInteger( $ids );
        			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
        		}
        		if ($secid)
        		{
        			$ids = explode( ',', $secid );
        			JArrayHelper::toInteger( $ids );
        			$secCondition = ' AND (s.id=' . implode( ' OR s.id=', $ids ) . ')';
        		}
        	}
		
    		// Content Items only
    		$query = 'SELECT count(a.id) ' .
    			' FROM #__content AS a' .
    			($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
    			($show_front == '2' ? ' INNER JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
    			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
    			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
    			' WHERE a.state = 1'. $where .' AND s.id > 0' .
    			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
    			($catid && $show_front != 2 ? $catCondition : '').
    			($secid && $show_front != 2 ? $secCondition : '').
    			($show_front == '0' ? ' AND f.content_id IS NULL ' : '').
    			' AND s.published = 1' .
    			' AND cc.published = 1';
    		// end Joomla specific
	    } else {
		    // start K2 specific
		    require_once (JPath::clean( JPATH_SITE.'/components/com_k2/helpers/route.php'));
		    
    		$query = "SELECT count(a.id)".
    		" FROM #__k2_items as a".
    		" LEFT JOIN #__k2_categories c ON c.id = a.catid";
	
    		$query .= " WHERE a.published = 1"
    		." AND a.access <= {$aid}"
    		." AND a.trash = 0"
    		." AND c.published = 1"
    		." AND c.access <= {$aid}"
    		." AND c.trash = 0"
    		;
	
   		    if ($params->get('catfilter')){
    			if (!is_null($cid)) {
    				if (is_array($cid)) {
    					$query .= " AND a.catid IN(".implode(',', $cid).")";
    				}
    				else {
    					$query .= " AND a.catid={$cid}";
    				}
    			}
    		}

    		if ($params->get('FeaturedItems')=='0')
    			$query.= " AND a.featured != 1";
	
    		if ($params->get('FeaturedItems')=='2')
    			$query.= " AND a.featured = 1";
    			
    		$query .= $where;
    		// end K2 specific
		}
		$db->setQuery($query);
		$rowcount = $db->loadresult();
		
		return $rowcount;
    }
    
	function getList(&$params)
	{
		global $mainframe;
		
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');
		
		$count		    = $params->get('article_count',4); 
		$catid		    = trim( $params->get('catid') );
		$secid		    = trim( $params->get('secid') );
		$show_front	    = $params->get('show_front', 1);
		$aid		    = $user->get('aid', 0);
		$content_type   = $params->get('content_type','joomla');
		$ordering       = $params->get('itemsOrdering');
		$cid            = $params->get('category_id', NULL);
        $text_length    = intval($params->get( 'preview_count', 200) );
        $show_comment_count = $params->get('show_comment_count',0);

		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('show_noauth');
		
		$offset     = JRequest::getInt('offset',0);

		$nullDate	= $db->getNullDate();
		
		$date =& JFactory::getDate();
		$now = $date->toMySQL();
		
        $thumb_size      = $params->get('thumb_width',90);
        $image_size      = $params->get('itemImgSize','M');



		$where = '';
		
		// ensure should be published
		$where .= " AND ( a.publish_up = ".$db->Quote($nullDate)." OR a.publish_up <= ".$db->Quote($now)." )";
		$where .= " AND ( a.publish_down = ".$db->Quote($nullDate)." OR a.publish_down >= ".$db->Quote($now)." )";
		
	    // ordering
		switch ($ordering) {
			case 'date' :
				$orderby = 'a.created ASC';
				break;
			case 'rdate' :
				$orderby = 'a.created DESC';
				break;
			case 'alpha' :
				$orderby = 'a.title';
				break;
			case 'ralpha' :
				$orderby = 'a.title DESC';
				break;
			case 'order' :
				$orderby = 'a.ordering';
				break;
			default :
				$orderby = 'a.id DESC';
				break;
		}

		// content specific stuff
        if ($content_type=='joomla') {
            // start Joomla specific
            
            $catCondition = '';
            $secCondition = '';

            if ($show_front != 2) {
        		if ($catid)
        		{
        			$ids = explode( ',', $catid );
        			JArrayHelper::toInteger( $ids );
        			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
        		}
        		if ($secid)
        		{
        			$ids = explode( ',', $secid );
        			JArrayHelper::toInteger( $ids );
        			$secCondition = ' AND (s.id=' . implode( ' OR s.id=', $ids ) . ')';
        		}
        	}	
    		// Content Items only
    		$query = 'SELECT a.*, cr.rating_sum/cr.rating_count as rating,' .
    			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
    			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
    			' FROM #__content AS a' .
    			($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
    			($show_front == '2' ? ' INNER JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
    			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
    			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
                ' LEFT JOIN #__content_rating as cr ON a.id = cr.content_id' .
    			' WHERE a.state = 1'. $where .' AND s.id > 0' .
    			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
    			($catid && $show_front != 2 ? $catCondition : '').
    			($secid && $show_front != 2 ? $secCondition : '').
    			($show_front == '0' ? ' AND f.content_id IS NULL ' : '').
    			' AND s.published = 1' .
    			' AND cc.published = 1' .
    			' ORDER BY '. $orderby;
    		// end Joomla specific
	    } else {
		    // start K2 specific
		    require_once (JPath::clean(JPATH_SITE.'/components/com_k2/helpers/route.php'));
		    
    		$query = "SELECT a.*, cr.rating_sum/cr.rating_count as rating, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams, cc.commentcount as commentcount".
    		" FROM #__k2_items as a".
    		" LEFT JOIN #__k2_categories c ON c.id = a.catid" .
            " LEFT JOIN #__k2_rating as cr ON a.id = cr.itemid".
            " LEFT JOIN (select cm.itemid  as id, count(cm.id) as commentcount from #__k2_comments as cm where cm.published=1 group by cm.itemid) as cc on a.id = cc.id";
				
    		$query .= " WHERE a.published = 1"
    		." AND a.access <= {$aid}"
    		." AND a.trash = 0"
    		." AND c.published = 1"
    		." AND c.access <= {$aid}"
    		." AND c.trash = 0"
    		;
	
   		    if ($params->get('catfilter')){
    			if (!is_null($cid)) {
    				if (is_array($cid)) {
    					$query .= " AND a.catid IN(".implode(',', $cid).")";
    				}
    				else {
    					$query .= " AND a.catid={$cid}";
    				}
    			}
    		}

    		if ($params->get('FeaturedItems')=='0')
    			$query.= " AND a.featured != 1";
	
    		if ($params->get('FeaturedItems')=='2')
    			$query.= " AND a.featured = 1";
    			
    		$query .= $where . ' ORDER BY ' . $orderby;
    		// end K2 specific
		}

		$db->setQuery($query, $offset, $count);
		$rows = $db->loadObjectList();

		$i		= 0;
		$lists	= array();
		foreach ( $rows as $row )
		{
            //process content plugins
		    $text = JHTML::_('content.prepare',$row->introtext,$contentConfig);

            $lists[$i]->comment_count = null;


			if ($content_type=='joomla') {
			    $lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
                $images = modRokNewsPagerHelper::getImages($row->introtext,$thumb_size);
                $lists[$i]->comment_count = modRokNewsPagerHelper::getCommentCount($row);

			} else {
			    $lists[$i]->link = JRoute::_(K2HelperRoute::getItemRoute($row->id.':'.$row->alias, $row->catid.':'.$row->categoryalias));
                $images = modRokNewsPagerHelper::getK2Images($row->id,$image_size,$thumb_size);
				// commentcount can be null, cast to int needed to make sure it's a numeric value
                $lists[$i]->comment_count = (int) $row->commentcount;
			}
			$lists[$i]->title = htmlspecialchars( $row->title );
			$lists[$i]->introtext = modRokNewsPagerHelper::prepareContent( $text, $text_length, $params);

            $lists[$i]->rating = (is_numeric($row->rating))?floatval($row->rating / 5 * 100):null;
            $lists[$i]->image = $images->image;
    		$lists[$i]->thumb = $images->thumb;

            // set the author
            $author		=& JFactory::getUser($row->created_by);
            $lists[$i]->author = $author->name;

            //set the pub date
            jimport('joomla.utilities.date');
            $created_date = new JDate($row->created);
            $publish_date = new JDate($row->publish_up);
            $lists[$i]->published_date = ($created_date->toUnix() > $publish_date->toUnix())?$row->created:$row->publish_up;

			if (isset($images->size)) $lists[$i]->thumb_size = $images->size;
			$i++;
		}

		return $lists;
	}
	
	function prepareContent($text,  $length=200, &$params) {
		$tags_option = $params->get('strip_tags', 'a,br');
		
		$tags = explode(",", $tags_option);
		$strip_tags = array();
		for($i = 0; $i < count($tags); $i++) {
			$strip_tags[$i] = '<'.trim($tags[$i]).'>';
		}
		$tags = implode(',', $strip_tags);
		
		// strips tags won't remove the actual jscript
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
		$text = preg_replace( '/{.+?}/', '', $text);
		//$text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );
		$text = strip_tags($text, $tags);

		if (strlen($text) > $length) {
			$text = substr($text, 0, strpos($text, ' ', $length)) . "..." ;
		} 
		
		return $text;
	}

	function getBrowser() 
	{
		$agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : false;
		$ie_version = false;
				
		if (preg_match("/msie/", $agent) && !preg_match("/opera/", $agent)){
            $val = explode(" ",stristr($agent, "msie"));
            $ver = explode(".", $val[1]);
			$ie_version = $ver[0];
			$ie_version = preg_replace("/[^0-9,.,a-z,A-Z]/", "", $ie_version);
		}
		
		return $ie_version;
	}


    function getK2Images($id, $image_size, $thumb_size=70) {

		$images = new stdClass();
		$images->image = false;
		$images->thumb = false;
		$current_size = intval($thumb_size);


		if (file_exists(JPath::clean(JPATH_SITE.'/media/k2/items/cache/'.md5("Image".$id).'_'.$image_size.'.jpg'))) {
		    $image_path = 'media/k2/items/cache/'.md5("Image".$id).'_'.$image_size.'.jpg';
		    $images->image = JURI::Root(true).'/'.$image_path;

			// create a thumb filename
			$file_div = strrpos($image_path,'.');
			$thumb_ext = substr($image_path, $file_div);
			$thumb_prev = substr($image_path, 0, $file_div);
			$thumb_path = $thumb_prev . "_thumb" . $thumb_ext;

			// check to see if this file exists, if so we don't need to create it
			if (function_exists("gd_info")) {
				// file doens't exist, so create it and save it
				if (!class_exists("Thumbnail")) include_once('thumbnail.inc.php');

				if (file_exists($thumb_path)) {
				    $existing_thumb = new Thumbnail($thumb_path);
				 	$images->size = $existing_thumb->currentDimensions;
				    $current_size = $existing_thumb->getCurrentWidth();
				}

                if (!file_exists($thumb_path) || $current_size!=$thumb_size) {

				    $thumb = new Thumbnail($image_path);
    				if ($thumb->error) {
    					echo "ROKSTORIES ERROR: " . $thumb->errmsg . ": " . $image_path;
    					return false;
    				}
    				$thumb->resize($thumb_size);
    				if (!is_writable(dirname($thumb_path))) {
    					$thumb->destruct();
    					return false;
    				}
					$images->size = $thumb->currentDimensions;
    				$thumb->save($thumb_path);
    				$thumb->destruct();
    			}
			}
			$images->thumb = $thumb_path;
		}
		return $images;
	}

    function getCommentCount(&$item){
        $jcommment_enabled = false;
        $comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
        if (file_exists($comments) ) {
            require_once($comments);
            $jcommment_enabled = true;
        }
        
        if ($jcommment_enabled && JCommentsContentPluginHelper::checkCategory($item->catid) && (JCommentsContentPluginHelper::isEnabled($item, false) || !JCommentsContentPluginHelper::isDisabled($item, false))){
            $count = JComments::getCommentsCount($item->id, 'com_content');
            if ($count == null) $count = 0;
            return $count;
        }
        return false;
    }

	function getImages($text, $thumb_size=70) {

        $matches = array();

		preg_match("/\<img.+?src=\"(.+?)\".+?\/>/", $text, $matches);

		$images = new stdClass();
		$images->image = false;
		$images->thumb = false;

		$paths = array();

		if (isset($matches[1])) {

			$image_path = $matches[1];

			//joomla 1.5 only
			$full_url = JURI::base();

			//remove any protocol/site info from the image path
			$parsed_url = parse_url($full_url);

			$paths[] = $full_url;
			if (isset($parsed_url['path']) && $parsed_url['path'] != "/") $paths[] = $parsed_url['path'];


			foreach ($paths as $path) {
				if (strpos($image_path,$path) !== false) {
					$image_path = substr($image_path,strpos($image_path, $path)+strlen($path));
				}
			}

			// remove any / that begins the path
			if (substr($image_path, 0 , 1) == '/') $image_path = substr($image_path, 1);

			//if after removing the uri, still has protocol then the image
			//is remote and we don't support thumbs for external images
			if (strpos($image_path,'http://') !== false ||
				strpos($image_path,'https://') !== false) {
				return false;
			}

			$images->image = JURI::Root(True)."/".$image_path;

			// create a thumb filename
			$file_div = strrpos($image_path,'.');
			$thumb_ext = substr($image_path, $file_div);
			$thumb_prev = substr($image_path, 0, $file_div);
			$thumb_path = $thumb_prev . "_thumb" . $thumb_ext;

			// check to see if this file exists, if so we don't need to create it
			if (function_exists("gd_info")) {
				// file doens't exist, so create it and save it
				if (!class_exists("Thumbnail")) include_once('thumbnail.inc.php');

				if (file_exists($thumb_path)) {
				    $existing_thumb = new Thumbnail($thumb_path);
				 	$images->size = $existing_thumb->currentDimensions;
				    $current_size = $existing_thumb->getCurrentWidth();
				}

                if (!file_exists($thumb_path) || $current_size!=$thumb_size) {

				    $thumb = new Thumbnail($image_path);

    				if ($thumb->error) {
    					echo "ROKNEWSPAGER ERROR: " . $thumb->errmsg . ": " . $image_path;
    					return false;
    				}
    				$thumb->resize($thumb_size);
    				if (!is_writable(dirname($thumb_path))) {
    					$thumb->destruct();
    					return false;
    				}
					$images->size = $thumb->currentDimensions;
    				$thumb->save($thumb_path);
    				$thumb->destruct();
    			}
			}
			$images->thumb = $thumb_path;
		}
		return $images;
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
