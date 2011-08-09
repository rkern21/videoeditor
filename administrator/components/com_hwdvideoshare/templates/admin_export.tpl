{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
  <tr>
    <td align="left"><h2>{$smarty.const._HWDVIDS_EXPORT_TITLE}</h2></td>
  </tr>
  <tr>
    <td align="left">
      <table width="100%">
        <tr>
          <td width="200"><span onmouseover="return overlib('{$smarty.const._HWDVIDS_EXPORT_TOEMAIL_TT}', CAPTION, '{$smarty.const._HWDVIDS_EXPORT_TOEMAIL}', BELOW, RIGHT);" onmouseout="return nd();" >{$smarty.const._HWDVIDS_EXPORT_TOEMAIL}</span></td>
          <td><input type="text" name="recipient" value="{$mosConfig_mailfrom}" class="text_area" size="40" /></td>
        </tr>
        <tr>
          <td><span onmouseover="return overlib('{$smarty.const._HWDVIDS_EXPORT_SUBJECT_TT}', CAPTION, '{$smarty.const._HWDVIDS_EXPORT_SUBJECT}', BELOW, RIGHT);" onmouseout="return nd();" >{$smarty.const._HWDVIDS_EXPORT_SUBJECT}</span></td>
          <td><input type="text" name="subject" value="{$smarty.const._HWDVIDS_EXPORT_SUBJECT_DEFAULT}" class="text_area" size="40" /></td>
        </tr>
        <tr>
          <td><span onmouseover="return overlib('{$smarty.const._HWDVIDS_EXPORT_BODY_TT}', CAPTION, '{$smarty.const._HWDVIDS_EXPORT_BODY}', BELOW, RIGHT);" onmouseout="return nd();" >{$smarty.const._HWDVIDS_EXPORT_BODY}</span></td>
          <td><input type="text" name="body" value="{$smarty.const._HWDVIDS_EXPORT_BODY_DEFAULT}" class="text_area" size="40" /></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

{include file='admin_footer.tpl'}
