<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: admin.comprofiler.controller.php 1430 2011-02-10 09:48:20Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php
* @author JoomlaJoe and Beat, database check function by Nick
* @copyright (C) JoomlaJoe and Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

$memMax			=	trim( @ini_get( 'memory_limit' ) );
if ( $memMax ) {
	$last			=	strtolower( $memMax{strlen( $memMax ) - 1} );
	switch( $last ) {
		case 'g':
			$memMax	*=	1024;
		case 'm':
			$memMax	*=	1024;
		case 'k':
			$memMax	*=	1024;
	}
	if ( $memMax < 16000000 ) {
		@ini_set( 'memory_limit', '16M' );
	}
	if ( $memMax < 32000000 ) {
		@ini_set( 'memory_limit', '32M' );
	}
	if ( $memMax < 48000000 ) {
		@ini_set( 'memory_limit', '48M' );		// DOMIT XML parser can be very memory-hungry on PHP < 5.1.3
	}
}
/**
 * CB framework
 * @global CBframework $_CB_framework
 */
global $_CB_framework;
/** @global string $_CB_adminpath
 *  @global array $ueConfig
 */
global $_CB_Admin_Done, $_CB_adminpath, $ueConfig, $mainframe;

if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
	$_CB_adminpath		=	JPATH_ADMINISTRATOR . '/components/com_comprofiler';
	include_once $_CB_adminpath . '/plugin.foundation.php';
} else {
	$_CB_adminpath		=	$mainframe->getCfg( 'absolute_path' ). '/administrator/components/com_comprofiler';
	include_once $_CB_adminpath . '/plugin.foundation.php';
}

$_CB_framework->cbset( '_ui', 2 );	// : we're in 1: frontend, 2: admin back-end

if($_CB_framework->getCfg( 'debug' )) {
	ini_set( 'display_errors', true );
	error_reporting( E_ALL );	// | E_STRICT );
}

cbimport( 'language.front' );
cbimport( 'language.cbteamplugins' );
cbimport( 'language.admin' );

if ( ! $_CB_framework->check_acl( 'canManageUsers', $_CB_framework->myUserType() ) ) {
	cbRedirect( $_CB_framework->backendUrl( 'index.php' ), _UE_NOT_AUTHORIZED, 'error' );
}

/** Backend menu: 'show' : only displays close button, 'edit' : special close button
 *  @global stdClass $_CB_Backend_Menu */
global $_CB_Backend_Menu;
$_CB_Backend_Menu	=	new stdClass();

$option				=	$_CB_framework->getRequestVar( 'option' );
$task				=	$_CB_framework->getRequestVar( 'task' );
$cid				=	cbGetParam( $_REQUEST, 'cid', array( 0 ) );
if ( ! is_array( $cid )) {
	$cid			=	array ( (int) $cid );
}

global $_CB_Backend_Title, $_CB_Backend_task;
$_CB_Backend_Title	=	array();
$_CB_Backend_task	=	$task;

$oldignoreuserabort	=	ignore_user_abort( true );

$taskPart1			=	strtok( $task, '.' );

$_CB_framework->document->_outputToHeadCollectionStart();
ob_start();

// remind step 2 if forgotten/failed:
$tgzFile			=	$_CB_framework->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/pluginsfiles.tgz';
if ( file_exists( $tgzFile ) ) {
	if ( in_array( $taskPart1, array( 'showusers', 'showconfig', 'showTab', 'showField', 'showLists', 'tools', 'showPlugins', '' ) ) ) {
		echo '<div class="cbWarning"> ' . sprintf( CBTxt::Th('Warning: file %s still exists. This is probably due to the fact that first installation step did not complete, or second installation step did not take place. If you are sure that first step has been performed, you need to execute second installation step before using CB. You can do this now by clicking here:') , $tgzFile )
		. ' <a href="' . $_CB_framework->backendUrl( 'index.php?option=com_comprofiler&task=finishinstallation' ) . '">' . CBTxt::Th('please click here to continue next and last installation step') . '</a>.</div>';
	}
}

