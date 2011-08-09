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

$tickers = $tickers[0];
$chart_values = $tickers["t"];
$size = "small";

if (preg_match("/\+/", $tickers["c"])) $direction = "plus";
else $direction = "minus";

if ($tickers['c'] == '') $tickers['c'] = '0.00';
if ($tickers['cp'] == '') $tickers['cp'] = '0.00';
            
if ($tickers['c'] == '0.00' && $tickers['cp'] == '0.00') $direction = "neutral";

$time_display = $params->get('time_display', "12");
$chart_url = "http://www.google.com/finance/chart?q=$chart_values&amp;tlf=$time_display";
?>

<div class="rokstock-tip-inner">

	<div class="graph centerloader"><?php require(JModuleHelper::getLayoutPath('mod_rokstock', 'chart')); ?></div>
	<div class="content">
		<h1><?php echo $tickers["n"]; ?></h1>
		<h2>(<?php echo $tickers['e'].':'.$tickers['t']; ?>)</h2>
		
		<div class="trade row">
			<span class="title"><?php echo JText::_('LAST_TRADE'); ?></span>
			<span><?php echo $tickers['l_cur']; ?></span>
		</div>
		
		<div class="trade-time row">
			<span class="title"><?php echo JText::_("TRADE_TIME"); ?></span>
			<span><?php echo $tickers['ltt']; ?></span>
		</div>
		
		<div class="trade-change row">
			<span class="title"><?php echo JText::_('CHANGE'); ?></span>
			<span class="<?php echo $direction; ?>"><?php echo $tickers['c']; ?> (<?php echo $tickers['cp']; ?>%)</span></span>
		</div>
		
		<!--<div class="more-details row">
			<a class="external" href="http://www.google.com/finance?q=<?php echo $tickers["t"]; ?>"><?php echo JText::_('MORE_DETAILS'); ?></a>
		</div>-->
</div>

<div class="clr"></div>