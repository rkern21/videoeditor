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

class GantryFeatureCopyright extends GantryFeature {
    var $_feature_name = 'copyright';

	function render($position="") {
	    ob_start();
	    ?>
		<div class="clear"></div>
		<span class="copytext"><?php echo $this->get('text'); ?></span>
		<?php
	    return ob_get_clean();
	}
}