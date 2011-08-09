<?php
/**
* @version $Id: cb.acl.php 1421 2011-02-09 10:25:47Z beat $
* @package Community Builder
* @subpackage cb.acl.php
* @author Beat and mambojoe
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBACL {
	/**
	 * @var gacl_api $_acl
	 */
	var $_acl;

	function CBACL( &$acl ) {
		$this->_acl			=&	$acl;
	}

	function get_group_id( $var_1 = null, $var_2 = null, $var_3 = null ) {
		global $_CB_database;

		if ( checkJversion() == 2 ) {
			$gname		=	( $var_1 ? $var_1 : $var_2 );

			$query		=	'SELECT ' . $_CB_database->NameQuote( 'id' )
						.	"\n FROM " . $_CB_database->NameQuote( '#__usergroups' )
						.	"\n WHERE " . $_CB_database->NameQuote( 'title' ) . " = " . $_CB_database->Quote( $gname );
			$_CB_database->setQuery( $query );
			$return		=	$_CB_database->loadResult();
		} else {
			if ( ! $var_2 ) {
				$var_2	=	'ARO';
			}

			$return		=	$this->_acl->get_group_id( $var_1, $var_2, $var_3 );
		}

		return $return;
	}

	function get_group_name( $var_1 = null, $var_2 = null ) {
		global $_CB_database;

		if ( checkJversion() == 2 ) {
			$query		=	'SELECT ' . $_CB_database->NameQuote( 'title' )
						.	"\n FROM " . $_CB_database->NameQuote( '#__usergroups' )
						.	"\n WHERE " . $_CB_database->NameQuote( 'id' ) . " = " . (int) $var_1;
			$_CB_database->setQuery( $query );
			$return		=	$_CB_database->loadResult();
		} else {
			if ( ! $var_2 ) {
				$var_2	=	'ARO';
			}

			$return		=	$this->_acl->get_group_name( $var_1, $var_2 );
		}

		return $return;
	}

	function acl_check( $var_1 = null, $var_2 = null, $var_3 = null, $var_4 = null, $var_5 = null, $var_6 = null, $var_7 = null, $var_8 = null ) {
		if ( checkJversion() == 2 ) {
			$return	=	JFactory::getUser()->authorise( $var_2, $var_1 );
		} else {
			$return	=	$this->_acl->acl_check( $var_1, $var_2, $var_3, $var_4, $var_5, $var_6, $var_7, $var_8 );
		}
		return $return;
	}

	function get_object_id( $var_1 = null, $var_2 = null, $var_3 = null ) {
		if ( checkJversion() == 2 ) {
			$return		=	$var_2;
		} else {
			$return		=	$this->_acl->get_object_id( $var_1, $var_2, $var_3 );
		}

		return $return;
	}

	function get_object_groups( $var_1 = null, $var_2 = null, $var_3 = null ) {
		if ( checkJversion() == 2 ) {
			$user_id	=	( is_integer( $var_1 ) ? $var_1 : $var_2 );
			$recurse	=	( $var_3 == 'RECURSE' ? true : false );
			$return		=	$this->_acl->getGroupsByUser( $user_id, $recurse );
		} elseif ( checkJversion() == 1 ) {
			if ( ! $var_2 ) {
				$var_2	=	'ARO';
			}

			if ( ! $var_3 ) {
				$var_3	=	'NO_RECURSE';
			}

			$return		=	$this->_acl->get_object_groups( $var_1, $var_2, $var_3 );
		} else {
			$return		=	$this->_acl->get_object_groups( $var_1, $var_2, $var_3 );
		}

		return $return;
	}

	function get_group_children( $var_1 = null, $var_2 = null, $var_3 = null ) {
		global $_CB_database;

		if ( ! $var_3 ) {
			$var_3		=	'NO_RECURSE';
		}

		if ( checkJversion() == 2 ) {
			$query		=	'SELECT g1.' . $_CB_database->NameQuote( 'id' )
						.	"\n FROM " . $_CB_database->NameQuote( '#__usergroups' ) . " AS g1";

			if ( $var_3 == 'RECURSE' ) {
				$query	.=	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__usergroups' ) . " AS g2"
						.	' ON g2.' . $_CB_database->NameQuote( 'lft' ) . ' < g1.' . $_CB_database->NameQuote( 'lft' )
						.	' AND g2.' . $_CB_database->NameQuote( 'rgt' ) . ' > g1.' . $_CB_database->NameQuote( 'rgt' )
						.	"\n WHERE g2." . $_CB_database->NameQuote( 'id' ) . " = " . (int) $var_1;
			} else {
				$query	.=	"\n WHERE g1." . $_CB_database->NameQuote( 'parent_id' ) . " = " . (int) $var_1;

			}

			$query		.=	"\n ORDER BY g1." . $_CB_database->NameQuote( 'title' );
			$_CB_database->setQuery( $query );
			$return		=	$_CB_database->loadResultArray();
		} else {
			if ( ! $var_2 ) {
				$var_2	=	'ARO';
			}

			$return		=	$this->_acl->get_group_children( $var_1, $var_2, $var_3 );
		}

		return $return;
	}

	function get_group_children_tree( $var_1 = null, $var_2 = null, $var_3 = null, $var_4 = null ) {
		global $_CB_database;

		if ( ! $var_4 ) {
			$var_4						=	true;
		}

		if ( checkJversion() == 2 ) {
			$query						=	'SELECT a.' . $_CB_database->NameQuote( 'id' ) . ' AS value'
										.	', a.' . $_CB_database->NameQuote( 'title' ) . ' AS text'
										.	', COUNT( DISTINCT b.' . $_CB_database->NameQuote( 'id' ) . ' ) AS level'
										.	"\n FROM " . $_CB_database->NameQuote( '#__usergroups' ) . " AS a"
										.	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__usergroups' ) . " AS b"
										.	' ON a.' . $_CB_database->NameQuote( 'lft' ) . ' > b.' . $_CB_database->NameQuote( 'lft' )
										.	' AND a.' . $_CB_database->NameQuote( 'rgt' ) . ' < b.' . $_CB_database->NameQuote( 'rgt' )
										.	"\n GROUP BY a." . $_CB_database->NameQuote( 'id' )
										.	"\n ORDER BY a." . $_CB_database->NameQuote( 'lft' ) . " ASC";
			$_CB_database->setQuery( $query );
			$groups						=	$_CB_database->loadObjectList();

			$user_groups				=	array();

			for ( $i = 0, $n = count( $groups ); $i < $n; $i++ ) {
				$groups[$i]->text		=	str_repeat( '- ', $groups[$i]->level ) . JText::_( $groups[$i]->text );

				if ( $var_4 ) {
					$user_groups[$i]	=	JHtml::_( 'select.option', $groups[$i]->value, $groups[$i]->text );
				} else {
					$user_groups[$i]	=	array( 'value' => $groups[$i]->value, 'text' => $groups[$i]->text );
				}
			}

			$return						=	$user_groups;
		} else {
			if ( ! $var_3 ) {
				$var_3					=	true;
			}

			$return						=	$this->_acl->get_group_children_tree( $var_1, $var_2, $var_3, $var_4 );
		}

		return $return;
	}

	function is_group_child_of( $var_1 = null, $var_2 = null, $var_3 = null ) {
		if ( checkJversion() == 2 ) {
			if ( ! is_integer( $var_1 ) ) {
				$group_src		=	$this->get_group_id( $var_1 );
			}

			$group_children		=	$this->get_group_children( $group_src, null, 'RECURSE' );

			if ( ! is_integer( $var_2 ) ) {
				$group_target	=	$this->get_group_id( $var_2 );
			}

			$return				=	( in_array( $group_target, $group_children ) ? 1 : 0 );
		} else {
			if ( ! $var_3 ) {
				$var_3			=	'ARO';
			}

			$return				=	$this->_acl->is_group_child_of( $var_1, $var_2, $var_3 );
		}

		return $return;
	}

	function get_object_access( $user_id, $recurse = false ) {
		global $_CB_database;

		$user_id			=	(int) $user_id;

		if ( checkJversion() == 2 ) {
			$levels 		=	$this->_acl->getAuthorisedViewLevels( $user_id );

			$return 		=	( $recurse ? $levels : array_slice( $levels, -1 ) );
		} else {
			if ( checkJversion() == 1 ) {
				$user		=&	JFactory::getUser();

				$user->load( $user_id );

				$level		=	$user->get( 'aid', 0 );
			} else {
				$user		=	new mosUser( $_CB_database );

				$user->load( $user_id );

				$level		=	$user->gid;
			}

			$query			=	'SELECT ' . $_CB_database->NameQuote( 'id' )
							.	"\n FROM " . $_CB_database->NameQuote( '#__groups' )
							.	"\n WHERE " . $_CB_database->NameQuote( 'id' ) . " <= " . (int) $level
							.	"\n ORDER BY " . $_CB_database->NameQuote( 'id' );
			$_CB_database->setQuery( $query );
			$levels			=	$_CB_database->loadResultArray();

			for ( $i = 0, $n = count( $levels ); $i < $n; $i++ ) {
				$levels[$i]	=	(int) $levels[$i];
			}

			$return 		=	( $recurse ? $levels : array_slice( $levels, -1 ) );
		}

		return $return;
	}

	function get_access_children_tree( $html = true ) {
		global $_CB_database;

		if ( checkJversion() == 2 ) {
			$levels							=	JHtml::_( 'access.assetgroups' );
			$access_levels					=	array();

			for ( $i = 0, $n = count( $levels ); $i < $n; $i++ ) {
				if ( in_array( $levels[$i]->value, array( 1, 2, 3 ) ) ) {
					--$levels[$i]->value;		// J1.6's 1 is CB's 0, 2 is 1, 3 is 2.
				}
				$levels[$i]->text			=	JText::_( $levels[$i]->text );

				if ( $html ) {
					$access_levels[$i]		=	JHtml::_( 'select.option', $levels[$i]->value, $levels[$i]->text );
				} else {
					$access_levels[$i]		=	array( 'value' => $levels[$i]->value, 'text' => $levels[$i]->text );
				}
			}

			$return							=	$access_levels;
		} else {
			$query							=	'SELECT ' . $_CB_database->NameQuote( 'id' ) . ' AS value'
											.	', ' . $_CB_database->NameQuote( 'name' ) . ' AS text'
											.	"\n FROM " . $_CB_database->NameQuote( '#__groups' )
											.	"\n ORDER BY " . $_CB_database->NameQuote( 'id' );
			$_CB_database->setQuery( $query );
			$levels							=	$_CB_database->loadObjectList();

			$access_levels					=	array();

			for ( $i = 0, $n = count( $levels ); $i < $n; $i++ ) {
				if ( checkJversion() == 1 ) {
					$levels[$i]->text		=	JText::_( $levels[$i]->text );
				}

				if ( $html ) {
					if ( checkJversion() == 1 ) {
						$access_levels[$i]	=	JHTML::_( 'select.option', $levels[$i]->value, $levels[$i]->text );
					} else {
						$access_levels[$i]	=	mosHTML::makeOption( $levels[$i]->value, $levels[$i]->text );
					}
				} else {
					$access_levels[$i]		=	array( 'value' => $levels[$i]->value, 'text' => $levels[$i]->text );
				}
			}

			$return							=	$access_levels;
		}

		return $return;
	}

	function get_allowed_access( $access_gid, $recurse, $user_gid ) {
		if ( ( $access_gid == -2 ) || ( ( $access_gid == -1 ) && ( $user_gid > 0 ) ) ) {
			return true;
		} else {
			if ( $user_gid == $access_gid ) {
				return true;
			} else {
				if ( $recurse == 'RECURSE' ) {
					$group_children	=	$this->get_group_parent_ids( $access_gid );

					if ( is_array( $group_children ) && ( count( $group_children ) > 0 ) ) {
						if ( in_array( $user_gid, $group_children ) ) {
							return true;
						}
					}
				}
			}

			return false;
		}
	}

	function get_group_children_ids( $gid ) {
		global $_CB_database;

		static $gids			=	array();

		$gid					=	(int) $gid;

		if ( ! isset( $gids[$gid] ) ) {
			if ( checkJversion() == 2 ) {
				$query			=	'SELECT g1.' . $_CB_database->NameQuote( 'id' ) . ' AS group_id'
								.	', g1.' . $_CB_database->NameQuote( 'title' ) . ' AS name'
								.	"\n FROM " . $_CB_database->NameQuote( '#__usergroups' ) . " AS g1";
				if ( $gid != 8 ) {
					$query		.=	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__usergroups' ) . " AS g2"
								.	' ON g2.' . $_CB_database->NameQuote( 'rgt' ) . ' <= g1.' . $_CB_database->NameQuote( 'rgt' )
								.	' AND g2.' . $_CB_database->NameQuote( 'lft' ) . ' >= g1.' . $_CB_database->NameQuote( 'lft' )
								.	"\n WHERE g2." . $_CB_database->NameQuote( 'id' ) . " = " . (int) $gid;
				}

				$query			.=	"\n ORDER BY g1." . $_CB_database->NameQuote( 'title' );
				$_CB_database->setQuery( $query );
				$groups			=	$_CB_database->loadResultArray();
			} elseif ( checkJversion() == 1 ) {
				$query			=	'SELECT g1.' . $_CB_database->NameQuote( 'id' ) . ' AS group_id'
								.	', g1.' . $_CB_database->NameQuote( 'name' )
								.	"\n FROM " . $_CB_database->NameQuote( '#__core_acl_aro_groups' ) . " AS g1"
								.	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__core_acl_aro_groups' ) . " AS g2"
								.	' ON g2.' . $_CB_database->NameQuote( 'lft' ) . ' >= g1.' . $_CB_database->NameQuote( 'lft' )
								.	"\n WHERE g2." . $_CB_database->NameQuote( 'id' ) . " = " . (int) $gid
								.	"\n ORDER BY g1." . $_CB_database->NameQuote( 'name' );
				$_CB_database->setQuery( $query );
				$groups			=	$_CB_database->loadResultArray();
			} else {
				$query			=	'SELECT g1.' . $_CB_database->NameQuote( 'group_id' )
								.	', g1.' . $_CB_database->NameQuote( 'name' )
								.	"\n FROM " . $_CB_database->NameQuote( '#__core_acl_aro_groups' ) . " AS g1"
								.	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__core_acl_aro_groups' ) . " AS g2"
								.	' ON g2.' . $_CB_database->NameQuote( 'lft' ) . ' >= g1.' . $_CB_database->NameQuote( 'lft' )
								.	"\n WHERE g2." . $_CB_database->NameQuote( 'group_id' ) . " = " . (int) $gid
								.	"\n ORDER BY g1." . $_CB_database->NameQuote( 'name' );
				$_CB_database->setQuery( $query );
				$groups			=	$_CB_database->loadResultArray();
			}

			for ( $i = 0, $n = count( $groups ); $i < $n; $i++ ) {
				$groups[$i]		=	(int) $groups[$i];
			}

			$standardlist		=	array( -2 );

			if ( $gid > 0 ) {
				$standardlist[]	=	-1;
			}

			$groups				=	array_merge( $groups, $standardlist );

			if ( checkJversion() == 2 ) {
				sort( $groups );
			}

			$gids[$gid]			=	$groups;
		}

		return $gids[$gid];
	}

	function get_group_parent_ids( $gid = null ) {
		global $_CB_database;

		static $gids		=	array();

		$gid				=	(int) $gid;

		if ( ! isset( $gids[$gid] ) ) {
			if ( checkJversion() == 2 ) {
				$query		=	'SELECT g1.' . $_CB_database->NameQuote( 'id' ) . ' AS group_id'
							// .	', g1.' . $_CB_database->NameQuote( 'title' ) . ' AS name'
							.	"\n FROM " . $_CB_database->NameQuote( '#__usergroups' ) . " AS g1"
							.	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__usergroups' ) . " AS g2"
							.	' ON g2.' . $_CB_database->NameQuote( 'rgt' ) . ' >= g1.' . $_CB_database->NameQuote( 'rgt' )
							.	' AND g2.' . $_CB_database->NameQuote( 'lft' ) . ' <= g1.' . $_CB_database->NameQuote( 'lft' )
							.	"\n WHERE g2." . $_CB_database->NameQuote( 'id' ) . " = " . (int) $gid
							.	"\n ORDER BY g1." . $_CB_database->NameQuote( 'title' );
				$_CB_database->setQuery( $query );
				$groups		=	$_CB_database->loadResultArray();

				if ( in_array( $gid, array( 6, 7 ) ) ) {
					// Add the missing super admin if admin or moderator in list, but superadmin is missing, for backwards compatibility:
					array_unshift( $groups, '8' );
				}

			} elseif ( checkJversion() == 1 ) {
				$query		=	'SELECT g1.' . $_CB_database->NameQuote( 'id' ) . ' AS group_id'
							// .	', g1.' . $_CB_database->NameQuote( 'name' )
							.	"\n FROM " . $_CB_database->NameQuote( '#__core_acl_aro_groups' ) . " AS g1"
							.	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__core_acl_aro_groups' ) . " AS g2"
							.	' ON g2.' . $_CB_database->NameQuote( 'lft' ) . ' <= g1.' . $_CB_database->NameQuote( 'lft' )
							.	"\n WHERE g2." . $_CB_database->NameQuote( 'id' ) . " = " . (int) $gid
							.	"\n ORDER BY g1." . $_CB_database->NameQuote( 'name' );
				$_CB_database->setQuery( $query );
				$groups		=	$_CB_database->loadResultArray();
			} else {
				$query		=	'SELECT g1.' . $_CB_database->NameQuote( 'group_id' )
							// .	', g1.' . $_CB_database->NameQuote( 'name' )
							.	"\n FROM " . $_CB_database->NameQuote( '#__core_acl_aro_groups' ) . " AS g1"
							.	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__core_acl_aro_groups' ) . " AS g2"
							.	' ON g2.' . $_CB_database->NameQuote( 'lft' ) . ' <= g1.' . $_CB_database->NameQuote( 'lft' )
							.	"\n WHERE g2." . $_CB_database->NameQuote( 'group_id' ) . " = " . (int) $gid
							.	"\n ORDER BY g1." . $_CB_database->NameQuote( 'name' );
				$_CB_database->setQuery( $query );
				$groups		=	$_CB_database->loadResultArray();
			}

			for ( $i = 0, $n = count( $groups ); $i < $n; $i++ ) {
				$groups[$i]	=	(int) $groups[$i];
			}

			if ( checkJversion() == 2 ) {
				sort( $groups );
			}

			$gids[$gid]		=	$groups;
		}

		return $gids[$gid];
	}

	function get_groups_below_me() {
		global $_CB_framework;

		static $gids		=	array();

		$myId				=	$_CB_framework->myId();

		if ( ! isset( $gids[$myId] ) ) {
			if ( checkJversion() == 2 ) {
				$my_groups	=	$this->get_object_groups( $myId );
			} elseif ( checkJversion() == 1 ) {
				$aro_id		=	$this->get_object_id( 'users', $myId, 'ARO' );
				$my_groups	=	$this->get_object_groups( $aro_id, 'ARO' );
			} else {
				$my_groups	=	$this->get_object_groups( 'users', $myId, 'ARO' );
			}

			if ( is_array( $my_groups ) && count( $my_groups ) > 0 ) {
				$ex_groups	=	$this->get_group_children( $my_groups[0], 'ARO', 'RECURSE' );
			} else {
				$ex_groups	=	array();
			}

			$groups			=	$this->get_group_children_tree( null, 'USERS', false );

			$i				=	0;

			if ( is_array( $ex_groups ) && ( count( $ex_groups ) > 0 ) ) {
				while ( $i < count( $groups ) ) {
					if ( in_array( $groups[$i]->value, $ex_groups ) ) {
						array_splice( $groups, $i, 1 );
					} else {
						$i++;
					}
				}
			}
			$gids[$myId]	=	$groups;
		}

		return $gids[$myId];
	}

	/**
	 * Prepare top most GID from array of IDs
	 *
	 * @param array $gids
	 * @return int
	 */
	function getBackwardsCompatibleGid( $gids ) {
		static $mod			=	null;
		static $admin		=	null;
		static $super_admin	=	null;
		if ( $super_admin === null ) {
			$mod			=	$this->mapGroupNamesToValues( 'Manager' );
			$admin			=	$this->mapGroupNamesToValues( 'Administrator' );
			$super_admin	=	$this->mapGroupNamesToValues( 'Superadministrator' );
		}

		$gids			=	(array) $gids;
		cbArrayToInts( $gids );

		if ( in_array( $super_admin, $gids ) ) {
			$gid		=	$super_admin;
		} elseif ( in_array( $admin, $gids ) ) {
			$gid		=	$admin;
		} elseif ( in_array( $mod, $gids ) ) {
			$gid		=	$mod;
		} else {
			$gid		=	( empty( $gids ) ? null : $gids[( count( $gids ) - 1 )] );
		}

		return $gid;
	}

	/**	 * Remap literal groups (such as in default values) to the hardcoded CMS values
	 *
	 * @param  string|array  $name  of int|string
	 * @return int|array of int
	 */
	function mapGroupNamesToValues( $name ) {
		static $ps					=	null;

		$selected					=	(array) $name;
		foreach ( $selected as $k => $v ) {
			if ( ! is_numeric( $v ) ) {
				if ( ! $ps ) {
					if ( checkJversion() >= 2 ) {
						$ps				=	array( 'Public' =>  1, 'Registered' =>  2, 'Author' =>  3, 'Editor' =>  4, 'Publisher' =>  5, 'Manager' =>  6, 'Administrator' =>  7, 'Superadministrator' =>  8 );
					} else {
						$ps				=	array( 'Public' => 29, 'Registered' => 18, 'Author' => 19, 'Editor' => 20, 'Publisher' => 21, 'Manager' => 23, 'Administrator' => 24, 'Superadministrator' => 25 );
					}
				}
				if ( array_key_exists( $v, $ps ) ) {
					$selected[$k]	=	$ps[$v];
				} else {
					$selected[$k]	=	(int) $v;
				}
			}
		}
		if ( ! is_array( $name ) ) {
			$selected				=	$selected[0];
		}
		return $selected;
	}

	function get_users_permission( $user_ids, $action, $allow_myself = false ) {
		global $_CB_database, $_CB_framework;

		$msg							=	null;

		$cms_admins						=	$this->mapGroupNamesToValues( array( 'Administrator', 'Superadministrator' ) );

		if ( is_array( $user_ids ) && count( $user_ids ) ) {
			$obj						=	new moscomprofilerUser( $_CB_database );

			foreach ( $user_ids as $user_id ) {
				if ( $user_id != 0 ) {
					if ( $obj->load( (int) $user_id ) ) {
						if ( checkJversion() == 2 ) {
							$groups		=	$this->get_object_groups( $user_id );
						} elseif ( checkJversion() == 1 ) {
							$aro_id		=	$this->get_object_id( 'users', $user_id, 'ARO' );
							$groups		=	$this->get_object_groups( $aro_id, 'ARO' );
						} else {
							$groups		=	$this->get_object_groups( 'users', $user_id, 'ARO' );
						}

						if ( isset( $groups[0] ) ) {
							$this_group =	strtolower( $this->get_group_name( $groups[0], 'ARO' ) );
						} else {
							$this_group	=	'Registered';
						}
					} else {
						$msg			.=	'User not found. ';
					}
				} else {
					$this_group			=	'Registered';
					$obj->gid 			=	$this->get_group_id( $this_group, 'ARO' );
				}

				if ( ( ! $allow_myself ) && ( $user_id == $_CB_framework->myId() ) ){
	 				$msg				.=	"You cannot $action Yourself! ";
	 			} else {
	 				$myGid				=	$this->get_user_group_id( $_CB_framework->myId() );

	 				if ( ( ( $obj->gid == $myGid ) && ! in_array( $myGid, $cms_admins ) ) || ( $user_id && $obj->gid && ! in_array( $obj->gid, $this->get_group_children_ids( $myGid ) ) ) ) {
						$msg			.=	"You cannot $action a `$this_group`. Only higher-level users have this power. ";
	 				}
				}
			}
		} else {
			$this_group 				=	'Registered';
			$gid 						=	$this->get_group_id( $this_group, 'ARO' );
			$myGid						=	$this->get_user_group_id( $_CB_framework->myId() );

			if ( ( ( $gid == $myGid ) && ! in_array( $myGid, $cms_admins ) ) || ( $gid && ! in_array( $gid, $this->get_group_children_ids( $myGid ) ) ) ) {				$msg					.=	"You cannot $action a `$this_group`. Only higher-level users have this power. ";
			}
		}

		return $msg;
	}

	function get_user_permission_task( $user_id, $action ) {
		global $_CB_framework, $ueConfig;

		if ( $user_id == 0 ) {
			$user_id					=	$_CB_framework->myId();
		}

		if ( $user_id == 0 ) {
			$ret						=	false;
		} elseif ( $user_id == $_CB_framework->myId() ) {
			$ret						=	null;
		} else {
			if ( ( ! isset( $ueConfig[$action] ) ) || ( $ueConfig[$action] == 0 ) ) {
				$ret					=	_UE_FUNCTIONALITY_DISABLED;
			} elseif ( $ueConfig[$action] == 1 ) {
				$isModerator			=	$this->get_user_moderator( $_CB_framework->myId() );

				if ( ! $isModerator ) {
					$ret				=	false;
				} else {
					$isModerator_user	=	$this->get_user_moderator( $user_id );

					if ( $isModerator_user ) {
						$ret			=	$this->get_users_permission( array( $user_id ), 'edit', true );
					} else {
						$ret			=	null;
					}
				}
			} elseif ( $ueConfig[$action] > 1 ) {
				if ( in_array( $this->get_user_group_id( $_CB_framework->myId() ), $this->get_group_parent_ids( $ueConfig[$action] ) ) ) {
					$ret				=	null;
				} else {
					$ret				=	false;
				}
			} else {
				$ret					=	false;
			}
		}

		if ( $ret === false ) {
			$ret						=	_UE_NOT_AUTHORIZED;

			if ( $_CB_framework->myId() < 1 ) {
				$ret 					.=	'<br />' . _UE_DO_LOGIN;
			}
		}

		return $ret;
	}

	function get_user_moderator( $user_id ) {
		global $ueConfig;

		static $uid			=	array();

		$user_id			=	(int) $user_id;

		if ( ! isset( $uid[$user_id] ) ) {
			$uid[$user_id]	=	( $user_id && in_array( $this->get_user_group_id( $user_id ), $this->get_group_parent_ids( $ueConfig['imageApproverGid'] ) ) );
		}

		return $uid[$user_id];
	}

	function get_user_group_id( $user_id ) {
		global $_CB_database;

		static $gid				=	array();

		$user_id				=	(int) $user_id;

		if ( ! isset( $gid[$user_id] ) ) {
			if ( checkJversion() == 2 ) {
				$query			=	'SELECT ' . $_CB_database->NameQuote( 'group_id' )
								.	"\n FROM " . $_CB_database->NameQuote( '#__user_usergroup_map' )
								.	"\n WHERE " . $_CB_database->NameQuote( 'user_id' ) . " = " . (int) $user_id;
				$_CB_database->setQuery( $query );
				$gid[$user_id]	=	(int) $_CB_database->loadResult();
			} else {
				$query			=	'SELECT ' . $_CB_database->NameQuote( 'gid' )
								.	"\n FROM " . $_CB_database->NameQuote( '#__users' )
								.	"\n WHERE " . $_CB_database->NameQuote( 'id' ) . " = " . (int) $user_id;
				$_CB_database->setQuery( $query );
				$gid[$user_id]	=	(int) $_CB_database->loadResult();
			}
		}

		return $gid[$user_id];
	}
}

