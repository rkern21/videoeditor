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

class GantryFeatureFeaturePanel extends GantryFeature {
    var $_feature_name = 'featurepanel';

	function init() {
		global $gantry;
		
		if ($this->get('enabled')) {
			$defaultStatus = $gantry->get('featurepanel-default') == 'open' ? 'true' : 'false';
			$state = JRequest::getVar('rt-feature-hybrid', $defaultStatus, 'cookie');
			$state = ($state == 'true') ? 'show' : 'hide';
			
			$showcase = $gantry->countModules('showcase') ? 0 : 1;
			
			$gantry->addScript('gantry-slidingpanel.js');
			$gantry->addInlineScript("window.addEvent('domready', function() { new GantrySlidingPanel('rt-feature', {delay: ".$this->get('delay').", state: '".$state."', 'transition': Fx.Transitions.Expo.easeInOut, cookie: 'rt-feature-hybrid', text: ['".JText::_('FEATURE_PANEL_OPEN')."', '".JText::_('FEATURE_PANEL_CLOSE')."'], 'flipped': ".$showcase."}); });");
		}
	}
}