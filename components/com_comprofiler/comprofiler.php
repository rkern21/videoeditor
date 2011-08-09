<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: comprofiler.php 1439 2011-02-13 01:21:13Z beat $
* @package Community Builder
* @subpackage comprofiler.php
* @author JoomlaJoe and Beat
* @copyright (C) JoomlaJoe and Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

$memMax				=	trim( @ini_get( 'memory_limit' ) );
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
	if ( $memMax < 24000000 ) {
		@ini_set( 'memory_limit', '24M' );
	}
	if ( $memMax < 32000000 ) {
		@ini_set( 'memory_limit', '32M' );
	}
}

/** @global mosMainFrame $mainframe
 */
global $mainframe;
/**
 * CB framework
 * @global CBframework $_CB_framework
 */
global $_CB_framework;
/** @global array $ueConfig
 */
global $ueConfig;
if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
	include_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';
	require_once $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.html.php';
} else {
	include_once $mainframe->getCfg( 'absolute_path' ). '/administrator/components/com_comprofiler/plugin.foundation.php';
	require_once $mainframe->getPath( 'front_html' );
}

$_CB_framework->cbset( '_ui', 1 );	// we're in 1: frontend, 2: admin back-end

if($_CB_framework->getCfg( 'debug' )) {
	ini_set('display_errors',true);
	error_reporting(E_ALL );	// | E_STRICT );
}

cbimport( 'language.front' );
cbimport( 'cb.tabs' );
cbimport( 'cb.imgtoolbox' );

if ( class_exists( 'JFactory' ) ) {	// Joomla 1.5 : for string WARNREG_EMAIL_INUSE used in error js popup.
	$lang			=&	JFactory::getLanguage();
	$lang->load( "com_user" );
}

$option				=	$_CB_framework->getRequestVar( 'option' );
$task				=	$_CB_framework->getRequestVar( 'task' );
$form				=	cbGetParam( $_REQUEST, 'reportform', 1 );
$uid				=	cbGetParam( $_REQUEST, 'uid', 0 );
$act				=	cbGetParam( $_REQUEST, 'act', 1 );

$oldignoreuserabort	=	null;

$_CB_framework->document->_outputToHeadCollectionStart();
ob_start();

switch( $task ) {

	case "userDetails":
	case "userdetails":
	userEdit( $option, $uid, _UE_UPDATE );
	break;

	case "saveUserEdit":
	case "saveuseredit":
	$oldignoreuserabort = ignore_user_abort(true);
	userSave( $option, (int) cbGetParam( $_POST, 'id', 0 ) );
	break;

	case "userProfile":
	case "userprofile":
	userProfile($option, $_CB_framework->myId(), _UE_UPDATE);
	break;

	case "usersList":
	case "userslist":
	usersList( $_CB_framework->myId() );
	break;

	case "userAvatar":
	case "useravatar":
	userAvatar($option, $uid, _UE_UPDATE);
	break;

	case "lostPassword":
	case "lostpassword":
	lostPassForm( $option );
	break;

	case "sendNewPass":
	case "sendnewpass":
	$oldignoreuserabort = ignore_user_abort(true);
	sendNewPass( $option );
	break;

	case "registers":
	registerForm( $option, isset( $ueConfig['emailpass'] ) ? $ueConfig['emailpass'] : '0' );
	break;

	case "saveregisters":
	$oldignoreuserabort = ignore_user_abort(true);
	saveRegistration( $option );
	break;

	case "login":
	$oldignoreuserabort = ignore_user_abort(true);
	login();
	break;

	case "logout":
	$oldignoreuserabort = ignore_user_abort(true);
	logout();
	break;

	case "confirm":
	$oldignoreuserabort = ignore_user_abort(true);
	confirm( cbGetParam( $_GET, 'confirmcode', '1' ) );		// mambo 4.5.3h braindead: does intval of octal from hex in cbGetParam...
	break;

	case "moderateImages":
	case "moderateimages":
	$oldignoreuserabort = ignore_user_abort(true);
	moderateImages($option);
	break;

	case "moderateReports":
	case "moderatereports":
	$oldignoreuserabort = ignore_user_abort(true);
	moderateReports($option);
	break;

	case "moderateBans":
	case "moderatebans":
	$oldignoreuserabort = ignore_user_abort(true);
	moderateBans($option,$act,$uid);
	break;

	case "approveImage":
	case "approveimage":
	$oldignoreuserabort = ignore_user_abort(true);
	approveImage();
	break;

	case "reportUser":
	case "reportuser":
	$oldignoreuserabort = ignore_user_abort(true);
	reportUser($option,$form,$uid);
	break;

	case "processReports":
	case "processreports":
	$oldignoreuserabort = ignore_user_abort(true);
	processReports();
	break;

	case "banProfile":
	case "banprofile":
	$oldignoreuserabort = ignore_user_abort(true);
	banUser($option,$uid,$form,$act);
	break;

	case "viewReports":
	case "viewreports":
	viewReports($option,$uid,$act);
	break;

	case "emailUser":
	case "emailuser":
	emailUser($option,$uid);
	break;

	case "pendingApprovalUser":
	case "pendingapprovaluser":
	pendingApprovalUsers($option);
	break;

	case "approveUser":
	case "approveuser":
	$oldignoreuserabort = ignore_user_abort(true);
	approveUser(cbGetParam($_POST,'uids'));
	break;

	case "rejectUser":
	case "rejectuser":
	$oldignoreuserabort = ignore_user_abort(true);
	rejectUser(cbGetParam($_POST,'uids'));
	break;

	case "sendUserEmail":
	case "senduseremail":
	$oldignoreuserabort = ignore_user_abort(true);
	sendUserEmail( $option, (int) cbGetParam( $_POST, 'toID', 0 ), (int) cbGetParam( $_POST, 'fromID', 0 ), cbGetParam( $_POST, 'emailSubject', '' ), cbGetParam( $_POST, 'emailBody', '' ) );
	break;

	case "addConnection":
	case "addconnection":
	$oldignoreuserabort = ignore_user_abort(true);
	addConnection( $_CB_framework->myId(), (int) cbGetParam($_REQUEST,'connectionid'), ((isset($_POST['message'])) ? cbGetParam($_POST,'message') : ""));
	break;

	case "removeConnection":
	case "removeconnection":
	$oldignoreuserabort = ignore_user_abort(true);
	removeConnection( $_CB_framework->myId(), (int) cbGetParam($_REQUEST, 'connectionid') );
	break;

	case "denyConnection":
	case "denyconnection":
	$oldignoreuserabort = ignore_user_abort(true);
	denyConnection( $_CB_framework->myId(), (int) cbGetParam($_REQUEST,'connectionid'));
	break;

	case "acceptConnection":
	case "acceptconnection":
	$oldignoreuserabort = ignore_user_abort(true);
	acceptConnection( $_CB_framework->myId(), (int) cbGetParam($_REQUEST,'connectionid'));
	break;

	case "manageConnections":
	case "manageconnections":
	manageConnections( $_CB_framework->myId() );
	break;

	case "saveConnections":
	case "saveconnections":
	$oldignoreuserabort = ignore_user_abort(true);
	saveConnections(cbGetParam($_POST,'uid'));
	break;

	case "processConnectionActions":
	case "processconnectionactions":
	$oldignoreuserabort = ignore_user_abort(true);
	processConnectionActions(cbGetParam($_POST,'uid'));
	break;

	case "teamCredits":
	case "teamcredits":
	teamCredits(1);
	break;

	case "fieldclass":
	case "tabclass":
	case "pluginclass":
	tabClass( $option, $task, $_CB_framework->myId() );
	break;

	case "done":
	break;

	case "performcheckusername":
	performCheckUsername( cbGetParam( $_POST, 'value' ), cbGetParam( $_GET, 'function' ) );
	break;

	case "performcheckemail":
	performCheckEmail( cbGetParam( $_POST, 'value' ), cbGetParam( $_GET, 'function' ) );
	break;

	default:
	userProfile($option, $_CB_framework->myId(), _UE_UPDATE);
	break;
}

if (!is_null($oldignoreuserabort)) ignore_user_abort($oldignoreuserabort);

echo $_CB_framework->getAllJsPageCodes();

$html		=	ob_get_contents();
ob_end_clean();

if ( ( cbGetParam( $_GET, 'no_html', 0 ) != 1 ) && ( cbGetParam( $_GET, 'format' ) != 'raw' ) ) {

	// Translations debug:
	if ( ! defined( 'JPATH_ADMINISTRATOR' ) ) {
		global $_CB_TxtIntStore;
		$translationTable	=	$_CB_TxtIntStore->listUsedStrings();
		if ( $translationTable ) {
			$html 			.=	$translationTable;
		} 
	}

	echo $_CB_framework->document->_outputToHead();
}
echo $html;

// END OF MAIN.

