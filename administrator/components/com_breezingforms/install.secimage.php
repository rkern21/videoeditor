<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

if(!version_compare(PHP_VERSION, '5.1.2', '>=')){
 
	echo '<b style="color:red">WARNING: YOU ARE RUNNING PHP VERSION "'.PHP_VERSION.'". BREEZINGFORMS WON\'T WORK WITH THIS VERSION. PLEASE UPGRADE TO AT LEAST PHP 5.1.2, SORRY BUT YOU BETTER UNINSTALL THIS COMPONENT NOW!</b>';
}

jimport('joomla.filesystem.file');

// we will be waiting until sh40sef and joomfish are officially J! 1.6 ready
jimport('joomla.version');
$version = new JVersion();
if(version_compare($version->getShortVersion(), '1.6', '<')){
    if (file_exists(JPATH_SITE . "/components/com_sh404sef/sef_ext")){
        JFile::copy( JPATH_SITE . "/administrator/components/com_breezingforms/sh404sef/com_breezingforms.php", JPATH_SITE . "/components/com_sh404sef/sef_ext/com_breezingforms.php");
    }
    if (file_exists(JPATH_SITE . "/administrator/components/com_joomfish/contentelements"))
    {
            JFile::copy( JPATH_SITE . "/administrator/components/com_breezingforms/joomfish/breezingforms_elements.xml",JPATH_SITE . "/administrator/components/com_joomfish/contentelements/breezingforms_elements.xml");
            JFile::copy( JPATH_SITE . "/administrator/components/com_breezingforms/joomfish/translationFformFilter.php", JPATH_SITE . "/administrator/components/com_joomfish/contentelements/translationFformFilter.php");
            JFile::copy( JPATH_SITE . "/administrator/components/com_breezingforms/joomfish/translationFformoptions_emptyFilter.php", JPATH_SITE . "/administrator/components/com_joomfish/contentelements/translationFformoptions_emptyFilter.php");
    }
}
?>