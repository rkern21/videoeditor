<?php
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.form');

class JFormFieldModule extends JFormField
{
	protected $type = 'Module';
	
	public function getInput()
	{
		return $this->fetchElement($this->element['name'], $this->value, $this->element, $this->name);
	}
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$modules = $this->getModuleList();
		$emptyItem = JText::_($node->attributes('empty_item'));
		if ($emptyItem)
		{
			$emptyItemObj = new stdClass();
			$emptyItemObj->id = '0';
			$emptyItemObj->title = $emptyItem;
			array_unshift($modules, $emptyItemObj);
		}

		return JHTML::_(
			'select.genericlist', 
			$modules, 
			$control_name, 
			'class="inputbox"', 
			'id', 
			'title', 
			$value);
	}
	
	function getModuleList()
	{
		static $modules;
		
		if (!is_null($modules))
			return $modules;
			
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT id,title FROM #__modules ORDER BY title');
		$modules = $db->loadObjectList();
		if ($db->getErrorNum())
		{
			$modules = array();
		}

		if (is_null($modules))
			$modules = array();
			
		return $modules;
	}
}
?>