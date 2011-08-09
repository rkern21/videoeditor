<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: view.tab.php 1379 2011-01-29 15:21:36Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : tab view
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBView_tab {
	function edittab( &$row, $option, &$lists, $tabid, &$paramsEditorHtml ) {
		global $_CB_framework, $task,$_CB_database, $_PLUGINS;

		_CBsecureAboveForm('edittab');
		outputCbTemplate( 2 );
		outputCbJs( 2 );
		initToolTip( 2 );
		$_CB_framework->outputCbJQuery( '' );

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-tabs', CBTxt::T('Community Builder Tab') . ": <small>" . ( $row->tabid ? CBTxt::T('Edit') . ' [ '. htmlspecialchars( getLangDefinition( $row->title ) ) .' ]' : CBTxt::T('New') ) . '</small>' ) );

		if ( $row->tabid && ( ! $row->enabled ) ) {
			echo '<div class="cbWarning">' . CBTxt::T('Tab is not published') . '</div>' . "\n";
		}

		$editorSave_description		=	$_CB_framework->saveCmsEditorJS( 'description' );
		ob_start();
?>
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'showTab') {
		        <?php echo $editorSave_description; ?>
				submitform( pressbutton );
				return;
			}
			var r = new RegExp("[^0-9A-Za-z]", "i");

			// do field validation
			if (jQuery.trim(form.title.value) == "") {
				alert('<?php echo addslashes( CBTxt::T('You must provide a title.') ); ?>');
			} else {
		        <?php echo $editorSave_description; ?>
				submitform( pressbutton );
			}
		}
<?php
		$js			=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->document->addHeadScriptDeclaration( $js );
