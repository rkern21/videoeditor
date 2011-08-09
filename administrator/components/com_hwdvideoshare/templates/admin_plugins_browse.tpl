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
        <th width="20" class="title">#</th>
        <th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll({$totalplugins});" /></th>
        <th class="title">{$smarty.const._HWDVIDS_DETAILS_PLUGNAME}</th>
        <th nowrap="nowrap" width="5%" class="title">{$smarty.const._HWDVIDS_DETAILS_PLUGINSTALLED}</th>
        <th nowrap="nowrap" width="5%" class="title">{$smarty.const._HWDVIDS_DETAILS_VPUB}</th>
        <th nowrap="nowrap" align="left" width="10%" class="title">{$smarty.const._HWDVIDS_DETAILS_PLUGTYPE}</th>
        <th nowrap="nowrap" align="left" width="10%" class="title">{$smarty.const._HWDVIDS_DETAILS_PLUGDIR}</th>
        <th nowrap="nowrap" align="left" width="80" class="title">Compatible</th>
      </tr>
    </thead>
    <tbody>
      {foreach name=outer key=k item=data from=$list}
      <tr class="row{$data->k}">
        <td>{$k+1}</td>
        <td>{$data->checked}</td>
        <td>{$data->title}</td>
        <td>{$data->installed}</td>
        <td>{$data->published}</td>
        <td>{$data->type}</td>
        <td>{$data->plugdir}</td>
        <td>{$data->compatible}</td>
      </tr>
      {/foreach}
    </tbody>
    <tfoot>
      <tr>
        <td align="center" colspan="11">{$writePagesLinks}</td>
      </tr>
      <tr>
        <td align="center" colspan="11">{$writePagesCounter}</td>
      </tr>
    </tfoot>
  </table>
</div>

{$hidden_inputs}
</form>

<div style="clear:both;">
  <form enctype="multipart/form-data" action="index.php" method="post" name="filename">
    <table class="adminform">
      <tr>
        <td><h2>{$smarty.const._HWDVIDS_TITLE_NEWPLUG}</h2></td>
      </tr>
      <tr>
        <td align="left">
          {$smarty.const._HWDVIDS_DETAILS_PLUGFILE}
          <input class="text_area" name="userfile" type="file" size="70"/>
          <input class="button" type="submit" value="{$smarty.const._HWDVIDS_BUTTON_UPLDINS}" />
        </td>
      </tr>
    </table>
  <input type="hidden" name="task" value="installPluginUpload"/>
  <input type="hidden" name="option" value="com_hwdvideoshare"/>
  <input type="hidden" name="client" value=""/>
  </form>
</div>

<form action="index.php" method="post">
{include file='admin_footer.tpl'}
