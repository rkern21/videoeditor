<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: default.php 698 2011-06-03 22:33:44Z nikosdion $
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

akimport('helpers.escape', true);
?>
<div id="akeeba-container" style="width:100%">
<div id="dialog" title="">
</div>

<div id="ftpdialog" title="<?php echo JText::_('CONFIG_UI_FTPBROWSER_TITLE') ?>" style="display:none;">
<p class="instructions">
	<?php echo JText::_('FTPBROWSER_LBL_INSTRUCTIONS'); ?>
</p>
<div class="error" id="ftpBrowserErrorContainer">
	<h2><?php echo JText::_('FTPBROWSER_LBL_ERROR'); ?></h2>
	<p id="ftpBrowserError"></p>
</div>
<div id="ak_crumbs"></div>
<div id="ftpBrowserFolderList"></div>
</div>

<form name="adminForm" action="index.php" method="post">
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="view" value="srprestore" />
	<input type="hidden" name="task" value="start" />
	<input type="hidden" name="id" value="<?php echo $this->id ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>

	<fieldset>
		<legend><?php echo JText::_('SRPRESTORE_INFO_LBL');?></legend>
		
		<table class="adminlist">
			<tr class="row0">
				<td><?php echo JText::_('SRPRESTORE_INFO_EXTTYPE')?></td>
				<td><?php echo JText::_($this->info->type) ?></td>
			</tr>
			<tr class="row1">
				<td><?php echo JText::_('SRPRESTORE_INFO_EXTNAME');?></td>
				<td><?php echo $this->info->name ?></td>
			</tr>
			<tr class="row0">
				<td><?php echo JText::_('SRPRESTORE_INFO_VERSIONANDDATE');?></td>
				<td><?php echo $this->info->version ?> &mdash; <?php echo $this->info->date ?></td>
			</tr>
			<tr class="row1">
				<td><?php echo JText::_('SRPRESTORE_INFO_SRPDATE');?></td>
				<td><?php echo $this->info->srpdate ?></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend><?php echo JText::_('RESTORE_LABEL_EXTRACTIONMETHOD'); ?></legend>
		
		<p>
			<?php echo JHTML::_('select.genericlist', $this->extractionmodes, 'procengine', '', 'value', 'text', $this->ftpparams['procengine']);?>
		</p>

		<p>
			<button id="backup-start" onclick="return false;"><?php echo JText::_('RESTORE_LABEL_START') ?></button>
		</p>
	</fieldset>

	<fieldset>
		<legend><?php echo JText::_('RESTORE_LABEL_FTPOPTIONS'); ?></legend>
		<table class="adminTable">
			<tr>
				<td><?php echo JText::_('CONFIG_DIRECTFTP_HOST_TITLE') ?></td>
				<td><input id="ftp_host" name="ftp_host" value="<?php echo $this->ftpparams['ftp_host']; ?>" type="text" /></td>
			</tr>
			<tr>
				<td><?php echo JText::_('CONFIG_DIRECTFTP_PORT_TITLE') ?></td>
				<td><input id="ftp_port" name="ftp_port" value="<?php echo $this->ftpparams['ftp_port']; ?>" type="text" /></td>
			</tr>
			<tr>
				<td><?php echo JText::_('CONFIG_DIRECTFTP_USER_TITLE') ?></td>
				<td><input id="ftp_user" name="ftp_user" value="<?php echo $this->ftpparams['ftp_user']; ?>" type="text" /></td>
			</tr>
			<tr>
				<td><?php echo JText::_('CONFIG_DIRECTFTP_PASSWORD_TITLE') ?></td>
				<td><input id="ftp_pass" name="ftp_pass" value="<?php echo $this->ftpparams['ftp_pass']; ?>" type="password" /></td>
			</tr>
			<tr>
				<td><?php echo JText::_('CONFIG_DIRECTFTP_INITDIR_TITLE') ?></td>
				<td>
					<input id="ftp_root" name="ftp_root" value="<?php echo $this->ftpparams['ftp_root']; ?>" type="text" />
					<input type="button" id="ftp-browse" value="<?php echo JText::_('CONFIG_UI_BROWSE') ?>" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<button id="testftp" onclick="return false;"><?php echo JText::_('CONFIG_DIRECTFTP_TEST_TITLE'); ?></button>
				</td>
			</tr>
		</table>		
	</fieldset>
</form>

<script type="text/javascript">
// Some stuff for the FTP browser...
var akeeba_ftp_init_browser = null;