function sendUserEmail( $option, $toid, $fromid, $subject, $message ) {
	global $ueConfig, $_CB_framework, $_CB_database, $_POST, $_PLUGINS;

	// simple spoof check security
	cbSpoofCheck( 'emailUser' );
	$errorMsg	=	cbAntiSpamCheck( false );

	if (($_CB_framework->myId() == 0) || ($_CB_framework->myId() != $fromid) || ( ! $toid ) || ($ueConfig['allow_email_display']!=1 && $ueConfig['allow_email_display']!=3)) {
		cbNotAuth();
		return;
	}

	$rowFrom = new moscomprofilerUser( $_CB_database );
	$rowFrom->load( (int) $fromid );

	$rowTo = new moscomprofilerUser( $_CB_database );
	$rowTo->load( (int) $toid );

	$subject	=	stripslashes( $subject );		// cbGetParam() adds slashes...remove'em...
	$message	=	stripslashes( $message );

	if ( ! $errorMsg ) {
		$errorMsg	=	_UE_SESSIONTIMEOUT . " " . _UE_SENTEMAILFAILED;
		if ( isset( $_POST["protect"] ) ) {
			$parts	=	explode( '_', cbGetParam( $_POST, 'protect', '' ) );
			if ( ( count( $parts ) == 3 ) && ( $parts[0] == 'cbmv1' ) && ( strlen( $parts[2] ) == 16 ) && ( $parts[1] == md5($parts[2].$rowTo->id.$rowTo->password.$rowTo->lastvisitDate.$rowFrom->password.$rowFrom->lastvisitDate) ) ) {
				$errorMsg	=	null;
				$_PLUGINS->loadPluginGroup('user');
				$pluginResults = $_PLUGINS->trigger( 'onBeforeEmailUser', array( &$rowFrom, &$rowTo, 1 ));	//$ui=1
				if ($_PLUGINS->is_errors()) {
					$errorMsg	=	$_PLUGINS->getErrorMSG( '<br />') . "\n";
				} else {
					$spamCheck = cbSpamProtect( $_CB_framework->myId(), true );
					if ( $spamCheck ) {
						$errorMsg	=	$spamCheck;
					} else {
						$cbNotification	=	new cbNotification();
						$res			=	$cbNotification->sendUserEmail($toid,$fromid,$subject,$message, true);

						if ($res) {
							echo _UE_SENTEMAILSUCCESS;
							if (is_array($pluginResults)) {
								echo implode( "<br />", $pluginResults );
							}
							return;
						}
						else {
							$errorMsg	=	_UE_SENTEMAILFAILED;
						}
					}
				}
			}
		}
	}
	echo '<div class="error">' . $errorMsg . '</div>';
	HTML_comprofiler::emailUser( $option, $rowFrom, $rowTo, $subject, $message );
}

function emailUser($option,$uid) {
	global $_CB_framework, $_CB_database, $ueConfig;
	if (($_CB_framework->myId() == 0) || ($ueConfig['allow_email_display']!=1 && $ueConfig['allow_email_display']!=3)) {
		cbNotAuth();
		return;
	}

	$spamCheck = cbSpamProtect( $_CB_framework->myId(), false );
	if ( $spamCheck ) {
		echo $spamCheck;
		return;
	}
	$rowFrom = new moscomprofilerUser( $_CB_database );
	$rowFrom->load( $_CB_framework->myId() );

	$rowTo = new moscomprofilerUser( $_CB_database );
	$rowTo->load( (int) $uid );
	HTML_comprofiler::emailUser($option,$rowFrom,$rowTo);
}

function userEdit( $option, $uid, $submitvalue, $regErrorMSG = null ) {
	global $_CB_framework, $_POST, $_PLUGINS;

	if ( $uid == 0 ) {
		$uid				=	$_CB_framework->myId();
	}

	$msg						=	cbCheckIfUserCanPerformUserTask( $uid, 'allowModeratorsUserEdit');
	if ( ( $uid != $_CB_framework->myId() ) && ( $msg === null ) ) {
		// safeguard against missconfiguration of the above: also avoids lower-level users editing higher level ones:
		$msg					=	checkCBpermissions( array( (int) $uid ), 'edit', true );
	}
	if ( $msg ) {
		echo $msg;
		return;
	}

	$_PLUGINS->loadPluginGroup('user');

	$cbUser						=&	CBuser::getInstance( $uid );
	if ( $cbUser !== null ) {
		$user					=&	$cbUser->getUserData();
		HTML_comprofiler::userEdit( $user, $option, $submitvalue, $regErrorMSG );
	} else {
		echo '<div class="error">' . _UE_ERROR_USER_NOT_SYNCHRONIZED . '</div>';
	}
/*
	$user						=	new moscomprofilerUser( $_CB_database );
	if ( $user->load( (int) $uid ) ) {
		HTML_comprofiler::userEdit( $user, $option, $submitvalue, $regErrorMSG );
	} else {
		echo '<div class="error">' . _UE_ERROR_USER_NOT_SYNCHRONIZED . '</div>';
	}
*/
}

function userSave( $option, $uid ) {
	global $_CB_framework, $_CB_database, $_POST, $_PLUGINS;

	// simple spoof check security
	cbSpoofCheck( 'userEdit' );

	// check rights to access:

	if ( $uid == null ) {
		echo _UE_USER_PROFILE_NOT;
		return;
	}
	$msg						=	cbCheckIfUserCanPerformUserTask( $uid, 'allowModeratorsUserEdit' );
	if ( $msg ) {
		echo $msg;
		return;
	}

	$_PLUGINS->loadPluginGroup('user');

	// Get current user state:

	$userComplete				=	new moscomprofilerUser( $_CB_database );
	if ( ! $userComplete->load( (int) $uid ) ) {
		echo _UE_USER_PROFILE_NOT;
		return;
	}

	// Update lastupdatedate of profile by user:
	if ( $_CB_framework->myId() == $uid ) {
		$userComplete->lastupdatedate	=	$_CB_framework->dateDbOfNow();
	}

	// Store new user state:

	$saveResult					=	$userComplete->saveSafely( $_POST, $_CB_framework->getUi(), 'edit' );
	if ( ! $saveResult ) {
		$regErrorMSG			=	$userComplete->getError();
		echo "<script type=\"text/javascript\">alert('" . str_replace( '\\\\n', '\\n', addslashes( strip_tags( str_replace( '<br />', '\n', $regErrorMSG ) ) ) ) . "'); </script>\n";
		// userEdit( $option, $uid, _UE_UPDATE, $userComplete->getError() );
		HTML_comprofiler::userEdit( $userComplete, $option, _UE_UPDATE, $regErrorMSG );
		return;
	}

	cbRedirectToProfile( $uid, _USER_DETAILS_SAVE );
}

function userAvatar( $option, $uid, $submitvalue) {
	global $_CB_database, $_CB_framework, $_REQUEST, $ueConfig, $_PLUGINS, $_FILES;

	if ( ! $uid ) {
		$uid	=	$_CB_framework->myId();
	}
	if ( ! $uid ) {
		echo _UE_NOT_AUTHORIZED;
		return;
	}
	$msg	=	cbCheckIfUserCanPerformUserTask( $uid, 'allowModeratorsUserEdit');
	if ( $msg ) {
		echo $msg;
		return;
	}
	$row = new moscomprofilerUser( $_CB_database );
	if ( ! $row->load( (int) $uid ) ) {
		echo _UE_NOSUCHPROFILE;
		return;
	}

	$do		=	cbGetParam( $_REQUEST, 'do', 'init' );
	if ( $do == 'init' ) {

		HTML_comprofiler::userAvatar( $row, $option, $submitvalue);

	} elseif ( $do == 'validate' ) {

		// simple spoof check security
		cbSpoofCheck( 'userAvatar' );

		if ( ! $ueConfig['allowAvatarUpload'] ) {
			cbNotAuth();
			return;
		}

		$isModerator=isModerator( $_CB_framework->myId() );

		if (	( ! isset( $_FILES['avatar']['tmp_name'] ) )
			||	empty( $_FILES['avatar']['tmp_name'] )
			||	( $_FILES['avatar']['error'] != 0 )
			||	( ! is_uploaded_file( $_FILES['avatar']['tmp_name'] ) )
		) {
			cbRedirectToProfile( $row->id, _UE_UPLOAD_ERROR_EMPTY, 'userAvatar' );
		}

		$_PLUGINS->loadPluginGroup( 'user' );
		$_PLUGINS->trigger( 'onBeforeUserAvatarUpdate', array( &$row, &$row, $isModerator, &$_FILES['avatar']['tmp_name'] ) );
		if ($_PLUGINS->is_errors()) {
			cbRedirectToProfile( $row->id, $_PLUGINS->getErrorMSG(), 'userAvatar' );
		}

		$imgToolBox						=	new imgToolBox();
		$imgToolBox->_conversiontype	=	$ueConfig['conversiontype'];
		$imgToolBox->_IM_path			=	$ueConfig['im_path'];
		$imgToolBox->_NETPBM_path		=	$ueConfig['netpbm_path'];
		$imgToolBox->_maxsize			=	$ueConfig['avatarSize'];
		$imgToolBox->_maxwidth			=	$ueConfig['avatarWidth'];
		$imgToolBox->_maxheight			=	$ueConfig['avatarHeight'];
		$imgToolBox->_thumbwidth		=	$ueConfig['thumbWidth'];
		$imgToolBox->_thumbheight		=	$ueConfig['thumbHeight'];
		$imgToolBox->_debug				=	0;
		$allwaysResize					=	( isset( $ueConfig['avatarResizeAlways'] ) ? $ueConfig['avatarResizeAlways'] : 1 );

		$newFileName		=	$imgToolBox->processImage( $_FILES['avatar'], uniqid($row->id."_"), $_CB_framework->getCfg('absolute_path') . '/images/comprofiler/', 0, 0, 1, $allwaysResize );
		if ( ! $newFileName ) {
			cbRedirectToProfile( $row->id, $imgToolBox->_errMSG, 'userAvatar' );
		}

		if ($row->avatar != null && $row->avatar!="") {
			deleteAvatar($row->avatar);
		}

		if ($ueConfig['avatarUploadApproval']==1 && $isModerator==0) {

			$cbNotification	=	new cbNotification();
			$cbNotification->sendToModerators(_UE_IMAGE_ADMIN_SUB,_UE_IMAGE_ADMIN_MSG);

			$_CB_database->setQuery("UPDATE #__comprofiler SET avatar='" . $_CB_database->getEscaped($newFileName) . "', avatarapproved=0 WHERE id=" . (int) $row->id);
			$redMsg			=	_UE_UPLOAD_PEND_APPROVAL;
		} else {
			$_CB_database->setQuery("UPDATE #__comprofiler SET avatar='" . $_CB_database->getEscaped($newFileName) . "', avatarapproved=1, lastupdatedate=". $_CB_database->Quote( $_CB_framework->dateDbOfNow() ) . " WHERE id=" . (int) $row->id);
			$redMsg			=	_UE_UPLOAD_SUCCESSFUL;
		}

		$_CB_database->query();

		$_PLUGINS->trigger( 'onAfterUserAvatarUpdate', array(&$row,&$row,$isModerator,$newFileName) );
		cbRedirectToProfile( $row->id, $redMsg );

	} elseif ( $do == 'fromgallery' ) {

		// simple spoof check security
		cbSpoofCheck( 'userAvatar' );

		if( ! $ueConfig['allowAvatarGallery'] ) {
			cbNotAuth();
			return;
		}

		$newAvatar = cbGetParam( $_POST, 'newavatar', null );
		if ( ( $newAvatar == '' ) || preg_match( '/[^-_a-zA-Z0-9.]/', $newAvatar ) || ( strpos( $newAvatar, '..' ) !== false ) ) {
			cbRedirectToProfile( $row->id, _UE_UPLOAD_ERROR_CHOOSE, 'userAvatar' );
		}
		$_CB_database->setQuery( "UPDATE #__comprofiler SET avatar = " . $_CB_database->Quote( 'gallery/' . $newAvatar )
								. ", avatarapproved=1, lastupdatedate = " . $_CB_database->Quote( $_CB_framework->dateDbOfNow() )
								. " WHERE id = " . (int) $row->id);
		if( ! $_CB_database->query() ) {
			$msg	=	_UE_USER_PROFILE_NOT;
		}else {
			// delete old avatar:
			deleteAvatar( $row->avatar );
			$msg	=	_UE_USER_PROFILE_UPDATED;
		}
		cbRedirectToProfile( $row->id, $msg );

	} elseif ( $do == 'deleteavatar' ) {

		if ( $row->avatar != null && $row->avatar != "" ) {
			deleteAvatar( $row->avatar );
			$_CB_database->setQuery("UPDATE  #__comprofiler SET avatar=null, avatarapproved=1, lastupdatedate=" . $_CB_database->Quote( $_CB_framework->dateDbOfNow() ) . " WHERE id=" . (int) $row->id);
			$_CB_database->query();
		}

		cbRedirectToProfile( $row->id, _USER_DETAILS_SAVE );
	}
}

