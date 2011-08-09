<?php
/**
* Fade Javascript Image Gallery Joomla! 1.5 Native Component
* @version 1.2.5
* @author DesignCompass corp <admin@designcompasscorp.com>
* @link http://www.designcompasscorp.com
* @license GNU/GPL **/


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
class FadeGalleryViewGalleries extends JView
{
    function display($tpl = null)
    {

		global $mainframe;
		
		

		JToolBarHelper::title(JText::_('Fade Gallery - Galleries'), 'generic.png');
		
		
		JToolBarHelper::addNewX('newItem');
		
		JToolBarHelper::customX( 'copyItem', 'copy.png', 'copy_f2.png', 'Copy', true);
		JToolBarHelper::deleteListX();


		$db = & JFactory::getDBO();

		$context			= 'com_fadegallery.galleries.';

		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		's.id',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',			'word' );
		
		$search				= $mainframe->getUserStateFromRequest( $context.'search',			'search',			'',			'string' );
		
		$limit		= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );

		$where = array();

		if ($search)
		{
			$where[] = 'LOWER(s.galleryname) LIKE '.$db->Quote( '%'.$db->getEscaped($search,true).'%', false );

		}

		$where		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		
		$orderby	= 'ORDER BY '. $filter_order .' '. $filter_order_Dir ;
		
		$query = 'SELECT COUNT(*)'
		. ' FROM #__fadegallery AS s '
		. $where
		;
		$db->setQuery( $query );
		if (!$db->query())    echo ( $db->stderr());
		$total = $db->loadResult();
		
		//echo $total;
		//echo 'total='.$total;exit;

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT s.* FROM #__fadegallery AS s '
		. $where 
		. $orderby
		;

		
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit );
		if (!$db->query())    echo ( $db->stderr());
		$rows = $db->loadObjectList();

		$javascript		= 'onchange="document.adminForm.submit();"';

		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		$this->assignRef('items',		$rows);
		$this->assignRef('pagination',		$pageNav);
		$this->assignRef('lists',		$lists);

		parent::display($tpl);
    }
}
?>