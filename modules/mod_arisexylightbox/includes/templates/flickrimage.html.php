<?php
/*
 * ARI Sexy Lightbox Joomla! module
 *
 * @package		ARI Sexy Lightbox Joomla! module.
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');
$repeater = $params['repeater'];
$template = $params['template'];
?>

<?php
	echo $template;

	$repeater->render();
?>