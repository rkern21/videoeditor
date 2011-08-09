<?php
/**
 * @package     gantry
 * @subpackage  features
 * @version        3.1.10 March 5, 2011
 * @author        RocketTheme http://www.rockettheme.com
 * @copyright     Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

defined('JPATH_BASE') or die();

gantry_import('core.gantryfeature');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryFeatureRequestVars extends GantryFeature
{

    var $_feature_name = 'requestvars';

    function isInPosition($position)
    {
        return false;
    }

    function isEnabled()
    {
        return true;
    }

    function init()
    {
        global $gantry;

        $gantry->addBodyClass('option-' . str_replace("_", "-", strip_tags(JRequest::getString('option'))));

        $view = strip_tags(JRequest::getString('view'));
        if (strlen($view)>0)
        {
            $gantry->addBodyClass('view-' . str_replace("_", "-", $view));
        }
        $layout = strip_tags(JRequest::getString('layout'));
        if (strlen($layout) >0)
        {
            $gantry->addBodyClass('layout-' . str_replace("_", "-", $layout));
        }
        $task = strip_tags(JRequest::getString('task'));
        if (strlen($task)>0)
        {
            $gantry->addBodyClass('task-' . str_replace("_", "-", $task));
        }
    }
}