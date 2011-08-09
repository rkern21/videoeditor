{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{if $print_sharing}
  <table width="100%" cellpadding="2" cellspacing="2" border="0">
  {if $usershare1}
    <tr>
      <td width="150">{$smarty.const._HWDVIDS_ACCESS}</td>
      <td>
        <select name="public_private">
          <option value="public"{$so1p}>{$smarty.const._HWDVIDS_SELECT_PUBLIC}</option>
          <option value="registered"{$so1r}>{$smarty.const._HWDVIDS_SELECT_REG}</option>
        </select>
      </td>
    </tr>
  {else}
    <tr>
      <td colspan="2"><input type="hidden" name="public_private" value="{$so1value}"></td>
    </tr>
  {/if}
  {if $usershare2}
    <tr>
      <td width="150">{$smarty.const._HWDVIDS_ACOMMENTS}</td>
      <td>
        <select name="allow_comments">
          <option value="1"{$so21}>{$smarty.const._HWDVIDS_SELECT_ALLOWCOMMS}</option>
          <option value="0"{$so20}>{$smarty.const._HWDVIDS_SELECT_DONTALLOWCOMMS}</option>
        </select>
      </td>
    </tr>
  {else}
    <tr>
      <td colspan="2"><input type="hidden" name="allow_comments" value="{$so2value}"></td>
    </tr>
  {/if}
  {if $usershare3}
    <tr>
      <td width="150">{$smarty.const._HWDVIDS_AEMBEDDING}</td>
      <td>
        <select name="allow_embedding">
          <option value="1"{$so31}>{$smarty.const._HWDVIDS_SELECT_ALLOWEMB}</option>
          <option value="0"{$so30}>{$smarty.const._HWDVIDS_SELECT_DONTALLOWEMB}</option>
        </select>
      </td>
    </tr>
  {else}
    <tr>
      <td colspan="2"><input type="hidden" name="allow_embedding" value="{$so3value}"></td>
    </tr>
  {/if}
  {if $usershare4}
    <tr>
      <td width="150">{$smarty.const._HWDVIDS_ARATINGS}</td>
      <td>
        <select name="allow_ratings">
          <option value="1"{$so31}>{$smarty.const._HWDVIDS_SELECT_ALLOWRATE}</option>
          <option value="0"{$so30}>{$smarty.const._HWDVIDS_SELECT_DONTALLOWRATE}</option>
        </select>
      </td>
    </tr>
  {else}
    <tr>
      <td colspan="2"><input type="hidden" name="allow_ratings" value="{$so4value}"></td>
    </tr>
  {/if}
  </table>
{else}
  <input type="hidden" name="public_private" value="{$so1value}" />
  <input type="hidden" name="allow_comments" value="{$so2value}" />
  <input type="hidden" name="allow_embedding" value="{$so3value}" />
  <input type="hidden" name="allow_ratings" value="{$so4value}" />
{/if}