<?php
/**
 * @package   Hybrid Template - RocketTheme
 * @version   1.5.4 November 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

gantry_import('core.gantryfeature');

class GantryFeatureBranding extends GantryFeature {
    var $_feature_name = 'branding';

	function render($position="") {
	    ob_start();
	    ?>
		<div class="clear"></div>
		<div class="rt-block">
			<div id="developed-by">
				<a href="http://www.rockettheme.com/" title="rockettheme.com" id="rocket"></a>
			</div>
		</div>
		<?php
	    return ob_get_clean();
	}
}