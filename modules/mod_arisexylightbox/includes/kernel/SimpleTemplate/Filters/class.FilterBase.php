<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

class AriSimpleTemplateFilterBase extends AriObject
{
	function __construct()
	{
		$this->_register();
	}
	
	function _register()
	{
		 AriSimpleTemplate::registerFilter($this->getFilterName(), $this->getClassName());
	}
	
	function getFilterName()
	{
		return '';
	}
	
	function parse($val)
	{
		
	}
}
?>