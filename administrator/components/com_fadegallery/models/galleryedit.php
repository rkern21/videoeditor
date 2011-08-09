<?php
/**
* Fade Javascript Image Gallery Joomla! 1.5 Native Component
* @version 1.2.5
* @author DesignCompass corp <admin@designcompasscorp.com>
* @link http://www.designcompasscorp.com
* @license GNU/GPL **/


// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

class FadeGalleryModelGalleryEdit extends JModel
{

	
    function __construct()
    {
		
		
		parent::__construct();
		$array = JRequest::getVar('cid',  0, '', 'array');
		

		$this->setId((int)$array[0]);
    }

	function setId($id)
	{
		// Set id and wipe data

		$this->_id	= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		//echo 'fff='.$this->_id.'<br>';
		$row =& $this->getTable();
		$row->load( $this->_id );
		return $row;
	}

	function store()
	{
	    
	
		$row =& $this->getTable();
		// consume the post data with allow_html
		$data = JRequest::get( 'post',JREQUEST_ALLOWRAW);
		$post = array();

		if (!$row->bind($data))
		{
			return false;
		}

		// Make sure the  record is valid
		if (!$row->check())
		{
			return false;
		}

		// Store
		if (!$row->store())
		{
			return false;
		}

		//Create MySQL Table
		$db = &$this->getDBO();
		

		return true;
	}

	function ConfirmRemove()
	{
		
		$cancellink='index.php?option=com_fadegallery&controller=galleries';
		
		//$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		if(count($cids)==0)
			return false;
		
		$deletelink='index.php?option=com_fadegallery&controller=galleries&task=remove_confirmed';
		
		foreach($cids as $cid)
		$deletelink.='&cid[]='.$cid;
		
		

		//		$db = & JFactory::getDBO();
		
		//Get Table Name
		
		if (count( $cids ))
		{
			echo '<p>'.JText::_( 'DELETE GALLERY_S?' ).' (ID='.(count($cids)>1 ? implode(',',$cids) : $cids[0] ).') <a href="'.$cancellink.'">'.JText::_( 'NO. CANCEL.' ).'</a></p>
		
			<a href="'.$deletelink.'">'.JText::_( 'YES. DELETE.' ).'</a>
		';
		}
		else
		{
			
			echo '<p><a href="'.$cancellink.'">'.JText::_( 'NO GALLERIES SELECTED' ).'</a></p>';
		}
		
		
		
	}
	function delete($cids)
	{
		$db = & JFactory::getDBO();
		
		$row =& $this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid)
			{
						
				
				if (!$row->delete( $cid ))
				{
					return false;
				}
			}
		}
		
		
		
		return true;
	}
	

	function copyItem($cid)
	{

	    $item =& $this->getTable();
	    

	    foreach( $cid as $id )
	    {
			
		
		$item->load( $id );
		$item->id 	= NULL;
		
			$old_gallery=$item->galleryname;
			$new_gallery='copy_of_'.$old_gallery;
		
		$item->galleryname 	= $new_gallery;
			
	
		
		if (!$item->check()) {
			return false;
		}

		if (!$item->store()) {
			return false;
		}
		$item->checkin();
			
	    }
		
		return true;
	}
	

	

}

?>