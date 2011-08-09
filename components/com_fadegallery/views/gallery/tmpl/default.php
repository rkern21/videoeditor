<?php
/**
 * FadeGallery Joomla! 1.5 Native Component
 * @version 1.2.5
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DS.'components'.DS.'com_fadegallery'.DS.'includes'.DS.'fadegalleryclass.php');
  
$fg=new FadeGalleryClass;


$fg_images=$fg->getFileList($this->params->get( 'folder' ), $this->params->get( 'filelist' ));
	
	
$fg_interval=(int)$this->params->get( 'interval' );
$fg_fadetime=(int)$this->params->get( 'fadetime' );
$fg_fadestep=(int)$this->params->get( 'fadestep' );

$width=(int)$this->params->get( 'width' );
$height=(int)$this->params->get( 'height' );

	if($width<1)			$width=400;
	if($height<1)			$height=300;

	if($fg_interval==0)		$fg_interval=6000;
	if($fg_interval<1000)	$fg_interval=1000;
	
	if($fg_fadetime==0)		$fg_fadetime=2000;
	if($fg_fadetime<100)	$fg_fadetime=100;
	
	if($fg_fadestep==0)		$fg_fadestep=20;
	if($fg_fadestep<1)		$fg_fadestep=1;
	
$objectname='fadegallerycom';




echo $fg->getDiv($fg_images,$width, $height,$objectname,$this->params->get( 'align' ),$this->params->get( 'padding' ),$this->params->get( 'cssstyle' ));


if(count($fg_images)>0)
{
	JHTML::script('fadegallery.js');
	
	echo $fg->getJavaScript($fg_images, $objectname,$fg_interval,$fg_fadetime,$fg_fadestep,$width,$height);
	
}



?>
    
    
    
    
