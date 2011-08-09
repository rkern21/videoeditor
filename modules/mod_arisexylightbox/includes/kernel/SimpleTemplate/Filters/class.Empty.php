<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('SimpleTemplate.Filters.FilterBase');

class AriSimpleTemplateEmptyFilter extends AriSimpleTemplateFilterBase
{
	function getFilterName()
	{
		return 'empty';
	}

	function parse($value, $replaceValue)
	{
		return empty($value) ? $replaceValue : $value;
	}
}

new AriSimpleTemplateEmptyFilter();
?>