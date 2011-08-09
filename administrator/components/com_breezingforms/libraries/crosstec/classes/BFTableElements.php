<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
class BFTableElements extends JTable {

	function __construct($db)
	{
		parent::__construct('#__facileforms_elements', 'id', $db);
	}

}