<?php
/**
 * @package RocketTheme
 * @subpackage roknewspager.elements
 * @version   1.7 August 26, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a file list from a directory in the current templates directory
 * @package RocketTheme
 * @subpackage roknewspager.elements
 */
class JElementOverlayList extends JElement
    {
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
    var    $_name = 'TemplateFilelist';

    var $_front_side_template;

    function fetchElement($name, $value, &$node, $control_name)
    {

        
        jimport( 'joomla.filesystem.folder' );
        jimport( 'joomla.filesystem.file' );

        $filter = '\.png$';
        $exclude    = $node->attributes('exclude');

        $imagefolder_path = '/images/roknewspager/overlays';
        $imagefolder_full_path = JPath::clean(JPATH_ROOT.$imagefolder_path);
        $imagefolder_text = JText::_("Overlays");

        // path to directory
        $template_themes_path = '/templates/'.$this->_getFrontSideTemplate().'/images/mod_roknewspager/overlays';
        $template_themes_full_path = JPath::clean(JPATH_ROOT.$template_themes_path);
        $template_theme_text = JText::_("Template Overlays");

        $theme = $this->_parent->get("theme");
        $module_themes_path = '/modules/mod_roknewspager/themes/'.$theme.'/images/overlay.png';
        $module_themes_full_path = JPath::clean(JPATH_ROOT.$module_themes_path);
        $module_theme_text = JText::_("Default Theme Overlay");


        $options = array ();
        if (!$node->attributes('hide_none'))
        {
            $options[] = JHTML::_('select.option', '-1', '- '.JText::_('Do not use').' -');
        }

        /** Get the Template overlays **/
        if (JFolder::exists($template_themes_full_path)) {
            $files = JFolder::files($template_themes_full_path, $filter);
            if ( is_array($files) )
            {
                reset($files);
                while (list($key, $val) = each($files)) {
                    $folder =& $files[$key];
                    if ($exclude)
                    {
                        if (preg_match( chr( 1 ) . $exclude . chr( 1 ), $folder ))
                        {
                            continue;
                        }
                    }
                    $options[] = JHTML::_('select.option', $template_themes_path."/".$folder, $template_theme_text." - ".$folder);
                }
            }
        }
        /** Get the Default Themes **/
        if (JFile::exists($module_themes_full_path)) {
            $options[] = JHTML::_('select.option', $module_themes_path."/".$folder, $module_theme_text. " - ". $folder);
        }


        /** Get the Template overlays **/
        if (JFolder::exists($imagefolder_full_path)) {
            $files = JFolder::files($imagefolder_full_path, $filter);
            if ( is_array($files) )
            {
                reset($files);
                while (list($key, $val) = each($files)) {
                    $folder =& $files[$key];
                    if ($exclude)
                    {
                        if (preg_match( chr( 1 ) . $exclude . chr( 1 ), $folder ))
                        {
                            continue;
                        }
                    }
                    $options[] = JHTML::_('select.option', $imagefolder_path."/".$folder, $imagefolder_text." - ".$folder);
                }
            }
        }

        return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, "param$name");

    }

    function _getFrontSideTemplate() {
        if (empty($this->_front_side_template)) {
            $db =& JFactory::getDBO();
            // Get the current default template
            $query = ' SELECT template '
                    .' FROM #__templates_menu '
                    .' WHERE client_id = 0 '
                    .' AND menuid = 0 ';
            $db->setQuery($query);
            $defaultemplate = $db->loadResult();
            $this->_front_side_template = $defaultemplate;
        }
        return $this->_front_side_template;
    }
}
