<?php
/**
 * @version   2.7 November 15, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */


class RokMenuIterator implements RecursiveIterator
{
    protected $ar;

    public function __construct(RokMenuNodeBase $menuNode)
    {
        $this->ar = & $menuNode->getChildren();
    }

    public function rewind()
    {
        reset($this->ar);
    }

    public function valid()
    {
        return !is_null(key($this->ar));
    }

    public function key()
    {
        return key($this->ar);
    }

    public function next()
    {
        next($this->ar);
    }

    public function current()
    {
        return current($this->ar);
    }

    public function hasChildren()
    {
        $current = current($this->ar);
        return $current->hasChildren();
    }

    public function getChildren()
    {
        return new RokMenuIterator($this->current());
    }
}
