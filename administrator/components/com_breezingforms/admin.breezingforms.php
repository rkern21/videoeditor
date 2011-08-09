<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

$mainframe = JFactory::getApplication();

jimport('joomla.filesystem.file');

// purge ajax save
$sourcePath = JPATH_SITE . '/administrator/components/com_breezingforms/ajax_cache/';
if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
    while (false !== ($file = @readdir($handle))) {
        if($file!="." && $file!="..") {
            $parts = explode('_', $file);
            if(count($parts)==3 && $parts[0] == 'ajaxsave') {
                if (@JFile::exists($sourcePath.$file) && @is_readable($sourcePath.$file)) {
                    $fileCreationTime = @filectime($sourcePath.$file);
                    $fileAge = time() - $fileCreationTime;
                    if($fileAge >= 3600) {
                        @JFile::delete($sourcePath.$file);
                    }
                }
            }
        }
    }
    @closedir($handle);
}

/**
 * DB UPGRADE BEGIN
 */
$tables = JFactory::getDBO()->getTableFields( JFactory::getDBO()->getTableList() );
if(isset($tables[JFactory::getDBO()->getPrefix().'facileforms_forms'])){
    /**
     * New as of 1.7.3
     */
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_alt_mailfrom'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_alt_mailfrom` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `alt_mailfrom` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_alt_fromname'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_alt_fromname` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `alt_fromname` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_custom_mail_subject'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_custom_mail_subject` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `custom_mail_subject` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_emailntf'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_emailntf` tinyint( 1 ) NOT NULL DEFAULT 1 AFTER `emailntf` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_emaillog'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_emaillog` tinyint( 1 ) NOT NULL DEFAULT 1 AFTER `emaillog` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_emailxml'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_emailxml` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `emailxml` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['email_type'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `email_type` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `mb_emailxml` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_email_type'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_email_type` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `email_type` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['email_custom_template'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `email_custom_template` TEXT AFTER `mb_email_type` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_email_custom_template'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_email_custom_template` TEXT AFTER `email_custom_template` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['email_custom_html'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `email_custom_html` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `mb_email_custom_template` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_email_custom_html'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_email_custom_html` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `email_custom_html` ");
        JFactory::getDBO()->query();
    }
    /////
    // New as of 1.7.2
    /////
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['alt_mailfrom'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `alt_mailfrom` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `id` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['alt_fromname'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `alt_fromname` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `alt_mailfrom` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_email_field'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_email_field` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `alt_fromname` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_checkbox_field'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_checkbox_field` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_email_field` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_api_key'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_api_key` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_checkbox_field` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_list_id'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_list_id` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_api_key` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_double_optin'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_double_optin` TINYINT( 1 ) NOT NULL DEFAULT 1 AFTER `mailchimp_list_id` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_mergevars'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_mergevars` TEXT AFTER `mailchimp_double_optin` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_text_html_mobile_field'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_text_html_mobile_field` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_mergevars` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_send_errors'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_send_errors` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_text_html_mobile_field` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_update_existing'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_update_existing` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_send_errors` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_replace_interests'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_replace_interests` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_update_existing` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_send_welcome'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_send_welcome` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_replace_interests` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_default_type'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_default_type` VARCHAR( 255 ) NOT NULL DEFAULT 'text' AFTER `mailchimp_send_welcome` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_delete_member'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_delete_member` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_default_type` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_send_goodbye'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_send_goodbye` TINYINT( 1 ) NOT NULL DEFAULT 1 AFTER `mailchimp_delete_member` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_send_notify'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_send_notify` TINYINT( 1 ) NOT NULL DEFAULT 1 AFTER `mailchimp_send_goodbye` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_unsubscribe_field'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_unsubscribe_field` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_send_notify` ");
        JFactory::getDBO()->query();
    }
}
/**
 * DB UPGRADE END
 */

require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/classes/BFTabs.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/classes/BFText.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/classes/BFTableElements.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/functions/helpers.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/constants.php');

jimport('joomla.version');
$version = new JVersion();

if(version_compare($version->getShortVersion(), '1.6', '>=')){

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGERECS'),
                        'index.php?option=com_breezingforms&act=managerecs', JRequest::getVar('act','') == 'managerecs' || JRequest::getVar('act','') == 'recordmanagement');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGEFORMS'),
                        'index.php?option=com_breezingforms&act=manageforms', JRequest::getVar('act','') == 'manageforms' || JRequest::getVar('act','') == 'easymode' || JRequest::getVar('act','') == 'quickmode');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGESCRIPTS'),
                        'index.php?option=com_breezingforms&act=managescripts', JRequest::getVar('act','') == 'managescripts');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGEPIECES'),
                        'index.php?option=com_breezingforms&act=managepieces', JRequest::getVar('act','') == 'managepieces');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_INTEGRATOR'),
                        'index.php?option=com_breezingforms&act=integrate', JRequest::getVar('act','') == 'integrate');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGEMENUS'),
                        'index.php?option=com_breezingforms&act=managemenus', JRequest::getVar('act','') == 'managemenus');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_CONFIG'),
                        'index.php?option=com_breezingforms&act=configuration', JRequest::getVar('act','') == 'configuration');

}