function & loadComprofilerUser( $uid ) {
	global $_CB_framework, $_REQUEST;

	if ( ! isset( $_REQUEST['user'] ) ) {
		if ( ! $uid ) {
			$null		=	null;
			return $null;
		}
	} else {
		$userReq		=	urldecode( stripslashes( cbGetParam( $_REQUEST, 'user' ) ) );
		$len			=	strlen( $userReq );
		if ( ( $len > 2 ) && ( $userReq[0] == "'" ) && ( $userReq[$len-1] == "'" ) ) {
			$userReq	=	substr($userReq, 1, $len-2);
			$uid		=	$_CB_framework->getUserIdFrom( 'username', utf8ToISO( $userReq ) );
		} else {
			$uid		=	(int) $userReq;
		}
	}
	if ( $uid ) {
		$cbUser			=&	CBuser::getInstance( $uid );
		if ( $cbUser ) {
			$user		=&	$cbUser->getUserData();
			return $user;
		}
	}
/*
		global $_CB_database;
		$user			=	new moscomprofilerUser( $_CB_database );
		if ( $user->load( (int) $uid ) ) {
			return $user;
		}
	}
*/
	$null		=	null;
	return $null;
}

function userProfile( $option, $uid, $submitvalue) {
	global $_REQUEST, $ueConfig, $_CB_framework;
	if ( isset( $_REQUEST['user'] ) ) {
		if ( ! allowAccess( $ueConfig['allow_profileviewbyGID'], 'RECURSE', userGID( $_CB_framework->myId() ) ) ) {
			if (	( $_CB_framework->myId() < 1 )
				&&	( ! ( ( ( $_CB_framework->getCfg( 'allowUserRegistration' ) == '0' )
		   				    && ( ( ! isset($ueConfig['reg_admin_allowcbregistration']) ) || $ueConfig['reg_admin_allowcbregistration'] != '1' ) )
						)
					)
					&&
					allowAccess( $ueConfig['allow_profileviewbyGID'], 'RECURSE', $_CB_framework->acl->get_group_id('Registered','ARO') )
			) {
				echo _UE_REGISTERFORPROFILEVIEW;
			} else {
				echo _UE_NOT_AUTHORIZED;
			}
			return;
		}
	} else {
		if ($uid==0) {
			echo _UE_REGISTERFORPROFILE;
			return;
		}
	}

	$user					=&	loadComprofilerUser( $uid );

	if ( $user === null ) {
		echo _UE_NOSUCHPROFILE;
		return;
	}

	HTML_comprofiler::userProfile( $user, $option, $submitvalue);
}

// NB for now duplicated in frontend and admin backend:
function tabClass( $option, $task, $uid ) {
	global $_CB_framework, $_PLUGINS, $ueConfig, $_REQUEST, $_POST;

	$user					=&	loadComprofilerUser( $uid );
	$cbUser					=&	CBuser::getInstance( ( $user === null ? null : $user->id ) );

	$unsecureChars			=	array( '/', '\\', ':', ';', '{', '}', '(', ')', "\"", "'", '.', ',', "\0", ' ', "\t", "\n", "\r", "\x0B" );
	if ( $task == 'fieldclass' ) {
		$reason				=	cbGetParam( $_REQUEST, 'reason' );
		if ( $user && $user->id ) {
			if ( $reason === 'edit' ) {
				$msg		=	cbCheckIfUserCanPerformUserTask( $user->id, 'allowModeratorsUserEdit' );
				if ( ( $uid != $_CB_framework->myId() ) && ( $msg === null ) ) {
					// safeguard against missconfiguration of the above: also avoids lower-level users editing higher level ones:
					$msg	=	checkCBpermissions( array( (int) $user->id ), 'edit', true );
				}
			} elseif ( ( $reason === 'profile' ) || ( $reason === 'list' ) ) {
				if ( allowAccess( $ueConfig['allow_profileviewbyGID'], 'RECURSE', userGID( $_CB_framework->myId() ) ) ) {
					$msg	=	null;
				} else {
					$msg	=	_UE_NOT_AUTHORIZED;
				}
			} else {
				$msg		=	_UE_NO_INDICATION;
			}

			if ( $msg ) {
				echo $msg;
				return;
			}
		} elseif ( $reason == 'register' ) {
			if ( $_CB_framework->myId() != 0 ) {
				echo _UE_ALREADY_LOGGED_IN;
				return;
			}
		} else {
/*
		if (	( ! ( ( ( $_CB_framework->getCfg( 'allowUserRegistration' ) == '0' )
		   				    && ( ( ! isset($ueConfig['reg_admin_allowcbregistration']) ) || $ueConfig['reg_admin_allowcbregistration'] != '1' ) )
						)
					)
					&&
					allowAccess( $ueConfig['allow_profileviewbyGID'], 'RECURSE', $_CB_framework->acl->get_group_id('Registered','ARO') )
			) {
				$msg		=	_UE_REGISTERFORPROFILEVIEW;
				echo $msg;
				return;
			} else {
				$msg		=	_UE_NOT_AUTHORIZED;
				echo $msg;
				return;
			}
*/
			$msg			=	_UE_NOT_AUTHORIZED;
			echo $msg;
			return;
		}

		$fieldName			=	trim( substr( str_replace( $unsecureChars, '', urldecode( stripslashes( cbGetParam( $_REQUEST, "field" ) ) ) ), 0, 50 ) );
		if ( ! $fieldName ) {
			echo 'no field';
			return;
		}
	} elseif ( $task == 'tabclass' ) {
		$tabClassName		=	urldecode( stripslashes( cbGetParam( $_REQUEST, "tab" ) ) );
		if ( ! $tabClassName ) {
			return;
		}
		$pluginName			=	null;
		$tabClassName		=	substr( str_replace( $unsecureChars, '', $tabClassName ), 0, 32 );
		$method				=	'getTabComponent';
	} elseif ( $task == 'pluginclass' ) {
		$pluginName			=	urldecode( stripslashes( cbGetParam( $_REQUEST, "plugin" ) ) );
		if ( ! $pluginName ) {
			return;
		}
		$tabClassName		=	'CBplug_' . strtolower( substr( str_replace( $unsecureChars, '', $pluginName ), 0, 32 ) );
		$method				=	'getCBpluginComponent';
	}
	$tabs					=	$cbUser->_getCbTabs( false );
	if ( $task == 'fieldclass' ) {
		$result			=	$tabs->fieldCall( $fieldName, $user, $_POST, $reason );
	} else {
		$result				=	$tabs->tabClassPluginTabs( $user, $_POST, $pluginName, $tabClassName, $method );
	}
	if ( $result === false ) {
	 	if( $_PLUGINS->is_errors() ) {
			echo "<script type=\"text/javascript\">alert(\"" . $_PLUGINS->getErrorMSG() . "\"); </script>\n";
	 	}
	} elseif ( $result !== null ) {
		echo $result;
	}
}

function usersList( $uid ) {
	global $_CB_database, $_CB_framework, $ueConfig, $Itemid, $_PLUGINS, $_POST, $_REQUEST;

	cbimport( 'cb.lists' );

	if ( isset( $_POST['listid'] ) ) {
		$listid				=	(int) cbGetParam( $_POST, 'listid', 0 );
	} else {
		$listid				=	(int) cbGetParam( $_GET, 'listid', 0 );
	}
	$searchFormValuesRAW	=	$_GET;

	$cbList					=	new cbUsersList();
	$cbList->drawUsersList( $uid, $listid, $searchFormValuesRAW );
}

function lostPassForm( $option ) {
	global $_CB_framework;

	$_CB_framework->setPageTitle( _PROMPT_PASSWORD );
	HTML_comprofiler::lostPassForm( $option );
}

