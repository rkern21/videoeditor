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

class GantryFeatureLogin extends GantryFeature {
    var $_feature_name = 'login';

	function render($position="") {
	    ob_start();
	    $user =& JFactory::getUser();
	    ?>
		<div class="rt-block">
		<div id="rt-login-button">
		<?php if ($user->guest) : ?>
			<a href="#" class="buttontext" rel="rokbox[215 265][module=rt-popuplogin]">
				<span class="desc"><?php echo $this->get('text'); ?></span>
				<span class="icon"></span>
			</a>
		<?php else : ?>
			<a href="#" class="buttontext" rel="rokbox[215 265][module=rt-popuplogin]">
				<span class="desc"><?php echo $this->get('logouttext'); ?> <?php echo JText::sprintf($user->get('username')); ?></span>
				<span class="icon"></span>
			</a>
		<?php endif; ?>
		</div>
		</div>
		<?php
	    return ob_get_clean();
	}
}