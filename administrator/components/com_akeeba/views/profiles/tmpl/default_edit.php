<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: default_edit.php 681 2011-06-01 08:50:04Z nikosdion $
 * @since 1.3
 */

defined('_JEXEC') or die('Restricted access');

// Include tooltip support
jimport('joomla.html.html');
JHTML::_('behavior.tooltip');

if( empty($this->profile) )
{
	$id = 0;
	$description = '';
}
else
{
	$id = $this->profile->id;
	$description = $this->profile->description;
}
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_akeeba" />
	<input type="hidden" name="view" value="profiles" />
	<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	<table>
		<tr>
			<td><?php echo JHTML::_('tooltip', JText::_('PROFILE_LABEL_DESCRIPTION_TOOLTIP'), '', '', JText::_('PROFILE_LABEL_DESCRIPTION')) ?></td>
			<td><input type="text" name="description" size="60" id="description" value="<?php echo $description; ?>" /></td>
		</tr>
	</table>
</form>