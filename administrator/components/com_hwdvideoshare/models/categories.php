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

class hwdvids_BE_cats
{
   /**
	* show categories
	*/
	function showcategories()
	{
		global $option, $limit, $limitstart;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$search 		= $app->getUserStateFromRequest( "search{$option}", 'search', '' );
		$search 		= $db->getEscaped( trim( strtolower( $search ) ) );

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidscategories AS a"
							. "\nWHERE a.published 	>= 0"
							);
		$total = $db->loadResult();
		echo $db->getErrorMsg();

		$where = array(
		"a.published 	>= 0",
		);

		if ($search) {
			$where[] = "LOWER(a.category_name) LIKE '%$search%'";
				$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidscategories AS a"
							. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
							);
				$total = $db->loadResult();
				echo $db->getErrorMsg();
		}

		$query = "SELECT a.*"
				. "\nFROM #__hwdvidscategories AS a"
				. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
   				. "\n GROUP BY a.id"
    			. "\n ORDER BY a.ordering, a.category_name"
    			;
		$db->SetQuery( $query );
		$rows = $db->loadObjectList();


		// establish the hierarchy of the categories
		$children = array ();

		// first pass - collect children
		foreach ($rows as $v)
		{
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array ();
			array_push($list, $v);
			$children[$pt] = $list;
		}

		// second pass - get an indent list of the items
		$list = hwdvids_BE_cats::fbTreeRecurse(0, '', array (), $children, '9999');
		$total = count($list);

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );
		//$levellist = JHTML::_('select.integerlist',1, 20, 1, 'levellimit', 'size="1" onchange="document.adminForm.submit();"', $levellimit);
		// perform adjustment to pagenav
		$pageNav = new JPagination( count($list), $limitstart, $limit );
		// slice out elements based on limits
		$list = array_slice($list, $pageNav->limitstart, $pageNav->limit);



		hwdvids_HTML::showcategories($list, $pageNav, $search);
	}
function fbTreeRecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1 ) {

    if (@$children[$id] && $level <= $maxlevel) {
        foreach ($children[$id] as $v) {
            $id = $v->id;
            if ( $type ) {
                $pre     = '&nbsp;';
                $spacer = '...';
            } else {
                $pre     = '- ';
                $spacer = '&nbsp;&nbsp;';
            }

            if ( $v->parent == 0 ) {
                $txt     = $v->category_name;
            } else {
                $txt     = $pre . $v->category_name;
            }
            $pt = $v->parent;
            $list[$id] = $v;
            $list[$id]->treename = "$indent$txt";
            $list[$id]->children = count( @$children[$id] );

            $list = hwdvids_BE_cats::fbTreeRecurse( $id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type );
        }
    }
    return $list;
}