function sendNewPass( $option ) {
	global $_CB_framework, $_CB_database, $ueConfig, $Itemid, $_PLUGINS, $_POST;

	// simple spoof check security
	cbSpoofCheck( 'lostPassForm' );
	cbRegAntiSpamCheck();

	$usernameExists	=	( ( isset( $ueConfig['login_type'] ) ) && ( $ueConfig['login_type'] < 2 ) );

	// ensure no malicous sql gets past
	$checkusername	=	trim( cbGetParam( $_POST, 'checkusername', '' ) );
	$confirmEmail	=	trim( cbGetParam( $_POST, 'checkemail', ''    ) );

	$_PLUGINS->loadPluginGroup('user');
	$_PLUGINS->trigger( 'onStartNewPassword', array( &$checkusername, &$confirmEmail ));
	if ($_PLUGINS->is_errors()) {
		cbRedirect( cbSef("index.php?option=$option&amp;task=lostPassword".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), $_PLUGINS->getErrorMSG(), 'error' );
		return;
	}
	$checkusername	=	stripslashes( $checkusername );
	$confirmEmail	=	stripslashes( $confirmEmail );

	// these two are used by _NEWPASS_SUB message below:
	$_live_site		=	$_CB_framework->getCfg( 'live_site' );
	$_sitename		=	"";	// NEEDED BY _NEWPASS_SUB for  sitename already added in subject by cbNotification class. was = $_CB_framework->getCfg( 'sitename' );

	if ( $usernameExists && ( $confirmEmail != '' ) && ! $checkusername ) {
		$_CB_database->setQuery( "SELECT id, username FROM #__users"
		. "\n WHERE email = " . $_CB_database->Quote( $confirmEmail )
		);
		$userIdUsername	=	null;
		$result			=	$_CB_database->loadObjectList( $userIdUsername );
		if ( $_CB_database->getErrorNum() || ( count( $result ) == 0 ) ) {
			cbRedirect( cbSef( 'index.php?option=' . $option . '&amp;task=lostPassword' . ( $Itemid ? '&amp;Itemid=' . (int) $Itemid : '' ), false ), sprintf( _UE_EMAIL_DOES_NOT_EXISTS_ON_SITE, htmlspecialchars( $confirmEmail ) ), 'error' );
		}
		foreach ( $result as $userIdUsername ) {
			$message = str_replace( '\n', "\n", sprintf( _UE_USERNAMEREMINDER_MSG, $_CB_framework->getCfg( 'sitename' ), $userIdUsername->username, $_live_site ) );
			$subject = sprintf( _UE_USERNAMEREMINDER_SUB, $userIdUsername->username );

			$_PLUGINS->trigger( 'onBeforeUsernameReminder', array( $userIdUsername->id, &$subject, &$message ));
			if ($_PLUGINS->is_errors()) {
				cbRedirect( cbSef("index.php?option=$option&amp;task=lostPassword".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), $_PLUGINS->getErrorMSG(), 'error' );
				return;
			}

			$cbNotification = new cbNotification();
			$res	=	$cbNotification->sendFromSystem( $userIdUsername->id, $subject, $message );
			if ( ! $res ) {
				break;
			}
		}
		$_PLUGINS->trigger( 'onAfterUsernameReminder', array( &$result, &$res ) );
		if ( $res ) {
			cbRedirect( cbSef("index.php?option=$option&amp;task=done".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), sprintf( _UE_USERNAME_REMINDER_SENT, htmlspecialchars( $confirmEmail ) ) );
		} else {
			cbRedirect( cbSef("index.php?option=$option&amp;task=done".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ),_UE_EMAIL_SENDING_ERROR );
		}

	} elseif ( $confirmEmail != '' ) {
		if ( $usernameExists ) {
			$_CB_database->setQuery( "SELECT id FROM #__users"
			. "\n WHERE username = " . $_CB_database->Quote( $checkusername ) . " AND email = " . $_CB_database->Quote( $confirmEmail )
			);
		} else {
			$_CB_database->setQuery( "SELECT id FROM #__users"
			. "\n WHERE email = " . $_CB_database->Quote( $confirmEmail )
			);
		}
		$user_id	=	$_CB_database->loadResult();
		if ( ! $user_id ) {
			cbRedirect( cbSef( 'index.php?option=' . $option . '&amp;task=lostPassword' . ( $Itemid ? '&amp;Itemid=' . (int) $Itemid : '' ), false ), _ERROR_PASS );
		}

		$newpass = cbMakeRandomString( 8, true );		// should be $user->setRandomPassword() but as this whole function needs to be redone to require clicking link for new password change, let's leave it for now.
		$message = str_replace( '\n', "\n", sprintf( _UE_NEWPASS_MSG, $checkusername, $_live_site, $newpass ) );
		$subject = sprintf( _UE_NEWPASS_SUB, $checkusername );

		$_PLUGINS->trigger( 'onBeforeNewPassword', array( $user_id, &$newpass, &$subject, &$message ));
		if ($_PLUGINS->is_errors()) {
			cbRedirect( cbSef("index.php?option=$option&amp;task=lostPassword".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), $_PLUGINS->getErrorMSG(), 'error' );
			return;
		}

		$cbNotification = new cbNotification();
		$res	=	$cbNotification->sendFromSystem($user_id,$subject,$message);

		if ($res) {
			$_PLUGINS->trigger( 'onNewPassword', array($user_id,$newpass));

			$cbUser		=	CBuser::getInstance( (int) $user_id );
			$user		=	$cbUser->getUserData();
			$newpass	=	$user->hashAndSaltPassword( $newpass );
			$sql		=	"UPDATE #__users SET password = " . $_CB_database->Quote( $newpass ) . " WHERE id = " . (int) $user_id;
			$_CB_database->setQuery( $sql );
			if (!$_CB_database->query()) {
				die("SQL error" . $_CB_database->stderr(true));
			}
			cbRedirect( cbSef("index.php?option=$option&amp;task=done".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), sprintf( _UE_NEWPASS_SENT, htmlspecialchars( $confirmEmail ) ) );
		} else {
			cbRedirect( cbSef("index.php?option=$option&amp;task=done".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ),_UE_NEWPASS_FAILED );
		}
	} else {
		cbRedirect( cbSef("index.php?option=$option&amp;task=done".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), _UE_NEWPASS_FAILED );
	}
}

function registerForm( $option, $emailpass, $regErrorMSG = null ) {
	global $_CB_framework, $_CB_database, $ueConfig, $_PLUGINS, $_POST;

	if ( ( ( $_CB_framework->getCfg( 'allowUserRegistration' ) == '0' )
		   && ( ( ! isset($ueConfig['reg_admin_allowcbregistration']) ) || $ueConfig['reg_admin_allowcbregistration'] != '1' ) ) )
	{
		cbNotAuth();
		return;
	}
	if ( $_CB_framework->myId() ) {
		echo '<div class="error">' . _UE_ALREADY_LOGGED_IN . '</div>';
		return;
	}
	$fieldsQuery	=	null;

	$_PLUGINS->loadPluginGroup('user');

	$results							=	$_PLUGINS->trigger( 'onBeforeRegisterForm', array( $option, $emailpass, &$regErrorMSG, $fieldsQuery ) );
	if($_PLUGINS->is_errors()) {
		echo "<script type=\"text/javascript\">alert('".addslashes($_PLUGINS->getErrorMSG(" ; "))."'); </script>\n";
		echo $_PLUGINS->getErrorMSG("<br />");
		return;
	}
	if ( implode( '', $results ) != "" ) {
		$allResults						=	implode( "</div><div>", $results );
		echo "<div>" . $allResults . "</div>";
		return;
	}
	$userComplete						=	new moscomprofilerUser( $_CB_database );
	if ( $regErrorMSG !== null ) {
		HTML_comprofiler::registerForm( $option, $emailpass, $userComplete, $_POST, $regErrorMSG );
	} else {
		$null							=	null;
		HTML_comprofiler::registerForm( $option, $emailpass, $userComplete, $null, $regErrorMSG );
	}
}

