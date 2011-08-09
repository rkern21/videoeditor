<?php
/**
 * @package   gantry
 * @subpackage core
 * @version   3.1.10 March 5, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('GANTRY_VERSION') or die();

jimport('joomla.html.parameter.element');
/**
 * Base class for all Gantry custom features.
 *
 * @package gantry
 * @subpackage core
 */
class GantryGroupElement extends JElement {

    function fetchElement($name, $value, &$node, $control_name) {
    }

    function _loadElementType($element_type) {
        global $gantry;
        $element = null;
        $element_classname = 'JElement' . ucfirst($element_type);
        if (!class_exists($element_classname)) {
            $element_paths = array(
                $gantry->templatePath . '/admin/elements',
                $gantry->templatePath . '/elements',
                $gantry->gantryPath . '/admin/elements',
                $gantry->basePath . 'libraries/joomla/html/parameter/element'
            );

            $raw_features = array();
            foreach ($element_paths as $element_path) {
                if (file_exists($element_path) && is_dir($element_path)) {
                    $d = dir($element_path);
                    while (false !== ($entry = $d->read())) {
                        if ($entry != '.' && $entry != '..') {
                            $entry_type = basename($entry, ".php");
                            if ($element_type == $entry_type) {
                                $path = $element_path . DS . $entry_type . '.php';
                                if (!class_exists($element_classname)) {
                                    if (file_exists($path)) {
                                        require_once($path);
                                        if (class_exists($element_classname)) {
                                            $d->close();
                                            break(2);
                                        }
                                    }

                                }
                            }
                        }
                    }
                    $d->close();
                }
            }
        }
        // get a new instace of the element
        if (class_exists($element_classname)){
            $element = new $element_classname;
        }
        return $element;
    }
}