$_POST    = bf_stripslashes_deep($_POST);
$_GET     = bf_stripslashes_deep($_GET);
$_REQUEST = bf_stripslashes_deep($_REQUEST);

$db = JFactory::getDBO();

/*
 * Temporary section end
 */

global $errors, $errmode;
global $ff_mospath, $ff_admpath, $ff_compath, $ff_request;
global $ff_mossite, $ff_admsite, $ff_admicon, $ff_comsite;
global $ff_config, $ff_compatible, $ff_install;

$my = JFactory::getUser();

if (!isset($ff_compath)) { // joomla!
	
	jimport('joomla.version');
        $version = new JVersion();

        if(version_compare($version->getShortVersion(), '1.6', '<')){
            if ($my->usertype != 'Super Administrator' && $my->usertype != 'Administrator') {
                    JFactory::getApplication()->redirect( 'index.php', BFText::_('COM_BREEZINGFORMS_NOT_AUTHORIZED') );
            } // if
        }

	// get paths
	$comppath = '/components/com_breezingforms';
	$ff_admpath = dirname(__FILE__);
	$ff_mospath = str_replace('\\','/',dirname(dirname(dirname($ff_admpath))));
	$ff_admpath = str_replace('\\','/',$ff_admpath);
	$ff_compath = $ff_mospath.$comppath;

	require_once($ff_admpath.'/toolbar.facileforms.php');
} // if

$errors = array();
$errmode = 'die';   // die or log

// compatibility check
if (!$ff_compatible) {
	echo '<h1>'.BFText::_('COM_BREEZINGFORMS_INCOMPATIBLE').'</h1>';
	exit;
} // if

// load ff parameters
$ff_request = array();
reset($_REQUEST);
while (list($prop, $val) = each($_REQUEST))
	if (is_scalar($val) && substr($prop,0,9)=='ff_param_')
		$ff_request[$prop] = $val;

if ($ff_install) {
	$act = 'installation';
	$task = 'step2';
} // if

$ids = JRequest::getVar( 'ids', array());

switch($act) {
	case 'installation':
		require_once($ff_admpath.'/admin/install.php');
		break;
	case 'configuration':
		require_once($ff_admpath.'/admin/config.php');
		break;
	case 'managemenus':
		require_once($ff_admpath.'/admin/menu.php');
		break;
	case 'manageforms':
		require_once($ff_admpath.'/admin/form.php');
		break;
	case 'editpage':
		require_once($ff_admpath.'/admin/element.php');
		break;
	case 'managescripts':
		require_once($ff_admpath.'/admin/script.php');
		break;
	case 'managepieces':
		require_once($ff_admpath.'/admin/piece.php');
		break;
	case 'run':
		require_once($ff_admpath.'/admin/run.php');
		break;
	case 'easymode':
		require_once($ff_admpath.'/admin/easymode.php');
		break;
	case 'quickmode':
		require_once($ff_admpath.'/admin/quickmode.php');
		break;
	case 'quickmode_editor':
		require_once($ff_admpath.'/admin/quickmode-editor.php');
		break;
	case 'integrate':
		require_once($ff_admpath.'/admin/integrator.php');
		break;
	case 'recordmanagement':
		require_once($ff_admpath.'/admin/recordmanagement.php');
		break;
	default:
		require_once($ff_admpath.'/admin/recordmanagement.php');
		break;
} // switch