?>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>

	<form action="<?php echo $_CB_framework->backendUrl( 'index.php?option=com_comprofiler&task=saveTab' ); ?>" method="POST" name="adminForm">
	<table cellspacing="0" cellpadding="0" width="100%">
	<tr valign="top">
		<td width="60%" valign="top">
			<table class="adminform">
			<tr>
				<th colspan="3">
				<?php echo CBTxt::T('Tab Details'); ?>
				</th>
			</tr>
			<tr>
				<td width="20%"><?php echo CBTxt::T('Title'); ?>:</td>
				<td width="35%"><input type="text" name="title" class="inputbox" size="40" value="<?php echo htmlspecialchars( $row->title ); ?>" /></td>
				<td width="45%"><?php echo CBTxt::T('Title as will appear on tab.'); ?></td>
			</tr>
			<tr>
				<td colspan="3"><?php echo CBTxt::T('Description: This description appears only on user edit, not on profile (For profile text, use delimiter fields)'); ?>:</td>
			</tr>
			<tr>
				<td colspan="3" align="left"><?php echo $_CB_framework->displayCmsEditor( 'description', $row->description, 600, 200, 50, 10 );
				// <textarea name="description" class="inputbox" cols="40" rows="10">< ?php echo htmlspecialchars( $row->description ); ? ></textarea>
				?></td>
			</tr>
			<tr>
				<td><?php echo CBTxt::T('Publish'); ?>:</td>
				<td><?php echo $lists['enabled']; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo CBTxt::T('Profile ordering'); ?>:</td>
				<td><?php echo $lists['ordering']; ?></td>
				<td><?php echo CBTxt::T('Tabs and fields on profile are ordered as follows:'); ?><ol>
				    <li><?php echo CBTxt::T('position of tab on user profile (top-down, left-right)'); ?></li>
				    <li><?php echo CBTxt::T('This ordering of tab on position of user profile'); ?></li>
				    <li><?php echo CBTxt::T('ordering of field within tab position of user profile.'); ?></li></ol>
				</td>
			</tr>
			<tr>
				<td><?php echo CBTxt::T('Registration ordering'); ?><br /><?php echo CBTxt::T('(default value: 10)'); ?>:</td>
				<td><input type="text" name="ordering_register" class="inputbox" size="40" value="<?php echo $row->ordering_register; ?>" /></td>
				<td><?php echo CBTxt::T('Tabs and fields on registration are ordered as follows:'); ?><ol>
					<li><?php echo CBTxt::T('This registration ordering of tab'); ?></li>
				    <li><?php echo CBTxt::T('position of tab on user profile (top-down, left-right)'); ?></li>
				    <li><?php echo CBTxt::T('ordering of tab on position of user profile'); ?></li>
				    <li><?php echo CBTxt::T('ordering of field within tab position of user profile.'); ?></li></ol>
				</td>
			</tr>
			<tr>
				<td><?php echo CBTxt::T('Position'); ?>:</td>
				<td><?php echo $lists['position']; ?></td>
				<td><?php echo CBTxt::T('Position on profile and ordering on registration.'); ?></td>
			</tr>
			<tr>
				<td><?php echo CBTxt::T('Display type'); ?>:</td>
				<td><?php echo $lists['displaytype']; ?></td>
				<td><?php echo CBTxt::T('In which way the content of this tab will be displayed on the profile.'); ?></td>
			</tr>
			<tr>
				<td><?php echo CBTxt::T('User Group to allow access to'); ?>:</td>
				<td><?php echo $lists['useraccessgroup']; ?></td>
				<td><?php echo CBTxt::T('All groups above that level will also have access to the list.'); ?></td>
			</tr>
			</table>
		</td>
		<td width="40%">
			<table class="adminform">
			<tr>
				<th colspan="2">
				<?php echo CBTxt::T('Parameters'); ?>
				</th>
			</tr>
			<tr>
				<td>
				<?php
				if ( $row->tabid && $row->pluginid > 0 ) {
					$plugin= new moscomprofilerPlugin($_CB_database);
					$plugin->load( (int) $row->pluginid);

					// fail if checked out not by 'me'
					if ($plugin->checked_out && $plugin->checked_out <> $_CB_framework->myId() ) {
						echo "<script type=\"text/javascript\">alert('" . addslashes( sprintf(CBTxt::T('The plugin %s is currently being edited by another administrator'), $plugin->name) ) . "'); document.location.href='" . $_CB_framework->backendUrl( "index.php?option=$option" ) . "'</script>\n";
						exit(0);
					}

					// get params values
					if ( $plugin->type !== "language" && $plugin->id ) {
						$_PLUGINS->loadPluginGroup( $plugin->type, array( (int) $plugin->id ), 0 );
					}

					$element	=	$_PLUGINS->loadPluginXML( 'editTab', $row->pluginclass, $plugin->id );
/*
					$xmlfile = $_CB_framework->getCfg('absolute_path') . '/components/com_comprofiler/plugin/' .$plugin->type . '/'.$plugin->folder . '/' . $plugin->element .'.xml';
					// $params = new cbParameters( $row->params, $xmlfile );
					cbimport('cb.xml.simplexml');
					$xmlDoc = new CBSimpleXML();
					if ( $xmlDoc->loadFile( $xmlfile ) ) {
						$element =& $xmlDoc->document;
					} else {
						$element = null;
					}
*/
					$pluginParams	=	new cbParamsBase( $plugin->params );

					$params			=	new cbParamsEditorController( $row->params, $element, $element, $plugin, $row->tabid );
					$params->setPluginParams( $pluginParams );
					$options		=	array( 'option' => $option, 'task' => $task, 'pluginid' => $row->pluginid, 'tabid' => $row->tabid );
					$params->setOptions( $options );

					echo $params->draw( 'params', 'tabs', 'tab', 'class', $row->pluginclass );
				} else {
					echo '<em>' . CBTxt::T('No Parameters') . '</em>';
				}

		if ( $paramsEditorHtml ) {
			foreach ( $paramsEditorHtml as $paramsEditorHtmlBlock ) {
?>
					<table class="adminform" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<th colspan="2">
								<?php echo $paramsEditorHtmlBlock['title']; ?>
							</th>
						</tr>
						<tr>
							<td>
								<?php echo $paramsEditorHtmlBlock['content']; ?>
							</td>
						</tr>
					</table>
<?php
			}
		}
?>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
  <input type="hidden" name="tabid" value="<?php echo $row->tabid; ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="" />
  <?php
	echo cbGetSpoofInputTag( 'tab' );
  ?>
</form>
<?php }

}	// class CBView_tab

?>