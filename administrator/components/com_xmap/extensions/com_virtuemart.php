<?php

/**
 * @author Guillermo Vargas, http://joomla.vargas.co.cr
 * @email guille@vargas.co.cr
 * @version $Id: com_virtuemart.php 154 2011-04-09 23:44:12Z guilleva $
 * @package Xmap
 * @license GNU/GPL
 * @description Xmap plugin for Virtuemart component
 */
defined('_VALID_MOS') or defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/** Adds support for Virtuemart categories to Xmap */
class xmap_com_virtuemart
{
    /*
     * This function is called before a menu item is printed. We use it to set the
     * proper uniqueid for the item and indicate whether the node is expandible or not
     */

    function prepareMenuItem(&$node,&$params)
    {
        $link_query = parse_url($node->link);
        parse_str(html_entity_decode($link_query['query']), $link_vars);
        $catid = xmap_com_virtuemart::getParam($link_vars, 'category_id', 0);
        $prodid = xmap_com_virtuemart::getParam($link_vars, 'product_id', 0);
        if (!$catid) {
            $menu = & JSite::getMenu();
            $params = $menu->getParams($node->id);
            $catid = $params->get('category_id', 0);
        }
        if (!$prodid) {
            $menu = & JSite::getMenu();
            $params = $menu->getParams($node->id);
            $prodid = $params->get('product_id', 0);
        }
        if ($prodid && $catid) {
            $node->uid = 'com_virtuemartc' . $catid . 'p' . $prodid;
            $node->expandible = false;
        } elseif ($catid) {
            $node->uid = 'com_virtuemartc' . $catid;
            $node->expandible = true;
        }
    }

    /** Get the content tree for this kind of content */
    function getTree(&$xmap, &$parent, &$params)
    {
        $menu = & JSite::getMenu();
        $vmparams = $menu->getParams($parent->id);

        $link_query = parse_url($parent->link);
        parse_str(html_entity_decode($link_query['query']), $link_vars);
        $catid = intval(xmap_com_virtuemart::getParam($link_vars, 'category_id', 0));
        $prodid = intval(xmap_com_virtuemart::getParam($link_vars, 'product_id', 0));
        $params['Itemid'] = intval(xmap_com_virtuemart::getParam($link_vars, 'Itemid', $parent->id));

        $page = xmap_com_virtuemart::getParam($link_vars, 'page', '');


        if (!$catid) {
            $catid = intval($vmparams->get('category_id', 0));
        }
        if (!$prodid) {
            $prodid = intval($vmparams->get('product_id', 0));
        }
        if (!$page) {
            $page = $vmparams->get('page', '');
        }

        //if ($page && $page != 'shop.browse') {  // We only expand VM category items or item
        if ($page && ($page != 'shop.browse' || $catid == 0)) {  // PW - We only expand VM browse menu items with a valid category_id
            return true;
        }

        if ($prodid)
            return $tree;

        $include_products = xmap_com_virtuemart::getParam($params, 'include_products', 1);
        $include_products = ( $include_products == 1
                || ( $include_products == 2 && $xmap->view == 'xml')
                || ( $include_products == 3 && $xmap->view == 'html'));
        $params['include_products'] = $include_products;

        $params['include_product_images'] = xmap_com_virtuemart::getParam($params, 'include_product_images', 1);
        $params['product_image_license_url'] = trim(xmap_com_virtuemart::getParam($params, 'product_image_license_url', ''));

        $priority = xmap_com_virtuemart::getParam($params, 'cat_priority', $parent->priority);
        $changefreq = xmap_com_virtuemart::getParam($params, 'cat_changefreq', $parent->changefreq);
        if ($priority == '-1')
            $priority = $parent->priority;
        if ($changefreq == '-1')
            $changefreq = $parent->changefreq;

        $params['cat_priority'] = $priority;
        $params['cat_changefreq'] = $changefreq;

        $priority = xmap_com_virtuemart::getParam($params, 'prod_priority', $parent->priority);
        $changefreq = xmap_com_virtuemart::getParam($params, 'prod_changefreq', $parent->changefreq);
        if ($priority == '-1')
            $priority = $parent->priority;
        if ($changefreq == '-1')
            $changefreq = $parent->changefreq;

        $params['prod_priority'] = $priority;
        $params['prod_changefreq'] = $changefreq;

        if (file_exists(JPATH_SITE . '/components/com_virtuemart/virtuemart_parser.php')) {
            // Virtuemart is trying to create a cookie but and that is generating some warning messages
            // because the output is already started. So I added the @ for this require_once
            @require_once JPATH_SITE . '/components/com_virtuemart/virtuemart_parser.php';

            // PW - Get the value of Itemid used by VirtueMart as the default value
            global $sess;
            if (!is_object($sess)) {
                return false;
            }
            $params['Itemid'] = $sess->getShopItemid(); // PW - Use the default VirtueMart Itemid value
        } else {
            return false;
        }

        xmap_com_virtuemart::getCategoryTree($xmap, $parent, $params, $catid);
        return true;
    }

