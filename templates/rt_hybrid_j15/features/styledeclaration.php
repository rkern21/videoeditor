<?php
/**
 * @package		Gantry Template Framework - RocketTheme
 * @version		1.5.4 November 16, 2010
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

gantry_import('core.gantryfeature');

class GantryFeatureStyleDeclaration extends GantryFeature {
    var $_feature_name = 'styledeclaration';

    function isEnabled() {
        global $gantry;
        $menu_enabled = $this->get('enabled');

        if (1 == (int)$menu_enabled) return true;
        return false;
    }

	function init() {
        global $gantry;

		// tooltips for articledetails layout3
		if ($gantry->get('articledetails') == 'layout3') $gantry->addScript('gantry-articledetails.js');
		$gantry->addInlineScript($this->_rokStoriesScroller());

		$this->_disableRokBoxForiPhone();

		//style stuff
		$gantry->addStyle($gantry->get('headerstyle').".css");
		$gantry->addStyle($gantry->get('bodystyle').".css");
		$gantry->addStyle($gantry->get('bodystyle')."-accents.css");
		$gantry->addStyle($gantry->get('footerstyle').".css");
		if ($gantry->get('thirdparty')) $gantry->addStyle('extended.css');

	}
	
	function _rokStoriesScroller() {
		return "
			window.addEvent('domready', function(){
				var storiesList = $$('.vertical-list-wrapper');
				storiesList.each(function(storyList){
					new Scroller(storyList.setStyle('overflow', 'hidden')).start();
				});
			});
		";
	}

	function _disableRokBoxForiPhone() {
		global $gantry;

		if ($gantry->browser->platform == 'iphone') {
			$gantry->addInlineScript("window.addEvent('domready', function() {\$\$('a[rel^=rokbox]').removeEvents('click');});");
		}
	}

}