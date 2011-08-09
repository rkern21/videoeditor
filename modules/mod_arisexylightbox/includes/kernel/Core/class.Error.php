<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

class AriError
{
	var $error;
	var $file;
	var $line;
	
	function AriError($error, $file = null, $line = null)
	{
		$this->error = $error;
		$this->file = $file;
		$this->line = $line; 
	}
}
?>
