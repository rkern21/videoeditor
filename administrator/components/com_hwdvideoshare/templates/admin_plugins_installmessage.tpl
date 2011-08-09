{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}

<table class="adminheading">
  <tr>
    <th class="install">{$title}</th>
  </tr>
</table>
<table class="adminform">
  <tr>
    <td align="left">
      <strong>{$message}</strong>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><a href="{$mosConfig_live_site}/administrator/index.php?option=com_hwdvideoshare&task=plugins" style="font-size: 16px; font-weight: bold">{$smarty.const._HWDVIDS_INFO_CONT}</a></td>
  </tr>
</table>

{include file='admin_footer.tpl'}
