{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}
<script type="text/javascript" src="{$mosConfig_live_site}/includes/js/overlib_mini.js"></script>

<div id="editcell">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
    <tr>
      <td align="left" colspan="2"><h2>Edit {$row->nameA}</h2></td>
    </tr>
    <tr valign="top">
      <td width="60%" valign="top">
        <table class="adminform">
          <tr>
            <th colspan="2">Plugin Common Settings</th>
          </tr>
          <tr>
            <td colspan="2"><h2>{$row->name}</h2></td>
          </tr>
          <tr>
            <td colspan="2">{$row->description}</td>
          </tr>
          <tr>
            <td valign="top" width="100">Published:</td>
            <td>{$published}</td>
          </tr>
        </table>
      </td>
      <td width="40%">
        <table class="adminform" cellspacing="0" cellpadding="0" width="100%">
          <tr>
            <th colspan="2">Parameters</th>
          </tr>
          <tr>
            <td>{$params}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>

{include file='admin_footer.tpl'}
