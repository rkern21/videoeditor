<?php
/**
 * RokCandy Macros RokCandy Macro Table
 *
 * @package		Joomla
 * @subpackage	RokCandy Macros
 * @copyright Copyright (C) 2009 RocketTheme. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author RocketTheme, LLC
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Weblink Table class
*
* @package		Joomla
* @subpackage	Weblinks
* @since 1.0
*/
class TableCandyMacro extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	/**
	 * @var int
	 */
	var $catid = null;

    /**
	 * @var text
	 */
	var $macro = null;
	
	/**
   	 * @var text
   	 */
   	var $html = null;

	/**
	 * @var int
	 */
	var $published = null;

	/**
	 * @var boolean
	 */
	var $checked_out = 0;

	/**
	 * @var time
	 */
	var $checked_out_time = 0;

	/**
	 * @var int
	 */
	var $ordering = null;

	/**
	 * @var string
	 */
	var $params = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__rokcandy', 'id', $db);
	}

	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}
		
	    if (key_exists('macro', $array )) $array['macro'] = trim($array['macro']);
	    if (key_exists('html', $array )) $array['html'] = trim($array['html']);

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check()
	{

		/** check for valid name */
		if (trim($this->macro) == '' or trim($this->html == '')) {
			$this->setError(JText::_('Your RokCandy Macro must contain both poritions'));
			return false;
		}

		/** check for existing macro name */
		$query = 'SELECT id FROM #__rokcandy WHERE macro = '.$this->_db->Quote($this->macro);
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
	
		if ($xid && $xid != intval($this->id)) {
			$this->setError(JText::_('Existing Macro Exists with the same macro value'));
			return false;
		}

		return true;
	}
}