// some general purpose functions for admin

function isInputElement($type)
{
	switch ($type) {
		case 'Static Text/HTML':
		case 'Rectangle':
		case 'Image':
		case 'Tooltip':
		case 'Query List':
		case 'Regular Button':
		case 'Graphic Button':
		case 'Icon':
			return false;
		default:
			break;
	} // switch
	return true;
} // isInputElement

function isVisibleElement($type)
{
	switch ($type) {
		case 'Hidden Input':
			return false;
		default:
			break;
	} // switch
	return true;
} // isVisibleElement

function _ff_query($sql, $insert = 0)
{
	global $database, $errors;
	$database = JFactory::getDBO();
	$id = null;
	$database->setQuery($sql);
	$database->query();
	if ($database->getErrorNum()) {
		if (isset($errmode) && $errmode=='log')
			$errors[] = $database->getErrorMsg();
		else
			die($database->stderr());
	} // if
	if ($insert) $id = $database->insertid();
	return $id;
} // _ff_query

function _ff_select($sql)
{
	global $database, $errors;
	$database = JFactory::getDBO();
	$database->setQuery($sql);
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		if ($errmode=='log')
			$errors[] = $database->getErrorMsg();
		else
			die($database->stderr());
	} // if
	
	return $rows;
} // _ff_select

function _ff_selectValue($sql)
{
	global $database, $errors;
	$database = JFactory::getDBO();
	$database->setQuery($sql);
	$value = $database->loadResult();
	if ($database->getErrorNum()) {
		if ($errmode=='log')
			$errors[] = $database->getErrorMsg();
		else
			die($database->stderr());
	} // if
	return $value;
} // _ff_selectValue

function protectedComponentIds()
{
    jimport('joomla.version');
    $version = new JVersion();

    if(version_compare($version->getShortVersion(), '1.6', '>=')){

        $rows = _ff_select(
		"select id, parent_id As parent from #__menu ".
		"where ".
		" link in (".
			"'index.php?option=com_breezingforms&act=managerecs',".
			"'index.php?option=com_breezingforms&act=managemenus',".
			"'index.php?option=com_breezingforms&act=manageforms',".
			"'index.php?option=com_breezingforms&act=managescripts',".
			"'index.php?option=com_breezingforms&act=managepieces',".
			"'index.php?option=com_breezingforms&act=share',".
			"'index.php?option=com_breezingforms&act=integrate',".
			"'index.php?option=com_breezingforms&act=configuration'".
		") ".
		"order by id"
	);

    }else{

	$rows = _ff_select(
		"select id, parent from #__components ".
		"where `option`='com_breezingforms' ".
		"and admin_menu_link in (".
			"'option=com_breezingforms&act=managerecs',".
			"'option=com_breezingforms&act=managemenus',".
			"'option=com_breezingforms&act=manageforms',".
			"'option=com_breezingforms&act=managescripts',".
			"'option=com_breezingforms&act=managepieces',".
			"'option=com_breezingforms&act=share',".
			"'option=com_breezingforms&act=integrate',".
			"'option=com_breezingforms&act=configuration'".
		") ".
		"order by id"
	);

    }
    
    $parent = 0;
    $ids = array();
    if (count($rows))
        foreach ($rows as $row) {
            if ($parent == 0) {
                $parent = 1;
                if(isset($row->parent)){
                    $ids[] = $row->parent;
                }
            } // if
            $ids[] = $row->id;
        } // foreach
 return implode($ids, ',');
} // protectedComponentIds

