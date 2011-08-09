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
	<div class="contact">

		<?php if ( $this->params->get( 'show_page_title', 1 ) && !$this->contact->params->get( 'popup' ) && $this->params->get('page_title') != $this->contact->name ) : ?>
		<h1 class="rt-pagetitle">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h1>
		<?php endif; ?>

		<?php if ( $this->params->get( 'show_contact_list' ) && count( $this->contacts ) > 1) : ?>
		<div class="filter">
			<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="selectForm" id="selectForm">
				<?php echo JText::_( 'Select Contact' ); ?>:
				<?php echo JHTML::_('select.genericlist',  $this->contacts, 'contact_id', 'class="inputbox" onchange="this.form.submit()"', 'id', 'name', $this->contact->id);?>
				<input type="hidden" name="option" value="com_contact" />
			</form>
		</div>
		<?php endif; ?>

		<?php if ( $this->contact->name && $this->contact->params->get( 'show_name' ) ) : ?>
		<h1 class="name">
			<?php echo $this->escape($this->contact->name); ?>
		</h1>
		<?php endif; ?>
		
		<?php if ( $this->contact->con_position && $this->contact->params->get( 'show_position' ) ) : ?>
		<h2>
			<?php echo $this->escape($this->contact->con_position); ?>
		</h2>
		<?php endif; ?>

		<?php if ( $this->contact->image && $this->contact->params->get( 'show_image' ) ) : ?>
		<div class="image">
			<?php echo JHTML::_('image', 'images/stories' . '/'.$this->contact->image, JText::_( 'Contact' ), array('align' => 'middle')); ?>
		</div>
		<?php endif; ?>
	
		<?php echo $this->loadTemplate('address'); ?>

		<?php if ( $this->contact->params->get( 'allow_vcard' ) ) : ?>
		<p>
			<?php echo JText::_( 'Download information as a' );?>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_contact&amp;task=vcard&amp;contact_id=<?php echo $this->contact->id; ?>&amp;format=raw&amp;tmpl=component"><?php echo JText::_( 'VCard' );?></a>
		</p>
		<?php endif;

		if ( $this->contact->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id))
			echo $this->loadTemplate('form');
		?>

	</div>
</div>