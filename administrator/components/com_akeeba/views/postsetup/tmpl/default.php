<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id$
 * @since 1.3
 */

defined('_JEXEC') or die('Restricted access');

?>
<div id="akeeba-container" style="width:100%">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="view" value="postsetup" />
	<input type="hidden" name="task" id="task" value="save" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
	<p><?php echo JText::_('AKEEBA_POSTSETUP_LBL_WHATTHIS'); ?></p>
	
	<input type="checkbox" id="srp" name="srp" <?php if($this->enablesrp): ?>checked="checked"<?php endif; ?> />
	<label for="srp" class="postsetup-main"><?php echo JText::_('AKEEBA_POSTSETUP_LBL_SRP')?></label>
	</br>
	<div class="postsetup-desc"><?php echo JText::_('AKEEBA_POSTSETUP_DESC_SRP');?></div>
	<br/>

	<input type="checkbox" id="autoupdate" name="autoupdate" <?php if($this->enableautoupdate): ?>checked="checked"<?php endif; ?> />
	<label for="autoupdate" class="postsetup-main"><?php echo JText::_('AKEEBA_POSTSETUP_LBL_AUTOUPDATE')?></label>
	</br>
	<div class="postsetup-desc"><?php echo JText::_('AKEEBA_POSTSETUP_DESC_autoupdate');?></div>
	<br/>
	
	<input type="checkbox" id="confwiz" name="confwiz" <?php if($this->enableconfwiz): ?>checked="checked"<?php endif; ?> />
	<label for="confwiz" class="postsetup-main"><?php echo JText::_('AKEEBA_POSTSETUP_LBL_confwiz')?></label>
	</br>
	<div class="postsetup-desc"><?php echo JText::_('AKEEBA_POSTSETUP_DESC_confwiz');?></div>
	<br/>
	
	<br/>
	<button onclick="this.form.submit(); return false;"><?php echo JText::_('AKEEBA_POSTSETUP_LBL_APPLY');?></button>

</form>
</div>