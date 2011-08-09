{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}

<table cellpadding="4" cellspacing="1" border="0" class="adminform">
  <tr>
    <th colspan="2"><h2>{$smarty.const._HWDVIDS_GROUPDET}</h2></th>
  </tr>
  <tr>
    <td valign="top" align="left" width="60%">
      <table>
        <tr>
          <td>{$smarty.const._HWDVIDS_TITLE}</td>
          <td><input name="group_name" value="{$group_name}" size="55" maxlength="500"></td>
        </tr>
        <tr>
          <td valign="top">{$smarty.const._HWDVIDS_DESC}</td>
          <td valign="top"><textarea rows="5" cols="80" name ="group_description">{$group_description}</textarea></td>
        </tr>
      </table>
    </td>
    <td valign="top" align="right" width="40%">
      <table>
        <tr>
          <td valign="top" width="40%">
{literal}
<script language="javascript" type="text/javascript">
<!--
//Browser Support Code
function ajaxChangeUser(){

	document.getElementById('ajaxChangeUserResponse').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/images/icons/loading.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";
	
	var ajaxRequest;  // The variable that makes Ajax possible!

	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			document.getElementById('ajaxChangeUserResponse').innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=changeGroupAdminSelect&cid={/literal}{$vid}{literal}", true);
	ajaxRequest.send(null);
}

//-->
</script>
{/literal}
            {$startpane}
            {$starttab1}
            <table>
              <tr>
                <td>{$smarty.const._HWDVIDS_PUB}</td>
                <td>{$group_published}</td>
              </tr>
              <tr>
                <td>{$smarty.const._HWDVIDS_FEATURED}</td>
                <td>{$group_featured}</td>
              </tr>
	      <tr>
		<td>{$smarty.const._HWDVIDS_ADMIN}</td>
		<td><div id="ajaxChangeUserResponse">{$group_admin} <span onclick="ajaxChangeUser();" style="cursor:pointer;">[{$smarty.const._HWDVIDS_CHANGEUSER}]</span></div></td>
              </tr>
              <tr>
                <td>{$smarty.const._HWDVIDS_ACCESS}</td>
                <td>{$group_access}</td>
              </tr>
              <tr>
                <td>{$smarty.const._HWDVIDS_ACOMMENTS}</td>
                <td>{$group_comments}</td>
              </tr>
            </table>
            {$endtab}
            {$starttab2}
            <table width="100%">
		{foreach name=outer item=data from=$groupVideoList}
			<tr>
				<td style="text-align:left;">{$data->video}</td>
				<td style="text-align:right;">{$data->remove}</td>
			</tr>
		{/foreach}
            </table>
            {$endtab}
            {$starttab3}
            <table width="100%">
		{foreach name=outer item=data from=$groupMemberList}
			<tr>
				<td style="text-align:left;">{$data->member}</td>
				<td style="text-align:right;">{$data->remove}</td>
			</tr>
		{/foreach}
            </table>
            {$endtab}
            {$endpane}
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

{include file='admin_footer.tpl'}
