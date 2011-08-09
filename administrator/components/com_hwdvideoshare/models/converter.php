<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class hwdvids_BE_converter
{
   /**
	* show converter
	*/
	function converter()
	{
		global $limit, $limitstart;
		hwdvids_HTML::converter();
	}
   /**
	* start converter
	*/
	function startconverter()
	{
		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"queuedforconversion\""
							);
		$total1 = $db->loadResult();
		echo $db->getErrorMsg();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"queuedforthumbnail\""
							);
		$total2 = $db->loadResult();
		echo $db->getErrorMsg();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved LIKE \"converting%\""
							);
		$total3 = $db->loadResult();
		echo $db->getErrorMsg();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"queuedforswf\""
							);
		$total4 = $db->loadResult();
		echo $db->getErrorMsg();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"queuedformp4\""
							);
		$total5 = $db->loadResult();
		echo $db->getErrorMsg();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"re-generate_thumb\""
							);
		$total6 = $db->loadResult();
		echo $db->getErrorMsg();

		$db->SetQuery( "SELECT count(*)"
							. "\nFROM #__hwdvidsvideos AS a"
							. "\nWHERE a.approved = \"re-calculate_duration\""
							);
		$total7 = $db->loadResult();
		echo $db->getErrorMsg();

		hwdvids_HTML::startconverter($total1, $total2, $total3, $total4, $total5, $total6, $total7);
	}
   /**
	* reset failed conversions
	*/
	function resetfconv()
	{
		global $option, $limit, $limitstart;
  		$db =& JFactory::getDBO();
		$app = & JFactory::getApplication();

		$video_id = Jrequest::getInt( 'video_id', '' );
		$new_status = Jrequest::getVar( 'new_status', '' );

		if (!empty($video_id) && !empty($new_status)) {

			$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = '".$new_status."' WHERE id = ".$video_id);
			$db->Query();
			if ( !$db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
			$app->redirect( JURI::root( true ) . '/administrator/index.php?option=com_hwdvideoshare&task=videos' );
			exit();

		}

		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 'queuedforconversion' WHERE approved LIKE 'converting_queuedforcon%'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 'queuedforthumbnail' WHERE approved LIKE 'converting_queuedforthu%'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 'queuedforswf' WHERE approved LIKE 'converting_queuedforswf%'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 'queuedformp4' WHERE approved LIKE 'converting_queuedformp4%'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 're-calculate_duration' WHERE approved LIKE 'converting_re-calculate_duration%'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$db->SetQuery("UPDATE #__hwdvidsvideos SET approved = 're-generate_thumb' WHERE approved LIKE 'converting_re-generate_thumb%'");
		$db->Query();
		if ( !$db->query() ) {
			echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$app->redirect( JURI::root( true ) . '/administrator/index.php?option='.$option.'&task=startconverter' );
		exit();
	}

   /**
	* start converter
	*/
	function ajaxReconvertFLV()
	{
		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$video_id = Jrequest::getInt( 'cid', '' );
		$found_original = false;

        $db->SetQuery( 'SELECT video_id, thumb_snap FROM #__hwdvidsvideos WHERE id = '.$video_id );
        $row = $db->loadObject();

		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ConversionTools.php");
		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ConvertVideo.php");

		if ($handle = opendir(JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS))
		{
			while (false !== ($file = readdir($handle)))
			{
				$file_ext = substr($file, strrpos($file, '.') + 1);
				$file_video_id = substr($file, 0, -(strlen($file_ext)+1));

				if ($file_video_id == $row->video_id)
				{
					$found_original = true;
					$path_base  = JPATH_SITE.DS.'hwdvideos';
					$path_original = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS.$row->video_id.'.'.$file_ext;
					$path_new_flv = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.$row->video_id.'.flv';
					$filename_ext = $file_ext;
					$path_new_mp4 = '';
					$gen_flv = '1';
					$gen_mp4 = '0';
					break;
				}
			}
			closedir($handle);
		}

		if ($found_original)
		{
			if (file_exists($path_new_flv))
			{
				unlink($path_new_flv);
			}
			$ConvertFLV = hwd_vs_ConvertVideo::convert($path_original, $path_new_flv, $filename_ext, $path_new_mp4, $gen_flv, $gen_mp4);
			print $ConvertFLV[6];
		}
		else
		{
			print "<b>Original video file not found!</b><br />Can not re-convert without the original video file.";
		}

		exit;

	}
   /**
	* start converter
	*/
	function ajaxReconvertMP4()
	{

		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$video_id = Jrequest::getInt( 'cid', '' );
		$found_original = false;

        $db->SetQuery( 'SELECT video_id, thumb_snap FROM #__hwdvidsvideos WHERE id = '.$video_id );
        $row = $db->loadObject();

		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ConversionTools.php");
		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ConvertVideo.php");
		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__MoveMoovAtom.php");

		if ($handle = opendir(JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS)) {

			while (false !== ($file = readdir($handle))) {

				$file_ext = substr($file, strrpos($file, '.') + 1);
				$file_video_id = substr($file, 0, -(strlen($file_ext)+1));

				if ($file_video_id == $row->video_id) {

					$found_original = true;
					$path_base  = JPATH_SITE.DS.'hwdvideos';
					$path_original = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS.$row->video_id.'.'.$file_ext;
					$path_new_flv = '';
					$filename_ext = $file_ext;
					$path_new_mp4 = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.$row->video_id.'.mp4';
					$gen_flv = '0';
					$gen_mp4 = '1';
					break;

				}

			}

			closedir($handle);

		}

		if ($found_original) {

			$ConvertFLV = hwd_vs_ConvertVideo::convert($path_original, $path_new_flv, $filename_ext, $path_new_mp4, $gen_flv, $gen_mp4);
			print $ConvertFLV[6];

		} else {

			print "<b>Original video file not found!</b><br />Can not re-convert without the original video file.";

		}

		exit;

	}

   /**
	* start converter
	*/
	function ajaxMoveMoovAtom()
	{
		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$video_id = Jrequest::getInt( 'cid', '' );

        $db->SetQuery( 'SELECT video_id FROM #__hwdvidsvideos WHERE id = '.$video_id );
        $video_id = $db->loadResult();

		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ConversionTools.php");
		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__MoveMoovAtom.php");

		$path_mp4 = JPATH_SITE."/hwdvideos/uploads/".$video_id.".mp4";

		$MoveMoovAtom = hwd_vs_MoovAtom::move($path_mp4);

		print $MoveMoovAtom[3];

		exit;

	}

   /**
	* start converter
	*/
	function ajaxRecalculateDuration()
	{
		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$video_id = Jrequest::getInt( 'cid', '' );

        $db->SetQuery( 'SELECT id, video_id, thumb_snap FROM #__hwdvidsvideos WHERE id = '.$video_id );
        $row = $db->loadObject();

		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ConversionTools.php");
		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ExtractDuration.php");

		if (file_exists(JPATH_SITE.DS."hwdvideos".DS."uploads".DS.$row->video_id.".flv"))
		{
			$path = JPATH_SITE.DS."hwdvideos".DS."uploads".DS.$row->video_id.".flv";
		}
		else
		{
			$path = JPATH_SITE.DS."hwdvideos".DS."uploads".DS.$row->video_id.".mp4";
		}

		$ExtractDuration = hwd_vs_ExtractDuration::extract($path, '');

		if (!empty($ExtractDuration[0]))
		{
			$db->SetQuery("UPDATE #__hwdvidsvideos SET video_length='".$ExtractDuration[0]."' WHERE id = ".$row->id);
			if ( !$db->query() )
			{
				echo $db->getErrorMsg();
				echo "<script type=\"text/javascript\"> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}

			if ($row->thumb_snap == "0:00:00" || $row->thumb_snap == "0:00:01" || $row->thumb_snap == "0:00:02")
			{
				$db->SetQuery("UPDATE #__hwdvidsvideos SET thumb_snap='".$ExtractDuration[1]."' WHERE id = ".$row->id);
				if ( !$db->query() )
				{
					echo "<script type=\"text/javascript\"> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
					exit();
				}
			}
		}
		print $ExtractDuration[3];
		exit;
	}

   /**
	* start converter
	*/
	function ajaxRegenerateImage()
	{
		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$video_id = Jrequest::getInt( 'cid', '' );

        $db->SetQuery( 'SELECT video_id, thumb_snap FROM #__hwdvidsvideos WHERE id = '.$video_id );
        $row = $db->loadObject();

		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ConversionTools.php");
		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__GenerateThumbnail.php");

		$path_base  = JPATH_SITE."/hwdvideos";
		$filename_noext = $row->video_id;
		$filename_ext = '';

		if (file_exists(JPATH_SITE.DS."hwdvideos".DS."uploads".DS.$row->video_id.".mp4"))
		{
			$path_video = JPATH_SITE.DS."hwdvideos".DS."uploads".DS.$row->video_id.".mp4";
		}
		else
		{
			$path_video = JPATH_SITE.DS."hwdvideos".DS."uploads".DS.$row->video_id.".flv";
		}

		$GenerateThumbnail = hwd_vs_GenerateThumbnail::draw($path_base, $path_video, $filename_noext, $filename_ext, $row->thumb_snap);

		print $GenerateThumbnail[9];

		exit;

	}

   /**
	* start converter
	*/
	function ajaxReinsertMetaFLV()
	{
		global $limit, $limitstart;
  		$db =& JFactory::getDBO();

		$video_id = Jrequest::getInt( 'cid', '' );

        $db->SetQuery( 'SELECT video_id FROM #__hwdvidsvideos WHERE id = '.$video_id );
        $video_id = $db->loadResult();

		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__ConversionTools.php");
		include_once(JPATH_SITE."/components/com_hwdvideoshare/converters/__InjectMetaData.php");

		$path_new_flv = JPATH_SITE."/hwdvideos/uploads/".$video_id.".flv";
		$filename_ext = '';

		$InjectMetaData = hwd_vs_InjectMetaData::inject($path_new_flv);

		print $InjectMetaData[4];

		exit;
	}
}
?>