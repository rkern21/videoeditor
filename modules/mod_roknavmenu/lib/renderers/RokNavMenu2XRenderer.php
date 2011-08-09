<?php
/**
 * @version   2.7 November 15, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokNavMenu2XRenderer extends RokMenuDefaultRenderer
{

    /**
     * @param RokMenuNodeTree $menu
     * @return RokMenuNodeTree menu after reprocessing
     */
    protected function preProcessMenu(RokMenuNodeTree &$menu)
    {
        $remove_nodes = array();
        $nodeIterator = new RecursiveIteratorIterator($menu, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($nodeIterator as $node) {
            if (!$this->isAccessable($node)){
                $remove_nodes[] = $node->getId();
            }
        }
        foreach($remove_nodes as $remove_node){
            $menu->removeNode($remove_node);
        }
        return $menu;
    }

    /**
     * @param JoomlaRokMenuNode $node
     * @return bool if the node is accessable
     */
    protected function isAccessable(JoomlaRokMenuNode $node)
    {
        $user =& JFactory::getUser();
        $aid = (array_key_exists('check_access_level',$this->args)) ? (int)$this->args['check_access_level'] : (int)$user->get('aid', 0);
        if (null == $node->getAccess()) {
            return null;
        }
        else if ($aid >= $node->getAccess()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function renderHeader(){
        parent::renderHeader();
        $doc = &JFactory::getDocument();

        foreach($this->layout->getScriptFiles() as $script){
            $doc->addScript($script['relative']);
        }

        foreach($this->layout->getStyleFiles() as $style){
            $doc->addStyleSheet($style['relative']);
        }
        $doc->addScriptDeclaration($this->layout->getInlineScript());
        $doc->addStyleDeclaration($this->layout->getInlineStyle());
    }
}
