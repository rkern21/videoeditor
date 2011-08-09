<?php

/**
 * @author Guillermo Vargas, http://joomla.vargas.co.cr
 * @email guille@vargas.co.cr
 * @version $Id: com_rdautos.php 134 2010-10-24 21:17:45Z guilleva $
 * @package Xmap
 * @license GNU/GPL
 * @description Xmap plugin for DOCman component
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class xmap_com_rdautos
{
    /*
     * This function is called before a menu item is printed. We use it to set the
     * proper uniqueid for the item
     */

    function prepareMenuItem(&$node, &$params)
    {
        $link_query = parse_url($node->link);
        parse_str(html_entity_decode($link_query['query']), $link_vars);
        $view = JArrayHelper::getValue($link_vars, 'view', '');
        $id = intval(JArrayHelper::getValue($link_vars, 'id', 0));

        switch ($view) {
            case 'detail':
                $node->uid = 'com_rdautosv' . $id;
                $node->false = true;
                break;
            case 'category':
                $node->uid = 'com_rdautosc' . $id;
                $node->expandible = true;
                break;
        }
    }

    function &getTree(&$xmap, &$parent, &$params)
    {
        $db = & JFactory::getDBO();

        // get the parameters
        $menu = JSite::getMenu();
        $queryparams = $menu->getParams($parent->id);

        $link_query = parse_url($parent->link);
        parse_str(html_entity_decode($link_query['query']), $link_vars);
        $view = JArrayHelper::getValue($link_vars, 'view', null);

        $catid=null;
        if ($view == 'category') {
            $catid = intval($queryparams->get('id', NULL));
            if (!$catid) {
                $catid = JArrayHelper::getValue($link_vars, 'id', 0);
            }
        }

        if (!in_array($view,array('category','categories'))) {
            return;
        }

        $include_vehicles = JArrayHelper::getValue($params, 'include_vehicles', 1);
        $include_vehicles = ( $include_vehicles == 1
                || ( $include_vehicles == 2 && $xmap->view == 'xml')
                || ( $include_vehicles == 3 && $xmap->view == 'html'));
        $params['include_vehicles'] = $include_vehicles;

        $priority = JArrayHelper::getValue($params, 'cat_priority', $parent->priority);
        $changefreq = JArrayHelper::getValue($params, 'cat_changefreq', $parent->changefreq);
        if ($priority == '-1')
            $priority = $parent->priority;
        if ($changefreq == '-1')
            $changefreq = $parent->changefreq;

        $params['cat_priority'] = $priority;
        $params['cat_changefreq'] = $changefreq;

        $priority = JArrayHelper::getValue($params, 'vehicle_priority', $parent->priority);
        $changefreq = JArrayHelper::getValue($params, 'vehicle_changefreq', $parent->changefreq);
        if ($priority == '-1')
            $priority = $parent->priority;
        if ($changefreq == '-1')
            $changefreq = $parent->changefreq;

        $params['vehicle_priority'] = $priority;
        $params['vehicle_changefreq'] = $changefreq;

        if (!$catid) {
            xmap_com_rdautos::getCategoryTree($xmap, $parent, $params);
        } else {
            xmap_com_rdautos::expandCategory($xmap, $parent, $params, $catid);
        }

    }

    function getCategoryTree(&$xmap, &$parent, &$params)
    {
        $db = & JFactory::getDBO();
        $include_vehicles = @$params['include_vehicles'];

        $query = 'select catid,catname,alias from #__rdautos_categories where published=1 order by catname ASC';
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        // Get sub-categories list
        $xmap->changeLevel(1);
        foreach ($rows as $row) {
            $row->slug = $row->catid.':'.$row->alias;
            $node = new stdclass;
            $node->id = $parent->id;
            $node->uid = 'com_rdautosc' . $row->catid; // should be uniq on component
            $node->name = $row->catname;
            $node->browserNav = $parent->browserNav;
            $node->priority = $params['cat_priority'];
            $node->changefreq = $params['cat_changefreq'];
            $node->expandible = true;
            $node->link = 'index.php?option=com_rdautos&view=category&id=' . $row->slug.'&Itemid='.$parent->id;
            if (($xmap->printNode($node) !== FALSE) && $include_vehicles) {
                xmap_com_rdautos::expandCategory($xmap, $parent,$params,$row->catid);
            }
        }
        $xmap->changeLevel(-1);
        return true;
    }

    function expandCategory(&$xmap, &$parent,&$params, $catid)
    {
        $db = & JFactory::getDBO();
        $xmap->changeLevel(1);
        // Get vehicles list
        $query = 'SELECT a.carid,m.makename,o.model,a.modeltype,
                  UNIX_TIMESTAMP(a.added) added,
                  UNIX_TIMESTAMP(a.updated) updated
                  FROM `#__rdautos_information` AS a
                  LEFT JOIN `#__rdautos_makes` AS m ON a.makeid=m.makeid
                  LEFT JOIN `#__rdautos_models` AS o ON a.modelid=o.modelid
                  WHERE a.catid='.$catid.' AND
                        a.published=1
                ';
        $db->setQuery($query);
        $cars = $db->loadObjectList();
        foreach ($cars as $car) {
            $car->slug = $car->carid.':'.JFilterOutput::stringURLSafe($car->makename).':'.JFilterOutput::stringURLSafe($car->model). ':'.JFilterOutput::stringURLSafe($car->modeltype);
            $node = new stdclass;
            $node->id = $parent->id;
            $node->uid = 'com_rdautosv' . $car->carid; // should be uniq on component
            $node->link = 'index.php?option=com_rdautos&view=detail&id=' .  $car->slug . '&Itemid=' . $parent->id;
            $node->browserNav = $parent->browserNav;
            $node->priority = $params['vehicle_priority'];
            $node->changefreq = $params['vehicle_changefreq'];
            $node->name = $car->makename.' '.$car->model. ' '.$car->modeltype;
            $node->expandible = false;
            $xmap->printNode($node);
        }
        $xmap->changeLevel(-1);
    }

}