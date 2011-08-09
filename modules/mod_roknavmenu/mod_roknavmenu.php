<?php
/**
 * @version $Id$
 * @package RocketWerx
 * @subpackage	RokNavMenu
 * @copyright Copyright (C) 2009 RocketWerx. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$params->def('menutype', 			'mainmenu');
$params->def('class_sfx', 			'');
$params->def('menu_images', 		0);
$params->def('startLevel', 		0);
$params->def('endLevel', 			0);
$params->def('showAllChildren', 	0);

require_once(dirname(__FILE__)."/lib/includes.php");
$rnm = new RokNavMenu($params->toArray());
$rnm->initialize();
echo $rnm->render();
$foo="bar";