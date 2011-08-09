<?php 
/**
 * RokStock Module
 *
 * @package RocketTheme
 * @subpackage rokstock
 * @version   0.7 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$slist = explode(",", $stocklist);

if (count($slist) > 4) { $slist = array_splice($slist, 0, 4); $slist = implode(",", $slist); }
else $slist = $stocklist;

$chartImage =  "http://www.google.com/finance/chart?cht=c&amp;q=".$slist."&amp;nocache=".time();

?>
<div id="rokstock">
	
	<div class="rokstock-graph">
		<?php if ($show_main_chart): ?>
		<div class="rokstock-image">
			<img width="250" height="130" src="<?php echo $chartImage; ?>" alt="" />
			<img class="rokstock-timeaxis" width="250" height="24" src="<?php echo $images; ?>time_axis_labels.gif" alt="" />
		</div>
		<?php endif; ?>
	</div>
	
	<div class="rokstock-list">
		<?php if (count($tickers)): ?>
		<?php 
			for($i = 0; $i < count($tickers); $i++): ?>
		<?php
				$class = "";
				$type = "type4";
				
				if ($i < 4) $type = "type".$i;
				
				if (preg_match("/\+/", $tickers[$i]["c"])) $direction = "plus";
				else $direction = "minus";
				
				if (substr($tickers[$i]["t"], 0 , 1) == ".") {
					list($word) = explode(" ", $tickers[$i]["n"]);
					$title = strtoupper($word);
				} else $title = $tickers[$i]["t"];
				
//				$title = str_replace("&", "&amp;", $title);
				$title = $title;
				
				if ($tickers[$i]['c'] == '') $tickers[$i]['c'] = '0.00';
				if ($tickers[$i]['cp'] == '') $tickers[$i]['cp'] = '0.00';
				
				if ($tickers[$i]['c'] == '0.00' && $tickers[$i]['cp'] == '0.00') $direction = "neutral";
				
				if (!$show_main_chart) $type = "type4";
			?>
			
			<div class="row">
				<div class="title">
					<span class="legend <?php echo $type; ?>"><small><?php echo ($i+1); ?></small></span>
					<a rel="<?php echo $tickers[$i]["e"].':'.$tickers[$i]["t"]; ?>" class="external" href="http://www.google.com/finance?q=<?php echo $tickers[$i]["t"]; ?>"><span class="name"><?php echo $title; ?></span></a>
					<small class="amount"><?php echo $tickers[$i]["l_cur"]; ?></small>
				</div>
				<div class="values <?php echo $direction; ?>">
					<span class="value"><?php echo $tickers[$i]["c"]; ?></span>
					<span class="percentage">(<?php echo $tickers[$i]["cp"]; ?>%)</span>
					<span class="delete">&nbsp;&nbsp;&nbsp;</span>
				</div>
			</div>
		<?php endfor;?>
	<?php endif; ?>
	</div>
	<?php
		if ($params->get("user_interaction", "1") == "1"):
	?>
	<div class="rokstock-add">
		<input type="text" name="add" class="rokstock-note" value="<?php echo JText::_('SEARCH'); ?>"/>
		<?php if ($show_main_chart): ?>
		<select class="rokstock-comparison">
			<?php
				for ($i = 0; $i < 4; $i++) {
					if ($i == 3) $sel = " selected='selected'";
					else $sel = '';
					
					echo "<option $sel value='count".($i+1)."'>".($i+1)."</option>\n";
				}
			
			?>
		</select>
		<?php endif; ?>
		<button class="add">add</button>
		<div class="rokstock-reload"></div>
	</div>
	<?php
		endif;
	?>
	
	<div class="rokstock-tooltip"></div>
</div>