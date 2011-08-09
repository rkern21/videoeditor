<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: view.plugin.php 1143 2010-07-05 17:03:54Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : plugin view
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBView_plugin {
	/**
	* Writes the edit form for new and existing module
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.
	* @param moscomprofilerPlugin $row
	* @param array of string $lists  An array of select lists
	* @param cbParamsEditor $params
	* @param string $option of component.
	*
	*/
	function editPlugin( &$row, &$lists, &$params, $options ) {
		global $_CB_framework, $_PLUGINS;

		_CBsecureAboveForm('editPlugin');
		outputCbTemplate( 2 );
		outputCbJs( 2 );
	    initToolTip( 2 );

	    $nameA = '';
		$filesInstalled = true;
		if ( $row->id ) {
			$nameA = '[ '. htmlspecialchars( getLangDefinition( $row->name ) ) .' ]';

			$xmlfile	=	$_PLUGINS->getPluginXmlPath( $row );
			$filesInstalled = file_exists($xmlfile);
		}

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-plugins', CBTxt::T('Community Builder Plugin') . ": <small>" . ( $row->id ? CBTxt::T('Edit') . ' ' . $nameA : CBTxt::T('New') ) . '</small>' ) );

		if ( $row->id && ( ! $row->published ) ) {
			echo '<div class="cbWarning">' . CBTxt::T('Plugin is not published') . '</div>' . "\n";
		}
		?>
		<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm">
		<table cellspacing="0" cellpadding="0" width="100%">
		<tr valign="top">
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo CBTxt::T('Plugin Common Settings'); ?>
					</th>
				</tr>
				<tr>
					<td width="100" align="left">
					<?php echo CBTxt::T('Name'); ?>:
					</td>
					<td>
					<input class="text_area" type="text" name="name" size="35" value="<?php echo htmlspecialchars( $row->name ); /* ideally a translation of this field should be given and this field be not editable */ ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo CBTxt::T('Plugin Order'); ?>:
					</td>
					<td>
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo CBTxt::T('Access Level'); ?>:
					</td>
					<td>
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo CBTxt::T('Published'); ?>:
					</td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" colspan="2">&nbsp;

					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo CBTxt::T('Description'); ?>:
					</td>
					<td>
					<?php echo CBTxt::T($row->description); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo CBTxt::T('Folder / File'); ?>:
					</td>
					<td>
					<?php echo $lists['type'] . "/" . htmlspecialchars( $row->element ) . ".php"; ?>
					</td>
				</tr>
				</table>
<?php				if ( $filesInstalled && $row->id ) {
						$settingsHtml = $params->draw( 'params', 'views', 'view', 'type', 'settings' );
						if ( $settingsHtml ) {	?>
				<table class="adminform">
				<tr>
					<th>
					<?php echo htmlspecialchars( $row->name ); ?> <?php echo CBTxt::T('Specific Plugin Settings'); ?>
					</th>
				</tr>
				<tr>
					<td width="100%" align="left"><?php echo $settingsHtml; ?></td>
				</tr>
				</table>
<?php					}
					}   ?>
			</td>
			<td width="40%">
				<table class="adminform" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<th colspan="2">
					<?php echo CBTxt::T('Parameters'); ?>
					</th>
				</tr>
				<tr>
					<td>
					<?php
					if ( $filesInstalled && $row->id ) {
						echo $params->draw();
					} elseif ( !$filesInstalled ) {
						echo '<strong><font style="color:red;">' . CBTxt::T('Plugin not installed') . '</font></strong><br />';
						echo $params->draw();
					} else {
						echo '<em>' . CBTxt::T('No Parameters') . '</em>';
					}
					?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $options['option']; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="editPlugin" />
		<?php
	echo cbGetSpoofInputTag( 'plugin' );
		?>
		</form>
		<?php
	}

}	// class CBView_plugin

?>