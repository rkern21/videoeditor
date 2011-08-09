{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}
		
<div>
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
      <tr>
        <td align="left">
        
          <h2>{$smarty.const._HWDVIDS_TITLE_FINSET}</h2>

          <input type="checkbox" name="cats" value="1" checked="checked">{$smarty.const._HWDVIDS_INS_SAMP_CATS}<br />
          <input type="checkbox" name="youtube" value="1" checked="checked">{$smarty.const._HWDVIDS_INS_YT}<br />
          <input type="checkbox" name="google" value="1" checked="checked">{$smarty.const._HWDVIDS_INS_GV}<br /><br />
          
          <p>{$smarty.const._HWDVIDS_JW_LIC}</p>
          
          <select name="jwflv_license">
            <option value="0">{$smarty.const._HWDVIDS_JW_AGREE}</option>
            <option value="1">{$smarty.const._HWDVIDS_JW_DECLINE}</option>
            <option value="2">{$smarty.const._HWDVIDS_JW_EXISTING}</option>
            <option value="3">{$smarty.const._HWDVIDS_JW_SKIP}</option>
          </select>
          <br /><br />
          
          <input type="submit" value="{$smarty.const._HWDVIDS_BUTTON_FINSET}">
        
        </td>
      </tr>
  </table>
</div>

{include file='admin_footer.tpl'}

