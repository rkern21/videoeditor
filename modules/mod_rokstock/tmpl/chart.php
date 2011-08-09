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
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!isset($size) || $size == "small") {$size = "small"; $chartsize = array(212, 102); }
else if ($size == "medium") $chartsize = array(300, 150);
else if ($size == "large") $chartsize = array(400, 200);

if (!isset($chart_url)) $chart_url = "http://www.google.com/finance/chart?cht=c";
$chart_url .= "&amp;q=$chart_values&amp;chs=".substr($size, 0, 1);
?>

<img width="<?php echo $chartsize[0]; ?>" height="<?php echo $chartsize[1]; ?>" src="<?php echo $chart_url."&amp;nocache=".time(); ?>" alt="" />