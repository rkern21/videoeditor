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

/**
 * This class is the HTML generator for hwdVideoShare frontend
 *
 * @package    hwdVideoShare
 * @author     Dave Horsfall <info@highwooddesign.co.uk>
 * @copyright  2008 Highwood Design
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.1.4 Alpha RC2.13
 */
class hwd_vs_uploads
{
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function uploadConfirmPerl()
	{
	global $database, $my, $acl, $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_live_site, $Itemid, $mosConfig_sitename;

	$c = hwd_vs_Config::get_instance();
	$db = & JFactory::getDBO();
	$my = & JFactory::getUser();
	$acl= & JFactory::getACL();

	// get server configuration data
	require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
	$s = hwd_vs_SConfig::get_instance();

	//******************************************************************************************************
	//   ATTENTION: THIS FILE HEADER MUST REMAIN INTACT. DO NOT DELETE OR MODIFY THIS FILE HEADER.
	//
	//   Name: ubr_finished.php
	//   Revision: 1.3
	//   Date: 2/18/2008 5:36:57 PM
	//   Link: http://uber-uploader.sourceforge.net
	//   Initial Developer: Peter Schmandra  http://www.webdice.org
	//   Description: Show successful file uploads.
	//
	//   Licence:
	//   The contents of this file are subject to the Mozilla Public
	//   License Version 1.1 (the "License"); you may not use this file
	//   except in compliance with the License. You may obtain a copy of
	//   the License at http://www.mozilla.org/MPL/
	//
	//   Software distributed under the License is distributed on an "AS
	//   IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
	//   implied. See the License for the specific language governing
	//   rights and limitations under the License.
	//
	//***************************************************************************************************************

	//***************************************************************************************************************
	// The following possible query string formats are assumed
	//
	// 1. ?upload_id=upload_id
	// 2. ?about=1
	//****************************************************************************************************************

	$THIS_VERSION = "1.3";                                // Version of this file
	$UPLOAD_ID = '';                                      // Initialize upload id

	require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_ini.php');
	require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_lib.php');
	require_once(JPATH_SITE.DS.'components'.DS.'com_hwdvideoshare'.DS.'assets'.DS.'uploads'.DS.'perl'.DS.'ubr_finished_lib.php');

	if($PHP_ERROR_REPORTING){ error_reporting(E_ALL); }

	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.date('r'));
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');

	if(preg_match("/^[a-zA-Z0-9]{32}$/", $_GET['upload_id'])){ $UPLOAD_ID = $_GET['upload_id']; }
	elseif(isset($_GET['about']) && $_GET['about'] == 1){ kak("<u><b>UBER UPLOADER FINISHED PAGE</b></u><br>UBER UPLOADER VERSION =  <b>" . $UBER_VERSION . "</b><br>UBR_FINISHED = <b>" . $THIS_VERSION . "<b><br>\n", 1 , __LINE__); }
	else{ kak("<font color='red'>ERROR</font>: Invalid parameters passed<br>", 1, __LINE__); }

	//Declare local values
	$_XML_DATA = array();                                          // Array of xml data read from the upload_id.redirect file
	$_CONFIG_DATA = array();                                       // Array of config data read from the $_XML_DATA array
	$_POST_DATA = array();                                         // Array of posted data read from the $_XML_DATA array
	$_FILE_DATA = array();                                         // Array of 'FileInfo' objects read from the $_XML_DATA array
	$_FILE_DATA_TABLE = '';                                        // String used to store file info results nested between <tr> tags
	$_FILE_DATA_EMAIL = '';                                        // String used to store file info results

	$xml_parser = new XML_Parser;                                  // XML parser
	$xml_parser->setXMLFile($TEMP_DIR, $_REQUEST['upload_id']);    // Set upload_id.redirect file
	$xml_parser->setXMLFileDelete($DELETE_REDIRECT_FILE);          // Delete upload_id.redirect file when finished parsing
	$xml_parser->parseFeed();                                      // Parse upload_id.redirect file

	// Display message if the XML parser encountered an error
	if($xml_parser->getError()){ kak($xml_parser->getErrorMsg(), 1, __LINE__); }

	$_XML_DATA = $xml_parser->getXMLData();                        // Get xml data from the xml parser
	$_CONFIG_DATA = getConfigData($_XML_DATA);                     // Get config data from the xml data
	$_POST_DATA  = getPostData($_XML_DATA);                        // Get post data from the xml data
	$_FILE_DATA = getFileData($_XML_DATA);                         // Get file data from the xml data

