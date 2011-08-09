<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementGSpacer extends JElement {
	

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;
		
		$output = "";
		
		$presetSaver = $node->attributes('preset-saver');
		if (isset($presetSaver) && $presetSaver == "true") {
			$saver  = "<span class='preset-saver'>";
			$saver .= "		<a href='#' class='hasTip' title='".JText::_('PRESET_SAVER_DESC')."'>";
			$saver .= "			<span>".JText::_('PRESET_SAVER')."</span>";
			$saver .= "		</a>";
			$saver .= "</span>";
		}
		else $saver = '';
		
		$this->template = end(explode(DS, $gantry->templatePath));

		$opensTable = "<table class='paramlist admintable' width='100%' cellspacing='1'><tbody><tr><td>";
		$closeTable = "</td></tr></tbody></table>";
		$surroundOpens = "<div id='g-$name' class='g-surround'>";
		$surroundClose = "</div>";
		$title = "<h3 class='g-title' rel='$name'>".JText::_($node->attributes('glabel')).$saver."<span class='arrow'></span></h3>";
		$shadow = "<div class='g-title-shadow'></div>";
		$innerOpens = "<div id='g-$name-inner' class='g-inner'>";
		$innerClose = "</div>";
				
		if (!defined("GSPACER")) {
			$output = $closeTable . $surroundOpens . $title . $innerOpens . $opensTable;
			define("GSPACER", 1);
		} else {
			$output = $closeTable . $innerClose . $surroundClose . $surroundOpens . $title . $innerOpens . $opensTable;
		}
		
		return $output;
		
	}
}