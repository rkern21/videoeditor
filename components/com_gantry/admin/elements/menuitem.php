<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementMenuItem extends JElement {

	var	$_name = 'MenuItem';

	function fetchElement($name, $value, &$node, $control_name) {
        global $gantry;
		$db =& JFactory::getDBO();

		$parent = $this->_parent;
		$menuType = ($parent) ? $parent->get('menu_type') : $this->get('menu_type');
		if (!empty($menuType)) {
			$where = ' WHERE menutype = '.$db->Quote($menuType);
		} else {
			$where = ' WHERE 1';
		}

		$attributes = $node->attributes();
		$filter = array();
		if (isset($attributes['filterids'])) {
			$filter = explode(',', $attributes['filterids']);
		} 
		
		// get custom menuitems list
		gantry_import('core.params.gantrymenuitemparams');
		$customList = GantryMenuItemParams::_getCustomParamsList();
		
		// load the list of menu types
		$query = 'SELECT menutype, title FROM #__menu_types ' .	' ORDER BY title';
		$db->setQuery( $query );
		$menuTypes = $db->loadObjectList();

		if ($state = $node->attributes('state')) {
			$where .= ' AND published = ' . (int) $state;
		}
		
		$where .= " AND published!=-2 ";

		// load the list of menu items
		$query = 'SELECT id, parent, name, menutype, type FROM #__menu' . $where . ' ORDER BY menutype, parent, ordering';

		$db->setQuery($query);
		$menuItems = $db->loadObjectList();



		// establish the hierarchy of the menu
		$children = array();

		if ($menuItems) {
            $found_value = false;
            foreach ($menuItems as $v) {
                if($v->id == $value){
                    $found_value = true;
                    break;
                }
			}

            if (!$found_value || null == $value || !isset($value)){
                $db		=& JFactory::getDBO();
                $default = 0;
                $query = 'SELECT id'
                    . ' FROM #__menu AS m'
                    . ' WHERE m.home = 1';

                $db->setQuery( $query );
                $value = $db->loadResult();
            }


			// first pass - collect children
			foreach ($menuItems as $v) {
				$pt 	= $v->parent;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		// second pass - get an indent list of the items
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );

		// assemble into menutype groups
		$n = count( $list );
		$groupedList = array();
		foreach ($list as $k => $v) {
			$groupedList[$v->menutype][] = &$list[$k];
		}

		// assemble menu items to the array
		$options 	= array();

		foreach ($menuTypes as $type) {
			if ($menuType == '') {
				$options[]	= JHTML::_('select.option',  '0', '&nbsp;', 'value', 'text', true);
				$options[]	= JHTML::_('select.option',  $type->menutype, $type->title, 'value', 'text', true );
			}
			if (isset( $groupedList[$type->menutype] ))	{
				$n = count($groupedList[$type->menutype]);
				for ($i = 0; $i < $n; $i++) {
					$item = &$groupedList[$type->menutype][$i];
					
					if (in_array($item->id, $filter)) continue;
					
					//If menutype is changed but item is not saved yet, use the new type in the list
					if ( JRequest::getString('option', '', 'get') == 'com_menus' ) {
						$currentItemArray = JRequest::getVar('cid', array(0), '', 'array');
						$currentItemId = (int) $currentItemArray[0];
						$currentItemType = JRequest::getString('type', $item->type, 'get');
						if ( $currentItemId == $item->id && $currentItemType != $item->type) {
							$item->type = $currentItemType;
						}
					}
					
					$disable = strpos($node->attributes('disable'), $item->type) !== false ? true : false;

					if (in_array($item->id, $customList)) $special = "<span class='custom-params'> &#x272A; </span>";
					else $special = "<span class='no-custom-params'>&nbsp;&nbsp;&nbsp; </span>";
					
					$options[] = JHTML::_('select.option',  $item->id, ''.$special .$item->treename, 'value', 'text', $disable );

				}
			}
		}
		
		$options = array_slice($options, 1);
		
		include_once('selectbox.php');
		$selectbox = new JElementSelectBox;
		return $selectbox->fetchElement($name, $value, $node, $control_name, $options);
	}
}
