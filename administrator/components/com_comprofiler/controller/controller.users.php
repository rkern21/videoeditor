<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: controller.users.php 1394 2011-01-31 13:58:28Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : users controller
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBController_users {
	function _importNeeded() {
		cbimport( 'cb.tabs' );

		if ( class_exists( 'JFactory' ) ) {	// Joomla 1.5 : for string WARNREG_EMAIL_INUSE used in error js popup.
			$lang			=&	JFactory::getLanguage();
			$lang->load( "com_users" );
		}

		cbimport( 'cb.params' );
		cbimport( 'cb.pagination' );
		cbimport( 'cb.lists' );

	}
	function showUsers( $option, $task, $cid ) {
		global $_CB_database, $_CB_framework, $_POST, $_PLUGINS, $_CB_TxtIntStore;

		$this->_importNeeded();

		$limit						=	(int) $_CB_framework->getCfg( 'list_limit' );
		if ( $limit == 0 ) {
			$limit					=	10;
		}
		$filter_type				=	$_CB_framework->getUserStateFromRequest( "filter_type{$option}", 'filter_type', 0 );
		$filter_status				=	$_CB_framework->getUserStateFromRequest( "filter_status{$option}", 'filter_status', 0 );
		$filter_logged				=	intval( $_CB_framework->getUserStateFromRequest( "filter_logged{$option}", 'filter_logged', 0 ) );
		$lastCBlist					=	$_CB_framework->getUserState( "view{$option}lastCBlist", null );
		if( $lastCBlist == 'showusers' ) {
			if ( $task == 'showusers' ) {
				$limit				=	$_CB_framework->getUserStateFromRequest( "viewlistlimit", 'limit', $limit );
				$limitstart			=	$_CB_framework->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
			}
			$lastSearch 			=	$_CB_framework->getUserState( "search{$option}", null );
			$search					=	$_CB_framework->getUserStateFromRequest( "search{$option}", 'search', '' );
			if ( $lastSearch != $search ) {
				$limitstart			=	0;
				$_CB_framework->setUserState( "view{$option}limitstart", $limitstart );
			}
			$search					=	stripslashes( trim( ( $_CB_TxtIntStore->_iso != 'UTF-8' ) ? strtolower( $search ) : ( is_callable( 'mb_convert_case' ) ? mb_convert_case( $search, MB_CASE_LOWER, "UTF-8") : utf8_encode(strtolower(utf8_decode( $search ) ) ) ) ) );
		} else {
			$filter_type			=	0;
			$filter_status			=	0;
			$filter_logged			=	0;
			clearSearchBox();
			$search					=	'';
			$limitstart				=	0;
			$_CB_framework->setUserState( "view{$option}limitstart", $limitstart );
			$_CB_framework->setUserState( "view{$option}lastCBlist", "showusers" );
		}
	
		if ( $task !== 'showusers' ) {
			if ( $task == 'ajaxemailusers' ) {
				$limitstart			=	cbGetParam( $_POST, 'limitstart', 0 );
				$limit				=	cbGetParam( $_POST, 'limit', 0 );
			} else {
				$limitstart			=	0;
				if ( $task == 'emailusers' ) {
					$limit			=	101;		// so that first 100 users and more... is displayed.
				} else {
					$limit			=	cbGetParam( $_POST, 'limit', 0 );
				}
			}
		}
		
		$tablesSQL					=	array( 'u'	=> '#__users AS u' );
		$joinsSQL					=	array( 'ue'	=> 'LEFT JOIN #__comprofiler AS ue ON u.id = ue.id' );
		$tablesWhereSQL				=	array();
	
		if ( isset( $search ) && ( $search != "") ) {
			$tablesWhereSQL[]		=	"(u.username LIKE '%" . $_CB_database->getEscaped( $search, true ) . "%' OR u.email LIKE '%" . $_CB_database->getEscaped( $search, true ) . "%' OR u.name LIKE '%" . $_CB_database->getEscaped( $search, true ) . "%')";
		}
		if ( $filter_type ) {
			if ( checkJversion() == 2 ) {
				$tablesWhereSQL[]	=	"aro.group_id = " . (int)  $filter_type;
			} else {
				if ( $filter_type == 'Public Frontend' ) {
					$tablesWhereSQL[]	=	"(u.usertype = 'Registered' OR u.usertype = 'Author' OR u.usertype = 'Editor'OR u.usertype = 'Publisher')";
				} else if ( $filter_type == 'Public Backend' ) {
					$tablesWhereSQL[]	=	"( u.usertype = 'Manager' OR u.usertype = 'Administrator' OR u.usertype = 'Super Administrator' )";
				} else {
					$tablesWhereSQL[]	=	"u.usertype = " . $_CB_database->Quote( $filter_type );
				}
			}
		}
		$tBlocked					=	CBTxt::T('Blocked');
		$tEnabled					=	CBTxt::T('Enabled');
		$tUnconfirmed				=	CBTxt::T('Unconfirmed');
		$tConfirmed					=	CBTxt::T('Confirmed');
		$tUnapproved				=	CBTxt::T('Unapproved');
		$tDisapproved				=	CBTxt::T('Disapproved');
		$tApproved					=	CBTxt::T('Approved');
		$tBanned					=	CBTxt::T('Banned');
		$p							=	' + ';
		$userstates	=	array(
						$tBlocked											=>	'u.block = 1',
						$tEnabled											=>	'u.block = 0',
						$tUnconfirmed										=>	'ue.confirmed = 0',
						$tConfirmed											=>	'ue.confirmed = 1',
						$tUnapproved										=>	'ue.approved = 0',
						$tDisapproved										=>	'ue.approved = 2',
						$tApproved											=>	'ue.approved = 1',
						$tBanned											=>	'ue.banned <> 0',
						$tBlocked . $p . $tUnconfirmed . $p . $tUnapproved	=>	'(u.block = 1 AND ue.confirmed = 0 AND ue.approved = 0)',
						$tEnabled . $p . $tUnconfirmed . $p . $tUnapproved	=>	'(u.block = 0 AND ue.confirmed = 0 AND ue.approved = 0)',
						$tBlocked . $p . $tConfirmed   . $p . $tUnapproved	=>	'(u.block = 1 AND ue.confirmed = 1 AND ue.approved = 0)',
						$tEnabled . $p . $tConfirmed   . $p . $tUnapproved	=>	'(u.block = 0 AND ue.confirmed = 1 AND ue.approved = 0)',
						$tBlocked . $p . $tUnconfirmed . $p . $tDisapproved	=>	'(u.block = 1 AND ue.confirmed = 0 AND ue.approved = 2)',
						$tEnabled . $p . $tUnconfirmed . $p . $tDisapproved	=>	'(u.block = 0 AND ue.confirmed = 0 AND ue.approved = 2)',
						$tBlocked . $p . $tConfirmed   . $p . $tDisapproved	=>	'(u.block = 1 AND ue.confirmed = 1 AND ue.approved = 2)',
						$tEnabled . $p . $tConfirmed   . $p . $tDisapproved	=>	'(u.block = 0 AND ue.confirmed = 1 AND ue.approved = 2)',
						$tBlocked . $p . $tUnconfirmed . $p . $tApproved	=>	'(u.block = 1 AND ue.confirmed = 0 AND ue.approved = 1)',
						$tEnabled . $p . $tUnconfirmed . $p . $tApproved	=>	'(u.block = 0 AND ue.confirmed = 0 AND ue.approved = 1)',
						$tBlocked . $p . $tConfirmed   . $p . $tApproved	=>	'(u.block = 1 AND ue.confirmed = 1 AND ue.approved = 1)',
						$tEnabled . $p . $tConfirmed   . $p . $tApproved	=>	'(u.block = 0 AND ue.confirmed = 1 AND ue.approved = 1)',
						CBTxt::T('Avatar not approved')						=>	"(ue.avatar > '' AND ue.avatarapproved = 0)" );
		if ( $filter_status ) {
			$tablesWhereSQL[]		=	$userstates[$filter_status];
		}
		if ( $filter_logged == 1 ) {
			$tablesWhereSQL[]		=	"s.userid = u.id";
		} else if ($filter_logged == 2) {
			$tablesWhereSQL[]		=	"s.userid IS NULL";
		}
	
		// exclude any child group id's for this user
		//$_CB_framework->acl->_debug = true;
		$pgids						=	$_CB_framework->acl->get_group_children( userGID( $_CB_framework->myId() ), 'ARO', 'RECURSE' );
		if ( is_array( $pgids ) && (count( $pgids ) > 0 ) ) {
			if ( checkJversion() == 2 ) {
				$tablesWhereSQL[]	=	"( aro.group_id NOT IN ( " . implode( ',', $pgids ) . " ) )";
			} else {
				$tablesWhereSQL[]	=	"( u.gid NOT IN ( " . implode( ',', $pgids ) . " ) )";
			}
		}
		// Filter the checkmarked users only:
		if ( $task !== 'showusers' ) {
			if ( is_array( $cid ) && ( count( $cid ) > 0 ) ) {
				cbArrayToInts( $cid );
				$tablesWhereSQL[]		=	"( u.id IN ( " . implode( ',', $cid ) . " ) )";
			}
		}
	
		// Advanced searches:
		$myCbUser				=&	CBuser::getInstance( $_CB_framework->myId() );
		$myUser					=&	$myCbUser->getUserData();
		$tabs					=	$myCbUser->_getCbTabs();		//	new cbTabs( 0, 1 );		//TBD: later: this private method should not be called here, but the whole users-list should go into there and be called here.
		$allFields				=	$tabs->_getTabFieldsDb( null, $myUser, 'adminfulllist' );
		foreach ( $allFields as $k => $v ) {
			if ( in_array( $v->type, array( 'pm', 'status', 'formatname', 'hidden', 'delimiter', 'userparams' ) ) ) {
				unset( $allFields[$k] );		// delimiter, userparams do not have search for now!
			}
		}
		$searchVals				=	new stdClass();
		$list_compare_types		=	1;		// Advanced: all possibilities (WARNING: can be slow)
		$tableReferences		=	array( '#__comprofiler' => 'ue', '#__users' => 'u' );
		$searchesFromFields		=	$tabs->applySearchableContents( $allFields, $searchVals, $_POST, $list_compare_types );
		$whereFields			=	$searchesFromFields->reduceSqlFormula( $tableReferences, $joinsSQL, TRUE );
		if ( $whereFields ) {
			$tablesWhereSQL[]	=	'(' . $whereFields . ')';
		}
		$searchTabContent		=	$tabs->getSearchablesContents( $allFields, $myUser, $searchVals, $list_compare_types );
		
		if ($filter_logged == 1 || $filter_logged == 2) {
			$joinsSQL[]				.=	"\n INNER JOIN #__session AS s ON s.userid = u.id";
		// } else {		done later, to avoid blocking site:
		//	$joinsSQL[]				.=	"\n LEFT JOIN #__session AS s ON s.userid = u.id";
		}
	
		if ( checkJversion() == 2 ) {
			$joinsSQL[]				=	"INNER JOIN #__user_usergroup_map AS aro ON aro.user_id = u.id";			// map user to aro for selection (and display if no selection)
			if ( $filter_type ) {
				$joinsSQL[]			=	"LEFT JOIN #__user_usergroup_map AS arodisplay ON arodisplay.user_id = u.id";	// map user to aro for display of all groups
				$joinsSQL[]			=	"INNER JOIN #__usergroups AS g ON g.id = arodisplay.group_id"; 					// map aro to group for display group name
			} else {
				$joinsSQL[]			=	"INNER JOIN #__usergroups AS g ON g.id = aro.group_id"; 					// map aro to group
			}
		}
	
	   	$_PLUGINS->loadPluginGroup('user');
		$_PLUGINS->trigger( 'onBeforeBackendUsersListBuildQuery', array( &$tablesSQL, &$joinsSQL, &$tablesWhereSQL, $option ) );
	
		$queryFrom					=	"\n FROM " . implode( ', ', $tablesSQL )
									.	( count( $joinsSQL ) ? "\n " . implode( "\n ", $joinsSQL ) : '' )
									.	( count( $tablesWhereSQL ) ? "\n WHERE " . implode( ' AND ', $tablesWhereSQL ) : '' )
									;
	
		// Counting query:
		$query						=	"SELECT COUNT(DISTINCT u.id)"
									.	$queryFrom
									;
		$_CB_database->setQuery( $query );
		$total						=	$_CB_database->loadResult();
		if ( $total === null ) {
			echo $_CB_database->getErrorMsg();
		}
		if ( $total <= $limitstart ) {
			$limitstart				=	0;
		}
	
		cbimport( 'cb.pagination' );
		$pageNav					=	new cbPageNav( $total, $limitstart, $limit  );
	
		if ( checkJversion() == 2 ) {
			$grp_name				=	'title';
		} elseif ( checkJversion() == 1 ) {
			$grp_name				=	'name';
			$joinsSQL[]				=	"INNER JOIN #__core_acl_aro AS aro ON aro.value = u.id";					// map user to aro
			$joinsSQL[]				=	"INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.id";		// map aro to group
			$joinsSQL[]				=	"INNER JOIN #__core_acl_aro_groups AS g ON g.id = gm.group_id";
			$tablesWhereSQL[]		=	"aro.section_value = 'users'";
		} else {
			$grp_name				=	'name';
			$joinsSQL[]				=	"INNER JOIN #__core_acl_aro AS aro ON aro.value = u.id";					// map user to aro
			$joinsSQL[]				=	"INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.aro_id";	// map aro to group
			$joinsSQL[]				=	"INNER JOIN #__core_acl_aro_groups AS g ON g.group_id = gm.group_id";
			$tablesWhereSQL[]		=	"aro.section_value = 'users'";
		}
	
		$queryFrom					=	"\n FROM " . implode( ', ', $tablesSQL )
									.	( count( $joinsSQL ) ? "\n " . implode( "\n ", $joinsSQL ) : '' )
									.	( count( $tablesWhereSQL ) ? "\n WHERE " . implode( ' AND ', $tablesWhereSQL ) : '' )
									;
	
		// Main query:
		if ( checkJversion() == 2 ) {
			$query					=	"SELECT u.*, GROUP_CONCAT( DISTINCT g.$grp_name ORDER BY g.$grp_name SEPARATOR ', ') AS groupname, ue.approved, ue.confirmed"
									.	$queryFrom
									.	' GROUP BY u.id'
									;
		} else {
			$query					=	"SELECT DISTINCT u.*, g.$grp_name AS groupname, ue.approved, ue.confirmed"
									.	$queryFrom
									;
		}
		$_CB_database->setQuery( $query, (int) $pageNav->limitstart, (int) $pageNav->limit );
		$rows						=	$_CB_database->loadObjectList( null, 'moscomprofilerUser', array( &$_CB_database ) );
		if ($_CB_database->getErrorNum()) {
			echo $_CB_database->stderr();
			return false;
		}
		// creates the CBUsers in cache corresponding to the $users:
		foreach ( array_keys( $rows ) as $k) {
			// do not do this otherwise substitutions do not work: 
			// CBuser::setUserGetCBUserInstance( $rows[$k] );
		}
	
		$template				=	'SELECT COUNT(s.userid) FROM #__session AS s WHERE s.userid = ';
		$n						=	count( $rows );
		for ( $i = 0; $i < $n; $i++ ) {
			$row				=	&$rows[$i];
			$query				=	$template . (int) $row->id;
			$_CB_database->setQuery( $query );
			$row->loggedin		=	$_CB_database->loadResult();
		}
	
		$select_tag_attribs		=	'class="inputbox" size="1" onchange="document.adminForm.submit( );"';
		$inputTextExtras		=	'';
		if ( $task != 'showusers' ) {
			$inputTextExtras	=	' disabled="disabled"';
			$select_tag_attribs	.=	$inputTextExtras;
		}
	
		// get list of Log Status for dropdown filter
		$logged[]				=	moscomprofilerHTML::makeOption( 0, CBTxt::T('- Select Login State -'));
		$logged[]				=	moscomprofilerHTML::makeOption( 1, CBTxt::T('Logged In'));
		$lists['logged']		=	moscomprofilerHTML::selectList( $logged, 'filter_logged', $select_tag_attribs, 'value', 'text', "$filter_logged", 2 );
	
		// get list of Groups for dropdown filter
		if ( checkJversion() == 2 ) {
			$query				=	"SELECT id AS value, title AS text"
								.	"\n FROM #__usergroups";
		} else {
			$query				=	"SELECT name AS value, name AS text"
								.	"\n FROM #__core_acl_aro_groups"
								.	"\n WHERE name != 'ROOT'"
								.	"\n AND name != 'USERS'";
		}
	
		$types[]				=	moscomprofilerHTML::makeOption( '0', CBTxt::T('- Select Group -') );
		$_CB_database->setQuery( $query );
		$types					=	array_merge( $types, $_CB_database->loadObjectList() );
		$lists['type']			=	moscomprofilerHTML::selectList( $types, 'filter_type', $select_tag_attribs, 'value', 'text', "$filter_type", 2 );
	
		$status[]				=	moscomprofilerHTML::makeOption( 0, CBTxt::T('- Select User Status -'));
		foreach ( array_keys( $userstates ) as $k ) {
			$status[]			=	moscomprofilerHTML::makeOption( $k, $k );
		}
		$lists['status']		=	moscomprofilerHTML::selectList( $status, 'filter_status', $select_tag_attribs, 'value', 'text', "$filter_status", 2 );
	
		$pluginAdditions		=	$_PLUGINS->trigger( 'onAfterBackendUsersList', array( 1, &$rows, &$pageNav, &$search, &$lists, $option, $select_tag_attribs ) );
		$pluginColumns			=	array();
		foreach ( $pluginAdditions as $addition ) {
			if ( is_array( $addition ) ) {
				$pluginColumns	=	array_merge( $pluginColumns, $addition );
			}
		}
	
		if ( $task == 'showusers' ) {
			$usersView			=	_CBloadView( 'users' );
			$usersView->showUsers( $rows, $pageNav, $search, $option, $lists, $pluginColumns, $inputTextExtras, $searchTabContent );
		} else {
			$emailSubject		=	stripslashes( cbGetParam( $_POST, 'emailsubject', '' ) );
			$emailBody			=	stripslashes( cbGetParam( $_POST, 'emailbody', '', _CB_ALLOWRAW | _CB_NOTRIM ) );
			$emailsPerBatch		=	stripslashes( cbGetParam( $_POST, 'emailsperbatch', 50 ) );
			$emailPause			=	stripslashes( cbGetParam( $_POST, 'emailpause', 30 ) );
			$simulationMode		=	stripslashes( cbGetParam( $_POST, 'simulationmode', '' ) );
			if ( count( $cid ) > 0 && count( $cid ) < $total ) {
				$total			=	count( $cid );
			}
			if ( $task == 'emailusers' ) {
				$pluginRows		=	$_PLUGINS->trigger( 'onBeforeBackendUsersEmailForm', array( &$rows, &$pageNav, &$search, &$lists, &$cid, &$emailSubject, &$emailBody, &$inputTextExtras, &$select_tag_attribs, $simulationMode, $option ) );
				$usersView		=	_CBloadView( 'users' );
				$usersView->emailUsers( $rows, $total, $search, $option, $lists, $cid, $inputTextExtras, $searchTabContent, $emailSubject, $emailBody, $emailsPerBatch, $emailPause, $simulationMode, $pluginRows );
			} elseif ( $task == 'startemailusers' ) {
				$pluginRows		=	$_PLUGINS->trigger( 'onBeforeBackendUsersEmailStart', array( &$rows, $total, $search, $lists, $cid, &$emailSubject, &$emailBody, &$inputTextExtras, $simulationMode, $option ) );
				$usersView		=	_CBloadView( 'users' );
				$usersView->startEmailUsers( $rows, $search, $option, $lists, $cid, $inputTextExtras, $searchTabContent, $emailSubject, $emailBody, $emailsPerBatch, $emailPause, $total, $simulationMode, $pluginRows );
			} elseif ( $task == 'ajaxemailusers' ) {
				$this->_cbadmin_emailUsers( $rows, $emailSubject, $emailBody, $limitstart, $limit, $total, $simulationMode );
			}
		}
		return true;
	}
	
	function _cbadmin_emailUsers( &$rows, $emailSubject, $emailBody, $limitstart, $limit, $total, $simulationMode ) {
		global $_PLUGINS;
		// simple spoof check security
		cbSpoofCheck( 'cbadmingui' );
		cbRegAntiSpamCheck();
	
		$cbNotification				=	new cbNotification();
		$mode						=	1;		// html
		
		$usernames					=	'';
		foreach ( $rows as $row ) {
			$user					=	CBuser::getUserDataInstance( (int) $row->id );
			$usernames				.=	( $usernames ? ', ' : '' ) . htmlspecialchars( $user->username );
			if ( $simulationMode ) {
				$usernames			.=	' (' . htmlspecialchars( CBTxt::T('email not send: simulation mode') ) . ')';
			} else {
				$extraStrings		=	array();
				$_PLUGINS->trigger( 'onBeforeBackendUserEmail', array( &$user, &$emailSubject, &$emailBody, $mode, &$extraStrings, $simulationMode ) );
				if ( ! $cbNotification->sendFromSystem( $user, $emailSubject, $this->_cbadmin_makeLinksAbsolute( $emailBody ), true, $mode, null, null, null, $extraStrings, false ) ) {
					$usernames		.=	': <span class="cb_result_error">' . htmlspecialchars( CBTxt::T('Error sending email!') ) . '</span>';
				}
			}
		}
	
		if ( $total < $limit ) {
			$limit					=	$total;
		}
		ob_start();
		$usersView					=	_CBloadView( 'users' );
		$usersView->ajaxResults( $usernames, $emailSubject, $this->_cbadmin_makeLinksAbsolute( $emailBody ), $limitstart, $limit, $total );
		$html						=	ob_get_contents();
		ob_end_clean();
	
		$reply						=	array(	'result'		=>	1,
												'htmlcontent'	=>	$html );
		if ( ! ( $total - ( $limitstart + $limit ) > 0 ) ) {
			$reply['result']		=	2;
		}
		echo json_encode( $reply );
		sleep(3);
	}
	function _cbadmin_makeLinksAbsolute( $text ) {
		// replace <a> links:
		$regex			=	'/<a ((?:[^>]* )*)href="(.*)"([^>]*)>/iUs';
		$text			=	preg_replace_callback( $regex, array( $this, '_cbadmin_parse_a_link' ), $text );
		// replace <img> links:
		$regex			=	'/<img ((?:[^>]* )*)src="(.*)"([^>]*)>/iUs';
		$text			=	preg_replace_callback( $regex, array( $this, '_cbadmin_parse_img_link' ), $text );
	
		return $text;
	}
	/**
	 * Replace relative non-sefed link with absolute sefed links
	 *
	 * @param array $matches
	 * @return string
	 */
	function _cbadmin_parse_a_link( &$matches ) {
		$url		=	cbUnHtmlspecialchars( $matches[2] );
	
		if ( ( substr( $url, 0, 6 ) == 'mailto' ) || ( substr( $url, 0, 1 ) == '#' ) || ( substr( $url, 0, 4 ) == 'http' ) ) {
			//mailto link or anchor inside mail or already absolute URL, do nothing..
			return $matches[0];
		}
	 
		//TODO: substitute mailing link for tracking and make a mailing stats:
		// find $url (remove absolute link case from above exception) in known links for id, otherwise insert
		// find linkid + mailing id in links_stats table, otherwise create, and re-create a specific url
	
		$url		=	cbSef( $url, true );
	  
		return '<a ' . $matches[1] . 'href="' . $url . '"' . $matches[3] . '>';
	}
	/**
	 * Replace relative image src links with absolute links
	 *
	 * @param array $matches
	 * @return string
	 */
	function _cbadmin_parse_img_link(&$matches){
		$image		=	cbUnHtmlspecialchars( $matches[2] );
	
		if ( substr( $image, 0, 4 ) == 'http' ) {
			// already absolute URL, do nothing..
			return $matches[0];
		}
	
		if ( substr( $image, 0, 1 ) != '/' ) {
			$image	=	'/' . $image;
		}
		global $_CB_framework;
		$image		=	$_CB_framework->getCfg( 'live_site' ) . $image;
	
		return '<img ' . $matches[1] . 'src="' . $image . '"' . $matches[3] . '>';
	}
	
}	// class CBController_users
?>