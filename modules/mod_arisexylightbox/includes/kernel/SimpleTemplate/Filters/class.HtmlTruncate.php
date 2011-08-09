<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('SimpleTemplate.Filters.FilterBase');
AriKernel::import('Text.Text');

class AriSimpleTemplateHtmlTruncateFilter extends AriSimpleTemplateFilterBase
{	
	function getFilterName()
	{
		return 'html_truncate';
	}

	function parse($string, $length = null, $addstring = '...')
	{
		$length = @intval($length, 10);
		if ($length < 1 || empty($string) || strlen($string) <= $length) 
			return $string;
		
		if (empty($addstring)) $addstring = '';		

		$isText = true; 
		$ret = ''; 
		$i = 0; 

		$currentChar = ''; 
		$lastSpacePosition = -1; 
		$lastChar = ''; 

		$tagsArray = array(); 
		$currentTag = ''; 
		$tagLevel = 0; 

		$noTagLength = strlen(strip_tags($string)); 

		// Parser loop 
		for ($j = 0; $j < strlen($string); $j++) 
		{ 
			$currentChar = substr($string, $j, 1); 
			$ret .= $currentChar; 

			// Lesser than event 
			if ($currentChar == "<") 
				$isText = false; 

			// Character handler 
			if ($isText) 
			{ 
				// Memorize last space position 
				if ($currentChar == '') 
					$lastSpacePosition = $j; 
				else
					$lastChar = $currentChar; 

				$i++; 
			} 
			else 
			{ 
				$currentTag .= $currentChar; 
			} 

			// Greater than event 
			if ($currentChar == ">") 
			{ 
				$isText = true; 

				// Opening tag handler 
				if ((strpos($currentTag, "<") !== false) && 
					(strpos($currentTag, "/>") === false) && 
					(strpos($currentTag, "</") === false)) 
				{ 
					// Tag has attribute(s) 
					if (strpos($currentTag, " ") !== false) 
					{ 
						$currentTag = substr($currentTag, 1, strpos($currentTag, " ") - 1); 
					} 
					else 
					{ 
						// Tag doesn't have attribute(s) 
						$currentTag = substr($currentTag, 1, -1); 
					} 

					array_push($tagsArray, $currentTag); 
				} 
				else if (strpos($currentTag, "</") !== false) 
				{ 
					array_pop($tagsArray); 
				} 

				$currentTag = ""; 
			} 

			if ($i >= $length) 
				break;
		} 

		// Cut HTML string at last space position 
		if ($length < $noTagLength) 
		{ 
			if ($lastSpacePosition != -1) 
			{ 
				$ret = substr($string, 0, $lastSpacePosition); 
			} 
			else 
			{ 
				$ret = substr($string, 0, $j); 
			} 
		} 

		// Close broken XHTML elements 
		while (sizeof($tagsArray) != 0) 
		{ 
			$aTag = array_pop($tagsArray); 
			$ret .= '</' . $aTag . '>'; 
		} 

		// only add string if text was cut 
		return $ret . $addstring; 
	}
}

new AriSimpleTemplateHtmlTruncateFilter();
?>