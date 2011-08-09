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
defined('_JEXEC') or die('Restricted access');
?>

<h4 class="rt-polltitle">
	<?php echo $poll->title; ?>
</h4>
<form action="index.php" method="post" name="form2" class="rt-poll">
	<fieldset>
		<?php for ($i = 0, $n = count($options); $i < $n; $i ++) : ?>
		<div class="rt-pollrow">
			<input type="radio" name="voteid" id="voteid<?php echo $options[$i]->id;?>" value="<?php echo $options[$i]->id;?>" alt="<?php echo $options[$i]->id;?>" />
			<label for="voteid<?php echo $options[$i]->id;?>">
				<?php echo $options[$i]->text; ?>
			</label>
		</div>
		<?php endfor; ?>
	</fieldset>
	<div class="rt-pollbuttons">
		<div class="readon">
			<input type="submit" name="task_button" class="button" value="<?php echo JText::_('Vote'); ?>" />
		</div>
		<div class="readon">
			<input type="button" name="option" class="button" value="<?php echo JText::_('Results'); ?>" onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_poll&id=$poll->slug".$itemid); ?>'" />
		</div>
	</div>

	<input type="hidden" name="option" value="com_poll" />
	<input type="hidden" name="task" value="vote" />
	<input type="hidden" name="id" value="<?php echo $poll->id;?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
