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

class AriModuleModelBase extends AriObject
{
	var $_modelName;
	var $_prefix = '';
	
	function execute($modelParams, $params, $templatePath)
	{
		$modelName = $this->getModelName();

		AriTemplate::display($templatePath . strtolower($modelName) . '.html.php', $modelParams);
	}
	
	function getModelName()
	{
		if (isset($this->_modelName))
			return $this->_modelName;
		
		$className = get_class($this);
		$matches = array();
		if (preg_match('/' . $this->_prefix . '(.+)Model/i', $className, $matches))
			$this->_modelName = ucfirst($matches[1]);
		else
			$this->_modelName = 'Error';
		
		return $this->_modelName;
	}
}
?>