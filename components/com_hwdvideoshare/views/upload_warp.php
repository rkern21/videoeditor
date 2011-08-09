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

global $hwdvsItemid, $smartyvs;
$c = hwd_vs_Config::get_instance();

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_hwdvideoshare'.DS.'libraries'.DS.'warp'.DS.'infin-lib.php');

$videoInfo = "title=".htmlspecialchars($title)."&description=".htmlspecialchars($description)."&category_id=".htmlspecialchars($category_id)."&tags=".htmlspecialchars($tags)."&public_private=".htmlspecialchars($public_private)."&allow_comments=".htmlspecialchars($allow_comments)."&allow_embedding=".htmlspecialchars($allow_embedding)."&allow_ratings=".htmlspecialchars($allow_ratings);

$accountKey = $c->warpAccountKey;
$secretKey = $c->warpSecretKey;
$infinVideo = new InfinovationVideo($accountKey, $secretKey);
$newVideoGuid = $infinVideo->getNewVideoGuid();
$uploadSignature = $infinVideo->generateUploadSignature($newVideoGuid);
$postUrl = urlencode(JRoute::_("index.php?option=com_hwdvideoshare&Itemid=".$hwdvsItemid."&task=uploadconfirmwarp&videoGuid=$newVideoGuid&$videoInfo"));
$flashvar = "AccountKey=$accountKey&VideoGuid=$newVideoGuid&Signature=$uploadSignature&PostURL=$postUrl&AllowWebcam=1&SizeLimit=0&RecordingLimit=0&MaxDuration=0";
$ul =  '<script type="text/javascript" language="javascript" src="'.JURI::base().'/components/com_hwdvideoshare/assets/js/AC_OETags.js"></script>
		<script type="text/javascript" language="javascript">
		<!--
		var requiredMajorVersion = 9;
		var requiredMinorVersion = 0;
		var requiredRevision = 115;
		var hasProductInstall = DetectFlashVer(6, 0, 65);
		var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);

		if ( hasProductInstall && !hasRequestedVersion ) {
			var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
			var MMredirectURL = window.location;
			document.title = document.title.slice(0, 47) + " - Flash Player Installation";
			var MMdoctitle = document.title;

			AC_FL_RunContent(
				"src", "'.JURI::base().'/components/com_hwdvideoshare/assets/uploads/warp/playerProductInstall",
				"FlashVars", "MMredirectURL="+MMredirectURL+\'&MMplayerType=\'+MMPlayerType+\'&MMdoctitle=\'+MMdoctitle+"",
				"width", "420",
				"height", "390",
				"align", "middle",
				"id", "InfinovationVideoUploader",
				"quality", "high",
				"bgcolor", "#ffffff",
				"name", "InfinovationVideoUploader",
				"allowScriptAccess","always",
				"type", "application/x-shockwave-flash",
				"pluginspage", "http://www.adobe.com/go/getflashplayer"
			);
		} else if (hasRequestedVersion) {
			AC_FL_RunContent(
					"src", "http://infinovision.s3.amazonaws.com/VideoUploaderRecorder",
					"width", "420",
					"height", "390",
					"align", "middle",
					"id", "VideoUploaderRecorder",
					"quality", "high",
					"name", "VideoUploaderRecorder",
					"allowScriptAccess","always",
					"type", "application/x-shockwave-flash",
					"pluginspage", "http://www.adobe.com/go/getflashplayer",
					"wmode", "transparent",
					"flashvars", "'.$flashvar.'"
			);
		  } else {  // flash is too old or we can\'t detect the plugin
			var alternateContent = \'This content requires the Adobe Flash Player.\'
			+ \'<a href=http://www.adobe.com/go/getflash/>Get Flash</a>\';
			document.write(alternateContent);  // insert non-flash content
		  }
		// -->
		</script>
		<noscript>
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
					id="VideoUploaderRecorder" name="VideoUploaderRecorder" width="420" height="390"
					codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
					<param name="movie" value="http://infinovision.s3.amazonaws.com/VideoUploaderRecorder.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="allowScriptAccess" value="always" />
					<param name="wmode" value="transparent" />
					<param name="flashvars" value="'.$flashvar.'" />
					<embed src="http://infinovision.s3.amazonaws.com/VideoUploaderRecorder.swf"
						width="420"
						height="390"
						id="VideoUploaderRecorder"
						name="VideoUploaderRecorder"
						align="middle"
						play="true"
						loop="false"
						quality="high"
						wmode="transparent"
						allowScriptAccess="always"
						type="application/x-shockwave-flash"
						pluginspage="http://www.adobe.com/go/getflashplayer">
					</embed>
			</object>
		</noscript>';

$smartyvs->assign("uploader", $ul);
