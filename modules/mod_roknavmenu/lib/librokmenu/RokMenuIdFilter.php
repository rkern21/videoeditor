<?php
/**
 * @version   2.7 November 15, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokMenuIdFilter extends RecursiveFilterIterator {
    protected $id;

    public function __construct(RecursiveIterator $recursiveIter, $id) {
        $this->id = $id;
        parent::__construct($recursiveIter);
    }
    public function accept() {
        return $this->hasChildren() || $this->current()->getId() == $this->id;
    }

    public function getChildren() {
        return new self($this->getInnerIterator()->getChildren(), $this->id);
    }
}