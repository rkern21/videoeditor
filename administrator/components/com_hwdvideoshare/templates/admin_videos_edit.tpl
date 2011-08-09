{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}
		
{literal}
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancelvid') {
		submitform( pressbutton );
		return;
	}
	if (pressbutton == 'homepage') {
		submitform( pressbutton );
		return;
	}
	// do field validation
	if (form.title.value == ""){
		alert( "{/literal}{$smarty.const._HWDVIDS_ALERT_NOTITLE}{literal}" );
		return false;
	//} if (form.description.value == ""){
	//	alert( "{/literal}{$smarty.const._HWDVIDS_ALERT_NODESC}{literal}" );
	//	return false;
	//} if (form.tags.value == ""){
		alert( "{/literal}{$smarty.const._HWDVIDS_ALERT_NOTAG}{literal}" );
		return false;
	} if (form.category_id.value == "-1"){
		alert( "{/literal}{$smarty.const._HWDVIDS_ALERT_NOCAT}{literal}" );
		return false;
	} else {
		submitform( pressbutton );
		return;
	}
}
</script>
{/literal}

{if $print_pending}
<table cellpadding="4" cellspacing="1" border="0" class="adminform">
  <tr>
    <td width="50%" style="width:50%;" valign="top">
      <div style="margin:5px;border:solid 1px #ff0000;padding:5px;width:100%:">
        <center><b>This video is <a href="index.php?option=com_hwdvideoshare&task=approvals">pending approval</a>.</b></center>        
      </div>
    </td>
  </tr>
</table>
{/if}

<table cellpadding="4" cellspacing="1" border="0" class="adminform">
  <tr>
    <td colspan="2">
      <h1>Edit Video Details</h1>
        <table cellpadding="4" cellspacing="0" border="0" width="100%">
          <tr>
            <td valign="top" align="left" width="60%">
              <table>
                <tr>
                  <td valign="top">{$smarty.const._HWDVIDS_TITLE}</td>
                  <td><input name="title" value="{$title}" size="55" maxlength="500"></td>
                </tr>
                <tr>
                  <td valign="top">{$smarty.const._HWDVIDS_CATEGORY}</td>
                  <td>{$categorylist}</td>
                </tr>
                <tr>
                  <td valign="top">{$smarty.const._HWDVIDS_TAGS}</td>
                  <td><input name="tags" value="{$tags}" size="55" maxlength="1000"></td>
                </tr>
                <tr>
                  <td valign="top">{$smarty.const._HWDVIDS_DESC}</td>
                  <td>{$description}</td>
                </tr>
              </table>
            </td>
            <td valign="top" align="right" width="40%">
              {$startpane}
              {$starttab1}
              <table>
                <tr>
                  <td>{$smarty.const._HWDVIDS_PUB}</td>
                  <td>{$published}</td>
                </tr>
                <tr>
                  <td>{$smarty.const._HWDVIDS_FEATURED}</td>
                  <td>{$featured}</td>
                </tr>
                <tr>
                  <td>{$smarty.const._HWDVIDS_DATEUPLD}</td>
                  <td><input name="date_uploaded" value="{$dateuploaded}" size="20" maxlength="50"></td>
                </tr>
                <tr>
                  <td>{$smarty.const._HWDVIDS_LENGTH}</td>
                  <td><input name="video_length" value="{$duration}" size="20" maxlength="50"></td>
                </tr>
                <tr>
                  <td>{$smarty.const._HWDVIDS_THUMBPOS}</td>
                  <td><input name="thumb_snap" value="{$thumb_snap}" size="20" maxlength="50"></td>
                </tr>   
                <tr>
                  <td>{$smarty.const._HWDVIDS_VIEWS}</td>
                  <td><input name="views" value="{$views}" size="20" maxlength="50"></td>
                </tr> 
                <tr>
                  <td>Age Check</td>
                  <td>{$age_check}</td>
                </tr>                 
                
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
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=changeuserselect&cid={/literal}{$vid}{literal}", true);
	ajaxRequest.send(null);
}

