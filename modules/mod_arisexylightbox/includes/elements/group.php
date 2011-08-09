<?php
defined('_JEXEC') or die ('Restricted access');

class JElementGroup extends JElement
{
	var	$_name = 'Group';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$parent =& $this->_parent;

		$childParameter = new JParameter($parent->_raw);
		$paths = $parent->_elementPath;
		if (is_array($paths))
			foreach ($paths as $path)
				$childParameter->addElementPath($path);
		$childParameter->setXML($node);

		$visible = $node->attributes('visible');
		$prefix = $node->attributes('prefix');
		$id = 'group_' . $prefix . '_' . $node->attributes('group_id');

		return sprintf('<div id="%s" class="el-group" style="display: %s;"><div class="el-group-header"><h4>%s</h4></div><div>%s</div></div>',
			$id,
			$visible ? 'block' : 'none',
			JText::_($node->attributes('label')),
			$childParameter->render($control_name));
	}
	
	function fetchTooltip($label, $description, &$xmlElement, $control_name='', $name='')
	{
		return '';
	}
}
?>