<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: controller.plugin.php 1218 2010-11-03 17:35:52Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : plugin controller
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBController_plugin {
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
	/**
	* Saves the CB plugin after an edit form submit
	*/
	function savePlugin( $option, $task ) {
		global $_CB_framework, $_CB_database, $_PLUGINS;

		if ( $task == 'showPlugins' ) {
			cbRedirect( $_CB_framework->backendUrl( "index.php?option=$option&task=showPlugins" ));
			return;
		}
	
		$this->_importNeeded();
		$this->_importNeededSave();

		$action = cbGetParam( $_REQUEST, 'action' );
	
		if ( ! $action ) {
			$this->savePluginParams( $option, $task );
		} else {
			$uid	= cbGetParam( $_REQUEST, 'cid' );
			$row 	= new moscomprofilerPlugin($_CB_database);
			if ( $uid ) {
				$row->load( (int) $uid );
			}
	
			// get params values
			if ($row->type !== "language") {
				$_PLUGINS->loadPluginGroup($row->type,array( (int) $row->id), 0);
			}
			// xml file for plugin
			$element	=&	$_PLUGINS->loadPluginXML( 'action', $action, $row->id );
	
			$_REQUEST['task'] = 'editPlugin';		// so that the actionPath matches
			$params		=	new cbParamsBase( $row->params );
			$this->editPluginView( $row, $option, 'editPlugin', $uid, $action, $element, $task, $params );
		}
	}
	
	/**
	* Saves the CB plugin params after an edit form submit
	*/
	function savePluginParams( $option, $task ) {
		global $_CB_framework, $_CB_database, $_POST;
	
		if ( isset( $_POST['params'] ) ) {
		 	$_POST['params']	=	cbParamsEditorController::getRawParamsMagicgpcEscaped( $_POST['params'] );
		} else {
			$_POST['params']	=	null;
		}
	
		$row = new moscomprofilerPlugin( $_CB_database );
		if (!$row->bind( $_POST )) {
			echo "<script type=\"text/javascript\"> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if (!$row->check()) {
			echo "<script type=\"text/javascript\"> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if (!$row->store()) {
			echo "<script type=\"text/javascript\"> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$row->checkin();
	
		$row->updateOrder( "type='".$_CB_database->getEscaped($row->type)."' AND ordering > -10000 AND ordering < 10000 " );
	
		switch ( $task ) {
			case 'applyPlugin':
				$msg = sprintf(CBTxt::T('Successfully Saved changes to Plugin: %s'), $row->name);
				cbRedirect( $_CB_framework->backendUrl( "index.php?option=$option&task=editPlugin&cid=$row->id" ), $msg );
	
			case 'savePlugin':
			default:
				$msg = sprintf(CBTxt::T('Successfully Saved Plugin: %s'), $row->name);
				cbRedirect( $_CB_framework->backendUrl( "index.php?option=$option&task=showPlugins" ), $msg );
				break;
		}
	}
	
	/**
	* Compiles information to add or edit a plugin
	* @param string The current GET/POST option
	* @param integer The unique id of the record to edit
	*/
	function editPlugin( $option, $task, $uid) {
		global $_CB_database, $_CB_framework, $_PLUGINS, $_POST;
	
		$this->_importNeeded();

		$action	= cbGetParam( $_REQUEST, 'action', null );
	
		if ( ! $uid ) {
			$uid = cbGetParam( $_POST, 'id' );
		}
	
		$row 	= new moscomprofilerPlugin($_CB_database);
		if ( $uid ) {
			// load the row from the db table
			$row->load( (int) $uid );
		}
		// fail if checked out not by 'me'
		if ($row->checked_out && $row->checked_out <> $_CB_framework->myId() ) {
			echo "<script type=\"text/javascript\">alert('" . addslashes( sprintf(CBTxt::T('The plugin %s is currently being edited by another administrator'), $row->name) ) . "'); document.location.href='" . $_CB_framework->backendUrl( "index.php?option=$option" ) . "'</script>\n";
			exit(0);
		}
	
		// get params values
		if ( $row->type !== "language" && $row->id ) {
			$_PLUGINS->loadPluginGroup($row->type,array( (int) $row->id), 0);
		}
	
		// xml file for plugin
		$element = null;
		if ($uid) {
			$element	=&	$_PLUGINS->loadPluginXML( 'action', $action, $row->id );
		}
	
		if ( $element && ( $action === null ) ) {
			$adminActionsModel	=&	$element->getChildByNameAttr( 'actions', 'ui', 'admin' );
			if ( $adminActionsModel ) {
				$defaultAction	=&	$adminActionsModel->getChildByNameAttr( 'action', 'name', 'default' );
				$actionRequest	=	$defaultAction->attributes( 'request' );
				$actionAction	=	$defaultAction->attributes( 'action' );
				if ( ( $actionRequest === '' ) && ( $actionRequest === '' ) ) {
					$action = $actionAction;
				}
			}
		}
		if ( $element ) {
			$description		=&	$element->getChildByNameAttributes( 'description' );
		} else {
			$description		=	null;
		}
		if ( $description ) {
			$row->description	=	$description->data();
		} else {
			$row->description	=	'-';
		}
		if ( $action === null ) {
	
			$params				=	new cbParamsEditorController( $row->params, $element, $element, $row );
			$options			=	array( 'option' => $option, 'task' => $task, 'pluginid' => $uid, 'tabid' => null );
			$params->setOptions( $options );
			$this->editPluginSettingsParams( $row, $option, $task, $uid, $element, $params, $options );
	
		} else {
			$params				=	new cbParamsBase( $row->params );
			$this->editPluginView( $row, $option, $task, $uid, $action, $element, 'editPlugin', $params );
	
		}
	}
	
	function editPluginSettingsParams( &$row, $option, $task, $uid, &$element, &$params, &$options ) {
		global $_CB_database, $_CB_framework;
	
		$lists 	= array();
	
		// get list of groups
		if ($row->access == 99 || $row->client_id == 1) {
			$lists['access'] = CBTxt::T('Administrator') . '<input type="hidden" name="access" value="99" />';
		} else {
			// build the html select list for the group access
			$accessTree		=	$_CB_framework->acl->get_access_children_tree();
			$lists['access'] = moscomprofilerHTML::selectList( $accessTree, 'access', 'class="inputbox" size="3"', 'value', 'text', intval( $row->access ), 2 );
		}
	
		if ($uid) {
			$row->checkout( $_CB_framework->myId() );
	
			if ( $row->ordering > -10000 && $row->ordering < 10000 ) {
				// build the html select list for ordering
				$query = "SELECT ordering AS value, name AS text"
				. "\n FROM #__comprofiler_plugin"
				. "\n WHERE type='" . $_CB_database->getEscaped( $row->type ) . "'"
				. "\n AND published > 0"
				. "\n AND ordering > -10000"
				. "\n AND ordering < 10000"
				. "\n ORDER BY ordering"
				;
				$order = $this->_cbGetOrderingList( $query );
				$lists['ordering'] = moscomprofilerHTML::selectList( $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval( $row->ordering ), 2 );
			} else {
				$lists['ordering'] = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />' . CBTxt::T('This plugin cannot be reordered');
			}
			$lists['type'] = '<input type="hidden" name="type" value="'. $row->type .'" />'. $row->type;
	
			if ($element && $element->name() == 'cbinstall' && $element->attributes( 'type' ) == 'plugin' ) {
				$description =& $element->getElementByPath( 'description' );
				$row->description = ( $description ) ? trim( $description->data() ) : '';
			}
	
		} else {
			$row->folder 		= '';
			$row->ordering 		= 999;
			$row->published 	= 1;
			$row->description 	= '';
	
			$folders			= cbReadDirectory( $_CB_framework->getCfg('absolute_path') . '/components/com_comprofiler/plugin/' );
			$folders2			= array();
			foreach ($folders as $folder) {
			    if (is_dir( $_CB_framework->getCfg('absolute_path') . '/components/com_comprofiler/plugin/' . $folder ) && ( $folder <> 'CVS' ) ) {
			        $folders2[] = moscomprofilerHTML::makeOption( $folder );
				}
			}
			$lists['type']		= moscomprofilerHTML::selectList( $folders2, 'type', 'class="inputbox" size="1"', 'value', 'text', null, 2 );
			$lists['ordering']	= '<input type="hidden" name="ordering" value="'. $row->ordering .'" />' . CBTxt::T('New items default to the last place. Ordering can be changed after this item is saved.') ;
		}
	
		$Yesoptions = array();
		$Yesoptions[] = moscomprofilerHTML::makeOption( '1', _UE_YES );
		if ( ( $row->type == 'language' ) || ( $row->id == 1 ) ) {
			$row->published		=	1;
		} else {
			$Yesoptions[]		=	moscomprofilerHTML::makeOption( '0', _UE_NO );
		}
		$lists['published'] = moscomprofilerHTML::radioList( $Yesoptions, 'published', 'class="inputbox"', 'value', 'text', $row->published, 2 );
	
		$pluginView				=	_CBloadView( 'plugin' );
		$pluginView->editPlugin( $row, $lists, $params, $options );
	}
	
	function editPluginView( &$row, $option, $task, $uid, $action, &$element, $mode, &$pluginParams ) {
		global $_CB_database, $_PLUGINS;
	
			if ( ! $row->id ) {
			echo CBTxt::T('Plugin id not found.');
			return null;;
		}
		if ( ! $element ) {
			echo CBTxt::T('No plugin XML found.');
			return null;
		}
	
		$adminHandlerModel	=& $element->getChildByNameAttr( 'handler', 'ui', 'admin' );
		if ( ! $adminHandlerModel ) {
			echo CBTxt::T('No admin handler defined in XML');
			return null;
		}
		$class	=	$adminHandlerModel->attributes( 'class' );
		if ( $class ) {
			if ( ! class_exists( $class ) ) {
				echo sprintf(CBTxt::T('Admin handler class %s does not exist.'), $class);
				return null;
			}
	
			$handler	=	new $class( $_CB_database );
			return $handler->editPluginView( $row, $option, $task, $uid, $action, $element, $mode, $pluginParams );
		} else {
			// new method in CB 1.2.3:
			$args		=	array( &$row, $option, $task, $uid, $action, &$element, $mode, &$pluginParams );
			return $_PLUGINS->call( $row->id, 'editPluginView', 'get' . $row->element . 'Tab', $args, null, true );
		}
	}
	
	/**
	* Compiles information to add or edit a plugin
	* @param string The current GET/POST option
	* @param integer The unique id of the record to edit
	*/
	function pluginMenu( $option, $uid) {
		global $_CB_database, $_CB_framework, $_PLUGINS, $_GET;
	
		if ( ! $uid ) {
			echo "<script type=\"text/javascript\">alert('" . addslashes( CBTxt::T('No plugin selected') ) . "'); document.location.href='" . $_CB_framework->backendUrl( "index.php?option=$option" ) . "'</script>\n";
			exit(0);
		}

		$this->_importNeeded();

		$row 	= new moscomprofilerPlugin($_CB_database);
	
		// load the row from the db table
		$row->load( (int) $uid );
	
		// fail if checked out not by 'me'
		if ($row->checked_out && $row->checked_out <> $_CB_framework->myId() ) {
			echo "<script type=\"text/javascript\">alert('" . addslashes( sprintf(CBTxt::T('The plugin %s is currently being edited by another administrator'), $row->name) ) . "'); document.location.href='" . $_CB_framework->backendUrl( "index.php?option=$option" ) . "'</script>\n";
			exit(0);
		}
		$basepath	=	$_CB_framework->getCfg('absolute_path') . '/' . $_PLUGINS->getPluginRelPath( $row ) . '/';
		$phpfile = $basepath . "admin." . $row->element . '.php';
	
		// see if there is an xml install file, must be same name as element
		if (file_exists( $phpfile )) {
		// get params values
			if ( $row->type !== "language" ) {
				$_PLUGINS->loadPluginGroup($row->type,array( (int) $row->id), 0);
			}
			$menu		=	cbGetParam( $_REQUEST, 'menu' );
			$element	=&	$_PLUGINS->loadPluginXML( 'menu', $menu, $row->id );		// xml file for plugin
	
			$params		=	new cbParamsEditorController( $row->params, $element, $element, $row );
	
			if ( cbGetParam( $_GET, 'no_html', 0 ) != 1 ) {
				outputCbTemplate( 2 );
				outputCbJs( 2 );
			    initToolTip( 2 );
			}
	
			require_once( $phpfile );
			$classname = $row->element . "Admin";
			$adminClass = new $classname();
			echo $adminClass->menu( $row, $menu, $params );
		} else {
			echo "<script type=\"text/javascript\">alert('" . addslashes( sprintf(CBTxt::T('The plugin %s has no administrator file %s'), $row->name, $phpfile . '-' .$uid ) ) . "'); document.location.href='" . $_CB_framework->backendUrl( "index.php?option=$option" ) . "'</script>\n";
			exit(0);
		}
	}
	/**
	* @param  string   $sql        SQL with ordering As value and 'name field' AS text
	* @param  int      $chop       The length of the truncated headline
	* @param  boolean  $translate  translate to CB language
	* @return array                of makeOption
	* @access private
	*/
	function _cbGetOrderingList( $sql, $chop = 30, $translate = true ) {
		global $_CB_database;
	
		$order				=	array();
		$_CB_database->setQuery( $sql );
		$orders				= $_CB_database->loadObjectList();
		if ( $_CB_database->getErrorNum() ) {
			echo $_CB_database->stderr();
			return false;
		}
		if ( count( $orders ) == 0 ) {
			$order[]	=	moscomprofilerHTML::makeOption( 1, CBTxt::T('first') );
			return $order;
		}
		$order[]			=	moscomprofilerHTML::makeOption( 0, '0 ' . CBTxt::T('first') );
		for ( $i=0, $n = count( $orders ); $i < $n; $i++ ) {
			if ( $translate ) {
				$text		=	getLangDefinition( $orders[$i]->text );
			} else {
				$text		=	$orders[$i]->text;
			}
	        if ( strlen( $text ) > $chop ) {
	        	$text		=	substr( $text, 0, $chop ) . '...';
	        }
	
			$order[]		=	moscomprofilerHTML::makeOption( $orders[$i]->value, $orders[$i]->value . ' (' . $text . ')' );
		}
		if ( isset( $orders[$i - 1] ) ) {
			$order[]		=	moscomprofilerHTML::makeOption( $orders[$i - 1]->value + 1, ( $orders[$i - 1]->value + 1 ) . ' ' . CBTxt::T('last') );
		}
		return $order;
	}

}	// class CBController_plugin

?>