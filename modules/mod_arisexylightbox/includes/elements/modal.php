<?php
/*
 * ARI Framework Lite
 *
 * @package		ARI Framework Lite
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('_JEXEC') or die ('Restricted access');

class JElementModal extends JElement
{
	var	$_name = 'Modal';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		JHTML::_('behavior.modal', 'a.modal');
		
		return '';
	}
	
	function fetchTooltip($label, $description, &$xmlElement, $control_name='', $name='') 
	{
		return false;
	} 
}
?>