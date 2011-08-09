<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<script type="text/javascript">
var insertAtCursor = function(el, value) {
	if (document.selection) {
		el.focus();
		var sel = document.selection.createRange();
		sel.text = value;
	}
	else if (el.selectionStart || el.selectionStart == "0") {
		var startPos = el.selectionStart;
		var endPos = el.selectionEnd;
		el.value = el.value.substring(0, startPos) + value + el.value.substring(endPos, el.value.length);
	} else {
		el.value += value;
	}
	return el;
};

window.addEvent('domready', function() {
	var parent = $(window.parent.document.body);
	var macros = $('editcell').getElements('tbody tr').getFirst(), text = parent.getElement('#<?php echo JRequest::getVar('textarea', 'text'); ?>'), sbox = parent.getElement('#sbox-window');
	if (macros.length) {
		macros.addClass('macro-highlight').setStyle('cursor', 'pointer');
		macros.each(function(macro) {
			macro.addEvent('click', function() {
				var value = this.innerHTML.trim();
				if (text.getStyle('display') == 'none') window.parent.jInsertEditorText(value, '<?php echo JRequest::getVar('textarea', 'text'); ?>');
				else insertAtCursor(text, value);
				sbox.close();
			});
		});
	}
});
</script>
<form action="index.php" method="post" name="adminForm">
<table>
<tr>
	<td align="left" width="100%">
		<?php echo JText::_( 'Filter' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
	</td>
	<td nowrap="nowrap">
		<?php
			echo $this->lists['catid'];
		?>
	</td>
</tr>
</table>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'Macro', 'a.macro', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'HTML', 'a.html', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="15%"  class="title">
				<?php echo JHTML::_('grid.sort',  'Category', 'category', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="9">
				&nbsp;
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];

		$link 	= JRoute::_( 'index.php?option=com_rokcandy&view=rokcandy&task=edit&cid[]='. $row->id );

		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$published 	= JHTML::_('grid.published', $row, $i );

		$ordering = ($this->lists['order'] == 'a.ordering');
		
		$row->cat_link 	= JRoute::_( 'index.php?option=com_categories&section=com_rokcandy&task=edit&type=other&cid[]='. $row->catid );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->escape($row->macro); ?>
			</td>
			<td>
				<?php echo $this->escape($row->html); ?>
			</td>
			<td>
    			<?php echo $this->escape($row->category); ?>
    		</td>
		</tr>

		<?php
		$k = 1 - $k;
	}
	?>
	<?php if (count($this->overrides) > 0): ?>
    <?php
    $k = 0; $i++;
	foreach ($this->overrides as $macro=>$html) {
    ?>
        <tr class="<?php echo "row$k"; ?>">
            <td class="macro"><?php echo $macro; ?></td>
            <td><?php echo htmlentities($html); ?></td>
            <td class="macro">Template Overrides</td>
        </tr>
    	<?php
    	$k = 1 - $k;
    }
    ?>
    <?php endif; ?>
	</tbody>
	</table>
</div>
    <input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="option" value="com_rokcandy" />
	<input type="hidden" name="task" value="list" />
	<input type="hidden" name="filter_state" value="P" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
