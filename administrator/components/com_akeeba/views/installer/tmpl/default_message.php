<?php // no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' ); 
	if(version_compare(JVERSION,'1.6.0','ge')) {
		$state			= $this->get('State');
		$message1		= $state->get('message');
		$message2		= $state->get('extension_message');
	} else {
		$state			= &$this->get('State');
		$message1		= $state->get('message');
		$message2		= $state->get('extension.message');
	}
?>
<table class="adminform">
	<tbody>
		<?php if($message1) : ?>
		<tr>
			<?php if(!version_compare(JVERSION,'1.6.0','ge')): ?>
			<th><?php echo JText::_($message1) ?></th>
			<?php else: ?>
			<th><?php echo $message1 ?></th>
			<?php endif; ?>
		</tr>
		<?php endif; ?>
		<?php if($message2) : ?>
		<tr>
			<td><?php echo $message2; ?></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>
