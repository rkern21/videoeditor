<?php
/*
 * ARI Framework Lite
 *
 * @package		ARI Framework Lite
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('_JEXEC') or die ('Restricted access');

require_once dirname(__FILE__) . '/../kernel/class.AriKernel.php';

AriKernel::import('Utils.Utils');
AriKernel::import('Utils.Utils2');
AriKernel::import('Parameters.ParametersHelper');
AriKernel::import('Web.JSON.JSONHelper');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldElementscloner extends JFormField
{
	protected $type = 'Elementscloner';

	function getInput()
	{
		return $this->fetchElement($this->element['name'], $this->value, $this->element, $this->name);
	}
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$this->_includeAssets();
		
		$id = $control_name . $name;
		$layout = $this->_getLayout($node, $id);
		$cssFile = $node->attributes('css_file');
		$keyField = $node->attributes('key_field');

		$document =& JFactory::getDocument();
		if (!empty($cssFile))
			$document->addStyleSheet(JURI::root(true) . $cssFile);
			
		if ($value && $control_name == 'refField_params')
			$value = html_entity_decode($value);

		$document->addScriptDeclaration(sprintf(
			'window.addEvent("domready", function(){ new ARIElementsCloner("%1$s_cloner", %2$s, %3$s, %4$s); });',
			$id,
			AriJSONHelper::encode(array('hiddenId' => $id, 'keyField' => $keyField)),
			AriJSONHelper::encode($this->_getClonerOptions($node)),
			$value ? addcslashes($value, "\n\r") : 'null'
		));
		
		return $layout 
			. '<input type="hidden" name="' . $control_name . '[' . $name . ']' . '" id="' . $id . '" value="' . str_replace('"', '&quot;', $value) . '" />';;
	}
	
	function _getClonerOptions(&$node)
	{
		$attrs = $node->attributes();
		$optAttrs = array();
		foreach ($attrs as $key => $value)
		{
			if (strpos($key, 'opt_') !== 0)
				continue;
				
			$optAttrs[$key] = JText::_($value);
		}

		$params = AriParametersHelper::flatParametersToArray($optAttrs);
		$clonerOptions = AriParametersHelper::getUniqueOverrideParameters(
			array(
				'numFormat' => '#{$num}.',
				'enableNumFormat' => true,
				'defaultItemCount' => 3,
				'message' => array(
					'removeConfirm' => 'Are you sure you want to remove this item?',
					'removeAllConfirm' => 'Are you sure you want to remove all items?'
				)
			), 
			isset($params['opt']) ? $params['opt'] : array(),
			true);
			
		return count($clonerOptions) > 0 ? $clonerOptions : new stdClass();
	}
	
	function _getLayout($node, $id)
	{
		$layout = '';
		if (!isset($node->layout))
			return $layout;
			
		$layout = $node->layout[0]->data();
		$layout = str_replace('{$id}', $id . '_cloner', $layout);
		
		$layout = preg_replace_callback('/@@(.+?)@@/i', array(&$this, 'updateLayoutCallback'), $layout);
			
		return $layout;
	}
	
	function updateLayoutCallback($matches) 
	{
		return !empty($matches[1]) ? JText::_($matches[1]) : '';
	}
	
	function _includeAssets()
	{
		static $loaded;
		
		if ($loaded)
			return ;
			
		$uri = $this->_getRootAssetsUri();
			
		$document =& JFactory::getDocument();
		$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
		$document->addScript($uri . 'jquery.noconflict.js');
		$document->addScript($uri . 'jquery.cloner.js');
		$document->addScript($uri . 'cloner.js');

		$loaded = true;
	}
	
	function _getRootAssetsUri()
	{
		static $uri;
		
		if (!is_null($uri))
			return $uri;
		
		$filePath = str_replace(DS == '\\' ? '/' : '\\', DS, dirname(__FILE__));
		if (strlen(JPATH_ROOT) > 1)
			$filePath = str_replace(JPATH_ROOT, '', $filePath);
			
		$uri = JURI::root(true) . str_replace(DS, '/', $filePath) . '/elementscloner/';
		
		return $uri;
	}
}
?>