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

$config =& JFactory::getConfig();
$publish_up =& JFactory::getDate($this->article->publish_up);
$publish_up->setOffset($config->getValue('config.offset'));
$publish_up = $publish_up->toFormat();

if (! isset($this->article->publish_down) || $this->article->publish_down == 'Never') {
	$publish_down = JText::_('Never');
} else {
	$publish_down =& JFactory::getDate($this->article->publish_down);
	$publish_down->setOffset($config->getValue('config.offset'));
	$publish_down = $publish_down->toFormat();
}
?>

<script language="javascript" type="text/javascript">
<!--
function setgood() {
	// TODO: Put setGood back
	return true;
}

var sectioncategories = new Array;
<?php
$i = 0;
foreach ($this->lists['sectioncategories'] as $k=>$items) {
	foreach ($items as $v) {
		echo "sectioncategories[".$i++."] = new Array( '$k','".addslashes( $v->id )."','".addslashes( $v->title )."' );\n\t\t";
	}
}
?>


function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	try {
		form.onsubmit();
	} catch(e) {
		alert(e);
	}

	// do field validation
	var text = <?php echo $this->editor->getContent( 'text' ); ?>
	if (form.title.value == '') {
		return alert ( "<?php echo JText::_( 'Article must have a title', true ); ?>" );
	} else if (text == '') {
		return alert ( "<?php echo JText::_( 'Article must have some text', true ); ?>");
	} else if (parseInt('<?php echo $this->article->sectionid;?>')) {
		// for articles
		if (form.catid && getSelectedValue('adminForm','catid') < 1) {
			return alert ( "<?php echo JText::_( 'Please select a category', true ); ?>" );
		}
	}
	<?php echo $this->editor->save( 'text' ); ?>
	submitform(pressbutton);
}
//-->
</script>

<div class="rt-joomla <?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">

	<div class="edit-article">

		<?php if ($this->params->get('show_page_title', 1)) : ?>
		<h1 class="rt-pagetitle">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h1>
		<?php endif; ?>

		<form action="<?php echo $this->action ?>" method="post" name="adminForm" onSubmit="setgood();">
		<fieldset>
			<legend><?php echo JText::_('Editor'); ?></legend><br />
			
			<div class="save">
				<div class="readon"><button type="button" class="button" onclick="submitbutton('save')">
					<?php echo JText::_('Save') ?>
				</button></div>
				<div class="readon"><button type="button" class="button" onclick="submitbutton('cancel')">
					<?php echo JText::_('Cancel') ?>
				</button></div>
			</div>
			
			<div>
				<label for="title">
					<?php echo JText::_( 'Title' ); ?>:
				</label>
				<input class="inputbox" type="text" id="title" name="title" size="50" maxlength="100" value="<?php echo $this->escape($this->article->title); ?>" />
				<input class="inputbox" type="hidden" id="alias" name="alias" value="<?php echo $this->escape($this->article->alias); ?>" />
			</div><br />
		
			<?php
			echo $this->editor->display('text', $this->article->text, '100%', '400', '70', '15');
			?>
			
		</fieldset>

		<fieldset>
			<legend><?php echo JText::_('Publishing'); ?></legend><br />

			<div>
				<label class="label-left" for="sectionid">
					<?php echo JText::_( 'Section' ); ?>:
				</label>
				<?php echo $this->lists['sectionid']; ?>
			</div>
	
			<div>
				<label class="label-left" for="catid">
					<?php echo JText::_( 'Category' ); ?>:
				</label>
				<?php echo $this->lists['catid']; ?>
			</div>
	
			<?php if ($this->user->authorize('com_content', 'publish', 'content', 'all')) : ?>
			<div>
				<label class="label-left" for="state">
					<?php echo JText::_( 'Published' ); ?>:
				</label>
				<?php echo $this->lists['state']; ?>
			</div>
			<?php endif; ?>
			
			<div>
				<label class="label-left" for="frontpage">
					<?php echo JText::_( 'Show on Front Page' ); ?>:
				</label>
				<?php echo $this->lists['frontpage']; ?>
			</div>
	
			<div>
				<label class="label-left" for="created_by_alias">
					<?php echo JText::_( 'Author Alias' ); ?>:
				</label>
				<input type="text" id="created_by_alias" name="created_by_alias" maxlength="100" value="<?php echo $this->escape($this->article->created_by_alias); ?>" class="inputbox" />
			</div>
	
			<div>
				<label class="label-left" for="publish_up">
					<?php echo JText::_( 'Start Publishing' ); ?>:
				</label>
				<?php echo JHTML::_('calendar', $publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
			</div>
	
			<div>
				<label class="label-left" for="publish_down">
					<?php echo JText::_( 'Finish Publishing' ); ?>:
				</label>
				<?php echo JHTML::_('calendar', $publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
			</div>
	
			<div>
				<label class="label-left" for="access">
					<?php echo JText::_( 'Access Level' ); ?>:
				</label>
				<?php echo $this->lists['access']; ?>
			</div>
	
			<div>
				<label class="label-left" for="ordering">
					<?php echo JText::_( 'Ordering' ); ?>:
				</label>
				<?php echo $this->lists['ordering']; ?>
			</div>
		
	</fieldset>

	<fieldset>
		<legend><?php echo JText::_('Metadata'); ?></legend><br />
		
		<div>
			<label class="label-left" for="metadesc">
				<?php echo JText::_( 'Description' ); ?>:
			</label>
			<textarea rows="5" cols="50" style="width:500px; height:120px" class="inputbox" id="metadesc" name="metadesc"><?php echo str_replace('&','&amp;',$this->article->metadesc); ?></textarea>
		</div>

		<div>
			<label class="label-left" for="metakey">
				<?php echo JText::_( 'Keywords' ); ?>:
			</label>
			<textarea rows="5" cols="50" style="width:500px; height:50px" class="inputbox" id="metakey" name="metakey"><?php echo str_replace('&','&amp;',$this->article->metakey); ?></textarea>
		</div>
		
	</fieldset>

	<input type="hidden" name="option" value="com_content" />
	<input type="hidden" name="id" value="<?php echo $this->article->id; ?>" />
	<input type="hidden" name="version" value="<?php echo $this->article->version; ?>" />
	<input type="hidden" name="created_by" value="<?php echo $this->article->created_by; ?>" />
	<input type="hidden" name="referer" value="<?php echo str_replace(array('"', '<', '>', "'"), '', @$_SERVER['HTTP_REFERER']); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="" />
	</form>
	
	<?php echo JHTML::_('behavior.keepalive'); ?>

	</div>
</div>