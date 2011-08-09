<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

JToolBarHelper::title('<img src="'. JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/themes/easymode/i/logo-breezingforms.png'.'" align="top"/>');

class HTML_facileFormsPiece
{
	function edit($option, $pkg, &$row, &$typelist)
	{
		global $ff_mossite, $ff_admsite, $ff_config;
		$action = $row->id ? BFText::_('COM_BREEZINGFORMS_PIECES_EDITPIECE') : BFText::_('COM_BREEZINGFORMS_PIECES_ADDPIECE');
?>
		<script type="text/javascript" src="<?php echo $ff_admsite; ?>/admin/areautils.js"></script>
		<script type="text/javascript">
		<!--
		function checkIdentifier(value)
		{
			var invalidChars = /\W/;
			var error = '';
			if (value == '')
				error += "<?php echo BFText::_('COM_BREEZINGFORMS_PIECES_ENTERNAME'); ?>\n";
			else
				if (invalidChars.test(value))
					error += "<?php echo BFText::_('COM_BREEZINGFORMS_PIECES_ENTERIDENT'); ?>\n";
			return error;
		} // checkIdentifier

		function submitbutton(pressbutton)
		{
			var form = document.adminForm;
			var error = '';
			if (pressbutton != 'cancel') {
				error += checkIdentifier(form.name.value, 'name');
				if (form.title.value == '') error += "<?php echo BFText::_('COM_BREEZINGFORMS_PIECES_ENTTITLE'); ?>\n";
			} // if
			if (error != '')
				alert(error);
			else
				submitform(pressbutton);
		} // submitbutton

		onload = function()
		{
			document.adminForm.title.focus();
		} // onload
		//-->
		</script>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script type="text/javascript" src="<?php echo $ff_mossite; ?>/components/com_breezingforms/libraries/js/overlib_mini.js"></script>
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="adminForm">
		<table cellpadding="4" cellspacing="1" border="0" class="adminform" style="width:100px;">
			<tr><th colspan="4" class="title">BreezingForms - <?php echo $action; ?></th></tr>
			<tr>
				<td></td>
				<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_TITLE'); ?>:</td>
				<td nowrap>
					<input type="text" size="50" maxlength="50" name="title" value="<?php echo $row->title; ?>" class="inputbox"/>
<?php
					echo bf_ToolTip(BFText::_('COM_BREEZINGFORMS_PIECES_TIPTITLE'));
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_PUBLISHED'); ?>:</td>
				<td nowrap><?php echo JHTML::_('select.booleanlist', "published", "", $row->published); ?></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_PACKAGE'); ?>:</td>
				<td nowrap>
					<input type="text" size="30" maxlength="30" id="package" name="package" value="<?php echo $row->package; ?>" class="inputbox"/>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_NAME'); ?>:</td>
				<td nowrap>
					<input type="text" size="30" maxlength="30" id="name" name="name" value="<?php echo $row->name; ?>" class="inputbox"/>
<?php
					echo bf_ToolTip(BFText::_('COM_BREEZINGFORMS_PIECES_TIPNAME'));
?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_TYPE'); ?>:</td>
				<td nowrap>
					<select id="type" name="type" class="inputbox" size="1">
<?php
					for ($t = 0; $t < count($typelist); $t++) {
						$tl = $typelist[$t];
						$selected = '';
						if ($tl[0] == $row->type) $selected = ' selected';
						echo '<option value="'.$tl[0].'"'.$selected.'>'.$tl[1].'</option>';
					} // for
?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap colspan="2">
					<?php echo BFText::_('COM_BREEZINGFORMS_PIECES_DESCRIPTION'); ?>:
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
					<br/>
					<textarea wrap="off" name="description" style="width:750px;" rows="<?php echo $ff_config->areasmall; ?>" class="inputbox"><?php echo $row->description; ?></textarea>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap colspan="2">
					<?php echo BFText::_('COM_BREEZINGFORMS_PIECES_CODE'); ?>:
					<br/>
					<textarea name="code" style="width:100%;height:500px;" wrap="off"><?php echo htmlspecialchars($row->code, ENT_QUOTES); ?></textarea>
					
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td nowrap colspan="2" style="text-align:right">
					<input onclick="submitbutton('save');" type="submit" value="<?php echo htmlentities(BFText::_('COM_BREEZINGFORMS_TOOLBAR_SAVE'), ENT_QUOTES, 'UTF-8'); ?>"/>
					&nbsp;&nbsp;
                                        <input onclick="submitbutton('cancel');" type="submit" value="<?php echo htmlentities(BFText::_('COM_BREEZINGFORMS_TOOLBAR_CANCEL'), ENT_QUOTES, 'UTF-8'); ?>"/>
				</td>
				<td></td>
			</tr>
		</table>
		<input type="hidden" name="pkg" value="<?php echo $pkg; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="managepieces" />
		</form>
<?php
	} // edit

	function typeName($type)
	{
		switch ($type) {
			case 'Untyped':         return BFText::_('COM_BREEZINGFORMS_PIECES_UNTYPED');
			case 'Before Form':     return BFText::_('COM_BREEZINGFORMS_PIECES_BEFOREFORM');
			case 'After Form':      return BFText::_('COM_BREEZINGFORMS_PIECES_AFTERFORM');
			case 'Begin Submit':    return BFText::_('COM_BREEZINGFORMS_PIECES_BEGINSUBMIT');
			case 'End Submit':      return BFText::_('COM_BREEZINGFORMS_PIECES_ENDSUBMIT');
			default:;
		} // switch
		return '???';
	} // typeName

	function listitems( $option, &$rows, &$pkglist )
	{
		global $ff_config ,$ff_version;
?>
		<script type="text/javascript">
			<!--
			function submitbutton(pressbutton)
			{
				var form = document.adminForm;
				switch (pressbutton) {
					case 'copy':
					case 'publish':
					case 'unpublish':
					case 'remove':
						if (form.boxchecked.value==0) {
							alert("<?php echo BFText::_('COM_BREEZINGFORMS_PIECES_SELPIECESFIRST'); ?>");
							return;
						} // if
						break;
					default:
						break;
				} // switch
				if (pressbutton == 'remove')
					if (!confirm("<?php echo BFText::_('COM_BREEZINGFORMS_PIECES_ASKDELETE'); ?>")) return;
				if (pressbutton == '' && form.pkgsel.value == '')
					form.pkg.value = '- blank -';
				else
					form.pkg.value = form.pkgsel.value;
				submitform(pressbutton);
			} // submitbutton

			function listItemTask( id, task )
			{
				var f = document.adminForm;
				cb = eval( 'f.' + id );
				if (cb) {
					for (i = 0; true; i++) {
						cbx = eval('f.cb'+i);
						if (!cbx) break;
						cbx.checked = false;
					} // for
					cb.checked = true;
					f.boxchecked.value = 1;
					submitbutton(task);
				}
				return false;
			} // listItemTask
			//-->
		</script>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="1" border="0">
			<tr>
				<td width="50%" nowrap>
					<table class="adminheading">
						<tr><th nowrap class="sections">BreezingForms <?php echo $ff_version; ?><br/><span class="componentheading"><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_MANAGEPIECES'); ?></span></th></tr>
					</table>
				</td>
				<td nowrap>
					<?php echo BFText::_('COM_BREEZINGFORMS_PIECES_PACKAGE'); ?>:
					<select id="pkgsel" name="pkgsel" class="inputbox" size="1" onchange="submitbutton('');">
<?php
					if (count($pkglist)) foreach ($pkglist as $pkg) {
						$selected = '';
						if ($pkg[0]) $selected = ' selected';
						echo '<option value="'.$pkg[1].'"'.$selected.'>'.$pkg[1].'&nbsp;</option>';
					} // foreach
?>
					</select>
				</td>
				<td align="right" width="50%" nowrap>
<?php
		JToolBarHelper::custom('new',       'new.png',       'new_f2.png',       BFText::_('COM_BREEZINGFORMS_TOOLBAR_NEW'),       false);
		JToolBarHelper::custom('copy',      'copy.png',      'copy_f2.png',      BFText::_('COM_BREEZINGFORMS_TOOLBAR_COPY'),      false);
		JToolBarHelper::custom('publish',   'publish.png',   'publish_f2.png',   BFText::_('COM_BREEZINGFORMS_TOOLBAR_PUBLISH'),   false);
		JToolBarHelper::custom('unpublish', 'unpublish.png', 'unpublish_f2.png', BFText::_('COM_BREEZINGFORMS_TOOLBAR_UNPUBLISH'), false);
		JToolBarHelper::custom('remove',    'delete.png',    'delete_f2.png',    BFText::_('COM_BREEZINGFORMS_TOOLBAR_DELETE'),    false);
?>
				</td>
			</tr>
		</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th nowrap align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
				<th nowrap align="left"><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_TITLE'); ?></th>
				<th nowrap align="left"><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_NAME'); ?></th>
				<th nowrap align="left"><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_TYPE'); ?></th>
				<th nowrap align="right">ID</th>
				<th nowrap align="center"><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_PUBLISHED'); ?></th>
				<th align="left" width="100%"><?php echo BFText::_('COM_BREEZINGFORMS_PIECES_DESCRIPTION'); ?></th>
			</tr>
<?php
			$k = 0;
			for($i=0; $i < count($rows); $i++) {
				$row = $rows[$i];
				$desc = $row->description;
				if (strlen($desc) > $ff_config->limitdesc) $desc = substr($desc,0,$ff_config->limitdesc).'...';
?>
				<tr class="row<?php echo $k; ?>">
					<td nowrap valign="top" align="center"><input type="checkbox" id="cb<?php echo $i; ?>" name="ids[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
					<td nowrap valign="top" align="left"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','edit')"><?php echo $row->title; ?></a></td>
					<td nowrap valign="top" align="left"><?php echo $row->name; ?></td>
					<td nowrap valign="top" align="left"><?php echo HTML_facileFormsPiece::typeName($row->type); ?></td>
					<td nowrap valign="top" align="right"><?php echo $row->id; ?></td>
					<td nowrap valign="top" align="center"><?php
					if ($row->published == "1") {
						?><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','unpublish')"><img src="components/com_breezingforms/images/icons/publish_g.png" alt="+" border="0" /></a><?php
					} else {
						?><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','publish')"><img src="components/com_breezingforms/images/icons/publish_x.png" alt="-" border="0" /></a><?php
					} // if
					?></td>
					<td valign="top" align="left"><?php echo htmlspecialchars($desc, ENT_QUOTES); ?></td>
				</tr>
<?php
				$k = 1 - $k;
			} // for
?>
		</table>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="managepieces" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="pkg" value="" />
		</form>
<?php
	} // listitems

} // class HTML_facileFormsPiece
?>