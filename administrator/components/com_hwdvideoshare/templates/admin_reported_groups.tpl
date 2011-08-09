{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

<div id="editcell">
  <table class="adminlist">
    <thead>
      <tr>
        <th width="5" class="title">ID</th>
        <th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAllPageBoxes();" /></th>
        <th class="title">{$smarty.const._HWDVIDS_TITLE}</th>
        <th class="title">{$smarty.const._HWDVIDS_REP_DELETEVID}</th>
        <th class="title">{$smarty.const._HWDVIDS_REP_INGOREG}</th>
        <th class="title">{$smarty.const._HWDVIDS_REP_USER}</th>
        <th class="title">{$smarty.const._HWDVIDS_REP_STATUS}</th>
        <th class="title">{$smarty.const._HWDVIDS_REP_DATE}</th>
      </tr>
    </thead>
    <tbody>
      {foreach name=outer item=data from=$list_groups}
      <tr class="row{$data->k}">
        <td>{$data->id}</td>
        <td>{$data->checked}</td>
        <td>{$data->title}</td>
        <td><a href="javascript: void(0);" onclick="return listItemTask('cb{$data->i}','deleteReportedGroup')"><img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/icons/delete.png" border="0" alt="Delete Video" /></a></td>
        <td><a href="javascript: void(0);" onclick="return listItemTask('cb{$data->i}','readReportedGroup')"><img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/icons/add.png" border="0" alt="Keep Video" /></a></td>
        <td>{$data->user}</td>
        <td>{$data->status}</td>
        <td>{$data->date}</td>
      </tr>
      {/foreach}
    </tbody>
    <tfoot>
      <tr><td colspan="11" align="center">{$writePagesLinks}<br />{$writePagesCounter}</td></tr>
    </tfoot>
  </table>
</div>			