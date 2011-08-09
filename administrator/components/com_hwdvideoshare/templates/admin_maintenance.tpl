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
	<script type="text/javascript">
	function confirm_thumb()
	{
	var r=confirm("You are about to re-generate your thumbnail images.\nYou must run the converter tool after clikcing OK.\nAre you sure you wish to continue?");
	if (r==true)
	  {
	  window.location = "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=regeneratethumbnails"
	  return false;
	  }
	else
	  {
	  return false;
	  }
	}
	function confirm_duration()
	{
	var r=confirm("You are about to re-calculate your video durations.\nYou must run the converter tool after clikcing OK.\nAre you sure you wish to continue?");
	if (r==true)
	  {
	  window.location = "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=recalculatedurations"
	  return false;
	  }
	else
	  {
	  return false;
	  }
	}
	</script>
	<script language="javascript" type="text/javascript">
	<!--
	function ajaxArchiveLogs ( )
	{
		document.getElementById('ajax_log_response').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/assets/images/processing.gif\" border=\"0\" alt=\"\" title=\"\"> Working...";
		document.getElementById('archiveLogBox').innerHTML = "";
		setInterval( "ajaxArchiveLogsRun()", 60000 );
	}

	//Browser Support Code
	function ajaxArchiveLogsRun(){

		document.getElementById('ajax_log_response').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/assets/images/processing.gif\" border=\"0\" alt=\"\" title=\"\"> Working...";

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
				document.getElementById('ajax_log_response').style.padding = "2px 0 2px 0";
				document.getElementById('ajax_log_response').innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=ajax_ArchiveLogs", true);
		ajaxRequest.send(null);
	}

	//Browser Support Code
	function ajaxMaintenanceTask(task){

		document.getElementById('maintenanceResponse').innerHTML = "<img src=\"{/literal}{$mosConfig_live_site}{literal}/components/com_hwdvideoshare/assets/images/processing.gif\" border=\"0\" alt=\"\" title=\"\"> Working...";

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
				document.getElementById('maintenanceResponse').innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "{/literal}{$mosConfig_live_site}{literal}/administrator/index.php?option=com_hwdvideoshare&task=ajax_"+task, true);
		ajaxRequest.send(null);
	}
	
	//-->
	</script>
{/literal}

