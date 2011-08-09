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
include_once(dirname(__FILE__).DS.'..'.DS.'icon.php');

$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>

<div class="rt-joomla <?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<div class="rt-article">
		<?php /** Begin Page Title **/ if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->article->title) : ?>
		<h1 class="rt-pagetitle">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h1>
		<?php /** End Page Title **/ endif; ?>
		
		<?php if ($canEdit || (intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) || ($this->params->get('show_author') && ($this->article->author != "")) || ($this->params->get('show_create_date')) || ($this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon'))) : ?>
		<div class="rt-articleinfo">
			<div class="rt-articleinfo-text">
				<?php /** Begin Created Date **/ if ($this->params->get('show_create_date')) : ?>
				<span class="rt-date-posted">
					<?php echo JHTML::_('date', $this->article->created, JText::_('DATE_FORMAT_LC3')) ?>
				</span>
				<?php /** End Created Date **/ endif; ?>

				<?php /** Begin Modified Date **/ if (intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) : ?>
				<span class="rt-date-modified">
					<?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date', $this->article->modified, JText::_('DATE_FORMAT_LC3'))); ?>
				</span>
				<?php /** End Modified Date **/ endif; ?>

				<?php /** Begin Author **/ if ($this->params->get('show_author') && ($this->article->author != "")) : ?>
				<span class="rt-author">
					<?php JText::printf(($this->escape($this->article->created_by_alias) ? $this->article->created_by_alias : $this->escape($this->article->author)) ); ?>
				</span>
				<?php /** End Author **/endif; ?>

				<?php /** Begin Url **/ if ($this->params->get('show_url') && $this->article->urls) : ?>
				<span class="rt-url">
					<a href="http://<?php echo $this->article->urls ; ?>" target="_blank"><?php echo $this->escape($this->article->urls); ?></a>
				</span>
				<?php /** End Url **/ endif; ?>
			</div>
			<?php /** Begin Article Icons **/ if ($canEdit || $this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon')) : ?>
			<div class="rt-article-icons">
				<?php if ($this->print) :
					echo RokIcon::print_screen($this->article, $this->params, $this->access);
				elseif ($this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon')) : ?>
				<?php if ($this->params->get('show_pdf_icon')) :
					echo RokIcon::pdf($this->article, $this->params, $this->access);
				endif;
				if ($this->params->get('show_print_icon')) :
					echo RokIcon::print_popup($this->article, $this->params, $this->access);
				endif;
				if ($this->params->get('show_email_icon')) :
					echo RokIcon::email($this->article, $this->params, $this->access);
				endif;
				endif; ?>
				<?php if (!$this->print) : ?>
					<?php if ($canEdit) : ?>
					<!--<span class="icon edit">
						< ?php echo JHTML::_('icon.edit', $this->article, $this->params, $this->access); ? >
					</span>
					-->
					<?php endif; ?>
				<?php else : ?>
					<span class="icon printscreen">
						<?php echo JHTML::_('icon.print_screen',  $this->article, $this->params, $this->access); ?>
					</span>
				<?php endif; ?>
			</div>
			<?php /** End Article Icons **/ endif; ?>
		</div>
		<?php endif; ?>

		<?php /** Begin Article Title **/ if ($this->params->get('show_title')) : ?>
		<?php if ($canEdit || (intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) || ($this->params->get('show_author') && ($this->article->author != "")) || ($this->params->get('show_create_date')) || ($this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon'))) : ?>
		<div class="module-title-surround">
		<?php endif; ?>
			<div class="module-title">
			<?php if ($this->params->get('show_title')) : ?>
			<h1 class="title">
			<?php if ($this->params->get('link_titles') && $this->article->readmore_link != '') : ?><a href="<?php echo $this->article->readmore_link; ?>"><?php echo $this->escape($this->article->title); ?></a><?php else : ?><?php echo $this->escape($this->article->title); ?><?php endif; ?>
			</h1>
			<?php endif; ?>
			</div>
		<?php if ($canEdit || (intval($this->article->modified) !=0 && $this->params->get('show_modify_date')) || ($this->params->get('show_author') && ($this->article->author != "")) || ($this->params->get('show_create_date')) || ($this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon'))) : ?>
		</div>
		<?php endif; ?>
		<?php /** End Article Title **/ endif; ?>

		<?php  if (!$this->params->get('show_intro')) :
			echo $this->article->event->afterDisplayTitle;
		endif; ?>

		<?php echo $this->article->event->beforeDisplayContent; ?>

		<?php if (isset ($this->article->toc)) : ?>
			<?php echo $this->article->toc; ?>
		<?php endif; ?>

		<?php echo $this->article->text; ?>

		<?php echo $this->article->event->afterDisplayContent; ?>
		<?php /** Begin Article Sec/Cat **/ if (($this->params->get('show_section') && $this->article->sectionid) || ($this->params->get('show_category') && $this->article->catid)) : ?>
		<p class="rt-article-cat">
			<?php if ($this->params->get('show_section') && $this->article->sectionid && isset($this->article->section)) : ?>
			<span class="rt-section">
				<?php if ($this->params->get('link_section')) : ?>
					<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->article->sectionid)).'">'; ?>
				<?php endif; ?>
				<?php echo $this->escape($this->article->section); ?>
				<?php if ($this->params->get('link_section')) : ?>
					<?php echo '</a>'; ?>
				<?php endif; ?>
				<?php if ($this->params->get('show_category')) : ?>
					<?php echo ' - '; ?>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<?php if ($this->params->get('show_category') && $this->article->catid) : ?>
			<span class="rt-category">
				<?php if ($this->params->get('link_category')) : ?>
					<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'">'; ?>
				<?php endif; ?>
				<?php echo $this->escape($this->article->category); ?>
				<?php if ($this->params->get('link_category')) : ?>
					<?php echo '</a>'; ?>
				<?php endif; ?>
			</span>
			<?php endif; ?>
		</p>
		<?php /** End Article Sec/Cat **/ endif; ?>
	</div>
</div>