function _CBloadController( $name ) {
	global $_CB_framework, $ueConfig;

	require_once $_CB_framework->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/controller/controller.' . $name . '.php';
	$controllerClass		=	'CBController_' . $name;
	return new $controllerClass( $ueConfig );
}
function _CBloadView( $name ) {
	global $_CB_framework, $ueConfig;

	require_once $_CB_framework->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/view/view.' . $name . '.php';
	$viewClass				=	'CBView_' . $name;
	return new $viewClass( $ueConfig );
}
function clearSearchBox(){
	global $_CB_framework;
	$_CB_framework->setUserState('searchcom_comprofiler','');
}

function _CBsecureAboveForm( $functionName ) {
	global $_CB_framework;
	ob_start();
?>
if(self!=top) {
	parent.document.body.innerHTML='Iframes not allowed, could be hack attempt..., sorry!';
	self.top.location=self.location;
}
<?php
	$js		=	 ob_get_contents();
	ob_end_clean();
	$_CB_framework->document->addHeadScriptDeclaration( $js );
	return null;
}

switch ( $taskPart1 ) {
	case "showusers":
	case "emailusers":
	case "startemailusers":
	case "ajaxemailusers":
		$cbController	=	_CBloadController( 'users' );
		$cbController->showUsers( $option, $task, $cid );
		break;
	case "new":
		$cbController	=	_CBloadController( 'user' );
		$cbController->editUser( 0, $option );
		break;

	case "edit":
		$cbController	=	_CBloadController( 'user' );
		$cbController->editUser( intval( $cid[0] ), $option );
		break;

	case "save":
		cbSpoofCheck( 'user' );
		$cbController	=	_CBloadController( 'user' );
		$cbController->saveUser( $option );
		break;

	case 'newPlugin':
	case 'editPlugin':
		$cbController	=	_CBloadController( 'plugin' );
		$cbController->editPlugin( $option, $task,  $cid[0] );
		break;

	case 'savePlugin':
	case 'applyPlugin':
		cbSpoofCheck( 'plugin' );
		$cbController	=	_CBloadController( 'plugin' );
		$cbController->savePlugin( $option, $task );
		break;

	case 'pluginmenu':
		$cbController	=	_CBloadController( 'plugin' );
		$cbController->pluginMenu( $option, cbGetParam( $_REQUEST, 'pluginid', 0 ) );
		break;

	case "newTab":
		$cbController	=	_CBloadController( 'tab' );
		$cbController->editTab( 0, $option);
		break;

	case "editTab":
		$cbController	=	_CBloadController( 'tab' );
		$cbController->editTab( intval( $cid[0] ), $option, $task );
		break;

	case "saveTab":
		cbSpoofCheck( 'tab' );
		$cbController	=	_CBloadController( 'tab' );
		$cbController->saveTab( $option );
		break;

	case "newField":
		$cbController	=	_CBloadController( 'field' );
		$cbController->editField( 0, $option, $task );
		break;

	case "editField":
		$cbController	=	_CBloadController( 'field' );
		$cbController->editField( intval( $cid[0] ), $option, $task );
		break;

	case "reloadField":
		cbSpoofCheck( 'field' );
		$cbController	=	_CBloadController( 'field' );
		$cbController->editField( (int) cbGetParam( $_POST, 'fieldid', 0 ), $option, $task );
		break;

	case "saveField":
	case "applyField":
		cbSpoofCheck( 'field' );
		$cbController	=	_CBloadController( 'field' );
		$cbController->saveField( $option, $task );
		break;

	default:
		_CBloadController( 'default' );

		break;
}

echo $_CB_framework->getAllJsPageCodes();

$html		=	ob_get_contents();
ob_end_clean();

