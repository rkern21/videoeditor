<?php
/**
 * @package   Hybrid Template - RocketTheme
 * @version   1.5.4 November 16, 2010
 * @author    YOOtheme http://www.yootheme.com & RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * These template overrides are based on the fantastic GNU/GPLv2 overrides created by YOOtheme (http://www.yootheme.com)
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>

<div class="rt-joomla <?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<div class="rt-category-list">
		<?php /** Begin Page Title **/ if ($this->params->get('show_page_title', 1)) : ?>
		<h1 class="rt-pagetitle"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
		<?php /** End Page Title **/ endif; ?>

		<?php /** Begin Category Description **/ if ($this->category->image || $this->category->description) : ?>
		<div class="rt-description">
			<?php if ($this->category->image) : ?>
				<img class="<?php echo $this->category->image_position;?>" src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->category->image;?>" alt="" />
			<?php endif; ?>
			<?php if ($this->category->description) : ?>
				<?php echo $this->category->description; ?>
			<?php endif; ?>
		</div>
		<?php /** End Category Description **/ endif; ?>

		<?php
			$this->items =& $this->getItems();
			echo $this->loadTemplate('items');
		?>

		<?php if ($this->access->canEdit || $this->access->canEditOwn) : ?>	
			<?php echo JHTML::_('icon.create', $this->category  , $this->params, $this->access); ?>	
		<?php endif; ?>	
	</div>
</div>