<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: view.field.php 1379 2011-01-29 15:21:36Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.php : field view
* @author Beat
* @copyright (C) Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBView_field {
	function editfield( &$row, $lists, $fieldvalues, $option, $paramsEditorHtml ) {
		global $_CB_framework, $_CB_database;

		_CBsecureAboveForm('editfield');
		outputCbTemplate( 2 );
		outputCbJs( 2 );
		initToolTip( 2 );

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-fields', CBTxt::T('Community Builder Field') . ': <small>' . ( $row->fieldid ? CBTxt::T('Edit') . ' [ ' . htmlspecialchars( getLangDefinition( $row->title ) ) . ' ] ' : CBTxt::T('New') ) . '</small>' ) );

		if ( $row->fieldid && ( ! $row->published ) ) {
			echo '<div class="cbWarning">' . CBTxt::T('Field is not published') . '</div>' . "\n";
		}
		if ( $row->pluginid ) {
			$plugin		=	new moscomprofilerPlugin( $_CB_database );
			if ( ! $plugin->load( (int) $row->pluginid ) ) {
				echo '<div class="cbWarning">' . CBTxt::T('Plugin is not installed') . '</div>' . "\n";
			} else {
				if ( ! $plugin->published ) {
					echo '<div class="cbWarning">' . CBTxt::T('Plugin is not published') . '</div>' . "\n";
				}
			}
		}

//		$_CB_framework->outputCbJQuery( "var cbTypeState = $('#type').val();	$('#type').change(function() { if ( cbTypeState != $('#type').val() ) submitbutton('reloadField') } ).change();" );
//		outputCbJs( 2 );
	if($row->fieldid > 0) {
		$_CB_framework->outputCbJQuery( 'document.adminForm.name.readOnly=true; document.adminForm.name.disabled=true; document.adminForm.type.disabled=true;');
	}
//		disableAll();
//		selType('".$row->type."');

	$editorSave_description		=	$_CB_framework->saveCmsEditorJS( 'description' );
	$editorSave_default			=	$_CB_framework->saveCmsEditorJS( 'default' );
		ob_start();
?>
   function submitbutton(pressbutton) {
     if ( (pressbutton == 'showField') || (pressbutton == 'reloadField') ) {
       document.adminForm.type.disabled=false;
       <?php echo $editorSave_description;
			if ( $row->type == 'editorta' ) {
				echo $editorSave_default;
			}
       ?>
       submitform(pressbutton);
       return;
     }
     var coll = document.adminForm;
     var errorMSG = '';
     var iserror=0;
     if (coll != null) {
       var elements = coll.elements;
       // loop through all input elements in form
       for (var i=0; i < elements.length; i++) {
         // check if element is mandatory; here mosReq=1
         if ( (typeof(elements.item(i).getAttribute('mosReq')) != "undefined") && (elements.item(i).getAttribute('mosReq') == 1) ) {
           if (elements.item(i).value == '') {
             //alert(elements.item(i).getAttribute('mosLabel') + ':' + elements.item(i).getAttribute('mosReq'));
             // add up all error messages
             errorMSG += elements.item(i).getAttribute('mosLabel') + ' : <?php echo _UE_REQUIRED_ERROR; ?>\n';
             // notify user by changing background color, in this case to red
             elements.item(i).style.backgroundColor = "red";
             iserror=1;
           }
         }
       }
     }
     if(iserror==1) {
       alert(errorMSG);
     } else {
       document.adminForm.type.disabled=false;
       <?php echo $editorSave_description;
			if ( $row->type == 'editorta' ) {
				echo $editorSave_default;
			}
       ?>
       submitform(pressbutton);
     }
   }
<?php
		$jsTop		=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->document->addHeadScriptDeclaration( $jsTop );
		ob_start();
?>
	function insertRow() {
		// Create and insert rows and cells into the first body.
//		var i = $('#adminForm input[name=valueCount]').val( Number( $('#adminForm input[name=valueCount]').val() ) + 1 ).val();
//		$('#fieldValuesBody').append('<tr><td><input id=\"vNames'+i+'\" name=\"vNames[' + i + ']\" /></td></tr>');
		var i = $('#adminForm input[name=valueCount]').val( Number( $('#adminForm input[name=valueCount]').val() ) + 1 ).val();
		$('#fieldValuesList').append('<li><input id=\"vNames'+i+'\" name=\"vNames[]\" /></li>');
		$('#vNames'+i).hide().slideDown('medium').focus();
	}

	function disableAll() {
		$('#divValues,#divColsRows,#divWeb,#divText').hide().css('visibility','visible');
		$('#vNames0').attr('mosReq','0');
	}

	function selType(sType) {
		var elem;
		//alert(sType);
		disableAll();
		switch (sType) {
			case 'editorta':
			case 'textarea':
				$('#divText,#divColsRows').show();
				break;

			case 'emailaddress':
			case 'password':
			case 'text':
			case 'integer':
			case 'predefined':
				$('#divText').show();
				break;

			case 'select':
			case 'multiselect':
				$('#divValues').show();
				$('#vNames0').attr('mosReq','1');
				break;

			case 'radio':
			case 'multicheckbox':
				$('#divValues,#divColsRows').show();
				$('#vNames0').attr('mosReq','1');
				break;

			case 'webaddress':
				$('#divText,#divWeb').show();
				break;

			case 'delimiter':
			default:
		}
	}

  function prep4SQL(o){
	if(o.value!='') {
		var cbsqloldvalue, cbsqlnewvalue;
		o.value=o.value.replace('cb_','');
		cbsqloldvalue = o.value;
		o.value=o.value.replace(/[^a-zA-Z0-9]+/g,'');
		cbsqlnewvalue = o.value;
		o.value='cb_' + o.value;
		if (cbsqloldvalue != cbsqlnewvalue) {
			alert('<?php echo addslashes( CBTxt::T('Warning: SQL name of field has been changed to fit SQL constraints') ); ?>');
		}
	}
  }
  var cbTypeState = $('#type').val();	$('#type').change(function() { selType(this.options[this.selectedIndex].value); if ( cbTypeState != $('#type').val() ) submitbutton('reloadField') } ).change();
  $('#name').change(function() { if ( ! $('#name').attr('disabled') ) { prep4SQL(this); } } ).change();
  $('#insertrow').click(function() { insertRow(); } );
  $('#fieldValuesList').sortable( { items: 'li', containment: 'parent', animated: true, placeholder: 'fieldValuesList-selected' } );
//  $('#mainparams').sortable( { items: 'tr', containment: 'parent', animated: true } );
  /* $('#adminForm').submit(function() { return submitbutton(''); } );	*/
  disableAll();
  selType('<?php echo $row->type; ?>');
<?php
$jsContent	=	ob_get_contents();
ob_end_clean();

		$_CB_framework->outputCbJQuery( $jsContent, 'ui-all' );
?>
<form action="<?php echo $_CB_framework->backendUrl( 'index.php?option=com_comprofiler&task=saveField' ); ?>" method="POST" id="adminForm" name="adminForm">
<?php
		if ( $paramsEditorHtml ) {
?>
  <table cellspacing="0" cellpadding="0" width="100%">
   <tr valign="top">
    <td width="60%" valign="top">
<?php
		}
?>

	<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform" id="mainparams">
		<tr>
			<td width="20%"><?php echo CBTxt::T('Type'); ?>:</td>
			<td width="20%"><?php echo $lists['type']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Tab'); ?>:</td>
			<td width="20%"><?php echo $lists['tabs']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Name'); ?>:</td>
			<td align=left  width="20%"><input type="text" id="name" name="name" maxlength='64' mosReq="1" mosLabel="<?php echo htmlspecialchars( CBTxt::T('Name') ); ?>" class="inputbox" value="<?php echo htmlspecialchars( $row->name ); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Title'); ?>:</td>
			<td width="20%" align=left><input type="text" name="title" mosReq="1" mosLabel="<?php echo htmlspecialchars( CBTxt::T('Title') ); ?>" class="inputbox" value="<?php echo htmlspecialchars( $row->title ); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3"><?php echo CBTxt::T('Description/"i" field-tip: text or HTML'); ?>:</td>
		</tr>
		<tr>
			<td colspan="3" align=left><?php echo $_CB_framework->displayCmsEditor( 'description', $row->description, 600 /* ( $row->type == 'delimiter' ? 600 : 286 ) */ , 200, 50, 7 );
			// <textarea name="description" cols="40" rows="6" maxlength='255' mosReq="0" mosLabel="Description" class="inputbox">< ?php echo htmlspecialchars( $row->description ); ? ></textarea>
			?></td>
		</tr>
<?php
		if ( $row->type != 'delimiter' ) { ?>

		<tr>
<?php		if ( $row->type == 'editorta' ) {	?>
			<td colspan="3"><?php echo CBTxt::T('Pre-filled default value at registration only'); ?>:</td>
		</tr>
		<tr>
			<td colspan="3"><?php
				echo $_CB_framework->displayCmsEditor( 'cb_default', $row->default, 600, 200, 50, 7 );
			?></td>
<?php
			} else {
				?>
			<td width="20%"><?php echo CBTxt::T('Pre-filled default value at registration only'); ?>:</td>
			<td width="20%">
				<input type="text" name="cb_default" mosLabel="<?php echo htmlspecialchars( CBTxt::T('Default value') ); ?>" class="inputbox" value="<?php echo htmlspecialchars( $row->default ); ?>" />
			</td>
			<td>&nbsp;</td><?php
			}
			?>
		</tr>
<?php
		}
?>

		<tr>
			<td width="20%"><?php echo CBTxt::T('Required'); ?>?:</td>
			<td width="20%"><?php echo $lists['required']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Show on Profile'); ?>?:</td>
			<td width="20%"><?php echo $lists['profile']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Display field title in Profile'); ?>?:</td>
			<td width="20%"><?php echo $lists['displaytitle']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Searchable in users-lists'); ?>?:</td>
			<td width="20%"><?php echo $lists['searchable']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('User Read Only'); ?>?:</td>
			<td width="20%"><?php echo $lists['readonly']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Show at Registration'); ?>?:</td>
			<td width="20%"><?php echo $lists['registration']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Published'); ?>:</td>
			<td width="20%"><?php echo ( $row->sys == 1 ? ( $row->published ? _UE_YES : _UE_NO ) . ' (' . CBTxt::T('System-fields cannot be published/unpublished here.') . ( in_array( $row->name, array( 'name', 'firstname', 'middlename', 'lastname' ) ) ? ' ' . CBTxt::T('Name-fields publishing depends on your setting in global CB config.') . ')' : ')' ) : $lists['published'] ); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Size'); ?>:</td>
			<td width="20%"><input type="text" name="size" mosLabel="<?php echo htmlspecialchars( CBTxt::T('Size') ); ?>" class="inputbox" value="<?php echo htmlspecialchars( $row->size ); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<div id="page1"  class="pagetext">

	</div>
	<div id="divText"  class="pagetext">
		<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
		<tr>
			<td width="20%"><?php echo CBTxt::T('Max Length'); ?>:</td>
			<td width="20%"><input type="text" name="maxlength" mosLabel="<?php echo htmlspecialchars( CBTxt::T('Max Length') ); ?>" class="inputbox" value="<?php echo htmlspecialchars( $row->maxlength ); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
		</table>
	</div>
	<div id="divColsRows"  class="pagetext">
		<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
		<tr>
			<td width="20%"><?php echo CBTxt::T('Cols'); ?>:</td>
			<td width="20%"><input type="text" name="cols" mosLabel="<?php echo htmlspecialchars( CBTxt::T('Cols') ); ?>" class="inputbox" value="<?php echo htmlspecialchars( $row->cols ); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo CBTxt::T('Rows'); ?>:</td>
			<td width="20%"><input type="text" name="rows"  mosLabel="<?php echo htmlspecialchars( CBTxt::T('Rows') ); ?>" class="inputbox" value="<?php echo htmlspecialchars( $row->rows ); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
		</table>
	</div>
	<div id="divWeb"  class="pagetext">
		<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
		<tr>
			<td width="20%"><?php echo CBTxt::T('Type'); ?>:</td>
			<td width="20%"><?php echo $lists['webaddresstypes']; ?></td>
			<td>&nbsp;</td>
		</tr>
		</table>
	</div>
	<div id="divValues" style="text-align:left;">
		<?php echo CBTxt::T('Use the table below to add new values.'); ?><br />
		<input type=button id="insertrow" value="<?php echo htmlspecialchars( CBTxt::T('Add a Value') ); ?>" />
		<table align="left" id="divFieldValues" cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform" >
		<thead>
		<tr>
			<th width="20%"><?php echo CBTxt::T('Name'); ?></th>
		</tr>
		</thead>
		<tbody id="fieldValuesBody">
		<tr>
			<td>
				<ul id="fieldValuesList">
	<?php
		//echo "count:".count( $fieldvalues );
		//print_r (array_values($fieldvalues));
		for ($i=0, $n=count( $fieldvalues ); $i < $n; $i++) {
			//print "count:".$i;
			$fieldvalue = $fieldvalues[$i];
			if ($i==0) $req =1;
			else $req = 0;
			echo "\n<li><input type='text' mosReq='$req'  mosLabel='" . htmlspecialchars( CBTxt::T('Value') ) . "' value=\"" . htmlspecialchars( $fieldvalue->fieldtitle ) . "\" name=\"vNames[]\" id=\"vNames".$i."\" /></li>\n";
		}
		if(count( $fieldvalues )< 1) {
			echo "\n<li><input type='text' mosReq='0'  mosLabel='" . htmlspecialchars( CBTxt::T('Value') ) . "' value='' name='vNames[]' /></li>\n";
			$i=0;
		}
	?>
				</ul>
			</td>
		</tr>
		</tbody>
	  </table>
	</div>
<?php
/*
		//echo "count:".count( $fieldvalues );
		//print_r (array_values($fieldvalues));
		for ($i=0, $n=count( $fieldvalues ); $i < $n; $i++) {
			//print "count:".$i;
			$fieldvalue = $fieldvalues[$i];
			if ($i==0) $req =1;
			else $req = 0;
			echo "<tr>\n<td width=\"20%\"><input type='text' mosReq='$req'  mosLabel='" . htmlspecialchars( CBTxt::T('Value') ) . "' value=\"" . htmlspecialchars( $fieldvalue->fieldtitle ) . "\" name=\"vNames[".$i."]\" id=\"vNames".$i."\" /></td></tr>\n";
		}
		if(count( $fieldvalues )< 1) {
			echo "<tr>\n<td width=\"20%\"><input type='text' mosReq='0'  mosLabel='" . htmlspecialchars( CBTxt::T('Value') ) . "' value='' name=vNames[0] /></td></tr>\n";
			$i=0;
		}
	?>
		</tbody>
		</table>
	</div>
<?php
*/
		if ( $paramsEditorHtml ) {
?>
    </td>
    <td width="40%" valign="top">
<?php
			foreach ( $paramsEditorHtml as $paramsEditorHtmlBlock ) {
?>
		<table class="adminform" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<th colspan="2">
					<?php echo $paramsEditorHtmlBlock['title']; ?>
				</th>
			</tr>
			<tr>
				<td>
					<?php echo $paramsEditorHtmlBlock['content']; ?>
				</td>
			</tr>
		</table>
<?php
			}
?>
    </td>
   </tr>
  </table>
<?php
		}
?>
  <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

  </table>
  <input type="hidden" name="valueCount" value=<?php echo $i; ?> />
  <input type="hidden" name="oldtabid" value="<?php echo htmlspecialchars( $row->tabid ); ?>" />
  <input type="hidden" name="fieldid" value="<?php echo (int) $row->fieldid; ?>" />
  <input type="hidden" name="ordering" value="<?php echo htmlspecialchars( $row->ordering ); ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="" />
  <?php
	echo cbGetSpoofInputTag( 'field' );
  ?>
</form>
<?php
	}

}	// class CBView_field

?>