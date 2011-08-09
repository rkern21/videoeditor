<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
	<!--controller=list&amp;-->

	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('levellimit').value='10';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
	</table>

<table class="adminlist">
	<thead>
		<tr>
			<th width="20">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',   JText::_( 'GALLERY NAME' ), 'galleryname', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			
			<th class="title">
				<?php echo JText::_( 'FOLDER' ); ?>
			</th>
			<th class="title">
				<?php echo JText::_( 'SIZE' ); ?>
			</th>
			
			
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	
	$k = 0;
	$i = 0;
	$n = count( $this->items );
	$rows = &$this->items;

	foreach ($rows as $row)
	{
		
		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$link_edittable='index.php?option=com_fadegallery&controller=galleries&task=edit&cid[]='.$row->id;
		
		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id;?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td nowrap="nowrap">
				<?php echo '<a href="'.$link_edittable.'">'.$row->galleryname.'</a>'; ?>
			</td>
			<td nowrap="nowrap">
				<?php echo $row->folder; ?>
			</td>
			
			<td nowrap="nowrap">
				<?php
				
				if($row->width>0 or $row->height>0)
					echo 	( $row->width>0 ? $row->width.'px' : '' )
							.' x '.
							( $row->height>0 ? $row->height.'px' : '' )
				;
				?>
			</td>
			

		</tr>
		<?php
		$k = 1 - $k;
		$i++;
	}
		?>
	
	</tbody>
	</table>

	<input type="hidden" name="option" value="com_fadegallery" />
	<input type="hidden" name="controller" value="galleries" />
	<input type="hidden" name="task" value="view" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>