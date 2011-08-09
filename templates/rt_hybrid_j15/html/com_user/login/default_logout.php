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

<?php /** @todo Should this be routed */ ?>

<div class="rt-joomla <?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	
	<div class="user">
	
		<?php if ( $this->params->get( 'show_logout_title' ) ) : ?>
		<h1>
			<?php echo $this->escape($this->params->get( 'header_logout' )); ?>
		</h1>
		<?php endif; ?>

		<?php if ($this->params->get('description_logout') || $this->image) : ?>
		<div class="rt-description">
			<?php echo $this->image; ?>
			<?php if ($this->params->get('description_logout')) : ?>
				<?php echo $this->escape($this->params->get('description_logout_text')); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" name="login" id="login">
		<div class="readon">
				<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'Logout' ); ?>" />
			</div>

		<input type="hidden" name="option" value="com_user" />
		<input type="hidden" name="task" value="logout" />
		<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
		</form>
		
	</div>
</div>