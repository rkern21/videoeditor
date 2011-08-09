{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

<html>
  <head>
    <link type="text/css" rel="stylesheet" href="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/css/converter.css" />
  </head>
  <body>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
      <tr align="left">
        <td colspan="2">
          <div style="width:100%;text-align:center;">
            <h2>{$smarty.const._HWDVIDS_STCV}</h2>
            <a href="{$mosConfig_live_site}/components/com_hwdvideoshare/converters/converter_internal.php">{$smarty.const._HWDVIDS_CONVERSIONSTART}</a>
            <div style="padding:5px;">
              <a href="{$mosConfig_live_site}/components/com_hwdvideoshare/converters/converter.php?internal=1">         
                <img src="{$mosConfig_live_site}/administrator/components/com_hwdvideoshare/assets/images/go.png" border="0" alt="" />
              </a>
            </div>
          </div>
        </td>
      </tr>
      <tr align="left">
        <td width="50%" valign="top">
          <div style="padding:5px;">
            <table cellpadding="3" cellspacing="3" border="0" width="100%" class="adminform">
              <tr>
                <td align="left">
           	  <h2>{$smarty.const._HWDVIDS_CVST}</h2>
                  <b>{$smarty.const._HWDVIDS_INFO_QFCON}: {$total1}</b><br />
                  <b>{$smarty.const._HWDVIDS_INFO_QFTUM}: {$total2}</b><br />
                  <b>{$smarty.const._HWDVIDS_INFO_QFSWF}: {$total4}</b><br />
                  <b>{$smarty.const._HWDVIDS_INFO_QFMP4}: {$total5}</b><br />
                  <b>{$smarty.const._HWDVIDS_INFO_QFTRG}: {$total6}</b> [<a href="index.php?option=com_hwdvideoshare&task=cancelThumbnailRegeneration" target="_top">CANCEL</a>]<br />
                  <b>{$smarty.const._HWDVIDS_INFO_QFDRC}: {$total7}</b> [<a href="index.php?option=com_hwdvideoshare&task=cancelDurationRecalculation" target="_top">CANCEL</a>]<br />
                  <b>{$smarty.const._HWDVIDS_INFO_QFING}: {$total3}</b><br />
                </td>
              </tr>
            </table>
          </div>
        </td>
        <td width="50%" valign="top">
          <div style="padding:5px;">
            <table cellpadding="3" cellspacing="3" border="0" width="100%" class="adminform">
              <tr>
                <td align="left" width="50%">
                  <h2>{$smarty.const._HWDVIDS_TT_01H}</h2><br />
                  {$smarty.const._HWDVIDS_TT_01B}<br /><br />
                  <form action="index.php" method="post">
                    <input type="submit" class="inputbox" value="{$smarty.const._HWDVIDS_BUTTON_RESETFCONV}">&#160;&#160;
                    <input type="hidden" name="option" value="com_hwdvideoshare" />
                    <input type="hidden" name="task" value="resetFailedConversions" />
                  </form>
                </td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </body>
</html>