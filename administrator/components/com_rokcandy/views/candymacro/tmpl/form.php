<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	// Set toolbar items for the page
	$edit		= JRequest::getVar('edit',true);
	$text = !$edit ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
	JToolBarHelper::title(   JText::_( 'ROKCANDY_MACRO' ).': <small><small>[ ' . $text.' ]</small></small>','rokcandy.png' );
	JToolBarHelper::save();
	if (!$edit)  {
		JToolBarHelper::cancel();
	} else {
		// for existing items the button is renamed `close`
		JToolBarHelper::cancel( 'cancel', 'Close' );
	}
	JToolBarHelper::help( 'screen.rokcandy.edit', true );
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.macro.value == ""){
			alert( "<?php echo JText::_( 'MACRO_REQUIRED', true ); ?>" );
		} else if (form.html.value == ""){
			alert( "<?php echo JText::_( 'HTML_REQUIRED', true ); ?>" );
		} else {
		    submitform( pressbutton );
		}
	}
</script>
<style type="text/css">
	table.paramlist td.paramlist_key {
		width: 92px;
		text-align: left;
		height: 30px;
	}
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'PUBLISHED' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="catid">
					<?php echo JText::_( 'CATEGORY' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['catid']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( 'ORDERING' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
	    </table>
	</fieldset>
</div>
<div class="col width-50">
	<fieldset class="adminform tip">
		<legend><?php echo JText::_( 'TIP' ); ?></legend>
		<p><?php echo JText::_('TIP_DETAIL'); ?></p>
	</fieldset>
</div>
<div class="clr"></div>

<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'MACRO' ); ?></legend>

		<table class="admintable">
		<tr>
			<td>
				<textarea class="text_area" cols="50" rows="15" name="macro" id="macro"><?php echo $this->rokcandymacro->macro; ?></textarea>
			</td>
			<td>
			    <span style="font-size:600%">=</span>
			</td>
		</tr>
		</table>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'HTML' ); ?></legend>

		<table class="admintable">
		<tr>
			<td>
				<textarea class="text_area" cols="50" rows="15" name="html" id="html"><?php echo $this->rokcandymacro->html; ?></textarea>
			</td>
		</tr>
		</table>
	</fieldset>
</div>

<div class="clr"></div>

	<input type="hidden" name="option" value="com_rokcandy" />
	<input type="hidden" name="cid[]" value="<?php echo $this->rokcandymacro->id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>