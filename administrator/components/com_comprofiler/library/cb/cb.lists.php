<?php
/**
* @version $Id: cb.lists.php 1292 2010-11-23 21:58:41Z beat $
* @package Community Builder
* @subpackage cb.lists.php
* @author Beat and various
* @copyright (C) JoomlaJoe and Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

/**
* Users list support class
* Work in progress: do not use outside CB
*/
class cbUsersList {
	function drawUsersList( $uid, $listid, $searchFormValuesRAW ) {
		global $_CB_database, $_CB_framework, $ueConfig, $Itemid, $_PLUGINS;
	
		$search					=	null;
		$searchGET				=	cbGetParam( $searchFormValuesRAW, 'search' );
		$limitstart				=	(int) cbGetParam( $searchFormValuesRAW, 'limitstart', 0 );
		$searchmode				=	(int) cbGetParam( $searchFormValuesRAW, 'searchmode', 0 );
		$randomParam			=	(int) cbGetParam( $searchFormValuesRAW, 'rand', 0 );
	
		// old search on formated name:
	
	/*	if ( $searchPOST || count( $_POST ) ) {
			// simple spoof check security
			cbSpoofCheck( 'usersList' );
			if ( cbGetParam( $searchFormValuesRAW, "action" ) == "search" ) {
				$search			=	$searchPOST;
			}
		} else
			if ( isset( $searchFormValuesRAW['limitstart'] ) ) {
				$search				=	stripslashes( $searchGET );
			}
	*/
		// get my user and gets the list of user lists he is allowed to see (ACL):
	
		$myCbUser				=&	CBuser::getInstance( $uid );
		if ( $myCbUser === null ) {
			$myCbUser			=&	CBuser::getInstance( null );
		}
		$myUser					=&	$myCbUser->getUserData();
	/*
		$myUser					=	new moscomprofilerUser( $_CB_database );
		if ( $uid ) {
			$myUser->load( (int) $uid );
		}
	*/
		$useraccessgroupSQL		=	" AND useraccessgroupid IN (".implode(',',getChildGIDS(userGID($uid))).")";
		$_CB_database->setQuery( "SELECT listid, title FROM #__comprofiler_lists WHERE published=1" . $useraccessgroupSQL . " ORDER BY ordering" );
		$plists					=	$_CB_database->loadObjectList();
		$lists					=	array();
		$publishedlists			=	array();
	
		for ( $i=0, $n=count( $plists ); $i < $n; $i++ ) {
			$plist				=&	$plists[$i];
			$listTitleNoHtml	=	strip_tags( cbReplaceVars( getLangDefinition( $plist->title ), $myUser, false, false ) );
		   	$publishedlists[]	=	moscomprofilerHTML::makeOption( $plist->listid, $listTitleNoHtml );
		}
	
		// select either list selected or default list to which he has access (ACL):
	
		if ( $listid == 0 ) {
			$_CB_database->setQuery( "SELECT listid FROM #__comprofiler_lists "
			. "\n WHERE `default`=1 AND published=1" . $useraccessgroupSQL );
			$listid				=	(int) $_CB_database->loadresult();
			if ( $listid == 0 && ( count( $plists ) > 0 ) ) {
				$listid			=	(int) $plists[0]->listid;
			}
		}
		if ( ! ( $listid > 0 ) ) {
			echo _UE_NOLISTFOUND;
			return;
		}
	
		// generates the drop-down list of lists:
	
		if ( count( $plists ) > 1 ) {
			$lists['plists']	=	moscomprofilerHTML::selectList( $publishedlists, 'listid', 'class="inputbox" size="1" onchange="this.form.submit();"', 'value', 'text', $listid, 1 );
		}
	
		// loads the list record:
	
		$row					=	new moscomprofilerLists( $_CB_database );
		if ( ( ! $row->load( (int) $listid ) ) || ( $row->published != 1 ) ) {
			echo _UE_LIST_DOES_NOT_EXIST;
			return;
		}
		if ( ! allowAccess( $row->useraccessgroupid,'RECURSE', userGID($uid) ) ) {
			echo _UE_NOT_AUTHORIZED;
			return;
		}
	
		$params					=	new cbParamsBase( $row->params );
	
		$hotlink_protection		=	$params->get( 'hotlink_protection', 0 );
		if ( $hotlink_protection == 1 ) {
			if ( ( $searchGET !== null ) || $limitstart ) {
				cbSpoofCheck( 'usersList', 'GET' );
			}
		}
	
		$limit					=	(int) $params->get( 'list_limit' );
		if ( $limit == 0 ) {
			$limit				=	(int) $ueConfig['num_per_page'];
		}
	
		$showPaging				=	$params->get( 'list_paging', 1 );
		if ( $showPaging != 1 ) {
			$limitstart			=	0;
		}
	
		$isModerator			=	isModerator( $_CB_framework->myId() );
	
		$_PLUGINS->loadPluginGroup( 'user' );
		// $plugSearchFieldsArray	=	$_PLUGINS->trigger( 'onStartUsersList', array( &$listid, &$row, &$search, &$limitstart, &$limit ) );
		$_PLUGINS->trigger( 'onStartUsersList', array( &$listid, &$row, &$search, &$limitstart, &$limit ) );
	
		// handles the users allowed to be listed in the list by ACL:
	
		$allusergids			=	array();
		$usergids				=	explode( ',', $row->usergroupids );
	/*	This was a bug tending to list admins when "public backend" was checked, and all frontend users when "public backend was checked. Now just ignore them:
		foreach( $usergids AS $usergid ) {
			$allusergids[]		=	$usergid;
			if ($usergid==29 || $usergid==30) {
				$groupchildren	=	array();
				$groupchildren	=	$_CB_framework->acl->get_group_children( $usergid, 'ARO','RECURSE' );
				$allusergids	=	array_merge($allusergids,$groupchildren);
			}
		}
	*/
		$allusergids			=	array_diff( $usergids, array( 29, 30 ) );
		$usergids				=	implode( ",", $allusergids );
	
		// build SQL Select query:
	
		$random					=	0;
		if( $row->sortfields != '' ) {
			$matches			=	null;
			if ( preg_match( '/^RAND\(\)\s(ASC|DESC)$/', $row->sortfields, $matches ) ) {
				// random sorting needs to have same seed on pages > 1 to not have probability to show same users:
				if ( $limitstart ) {
					$random		=	(int) $randomParam;
				}
				if ( ! $random ) {
					$random		=	rand( 0, 32767 );
				}
				$row->sortfields =	'RAND(' . (int) $random . ') ' . $matches[1];
			}
			$orderby			=	"\n ORDER BY " . $row->sortfields;
		}
		$filterby				=	'';
		if ( $row->filterfields != '' ) {
			$filterRules		=	utf8RawUrlDecode( substr( $row->filterfields, 1 ) );
	
			if ( $_CB_framework->myId() ) {
				$user			=	new moscomprofilerUser( $_CB_database );
				if ( $user->load( (int) $_CB_framework->myId() ) ) {
					$filterRules	=	cbReplaceVars( $filterRules, $user, array( $_CB_database, 'getEscaped' ), false, array() );
				}
			}
			$filterby			=	" AND ". $filterRules;
		}
	
		// Prepare part after SELECT .... " and before "FROM" :
	
		$tableReferences		=	array( '#__comprofiler' => 'ue', '#__users' => 'u' );
	
		// Fetch all fields:
	
		$tabs					=	$myCbUser->_getCbTabs();		//	new cbTabs( 0, 1 );		//TBD: later: this private method should not be called here, but the whole users-list should go into there and be called here.
	
		$allFields				=	$tabs->_getTabFieldsDb( null, $myUser, 'list' );
		// $_CB_database->setQuery( "SELECT * FROM #__comprofiler_fields WHERE published = 1" );
		// $allFields				=	$_CB_database->loadObjectList( 'fieldid', 'moscomprofilerFields', array( &$_CB_database ) );
	
	
		//Make columns array. This array will later be constructed from the tabs table:
	
		$columns				=	array();
	
		for ( $i = 1; $i < 50; ++$i ) {
			$enabledVar			=	"col".$i."enabled";
	
			if ( ! isset( $row->$enabledVar ) ) {
				break;
			}
			$titleVar			=	"col".$i."title";
			$fieldsVar			=	"col".$i."fields";
			$captionsVar		=	"col".$i."captions";
	
			if ( $row->$enabledVar == 1 ) {
				$col			=	new stdClass();
				$col->fields	=	( $row->$fieldsVar ? explode( '|*|', $row->$fieldsVar ) : array() );
				$col->title		=	$row->$titleVar;
				$col->titleRendered		=	$myCbUser->replaceUserVars( $col->title );
				$col->captions	=	$row->$captionsVar;
				// $col->sort	=	1; //All columns can be sorted
				$columns[$i]	=	$col;
			}
		}
	
		// build fields and tables accesses, also check for searchable fields:
	
		$searchableFields		=	array();
		$fieldsSQL				=	cbUsersList::getFieldsSQL( $columns, $allFields, $tableReferences, $searchableFields, $params );
	
		$_PLUGINS->trigger( 'onAfterUsersListFieldsSql', array( &$columns, &$allFields, &$tableReferences ) );
	
		$tablesSQL				=	array();
		$joinsSQL				=	array();
		$tablesWhereSQL			=	array(	'block'		=>	'u.block = 0',
											'approved'	=>	'ue.approved = 1',
											'confirmed'	=>	'ue.confirmed = 1'
										 );
	
		if ( checkJversion() == 2 ) {
			$joinsSQL[]				=	'JOIN #__user_usergroup_map g ON g.`user_id` = u.`id`';
		}
	
		if ( ! $isModerator ) {
			$tablesWhereSQL['banned']	=	'ue.banned = 0';
		}
		if ( $usergids ) {
			if ( checkJversion() == 2 ) {
				$tablesWhereSQL['gid']	=	'g.group_id IN (' . $usergids . ')';
			} else {
				$tablesWhereSQL['gid']	=	'u.gid IN (' . $usergids . ')';
			}
		}
	
		foreach ( $tableReferences as $table => $name ) {
			$tablesSQL[]				=	$table . ' ' . $name;
			if ( $name != 'u' ) {
				$tablesWhereSQL[]		=	"u.`id` = " . $name . ".`id`";
			}
		}
	
		// handles search criterias:
	
		$list_compare_types		=	$params->get( 'list_compare_types', 0 );
		$searchVals				=	new stdClass();
		$searchesFromFields		=	$tabs->applySearchableContents( $searchableFields, $searchVals, $searchFormValuesRAW, $list_compare_types );
		$whereFields			=	$searchesFromFields->reduceSqlFormula( $tableReferences, $joinsSQL, TRUE );
		if ( $whereFields ) {
			$tablesWhereSQL[]	=	'(' . $whereFields . ')';
	/*
			if ( $search === null ) {
				$search			=	'';
			}
	*/
		}
	
		$_PLUGINS->trigger( 'onBeforeUsersListBuildQuery', array( &$tablesSQL, &$joinsSQL, &$tablesWhereSQL ) );
	
		$queryFrom				=	"FROM " . implode( ', ', $tablesSQL )
								.	( count( $joinsSQL ) ? "\n " . implode( "\n ", $joinsSQL ) : '' )
								.	"\n WHERE " . implode( "\n AND ", $tablesWhereSQL );
	
		// handles old formatted names search:
	/*
		if ( $search != '' ) {
			$searchSQL			=	cbEscapeSQLsearch( strtolower( $_CB_database->getEscaped( $search ) ) );
			$queryFrom 			.=	" AND (";
	
			$searchFields		=	array();
			if ( $ueConfig['name_format']!='3' ) {
				$searchFields[]	=	"u.name LIKE '%%s%'";
			}
			if ( $ueConfig['name_format']!='1' ) {
				$searchFields[]	=	"u.username LIKE '%%s%'";
			}
			if ( is_array( $plugSearchFieldsArray ) ) {
				foreach ( $plugSearchFieldsArray as $v ) {
					if ( is_array( $v ) ) {
						$searchFields	=	array_merge( $searchFields, $v );
					}
				}
			}
			$queryFrom			.=	str_replace( '%s', $searchSQL, implode( " OR ", $searchFields ) );
			$queryFrom			.=	")";
		}
	*/
		$queryFrom				.=	" " . $filterby;
	
		$_PLUGINS->trigger( 'onBeforeUsersListQuery', array( &$queryFrom, 1, $listid ) );	// $uid = 1
	
		$errorMsg		=	null;
	
		// counts number of users and loads the listed fields of the users if not in search-form-only mode:
	
		if ( $searchmode == 0 ) {
			if ( checkJversion() == 2 ) {
				$_CB_database->setQuery( "SELECT COUNT(DISTINCT u.id) " . $queryFrom );
			} else {
			$_CB_database->setQuery( "SELECT COUNT(*) " . $queryFrom );
			}
			$total					=	$_CB_database->loadResult();
	
			if ( ( $limit > $total ) || ( $limitstart >= $total ) ) {
				$limitstart			=	0;
			}
	
			// $query					=	"SELECT u.id, ue.banned, '' AS 'NA' " . ( $fieldsSQL ? ", " . $fieldsSQL . " " : '' ) . $queryFrom . " " . $orderby
			if ( checkJversion() == 2 ) {
				$query				=	"SELECT DISTINCT ue.*, u.*, '' AS 'NA' " . ( $fieldsSQL ? ", " . $fieldsSQL . " " : '' ) . $queryFrom . " " . $orderby;
			} else {
				$query				=	"SELECT ue.*, u.*, '' AS 'NA' " . ( $fieldsSQL ? ", " . $fieldsSQL . " " : '' ) . $queryFrom . " " . $orderby;
			}
			$_CB_database->setQuery( $query, (int) $limitstart, (int) $limit );
			$users				=	$_CB_database->loadObjectList( null, 'moscomprofilerUser', array( &$_CB_database ) );
	
			if ( ! $_CB_database->getErrorNum() ) {
				// creates the CBUsers in cache corresponding to the $users:
				foreach ( array_keys( $users ) as $k) {
					CBuser::setUserGetCBUserInstance( $users[$k] );
				}
			} else {
				$users			=	array();
				$errorMsg		=	_UE_ERROR_IN_QUERY_TURN_SITE_DEBUG_ON_TO_VIEW;
			}
	
			if ( count( get_object_vars( $searchVals ) ) > 0 ) {
				$search			=	'';
			} else {
				$search			=	null;
			}
	
		} else {
			$total				=	null;
			$users				=	array();
			if ( $search === null ) {
				$search			=	'';
			}
		}
	
		// Compute itemId of users in users-list:
	
		if ( $Itemid ) {
			$option_itemid		=	(int) $Itemid;
		} else {
			$option_itemid		=	getCBprofileItemid( 0 );
		}
		HTML_comprofiler::usersList( $row, $users, $columns, $allFields, $lists, $listid, $search, $searchmode, $option_itemid, $limitstart, $limit, $total, $myUser, $searchableFields, $searchVals, $tabs, $list_compare_types, $showPaging, $hotlink_protection, $errorMsg, $random );
	}
	/**
	 * Creates the column references for the userlist query
	 * @static
	 *
	 * @param  array         $columns
	 * @param  array         $allFields
	 * @param  array         $tables
	 * @param  array         $searchableFields
	 * @param  cbParamsBase  $params
	 * @return string
	 */
	function getFieldsSQL( &$columns, &$allFields, &$tables, &$searchableFields, &$params ){
		$colRefs										=	array();
	
		$newtableindex									=	0;
	
		$list_search									=	(int) $params->get( 'list_search', 1 );
	
		foreach ( $columns as $i => $column ) {
			foreach ( $column->fields as $k => $fieldid ) {
				if ( isset( $allFields[$fieldid] ) ) {
					// now done in field fetching:
					//	if ( ! is_object( $allFields[$fieldid]->params ) ) {
					//		$allFields[$fieldid]->params	=	new cbParamsBase( $allFields[$fieldid]->params );
					//	}
					$field								=	$allFields[$fieldid];
					if ( ! array_key_exists( $field->table, $tables ) ) {
						$newtableindex++;
						$tables[$field->table]			=  't'.$newtableindex;
					}
	/*
					if ( $field->name == 'avatar' ) {
						$colRefs['avatarapproved']		=	'ue.`avatarapproved`';
						$colRefs['name']				=	'u.`name`';
						$colRefs['username']			=	'u.`username`';
					}
					if ( $field->type == 'formatname' ) {
						$colRefs['name']				=	'u.`name`';
						$colRefs['username']			=	'u.`username`';
					}
	*/
					if ( ( $tables[$field->table][0] != 'u' ) && ( $field->name != 'NA' ) ) {		// CB 1.1 table compatibility : TBD: remove after CB 1.2
						foreach ( $field->getTableColumns() as $col ) {
							$colRefs[$col]				=	$tables[$field->table] . '.' . $field->_db->NameQuote( $col );
						}
					}
					if ( $field->searchable && ( $list_search == 1 ) ) {
						$searchableFields[]				=&	$allFields[$fieldid];
					}
					$allFields[$fieldid]->_listed		=	true;
				} else {
					// field unpublished or deleted but still in list: remove field from columns, so that we don't handle it:
					unset( $columns[$i]->fields[$k] );
				}
			}
		}
	
		if ( $list_search == 2 ) {
			foreach ( $allFields as $fieldid => $field ) {
				if ( $field->searchable ) {
					$searchableFields[]					=&	$allFields[$fieldid];
				}
			}
		}
		return implode( ', ', $colRefs );
	}
	/**
	 * Outputs javascript for the advanced search feature on users lists
	 * @static
	 *
	 * @param $search   null: show just search button, 'onlyactive': show also activated searches
	 */
	function outputAdvancedSearchJs( $search ) {
		global $_CB_framework;

		// Searchable fields appearing in the users list:
		// Search box:
		//TBD: display if there is a search criteria:
		if ( $search === null || $search == 'onlyactive' ) {
						//	Show the "Search" button:
			$jsSearch		=	"	$('#cbUserListsSearchTrigger').show();";
			if ( $search === null ) {
				//	Hide  the Search Criteria part and Results title:
				$jsSearch	.=	"\n	$('.cbUserListSearch').hide();";
			} else {
				// Show Criterias, but hide inactive fields:
				$jsSearch	.=	"\n	var allSearchLines = $('#cbUserListsSearcher .cbUserListSearchFields .cb_form_line');"
							.	"\n	var inactiveSearchLines = allSearchLines.filter( function() {"
							.	"\n		var searchKindSelector = $(this).find('.cbSearchKind select');"
							.	"\n		if (searchKindSelector.length > 0) {"
							.	"\n			return searchKindSelector.val() == '';"
							.	"\n		} else {"
							.	"\n			var searchFields = $(this).find('.cbSearchCriteria select,.cbSearchCriteria input,.cbSearchCriteria textarea');"
							.	"\n			return ((searchFields.length > 0) && (searchFields.filter( function() { return $(this).val() != ''; }).length == 0));"
							.	"\n		}"
							.	"\n	});"
							.	"\n	inactiveSearchLines.hide();"
							.	"\n	if ( inactiveSearchLines.size() < allSearchLines.size() ) {"
							.	"\n		$('.cbUserListSearch').show();"
							.	"\n	}"
							;
			}
							//	When button <a> link is clicked:
			$jsSearch		.=	"\n	$('#cbUserListsSearchTrigger').click( function() {"
							//	Show the lines hidden in case of onlyactive:
							.	"\n		$('#cbUserListsSearcher .cbUserListSearchFields .cb_form_line').show();"
							//	Hide the button:
							.	"\n		$('#cbUserListsSearchTrigger').hide('medium', function() {"
							//	Show the Search Criteria part:
							.	"\n			$('#cbUserListsSearcher').slideDown('slow');"
							.	"\n		} );"
							//	And avoid the <a> link being followed:
							.	"\n		$('div.cbSearchKind select').each(function() {if ($(this).val() == '') $(this).parent( 'div' ).next('div.cbSearchCriteria').hide();});"
							.	"\n		return false;"
							.	"\n	} );"
							;
		} else {
			/*
			$ajaxCode	=	"$('#cbUserListsSearchTrigger').hide();"
						.	"$('.cbUserListSearch').show();"
						.	"} );"
						;
			$_CB_framework->outputCbJQuery( $ajaxCode );
			*/
			// hide unneeded fields:
			$jsSearch	=	"\n	$('div.cbSearchKind select').each(function() {if ($(this).val() == '') $(this).parent( 'div' ).next('div.cbSearchCriteria').hide();});"
						.	"\n	$('.cbUserListSearch').show();";
						;
		}
		$_CB_framework->outputCbJQuery( $jsSearch );

		//	When a search kind ('is', 'is not', 'contains', etc) is clicked (change does not work correctly in some safari 2 and IE 6 versions):
		$searchTabJs	=	"\n{"
						.	"\n	function cbsearchkrit(thisSelect) {"
						//	Get value of the selected option:
						.	"\n		var kindval = $(thisSelect).val();"
						.	"\n		if ( kindval == '' ) {"
						//	Hide the search criteria if there is 'no preference' selected:
						.	"\n			$(thisSelect).parent( 'div' ).next('div.cbSearchCriteria').slideUp('slow');"
						.	"\n		} else {"
						//	Otherwise show the search criteria:
						.	"\n			$(thisSelect).parent( 'div' ).next('div.cbSearchCriteria').slideDown('slow');"
						//	Check for search kind being precise search:
						.	"\n			if ( ( kindval == 'is' ) || ( kindval == 'isnot' ) ) {"
						//	For radio buttons, insure they are (again) radios: unfortunately, DOM doesn't allow to change type of input on the fly, so do it by regex replacing html:
						.	"\n				$(thisSelect).parent('div').next('div.cbSearchCriteria.cb__js_radio').find('input:checkbox').parent().each( function() {"
						.	"\n				    return $(this).html( $(this).html().replace(/(name=)(\"?)([^\"\\[ >]+)(\\[\\])(\"?)([ >])/g, '\$1\"\$3\"\$6').replace(/type=\"?checkbox\"?/g,'type=\"radio\"') );"
						.	"\n				} );"
						//	For single-selects, insure they are not multiple anymore:
						.	"\n				$(thisSelect).parent('div').next('div.cbSearchCriteria.cb__js_select').each( function() {"
						.	"\n				    return $(this).html( $(this).html().replace(/(name=)(\"?)([^\"\\[ >]+)(\\[\\])(\"?)([ >])/g, '\$1\"\$3\"\$6').replace(/multiple(=(\"?)multiple(\"?))?/gi,'') );"
						.	"\n				} );"
						.	"\n			} else {"
						//	If search criteria is multiple, then make also radios into checkboxes (and below single-selects into multi-selects):
						.	"\n				$(thisSelect).parent('div').next('div.cbSearchCriteria.cb__js_radio').find('input:radio').parent().each( function() {"
						.	"\n				    return $(this).html( $(this).html().replace(/(name=)(\"?)([^\"\\[ >]+)(\\[\\])?(\"?)([ >])/g, '\$1\"\$3\\[\\]\"\$6').replace(/type=\"?radio\"?/g,'type=\"checkbox\"') );"
						.	"\n				} );"
						.	"\n				$(thisSelect).parent('div').next('div.cbSearchCriteria.cb__js_select').each( function() {"
						.	"\n				    return $(this).html( $(this).html().replace(/(name=)(\"?)([^\"\\[ >]+)(\\[\\])?(\"?)([ >])/g, '\$1\"\$3\\[\\]\"\$6').replace(/(<select )/gi,'\$1multiple=\"multiple\" ').replace(/size=(\"?)[^\" >]*(\"?)/g,'size=\"0\"') );"
						.	"\n				} );"
						.	"\n			}"
						.	"\n		}"
						.	"\n	}"
						.	"\n	$('div.cbSearchKind select').click( function() {"
						.	"\n		cbsearchkrit( this );"
						//	At page startup fires the click event, which executes the callback just defined above:
						.	"\n	} ).click();"
						.	"\n	$('div.cbSearchKind select').change( function() {"
						.	"\n		cbsearchkrit( this );"
						//	At search startup the fields are hidden as just defined above
						.	"\n	$('div.cbSearchKind select').each(function() { cbsearchkrit(this); });"
						.	"\n	} );"
						.	"\n}"
						;
		$_CB_framework->outputCbJQuery( $searchTabJs );
	}
}
?>