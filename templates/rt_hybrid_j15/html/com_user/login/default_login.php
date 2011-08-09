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

<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
		$lang = &JFactory::getLanguage();
		$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
		$langScript = 	'var JLanguage = {};'.
						' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
						' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
						' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
						' var comlogin = 1;';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration( $langScript );
		JHTML::_('script', 'openid.js');
endif; ?>

<div class="rt-joomla <?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	
	<div class="user">

		<?php if ( $this->params->get( 'show_login_title' ) ) : ?>
		<h1>
			<?php echo $this->params->get( 'header_login' ); ?>
		</h1>
		<?php endif; ?>

		<?php if ($this->params->get('description_login') || $this->image) : ?>
		<div class="rt-description">
			<?php echo $this->image; ?>
			<?php if ( $this->params->get( 'description_login' ) ) : ?>
				<?php echo $this->params->get( 'description_login_text' ); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		
		<form action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure')); ?>" method="post" name="com-login" id="com-form-login">
		<fieldset>
			<legend><?php echo JText::_('LOGIN') ?></legend>
			<br />
			<div>
				<label class="label-left" for="username"><?php echo JText::_('Username') ?></label>
				<input name="username" id="username" type="text" class="inputbox" alt="username" size="18" />
			</div>
			<div>
				<label class="label-left" for="passwd"><?php echo JText::_('Password') ?></label>
				<input type="password" id="passwd" name="passwd" class="inputbox" size="18" alt="password" />
			</div>
			<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
			<div>
				<input type="checkbox" id="remember" name="remember" class="inputbox" value="yes" alt="Remember Me" />
				<label for="remember"><?php echo JText::_('Remember me') ?></label>
			</div>
			<?php endif; ?>
				<div class="readon"><input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" /></div>
			
		</fieldset>
	
		<ul>
			<li>
				<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>"><?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>"><?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
			</li>
			<?php $usersConfig = &JComponentHelper::getParams( 'com_users' ); ?>
			<?php if ($usersConfig->get('allowUserRegistration')) : ?>
			<li>
				<a href="<?php echo JRoute::_( 'index.php?option=com_user&task=register' ); ?>"><?php echo JText::_('REGISTER'); ?></a>
			</li>
			<?php endif; ?>
		</ul>

		<input type="hidden" name="option" value="com_user" />
		<input type="hidden" name="task" value="login" />
		<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>

	</div>
</div>