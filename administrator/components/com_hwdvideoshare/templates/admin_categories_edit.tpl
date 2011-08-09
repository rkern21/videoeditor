{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}

{if $print_parentcheck}
{literal}
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancelcat') {
		submitform( pressbutton );
		return;
	}
	
	if (pressbutton == 'homepage') {
		submitform( pressbutton );
		return;
	}
	
	// do field validation
	if (form.category_name.value == ""){
		alert( "{/literal}{$smarty.const._HWDVIDS_NOTITLE}{literal}" );
		return false;
	} else if (form.parent.value == "{/literal}{$category_id}{literal}"){
		alert( "{/literal}{$smarty.const._HWDVIDS_ALERT_PARENTNOTSELF}{literal}" );
		return false;
	} else {
		submitform( pressbutton );
		return;
	}
}
</script>
{/literal}
{else}
{literal}
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancelcat') {
		submitform( 'cancelcat' );
		return;
	}

	if (pressbutton == 'homepage') {
		submitform( 'homepage' );
		return;
	}
	
	// do field validation
	if (form.category_name.value == ""){
		alert( "{/literal}{$smarty.const._HWDVIDS_NOTITLE}{literal}" );
		return false;
	} else if (form.parent.value == ""){
		alert( "{/literal}{$smarty.const._HWDVIDS_ALERT_NOCAT}{literal}" );
		return false;
	} else {
		submitform( pressbutton );
		return;
	}
}
</script>
{/literal}
{/if}



<table cellpadding="4" cellspacing="1" border="0" class="adminform">
  <tr>
    <th colspan="2"><h2>{$smarty.const._HWDVIDS_CATEGORYDET}</h2></th>
  </tr>
  <tr>
    <td valign="top" align="left" width="60%">
      <table>
        <tr>
          <td>{$smarty.const._HWDVIDS_CPARENT}</td>
          <td>{$categoryList}</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>{$smarty.const._HWDVIDS_TITLE}</td>
          <td><input name="category_name" value="{$row->category_name}" size="55" maxlength="500"></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td valign="top">{$smarty.const._HWDVIDS_DESC}</td>
          <td valign="top"><textarea rows="5" cols="80" name ="category_description">{$row->category_description}</textarea></td>
        </tr>
      </table>
    </td>
    <td valign="top" align="right" width="40%">
      <table>
        <tr>
          <td valign="top" width="40%">
            <table class="adminform">
              <tr>
                <td>{$smarty.const._HWDVIDS_PUB}</td>
                <td>{$published}</td>
              </tr>
              {if $print_accessgroups}
              <tr>
                <td valign="top">{$smarty.const._HWDVIDS_CVACCESS}</td>
                <td valign="top">{$cvaccess_g}</td>
              </tr>
              <tr>
                <td valign="top">{$smarty.const._HWDVIDS_INCLUDECHILD}</td>
                <td valign="top">{$access_v_r}</td>
              </tr>
              <tr>
                <td valign="top">{$smarty.const._HWDVIDS_CUACCESS}</td>
                <td valign="top">{$cuaccess_g}</td>
              </tr>	  
              <tr>
                <td valign="top">{$smarty.const._HWDVIDS_INCLUDECHILD}</td>
                <td valign="top">{$access_u_r}</td>
              </tr>	  
              {else}
              <tr>
                <td valign="top">{$smarty.const._HWDVIDS_CVACCESS}</td>
                <td valign="top">{$cvaccess_l}</td>
              </tr>
              <tr>
                <td valign="top">{$smarty.const._HWDVIDS_CUACCESS}</td>
                <td valign="top">{$cuaccess_l}</td>
              </tr>
              {/if}
              <tr>
                <td valign="top">{$smarty.const._HWDVIDS_CVVISIBLE}</td>
                <td valign="top">{$access_b_v}</td>
              </tr>
              <tr>
                <td valign="top">{$smarty.const._HWDVIDS_CCOB}</td>
                <td valign="top">{$order_by_select}</td>
              </tr>
              <tr>
                <td valign="top" colspan="2">
                {$hidden_inputs}
		</form>
                <h3>Custom Thumbnail</h3>
 			
 			{if $print_thumbnail}
 			<div style="float:right;padding:5px;"><img src="{$thumbnail_url}" border="0" alt="" /></div>
			{/if}
			
			<form action="index.php" method="post" enctype="multipart/form-data">
			<div style="padding:2px 0;"><input type="file" name="thumbnail_file" value="" size="30"></div>
			<div style="padding:2px 0;"><input type="submit" value="Upload"></div>
	
			{$hidden_inputs}

			</form>
 			
 			<div style="padding:5px 0;">Delete Custom Thumbnail</div>

                 </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<form>
{include file='admin_footer.tpl'}