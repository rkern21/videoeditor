<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('SimpleTemplate.Filters.Truncate');
AriKernel::import('SimpleTemplate.Filters.HtmlTruncate');
AriKernel::import('SimpleTemplate.Filters.RestoreTags');
AriKernel::import('SimpleTemplate.Filters.StripTags');
AriKernel::import('SimpleTemplate.Filters.UpperCase');
AriKernel::import('SimpleTemplate.Filters.LowerCase');
AriKernel::import('SimpleTemplate.Filters.Empty');
?>