<?php
/**
 * @package   Hybrid Template - RocketTheme
 * @version   1.5.4 November 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Hybrid Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

gantry_import('core.gantryfeature');

class GantryFeatureLowerPanel extends GantryFeature {
    var $_feature_name = 'lowerpanel';

	function init() {
		global $gantry;
		
		if ($this->get('enabled')) {
			$defaultStatus = $gantry->get('lowerpanel-default') == 'open' ? 'true' : 'false';
			$state = JRequest::getVar('rt-lowerpanel-hybrid', $defaultStatus, 'cookie');
			$state = ($state == 'true') ? 'show' : 'hide';
			
			$gantry->addScript('gantry-slidingpanel.js');
			$gantry->addInlineScript("window.addEvent('domready', function() { new GantrySlidingPanel('rt-lowerpanel', {delay: ".$this->get('delay').", state: '".$state."', 'transition': Fx.Transitions.Expo.easeInOut, cookie: 'rt-lowerpanel-hybrid', text: ['".JText::_('LOWER_PANEL_OPEN')."', '".JText::_('LOWER_PANEL_CLOSE')."']}); });");
		}
	}
}