<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2006-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: restorepoint.php 681 2011-06-01 08:50:04Z nikosdion $
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

JHTML::_('behavior.mootools');
JHTML::_('behavior.calendar');

// Filesize formatting function by eregon at msn dot com
// Published at: http://www.php.net/manual/en/function.number-format.php
function format_filesize($number, $decimals = 2, $force_unit = false, $dec_char = '.', $thousands_char = '')
{
	if($number <= 0) return '-';

	$units = array('b', 'Kb', 'Mb', 'Gb', 'Tb');
	if($force_unit === false)
	$unit = floor(log($number, 2) / 10);
	else
	$unit = $force_unit;
	if($unit == 0)
	$decimals = 0;
	return number_format($number / pow(1024, $unit), $decimals, $dec_char, $thousands_char).' '.$units[$unit];
}

// Load a mapping of backup types to textual representation
$scripting = AEUtilScripting::loadScripting();
$backup_types = array();
foreach($scripting['scripts'] as $key => $data)
{
	$backup_types[$key] = JText::_($data['text']);
}

?>
<div id="jpcontainer">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" id="option" value="com_akeeba" />
	<input type="hidden" name="view" id="view" value="buadmin" />
	<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>" />
	<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>" />	
	<?php echo JHTML::_( 'form.token' ); ?>
<table class="adminlist">
	<thead>
		<tr>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->list ) + 1; ?>);" /></th>

			<th>
				<?php echo JHTML::_('grid.sort', 'STATS_LABEL_DESCRIPTION', 'description', $this->lists->order_Dir, $this->lists->order); ?>
			</th>
			<th width="190px">
				<?php echo JHTML::_('grid.sort', 'STATS_LABEL_STARTSRP', 'backupstart', $this->lists->order_Dir, $this->lists->order); ?>
			</th>
			<th width="90px"><?php echo JText::_('STATS_LABEL_STATUS'); ?></th>
			<th width="90px"><?php echo JText::_('STATS_LABEL_SIZE'); ?></th>
			<th><?php echo JText::_('STATS_LABEL_MANAGEANDDL'); ?></th>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="text" name="description" id="description"
					value="<?php echo $this->escape($this->lists->fltDescription) ?>"
					class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit(); return false;"><?php echo JText::_('Go'); ?></button>
				<button onclick="document.adminForm.description.value='';this.form.submit(); return;"><?php echo JText::_('Reset'); ?></button>
			</td>
			<td colspan="2" width="320">
				<?php echo JHTML::_('calendar', $this->lists->fltFrom, 'from', 'from'); ?> &mdash;
				<?php echo JHTML::_('calendar', $this->lists->fltTo, 'to', 'to'); ?>
				<button onclick="this.form.submit(); return false;"><?php echo JText::_('Go'); ?></button>
			</td>
			<td colspan="2"></td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php if(!empty($this->list)): ?>
	<?php $id = 1; $i = 0;?>
	<?php foreach($this->list as $record): ?>
	<?php
	$id = 1 - $id;
	$check = JHTML::_('grid.id', ++$i, $record['id']);
	switch($record['meta'])
	{
		case 'ok':
			$status = JText::_('STATS_LABEL_STATUS_OK');
			break;

		case 'obsolete':
			$status = JText::_('STATS_LABEL_STATUS_OBSOLETE');
			break;

		case 'fail':
			$status = JText::_('STATS_LABEL_STATUS_FAIL');
			break;

		case 'pending':
			$status = JText::_('STATS_LABEL_STATUS_PENDING');
			break;
	}

	jimport('joomla.utilities.date');
	$startTime = new JDate($record['backupstart']);
	$endTime = new JDate($record['backupend']);

	$user =& JFactory::getUser();
	$userTZ = $user->getParam('timezone',0);
	$startTime->setOffset($userTZ);

	$filename_col = '';

	if(empty($record['description'])) $record['description'] = JText::_('STATS_LABEL_NODESCRIPTION');
	?>
		<tr class="row<?php echo $id; ?>">
			<td><?php echo $check; ?></td>
			<td>
				<?php echo $this->escape($record['description']) ?>
			</td>
			<td>
				<?php if( AKEEBA_JVERSION == '16' ): ?>
					<?php echo $startTime->format(JText::_('DATE_FORMAT_LC2'), true); ?>
				<?php else: ?>
					<?php echo $startTime->toFormat(JText::_('DATE_FORMAT_LC2')); ?>
				<?php endif; ?>
			</td>
			<td class="bufa-<?php echo $record['meta']; ?>"><?php echo $status ?></td>
			<td><?php echo ($record['meta'] == 'ok') ? format_filesize($record['size']) : ($record['total_size'] > 0 ? "(<i>".format_filesize($record['total_size'])."</i>)" : '&mdash;') ?></td>
			<td>
				<?php echo $filename_col; ?>
				<?php if($record['meta'] == 'ok'): ?>
					<br/>
					<?php
						$infoParts = explode("\n", $record['comment']);
						$info = json_decode($infoParts[1]);
					?>
					<?php echo JText::_($info->type) .': '. $info->name ?>
					<?php if($info->version): ?>
					&bull;
					<?php echo JText::_('BUADMIN_LABEL_VERSION')?>: <?php echo $info->version ?>
					<?php if($info->date) echo " &bull; "?>
					<?php endif?>
					<?php if($info->date): ?>
					<?php echo JText::_('BUADMIN_LABEL_DATE')?>: <?php echo $info->date ?>
					<?php endif?>
					<br/>
					<button onclick="window.location='index.php?option=com_akeeba&view=srprestore&id=<?php echo $record['id'] ?>'; return false;"><?php echo JText::_('BUADMIN_LABEL_SRPRESTORE') ?></button>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>
</form>
</div>
