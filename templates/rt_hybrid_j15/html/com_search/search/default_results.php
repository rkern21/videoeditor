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

<?php if (!empty($this->searchword)) : ?>
<div class="searchintro<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
	<p>
		<?php echo JText::_('Search Keyword') ?> <strong><?php echo $this->escape($this->searchword) ?></strong>
		<?php echo $this->result ?>
	</p>
	<p>
		<a href="#form1" class="readon" onclick="document.getElementById('search_searchword').focus();return false" onkeypress="document.getElementById('search_searchword').focus();return false"><span><?php echo JText::_('Search_again') ?></span></a>
	</p>
</div>
<?php endif; ?>

<?php if (count($this->results)) : ?>
	<h3><?php echo JText :: _('Search_result'); ?></h3>
<div class="results">
	<?php $start = $this->pagination->limitstart + 1; ?>
	<ol class="list" start="<?php echo  $start ?>">
		<?php foreach ($this->results as $result) : ?>
		<?php
		$text = $result->text;
		$text = preg_replace( '/\[.+?\]/', '', $text);
		?>	
		<li>
			<?php if ($result->href) : ?>
			<h4>
				<a href="<?php echo JRoute :: _($result->href) ?>" <?php echo ($result->browsernav == 1) ? 'target="_blank"' : ''; ?> >
					<?php echo $this->escape($result->title); ?></a>
			</h4>
			<?php endif; ?>
			<?php if ($result->section) : ?>
			<p><?php echo JText::_('Category') ?>:
				<span class="small">
					<?php echo $this->escape($result->section); ?>
				</span>
			</p>
			<?php endif; ?>

			<div class="description">
			<?php echo $result->text; ?>
			</div>
			<span class="small">
				<?php echo $this->escape($result->created); ?>
			</span>
		</li>
		<?php endforeach; ?>
	</ol>
	<div class="rt-pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
	<br />
</div>
<?php else: ?>
<div class="noresults"></div>
<?php endif; ?>
