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
    <td align="left">
    <div style="float:right;padding: 5px;">{$download_log}</div>
    <h2>{$smarty.const._HWDVIDS_TITLE_HWDVCONVERTOR}</h2>
    </td>
  </tr>
  <tr>
    <td align="left"><center><iframe src ="{$mosConfig_live_site}/administrator/index.php?option=com_hwdvideoshare&task=startconverter" frameborder="0" marginwidth="2" scrolling= "yes" height="500" width="95%" style="border:1px solid black; padding: 1px;"></iframe></center></td>
  </tr>
</table>

{include file='admin_footer.tpl'}


