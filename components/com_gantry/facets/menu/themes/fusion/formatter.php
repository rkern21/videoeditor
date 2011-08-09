<?php
// no direct access
defined('GANTRY_VERSION') or die('Restricted access');

gantry_import('facets.menu.gantrymenuformatter');

/*
 * Created on Jan 16, 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class GantryMenuFormatterFusion extends GantryMenuFormatter {
	function format(&$node, &$menu_params) {
	    // Format the current node
		
		if ($node->type == 'menuitem' or $node->type == 'separator') {
		    if ($node->hasChildren() ) {
    			$node->addLinkClass("daddy");
    		} 
    		
    		$node->addLinkClass("item");
            
		}
		
		if ($node->level == "0") {
			$node->addListItemClass("root");
		}
	}
}