{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}
{$hidden_inputs}
</form>

<table cellpadding="4" cellspacing="1" border="0" class="adminform">
  <tr>
    <td colspan="2">
      <div style="margin:5px;border:solid 1px #333;padding:5px;width:100%:">
        <h1>This video is currently being converted</h1>
        <p>More information will become available when the thumbnail image has been created successfully.</p>
        <p>If this video has been converting for more than 30 minutes it is likely that the conversion process has failed. You can reset the conversion by selecting the original status below.</p>

	<form action="index.php" method="post" enctype="multipart/form-data">
	  <select name="new_status">
	    <option value="queuedforconversion">Queued for conversion</option>
	    <option value="queuedforthumbnail">Queued for thumbnail</option>
	    <option value="queuedforswf">Queued for SWF processing</option>
	    <option value="queuedformp4">Queued for MP4 processingn</option>
	    <option value="re-calculate_duration">Re-calculate duration</option>
	    <option value="re-generate_thumb">Re-generate thumbnail</option>

	  </select>    
	  <input type="submit" value="Update">
	  <input type="hidden" name="option" value="com_hwdvideoshare" />
	  <input type="hidden" name="task" value="resetFailedConversions" />
	  <input type="hidden" name="video_id" value="{$vid}" />
	  <input type="hidden" name="hidemainmenu" value="0">
	</form>

      </div>
    </td>
  </tr>
</table>

<form action="index.php" method="post" name="adminForm">
{include file='admin_footer.tpl'}
