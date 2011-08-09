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
$idList = AriUtils2::getParam($params, 'idList', array());
$width = AriUtils2::getParam($params, 'width', 350);
$height = AriUtils2::getParam($params, 'height', 125);

if (is_array($idList) && count($idList) > 0):
	$firstArticle = true;
	foreach ($idList as $id):
		$url = sprintf(JURI::root(true) . '/index.php?option=com_content&amp;view=article&amp;tmpl=component&amp;id=%d&amp;TB_iframe=1&amp;width=%s&amp;height=%s%s%s',
			$id,
			$width,
			$height,
			$modal ? '&amp;modal=1' : '',
			$bgColor ? '&amp;background=' . $bgColor : '');
?>

<a class="sexy-link<?php if ($class) echo ' ' . $class; ?>" href="<?php echo $url; ?>" rel="sexylightbox<?php if ($group): ?>[<?php echo $group; ?>]<?php endif; ?>" title="<?php echo $title; ?>"<?php if (!$firstArticle): ?> style="display: none;"<?php endif; ?>>
	<?php echo $firstArticle ? AriUtils2::getParam($params, 'link') : ''; ?>
</a>

<?php
		$firstArticle = false;
	endforeach;
endif;
?>