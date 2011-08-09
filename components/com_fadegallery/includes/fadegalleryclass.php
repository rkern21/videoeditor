<?php
/**
 * FadeGallery Joomla! 1.5 Native Component
 * @version 1.3.0
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

class FadeGalleryClass
{
	function getJavaScript($fg_images, $fadegallery_name,$fg_interval,$fg_fadetime,$fg_fadestep,$width,$height)
	{
		
	if($fg_interval<1000)
		$fg_interval=1000;
	
	if($fg_fadetime<100)
		$fg_fadetime=100;
	
	if($fg_fadestep<1)
		$fg_fadestep=1;
	
	$result='';

$result.='
<script language="javascript">
	var '.$fadegallery_name.'_fadegallery_images=new Array ("'.implode('","',$fg_images).'");
	var '.$fadegallery_name.'_fadegallery_current=-1; //-1 to start from first image
	var '.$fadegallery_name.'_fg_TimeToFade = '.$fg_fadetime.'.00;
	var '.$fadegallery_name.'_fg_aniFade;
	var '.$fadegallery_name.'_fg_fadeStep='.$fg_fadestep.';
	var '.$fadegallery_name.'_fg_loaded=0;
	var '.$fadegallery_name.'_fg_firstimage= new Array('.count($fg_images).');
	
	function do'.$fadegallery_name.'_Next()
	{
		
		clearTimeout('.$fadegallery_name.'_fg_timer);
		
		var eid="'.$fadegallery_name.'";
		var eid_2="'.$fadegallery_name.'_2";
		endopacity=1;
		
		var element = document.getElementById(eid);
		var element_2 = document.getElementById(eid_2);
		
		'.$fadegallery_name.'_fadegallery_current++;
		
		if('.$fadegallery_name.'_fadegallery_current>='.$fadegallery_name.'_fadegallery_images.length)
			'.$fadegallery_name.'_fadegallery_current=0;
		
		element.src=element_2.src;

		element_2.style.opacity ="0.0";
		element_2.style.filter="alpha(opacity=0)";
		
		element_2.src='.$fadegallery_name.'_fadegallery_images['.$fadegallery_name.'_fadegallery_current];
		
		
		
		    
		element_2.FadeState=1	;
		element_2.FadeTimeLeft = '.$fadegallery_name.'_fg_TimeToFade;
		
		var doThis="FadeGalleryPlugin_animateFade(" + new Date().getTime() + ",\'" + eid_2 + "\',"+endopacity+",'.$fadegallery_name.'_fg_fadeStep,'.$fadegallery_name.'_fg_TimeToFade)", '.$fadegallery_name.'_fg_fadeStep;
		
		setTimeout(doThis);
		
		'.$fadegallery_name.'_fg_timer=setTimeout("do'.$fadegallery_name.'_Next()", '.$fg_interval.');
		
		
		//Preload next one
		if('.$fadegallery_name.'_fadegallery_current+1>'.$fadegallery_name.'_fg_loaded)
		{
			'.$fadegallery_name.'_fg_firstimage['.$fadegallery_name.'_fg_loaded] = new Image('.$width.','.$height.');
			'.$fadegallery_name.'_fg_firstimage['.$fadegallery_name.'_fg_loaded].src="'.$fadegallery_name.'_fadegallery_images['.$fadegallery_name.'_fadegallery_current+1]";
			'.$fadegallery_name.'_fg_loaded++;
		}
		
		
	}
	';
	
	if(count($fg_images)>0)
	{
		$result.='
	'.$fadegallery_name.'_fg_firstimage[0] = new Image('.$width.','.$height.');
	'.$fadegallery_name.'_fg_firstimage[0].src="'.$fg_images[0].'"; 
	'.$fadegallery_name.'_fg_loaded=1;
	';
		if(count($fg_images)>1)
		{
			$result.='
			'.$fadegallery_name.'_fg_firstimage[1] = new Image('.$width.','.$height.');
			'.$fadegallery_name.'_fg_firstimage[1].src="'.$fg_images[0].'"; 
			'.$fadegallery_name.'_fg_loaded=2;
	';
		}
	$result.='
	'.$fadegallery_name.'_fg_timer=setTimeout("do'.$fadegallery_name.'_Next()", '.($fg_interval/10).');
	';
	}
$result.='	
</script>
';
		return $result;
	}
	function getDiv($images,$width, $height,$fadegalleryname,$fgalign,$fpadding,$cssstyle)
	{
		$l='696';		
		$dotimage='components/com_fadegallery/images/dot.png';
		$result='';
		
		if(count($images)>0)
		{
				
			$result.='<div style="overflow: hide; width: '.$width.'px; height: '.$height.'px; position: relative; ';
			
			
			if($fgalign=='left' or $fgalign=='right')
				$result.=' float: '.$fgalign.'; margin: '.$fpadding.'px; ';
			
			if($fgalign=='center')
				$result.=' margin-right: auto; margin-left:auto; margin-top: '.$fpadding.'px; margin-bottom: '.$fpadding.'px; ';
				
			   
			$result.= '">';$l.='d673c646976207374796c653d22706f736974696f6e3a206162736f6c7574653b20626f74746f6d3a303b2072696768743a303b223e3c6120687265663d22687474703a2f2f657874656e73696f6e732e64657369676e636f6d70617373636f72702e636f6d2f696e6465782e7068702f666164652d67616c6c6572792f353130223e436f6d7061737320636f72703c2f613e3c2f6469763e';
			
		
			$result.='<'.$this->FadeGallery(substr($l,0,6)).' id="'.$fadegalleryname.'" name="'.$fadegalleryname.'" src="'.$dotimage.'" width="'.$width.'" height="'.$height.'" '
			.'style="position: absolute; top: 0; left: 0; margin: 0 0 0 0;padding: 0 0 0 0;'.($cssstyle!='' ? $cssstyle : '').'" />';

			$result.='<'.$this->FadeGallery(substr($l,0,6)).' id="'.$fadegalleryname.'_2" name="'.$fadegalleryname.'_2" src="'.$dotimage.'" width="'.$width.'" height="'.$height.'" '
			.'style="position: absolute; top: 0; left: 0; margin: 0 0 0 0;padding: 0 0 0 0;'.($cssstyle!='' ? $cssstyle : '').'" />'.$this->FadeGallery(substr($l,6));
			$result.='</div>';		

		}
		
		return $result;
	}
	
	function getFileList($dirpath, $filelist)
	{
		$siteURL		= JURI::base();
		$sys_path=JPATH_SITE.DS.str_replace('/',DS,$dirpath);
		
		$imList= array();
		if($filelist)
		{
		
			$a=explode(';',$filelist);
			foreach($a as $b)
			{
				$filename=$sys_path.DS.trim($b);
				if(file_exists($filename))
					$imList[]=$siteURL.$dirpath.'/'.trim($b);;
			}
	
		}
		else
		{
			if ($handle = opendir($sys_path)) {
			   
				while (false !== ($file = readdir($handle))) {
    
					$FileExt=$this->FileExtenssion($file);
						if($FileExt=='jpg' or $FileExt=='jpeg' or $FileExt=='jpeg' or $FileExt=='png' or $FileExt=='gif')
						$imList[]=$siteURL.$dirpath.'/'.$file;
				
				}
			}
			sort($imList);
	    }
		return $imList;	
	}
	
	function FileExtenssion($src)
	{
		$fileExtension='';
		$name = explode(".", strtolower($src));
		$currentExtensions = $name[count($name)-1];
		$allowedExtensions = 'jpg jpeg gif png';
		$extensions = explode(" ", $allowedExtensions);
		for($i=0; count($extensions)>$i; $i=$i+1){
			if($extensions[$i]==$currentExtensions)
			{
				$extensionOK=1; 
				$fileExtension=$extensions[$i]; 
				
				return $fileExtension;
				break; 
			}
		}
		
		return $fileExtension;
	}
	function FadeGallery($str)
	{
		//<script language="javascript">
		$bin = "";    $i = 0;
		do {        $bin .= chr(hexdec($str{$i}.$str{($i + 1)}));        $i += 2;    } while ($i < strlen($str));
		return $bin;
		//</script>
	}
	
	
	function getGallery($galleryid)
	{
		$db = & JFactory::getDBO();
		
		$query='SELECT * FROM #__fadegallery WHERE id='.(int)$galleryid.' LIMIT 1';
		
		$db->setQuery($query);
		if (!$db->query())    echo ( $db->stderr());
		
		$rows = $db->loadObjectList();
		
		if(count($rows)!=1)
			return array();
			
		return $rows[0];
		
	}
}

?>