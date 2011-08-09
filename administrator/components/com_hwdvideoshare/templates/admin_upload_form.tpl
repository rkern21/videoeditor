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
    <td width="150">{$smarty.const._HWDVIDS_TITLE} <font class="required">*</font></td>
    <td><input name="title" value="" class="inputbox" size="20" maxlength="500" style="width: 200px;" /></td>
  </tr>
  <tr>
    <td valign="top">{$smarty.const._HWDVIDS_DESC} <font class="required">*</font></td>
    <td valign="top"><textarea rows="4" cols="20" name="description" class="inputbox" style="width: 200px;"></textarea></td>
  </tr>
  <tr>
    <td>{$smarty.const._HWDVIDS_CATEGORY} <font class="required">*</font></td>
    <td>{$categoryselect}</td>
  </tr>
  <tr>
    <td>{$smarty.const._HWDVIDS_TAGS} <font class="required">*</font></td>
    <td>{$smarty.const._HWDVIDS_INFO_TAGS}</td>
  </tr>
  <tr>
    <td></td>
    <td><input name="tags" value="" class="inputbox" size="20" maxlength="1000" style="width: 200px;" /></td>
  </tr>
  <tr>
    <td colspan="2"><font class="required">*</font> {$smarty.const._HWDVIDS_INFO_REQUIREDFIELDS}</td>
  </tr>
</table>
{include file='admin_upload_sharingoptions.tpl'}





