<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

$repeater = $params['repeater'];
$firstImage = $params['firstImage'];
?>

<?php
	echo $firstImage;

	$repeater->render();
?>