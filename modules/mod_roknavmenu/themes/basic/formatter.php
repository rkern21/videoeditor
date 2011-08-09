<?php
/**
 * @package   Gantry Template - RocketTheme
 * @version   2.7 November 15, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Gantry Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
if (!class_exists('RokNavMenuFormatter')) {
    class RokNavMenuBasicFormatter extends AbstractJoomlaRokMenuFormatter {
        function format_subnode(&$node) {
            if ($node->getId() == $this->current_node) {
                $node->setCssId('current');
            }
            if (in_array($node->getId(), array_keys($this->active_branch))){
                $node->addListItemClass('active');
            }
        }
    }
}