<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('SimpleTemplate.Filters.FilterBase');

class AriSimpleTemplateLowerCaseFilter extends AriSimpleTemplateFilterBase
{	
	function getFilterName()
	{
		return 'lower_case';
	}

	function parse($value)
	{
		return strtolower($value);
	}
}

new AriSimpleTemplateLowerCaseFilter();
?>