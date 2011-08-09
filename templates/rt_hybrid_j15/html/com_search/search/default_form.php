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

<form action="<?php echo JRoute::_( 'index.php?option=com_search#content' ) ?>" method="post" class="search_result">
<a name="form1"></a>
<fieldset class="word">
<label for="search_searchword"><?php echo JText::_('Search Keyword') ?> </label>
<input type="text" name="searchword" id="search_searchword"  maxlength="20" value="<?php echo $this->escape($this->searchword) ?>" class="inputbox" />
</fieldset>

<fieldset class="phrase">
<legend><?php echo JText::_('Search Parameters') ?></legend>
<label for="ordering" class="ordering"><?php echo JText::_('Ordering') ?>:</label>
<?php echo $this->lists['ordering']; ?>
<?php echo $this->lists['searchphrase']; ?>
</fieldset>

<?php if ($this->params->get('search_areas', 1)) : ?>
<fieldset class="only"><legend><?php echo JText::_('Search Only') ?>:</legend>
	<?php foreach ($this->searchareas['search'] as $val => $txt) : ?>
		<?php $checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="true"' : ''; ?>
		<input type="checkbox" name="areas[]" value="<?php echo $val ?>" id="area_<?php echo $val ?>" <?php echo $checked ?> />
		<label for="area_<?php echo $val ?>">
		<?php echo JText::_($txt); ?>
		</label>
	<?php endforeach; ?>
</fieldset>
<?php endif; ?>
<p>
	<div class="readon"><input type="submit" name="Search" onClick="this.form.submit()" class="button" value="<?php echo JText::_( 'Search' );?>" /></div>
</p><br />


<?php if (count($this->results)) : ?>
<div class="display">
<label for="limit"><?php echo JText :: _('Display Num') ?></label>
	<?php echo $this->pagination->getLimitBox(); ?>
	<p>
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
</div>
<?php endif; ?>

<input type="hidden" name="task"   value="search" />
</form>
