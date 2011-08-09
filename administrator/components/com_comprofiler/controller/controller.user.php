<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: controller.user.php 1348 2011-01-24 22:46:06Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : user controller
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBController_user {
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
	function editUser( $uid = '0', $option = 'users' ) {
		global $_CB_framework, $_PLUGINS;

		$this->_importNeeded();

		$msg						=	checkCBpermissions( array($uid), "edit", true );
		if ($msg) {
			echo "<script type=\"text/javascript\"> alert('".$msg."'); window.history.go(-1);</script>\n";
			exit;
		}
	
		$_PLUGINS->loadPluginGroup('user');
	
		$cbUser						=&	CBuser::getInstance( $uid );
		$cmsUserExists				=	( $uid != 0 ) && ( $cbUser !== null );
		if ( ! $cmsUserExists ) {
			$cbUser					=&	CBuser::getInstance( null );
		}
		$user						=&	$cbUser->getUserData();
	/*
		$user						=	new moscomprofilerUser( $_CB_database );
		$cmsUserExists				=	$user->load( (int) $uid );
	*/
		$comprofilerExists			=	( $user->user_id != null );
		if ( $cmsUserExists && $comprofilerExists ) {
			// Edit existing CB user:
			$newCBuser				=	'0';
		} else {
			$newCBuser				=	'1';
			if ( $cmsUserExists ) {
				// Edit existing CMS (but new CB) user:
				$user->approved		=	'1';
				$user->confirmed	=	'1';
			} else {
				// New user:
				$user->block		=	'0';
				$user->approved		=	'1';
				$user->confirmed	=	'1';
				$user->sendEmail	=	'0';
				$user->gid			=	$_CB_framework->acl->get_group_id( $_CB_framework->getCfg( 'new_usertype' ), 'ARO' );
				$user->gids			=	array( $user->gid );
			}
		}
		$null						=	null;
		$usersView					=	_CBloadView( 'user' );
		$usersView->edituser( $user, $option, $newCBuser, $null );
	}
	
	function saveUser( $option ) {
		global $_CB_framework, $_CB_database, $_POST, $_PLUGINS;

		$this->_importNeeded();
		$this->_importNeededSave();

		if ( ! ( isset( $_POST['approved'] ) && isset( $_POST['confirmed'] ) && isset( $_POST['username'] ) ) ) {
			echo "<script type=\"text/javascript\"> alert('" . addslashes( CBTxt::T('Not Authorized') ) ."'); window.history.go(-1);</script>\n";
			exit;
		}
	
		// Check rights to access:
	
		$myGid						=	userGID( $_CB_framework->myId() );
		$userIdPosted				=	(int) cbGetParam($_POST, "id", 0 );
		if ( $userIdPosted == 0 ) {
			$_POST['id']			=	null;
		}

		$adminGroups				=	$_CB_framework->acl->mapGroupNamesToValues( array( 'Administrator', 'Superadministrator' ) );
		
		if ( $userIdPosted != 0 ) {
			$msg					=	checkCBpermissions( array( $userIdPosted ), 'save', in_array( $myGid, $adminGroups ) );
		} else {
			$msg					=	checkCBpermissions( null, 'save', in_array( $myGid, $adminGroups ) );
		}
		if ($msg) {
			echo "<script type=\"text/javascript\"> alert('" . addslashes( $msg ) . "'); window.history.go(-1);</script>\n";
			exit;
		}
	
		$_PLUGINS->loadPluginGroup('user');
	
		// Get current user state:
	
		$userComplete				=	new moscomprofilerUser( $_CB_database );
		if ( $userIdPosted != 0 ) {
			if ( ! $userComplete->load( (int) $userIdPosted ) ) {
				echo "<script type=\"text/javascript\"> alert('" . addslashes( _UE_USER_PROFILE_NOT ) . "'); window.history.go(-1);</script>\n";
				return;
			}
		}
	
		// Store new user state:
	
		$saveResult					=	$userComplete->saveSafely( $_POST, $_CB_framework->getUi(), 'edit' );
		if ( ! $saveResult ) {
			$regErrorMSG			=	$userComplete->getError();
	
			$msg					=	checkCBpermissions( array( $userComplete->id ), "edit", true );
			if ($msg) {
				echo "<script type=\"text/javascript\"> alert('" . addslashes( $msg ) ."'); window.history.go(-1);</script>\n";
				exit;
			}
	
			echo "<script type=\"text/javascript\">alert('" . str_replace( '\\\\n', '\\n', addslashes( strip_tags( str_replace( '<br />', '\\n', $regErrorMSG ) ) ) ) . "'); </script>\n";
			global $_CB_Backend_task;
			$_CB_Backend_task		=	'edit';			// so the toolbar comes up...
			$_PLUGINS->loadPluginGroup( 'user' );		// resets plugin errors
			$usersView					=	_CBloadView( 'user' );
			$usersView->edituser( $userComplete, $option, ( $userComplete->user_id != null ? '0' : '1' ), $_POST );
			// echo "<script type=\"text/javascript\">alert('" . addslashes( str_replace( '<br />', '\n', $userComplete->getError() ) ) . "'); window.history.go(-1);</script>\n";
			return;
		}
	
		// Checks-in the row:
		$userComplete->checkin();
	
		cbRedirect( $_CB_framework->backendUrl( "index.php?option=$option&task=showusers" ), sprintf(CBTxt::T('Successfully Saved User: %s'), $userComplete->username) );
	}

}	// class CBController_user

?>