/**
 * CB 1.x ACL DEPRECIATED functions:
 */

function isModerator( $oID ) {
	global $_CB_framework;

	return $_CB_framework->acl->get_user_moderator( $oID );
}

function userGID( $oID ){
	global $_CB_framework;

	return $_CB_framework->acl->get_user_group_id( $oID );
}

function allowAccess( $accessgroupid, $recurse, $usersgroupid ) {
	global $_CB_framework;

	return $_CB_framework->acl->get_allowed_access( $accessgroupid, $recurse, $usersgroupid );
}

function cbGetAllUsergroupsBelowMe() {
	global $_CB_framework;

	return $_CB_framework->acl->get_groups_below_me();
}

function getChildGIDS( $gid ) {
	global $_CB_framework;

	return $_CB_framework->acl->get_group_children_ids( $gid );
}

function getParentGIDS( $gid = null ) {
	global $_CB_framework;

	return $_CB_framework->acl->get_group_parent_ids( $gid );
}

function checkCBpermissions( $cid, $actionName, $allowActionToMyself = false ) {
	global $_CB_framework;

	return $_CB_framework->acl->get_users_permission( $cid, $actionName, $allowActionToMyself );
}

function cbCheckIfUserCanPerformUserTask( $uid, $ueConfigVarName ) {
	global $_CB_framework;

	return $_CB_framework->acl->get_user_permission_task( $uid, $ueConfigVarName );
}

// ----- NO MORE CLASSES OR FUNCTIONS PASSED THIS POINT -----
// Post class declaration initialisations
// some version of PHP don't allow the instantiation of classes
// before they are defined
?>