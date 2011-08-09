<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if(version_compare(JVERSION, '1.6.0', 'ge')): ?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.install_package.value == ""){
			alert("<?php echo JText::_('COM_INSTALLER_MSG_INSTALL_PLEASE_SELECT_A_PACKAGE', true); ?>");
		} else {
			form.installtype.value = 'upload';
			form.submit();
		}
	}

	Joomla.submitbutton3 = function(pressbutton) {
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.install_directory.value == ""){
			alert("<?php echo JText::_('COM_INSTALLER_MSG_INSTALL_PLEASE_SELECT_A_DIRECTORY', true); ?>");
		} else {
			form.installtype.value = 'folder';
			form.submit();
		}
	}

	Joomla.submitbutton4 = function(pressbutton) {
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.install_url.value == "" || form.install_url.value == "http://"){
			alert("<?php echo JText::_('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL', true); ?>");
		} else {
			form.installtype.value = 'url';
			form.submit();
		}
	}
	
	function showWhatsthis() {
		if($('whatsthis').getStyle('display') == 'block') {
			$('whatsthis').setStyle('display','none');
		} else {
			$('whatsthis').setStyle('display','block');
		}
	}
</script>

<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_akeeba&view=installer');?>" method="post" name="adminForm" id="adminForm">

	<?php if ($this->ftp) : ?>
		<?php echo $this->loadTemplate('ftp'); ?>
	<?php endif; ?>
	<div class="width-70 fltlft">
		<fieldset class="uploadform">
			<legend><?php echo JText::_('COM_INSTALLER_UPLOAD_PACKAGE_FILE'); ?></legend>
			<label for="install_package"><?php echo JText::_('COM_INSTALLER_PACKAGE_FILE'); ?></label>
			<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
			<input class="button" type="button" value="<?php echo JText::_('COM_INSTALLER_UPLOAD_AND_INSTALL'); ?>" onclick="Joomla.submitbutton()" />
		</fieldset>
		<div class="clr"></div>
		<fieldset class="uploadform">
			<legend><?php echo JText::_('COM_INSTALLER_INSTALL_FROM_DIRECTORY'); ?></legend>
			<label for="install_directory"><?php echo JText::_('COM_INSTALLER_INSTALL_DIRECTORY'); ?></label>
			<input type="text" id="install_directory" name="install_directory" class="input_box" size="70" value="<?php echo $this->state->get('install.directory'); ?>" />			<input type="button" class="button" value="<?php echo JText::_('COM_INSTALLER_INSTALL_BUTTON'); ?>" onclick="Joomla.submitbutton3()" />
		</fieldset>
		<div class="clr"></div>
		<fieldset class="uploadform">
			<legend><?php echo JText::_('COM_INSTALLER_INSTALL_FROM_URL'); ?></legend>
			<label for="install_url"><?php echo JText::_('COM_INSTALLER_INSTALL_URL'); ?></label>
			<input type="text" id="install_url" name="install_url" class="input_box" size="70" value="http://" />
			<input type="button" class="button" value="<?php echo JText::_('COM_INSTALLER_INSTALL_BUTTON'); ?>" onclick="Joomla.submitbutton4()" />
		</fieldset>
		<input type="hidden" name="type" value="" />
		<input type="hidden" name="installtype" value="upload" />
		<input type="hidden" name="task" value="doInstall" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<?php else: ?>

<script language="javascript" type="text/javascript">
<!--
	function submitbutton3(pressbutton) {
		var form = document.adminForm;

		// do field validation
		if (form.install_directory.value == ""){
			alert( "<?php echo JText::_( 'Please select a directory', true ); ?>" );
		} else {
			form.installtype.value = 'folder';
			form.submit();
		}
	}

	function submitbutton4(pressbutton) {
		var form = document.adminForm;

		// do field validation
		if (form.install_url.value == "" || form.install_url.value == "http://"){
			alert( "<?php echo JText::_( 'Please enter a URL', true ); ?>" );
		} else {
			form.installtype.value = 'url';
			form.submit();
		}
	}
	
	function showWhatsthis() {
		if($('whatsthis').getStyle('display') == 'block') {
			$('whatsthis').setStyle('display','none');
		} else {
			$('whatsthis').setStyle('display','block');
		}
	}
//-->
</script>

<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm">

	<?php if ($this->ftp) : ?>
		<?php echo $this->loadTemplate('ftp'); ?>
	<?php endif; ?>

	<table class="adminform">
	<tr>
		<th colspan="2"><?php echo JText::_( 'Upload Package File' ); ?></th>
	</tr>
	<tr>
		<td width="120">
			<label for="install_package"><?php echo JText::_( 'Package File' ); ?>:</label>
		</td>
		<td>
			<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
			<input class="button" type="button" value="<?php echo JText::_( 'Upload File' ); ?> &amp; <?php echo JText::_( 'Install' ); ?>" onclick="submitbutton()" />
		</td>
	</tr>
	</table>

	<table class="adminform">
	<tr>
		<th colspan="2"><?php echo JText::_( 'Install from directory' ); ?></th>
	</tr>
	<tr>
		<td width="120">
			<label for="install_directory"><?php echo JText::_( 'Install directory' ); ?>:</label>
		</td>
		<td>
			<input type="text" id="install_directory" name="install_directory" class="input_box" size="70" value="<?php echo $this->state->get('install.directory'); ?>" />
			<input type="button" class="button" value="<?php echo JText::_( 'Install' ); ?>" onclick="submitbutton3()" />
		</td>
	</tr>
	</table>

	<table class="adminform">
	<tr>
		<th colspan="2"><?php echo JText::_( 'Install from URL' ); ?></th>
	</tr>
	<tr>
		<td width="120">
			<label for="install_url"><?php echo JText::_( 'Install URL' ); ?>:</label>
		</td>
		<td>
			<input type="text" id="install_url" name="install_url" class="input_box" size="70" value="http://" />
			<input type="button" class="button" value="<?php echo JText::_( 'Install' ); ?>" onclick="submitbutton4()" />
		</td>
	</tr>
	</table>

	<input type="hidden" name="type" value="" />
	<input type="hidden" name="installtype" value="upload" />
	<input type="hidden" name="task" value="doInstall" />
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="view" value="installer" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<?php endif; ?>

<div class="width-70 fltlft">
<p>
	<img src="components/com_akeeba/assets/images/akeeba-16.png" align="bottom" />
	<em>
		<?php echo JText::_('INSTALLER_ENHANCEDBY');?> <a href="https://www.AkeebaBackup.com">Akeeba Backup</a>
	</em>
	&bull;
	<a href="index.php?option=com_installer&skipsrp=1"><?php echo JText::_('INSTALLER_SWITCHTOREGULAR') ?></a>
	&bull;
	<a href="javascript:showWhatsthis()"><?php echo JText::_('INSTALLER_WHATSTHIS') ?></a>
</p>

<fieldset id="whatsthis" style="display:none">
	<legend><?php echo JText::_('INSTALLER_WHATSTHIS') ?></legend>
	<?php echo JText::_('INSTALLER_WHATSTHIS_TEXT') ?>
</fieldset>

</div>