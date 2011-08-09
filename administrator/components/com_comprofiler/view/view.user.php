<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: view.user.php 1368 2011-01-28 14:06:18Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : user view
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBView_user {
	/**
	 * override styles from webfxlayout
	 *
	 */
	function _overideWebFxLayout() {
		global $_CB_framework;

		ob_start();
?>
.dynamic-tab-pane-control h2 {
	text-align:	center;
	width:		auto;
}

.dynamic-tab-pane-control h2 a {
	display:	inline;
	width:		auto;
}

.dynamic-tab-pane-control a:hover {
	background: transparent;
}
<?php
		$css	=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->document->addHeadStyleInline( $css );
	}

	function edituser( $user, $option, $newCBuser, &$postdata ) {
		global $_CB_framework, $_PLUGINS;

		$results = $_PLUGINS->trigger( 'onBeforeUserProfileEditDisplay', array( &$user, 2 ) );
		if ($_PLUGINS->is_errors()) {
			echo "<script type=\"text/javascript\">alert(\"" . str_replace( array("\n",'<br />'), array('\\n','\\n'), addslashes( $_PLUGINS->getErrorMSG() ) ) ."\"); window.history.go(-1); </script>\n";
			exit();
		}

		_CBsecureAboveForm('edituser');
		outputCbTemplate(2);
		initToolTip(2);
		$tabs			=	new cbTabs( ( ( ( $_CB_framework->getUi() == 2 ) && ( ! isset($_REQUEST['tab']) ) ) ? 1 : 0 ), 2 );		// use cookies in backend to remember selected tab.
		$tabcontent		=	$tabs->getEditTabs( $user, $postdata );

		outputCbJs( 2 );

		global $_CB_Backend_Title;
//OLD:	$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-users', "Community Builder User: <small>" . ( $user->id ? "Edit" . ' [ '. $user->username .' ]' : "New" ) . '</small>' ) );
//NEW:
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-users', CBTxt::T('Community Builder User') . ": <small>" . ( $user->id ? CBTxt::T('Edit') . ' [ '. $user->username .' ]' : CBTxt::T('New') ) . '</small>' ) );

		ob_start();

	if ( defined( '_CB_VALIDATE_NEW' ) ) {
		cbimport( 'cb.validator' );
		cbValidator::renderGenericJs();
?>

$('div.cbtoolbarbar a.cbtoolbar').click( function() {
		var taskVal = $(this).attr('href').substring(1);

		$('#cbcheckedadminForm input[name=task]').val( taskVal );
		if (taskVal == 'showusers') {
			$('#cbcheckedadminForm')[0].submit();
		} else {
			$('#cbcheckedadminForm').submit();
		}
		return false;
	} );

<?php
			$cbjavascript	=	ob_get_contents();
			ob_end_clean();
			$_CB_framework->outputCbJQuery( $cbjavascript, array( 'metadata', 'validate' ) );
		} else {
			// old way:
?>
var cbDefaultFieldbackgroundColor;
function cbFrmSubmitButton() {
	var me = this.elements;
<?php
$version = checkJversion();
if ($version == 1) {
	// var r = new RegExp("^[a-zA-Z](([\.\-a-zA-Z0-9@])?[a-zA-Z0-9]*)*$", "i");
?>
	var r = new RegExp("^[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]*$", "i");
<?php
} elseif ( $version == -1 ) {
?>
	var r = new RegExp("[^A-Za-z0-9]", "i");
<?php
} else {
?>
	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
<?php
}
?>
	var errorMSG = '';
	var iserror=0;
	if (cbDefaultFieldbackgroundColor === undefined) cbDefaultFieldbackgroundColor = ((me['username'].style.getPropertyValue) ? me['username'].style.getPropertyValue("backgroundColor") : me['username'].style.backgroundColor);
<?php echo $tabs->fieldJS; ?>
	if (me['username'].value == "") {
		errorMSG += "<?php echo str_replace( array( "\n", "\r" ), ' ', CBTxt::html_entity_decode( _REGWARN_UNAME ) ); ?>\n";
		me['username'].style.backgroundColor = "red";
		iserror=1;
	} else if (r.exec(me['username'].value) || (me['username'].value.length < 3)) {
		errorMSG += "<?php echo str_replace( array( "\n", "\r" ), ' ', sprintf( CBTxt::html_entity_decode(_VALID_AZ09), CBTxt::html_entity_decode( _PROMPT_UNAME ), 2 ) );?>\n";
		me['username'].style.backgroundColor = "red";
		iserror=1;
	} else if (me['username'].style.backgroundColor.slice(0,3)=="red") {
		me['username'].style.backgroundColor = cbDefaultFieldbackgroundColor;
	}
	if ((me['password'].value != "") && (me['password'].value != me['password__verify'].value)){
		errorMSG += "<?php echo CBTxt::html_entity_decode(_REGWARN_VPASS2);?>\n";
		me['password'].style.backgroundColor = "red"; me['password__verify'].style.backgroundColor = "red";
		iserror=1;
	} else {
		if (me['password'].style.backgroundColor.slice(0,3)=="red") me['password'].style.backgroundColor = cbDefaultFieldbackgroundColor;
		if (me['password__verify'].style.backgroundColor.slice(0,3)=="red") me['password__verify'].style.backgroundColor = cbDefaultFieldbackgroundColor;
	}
	if (!$('input[name^=\"gid\"],select[name^=\"gid\"]').val()) {
		errorMSG += '<?php echo addslashes( CBTxt::T('You must assign user to a group.') ); ?>' + "\n";
		iserror=1;
	}

	// loop through all input elements in form
	var fieldErrorMessages = new Array;
	for (var i=0; i < me.length; i++) {
		// check if element is mandatory; here mosReq=1
		if ( (typeof(me[i].getAttribute('mosReq')) != "undefined") && ( me[i].getAttribute('mosReq') == 1) ) {
			if (me[i].type == 'radio' || me[i].type == 'checkbox') {
				var rOptions = me[me[i].getAttribute('name')];
				var rChecked = 0;
				if(rOptions.length > 1) {
					for (var r=0; r < rOptions.length; r++) {
						if ( (typeof(rOptions[r].getAttribute('mosReq')) != "undefined") && ( rOptions[r].getAttribute('mosReq') == 1) ) {
							if (rOptions[r].checked) {
								rChecked=1;
							}
						}
					}
				} else {
					if (me[i].checked) {
						rChecked=1;
					}
				}
				if(rChecked==0) {
					for (var k=0; k < me.length; k++) {
						if (me[i].getAttribute('name') == me[k].getAttribute('name')) {
							if (me[k].checked) {
								rChecked=1;
								break;
							}
						}
					}
				}
				if(rChecked==0) {
					var alreadyFlagged = false;
					for (var j = 0, n = fieldErrorMessages.length; j < n; j++) {
						if (fieldErrorMessages[j] == me[i].getAttribute('name')) {
							alreadyFlagged = true;
							break
						}
					}
					if ( ! alreadyFlagged ) {
						fieldErrorMessages.push(me[i].getAttribute('name'));
						// add up all error messages
						errorMSG += me[i].getAttribute('mosLabel') + ' : <?php echo CBTxt::html_entity_decode(_UE_REQUIRED_ERROR); ?>\n';
						// notify user by changing background color, in this case to red
						me[i].style.backgroundColor = "red";
						iserror=1;
					}
				} else if (me[i].style.backgroundColor.slice(0,3)=="red") me[i].style.backgroundColor = cbDefaultFieldbackgroundColor;
			}
			if (me[i].value == '') {
				// add up all error messages
				errorMSG += me[i].getAttribute('mosLabel') + ' : <?php echo CBTxt::html_entity_decode(_UE_REQUIRED_ERROR); ?>\n';
				// notify user by changing background color, in this case to red
				me[i].style.backgroundColor = "red";
				iserror=1;
			} else if (me[i].style.backgroundColor.slice(0,3)=="red") me[i].style.backgroundColor = cbDefaultFieldbackgroundColor;
		}
	}
	if(iserror==1) {
		alert(errorMSG);
		return false;
	} else {
		return true;
	}
}
$('#cbcheckedadminForm').submit( cbFrmSubmitButton );
$('div.cbtoolbarbar a.cbtoolbar').click( function() {
		var taskVal = $(this).attr('href').substring(1);
		$('#cbcheckedadminForm input[name=task]').val( taskVal );
		if (taskVal == 'showusers') {
			$('#userEditTable input').val('');
			$('#cbcheckedadminForm')[0].submit();
		} else {
			$('#cbcheckedadminForm').submit();
		}
		return false;
	} );
<?php
		$cbjavascript	=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->outputCbJQuery( $cbjavascript );
		// end of old way
	}

		if ( is_array( $results ) ) {
			echo implode( '', $results );
		}

		$this->_overideWebFxLayout();
?>
<div id="cbErrorMessages"></div>
<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm" id="cbcheckedadminForm" enctype="multipart/form-data" autocomplete="off">
<?php
echo "<table cellspacing='0' cellpadding='4' border='0' width='100%' id='userEditTable'><tr><td width='100%'>\n";
echo $tabcontent;
echo "</td></tr></table>";
?>
  <input type="hidden" name="id" value="<?php echo $user->id; ?>" />
  <input type="hidden" name="newCBuser" value="<?php echo $newCBuser; ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="save" />
  <?php
	echo cbGetSpoofInputTag( 'user' );
  ?>
</form>
<div style="align:center;">
<?php
echo getFieldIcons(2,true,true,"","",true);
if( isset( $_REQUEST['tab'] ) ) {
	$_CB_framework->outputCbJQuery( "showCBTab( '" . addslashes( urldecode( stripslashes( cbGetParam( $_REQUEST, 'tab' ) ) ) ) . "' );" );
}
?>
</div>
<?php }

}	// class CBView_user

?>