<?php
/**
 * FadeGallery Joomla! 1.5 Native Component
 * @version 1.2.5
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Initialize the controller
$controller = new FadeGalleryController();
$controller->execute( null );

// Redirect if set by the controller
$controller->redirect();

?>