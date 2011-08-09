<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: default.php 698 2011-06-03 22:33:44Z nikosdion $
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

// Apply error container chrome if there are errors detected
$quirks_style = $this->haserrors ? 'class="ui-state-error"' : "";
?>
<div id="akeeba-container" style="width: 100%">

<!-- jQuery & jQuery UI detection. Also shows a big, fat warning if they're missing -->
<div id="nojquerywarning" style="margin: 1em; padding: 1em; background: #ffff00; border: thick solid red; color: black; font-size: 14pt;">
	<h1 style="margin: 1em 0; color: red; font-size: 22pt;"><?php echo JText::_('AKEEBA_CPANEL_WARN_ERROR') ?></h1>
	<p><?php echo JText::_('AKEEBA_CPANEL_WARN_JQ_L1'); ?></p>
	<p><?php echo JText::_('AKEEBA_CPANEL_WARN_JQ_L2'); ?></p>
</div>

<script type="text/javascript">
	if(typeof akeeba.jQuery == 'function')
	{
		if(typeof akeeba.jQuery.ui == 'object')
		{
			akeeba.jQuery('#nojquerywarning').css('display','none');
		}
	}
</script>

<script type="text/javascript">
// Initialization
akeeba.jQuery(document).ready(function($){
	// The return URL
	akeeba_return_url = '<?php echo AkeebaHelperEscape::escapeJS($this->returnurl) ?>';
	akeeba_is_stw = <?php echo ($this->isSTW) ? 'true' : 'false' ?>;

	// Used as parameters to start_timeout_bar()
	akeeba_max_execution_time = <?php echo $this->maxexec; ?>;
	akeeba_time_bias = <?php echo $this->bias; ?>;

	// Create a function for saving the editor's contents
	akeeba_comment_editor_save = function() {
	}

	// Push some translations
	akeeba_translations['UI-LASTRESPONSE'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('BACKUP_TEXT_LASTRESPONSE')) ?>';
	akeeba_translations['UI-STW-CONTINUE'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('STW_MSG_CONTINUE')) ?>';

	//Parse the domain keys
	akeeba_domains = JSON.parse("<?php echo $this->domains ?>");

	// Setup AJAX proxy URL
	akeeba_ajax_url = 'index.php?option=com_akeeba&view=backup&task=ajax';

	// Setup the IFRAME mode
	akeeba_use_iframe = <?php echo $this->useiframe ?>;
	
	// Publish the SRP info
	akeeba_srp_info = JSON.parse('<?php echo json_encode($this->srpinfo) ?>');

	<?php if( ($this->srpinfo['tag'] == 'restorepoint') || ($this->autostart) ):?>
	backup_start();
	<?php else: ?>
	// Bind start button's click event
	$('#backup-start').bind("click", function(e){
		backup_start();
	});	
	<?php endif; ?>
});
</script>

<div id="backup-setup">
	<h1><?php echo JText::_('BACKUP_HEADER_STARTNEW') ?></h1>

	<script type="text/javascript">
	function flipProfile()
	{
		(function($) {
			// Save the description and comments
			$('#flipDescription').val(  $('#backup-description').val() );
			$('#flipComment').val( $('#comment').val() );
			document.forms.flipForm.submit();
		})(akeeba.jQuery);
	}
	</script>

	<?php if ($this->hasquirks): ?>
	<div id="quirks" <?php echo $quirks_style ?>>
		<h3><?php echo JText::_('BACKUP_LABEL_DETECTEDQUIRKS') ?></h3>
		<p><?php echo JText::_('BACKUP_LABEL_QUIRKSLIST') ?></p>
		<?php echo $this->quirks; ?>
	</div>
	<?php endif; ?>

	<?php $row = 1 ?>

	<hr/>

	<form action="index.php" method="post" name="flipForm" id="flipForm">
		<input type="hidden" name="option" value="com_akeeba" />
		<input type="hidden" name="view" value="backup" />
		<input type="hidden" name="returnurl" value="<?php htmlentities($this->returnurl, ENT_COMPAT, 'UTF-8', false) ?>" />
		<input type="hidden" name="description" id="flipDescription" value="" />
		<input type="hidden" name="comment" id="flipComment" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<table id="backup-setup-parameters" width="100%">
			<tr class="row<?php $row = 1 - $row; echo $row ?>">
				<td width="200">
					<?php echo JText::_('CPANEL_PROFILE_TITLE'); ?>
				</td>
				<td>
					#<?php echo $this->profileid; ?>
					<?php echo JHTML::_('select.genericlist', $this->profilelist, 'profileid', 'onchange="flipProfile();"', 'value', 'text', $this->profileid); ?>
					
					<button onclick="flipProfile(); return false;"><?php echo JText::_('CPANEL_PROFILE_BUTTON'); ?></button>
				</td>
			</tr>
		</table>		
	</form>

	<hr/>

	<form id="dummyForm">
	<table id="backup-setup-parameters" width="100%">
		<tr class="row<?php $row = 1 - $row; echo $row ?>">
			<td width="200" valign="top"><?php echo JText::_('BACKUP_LABEL_DESCRIPTION'); ?></td>
			<td valign="top">
				<input type="text" name="description" value="<?php echo $this->description; ?>"
				maxlength="255" size="80" id="backup-description" />
			</td>
		</tr>
		<?php if($this->showjpskey): ?>
		<tr class="row<?php $row = 1 - $row; echo $row ?>">
			<td>
				<?php echo JText::_('CONFIG_JPS_KEY_TITLE'); ?>
			</td>
			<td>
				<input type="password" name="jpskey" value="<?php echo htmlentities($this->jpskey, ENT_COMPAT, 'UTF-8', false) ?>"
				size="50" id="jpskey" />
			</td>
		</tr>
		<?php endif; ?>
		<tr class="row<?php $row = 1 - $row; echo $row ?>">
			<td><?php echo JText::_('BACKUP_LABEL_COMMENT'); ?></td>
			<td>
