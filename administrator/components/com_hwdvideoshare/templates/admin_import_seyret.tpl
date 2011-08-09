{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

<div style="text-align:left;padding:5px;margin:5px;border:1px solid #ccc;background:#f5f5ee;">
  <h3>{$smarty.const._HWDVIDS_IMPT_SEYRET_TITLE}</h3>
  {$smarty.const._HWDVIDS_DOCS}: <a href="http://documentation.hwdmediashare.co.uk/wiki/Import_Videos_from_SQL_Backup_File" target="_blank">http://documentation.hwdmediashare.co.uk/wiki/Import_Videos_from_SQL_Backup_File</a>
  <p>{$smarty.const._HWDVIDS_IMPT_SEYRET_DESC}</p>
</div>


{if $seyretinstalled}
    <div style="border:1px solid #ccc;color:#333333;font-weight: bold;text-align:left;padding:5px;margin:5px;">Seyret is installed on this Joomla website and you can import {$seyretitems} videos.</div>

    <div style="text-align:left;padding:5px;margin:5px;border:1px solid #ccc;">

      <h3>Import {$seyretitems} videos from Seyret</h3>
      <form name="seyretimport" action="index.php" method="post">

        <table cellpadding="4" cellspacing="1" border="0">
          <tr>
            <td valign="top">Import from Seyret category:</td>
            <td valign="top">{$seyretcatsel}</td>
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
        <input type="hidden" name="task" value="seyretImport" />
        <input type="hidden" name="hidemainmenu" value="0">
      
      </form>

      <h3>Remove all videos imported from Seyret<h3>
      <form name="seyretimport" action="index.php" method="post">
 
        <input type="submit" value="{$smarty.const._HWDVIDS_BUTTON_UNDOIMPORT}"/>
        <input type="hidden" name="limitstart" value="0" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="option" value="com_hwdvideoshare" />
        <input type="hidden" name="task" value="seyretImportUndo" />
        <input type="hidden" name="hidemainmenu" value="0">
      
      </form>

    </div>

{else}
    <div style="border:1px solid #c30;color:#333333;background:#e9ddd9;font-weight: bold;text-align:left;padding:5px;margin:5px;">{$smarty.const._HWDVIDS_IMPT_SEYRET_WARN}</div>
{/if}
