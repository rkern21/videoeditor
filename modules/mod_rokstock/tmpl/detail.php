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

?>

	<div class="rokstock-list">
	<?php if (count($tickers)): ?>
	<?php 
		for($i = 0; $i < count($tickers); $i++): ?>
	<?php
			$class = "";
			$type = "type4";
						
			if (preg_match("/\+/", $tickers[$i]["c"])) $direction = "plus";
			else $direction = "minus";
			
			if (substr($tickers[$i]["t"], 0 , 1) == ".") {
				$words = str_word_count($tickers[$i]["n"], 1, '&/');
				$title = strtoupper($words[0]);
			} else $title = $tickers[$i]["t"];
			
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