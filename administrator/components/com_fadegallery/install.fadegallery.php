<?php
/**
 * FadeGallery Joomla! 1.5 Native Component
 * @version 1.2.8
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

function com_install()
{
	jimport('joomla.filesystem.file');

	$filestodelete=array();

	//Module to update
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'index.html';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'mod_fadegallery.php';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'mod_fadegallery.xml';
	
	//Module to remove indipendant module
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'dot.png';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'fadegalleryclass.php';
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery'.DS.'mod_fadegallery.js';
	
	$filestodelete[]=JPATH_SITE.DS.'modules'.DS.'mod_fadegallery';
	
	//Plugin to update
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery.php';
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery.xml';
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery';
	
	//Plugin to remove indipendant plugin
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery'.DS.'dot.png';
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery'.DS.'fadegallery.js';
	$filestodelete[]=JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery'.DS.'fadegalleryclass.php';
	
	$filestodelete[]=JPATH_SITE.DS.'media'.DS.'system'.DS.'js'.DS.'fadegallery.js';
	
	foreach($filestodelete as $file)
	{
		if(file_exists($file))
		{
			if(is_dir($file))
				rmdir($file);
				
			else
				unlink($file);
		}
		
	}	
	
		
	rename(JPATH_SITE.DS.'components'.DS.'com_fadegallery'.DS.'module',JPATH_SITE.DS.'modules'.DS.'mod_fadegallery');
	rename(JPATH_SITE.DS.'components'.DS.'com_fadegallery'.DS.'plugin'.DS.'fadegallery.php',JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery.php');
	rename(JPATH_SITE.DS.'components'.DS.'com_fadegallery'.DS.'plugin'.DS.'fadegallery.xml',JPATH_SITE.DS.'plugins'.DS.'content'.DS.'fadegallery.xml');
	
	rename(JPATH_SITE.DS.'components'.DS.'com_fadegallery'.DS.'fadegallery.js',JPATH_SITE.DS.'media'.DS.'system'.DS.'js'.DS.'fadegallery.js');

	rmdir(JPATH_SITE.DS.'components'.DS.'com_fadegallery'.DS.'plugin');
	
	if(!file_exists(JPATH_SITE.DS.'images'.DS.'fadegallery'))
	{
		rename(JPATH_SITE.DS.'components'.DS.'com_fadegallery'.DS.'fadegallery',JPATH_SITE.DS.'images'.DS.'fadegallery');
	}
	

	if (file_exists(JPATH_SITE.DS."components".DS."com_fadegallery".DS."fadegallery.php"))
       	{

		echo '<h1>FadeGallery 1.3.0 installed succesfully</h1>
		<p>To see how it works, go to Module Manager and enable Fade Gallery. Sure you can change images, dimension, fade time e.t.c.</p>
		<p>This package contains Component, Module, and Plugin.</p>
		<p>For more info go to Components/Fade Gallery/Documentation.</p>
		
		
		<div style="text-align:right;"><a href="http://www.designcompasscorp.com/index.php?option=com_content&view=article&id=508&Itemid=709" target="_blank"><img src="../components/com_fadegallery/images/compasslogo.png" border=0></a></div>';
	}
	else
	{
		echo '<font color="red">Sorry, something went wrong while installing FadeGallery on your web site</font>';
	}
	
	$db	= & JFactory::getDBO();
	//Add plugin
	$query = 'SELECT count(*) FROM #__plugins WHERE `element`="fadegallery"';
	$db->setQuery( $query );
	$total_rows = $db->loadResult();
	
	if($total_rows==0)
	{
		$query ='INSERT `#__plugins` SET `name`="Content - Fade Gallery", `element`="fadegallery", `folder`="content", `published`=1';
		$db->setQuery( $query );
		if (!$db->query())    die( $db->stderr());
	}

	//Add module
	$query = 'SELECT count(*) FROM #__modules WHERE `module`="mod_fadegallery"';
	$db->setQuery( $query );
	$total_rows = $db->loadResult();
	if($total_rows==0)
	{
		$query ='INSERT `#__modules` SET '
			.' `title`="Fade Gallery", '
			.' `position`="left", '
			.' `published`=0, '
			.' `module`="mod_fadegallery", '
			.' `params`="folder=images/fadegallery
width=180
height=90
interval=6000
fadetime=2000
fadestep=20"';
			
		$db->setQuery( $query );
		if (!$db->query())    die( $db->stderr());
		
		//Add menu Items

		$query = 'SELECT id FROM #__modules WHERE '
			.' `title`="Fade Gallery" AND'
			.' `module`="mod_fadegallery" AND'
			.' `position`="left" AND'
			.' `published`=0'
			.' LIMIT 1';
			
			
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if(count($rows)==1)
		{
			$id=$rows[0]->id;
			
			$query = 'SELECT count(*)  FROM #__modules_menu WHERE moduleid='.$id;
			$db->setQuery( $query );
			$total_rows = $db->loadResult();
			if($total_rows==0)
			{
				$query ='INSERT `#__modules_menu` SET `menuid`=0, `moduleid`='.$id;
				$db->setQuery( $query );
				if (!$db->query())    die( $db->stderr());
			}
		}
		else
			echo '<p>Database error, cannot add module</p>';
		
		
	}
	
	
	
		
	AddColumnIfNotExist($db->getPrefix().'fadegallery', 'cssstyle', 'varchar(255)', 'NOT NULL');

}

function CheckIfColumnExist($tablename, $columnname,&$columntype)
    {
		$db =& JFactory::getDBO();
	
		$query="SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='".$columnname."' AND TABLE_NAME='".$tablename."' LIMIT 1";
	
		$db->setQuery( $query );
		if (!$db->query())    die( $db->stderr());
		
		$rows = $db->loadObjectList();
		
		if(count($rows)==1)
		{
			$row=$rows[0];
			$columntype=$row->COLUMN_TYPE;
			return true;
		}
		
		
		return false;
    }
	
	function AddColumnIfNotExist($tablename, $columnname, $filedtype, $options)
    {
		$db =& JFactory::getDBO();

		
	$query="
CREATE PROCEDURE addcol() BEGIN
IF NOT EXISTS(
	SELECT * FROM information_schema.COLUMNS
	WHERE COLUMN_NAME='".$columnname."' AND TABLE_NAME='".$tablename."' 
	)
	THEN
		ALTER TABLE `".$tablename."`
		ADD COLUMN `".$columnname."` ".$filedtype." ".$options.";

END IF;
END;

	";

	
	$db->setQuery("DROP PROCEDURE IF EXISTS addcol;" );
	$db->query();
	
	$db->setQuery( $query );
	if (!$db->query())    die( $db->stderr());
	
	//echo $query;
	$db->setQuery( "CALL addcol();" );
	if (!$db->query())    die( $db->stderr());
	
	$db->setQuery("DROP PROCEDURE addcol;" );
	if (!$db->query())    die( $db->stderr());
	
	
	
    }
	//end functions
	
?>
