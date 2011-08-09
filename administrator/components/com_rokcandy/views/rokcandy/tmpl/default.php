<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	// Set toolbar items for the page
	JToolBarHelper::title(   JText::_( 'ROKCANDY_MANAGER' ), 'rokcandy.png' );
	JToolBarHelper::publishList();
	JToolBarHelper::unpublishList();
	JToolBarHelper::deleteList();
	JToolBarHelper::editListX();
	JToolBarHelper::addNewX();
	JToolBarHelper::preferences('com_rokcandy', '320');
	JToolBarHelper::help( 'screen.rokcandy', true );
?>
<form action="index.php" method="post" name="adminForm">
<table>
<tr>
	<td align="left" width="100%">
		<?php echo JText::_( 'FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
	</td>
	<td nowrap="nowrap">
		<?php
			echo $this->lists['catid'];
			echo $this->lists['state'];
		?>
	</td>
</tr>
</table>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'Macro', 'a.macro', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'HTML', 'a.html', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="15%"  class="title">
				<?php echo JHTML::_('grid.sort',  'Category', 'category', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Published', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order',  $this->items ); ?>
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
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<?php
				if (  JTable::isCheckedOut($this->user->get ('id'), $row->checked_out ) ) {
					echo $this->escape($row->title);
				} else {
				?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT_MACRO' );?>::<?php echo $this->escape($row->macro); ?>">
					<a href="<?php echo $link; ?>">
						<?php echo $this->escape($row->macro); ?></a></span>
				<?php
				}
				?>
			</td>
			<td>
				<?php echo $this->escape($row->html); ?>
			</td>
			<td>
    			<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT_CATEGORY' );?>::<?php echo $this->escape($row->category); ?>">
    			<a href="<?php echo $row->cat_link; ?>" >
    			<?php echo $this->escape($row->category); ?></a></span>
    		</td>
			<td align="center">
				<?php echo $published;?>
			</td>
			<td class="order">
			    <span><?php echo $this->pagination->orderUpIcon( $i, true,'orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="2" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
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
            <td><?php echo $i++; ?></td>
            <td align="center">n/a</td>
            <td class="macro"><?php echo $macro; ?></td>
            <td><?php echo htmlentities($html); ?></td>
            <td class="macro">Template Overrides</td>
            <td align="center"><img border="0" alt="Published" src="images/tick.png"/></td>
            <td align="center">n/a</td>
        </tr>
    	<?php
    	$k = 1 - $k;
    }
    ?>
    <?php endif; ?>
	</tbody>
	</table>
</div>

	<input type="hidden" name="option" value="com_rokcandy" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
