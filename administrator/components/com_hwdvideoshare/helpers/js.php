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
class hwd_vs_javascript
{
    /**
     *
     */
    function confirmDelete($ajax=null)
	{
		$doc = & JFactory::getDocument();

		$code=null;
		$code.='function confirmDelete()
				{
					var agree=confirm("'._HWDVIDS_INFO_CONFIRMFRONTDEL.'");
					if (agree)
					 return true;
					else
					 return false;
				}';

		if (!defined('_HWD_VS_JS_CONFIRMDELETE'))
		{
			define( '_HWD_VS_JS_CONFIRMDELETE', 1 );
			if ($doc->getType() != 'raw')
			{
				$doc->addCustomTag("<script language=\"javascript\" type=\"text/javascript\">$code</script>");
			}
			else
			{
				echo "<script language=\"javascript\" type=\"text/javascript\">$code</script>";
			}
		}
		return;
	}
    /**
     *
     */
    function disableSubmit()
	{
		$doc = & JFactory::getDocument();

		$code=null;
		$code.='function function disablesubmit ()
				{
					videoupload.send.disabled=true
				}';

		if (!defined('_HWD_VS_JS_DISABLESUBMIT'))
		{
			define( '_HWD_VS_JS_DISABLESUBMIT', 1 );
			if ($doc->getType() != 'raw')
			{
				$doc->addCustomTag("<script language=\"javascript\" type=\"text/javascript\">$code</script>");
			}
			else
			{
				echo "<script language=\"javascript\" type=\"text/javascript\">$code</script>";
			}
		}
		return;
	}
    /**
     *
     */
    function checkUploadForm()
	{
		global $task;
		$c = hwd_vs_Config::get_instance();
		$doc = & JFactory::getDocument();

		$code=null;
		$code.='function chkform ()
				{
					var form = document.videoupload;
					if (form.title.value == "")
					{
						alert("'._HWDVIDS_ALERT_NOTITLE.'");
						form.title.focus();
						return false;
					}
					else if (form.description.value == "")
					{
						alert("'._HWDVIDS_ALERT_NODESC.'");
						form.description.focus();
						return false;
					}
					else if (form.category_id.value == "0")
					{
						alert("'._HWDVIDS_ALERT_NOCAT.'");
						form.category_id.focus();
						return false;
					}
					else if (form.tags.value == "")
					{
						alert("'._HWDVIDS_ALERT_NOTAG.'");
						form.tags.focus();
						return false;
					}';

		if ($c->disablecaptcha == 0 && $task == "upload")
		{
		$code.='    else if (form.security_code.value == "")
					{
						alert("'._HWDVIDS_ALERT_NOSECURE.'");
						form.security_code.focus();
						return false;
					}';
		}
		$code.='    else {
						document.videoupload.send.disabled=true;
					}';

		if (!defined( '_HWD_VS_JS_CHECKUPLOADFORM' ) && $doc->getType() != 'raw')
		{
			define( '_HWD_VS_JS_CHECKUPLOADFORM', 1 );
			if ($doc->getType() != 'raw')
			{
				$doc->addCustomTag("<script language=\"javascript\" type=\"text/javascript\">$code</script>");
			}
			else
			{
				echo "<script language=\"javascript\" type=\"text/javascript\">$code</script>";
			}
		}
		return;
	}
    /**
     *
     */
	function checkAddForm()
	{ ?>
	<script language="javascript" type="text/javascript">
		function chkaddform () {
		var form = document.videoadd;
		if (form.embeddump.value == "") {
    		alert("<?php echo _HWDVIDS_ALERT_NOEMBEDCODE ?>");
    		form.embeddump.focus();
    		return false;
  		} else if (form.category_id.value == "0") {
    		alert("<?php echo _HWDVIDS_ALERT_NOCAT ?>");
    		form.category_id.focus();
    		return false;
    	} else {
			document.videoadd.send.disabled=true;
  		}
	}
	</script>
	<?php }
    /**
     *
     */
    function CheckEditForm()
	{
	$c = hwd_vs_Config::get_instance();
	?>
	<script language="javascript" type="text/javascript">
		function chkform () {
		videoupload.send.disabled=true
		var form = document.videoupload;
		if (form.title.value == "") {
    		alert("<?php echo _HWDVIDS_ALERT_NOTITLE ?>");
    		form.title.focus();
    		return false;
  		} else if (form.description.value == "") {
    		alert("<?php echo _HWDVIDS_ALERT_NODESC ?>");
    		form.description.focus();
    		return false;
  		} else if (form.category_id.value == "none") {
    		alert("<?php echo _HWDVIDS_ALERT_NOCAT ?>");
    		form.category_id.focus();
    		return false;
  		} else if (form.tags.value == "") {
    		alert("<?php echo _HWDVIDS_ALERT_NOTAG ?>");
    		form.tags.focus();
    		return false;
  		<?php
  		if ($c->disablecaptcha == 0) {
  			echo "} else if (form.security_code.value == \"\") {";
  			echo "alert(\""._HWDVIDS_ALERT_NOSECURE."\");";
  			echo "form.security_code.focus();";
  			echo "return false;";
		}
		?>
  		}
	}
	</script>
	<?php }
    /**
     *
     */
    function checkAddGroupForm()
	{
	$c = hwd_vs_Config::get_instance();
	?>
	<script language="javascript" type="text/javascript">
	function chkform () {
		var form = document.creategroup;
		if (form.group_name.value == "") {
    		alert("<?php echo _HWDVIDS_ALERT_NOGNAME ?>");
    		form.group_name.focus();
    		return false;
  		} else if (form.group_description.value == "") {
    		alert("<?php echo _HWDVIDS_ALERT_NOGDESC ?>");
    		form.group_description.focus();
    		return false;
  		<?php
  		if ($c->disablecaptcha == 0) {
  			echo "} else if (form.security_code.value == \"\") {";
  			echo "alert(\""._HWDVIDS_ALERT_NOSECURE."\");";
  			echo "form.security_code.focus();";
  			echo "return false;";
		}
		?>
  		}
	}
	</script>
	<?php }
    /**
     *
     */
    function checkAddPlaylistForm()
	{
	$c = hwd_vs_Config::get_instance();
	?>
	<script language="javascript" type="text/javascript">
	function chkform () {
		var form = document.createPlaylist;
		if (form.playlist_name.value == "") {
    		alert("<?php echo _HWDVIDS_ALERT_NOPLNAME ?>");
    		form.playlist_name.focus();
    		return false;
  		} else if (form.playlist_description.value == "") {
    		alert("<?php echo _HWDVIDS_ALERT_NOPLDESC ?>");
    		form.playlist_description.focus();
    		return false;
  		<?php
  		if ($c->disablecaptcha == 0) {
  			echo "} else if (form.security_code.value == \"\") {";
  			echo "alert(\""._HWDVIDS_ALERT_NOSECURE."\");";
  			echo "form.security_code.focus();";
  			echo "return false;";
		}
		?>
  		}
	}
	</script>
	<?php }
    /**
     *
     */
    function ajaxAddToFav($row, $remfav, $addfav)
	{
	$c = hwd_vs_Config::get_instance();
	$my = & JFactory::getUser();

	if ($my->id == 0)
	{
		$rff = $addfav;
		$atf = $remfav;
	}
	else
	{
		$rff = $remfav;
		$atf = $addfav;
	}

	?>
	<script language='javascript' type='text/javascript'>
	//Browser Support Code
	function ajaxFunctionATF(){
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("<?php echo _HWDVIDS_AJAX_BBROKE; ?>");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById('ajaxresponse').style.overflow = "hidden";
				document.getElementById('ajaxresponse').innerHTML = ajaxRequest.responseText;
				document.getElementById('addremfav').innerHTML = '<?php echo $rff ?>';
			}
		}
		ajaxRequest.open("GET", "<?php echo JURI::base( true )."/index.php?option=com_hwdvideoshare&task=ajax_addtofavourites&userid=".$my->id."&videoid=".$row->id; ?>", true);
		ajaxRequest.send(null);
	}
	function ajaxFunctionRFF(){
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("<?php echo _HWDVIDS_AJAX_BBROKE; ?>");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById('ajaxresponse').style.overflow = "hidden";
				document.getElementById('ajaxresponse').innerHTML = ajaxRequest.responseText;
				document.getElementById('addremfav').innerHTML = '<?php echo $atf ?>';
			}
		}

		ajaxRequest.open("GET", "<?php echo JURI::base( true )."/index.php?option=com_hwdvideoshare&task=ajax_removefromfavourites&userid=".$my->id."&videoid=".$row->id; ?>", true);
		ajaxRequest.send(null);
	}
	//-->
	</script>
	<?php }
    /**
     *
     */
    function ajaxSwitchQuality($row, $sq_button, $hq_button)
	{
	$c = hwd_vs_Config::get_instance();
	$my = & JFactory::getUser();
	?>
	<script language='javascript' type='text/javascript'>
	//Browser Support Code
	function ajaxSwitchStandardQuality(){
		var ajaxRequest;  // The variable that makes Ajax possible!

		document.getElementById('hwdvsplayer').style.padding = "0";
		document.getElementById('hwdvsplayer').style.margin = "0";
		document.getElementById('hwdvsplayer').innerHTML = '<div style="padding:5px;">Loading...<br /><img src="<?php echo JURI::root( true ); ?>/plugins/community/hwdvideoshare/loading.gif"></div>';

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("<?php echo _HWDVIDS_AJAX_BBROKE; ?>");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById('hwdvsplayer').innerHTML = ajaxRequest.responseText;
				document.getElementById('switchQuality').innerHTML = '<?php echo $hq_button ?>';

				var theInnerHTML = ajaxRequest.responseText;
				var theID = 'hwdvsplayer';
				setAndExecute(theID,theInnerHTML);
			}
		}
		ajaxRequest.open("GET", "<?php echo JURI::base( true )."/index.php?option=com_hwdvideoshare&task=grabajaxplayer&template=playeronly&quality=sd&video_id=".$row->id ?>", true);
		ajaxRequest.send(null);

		function setAndExecute(divId, innerHTML)
		{
			var div = document.getElementById(divId);
			div.innerHTML = innerHTML;
			var x = div.getElementsByTagName("script");
			for(var ii=0;ii<x.length;ii++)
			{
				eval(x[ii].text);
			}
		}
	}
	function ajaxSwitchHighQuality(){
		var ajaxRequest;  // The variable that makes Ajax possible!

		document.getElementById('hwdvsplayer').style.padding = "0";
		document.getElementById('hwdvsplayer').style.margin = "0";
		document.getElementById('hwdvsplayer').innerHTML = '<div style="padding:5px;">Loading...<br /><img src="<?php echo JURI::root( true ); ?>/plugins/community/hwdvideoshare/loading.gif"></div>';

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("<?php echo _HWDVIDS_AJAX_BBROKE; ?>");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById('hwdvsplayer').innerHTML = ajaxRequest.responseText;
				document.getElementById('switchQuality').innerHTML = '<?php echo $sq_button ?>';

				var theInnerHTML = ajaxRequest.responseText;
				var theID = 'hwdvsplayer';
				setAndExecute(theID,theInnerHTML);
			}
		}
		ajaxRequest.open("GET", "<?php echo JURI::base( true )."/index.php?option=com_hwdvideoshare&task=grabajaxplayer&template=playeronly&quality=hd&video_id=".$row->id ?>", true);
		ajaxRequest.send(null);

		function setAndExecute(divId, innerHTML)
		{
			var div = document.getElementById(divId);
			div.innerHTML = innerHTML;
			var x = div.getElementsByTagName("script");
			for(var ii=0;ii<x.length;ii++)
			{
				eval(x[ii].text);
			}
		}
	}
	//-->
	</script>
	<?php }
	/**
     *
     */
    function ajaxAddToGroup($row)
	{
	$c = hwd_vs_Config::get_instance();
	?>
	<script language='javascript' type='text/javascript'>
	//Browser Support Code
	function ajaxFunctionA2G(){
		var box = document.add2group.groupid.options;
		var chosen_value = box[box.selectedIndex].value;
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("<?php echo _HWDVIDS_AJAX_BBROKE; ?>");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById('add2groupresponse').innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "<?php echo JURI::base( true )."/index.php?option=com_hwdvideoshare&task=ajax_addvideotogroup&videoid=".$row->id."&groupid=" ?>"+ chosen_value , true);
		ajaxRequest.send(null);
	}
	//-->
	</script>
	<?php }
	/**
     *
     */
    function ajaxAddToPlaylist($row)
	{
	$c = hwd_vs_Config::get_instance();
	?>
	<script language='javascript' type='text/javascript'>
	//Browser Support Code
	function ajaxFunctionA2PL(){
		var box = document.add2playlist.playlistid.options;
		var chosen_value = box[box.selectedIndex].value;
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("<?php echo _HWDVIDS_AJAX_BBROKE; ?>");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById('add2playlistresponse').innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "<?php echo JURI::base( true )."/index.php?option=com_hwdvideoshare&task=ajax_addvideotoplaylist&videoid=".$row->id."&playlistid=" ?>"+ chosen_value , true);
		ajaxRequest.send(null);
	}
	//-->
	</script>
	<?php }
	/**
     *
     */
    function ajaxReportMedia($row)
	{
	$c = hwd_vs_Config::get_instance();
	$my = & JFactory::getUser();
	?>
	<script language='javascript' type='text/javascript'>
	//Browser Support Code
	function ajaxFunctionRV(){
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("<?php echo _HWDVIDS_AJAX_BBROKE; ?>");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById('ajaxresponse').style.overflow = "hidden";
				document.getElementById('ajaxresponse').innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "<?php echo JURI::base( true )."/index.php?option=com_hwdvideoshare&task=ajax_reportvideo&userid=".$my->id."&videoid=".$row->id."&userid=".$my->id ?>", true);
		ajaxRequest.send(null);
	}

	//-->
	</script>
	<?php }
    /**
     *
     */
    function ajaxRate($row)
	{
	$c = hwd_vs_Config::get_instance();
	$my = & JFactory::getUser();
	$ip = $_SERVER['REMOTE_ADDR'];
	?>
	<script language='javascript' type='text/javascript'>
	//Browser Support Code
	function ajaxFunctionRate(rate, id, rand){
		var ajaxRequest;  // The variable that makes Ajax possible!

		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("<?php echo _HWDVIDS_AJAX_BBROKE; ?>");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				document.getElementById('hwdvsrb'+rand).innerHTML = ajaxRequest.responseText;
			}
		}
		ajaxRequest.open("GET", "<?php echo JURI::base( true )."/index.php?option=com_hwdvideoshare&task=ajax_rate&videoid="; ?>" + id + "<?php echo "&rating="; ?>" + rate, true);
		ajaxRequest.send(null);
	}

		//-->
		</script>
	<?php }
}
?>