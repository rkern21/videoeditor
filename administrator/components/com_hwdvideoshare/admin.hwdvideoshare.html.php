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

class hwdvids_HTML
{
   /**
	* show frontpage
	*/
	function frontpage($stats, $mostpopular, $mostviewed, $mostrecent, $recentgroups)
	{
		global $smartyvs, $limitstart;

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="option" value="com_hwdvideoshare" />
						  <input type="hidden" name="limitstart" value="'.$limitstart.'" />
		                  <input type="hidden" name="task" value="homepage" />';
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs');
		$startpane = $pane->startPane( 'welcome-pane' );
		$endtab = $pane->endPanel();
		$endpane = $pane->endPane();
		$starttab1 = $pane->startPanel( _HWDVIDS_TAB_STATS, 'panel1' );
		$starttab2 = $pane->startPanel( _HWDVIDS_TAB_INFO, 'panel2' );

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs", $hidden_inputs );
		$smartyvs->assign( "header_title", _HWDVIDS_SECTIONHEAD_HOME );
		$smartyvs->assign( "stats", $stats );
		$smartyvs->assign( "mostpopular", $mostpopular );
		$smartyvs->assign( "mostviewed", $mostviewed );
		$smartyvs->assign( "mostrecent", $mostrecent );
		$smartyvs->assign( "recentgroups", $recentgroups );
		$smartyvs->assign( "startpane", $startpane );
		$smartyvs->assign( "endTab", $endtab );
		$smartyvs->assign( "endpane", $endpane );
		$smartyvs->assign( "starttab1", $starttab1 );
		$smartyvs->assign( "starttab2", $starttab2 );

		/** display template **/
		$smartyvs->display('admin_index.tpl');
		return;
	}
   /**
	* show videos
	*/
	function showvideos($rows, &$pageNav, $searchtext, $category_id, $featuredOnly)
	{
		global $smartyvs, $limitstart, $Itemid, $option;
		$app = & JFactory::getApplication();
		JHTML::_('behavior.tooltip');
		jimport( 'joomla.filesystem.file' );

		$filter_order     = $app->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'date_uploaded', 'cmd' );
		$filter_order_Dir = $app->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'desc', 'word' );

		if ($featuredOnly)
		{
			$orderString = JFile::read(JPATH_SITE.DS.'media'.DS.'hwdvsfeatured.order');
			$featuredArray = explode(",", $orderString);
			$total = count($featuredArray);
			for ($i = 0; $i < $total; $i ++)
			{
				$featuredData = explode("=", $featuredArray[$i]);
				if (isset($featuredData[0]) && isset($featuredData[1]))
				{
					$featuredOrder[$featuredData[0]] = $featuredData[1];
				}
			}
		}

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
						  <input type="hidden" name="option" value="com_hwdvideoshare" />
						  <input type="hidden" name="task" value="videos" />
						  <input type="hidden" name="limitstart" value="'.$limitstart.'" />
						  <input type="hidden" name="hidemainmenu" value="0">
						  <input type="hidden" name="filter_order" value="'.$filter_order.'" />
						  <input type="hidden" name="filter_order_Dir" value="'.$filter_order_Dir.'" />';

		$categoryselectlist = hwd_vs_tools::categoryList(_HWDVIDS_INFO_ANYCAT, $category_id, _HWDVIDS_INFO_NOCATS, 0, "category_id", 0, 'class="inputbox" onChange="document.adminForm.submit()"', true);
		$featuredSelected = $featuredOnly == 1 ?  "selected = \"selected\"" : "";
		$filterFeatured = "<select name=\"featuredOnly\" onChange=\"document.adminForm.submit()\"><option value=\"0\">All</option><option value=\"1\" $featuredSelected>Featured Only</option></select>";

		$search = _HWDVIDS_SEARCHV.'&nbsp;';
		$search.= '<input type="text" name="search" value="'.$searchtext.'" class="text_area" onChange="document.adminForm.submit();" />&nbsp;';
		$search.= $filterFeatured.'&nbsp;';
		$search.= $categoryselectlist.'&nbsp;';
		$search.= _HWDVIDS_RPP.'&nbsp;';
		$search.= $pageNav->getLimitBox().'&nbsp;';

		$ordering = ($filter_order == "category_id" || $filter_order == "ordering") ?  true : false;

		$video_header = ($filter_order == 'title') ? _HWDVIDS_TITLE.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_TITLE;
		$category_header = ($filter_order == 'category_id') ? _HWDVIDS_CATEGORY.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_CATEGORY;
		$length_header = ($filter_order == 'video_length') ? _HWDVIDS_LENGTH.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_LENGTH;
		$rating_header = ($filter_order == 'updated_rating') ? _HWDVIDS_RATING.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_RATING;
		$views_header = ($filter_order == 'number_of_views') ? _HWDVIDS_VIEWS.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_VIEWS;
		$access_header = ($filter_order == 'public_private') ? _HWDVIDS_ACCESS.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_ACCESS;
		$date_header = ($filter_order == 'date_uploaded') ? _HWDVIDS_DATEUPLD.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_DATEUPLD;
		$status_header =  ($filter_order == 'approved') ? _HWDVIDS_APPROVED.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_APPROVED;
		$featured_header = ($filter_order == 'featured') ? _HWDVIDS_FEATURED.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_FEATURED;
		$published_header = ($filter_order == 'published') ? _HWDVIDS_PUB.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_PUB;
		$order_header = ($filter_order == 'ordering') ? _HWDVIDS_ORDER.'&nbsp;<img src="'.JURI::root(true).'/administrator/images/sort_'.$filter_order_Dir.'.png" />' : _HWDVIDS_ORDER;
		if ($ordering)
		{
			if ($featuredOnly)
			{
				$order_header.= JHTML::_('grid.order', $rows, "filesave.png", "saveFeaturedVideoOrder");
			}
			else
			{
				$order_header.= JHTML::_('grid.order', $rows, "filesave.png", "saveVideoOrder");
			}
		}

		/** define template arrays **/
		$list = array();
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];
			if ($featuredOnly && $filter_order == "ordering")
			{
				//order array by featured ordering - not complete
			}
			$link = 'index.php?option=com_hwdvideoshare&task=editvidsA&hidemainmenu=1&cid='. $row->id;
			$toolTipTitle = "Length: $row->video_length<br />Rating: $row->updated_rating/5<br />Views: $row->number_of_views";

			$list[$i]->id = $row->id;
			$list[$i]->checked = JHTML::_('grid.checkedout', $row, $i);
			$list[$i]->title = '<span class="hasTip" title="'.$toolTipTitle.'"><a href="'.$link.'" title="" alt="">'.stripslashes($row->title).'</a></span>';
			$list[$i]->category = hwd_vs_tools::generateCategory( $row->category_id );
			$list[$i]->length = $row->video_length;
			$list[$i]->rating = $row->updated_rating;
			$list[$i]->views = $row->number_of_views;
			$list[$i]->access = hwd_vs_tools::generateVideoAccess($row->public_private);
			$list[$i]->date = $row->date_uploaded;
			$list[$i]->status = hwd_vs_tools::generateVideoStatus($row->approved);
			$list[$i]->published_task = $row->published ? 'unpublish' : 'publish';
			$list[$i]->published_img = $row->published ? 'publish_g.png' : 'publish_x.png';
			$list[$i]->featured_task = $row->featured ? 'unfeature' : 'feature';
			$list[$i]->featured_img =$row->featured ? 'publish_g.png' : 'publish_x.png';
			if ($featuredOnly)
			{
				if (!isset($fo[$row->id])) $fo[$row->id] = 0;
				$list[$i]->ordering = "<input type=\"text\" name=\"order[]\" size=\"4\" value=\"".$featuredOrder[$row->id]."\" class=\"text_area\" style=\"text-align: center\" />";
				$list[$i]->reorderup = $pageNav->orderUpIcon($i, true, "orderFeaturedVideoUp");
				$list[$i]->reorderdown = $pageNav->orderDownIcon($i, $n, true, "orderFeaturedVideoDown");
			}
			else
			{
				$disabled = $ordering ?  '' : 'disabled="disabled"';
				$list[$i]->ordering = "<input type=\"text\" name=\"order[]\" size=\"4\" value=\"".$row->ordering."\" $disabled class=\"text_area\" style=\"text-align: center\" />";
				if ($ordering)
				{
					$list[$i]->reorderup = $pageNav->orderUpIcon($i, ($row->category_id == @$rows[$i-1]->category_id), "orderVideoUp");
					$list[$i]->reorderdown = $pageNav->orderDownIcon($i, $n, ($row->category_id == @$rows[$i+1]->category_id), "orderVideoDown");
				}
				else
				{
					$list[$i]->reorderup = $pageNav->orderUpIcon($i, ($row->category_id == @$rows[$i-1]->category_id), "orderVideoUp", null. null, false);
					$list[$i]->reorderdown = $pageNav->orderDownIcon($i, $n, ($row->category_id == @$rows[$i+1]->category_id), "orderVideoDown", null. null, false);
				}
			}
			$list[$i]->k = $k;
			$list[$i]->i = $i;
			if ($row->video_type == "local" || $row->video_type == "mp4")
			{
				$list[$i]->type = JURI::root(true)."/administrator/components/com_hwdvideoshare/assets/images/icons/local.png";
			}
			else if ($row->video_type == "remote" && substr($row->video_id, 0, 6) !== "embed|")
			{
				$list[$i]->type = JURI::root(true)."/administrator/components/com_hwdvideoshare/assets/images/icons/remote.png";
			}
			else if ($row->video_type == "swf")
			{
				$list[$i]->type = JURI::root(true)."/administrator/components/com_hwdvideoshare/assets/images/icons/swf.png";
			}
			else
			{
				$list[$i]->type = JURI::root(true)."/administrator/components/com_hwdvideoshare/assets/images/icons/thirdparty.png";
			}
			$k = 1 - $k;
		}

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_VIDEOS );
		$smartyvs->assign( "print_search", 1 );
		$smartyvs->assign( "search", $search );
		$smartyvs->assign( "totalvideos", count($rows) );
		$smartyvs->assign( "writePagesLinks", $pageNav->getPagesLinks() );
		$smartyvs->assign( "writePagesCounter", $pageNav->getPagesCounter() );
		$smartyvs->assign( "list_all", $list );

		$smartyvs->assign( "video_sort_header", JHTML::_('grid.sort', $video_header, 'title', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "category_sort_header", JHTML::_('grid.sort', $category_header, 'category_id', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "length_sort_header", JHTML::_('grid.sort', $length_header, 'video_length', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "rating_sort_header", JHTML::_('grid.sort', $rating_header, 'updated_rating', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "views_sort_header", JHTML::_('grid.sort', $views_header, 'number_of_views', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "access_sort_header", JHTML::_('grid.sort', $access_header, 'public_private', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "date_sort_header", JHTML::_('grid.sort', $date_header, 'date_uploaded', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "status_sort_header", JHTML::_('grid.sort', $status_header, 'approved', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "featured_sort_header", JHTML::_('grid.sort', $featured_header, 'featured', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "published_sort_header", JHTML::_('grid.sort', $published_header, 'published', $filter_order_Dir, $filter_order_Dir ) );
		$smartyvs->assign( "ordering_sort_header", JHTML::_('grid.sort', $order_header, 'ordering', $filter_order_Dir, $filter_order_Dir ) );

		/** display template **/
		$smartyvs->display('admin_videos.tpl');
		return;
	}
   /**
	* edit videos
	*/
	function editvideos($row, $cat, $usr, $favs, $flagged)
	{
		global $option, $smartyvs, $Itemid;
		$c = hwd_vs_Config::get_instance();
		jimport('joomla.user.authorization');
		$editor      =& JFactory::getEditor();
		$acl=& JFactory::getACL();

		// force no-cache so new thumbnail will display
		@header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		@header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		@header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		@header( 'Cache-Control: post-check=0, pre-check=0', false );
		@header( 'Pragma: no-cache' );

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="id" value="'.$row->id.'" />
		<input type="hidden" name="video_type" value="'.$row->video_type.'" />
		<input type="hidden" name="task" value="savevid" />';
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs');
		$startpane = $pane->startPane( 'video-edit-pane' );
		$endtab = $pane->endPanel();
		$endpane = $pane->endPane();
		$starttab1 = $pane->startPanel( _HWDVIDS_TAB_BASIC, 'panel1' );
		$starttab2 = $pane->startPanel( _HWDVIDS_TAB_SHARING, 'panel2' );

		//echo '<script type="text/javascript" src="'.JURI::root(true).'/components/com_hwdvideoshare/js/mootools-1.2-core-yc.js"></script>';

		if ($row->public_private == "public")          { $pubsel = "selected=\"selected\""; $regsel=null; $msel=null; $wsel=null; $gsel=null; $lsel=null; }
		else if ($row->public_private == "registered") { $regsel = "selected=\"selected\""; $pubsel=null; $msel=null; $wsel=null; $gsel=null; $lsel=null; }
		else if ($row->public_private == "me")         { $msel = "selected=\"selected\""; $pubsel=null; $regsel=null; $wsel=null; $gsel=null; $lsel=null; }
		else if ($row->public_private == "password")   { $wsel = "selected=\"selected\""; $pubsel=null; $regsel=null; $msel=null; $gsel=null; $lsel=null; }
		else if ($row->public_private == "group")   { $gsel = "selected=\"selected\""; $pubsel=null; $regsel=null; $msel=null; $wsel=null; $lsel=null; }
		else if ($row->public_private == "level")   { $lsel = "selected=\"selected\""; $pubsel=null; $regsel=null; $msel=null; $wsel=null; $gsel=null; }

		$public_private = "<select name=\"public_private\" onChange=\"ShowPasswordField()\">
		                   <option value=\"public\" ".$pubsel.">"._HWDVIDS_SELECT_PUBLIC."</option>
		                   <option value=\"registered\" ".$regsel.">"._HWDVIDS_SELECT_REG."</option>
		                   <option value=\"me\" ".$msel.">"._HWDVIDS_SELECT_ME."</option>
		                   <option value=\"password\" ".$wsel.">"._HWDVIDS_SELECT_PASSWORD."</option>
		                   <option value=\"group\" ".$gsel.">"._HWDVIDS_SELECT_JACG."</option>
		                   <option value=\"level\" ".$lsel.">"._HWDVIDS_SELECT_JACL."</option>
					       </select>";

		$gtree=array();
		$gtree[] = JHTML::_('select.option', -2 , '- ' ._HWDVIDS_SELECT_EVERYONE . ' -');
		$gtree[] = JHTML::_('select.option', -1, '- ' . _HWDVIDS_SELECT_ALLREGUSER . ' -');
		$gtree = array_merge( $gtree, $acl->get_group_children_tree( null, 'USERS', false  ) );

		if ($row->public_private == "group")
		{
			$gtree_video = JHTML::_('select.genericlist', $gtree, 'gtree_video', 'size="4"', 'value', 'text', $row->password);
			$smartyvs->assign( "gtree_video", $gtree_video );
		}
		else
		{
			$gtree_video = JHTML::_('select.genericlist', $gtree, 'gtree_video', 'size="4"', 'value', 'text', '');
			$smartyvs->assign( "gtree_video", $gtree_video );
		}

		if ($row->public_private == "level")
		{
			$jacl_video = hwd_vs_tools::hwdvsMultiAccess( $row->password, 'jacl_video[]' );
			$smartyvs->assign( "jacl_video", $jacl_video );
		}
		else
		{
			$jacl_video = hwd_vs_tools::hwdvsMultiAccess( '', 'jacl_video[]' );
			$smartyvs->assign( "jacl_video", $jacl_video );
		}

		$missingfile=null;
		if ($row->video_type == "local" || $row->video_type == "mp4") {

			$location = _HWDVIDS_DETAILS_SOTS."<br />";
			if (file_exists(JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".flv")) {
				$location.= "<b>"._HWDVIDS_NQFILE.":</b> ".JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".flv<br />";
			}
			else
			{
				$location.= "<b>"._HWDVIDS_NQFILE.":</b> ".JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".flv <b>(MISSING)</b><br />";
				$smartyvs->assign( "print_missingfile", 1 );
			}
			if (file_exists(JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".mp4")) {
				$location.= "<b>"._HWDVIDS_HQFILE.":</b> ".JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".mp4<br />";
			}
			else
			{
				$location.= "<b>"._HWDVIDS_HQFILE.":</b> ".JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".mp4 <b>(MISSING)</b><br />";
				$smartyvs->assign( "print_missingfile", 1 );
			}
		} else if ($row->video_type == "swf") {
			$location = _HWDVIDS_DETAILS_SOTS."<br /><b>"._HWDVIDS_FNAME.":</b> ".JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".swf";
			if (@!file_exists(JPATH_SITE."/hwdvideos/uploads/".$row->video_id.".swf")) {
				$missingfile = "<div style=\"color:#ff0000;font-weight:bold;\">"._HWDVIDS_ALERT_MISSINGVIDFILE."</div>";
			}
		} else if ($row->video_type == "remote") {
			$data = @explode(",", $row->video_id);
			$location = _HWDVIDS_DETAILS_REMSER." (".$row->video_type.")<br /><b>"._HWDVIDS_FURL.":</b> ".$data[0];
		} else if ($row->video_type == "seyret") {

			$data = @explode(",", $row->video_id);
			if ($data[0] == "local") {

				$data = @explode(",", $row->video_id);
				$location = _HWDVIDS_DETAILS_SOTS."<br /><b>"._HWDVIDS_NAME.":</b> ".$data[1];

			} else {

				hwd_vs_tools::getPluginDetails($data[0]);
				$flvurlfunc = preg_replace("/[^a-zA-Z0-9s_-]/", "", $data[0])."PrepareFlvURL";
				if (function_exists($flvurlfunc)) {
					$truepath = $flvurlfunc($data[1].",".$data[2], $row);
					$location = _HWDVIDS_DETAILS_REMSER." (".$data[0].")<br /><b>"._HWDVIDS_FURL.":</b><br /><textarea readonly rows=\"5\" cols=\"60\">".urldecode($truepath)."</textarea>";
				} else {
					$location = _HWDVIDS_DETAILS_REMSER." (".$data[0].")";
				}
			}

		} else {
			hwd_vs_tools::getPluginDetails($row->video_type);
			$flvurlfunc = preg_replace("/[^a-zA-Z0-9s_-]/", "", $row->video_type)."PrepareFlvURL";
			if (function_exists($flvurlfunc)) {
				$truepath = $flvurlfunc($row->video_id, $row);
				$location = _HWDVIDS_DETAILS_REMSER." (".$row->video_type.")<br /><b>"._HWDVIDS_FURL.":</b><br /><textarea readonly rows=\"5\" cols=\"60\">".urldecode($truepath)."</textarea>";
			} else {
				$location = _HWDVIDS_DETAILS_REMSER." (".$row->video_type.")";
			}
		}

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_VIDEOS );
		$smartyvs->assign( "row" , $row );
		$smartyvs->assign( "startpane", $startpane );
		$smartyvs->assign( "endtab", $endtab );
		$smartyvs->assign( "endpane", $endpane );
		$smartyvs->assign( "starttab1", $starttab1 );
		$smartyvs->assign( "starttab2", $starttab2 );
		$smartyvs->assign( "vid", $row->id);

		if ($row->approved == "deleted") {
			$smartyvs->display('admin_videos_edit_deleted.tpl');
			return;
		} else if ($row->approved == "queuedforconversion") {
			$smartyvs->display('admin_videos_edit_queuedforconversion.tpl');
			return;
		} else if ($row->approved == "queuedforthumbnail") {
			$smartyvs->display('admin_videos_edit_queuedforthumbnail.tpl');
			return;
		} else if ($row->approved == "queuedforswf") {
			$smartyvs->display('admin_videos_edit_queuedforswf.tpl');
			return;
		} else if ($row->approved == "converting") {
		    $smartyvs->display( 'admin_videos_edit_converting.tpl');
			return;
		} else if (strpos($row->approved, "converting") || strpos($row->approved, "re-calculate_duration") || strpos($row->approved, "re-generate_thumb")) {
		    $smartyvs->display( 'admin_videos_edit_queuedforconversion.tpl');
			return;
		} else if ($row->approved == "pending") {
		    $smartyvs->assign( 'print_pending', 1 );
		}

		$age_check = "<select name=\"age_check\" size=\"1\" class=\"inputbox\">";
		$age_check.= "<option value=\"-1\""; if ($row->age_check == -1) { $age_check.= " selected=\"selected\""; } $age_check.= ">Global</option>";
		$age_check.= "<option value=\"0\""; if ($row->age_check == 0) { $age_check.= " selected=\"selected\""; } $age_check.= ">Off</option>";

		for ($i=1, $n=100; $i < $n; $i++)
		{
		$age_check.= "<option value=\"$i\""; if ($row->age_check == $i) $age_check.= " selected=\"selected\""; $age_check.= ">$i</option>";
		}

		$age_check.= "</select>";

		$smartyvs->assign( "age_check" , $age_check);
		$smartyvs->assign( "categorylist" , hwd_vs_tools::categoryList(_HWDVIDS_INFO_CHOOSECAT, $row->category_id, _HWDVIDS_INFO_NOCATS, 1) );
		$smartyvs->assign( "title", str_replace('"', "&#34;", stripslashes($row->title)) );
		$smartyvs->assign( "category", hwd_vs_tools::generateCategory( $row->category_id ) );
		$smartyvs->assign( "description", $editor->display("description",stripslashes($row->description),350,250,40,20,1) );
		$smartyvs->assign( "tags", str_replace('"', "&#34;", $row->tags) );
		$smartyvs->assign( "published", hwd_vs_tools::yesnoSelectList( 'published', 'class="inputbox"', $row->published ) );
		$smartyvs->assign( "featured", hwd_vs_tools::yesnoSelectList( 'featured', 'class="inputbox"', $row->featured ) );
		$smartyvs->assign( "dateuploaded", $row->date_uploaded );
		$smartyvs->assign( "duration", $row->video_length );
		$smartyvs->assign( "thumb_snap", $row->thumb_snap );
		$smartyvs->assign( "public_private", $public_private );
		$smartyvs->assign( "allow_comments", hwd_vs_tools::yesnoSelectList( 'allow_comments', 'class="inputbox"', $row->allow_comments ) );
		$smartyvs->assign( "allow_embedding", hwd_vs_tools::yesnoSelectList( 'allow_embedding', 'class="inputbox"', $row->allow_embedding ) );
		$smartyvs->assign( "allow_ratings", hwd_vs_tools::yesnoSelectList( 'allow_ratings', 'class="inputbox"', $row->allow_ratings ) );
		$smartyvs->assign( "link_live_video", JURI::root(true)."/index.php?option=com_hwdvideoshare&task=viewvideo&Itemid=".$Itemid."&video_id=".$row->id );
		$smartyvs->assign( "status", hwd_vs_tools::generateVideoStatus($row->approved) );
		$smartyvs->assign( "videoplayer", hwd_vs_tools::generateVideoPlayer($row) );
		$smartyvs->assign( "missingfile", $missingfile );
		$smartyvs->assign( "location", $location );
		$smartyvs->assign( "thumbnail", hwd_vs_tools::generateThumbnail( $row->id, $row->video_id, $row->video_type, $row->thumbnail, null, null, null, null) );
		$smartyvs->assign( "access", hwd_vs_tools::generateVideoAccess( $row->public_private ) );
		$smartyvs->assign( "rating", hwd_vs_tools::generateExactRating($row) );
		$smartyvs->assign( "views", $row->number_of_views );
		$smartyvs->assign( "user", $usr->username );
		$smartyvs->assign( "favoured", $favs );

		if ($row->video_type == "local" || $row->video_type == "mp4")
		{
			$smartyvs->assign( "remotevideo", 0 );
		}
		else if ($row->video_type == "swf")
		{
			$smartyvs->assign( "remotevideo", 2 );
		}
		else if ($row->video_type == "seyret")
		{
			$data = @explode(",", $row->video_id);
			if ($data[0] == "local")
			{
				$smartyvs->assign( "remotevideo", 0 );
			}
			else
			{
				$smartyvs->assign( "remotevideo", 1 );
			}
		}
		else
		{
			$smartyvs->assign( "remotevideo", 1 );
		}

		$thumbnail_form_code = null;
		// generate thumbnail form
		if ($row->approved == "yes" || $row->approved == "pending") {
			$thumbnail_form_code.= '<h3>Upload Custom Thumbnail</h3>';
			$thumbnail_form_code.= '<p>Upload a custom thumbnail image from your computer.</p>';
			$thumbnail_form_code.= '<form action="index.php" method="post" enctype="multipart/form-data">
			<div style="padding:2px 0;"><input type="file" name="thumbnail_file" value="" size="30"></div>
			<div style="padding:2px 0;"><input type="submit" value="Upload"></div>
			<input type="hidden" name="option" value="'.$option.'" />
			<input type="hidden" name="cid" value="'.$row->id.'" />
			<input type="hidden" name="task" value="editvidsA" />
			<input type="hidden" name="upld_thumbnail" value="1" />
			</form>';
		}
		$smartyvs->assign( "thumbnail_form_code", $thumbnail_form_code );

		/** display template **/
		$smartyvs->display('admin_videos_edit.tpl');
		return;
	}
   /**
	* show categories
	*/
	function showcategories($rows, &$pageNav, $searchtext)
	{
		global $limitstart, $smartyvs, $Itemid;

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="categories" />
		<input type="hidden" name="limitstart" value="'.$limitstart.'" />
		<input type="hidden" name="hidemainmenu" value="0">';
		$search = _HWDVIDS_SEARCHC.'&nbsp;';
		$search.= '<input type="text" name="search" value="'.$searchtext.'" class="text_area" onChange="document.adminForm.submit();" />&nbsp;';
		$search.= _HWDVIDS_RPP.'&nbsp;';
		$search.= $pageNav->getLimitBox().'&nbsp;';

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_CATS );
		$smartyvs->assign( "print_search", 1 );
		$smartyvs->assign( "search", $search );
		$smartyvs->assign( "totalcategories", count($rows) );
		$smartyvs->assign( "writePagesLinks", $pageNav->getPagesLinks() );
		$smartyvs->assign( "writePagesCounter", $pageNav->getPagesCounter() );
		$smartyvs->assign( "saveOrder", JHTML::_('grid.order', $rows, "filesave.png", "saveCategoryOrder" ) );

		/** define template arrays **/
		$list = array();
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$list[$i]->id = $row->id;
			$list[$i]->checked = JHTML::_('grid.checkedout', $row, $i);
            if ($row->parent == 0) {
                $list[$i]->isparent = 1;
			}

			$link = 'index.php?option=com_hwdvideoshare&task=editcatA&hidemainmenu=1&cid='. $row->id;
			$list[$i]->title = '<a href="'.$link.'" title="Edit Category">'.stripslashes($row->treename).'</a>';

            if ($row->access_v == -2) {
				$list[$i]->view_access = _HWDVIDS_SELECT_EVERYONE;
            } else if ($row->access_v == -2) {
				$list[$i]->view_access = _HWDVIDS_SELECT_ALLREGUSER;
			} else {
                $gID = hwd_vs_access::groupName($row->access_v);
				$list[$i]->view_access = $gID;
            }
            if ($row->access_u == -2) {
				$list[$i]->upld_access = _HWDVIDS_SELECT_EVERYONE;
            } else if ($row->access_u == -2) {
				$list[$i]->upld_access = _HWDVIDS_SELECT_ALLREGUSER;
			} else {
                $gID = hwd_vs_access::groupName($row->access_u);
				$list[$i]->upld_access = $gID;
            }
			$list[$i]->published_task = $row->published ? 'unpublishcat' : 'publishcat';
			$list[$i]->published_img = $row->published ? 'publish_g.png' : 'publish_x.png';
			$list[$i]->ordering = "<input type=\"text\" name=\"order[]\" size=\"4\" value=\"".$row->ordering."\" class=\"text_area\" style=\"text-align: center\" />";
			$list[$i]->reorderup = $pageNav->orderUpIcon($i, true, "orderCategoryUp");
			$list[$i]->reorderdown = $pageNav->orderDownIcon($i, $n, true, "orderCategoryDown");
			$list[$i]->k = $k;
			$list[$i]->i = $i;
			$k = 1 - $k;

		}


		$smartyvs->assign( "list", $list );

		/** display template **/
		$smartyvs->display('admin_categories_browse.tpl');
		return;
	}
   /**
	* edit categories
	*/
	function editcategories($row, $gtree, $categoryList)
	{
		global $option, $smartyvs, $task;
		$task        = JRequest::getCmd( 'task', 'frontpage' );
		$c = hwd_vs_Config::get_instance();

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="id" value="'.$row->id.'" />
		<input type="hidden" name="task" value="savecat" />';

		if ($row->access_v_r == "RECURSE") { $recusel = "selected=\"selected\""; $nonesel=null; } else { $nonesel = "selected=\"selected\""; $recusel=null; }
		$access_v_r = "<select name=\"access_v_r\" size=\"1\" class=\"inputbox\">
		                   <option value=\"RECURSE\" ".$recusel.">"._HWDVIDS_YES."</option>
		                   <option value=\"0\" ".$nonesel.">"._HWDVIDS_NO."</option>
					       </select>";
		if ($row->access_u_r == "RECURSE") { $recusel = "selected=\"selected\""; } else { $nonesel = "selected=\"selected\""; }
		$access_u_r = "<select name=\"access_u_r\" size=\"1\" class=\"inputbox\">
		                   <option value=\"RECURSE\" ".$recusel.">"._HWDVIDS_YES."</option>
		                   <option value=\"0\" ".$nonesel.">"._HWDVIDS_NO."</option>
					       </select>";

		$order_by_select = '';
		$order_by_select.= '<select name="order_by" size="1" class="inputbox">
			<option value="0"'; if ($row->order_by == "0") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_GLOBAL.'</option>
			<option value="orderASC"'; if ($row->order_by == "orderASC") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_SELECT_ORDERING.' ASC</option>
			<option value="orderDESC"'; if ($row->order_by == "orderDESC") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_SELECT_ORDERING.' DESC</option>
			<option value="nameASC"'; if ($row->order_by == "nameASC") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_SELECT_NAME.' ASC</option>
			<option value="nameDESC"'; if ($row->order_by == "nameDESC") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_SELECT_NAME.' DESC</option>
			<option value="novidsASC"'; if ($row->order_by == "novidsASC") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_SELECT_NOVIDS.' ASC</option>
			<option value="novidsDESC"'; if ($row->order_by == "novidsDESC") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_SELECT_NOVIDS.' DESC</option>
			<option value="nosubsASC"'; if ($row->order_by == "nosubsASC") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_SELECT_NOSUBS.' ASC</option>
			<option value="nosubsDESC"'; if ($row->order_by == "nosubsDESC") { $order_by_select.= ' selected="selected"'; } $order_by_select.= '>'._HWDVIDS_SELECT_NOSUBS.' DESC</option>
		</select>';

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_CATS );
		$smartyvs->assign( "row" , $row );
		$smartyvs->assign( "categoryList" , $categoryList );
		if ($c->access_method == 0) {
			$smartyvs->assign( "print_accessgroups", 1 );
		} else {
			$smartyvs->assign( "print_accesslevels", 1 );
		}


		if ($task !== "newcat") {
			$smartyvs->assign( "print_parentcheck", 1 );
		}

		$smartyvs->assign( "category_id", $row->id );
		$smartyvs->assign( "published", hwd_vs_tools::yesnoSelectList( 'published', 'class="inputbox"', $row->published ) );
		$smartyvs->assign( "cvaccess_g", JHTML::_('select.genericlist', $gtree, 'access_v', 'size="4"', 'value', 'text', $row->access_v) ) ;
		$smartyvs->assign( "cuaccess_g", JHTML::_('select.genericlist', $gtree, 'access_u', 'size="4"', 'value', 'text', $row->access_u) );
		$smartyvs->assign( "access_v_r", $access_v_r );
		$smartyvs->assign( "access_u_r", $access_u_r );
		$smartyvs->assign( "order_by_select", $order_by_select );
		$smartyvs->assign( "cvaccess_l", hwd_vs_tools::hwdvsMultiAccess( $row->access_lev_v, 'access_lev_v[]' ) );
		$smartyvs->assign( "cuaccess_l", hwd_vs_tools::hwdvsMultiAccess( $row->access_lev_u, 'access_lev_u[]' ) );
		$smartyvs->assign( "access_b_v", hwd_vs_tools::yesnoSelectList( 'access_b_v', 'class="inputbox"', $row->access_b_v ) );
		if (!empty($row->thumbnail)) {
			$smartyvs->assign( "print_thumbnail", 1 );
			$smartyvs->assign( "thumbnail_url", $row->thumbnail );
		}

		/** display template **/
		$smartyvs->display('admin_categories_edit.tpl');
		return;
	}
   /**
	* show groups
	*/
	function showgroups($rows, &$pageNav, $searchtext)
	{
		global $Itemid, $smartyvs, $limitstart;

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="limitstart" value="'.$limitstart.'" />
		<input type="hidden" name="task" value="groups" />
		<input type="hidden" name="hidemainmenu" value="0">';
		$search = _HWDVIDS_SEARCHG.'&nbsp;';
		$search.= '<input type="text" name="search" value="'.$searchtext.'" class="text_area" onChange="document.adminForm.submit();" />&nbsp;';
		$search.= _HWDVIDS_RPP.'&nbsp;';
		$search.= $pageNav->getLimitBox().'&nbsp;';

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_GROUPS );
		$smartyvs->assign( "print_search", 1 );
		$smartyvs->assign( "search", $search );
		$smartyvs->assign( "totalgroups", count($rows) );
		$smartyvs->assign( "writePagesLinks", $pageNav->getPagesLinks() );
		$smartyvs->assign( "writePagesCounter", $pageNav->getPagesCounter() );

		/** define template arrays **/
		$list = array();
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$list[$i]->id = $row->id;
			$list[$i]->checked = JHTML::_('grid.checkedout', $row, $i);
			if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
				$list[$i]->title = stripslashes($row->treename);
			} else {
				$link = 'index.php?option=com_hwdvideoshare&task=editgrpA&hidemainmenu=1&cid='. $row->id;
				$list[$i]->title = '<a href="'.$link.'" title="Edit Group">'.stripslashes($row->group_name).'</a>';
			}
			$list[$i]->description = stripslashes($row->group_description);
			$list[$i]->access = hwd_vs_tools::generateVideoAccess( $row->public_private );
			$list[$i]->date = $row->date;
			$list[$i]->total_members = $row->total_members;
			$list[$i]->total_videos = $row->total_videos;
			$list[$i]->published_task = $row->published ? 'unpublishg' : 'publishg';
			$list[$i]->published_img = $row->published ? 'publish_g.png' : 'publish_x.png';
			$list[$i]->featured_task = $row->featured ? 'unfeatureg' : 'featureg';
			$list[$i]->featured_img =$row->featured ? 'publish_g.png' : 'publish_x.png';
			$list[$i]->k = $k;
			$list[$i]->i = $i;
			$k = 1 - $k;
		}
		$smartyvs->assign( "list", $list );

		/** display template **/
		$smartyvs->display('admin_groups_browse.tpl');
		return;
	}
   /**
	* edit categories
	*/
	function editgroups($row, $groupMembers, $groupVideos)
	{
		global $option, $smartyvs;
		$c = hwd_vs_Config::get_instance();

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="option" value="'.$option.'" />
		<input type="hidden" name="id" value="'.$row->id.'" />
		<input type="hidden" name="task" value="" />';
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs');
		$startpane = $pane->startPane( 'group-edit-pane' );
		$endtab = $pane->endPanel();
		$endpane = $pane->endPane();
		$starttab1 = $pane->startPanel( "Basic", 'panel1' );
		$starttab2 = $pane->startPanel( "Videos", 'panel2' );
		$starttab3 = $pane->startPanel( "Members", 'panel3' );

		if ($row->public_private == "public") { $pubsel = "selected=\"selected\""; $regsel=null; } else { $regsel = "selected=\"selected\""; $pubsel=null; }
		$public_private = "<select name=\"public_private\">
		                   <option value=\"public\" ".$pubsel.">"._HWDVIDS_SELECT_PUBLIC."</option>
		                   <option value=\"registered\" ".$regsel.">"._HWDVIDS_SELECT_REG."</option>
					       </select>";

		$groupMemberList = null;
		for ($i=0, $n=count($groupMembers); $i < $n; $i++)
		{
			$row = $groupMembers[$i];

			$groupMemberList[$i]->member = "$row->username ($row->name)";
			$groupMemberList[$i]->remove = "<a href=\"index.php?option=com_hwdvideoshare&task=removeGroupMember&groupid=$row->groupid&memberid=$row->memberid\"><img border=\"0\" title=\"Remove\" alt=\"Remove\" src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\"></a>";
		}

		$groupVideoList = null;
		for ($i=0, $n=count($groupVideos); $i < $n; $i++)
		{
			$row = $groupVideos[$i];

			$groupVideoList[$i]->video = stripslashes($row->title);
			$groupVideoList[$i]->remove = "<a href=\"index.php?option=com_hwdvideoshare&task=removeGroupVideo&groupid=$row->groupid&videoid=$row->videoid\"><img border=\"0\" title=\"Remove\" alt=\"Remove\" src=\"components/com_hwdvideoshare/assets/images/icons/delete.png\"></a>";
		}

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs", $hidden_inputs );
		$smartyvs->assign( "header_title", _HWDVIDS_SECTIONHEAD_GROUPS );
		$smartyvs->assign( "startpane", $startpane );
		$smartyvs->assign( "endtab", $endtab );
		$smartyvs->assign( "endpane", $endpane );
		$smartyvs->assign( "starttab1", $starttab1 );
		$smartyvs->assign( "starttab2", $starttab2 );
		$smartyvs->assign( "starttab3", $starttab3 );

		$smartyvs->assign( "group_name", stripslashes($row->group_name) );
		$smartyvs->assign( "group_description", stripslashes($row->group_description) );
		$smartyvs->assign( "group_published", hwd_vs_tools::yesnoSelectList( 'published', 'class="inputbox"', $row->published ) );
		$smartyvs->assign( "group_featured", hwd_vs_tools::yesnoSelectList( 'featured', 'class="inputbox"', $row->featured ) );
		$smartyvs->assign( "group_admin", hwd_vs_tools::generateBEUserFromID($row->adminid) );
		$smartyvs->assign( "group_access", $public_private );
		$smartyvs->assign( "group_comments", hwd_vs_tools::yesnoSelectList( 'allow_comments', 'class="inputbox"', $row->allow_comments ) );
		$smartyvs->assign( "groupMemberList", $groupMemberList );
		$smartyvs->assign( "groupVideoList", $groupVideoList );


		/** display template **/
		$smartyvs->display('admin_groups_edit.tpl');
		return;
	}
   /**
	* show server settings
	*/
	function showserversettings()
	{
		global $smartyvs;
		$s = hwd_vs_SConfig::get_instance();

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="serversettings" />
		<input type="hidden" name="hidemainmenu" value="0">';

		$jconfig = new jconfig();
		if ($jconfig->ftp_enable != 1)
		{
			$printConfigFileStatus = 1;
			if (is_writable(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php'))
			{
				$config_file_status = "<span style=\"color:#458B00;\">"._HWDVIDS_INFO_CONFIGF2."</span>.";
			}
			else
			{
				$config_file_status = '<span style="color:#ff0000;">'._HWDVIDS_INFO_CONFIGF3.'</span> ('.JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php)';
			}
		}
		else
		{
			$printConfigFileStatus = 0;
			$config_file_status = '';
		}

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_SS );
  		$smartyvs->assign( "s" , $s );
  		$smartyvs->assign( "config_file_status" , $config_file_status );
  		$smartyvs->assign( "printConfigFileStatus" , $printConfigFileStatus );

		/** display template **/
		$smartyvs->display('admin_settings_server.tpl');
		return;
	}
   /**
	* show server settings
	*/
	function showgeneralsettings(&$gtree)
	{
		global $smartyvs;
		$s = hwd_vs_SConfig::get_instance();
		$c = hwd_vs_Config::get_instance();

		hwdvsInitialise::language('settings');

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'views'.DS.'generalsettings.php');
		hwdvids_HTML_settings::showgeneralsettings($gtree);
		return;
	}
   /**
	* show server settings
	*/
	function showlayoutsettings(&$gtree)
	{
		global $smartyvs;
		$s = hwd_vs_SConfig::get_instance();
		$c = hwd_vs_Config::get_instance();

		hwdvsInitialise::language('settings');

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'views'.DS.'layoutsettings.php');
		hwdvids_HTML_settings::showlayoutsettings($gtree);
		return;
	}
   /**
	* show converter
	*/
	function converter()
	{
		global $smartyvs;

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="hidemainmenu" value="0">';

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_CONVERTOR );

		if (file_exists(JPATH_SITE.DS.'media'.DS.'hwdVideoShare_VideoConversionLog.dat')) {
			$download_log = '<a href="'.JURI::root( true ).'/media/hwdVideoShare_VideoConversionLog.dat" target="_blank">View Log</a>';
		} else {
			$download_log = 'Conversion Log does not exist!';
		}

		$smartyvs->assign( "download_log" , $download_log );

		/** display template **/
		$smartyvs->display('admin_converter.tpl');
		return;
	}
   /**
	* show converter
	*/
	function startconverter($total1, $total2, $total3, $total4, $total5, $total6, $total7)
	{
		global $smartyvs;

		header('Content-type: text/html; charset=utf-8');

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="resetfconv" />
		<input type="hidden" name="hidemainmenu" value="0">';

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_CONVERTOR );
		$smartyvs->assign( "total1" , $total1 );
		$smartyvs->assign( "total2" , $total2 );
		$smartyvs->assign( "total3" , $total3 );
		$smartyvs->assign( "total4" , $total4 );
		$smartyvs->assign( "total5" , $total5 );
		$smartyvs->assign( "total6" , $total6 );
		$smartyvs->assign( "total7" , $total7 );
		$smartyvs->assign( "tool1" , JHTML::_('behavior.tooltip', _HWDVIDS_TT_01B, _HWDVIDS_TT_01H) );

		/** display template **/
		$smartyvs->display('admin_converter_go.tpl');
		exit;
	}
   /**
	* Show waiting approvals
	*/
	function showapprovals($rows, $pageNav)
	{
		global $smartyvs, $limitstart, $Itemid, $option;

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="limitstart" value="'.$limitstart.'" />
		<input type="hidden" name="task" value="approvals" />
		<input type="hidden" name="hidemainmenu" value="0">';
		$search = _HWDVIDS_RPP.'&nbsp;';
		$search.= $pageNav->getLimitBox().'&nbsp;';

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_APPROVALS );
		$smartyvs->assign( "print_search", 1 );
		$smartyvs->assign( "search", $search );
		$smartyvs->assign( "totalvideos", count($rows) );
		$smartyvs->assign( "writePagesLinks", $pageNav->getPagesLinks() );
		$smartyvs->assign( "writePagesCounter", $pageNav->getPagesCounter() );

		/** assign template arrays **/
		$list_all = array();
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$list_all[$i]->id = $row->id;
			$list_all[$i]->checked = JHTML::_('grid.checkedout', $row, $i);
			if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
				$list_all[$i]->title = stripslashes($row->title);
			} else {
				$link = 'index.php?option=com_hwdvideoshare&task=editvidsA&hidemainmenu=1&cid='. $row->id;
				$list_all[$i]->title = '<a href="'.$link.'" title="Edit Category">'.stripslashes($row->title).'</a>';
			}
			$list_all[$i]->length = $row->video_length;
			$list_all[$i]->rating = $row->updated_rating;
			$list_all[$i]->views = $row->number_of_views;
			$list_all[$i]->access = hwd_vs_tools::generateVideoAccess($row->public_private);
			$list_all[$i]->date = $row->date_uploaded;
			$list_all[$i]->status = hwd_vs_tools::generateVideoStatus($row->approved);
			$list_all[$i]->published_task = $row->published ? 'unpublish' : 'publish';
			$list_all[$i]->published_img = $row->published ? 'publish_g.png' : 'publish_x.png';
			$list_all[$i]->featured_task = $row->featured ? 'unfeature' : 'feature';
			$list_all[$i]->featured_img =$row->featured ? 'publish_g.png' : 'publish_x.png';
			$list_all[$i]->approve_task = 'approve';
			$list_all[$i]->approve_img = 'publish_g.png';

			$list_all[$i]->k = $k;
			$list_all[$i]->i = $i;
			$k = 1 - $k;
		}
		$smartyvs->assign( "list_all", $list_all );

		/** display template **/
		$smartyvs->display('admin_approvals.tpl');
		return;
	}
   /**
	* show flagged media
	*/
	function showflagged(&$rowsfv, &$rowsfg)
	{
		global $limitstart, $smartyvs, $Itemid;

		/** define template variables **/
		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="limitstart" value="'.$limitstart.'" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="flagged" />
		<input type="hidden" name="hidemainmenu" value="0">';
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs');
		$startpane = $pane->startPane( 'reported-pane' );
		$endtab = $pane->endPanel();
		$endpane = $pane->endPane();
		$starttab1 = $pane->startPanel( _HWDVIDS_TAB_VIDEO, 'panel-v' );
		$starttab2 = $pane->startPanel( _HWDVIDS_TAB_GROUPS, 'panel-g' );

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs", $hidden_inputs );
		$smartyvs->assign( "header_title", _HWDVIDS_SECTIONHEAD_FLAGGED );
		$smartyvs->assign( "totalvideos", count($rowsfv) );
		$smartyvs->assign( "totalgroups", count($rowsfg) );
		$smartyvs->assign( "startpane", $startpane );
		$smartyvs->assign( "endtab", $endtab );
		$smartyvs->assign( "endpane", $endpane );
		$smartyvs->assign( "starttab1", $starttab1 );
		$smartyvs->assign( "starttab2", $starttab2 );

		$list_videos = array();
		$k = 0;
		for ($i=0, $n=count($rowsfv); $i < $n; $i++) {
			$row = $rowsfv[$i];
			$list_videos[$i]->id = $row->id;
			$list_videos[$i]->checked = JHTML::_('grid.checkedout', $row, $i);
			if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
				$list_videos[$i]->title = stripslashes($row->title);
			} else {
				$link = 'index.php?option=com_hwdvideoshare&task=editvidsA&hidemainmenu=1&cid='. $row->id;
				$list_videos[$i]->title = '<a href="'.$link.'" title="Edit Category">'.stripslashes($row->title).'</a>';
			}
			$list_videos[$i]->user = hwd_vs_tools::generateBEUserFromID($row->userid);
			$list_videos[$i]->status = $row->status;
			$list_videos[$i]->date = $row->date;
			$list_videos[$i]->k = $k;
			$list_videos[$i]->i = $i;
			$k = 1 - $k;
		}
		$smartyvs->assign( "list_videos", $list_videos );

		$cbtotal = count($rowsfv)+1;
		$list_groups = array();
		$k = 0;
		for ($i=0, $n=count($rowsfg); $i < $n; $i++) {
			$row = $rowsfg[$i];
			$list_groups[$i]->id = $row->id;
			$list_groups[$i]->checked = JHTML::_('grid.checkedout', $row, $cbtotal);
			if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
				$list_groups[$i]->title = stripslashes($row->title);
			} else {
				$link = 'index.php?option=com_hwdvideoshare&task=editgrpA&hidemainmenu=1&cid='. $row->id;
				$list_groups[$i]->title = '<a href="'.$link.'" title="Edit Category">'.stripslashes($row->group_name).'</a>';
			}
			$list_groups[$i]->user = hwd_vs_tools::generateBEUserFromID($row->userid);
			$list_groups[$i]->status = $row->status;
			$list_groups[$i]->date = $row->date;
			$list_groups[$i]->k = $k;
			$list_groups[$i]->i = $cbtotal;
			$cbtotal++;
			$k = 1 - $k;
		}
		$smartyvs->assign( "list_groups", $list_groups );

		/** display template **/
		$smartyvs->display('admin_reported.tpl');
		return;
	}
   /**
	* show plugins
	*/
	function plugins()
	{
		global $smartyvs, $limitstart, $option;

		$hidden_inputs = '<input type="hidden" name="option" value="'.$option.'" />
		<input type="hidden" name="task" value="plugins" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="limitstart" value="'.$limitstart.'" />
		<input type="hidden" name="hidemainmenu" value="0" />';
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_PLUGIN );

        $smartyvs->display('admin_plugins.tpl');
		return;
	}
   /**
	* export
	*/
	function backuptables()
	{
		global $smartyvs;
		$config = new JConfig;

		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="botJombackup" />
		<input type="hidden" name="hidemainmenu" value="0">';
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_BCUP );
  		$smartyvs->assign( "mosConfig_mailfrom" , $config->mailfrom );

		$smartyvs->display('admin_export.tpl');
		return;
	}
   /**
	* export
	*/
	function importdata()
	{
		global $smartyvs;
		$db = & JFactory::getDBO();

		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="botJombackup" />
		<input type="hidden" name="hidemainmenu" value="0">';
		jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs');
		$startpane = $pane->startPane( 'video-pane' );
		$endtab = $pane->endPanel();
		$endpane = $pane->endPane();
		$starttab1 = $pane->startPanel( _HWDVIDS_TAB_FTP, 'panel1' );
		$starttab2 = $pane->startPanel( _HWDVIDS_TAB_REMOTE, 'panel2' );
		$starttab3 = $pane->startPanel( _HWDVIDS_TAB_SQL, 'panel3' );
		$starttab4 = $pane->startPanel( _HWDVIDS_TAB_CSV, 'panel4' );
		$starttab5 = $pane->startPanel( _HWDVIDS_TAB_SEYRET, 'panel5' );
		$starttab6 = $pane->startPanel( _HWDVIDS_TAB_TPV, 'panel6' );
		$starttab7 = $pane->startPanel( _HWDVIDS_TAB_PHPM, 'panel7' );
		$starttab8 = $pane->startPanel( _HWDVIDS_TAB_SCAN, 'panel8' );
		$starttab9 = $pane->startPanel( _HWDVIDS_TAB_RTMP, 'panel9' );
		$starttab10= $pane->startPanel( "JomSocial", 'panel10' );

		/** assign template variables **/
		$smartyvs->assign( "hidden_inputs", $hidden_inputs );
		$smartyvs->assign( "header_title", _HWDVIDS_SECTIONHEAD_IMPORT );
		$smartyvs->assign( "startpane", $startpane );
		$smartyvs->assign( "endtab", $endtab );
		$smartyvs->assign( "endpane", $endpane );
		$smartyvs->assign( "starttab1", $starttab1 );
		$smartyvs->assign( "starttab2", $starttab2 );
		$smartyvs->assign( "starttab3", $starttab3 );
		$smartyvs->assign( "starttab4", $starttab4 );
		$smartyvs->assign( "starttab5", $starttab5 );
		$smartyvs->assign( "starttab6", $starttab6 );
		$smartyvs->assign( "starttab7", $starttab7 );
		$smartyvs->assign( "starttab8", $starttab8 );
		$smartyvs->assign( "starttab9", $starttab9 );
		$smartyvs->assign( "starttab10",$starttab10 );
		$smartyvs->assign( "newvideoid", hwd_vs_tools::generateNewVideoid() );

		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_seyret'.DS))
		{
			$smartyvs->assign( "seyretinstalled", 1 );

			$db->SetQuery( 'SELECT count(*) FROM #__seyret_items' );
			$seyretitems1 = $db->loadResult();

			if ($seyretitems1 == 0)
			{
				$db->SetQuery( 'SELECT count(*) FROM #__seyret_video' );
				$seyretitems2 = $db->loadResult();
			}

			if ($seyretitems1 == 0 && $seyretitems2 == 0)
			{
				$smartyvs->assign( "seyretinstalled", 0 );
			}
			else if ($seyretitems1 > 0)
			{
				$smartyvs->assign( "seyretitems", $seyretitems1 );
				//get seyret categories
				$db->setQuery( "SELECT `id` AS `key`, `categoryname` AS `text` FROM #__seyret_categories ORDER BY categoryname" );
				$rows_seyret = $db->loadObjectList();
			}
			else if ($seyretitems2 > 0)
			{
				$smartyvs->assign( "seyretitems", $seyretitems2 );
				//get seyret categories
				$db->setQuery( "SELECT `id` AS `key`, `categoryname` AS `text` FROM #__seyret_category ORDER BY categoryname" );
				$rows_seyret = $db->loadObjectList();
			}

			$n = count($rows_seyret);
			$rows_seyret[$n]->key = "-1";
			$rows_seyret[$n]->text = "All Categories";

			$seyretcatsel = JHTML::_('select.genericlist', $rows_seyret, 'seyretcid', 'class="inputbox" size="1"', 'key', 'text', -1);

			$smartyvs->assign( "seyretcatsel", $seyretcatsel );
		}
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_community'.DS))
		{
			$smartyvs->assign( "jomsocialinstalled", 1 );

			$db->SetQuery( 'SELECT count(*) FROM #__community_videos' );
			$jomsocialitems = $db->loadResult();

			$smartyvs->assign( "jomsocialitems", $jomsocialitems );

			$db->setQuery( "SELECT `id` AS `key`, `name` AS `text` FROM #__community_videos_category ORDER BY name" );
			$rows_jsvc = $db->loadObjectList();

			$n = count($rows_seyret);
			$rows_jsvc[$n]->key = "-1";
			$rows_jsvc[$n]->text = "All Categories";

			$jsvcSelect = JHTML::_('select.genericlist', $rows_jsvc, 'jsvcid', 'class="inputbox" size="1"', 'key', 'text', -1);

			$smartyvs->assign( "jsvcSelect", $jsvcSelect );
		}
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_achtube'.DS))
		{
			$smartyvs->assign( "achtubeinstalled", 1 );
		}

		$smartyvs->display('admin_import.tpl');
		return;
	}
   /**
	* system cleanup
	*/
	function maintenance($permdelete_report, $total, $fixerrors_report, $recount_report, $archivelogs_report, $fixerror_cache, $recount_cache, $archive_cache)
	{
		global $smartyvs;

		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="option" value="com_hwdvideoshare" />
				<input type="hidden" name="task" value="runmaintenance" />
				<input type="hidden" name="hidemainmenu" value="0">';
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_CLUP );
		$smartyvs->assign( "permdelete_report" , $permdelete_report );
		$smartyvs->assign( "total" , $total );
		$smartyvs->assign( "fixerrors_report" , $fixerrors_report );
		$smartyvs->assign( "recount_report" , $recount_report );
		$smartyvs->assign( "archivelogs_report" , $archivelogs_report );
		$smartyvs->assign( "fixerror_cache" , $fixerror_cache );
		$smartyvs->assign( "recount_cache" , $recount_cache );
		$smartyvs->assign( "archive_cache" , $archive_cache );

		$smartyvs->display('admin_maintenance.tpl');
		return;
	}
   /**
	* system cleanup
	*/
	function initialise()
	{
		global $smartyvs;

		$hidden_inputs = '<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="com_hwdvideoshare" />
		<input type="hidden" name="task" value="initialise_now" />
		<input type="hidden" name="hidemainmenu" value="0">';
		$smartyvs->assign( "hidden_inputs" , $hidden_inputs );
		$smartyvs->assign( "header_title" , _HWDVIDS_SECTIONHEAD_HOME );
		$smartyvs->assign( "block_maintenance", 1 );

		$smartyvs->display('admin_initialise.tpl');
		return;
	}
}
?>