<?php 
/**
 * RokStories Module
 *
 * @package RocketTheme
 * @subpackage rokstories.tmpl
 * @version   1.9 September 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$layout = $params->get("layout_type", 'layout1');
$content_position = $params->get("content_position", 'right');
$height = $params->get('fixed_height', 0);
if ($height != 0 && $height != '0') $style = 'style="height: '.$height.'px;"';
else $style = "";
$image_pad = '';
$content_pad = '';
if ($content_position == 'right') $image_pad = ' feature-pad';
if ($content_position == 'left') $content_pad = ' feature-pad';

$contentOptions = ($params->get("show_article_title", 1) || $params->get("show_created_date", 0) || $params->get("show_article", 1) || $params->get("show_article_link", 1));
?>

<script type="text/javascript">
<?php foreach ($list as $item): ?>
    RokStoriesImage['rokstories-<?php echo $module->id; ?>'].push('<?php echo $item->image; ?>');
	<?php if ($params->get('link_images', 0) == 1): ?>
	RokStoriesLinks['rokstories-<?php echo $module->id; ?>'].push('<?php echo $item->link; ?>');
	<?php endif; ?>
<?php endforeach; ?>
</script>
<div id="rokstories-<?php echo $module->id; ?>" class="rokstories-<?php echo $layout; ?>"  <?php echo $style; ?>>
	<div class="feature-block">
		<div class="image-container<?php echo $image_pad; ?>" style="float: <?php echo $content_position; ?>">
			<div class="image-full"></div>
			<div class="image-small">
			    <?php foreach ($list as $item): ?>
			    <img src="<?php echo $item->thumb; ?>" class="feature-sub" alt="image" width="<?php echo $item->thumbSizes['width']; ?>" height="<?php echo $item->thumbSizes['height']; ?>" />
				<?php endforeach; ?>
			</div>
			<?php if ($layout == 'layout2'): ?>
				<div class="feature-block-tl"></div>
				<div class="feature-block-tr"></div>
				<div class="feature-block-bl"></div>
				<div class="feature-block-br"></div>
				<?php if ($params->get("show_arrows",1)==1 && $params->get("arrows_placement", 'inside')=='inside'): ?>
				<div class="feature-arrow-r"></div>
				<div class="feature-arrow-l"></div>
				<?php endif; ?>
				
				<?php if ($params->get("show_label_article_title",1)==1): ?>
				<div class="labels-title">
					<?php foreach ($list as $item): ?>
						<?php if ($params->get("show_label_article_title",1)==1): ?>
						<div class="feature-block-title">
							<div class="feature-block-title2"></div>
							<div class="feature-block-title3">
								<?php if ($params->get("link_labels", 0) == 1): ?>
									<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
								<?php else: ?>
									<?php echo $item->title; ?>
								<?php endif; ?>
							</div>
						</div>
						<?php endif;?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			<?php endif;?>
			<div class="desc-container">
				<?php if ($contentOptions): ?>
				<div class="feature-block-top"><div class="feature-block-top2"></div><div class="feature-block-top3"></div></div>
				 <?php endif; ?>
				
				<div class="feature-block-inner">
			    <?php foreach ($list as $item): ?>

				<div class="description<?php echo $content_pad; ?>">
					<?php if ($params->get("show_article_title",1)==1): ?>
						<?php if ($params->get("link_titles", 0) == 1): ?>
							<a href="<?php echo $item->link; ?>" class="feature-link"><span class="feature-title"><?php echo $item->title; ?></span></a>
						<?php else: ?>
							<span class="feature-title"><?php echo $item->title; ?></span>					
						<?php endif; ?>
					<?php endif;?>
					<?php if ($params->get("show_created_date",0)==1): ?>
					    <span class="created-date"><?php echo JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC2')); ?></span>
					<?php endif;?>

					<?php if ($params->get("show_article",1)==1): ?>
						<span class="feature-desc"><?php echo $item->introtext; ?></span>
					<?php endif; ?>

					<?php if ($params->get("show_article_link",1)==1): ?>
						<?php if ($params->get('legacy_readmore',1)==1): ?>
							<div class="clr"></div><div class="readon-wrap1"><div class="readon1-l"></div><a href="<?php echo $item->link; ?>" class="readon-main"><span class="readon1-m"><span class="readon1-r"><?php echo JText::_('ROKSTORIES_READMORE'); ?></span></span></a></div><div class="clr"></div>
						<?php endif;?>
						<?php if ($params->get('legacy_readmore',1)==0): ?>
							<a href="<?php echo $item->link; ?>" class="readon"><span><?php echo JText::_('ROKSTORIES_READMORE'); ?></span></a>
						<?php endif; ?>
					<?php endif; ?>
				</div>
		        <?php endforeach; ?>
				</div>
				
				<?php if ($contentOptions): ?>
				<div class="feature-block-bottom"><div class="feature-block-bottom2"></div><div class="feature-block-bottom3"></div></div>
				<?php endif; ?>
			</div>
		</div>
		<div class="feature-circles">
			<?php $i = 0; foreach ($list as $item): ?>
				<?php 
					if ($i == $params->get('startElement', 0)) $class = ' active';
					else $class = '';
				?>
		    	<span class="feature-circles-sub<?php echo $class; ?>"><span>*</span></span>
				<?php $i++; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>