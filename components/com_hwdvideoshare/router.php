<?php
function hwdVideoShareBuildRoute(&$query)
{
	require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
	require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'initialise.php');
	hwdvsInitialise::language('plugs');

	$segments = array();

	$db =& JFactory::getDBO();
	jimport('joomla.filter.output');
	$escapeRouteChar	= array('.', '\\', '/', '@', '#', '?', '!', '^', '&', '<', '>', '\'' , '"', '*', ',' );

	if (isset($query['task']))
	{
		switch ($query['task'])
		{
			case 'frontpage':
				$segments[] = URLSafe(_HWDVS_SEF_FP);
				unset( $query['task'] );
			break;

			case 'upload':
				$segments[] = URLSafe(_HWDVS_SEF_UPLOAD);
				unset( $query['task'] );
			break;

			case 'uploadconfirmperl':
				$segments[] = URLSafe(_HWDVS_SEF_UPLOADEDP);
				unset( $query['task'] );
			break;

			case 'uploadconfirmflash':
				$segments[] = URLSafe(_HWDVS_SEF_UPLOADEDF);
				unset( $query['task'] );
			break;

			case 'uploadconfirmphp':
				$segments[] = URLSafe(_HWDVS_SEF_UPLOADEDB);
				unset( $query['task'] );
			break;

			case 'addconfirm':
				$segments[] = URLSafe(_HWDVS_SEF_ADDED);
				unset( $query['task'] );
			break;

			case 'groups':
				$segments[] = URLSafe(_HWDVS_SEF_GROUPS);
				unset( $query['task'] );
			break;

			case 'creategroup':
				$segments[] = URLSafe(_HWDVS_SEF_CREATEGROUP);
				unset( $query['task'] );
			break;

			case 'editgroup':
				$segments[] = URLSafe(_HWDVS_SEF_EDITGROUP);
				unset( $query['task'] );
			break;

			case 'viewgroup':
				$segments[] = URLSafe(_HWDVS_SEF_VIEWGROUP);
				unset( $query['task'] );
			break;

			case 'yourvideos':
				$segments[] = URLSafe(_HWDVS_SEF_YV);
				unset( $query['task'] );
			break;

			case 'yourfavourites':
				$segments[] = URLSafe(_HWDVS_SEF_YF);
				unset( $query['task'] );
			break;

			case 'yourgroups':
				$segments[] = URLSafe(_HWDVS_SEF_YG);
				unset( $query['task'] );
			break;

			case 'yourmemberships':
				$segments[] = URLSafe(_HWDVS_SEF_YM);
				unset( $query['task'] );
			break;

			case 'editvideo':
				$segments[] = URLSafe(_HWDVS_SEF_EDITVIDEO);
				unset( $query['task'] );
			break;

			case 'featuredvideos':
				$segments[] = URLSafe(_HWDVS_SEF_FEATUREDVIDEOS);
				unset( $query['task'] );
			break;

			case 'featuredgroups':
				$segments[] = URLSafe(_HWDVS_SEF_FEATUREDGROUPS);
				unset( $query['task'] );
			break;

			case 'rss':
				$segments[] = URLSafe(_HWDVS_SEF_RSS);
				unset( $query['task'] );
				$segments[] = $query['feed'];
				unset( $query['feed'] );
			break;

			case 'categories':
				$segments[] = URLSafe(_HWDVS_SEF_CATEGORIES);
				unset( $query['task'] );
			break;

			case 'gotocategory':
				$segments[] = URLSafe(_HWDVS_SEF_VC);
				unset( $query['task'] );
			break;

			case 'nextvideo':
				$segments[] = URLSafe(_HWDVS_SEF_NV);
				unset( $query['task'] );
				$segments[] = $query['category_id'];
				unset( $query['category_id'] );
				$segments[] = $query['video_id'];
				unset( $query['video_id'] );
			break;

			case 'previousvideo':
				$segments[] = URLSafe(_HWDVS_SEF_PV);
				unset( $query['task'] );
				$segments[] = $query['category_id'];
				unset( $query['category_id'] );
				$segments[] = $query['video_id'];
				unset( $query['video_id'] );
			break;

			case 'search':
				$segments[] = URLSafe(_HWDVS_SEF_SEARCH);
				unset( $query['task'] );
				if (empty($query['category_id'])) { $query['category_id'] = 0; }
				$segments[] = $query['category_id'];
				unset( $query['category_id'] );
			break;

			case 'displayresults':
				$segments[] = URLSafe(_HWDVS_SEF_DR);
				unset( $query['task'] );
				if (empty($query['category_id'])) { $query['category_id'] = 0; }
				$segments[] = $query['category_id'];
				unset( $query['category_id'] );
			break;

			case 'viewvideo':
				$vid = intval($query['video_id']);
				$sqlquery = 'SELECT v.title, c.category_name'
					   . ' FROM #__hwdvidsvideos AS v'
					   . ' LEFT JOIN #__hwdvidscategories AS c ON c.id = v.category_id'
					   . ' WHERE v.id = '.$vid
					   ;
				$db->SetQuery($sqlquery);
				$row = $db->loadObject();

				if (!isset($row->category_name)) { $row->category_name = ''; }
				if (!isset($row->title)) { $row->title = ''; }

				$categoryName 	= URLSafe(html_entity_decode($row->category_name));
				$videoName 	    = URLSafe(html_entity_decode($row->title));

				$segments[] = URLSafe(_HWDVS_SEF_VIEWVIDEO);
				unset( $query['task'] );
				$segments[] = $query['video_id'];
				unset( $query['video_id'] );
				$segments[] = $categoryName;
				$segments[] = $videoName;
			break;

			case 'viewcategory':
				$cid = intval($query['cat_id']);
				$sqlquery = 'SELECT c.category_name'
					   . ' FROM #__hwdvidscategories AS c'
					   . ' WHERE c.id = '.$cid
					   ;
				$db->SetQuery($sqlquery);
				$category_name = $db->loadResult();

				$categoryName 	= URLSafe(html_entity_decode($category_name));

				$segments[] = URLSafe(_HWDVS_SEF_VIEWCATEGORY);
				unset( $query['task'] );
				$segments[] = $query['cat_id'];
				unset( $query['cat_id'] );
				$segments[] = $categoryName;
			break;

			case 'viewgroup':
				$gid = intval($query['group_id']);
				$sqlquery = 'SELECT g.group_name'
					   . ' FROM #__hwdvidsgroups AS g'
					   . ' WHERE g.id = '.$gid
					   ;
				$db->SetQuery($sqlquery);
				$group_name = $db->loadResult();

				$groupName 	= URLSafe(html_entity_decode($group_name));

				$segments[] = URLSafe(_HWDVS_SEF_VIEWGROUP);
				unset( $query['task'] );
				$segments[] = $query['group_id'];
				unset( $query['group_id'] );
				$segments[] = $groupName;
			break;

			case 'viewchannel':
				$uid = intval($query['user_id']);
				$sqlquery = "SELECT username FROM #__users WHERE id = $uid";
				$db->SetQuery($sqlquery);
				$username = $db->loadResult();

				$segments[] = URLSafe(_HWDVS_SEF_VIEWCHANNEL);
				unset( $query['task'] );
				$segments[] = $uid;
				unset( $query['user_id'] );

				if (isset($username))
				{
					$segments[] = URLSafe($username);
				}
			break;

			default:
				$segments[] = $query['task'];
				unset( $query['task'] );
			break;

////
// downloadfile
// deliverthumb
// savegroup
// deletegroup
// joingroup
// leavegroup
// savevideo
// deletevideo
// setusertemplate
// publishvideo
// rate
// addfavourite
// removefavourite
// addvideotogroup
// reportvideo
// reportgroup
// ajax_rate
// ajax_addtofavourites
// ajax_removefromfavourites
// ajax_reportvideo
// ajax_addvideotogroup
// grabjomsocialplayer
// insertVideo

		}
	}
	return $segments;
}

