<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

$id = uniqid('asg_', false);
$repeater = $params['repeater'];
$modParams = $params['params'];

$slickGalleryPath = JPATH_ROOT . DS . 'modules' . DS . 'mod_arislickgallery' . DS . 'mod_arislickgallery' . DS . 'kernel' . DS . 'class.AriKernel.php';

require_once $slickGalleryPath;

AriKernel::import('SlickGallery.SlickGallery');

$sgParams = new JParameter('');
$sgParams->def('includeJQuery', '0');
$sgParams->def('width', intval($modParams['width'], 10));
$sgParams->def('height', intval($modParams['height'], 10));

$showTitle = (bool)AriUtils::getParam($modParams, 'showTitle', true);
if (!$showTitle)
{
	$cssStyles = '#' . $id . ' .arislickgallery-title{text-indent:-9999em}';

	$document =& JFactory::getDocument();
	$document->addStyleDeclaration($cssStyles);
}

AriSlickGalleryHelper::initGallery($id, $sgParams);
?>

<div class="ari-slick-gallery" id="<?php echo $id; ?>">
<?php
$repeater->render();
?>
</div>