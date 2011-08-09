<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('SimpleTemplate.Filters.FilterBase');

class AriSimpleTemplateUpperCaseFilter extends AriSimpleTemplateFilterBase
{	
	function getFilterName()
	{
		return 'upper_case';
	}

	function parse($value)
	{
		return strtoupper($value);
	}
}

new AriSimpleTemplateUpperCaseFilter();
?>