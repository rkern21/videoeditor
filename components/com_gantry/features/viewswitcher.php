<?php
/**
 * @package     gantry
 * @subpackage  features
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
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
class GantryFeatureViewSwitcher extends GantryFeature {
	var $_platform = "";
	
	function isEnabled() {
		global $gantry;
		if (!$gantry->browser) return false;
		
		$this->_platform = $gantry->browser->platform;
		if ($gantry->get($this->_platform.'-enabled')) return true;
		else return false;
	}
	
	function init() {
		global $gantry;
		
		$prefix = $gantry->get('template_prefix');
		$cookiename = $prefix.$this->_platform.'-switcher';
		
		$cookie = JRequest::getVar($cookiename, false, 'COOKIE', 'STRING');
		
		if (!strlen($cookie) || $cookie === false) {
			setcookie($cookiename, "1", time() + 60*60*24*365);
			$cookie = "1";
		}
		
		$gantry->addTemp('platform', $cookiename, $cookie);
		
		if ($gantry->get($this->_platform . '-switcher-enabled')) $gantry->addInlineScript($this->_js($cookie, $cookiename));
	}

    function getPosition() {
		$this->_feature_name = $this->_platform.'-switcher';
        return $this->get('position');
    }
	
	function render($position="") {
		global $gantry;
		
		if (!$gantry->get($gantry->browser->platform . '-switcher-enabled')) return "";
		
		$prefix = $gantry->get('template_prefix');
		$cookiename = $prefix.$gantry->browser->platform.'-switcher';
		$cookie = JRequest::getVar($cookiename, false, 'COOKIE', 'STRING');
		$cls = (!$gantry->retrieveTemp('platform', $cookiename)) ? 'off' : 'on';
		
	    ob_start();
	    ?>
		<div class="clear"></div>
		<a href="#" id="gantry-viewswitcher" class="<?php echo $cls; ?>"><span>Switcher</span></a>
		<?php
	    return ob_get_clean();
	}
	
	function _js($cookie, $cookiename) {
		global $gantry;
		if ($cookie === false || $cookie == '1' || $gantry->retrieveTemp('platform', $cookiename) == "1") $cookie = 0;
		else $cookie = 1;

		return "
			window.addEvent('domready', function() {
				var switcher = $('gantry-viewswitcher');
				if (switcher) {
					switcher.addEvent('click', function(e) {
						new Event(e).stop();
						if ('".$cookie."' == '0') $('gantry-viewswitcher').addClass('off');
						else $('gantry-viewswitcher').removeClass('off');
						Cookie.set('".$cookiename."', '".$cookie."');
						window.location.reload();
					});
				}
			});
		";
	}
}