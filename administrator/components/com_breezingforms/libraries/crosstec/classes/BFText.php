<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
final class BFText {
	
	const COMPONENT_NAME = 'com_breezingforms';
	
	/**
	 * @var JLanguage
	 */
	private $language = null;

	/**
	 * @var BFText
	 */
	static private $bftext = null;
	
	/**
	 * Constructor is private, only BFText::_(string) is allowed to be used from outside
	 */
	private function __construct(){}
	
	/**
	 * Returns a new BFText object containing the language object.
	 * 
	 * @return BFText
	 */
	private function getInstance(){
		if(!(self::$bftext instanceof BFText)){
			self::$bftext = new BFText();
			self::$bftext->language = JFactory::getLanguage();
		}
		
		return self::$bftext; 
	}
	
	/**
	 * BFText::_(string) does the same like JText::_(string), except that it reloads the
	 * language for com_breezingforms if a key is not set. A key could be not set from within the HTML_* view functions because the language
	 * is not loaded there. So if one text request comes from one of these view functions, 
	 * it makes sure that the language is always been loaded (of course in a lazy way).
	 *
	 * @param string
	 * @return string
	 */
	public static function _($name){
		$bftext = BFText::getInstance();
		if(!$bftext->language->hasKey($name)){
			$bftext->language->load(BFText::COMPONENT_NAME);
		}
		
		// ok, loaded and ready to go
		return JText::_($name);
	}
	
}