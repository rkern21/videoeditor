<?php
/*
 * ARI Sexy Lightbox
 *
 * @package		ARI Sexy Lightbox
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2010 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('SexyLightbox.Models.SexyLightboxModel');

class AriSexyLightboxIcontentModel extends AriSexyLightboxModel
{
	function execute($modelParams, $params, $templatePath)
	{
		$modelParams['modal'] = $params['_default']['modal'];
		$modelParams['group'] = $params['_default']['groupName'];
		$modelParams['bgColor'] = isset($params['_default']['bgColor']) ? $params['_default']['bgColor'] : '';
		
		parent::execute($modelParams, $params, $templatePath);
	}
}
?>