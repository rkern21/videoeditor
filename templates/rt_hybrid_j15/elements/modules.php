<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version        1.5.4 November 16, 2010
 * @author        RocketTheme http://www.rockettheme.com
 * @copyright     Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementModules extends JElement
{
	var	$_name = 'Modules';

	function fetchElement( $name, $value, &$node, $control_name ) {

		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__modules where client_id=0 ORDER BY title ASC";
		$db->setQuery($query);
		$groups = $db->loadObjectList();

		$groupHTML = array();
		if ($groups && count ($groups)) {
			foreach ($groups as $v=>$t){
				$groupHTML[] = JHTML::_('select.option', $t->id, $t->title);
			}
		}
		$lists = JHTML::_('select.genericlist', $groupHTML, "params[".$name."][]", ' multiple="multiple"  size="10" ', 'value', 'text', $value);

		return $lists;
	}
}