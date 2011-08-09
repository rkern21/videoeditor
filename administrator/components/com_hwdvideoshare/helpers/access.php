<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * ACL functions: original code from com_comprofiler
 *
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC2.13
 */
class hwd_vs_access
{
    /**
     * Grants or prevents access based on group id
     *
     * @param int    $accessgroupid  the group id to check against
     * @param string $recurse  the switch for recursive access check
     * @param int    $usersgroupid  the user's group id
     * @return       True or false
     */
	function allowAccess( $accessgroupid, $recurse, $usersgroupid)
	{
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		if ($accessgroupid == -2 || ($accessgroupid == -1 && $usersgroupid > 0))
		{
			// Grant public access or access to all registered users
			return true;
		}
		else
		{
			// Need to do more checking based on more restrictions
			if ($usersgroupid == $accessgroupid)
			{
				// Direct match
				return true;
			}
			else
			{
				if ($recurse=='RECURSE')
				{
					// Check if there are children groups
					// $groupchildren = hwd_vs_access::getParentGIDS($accessgroupid);
					$groupchildren_site = $acl->get_group_children( $usersgroupid, 'ARO', $recurse );
					$groupchildren_admin = $acl->get_group_children( 30, 'ARO', $recurse );

					if (is_array($groupchildren_site) && is_array($groupchildren_admin))
					{
						$groupchildren = array_merge($groupchildren_site, $groupchildren_admin);
					}
					else if (is_array($groupchildren_site) && !empty($groupchildren_admin))
					{
						$groupchildren = array_push($groupchildren_site, $groupchildren_admin);
					}
					else if (is_array($groupchildren_admin) && !empty($groupchildren_site))
					{
						$groupchildren = array_push($groupchildren_admin, $groupchildren_site);
					}
					else if (is_array($groupchildren_site))
					{
						$groupchildren = $groupchildren_site;
					}
					else
					{
						$groupchildren = $groupchildren_admin;
					}

					if (is_array($groupchildren) && count($groupchildren) > 0)
					{
						if (in_array($usersgroupid, $groupchildren) )
						{
							//match
							return true;
						}
					}
				}
			}
			// Deny access
			return false;
		}
	}
    /**
     * Grants or prevents access based on JACL level
     *
     * @param int    $accessgroupid  the group id to check against
     * @param string $recurse  the switch for recursive access check
     * @param int    $usersgroupid  the user's group id
     * @return       True or false
     */
	function allowLevelAccess( $accessLevelId, $usersAccessId )
	{
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		if ($usersAccessId == 0)
		{
			$allowArry = "0";
		}
		else
		{
			if ($usersAccessId == 1)
			{
				$allowArry = "0,1";
			}
			else if ($usersAccessId == 2)
			{
				$allowArry = "0,1,2";
			}
		}

		$allowArry = @explode(",", $allowArry);
		$checkArry = @explode(",", $accessLevelId);

		$result = array_intersect($allowArry, $checkArry);

		if (count($result) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/**
	 * Gives ACL group id of userid $oID
	 *
	 * @param int $oID   user id
	 * @return int       ACL group id
	 */
	function userGID( $oID )
	{
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		static $uidArry			=	array();	// cache

		$oID					=	(int) $oID;
		if ( ! isset( $uidArry[$oID] ) ) {
		  	if( $oID > 0 ) {
				$query			=	"SELECT gid FROM #__users WHERE id = ".(int) $oID;
				$db->setQuery( $query );
				$uidArry[$oID]	=	$db->loadResult();
			}
			else {
				$uidArry[$oID]	=	0;
			}
		}
		return $uidArry[$oID];
	}
	/**
	 * Gives ACL group name of groupid $gID
	 *
	 * @param int $gID   group id
	 * @return string    ACL group name
	 */
	function groupName( $gID )
	{
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		static $gidArry			=	array();	// cache

		$gID					=	(int) $gID;
		if ( ! isset( $uidArry[$gID] ) ) {
		  	if( $gID > 0 ) {
				$query			=	"SELECT name FROM #__core_acl_aro_groups WHERE id = ".(int) $gID;
				$db->setQuery( $query );
				$uidArry[$gID]	=	$db->loadResult();
			}
			else {
				$uidArry[$gID]	=	_HWDVIDS_UNKNOWN;
			}
		}
		return $uidArry[$gID];
	}
	/**
	 * getParentGIDS
	 */
	function getParentGIDS( $gid )
	{
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		static $gidsArry			=	array();	// cache

		$gid		=	(int) $gid;

		if ( ! isset( $gidsArry[$gid] ) ) {

			$query	=	"SELECT g1.id AS group_id, g1.name"
			."\n FROM #__core_acl_aro_groups g1"
			."\n LEFT JOIN #__core_acl_aro_groups g2 ON g2.lft <= g1.lft"
			."\n WHERE g2.id =" . (int) $gid
			."\n ORDER BY g1.name";

	       	$db->setQuery( $query );
			$gidsArry[$gid]		=	$db->loadResultArray();
	      	if ( ! is_array( $gidsArry[$gid] ) ) {
	       		$gidsArry[$gid]	=	array();
	       	}
		}
		return $gidsArry[$gid];
	}
	/**
	 * getParentGIDS
	 */
	function getRecursiveGIDS( $gid )
	{
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		static $gidsArry			=	array();	// cache

		$gid		=	(int) $gid;

		if ( ! isset( $gidsArry[$gid] ) ) {

			$query	=	"SELECT g1.id AS group_id, g1.name"
			."\n FROM #__core_acl_aro_groups g1"
			."\n LEFT JOIN #__core_acl_aro_groups g2 ON g2.lft >= g1.lft"
			."\n WHERE g2.id =" . (int) $gid
			."\n ORDER BY g1.name";

	       	$db->setQuery( $query );
			$gidsArry[$gid]		=	$db->loadResultArray();
	      	if ( ! is_array( $gidsArry[$gid] ) ) {
	       		$gidsArry[$gid]	=	array();
	       	}
		}
		return $gidsArry[$gid];
	}
	/**
	 * Check Joomla/Mambo version for API
	 *
	 * @return int API version: =0 = mambo 4.5.0-4.5.3+Joomla 1.0.x, =1 = Joomla! 1.1, >1 newever ones: maybe compatible, <0: -1: Mambo 4.6
	 */
	function checkJversion()
	{
		return;
	}
	/**
	 *
	 *
	 *
	 */
	function checkAccess($gtree, $gtree_child, $nav, $usernav, $title, $message_register, $message_denied, $icon, $backlink, $action="core.frontend.access", $noMessage=0)
	{
		global $j15, $j16, $smartyvs;
        $c = hwd_vs_Config::get_instance();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = & JComponentHelper::getParams( 'com_users' );

		if ($j16)
		{
			// Access check.
			if (!JFactory::getUser()->authorise($action, 'com_hwdvideoshare'))
			{
				if ($noMessage == 1)
				{
					return false;
				}
				else
				{
					JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
					if ($my->id == 0)
					{
						$smartyvs->assign("showconnectionbox", 1);
					}
					hwd_vs_tools::infomessage($nav, $usernav, $title, $message_register, $icon, $backlink);
					return false;
				}
			}
		}
		else
		{
			if (!hwd_vs_access::allowAccess( $gtree, $gtree_child, hwd_vs_access::userGID( $my->id )))
			{
				if ( ($my->id < 1) && (!$usersConfig->get( 'allowUserRegistration' ) == '0' && hwd_vs_access::allowAccess( $c->gtree_upld, 'RECURSE', $acl->get_group_id('Registered','ARO') ) ) )
				{
					if ($noMessage == 1)
					{
						return false;
					}
					else
					{
						if ($my->id == 0)
						{
							$smartyvs->assign("showconnectionbox", 1);
						}
						hwd_vs_tools::infomessage($nav, $usernav, $title, $message_register, $icon, $backlink);
						return false;
					}
				}
				else
				{
					if ($noMessage == 1)
					{
						return false;
					}
					else
					{
						if ($my->id == 0)
						{
							$smartyvs->assign("showconnectionbox", 1);
						}
						hwd_vs_tools::infomessage($nav, $usernav, $title, $message_denied, $icon, $backlink);
						return false;
					}
				}
			}
		}
		return true;
	}
}
?>