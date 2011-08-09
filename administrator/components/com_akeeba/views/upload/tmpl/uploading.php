<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id$
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
?>
<div id="akeeba-container" style="width:100%">
<form action="index.php" method="get" name="akeebaform">
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="view" value="upload" />
	<input type="hidden" name="task" value="upload" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="id" value="<?php echo $this->id ?>" />
	<input type="hidden" name="part" value="<?php echo $this->part ?>" />
	<input type="hidden" name="frag" value="<?php echo $this->frag ?>" />
</form>

<?php if($frag == 0): ?>
<p>
	<?php echo JText::sprintf('AKEEBA_TRANSFER_MSG_UPLOADINGPART',$this->part+1, $this->parts); ?>
</p>
<?php else: ?>
<p>
	<?php echo JText::sprintf('AKEEBA_TRANSFER_MSG_UPLOADINGFRAG',$this->part+1, $this->parts); ?>
</p>
<?php endif; ?>

<script type="text/javascript">
	window.setTimeout('postMyForm();', 1000);
	function postMyForm()
	{
		document.forms.akeebaform.submit();
	}
</script>
</div>