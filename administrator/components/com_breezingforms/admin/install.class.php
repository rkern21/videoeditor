<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/install.html.php');
require_once($ff_admpath.'/admin/config.class.php');

class facileFormsInstaller
{

	function split_sql($sql)
	{
		$sql = trim($sql);
                //$sql = preg_replace("\n\#[^\n]*\n", "\n", $sql);
		//$sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);
		$buffer = array();
		$ret = array();
		$in_string = false;
		for($i = 0; $i < strlen($sql)-1; $i++) {
			if($sql[$i] == ";" && !$in_string) {
				$ret[] = substr($sql, 0, $i);
				$sql = substr($sql, $i + 1);
				$i = 0;
			} // if
			if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\")
				$in_string = false;
			else
				if(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\"))
					$in_string = $sql[$i];
			if(isset($buffer[1])) $buffer[0] = $buffer[1];
			$buffer[1] = $sql[$i];
		} // for
		if(!empty($sql)) $ret[] = $sql;
		return($ret);
	} // split_sql

	function exec_sql($sqlfile)
	{
		$mqr = @get_magic_quotes_runtime();
		@set_magic_quotes_runtime(0);
		$query = fread(fopen($sqlfile, "r"), filesize($sqlfile));
		@set_magic_quotes_runtime($mqr);
		$pieces  = facileFormsInstaller::split_sql($query);
		for ($i = 0; $i < count($pieces); $i++) {
			$pieces[$i] = trim($pieces[$i]);
			if (!empty($pieces[$i]) && $pieces[$i] != "#") _ff_query($pieces[$i]);
		} // for
	} // exec_sql

	function chmodRecursive($path, $filemode=NULL, $dirmode=NULL)
	{
		$ret = TRUE;
		if (is_dir($path)) {
			$dh = opendir($path);
			while ($file = readdir($dh)) {
				if ($file != '.' && $file != '..') {
					$fullpath = $path.'/'.$file;
					if (is_dir($fullpath)) {
						if (!facileFormsInstaller::chmodRecursive($fullpath, $filemode, $dirmode))
							$ret = FALSE;
					} else {
						if (!is_null($filemode))
							if (!@chmod($fullpath, $filemode))
								$ret = FALSE;
					} // if
				} // if
			} // while
			closedir($dh);
			if (!is_null($dirmode))
				if (!@chmod($path, $dirmode))
					$ret = FALSE;
		} else {
			if (!is_null($filemode))
				if (!@chmod($path, $filemode))
					$ret = FALSE;
		} // if
		return $ret;
	} // chmodRecursive

	function testdb($table, $column)
	{
		global $database;
		$database = JFactory::getDBO();
		$cnt = NULL;
		$olderr = error_reporting(0);
		$database->setQuery("select count(".$column.") from ".$table);
		$cnt = $database->loadResult();
		error_reporting($olderr);
		return !is_null($cnt);
	} // testdb

	function step2($option)
	{
		global $ff_mospath, $ff_admpath, $ff_compath, $mosConfig_fileperms;

		// remove erraneous by installer created dirs
		@rmdir($ff_admpath.'/exports');
		@rmdir($ff_admpath.'/images/pizzashop');
		@rmdir($ff_admpath.'/uploads');
		@rmdir($ff_compath.'/admin');
		@rmdir($ff_compath.'/images/icons');
		@rmdir($ff_compath.'/packages');
		@rmdir($ff_compath.'/sql');

		// change file permissions
		if (!isset($mosConfig_fileperms)) {
			// pre 4.5.2, need to chmod myself
			$filemode = 0644;
			$dirmode = 0755;
			facileFormsInstaller::chmodRecursive($ff_admpath, $filemode, $dirmode);
			facileFormsInstaller::chmodRecursive($ff_compath, $filemode, $dirmode);

			$ff_modpath = $ff_mospath.'/modules';
			facileFormsInstaller::chmodRecursive($ff_modpath.'/mod_breezingforms.php', $filemode, $dirmode);
			facileFormsInstaller::chmodRecursive($ff_modpath.'/mod_breezingforms.xml', $filemode, $dirmode);

			$ff_botpath = $ff_mospath.'/mambots/content';
			facileFormsInstaller::chmodRecursive($ff_botpath.'/bot_breezingforms.php', $filemode, $dirmode);
			facileFormsInstaller::chmodRecursive($ff_botpath.'/bot_breezingforms.xml', $filemode, $dirmode);
		} // if

		// detect release
		$release = '1.4';
		if (!facileFormsInstaller::testdb('#__facileforms_forms', 'package')) {
			$release = '1.3';
			if (!facileFormsInstaller::testdb('#__facileforms_forms', 'runmode')) {
				$release = '1.2';
				if (!facileFormsInstaller::testdb('#__facileforms_forms', 'class1')) {
					$release = '1.1';
					if (!facileFormsInstaller::testdb('#__facileforms_forms', 'description')) {
						$release = '1.0';
						if (!facileFormsInstaller::testdb('#__facileforms_forms', 'id'))
							$release = ''; // was not yet installed
					} // if
				} // if
			} // if
		} // if

		HTML_facileFormsInstaller::step2($option, $release);
	} // step2

	function step3($option)
	{
		global $ff_admpath,$mainframe, $ff_config, $errors, $errmode;
		
		$instmode = intval(JRequest::getVar( 'ff_installmode', 1));
		$instsamp = intval(JRequest::getVar( 'ff_instsamples', 0));
                $instqmsamp = intval(JRequest::getVar( 'ff_instqmsamples', 0));
		$instold  = intval(JRequest::getVar( 'ff_instoldlib', 0));

		// list of sample forms before 1.4
		$sampleforms =
			"'SampleContactForm',".
			"'SampleCountrySelect',".
			"'SamplePaneNavigation',".
			"'SamplePizzaShop',".
			"'SampleEmbeddedCode',".
			"'SamplePieceApp',".
			"'RnrContestRegist',".
			"'RnrContestList'";

		// list of sample menus before 1.4
		$samplemenus =
			"'Facile Forms Sample Menu'";

		// list of sample scripts before 1.4
		$stdscripts =
			"'ff_anychecked',".
			"'ff_checked',".
			"'ff_countQuerySelections',".
			"'ff_getfocus',".
			"'ff_getQuerySelections',".
			"'ff_getQuerySelectedRows',".
			"'ff_integer_or_empty',".
			"'ff_nextpage',".
			"'ff_page1',".
			"'ff_page2',".
			"'ff_page3',".
			"'ff_previouspage',".
			"'ff_showaction',".
			"'ff_showelementinit',".
			"'ff_showforminit',".
			"'ff_showsubmitted',".
			"'ff_showvalidation',".
			"'ff_submittedhome',".
			"'ff_unchecked',".
			"'ff_validate_form',".
			"'ff_validate_nextpage',".
			"'ff_validate_page',".
			"'ff_validate_submit',".
			"'ff_validemail',".
			"'ff_valuenotempty'";

		// list of sample pieces before 1.4
		$stdpieces =
			"'ff_InitUtilities',".
			"'ff_SubmitUtilities',".
			"'ff_redirectParent',".
			"'ff_getPageByName',".
			"'ff_setSelected',".
			"'ff_setChecked',".
			"'ff_setValue'";

		// database update
		$sql_path = $ff_admpath.'/sql';
		$errors = array();
		$errmode = 'log';
		switch ($instmode) {
			case 0: // New install: create tables
				facileFormsInstaller::exec_sql($sql_path.'/create.sql');
				$ff_config = new facileFormsConfig();
				break;
			case 1: // Reinstall: no db change
				break;
			case 2: // Upgrade from 1.0.x
				facileFormsInstaller::exec_sql($sql_path.'/upgrade_1.1.sql');
			case 3: // Upgrade from 1.1.x
				facileFormsInstaller::exec_sql($sql_path.'/upgrade_1.2.sql');
			case 4: // Upgrade from 1.2.x
				facileFormsInstaller::exec_sql($sql_path.'/upgrade_1.3.sql');
			case 5: // Upgrade from 1.3.x
				facileFormsInstaller::exec_sql($sql_path.'/upgrade_1.4.sql');
			default:
				break;
		} // switch

		// get xref to old samples
		$oldscripts = NULL;
		$oldpieces = NULL;
		if ($instmode>=2 && $instmode<=5) { // upgrades until 1.4
			// get old xref tables
			$oldscripts = _ff_select("select id, name from #__facileforms_scripts where name in ($stdscripts)");
			$oldpieces = _ff_select("select id, name from #__facileforms_pieces where name in ($stdpieces)");
			// drop old std scripts & pieces
			_ff_query("delete from #__facileforms_scripts where name in ($stdscripts)");
			_ff_query("delete from #__facileforms_pieces where name in ($stdpieces)");
		} // if

		// call installer to load new std libraries
		$xmlfile = $ff_admpath.'/packages/stdlib.english.xml';

		$inst = new ff_importPackage();
		$inst->import($xmlfile);

		if (intval($instold) == 1) {
			// call installer to load backward compatibility library
			$xmlfile = $ff_admpath.'/packages/oldlib.english.xml';
			$inst->import($xmlfile);
		} // if

		
		if (intval($instsamp) == 1) {
			if ($instmode>=2 && $instmode<=5) { // upgrades until 1.4
				// drop old sample forms
				$rows = _ff_select("select id from #__facileforms_forms where name in ($sampleforms)");
				if (count($rows)) foreach ($rows as $row) {
					_ff_query("delete from #__facileforms_elements where form = $row->id");
					_ff_query("delete from #__facileforms_forms where id = $row->id");
				} // if

				// drop old sample menus
				$rows = _ff_select("select id from #__facileforms_compmenus where title in ($samplemenus)");
				if (count($rows)) foreach ($rows as $row)
					_ff_query("delete from #__facileforms_compmenus where id=$row->id or parent=$row->id");
			} // if

			// call installer to load new samples
			$xmlfile = $ff_admpath.'/packages/samples.english.xml';
			$inst->import($xmlfile);
		} // if

                if (intval($instqmsamp) == 1) {
                    $xmlfile = $ff_admpath.'/packages/quickmode.samples.xml';
                    if(file_exists($xmlfile)){
                        $inst->import($xmlfile);
                    }
                }

		// relink items refering to old scripts and pieces
		relinkScripts($oldscripts);
		relinkPieces($oldpieces);

		// adjust component menu
                jimport('joomla.version');
                $version = new JVersion();

                if(version_compare($version->getShortVersion(), '1.6', '>=')){

                    updateComponentMenus();

                    _ff_query(
                            "update #__menu set `alias` = 'BreezingForms' ".
                            "where `link`='index.php?option=com_breezingforms'"
                    );
                    _ff_query(
                            "update #__menu set `alias` = 'Manage Records', img='components/com_breezingforms/images/js/ThemeOffice/checkin.png' ".
                            "where `link`='index.php?option=com_breezingforms&act=managerecs'"
                    );
                    _ff_query(
                            "update #__menu set `alias` = 'Manage Backend Menus', img='components/com_breezingforms/images/js/ThemeOffice/mainmenu.png' ".
                            "where `link`='index.php?option=com_breezingforms&act=managemenus'"
                    );
                    _ff_query(
                            "update #__menu set `alias` = 'Manage Forms', img='components/com_breezingforms/images/js/ThemeOffice/content.png' ".
                            "where `link`='index.php?option=com_breezingforms&act=manageforms'"
                    );
                    _ff_query(
                            "update #__menu set `alias` = 'Manage Scripts', img='components/com_breezingforms/images/js/ThemeOffice/controlpanel.png' ".
                            "where `link`='index.php?option=com_breezingforms&act=managescripts'"
                    );
                    _ff_query(
                            "update #__menu set `alias` = 'Manage Pieces', img='components/com_breezingforms/images/js/ThemeOffice/controlpanel.png' ".
                            "where `link`='index.php?option=com_breezingforms&act=managepieces'"
                    );
                    _ff_query(
                            "update #__menu set `alias` = 'Integrator', img='components/com_breezingforms/images/js/ThemeOffice/controlpanel.png' ".
                            "where `link`='index.php?option=com_breezingforms&act=integrate'"
                    );
                    _ff_query(
                            "update #__menu set `alias` = 'Configuration', img='components/com_breezingforms/images/js/ThemeOffice/config.png' ".
                            "where `link`='index.php?option=com_breezingforms&act=configuration'"
                    );
                } else {
                    
                    _ff_query("update #__components set admin_menu_link='' where `option`='com_breezingforms' and parent=0");
                    updateComponentMenus();

                    // assign nice icons to facileforms
                    _ff_query(
                            "update #__components set admin_menu_img='components/com_breezingforms/images/js/ThemeOffice/checkin.png' ".
                            "where admin_menu_link='option=com_breezingforms&act=managerecs'"
                    );
                    _ff_query(
                            "update #__components set admin_menu_img='components/com_breezingforms/images/js/ThemeOffice/mainmenu.png' ".
                            "where admin_menu_link='option=com_breezingforms&act=managemenus'"
                    );
                    _ff_query(
                            "update #__components set admin_menu_img='components/com_breezingforms/images/js/ThemeOffice/content.png' ".
                            "where admin_menu_link='option=com_breezingforms&act=manageforms'"
                    );
                    _ff_query(
                            "update #__components set admin_menu_img='components/com_breezingforms/images/js/ThemeOffice/controlpanel.png' ".
                            "where admin_menu_link='option=com_breezingforms&act=managescripts'"
                    );
                    _ff_query(
                            "update #__components set admin_menu_img='components/com_breezingforms/images/js/ThemeOffice/controlpanel.png' ".
                            "where admin_menu_link='option=com_breezingforms&act=managepieces'"
                    );
                    _ff_query(
                            "update #__components set admin_menu_img='components/com_breezingforms/images/js/ThemeOffice/config.png' ".
                            "where admin_menu_link='option=com_breezingforms&act=integrate'"
                    );
                    _ff_query(
                            "update #__components set admin_menu_img='components/com_breezingforms/images/js/ThemeOffice/config.png' ".
                            "where admin_menu_link='option=com_breezingforms&act=configuration'"
                    );

                    // fix broken menuitems
                    $id = _ff_selectValue(
                            "select min(id) from #__components ".
                             "where `parent`=0 and `option`='com_breezingforms'"
                    );
                    if ($id)
                            _ff_query(
                                    "update #__menu ".
                                       "set componentid=$id, link='index.php?option=com_breezingforms' ".
                                     "where type='components' and params like 'ff_com_name=%'"
                            );
                }
                
		if ($ff_config->images == '{mossite}/administrator/components/com_breezingforms/images')
			$ff_config->images = '{mossite}/components/com_breezingforms/images';
		if ($ff_config->uploads == '{mospath}/administrator/components/com_breezingforms/uploads')
			$ff_config->uploads = '{mospath}/components/com_breezingforms/uploads';
		$ff_config->store();
		HTML_facileFormsInstaller::step3($option, $errors);
	} // step3

} //class facileFormsInstaller
?>