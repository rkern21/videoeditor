{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{literal}
<script language="javascript" type="text/javascript">
function chk_importFormThirdParty() {
	var form = document.importFormThirdParty;

	// do field validation
	if (form.embeddump.value == ""){
	    alert( "{/literal}{$smarty.const._HWDVIDS_ALERT_NOURL}{literal}" );
    	    form.embeddump.focus();
	    return false;
	} else if (form.category_id.value == 0) {
	    alert( "{/literal}{$smarty.const._HWDVIDS_ALERT_NOCAT}{literal}" );
    	    form.category_id.focus();
	    return false;
  	}
}
</script>
{/literal}

<div style="text-align:left;padding:5px;margin:5px;border:1px solid #ccc;background:#f5f5ee;">
  <h3>{$smarty.const._HWDVIDS_IMPT_TP_TITLE}</h3>
  {$smarty.const._HWDVIDS_DOCS}: <a href="http://documentation.hwdmediashare.co.uk/wiki/Import_Videos_from_SQL_Backup_File" target="_blank">http://documentation.hwdmediashare.co.uk/wiki/Import_Videos_from_SQL_Backup_File</a>
  <p>{$smarty.const._HWDVIDS_IMPT_TP_DESC}</p>
</div>

<div style="text-align:left;padding:5px;margin:5px;border:1px solid #ccc;">

    <form action="index.php" method="post" enctype="multipart/form-data" name="importFormThirdParty" onsubmit="return chk_importFormThirdParty()">
  
    <div style="float:right;">
      <table cellpadding="0" cellspacing="1" border="0" class="adminform">
        <tr>
          <td align="left" colspan="2" valign="top">{include file='admin_upload_form_tp.tpl'}</td>
        </tr>
      </table>
    </div>

    <table cellpadding="2" cellspacing="2" border="0">
      <tr>
        <td valign="top" width="150">{$smarty.const._HWDVIDS_IMPT_MT}</td>
        <td valign="top">
          <select name="videotype">
            <option value="1">YouTube Videos</option>
            <option value="2">YouTube Playlist</option>
            <option value="3">YouTube User Videos</option>
            <option value="4">YouTube RSS Feed</option>
            <!-- <option value="00">YouTube User's Favorites</option> -->
            <option value="5">Other Third Party Videos</option>
          </select>
        </td>
      </tr>
      <tr>
        <td valign="top">Video URL</td>
        <td valign="top">
          <textarea rows="3" cols="40" name="embeddump"></textarea>
        </td>
      </tr>
      <tr>
         <td valign="top" colspan="2" valign="top"><input type="submit" value="{$smarty.const._HWDVIDS_BUTTON_UPLOAD}"></td>
      </tr>
    </table>

    <input type="hidden" name="option" value="com_hwdvideoshare" />
    <input type="hidden" name="task" value="thirdpartyimport" />
    <input type="hidden" name="hidemainmenu" value="0">
  
    </form>

    <div style="clear:both"></div>

</div>




