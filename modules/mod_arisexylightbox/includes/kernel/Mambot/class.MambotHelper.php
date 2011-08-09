<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Utils.Utils');

class AriMambotHelper extends AriObject
{
	function &_currentCallback(&$callback)
	{
		static $currentCallback = null;

		if ($callback !== false)
		{
			$currentCallback = $callback;
		}
		
		return $currentCallback;
	}
	
	function processTagList($text, $tag, &$callback)
	{
		$tagRegExp = '/\{' . $tag . '(\s+[a-z\_0-9]+=(?:"[^"]*"|&quot;.*?&quot;|[^\s}]*))*\s*\}(?:(.*?)\{\/' . $tag . '\})?/si';
		$className = __CLASS__;
		$false = false;
		$oldCallback = AriMambotHelper::_currentCallback($false);

		AriMambotHelper::_currentCallback($callback);
		$backtrack_limit = @ini_set("pcre.backtrack_limit", -1);
		
		$result = preg_replace_callback($tagRegExp, 
			create_function('$matches', 
				'return ' . $className . '::parseTagList($matches);'),
			$text);

		@ini_set("pcre.backtrack_limit", $backtrack_limit);

		AriMambotHelper::_currentCallback($oldCallback);

		return $result;
	}
	
	function parseTagList($matches)
	{
		$false = false;
		$callback =& AriMambotHelper::_currentCallback($false);

		if (!empty($callback) && isset($matches[0]))
		{
			$innerContent = isset($matches[2]) ? $matches[2] : '';
			$attrs = AriMambotHelper::parseAttributes($matches[0]);

			return call_user_func($callback, $attrs, $innerContent);
		}

		return '';
	}

	function parseAttributes($text)
	{
		$pos = strpos($text, '}');
		if ($pos > 0) $text = substr($text, 0, $pos);
		
		$attrRegExp = '/([a-z\_0-9]+)=("[^"]*"|&quot;.*?&quot;|[^\s]*)/i';
		$attrs = array();
		$matches = array();
		preg_match_all($attrRegExp, $text, $matches, PREG_SET_ORDER);
		if (is_array($matches))
		{
			foreach ($matches as $match)
			{
				if (isset($match[1]) && isset($match[2])) $attrs[$match[1]] = trim(html_entity_decode($match[2]), '"');
			}
		}

		return $attrs;
	}
	
	function getParameters($tag, $type = 'content')
	{
		global $database;

		$params = null;

		if (AriJoomlaBridge::isJoomla1_5())
		{
			$plugin =& JPluginHelper::getPlugin($type, $tag);
	    	$params = new JParameter(AriUtils::getParam($plugin, 'params', ''));
		}
		else
		{
			global $_MAMBOTS;
			
			$mambot = null;
			if (!isset($_MAMBOTS->_content_mambot_params[$tag]))
			{
				$query = sprintf('SELECT params FROM #__mambots WHERE element = %s AND folder = %s',
					$database->Quote($tag),
					$database->Quote($type));
			    $database->setQuery($query);
		    	$database->loadObject($mambot);
		    	
		    	$_MAMBOTS->_content_mambot_params[$tag] = $mambot;
			}
			else
			{
				$mambot = $_MAMBOTS->_content_mambot_params[$tag];
			}
	    	
	    	$params = new mosParameters(AriUtils::getParam($mambot, 'params', ''));
		}	

		return $params;
	}
}
?>