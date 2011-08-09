<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		3.1.4 November 12, 2010
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2010 RocketTheme, LLC
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
class JElementFonts extends JElement {

    var $_google_fonts = array('Arvo','Arimo','Cantarell','Cardo','Cousine','Crimson','Cuprum','Droid Sans','Droid Sans Mono','Droid Serif', 'IM Fell','Inconsolata','Josefin Sans Std Light','Josefin Slab','Lobster','Molengo','Nobile','Neucha','Neuton','OFL Sorts Mill Goudy TT','Old Standard TT','Philosopher','PT Sans','Reenie Beanie','Tangerine','Tinos','Vollkorn','Yanone Kaffeesatz');


	function fetchElement($name, $value, &$node, $control_name, $options = false, $translation = true) {
        global $gantry;

		if (!defined('GANTRY_SELECTBOX')) {
			$this->template = end(explode(DS, $gantry->templatePath));
			gantry_addScript($gantry->gantryUrl.'/admin/widgets/selectbox/js/selectbox.js');

			define('GANTRY_SELECTBOX', 1);
		}

		$lis = $activeElement = "";
        $options_list = "";
		$xml = false;

		if (!$options) {
			$options = $node->children();
			$xml = true;
		}

		$isPreset = ($node->attributes('preset')) ? $node->attributes('preset') : false;



		foreach($options as $option) {
			if ($xml) {
				$optionData = $option->data();
				$optionValue = $option->attributes('value');
				$optionDisabled = $option->attributes('disable');
			} else {
				$optionData = $option->text;
				$optionValue = $option->value;
				$optionDisabled = $option->disable;
			}

			$disabled = ($optionDisabled == 'disable') ? "disabled='disabled'" : "";
			$selected = ($value == $optionValue) ? "selected='selected'" : "";
			$active = ($value == $optionValue) ? "class='active'" : "";
			if (strlen($active)) $activeElement = $optionData;

			if (strlen($disabled)) $active = "class='disabled'";

			$imapreset = ($isPreset) ? "im-a-preset" : "";

			$text = ($translation) ? JTEXT::_($optionData) : $optionData;

			$options_list .= "<option value='$optionValue' $selected $disabled>".$text."</option>\n";
			$lis .= "<li ".$active.">".$text."</li>";
		}

        // add webfonts if enabled
        if ($gantry->get('webfonts-enabled')) {
            // only google right now
            if ($gantry->get('webfonts-source') == 'google') {
                $webfonts = $this->_google_fonts;
            }
            foreach ($webfonts as $webfont) {
                $webfontsData = $webfont;
				$webfontsValue = $webfont;

			    $selected = ($value == $webfontsValue) ? "selected='selected'" : "";
			    $active = ($value == $webfontsValue) ? "class='active'" : "";
				if (strlen($active)) $activeElement = $webfontsData;
                $text = $webfontsData;

                $options_list .= "<option value='$webfontsValue' $selected>".$text."</option>\n";
			    $lis .= "<li ".$active.">".$text."</li>";

            }
        }

		$html  = "<div class='wrapper'>";
		$html .= "<div class='selectbox-wrapper'>";

		$html .= "	<div class='selectbox'>";

		$html .= "		<div class='selectbox-top'>";
		$html .= "			<div class='selected'><span>".JTEXT::_($activeElement)."</span></div>";
		$html .= "			<div class='arrow'></div>";
		$html .= "		</div>";
		$html .= "		<div class='selectbox-dropdown'>";
		$html .= "			<ul>".$lis."</ul>";
		$html .= "			<div class='selectbox-dropdown-bottom'><div class='selectbox-dropdown-bottomright'></div></div>";
		$html .= "		</div>";

		$html .= "	</div>";

		$html .= "	<select id='params".$name."' name='params[".$name."]' class='selectbox-real ".$imapreset."'>";
		$html .= 		$options_list;
		$html .= "	</select>";
		$html .= "</div>";
		$html .= "<div class='clr'></div>";
		$html .= "</div>";

		return $html;
	}

}

?>