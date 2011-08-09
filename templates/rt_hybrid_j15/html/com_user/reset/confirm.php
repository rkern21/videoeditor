<?php
/**
 * @package   Hybrid Template - RocketTheme
 * @version   1.5.4 November 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Hybrid Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div class="rt-joomla <?php echo $this->params->get('pageclass_sfx')?>">
	<div class="user">

		<h1 class="rt-pagetitle">
			<?php echo JText::_('Confirm your Account'); ?>
		</h1>

		<p>
			<?php echo JText::_('RESET_PASSWORD_CONFIRM_DESCRIPTION'); ?>
		</p>

		<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=confirmreset' ); ?>" method="post" class="josForm form-validate">
		<fieldset>
			<legend><?php echo JText::_('Confirm your Account'); ?></legend>
			
			<div>
				<label for="username" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_USERNAME_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_USERNAME_TIP_TEXT'); ?>"><?php echo JText::_('User Name'); ?>:</label>
				<input id="username" name="username" type="text" class="required" size="36" />
			</div>
			<div>
				<label for="token" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TEXT'); ?>"><?php echo JText::_('Token'); ?>:</label>
				<input id="token" name="token" type="text" class="required" size="36" />
			</div>
			<div class="readon">
				<button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
			</div>
			
		</fieldset>
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>