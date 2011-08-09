<?php
/**
* @author Guillermo Vargas, http://joomla.vargas.co.cr
* @email guille@vargas.co.cr
* @version $Id: com_lknanswers.php 120 2010-06-26 11:51:39Z guilleva $
* @package Xmap
* @license GNU/GPL
* @description Xmap plugin for lknanswers component
*/

defined( '_JEXEC' ) or die( 'Restricted access.' );

class xmap_com_lknanswers {

	/*
	* This function is called before a menu item is printed. We use it to set the
	* proper uniqueid for the item
	*/
	function prepareMenuItem(&$node,&$params) {
		$link_query = parse_url( $node->link );
		parse_str( html_entity_decode($link_query['query']), $link_vars);
		$id = intval(JArrayHelper::getValue($link_vars,'id',0));
		$task = JArrayHelper::getValue( $link_vars, 'task', '', '' );
		if ( $task == 'detail_category' && $id ) {
			$node->uid = 'com_lknanswersc'.$cid;
			$node->expandible = true;
		} elseif ($task == 'question' && $id) {
			$node->uid = 'com_lknanswersq'.$id;
			$node->expandible = false;
		}
	}

	function getTree( &$xmap, &$parent, &$params)
	{
		$link_query = parse_url( $parent->link );
        parse_str( html_entity_decode($link_query['query']), $link_vars );
        $task = JArrayHelper::getValue($link_vars,'task','');
        
		if ($task && $task != 'detail_category') {
			return $list;
		} elseif ($task == 'detail_category') {
            $catid = intval(JArrayHelper::getValue($link_vars,'id',0));
        } else {
            $catid=0;
        }

		$include_questions = JArrayHelper::getValue( $params, 'include_questions',1,'' );
		$include_questions = ( $include_questions == 1
                                  || ( $include_questions == 2 && $xmap->view == 'xml')
                                  || ( $include_questions == 3 && $xmap->view == 'html')
				  ||   $xmap->view == 'navigator');
		$params['include_questions'] = $include_questions;

		$priority = JArrayHelper::getValue($params,'cat_priority',$parent->priority,'');
		$changefreq = JArrayHelper::getValue($params,'cat_changefreq',$parent->changefreq,'');
		if ($priority  == '-1')
			$priority = $parent->priority;
		if ($changefreq  == '-1')
			$changefreq = $parent->changefreq;

		$params['cat_priority'] = $priority;
		$params['cat_changefreq'] = $changefreq;

		$priority = JArrayHelper::getValue($params,'question_priority',$parent->priority,'');
		$changefreq = JArrayHelper::getValue($params,'question_changefreq',$parent->changefreq,'');
		if ($priority  == '-1')
			$priority = $parent->priority;

		if ($changefreq  == '-1')
			$changefreq = $parent->changefreq;

		$params['question_priority'] = $priority;
		$params['question_changefreq'] = $changefreq;

		if ( $include_questions ) {
			$params['limit'] = '';
			$params['days'] = '';
			$limit = JArrayHelper::getValue($params,'max_questions','','');

			if ( intval($limit) )
				$params['limit'] = ' LIMIT '.$limit;

			$days = JArrayHelper::getValue($params,'max_age','','');
			if ( intval($days) )
				$params['days'] = ' AND a.created >= \''.date('Y-m-d H:m:s', ($xmap->now - ($days*86400)) ) ."' ";
		}

		xmap_com_lknanswers::getCategoriesTree( $xmap, $parent, $params, $catid );
	}

	function getCategoriesTree ( &$xmap, &$parent, &$params, &$catid )
	{
		$db = JFactory::getDBO();
		$db->setQuery(
            "SELECT a.id, a.title, a.parent_id, ".
            "CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(':', a.id, a.alias) ELSE a.id END as slug ".
            "FROM `#__lknanswers_categories` AS a, ".
            "     `#__lknanswers_acl` AS b " .
            "WHERE a.published=1 AND a.id = b.cat_id AND b.group_id={$xmap->gid} AND a.parent_id=$catid ".
            "ORDER BY Title"
        );
		$cats = $db->loadObjectList();
		$xmap->changeLevel(1);

		foreach($cats as $cat) {
			$node = new stdclass;
			$node->id   = $parent->id;
			$node->uid  = $parent->uid.'c'.$cat->id;   // Uniq ID for the category
			$node->pid  = $cat->parent_id;
			$node->name = $cat->title;
			$node->priority   = $params['cat_priority'];
			$node->changefreq = $params['cat_changefreq'];
			$node->link = 'index.php?option=com_lknanswers&task=detail_category&id='.$cat->slug;
			$node->expandible = true;

			if ($xmap->printNode($node) !== FALSE ) {
				xmap_com_lknanswers::getCategoriesTree($xmap, $parent, $params, $cat->id);
			}
		}

		if ( $params['include_questions'] ) {
			$db->setQuery (
                "SELECT a.id, a.title, a.cat_id,UNIX_TIMESTAMP(a.created) AS created, UNIX_TIMESTAMP(MAX(b.created)) AS last_answered, ".
                "    CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(':', a.id, a.alias) ELSE a.id END as slug ".
                "FROM `#__lknanswers_questions` AS a LEFT JOIN `#__lknanswers_question_answers` as b ON a.id = b.question_id and b.published=1 ".
                "WHERE a.cat_id=$catid and a.published=1 ".
                $params['days'] . " " .
                "GROUP by a.id ORDER BY a.created desc" .
                $params['limit']
            );
			$questions = $db->loadObjectList();
			foreach($questions as $question) {
				$node = new stdclass;
				$node->id   = $parent->id;  // Itemid
				$node->uid  = $parent->uid .'q'.$question->id; // Uniq ID for the download
				$node->name = $question->title;
                $node->modified = $question->last_answered? $question->last_answered : $question->created;
				$node->link = 'index.php?option=com_lknanswers&task=question&id='.$question->slug;
				$node->priority   = $params['question_priority'];
				$node->changefreq = $params['question_changefreq'];
				$node->expandible = false;
				$xmap->printNode($node);
			}
		}
		$xmap->changeLevel(-1);
	}
}