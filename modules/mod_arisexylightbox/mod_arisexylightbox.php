<?php
/*
 * ARI Sexy Lightbox Joomla! module
 *
 * @package		ARI Sexy Lightbox Joomla! module.
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2010 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__) . '/includes/kernel/class.AriKernel.php';

AriKernel::import('Utils.Utils2');
AriKernel::import('Joomla.JoomlaUtils');
AriKernel::import('Document.DocumentHelper');
AriKernel::import('Template.Template');
AriKernel::import('SimpleTemplate.SimpleTemplate');
AriKernel::import('Web.HtmlHelper');
AriKernel::import('Web.JSON.JSONHelper');
AriKernel::import('Parameters.ParametersHelper');
AriKernel::import('SexyLightbox.SexyLightbox');

$params->set('key', 'arisexybox_mod_' . $module->id);
$params->set('checkSum', md5($module->params));
$groupName = $params->get('groupName');
$thumbQuality = intval($params->get('thumbQuality', 80), 10);
if (!defined('ASIDO_GD_JPEG_QUALITY'))
	define('ASIDO_GD_JPEG_QUALITY', $thumbQuality);

if (empty($groupName)) $params->set('groupName', uniqid('asexy_'));
$autoShow = $params->get('autoShow');
$showOnClose = $params->get('showOnClose');
$mParams = AriParametersHelper::flatParametersToArray($params);
AriSexyLightboxHelper::includeAssets(
	AriUtils2::parseValueBySample($params->get('includeJQuery'), true),
	AriUtils2::parseValueBySample($params->get('noConflict'), true),
	AriUtils2::parseValueBySample($params->get('jQueryVer', '1.4.4'), '1.4.4'),
	AriUtils2::getParam($mParams, 'opt', array()));

AriSexyLightboxHelper::activateAutoShow(
	trim($params->get('groupName', '')), 
	$autoShow, 
	$params->get('unique', 'aslmod_' . $module->id),
	AriUtils2::getParam($mParams, 'cookie'));
	
AriSexyLightboxHelper::activateAutoShowOnClose(
	trim($params->get('groupName', '')),
	$params->get('onCloseMessage'), 
	$showOnClose, 
	$params->get('unique', 'aslmod_' . $module->id),
	AriUtils2::getParam($mParams, 'cookie'));

$activeType = $params->get('activeType', 'imagelist');
AriSexyLightboxHelper::executeModel($activeType, $mParams, dirname(__FILE__) . DS . 'includes' . DS . 'templates' . DS);
?>