//-->
</script>
{/literal}
{literal}
<script language="javascript">
function ShowPasswordField(){
	box = document.adminForm.public_private;
	uploadstatus = box.options[box.selectedIndex].value;
		if (uploadstatus == 'password')
		{
		document.getElementById("passwordField").style.visibility="visible";
		document.getElementById("passwordField").style.height="auto";
		}
		else
		{
		document.getElementById("passwordField").style.visibility="hidden";
		document.getElementById("passwordField").style.height="0px";
		}
		if (uploadstatus == 'group')
		{
		document.getElementById("groupField").style.visibility="visible";
		document.getElementById("groupField").style.height="auto";
		}
		else
		{
		document.getElementById("groupField").style.visibility="hidden";
		document.getElementById("groupField").style.height="0px";
		}
		if (uploadstatus == 'level')
		{
		document.getElementById("levelField").style.visibility="visible";
		document.getElementById("levelField").style.height="auto";
		}
		else
		{
		document.getElementById("levelField").style.visibility="hidden";
		document.getElementById("levelField").style.height="0px";
		}
}
</script>
{/literal}

                <tr>
                  <td>{$smarty.const._HWDVIDS_UPLOADER}</td>
                  <td><div id="ajaxChangeUserResponse">{$user} <span onclick="ajaxChangeUser();" style="cursor:pointer;">[{$smarty.const._HWDVIDS_CHANGEUSER}]</span></div></td>
                </tr>
              </table>
              {$endtab}
              {$starttab2}
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td width="150">{$smarty.const._HWDVIDS_ACCESS}</td>
                  <td>{$public_private}</td>
                </tr>
                <tr>
                  <td>{$smarty.const._HWDVIDS_ACOMMENTS}</td>
                  <td>{$allow_comments}</td>
                </tr>
                <tr>
                  <td>{$smarty.const._HWDVIDS_AEMBEDDING}</td>
                  <td>{$allow_embedding}</td>
                </tr>
                <tr>
                  <td>{$smarty.const._HWDVIDS_ARATINGS}</td>
                  <td>{$allow_ratings}</td>
                </tr>
              </table>
	      <div id="passwordField" {if $row->public_private ne "password"}style="visibility:hidden;height:0;"{/if}>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		  <tr>
		    <td width="150">{$smarty.const._HWDVIDS_PASSWORD}</td>
		    <td>
		      <input name="hwdvspassword" value="" type="password" class="inputbox" size="20" maxlength="500" style="width: 200px;" />
		    </td>
		  </tr>
		</table>
              </div>
	      <div id="groupField" {if $row->public_private ne "group"}style="visibility:hidden;height:0;"{/if}>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		  <tr>
		    <td width="150" valign="top">{$smarty.const._HWDVIDS_SELECT_JACG}</td>
		    <td>
		      {$gtree_video}
		    </td>
		  </tr>
		</table>
              </div>
	      <div id="levelField" {if $row->public_private ne "level"}style="visibility:hidden;height:0;"{/if}>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		  <tr>
		    <td width="150" valign="top">{$smarty.const._HWDVIDS_SELECT_JACL}</td>
		    <td>
		      {$jacl_video}
		    </td>
		  </tr>
		</table>
              </div>
              {$endtab}
              {$endpane}
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>

{$hidden_inputs}
</form>

{if $remotevideo eq 0}
<table cellpadding="4" cellspacing="1" border="0" class="adminform">
  <tr>
    <td width="50%" style="width:50%;" valign="top">
        <h2>Re-conversion Tools</h2>


{literal}
<script language="javascript" type="text/javascript">
<!--
//Browser Support Code
function ajaxReconvertFLV(){

	document.getElementById('conversionUutput').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/images/icons/loading.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";
	
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
			document.getElementById('conversionUutput').innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=ajaxReconvertFLV&cid={/literal}{$vid}{literal}", true);
	ajaxRequest.send(null);
}

//-->
</script>
{/literal}
{literal}
<script language="javascript" type="text/javascript">
<!--
//Browser Support Code
function ajaxReconvertMP4(){

	document.getElementById('conversionUutput').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/images/icons/loading.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";
	
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
			document.getElementById('conversionUutput').innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=ajaxReconvertMP4&cid={/literal}{$vid}{literal}", true);
	ajaxRequest.send(null);
}

//-->
</script>
{/literal}
{literal}
<script language="javascript" type="text/javascript">
<!--
//Browser Support Code
function ajaxMoveMoovAtom(){

	document.getElementById('conversionUutput').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/images/icons/loading.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";
	
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
			document.getElementById('conversionUutput').innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=ajaxMoveMoovAtom&cid={/literal}{$vid}{literal}", true);
	ajaxRequest.send(null);
}

//-->
</script>
{/literal}
{literal}
<script language="javascript" type="text/javascript">
<!--
//Browser Support Code
function ajaxRecalculateDuration(){

	document.getElementById('conversionUutput').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/images/icons/loading.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";
	
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
			document.getElementById('conversionUutput').innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=ajaxRecalculateDuration&cid={/literal}{$vid}{literal}", true);
	ajaxRequest.send(null);
}

//-->
</script>
{/literal}
{literal}
<script language="javascript" type="text/javascript">
<!--
//Browser Support Code
function ajaxRegenerateImage(){

	document.getElementById('conversionUutput').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/images/icons/loading.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";
	
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
			document.getElementById('conversionUutput').innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=ajaxRegenerateImage&cid={/literal}{$vid}{literal}", true);
	ajaxRequest.send(null);
}

//-->
</script>
{/literal}
{literal}
<script language="javascript" type="text/javascript">
<!--
//Browser Support Code
function ajaxReinsertMetaFLV(){

	document.getElementById('conversionUutput').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/images/icons/loading.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";
	
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
			document.getElementById('conversionUutput').innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=ajaxReinsertMetaFLV&cid={/literal}{$vid}{literal}", true);
	ajaxRequest.send(null);
}

