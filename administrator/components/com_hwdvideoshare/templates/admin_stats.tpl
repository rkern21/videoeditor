{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

<h2>{$smarty.const._HWDVIDS_HOME_01}</h2>
<table cellpadding="0" cellspacing="0" border="1" width="100%" class="adminform">
    <tr>
        <td align="left" width="50%">
        <div style="float:right"><b>{$stats.approvals}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=approvals">Videos Waiting Approval</a></b>
        </td>
        <td align="left" width="50%">
        <div style="float:right"><b>{$stats.conversion}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=converter">Videos Waiting Conversion</a></b>
        </td>
    </tr>
    <tr>
        <td align="left">
        <div style="float:right"><b>{$stats.reportedvideos}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=reported">Reported Videos</a></b>
        </td>
        <td align="left">
        <div style="float:right"><b>{$stats.reportedgroups}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=reported">Reported Groups</a></b>
        </td>
    </tr>
    <tr>
        <td align="left">
        <div style="float:right"><b>{$stats.totalvideos}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=videos">Total Videos</a></b>
        </td>
        <td align="left">
        <div style="float:right"><b>{$stats.totalcategories}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=categories">Total Categories</a></b>
        </td>
    </tr>
    <tr>
        <td align="left">
        <div style="float:right"><b>{$stats.totalviews}</b>&nbsp;&nbsp;</div>
        <b>Total View Count</b>
        </td>
        <td align="left">
        <div style="float:right"><b>{$stats.totalfavours}</b>&nbsp;&nbsp;</div>
        <b>Total Favour Count</b>
        </td>
    </tr>
    <tr>
        <td align="left">
        <div style="float:right"><b>{$stats.totalusers}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_users&task=view">Total Members</a></b>
        </td>
        <td align="left">
        <div style="float:right"><b>{$stats.latestuser}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_users&task=view">Latest Member</a></b>
        </td>
    </tr>
    <tr>
        <td align="left">
        <div style="float:right"><b>{$stats.totalgroups}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=groups">Total Groups</a></b>
        </td>
        <td align="left">
        <div style="float:right"><b>{$stats.latestgroup}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=groups">Latest Group</a></b>
        </td>
    </tr>
    <tr>
        <td align="left">
        <div style="float:right"><b>{$stats.totalvideostoday}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=videos">Videos Added Today</a></b>
        </td>
        <td align="left">
        <div style="float:right"><b>{$stats.totalvideosweek}</b>&nbsp;&nbsp;</div>
        <b><a href="index.php?option=com_hwdvideoshare&task=videos">Videos Added This Week</a></b>
        </td>
    </tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td align="left" width="50%">
	    <table cellpadding="0" cellspacing="0" border="1" width="100%" class="adminform">
	        <tr>
		    <th align="left" colspan="2" width="*">MOST POPULAR VIDEOS</th>
		    <th align="left" width="125">Rating</th>
	        </tr>
	        {foreach name=outer item=data key=k from=$mostpopular}
                <tr>
                    <td align="left" width="15" style="width:15px!important;">{$k+1}</td>
                    <td align="left" width="*"><a href="index.php?option=com_hwdvideoshare&task=editvidsA&hidemainmenu=1&cid={$data->id}">{$data->title}</a></td>
                    <td align="left" width="125">{$data->updated_rating}</td>
	        </tr>
	        {/foreach}
            </table>
        </td>
        <td align="left" valign="top">
            <table cellpadding="0" cellspacing="0" border="1" width="100%" class="adminform">
                <tr>
                    <th align="left" colspan="2" width="*">MOST VIEWED VIDEOS</th>
                    <th align="left" width="125">Views</th>
                </tr>
                {foreach name=outer item=data key=k from=$mostviewed}
                <tr>
                    <td align="left" width="15" style="width:15px!important;">{$k+1}</td>
                    <td align="left" width="*"><a href="index.php?option=com_hwdvideoshare&task=editvidsA&hidemainmenu=1&cid={$data->id}">{$data->title}</a></td>
                    <td align="left" width="125">{$data->number_of_views}</td>
                </tr>
                {/foreach}
            </table>
        </td>
    </tr>
    <tr>
        <td align="left" width="50%">
            <table cellpadding="0" cellspacing="0" border="1" width="100%" class="adminform">
                <tr>
                    <th align="left" colspan="2" width="*">MOST RECENT VIDEOS</th>
                    <th align="left" width="125">Date</th>
                </tr>
                {foreach name=outer item=data key=k from=$mostrecent}
                <tr>
                    <td align="left" width="15" style="width:15px!important;">{$k+1}</td>
                    <td align="left" width="*"><a href="index.php?option=com_hwdvideoshare&task=editvidsA&hidemainmenu=1&cid={$data->id}">{$data->title}</a></td>
                    <td align="left" width="125">{$data->date_uploaded}</td>
                </tr>
                {/foreach}
            </table>
        </td>
        <td align="left" valign="top">
            <table cellpadding="0" cellspacing="0" border="1" width="100%" class="adminform">
                <tr>
                    <th align="left" colspan="2" width="*">MOST RECENT GROUPS</th>
                    <th align="left" width="125">Date Created</th>
                </tr>
                {foreach name=outer item=data key=k from=$recentgroups}
                <tr>
                    <td align="left" width="15" style="width:15px!important;">{$k+1}</td>
                    <td align="left" width="*"><a href="index.php?option=com_hwdvideoshare&task=editgrpA&hidemainmenu=1&cid={$data->id}">{$data->group_name}</a></td>
                    <td align="left" width="125">{$data->date}</td>
                </tr>
                {/foreach}
            </table>
        </td>
    </tr>
</table>