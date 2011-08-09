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
AriKernel::import('Module.ModuleHelper');

class AriSexyLightboxModuleModel extends AriSexyLightboxModel
{
	function execute($modelParams, $params, $templatePath)
	{
		$module =& AriModuleHelper::getModuleById(AriUtils2::getParam($modelParams, 'id'));
		if (empty($module))
			return ;
			
		$content = AriModuleHelper::renderModule($module);
		if (empty($content))
			return ;

		$modelParams['text'] = $content;
		$modelParams['modal'] = $params['_default']['modal'];
		$modelParams['group'] = $params['_default']['groupName'];
		$modelParams['bgColor'] = isset($params['_default']['bgColor']) ? $params['_default']['bgColor'] : '';
		
		parent::execute($modelParams, $params, $templatePath);
	}
}
?>