<?php
/**
 * RokNewsFlash Module
 *
 * @package RocketTheme
 * @subpackage roknewsflash.tmpl
 * @version   1.5.4 November 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$total_columns = ' col' . count($data);
?>
<div class="featuretable<?php echo $total_columns;?>"><div class="featuretable-border">
<?php
$even = ' even'; 
$counter = 0;
foreach ($data as $col):
	$counter++;
	$row_bg = ' bg';
	$even = $even == '' ? ' ft-col-even' : '';
	$first = $counter == 1 ? ' ft-col-first' : '';
	$last = $counter == sizeof($data) ? ' ft-col-last' : '';
	$highlight = $counter == $params->get('highlight-col',1) ? ' highlight' : '';

?>
<div class="featuretable-col <?php echo $even; ?><?php echo $first;?><?php echo $last;?><?php echo $highlight; ?>">
	<div class="featuretable-col-border">
		<?php if (isset($col['name']->data) || isset($col['price']->data)): ?>
		<div class="featuretable-head">
			<div class="head-text" style="<?php echo (isset($col['name']) && $col['name']->style) ? $col['name']->style : ''; ?>">
				<?php if (isset($col['name']) && isset($col['name']->data)): ?>
				<div class="name"><?php echo $col['name']->data; ?></div>
				<?php endif; ?>
				<?php if (isset($col['price']) && isset($col['price']->data)): ?>
				<div class="price" style="<?php echo ($col['price']->style) ? $col['price']->style : ''; ?>">
					<span class="item1"><?php echo $col['price']->data; ?></span>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
		<?php for ($y=1;$y<20;$y++): 
			$row_bg = $row_bg == '' ? ' bg' : '';
			if (isset($col['row-'.$y])) :
		?>
		<div class="featuretable-cell<?php echo $row_bg;?>" style="<?php echo $col['row-'.$y]->style; ?>">
			<?php echo $col['row-'.$y]->data; ?>
		</div>
		<?php	endif; 
			endfor; ?>
		<?php if (isset($col['button-text']->data) or isset($col['button-text']->sub)) :?>
		<?php
			$extra_classes = "";
			if (isset($col['button-text']->classes)) $extra_classes = " ".$col['button-text']->classes;
		?>
		<div class="featuretable-cell bottom<?php echo $extra_classes;?>" style="<?php echo $col['button-text']->style; ?>">
			<a href="<?php echo (isset($col['button-text']->link) ? $col['button-text']->link : '#'); ?>" class="readon"><span><?php echo $col['button-text']->data; ?></span></a>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php endforeach; ?>
<div class="clear"></div>
</div></div>		
