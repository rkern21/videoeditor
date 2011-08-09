<?php
/**
 * @package gantry
 * @subpackage admin.ajax-models
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();
gantry_import('core.gantryjson');

global $gantry;


$file = $gantry->custom_presets_file;
$action = JRequest::getString('action');

if ($action == 'add') {
	$data = GantryJSON::decode(JRequest::getString('presets-data'), false);

	if (!file_exists($file)) {
		$handle = @fopen($file, 'w');
		@fwrite($handle, "");
	}

	gantry_import('core.gantryini');
	$newEntry = GantryINI::write($file, $data);

	if ($newEntry) echo "success";
} else if ($action == 'delete') {
	$presetTitle = JRequest::getString('preset-title');
	$presetKey = JRequest::getString('preset-key');
	if (!$presetKey || !$presetTitle) return "error";
	
	GantryINI::write($file, array($presetTitle => array($presetKey => array())), 'delete-key');
	
} else {
	return "error";
}

?>