function saveRegistration( $option ) {
	global $_CB_framework, $_CB_database, $ueConfig, $_POST, $_PLUGINS;

	// simple spoof check security
	cbSpoofCheck( 'registerForm' );
	cbRegAntiSpamCheck();

	// Check rights to access:

	if ( ( ( $_CB_framework->getCfg( 'allowUserRegistration' ) == '0' )
		   && ( ( ! isset($ueConfig['reg_admin_allowcbregistration']) ) || $ueConfig['reg_admin_allowcbregistration'] != '1' ) )
		 || $_CB_framework->myId() ) {
		cbNotAuth();
		return;
	}
	if ( ! isset( $ueConfig['emailpass'] ) ) {
		$ueConfig['emailpass']			=	'0';
	}

	$userComplete						=	new moscomprofilerUser( $_CB_database );

	// Pre-registration trigger:

	$_PLUGINS->loadPluginGroup('user');
	$_PLUGINS->trigger( 'onStartSaveUserRegistration', array() );
	if( $_PLUGINS->is_errors() ) {
		echo "<script type=\"text/javascript\">alert('".addslashes($_PLUGINS->getErrorMSG())."'); </script>\n";
		$oldUserComplete				=	new moscomprofilerUser( $_CB_database );
		$userComplete->bindSafely( $_POST, $_CB_framework->getUi(), 'register', $oldUserComplete );
		HTML_comprofiler::registerForm( $option, $ueConfig['emailpass'], $userComplete, $_POST, $_PLUGINS->getErrorMSG("<br />") );
		return;
	}

	// Check if this user already registered with exactly this username and password:

	$username							=	cbGetParam( $_POST, 'username', '' );
	$usernameExists						=	$userComplete->loadByUsername( $username );
	if ( $usernameExists ) {
		$password						=	cbGetParam( $_POST, 'password', '', _CB_ALLOWRAW );
		if ( $userComplete->verifyPassword( $password ) ) {
			$pwd_md5					=	$userComplete->password;
			$userComplete->password		=	$password;
			$messagesToUser				=	activateUser( $userComplete, 1, 'SameUserRegistrationAgain' );
			$userComplete->password		=	$pwd_md5;
			echo "\n<div>" . implode( "</div>\n<div>", $messagesToUser ) . "</div>\n";
			return;
		} else {
			$msg						=	sprintf( _UE_USERNAME_ALREADY_EXISTS, $username );
			echo "<script type=\"text/javascript\">alert('" . addslashes( $msg ) . "'); </script>\n";
			$oldUserComplete				=	new moscomprofilerUser( $_CB_database );
			$userComplete->bindSafely( $_POST, $_CB_framework->getUi(), 'register', $oldUserComplete );
			HTML_comprofiler::registerForm( $option, $ueConfig['emailpass'], $userComplete, $_POST, htmlspecialchars( $msg ) );
			return;
		}
	}

	// Store and check terms and conditions accepted (not a field yet !!!!):

	if ( isset( $_POST['acceptedterms'] ) ) {
		$userComplete->acceptedterms	=	( (int) cbGetParam( $_POST, 'acceptedterms', 0 ) == 1 ? 1 : 0 );
	} else {
		$userComplete->acceptedterms	=	null;
	}

	if($ueConfig['reg_enable_toc']) {
		if ( $userComplete->acceptedterms != 1 ) {
			echo "<script type=\"text/javascript\">alert('" . addslashes( cbUnHtmlspecialchars( _UE_TOC_REQUIRED ) ) ."'); </script>\n";
			$oldUserComplete				=	new moscomprofilerUser( $_CB_database );
			$userComplete->bindSafely( $_POST, $_CB_framework->getUi(), 'register', $oldUserComplete );
			HTML_comprofiler::registerForm( $option, $ueConfig['emailpass'], $userComplete, $_POST, _UE_TOC_REQUIRED . '<br />' );
			return;
		}
	}

	// Set id to 0 for autoincrement and store IP address used for registration:

	$userComplete->id			 		=	0;
	$userComplete->registeripaddr		=	cbGetIPlist();


	// Store new user state:

	$saveResult					=	$userComplete->saveSafely( $_POST, $_CB_framework->getUi(), 'register' );
	if ( $saveResult === false ) {
		echo "<script type=\"text/javascript\">alert('" . str_replace( '\\\\n', '\\n', addslashes( strip_tags( str_replace( '<br />', '\n', $userComplete->getError() ) ) ) ) ."'); </script>\n";
		HTML_comprofiler::registerForm( $option, $ueConfig['emailpass'], $userComplete, $_POST, $userComplete->getError() );
		return;
	}

	if ( $saveResult['ok'] === true ) {
		$messagesToUser			=	activateUser( $userComplete, 1, "UserRegistration" );
	}
	foreach ( $saveResult['tabs'] as $res ) {
		if ($res) {
			$messagesToUser[] = $res;
		}
	}
	if ( $saveResult['ok'] === false ) {
		echo "<script type=\"text/javascript\">alert('" . str_replace( '\\\\n', '\\n', addslashes( strip_tags( str_replace( '<br />', '\n', $userComplete->getError() ) ) ) ) . "'); </script>\n";
		HTML_comprofiler::registerForm( $option, $ueConfig['emailpass'], $userComplete, $_POST, $userComplete->getError() );
		return;
	}

	$_PLUGINS->trigger( 'onAfterUserRegistrationMailsSent', array( &$userComplete, &$userComplete, &$messagesToUser, $ueConfig['reg_confirmation'], $ueConfig['reg_admin_approval'], true));

	foreach ( $saveResult['after'] as $res ) {
		if ( $res ) {
			echo "\n<div>" . $res . "</div>\n";
		}
	}

	if ( $_PLUGINS->is_errors() ) {
		echo $_PLUGINS->getErrorMSG();
		HTML_comprofiler::registerForm( $option, $ueConfig['emailpass'], $userComplete, $_POST, $_PLUGINS->getErrorMSG() );
		return;
	}

	echo "\n<div>" . implode( "</div>\n<div>", $messagesToUser ) . "</div>\n";
}


/**
 * Ajax function: Checks the availability of a username for registration and echoes a text containing the result of username search.
 *
 * @param string $username
 */
function performCheckUsername( $username, $function ) {
	global $_CB_database, $ueConfig;

	if ( ( ! isset( $ueConfig['reg_username_checker'] ) ) || ( ! $ueConfig['reg_username_checker'] ) ) {
		echo ISOtoUtf8( _UE_NOT_AUTHORIZED );
		exit();
	}
	// simple spoof check security
	cbSpoofCheck( 'registerForm' );
	cbRegAntiSpamCheck();

	$username	=	stripslashes( $username );
	$usernameISO =	utf8ToISO( $username );			// ajax sends in utf8, we need to convert back to the site's encoding.

	if ( $_CB_database->isDbCollationCaseInsensitive() ) {
		$query	=	"SELECT COUNT(*) AS result FROM #__users WHERE username = " . $_CB_database->Quote( ( trim( $usernameISO ) ) );
	} else {
		$query	=	"SELECT COUNT(*) AS result FROM #__users WHERE LOWER(username) = " . $_CB_database->Quote( ( strtolower( trim( $usernameISO ) ) ) );
	}
	$_CB_database->setQuery($query);
	$dataObj	=	null;
	if ( $_CB_database->loadObject( $dataObj ) ) {
		if ( $dataObj->result ) {
			// funily, the output does not need to be UTF8 again:
			if ( $function == 'testexists' ) {
				echo ( '<span class="cb_result_ok">' . sprintf( ISOtoUtf8( _UE_USERNAME_EXISTS_ON_SITE ), htmlspecialchars( $username ) ) . '</span>' );
			} else {
				echo ( '<span class="cb_result_error">' . sprintf( ISOtoUtf8( _UE_USERNAME_ALREADY_EXISTS ), htmlspecialchars( $username ) ) . '</span>' );
			}
		} else {
			if ( $function == 'testexists' ) {
				echo ( '<span class="cb_result_error">' . sprintf( ISOtoUtf8( _UE_USERNAME_DOES_NOT_EXISTS_ON_SITE ), htmlspecialchars( $username ) ) . '</span>' );
			} else {
				echo ( '<span class="cb_result_ok">' . sprintf( ISOtoUtf8( _UE_USERNAME_DOESNT_EXISTS ), htmlspecialchars( $username ) ) . '</span>' );
			}
		}
	} else {
		echo ( '<span class="cb_result_error">' . ISOtoUtf8( _UE_SEARCH_ERROR ) . ' !' . '</span>' );
	}
}

/**
 * Ajax function: Checks the availability of a username for registration and echoes a text containing the result of username search.
 *
 * @param string $username
 */
function performCheckEmail( $email, $function ) {
	global $_CB_framework, $_CB_database, $ueConfig;

	if ( ( ! isset( $ueConfig['reg_email_checker'] ) ) || ( ! $ueConfig['reg_email_checker'] ) ) {
		echo ISOtoUtf8( _UE_NOT_AUTHORIZED );
		exit();
	}
	// simple spoof check security
	if ( ( ! cbSpoofCheck( 'registerForm', 'POST', 2 ) ) || ( ! cbRegAntiSpamCheck( 2 ) ) ) {
		echo '<span class="cb_result_error">' . ISOtoUtf8( _UE_SESSION_EXPIRED ) . "</span>";
		exit;
	}

	$email		=	stripslashes( $email );
	$emailISO 	=	utf8ToISO( $email );				// ajax sends in utf8, we need to convert back to the site's encoding.

	if ( $ueConfig['reg_email_checker'] > 1 ) {
		if ( $_CB_database->isDbCollationCaseInsensitive() ) {
			$query	=	"SELECT COUNT(*) AS result FROM #__users WHERE email = " . $_CB_database->Quote( ( trim( $emailISO ) ) );
		} else {
			$query	=	"SELECT COUNT(*) AS result FROM #__users WHERE LOWER(email) = " . $_CB_database->Quote( ( strtolower( trim( $emailISO ) ) ) );
		}
		$_CB_database->setQuery($query);
		$dataObj	=	null;
		if ( $_CB_database->loadObject( $dataObj ) ) {
			if ( $function == 'testexists' ) {
				if ( $dataObj->result ) {
					echo '<span class="cb_result_ok">' . sprintf( ISOtoUtf8( _UE_EMAIL_EXISTS_ON_SITE ), htmlspecialchars( $email ) ) . "</span>";
					return;
				} else {
					echo '<span class="cb_result_error">' . sprintf( ISOtoUtf8( _UE_EMAIL_DOES_NOT_EXISTS_ON_SITE ), htmlspecialchars( $email ) ) . "</span>";
					return;
				}
			} else {
				if ( $dataObj->result ) {
					echo '<span class="cb_result_error">' . sprintf( ISOtoUtf8( _UE_EMAIL_ALREADY_REGISTERED ), htmlspecialchars( $email ) ) . "</span>";
					return;
				}
			}
		}
	}
	if ( $function == 'testexists' ) {
		echo ISOtoUtf8( _UE_NOT_AUTHORIZED );
		return;
	} else {
		$checkResult	=	cbCheckMail( $_CB_framework->getCfg( 'mailfrom' ), $email );
	}
	switch ( $checkResult ) {
		case -2:
			echo '<span class="cb_result_error">' . sprintf( ISOtoUtf8( _UE_EMAIL_NOVALID ), htmlspecialchars( $email ) ) . "</span>";
			break;
		case -1:
			echo '<span class="cb_result_warning">' . sprintf( ISOtoUtf8( _UE_EMAIL_COULD_NOT_CHECK ), htmlspecialchars( $email ) ) . "</span>";
			break;
		case 0:
			if ( $ueConfig['reg_confirmation'] == 0 ) {
				echo '<span class="cb_result_error">' . sprintf( ISOtoUtf8( _UE_EMAIL_INCORRECT_CHECK ), htmlspecialchars( $email ) ) . "</span>";
			} else {
				echo '<span class="cb_result_error">' . sprintf( ISOtoUtf8( _UE_EMAIL_INCORRECT_CHECK_NEEDED ), htmlspecialchars( $email ) ) . "</span>";
			}
			break;
		case 1:
			echo '<span class="cb_result_ok">' . sprintf( ISOtoUtf8( _UE_EMAIL_VERIFIED ), htmlspecialchars( $email ) ) . "</span>";
			break;
		default:
			echo '<span class="cb_result_error">performCheckEmail:: Unexpected cbCheckMail result.</span>';
			break;
	}
}


