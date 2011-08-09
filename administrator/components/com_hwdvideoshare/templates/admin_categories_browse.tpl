{* 
//////
//    @version [ Wainuiomata ]
//    @package hwdVideoShare
//    @copyright (C) 2007 - 2009 Highwood Design
//    @license http://creativecommons.org/licenses/by-nc-nd/3.0/
//////
*}

{include file='admin_header.tpl'}

<div id="editcell">
	<table class="adminlist">
		<thead>
		<tr>
			<th width="5" class="title">ID</th>
			<th width="5" class="title"><input type="checkbox" name="toggle" value="" onClick="checkAll({$totalcategories});" /></th>
			<th class="title">{$smarty.const._HWDVIDS_TITLE}</th>
			<th class="title">{$smarty.const._HWDVIDS_CVACCESS}</th>
			<th class="title">{$smarty.const._HWDVIDS_CUACCESS}</th>
			<th class="title">{$smarty.const._HWDVIDS_PUB}</th>
			<th class="title" width="75">{$smarty.const._HWDVIDS_ORDER} {$saveOrder}</th>
		</tr>
		</thead>
		<tbody>
			{foreach name=outer key=k item=data from=$list}
				{if $data->isparent}
					<tr bgcolor = "#f1f3f0">
				{else}
					<tr class = "row{$data->k}">
				{/if}
						<td>{$data->id}</td>
						<td>{$data->checked}</td>
						<td>{$data->title}</td>
						<td>{$data->view_access}</td>
						<td>{$data->upld_access}</td>
						<td><a href="javascript: void(0);" onclick="return listItemTask('cb{$data->i}','{$data->published_task}')"><img src="images/{$data->published_img}" width="12" height="12" border="0" alt="" /></a></td>
						<td class="order">
							<span>{$data->reorderup}</span>
							<span>{$data->reorderdown}</span>
							{$data->ordering}
						</td>
					</tr>
			{/foreach}
		</tbody>
		<tfoot>
		<tr><td colspan="9" align="center">{$writePagesLinks}<br />{$writePagesCounter}</td></tr>
		</tfoot>
	</table>
</div>

{include file='admin_footer.tpl'}
