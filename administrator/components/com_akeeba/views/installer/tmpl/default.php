<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<div id="akeeba-container" style="width:100%">
<?php if ($this->showMessage) : ?>
<?php echo $this->loadTemplate('message'); ?>
<?php endif; ?>
<?php echo $this->loadTemplate('form'); ?>
</div>