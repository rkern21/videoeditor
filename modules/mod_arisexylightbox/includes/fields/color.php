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

AriKernel::import('Web.JSON.JSONHelper');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldColor extends JFormField
{
	protected $type = 'Color';
	
	function getInput()
	{
		return $this->fetchElement($this->element['name'], $this->value, $this->element, $this->name);
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
		$this->_includeAssets();

		$ctrlId = str_replace(array('[', ']'), array('_', ''), $control_name);
		$rgbColor = $this->_parseColor($value);
		$uri = $this->_getRootAssetsUri();
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration(
			sprintf('window.addEvent("domready", function(){ var opt = %2$s; opt.onComplete = function(color) { $("%1$s").value = color.hex; }; new MooRainbow("%1$s", opt); });',
				$ctrlId,
				AriJSONHelper::encode(
					array(
						'wheel' => true, 
						'imgPath' => $uri . 'images/', 
						'id' => uniqid('cp_'),
						'startColor' => $rgbColor
					))));
				
		return '<div style="float: left"><input type="text" name="' . $control_name . '" id="' . $ctrlId . '" value="' . $value . '" size="10" readonly="readonly" onclick="$(window).scrollTo($(window).getScrollLeft(), $(this).getTop());" /><span style="margin: 6px 5px 5px 0; display: block; float: left;">[<a href="javascript:void(0);" onclick="$(\'' . $ctrlId . '\').value = \'\'; return false;">Clear</a>]</span></div>'; 
	}

	function _parseColor($color)
	{
		$rgb = array(0, 0, 0);

		if (empty($color))
			return $rgb;
			
		$color = preg_replace('/[^A-F0-9]/i', '', $color);
		$len = strlen($color);
		if ($len != 3 && $len != 6)
			return $rgb;
			
		if ($len == 3)
			$color = preg_replace('/./', '$0$0', $color);
		
		$rgb[0] = hexdec(substr($color, 0, 2));
		$rgb[1] = hexdec(substr($color, 2, 2));
		$rgb[2] = hexdec(substr($color, 4, 2));
		
		return $rgb;
	}
	
	function _includeAssets()
	{
		static $loaded;
		
		if ($loaded)
			return ;
			
		$uri = $this->_getRootAssetsUri();
			
		$document =& JFactory::getDocument();
		$document->addScript($uri . 'mooRainbow.js');
		$document->addStyleSheet($uri . 'mooRainbow.css', 'text/css', null, array());
			
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
			
		$uri = JURI::root(true) . str_replace(DS, '/', $filePath) . '/color/';
		
		return $uri;
	}
}
?>