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
 * Renders an menus element
 *
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementFeatures extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/

    var	$_name = 'Preset';

	function fetchElement($name, $value, &$node, $control_name)
	{
		global $gantry;

        $final_ordered_features = array();

        if (!defined('GANTRY_FEATURES')) {
			$this->template = end(explode(DS, $gantry->templatePath));
            gantry_addScript($gantry->gantryUrl.'/admin/widgets/features/js/features.js');
			
			define('GANTRY_FEATURES', 1);
        }

        $features_ordered_initial = explode(",",$value);
        foreach ($features_ordered_initial as $ordered_feature) {
            if (array_key_exists($ordered_feature, $gantry->_features)){
                $final_ordered_features[] = $ordered_feature;
            }
        }
        foreach ($gantry->_features as $feature => $value){
            if (!in_array($feature, $final_ordered_features)){
                $final_ordered_features[] = $feature;
            }
        }
		
		$output = '<div class="wrapper">';
        $output .= '<div id="features-sort">';
        $output .= '<ul>';

		foreach ($final_ordered_features as $feature) {
			$feature_instance = $gantry->_getFeature($feature);
			if ($feature_instance->isOrderable()) $output .= '<li>'.$feature.'</li>';
        }

        $output .= '</ul></div>';
        $output .= '<input type="hidden" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.implode(",",$final_ordered_features).'"/>';
		$output .= "</div>";
		
		return $output;
    }
}
