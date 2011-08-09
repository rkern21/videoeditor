<?php
/**
 * RokNewsFlash Module
 *
 * @package RocketTheme
 * @subpackage roknewsflash.tmpl
 * @version   1.1 September 13, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$counter_col = 0;
$counter_row = 0;
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$total_columns = ' col' . count($data);

?>
<div class="rokfeaturetable <?php echo $moduleclass_sfx;?><?php echo $total_columns;?>">
	<?php
	foreach ($data as $col):
		$counter_col++;
		$col_oddeven = (!($counter_col % 2) ? ' ft-column-even' : ' ft-column-odd');
		$first = $counter_col == 1 ? ' ft-column-first' : '';
		$last = $counter_col == sizeof($data) ? ' ft-column-last' : '';
		$highlight = $counter_col == $params->get('highlight-col',1) ? ' ft-highlight' : '';

	?>
	<div class="featuretable-col <?php echo $col_oddeven; ?><?php echo $first;?><?php echo $last;?><?php echo $highlight; ?>">
		<?php
			$counter_row = 0;
			foreach($col as $cls => $row):
				$counter_row++;
				$row_oddeven = (!($counter_row % 2) ? ' ft-row-even' : ' ft-row-odd');
				$top = $counter_row == 1 ? ' ft-row-top' : '';
				$bottom = $counter_row == sizeof($col) ? ' ft-row-bottom' : '';
				$cell_cls = " ft-cell-".$cls;
				
				if (isset($row->style)) $styles = " style='".$row->style."'";
				else $styles = "";
		?>
		<div class="featuretable-cell<?php echo $row_oddeven;?><?php echo $top;?><?php echo $bottom;?><?php echo $cell_cls; ?>"<?php echo $styles; ?>>
			<div class="featuretable-cell-inner">
				<div class="featuretable-cell-data">
				<?php if (isset($row->link)): ?>
					<a href="<?php echo $row->link; ?>"><?php echo $row->data; ?></a>
				<?php else: ?>
					<?php echo $row->data; ?>
				<?php endif; ?>
				</div>
				<?php if (isset($row->sub)): ?>
				<div class="featuretable-cell-sub"><?php echo $row->sub; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
</div>
<div class="clear"></div>
			
