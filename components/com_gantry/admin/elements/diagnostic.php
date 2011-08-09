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
class JElementDiagnostic extends JElement
{
	var	$_name = 'Diagnostic';

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;
		
		gantry_import('core.gantrydiagnostic');
		$diagnose = new GantryDiagnostic();
		$errors = $diagnose->checks();
		
		$output = "";
		
		if (count($errors) > 0) {
			$klass = "errors";
			$output = implode("", $errors);
		} else {
			$klass = "success";
			$output = "Diagnostic checks successfully passed.";
		}
        $output .= '<br/><a href="'.JURI::base(true).'?option=com_admin&tmpl=gantry-ajax-admin&model=diagnostics&template='.$gantry->templateName.'">Download Diagnostic Info</a>';
		
		return "
		<div id='diagnostic' class='".$klass."'>
			<div id='diagnostic-bar' class='g-title'>".JText::_('DIAGNOSTICS')." - ". ucfirst($klass) ." <span class='arrow'></span></div>
			<div id='diagnostic-desc' class='g-inner'>
			".$output."
			</div>
		</div>";
		
	}
}