	// Output XML DATA, CONFIG DATA, POST DATA, FILE DATA to screen and exit if DEBUG_ENABLED.
	if($DEBUG_FINISHED){
		debug("<br><u>XML DATA</u>", $_XML_DATA);
		debug("<u>CONFIG DATA</u>", $_CONFIG_DATA);
		debug("<u>POST DATA</u>", $_POST_DATA);
		debug("<u>FILE DATA</u><br>", $_FILE_DATA);
		exit;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////
	//
	//           *** ATTENTION: ENTER YOUR CODE HERE !!! ***
	//
	// This is a good place to put your post upload code. Like saving the
	// uploaded file information to your DB or doing some image
	// manipulation. etc. Everything you need is in the
	// $XML DATA, $_CONFIG_DATA, $_POST_DATA and $_FILE_DATA arrays.
	//
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// NOTE: You can now access all XML values below this comment. eg.
	//   $_XML_DATA['upload_dir']; or $_XML_DATA['link_to_upload'] etc
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// NOTE: You can now access all config values below this comment. eg.
	//   $_CONFIG_DATA['upload_dir']; or $_CONFIG_DATA['link_to_upload'] etc
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// NOTE: You can now access all post values below this comment. eg.
	//   $_POST_DATA['client_id']; or $_POST_DATA['check_box_1_'] etc
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// NOTE: You can now access all file (slot, name, size, type) info below this comment. eg.
	//   $_FILE_DATA[0]->name  or  $_FILE_DATA[0]->getFileInfo('name')
	/////////////////////////////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Create thumnail example  (must uncomment line 34)
	// if( $_FILE_DATA[0]->type  == 'image/jpeg'){ $success = createThumbFile($_CONFIG_DATA['upload_dir'],  $_FILE_DATA[0]->name, $_CONFIG_DATA['upload_dir'],  120,  100);  }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Create file upload table
	$_FILE_DATA_TABLE = getFileDataTable($_FILE_DATA, $_CONFIG_DATA);

	// Create and send email
	if($_CONFIG_DATA['send_email_on_upload']){ emailUploadResults($_FILE_DATA, $_CONFIG_DATA, $_POST_DATA); }

		$file_name = $_FILE_DATA[0]->name;
		$file_ext = substr($file_name, strrpos($file_name, '.') + 1);
		$file_ext = strtolower($file_ext);
		$file_video_name = $file_name;
		$file_video_id = substr($file_name, 0, -(strlen($file_ext)+1));

		$title 				= hwd_vs_tools::generatePostTitle($_POST_DATA['title']);
		$description 		= hwd_vs_tools::generatePostDescription($_POST_DATA['description']);
		$tags 				= hwd_vs_tools::generatePostTags($_POST_DATA['tags']);
		$category_id 		= intval ($_POST_DATA['category_id']);
		$public_private 	= $_POST_DATA['public_private'];
		$allow_comments 	= intval ($_POST_DATA['allow_comments']);
		$allow_embedding 	= intval ($_POST_DATA['allow_embedding']);
		$allow_ratings 		= intval ($_POST_DATA['allow_ratings']);

		$checkform = hwd_vs_tools::checkFormComplete($title, $description, $category_id, $tags, $public_private, $allow_comments, $allow_embedding, $allow_ratings);
		if (!$checkform) { return; }

		// initialise database
		$row = new hwdvids_video($db);

		if ($file_ext == "swf") {
			$_POST['video_type'] 		= "swf";
		} else if ($file_ext == "mp4") {
			$_POST['video_type'] 		= "mp4";
		} else {
			$_POST['video_type'] 		= "local";
		}

		$password = $_POST_DATA['hwdvspassword'];
		if (!empty($password))
		{
			$_POST['password'] 		= $password;
		}

		$_POST['title'] 			= $title;
		$_POST['description'] 		= $description;
		$_POST['category_id'] 		= $category_id;
		$_POST['tags'] 				= $tags;
		$_POST['public_private'] 	= $public_private;
		$_POST['allow_comments'] 	= $allow_comments;
		$_POST['allow_embedding'] 	= $allow_embedding;
		$_POST['allow_ratings'] 	= $allow_ratings;
		$_POST['date_uploaded'] 	= date('Y-m-d H:i:s');
		$_POST['user_id'] 			= $my->id;
		$_POST['published'] 		= 1;

		if ($c->requiredins == 1) {
			$_POST['video_id'] 		= $file_video_name;
			// check if we are reprocessing
			if ($c->reconvertflv == 0) {
				if ($file_ext == "flv") {
					$_POST['approved'] = "queuedforthumbnail";
				} else if ($file_ext == "swf") {
					$_POST['approved'] = "queuedforswf";
				} else if ($file_ext == "mp4") {
					$_POST['approved'] = "queuedformp4";
				} else {
					$_POST['approved'] = "queuedforconversion";
				}
			} else {
				if ($file_ext == "swf") {
					$_POST['approved'] = "queuedforswf";
				} else if ($file_ext == "mp4") {
					$_POST['approved'] = "queuedformp4";
				} else {
					$_POST['approved'] = "queuedforconversion";
				}
			}
		} else if ($c->requiredins == 0) {

			$originals_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS;
			$base_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS;

			if ($file_ext !== "flv" && $file_ext !== "mp4" && $file_ext !== "swf") {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR04, "exclamation.png", 0);
				return;
			}

			if (!copy($originals_Dir.$file_name, $base_Dir.$file_video_id.".".strtolower($file_ext))) {

        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
				return;

			} else {

				if ($c->deleteoriginal == 1) {
					if (file_exists($base_Dir.$file_name)) {
						@unlink($originals_Dir.$file_name);
					}
				}

				if ($c->aav == 1) {
					$_POST['approved'] = "yes";
				} else {
					$_POST['approved'] = "pending";
				}
			}

			$_POST['video_id'] 		= $file_video_id;

		}

