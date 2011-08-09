{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

<table width="100%" cellpadding="2" cellspacing="2" border="0">
  <tr>
    <td width="150">{$smarty.const._HWDVIDS_CATEGORY} <font class="required">*</font></td>
    <td>{$categoryselect}</td>
  </tr>
  <tr>
    <td colspan="2"><font class="required">*</font> {$smarty.const._HWDVIDS_INFO_REQUIREDFIELDS}</td>
  </tr>
</table>
{include file='admin_upload_sharingoptions.tpl'}