<table cellpadding="0" cellspacing="0" border="0" width="100%">
  <tr>
    <td align="left" valign="top" width="50%">
      
      <div style="border: 1px solid #ccc; padding: 5px; margin: 0 5px 5px 0;">
      
          <h2>{$smarty.const._HWDVIDS_TITLE_DELETEOLDVIDS}</h2>
          <p>{$smarty.const._HWDVIDS_INFO_PERMDEL1}</p>
          <p>{$smarty.const._HWDVIDS_INFO_PERMDEL2} <b>{$total}</b> {$smarty.const._HWDVIDS_INFO_PERMDEL3}</p>
          <select name="run_permdel">
            <option value="1" selected="selected">{$smarty.const._HWDVIDS_MAIN_RN}</option>
            <option value="0">{$smarty.const._HWDVIDS_MAIN_DRN}</option>
          </select>
          &nbsp;<img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/icons/run.png" border="0" alt="{$smarty.const._HWDVIDS_MAIN_RN}" style="cursor:pointer;" onclick="submitform('runmaintenance');" />

      </div>
      
    </td>
    <td align="left" valign="top">
      
      <div style="border: 1px solid #ccc; padding: 5px; margin: 0 0 5px 0;">
      
          <h2>{$smarty.const._HWDVIDS_MAIN_FDE}</h2>
          <p><b>{$smarty.const._HWDVIDS_MAIN_LR} {$fixerror_cache}</b></p>
          <select name="run_fixerrors">
            <option value="2">{$smarty.const._HWDVIDS_MAIN_RIR}</option>
            <option value="1" selected="selected">{$smarty.const._HWDVIDS_MAIN_RN}</option>
            <option value="0">{$smarty.const._HWDVIDS_MAIN_DRN}</option>
          </select>
          &nbsp;<img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/icons/run.png" border="0" alt="{$smarty.const._HWDVIDS_MAIN_RN}" style="cursor:pointer;" onclick="submitform('runmaintenance');" />

      </div>
      
    </td>
    <td align="left" valign="top">
  </tr>
  <tr>
    <td align="left" valign="top" width="50%">

      <div style="border: 1px solid #ccc; padding: 5px; margin: 0 5px 5px 0;">
      
          <h2>{$smarty.const._HWDVIDS_MAIN_RDS}</h2>
          <p><b>{$smarty.const._HWDVIDS_MAIN_LR} {$recount_cache}</b></p>
          <select name="run_recount">
            <option value="2">{$smarty.const._HWDVIDS_MAIN_RIR}</option>
            <option value="1" selected="selected">{$smarty.const._HWDVIDS_MAIN_RN}</option>
            <option value="0">{$smarty.const._HWDVIDS_MAIN_DRN}</option>
          </select>  
          &nbsp;<img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/icons/run.png" border="0" alt="{$smarty.const._HWDVIDS_MAIN_RN}" style="cursor:pointer;" onclick="submitform('runmaintenance');" />
          
      </div>

    </td>
    <td align="left" valign="top">
      
      <div style="border: 1px solid #ccc; padding: 5px; margin: 0 0 5px 0;">
      
          <span id="archiveLogBox" style="cursor:pointer;float:right;" onclick="ajaxArchiveLogs()"><img src="components/com_hwdvideoshare/assets/images/menu/maintenance.png" border="0" alt="" title="" style="padding:1px 5px;vertical-align:bottom;" /><b>{$smarty.const._HWDVIDS_MAIN_AAL}</b></span>
          <h2>{$smarty.const._HWDVIDS_MAIN_AAL}</h2>
          <p><b>{$smarty.const._HWDVIDS_MAIN_LR} {$archive_cache}</b></p>
          <div id="ajax_log_response"></div>
          
      </div>
      
    </td>
  </tr>
  <tr>
    <td align="left" valign="top" width="50%">

      <div style="border: 1px solid #ccc; padding: 5px; margin: 0 5px 5px 0;">
        <h2>Other Maintenance Tools</h2>
        <div style="cursor:pointer;" onclick="ajaxMaintenanceTask('warphdsync')"><img src="components/com_hwdphotoshare/assets/images/menu/maintenance.png" border="0" alt="" title="" style="padding:1px 5px;vertical-align:bottom;" /><b>Synchronise WarpHD</b></div>
      </div>

    </td>
    <td align="left" valign="top">
      
      <div style="border: 1px solid #ccc; padding: 5px; margin: 0 0 5px 0;">
         <div id="maintenanceResponse"></div>
      </div>
      
    </td>
  </tr>
  <tr>
    <td align="left" valign="top" width="50%">

      <div style="border: 1px solid #ccc; padding: 5px; margin: 0 5px 5px 0;">
      
          <h2>{$smarty.const._HWDVIDS_REGENTHUMB}</h2>
          <div style="padding:5px;">
            <a href="#">
              <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/go.png" border="0" alt="{$smarty.const._HWDVIDS_MAIN_RN}" onclick="confirm_thumb();"  />
            </a>
          </div>   
          
      </div>

    </td>
    <td align="left" valign="top">

      <div style="border: 1px solid #ccc; padding: 5px; margin: 0 5px 5px 0;">
      
          <h2>{$smarty.const._HWDVIDS_RECALDUR}</h2>
          <div style="padding:5px;">
            <a href="#">
              <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/go.png" border="0" alt="{$smarty.const._HWDVIDS_MAIN_RN}" onclick="confirm_duration();" />
            </a>
          </div>    
          
      </div>
      
    </td>
  </tr>  
</table>

{include file='admin_footer.tpl'}
