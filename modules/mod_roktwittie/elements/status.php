<?php
/**
 * RokTwittie Module
 *
 * @package RocketTheme
 * @subpackage roktwittie
 * @version   2.0 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
defined('_JEXEC') or die();

class JElementStatus extends JElement
{
	const COLOR_GREEN = 1;
	const COLOR_YELLOW = 2;
	const COLOR_RED = 3;
	
	private $_colors = array(self::COLOR_GREEN => 'green', self::COLOR_YELLOW => '#FF9900', self::COLOR_RED => 'red');
	
	public function fetchElement($name, $value, $node, $control_name)
	{
		if (!extension_loaded('curl')) {
			return $this->getStatus('CURL extension is not enabled, contact your administrator.', self::COLOR_RED);
		}
	
		if (!$this->_parent->get('use_oauth', 0)) {
			return $this->getStatus('Using anonymous mode.', self::COLOR_GREEN);
		}
	
		if (!$this->_parent->get('consumer_key', '') || !$this->_parent->get('consumer_secret')) {
			return $this->getStatus('Consumer keys are not setup! Using anonymous mode.', self::COLOR_RED);
		}
	
		if (!$this->_parent->get('oauth_token') || !$this->_parent->get('oauth_token_secret')) {
			return $this->getStatus('Authentication is not completed! Using anonymous mode.', self::COLOR_YELLOW);
		}
		
		return $this->getStatus('Using authenticated mode.', self::COLOR_GREEN);
	}
	
	private function getStatus($message, $color = self::COLOR_GREEN)
	{
		return '<span style="color:' . $this->_colors[$color] . '">' . $message . '</span>';
	}
}