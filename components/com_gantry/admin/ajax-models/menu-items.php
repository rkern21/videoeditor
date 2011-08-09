<?php
/**
 * @package gantry
 * @subpackage admin.ajax-models
 * @version        3.1.10 March 5, 2011
 * @author        RocketTheme http://www.rockettheme.com
 * @copyright     Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

global $gantry;

$action = JRequest::getString('action');
gantry_import('core.gantryjson');


if ($action == 'pull') {
    $id = JRequest::getString('menuitem');
    if (null == $id){
        return "error: missing menuitem";
    }

    $gantry->currentMenuItem = $id;
    $gantry->repopulateParams();

    $out = new stdClass();
    $params = $gantry->readMenuItemParams($id, true);

    // get the menu item override param items
    $out->params = array($id=>$params);

    //get the count of modules for all the positions based on a menu item
    $module_counts = array();

    foreach ($gantry->_working_params as $param){
        if ($param['type']=='positions'){
            $posName = ($param['name'] == "mainbodyPosition") ? "sidebar" : str_replace("Position", "", $param['name']);
            $realCount = $gantry->countModules($posName);
            if ($posName == 'sidebar') $realCount += 1;
            $module_counts[$posName]=$realCount;
        }
    }
    $out->module_counts=$module_counts;

    //$app = &JApplication::getInstance('site', array(), 'J');
    //$menus = $app->getMenu();
    //$menu = $menus->getItem($id);
    $menus = load_menu_items_menu();
    $menu = $menus[$id];
    $out->tree = array();
    foreach ($menu->tree as $treeid){
        if ($treeid == $id){
            break;
        }
        $out->tree[$treeid] = $gantry->readMenuItemParams($treeid, true);    
    }
    
    $outdata = GantryJSON::encode($out);
    $outdata = str_replace('\\\\\\' , '\\', $outdata);
	echo $outdata;
}
elseif ($action == 'push') {
    $data = JRequest::getString('menuitems-data');
    $data = GantryJSON::decode($data, false);
	
	foreach ($data as $menuitem => $content){
		$gantry->writeMenuItemParams($menuitem, $content);
	}
}
elseif ($action == 'erase') {
    $id = JRequest::getString('menuitem');
    if (null == $id){
        return "error: missing menuitem";
    }
    $gantry->writeMenuItemParams($id, array());
}
else {
    return "error";
}


function load_menu_items_menu() {

    $cache = &JFactory::getCache('_system', 'output');

    if (!$data = $cache->get('all_menu_items')) {
        // Initialize some variables
        $db = & JFactory::getDBO();

        $sql = 'SELECT m.*, c.`option` as component' .
                ' FROM #__menu AS m' .
                ' LEFT JOIN #__components AS c ON m.componentid = c.id' .
                ' ORDER BY m.sublevel, m.parent, m.ordering';
        $db->setQuery($sql);

        if (!($menus = $db->loadObjectList('id'))) {
            return false;
        }

        foreach ($menus as $key => $menu)
        {
            //Get parent information
            $parent_route = '';
            $parent_tree = array();
            if (($parent = $menus[$key]->parent) && (isset($menus[$parent])) &&
                    (is_object($menus[$parent])) && (isset($menus[$parent]->route)) && isset($menus[$parent]->tree)) {
                $parent_route = $menus[$parent]->route . '/';
                $parent_tree = $menus[$parent]->tree;
            }

            //Create tree
            array_push($parent_tree, $menus[$key]->id);
            $menus[$key]->tree = $parent_tree;

            //Create route
            $route = $parent_route . $menus[$key]->alias;
            $menus[$key]->route = $route;

            //Create the query array
            $url = str_replace('index.php?', '', $menus[$key]->link);
            if (strpos($url, '&amp;') !== false) {
                $url = str_replace('&amp;', '&', $url);
            }

            parse_str($url, $menus[$key]->query);
        }

        $cache->store(serialize($menus), 'menu_items');
        return $menus;
    } else {
        return unserialize($data);
    }
}