    /** Virtuemart support */
    function &getCategoryTree(&$xmap, &$parent, &$params, $catid=0)
    {
        $database = &JFactory::getDBO();
        global $sess;

        static $urlBase;
        if (!isset($urlBase)) {
            $urlBase = JURI::base();
        }

        $query =
                "SELECT a.category_id, a.category_name, a.mdate,a.category_flypage "
                . "\n FROM #__vm_category AS a, #__vm_category_xref AS b "
                . "\n WHERE a.category_publish='Y' "
                . "\n AND b.category_parent_id = $catid "
                . "\n AND a.category_id=b.category_child_id "
                . "\n ORDER BY a.list_order ASC, a.category_name ASC";

        $database->setQuery($query);

        $rows = $database->loadObjectList();

        $xmap->changeLevel(1);
        foreach ($rows as $row) {
            $node = new stdclass;

            $node->id = $params['Itemid'];
            $node->uid = $parent->uid . 'c' . $row->category_id;
            $node->browserNav = $parent->browserNav;
            $node->name = stripslashes($row->category_name);
            $node->modified = intval($row->mdate);
            $node->priority = $params['cat_priority'];
            $node->changefreq = $params['cat_changefreq'];
            $node->expandible = true;
            //$node->link = $parent->link.'&amp;page=shop.browse&amp;category_id='.$row->category_id.'&amp;Itemid='.$params['Itemid'];
            $node->link = $sess->url("index.php?page=shop.browse&amp;category_id=" . $row->category_id);
            if ($xmap->printNode($node) !== FALSE) {
                xmap_com_virtuemart::getCategoryTree($xmap, $parent, $params, $row->category_id);
            }
        }
        $xmap->changeLevel(-1);

        if ($params['include_products']) {
            $query =
                    "SELECT a.product_id, a.product_name,a.mdate, a.product_thumb_image, a.product_full_image, b.category_id,d.category_flypage "
                    . "\n FROM #__vm_product AS a, #__vm_product_category_xref AS b, #__vm_category d"
                    . "\n WHERE a.product_publish='Y'"
                    . "\n AND b.category_id=$catid "
                    . "\n AND a.product_parent_id=0 "
                    . "\n AND a.product_id=b.product_id "
                    . "\n AND b.category_id=d.category_id "
                    . "\n ORDER BY a.product_name";

            $database->setQuery($query);
            $rows = $database->loadObjectList();
            $xmap->changeLevel(1);
            foreach ($rows as $row) {
                $node = new stdclass;
                $node->id = $params['Itemid'];
                $node->uid = $parent->uid . 'c' . $row->category_id . 'p' . $row->product_id;
                $node->browserNav = $parent->browserNav;
                $node->priority = $params['prod_priority'];
                $node->changefreq = $params['prod_changefreq'];
                $node->name = $row->product_name;
                $node->modified = intval($row->mdate);
                $node->expandible = false;
                $node->link = $sess->url("index.php?page=shop.product_details&amp;flypage=" . ($row->category_flypage ? $row->category_flypage : FLYPAGE) . "&amp;category_id=" . $row->category_id . '&amp;product_id=' . $row->product_id);
                //$node->link = 'index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage='.($row->category_flypage? $row->category_flypage : FLYPAGE).'&amp;category_id='.$row->category_id . '&amp;product_id=' . $row->product_id. '&amp;Itemid='.$params['Itemid'];
                if ($params['include_product_images']) {
                    // Include the standard product image
                    if (isset($row->product_full_image) && $row->product_full_image != "") {
                        $image = new stdClass;
                        $image->src = $urlBase . "components/com_virtuemart/shop_image/product/" . $row->product_full_image;
                        $image->title = $row->product_name;
                        $image->license = $params['product_image_license_url'];
                        $node->images[] = $image;
                    }
                    // Include additional product images
                    $query = " SELECT file_name FROM #__vm_product_files WHERE file_product_id = ";
                    $query .= $row->product_id;
                    $database->setQuery($query);
                    $additional_images = $database->loadObjectList();
                    foreach ($additional_images as $additional_image) {
                        $image = new stdClass;
                        $image->src = $urlBase . $additional_image->file_name;
                        $image->title = $row->product_name;
                        $node->images[] = $image;
                    }
                }
                $xmap->printNode($node);
            }
            $xmap->changeLevel(-1);
        }

        return $list;
    }

    function getParam($arr, $name, $def)
    {
        return JArrayHelper::getValue($arr, $name, $def, '');
    }

}
