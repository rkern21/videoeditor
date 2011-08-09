<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: step3.php 698 2011-06-03 22:33:44Z nikosdion $
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
?>
<div id="akeeba-container" style="width:100%">
<form name="adminForm" action="index.php" method="post" class="akeeba-formstyle-reset">
<input type="hidden" name="option" value="com_akeeba" />
<input type="hidden" name="view" value="backup" />
<input type="hidden" name="returnurl" value="<?php echo AEFactory::getConfiguration()->get('akeeba.stw.livesite','')?>/installation/index.php" />
<input type="hidden" name="autostart" value="1" />
<?php echo JHTML::_( 'form.token' ); ?>

<img src="<?php echo AEFactory::getConfiguration()->get('akeeba.stw.livesite','')?>/akeeba_connection_test.png" onerror="incorrectDirectory()" style="display: none" />

<fieldset>
	<legend><?php echo JText::_('STW_LBL_STEP3') ?></legend>
	
	<p><?php echo JText::_('STW_LBL_STEP3_INTROA');?></p>
	
	<p>
		<button onclick="document.forms.adminForm.view.value='cpanel'; this.form.submit(); return false;"><?php echo JText::_('STW_LBL_STEP3_LBL_CONTROLPANEL') ?></button>
	</p>
	
	<p><?php echo JText::_('STW_LBL_STEP3_INTROB');?></p>
	
	<p>
		<button onclick="this.form.submit(); return false;"><?php echo JText::_('STW_LBL_STEP3_LBL_TRANSFER') ?></button>
	</p>
</fieldset>
	
</form>

<script type="text/javascript">
function incorrectDirectory()
{
	alert('<?php echo JText::_('STW_LBL_CONNECTION_ERR_HOST') ?>');
	history.go(-1);
}
</script>
</div>