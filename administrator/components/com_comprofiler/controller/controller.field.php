<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: controller.field.php 1203 2010-10-12 13:12:26Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : field controller
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBController_field {
	function _importNeeded() {
		cbimport( 'cb.tabs' );

		if ( class_exists( 'JFactory' ) ) {	// Joomla 1.5 : for string WARNREG_EMAIL_INUSE used in error js popup.
			$lang			=&	JFactory::getLanguage();
			$lang->load( "com_users" );
		}
		cbimport( 'cb.params' );
		
	}
	function _importNeededSave() {
		// backend only:
		cbimport( 'cb.adminfilesystem' );
		cbimport( 'cb.imgtoolbox' );
	}
	function editField( $fid = 0, $option = 'com_comprofiler', $task = 'editField' ) {
		global $_CB_database, $_CB_framework, $_PLUGINS;
	
		$this->_importNeeded();
	
		$row = new moscomprofilerFields( $_CB_database );
	
		$paramsEditorHtml			=	null;
	
		if ( $fid == 0 ) {
			// default values for new types:
			$row->type				=	'text';
			$row->tabid				=	11;		// contact info by default
			$row->profile			=	1;
			$row->registration		=	1;
			$row->displaytitle		=	1;
			$row->published			=	1;
			$paramsEditorHtml		=	array( array( 'title' => CBTxt::T('Parameters'), 'content' => "<strong>" . CBTxt::T('To see Parameters, first save new field') . "</strong>" ) );
		} else {
			// load the row from the db table
			$row->load( (int) $fid );
	
			$fieldTab				=	new moscomprofilerTabs( $_CB_database );
			// load the row from the db table
			$fieldTab->load( (int) $row->tabid );
	
			if ( ! in_array( $fieldTab->useraccessgroupid, getChildGIDS( userGID( $_CB_framework->myId() ) ) ) ) {
				echo "<script type=\"text/javascript\"> alert('" . addslashes( CBTxt::T('Unauthorized Access') ) . "'); window.history.go(-1);</script>\n";
				exit;
			}
		}
	
		$_PLUGINS->loadPluginGroup( 'user' );
	
		if ( $task == 'reloadField' ) {
			if ( ! $this->_prov_bind_CB_field( $row, $fid ) ) {
				echo "<script type=\"text/javascript\"> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
				exit();
			}
		}
	
		// params:
		$paramsEditorHtml			=	array();
		$options					=	array( 'option' => $option, 'task' => $task, 'cid' => $row->fieldid );
	
		// field-specific own parameters:
		$fieldHandler				=	new cbFieldHandler();
		$fieldOwnParamsEditHtml		=	$fieldHandler->drawParamsEditor( $row, $options );
		if ( $fieldOwnParamsEditHtml ) {
			$paramsEditorHtml[]		=	array( 'title' => CBTxt::T('Field-specific Parameters'), 'content' => $fieldOwnParamsEditHtml );
		}
	
		// additional non-specific other parameters:
		$fieldsParamsPlugins		=	$_PLUGINS->getUserFieldParamsPluginIds();
		foreach ($fieldsParamsPlugins as $pluginId => $fieldParamHandlerClassName ) {
			$fieldParamHandler		=	new $fieldParamHandlerClassName( $pluginId, $row );			// cbFieldParamsHandler();
			$addParamsHtml			=	$fieldParamHandler->drawParamsEditor( $options );
			if ( $addParamsHtml ) {
				$addParamsTitle		=	$fieldParamHandler->getFieldsParamsLabel();
				$paramsEditorHtml[]	=	array( 'title' => $addParamsTitle, 'content' => $addParamsHtml );
			}
		}
	
		$where						=	array();
		$where[]					=	"`fields` = 1";
		$where[]	 				=	"useraccessgroupid IN (".implode(',',getChildGIDS(userGID( $_CB_framework->myId() ))).")";
		$_CB_database->setQuery("SELECT tabid, title FROM #__comprofiler_tabs WHERE " . implode( ' AND ', $where ) . " ORDER BY ordering");
		$tabs						=	$_CB_database->loadObjectList();
		$lists						=	array();
		$tablist					=	array();
	
		for ($i=0, $n=count( $tabs ); $i < $n; $i++) {
			$tab					=&	$tabs[$i];
		   	$tablist[]				=	moscomprofilerHTML::makeOption( $tab->tabid, getLangDefinition($tab->title) );
		}
	
		$lists['tabs']				=	moscomprofilerHTML::selectList( $tablist, 'tabid', 'class="inputbox" size="1" mosReq=1 mosLabel="' . htmlspecialchars( CBTxt::T('Tab') ) . '"', 'value', 'text', $row->tabid, 2 );
	
	
		$types						=	array();
	
		if ( $fid == 0 ) {
			$typeHandlers			=	array();
	
			$registeredTypes		=	$_PLUGINS->getUserFieldTypes();
			foreach ( $registeredTypes as $typ ) {
				$typeHandlers[$typ]	=	new cbFieldHandler();
				$tmpField			=	new moscomprofilerFields( $_CB_database );
				$tmpField->type		=	$typ;
				$typLabel			=	$typeHandlers[$typ]->getFieldTypeLabel( $tmpField );
				if ( $typLabel ) {
					$types[]		=	moscomprofilerHTML::makeOption( $typ, $typLabel );
				}
			}
		} else {
			$types[] = moscomprofilerHTML::makeOption( $row->type, $fieldHandler->getFieldTypeLabel( $row, false ) );
		}
	
		$webaddrtypes = array();
	
		$webaddrtypes[] = moscomprofilerHTML::makeOption( '0', CBTxt::T('URL only') );
		$webaddrtypes[] = moscomprofilerHTML::makeOption( '2', CBTxt::T('Hypertext and URL') );
	
		$profiles = array();
	
		$profiles[] = moscomprofilerHTML::makeOption( '0', CBTxt::T('No') );
		$profiles[] = moscomprofilerHTML::makeOption( '1', CBTxt::T('Yes: on 1 Line') );
		$profiles[] = moscomprofilerHTML::makeOption( '2', CBTxt::T('Yes: on 2 Lines') );
	
		$fvalues = $_CB_database->setQuery( "SELECT fieldtitle "
			. "\n FROM #__comprofiler_field_values"
			. "\n WHERE fieldid=" . (int) $fid
			. "\n ORDER BY ordering" );
		$fvalues = $_CB_database->loadObjectList();
	
		$lists['webaddresstypes'] = moscomprofilerHTML::selectList( $webaddrtypes, 'webaddresstypes', 'class="inputbox" size="1"', 'value', 'text', $row->rows, 2 );
	
		$lists['type'] = moscomprofilerHTML::selectList( $types, 'type', 'class="inputbox" size="1"', 'value', 'text', $row->type, 2 );
	
		$lists['required'] = moscomprofilerHTML::yesnoSelectList( 'required', 'class="inputbox" size="1"', ( $row->required === null ? 0 : $row->required ) );
	
		$lists['published'] = moscomprofilerHTML::yesnoSelectList( 'published', 'class="inputbox" size="1"', $row->published );
	
		$lists['readonly'] = moscomprofilerHTML::yesnoSelectList( 'readonly', 'class="inputbox" size="1"', ( $row->readonly === null ? 0 : $row->readonly ) );
	
		$lists['profile'] = moscomprofilerHTML::selectList( $profiles, 'profile', 'class="inputbox" size="1"', 'value', 'text', $row->profile, 2 );
	
		$lists['displaytitle'] = moscomprofilerHTML::yesnoSelectList( 'displaytitle', 'class="inputbox" size="1"', $row->displaytitle );
	
		if ( $row->tablecolumns != '' && ! in_array( $row->type, array( 'password', 'userparams' ) ) ) {
			$lists['searchable'] = moscomprofilerHTML::yesnoSelectList( 'searchable', 'class="inputbox" size="1"', $row->searchable );
		} else {
			$lists['searchable'] = _UE_NO . '<input type="hidden" name="searchable" value="0" />';
		}
	
		$lists['registration'] = moscomprofilerHTML::yesnoSelectList( 'registration', 'class="inputbox" size="1"', $row->registration );
	
		$pluginView					=	_CBloadView( 'field' );
		$pluginView->editfield( $row, $lists, $fvalues, $option, $paramsEditorHtml );
	}
	/**
	 * A more extensive bind method for fields ( 	//TBD: should got to the moscomprofilerFields class).
	 *
	 * @param  moscomprofilerFields  $row
	 * @param  int                   $fid
	 * @return boolean
	 */
	function _prov_bind_CB_field( &$row, $fid ) {
		global $_PLUGINS, $_POST;
	
		if ( isset( $_POST['cb_default'] ) ) {
			$_POST['default']		=	$_POST['cb_default'];			// go around WysywigPro3 bug
			unset( $_POST['cb_default'] );
		}
		$bindSuccess				=	$row->bind( $_POST );
	
		if ( $bindSuccess ) {
			// auto-fix description translation in case the editor adds <p> around it:
			$row->description		=	cleanEditorsTranslationJunk( trim( $row->description ) );
	
			$pluginid				=	$_PLUGINS->getUserFieldPluginId( $row->type );
			if ( $pluginid != 1 ) {
				$row->pluginid		=	$pluginid;		// not core plugin for now as we don't allow changing field types
			}
	
			if ( ! isset( $_POST['params'] ) ) {
				$_POST['params']	=	null;
			}
			if ( $fid && $row->pluginid ) {
				// handles field-specific parameters:
				$fieldHandler		=	new cbFieldHandler();
				$row->params		=	$fieldHandler->getRawParamsRaw( $row, $_POST['params'] );
			} else {
				// if not a plugin-specific field, handle parameters in standard way:
			 	$row->params		=	stripslashes( cbParamsEditorController::getRawParamsUnescaped( $_POST['params'], true ) );
			}
		}
		return $bindSuccess;
	}
	
	function saveField( $option, $task ) {
		global $_CB_database, $_CB_framework, $_POST, $_PLUGINS;
	
		if ( ( $task == 'showField' ) || ! ( isset( $_POST['oldtabid'] ) && isset( $_POST['tabid'] ) && isset( $_POST['fieldid'] ) ) ) {
			cbRedirect( $_CB_framework->backendUrl( "index.php?option=$option&task=$task" ) );
			return;
		}
	
		$this->_importNeeded();
		$this->_importNeededSave();
	
		$fid					=	(int) $_POST['fieldid'];
	
		$row					=	new moscomprofilerFields( $_CB_database );
	
		if ( $fid ) {
			// load the row from the db table
			if ( ! $row->load( (int) $fid ) ) {
				echo "<script type=\"text/javascript\"> alert('" . addslashes( CBTxt::T('Innexistant field') ) . "'); window.history.go(-1);</script>\n";
				exit;
			}
	
			$fieldTab			=	new moscomprofilerTabs( $_CB_database );
			// load the row from the db table
			$fieldTab->load( (int) $row->tabid );
	
			if ( ! in_array( $fieldTab->useraccessgroupid, getChildGIDS( userGID( $_CB_framework->myId() ) ) ) ) {
				echo "<script type=\"text/javascript\"> alert('" . addslashes( CBTxt::T('Unauthorized Access') ) ."'); window.history.go(-1);</script>\n";
				exit;
			}
		}
	
		$_PLUGINS->loadPluginGroup( 'user' );
	
		if ( ! $this->_prov_bind_CB_field( $row, $fid ) ) {
			echo "<script type=\"text/javascript\"> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
			exit();
		}
	
		// in case the above changed perms.... really ?
		$fieldTab				=	new moscomprofilerTabs( $_CB_database );
		$fieldTab->load( (int) $row->tabid );
		if ( ! in_array( $fieldTab->useraccessgroupid, getChildGIDS( userGID( $_CB_framework->myId() ) ) ) ) {
			echo "<script type=\"text/javascript\"> alert('" . addslashes( CBTxt::T('Unauthorized Access') ) . "'); window.history.go(-1);</script>\n";
			exit;
		}
	
		if ($row->type == 'webaddress') {
			$row->rows			=	$_POST['webaddresstypes'];
			if ( !(($row->rows == 0) || ($row->rows == 2)) ) {
				$row->rows = 0;
			}
		}
		if ( $_POST['oldtabid'] != $_POST['tabid'] ) {
			if ( $_POST['oldtabid'] !== '' ) {
				//Re-order old tab
				$sql			=	"UPDATE #__comprofiler_fields SET ordering = ordering-1 WHERE ordering > ".(int) $_POST['ordering']." AND tabid = ".(int) $_POST['oldtabid'];
				$_CB_database->setQuery($sql);
				$_CB_database->query();
			}
			//Select Last Order in New Tab
			$sql				=	"SELECT MAX(ordering) FROM #__comprofiler_fields WHERE tabid=".(int) $_POST['tabid'];
			$_CB_database->SetQuery($sql);
			$max				=	$_CB_database->LoadResult();
			$row->ordering		=	max( $max + 1, 1 );
		}
	
		if ( cbStartOfStringMatch( $row->name, 'cb_' ) ) {
			$row->name			=	str_replace(" ", "", strtolower($row->name));
		}
		if ( ! $row->check() ) {
			echo "<script type=\"text/javascript\"> alert('".$row->getError()."'); window.history.go(-2); </script>\n";
			exit();
		}
		if ( ! $row->store( (int) $fid ) ) {
			echo "<script type=\"text/javascript\"> alert('".$row->getError()."'); window.history.go(-2); </script>\n";
			exit();
		}
		$fieldNames				=	$_POST['vNames'];
		$j						=	1;
		if( $row->fieldid > 0 ) {
			$_CB_database->setQuery( "DELETE FROM #__comprofiler_field_values"
				. " WHERE fieldid = " . (int) $row->fieldid );
			if( $_CB_database->query() === false ) {
				echo $_CB_database->getErrorMsg();
			}
		} else {
			$_CB_database->setQuery( "SELECT MAX(fieldid) FROM #__comprofiler_fields");
			$maxID				=	$_CB_database->loadResult();
			$row->fieldid		=	$maxID;
			echo $_CB_database->getErrorMsg();
		}
		//for($i=0, $n=count( $fieldNames ); $i < $n; $i++) {
		foreach ($fieldNames as $fieldName) {
			if(trim($fieldName)!=null || trim($fieldName)!='') {
				$_CB_database->setQuery( "INSERT INTO #__comprofiler_field_values (fieldid,fieldtitle,ordering)"
					. " VALUES( " . (int) $row->fieldid . ",'".cbGetEscaped(trim($fieldName))."', " . (int) $j . ")"
				);
				if ( $_CB_database->query() === false ) {
					echo $_CB_database->getErrorMsg();
				}
				$j++;
			}
	
		}
	
		switch ( $task ) {
			case 'applyField':
				$msg = CBTxt::T('Successfully Saved changes to Field') . ': '. $row->name;
				cbRedirect( $_CB_framework->backendUrl( "index.php?option=$option&task=editField&cid=$row->fieldid" ), $msg );
				break;
			case 'saveField':
			default:
				$msg = CBTxt::T('Successfully Saved Field') . ': '. $row->name;
				cbRedirect( $_CB_framework->backendUrl( "index.php?option=$option&task=showField" ), $msg );
				break;
		}
	}

}	// class CBController_field

?>