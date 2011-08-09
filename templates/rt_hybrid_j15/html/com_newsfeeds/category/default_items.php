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

<form action="<?php echo JRoute::_('index.php?view=category&id='.$this->category->slug); ?>" method="post" name="adminForm">

<?php if ($this->params->get('show_limit')) : ?>
<div class="filter">
	<?php
		echo JText::_('Display Num') .'&nbsp;';
		echo $this->pagination->getLimitBox();
	?>
</div>
<?php endif; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="rt-joomla-table">
	<?php if ( $this->params->get( 'show_headings' ) ) : ?>
	<tr>
		<th align="right" width="5">
			<?php echo JText::_('Num'); ?>
		</th>
		<?php if ( $this->params->get( 'show_name' ) ) : ?>
		<th align="left" width="90%">
			<?php echo JText::_( 'Feed Name' ); ?>
		</th>
		<?php endif; ?>
		<?php if ( $this->params->get( 'show_articles' ) ) : ?>
		<th width="10%" align="center" nowrap="nowrap">
			<?php echo JText::_( 'Num Articles' ); ?>
		</th>
		<?php endif; ?>
	 </tr>
	<?php endif; ?>
	
	<?php foreach ($this->items as $item) : ?>
	<tr class="<?php if ($item->odd) { echo 'even'; } else { echo 'odd'; } ?>">
		<td align="right" width="5">
			<?php echo $item->count + 1; ?>
		</td>
		<td width="90%">
			<a href="<?php echo $item->link; ?>" class="category"><?php echo $this->escape($item->name); ?></a>
		</td>
		<?php if ( $this->params->get( 'show_articles' ) ) : ?>
		<td width="10%" align="center">
			<?php echo $item->numarticles; ?>
		</td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>

</table>

<div class="rt-pagination">
	<p class="rt-results">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>

</form>