function hwdVideoShareParseRoute($segments)
{
	require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'config.hwdvideoshare.php');
	require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'initialise.php');
	hwdvsInitialise::language('plugs');

	$vars = array();
	switch($segments[0])
	{
		case URLSafe(_HWDVS_SEF_FP):
			$vars['task'] = 'frontpage';
		break;

		case URLSafe(_HWDVS_SEF_UPLOAD):
			$vars['task'] = 'upload';
		break;

		case URLSafe(_HWDVS_SEF_UPLOADEDP):
			$vars['task'] = 'uploadconfirmperl';
		break;

		case URLSafe(_HWDVS_SEF_UPLOADEDF):
			$vars['task'] = 'uploadconfirmflash';
		break;

		case URLSafe(_HWDVS_SEF_UPLOADEDB):
			$vars['task'] = 'uploadconfirmphp';
		break;

		case URLSafe(_HWDVS_SEF_ADDED):
			$vars['task'] = 'addconfirm';
		break;

		case URLSafe(_HWDVS_SEF_GROUPS):
			$vars['task'] = 'groups';
		break;

		case URLSafe(_HWDVS_SEF_CREATEGROUP):
			$vars['task'] = 'creategroup';
		break;

		case URLSafe(_HWDVS_SEF_EDITGROUP):
			$vars['task'] = 'editgroup';
		break;

		case URLSafe(_HWDVS_SEF_VIEWGROUP):
			$vars['task'] = 'viewgroup';
		break;

		case URLSafe(_HWDVS_SEF_YV):
			$vars['task'] = 'yourvideos';
		break;

		case URLSafe(_HWDVS_SEF_YF):
			$vars['task'] = 'yourfavourites';
		break;

		case URLSafe(_HWDVS_SEF_YG):
			$vars['task'] = 'yourgroups';
		break;

		case URLSafe(_HWDVS_SEF_YM):
			$vars['task'] = 'yourmemberships';
		break;

		case URLSafe(_HWDVS_SEF_EDITVIDEO):
			$vars['task'] = 'editvideo';
		break;

		case URLSafe(_HWDVS_SEF_FEATUREDVIDEOS):
			$vars['task'] = 'featuredvideos';
		break;

		case URLSafe(_HWDVS_SEF_FEATUREDGROUPS):
			$vars['task'] = 'featuredgroups';
		break;

		case URLSafe(_HWDVS_SEF_RSS):
			$vars['task'] = 'rss';
			$vars['feed'] = $segments[1];
		break;

		case URLSafe(_HWDVS_SEF_CATEGORIES):
			$vars['task'] = 'categories';
		break;

		case URLSafe(_HWDVS_SEF_VC):
			$vars['task'] = 'gotocategory';
		break;

		case URLSafe(_HWDVS_SEF_NV):
			$vars['task'] = 'nextvideo';
			$vars['category_id'] = $segments[1];
			$vars['video_id'] = $segments[2];
		break;

		case URLSafe(_HWDVS_SEF_PV):
			$vars['task'] = 'previousvideo';
			$vars['category_id'] = $segments[1];
			$vars['video_id'] = $segments[2];
		break;

		case URLSafe(_HWDVS_SEF_SEARCH):
			$vars['task'] = 'search';
			$vars['category_id'] = $segments[1];
		break;

		case URLSafe(_HWDVS_SEF_DR):
			$vars['task'] = 'displayresults';
			$vars['category_id'] = $segments[1];
		break;

		case URLSafe(_HWDVS_SEF_VIEWVIDEO):
			$vars['task'] = 'viewvideo';
			$vars['video_id'] = $segments[1];
		break;

		case URLSafe(_HWDVS_SEF_VIEWCATEGORY):
			$vars['task'] = 'viewcategory';
			$vars['cat_id'] = $segments[1];
		break;

		case URLSafe(_HWDVS_SEF_VIEWGROUP):
			$vars['task'] = 'viewgroup';
			$vars['group_id'] = $segments[1];
		break;

		case URLSafe(_HWDVS_SEF_VIEWCHANNEL):
			$vars['task'] = 'viewchannel';
			$vars['user_id'] = $segments[1];
		break;

		default:
			$vars['task'] = $segments[0];
		break;
	}
	return $vars;
}

if (!function_exists('URLSafe'))
{
	function URLSafe($string)
	{
		jimport( 'joomla.filter.output' );
		$string = JFilterOutput::stringURLSafe($string);
		return $string;
	}
}

?>