<?php
/**
 * @version		$Id: login.php 303 2010-01-07 02:56:33Z joomlaworks $
 * @package		K2
 * @author    JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// OpenID stuff (do not edit)
if(JPluginHelper::isEnabled('authentication', 'openid')){
	$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
	$langScript = '
		var JLanguage = {};
		JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';
		JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';
		JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';
		var modlogin = 1;
	';
	$document = &JFactory::getDocument();
	$document->addScriptDeclaration( $langScript );
	JHTML::_('script', 'openid.js');
}

// Get user stuff (do not edit)
$usersConfig = &JComponentHelper::getParams( 'com_users' );

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2LoginBlock <?php echo $params->get('moduleclass_sfx'); ?>">
	<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	
		<?php if($params->get('pretext')): ?>
		<p class="preText"><?php echo $params->get('pretext'); ?></p>
	  <?php endif; ?>
	  
	  <fieldset class="input">
	    <p id="form-login-username">
	      <label for="modlgn_username"><?php echo JText::_('Username') ?></label>
	      <input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" />
	    </p>
	    <p id="form-login-password">
	      <label for="modlgn_passwd"><?php echo JText::_('Password') ?></label>
	      <input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" />
	    </p>
	    <?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	    <p id="form-login-remember">
	      <input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
	      <label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
	    </p>
	    <?php endif; ?>
	    
	    <div class="readon"><input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" /></div>
	  </fieldset>
	  
	  <ul>
	    <li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>"><?php echo JText::_('Forgot your password?'); ?></a></li>
	    <li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>"><?php echo JText::_('Forgot your username?'); ?></a></li>
	    <?php if ($usersConfig->get('allowUserRegistration')): ?>
	    <li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>"><?php echo JText::_('Create an account'); ?></a></li>
	    <?php endif; ?>
	  </ul>
	  
	  <?php if($params->get('posttext')): ?>
	  <p class="postText"><?php echo $params->get('posttext'); ?></p>
	  <?php endif; ?>
	  
	  <input type="hidden" name="option" value="com_user" />
	  <input type="hidden" name="task" value="login" />
	  <input type="hidden" name="return" value="<?php echo $return; ?>" />
	  <?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
