{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}

{if $printConfigFileStatus}<div style="border: solid 1px #333;margin:5px 0 5px 0;padding:5px;text-align:left;font-weight:bold;">{$smarty.const._HWDVIDS_INFO_CONFIGF1} {$config_file_status}</div>{/if}
<div>
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
    <tr>
      <td colspan="3" align="left"><h2>{$smarty.const._HWDVIDS_TITLE_SS}</h2></td>
    </tr>
    <tr>
      <td align="left" valign="top" width="20%"><b>{$smarty.const._HWDVIDS_PATHFFMPEG}</b></td>
      <td align="left" valign="top" width="20%"><input type="text" name="ffmpegpath" value="{$s->ffmpegpath}" size="40" maxlength="100"></td>
      <td align="left" valign="top" width="60%">{$smarty.const._HWDVIDS_SETT_PATHFFMPEG_DESC}</td>
    </tr>
    <tr>
      <td align="left" valign="top" width="20%"><b>{$smarty.const._HWDVIDS_PATHFLVTOOL2}</b></td>
      <td align="left" valign="top" width="20%"><input type="text" name="flvtool2path" value="{$s->flvtool2path}" size="40" maxlength="100"></td>
      <td align="left" valign="top" width="60%">{$smarty.const._HWDVIDS_SETT_PATHFLVTOOL2_DESC}</td>
    </tr>
    <tr>
      <td align="left" valign="top" width="20%"><b>{$smarty.const._HWDVIDS_PATHMENCODER}</b></td>
      <td align="left" valign="top" width="20%"><input type="text" name="mencoderpath" value="{$s->mencoderpath}" size="40" maxlength="100"></td>
      <td align="left" valign="top" width="60%">{$smarty.const._HWDVIDS_SETT_PATHMENCODER_DESC}</td>
    </tr>
    <tr>
      <td align="left" valign="top" width="20%"><b>{$smarty.const._HWDVIDS_PATHPHP}</b></td>
      <td align="left" valign="top" width="20%"><input type="text" name="phppath" value="{$s->phppath}" size="40" maxlength="100"></td>
      <td align="left" valign="top" width="60%">{$smarty.const._HWDVIDS_SETT_PATHPHP_DESC}</td>
    </tr>
    <tr>
      <td align="left" valign="top" width="20%"><b>{$smarty.const._HWDVIDS_PATHWGET}</b></td>
      <td align="left" valign="top" width="20%"><input type="text" name="wgetpath" value="{$s->wgetpath}" size="40" maxlength="100"></td>
      <td align="left" valign="top" width="60%">{$smarty.const._HWDVIDS_SETT_PATHWGET_DESC}</td>
    </tr>
    <tr>
      <td align="left" valign="top" width="20%"><b>{$smarty.const._HWDVIDS_PATHQTFS}</b></td>
      <td align="left" valign="top" width="20%"><input type="text" name="qtfaststart" value="{$s->qtfaststart}" size="40" maxlength="100"></td>
      <td align="left" valign="top" width="60%">{$smarty.const._HWDVIDS_SETT_PATHQTFS_DESC}</td>
    </tr>
  </table>
</div>

{include file='admin_footer.tpl'}
