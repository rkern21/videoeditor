<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Mambot.MambotHelper');

class AriMambotBase extends AriObject 
{
	var $_notPublishProcessing = false;
	var $_tag;
	var $_type = 'content';
	var $_params;
	var $_published;
	var $_row;
	var $_processParams;
	var $_page;
	
	function __construct($tag, $type = 'content')
	{
		$this->_tag = $tag;
		$this->_type = $type;
	}
	
	function processContent($published, &$row, &$params, $page = 0)
	{
		$this->_published = $published;
		$this->_row =& $row;
		$this->_processParams =& $params;
		$this->_page = $page;
		
		$tag = $this->_tag;
		$text = is_object($row) ? $row->text : $row;
		if (strpos($text, $tag) === false) return true;
		
		$needProcessTags = ($published || $this->_notPublishProcessing);
		
		if (!$needProcessTags) return true;

		if ($needProcessTags) $this->registerJs();
		
		$callback = array(&$this, $needProcessTags ? 'replaceCallback' : 'emptyReplaceCallback');
		$text = AriMambotHelper::processTagList($text, $tag, $callback);

		$this->updateContent($row, $text);

		return true;
	}
	
	function updateContent(&$row, $content)
	{
		if (is_object($row))
		{
			$row->text = $content;
		}
		else
		{
			$row = $content;
		}
	}
	
	function registerJs()
	{
		static $isJsRegistered;

		if (!isset($isJsRegistered))
		{
			$this->jsDeclaration();
		
			$isJsRegistered = true;
		}
	}
	
	function jsDeclaration()
	{
		echo '';
	}

	function replaceCallback($attrs)
	{
		return '';
	}

	function emptyReplaceCallback($attrs)
	{
		return '';
	}
	
	function getParameters()
	{
		if (!$this->_params)
		{
			$this->_params = AriMambotHelper::getParameters($this->_tag, $this->_type);
		}
		
		return $this->_params;
	}

	function generateMambotString($params, $innerContent = null, $ignoreParams = null)
	{
		if (empty($ignoreParams)) $ignoreParams = array();
		$list = array();
		if (is_array($params))
		{
			$list = $params;  
		}
		else if (is_object($params))
		{
			$vars = get_class_vars(get_class($params));
			if ($vars)
			{
				foreach ($vars as $name => $value)
				{
					if (strpos($name, '_') !== 0)
					{
						$list[$name] = $value;
					}
				}
			}
		}
		
		$mambotString = '';
		foreach ($list as $key => $value)
		{
			if (in_array($key, $ignoreParams)) continue ;
			$mambotString .= sprintf(' %s="%s"', $key, $value); 
		}
		
		return empty($innerContent) 
			? sprintf('{%s%s}', $this->_tag, $mambotString)
			: sprintf('{%1$s%2$s}%3$s{/%1$s}', $this->_tag, $mambotString, $innerContent);
	}
}
?>