function login( $username=null, $passwd2=null ) {
    global $_POST, $_CB_framework, $ueConfig;

    if ( count( $_POST ) == 0 ) {
    	HTML_comprofiler::loginForm( 'com_comprofiler', $_POST, null );
    	return;
    }

    $spoofCheckOk		=	false;
    if ( cbSpoofCheck( 'login', 'POST', 2 ) ) {
    	$spoofCheckOk	=	true;
    } else {
		if ( is_callable("josSpoofCheck") && is_callable("josSpoofValue") ) {
			$validate = josSpoofValue();
    		if ( cbGetParam( $_POST, $validate ) ) {
				josSpoofCheck(1);
		    	$spoofCheckOk	=	true;
    		}
		}
    }
    if ( ! $spoofCheckOk ) {
    	echo  _UE_SESSION_EXPIRED . ' ' . _UE_PLEASE_REFRESH;
    	return;
    }

	$messagesToUser		=	array();
	$alertmessages		=	array();

    if ( !$username || !$passwd2 ) {
		$username		=	trim( cbGetParam( $_POST, 'username', '' ) );
		$passwd2		=	trim( cbGetParam( $_POST, 'passwd', '', _CB_ALLOWRAW ) );
    }
	$rememberMe			=	cbGetParam( $_POST, 'remember' );
    $return				=	trim( stripslashes( cbGetParam( $_POST, 'return', null ) ) );
	if ( cbStartOfStringMatch( $return, 'B:' ) ) {
		$return			=	base64_decode( substr( $return, 2 ) );
		$arrToClean		=	array( 'B' => get_magic_quotes_gpc() ? addslashes( $return ) : $return );
		$return			=	cbGetParam( $arrToClean, 'B', '' );
	}
	if ( ! ( ( cbStartOfStringMatch( $return, $_CB_framework->getCfg( 'live_site' ) ) || cbStartOfStringMatch( $return, 'index.php' ) ) ) ) {
		$return			=	'';
	}
	$message			=	trim( cbGetParam( $_POST, 'message', 0 ) );

	$loginType			=	( isset( $ueConfig['login_type'] ) ? $ueConfig['login_type'] : 0 );

	// Do the login including all authentications and event firing:
	cbimport( 'cb.authentication' );
	$cbAuthenticate		=	new CBAuthentication();
	$resultError		=	$cbAuthenticate->login( $username, $passwd2, $rememberMe, $message, $return, $messagesToUser, $alertmessages, $loginType );

	if ( count( $messagesToUser ) > 0 ) {
		if ( $resultError ) {
			echo "<div class=\"message\">".$resultError."</div>";
		}
		echo "\n<div>" . stripslashes(  implode( "</div>\n<div>", $messagesToUser ) ) . "</div>\n";
		if ( in_array( cbGetParam( $_POST, 'loginfrom' ), array( 'loginform', 'regform', 'loginmodule' ) ) ) {
	    	HTML_comprofiler::loginForm( 'com_comprofiler', $_POST, $resultError );
		}
	} elseif ($resultError) {
		if ( in_array( cbGetParam( $_POST, 'loginfrom' ), array( 'loginform', 'regform', 'loginmodule' ) ) ) {
	    	HTML_comprofiler::loginForm( 'com_comprofiler', $_POST, $resultError );
		} else {
			echo "<div class=\"message\">".$resultError."</div>";
		}
	} else {
		cbRedirect( cbSef( $return, false ), ( count( $alertmessages ) > 0 ? stripslashes( implode( '\n', $alertmessages ) ) : '' ) );
	}
}

function logout() {
	global $_POST, $_CB_framework, $_CB_database, $_PLUGINS;

	$return					=	trim( stripslashes( cbGetParam( $_POST, 'return', null ) ) );
	if ( cbStartOfStringMatch( $return, 'B:' ) ) {
		$return				=	base64_decode( substr( $return, 2 ) );
		$arrToClean			=	array( 'B' => get_magic_quotes_gpc() ? addslashes( $return ) : $return );
		$return				=	cbGetParam( $arrToClean, 'B', '' );
	}
	$message				=	trim( cbGetParam( $_POST, 'message', 0 ) );

	if ($return || $message) {
	    $spoofCheckOk		=	false;
	    if ( cbSpoofCheck( 'logout', 'POST', 2 ) ) {
	    	$spoofCheckOk	=	true;
	    } else {
			if ( is_callable("josSpoofCheck") && is_callable("josSpoofValue") ) {
				$validate = josSpoofValue();
	    		if ( cbGetParam( $_POST, $validate ) ) {
					josSpoofCheck(1);
			    	$spoofCheckOk	=	true;
	    		}
			}
	    }
	    if ( ! $spoofCheckOk ) {
	    	echo  _UE_SESSION_EXPIRED . ' ' . _UE_PLEASE_REFRESH;
	    	return;
	    }
	}

	// Do the logout including all authentications and event firing:
	cbimport( 'cb.authentication' );
	$cbAuthenticate		=	new CBAuthentication();
	$resultError		=	$cbAuthenticate->logout( $return );

	if ( $resultError ) {
		echo "<script type=\"text/javascript\">alert('".addslashes($_PLUGINS->getErrorMSG())."');</script>\n";
		echo "<div class=\"message\">".$_PLUGINS->getErrorMSG()."</div>";;
		return;
	}

	cbRedirect( cbSef( ( $return ? $return : 'index.php' ), false ), ( $message ? stripslashes( _LOGOUT_SUCCESS ) : '' ) );
}
function confirm( $confirmcode ) {
	global $_CB_database, $_CB_framework, $ueConfig, $_PLUGINS;

	if( $_CB_framework->myId() < 1 ) {
		$unscrambledId						=	moscomprofilerUser::getUserIdFromActivationCode( $confirmcode );
		if ( $unscrambledId ) {
			$cbUser							=	CBuser::getInstance( (int) $unscrambledId );
			if ( $cbUser ) {
				$user						=	$cbUser->getUserData();
				if ( $user && $user->id ) {
					if ( $user->confirmed == 0 ) {
						if ( $user->checkActivationCode( $confirmcode ) ) {
							// THIS is the normal case: user exists, is not yet confirmed, and confirmation code does match:
							$messagesToUser	=	null;
							$confirmed		=	$user->confirmUser( $messagesToUser );
						} else {
							// confirmation code does not match:
							$messagesToUser	=	array( _UE_WRONG_CONFIRMATION_CODE );
							$confirmed		=	false;
						}
					} else {
						// User has already confirmed: show friendly activation messages depending on his state:
						$messagesToUser		=	getActivationMessage( $user, 'UserConfirmation' );
						$confirmed			=	true;
					}

					if ( $confirmed ) {
						// THIS is the normal case: user exists, is not yet confirmed, and confirmation code does match:
						$class				=	'cbconfirmationinfo';
					} else {
						$class				=	'error';
					}
					echo "\n" . '<div class="cbconfirming"><div class="' . $class . '">' . implode( '</div><div class="' . $class . '">', $messagesToUser ) . "</div></div>\n";
					return;
				}
			}
		}
		// this is the error case where the URL is simply not right:
		cbNotAuth();
		return;
	} else {
		// this is the case where the user is already logged in (mostly test-cases):
		echo '<div class="error">' . _UE_NOT_AUTHORIZED." :<br /><br />"._UE_DO_LOGOUT." !</div>";
	}
}


function approveImage() {
	global $_CB_database, $_POST, $_REQUEST, $_SERVER, $_CB_framework;

	$andItemid = getCBprofileItemid();

	// simple spoof check security for posts (menus do gets):
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		cbSpoofCheck( 'moderateImages' );
	}
	$isModerator=isModerator( $_CB_framework->myId() );
	if (!$isModerator) {
		cbNotAuth();
		return;
	}
	$avatars=array();
	if(isset($_POST['avatar'])) $avatars=$_POST['avatar'];
	else $avatars[] = $_REQUEST['avatars'];
	if(isset($_POST['act'])) $act=$_POST['act'];
	else $act = $_REQUEST['flag'];
	$cbNotification = new cbNotification();
	if($act=='1') {
		foreach ($avatars AS $avatar) {
			$query = "UPDATE #__comprofiler SET avatarapproved = 1, lastupdatedate=". $_CB_database->Quote( $_CB_framework->dateDbOfNow() ) ." WHERE id = " . (int) $avatar;
			$_CB_database->setQuery($query);
			$_CB_database->query();
			$cbNotification->sendFromSystem( (int) $avatar, _UE_IMAGEAPPROVED_SUB, _UE_IMAGEAPPROVED_MSG );
		}
	} else {
		foreach ($avatars AS $avatar) {
			$query = "SELECT avatar FROM #__comprofiler WHERE id = " . (int) $avatar;
			$_CB_database->setQuery($query);
			$file = $_CB_database->loadResult();
		   	if(preg_match("/gallery\\//i",$file)==false && is_file($_CB_framework->getCfg('absolute_path')."/images/comprofiler/".$file)) {
				unlink($_CB_framework->getCfg('absolute_path')."/images/comprofiler/".$file);
				if(is_file($_CB_framework->getCfg('absolute_path')."/images/comprofiler/tn".$file)) unlink($_CB_framework->getCfg('absolute_path')."/images/comprofiler/tn".$file);
			}
			$query = "UPDATE #__comprofiler SET avatarapproved = 1, avatar=null WHERE id = " . (int) $avatar;
			$_CB_database->setQuery($query);
			$_CB_database->query();
			$cbNotification->sendFromSystem( (int) $avatar, _UE_IMAGEREJECTED_SUB, _UE_IMAGEREJECTED_MSG );
		}

	}
	cbRedirect( cbSef( 'index.php?option=com_comprofiler&amp;task=moderateImages' . $andItemid, false ), _UE_USERIMAGEMODERATED_SUCCESSFUL);
}

