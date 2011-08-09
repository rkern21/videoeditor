<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: restore.php 632 2011-05-22 20:44:46Z nikosdion $
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

?>
<div id="akeeba-container" style="width:100%">
<div id="restoration-progress" class="akeeba-restore">
	<div class="ui-state-highlight" style="padding: 0.3em; margin: 0.3em 0.2em; font-weight: bold;">
			<span class="ui-icon ui-icon-notice" style="float: left;"></span>
			<?php echo JText::_('RESTORE_LABEL_DONOTCLOSE'); ?>
	</div>
	<fieldset>
		<legend><?php echo JText::_('RESTORE_LABEL_INPROGRESS') ?></legend>
		<div id="extprogress">
			<div class="extprogrow">
				<span class="extlabel"><?php echo JText::_('RESTORE_LABEL_BYTESREAD'); ?></span>
				<span class="extvalue" id="extbytesin"></span>
			</div>
			<div class="extprogrow">
				<span class="extlabel"><?php echo JText::_('RESTORE_LABEL_BYTESEXTRACTED'); ?></span>
				<span class="extvalue" id="extbytesout"></span>
			</div>
			<div class="extprogrow">
				<span class="extlabel"><?php echo JText::_('RESTORE_LABEL_FILESEXTRACTED'); ?></span>
				<span class="extvalue" id="extfiles"></span>
			</div>
		</div>
		<div id="response-timer" class="ui-corner-all">
			<div class="color-overlay"></div>
			<div class="text"></div>
		</div>
	</fieldset>
</div>

<div id="restoration-db-progress" style="display:none">
	<div class="ui-state-highlight" style="padding: 0.3em; margin: 0.3em 0.2em; font-weight: bold;">
			<span class="ui-icon ui-icon-notice" style="float: left;"></span>
			<?php echo JText::_('RESTORE_LABEL_DONOTCLOSE_DB'); ?>
	</div>
	<fieldset>
		<legend><?php echo JText::_('RESTORE_LABEL_INPROGRESS_DB') ?></legend>
		<div id="extprogress">
			<span id="restoration-db-progress-message"></span>
		</div>
	</fieldset>
</div>

<div id="restoration-error" style="display:none">
	<fieldset>
		<legend><?php echo JText::_('RESTORE_LABEL_FAILED'); ?></legend>
		<div id="errorframe">
			<p><?php echo JText::_('RESTORE_LABEL_FAILED_INFO'); ?></p>
			<p id="backup-error-message">
			</p>
		</div>
	</fieldset>
</div>

<div id="restoration-done" style="display:none">
	<fieldset>
		<legend><?php echo JText::_('RESTORE_LABEL_SRP_COMPLETE_TITLE') ?></legend>
		<div id="restoration-done-frame">
			<p><?php echo JText::_('RESTORE_LABEL_SRP_COMPLETE_BODY') ?></p>
		</div>
	</fieldset>
</div>

<script type="text/javascript">
	var akeeba_srprestoration_ajax_url = 'index.php?option=com_akeeba&view=srprestore&task=ajax';

	(function($){
		$(document).ready(function(){
			pingSRPRestoration();
		});
	})(akeeba.jQuery);
</script>
</div>