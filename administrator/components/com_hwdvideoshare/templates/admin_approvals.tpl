{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}
		
<div id="editcell">
  <table class="adminlist">
    <thead>
      <tr>
        <th width="5" class="title">ID</th>
        <th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll({$totalvideos});" /></th>
        <th class="title">{$smarty.const._HWDVIDS_TITLE}</th>
        <th class="title">{$smarty.const._HWDVIDS_LENGTH}</th>
        <th class="title">{$smarty.const._HWDVIDS_RATING}</th>
        <th class="title">{$smarty.const._HWDVIDS_VIEWS}</th>
        <th class="title">{$smarty.const._HWDVIDS_ACCESS}</th>
        <th class="title">{$smarty.const._HWDVIDS_DATEUPLD}</th>
        <th class="title">{$smarty.const._HWDVIDS_APPROVED}</th>
        <th class="title" width="140">{$smarty.const._HWDVIDS_VAPPROVEPUB}</th>
      </tr>
    </thead>
    <tbody>
      {foreach name=outer item=data from=$list_all}
      <tr class="row{$data->k}">
        <td>{$data->id}</td>
        <td>{$data->checked}</td>
        <td>{$data->title}</td>
        <td>{$data->length}</td>
        <td>{$data->rating}</td>
        <td>{$data->views}</td>
        <td>{$data->access}</td>
        <td>{$data->date}</td>
        <td>{$data->status}</td>
        <td><a href="javascript: void(0);" onclick="return listItemTask('cb{$data->i}','{$data->approve_task}')"><img src="images/{$data->approve_img}" width="12" height="12" border="0" alt="" /></a></td>
      </tr>
      {/foreach}
    </tbody>
    <tfoot>
      <tr><td colspan="10" align="center">{$writePagesLinks}<br />{$writePagesCounter}</td></tr>
    </tfoot>
  </table>
</div>

{include file='admin_footer.tpl'}