function reportUser($option,$form=1,$uid=0) {
	global $_CB_framework, $_CB_database, $ueConfig, $Itemid, $_POST;

	if($ueConfig['allowUserReports']==0) {
			echo _UE_FUNCTIONALITY_DISABLED;
			exit();
	}
	if (!allowAccess( $ueConfig['allow_profileviewbyGID'],'RECURSE', userGID( $_CB_framework->myId() ))) {
		echo _UE_NOT_AUTHORIZED;
		return;
	}
	if($form==1) {
		HTML_comprofiler::reportUserForm($option,$uid);
	} else {
		// simple spoof check security
		cbSpoofCheck( 'reportUserForm' );

		$row = new moscomprofilerUserReport( $_CB_database );

		if (!$row->bind( $_POST )) {
			cbRedirect( cbSef("index.php?option=$option&amp;task=reportUser".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), $row->getError(), 'error' );
			return;
		}

		_cbMakeHtmlSafe($row);			//TBD: remove this: not urgent but isn't right

		$row->reportedondate = date("Y-m-d H:i:s");

		if (!$row->check()) {
			cbRedirect( cbSef("index.php?option=$option&amp;task=reportUser".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), $row->getError(), 'error' );
			return;
		}

		if (!$row->store()) {
			cbRedirect( cbSef("index.php?option=$option&amp;task=reportUser".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), $row->getError(), 'error' );
			return;
		}
		if($ueConfig['moderatorEmail']==1) {
			$cbNotification = new cbNotification();
			$cbNotification->sendToModerators(_UE_USERREPORT_SUB,_UE_USERREPORT_MSG);
		}
		echo _UE_USERREPORT_SUCCESSFUL;
	}
}

function banUser( $option, $uid, $form=1, $act=1 ) {
	global $_CB_framework, $_CB_database, $ueConfig, $_POST;

	$isModerator=isModerator( $_CB_framework->myId() );
	if ( ( $_CB_framework->myId() < 1 ) || ( $uid < 1 ) )  {
			cbNotAuth();
			exit();
	}
	if ( $ueConfig['allowUserBanning'] == 0 ) {
			echo _UE_FUNCTIONALITY_DISABLED;
			exit();
	}

	if ( $form == 1 ) {
		$_CB_database->setQuery( "SELECT bannedreason FROM #__comprofiler WHERE id = " . (int) $uid );
		$orgbannedreason	=	$_CB_database->loadresult();

		HTML_comprofiler::banUserForm( $option, $uid, $act, $orgbannedreason);
	} else {

		$now				=	$_CB_framework->now();
		$dateStr			=	cbFormatDate( $now );

		$cbNotification		=	new cbNotification();
		if ( $act == 1 ) {
			// Ban by moderator:
			if ( ( ! $isModerator ) || ( $_CB_framework->myId() != cbGetParam( $_POST, 'bannedby', 0 ) ) ) {
				cbNotAuth();
				return;
			}
			// simple spoof check security
			cbSpoofCheck( 'banUserForm' );

			$bannedreason	=	'<b>' . htmlspecialchars("["._UE_MODERATORBANRESPONSE.", " . $dateStr . "]") . "</b>\n" . htmlspecialchars( stripslashes( cbGetParam( $_POST, 'bannedreason') ) ) ."\n";
			$sql="UPDATE #__comprofiler SET banned=1, bannedby=" . (int) $_CB_framework->myId() . ", banneddate='".date('Y-m-d\TH:i:s')."', bannedreason = CONCAT_WS('','" . $_CB_database->getEscaped( $bannedreason ) . "', bannedreason) WHERE id=". (int) $uid;
			$_CB_database->SetQuery($sql);
			$_CB_database->query();

			$cbNotification->sendFromSystem($uid,_UE_BANUSER_SUB,_UE_BANUSER_MSG);
			echo _UE_USERBAN_SUCCESSFUL;
		} elseif ( $act == 0 ) {
			// Unban by moderator:
			if (!$isModerator){
				cbNotAuth();
				return;
			}
			// $mineName		=	getNameFormat($_CB_framework->myName(), $_CB_framework->myUsername,$ueConfig['name_format']);
			// DEFINE('_UE_UNBANUSER_BY_ON','User profile unbanned by %s on %s');
			// $unbannedBy	=	"<b>" . addslashes( htmlspecialchars("[".sprintf( _UE_UNBANUSER_BY_ON, $mineName, $dateStr ) ) ) . "]</b>\n";
			$unbannedBy	=	"<b>" . htmlspecialchars("[". _UE_UNBANUSER . ", " . $dateStr ) . "]</b>\n";
			$sql="UPDATE #__comprofiler SET banned=0, unbannedby=" . (int) $_CB_framework->myId() . ", bannedreason = CONCAT_WS('','" . $_CB_database->getEscaped( $unbannedBy ) . "', bannedreason), unbanneddate='".date('Y-m-d\TH:i:s')."'  WHERE id=".(int) $uid;				// , bannedreason=null, bannedby=null, banneddate=null
			$_CB_database->SetQuery($sql);
			$_CB_database->query();
			$cbNotification->sendFromSystem($uid,_UE_UNBANUSER_SUB,_UE_UNBANUSER_MSG);

			echo _UE_USERUNBAN_SUCCESSFUL;
		} elseif ( $act == 2 ) {
			// Unban request from user:
			if ( $_CB_framework->myId() != $uid ) {
				cbNotAuth();
				return;
			}
			$bannedreason = "<b>".htmlspecialchars("["._UE_USERBANRESPONSE.", " . $dateStr . "]")."</b>\n" . htmlspecialchars( stripslashes( cbGetParam( $_POST, 'bannedreason' ) ) ) ."\n";
			$sql="UPDATE #__comprofiler SET banned=2, bannedreason = CONCAT_WS('','" . $_CB_database->getEscaped( $bannedreason) . "', bannedreason) WHERE id=" . (int) $uid;
			$_CB_database->SetQuery($sql);
			$_CB_database->query();
			if($ueConfig['moderatorEmail']==1) {
				$cbNotification->sendToModerators(_UE_UNBANUSERREQUEST_SUB,_UE_UNBANUSERREQUEST_MSG);
			}
			echo _UE_USERUNBANREQUEST_SUCCESSFUL;

		}
	}
}

function processReports(){
	global $_CB_framework, $_CB_database, $_POST;

	// simple spoof check security
	cbSpoofCheck( 'moderateReports' );

	$isModerator=isModerator( $_CB_framework->myId() );
	if (!$isModerator) {
		cbNotAuth();
		return;
	}
	$reports	=	cbGetParam( $_POST, 'reports', array() );
	foreach ($reports AS $report) {
		$query = "UPDATE #__comprofiler_userreports SET reportedstatus = 1 WHERE reportid = " . (int) $report;
		$_CB_database->setQuery($query);
		$_CB_database->query();
	}
	cbRedirect( cbSef( 'index.php?option=com_comprofiler&amp;task=moderateReports' . getCBprofileItemid(), false ), _UE_USERREPORTMODERATED_SUCCESSFUL );
}

function moderator(){
  global $_CB_framework, $_CB_database;
	$isModerator=isModerator( $_CB_framework->myId() );
	if (!$isModerator) {
		cbNotAuth();
		return;
	}
	$query = "SELECT count(*) FROM #__comprofiler  WHERE avatarapproved=0 AND approved=1 AND confirmed=1 AND banned=0";
	if(!$_CB_database->setQuery($query)) print $_CB_database->getErrorMsg();
	$totalimages = $_CB_database->loadResult();

	$query = "SELECT count(*) FROM #__comprofiler_userreports  WHERE reportedstatus=0 ";
	if(!$_CB_database->setQuery($query)) print $_CB_database->getErrorMsg();
	$totaluserreports = $_CB_database->loadResult();

	$query = "SELECT count(*) FROM #__comprofiler WHERE banned=2 AND approved=1 AND confirmed=1";
	if(!$_CB_database->setQuery($query)) print $_CB_database->getErrorMsg();
	$totalunban = $_CB_database->loadResult();

	if($totalunban > 0 || $totaluserreports > 0 || $totalimages > 0) {
		if($totalunban > 0) echo "<div>".$totalunban._UE_UNBANREQUIREACTION."</div>";
		if($totaluserreports > 0) echo "<div>".$totaluserreports._UE_USERREPORTSREQUIREACTION."</div>";
		if($totalimages > 0) echo "<div>".$totalimages._UE_IMAGESREQUIREACTION."</div>";


	} else {
		echo _UE_NOACTIONREQUIRED;

	}

}


function approveUser($uids) {
	global $_CB_framework, $_CB_database, $ueConfig, $_PLUGINS, $Itemid;

	$andItemid = getCBprofileItemid();

	// simple spoof check security
	cbSpoofCheck( 'pendingApprovalUsers' );

	if($ueConfig['allowModUserApproval']==0) {
			echo _UE_FUNCTIONALITY_DISABLED;
			exit();
	}

	$isModerator=isModerator( $_CB_framework->myId() );
	if (!$isModerator){
		cbNotAuth();
		return;
	}

	$_PLUGINS->loadPluginGroup('user');

	if ( ! isset( $ueConfig['emailpass'] ) ) {
		$ueConfig['emailpass']	=	'0';
	}

	foreach($uids AS $uid) {
		$cbUser				=	CBuser::getInstance( (int) $uid );
		$user				=	$cbUser->getUserData();
		if ( $user->approved != 1 ) {
			if ( $ueConfig['emailpass'] == "1" ) {
				$user->setRandomPassword();
			}
			$_PLUGINS->trigger( 'onBeforeUserApproval', array( $user, true ) );
			if($_PLUGINS->is_errors()) {
				cbRedirect( cbSef("index.php?option=com_comprofiler&amp;task=pendingApprovalUser".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), $_PLUGINS->getErrorMSG(), 'error' );
				return;
			}
			$user->approved		=	1;
			$user->storeApproved();
			if ( $ueConfig['emailpass'] == "1" ) {
				$user->storePassword();
			}
			$_PLUGINS->trigger( 'onAfterUserApproval', array( $user, true, true ) );
			activateUser($user, 1, "UserApproval", false);
		}
	}
	cbRedirect( cbSef( 'index.php?option=com_comprofiler&amp;task=pendingApprovalUser' . $andItemid, false ), ( count( $uids ) ) ? count( $uids ) . ' ' . _UE_USERAPPROVAL_SUCCESSFUL : '' );

}

