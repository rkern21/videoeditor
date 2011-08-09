<?php
/**
* @author Maritn Mueller
* @email yosha@yoflash.com
* @version $Id: com_mochigames.php
* @package Xmap
* @license GNU/GPL
* @description Xmap plugin for Mochigames component
*/

defined( '_JEXEC' ) or die( 'Restricted access.' );

class xmap_com_yoflash {

	/*
	* This function is called before a menu item is printed. We use it to set the
	* proper uniqueid for the item and indicate whether the node is expandible or not
	*/
	function prepareMenuItem(&$node) {
		$link_query = parse_url( $node->link );
		parse_str( html_entity_decode($link_query['query']), $link_vars);
		$view = JArrayHelper::getValue($link_vars,'view','');
		if ( $view == 'game') {
			$id = intval(JArrayHelper::getValue($link_vars,'id',0));
			if ( $id ) {
				$node->uid = 'com_yoflashgamesi'.$id;
				$node->expandible = false;
			}
		}else{
			$catid = intval(JArrayHelper::getValue($link_vars,'category',0));
			$node->uid = 'com_yoflashgamesc'.$catid;
			$node->expandible = true;
		}

	}

	function getTree( &$xmap, &$parent, &$params) {

		$link_query = parse_url( $parent->link );
		parse_str( html_entity_decode($link_query['query']), $link_vars );
		$view = JArrayHelper::getValue($link_vars,'view',0);

		$menu =& JSite::getMenu();
		$menuparams = $menu->getParams($parent->id);

		$catid = 0;
		if ( $view == 'category' ) {
			$catid = intval(JArrayHelper::getValue($link_vars,'cateory',0));
		}


		$include_mochigames = JArrayHelper::getValue( $params, 'include_yoflash',1,'' );
		$include_mochigames = ( $include_mochigames == 1
				  || ( $include_mochigames == 2 && $xmap->view == 'xml')
				  || ( $include_mochigames == 3 && $xmap->view == 'html')
				  ||   $xmap->view == 'navigator');
		$params['include_yoflash'] = $include_mochigames;


		$priority = JArrayHelper::getValue($params,'cat_priority',$parent->priority,'');
		$changefreq = JArrayHelper::getValue($params,'cat_changefreq',$parent->changefreq,'');
		if ($priority  == '-1')
			$priority = $parent->priority;
		if ($changefreq  == '-1')
			$changefreq = $parent->changefreq;

		$params['cat_priority'] = $priority;
		$params['cat_changefreq'] = $changefreq;

		$priority = JArrayHelper::getValue($params,'games_priority',$parent->priority,'');
		$changefreq = JArrayHelper::getValue($params,'games_changefreq',$parent->changefreq,'');
		if ($priority  == '-1')
			$priority = $parent->priority;

		if ($changefreq  == '-1')
			$changefreq = $parent->changefreq;

		$params['games_priority'] = $priority;
		$params['games_changefreq'] = $changefreq;

		$params['limit'] = '';
		$limit = JArrayHelper::getValue($params,'max_games','','');

		if ( intval($limit) && $xmap->view != 'navigator' ) {
			$params['limit'] = ' LIMIT '.$limit;
		}

		xmap_com_yoflash::getCategoryTree($xmap, $parent, $params, $catid );

	}

	function getCategoryTree ( &$xmap, &$parent, &$params, $catid) {
		$db = &JFactory::getDBO();

		$query = ' SELECT a.id,a.name, a.slug'.
		         ' FROM #__yfl_game a, #__yfl_game2cat c '.
		         ' WHERE a.id = c.gid AND c.cid='.$catid.' ' .
		         ' ORDER BY a.name ASC '.
		         $params['limit'];

		if($catid==-1)
            $query = ' SELECT a.id,a.name, a.slug'.
                     ' FROM #__yfl_game a '.
                     ' ORDER BY a.name ASC '.
                     $params['limit'];

		$db->setQuery($query);


		$games = $db->loadObjectList();

		$xmap->changeLevel(1);
		if(   $params['include_yoflash'] ) {
            foreach($games as $game) {
                $node = new stdclass;
                $node->id   = $parent->id;
                $node->uid  = $parent->uid .'i'.$game->id;
                $node->name = $game->name;
                $node->link = 'index.php?option=com_yoflashs&amp;view=game&amp;id='.$game->slug.'&amp;Itemid='.$parent->id;
                $node->priority   = $params['games_priority'];
                $node->changefreq = $params['games_changefreq'];
                $node->expandible = false;
                //$node->tree = array();
                $xmap->printNode($node);
            }
		}
       $xmap->changeLevel(-1);
	}

}