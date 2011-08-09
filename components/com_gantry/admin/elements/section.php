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

class JElementSection extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Section';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT id, title FROM #__sections WHERE published = 1 AND scope = "content" ORDER BY title';
		$db->setQuery($query);
		$options = $db->loadObjectList();

		foreach($options as $index => $option) {
			$option->text = $option->title;
			$option->value = $option->id;
			$option->disable = false;
			$options[$index] = $option;
		}
		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('Select Section').' -', 'value', 'text'));

		include_once('selectbox.php');
		$selectbox = new JElementSelectBox;
		return $selectbox->fetchElement($name, $value, $node, $control_name, $options);
	}
}
