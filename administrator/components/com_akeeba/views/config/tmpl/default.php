<?php
/**
 * @package Akeeba
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: default.php 681 2011-06-01 08:50:04Z nikosdion $
 * @since 3.0
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

?>
<form name="adminForm" method="post" action="index.php" >

<!-- jQuery & jQuery UI detection. Also shows a big, fat warning if they're missing -->
<div id="nojquerywarning" style="margin: 1em; padding: 1em; background: #ffff00; border: thick solid red; color: black; font-size: 14pt;">
	<h1 style="margin: 1em 0; color: red; font-size: 22pt;"><?php echo JText::_('AKEEBA_CPANEL_WARN_ERROR') ?></h1>
	<p><?php echo JText::_('AKEEBA_CPANEL_WARN_JQ_L1'); ?></p>
	<p><?php echo JText::_('AKEEBA_CPANEL_WARN_JQ_L2'); ?></p>
</div>
<script type="text/javascript">
	if(typeof akeeba.jQuery == 'function')
	{
		if(typeof akeeba.jQuery.ui == 'object')
		{
			akeeba.jQuery('#nojquerywarning').css('display','none');
		}
	}
</script>

<div id="dialog" title="<?php echo JText::_('CONFIG_UI_BROWSER_TITLE') ?>">
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

<fieldset>
	<legend>
		<span class="ui-icon ui-icon-info" style="display:inline-block;">&nbsp;</span>
		<?php echo JText::_('CONFIG_LABEL_INFORMATION') ?>
	</legend>
	
	<?php if($this->securesettings == 1): ?>
	<div class="akeeba-settings-secured">
		<?php echo JText::_('CONFIG_UI_SETTINGS_SECURED'); ?>
	</div>
	<div style="clear:both;"></div>
	<?php elseif($this->securesettings == 0): ?>
	<div class="akeeba-settings-notsecured">
		<?php echo JText::_('CONFIG_UI_SETTINGS_NOTSECURED'); ?>
	</div>
	<div style="clear:both;"></div>
	<?php endif; ?>
	
	<div>
		<b><?php echo JText::_('CPANEL_PROFILE_TITLE'); ?></b>: #<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
	</div>

	<p>
		<?php echo JText::_('CONFIG_WHERE_ARE_THE_FILTERS'); ?>
	</p>
</fieldset>

<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option') ?>" />
<input type="hidden" name="view" value="config" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>

<!-- This div contains dynamically generated user interface elements -->
<div id="akeebagui">
</div>

</form>
<script type="text/javascript">
	// Callback routine to close the browser dialog
	var akeeba_browser_callback = null;

	// Hook for DirectFTP connection test
	var directftp_test_connection = null;
	
	var directsftp_test_connection = null;

	<?php if(defined('AKEEBA_PRO')): ?>
	// Hook for Upload to Remote FTP connection test
	var postprocftp_test_connection = null;
	<?php endif; ?>

	// Some stuff for the FTP browser...
	var akeeba_directftp_init_browser = null;
	var akeeba_postprocftp_init_browser = null;

	var akeeba_ftpbrowser_hook = null;

	var akeeba_ftpbrowser_host = null;
	var akeeba_ftpbrowser_port = 21;
	var akeeba_ftpbrowser_username = null;
	var akeeba_ftpbrowser_password = null;
	var akeeba_ftpbrowser_passive = 1;
	var akeeba_ftpbrowser_ssl = 0;
	var akeeba_ftpbrowser_directory = '';

	akeeba.jQuery(document).ready(function($){
		// Push some translations
		akeeba_translations['UI-BROWSE'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_BROWSE')) ?>';
		akeeba_translations['UI-CONFIG'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_CONFIG')) ?>';

		// Load the configuration UI data
		akeeba_ui_theme_root = '<?php echo $this->mediadir ?>';
		var data = JSON.parse("<?php echo $this->json; ?>");
		parse_config_data(data);

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
			//dialog_element.addClass('ui-state-error');
			dialog_element.dialog('option', 'title', '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TITLE')) ?>');
			$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TEXT')) ?>').appendTo(dialog_element);
			$(document.createElement('pre')).html( message ).appendTo(dialog_element);
			dialog_element.dialog('open');
		};

		// Create the DirectFTP connection test hook
		directftp_test_connection = function()
		{
			var button = $(document.getElementById('engine.archiver.directftp.ftp_test'));
			button.addClass('ui-state-disabled');
			button.removeClass('ui-state-default');

			// Get the values the user has entered
			var data = new Object();
			data['host'] = $(document.getElementById('var[engine.archiver.directftp.host]')).val();
			data['port'] = $(document.getElementById('var[engine.archiver.directftp.port]')).val();
			data['user'] = $(document.getElementById('var[engine.archiver.directftp.user]')).val();
			data['pass'] = $(document.getElementById('var[engine.archiver.directftp.pass]')).val();
			data['initdir'] = $(document.getElementById('var[engine.archiver.directftp.initial_directory]')).val();
			data['usessl'] = $(document.getElementById('var[engine.archiver.directftp.ftps]')).is(':checked');
			data['passive'] = $(document.getElementById('var[engine.archiver.directftp.passive_mode]')).is(':checked');

			// Construct the query
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS('index.php?option=com_akeeba&view=config&task=testftp') ?>';
			doAjax(data, function(res){
				var button = $(document.getElementById('engine.archiver.directftp.ftp_test'));
				button.removeClass('ui-state-disabled');
				button.addClass('ui-state-default');

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
		}
		
		// Create the DirectSFTP connection test hook
		directsftp_test_connection = function()
		{
			var button = $(document.getElementById('engine.archiver.directsftp.sftp_test'));
			button.addClass('ui-state-disabled');
			button.removeClass('ui-state-default');

			// Get the values the user has entered
			var data = new Object();
			data['host'] = $(document.getElementById('var[engine.archiver.directsftp.host]')).val();
			data['port'] = $(document.getElementById('var[engine.archiver.directsftp.port]')).val();
			data['user'] = $(document.getElementById('var[engine.archiver.directsftp.user]')).val();
			data['pass'] = $(document.getElementById('var[engine.archiver.directsftp.pass]')).val();
			data['initdir'] = $(document.getElementById('var[engine.archiver.directsftp.initial_directory]')).val();

			// Construct the query
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS('index.php?option=com_akeeba&view=config&task=testsftp') ?>';
			var dialog_element = $("#dialog");
			dialog_element.html(''); // Clear the dialog's contents
			dialog_element.dialog('option', 'title', '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTSFTP_TEST_DIALOG_TITLE')) ?>');
			dialog_element.removeClass('ui-state-error');
			$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTSFTP_PLEASE_WAIT')) ?>').appendTo(dialog_element);
			dialog_element.dialog('open');
			
			doAjax(data, function(res){
				var button = $(document.getElementById('engine.archiver.directsftp.sftp_test'));
				button.removeClass('ui-state-disabled');
				button.addClass('ui-state-default');

				dialog_element.dialog('close');
				dialog_element.html(''); // Clear the dialog's contents
				if( res === true )
				{
					$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTSFTP_TEST_OK')) ?>').appendTo(dialog_element);
				}
				else
				{
					$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTSFTP_TEST_FAIL')) ?>').appendTo(dialog_element);
					$(document.createElement('p')).html( res ).appendTo( dialog_element );
				}
				dialog_element.dialog('open');
			});
		}

<?php if(defined('AKEEBA_PRO')): ?>
		// Create the FTP upload post-proc engine test hook
		postprocftp_test_connection = function()
		{
			var button = $(document.getElementById('engine.postproc.ftp.ftp_test'));
			button.addClass('ui-state-disabled');
			button.removeClass('ui-state-default');

			// Get the values the user has entered
			var data = new Object();
			data['host'] = $(document.getElementById('var[engine.postproc.ftp.host]')).val();
			data['port'] = $(document.getElementById('var[engine.postproc.ftp.port]')).val();
			data['user'] = $(document.getElementById('var[engine.postproc.ftp.user]')).val();
			data['pass'] = $(document.getElementById('var[engine.postproc.ftp.pass]')).val();
			data['initdir'] = $(document.getElementById('var[engine.postproc.ftp.initial_directory]')).val();
			data['usessl'] = $(document.getElementById('var[engine.postproc.ftp.ftps]')).is(':checked');
			data['passive'] = $(document.getElementById('var[engine.postproc.ftp.passive_mode]')).is(':checked');

			// Construct the query
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS('index.php?option=com_akeeba&view=config&task=testftp') ?>';
			doAjax(data, function(res){
				var button = $(document.getElementById('engine.postproc.ftp.ftp_test'));
				button.removeClass('ui-state-disabled');
				button.addClass('ui-state-default');

				var dialog_element = $("#dialog");
				dialog_element.html(''); // Clear the dialog's contents
				dialog_element.dialog('option', 'title', '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCFTP_TEST_DIALOG_TITLE')) ?>');
				dialog_element.removeClass('ui-state-error');
				if( res === true )
				{
					$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCFTP_TEST_OK')) ?>').appendTo(dialog_element);
				}
				else
				{
					$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCFTP_TEST_FAIL')) ?>').appendTo(dialog_element);
					$(document.createElement('p')).html( res ).appendTo( dialog_element );
				}
				dialog_element.dialog('open');
			});
		}

		// Create the FTP Post-Processing browser directory loader hook
		akeeba_postprocftp_init_browser = function( )
		{
			akeeba_ftpbrowser_host = $(document.getElementById('var[engine.postproc.ftp.host]')).val();
			akeeba_ftpbrowser_port = $(document.getElementById('var[engine.postproc.ftp.port]')).val();
			akeeba_ftpbrowser_username = $(document.getElementById('var[engine.postproc.ftp.user]')).val();
			akeeba_ftpbrowser_password = $(document.getElementById('var[engine.postproc.ftp.pass]')).val();
			akeeba_ftpbrowser_passive = $(document.getElementById('var[engine.postproc.ftp.passive_mode]')).is(':checked');
			akeeba_ftpbrowser_ssl = $(document.getElementById('var[engine.postproc.ftp.ftps]')).is(':checked');
			akeeba_ftpbrowser_directory = $(document.getElementById('var[engine.postproc.ftp.initial_directory]')).val();

			var akeeba_postprocftp_callback = function(path) {
				var charlist = ('/').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
			    var re = new RegExp('^[' + charlist + ']+', 'g');
			    path = '/' + (path+'').replace(re, '');
				$(document.getElementById('var[engine.postproc.ftp.initial_directory]')).val(path);
			}
			
			akeeba_ftpbrowser_hook( akeeba_postprocftp_callback );
		}
		
<?php endif; ?>

		// Create the browser hook
		akeeba_browser_hook = function( folder, element )
		{
			var dialog_element = $("#dialog");
			dialog_element.html(''); // Clear the dialog's contents
			dialog_element.removeClass('ui-state-error');
			dialog_element.dialog('option', 'title', '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_BROWSER_TITLE')) ?>');

			// URL to load the browser
			var browserSrc = '<?php echo AkeebaHelperEscape::escapeJS(JURI::base().'index.php?option=com_akeeba&view=browser&processfolder=1&folder=') ?>';
			browserSrc = browserSrc + encodeURIComponent(folder);

			// IFrame holding the browser
			var akeeba_browser_iframe = $(document.createElement('iframe')).attr({
				'id':			'akeeba_browser_iframe',
				width:			'100%',
				height:			'98%',
				marginWidth		: 0,
				marginHeight	: 0,
				frameBorder		: 0,
				scrolling		: 'auto',
				src				: browserSrc
			});
			akeeba_browser_iframe.appendTo( dialog_element );

			// Close dialog callback (user confirmed the new folder)
			akeeba_browser_callback = function( myFolder ) {
				$(element).val( myFolder );
				dialog_element.dialog('close');
			};

			dialog_element.dialog('open');
		};

		// Create the DirectFTP browser directory loader hook
		akeeba_directftp_init_browser = function( )
		{
			akeeba_ftpbrowser_host = $(document.getElementById('var[engine.archiver.directftp.host]')).val();
			akeeba_ftpbrowser_port = $(document.getElementById('var[engine.archiver.directftp.port]')).val();
			akeeba_ftpbrowser_username = $(document.getElementById('var[engine.archiver.directftp.user]')).val();
			akeeba_ftpbrowser_password = $(document.getElementById('var[engine.archiver.directftp.pass]')).val();
			akeeba_ftpbrowser_passive = $(document.getElementById('var[engine.archiver.directftp.passive_mode]')).is(':checked');
			akeeba_ftpbrowser_ssl = $(document.getElementById('var[engine.archiver.directftp.ftps]')).is(':checked');
			akeeba_ftpbrowser_directory = $(document.getElementById('var[engine.archiver.directftp.initial_directory]')).val();

			var akeeba_directftp_callback = function(path) {
				var charlist = ('/').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
			    var re = new RegExp('^[' + charlist + ']+', 'g');
			    path = '/' + (path+'').replace(re, '');
				$(document.getElementById('var[engine.archiver.directftp.initial_directory]')).val(path);
			}
			
			akeeba_ftpbrowser_hook( akeeba_directftp_callback );
		}

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
														
							$.each(data.breadcrumbs, function(i, crumb) {
								$('#ak_crumbs').append(' &bull; ');
								relativePath += '/'+crumb;

								akeeba_ftpbrowser_addcrumb(crumb, relativePath, callback);
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
			myElement.text(label);
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