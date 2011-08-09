<?php
/**
 * @version   1.1 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined( '_JEXEC' ) or die('Restricted access');
define ( 'MICRONEWS', true);

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
require_once (JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'content.php');

/**
 * @package RocketTheme
 * @subpackage rokmicronews
 */
class modRokMicroNewsHelper
{
	
	function getList(&$params)
	{
		global $mainframe;
		
		$cparams	=& $mainframe->getParams('com_content');

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$userId		= (int) $user->get('id');

		$count		= $params->get('article_count',3); 
		$catid		= trim( $params->get('catid') );
		$secid		= trim( $params->get('secid') );
		$show_front	= $params->get('show_front', 1);
		$aid		= $user->get('aid', 0);
		
		$tags 		= $params->get('strip_tags', "a,i,br");
		
		$text_length = intval($params->get( 'preview_count', 200) );

		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('shownoauth');

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;

		// User Filter
		switch ($params->get( 'user_id' ))
		{
			case 'by_me':
				$where .= ' AND (created_by = ' . (int) $userId . ' OR modified_by = ' . (int) $userId . ')';
				break;
			case 'not_me':
				$where .= ' AND (created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
				break;
		}

		// Ordering
		switch ($params->get( 'ordering' ))
		{
			case 'o_dsc':
			    $ordering       = 'a.ordering ASC';
			    break;
			case 'm_dsc':
				$ordering		= 'a.modified DESC, a.created DESC';
				break;
			case 'c_dsc':
			default:
				$ordering		= 'a.created DESC';
				break;
		}

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

		// Content Items only
		$query = 'SELECT a.*, ' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
			' FROM #__content AS a' .
			($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
			' WHERE '. $where .' AND s.id > 0' .
			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
			($catid ? $catCondition : '').
			($secid ? $secCondition : '').
			($show_front == '0' ? ' AND f.content_id IS NULL ' : '').
			' AND s.published = 1' .
			' AND cc.published = 1' .
			' ORDER BY '. $ordering;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

        $i=0;
		$lists	= array();
		foreach ( $rows as $row )
		{
		    //process content plugins
		    $text = JHTML::_('content.prepare',$row->introtext,$cparams);
			$lists[$i]->id = $row->id;
			$lists[$i]->thumb = modRokMicroNewsHelper::getThumb($row->introtext,$params->get('thumb_width',160));
			$lists[$i]->created = $row->created;
			$lists[$i]->modified = $row->modified;
			$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			$lists[$i]->title = htmlspecialchars( $row->title );
			$lists[$i]->introtext = modRokMicroNewsHelper::prepareContent( $text, $text_length, $tags);
			$i++;
		}

		return $lists;
	}
	
	function getThumb($text, $size=70, $reflections=false) {
		
		preg_match("/\<img.+?src=\"(.+?)\".+?\/>/", $text, $matches);

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
			
			// create a thumb filename
			$file_div = strrpos($image_path,'.');
			$thumb_ext = substr($image_path, $file_div);
			$thumb_prev = substr($image_path, 0, $file_div);
			$thumb_path = $thumb_prev . "_thumb" . $thumb_ext;
	
			// check to see if this file exists, if so we don't need to create it
			if (function_exists("gd_info") && !file_exists($thumb_path)) {
				// file doens't exist, so create it and save it
				include_once('thumbnail.inc.php');
				$thumb = new Thumbnail($image_path);
				
				if ($thumb->error) { 
					if (MICRONEWS)	echo "ROKMININEWS ERROR: " . $thumb->errmsg . ": " . $image_path; 
					return false;
				}
				$thumb->resize($size);
				if ($reflections) {
					$thumb->createReflection(30,30,60,false);
				}
				if (!is_writable(dirname($thumb_path))) {
					$thumb->destruct();
					return false;
				}
				$thumb->save($thumb_path);
				$thumb->destruct();
			}
			return ($thumb_path);
		} else {
			return false;
		}
	}
	
	function prepareContent( $text, $length=200 , $tags = "a,i,br") {
		// strips tags won't remove the actual jscript
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
		$text = preg_replace( '/{.+?}/', '', $text);
		
		$tags = preg_replace("/\s/ism", "", $tags);
		$tags = "<".implode(">,<", explode(",", $tags)) . ">";
		
		$text = strip_tags($text,$tags);
		// cut off text at word boundary if required
		if (strlen($text) > $length) {
			$text = substr($text, 0, strpos($text, ' ', $length)) . "..." ;
		} 
		return $text;
	}
	
	function processPlugins($article) {

    		global $mainframe;

    		$dispatcher	=& JDispatcher::getInstance();
    		$limitstart = 0;
    		$params		=& $mainframe->getParams('com_content');

    		JPluginHelper::importPlugin('content');
    		$results = $dispatcher->trigger('onPrepareContent', array (& $article, & $params, $limitstart));

    		return $results;

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
