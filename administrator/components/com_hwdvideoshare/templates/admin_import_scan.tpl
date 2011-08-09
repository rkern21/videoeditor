{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

<div style="text-align:left;padding:5px;margin:5px;border:1px solid #ccc;background:#f5f5ee;">
  <h3>Scan a Directory on Your Server</h3>
  Documentation: <a href="http://documentation.hwdmediashare.co.uk/wiki/Import_Videos_by_Scanning_Server_Directories" target="_blank">http://documentation.hwdmediashare.co.uk/wiki/Import_Videos_by_Scanning_Server_Directories</a>
  <p>This feature can be used to scan a directory on your server and import videos to hwdVideoShare.</p>
</div>

{literal}
<script language="javascript" type="text/javascript">
<!-- 
//Browser Support Code
function ajaxFunction(){

	var directory = document.scandirectory.directory.value;
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
			document.getElementById('ajaxresponse').style.border = "1px solid #c30";
			document.getElementById('ajaxresponse').style.overflow = "hidden";
			document.getElementById('ajaxresponse').style.padding = "3px";
			document.getElementById('ajaxresponse').style.margin = "5px 0 3px 0";
			document.getElementById('ajaxresponse').innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=scandirectory&directory="+directory, true);
	ajaxRequest.send(null); 
}

//-->
</script>
{/literal}
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

<div style="text-align:left;padding:5px;margin:5px;border:1px solid #ccc;">
  <form action="index.php" method="post" name="scandirectory">
    <h3>Scan Directory</h3>
    <input type="text" name="directory" onKeyUp="ajaxFunction();" value="{$mosConfig_absolute_path}" size="120">
    <input type="submit" value="Import Videos">
    <div id="ajaxresponse"></div>
    
    <h3>Assign User</h3>
    <div id="ajaxChangeUserResponse">{$user} <span onclick="ajaxChangeUser();" style="cursor:pointer;">[{$smarty.const._HWDVIDS_CHANGEUSER}]</span></div>
    
    <h3>Default Video Information</h3>
    <p>The videos that are found in this directory will be copied to the hwdVideoShare video folder and added to the database. The following information will be used to label the videos.</p>
    <table cellpadding="0" cellspacing="0" border="0">
      <tr><td align="left" valign="top">{include file='admin_upload_form.tpl'}</td></tr>
    </table> 
  <input type="hidden" name="option" value="com_hwdvideoshare" />
  <input type="hidden" name="task" value="importdirectory" />
  <input type="hidden" name="hidemainmenu" value="0">
  </form>
</div>
