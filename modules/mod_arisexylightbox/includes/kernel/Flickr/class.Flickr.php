<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

if (!class_exists('phpFlickr'))
{
	if (version_compare(PHP_VERSION, '5.0.0', '<'))
		require_once 'phpFlickr.php';
	else
		require_once 'phpFlickr5.php';
}
?>