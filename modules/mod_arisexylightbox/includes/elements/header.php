<?php
defined('_JEXEC') or die ('Restricted access');

class JElementHeader extends JElement
{
	var	$_name = 'Header';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$options = array(JText::_($value));
		foreach ($node->children() as $option)
		{
			$options[] = $option->data();
		}
		
		return sprintf('<div style="font-weight: bold; font-size: 120%%; color: #FFF; background-color: #7A7A7A; padding: 2px 0; text-align: center;">%s</div>', call_user_func_array('sprintf', $options));
	}
}
?>