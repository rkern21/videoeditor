<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

$template = $params['template'];
$repeater = $params['repeater'];
?>

<div class="ari_lightbox_container">
<?php
	echo $template;

	$repeater->render();
?>
</div>