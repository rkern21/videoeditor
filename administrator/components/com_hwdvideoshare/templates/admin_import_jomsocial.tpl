{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

<div style="text-align:left;padding:5px;margin:5px;border:1px solid #ccc;background:#f5f5ee;">
  <h3>Import Videos from JomSocial</h3>
  {$smarty.const._HWDVIDS_DOCS}: <a href="http://documentation.hwdmediashare.co.uk/wiki/" target="_blank">http://documentation.hwdmediashare.co.uk/wiki/</a>
  <p>This feature can be used to import videos from JomSocial (Youtube videos only).</p>
</div>

{if $jomsocialinstalled}
    <div style="border:1px solid #ccc;color:#333333;font-weight: bold;text-align:left;padding:5px;margin:5px;">JomSocial is installed on this Joomla website and you can import {$jomsocialitems} videos.</div>

    <div style="text-align:left;padding:5px;margin:5px;border:1px solid #ccc;">

      <h3>Import {$jomsocialitems} videos from JomSocial</h3>
      <form name="jomsocialimport" action="index.php" method="post">

        <table cellpadding="4" cellspacing="1" border="0">
          <tr>
            <td valign="top">Import from Jomsocial video category:</td>
            <td valign="top">{$jsvcSelect}</td>
          </tr>
          <tr>
            <td valign="top">Import into hwdVideoShare category:</td>
            <td valign="top">{$categoryselect}</td>
          </tr>
        </table>
  
        <input type="submit" value="{$smarty.const._HWDVIDS_BUTTON_IMPORT}" />
        <input type="hidden" name="limitstart" value="0" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="option" value="com_hwdvideoshare" />	
        <input type="hidden" name="task" value="jomsocialImport" />
        <input type="hidden" name="hidemainmenu" value="0">
      
      </form>

    </div>
{else}
	<div style="border:1px solid #c30;color:#333333;background:#e9ddd9;font-weight: bold;text-align:left;padding:5px;margin:5px;">JomSocial is not installed.</div>
{/if}
