<?php
/**
 * RokStories Module
 *
 * @package RocketTheme
 * @subpackage rokstories
 * @version   1.9 September 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined( '_JEXEC' ) or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
/**
 * @package RocketTheme
 * @subpackage rokstories
 */
class modRokStoriesHelper
{
	
	function loadScripts(&$module, &$params)
	{
		JHTML::_('behavior.mootools');
		
		$swidth = $params->get("start_width", 410);
		$layout = $params->get("layout_type", "layout1");
		$thumbsOpacity = $params->get("thumbs_opacity", 0.3);
		if ($swidth == 'auto' || $layout == 'layout2') $swidth = "'auto'";
		if ($layout == 'layout2') $thumbsOpacity = 1;
		
		$tlx = $params->get('left_offset_x', -40);
		$tly = $params->get('left_offset_y', -100);
		$trx = $params->get('right_offset_x', -30);
		$try = $params->get('right_offset_y', -100);
		
		if ($params->get("fixed_thumb", 1) == 0) {
			$tlx = $params->get('left_f_offset_x', -55);
			$tly = $params->get('left_f_offset_y', -105);
			$trx = $params->get('right_f_offset_x', -55);
			$try = $params->get('right_f_offset_y', -105);
		}
		
		$leftOffset = "{x: $tlx, y: $tly}";
		$rightOffset = "{x: $trx, y: $try}";
		
		
		$document =& JFactory::getDocument();
		if (!defined('ROKSTORIES')) {
			$document->addScript(JURI::Root(true)."/modules/mod_rokstories/tmpl/js/rokstories".modRokStoriesHelper::_getJSVersion().".js");
			$document->addScriptDeclaration("var RokStoriesImage = {}, RokStoriesLinks = {};");
			define('ROKSTORIES', 1);
		}
		$document->addScriptDeclaration("
		RokStoriesImage['rokstories-{$module->id}'] = [];
		RokStoriesLinks['rokstories-{$module->id}'] = [];
		window.addEvent('domready', function() {
			new RokStories('rokstories-{$module->id}', {
				'id': ".$module->id.",
				'startElement': ".$params->get("start_element", 0).",
				'thumbsOpacity': ".$thumbsOpacity.",
				'mousetype': '".$params->get("mouse_type", "click")."',
				'autorun': ".$params->get("autoplay", 0).",
				'delay': ".$params->get("autoplay_delay", 5000).",
				'scrollerDuration': ".$params->get("scrollerDuration", 5000).",
				'scrollerTransition': Fx.Transitions.".$params->get("scrollerTransition", 'Expo.easeInOut').",
				'startWidth': ".$swidth.",
				'layout': '$layout',
				'linkedImgs': ".$params->get("link_images", 0).",
				'showThumbs': ".$params->get("show_thumbs", 0).",
				'fixedThumb': ".$params->get("fixed_thumb", 1).",
				'mask': ".$params->get('show_mask', 1).",
				'descsAnim': '".$params->get('mask_desc_dir', 'topdown')."',
				'imgsAnim': '".$params->get('mask_imgs_dir', 'bottomup')."',
				'thumbLeftOffsets': $leftOffset,
				'thumbRightOffsets': $rightOffset
			});
		});");
	}
	
	function getList($params)
	{
		global $mainframe;
		
		$cparams	    =& $mainframe->getParams('com_content');

		$db			    =& JFactory::getDBO();
		$user		    =& JFactory::getUser();
		$userId		    = (int) $user->get('id');

		$count		    = $params->get('article_count',4); 
		$catid		    = trim( $params->get('catid') );
		$secid		    = trim( $params->get('secid') );
		$show_front	    = $params->get('show_front', 1);
		$aid		    = $user->get('aid', 0);
		$content_type   = $params->get('content_type','joomla');
		$ordering       = $params->get('itemsOrdering');
		$cid            = $params->get('category_id', NULL);
		$user_id        = $params->get('user_id');
		$image_size      = $params->get('itemImgSize','M');
		$thumb_size      = $params->get('thumb_width',90);

		$contentConfig  = &JComponentHelper::getParams( 'com_content' );
		$access		    = !$contentConfig->get('shownoauth');

		$nullDate	    = $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();
		$where = '';
		
		// User Filter
		switch ($user_id)
		{
			case 'by_me':
				$where .= ' AND (a.created_by = ' . (int) $userId . ' OR a.modified_by = ' . (int) $userId . ')';
				break;
			case 'not_me':
				$where .= ' AND (a.created_by <> ' . (int) $userId . ' AND a.modified_by <> ' . (int) $userId . ')';
				break;
		}
		
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
    		$query = 'SELECT a.*, ' .
    			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
    			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
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
    			' AND cc.published = 1' .
    			' ORDER BY '. $orderby;
    		// end Joomla specific
	    } else {
		    // start K2 specific
		    require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
		    
    		$query = "SELECT a.*, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams".
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
    			
    		$query .= $where . ' ORDER BY ' . $orderby;
    		// end K2 specific
		}	
		
			
		$db->setQuery($query, 0, $count);

		$rows = $db->loadObjectList();

        $i=0;
		$lists	= array();
		
		if (is_array($rows) && count($rows)>0) {
    		foreach ( $rows as $row )
    		{
    		    //process content plugins
    		    $text = JHTML::_('content.prepare',$row->introtext,$cparams);
    			$lists[$i]->id = $row->id;
    			$lists[$i]->created = $row->created;
    			$lists[$i]->modified = $row->modified;
    			$lists[$i]->title = htmlspecialchars( $row->title );
    			$lists[$i]->introtext = modRokStoriesHelper::prepareContent($text, $params);

    			if ($content_type=='joomla') {
    			    $lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
    			    $images = modRokStoriesHelper::getImages($row->introtext,$thumb_size);
    			} else {
    			    $lists[$i]->link = JRoute::_(K2HelperRoute::getItemRoute($row->id.':'.$row->alias, $row->catid.':'.$row->categoryalias));
    			    $images = modRokStoriesHelper::getK2Images($row->id,$image_size,$thumb_size);
    			}
			
    			$lists[$i]->image = $images->image;
    			$lists[$i]->thumb = $images->thumb;
				$lists[$i]->thumbSizes = $images->thumbSizes;
    			$i++;
    		}
        }
		return $lists;
	}
	function getK2Images($id, $image_size, $thumb_size=70) {	  
		
		$images = new stdClass();
		$images->image = false;
		$images->thumb = false;
		$images->thumbSizes = array('width' => $thumb_size, 'height' => 'auto');
		$current_size = intval($thumb_size);
		
		
		if (file_exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$id).'_'.$image_size.'.jpg')) {
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
				    $current_size = $existing_thumb->getCurrentWidth();
					$images->thumbSizes = $existing_thumb->currentDimensions;
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
    				$thumb->save($thumb_path);
					$images->thumbSizes = $thumb->currentDimensions;
    				$thumb->destruct();
    			}
			}
			$images->thumb = $thumb_path;
		} 
		return $images;
	}
	
	
	function getImages($text, $thumb_size=70) {	  
		
		preg_match("/\<img.+?src=\"(.+?)\".+?\/>/", $text, $matches);
		
		$images = new stdClass();
		$images->image = false;
		$images->thumb = false;
		$images->thumbSizes = array('width' => $thumb_size, 'height' => 'auto');

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
				if (strpos($image_path,$path) === 0) {
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
				    $current_size = $existing_thumb->getCurrentWidth();
					$images->thumbSizes = $existing_thumb->currentDimensions;
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
    				$thumb->save($thumb_path);
					$images->thumbSizes = $thumb->currentDimensions;
    				$thumb->destruct();
    			}
			}
			$images->thumb = $thumb_path;
		} 
		return $images;
	}
	
	function prepareContent($text, &$params) {
		$tags_option = $params->get('strip_tags', 'a,i,br');
		
		$tags = explode(",", $tags_option);
		$strip_tags = array();
		for($i = 0; $i < count($tags); $i++) {
			$strip_tags[$i] = '<'.trim($tags[$i]).'>';
		}
		$tags = implode(',', $strip_tags);
		
		// strips tags won't remove the actual jscript
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
		
		$text = preg_replace( '/{.+?}/', '', $text);
		$text = strip_tags($text, $tags);

		return $text;
	}
	
	function checkRequest() 
	{
		return (JRequest::getVar('import') == 'true');
	}
	
	function getBrowser() 
	{
		$agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : false;
		$ie_version = false;
				
		if (preg_match("/msie/", $agent) && !preg_match("/opera/", $agent)){
            $val = explode(" ",stristr($agent, "msie"));
            $ver = explode(".", $val[1]);
			$ie_version = $ver[0];
			$ie_version = preg_replace("#[^0-9,.,a-z,A-Z]#", "", $ie_version);
		}
		
		return $ie_version;
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
