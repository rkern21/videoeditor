<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: step1.php 698 2011-06-03 22:33:44Z nikosdion $
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
?>
<div id="akeeba-container" style="width:100%">
<form name="adminForm" action="index.php" method="get" class="akeeba-formstyle-reset">
<input type="hidden" name="option" value="com_akeeba" />
<input type="hidden" name="view" value="stw" />
<input type="hidden" name="task" value="step2" />
<?php echo JHTML::_( 'form.token' ); ?>

<fieldset>
	<legend><?php echo JText::_('STW_LBL_STEP1') ?></legend>
	
	<p><?php echo JText::_('STW_LBL_STEP1_INTRO');?></p>
	
	<?php if($this->stw_profile_id > 0): ?>
	<input type="radio" name="method" value="none" checked="checked">
		<?php echo JText::_('STW_PROFILE_STW') ?>
	</input>
	<br/>
	<?php endif; ?>
	
	<input type="radio" name="method" value="copyfrom">
		<?php echo JText::_('STW_PROFILE_COPYFROM') ?>
		<?php echo JHTML::_('select.genericlist', $this->profilelist, 'oldprofile'); ?>
	</input>
	<br/>
	
	<input type="radio" name="method" value="blank" <?php echo ($this->stw_profile_id > 0) ? '' : 'checked="checked"' ?>>
		<?php echo JText::_('STW_PROFILE_BLANK') ?>
	</input>
	<br/>
	
	<p>
		<button onclick="this.form.submit(); return false;"><?php echo JText::_('STW_LBL_NEXT') ?></button>
	</p>
	
</fieldset>
	
</form>
</div>