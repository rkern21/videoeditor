<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

if (function_exists('t3import'))
	t3import('core.joomla.modulehelper');
else if (!class_exists('JModuleHelper'))
	jimport('joomla.application.module.helper');

$version = new JVersion();
$j15 = version_compare($version->getShortVersion(), '1.6.0', '<');
	
if ($j15)
	AriKernel::import('Module.j15.ModuleHelper');
else
	AriKernel::import('Module.j16.ModuleHelper');
?>