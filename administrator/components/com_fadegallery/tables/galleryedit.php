<?php
/**
* Fade Javascript Image Gallery Joomla! 1.5 Native Component
* @version 1.2.5
* @author DesignCompass corp <admin@designcompasscorp.com>
* @link http://www.designcompasscorp.com
* @license GNU/GPL **/



// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class TableGalleryEdit extends JTable
{

	var $id = null;
	var $galleryname = null;
	var $folder = null;
	var $filelist = null;
	var $width = null;
	var $height = null;
	var $interval = null;
	var $fadetime = null;
	var $fadestep = null;
	var $align=null;
	var $padding=null;
	var $cssstyle=null;
	
	function TableGalleryEdit(& $db)
	{
		parent::__construct('#__fadegallery', 'id', $db);
	}

}

?>