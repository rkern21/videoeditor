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

class AriSexyLightboxArticleModel extends AriSexyLightboxModel
{
	function execute($modelParams, $params, $templatePath)
	{
		$modelParams['modal'] = $params['_default']['modal'];
		$modelParams['group'] = $params['_default']['groupName'];
		$modelParams['bgColor'] = isset($params['_default']['bgColor']) ? $params['_default']['bgColor'] : '';
		$modelParams['idList'] = $this->getIdList($modelParams);

		parent::execute($modelParams, $params, $templatePath);
	}
	
	function getIdList($modelParams)
	{
		$articleIdList = array();
		$tempIdList = AriUtils2::getParam($modelParams, 'id');
		$tempIdList = preg_split("/[\s,;]+/i", $tempIdList);
		if (empty($tempIdList))
			return $articleIdList;
			
		foreach ($tempIdList as $id)
		{
			if (empty($id))
				continue ;

			$articleId = intval($id, 10);
			if ($articleId > 0)
				$articleIdList[] = $articleId;
		}

		return $articleIdList;
	}
}
?>