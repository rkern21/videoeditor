<?php
/**
 * @package RocketTheme
 * @subpackage rokstories.elements
 * @version   1.9 September 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * "K2 Items" Module by JoomlaWorks for Joomla! 1.5.x - Version 2.0.0
 * Copyright (c) 2006 - 2009 JoomlaWorks Ltd. All rights reserved.
 * Released under the GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * More info at http://www.joomlaworks.gr
 * Designed and developed by the JoomlaWorks team
 * *** Last update: June 20th, 2009 ***
 */

// no direct access
defined('_JEXEC') or die ('Restricted access');

/**
 * @package RocketTheme
 * @subpackage rokstories.elements
 */
class JElementCategories extends JElement
{

	var	$_name = 'categories';

	function fetchElement($name, $value, &$node, $control_name) {
		$db = &JFactory::getDBO();

		$query = 'SELECT m.* FROM #__k2_categories m WHERE published=1 AND trash = 0 ORDER BY parent, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$children = array();
		if ( $mitems )
		{
			foreach ( $mitems as $v )
			{
				$pt 	= $v->parent;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$mitems = array();

		foreach ( $list as $item ) {
			$mitems[] = JHTML::_('select.option',  $item->id, '&nbsp;&nbsp;&nbsp;'.$item->treename );
		}
		
		$doc = & JFactory::getDocument();
		$js = "
		window.addEvent('domready', function(){
			var filter0 = $('paramscatfilter0');
			if (!filter0) return;
			filter0.addEvent('click', function(){
				$('paramscategory_id').setProperty('disabled', 'disabled');
				$$('#paramscategory_id option').each(function(el) {
					el.setProperty('selected', 'selected');
				});
			})
			
			$('paramscatfilter1').addEvent('click', function(){
				$('paramscategory_id').removeProperty('disabled');
				$$('#paramscategory_id option').each(function(el) {
					el.removeProperty('selected');
				});

			})
			
			if ($('paramscatfilter0').checked) {
				$('paramscategory_id').setProperty('disabled', 'disabled');
				$$('#paramscategory_id option').each(function(el) {
					el.setProperty('selected', 'selected');
				});
			}
			
			if ($('paramscatfilter1').checked) {
				$('paramscategory_id').removeProperty('disabled');
			}
			
		});
		";
		
		$doc->addScriptDeclaration($js);
		$output= JHTML::_('select.genericlist',  $mitems, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;" multiple="multiple" size="10"', 'value', 'text', $value );
		return $output;
	}
	
}
