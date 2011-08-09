<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/recordmanagement.class.php');

$record = new bfRecordManagement();

switch($task)
{
	case 'edit':
		$record->editRecord();
		break;
	default:
		$record->listRecords();
}