function catTreeRecurse($id, $indent = "&nbsp;&nbsp;&nbsp;", $list, &$children, $maxlevel = 9999, $level = 0, $seperator = " >> ")
{
    if (@$children[$id] && $level <= $maxlevel)
    {
        foreach ($children[$id] as $v)
        {
            $id = $v->id;
            $txt = $v->category_name;
            $pt = $v->parent;
            $list[$id] = $v;
            $list[$id]->treename = "$indent$txt";
            $list[$id]->children = count(@$children[$id]);
            $list = hwdvids_BE_cats::catTreeRecurse($id, "$indent$txt$seperator", $list, $children, $maxlevel, $level + 1);
        //$list = hwdvids_BE_cats::catTreeRecurse( $id, "*", $list, $children, $maxlevel, $level+1 );
        }
    }

    return $list;
}
   /**
	* edit categories
	*/
	function editcategories($cid)
	{
		global $option;
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$app = & JFactory::getApplication();

		$row = new hwdvids_cats( $db );
		$row->load( $cid );

		// fail if checked out not by 'me'
		if ($row->isCheckedOut( $my->id )) {
			//BUMP needs change for multilanguage support
			$app->enqueueMessage('This category is being editted by another user');
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=categories' );
		}

		$db->SetQuery("SELECT * FROM #__hwdvidscategories"
							. "\nWHERE id = $cid");
		$db->loadObject($row);

		if ($cid) {
			$row->checkout( $my->id );
		} else {
			$row->published = 1;
		}

		$gtree=array();
		$gtree[] = JHTML::_('select.option', -2 , '- ' ._HWDVIDS_SELECT_EVERYONE . ' -');	// '- Everybody -'
		$gtree[] = JHTML::_('select.option', -1, '- ' . _HWDVIDS_SELECT_ALLREGUSER . ' -'); // '- All Registered Users -'
		$gtree = array_merge( $gtree, $acl->get_group_children_tree( null, 'USERS', false ));

    	$categoryList = hwd_vs_tools::categoryList(_HWDVIDS_SELECT_NOPAR, $row->parent, _HWDVIDS_INFO_NOCATS, 0, "parent", 0);

		hwdvids_HTML::editcategories($row, $gtree, $categoryList);
	}
	/**
	 * save categories
	 */
	function savecategories()
	{
		global $option;
		$db = & JFactory::getDBO();
		$app = & JFactory::getApplication();
		$c = hwd_vs_Config::get_instance();

		$access_lev_u = Jrequest::getVar( 'access_lev_u', '0' );
		$access_lev_v = Jrequest::getVar( 'access_lev_v', '0' );

		$row = new hwdvids_cats($db);

		if (isset($_FILES['thumbnail_file']['error'])) {

			$file_name_org   = $_FILES['thumbnail_file']['name'];
			$file_ext        = substr($file_name_org, strrpos($file_name_org, '.') + 1);

			$thumbnail_url = JURI::root( true ).'/hwdvideos/thumbs/category'.$_POST['id'].'.'.$file_ext;
			$base_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS;
			$thumbnail_name = 'category'.$_POST['id'];

			$upload_result = hwd_vs_tools::uploadFile("thumbnail_file", $thumbnail_name, $base_Dir, 2, "jpg,jpeg", 1);

			if ($upload_result[0] == "0") {

				$msg = $upload_result[1];
				$app->enqueueMessage($msg);

			} else {

				include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'thumbnail.inc.php');
				$thumb_path_s = JPATH_SITE.DS.'hwdvideos'.DS.'thumbs'.DS.$thumbnail_name.'.'.$file_ext;
				$twidth_s = round($c->con_thumb_n);
				$theight_s = round($c->con_thumb_n*$c->tar_fb);

				list($width, $height, $type, $attr) = @getimagesize($thumb_path_s);
				$ratio = $height/$width;

				if ($ratio < $c->tar_fb) {

					$resized_s = new Thumbnail($thumb_path_s);
					$resized_s->resize(1000, $theight_s);
					$resized_s->cropFromCenter($twidth_s, $theight_s);
					$resized_s->save($thumb_path_s);
					$resized_s->destruct();

				} else {

					$resized_s = new Thumbnail($thumb_path_s);
					$resized_s->resize($twidth_s,1000);
					$resized_s->cropFromCenter($twidth_s, $theight_s);
					$resized_s->save($thumb_path_s);
					$resized_s->destruct();

				}
			}

			// update db with new thumbnail
			$db->SetQuery("UPDATE #__hwdvidscategories SET thumbnail = '$thumbnail_url' WHERE id = ".intval($_POST['id']));
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}

			$msg = "Thumbnail was successfully uploaded";
			$app->enqueueMessage($msg);
			$app->redirect( 'index.php?option=com_hwdvideoshare&Itemid='.$Itemid.'&task=editcatA&hidemainmenu=1&cid='.$_POST['id'] );

		} else {

			if (intval($_POST['id']) !== 0 && (intval($_POST['id']) == intval($_POST['parent']))) {
				$app->enqueueMessage(_HWDVIDS_ALERT_PARENTNOTSELF);
				$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=categories' );
			}

			$_POST['category_name'] = Jrequest::getVar( 'category_name', 'no name supplied' );
			$_POST['category_description'] = Jrequest::getVar( 'category_description', 'no name supplied' );
			$_POST['access_lev_u'] = @implode(",", $access_lev_u);
			$_POST['access_lev_v'] = @implode(",", $access_lev_v);

		}

		// bind it to the table
		if (!$row -> bind($_POST)) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		if(empty($row->category_name)) {
			$app->enqueueMessage(_HWDVIDS_NOTITLE);
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=categories' );
		}

		// store it in the db
		if (!$row -> store()) {
			echo "<script> alert('"
				.$row -> getError()
				."'); window.history.go(-1); </script>\n";
			exit();
		}

		$row->checkin();

		// perform maintenance
		include(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'maintenance_recount.class.php');
		hwd_vs_recount::recountSubcatsInCategory();

		$app->enqueueMessage(_HWDVIDS_ALERT_CATSAVED);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=categories' );
	}
	/**
	 * cancel categories
	 */
	function cancelcat()
	{
		global $option;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();
		$row = new hwdvids_cats( $db );
		$row->bind( $_POST );
		$row->checkin();

		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=categories' );
	}
	/**
	 * delete categories
	 */
	function deletecategories($cid)
	{
		global $option;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$total = count( $cid );
		$catego = join(",", $cid);

		//Check for videos in the category
		$db->setQuery("SELECT category_id FROM #__hwdvidsvideos");
		$result = $db->Query();
		while($row = mysql_fetch_assoc($result)) {
			if ($row['category_id'] == $catego) {
				$app->enqueueMessage(_HWDVIDS_ALERT_CATCONTAINSVIDS);
				$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=categories' );
			}
		}

		//If no videos in category proceed to delete the category
		$db->SetQuery("DELETE FROM #__hwdvidscategories WHERE id IN ($catego)");
		$db->Query();

		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$msg = $total ._HWDVIDS_ALERT_ADMIN_CATDEL." ";
		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=categories' );
	}
	/**
	 * publish/unpublish categories
	 */
	function publishcategory($cid=null, $publishcat=1)
	{
		global $option;
  		$db =& JFactory::getDBO();
		$my = & JFactory::getUser();
		$app = & JFactory::getApplication();

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$action = $publishcat ? 'publishcat' : 'unpublishcat';
			echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
			exit;
		}
		$total = count ( $cid );
		$cids = implode( ',', $cid );

		$db->setQuery( "UPDATE #__hwdvidscategories"
						. "\nSET published =". intval( $publishcat )
						. "\nWHERE id IN ( $cids )"
						. "\nAND ( checked_out = 0 OR ( checked_out = $my->id ) )"
						);

		if (!$db->query()) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		switch ( $publishcat ) {
			case 1:
				$msg = $total ._HWDVIDS_ALERT_ADMIN_CATPUB." ";
				break;

			case 0:
			default:
				$msg = $total ._HWDVIDS_ALERT_ADMIN_CATUNPUB." ";
				break;
		}

		if (count( $cid ) == 1) {
			$row = new hwdvids_cats( $db );
			$row->checkin( $cid[0] );
		}

		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=categories' );
	}
   /**
	*/
	function orderAll($uid, $inc)
	{
		$db = & JFactory::getDBO();
		$app = & JFactory::getApplication();
		$row = new hwdvids_cats($db);
		$row->load($uid);

		if ( ! $row->move( $inc ) ) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=categories' );
	}
   /**
	*/
	function saveOrder()
	{
		$db = & JFactory::getDBO();
		$app = & JFactory::getApplication();

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);
		$conditions	= array ();

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// Instantiate an article table object
    	$row = new hwdvids_cats($db);

		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
				// remember to updateOrder this group
				$condition = 'parent_id = '.(int) $row->parent_id.' AND published >= 0';
				$found = false;
				foreach ($conditions as $cond)
					if ($cond[1] == $condition) {
						$found = true;
						break;
					}
				if (!$found)
					$conditions[] = array ($row->id, $condition);
			}
		}

		// execute updateOrder for each group
		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}

		$msg = JText::_('New ordering saved');
		$app->enqueueMessage($msg);
		$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=categories' );
	}
}
?>