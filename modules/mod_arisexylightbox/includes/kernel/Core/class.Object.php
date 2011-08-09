<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Core.Error');

class AriObject
{
	var $_lastError = null;
	var $_configProps = array();
	
	function AriObject()
	{
		$args = func_get_args();
		call_user_func_array(array(&$this, '__construct'), $args);
	}
	
	function __construct()
	{
		
	}
	
	function _registerErrorHandler()
	{
		set_error_handler(array(&$this, 'errorHandler'));
	}
	
	function errorHandler($errNo, $errStr, $errFile, $errLine)
	{
		$stopPhpHandler = false;
		switch ($errNo)
		{
			case E_USER_ERROR:
				$this->_lastError = new AriError($errStr, $errFile, $errLine);
				$stopPhpHandler = true;
				break;
		}
		
		return $stopPhpHandler;
	}
	
	function getLastErrorMsg($clear = true)
	{
		if ($this->_isError(false, false))
		{
			$msg = $this->_lastError->error;
			if ($clear) $this->_lastError = null;
			
			return $msg;
		}
	}
	
	function _isError($clear = TRUE, $raised = TRUE)
	{
		$error = $this->_lastError;
		$isError = $error !== null; 		
		
		if ($isError)
		{
			if ($clear)
			{
				$this->_lastError = null;
			}
	
			if ($raised)
			{
				$this->_raiseError($error);
			}
		}
		
		return $isError;
	}
	
	function _raiseError($error)
	{
		
	}

	function extendConfig($newProps)
	{
		if (!is_array($newProps) || count($newProps) < 1) return ;
		
		foreach ($newProps as $key => $value)
		{
			$val =& $newProps[$key];
			$this->_configProps[$key] =& $val;
		}
	}
	
	function bindConfig($props)
	{
		$this->bindPropertiesToProperty($props, $this->_configProps);
	}
	
	function getConfigValue($key)
	{
		return isset($this->_configProps[$key]) ? $this->_configProps[$key] : null;
	}
	
	function setConfigValue($key, $value)
	{
		if (key_exists($key, $this->_configProps)) $this->_configProps[$key] = $value;
	}
	
	function bindPropertiesToProperty($props, &$prop)
	{
		if (!is_array($prop)) return ;
		
		foreach ($prop as $key => $value)
		{
			if (isset($props[$key])) $prop[$key] = $props[$key];
		}
	}
	
	function bindProperties($props)
	{
		if (!is_array($props)) return ;
		
		$vars = get_class_vars(get_class($this));
		if ($vars)
		{
			foreach ($vars as $name => $value)
			{
				if (isset($props[$name])) $this->$name = $props[$name];
			}
		}
	}

	function getClassName()
	{
		$class = isset($this) ? strtolower(get_class($this)) : null;
		/*
		$backtrace = debug_backtrace();
        $class = $backtrace[0]['class'];
		*/

		return $class;
	}
}
?>