function rejectUser($uids) {
	global $_CB_framework, $_CB_database, $ueConfig, $_POST, $_PLUGINS;

	$andItemid = getCBprofileItemid();

	// simple spoof check security
	cbSpoofCheck( 'pendingApprovalUsers' );

	if($ueConfig['allowModUserApproval']==0) {
			echo _UE_FUNCTIONALITY_DISABLED;
			exit();
	}

	$isModerator=isModerator( $_CB_framework->myId() );
	if (!$isModerator){
		cbNotAuth();
		return;
	}

	$cbNotification= new cbNotification();
	foreach($uids AS $uid) {
		$query = "SELECT * FROM #__comprofiler c, #__users u WHERE c.id=u.id AND c.id = " . (int) $uid;
		$_CB_database->setQuery($query);
		$user = $_CB_database->loadObjectList();
		$row = $user[0];
		$_PLUGINS->loadPluginGroup('user');
		$_PLUGINS->trigger( 'onBeforeUserApproval', array($row,false));
		if($_PLUGINS->is_errors()) {
			cbRedirect( cbSef("index.php?option=$option&amp;task=pendingApprovalUser".($Itemid ? "&amp;Itemid=". (int) $Itemid : ""), false ), $_PLUGINS->getErrorMSG(), 'error' );
			return;
		}
		$sql="UPDATE #__comprofiler SET approved=2 WHERE id=" . (int) $uid;
		$_CB_database->SetQuery($sql);
		$_CB_database->query();
		$_PLUGINS->trigger( 'onAfterUserApproval', array($row,false,true));
		$cbNotification->sendFromSystem(cbGetEscaped($uid),_UE_REG_REJECT_SUB,sprintf(_UE_USERREJECT_MSG,$_CB_framework->getCfg( 'sitename' ), stripslashes( cbGetParam( $_POST, 'comment' . $uid, '' ) ) ) );
	}
	cbRedirect( cbSef( 'index.php?option=com_comprofiler&amp;task=pendingApprovalUser' . $andItemid, false ),(count($uids))?count($uids)." "._UE_USERREJECT_SUCCESSFUL:"");

}

function pendingApprovalUsers($option) {
	global $_CB_framework, $_CB_database, $ueConfig;

	$isModerator	=	isModerator( $_CB_framework->myId() );
	if($ueConfig['allowModUserApproval']==0) {
			echo _UE_FUNCTIONALITY_DISABLED;
			exit();
	}
	if (!$isModerator){
		cbNotAuth();
		return;
	}

	$_CB_database->setQuery( "SELECT u.id, u.name, u.username, u.email, u.registerDate "
	."\n FROM #__users u, #__comprofiler c "
	."\n WHERE u.id=c.id AND c.approved=0 AND c.confirmed=1" );
	$rows = $_CB_database->loadObjectList();

	HTML_comprofiler::pendingApprovalUsers($option, $rows);
}

//Connections

function addConnection($userid,$connectionid,$umsg=null) {
	global $_CB_framework, $ueConfig;

	$andItemid = getCBprofileItemid(true);

	if(!$ueConfig['allowConnections']) {
		echo _UE_FUNCTIONALITY_DISABLED;
		return;
	}
	if (! ($_CB_framework->myId() > 0 ) ) {
		cbNotAuth();
		return;
	}
	$cbCon=new cbConnection($userid);
	$cbCon->addConnection($connectionid,stripcslashes($umsg));
	$url=cbSef( "index.php?option=com_comprofiler&amp;task=userProfile&amp;user=" . $connectionid . $andItemid );
	echo "<script type=\"text/javascript\"> alert('".addslashes(htmlspecialchars($cbCon->getUserMSG()))."'); document.location.href='".cbUnHtmlspecialchars($url)."'; </script>\n";
}

function removeConnection( $userid, $connectionid ) {
	global $_CB_framework, $ueConfig;

	$andItemid	=	getCBprofileItemid(true);

	if ( ! $ueConfig['allowConnections'] ) {
		echo _UE_FUNCTIONALITY_DISABLED;
		return;
	}
	if ( ! ( $_CB_framework->myId() > 0 ) ) {
		cbNotAuth();
		return;
	}
	$cbCon		=	new cbConnection( $userid );
	if ( ! $cbCon->removeConnection( $userid, $connectionid ) ) {
		$msg	=	$cbCon->getErrorMSG();
	} else {
		$msg	=	$cbCon->getUserMSG();
	}

	// $url=cbSef("index.php?option=com_comprofiler&task=manageConnections");
	$url=cbSef( "index.php?option=com_comprofiler&amp;tab=getConnectionTab" . $andItemid );
	echo "<script type=\"text/javascript\"> alert('".addslashes($msg)."'); document.location.href='".cbUnHtmlspecialchars($url)."'; </script>\n";

}

function denyConnection( $userid, $connectionid ) {
	global $_CB_framework, $ueConfig;

	if(!$ueConfig['allowConnections']) {
		echo _UE_FUNCTIONALITY_DISABLED;
		return;
	}
	if (! ( $_CB_framework->myId() > 0 ) ) {
		cbNotAuth();
		return;
	}

	$cbCon		=	new cbConnection( $userid );
	$cbCon->denyConnection( $userid, $connectionid );

	echo "<script type=\"text/javascript\"> alert('".addslashes($cbCon->getUserMSG())."'); window.history.go(-1); </script>\n";			//TBD solve this as a redirect to ???

}

function acceptConnection($userid,$connectionid) {
	global $_CB_framework, $ueConfig;

	if(!$ueConfig['allowConnections']) {			// do not test, needed if rules changed! || !$ueConfig['useMutualConnections']
		echo _UE_FUNCTIONALITY_DISABLED;
		return;
	}
	if (! ( $_CB_framework->myId() > 0 ) ) {
		cbNotAuth();
		return;
	}

	$cbCon=new cbConnection($userid);
	$cbCon->acceptConnection($userid,$connectionid);

	echo "<script type=\"text/javascript\"> alert('".addslashes($cbCon->getUserMSG())."'); window.history.go(-1); </script>\n";			//TBD solve this as a redirect to ???
}

function manageConnections($userid) {
	global $_CB_framework, $ueConfig;

	if(!$ueConfig['allowConnections']) {
		echo _UE_FUNCTIONALITY_DISABLED;
		return;
	}
	if ( $_CB_framework->myId() != $userid || $_CB_framework->myId() == 0) {
		cbNotAuth();
		return;
	}

	$cbCon			=	new cbConnection( $userid );

	$connections	=	$cbCon->getActiveConnections( $userid );
	$tabs			=	new cbTabs( 0, $_CB_framework->getUi() );
	$tabs->element	=	'';
	$pagingParams	=	$tabs->_getPaging( array(), array( 'connections_' ) );

	$perpage		=	20;		//TBD unhardcode and get the code below better
	$total			=	$cbCon->getConnectionsCount( $userid, true );

	if ( $pagingParams["connections_limitstart"] === null ) {
		$pagingParams["connections_limitstart"]	=	0;
	}
	if ( $pagingParams["connections_limitstart"] > $total ) {
		$pagingParams["connections_limitstart"]	=	0;
	}
	$offset			=	( $pagingParams["connections_limitstart"] ? (int) $pagingParams["connections_limitstart"] : 0 );
	$connections	=	$cbCon->getActiveConnections( $userid, $offset, $perpage );

	$actions		=	$cbCon->getPendingConnections( $userid );

	$connecteds		=	$cbCon->getConnectedToMe( $userid );

	HTML_comprofiler::manageConnections( $connections, $actions, $total, $tabs, $pagingParams, $perpage, $connecteds );
}

function saveConnections($connectionids) {
	global $_CB_framework, $ueConfig, $_POST;

	$andItemid = getCBprofileItemid();

	// simple spoof check security
	cbSpoofCheck( 'manageConnections' );

	if(!$ueConfig['allowConnections']) {
		echo _UE_FUNCTIONALITY_DISABLED;
		return;
	}
	if ( ! ( $_CB_framework->myId() > 0 ) ) {
		cbNotAuth();
		return;
	}
	$cbCon	=	new cbConnection( $_CB_framework->myId() );
	if (is_array($connectionids)) {
		foreach($connectionids AS $cid) {
			$connectionTypes	=	cbGetParam( $_POST, $cid.'connectiontype', array() );
			$cbCon->saveConnection( $cid, stripslashes( cbGetParam( $_POST, $cid . 'description', '' ) ), implode( '|*|', $connectionTypes ) );
		}
	}
	cbRedirect( cbSef( 'index.php?option=com_comprofiler&amp;task=manageConnections&tab=1' . $andItemid, false ),
							(is_array($connectionids)) ? _UE_CONNECTIONSUPDATEDSUCCESSFULL : null);

}

function processConnectionActions($connectionids) {
	global $_CB_framework, $ueConfig, $_POST;

	// simple spoof check security
	cbSpoofCheck( 'manageConnections' );

	if(!$ueConfig['allowConnections']) {
		echo _UE_FUNCTIONALITY_DISABLED;
		return;
	}
	if ( ! ( $_CB_framework->myId() > 0 ) ) {
		cbNotAuth();
		return;
	}
	$cbCon	=	new cbConnection( $_CB_framework->myId() );

	if (is_array($connectionids)) {
		foreach($connectionids AS $cid) {
			$action		=	cbGetParam( $_POST, $cid . 'action' );
			if ( $action== 'd' ) {
				$cbCon->denyConnection( $_CB_framework->myId(), $cid );
			} elseif ( $action == 'a' ) {
				$cbCon->acceptConnection( $_CB_framework->myId(), $cid );
			}
		}
	}
	$error				=	$cbCon->getErrorMSG();
	if ( $error ) {
		cbRedirect( cbSef( 'index.php?option=com_comprofiler&amp;task=manageConnections' . getCBprofileItemid(), false ), $error, 'error' );
	} else {
		cbRedirect( cbSef( 'index.php?option=com_comprofiler&amp;task=manageConnections' . getCBprofileItemid(), false ),
							( is_array($connectionids) ) ? _UE_CONNECTIONACTIONSSUCCESSFULL : null );
	}
	return;
}

?>
