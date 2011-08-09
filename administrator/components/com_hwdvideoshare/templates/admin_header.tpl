{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{literal}
	<script language="javascript" type="text/javascript">
	<!--
	//Browser Support Code
	function ajaxClearTemplateCache(){

		document.getElementById('ajaxmresponse').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/assets/images/processing.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";

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
				document.getElementById('ajaxmresponse').style.padding = "2px 0 2px 0";
				document.getElementById('ajaxmresponse').innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=cleartemplatecache", true);
		ajaxRequest.send(null);
	}

	//-->
	</script>
	<script language="javascript" type="text/javascript">
	<!--
	//Browser Support Code
	function ajaxClearPlaylistCache(){

		document.getElementById('ajaxmresponse').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/assets/images/processing.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";

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
				document.getElementById('ajaxmresponse').style.padding = "2px 0 2px 0";
				document.getElementById('ajaxmresponse').innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=clearplaylistcache", true);
		ajaxRequest.send(null);
	}

	//-->
	</script>
	<script language="javascript" type="text/javascript">
	<!--
	//Browser Support Code
	function ajaxGenerateMaintenace(){

		document.getElementById('ajaxmresponse').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/assets/images/processing.gif\" border=\"0\" alt=\"\" title=\"\"> Loading...";
		
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
			if (ajaxRequest.readyState == 4){
				document.getElementById('ajaxmresponse').style.padding = "2px 0 2px 0";
				document.getElementById('ajaxmresponse').innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/index.php?option=com_hwdvideoshare&maintenance=generateplaylists", true);
		ajaxRequest.send(null);
	}

	//-->
	</script>	
{/literal}
<form action="index.php" method="post" name="adminForm" id="adminForm">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform" style="margin:10px 0;">
    <tr>
      <td style="background-color: #202626; width:100%; text-align: right; vertical-align: top;">
        <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/logo.png" height="47" width="250" alt="Logo" style="float: left;" />
        <font style="color: #fffffe; font-size: 200%; font-weight: bold;">{$header_title}</font>
      </td>
    </tr>
    {if $print_search}
    <tr>
      <td style="width:100%; text-align: right;">{$search}</td>
    </tr>
    {/if}
  </table>

  
  {if !$block_maintenance}
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
    <tr>
      <td>
        {if $hwdIntegrate}
        <div style="float:left;padding-right:5px;">
          <img src="components/com_hwdphotoshare/assets/images/menu/hwdphotoshare.png" border="0" alt="" title="" style="padding:1px 5px;vertical-align:bottom;" /><a href="index.php?option=com_hwdphotoshare"><b>hwdPhotoShare</b></a>
          <img src="components/com_hwdvideoshare/assets/images/menu/categories.png" border="0" alt="" title="" style="padding:1px 5px;vertical-align:bottom;" /><a href="index.php?option=com_hwdphotoshare&task=integrationCategorySync"><b>Category Sync</b></a>
        </div>    
        {/if}
        <div style="float:right;">
          <span style="cursor:pointer;" onclick="ajaxClearTemplateCache();"><img src="components/com_hwdvideoshare/assets/images/icons/bin.png" border="0" alt="" title="" style="padding:1px 5px;vertical-align:bottom;" /><b>{$smarty.const._HWDVIDS_CTC}</b></span>
          <span style="cursor:pointer;" onclick="ajaxClearPlaylistCache();"><img src="components/com_hwdvideoshare/assets/images/icons/bin.png" border="0" alt="" title="" style="padding:1px 5px;vertical-align:bottom;" /><b>{$smarty.const._HWDVIDS_CPC}</b></span>
          <span style="cursor:pointer;" onclick="ajaxGenerateMaintenace();"><img src="components/com_hwdvideoshare/assets/images/menu/maintenance.png" border="0" alt="" title="" style="padding:1px 5px;vertical-align:bottom;" /><b>{$smarty.const._HWDVIDS_RGP}</b></span>
        </div>      
        <div style="clear:both;float:right;"><div id="ajaxmresponse"></div></div> 
      </td>
    </tr>
  </table>
  {/if}
  