if ( in_array( $taskPart1, array( 'fieldclass', 'tabclass', 'pluginclass' ) ) || ( cbGetParam( $_GET, 'no_html', 0 ) == 1 ) || ( cbGetParam( $_GET, 'format' ) == 'raw' ) ) {
	echo $html;
} else {
	$cssIE7fix	=	'</style><!--[if lte IE 7]><style type="text/css">.cbtoolbarbar .cbtoolbar { width: 48px; }</style><![endif]--><style type="text/css">';
	if ( checkJversion() < 2 ) {
		// Joomla 1.6 does not comment styles anymore, and Safari takes the comments seriously, displaying a blank page without this check:
		$cssIE7fix	=	'-->' . $cssIE7fix . '<!--';
	}
	echo $_CB_framework->document->addHeadStyleInline( $cssIE7fix );
	echo $_CB_framework->document->_outputToHead();
	// fix the backend toolbar icons taking full width in joomla 1.5:
?>
<div style="margin:0px;border-width:0px;padding:0px;float:left;width:100%;text-align:left;"><div id="cbAdminMainWrapper" style="margin:0px;border-width:0px;padding:0px;float:none;width:auto;">
<?php
	if ( checkJversion() == 2 ) {
		JSubMenuHelper::addEntry( CBTxt::T( 'User Management' ), 'index.php?option=com_comprofiler&task=showusers&view=showusers', ( $taskPart1 == 'showusers' ) );
		JSubMenuHelper::addEntry( CBTxt::T( 'Tab Management' ), 'index.php?option=com_comprofiler&task=showTab&view=showTab', ( $taskPart1 == 'showTab' ) );
		JSubMenuHelper::addEntry( CBTxt::T( 'Field Management' ), 'index.php?option=com_comprofiler&task=showField&view=showField', ( $taskPart1 == 'showField' ) );
		JSubMenuHelper::addEntry( CBTxt::T( 'List Management' ), 'index.php?option=com_comprofiler&task=showLists&view=showLists', ( $taskPart1 == 'showLists' ) );
		JSubMenuHelper::addEntry( CBTxt::T( 'Plugin Management' ), 'index.php?option=com_comprofiler&task=showPlugins&view=showPlugins', ( $taskPart1 == 'showPlugins' ) );
		JSubMenuHelper::addEntry( CBTxt::T( 'Tools' ), 'index.php?option=com_comprofiler&task=tools&view=tools', ( $taskPart1 == 'tools' ) );
		JSubMenuHelper::addEntry( CBTxt::T( 'Configuration' ), 'index.php?option=com_comprofiler&task=showconfig&view=showconfig', ( $taskPart1 == 'showconfig' ) );
	}

	echo '<div style="float:right;">';
	include( $_CB_adminpath . '/comprofiler.toolbar.php' );
	echo '</div>';
	if ( count( $_CB_Backend_Title ) > 0 ) {
		echo '<div class="header' . ( isset( $_CB_Backend_Title[0][0] ) ? ' ' . $_CB_Backend_Title[0][0] : '' ) . '">';
		echo $_CB_Backend_Title[0][1];
		echo '</div>';
		echo '<div style="clear:both;">';
		echo '</div>';
	}
	echo '<div style="float:left;width:100%;">';
	echo $html;
	// Translations debug:
	if ( ! defined( 'JPATH_ADMINISTRATOR' ) ) {
		global $_CB_TxtIntStore;
		echo $_CB_TxtIntStore->listUsedStrings();
	}
	echo '</div>';
	echo '<div style="clear:both;">';
	echo '</div>';
?>
</div></div>
<?php
}
if ( ! is_null( $oldignoreuserabort ) ) {
	ignore_user_abort($oldignoreuserabort);
}

// END OF MAIN.


/**
* @deprecated 1.2.3 (but kept for backwards compatibility)
*
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

/**
 * Cleans junk of html editors that's needed for clean translation
 *
 * @deprecated 1.2.3 (but kept for backwards compatibility)
 *
 * @param  string $text
 * @return string
 */
function cleanEditorsTranslationJunk( $text ) {
	$matches					=	null;
	if ( preg_match( '/^<p>([^<]+)<\/p>$/i', $text, $matches ) ) {
		if ( trim( $matches[1] ) != getLangDefinition( trim( $matches[1] ) ) ) {
			$text				=	trim( $matches[1] );
		}
	}
	return $text;
}


?>
