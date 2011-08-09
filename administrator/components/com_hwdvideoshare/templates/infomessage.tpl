{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright 2008 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
//    hwdVideoShare Template System:::This template system uses the Smarty Template Engine. 
//    For full documentation, including syntax usage please refer to http://www.smarty.net 
//    or our website at http://www.hwdmediashare.co.uk   
//////
*}

{include file='admin_header.tpl'}

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
  <tr>
    <td align="left"><h2>{$title}</h2></td>
  </tr>
  <tr>
    <td align="left">
      <img src="{$icon}" border="0" style="vertical-align:middle;" />&nbsp;&nbsp;{$message}<br /><br />
      {$backlink}
    </td>
  </tr>
</table>

{include file='admin_footer.tpl'}

