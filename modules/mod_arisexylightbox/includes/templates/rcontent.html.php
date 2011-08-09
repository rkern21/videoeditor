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

$title = str_replace('"', '&quot;', AriUtils2::getParam($params, 'title', ''));
$url = AriUtils2::getParam($params, 'url', '');
$class = AriUtils2::getParam($params, 'class', '');
$modal = AriUtils2::getParam($params, 'modal', false);
$group = AriUtils2::getParam($params, 'group', false);
$bgColor = AriUtils2::getParam($params, 'bgColor', false);

if (strpos($url, '?') === false)
	$url .= '?';
else
	$url .= '&';

$url .= sprintf('TB_iframe=1&amp;width=%s&amp;height=%s%s%s',
	AriUtils2::getParam($params, 'width', 350),
	AriUtils2::getParam($params, 'height', 125),
	$modal ? '&amp;modal=1' : '',
	$bgColor ? '&amp;background=' . $bgColor : '');
?>

<a class="sexy-link<?php if ($class) echo ' ' . $class; ?>" href="<?php echo $url; ?>" rel="sexylightbox<?php if ($group): ?>[<?php echo $group; ?>]<?php endif; ?>" title="<?php echo $title; ?>">
<?php echo AriUtils2::getParam($params, 'link'); ?>
</a>