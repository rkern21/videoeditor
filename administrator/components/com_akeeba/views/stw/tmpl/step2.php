<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: step2.php 698 2011-06-03 22:33:44Z nikosdion $
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');
?>
<div id="akeeba-container" style="width:100%">
<form name="adminForm" action="index.php" method="get" class="akeeba-formstyle-reset">
<input type="hidden" name="option" value="com_akeeba" />
<input type="hidden" name="view" value="stw" />
<input type="hidden" name="task" value="step3" />
<?php echo JHTML::_( 'form.token' ); ?>

<fieldset>
	<legend><?php echo JText::_('STW_LBL_STEP2') ?></legend>
	
	<p><?php echo JText::_('STW_LBL_STEP2_INTRO');?></p>
	
	<table class="adminlist" width="100%">
		<tr class="row0">
			<td>
				<label for="method"><?php echo JText::_('STW_LBL_CONNECTION_TYPE')?></label>
			</td>
			<td>
				<select id="method" name="method">
					<option value="ftp" <?php if($this->opts->method == 'ftp') echo 'selected="selected"' ?>><?php echo JText::_('STW_LBL_CONNECTION_TYPE_FTP') ?></option>
					<option value="ftps" <?php if($this->opts->method == 'ftps') echo 'selected="selected"' ?>><?php echo JText::_('STW_LBL_CONNECTION_TYPE_FTPS') ?></option>
					<?php if(AKEEBA_PRO): ?>
					<option value="sftp" <?php if($this->opts->method == 'sftp') echo 'selected="selected"' ?>><?php echo JText::_('STW_LBL_CONNECTION_TYPE_SFTP') ?></option>
					<?php endif; ?>
				</select>
			</td>
		</tr>
		<tr class="row1">
			<td>
				<label for="hostname"><?php echo JText::_('STW_LBL_CONNECTION_HOST')?></label>
			</td>
			<td>
				<input type="text" size="50" name="hostname" value="<?php echo $this->opts->hostname ?>" />
			</td>
		</tr>
		<tr class="row0">
			<td>
				<label for="port"><?php echo JText::_('STW_LBL_CONNECTION_PORT')?></label>
			</td>
			<td>
				<input type="text" size="5" name="port" value="<?php echo $this->opts->port ?>" />
			</td>
		</tr>
		<tr class="row1">
			<td>
				<label for="username"><?php echo JText::_('STW_LBL_CONNECTION_USERNAME')?></label>
			</td>
			<td>
				<input type="text" size="50" name="username" value="<?php echo $this->opts->username ?>" />
			</td>
		</tr>
		<tr class="row0">
			<td>
				<label for="password"><?php echo JText::_('STW_LBL_CONNECTION_PASSWORD')?></label>
			</td>
			<td>
				<input type="password" size="50" name="password" value="<?php echo $this->opts->password ?>" />
			</td>
		</tr>
		<tr class="row1">
			<td>
				<label for="directory"><?php echo JText::_('STW_LBL_CONNECTION_DIRECTORY')?></label>
			</td>
			<td>
				<input type="text" size="50" name="directory" value="<?php echo $this->opts->directory ?>" />
			</td>
		</tr>
		<tr class="row0">
			<td>
				<label for="passive"><?php echo JText::_('STW_LBL_CONNECTION_PASSIVE')?></label>
			</td>
			<td>
				<input type="checkbox" name="passive" <?php if($this->opts->passive) echo 'checked="checked"' ?> />
			</td>
		</tr>
		<tr class="row1">
			<td>
				<label for="livesite"><?php echo JText::_('STW_LBL_CONNECTION_URL')?></label>
			</td>
			<td>
				<input type="text" size="50" name="livesite" value="<?php echo $this->opts->livesite ?>" />
			</td>
		</tr>
	</table>
	
	<p>
		<button onclick="this.form.submit(); return false;"><?php echo JText::_('STW_LBL_NEXT') ?></button>
	</p>
</fieldset>
	
</form>
</div>