<textarea id="comment" rows="5" cols="73"><?php echo $this->comment ?></textarea>
			</td>
		</tr>
		<tr class="row<?php $row = 1 - $row; echo $row ?>">
			<td>&nbsp;</td>
			<td>
				<button id="backup-start" onclick="return false;"><?php echo JText::_('BACKUP_LABEL_START') ?></button>
			</td>
		</tr>
	</table>
	</form>
</div>

<div id="backup-progress-pane" style="display: none">
	<div class="ui-state-highlight" style="padding: 0.3em; margin: 0.3em 0.2em; font-weight: bold;">
			<span class="ui-icon ui-icon-notice" style="float: left;"></span>
			<?php echo JText::_('BACKUP_TEXT_BACKINGUP'); ?>
	</div>
	<fieldset>
		<legend><?php echo JText::_('BACKUP_LABEL_PROGRESS') ?></legend>
		<div id="backup-progress-content">
			<div id="backup-steps" class="ui-corner-all">
			</div>
			<div id="backup-status" class="ui-corner-all">
				<div id="backup-step"></div>
				<div id="backup-substep"></div>
			</div>
			<div id="backup-percentage" class="ui-corner-all">
				<div class="color-overlay" class="ui-corner-all"></div>
				<div class="text"></div>
			</div>
			<div id="response-timer">
				<div class="color-overlay"></div>
				<div class="text"></div>
			</div>
		</div>
		<span id="ajax-worker"></span>
	</fieldset>
</div>

<div id="backup-complete" style="display: none">
	<fieldset>
		<legend><?php echo JText::_('BACKUP_HEADER_BACKUPFINISHED'); ?></legend>
		<div id="finishedframe">
			<div style="min-height: 32px">
				<div class="ak-icon ak-icon-ok" style="float: left; margin: 0 1em 0 0 !important;"></div>
				<p>
					<?php echo JText::_('BACKUP_TEXT_CONGRATS') ?>
				</p>
			</div>
	
			<div class="ak-action-button">
				<div class="ak-icon ak-icon-adminfiles"></div>
				<button onclick="window.location='<?php echo JURI::base() ?>index.php?option=com_akeeba&view=buadmin'; return false;"><?php echo JText::_('BUADMIN'); ?></button>
			</div>
			<div class="ak-action-button">
				<div class="ak-icon ak-icon-viewlog"></div>
				<button onclick="window.location='<?php echo JURI::base() ?>index.php?option=com_akeeba&view=log'; return false;"><?php echo JText::_('VIEWLOG'); ?></button>
			</div>
		</div>
	</fieldset>
</div>

<div id="backup-warnings-panel" style="display:none">
	<fieldset>
		<legend><?php echo JText::_('BACKUP_LABEL_WARNINGS') ?></legend>
		<div id="warnings-list">
		</div>
	</fieldset>
</div>

<div id="error-panel" style="display: none">
	<fieldset>
		<legend><?php echo JText::_('BACKUP_HEADER_BACKUPFAILED'); ?></legend>
		<div id="errorframe">
			<p><?php echo JText::_('BACKUP_TEXT_BACKUPFAILED') ?></p>
			<p id="backup-error-message">
			</p>
			<p>
				<?php echo JText::_('BACKUP_TEXT_READLOGFAIL') ?>
			</p>
			<p>
				<?php echo JText::sprintf('BACKUP_TEXT_RTFMFIRST', 'http://www.akeebabackup.com/forum') ?>
			</p>
			
			<div class="ak-action-button">
				<div class="ak-icon ak-icon-viewlog"></div>
				<button onclick="window.location='<?php echo JURI::base() ?>index.php?option=com_akeeba&view=log'; return false;"><?php echo JText::_('VIEWLOG'); ?></button>
			</div>
		</div>
	</fieldset>
</div>

</div>