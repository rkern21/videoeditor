<?php
/**
 * @version $Id: install.rokcandy.php 6328 2008-10-22 21:06:47Z wonderslug $
 * @package RocketTheme
 * @subpackage	RokDownloads
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


function rok_com_install() {
	return com_rokcandy_install();
}

function com_rokcandy_install() {
	global $rok_install_status;
	$db = JFactory::getDBO();
	
	$status = new Status();
	$status->status = $status->STATUS_FAIL;
	$status->component = "com_rokcandy";
	
    if (!com_rokcandy_check_for_table($db)) {
        com_rokcandy_newtable($db, $status);
    }

    if (!com_rokcandy_initial_data_population($db, $status)){
		return false;
	}
	
	if (count($status->errmsg) == 0) {
		$status->status = $status->STATUS_SUCCESS;
	}
	
	//clear link
	$sql = "update #__components set link='' where name='RokCandy'";
	$db->setQuery($sql);
	$db->query();
	
	$rok_install_status["RokCandy Component"] = $status;
	return true;
}

function com_rokcandy_initial_data_population($db, &$status) {
    
    $category = 0;
    
    // see if we have any categories
    $sql = "select id from #__categories where section = 'com_rokcandy' order by id limit 1";
	$db->setQuery($sql);
	if (!$db->query()) {
		$status->errormsg[] = $db->getErrorMsg();
		print_r($status);
		return false;
	}

    // no categories, create some
	if ($db->getNumRows() == 0) {
	    $sql = "insert into #__categories values " .
	            "(null,'0','Basic','','basic','','com_rokcandy','left','','1','0','0000-00-00 00:00:00',null,'1','0','0','')," .
	            "(null,'0','Typography','','typography','','com_rokcandy','left','','1','0','0000-00-00 00:00:00',null,'1','0','0','')";
	    $db->setQuery($sql);
	    if (!$db->query()) {
			$status->errormsg[] = $db->getErrorMsg();
			print_r($status);
			return false;
		} else {
            $sql = "select id from #__categories where section = 'com_rokcandy' order by id limit 1";
            $db->setQuery($sql);
            $category = $db->loadResult();
		}
	} else {
	    $category = $db->loadResult();
	}
    
	
	$sql = "select * from #__rokcandy";
	$db->setQuery($sql);
	if (!$db->query()) {
	    $status->errormsg[] = $db->getErrorMsg();
		print_r($status);
		return false;   
	} else {
	    if ($db->getNumRows() == 0) {
	        //no data, add some
        	$sql = "insert into #__rokcandy values " .
        	       "(null,'" . $category . "','[code]{text}[/code]','<code>{text}</code>','1','0','0000-00-00 00:00:00','7',''), " .
                   "(null,'" . $category . "','[i]{text}[/i]','<em>{text}</em>','1','0','0000-00-00 00:00:00','6',''), " .
                   "(null,'" . $category . "','[b]{text}[/b]','<strong>{text}</strong>','1','0','0000-00-00 00:00:00','5',''), " .
                   "(null,'" . $category . "','[h4]{text}[/h4]','<h4>{text}</h4>','1','0','0000-00-00 00:00:00','4',''), " .
                   "(null,'" . $category . "','[h1]{text}[/h1]','<h1>{text}</h1>','1','0','0000-00-00 00:00:00','1',''), " .
                   "(null,'" . $category . "','[h2]{text}[/h2]','<h2>{text}</h2>','1','0','0000-00-00 00:00:00','2',''), " .
                   "(null,'" . $category . "','[h3]{text}[/h3]','<h3>{text}</h3>','1','0','0000-00-00 00:00:00','3','')";
            $db->setQuery($sql);
            if (!$db->query()) {
    			$status->errormsg[] = $db->getErrorMsg();
    			print_r($status);
    			return false;
    		}
	    } 
	}
	return true;
	
	
	
	
}


function com_rokcandy_check_for_table($db) {
	$table_check_query = "select id from #__rokcandy where id = 1";
	$db->setQuery($table_check_query);
	if (!$db->query()) {
		return false;
	}
	return true;
}

function com_rokcandy_newtable($db, &$status) {
	$create_rokcandy_table_sql =
		"CREATE TABLE IF NOT EXISTS `#__rokcandy` ( " .
        "  `id` int(11) unsigned NOT NULL auto_increment, " .
        "  `catid` int(11) NOT NULL, " .
        "  `macro` text NOT NULL, " .
        "  `html` text NOT NULL, " .
        "  `published` tinyint(1) NOT NULL, " .
        "  `checked_out` int(11) NOT NULL, " .
        "  `checked_out_time` datetime NOT NULL, " .
        "  `ordering` int(11) NOT NULL, " .
        "  `params` text NOT NULL, " .
        "  PRIMARY KEY  (`id`) " .
        ") ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8";
	$db->setQuery($create_rokcandy_table_sql);
	if (!$db->query()) {
		$status->errormsg[] = $db->getErrorMsg();
		return false;
	}
	return true;
}