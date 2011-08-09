<?php
/**
 * FadeGallery Joomla! 1.5 Native Component
 * @version 1.3.0
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class JElementFadeGallery extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'fadegallery';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id, galleryname '
		. ' FROM #__fadegallery '
		. ' ORDER BY galleryname'
		;
		$db->setQuery( $query );
		$options = $db->loadObjectList( );
		if(!$options) $options = array();
		
		return JHTML::_('select.genericlist', $options, $control_name.'['.$name.']', 'class="inputbox"', 'id', 'galleryname', $value);
	}
}