function addComponentMenu($row, $parent, $copy = false)
{
	$db = JFactory::getDBO();
	$admin_menu_link = '';
	if ($row->name!='') {
		$admin_menu_link =
			'option=com_breezingforms'.
			'&act=run'.
			'&ff_name='.$row->name;
		if ($row->page!=1) $admin_menu_link .= '&ff_page='.$row->page;
		if ($row->frame==1) $admin_menu_link .= '&ff_frame=1';
		if ($row->border==1) $admin_menu_link .= '&ff_border=1';
		if ($row->params!='') $admin_menu_link .= $row->params;
	} // if
	if ($parent==0) $ordering = 0; else $ordering = $row->ordering;

        jimport('joomla.version');
        $version = new JVersion();

        if(version_compare($version->getShortVersion(), '1.6', '>=')){

            $parent = $parent == 0 ? 1 : $parent;

            $db->setQuery("Select component_id From #__menu Where `title`='com_breezingforms' And link = 'index.php?option=com_breezingforms' And parent_id = 1");
            $result = $db->loadResult();
            if($result){
                
                return _ff_query(
                    "insert into #__menu (".
                            "`title`, alias, menutype, parent_id, ".
                            "link,".
                            "ordering, level, component_id, client_id, img".
                    ") ".
                    "values (".
                            "'com_breezingforms', ".$db->Quote( ($copy ? 'Copy of ' : '') . $row->title . ($copy ? ' ('.md5(session_id().microtime().mt_rand(0,  mt_getrandmax())).')' : '')).", 'menu', $parent, ".
                            "'index.php?$admin_menu_link',".
                            "'$ordering', 1, ".intval($result).", 1, 'components/com_breezingforms/images/$row->img'".
                    ")",
                    true
                );
            }else{
                die("BreezingForms main menu item not found!");
            }
        }
        // if older JVersion
	return _ff_query(
		"insert into #__components (".
			"id, name, link, menuid, parent, ".
			"admin_menu_link, admin_menu_alt, `option`, ".
			"ordering, admin_menu_img, iscore, params".
		") ".
		"values (".
			"'', ".$db->Quote($row->title).", '', 0, $parent, ".
			"'$admin_menu_link', ".$db->Quote($row->title).", 'com_breezingforms', ".
			"'$ordering', '$row->img', 1, ''".
		")",
		true
	);
} // addComponentMenu

function updateComponentMenus($copy = false)
{
	// remove unprotected menu items
	$protids = protectedComponentIds();
	if(trim($protids)!=''){

            jimport('joomla.version');
            $version = new JVersion();

            if(version_compare($version->getShortVersion(), '1.6', '>=')){
                _ff_query(
			"delete from #__menu ".
			"where `link` Like 'index.php?option=com_breezingforms%' ".
			"and id not in ($protids)"
		);
            }else{
		_ff_query(
			"delete from #__components ".
			"where `option`='com_breezingforms' ".
			"and id not in ($protids)"
		);
            }
	} 
	
	// add published menu items
	$rows = _ff_select(
		"select ".
			"m.id as id, ".
			"m.parent as parent, ".
			"m.ordering as ordering, ".
			"m.title as title, ".
			"m.img as img, ".
			"m.name as name, ".
			"m.page as page, ".
			"m.frame as frame, ".
			"m.border as border, ".
			"m.params as params, ".
			"m.published as published ".
		"from #__facileforms_compmenus as m ".
			"left join #__facileforms_compmenus as p on m.parent=p.id ".
		"where m.published=1 ".
			"and (m.parent=0 or p.published=1) ".
		"order by ".
			"if(m.parent,p.ordering,m.ordering), ".
			"if(m.parent,m.ordering,-1)"
	);
	$parent = 0;
	if (count($rows)) foreach ($rows as $row) {

                jimport('joomla.version');
                $version = new JVersion();

                if(version_compare($version->getShortVersion(), '1.6', '>=')){

                    JFactory::getDBO()->setQuery("Select id From #__menu Where `alias` = " . JFactory::getDBO()->Quote($row->title));

                    if(JFactory::getDBO()->loadResult()){
                        return BFText::_('COM_BREEZINGFORMS_MENU_ITEM_EXISTS');
                    }

                    if ($row->parent==0 || $row->parent==1){
                            $parent = addComponentMenu($row, 1, $copy);
                    }else{
                            addComponentMenu($row, $parent, $copy);
                    }
                }else{
                    if ($row->parent==0){
                            $parent = addComponentMenu($row, 0);
                    }else{
                            addComponentMenu($row, $parent);
                    }
                }
	} // foreach

        return '';
} // updateComponentMenus

