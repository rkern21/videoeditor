<?php
/**
 * FadeGallery Joomla! 1.5 Native Component
 * @version 1.3.0
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/


// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

function com_uninstall()
{
	
$filestodelete=array();

	//Module to update
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'index.html';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'mod_fadegallery.php';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'mod_fadegallery.xml';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery';
	
	//Plugin to update
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery.php';
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery.xml';
	
	$filestodelete[]=JPATH_SITE.DS.'media'.DS.'system'.DS.'js'.DS.'fadegallery.js';
	
	//Images
	$filestodelete[]=JPATH_SITE.DS.'images'.DS.'fadegallery'.DS.'img1.jpg';
	$filestodelete[]=JPATH_SITE.DS.'images'.DS.'fadegallery'.DS.'img2.jpg';
	$filestodelete[]=JPATH_SITE.DS.'images'.DS.'fadegallery'.DS.'img3.jpg';
	$filestodelete[]=JPATH_SITE.DS.'images'.DS.'fadegallery'.DS.'img4.jpg';
	$filestodelete[]=JPATH_SITE.DS.'images'.DS.'fadegallery';
	
	foreach($filestodelete as $file)
	{
		if(file_exists($file))
		{
			if(is_dir($file))
			{
				rmdir($file);
				//echo $file.' - folder deleted<br>';
			}
			else
			{
				unlink($file);
				//echo $file.' - file deleted<br>';
			}
		}
		//else
			//echo $file.' - not found<br>';
		
	}
	
	
	$db	= & JFactory::getDBO();
	//Delete gallery table if empty
	$query = 'SELECT count(*) FROM `#__fadegallery`';
	$db->setQuery( $query );
	$total_rows = $db->loadResult();
	
	if($total_rows==0)
	{
		$query ='DROP TABLE `#__fadegallery`';
		$db->setQuery( $query );
		if (!$db->query())    die( $db->stderr());
	}
	
	
	//Delete plugin
	$query = 'SELECT count(*) FROM #__plugins WHERE `element`="fadegallery"';
	$db->setQuery( $query );
	$total_rows = $db->loadResult();
	
	if($total_rows!=0)
	{
		$query ='DELETE FROM `#__plugins` WHERE `element`="fadegallery"';
		$db->setQuery( $query );
		if (!$db->query())    die( $db->stderr());
	}
	
	//Delete module
	$query = 'SELECT id FROM #__modules WHERE `module`="mod_fadegallery"';
	$db->setQuery( $query );
	$rows = $db->loadObjectList();
	
	if(count($rows)>0)
	{
		$id=$rows[0]->id;
		
		$query ='DELETE FROM `#__modules` WHERE `module`="mod_fadegallery"';
		$db->setQuery( $query );
		if (!$db->query())    die( $db->stderr());
		
		$query ='DELETE FROM `#__modules_menu` WHERE `moduleid`='.$id;
		$db->setQuery( $query );
		if (!$db->query())    die( $db->stderr());
	}
	
	
	echo '<p>Fade Gallery Extension has been deleted.</p>';
}

?>
