<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('SimpleTemplate.Filters.FilterBase');

class AriSimpleTemplateTruncateFilter extends AriSimpleTemplateFilterBase
{	
	function getFilterName()
	{
		return 'truncate';
	}

	function parse($value, $length = null, $etc = '...')
	{
		$length = @intval($length, 10);
		if ($length < 1) return $value;
		
		if (empty($etc) || strlen($value) <= $length) $etc = '';

		return substr($value, 0, $length) . $etc;
	}
}

new AriSimpleTemplateTruncateFilter();
?>