function dropPackage($id)
{
	// drop package settings
	_ff_query("delete from #__facileforms_packages where id = '$id'");

	// drop backend menus
	$rows = _ff_select("select id from #__facileforms_compmenus where package = '$id'");
	if (count($rows)) foreach ($rows as $row)
		_ff_query("delete from #__facileforms_compmenus where id=$row->id or parent=$row->id");
	updateComponentMenus();

	// drop forms
	$rows = _ff_select("select id from #__facileforms_forms where package = '$id'");
	if (count($rows)) foreach ($rows as $row) {
		_ff_query("delete from #__facileforms_elements where form = $row->id");
		_ff_query("delete from #__facileforms_forms where id = $row->id");
	} // if

	// drop scripts
	_ff_query("delete from #__facileforms_scripts where package =  '$id'");

	// drop pieces
	_ff_query("delete from #__facileforms_pieces where package =  '$id'");
} // dropPackage

function savePackage($id, $name, $title, $version, $created, $author, $email, $url, $description, $copyright)
{
	$db = JFactory::getDBO();
	$cnt = _ff_selectValue("select count(*) from #__facileforms_packages where id='$id'");
	if (!$cnt) {
		
		_ff_query(
			"insert into #__facileforms_packages ".
					"(id, name, title, version, created, author, ".
					 "email, url, description, copyright) ".
			"values (".$db->Quote($id).", ".$db->Quote($name).", ".$db->Quote($title).", ".$db->Quote($version).", ".$db->Quote($created).", ".$db->Quote($author).",
					".$db->Quote($email).", ".$db->Quote($url).", ".$db->Quote($description).", ".$db->Quote($copyright).")"
		);
	} else {
		_ff_query(
			"update #__facileforms_packages ".
				"set name=".$db->Quote($name).", title=".$db->Quote($title).", version=".$db->Quote($version).", created=".$db->Quote($created).", author=".$db->Quote($author).", ".
				"email=".$db->Quote($email).", url=".$db->Quote($url).", description=".$db->Quote($description).", copyright=".$db->Quote($copyright). " 
			where id =  ".$db->Quote($id)
		);
	} // if
} // savePackage

function relinkScripts(&$oldscripts)
{
	if (count($oldscripts))
		foreach ($oldscripts as $row) {
			$newid = _ff_selectValue("select max(id) from #__facileforms_scripts where name = '".$row->name."'");
			if ($newid) {
				_ff_query("update #__facileforms_forms set script1id=$newid where script1id=$row->id");
				_ff_query("update #__facileforms_forms set script2id=$newid where script2id=$row->id");
				_ff_query("update #__facileforms_elements set script1id=$newid where script1id=$row->id");
				_ff_query("update #__facileforms_elements set script2id=$newid where script2id=$row->id");
				_ff_query("update #__facileforms_elements set script3id=$newid where script3id=$row->id");
			} // if
		} // foreach
} // relinkScripts

function relinkPieces(&$oldpieces)
{
	if (count($oldpieces))
		foreach ($oldpieces as $row) {
			$newid = _ff_selectValue("select max(id) from #__facileforms_pieces where name = '".$row->name."'");
			if ($newid) {
				_ff_query("update #__facileforms_forms set piece1id=$newid where piece1id=$row->id");
				_ff_query("update #__facileforms_forms set piece2id=$newid where piece2id=$row->id");
				_ff_query("update #__facileforms_forms set piece3id=$newid where piece3id=$row->id");
				_ff_query("update #__facileforms_forms set piece4id=$newid where piece4id=$row->id");
			} // if
		} // foreach
} // relinkPieces
?>