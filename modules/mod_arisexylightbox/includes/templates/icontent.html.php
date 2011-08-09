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

$contentId = uniqid('asl', false);
$width = AriUtils2::getParam($params, 'width', 350);
$height = AriUtils2::getParam($params, 'height', 125);
$class = AriUtils2::getParam($params, 'class', '');
$title = str_replace('"', '&quot;', AriUtils2::getParam($params, 'title', ''));
$modal = AriUtils2::getParam($params, 'modal', false);
$group = AriUtils2::getParam($params, 'group', false);
$bgColor = AriUtils2::getParam($params, 'bgColor', false);
$wrapTag = AriUtils2::getParam($params, 'wrapTag', 'div');
if (empty($wrapTag)) $wrapTag = 'div';
?>

<<?php echo $wrapTag; ?> id="<?php echo $contentId; ?>" style="display: none;"><?php echo AriUtils2::getParam($params, 'text'); ?></<?php echo $wrapTag; ?>> 
<a class="sexy-link<?php if ($class) echo ' ' . $class; ?>" href="#TB_inline?inlineId=<?php echo $contentId; ?>&amp;height=<?php echo $height; ?>&amp;width=<?php echo $width; ?><?php if ($modal): ?>&amp;modal=1<?php endif; ?><?php if ($bgColor): ?>&amp;background=<?php echo $bgColor; ?><?php endif; ?>" rel="sexylightbox<?php if ($group): ?>[<?php echo $group; ?>]<?php endif; ?>" title="<?php echo $title; ?>">
<?php echo AriUtils2::getParam($params, 'link'); ?>
</a>