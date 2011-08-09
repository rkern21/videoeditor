<?php
/**
 * @version $Id: uninstall.rokcandy.php 5682 2008-08-18 05:39:07Z wonderslug $
 * @package RocketTheme
 * @subpackage	RokBrdige
 * @copyright Copyright (C) 2008 RocketTheme. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');	
 
class Status {
	var $STATUS_FAIL = 'Failed';
	var $STATUS_SUCCESS = 'Success';
	var $infomsg = array();
	var $errmsg = array();
	var $status;
}

$rok_database = JFactory::getDBO();
$rok_install_status = array();

function rok_com_uninstall()
{
	echo( "RokCandy has been successfully uninstalled." );
}