//-->
</script>
{/literal}


            <div onclick="ajaxReconvertFLV();" style="cursor:pointer;float:left;width:140px;text-align:center;">
                <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/cvtool_flv.png" border="0" alt="" title="" /><br />[ Re-convert FLV Video ]
            </div>

            <div onclick="ajaxReconvertMP4();" style="cursor:pointer;float:left;width:140px;text-align:center;">
                <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/cvtool_mp4.png" border="0" alt="" title="" /><br />[ Re-convert MP4 Video ]
            </div>

            <div onclick="ajaxMoveMoovAtom();" style="cursor:pointer;float:left;width:140px;text-align:center;">
            	<img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/cvtool_moovatom.png" border="0" alt="" title="" /><br />[ Move Moov Atom ]
            </div>            

            <div onclick="ajaxRecalculateDuration();" style="cursor:pointer;float:left;width:140px;text-align:center;">
                <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/cvtool_duration.png" border="0" alt="" title="" /><br />[ Re-calculate Duration ]
            </div> 

            <div onclick="ajaxRegenerateImage();" style="cursor:pointer;float:left;width:160px;text-align:center;">
                <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/cvtool_image.png" border="0" alt="" title="" /><br />[ Re-generate Thumbnail Image ]
            </div> 

            <div onclick="ajaxReinsertMetaFLV();" style="cursor:pointer;float:left;width:140px;text-align:center;">
                <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/cvtool_flvtool2.png" border="0" alt="" title="" /><br />[ Re-insert Meta Data ]
            </div> 

      	<div style="clear:both"></div>
      	<br /><br />
        <div id="conversionUutput"></div>
    </td>
  </tr>
</table>
{/if}

{if $remotevideo eq 1}
<table cellpadding="4" cellspacing="1" border="0" class="adminform">
  <tr>
    <td width="50%" style="width:50%;" valign="top">

      <h2>Change Third Party Source</h2>

	<form action="index.php" method="post" enctype="multipart/form-data">
	<table cellpadding="4" cellspacing="1" border="0">
	  <tr>
	    <td valign="top" width="150">Video Type</td>
	    <td valign="top">
	      <select name="videotype">
	        <option value="1">Third Party Video (From Youtube.com, etc)</option>
	        <option value="2">Remote Video (Static Video Url)</option>
	        <option value="3">RTMP Video (Streaming Video)</option>
	      </select>
	    </td>
	  </tr>
	  <tr>
	    <td valign="top">Video Url</td>
	    <td valign="top"><input type="text" name="embeddump" value="" size="30"></td>
	  </tr>
	  <tr>
	    <td valign="top">Update video details</td>
	    <td valign="top"><input type="checkbox" name="updatedetails"></td>
	  </tr>
	  <tr>
	    <td valign="top" colspan="2" valign="top"><input type="submit" value="Update Now"></td>
	  </tr>
	</table>
	<input type="hidden" name="id" value="{$vid}" />
	<input type="hidden" name="option" value="com_hwdvideoshare" />
	<input type="hidden" name="task" value="updatevideosource" />
	<input type="hidden" name="hidemainmenu" value="0">
	</form>
    
      </div>
    </td>
  </tr>
</table>
{/if}

<table cellpadding="4" cellspacing="1" border="1" class="adminform">
  <tr>
    <td style="width:50%;padding: 5px;" valign="top">
        <h2>Video Summary</h2>
        <h2><a href="{$link_live_video}" target="_blank">{$title}</a></h2>
        <b>{$smarty.const._HWDVIDS_CATEGORY}:</b> {$category}<br />
        <b>{$smarty.const._HWDVIDS_TAGS}:</b> {$tags}<br />
        <b>{$smarty.const._HWDVIDS_APPROVED}:</b> {$status}<br />
        <b>{$smarty.const._HWDVIDS_FLOC}:</b> {$location}<br />
        {if $print_missingfile}
        <center><h2><div style="color:#ff0000;">{$smarty.const._HWDVIDS_ALERT_MISSINGVIDFILE}</div></h2></center>
        {/if}

        <h2>Video Statistics</h2>
        {$smarty.const._HWDVIDS_DATEUPLD}: <b>{$dateuploaded}</b><br />
        {$smarty.const._HWDVIDS_LENGTH}: <b>{$duration}</b><br />
        {$smarty.const._HWDVIDS_RATING}: <b>{$rating}</b><br />
        {$smarty.const._HWDVIDS_ACCESS}: <b>{$access}</b><br />
        {$smarty.const._HWDVIDS_APPROVED}: <b>{$status}</b><br />
        {$smarty.const._HWDVIDS_VIEWS}: <b>{$views}</b><br />
        {$smarty.const._HWDVIDS_UPLOADER}: <b>{$user}</b><br />
        {$smarty.const._HWDVIDS_FAVOURED}: <b>{$favoured}</b> {$smarty.const._HWDVIDS_DETAILS_TIMES}<br />
    </td>
    <td style="width:50%;padding: 5px;" valign="top">
        <h2>Video Thumbnail</h2>
        <div style="float:right;padding:5px;">{$thumbnail}</div>
        {$thumbnail_form_code}

        <h2>Watch Video</h2>
        <center>{$videoplayer}</center>
    </td>
  </tr>
</table>

{include file='admin_footer.tpl'}