		//check if already exists
		$db->SetQuery( 'SELECT count(*)'
						. ' FROM #__hwdvidsvideos'
						. ' WHERE video_id = "'.$file_video_id.'"'
						);
		$duplicatecount = $db->loadResult();
		if ($duplicatecount > 0) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
			return;
		}

		if(empty($_POST['video_id'])) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
			return;
		}

		// bind it to the table
		if (!$row->bind($_POST))
		{
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
		}

		// store it in the db
		if (!$row->store())
		{
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
		}

		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'events.php');

		$params->title = $title;
		$params->id = $row->id;
		$params->category_id = $row->category_id;
		$params->type = $row->video_type;
		$params->user_id = $row->user_id;

		hwdvsEvent::onAfterVideoUpload($params);

		hwd_vs_html::uploadConfirm($title, $row);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function uploadConfirmFlash()
	{
	global $database, $my, $acl, $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_live_site, $Itemid, $mosConfig_sitename;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		// get server configuration data
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
		$s = hwd_vs_SConfig::get_instance();

		$uploadDir = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS;
		$base_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS;

		// generate random filename
		$file_video_id = hwd_vs_tools::generateNewVideoid();

		if (isset($_FILES['myFile'])) {

			// Javascript off, we need to upload the file
			$file_name0= (isset($_FILES['myFile']['tmp_name']) ? $_FILES['myFile']['tmp_name'] : "");
			$file_name = (isset($_FILES['myFile']['name']) ? $_FILES['myFile']['name'] : "");
			$file_size = (isset($_FILES['myFile']['size']) ? $_FILES['myFile']['size'] : "");

			if (!isset($_FILES['myFile']['error'])) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR00, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 8) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR08, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 7) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR07, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 6) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR06, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 5) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR05, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 4) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR04, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 3) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR03, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 2) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR02, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 1) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR01, "exclamation.png", 0);
				return;
			} else if ($_FILES['myFile']['error'] == 0) {

				if (!empty($file_name)) {
					// get extension and create new random filename
					$file_ext = substr($file_name, strrpos($file_name, '.') + 1);
					$file_video_name = $file_video_id.".".$file_ext;
				}

				if (empty($file_video_name)) {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
					return;
				}

				$sizelimit = $c->maxupld*1024*1024; //size limit in mb
				if ($file_size > $sizelimit) {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR02." ".$c->maxupld."MB.", "exclamation.png", 0);
					return;
				}

				$filename = split("\.", $file_video_name);
				if (eregi("[^0-9a-zA-Z_]", $filename[0])) {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR03, "exclamation.png", 0);
					return;
				}

				// check file extensions
				if (($c->ft_mpg == "on" && $file_ext == "mpg") || ($c->ft_mpeg == "on" && $file_ext == "mpeg") || ($c->ft_avi == "on" && $file_ext == "avi") || ($c->ft_divx == "on" && $file_ext == "divx") || ($c->ft_mp4 == "on" && $file_ext == "mp4") || ($c->ft_flv == "on" && $file_ext == "flv") || ($c->ft_wmv == "on" && $file_ext == "wmv") || ($c->ft_rm == "on" && $file_ext == "rm") || ($c->ft_mov == "on" && $file_ext == "mov") || ($c->ft_moov == "on" && $file_ext == "moov") || ($c->ft_asf == "on" && $file_ext == "asf") || ($c->ft_swf == "on" && $file_ext == "swf") || ($c->ft_vob == "on" && $file_ext == "vob")) {
				} else {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR04, "exclamation.png", 0);
					return;
				}

				// move to uploaded file directory
				$file_video_path = $uploadDir . $file_video_name;
				if (file_exists($base_Dir.$file_video_name)) {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR05, "exclamation.png", 0);
					return;
				}
				if (!move_uploaded_file ($_FILES['myFile']['tmp_name'],$file_video_path) || !JPath::setPermissions($file_video_path)) {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR06, "exclamation.png", 0);
					return;
				}

			}

		} else if (isset($_POST['uploadedFile'])) {

			// Javascript on, so the file has been uploaded and its filename is in the POST array
			// We need to check it and then move it into the hwdVideoShare upload directory
			// Search for file
			if ($handle = opendir(JPATH_SITE.DS.'tmp'.DS)) {
				$n = 0;
				$file_length = strlen($_POST['uploadedFile']);
				/* This is the correct way to loop over the directory. */
				while (false !== ($file = readdir($handle))) {
					// echo $_POST['uploadedFile'].' -- '.substr($file, -$file_length).'<br />';
					if (substr($file, -$file_length) == $_POST['uploadedFile']) {
						// echo "FILE NAME MATCH FOUND:$file\n";
						$file_name = $file;
						$n++;
						break;
					}
				}
				closedir($handle);

				if ($n < 1) {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
					return;
				//} elseif ($n > 1) {
				//	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
				//	return;
				} else {

					// single match found so can continue
					$file_ext = substr(strrchr($file_name, '.'), 1);
					$file_ext = strtolower($file_ext);
					$file_video_name = $file_video_id.".".$file_ext;
					$file_video_path = $uploadDir . $file_video_name;

					if (@copy(JPATH_SITE.DS.'tmp'.DS.$file_name, $file_video_path)) {
						// echo "COPIED FILE FROM TEMP LOCATION INTO hwdVideoShare DIRECTORY\n";
						if (file_exists($file_video_path)) {
							if (!@unlink(JPATH_SITE.DS.'tmp'.DS.$file_name)) {
								// echo "COULD NOT REMOVE TEMPORARY VIDEO FILE\n";
							}
						}
					} else {
						hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
						return;
					}
				}
			}

		} else {
			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
			return;
		}

		if (file_exists($file_video_path)) {

			$row = new hwdvids_video($db);

			$title 				= hwd_vs_tools::generatePostTitle();
			$description 		= hwd_vs_tools::generatePostDescription();
			$tags 				= hwd_vs_tools::generatePostTags();
			$category_id 		= JRequest::getInt( 'category_id', 0, 'post' );
			$public_private 	= JRequest::getWord( 'public_private' );
			$allow_comments 	= JRequest::getInt( 'allow_comments', 0, 'post' );
			$allow_embedding 	= JRequest::getInt( 'allow_embedding', 0, 'post' );
			$allow_ratings 		= JRequest::getInt( 'allow_ratings', 0, 'post' );

			$user_id 			= $my->id;

			if ($file_ext == "swf") {
				$_POST['video_type'] 		= "swf";
			} else if ($file_ext == "mp4") {
				$_POST['video_type'] 		= "mp4";
			} else {
				$_POST['video_type'] 		= "local";
			}

			$password = Jrequest::getVar( 'hwdvspassword', '' );
			if (!empty($password))
			{
				$_POST['password'] 		= $password;
			}

			$_POST['title'] 			= $title;
			$_POST['description'] 		= $description;
			$_POST['category_id'] 		= $category_id;
			$_POST['tags'] 				= $tags;
			$_POST['public_private'] 	= $public_private;
			$_POST['allow_comments'] 	= $allow_comments;
			$_POST['allow_embedding'] 	= $allow_embedding;
			$_POST['allow_ratings'] 	= $allow_ratings;
			$_POST['date_uploaded'] 	= date('Y-m-d H:i:s');
			$_POST['user_id'] 			= $my->id;
			$_POST['published'] 		= 1;

			//check if already exists
			$db->SetQuery( 'SELECT count(*)'
							. ' FROM #__hwdvidsvideos'
							. ' WHERE video_id = "'.$file_video_id.'"'
							);
			$duplicatecount = $db->loadResult();
			if ($duplicatecount > 0) {
        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
				return;
			}

			$checkform = hwd_vs_tools::checkFormComplete($title, $description, $category_id, $tags, $public_private, $allow_comments, $allow_embedding, $allow_ratings);
			if (!$checkform) { return; }

			if ($c->requiredins == 1) {

				// check if we are reprocessing
				if ($c->reconvertflv == 0) {
					if ($file_ext == "flv") {
						$_POST['approved'] = "queuedforthumbnail";
					} else if ($file_ext == "swf") {
						$_POST['approved'] = "queuedforswf";
					} else if ($file_ext == "mp4") {
						$_POST['approved'] = "queuedformp4";
					} else {
						$_POST['approved'] = "queuedforconversion";
					}
				} else {
					if ($file_ext == "swf") {
						$_POST['approved'] = "queuedforswf";
					} else if ($file_ext == "mp4") {
						$_POST['approved'] = "queuedformp4";
					} else {
						$_POST['approved'] = "queuedforconversion";
					}
				}
				$_POST['video_id'] = $file_video_name;

			} else if ($c->requiredins == 0) {

				// get new file name
				$originals_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS;
				$base_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS;

				if ($file_ext !== "flv" && $file_ext !== "mp4" && $file_ext !== "swf") {
        			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR04, "exclamation.png", 0);
					return;
				}

				if (!copy($originals_Dir.$file_video_name, $base_Dir.$file_video_id.".".strtolower($file_ext))) {

        			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR06, "exclamation.png", 0);
					return;

				} else {

					if ($c->deleteoriginal == 1) {
						if (file_exists($base_Dir.$file_video_name) && file_exists($originals_Dir.$file_video_name)) {
							@unlink($originals_Dir.$file_video_name);
						}
					}

					if ($c->aav == 1) {
						$_POST['approved'] = "yes";
					} else {
						$_POST['approved'] = "pending";
					}

					$_POST['video_id'] = $file_video_id;

				}

			}

			if(empty($_POST['video_id'])) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_ERRUP, "exclamation.png", 0);
				return;
			}

			// bind it to the table
			if (!$row->bind($_POST))
			{
				echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
				exit();
			}

			// store it in the db
			if (!$row->store())
			{
				echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
				exit();
			}

		}

		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'events.php');

		$params->title = $title;
		$params->id = $row->id;
		$params->category_id = $row->category_id;
		$params->type = $row->video_type;
		$params->user_id = $row->user_id;

		hwdvsEvent::onAfterVideoUpload($params);

		hwd_vs_html::uploadConfirm($title, $row);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function uploadConfirmPhp()
	{
	global $database, $my, $acl, $mosConfig_absolute_path, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_live_site, $Itemid, $mosConfig_sitename;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		// get server configuration data
		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'serverconfig.hwdvideoshare.php');
		$s = hwd_vs_SConfig::get_instance();

		$file_name0= (isset($_FILES['upfile_0']['tmp_name']) ? $_FILES['upfile_0']['tmp_name'] : "");
		$file_name = (isset($_FILES['upfile_0']['name']) ? $_FILES['upfile_0']['name'] : "");
		$file_size = (isset($_FILES['upfile_0']['size']) ? $_FILES['upfile_0']['size'] : "");

		if (!isset($_FILES['upfile_0']['error'])) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR00, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 8) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR08, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 7) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR07, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 6) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR06, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 5) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR05, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 4) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR04, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 3) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR03, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 2) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR02, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 1) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_PHPUPLD_ERR01, "exclamation.png", 0);
			return;
		} else if ($_FILES['upfile_0']['error'] == 0) {

			$title 				= hwd_vs_tools::generatePostTitle();
			$description 		= hwd_vs_tools::generatePostDescription();
			$tags 				= hwd_vs_tools::generatePostTags();
			$category_id 		= JRequest::getInt( 'category_id', 0, 'post' );
			$public_private 	= JRequest::getWord( 'public_private' );
			$allow_comments 	= JRequest::getInt( 'allow_comments', 0, 'post' );
			$allow_embedding 	= JRequest::getInt( 'allow_embedding', 0, 'post' );
			$allow_ratings 		= JRequest::getInt( 'allow_ratings', 0, 'post' );

			$checkform = hwd_vs_tools::checkFormComplete($title, $description, $category_id, $tags, $public_private, $allow_comments, $allow_embedding, $allow_ratings);
			if (!$checkform) { return; }

			$row = new hwdvids_video($db);

			if (!empty($file_name)) {
				// generate random filename
				$file_video_id = hwd_vs_tools::generateNewVideoid();
				// get extension and create new random filename
				$file_ext = substr($file_name, strrpos($file_name, '.') + 1);
				$file_ext = strtolower($file_ext);
				$file_video_name = $file_video_id.".".$file_ext;
			}

			if (empty($file_video_name)) {
        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
				return;
			}

			$sizelimit = $c->maxupld*1024*1024; //size limit in mb
			if ($file_size > $sizelimit) {
        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR02." ".$c->maxupld."MB.", "exclamation.png", 0);
				return;
			}

			$filename = split("\.", $file_video_name);
			if (eregi("[^0-9a-zA-Z_]", $filename[0])) {
        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR03, "exclamation.png", 0);
				return;
			}

			if ($c->requiredins == 1) {
				$_POST['video_id'] = $file_video_name;

				$oformats = explode(",", $c->oformats);
				// check file extensions
				if (($c->ft_mpg == "on" && $file_ext == "mpg") || ($c->ft_mpeg == "on" && $file_ext == "mpeg") || ($c->ft_avi == "on" && $file_ext == "avi") || ($c->ft_divx == "on" && $file_ext == "divx") || ($c->ft_mp4 == "on" && $file_ext == "mp4") || ($c->ft_flv == "on" && $file_ext == "flv") || ($c->ft_wmv == "on" && $file_ext == "wmv") || ($c->ft_rm == "on" && $file_ext == "rm") || ($c->ft_mov == "on" && $file_ext == "mov") || ($c->ft_moov == "on" && $file_ext == "moov") || ($c->ft_asf == "on" && $file_ext == "asf") || ($c->ft_swf == "on" && $file_ext == "swf") || ($c->ft_vob == "on" && $file_ext == "vob")) {
					// format matches a main allowed format
				} else if (in_array($file_ext, $oformats )) {
					// format matches an allowed extra format
				} else {
        			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR04, "exclamation.png", 0);
					return;
				}

				// move to uploaded file directory
				$base_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS.'originals'.DS;
				if (file_exists($base_Dir.$file_video_name)) {
        			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR05, "exclamation.png", 0);
					return;
				}
				if (!move_uploaded_file ($_FILES['upfile_0']['tmp_name'],$base_Dir.$file_video_name)) {
        			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR06, "exclamation.png", 0);
					return;
				}

				// check if we are reprocessing
				if ($c->reconvertflv == 0) {
					if ($file_ext == "flv") {
						$_POST['approved'] = "queuedforthumbnail";
					} else if ($file_ext == "swf") {
						$_POST['approved'] = "queuedforswf";
					} else if ($file_ext == "mp4") {
						$_POST['approved'] = "queuedformp4";
					} else {
						$_POST['approved'] = "queuedforconversion";
					}
				} else {
					if ($file_ext == "swf") {
						$_POST['approved'] = "queuedforswf";
					} else if ($file_ext == "mp4") {
						$_POST['approved'] = "queuedformp4";
					} else {
						$_POST['approved'] = "queuedforconversion";
					}
				}
			} else if ($c->requiredins == 0) {
				$_POST['video_id'] = $file_video_id;
				if ($file_ext !== "flv" && $file_ext !== "mp4" && $file_ext !== "swf") {
        			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR04, "exclamation.png", 0);
					return;
				}

				$base_Dir = JPATH_SITE.DS.'hwdvideos'.DS.'uploads'.DS;
				if (file_exists($base_Dir.$file_video_name)) {
        			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR05, "exclamation.png", 0);
					return;
				}

				if (!move_uploaded_file ($_FILES['upfile_0']['tmp_name'],$base_Dir.$file_video_id.".".strtolower($file_ext)) || !JPath::setPermissions($base_Dir.$file_video_name)) {
        			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR06, "exclamation.png", 0);
					return;
				}

				if ($c->aav == 1) {
					$_POST['approved'] = "yes";
				} else {
					$_POST['approved'] = "pending";
				}
			}

			//check if already exists
			$db->SetQuery( 'SELECT count(*)'
							. ' FROM #__hwdvidsvideos'
							. ' WHERE video_id = "'.$file_video_id.'"'
							);
			$duplicatecount = $db->loadResult();
			if ($duplicatecount > 0) {
        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_DUPLICATE, "exclamation.png", 0);
				return;
			}

			$ext = strrchr($file_name, '.');
			if ($ext == ".swf") {
				$_POST['video_type'] 		= "swf";
			} else if ($file_ext == "mp4") {
				$_POST['video_type'] 		= "mp4";
			} else {
				$_POST['video_type'] 		= "local";
			}

			$password = Jrequest::getVar( 'hwdvspassword', '' );
			if (!empty($password))
			{
				$_POST['password'] 		= $password;
			}

			$_POST['title'] 			= $title;
			$_POST['description'] 		= $description;
			$_POST['category_id'] 		= $category_id;
			$_POST['tags'] 				= $tags;
			$_POST['public_private'] 	= $public_private;
			$_POST['allow_comments'] 	= $allow_comments;
			$_POST['allow_embedding'] 	= $allow_embedding;
			$_POST['allow_ratings'] 	= $allow_ratings;
			$_POST['date_uploaded'] 	= date('Y-m-d H:i:s');
			$_POST['user_id'] 			= $my->id;
			$_POST['published'] 		= "1";

			if(empty($_POST['video_id'])) {
        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
				return;
			}

			// bind it to the table
			if (!$row->bind($_POST))
			{
				echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
				exit();
			}

			// store it in the db
			if (!$row->store())
			{
				echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
				exit();
			}

		}

		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'events.php');

		$params->title = $title;
		$params->id = $row->id;
		$params->category_id = $row->category_id;
		$params->type = $row->video_type;
		$params->user_id = $row->user_id;

		hwdvsEvent::onAfterVideoUpload($params);

		hwd_vs_html::uploadConfirm($title, $row);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function uploadConfirmWarp()
	{
		global $Itemid;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$video_id 		    = JRequest::getVar( 'videoGuid', '' );

		$title 				= hwd_vs_tools::generatePostTitle();
		$description 		= hwd_vs_tools::generatePostDescription();
		$tags 				= hwd_vs_tools::generatePostTags();
		$category_id 		= JRequest::getInt("category_id", 0);
		$public_private 	= JRequest::getWord("public_private", "");
		$allow_comments 	= JRequest::getInt("allow_comments", 0);
		$allow_embedding 	= JRequest::getInt("allow_embedding", 0);
		$allow_ratings 		= JRequest::getInt("allow_ratings", 0);

		$checkform = hwd_vs_tools::checkFormComplete($title, $description, $category_id, $tags, $public_private, $allow_comments, $allow_embedding, $allow_ratings);
		if (!$checkform) { return; }

		require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'warp'.DS.'infin-lib.php');
		$accountKey = $c->warpAccountKey;
		$secretKey = $c->warpSecretKey;

		// update warp hd
		$infinVideo = new InfinovationVideo($accountKey, $secretKey);
		$infinVideo->updateVideo($video_id, $title, $description, $tags, $my->id, "");

		$row = new hwdvids_video($db);

		$_POST['approved']          = "yes";
		$_POST['video_type'] 		= "warphd";
		$_POST['video_id'] 		    = $video_id;

		$password = Jrequest::getVar( 'hwdvspassword', '' );
		if (!empty($password))
		{
			$_POST['password'] 		= $password;
		}

		$_POST['title'] 			= $title;
		$_POST['description'] 		= $description;
		$_POST['category_id'] 		= $category_id;
		$_POST['tags'] 				= $tags;
		$_POST['public_private'] 	= $public_private;
		$_POST['allow_comments'] 	= $allow_comments;
		$_POST['allow_embedding'] 	= $allow_embedding;
		$_POST['allow_ratings'] 	= $allow_ratings;
		$_POST['date_uploaded'] 	= date('Y-m-d H:i:s');
		$_POST['user_id'] 			= $my->id;
		$_POST['published'] 		= "1";

		if(empty($_POST['video_id']))
		{
			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ERROR_UPLDERR01, "exclamation.png", 0);
			return;
		}

		// bind it to the table
		if (!$row->bind($_POST))
		{
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
		}

		// store it in the db
		if (!$row->store())
		{
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
		}


		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'events.php');

		$params->title = $title;
		$params->id = $row->id;
		$params->category_id = $row->category_id;
		$params->type = $row->video_type;
		$params->user_id = $row->user_id;

		hwdvsEvent::onAfterVideoUpload($params);

		hwd_vs_html::uploadConfirm($title, $row);
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function uploadMedia()
	{
		global $Itemid, $smartyvs, $j15, $j16;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();
		$usersConfig = &JComponentHelper::getParams( 'com_users' );

		// check for upload limit (temporary hack)
		//$db->SetQuery( 'SELECT count(*) FROM #__hwdvidsvideos WHERE user_id = '.$my->id );
  		//$videoCount = $db->loadResult();
		//echo $db->getErrorMsg();
		//if ($videoCount > 50) {
		//	global $mainframe;
		//	$mainframe->enqueueMessage("You have reached your maximum upload limit and can not upload any more videos.");
		//	$mainframe->redirect( JURI::root().'index.php?option=com_hwdvideoshare&task=frontpage&Itemid='.$Itemid );
		//}

		$sessid = session_id();
		if (empty($sessid)) {
			session_start();
		}

		$localUploadAccess = hwd_vs_access::checkAccess($c->gtree_upld, $c->gtree_upld_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORUPLD, _HWDVIDS_ALERT_UPLD_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.upload.local", 1);
		$thirdPartyUploadAccess = hwd_vs_access::checkAccess($c->gtree_ultp, $c->gtree_ultp_child, 4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_REGISTERFORUPLD, _HWDVIDS_ALERT_UPLD_NOT_AUTHORIZED, "exclamation.png", 0, "core.frontend.upload.tp", 1);

		if (!$localUploadAccess && !$thirdPartyUploadAccess)
		{
			if ($my->id == 0)
			{
				$smartyvs->assign("showconnectionbox", 1);
			}
			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_NOACCESS, _HWDVIDS_ALERT_UPLD_NOT_AUTHORIZED, "exclamation.png", 0);
			return;
		}

		$videotype = JRequest::getCmd( 'videotype' );

		if ($j15)
		{
			$db->SetQuery( 'SELECT count(*) FROM #__plugins WHERE published = 1 AND folder = "hwdvs-thirdparty"');
		}
		if ($j16)
		{
			$db->SetQuery( 'SELECT count(*) FROM #__extensions WHERE type = "plugin" AND folder = "hwdvs-thirdparty" AND enabled = 1');
		}

  		$thirdpartycount = $db->loadResult();
		echo $db->getErrorMsg();

		$checksecurity = "0";
		if ((empty($videotype)) && ($thirdpartycount > 0) && ($c->disablelocupld == 0) && $localUploadAccess) {
			$uploadpage = "0";
		} else if ((empty($videotype)) && (!$thirdPartyUploadAccess)) {
			$uploadpage = "1";
		} else if ( (empty($videotype)) && (!$thirdpartycount || $thirdpartycount == 0) && ($c->disablelocupld == 0) ) {
			$uploadpage = "1";
		} else if ( (empty($videotype)) && ($thirdpartycount > 0) && ($c->disablelocupld == 1)) {
			$uploadpage = "thirdparty";
		} else if ( (empty($videotype)) && (!$thirdpartycount || $thirdpartycount == 0) && ($c->disablelocupld == 1)) {
        	hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_NOUPLDMETH, "exclamation.png", 0);
			return;
		} else if ($videotype == "00") {
			$uploadpage = "1";
		} else if ($videotype == "local") {
			$uploadpage = "2";
			$checksecurity = "1";
		} else {
			$uploadpage = "thirdparty";
		}

		$title 				= Jrequest::getVar( 'title', _HWDVIDS_UNKNOWN );
		$description 		= Jrequest::getVar( 'description', _HWDVIDS_UNKNOWN );
		$category_id 		= JRequest::getInt( 'category_id', 0, 'post' );
		$tags 				= Jrequest::getVar( 'tags', _HWDVIDS_UNKNOWN );
		$public_private 	= JRequest::getWord( 'public_private' );
		$allow_comments 	= JRequest::getInt( 'allow_comments', 0 );
		$allow_embedding 	= JRequest::getInt( 'allow_embedding', 0 );
		$allow_ratings 		= JRequest::getInt( 'allow_ratings', 0 );
		$hwdvspassword 		= JRequest::getVar( 'hwdvspassword', "" );
		if (!empty($hwdvspassword))
		{
			$md5password = md5($hwdvspassword);
		}
		else
		{
			$md5password = null;
		}

		$security_code = JRequest::getCmd( 'security_code', '' );

		if ($c->disablecaptcha == "1") {
			$checksecurity = "0";
		}

		if ($checksecurity == "1") {
			if(($_SESSION['security_code'] == $security_code) && (!empty($_SESSION['security_code'])) ) {
					// Insert you code for processing the form here, e.g emailing the submission, entering it into a database.
					hwd_vs_html::uploadmedia($uploadpage, $videotype, $checksecurity, $title, $description, $category_id, $tags, $public_private, $allow_comments, $allow_embedding, $allow_ratings, $md5password);
					unset($_SESSION['security_code']);
			} else {
				// Insert your code for showing an error message here
        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_ERRSC, "exclamation.png", 0);
				return;
			}
		} else {
			hwd_vs_html::uploadMedia($uploadpage, $videotype, $checksecurity, $title, $description, $category_id, $tags, $public_private, $allow_comments, $allow_embedding, $allow_ratings, $md5password);
		}
	}
    /**
     * Outputs frontpage HTML
     *
     * @return       Nothing
     */
    function addConfirm($admin_import=false)
	{
		global $Itemid, $j15, $j16;
		$c = hwd_vs_Config::get_instance();
		$db = & JFactory::getDBO();
		$my = & JFactory::getUser();
		$acl= & JFactory::getACL();

		$security_code = JRequest::getCmd( 'security_code', '' );
		if ($c->disablecaptcha == "1")
		{
			$checksecurity = "0";
		}
		else
		{
			$checksecurity = "1";
		}
		if ($checksecurity == "1" && !$admin_import)
		{
			if(($_SESSION['security_code'] == $security_code) && (!empty($_SESSION['security_code'])) )
			{
				unset($_SESSION['security_code']);
			}
			else
			{
        		hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_ERRSC, "exclamation.png", 0);
				return;
			}
		}

		$requestarray = JRequest::get( 'default', 2 );
		$embeddump = $requestarray['embeddump'];
		$remote_verified = null;

		$parsedurl = parse_url($embeddump);
		if (empty($parsedurl['host'])) { $parsedurl['host'] = ''; }
		preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $parsedurl['host'], $regs);
		if (empty($regs['domain'])) { $regs['domain'] = ''; }

		if ($j15)
		{
			if ($regs['domain'] == 'youtube.com' && file_exists(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'youtube.php'))
			{
				require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'youtube.php');
			}
			else if ($regs['domain'] == 'google.com' && file_exists(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'google.php'))
			{
				require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'google.php');
			}
			else if (file_exists(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.$regs['domain'].'.php'))
			{
				require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.$regs['domain'].'.php');
			}
			else
			{
				require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'youtube.php');
				$regs['domain'] = 'remote';
			}
		}
		if ($j16)
		{
			if ($regs['domain'] == 'youtube.com' && file_exists(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'youtube'.DS.'youtube.php'))
			{
				require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'youtube'.DS.'youtube.php');
			}
			else if ($regs['domain'] == 'google.com' && file_exists(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'google'.DS.'google.php'))
			{
				require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'google'.DS.'google.php');
			}
			else if (file_exists(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'thirdpartysupportpack'.DS.$regs['domain'].'.php'))
			{
				require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'thirdpartysupportpack'.DS.$regs['domain'].'.php');
			}
			else
			{
				require_once(JPATH_SITE.DS.'plugins'.DS.'hwdvs-thirdparty'.DS.'remote'.DS.'remote.php');
				$regs['domain'] = 'remote';
			}
		}

		$failures = "";
		if (!isset($remote_verified)) {

			$cn = 'hwd_vs_tp_'.preg_replace("/[^a-zA-Z0-9s_-]/", "", $regs['domain']);
			$f_processc = preg_replace("/[^a-zA-Z0-9s_-]/", "", $regs['domain']).'processCode';
			$f_processi = preg_replace("/[^a-zA-Z0-9s_-]/", "", $regs['domain']).'processThumbnail';
			$f_processt = preg_replace("/[^a-zA-Z0-9s_-]/", "", $regs['domain']).'processTitle';
			$f_processd = preg_replace("/[^a-zA-Z0-9s_-]/", "", $regs['domain']).'processDescription';
			$f_processk = preg_replace("/[^a-zA-Z0-9s_-]/", "", $regs['domain']).'processKeywords';
			$f_processl = preg_replace("/[^a-zA-Z0-9s_-]/", "", $regs['domain']).'processDuration';

			$tp = new $cn();

			$ext_v_code  = $tp->$f_processc($embeddump);

			//check if already exists
			$db->SetQuery( 'SELECT count(*) FROM #__hwdvidsvideos WHERE video_id = "'.$ext_v_code[1].'"' );
			$duplicatecount = $db->loadResult();

			if ($duplicatecount > 0 && $admin_import == false) {
				hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_DUPLICATE, "exclamation.png", 0);
				return;
			} else if ($duplicatecount > 0 && $admin_import == true) {
				return false;
			}

			$ext_v_title = $tp->$f_processt($embeddump, @$ext_v_code[1]);
			$ext_v_descr = $tp->$f_processd($embeddump, @$ext_v_code[1]);
			$ext_v_keywo = $tp->$f_processk($embeddump, @$ext_v_code[1]);
			$ext_v_durat = $tp->$f_processl($embeddump, @$ext_v_code[1]);

			if ($ext_v_code[0] == "0") {

				require_once(JPATH_SITE.'/plugins/hwdvs-thirdparty/remote.php');
				$regs['domain'] = 'remote';

				$tp = new hwd_vs_tp_remote();
				$ext_v_code  = $tp->remoteProcessCode($embeddump);
				$ext_v_title = $tp->remoteProcessTitle($embeddump, @$ext_v_code[1]);
				$ext_v_descr = $tp->remoteProcessDescription($embeddump, @$ext_v_code[1]);
				$ext_v_keywo = $tp->remoteProcessKeywords($embeddump, @$ext_v_code[1]);
				$ext_v_durat = $tp->remoteProcessDuration($embeddump, @$ext_v_code[1]);

				if ($ext_v_code[0] == "0") {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_INFO_TPPROCESSFAIL, "exclamation.png", 0);
					return;
				}

				//check if already exists
				$db->SetQuery( 'SELECT count(*) FROM #__hwdvidsvideos WHERE video_id = "'.$ext_v_code[1].'"' );
				$duplicatecount = $db->loadResult();

				if ($duplicatecount > 0 && $admin_import == false) {
					hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, _HWDVIDS_ALERT_DUPLICATE, "exclamation.png", 0);
					return;
				} else if ($duplicatecount > 0 && $admin_import == true) {
					return false;
				}
			}

			if ($ext_v_title[0] == 0) {$failures.=_HWDVIDS_INFO_TPTITLEFAIL."<br />";}
			if ($ext_v_descr[0] == 0) {$failures.=_HWDVIDS_INFO_TPDESCFAIL."<br />";}
			if ($ext_v_keywo[0] == 0) {$failures.=_HWDVIDS_INFO_TPKWFAIL."<br />";}
			if ($ext_v_durat[0] == 0) {$failures.=_HWDVIDS_INFO_TPDRFAIL."<br />";}

		} else if ($remote_verified == 0) {

			$error_msg = _HWDVIDS_ERROR_UPLDERR11."<br /><br />"._HWDVIDS_INFO_SUPPTPW."<br />".hwd_vs_tools::generateSupportedWebsiteList();
			hwd_vs_tools::infomessage(4, 0, _HWDVIDS_TITLE_UPLDFAIL, $error_msg, "exclamation.png", 1);
			return;

		}

		$title 				= hwd_vs_tools::generatePostTitle($ext_v_title[1]);
		$description 		= hwd_vs_tools::generatePostDescription($ext_v_descr[1]);
		$tags 				= hwd_vs_tools::generatePostTags($ext_v_keywo[1]);
		$category_id 		= JRequest::getInt( 'category_id', 0, 'post' );
		$public_private 	= JRequest::getWord( 'public_private' );
		$allow_comments 	= JRequest::getInt( 'allow_comments', 0, 'post' );
		$allow_embedding 	= JRequest::getInt( 'allow_embedding', 0, 'post' );
		$allow_ratings 		= JRequest::getInt( 'allow_ratings', 0, 'post' );

		$checkform = hwd_vs_tools::checkFormComplete($title, $description, $category_id, $tags, $public_private, $allow_comments, $allow_embedding, $allow_ratings);
		if (!$checkform) { return; }

		$row = new hwdvids_video($db);

		$password = Jrequest::getVar( 'hwdvspassword', '' );
		if (!empty($password))
		{
			$password = md5($password);
			$_POST['password'] 		= $password;
		}

		$_POST['video_type'] 		= $regs['domain'];
		$_POST['video_id'] 			= $ext_v_code[1];
		$_POST['title'] 			= $title;
		$_POST['description'] 		= $description;
		$_POST['category_id'] 		= $category_id;
		$_POST['tags'] 				= $tags;
		$_POST['public_private'] 	= $public_private;
		$_POST['allow_comments'] 	= $allow_comments;
		$_POST['allow_embedding'] 	= $allow_embedding;
		$_POST['allow_ratings'] 	= $allow_ratings;
		$_POST['video_length'] 		= $ext_v_durat[1];
		$_POST['date_uploaded'] 	= date('Y-m-d H:i:s');

		if ($admin_import)
		{
			$_POST['user_id'] 		= $_REQUEST['user_id'];
		}
		else
		{
			$_POST['user_id'] 		= $my->id;
		}

		if ($c->aa3v == 1) {
			$_POST['approved'] 	= "yes";
			$_POST['published'] = "1";
		} else {
			$_POST['approved'] 	= "pending";
			$_POST['published'] = "0";
		}

		// bind it to the table
		if (!$row->bind($_POST))
		{
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
		}

		// store it in the db
		if (!$row->store())
		{
			echo "<script type=\"text/javascript\">alert('".$row->getError()."');window.history.go(-1);</script>\n";
			exit();
		}

		include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'helpers'.DS.'events.php');

		$params->title = $title;
		$params->id = $row->id;
		$params->category_id = $row->category_id;
		$params->type = $row->video_type;
		$params->user_id = $row->user_id;

		hwdvsEvent::onAfterVideoUpload($params);

		// save remote thumbnail to disk
		$data = @explode(",", $row->video_id);
		$thumburl = hwd_vs_tools::get_final_url( @$ext_v_code[2] );
		$thumbbase = "tp-".$row->id.".jpg";
		$thumbpath = JPATH_SITE.DS."hwdvideos".DS."thumbs".DS.$thumbbase;

		$ch = curl_init ($thumburl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$rawdata=curl_exec($ch);
		curl_close ($ch);
		if(file_exists($thumbpath))
		{
			unlink($thumbpath);
		}
		$fp = fopen($thumbpath,'x');
		fwrite($fp, $rawdata);
		fclose($fp);

		if(file_exists($thumbpath))
		{
			$db->SetQuery( "UPDATE #__hwdvidsvideos SET `thumbnail` = \"$thumbbase\" WHERE id = $row->id" );
			$db->Query();
		}

		$video = new hwdvids_video($db);
		$video->load( $row->id );

		if (!$admin_import) {
			hwd_vs_html::addConfirm($title, $failures, $video);
		} else {
			return true;
		}
	}
}
?>