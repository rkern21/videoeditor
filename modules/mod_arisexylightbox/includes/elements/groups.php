<?php
defined('_JEXEC') or die ('Restricted access');

require_once dirname(__FILE__) . '/../kernel/class.AriKernel.php';

AriKernel::import('Web.JSON.JSONHelper');

class JElementGroups extends JElement
{
	var	$_name = 'Groups';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$selectId = $control_name . $name;
		$this->_addGroupAttrs($node, $value, $selectId);
		$parent =& $this->_parent;
		$childParameter = new JParameterGroups($parent->_raw);
		
		$paths = $parent->_elementPath;
		if (is_array($paths))
			foreach ($paths as $path)
				$childParameter->addElementPath($path);

		$childParameter->setXML($node);
		$this->_includeAssets();

		$containerId = uniqid('groups', false);
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration(
			sprintf('window.addEvent("domready", function(){ new ARIElementGroups("%s", %s); });',
				$containerId,
				AriJSONHelper::encode(array('selectId' => $selectId))));
				
		return sprintf('<div id="%1$s"><fieldset><legend><label for="%2$s">%3$s</label>&nbsp;&nbsp;%4$s</legend><div>%5$s</div></fieldset></div>',
			$containerId,
			$selectId,
			JText::_($node->attributes('label')),
			JHTML::_(
				'select.genericlist', 
				$this->_getOptionsGroup($node), 
				$control_name . '[' . $name . ']', 
				'inputbox', 
				'value', 
				'text', 
				$value, 
				$selectId), 
			$childParameter->render($control_name));
	}

	function fetchTooltip($label, $description, &$xmlElement, $control_name='', $name='')
	{
		return '';
	}
	
	function _includeAssets()
	{
		static $loaded;
		
		if ($loaded)
			return ;

		$filePath = str_replace(DS == '\\' ? '/' : '\\', DS, dirname(__FILE__));
		if (strlen(JPATH_ROOT) > 1)
			$filePath = str_replace(JPATH_ROOT, '', $filePath);
			
		$uri = JURI::root(true) . str_replace(DS, '/', $filePath) . '/';
			
		$document =& JFactory::getDocument();
		$document->addScript($uri . 'groups.js');
		$document->addStyleSheet($uri . 'groups.css', 'text/css', null, array());
			
		$loaded = true;
	}
	
	function _addGroupAttrs(&$node, $selectedGroup, $selectId)
	{
		if (empty($node->group))
			return $options;

		foreach ($node->group as $key => $val)
		{
			$group =& $node->group[$key];
			$group_id = $group->attributes('group_id');
			$group->addAttribute('visible', $group_id == $selectedGroup ? '1' : '0');
			$group->addAttribute('prefix', $selectId); 
		}
	}
	
	function _getOptionsGroup(&$node)
	{
		$options = array();
		
		if (empty($node->group))
			return $options; 

		foreach ($node->group as $group)
		{
			$options[] = JHTML::_(
				'select.option', 
				$group->attributes('group_id'), 
				JText::_($group->attributes('label')));
		}

		return $options;
	}
}

class JParameterGroups extends JParameter
{
	function render($name = 'params', $group = '_default')
	{
		if (!isset($this->_xml[$group]))
			return false;

		$params = $this->getParams($name, $group);

		$html = array();
		foreach ($params as $param)
		{
			$html[] = '' . $param[1] . '';
		}

		return implode("\n", $html);
	}
}
?>