var akeeba_ftpbrowser_hook = null;
var akeeba_ftpbrowser_host = null;
var akeeba_ftpbrowser_port = 21;
var akeeba_ftpbrowser_username = null;
var akeeba_ftpbrowser_password = null;
var akeeba_ftpbrowser_passive = 1;
var akeeba_ftpbrowser_ssl = 0;
var akeeba_ftpbrowser_directory = '';

akeeba.jQuery(document).ready(function($){
	// Create the dialog
	$("#dialog").dialog({
		autoOpen: false,
		closeOnEscape: false,
		height: 400,
		width: 640,
		hide: 'slide',
		modal: true,
		position: 'center',
		show: 'slide'
	});

	// Create an AJAX error trap
	akeeba_error_callback = function( message ) {		
		var dialog_element = $("#dialog");
		dialog_element.html(''); // Clear the dialog's contents
		dialog_element.dialog('option', 'title', '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TITLE')) ?>');
		$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TEXT')) ?>').appendTo(dialog_element);
		$(document.createElement('pre')).html( message ).appendTo(dialog_element);
		dialog_element.dialog('open');
	};

	// Create the DirectFTP connection test hook
	$('#testftp').click(function(event)
	{
		var button = $(document.getElementById('testftp'));
		button.attr('disabled','disabled');

		// Get the values the user has entered
		var data = new Object();
		data['host'] = $(document.getElementById('ftp_host')).val();
		data['port'] = $(document.getElementById('ftp_port')).val();
		data['user'] = $(document.getElementById('ftp_user')).val();
		data['pass'] = $(document.getElementById('ftp_pass')).val();
		data['initdir'] = $(document.getElementById('ftp_root')).val();
		data['usessl'] = false;
		data['passive'] = true;

		// Construct the query
		akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS(JURI::base().'index.php?option=com_akeeba&view=srprestore&task=ajax&ajax=testftp') ?>';
		doAjax(data, function(res){
			var button = $(document.getElementById('testftp'));
			button.attr('disabled','');

			var dialog_element = $("#dialog");
			dialog_element.html(''); // Clear the dialog's contents
			dialog_element.dialog('option', 'title', '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTFTP_TEST_DIALOG_TITLE')) ?>');
			dialog_element.removeClass('ui-state-error');
			if( res === true )
			{
				$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTFTP_TEST_OK')) ?>').appendTo(dialog_element);
			}
			else
			{
				$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTFTP_TEST_FAIL')) ?>').appendTo(dialog_element);
				$(document.createElement('p')).html( res ).appendTo( dialog_element );
			}
			dialog_element.dialog('open');
		});
	});

	$('#backup-start').click(function(event){
		document.adminForm.submit();
	});

	// Create the FTP browser directory loader hook
	akeeba_ftp_init_browser = function( )
	{
		akeeba_ftpbrowser_host = $(document.getElementById('ftp_host')).val();
		akeeba_ftpbrowser_port = $(document.getElementById('ftp_port')).val();
		akeeba_ftpbrowser_username = $(document.getElementById('ftp_user')).val();
		akeeba_ftpbrowser_password = $(document.getElementById('ftp_pass')).val();
		akeeba_ftpbrowser_passive = true;
		akeeba_ftpbrowser_ssl = false;
		akeeba_ftpbrowser_directory = $(document.getElementById('ftp_root')).val();

		var akeeba_ftp_callback = function(path) {
			var charlist = ('/').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
		    var re = new RegExp('^[' + charlist + ']+', 'g');
		    path = '/' + (path+'').replace(re, '');
			$(document.getElementById('ftp_root')).val(path);
		}
		
		akeeba_ftpbrowser_hook( akeeba_ftp_callback );
	};
	
	$('#ftp-browse').click(function(event){
		akeeba_ftp_init_browser();
	});


	// FTP browser function
	akeeba_ftpbrowser_hook = function( callback )
	{
		var ftp_dialog_element = $("#ftpdialog");
		var ftp_callback = function() {
			callback(akeeba_ftpbrowser_directory);
			ftp_dialog_element.dialog("close");
		};
		
		ftp_dialog_element.css('display','block');
		ftp_dialog_element.removeClass('ui-state-error');
		ftp_dialog_element.dialog({
			autoOpen	: false,
			'title'		: '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_FTPBROWSER_TITLE')) ?>',
			draggable	: false,
			height		: 500,
			width		: 500,
			modal		: true,
			resizable	: false,
			buttons		: {
				"OK": ftp_callback,
				"Cancel": function() {
					ftp_dialog_element.dialog("close");
				}
			}
		});

		$('#ftpBrowserErrorContainer').css('display','none');
		$('#ftpBrowserFolderList').html('');
		$('#ftpBrowserCrumbs').html('');

		ftp_dialog_element.dialog('open');
		
		// URL to load the browser
		akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS(JURI::base().'index.php?option=com_akeeba&view=ftpbrowser' ) ?>';

		if(empty(akeeba_ftpbrowser_directory)) akeeba_ftpbrowser_directory = '';
		
		var data = {
			'host'		: akeeba_ftpbrowser_host,
			'username'	: akeeba_ftpbrowser_username,
			'password'	: akeeba_ftpbrowser_password,
			'passive'	: (akeeba_ftpbrowser_passive ? 1 : 0),
			'ssl'		: (akeeba_ftpbrowser_ssl ? 1 : 0),
			'directory'	: akeeba_ftpbrowser_directory
		};

		// Ugly, ugly, UGLY hack...
		//$.data($('#ftpdialog'), 'directory', akeeba_ftpbrowser_directory);

		// Do AJAX call & Render results
		doAjax(
			data,
			function(data) {
				if(data.error != false) {
					// An error occured
					$('#ftpBrowserError').html(data.error);
					$('#ftpBrowserErrorContainer').css('display','block');
					$('#ftpBrowserFolderList').css('display','none');
					$('#ak_crumbs').css('display','none');
				} else {
					// Create the interface
					$('#ftpBrowserErrorContainer').css('display','none');

					// Display the crumbs
					if(!empty(data.breadcrumbs)) {
						$('#ak_crumbs').css('display','block');
						$('#ak_crumbs').html('');
						var relativePath = '/';

						akeeba_ftpbrowser_addcrumb(akeeba_translations['UI-ROOT'], '/', callback);
						$('#ak_crumbs').append(' &bull; ');
													
						$.each(data.breadcrumbs, function(i, crumb) {
							relativePath += '/'+crumb;

							akeeba_ftpbrowser_addcrumb(crumb, relativePath, callback);

							if(i < (data.breadcrumbs.length-1) ) $('#ak_crumbs').append(' &bull; ');
						});
					} else {
						$('#ftpBrowserCrumbs').css('display','none');
					}

					// Display the list of directories
					if(!empty(data.list)) {
						$('#ftpBrowserFolderList').css('display','block');
						//akeeba_ftpbrowser_directory = $.data($('#ftpdialog'), 'directory');
						//if(empty(akeeba_ftpbrowser_directory)) akeeba_ftpbrowser_directory = '';
						
						$.each(data.list, function(i, item) {
							akeeba_ftpbrowser_create_link(akeeba_ftpbrowser_directory+'/'+item, item, $('#ftpBrowserFolderList'), callback );
						});							
					} else {
						$('#ftpBrowserFolderList').css('display','none');
					}
				}
			},
			function(message) {
				$('#ftpBrowserError').html(message);
				$('#ftpBrowserErrorContainer').css('display','block');
				$('#ftpBrowserFolderList').css('display','none');
				$('#ftpBrowserCrumbs').css('display','none');
			},
			false
		);
	}

	/**
	 * Creates a directory link for the FTP browser UI
	 */
	function akeeba_ftpbrowser_create_link(path, label, container, callback)
	{
		var wrapper = $(document.createElement('div'))
			.addClass('folder-container');
		var innerWrapper = $(document.createElement('span')).addClass('folder-name').appendTo(wrapper);
		var myElement = $(document.createElement('span'));
		myElement.addClass('folder-name');
		myElement.html(label);
		myElement.click(function(){
			akeeba_ftpbrowser_directory = path;
			akeeba_ftpbrowser_hook(callback);
		});
		myElement.appendTo(innerWrapper);
		wrapper.appendTo($(container));
	}

	/**
	 * Adds a breadcrumb to the FTP browser
	 */
	function akeeba_ftpbrowser_addcrumb(crumb, relativePath, callback)
	{
		$(document.createElement('span'))
		.html(crumb)
		.attr('class', 'ui-state-default')
		.hover(
			function(){$(this).addClass('ui-state-hover');}, 
			function(){$(this).removeClass('ui-state-hover');}
		)
		.click(function(){
			akeeba_ftpbrowser_directory = relativePath;
			akeeba_ftpbrowser_hook(callback);
		})
		.appendTo('#ak_crumbs');
	}
	
});

</script>
</div>