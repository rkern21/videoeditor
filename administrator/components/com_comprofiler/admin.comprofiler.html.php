<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: admin.comprofiler.html.php 1453 2011-02-13 21:44:45Z beat $
* @package Community Builder
* @subpackage admin.comprofiler.html.php
* @author JoomlaJoe and Beat
* @copyright (C) JoomlaJoe and Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class HTML_comprofiler {
	static function secureAboveForm( $functionName ) {
		return _CBsecureAboveForm( $functionName );
	}
	static function installPluginForm() {
	}

	static function _saveOrderJs( $task ) {
		global $_CB_framework;

		ob_start();
?>
function cbsaveorder( n ) {
	cbcheckAll_button( n );
	submitform('<?php echo addslashes( $task ); ?>');
}

//needed by cbsaveorder function
function cbcheckAll_button( n ) {
	for ( var j = 0; j <= n; j++ ) {
		box = eval( "document.adminForm.cb" + j );
		if ( box.checked == false ) {
			box.checked = true;
		}
	}
}
<?php
		$js		=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->document->addHeadScriptDeclaration( $js );
	}
	static function showLists( &$rows, $pageNav, $search, $option ) {
		global $_CB_framework;
		HTML_comprofiler::secureAboveForm('showLists');

		outputCbTemplate( 2 );
		outputCbJs( 2 );

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-lists', htmlspecialchars( CBTxt::T('CB List Manager') ) ) );

		HTML_comprofiler::_saveOrderJs( 'savelistorder' );
?>
<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
       <td><?php echo htmlspecialchars( CBTxt::T('Search') ); ?>: <input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="inputbox" onChange="document.adminForm.submit();" />
      </td>
    </tr>
  </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
   <thead>
    <tr>
      <th width="2%" class="title"><?php echo htmlspecialchars( CBTxt::T('#') ); ?></th>
      <th width="3%" class="title"> <input type="checkbox" name="toggle" value="" <?php echo 'onClick="checkAll(' . count($rows) . ');"'; ?> />
      </th>
      <th width="25%" class="title"><?php echo htmlspecialchars( CBTxt::T('Title') ); ?></th>
      <th width="25%" class="title"><?php echo htmlspecialchars( CBTxt::T('Description') ); ?></th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Published') ); ?>?</th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Default') ); ?>?</th>
      <th width="15%" class="title"><?php echo htmlspecialchars( CBTxt::T('Access') ); ?></th>
      <th width="5%" class="title" colspan="2"><?php echo htmlspecialchars( CBTxt::T('Re-Order') ); ?></th>
      <th width="1%"><a href="javascript: cbsaveorder( <?php echo count( $rows )-1; ?> )"><img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-16-filesave.png" border="0" width="16" height="16" alt="<?php echo htmlspecialchars( CBTxt::T('Save Order') ); ?>" /></a></th>
      <th width="2%" class="title"><?php echo htmlspecialchars( CBTxt::T('listid') ); ?></th>
    </tr>
   </thead>
   <tbody>
<?php
		$k = 0;
		$imgpath='../components/com_comprofiler/images/';
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row =& $rows[$i];

		    $img3 = $row->published ?  'tick.png' : 'publish_x.png';
		    $task3 = $row->published ?  'listPublishedNo' : 'listPublishedYes';
		    $img4 = $row->default ?  'tick.png' : 'publish_x.png';
			$task4 = $row->default ?  'listDefaultNo' : 'listDefaultYes';
?>
    <tr class="<?php echo "row$k"; ?>">
      <td><?php echo $i+1+$pageNav->limitstart;?></td>
      <td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->listid; ?>" onClick="isChecked(this.checked);" /></td>
      <td> <a href="#editList" onClick="return listItemTask('cb<?php echo $i;?>','editList')"><?php echo htmlspecialchars( getLangDefinition($row->title) ); ?></a></td>
      <td><?php echo htmlspecialchars( getLangDefinition($row->description) ); ?></td>
      <td width="10%"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task3;?>')"><img src="<?php echo $imgpath.$img3;?>" width="16" height="16" border="0" alt="" /></a></td>
      <td width="10%"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task4;?>')"><img src="<?php echo $imgpath.$img4;?>" width="16" height="16" border="0" alt="" /></a></td>
	  <td><?php
	  		if ( $row->useraccessgroupid >= 0 ) {
		  		echo '<span style="color:red;">' . $_CB_framework->acl->get_group_name( (int) $row->useraccessgroupid ) . '</span>';
	  		} elseif ( $row->useraccessgroupid == -2 ) {
	  			echo '<span style="color:green;">' . CBTxt::T('Everybody') . '</span>';
	  		} elseif ( $row->useraccessgroupid == -1 ) {
	  			echo '<span style="color:orange;">' . CBTxt::T('All Registered Users') . '</span>';
	  		}
	  ?></td>
      <td>
	<?php    if ($i > 0 || ($i+$pageNav->limitstart > 0)) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderupList')">
            <img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-12-uparrow.png" width="12" height="12" border="0" alt="<?php echo htmlspecialchars( CBTxt::T('Move Up') ); ?>" />
         </a>
	<?php    } ?>
      </td>
      <td>
	<?php    if ($i < $n-1 || $i+$pageNav->limitstart < $pageNav->total-1) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderdownList')">
            <img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-12-downarrow.png" width="12" height="12" border="0" alt="<?php echo htmlspecialchars( CBTxt::T('Move Down') ); ?>" />
         </a>
	<?php    } ?>
      </td>
	  <td align="center">
	  <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
	  </td>
      <td style="text-align:right"><?php echo $row->listid;?></td>
    </tr>
    <?php $k = 1 - $k; } ?>
   </tbody>
   <tfoot>
    <tr>
      <th align="center" colspan="12"> <?php echo $pageNav->getListFooter(); ?></th>
    </tr>
   </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="showLists" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php
	echo cbGetSpoofInputTag( 'list' );
  ?>
</form>
<?php
	}


	static function editList( &$row, $lists, $fields, $option, $tabid, $paramsEditorHtml ) {
		global $_CB_database, $_CB_framework;

		HTML_comprofiler::secureAboveForm('editList');
		outputCbTemplate( 2 );
		outputCbJs( 2 );
		initToolTip(2);

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-lists', htmlspecialchars( CBTxt::T('Community Builder List') ) . ": <small>" . ( $row->listid ? htmlspecialchars( CBTxt::T('Edit') ) . ' [ '. htmlspecialchars( getLangDefinition( $row->title ) ) .' ]' : htmlspecialchars( CBTxt::T('New') ) ) . '</small>' ) );

		if ( $row->listid && ( ! $row->published ) ) {
			echo '<div class="cbWarning">' . htmlspecialchars( CBTxt::T('List is not published') ) . '</div>' . "\n";
		}

		$notFoundFielIds		=	array();
		$fieldids				=	array();
		$col1options="";
		$col2options="";
		$col3options="";
		$col4options="";
		if($tabid >0) {
			$col1fields=explode('|*|',$row->col1fields);
			for ($i=0, $n=count( $col1fields ); $i < $n; $i++) {
				$col1field = $col1fields[$i];
				if(trim($col1field)!='' && trim($col1field)!=null) {
					$text			=	array_search($col1field,$fields);
					if ( is_string( $text ) ) {
						$col1options .= "<option value=\"".$col1field."\">". htmlspecialchars( getLangDefinition($text) ) ."\n";
						$fieldids[] = $col1field;
					} else {
						$notFoundFielIds[]	=	$col1field;
					}
				}
			}
			$col2fields=explode('|*|',$row->col2fields);
			for ($i=0, $n=count( $col2fields ); $i < $n; $i++) {
				$col2field = $col2fields[$i];
				if(trim($col2field)!='' && trim($col2field)!=null) {
					$text			=	array_search($col2field,$fields);
					if ( is_string( $text ) ) {
						$col2options .= "<option value=\"".$col2field."\">". htmlspecialchars( getLangDefinition($text) ) ."\n";
						$fieldids[]			=	$col2field;
					} else {
						$notFoundFielIds[]	=	$col2field;
					}
				}
			}
			$col3fields=explode('|*|',$row->col3fields);
			for ($i=0, $n=count( $col3fields ); $i < $n; $i++) {
				$col3field = $col3fields[$i];
				if(trim($col3field)!='' && trim($col3field)!=null) {
					$text			=	array_search($col3field,$fields);
					if ( is_string( $text ) ) {
						$col3options .= "<option value=\"".$col3field."\">". htmlspecialchars( getLangDefinition($text) ) ."\n";
						$fieldids[]			=	$col3field;
					} else {
						$notFoundFielIds[]	=	$col3field;
					}
				}
			}
			$col4fields=explode('|*|',$row->col4fields);
			for ($i=0, $n=count( $col4fields ); $i < $n; $i++) {
				$col4field = $col4fields[$i];
				if(trim($col4field)!='' && trim($col4field)!=null) {
					$text			=	array_search($col4field,$fields);
					if ( is_string( $text ) ) {
						$col4options .= "<option value=\"".$col4field."\">". htmlspecialchars( getLangDefinition($text) ) ."\n";
						$fieldids[]			=	$col4field;
					} else {
						$notFoundFielIds[]	=	$col4field;
					}
				}
			}
		}

		// this query is for listing displayable fields which are not yet in a column:
		$fieldsRemaining		=	array_diff( $fields, $fieldids );
		if ( count( $fieldsRemaining ) > 0 ) {
			$_CB_database->setQuery( "SELECT f.fieldid, f.title, f.name"
				. "\n FROM #__comprofiler_fields f"
				. "\n INNER JOIN #__comprofiler_plugin AS p ON (f.pluginid = p.id)"
				. "\n WHERE f.fieldid IN (" . implode( ',', $fieldsRemaining ) . ')'
				. "\n  AND p.published = 1"
			);
			$fields				=	$_CB_database->loadObjectList();
		} else {
			$fields				=	array();
		}

		$stripME				=	array(" ASC", " DESC","`");
		$sortsArray				=	explode( ', ', $row->sortfields );
		$SQLfunctions			=	array();
		$k						=	-1;
		foreach ( $sortsArray as $k => $v ) {
			$WhereIn			=	trim( str_replace($stripME, "", $v ) );
			if ( substr( $WhereIn, -1, 1 ) == ')') {
				$SQLfunctions[]	=	$WhereIn;
				unset( $sortsArray[$k] );
			} else {
				$sortsArray[$k]	=	$_CB_database->Quote( $WhereIn );
			}
		}
		$sortsArray[$k+1]		=	$_CB_database->Quote( 'onlinestatus' );		//TBD: refactor to field, temporarly in here...
		// this query is for listing sortable fields
		// sortsArray never empty, so this is ok:
		$_CB_database->setQuery( "SELECT f.title, f.name"
			. "\n FROM #__comprofiler_fields f"
			. "\n INNER JOIN #__comprofiler_plugin AS p ON (f.pluginid = p.id)"
			. "\n WHERE ( f.published = 1 OR f.name IN ('name','username') )"
			. "\n  AND f.name <> 'NA'"
			. "\n  AND f.tablecolumns <> ''"
			. "\n  AND p.published = 1"
			. "\n AND f.name NOT IN(" . implode( ',', $sortsArray ) . ")"
		);
/*
		$stripME = array(" ASC", " DESC","`");
		$WhereIn = str_replace($stripME, "", $row->sortfields);
		$WhereIn = "'".str_replace(", ","','",$WhereIn)."'";
		$_CB_database->setQuery( "SELECT f.title, f.name"
			. "\nFROM #__comprofiler_fields f"
			. "\nWHERE f.published = 1 AND f.name!='NA'"
			. "\nAND f.name NOT IN(".$WhereIn.")"
		);
*/
		$sortfields = $_CB_database->loadObjectList();
		if ( ( ! $_CB_database->getErrorNum() ) && ( count( $sortfields ) > 0 ) && ! in_array( 'RAND()', $SQLfunctions ) ) {
			$randomSort		=	new stdClass();
			$randomSort->title	=	CBTxt::T('Sort Randomly');
			$randomSort->name	=	"RAND()";
			$sortfields[]	=	$randomSort;
		}
		// this query is for listing filterable fields
		$_CB_database->setQuery( "SELECT f.title, f.name"
			. "\n FROM #__comprofiler_fields f"
			. "\n INNER JOIN #__comprofiler_plugin AS p ON (f.pluginid = p.id)"
			. "\n WHERE f.published = 1"
			. "\n  AND f.name <> 'NA'"
			. "\n  AND f.tablecolumns <> ''"
			. "\n  AND p.published = 1"
			. "\n   OR f.name IN ('name','username')"
		);
		$filterfields = $_CB_database->loadObjectList();



		$sortlists=explode(", ",str_replace("`","",$row->sortfields));
		$sortparts=array();
		$i=0;
		foreach($sortlists as $sortlist) {
			$sortlistpart=array();
			$sortlistpart=explode(" ",$sortlist);
			if(!ISSET($sortlistpart[1])) $sortlistpart[1]="";
			$sortparts[$i]['field']=$sortlistpart[0];
			$sortparts[$i]['dir']=$sortlistpart[1];
			if ( substr( $sortlistpart[0], -1, 1 ) != ')' ) {
				$_CB_database->setQuery("SELECT title FROM #__comprofiler_fields WHERE name='".$sortlistpart[0]."'",0,1);
				$sortparts[$i]['title']=$_CB_database->loadResult();
			} else {
				switch ( $sortlistpart[0] ) {
					case 'RAND()':
						$sortparts[$i]['title']	=	CBTxt::T('Sort Randomly');
						break;

					default:
						$sortparts[$i]['title']	=	CBTxt::T('Non-existing field') . ": " . $sortlistpart[0];
						break;
				}
			}
			$i++;
		}

if ( count( $notFoundFielIds ) > 0 ) {
	cbArrayToInts( $notFoundFielIds );
	$_CB_database->setQuery( "SELECT f.name, f.title, f.published, f.profile, p.published AS pluginpublished, p.name AS pluginname"
		. "\n FROM #__comprofiler_fields AS f"
		. "\n INNER JOIN #__comprofiler_plugin AS p ON (f.pluginid = p.id)"
		. "\n WHERE f.fieldid IN (" . implode( ',', $notFoundFielIds ) . ")"
	//	. "\n WHERE f.published = 1"
	//	. "\n  AND f.profile > 0"
	//	. "\n  AND p.published = 1"
		. "\n ORDER BY f.ordering"
	);
	$problemFields				=	$_CB_database->loadObjectList();
	if ( ( ! $_CB_database->getErrorNum() ) && ( count( $problemFields ) > 0 ) ) {
		echo '<div class="cbWarning">' . htmlspecialchars( CBTxt::T('Following fields are in list but not visible in here for following reason(s)') ) . ':<ul>';
		foreach ( $problemFields as $f ) {
			if ( $f->published != 1 ) {
				echo '<li>'. htmlspecialchars( sprintf(CBtxt::T('Field "%s (%s)" is not published !'), getLangDefinition( $f->title ), $f->name) ) . '</li>';
			}
			if ( $f->profile <= 0 ) {
				echo '<li>'. htmlspecialchars( sprintf(CBtxt::T('Field "%s (%s)" is not displayed on profile !'), getLangDefinition( $f->title ), $f->name) ) . '</li>';
			}
			if ( $f->pluginpublished != 1 ) {
				echo '<li>'. htmlspecialchars( sprintf(CBtxt::T('Field "%s (%s)" is from plugin "%s" but this plugin is not published !'), getLangDefinition( $f->title ), $f->name, $f->pluginname) ) . '</li>';
			}
		}
		echo '</ul>' . htmlspecialchars( CBTxt::T('If you save this users list now, the fields listed above will be removed from this users list. If you want to keep these fields in this list, cancel now and go to Components / Community Builder / Field Manager.') ) . '</div>' . "\n";
	}
}
	$editorSave_description		=	$_CB_framework->saveCmsEditorJS( 'description' );
	ob_start();
?>
function getObject(obj) {
	return document.getElementById(obj);
}
function shDiv(objID,sh) {
	var strObj;
	strObj = getObject(objID);
	if(sh==0) {
	strObj.style.display="none";
	} else {
	strObj.style.display="block";
	}
}
		function submitbutton(pressbutton) {
			if (pressbutton == 'showLists') {
		        <?php echo $editorSave_description; ?>
				submitform( pressbutton );
				return;
			}
			var coll = document.adminForm;
			var errorMSG = '';
			var iserror=0;
			if (coll.col1enabled.checked == true) coll.col1title.setAttribute('mosReq',1);
			if (coll.col2enabled.checked == true) coll.col2title.setAttribute('mosReq',1);
			if (coll.col3enabled.checked == true) coll.col3title.setAttribute('mosReq',1);
			if (coll.col4enabled.checked == true) coll.col4title.setAttribute('mosReq',1);
			getSortList(document.adminForm.sort);
			getFilterList(document.adminForm.filter);
		     if (coll != null) {
		       var elements = coll.elements;
		       // loop through all input elements in form
		       for (var i=0; i < elements.length; i++) {
		         // check if element is mandatory; here mosReq=1
		         if ((typeof(elements.item(i).getAttribute('mosReq')) != "undefined") && (elements.item(i).getAttribute('mosReq') == 1)) {
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
			if(iserror==1) { alert(errorMSG); }
			else {
				selectAll(document.adminForm.col1);
				selectAll(document.adminForm.col2);
				selectAll(document.adminForm.col3);
				selectAll(document.adminForm.col4);
		        <?php echo $editorSave_description; ?>
				submitform( pressbutton );
			}

		}

    function addOption(selectObj, value)
    {
      optionSelected = (value == null);
      if(value == null) value = prompt('', '');
      if(value != null)
      {
        if(value.indexOf(',') != -1)
          alert('<?php echo addslashes( CBTxt::T('Commas are not allowed in size values') ); ?>');
        else
        {
          var i = selectObj.options.length;
          value = value.replace(/1\/2/g, '�');
          selectObj.options.length = i + 1;
          selectObj.options[i].value = (value != '' && value != ' ') ? value : ' ';
          selectObj.options[i].text = (value != '' && value != ' ') ? value : '[empty]';
          selectObj.options[i].selected = optionSelected;
// uncomment the line below if you want the select list to change it's size to match the number of options it contains.
//          selectObj.size = selectObj.options.length;
        }
      }
    }

    function editOptions(selectObj)
    {
      for(var i = 0; i < selectObj.options.length; i++)
      {
        if(selectObj.options[i].selected)
        {
          var value = prompt('', selectObj.options[i].value);
          if(value != null)
          {
            if(value.indexOf(',') != -1)
              alert('<?php echo addslashes( CBTxt::T('Commas are not allowed in size values') ); ?>');
            else
            {
              selectObj.options[i].value = value;
              selectObj.options[i].text = (value != '') ? value : '[empty]';
              selectObj.options[i].selected = true;
            }
          }
        }
      }
    }

    function deleteOptions(selectObj)
    {
      for(var i = 0; i < selectObj.options.length; i++)
      {
        if(selectObj.options[i].selected)
        {
          for(var j = i; j < selectObj.options.length - 1; j++)
          {
            selectObj.options[j].value = selectObj.options[j + 1].value;
            selectObj.options[j].text = selectObj.options[j + 1].text;
            selectObj.options[j].selected = selectObj.options[j + 1].selected;
          }
          selectObj.options.length = selectObj.options.length - 1;
          i--;
        }
      }
    }

    function moveOptions(selectObj, direction)
    {
      if(selectObj.selectedIndex != -1)
      {
        if(direction < 0)
        {
          for(i = 0; i < selectObj.options.length; i++)
          {
            swapValue = (i == 0 || selectObj.options[i + direction].selected) ? null : selectObj.options[i + direction].value;
            swapText = (i == 0 || selectObj.options[i + direction].selected) ? null : selectObj.options[i + direction].text;
            if(selectObj.options[i].selected && swapValue != null && swapText != null)
            {
              thisValue = selectObj.options[i].value;
              thisText = selectObj.options[i].text;
              selectObj.options[i].value = swapValue;
              selectObj.options[i].text = swapText;
              selectObj.options[i + direction].value = thisValue;
              selectObj.options[i + direction].text = thisText;
              selectObj.options[i].selected = false;
              selectObj.options[i + direction].selected = true;
            }
          }
        }
        else
        {
          for(i = selectObj.options.length - 1; i >= 0; i--)
          {
            swapValue = (i == selectObj.options.length - 1 || selectObj.options[i + direction].selected) ? null : selectObj.options[i + direction].value;
            swapText = (i == selectObj.options.length - 1 || selectObj.options[i + direction].selected) ? null : selectObj.options[i + direction].text;
            if(selectObj.options[i].selected && swapValue != null && swapText != null)
            {
              thisValue = selectObj.options[i].value;
              thisText = selectObj.options[i].text;
              selectObj.options[i].value = swapValue;
              selectObj.options[i].text = swapText;
              selectObj.options[i + direction].value = thisValue;
              selectObj.options[i + direction].text = thisText;
              selectObj.options[i].selected = false;
              selectObj.options[i + direction].selected = true;
            }
          }
        }
      }
    }
    var NS4 = (document.layers);

    function moveOption(fromObj, toObj)
    {
      for(var i = fromObj.options.length - 1; i >= 0; i--)
      {
        if(fromObj.options[i].selected)
        {
          fromObj.options[i].selected = false;
          var optionText = fromObj.options[i].text.replace(' [ASC]','');
	      optionText = optionText.replace(' [DESC]','');
          var optionValue = fromObj.options[i].value.replace(' ASC','');
	      optionValue = optionValue.replace(' DESC','');
          for(var j = i; j < fromObj.options.length - 1; j++)
          {
            fromObj.options[j].text = fromObj.options[j + 1].text;
            fromObj.options[j].value = fromObj.options[j + 1].value;
          }
          fromObj.options.length = fromObj.options.length - 1;
          toObjIndex = toObj.options.length;
          toObj.options.length = toObj.options.length + 1;
          toObj.options[toObjIndex].text = optionText;
          toObj.options[toObjIndex].value = optionValue;
          if(NS4)
            history.go(0);
        }
      }
    }

    function moveOption2(fromObj, toObj, appendValue)
    {
        if(fromObj.options[fromObj.selectedIndex].selected)
        {
	  fromObjIndex=fromObj.selectedIndex;
          fromObj.options[fromObjIndex].selected = false;
          optionText = fromObj.options[fromObjIndex].text+ ' ['+appendValue+']';
          optionValue = fromObj.options[fromObjIndex].value+' '+appendValue;
          for(var j = fromObjIndex; j < fromObj.options.length - 1; j++)
          {
            fromObj.options[j].text = fromObj.options[j + 1].text;
            fromObj.options[j].value = fromObj.options[j + 1].value;
          }
          fromObj.options.length = fromObj.options.length - 1;
          toObjIndex = toObj.options.length;
          toObj.options.length = toObj.options.length + 1;
          toObj.options[toObjIndex].text = optionText;
          toObj.options[toObjIndex].value = optionValue;
	  toObj.options[toObjIndex].selected=false;
          if(NS4)
            history.go(0);
        }

    }

    function moveOption3(fromObj, toObj, comparison, condition)
    {
        if(fromObj.options[fromObj.selectedIndex].selected)
        {
	  if((condition=='' || condition==null) && document.adminForm.condition.getAttribute('Req')==1) {
		alert('<?php echo addslashes( CBTxt::T('You must define a condition text!') ); ?>');
		return;
	  }
	  fromObjIndex=fromObj.selectedIndex;
          fromObj.options[fromObjIndex].selected = false;
          optionText = fromObj.options[fromObjIndex].text+ ' '+comparison+' '+condition;
	  condition=condition.replace("'", "\\'");
	  if(condition!='' && condition!=null) condition="'"+escape(condition)+"'";
          optionValue = fromObj.options[fromObjIndex].value+' '+comparison+condition;
          toObjIndex = toObj.options.length;
          toObj.options.length = toObj.options.length + 1;
          toObj.options[toObjIndex].text = optionText;
          toObj.options[toObjIndex].value = optionValue;
	  toObj.options[toObjIndex].selected=false;
          if(NS4)
            history.go(0);
        }

    }
    function moveOption4(fromObj, toObj)
    {
      for(var i = fromObj.options.length - 1; i >= 0; i--)
      {
        if(fromObj.options[i].selected)
        {
          fromObj.options[i].selected = false;
          for(var j = i; j < fromObj.options.length - 1; j++)
          {
            fromObj.options[j].text = fromObj.options[j + 1].text;
            fromObj.options[j].value = fromObj.options[j + 1].value;
          }
          fromObj.options.length = fromObj.options.length - 1;
          if(NS4)
            history.go(0);
        }
      }
    }


    function getSortList(selectObj) {
    	var sortfields='';
    	var j=0;
    	selectAll(selectObj);
    	if(selectObj.selectedIndex != -1)
    	{
    		for(i = 0; i < selectObj.options.length; i++)
    		{
    			if(j>0) sortfields +=  ', ';
    			sortfields +=  selectObj.options[i].value;
    			j++;
    		}
    		//alert(sortfields);
    		document.adminForm.sortfields.value=sortfields;
    	}
    }

	function getFilterList(selectObj) {
		var filterfields='';
		var j=0;
		var advType=getObject('ft2');
		var simType=getObject('ft1');
		//alert(simType.checked);
		if(simType.checked) {
			selectAll(selectObj);
			if(selectObj.selectedIndex != -1) {
				for(i = 0; i < selectObj.options.length; i++) {
					if(j>0) filterfields +=  ' AND ';
					filterfields +=  selectObj.options[i].value;
					j++;
				}
			}
			if(filterfields!="") {
				document.adminForm.filterfields.value="s("+filterfields+")";
			} else {
				document.adminForm.filterfields.value="";
			}
		} else {
			if(document.adminForm.advFilterText.value!="") {
				document.adminForm.filterfields.value="a("+escape(document.adminForm.advFilterText.value)+")";
			} else {
				document.adminForm.filterfields.value="";
			}
		}
	}

    function selectAll(selectObj)
    {
      if(selectObj.options.length)
        for(i = 0; i < selectObj.options.length; i++)
          selectObj.options[i].selected = true;
      return false;
    }

    function loadUGIDs(selectObj)
    {
	var UGIDs='';
	var j=0;
      if(selectObj.selectedIndex != -1)
      {
          for(i = 0; i < selectObj.options.length; i++)
          {
		if(selectObj.options[i].selected) {
			if(j>0) UGIDs +=  ', ';
			UGIDs +=  selectObj.options[i].value;
			j++;
		}
          }
		document.adminForm.usergroupids.value=UGIDs;
        }
    }
    function enableListColumn(colnum) {
	var oForm;
	var colName;
	oForm=document.adminForm;
	colName="col"+colnum+"enabled";
	if(oForm.elements[colName].checked) {
		//alert("Enabled");
		oForm.col1title.readOnly=false;
		oForm.col1captions.disabled=false;
		//document.col1.disabled=false;
		oForm.col1up.disabled=false;
		oForm.col1down.disabled=false;
		oForm.col1remove.disabled=false;
		oForm.addcol1.disabled=false;
	} else {
		//alert("Disabled");
		oForm.col1title.readOnly=true;
		oForm.col1captions.disabled=true;
		//document.col1.disabled=true;
		oForm.col1up.disabled=true;
		oForm.col1down.disabled=true;
		oForm.col1remove.disabled=true;
		oForm.addcol1.disabled=true;
	}

    }
	function filterCondition(needCond) {
		if(needCond==0) {
			document.adminForm.condition.value="";
			document.adminForm.condition.readOnly=true;
			document.adminForm.condition.setAttribute("Req",0);
		} else {
			document.adminForm.condition.value="";
			document.adminForm.condition.readOnly=false;
			document.adminForm.condition.setAttribute("Req",1);
		}

	}

<?php
		$jsListsJs		=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->document->addHeadScriptDeclaration( $jsListsJs );
?>
	<form action="<?php echo $_CB_framework->backendUrl( 'index.php?option=com_comprofiler&task=saveList' ); ?>" method="POST" name="adminForm">
	<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
		<tr>
			<td width="20%"><?php echo htmlspecialchars( CBTxt::T('URL for menu link to this list') ); ?>:</td>
			<td align=left  width="40%"><?php
		if ( $row->listid ) {
			$url	=	'index.php?option=com_comprofiler&amp;task=usersList&amp;listid=' . (int) $row->listid;
			echo '<a href="' . $_CB_framework->getCfg('live_site') . '/' . $url . '" target="_blank">' . $url . '</a>';
		} else {
			echo htmlspecialchars( CBTxt::T('You need to save this new list first to see the direct menu link url.') );
		}
			?></td>
			<td width="40%">&nbsp;</td>
		</tr>
		<tr>
			<td width="20%"><?php echo htmlspecialchars( CBTxt::T('URL for search link to this list') ); ?>:</td>
			<td align=left  width="40%"><?php
		if ( $row->listid ) {
			$url	=	'index.php?option=com_comprofiler&amp;task=usersList&amp;listid=' . (int) $row->listid . '&amp;searchmode=1';
			echo '<a href="' . $_CB_framework->getCfg('live_site') . '/' . $url . '" target="_blank">' . $url . '</a>';
		} else {
			echo htmlspecialchars( CBTxt::T('You need to save this new list first to see the direct menu link url.') );
		}
			?></td>
			<td width="40%"><?php echo htmlspecialchars( CBTxt::T('Only fields appearing in list columns and on profiles and which are have the searchable attribute ON will appear in search criterias of the list.') ); ?></td>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars( CBTxt::T('Title') ); ?>:</td>
			<td align=left><input type="text" name="title" mosReq="1" mosLabel="<?php echo htmlspecialchars( CBTxt::T('Title') ); ?>" class="inputbox" value="<?php echo htmlspecialchars($row->title); ?>" /></td>
			<td><?php echo htmlspecialchars( CBTxt::T('Title appears in frontend on top of the list.') ); ?></td>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars( CBTxt::T('Description') ); ?>:</td>
			<td align=left><?php echo $_CB_framework->displayCmsEditor( 'description', $row->description, 600, 200, 50, 7 );
				// <textarea name="description" cols="50" rows="7">< ?php echo htmlspecialchars($row->description); ? ></textarea>
			?></td>
			<td><?php echo htmlspecialchars( CBTxt::T('Description appears in frontend under the title of the list.') ); ?></td>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars( CBTxt::T('User Group to allow access to') ); ?>:</td>
			<td><?php echo $lists['useraccessgroup']; ?></td>
			<td><?php echo htmlspecialchars( CBTxt::T('All groups above that level will also have access to the list.') ); ?></td>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars( CBTxt::T('User Groups to Include in List') ); ?>:</td>
			<td><?php echo $lists['usergroups']; ?></td>
			<td><strong><font color="red"><?php echo htmlspecialchars( CBTxt::T('Multiple choices') ); ?>:</font> <?php echo htmlspecialchars( CBTxt::T('CTRL/CMD-click to add/remove single choices.') ); ?></strong></td>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars( CBTxt::T('Published') ); ?>:</td>
			<td><?php echo $lists['published']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars( CBTxt::T('Default') ); ?>:</td>
			<td><?php echo $lists['default']; ?></td>
			<td><strong><font color="red"><?php echo htmlspecialchars( CBTxt::T('WARNING') ); ?>:</font></strong> <?php echo htmlspecialchars( CBTxt::T('The default list should be the one with the lowest user groups access rights !') ); ?></td>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars( CBTxt::T('Sort By') ); ?>:</td>
			<td>
				<select name="sortfieldlist">
					<?php
						for ($i=0, $n=count( $sortfields ); $i < $n; $i++) {
							$sortfield =& $sortfields[$i];
							if ( substr( $sortfield->name, -1, 1 ) != ')' ) {
								$sortfieldName	=	'`' . $sortfield->name . '`';
							} else {
								$sortfieldName	=	$sortfield->name;
							}
							echo "<option value=\"" . $sortfieldName . "\">". htmlspecialchars( getLangDefinition($sortfield->title) ) ."</option>\n";
						}
					?>
				</select><select name=direction><option value="ASC"><?php echo htmlspecialchars( CBTxt::T('ASC') ); ?></option><option value="DESC"><?php echo htmlspecialchars( CBTxt::T('DESC') ); ?></option></select><input type=button onclick="moveOption2(this.form.sortfieldlist, sort, this.form.direction.value);" value=" <?php echo htmlspecialchars( CBTxt::T('Add') ); ?> "><br />
				<select id=sort name=sort size="5" multiple  mosReq="1" mosLabel="<?php echo htmlspecialchars( CBTxt::T('Sort By') ); ?>">
					<?php
						for ($i=0, $n=count( $sortparts ); $i < $n; $i++) {
							$sortpart = $sortparts[$i];
							if( $sortpart['field'] != '' ) {
								if ( substr( $sortpart['field'], -1, 1 ) != ')' ) {
									$sortfiNam		=	'`' . $sortpart['field'] . '`';
								} else {
									$sortfiNam		=	$sortpart['field'];
								}
								echo '<option value="' . $sortfiNam . ' ' . $sortpart['dir'] . '">' . htmlspecialchars( getLangDefinition($sortpart['title']) ) . ' [' . $sortpart['dir'] . "]</option>\n";
							}
						}

					?>
				</select><br />
				<input type=button onclick="moveOptions(sort, -1);" value=" <?php echo htmlspecialchars( CBTxt::T('+') ); ?> " />
				<input type=button onclick="moveOptions(sort, 1);" value=" <?php echo htmlspecialchars( CBTxt::T('-') ); ?> " />
				<br />
				<input type=button onclick="moveOption(this.form.sort,this.form.sortfieldlist);" value=" <?php echo htmlspecialchars( CBTxt::T('Remove') ); ?> ">
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo htmlspecialchars( CBTxt::T('Filter') ); ?>:</td>
			<td colspan="2">
<?php

		$simChecked="";
		$advChecked="";
		$simStyle="display:none;";
		$advStyle="display:none;";
		//echo $row->filterfields;
		$filttype=substr($row->filterfields,0,1);
		$row->filterfields=substr($row->filterfields,2,-1);
		//substr($row->filterfields,1,-1)
		// echo "row->filterfields=".$row->filterfields;
		if($filttype=="a") {
			$advChecked="CHECKED";
			$advStyle="display:block;";
		} else {
			$simChecked="checked=\"checked\"";
			$simStyle="display:block;";
		}
		$filterlists=explode(" AND ",$row->filterfields);
		$filterparts=array();
		$i=0;
		foreach($filterlists as $filterlist) {

			$filterlistpart=array();
			$filterlistpart=explode(" ",$filterlist);
			$filterparts[$i]['field']=str_replace("`","",$filterlistpart[0]);
			$_CB_database->setQuery("SELECT title FROM #__comprofiler_fields WHERE name='".$filterparts[$i]['field']."'", 0, 1);
			$filtertitle=$_CB_database->loadResult();
			$filterparts[$i]['value']=$filterlist;
			$filterparts[$i]['title']=str_replace(array("'","`"),"",str_replace($filterparts[$i]['field'],getLangDefinition($filtertitle),$filterlist));

			$i++;
		}
?>
				<label for=ft1 ><input type="radio" <?php echo $simChecked; ?> id="ft1" onclick="javascript:shDiv('simFilter',1);shDiv('advFilter',0);" name=filtertype value="0" checked="checked" /><?php echo htmlspecialchars( CBTxt::T('Simple') ); ?> </label><label for=ft2 ><input type="radio" <?php echo $advChecked; ?> onclick="javascript:shDiv('simFilter',0);shDiv('advFilter',1);" id="ft2" name="filtertype" value="1" /><?php echo htmlspecialchars( CBTxt::T('Advanced') ); ?> </label>
				<br />
				<div id="simFilter" name="simFilter" style="<?php echo $simStyle; ?>" >
				<select name="filterfieldlist">
					<?php
						foreach ($filterfields AS $filterfield) {
							echo "<option value=\"`".$filterfield->name."`\">". htmlspecialchars( getLangDefinition($filterfield->title) ) ."\n";
						}

					?>
				</select>
				<select name=comparison onchange="javascript:filterCondition(this.options[this.selectedIndex].getAttribute('needCond'));">
					<option value=">" needCond="1"><?php echo htmlspecialchars( CBTxt::T('Greater Than') ); ?></option>
					<option value=">=" needCond="1"><?php echo htmlspecialchars( CBTxt::T('Greater Than or Equal To') ); ?></option>
					<option value="&lt;" needCond="1"><?php echo htmlspecialchars( CBTxt::T('Less Than') ); ?></option>
					<option value="&lt;=" needCond="1"><?php echo htmlspecialchars( CBTxt::T('Less Than or Equal To') ); ?></option>
					<option value="=" needCond="1"><?php echo htmlspecialchars( CBTxt::T('Equal To') ); ?></option>
					<option value="!=" needCond="1"><?php echo htmlspecialchars( CBTxt::T('Not Equal To') ); ?></option>
					<option value="= ''" needCond="0"><?php echo htmlspecialchars( CBTxt::T('Is Empty') ); ?></option>
					<option value="!= ''" needCond="0"><?php echo htmlspecialchars( CBTxt::T('Is Not Empty') ); ?></option>
					<option value="IS NULL" needCond="0"><?php echo htmlspecialchars( CBTxt::T('Is NULL') ); ?></option>
					<option value="IS NOT NULL"  needCond="0"><?php echo htmlspecialchars( CBTxt::T('Is Not NULL') ); ?></option>
					<option value="LIKE"  needCond="1"><?php echo htmlspecialchars( CBTxt::T('Like') ); ?></option>
				</select>
				<input type=text name=condition value="" Req=1 />
				<input type=button onclick="moveOption3(this.form.filterfieldlist, filter, this.form.comparison.value, this.form.condition.value);" value=" <?php echo htmlspecialchars( CBTxt::T('Add') ); ?> ">
				<br />
				<select id=filter name=filter size="5" multiple  mosReq=0 mosLabel="<?php echo htmlspecialchars( CBTxt::T('Filter By') ); ?>">
					<?php
						foreach ($filterparts AS $filterpart) {
							if($filterpart['value']!='') {
								echo "<option value=\"".$filterpart['value']."\">".stripslashes(utf8RawUrlDecode($filterpart['title']))."\n";	//BB todo sortout htmlspecialchars...not compatible with utf8rawdecode
							}
						}

					?>
				</select><br />
				<input type=button onclick="moveOptions(filter, -1);" value=" <?php echo htmlspecialchars( CBTxt::T('+') ); ?> " />
				<input type=button onclick="moveOptions(filter, 1);" value=" <?php echo htmlspecialchars( CBTxt::T('-') ); ?> " />
				<br />
				<input type=button onclick="moveOption4(this.form.filter,this.form.filterfieldlist);" value=" <?php echo htmlspecialchars( CBTxt::T('Remove') ); ?> ">
				</div>
				<div id="advFilter" name="advFilter" style="<?php echo $advStyle; ?>">
					<textarea name="advFilterText" cols="50" rows="7"><?php echo stripslashes(utf8RawUrlDecode($row->filterfields)); 	//BB todo sortout htmlspecialchars...not compatible with utf8rawdecode
					?></textarea>
				</div>
			</td>
		</tr>
	</table>
	<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
		<tr>
			<td width="100%" colspan="3" style="text-align:center;">
				<?php echo CBTxt::T('<strong>Note:</strong> fields must be on profile to appear in this list and be visible on the users-list.'); ?>
			</td>
		</tr>
		<tr>
			<td width="33%">
				<?php echo htmlspecialchars( CBTxt::T('Enable Column 1') ); ?>: <input type=checkbox <?php /* onclick="javascript:enableListColumn(1);" */ ?> name="col1enabled" <?php if($row->col1enabled == 1) echo ' checked="checked" ';  ?> value=1 ><br />
				<?php echo htmlspecialchars( CBTxt::T('Column 1 Title') ); ?>:<br />
				<input type="text" name="col1title" mosReq=0 mosLabel="<?php echo htmlspecialchars( CBTxt::T('Column 1 Title') ); ?>" class="inputbox" value="<?php echo htmlspecialchars($row->col1title); ?>" /><br />
				<?php echo htmlspecialchars( CBTxt::T('Column 1 Captions') ); ?>:<input type=checkbox name=col1captions <?php if($row->col1captions == 1) echo " CHECKED ";  ?> value=1 ><br />
				<select id=col1 size="5" multiple name=col1[] >
					<?php
					echo $col1options;
					?>
				</select><br />
				<input name=col1up type=button onclick="moveOptions(col1, -1);" value=" <?php echo htmlspecialchars( CBTxt::T('+') ); ?> " />
				<input name=col1down type=button onclick="moveOptions(col1, 1);" value=" <?php echo htmlspecialchars( CBTxt::T('-') ); ?> " />
				<br />
				<input name=col1remove type=button onclick="moveOption(col1,this.form.fieldlist);" value=" <?php echo htmlspecialchars( CBTxt::T('Remove') ); ?> ">
			</td>
			<td width="33%" rowspan=3 valign=center align=center><?php echo htmlspecialchars( CBTxt::T('Field List') ); ?>:<br />
				<input name=addcol1 type=button onclick="moveOption(this.form.fieldlist, col1);" value=" <?php echo htmlspecialchars( CBTxt::T('<- Add') ); ?> ">
				<input type=button onclick="moveOption(this.form.fieldlist, col2);" value=" <?php echo htmlspecialchars( CBTxt::T('Add ->') ); ?> "><br />
				<select name="fieldlist" size="10" multiple>
					<?php
						foreach ( $fields as $field ) {
							echo "<option value=\"".$field->fieldid."\">".htmlspecialchars( getLangDefinition($field->title) )."\n";
						}

					?>
				</select><br />
				<input type=button onclick="moveOption(this.form.fieldlist, col3);" value=" <?php echo htmlspecialchars( CBTxt::T('<- Add') ); ?> ">
				<input type=button onclick="moveOption(this.form.fieldlist, col4);" value=" <?php echo htmlspecialchars( CBTxt::T('Add ->') ); ?> ">
			</td>
			<td width="33%">
				<?php echo htmlspecialchars( CBTxt::T('Enable Column 2') ); ?>: <input type=checkbox name=col2enabled <?php if($row->col2enabled == 1) echo " CHECKED ";  ?> value=1 ><br />
				<?php echo htmlspecialchars( CBTxt::T('Column 2 Title') ); ?>:<br />
				<input type="text" name="col2title" mosReq=0 mosLabel="<?php echo htmlspecialchars( CBTxt::T('Column 2 Title') ); ?>" class="inputbox" value="<?php echo htmlspecialchars($row->col2title); ?>" /><br />
				<?php echo htmlspecialchars( CBTxt::T('Column 2 Captions') ); ?>:<input type=checkbox name=col2captions <?php if($row->col2captions == 1) echo " CHECKED ";  ?> value=1 ><br />
				<select id=col2 size="5" multiple name=col2[] >
					<?php
					echo $col2options;
					?>
				</select><br />
				<input type=button onclick="moveOptions(col2, -1);" value=" <?php echo htmlspecialchars( CBTxt::T('+') ); ?> " />
				<input type=button onclick="moveOptions(col2, 1);" value=" <?php echo htmlspecialchars( CBTxt::T('-') ); ?> " />
				<br />
				<input type=button onclick="moveOption(col2,this.form.fieldlist);" value=" <?php echo htmlspecialchars( CBTxt::T('Remove') ); ?> ">
			</td>
		</tr>
		<tr>
		</tr>
		<tr>
			<td width="33%">
				<?php echo htmlspecialchars( CBTxt::T('Enable Column 3') ); ?>: <input type=checkbox name=col3enabled <?php if($row->col3enabled == 1) echo " CHECKED ";  ?> value=1 /><br />
				<?php echo htmlspecialchars( CBTxt::T('Column 3 Title') ); ?>:<br />
				<input type="text" name="col3title" mosReq=0 mosLabel="<?php echo htmlspecialchars( CBTxt::T('Column 3 Title') ); ?>" class="inputbox" value="<?php echo htmlspecialchars($row->col3title); ?>" /><br />
				<?php echo htmlspecialchars( CBTxt::T('Column 3 Captions') ); ?>:<input type=checkbox name=col3captions <?php if($row->col3captions == 1) echo " CHECKED ";  ?> value=1 ><br />
				<select id=col3 size="5" multiple name=col3[]>
					<?php
					echo $col3options;
					?>
				</select><br />
				<input type=button onclick="moveOptions(col3, -1);" value=" <?php echo htmlspecialchars( CBTxt::T('+') ); ?> " />
				<input type=button onclick="moveOptions(col3, 1);" value=" <?php echo htmlspecialchars( CBTxt::T('-') ); ?> " />
				<br />
				<input type=button onclick="moveOption(col3,this.form.fieldlist);" value=" <?php echo htmlspecialchars( CBTxt::T('Remove') ); ?> ">
			</td>
			<td width="33%">
				<?php echo htmlspecialchars( CBTxt::T('Enable Column 4') ); ?>: <input type=checkbox name=col4enabled <?php if($row->col4enabled == 1) echo " CHECKED ";  ?> value=1 ><br />
				<?php echo htmlspecialchars( CBTxt::T('Column 4 Title') ); ?>:<br />
				<input type="text" name="col4title" mosReq=0 mosLabel="<?php echo htmlspecialchars( CBTxt::T('Column 4 Title') ); ?>" class="inputbox" value="<?php echo htmlspecialchars($row->col4title); ?>" /><br />
				<?php echo htmlspecialchars( CBTxt::T('Column 4 Captions') ); ?>:<input type=checkbox name=col4captions <?php if($row->col4captions == 1) echo " CHECKED ";  ?> value=1 ><br />
				<select id=col4 size="5" multiple name=col4[]>
					<?php
					echo $col4options;
					?>
				</select><br />
				<input type=button onclick="moveOptions(col4, -1);" value=" <?php echo htmlspecialchars( CBTxt::T('+') ); ?> " />
				<input type=button onclick="moveOptions(col4, 1);" value=" <?php echo htmlspecialchars( CBTxt::T('-') ); ?> " />
				<br />
				<input type=button onclick="moveOption(col4,this.form.fieldlist);" value=" <?php echo htmlspecialchars( CBTxt::T('Remove') ); ?> ">
			</td>
		</tr>
	</table>
<?php
	// params:
	if ( $paramsEditorHtml ) {
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
	}
?>

  <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

  </table>
  <input type="hidden" name="sortfields" value="<?php echo $row->sortfields; ?>" />
  <input type="hidden" name="filterfields" value="<?php echo $row->filterfields; ?>" />
  <input type="hidden" name="usergroupids" value="<?php echo $row->usergroupids; ?>" />
  <input type="hidden" name="listid" value="<?php echo $row->listid; ?>" />
  <input type="hidden" name="ordering" value="<?php echo $row->ordering; ?>" />
  <input type="hidden" name="option" value="com_comprofiler" />
  <input type="hidden" name="task" value="" />
  <?php
	echo cbGetSpoofInputTag( 'list' );
  ?>
</form>

<?php
	}


	static function showFields( &$rows, $pageNav, $search, $option ) {
		global $_CB_framework, $ueConfig;

		HTML_comprofiler::secureAboveForm('showFields');

		outputCbTemplate( 2 );
		outputCbJs( 2 );

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-fields', htmlspecialchars( CBTxt::T('CB Field Manager') ) ) );

		HTML_comprofiler::_saveOrderJs( 'savefieldorder' );
?>
<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td><?php echo htmlspecialchars( CBTxt::T('Search') ); ?>: <input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="inputbox" onChange="document.adminForm.submit();" />
      </td>
    </tr>
  </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
   <thead>
    <tr>
      <th width="2%" class="title"><?php echo htmlspecialchars( CBTxt::T('#') ); ?></th>
      <th width="3%" class="title"> <input type="checkbox" name="toggle" value="" <?php echo 'onClick="checkAll(' . count($rows) . ');"'; ?> />
      </th>
      <th width="10%" class="title"><?php echo htmlspecialchars( CBTxt::T('Name') ); ?></th>
      <th width="10%" class="title"><?php echo htmlspecialchars( CBTxt::T('Title') ); ?></th>
      <th width="10%" class="title"><?php echo htmlspecialchars( CBTxt::T('Type') ); ?></th>
      <th width="10%" class="title"><?php echo htmlspecialchars( CBTxt::T('Tab') ); ?></th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Required') ); ?>?</th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Profile') ); ?>?</th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Registration') ); ?>?</th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Searchable') ); ?>?</th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Published') ); ?>?</th>
      <th width="5%" class="title" colspan="2"><?php echo htmlspecialchars( CBTxt::T('Re-Order') ); ?></th>
	  <th width="1%"><a href="javascript: cbsaveorder( <?php echo count( $rows )-1; ?> )"><img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-16-filesave.png" border="0" width="16" height="16" alt="<?php echo htmlspecialchars( CBTxt::T('Save Order') ); ?>" /></a></th>
    </tr>
   </thead>
   <tbody>
<?php
		$k = 0;
		$imgpath='../components/com_comprofiler/images/';
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row =& $rows[$i];
			$img = $row->required ? 'tick.png' : 'publish_x.png' ;
			$task = $row->required ? 'fieldRequiredNo' : 'fieldRequiredYes' ;
			switch ($row->profile) {
				case 0:
					$img2	= 'publish_x.png';
					$task2	= 'fieldProfileYes1';
					$text2	= '';
					break;
				case 1:
					$img2	= 'tick.png';
					$task2	= 'fieldProfileYes2';
					$text2	= '<span style="color:green;">' . htmlspecialchars( CBTxt::T('(1 Line)') ) . '</span>';
					break;
				case 2:
				default:
					$img2	= 'tick.png';
					$task2	= 'fieldProfileNo';
					$text2	= '<span style="color:green;">' . htmlspecialchars( CBTxt::T('(2 Lines)') ) . '</span>';
					break;
			}
			$img3  = $row->published ?  'tick.png' : 'publish_x.png';
			$task3 = $row->published ?  'fieldPublishedNo' : 'fieldPublishedYes';
			$img4  = $row->registration ?  'tick.png' : 'publish_x.png';
			$task4 = $row->registration ?  'fieldRegistrationNo' : 'fieldRegistrationYes';
			$img5  = $row->searchable ?  'tick.png' : 'publish_x.png';
			$task5 = $row->searchable ?  'fieldSearchableNo' : 'fieldSearchableYes';
?>
    <tr class="<?php echo "row$k"; ?>">
      <td><?php echo $i+1+$pageNav->limitstart;?></td>
      <td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->fieldid; ?>" onClick="isChecked(this.checked);" /></td>
      <td> <a href="#editField" onClick="return listItemTask('cb<?php echo $i;?>','editField')">
        <?php echo htmlspecialchars( $row->name ); ?> </a> </td>
      <td><?php echo htmlspecialchars( getLangDefinition( $row->title ) ); ?></td>
      <td><?php
      		if ( $row->pluginid && ( $row->fieldpluginpublished == 0 ) ) {
      			echo '<span style="color:red;" title="' . htmlspecialchars( sprintf( CBTxt::T('field will not be visible as field plugin "%s" is not published.'), htmlspecialchars( $row->fieldpluginname ) ) ) . '">';
      		}
      		if ( $row->type == 'connections' && $ueConfig['allowConnections'] == 0 ) {
      			echo '<span style="color:red;" title="' . htmlspecialchars( CBTxt::T('field will not be visible as connections are not enabled in CB configuration.') ). '">';
      		}
			echo htmlspecialchars( $row->type );
     		if ( ( $row->pluginid && ( $row->fieldpluginpublished == 0 ) ) || ( $row->type == 'connections' && $ueConfig['allowConnections'] == 0 ) ) {
     			echo '</span>';
     		}
	?></td>
      <td><?php
      		if ( $row->tabenabled == 0 ) {
      			echo '<span style="color:red;" title="' . htmlspecialchars( CBTxt::T('field will not be visible as tab is not enabled.') ) .'">';
      		} elseif ( $row->tabpluginid && ( $row->pluginpublished == 0 ) ) {
      			echo '<span style="color:red;" title="' . htmlspecialchars( sprintf(CBTxt::T('field will not be visible as tab\'s plugin "%s" is not published.'), htmlspecialchars( $row->pluginname ) ) ) . '">';
      		}
			echo htmlspecialchars( getLangDefinition( $row->tab ) );
     		if ( $row->tabenabled == 0 || ( $row->tabpluginid && ( $row->pluginpublished == 0 ) ) ) {
     			echo '</span>';
     		}
	  ?></td>
      <td width="10%"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="<?php echo $imgpath.$img;?>" width="16" height="16" border="0" alt="" /></a></td>
      <td width="10%"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task2;?>')"><img src="<?php echo $imgpath.$img2;?>" width="16" height="16" border="0" alt="" /><?php echo $text2;?></a></td>
      <td width="10%"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task4;?>')"><img src="<?php echo $imgpath.$img4;?>" width="16" height="16" border="0" alt="" /></a></td>
      <td width="10%"><?php
      if ( $row->tablecolumns != '' && ! in_array( $row->type, array( 'password', 'userparams' ) ) ) {
		?><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task5;?>')"><img src="<?php echo $imgpath.$img5;?>" width="16" height="16" border="0" alt="" /></a><?php
      } else {
      	 echo '<img src="' . $imgpath . $img5 . '" width="16" height="16" border="0" alt="" />';
      }
		?></td>
<?php		if ( $row->sys == 1 ) {
?>
      <td width="10%"><img src="<?php echo $imgpath.$img3;?>" width="16" height="16" border="0" alt="" title="<?php echo htmlspecialchars( CBTxt::T('System-fields cannot be published/unpublished here.') ); if ( in_array( $row->name, array( 'name', 'firstname', 'middlename', 'lastname' ) ) ) echo ' ' . htmlspecialchars( CBTxt::T('Name-fields publishing depends on your setting in global CB config.') ); ?>" /></td>
<?php
			} else {
?>
      <td width="10%"><a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task3;?>')"><img src="<?php echo $imgpath.$img3;?>" width="16" height="16" border="0" alt="" /></a></td>
<?php
			}
?>
      <td>
	<?php    if (($i > 0 || ($i+$pageNav->limitstart > 0)) && $row->tab == @$rows[$i-1]->tab) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderupField')">
            <img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-12-uparrow.png" width="12" height="12" border="0" alt="<?php echo htmlspecialchars( CBTxt::T('Move Up') ); ?>" />
         </a>
	<?php    } ?>
      </td>
      <td>
	<?php    if (($i < $n-1 || $i+$pageNav->limitstart < $pageNav->total-1) && $row->tab == @$rows[$i+1]->tab) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderdownField')">
            <img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-12-downarrow.png" width="12" height="12" border="0" alt="<?php echo htmlspecialchars( CBTxt::T('Move Down') ); ?>" />
         </a>
	<?php    } ?>
      </td>
	  <td align="center">
	  <input type="text" name="order[]" size="5" value="<?php echo htmlspecialchars( $row->ordering ); ?>" class="text_area" style="text-align: center" />
	  </td>
    </tr>
    <?php $k = 1 - $k; } ?>
   </tbody>
   <tfoot>
    <tr>
      <th align="center" colspan="14"> <?php echo $pageNav->getListFooter(); ?></th>
    </tr>
   </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="showField" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php
	echo cbGetSpoofInputTag( 'field' );
  ?>
</form>
<?php
	}


	static function showTabs( &$rows, $pageNav, $search, $option ) {
		global $_CB_framework;
		HTML_comprofiler::secureAboveForm('showTabs');

		outputCbTemplate( 2 );
		outputCbJs( 2 );

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-tabs', htmlspecialchars( CBTxt::T('CB Tab Manager') ) ) );

		HTML_comprofiler::_saveOrderJs( 'savetaborder' );
?>
<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td><?php echo htmlspecialchars( CBTxt::T('Search') ); ?>: <input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="inputbox" onChange="document.adminForm.submit();" />
      </td>
    </tr>
  </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
   <thead>
    <tr>
      <th width="1%" class="title"><?php echo htmlspecialchars( CBTxt::T('#') ); ?></th>
      <th width="1%" class="title"> <input type="checkbox" name="toggle" value="" <?php echo 'onClick="checkAll(' . count($rows) . ');"'; ?> /></th>
      <th width="17%" class="title"><?php echo htmlspecialchars( CBTxt::T('Title') ); ?></th>
      <th width="30%" class="title"><?php echo htmlspecialchars( CBTxt::T('Description') ); ?></th>
      <th width="10%" class="title"><?php echo htmlspecialchars( CBTxt::T('Display') ); ?></th>
      <th width="12%" class="title"><?php echo htmlspecialchars( CBTxt::T('Plugin') ); ?></th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Published') ); ?></th>
      <th width="10%" class="title"><?php echo htmlspecialchars( CBTxt::T('Access') ); ?></th>
      <th width="5%" class="title"><?php echo htmlspecialchars( CBTxt::T('Position') ); ?></th>
      <th width="5%" class="title" colspan="2"><?php echo htmlspecialchars( CBTxt::T('Re-Order') ); ?></th>
      <th width="3%" colspan="2"><a href="javascript: cbsaveorder( <?php echo count( $rows )-1; ?> )"><img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-16-filesave.png" border="0" width="16" height="16" alt="<?php echo htmlspecialchars( CBTxt::T('Save Order') ); ?>" /></a></th>
      <th width="1%" class="title"><?php echo htmlspecialchars( CBTxt::T('Tabid') ); ?></th>
    </tr>
   </thead>
   <tbody>
<?php
		$k = 0;
		$imgpath='../components/com_comprofiler/images/';
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row =& $rows[$i];
			if($row->sys==2) {
				$img3='tick.png';
				$task3=null;
			} else {
			        $img3 = $row->enabled ?  'tick.png' : 'publish_x.png';
			        $task3 = $row->enabled ?  'tabPublishedNo' : 'tabPublishedYes';
			}
?>
    <tr class="<?php echo "row$k"; ?>">
      <td><?php echo $i+1+$pageNav->limitstart;?></td>
      <td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->tabid; ?>" onclick="isChecked(this.checked);" /></td>
      <td> <a href="#editTab" onclick="return listItemTask('cb<?php echo $i;?>','editTab')">
        <?php echo htmlspecialchars( getLangDefinition($row->title) ); ?> </a> </td>
	<td><?php echo htmlspecialchars( getLangDefinition($row->description) ); ?></td>
	<td><?php echo htmlspecialchars( $row->displaytype ); ?></td>
	<td><?php
      		if ( $row->pluginid && ( $row->pluginpublished == 0 ) ) {
      			echo '<span style="color:red;" title="' . htmlspecialchars( CBTxt::T('tab will not be visible as plugin is not published.') ) . '">';
      		}
			echo ( ( $row->pluginname) ? htmlspecialchars( $row->pluginname ) : "-" );
     		if ( $row->pluginid && ( $row->pluginpublished == 0 ) ) {
     			echo '</span>';
     		}
	  ?></td>
	<?php $task3 = ($task3==null) ? " " : "onClick=\"return listItemTask('cb".$i."','".$task3."')\"" ; ?>
      <td><a href="javascript: void(0);" <?php echo $task3; ?> ><img src="<?php echo $imgpath.$img3;?>" width="16" height="16" border="0" alt="" /></a></td>
	  <td><?php
	  		if ( $row->useraccessgroupid >= 0 ) {
		  		echo '<span style="color:red;">' . $_CB_framework->acl->get_group_name( (int) $row->useraccessgroupid ) . '</span>';
	  		} elseif ( $row->useraccessgroupid == -2 ) {
	  			echo '<span style="color:green;">' . htmlspecialchars( CBTxt::T('Everybody') ) . '</span>';
	  		} elseif ( $row->useraccessgroupid == -1 ) {
	  			echo '<span style="color:orange;">' . htmlspecialchars( CBTxt::T('All Registered Users') ) . '</span>';
	  		}
	  ?></td>
	<td><?php echo htmlspecialchars( substr( $row->position, 0, 3 ) == 'cb_' ? substr( $row->position, 3 ) : $row->position ); ?></td>
      <td>
	<?php    if (($i > 0 || ($i+$pageNav->limitstart > 0)) && $row->position == @$rows[$i-1]->position) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderupTab')">
            <img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-12-uparrow.png" width="12" height="12" border="0" alt="<?php echo htmlspecialchars( CBTxt::T('Move Up') ); ?>" />
         </a>
	<?php    } ?>
      </td>
      <td>
	<?php    if (($i < $n-1 || $i+$pageNav->limitstart < $pageNav->total-1) && $row->position == @$rows[$i+1]->position) { ?>
         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderdownTab')">
            <img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-12-downarrow.png" width="12" height="12" border="0" alt="<?php echo htmlspecialchars( CBTxt::T('Move Down') ); ?>" />
         </a>
	<?php    } ?>
      </td>
	  <td align="center" colspan="2">
	  <input type="text" name="order[]" size="5" value="<?php echo htmlspecialchars( $row->ordering ); ?>" class="text_area" style="text-align: center" />
	  </td>
	  <td style="text-align:right;"><?php echo htmlspecialchars( $row->tabid ); ?></td>
    </tr>
    <?php $k = 1 - $k; } ?>
   </tbody>
   <tfoot>
    <tr>
      <th align="center" colspan="15"> <?php echo $pageNav->getListFooter(); ?></th>
    </tr>
   </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="showTab" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php
	echo cbGetSpoofInputTag( 'tab' );
  ?>
</form>
<?php }

	/**
	 * over ride styles from webfxlayout
	 *
	 */
	static function _overideWebFxLayout() {
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
	static function showConfig( &$ueConfig, &$lists, $option ) {
		global $_CB_framework;
	
		HTML_comprofiler::secureAboveForm('showConfig');
		outputCbTemplate(2);
		outputCbJs(2);
	
		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-settings', htmlspecialchars( "CB " . _UE_REG_CONFIGURATION_MANAGER ) ) );
		HTML_comprofiler::_overideWebFxLayout();

?>
<div style="width:95%;text-align:center;margin-bottom:15px;">
	<div style="width:88%;margin:auto;text-align:left;">
<?php update_checker(); ?>
	</div>
</div>
   <form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm">
   <table cellspacing='0' cellpadding='4' border='0' width='100%'><tr><td width='100%'>
<?php
$tabs = new cbTabs( 0,2 );
?>
<?php

echo $tabs->startPane( "CB" );
echo $tabs->startTab("CB",_UE_GENERAL,"tab1");
?>

   <table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _UE_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _UE_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_NAME_STYLE ?></td>
         <td align="left" valign="top"><?php echo $lists['name_style']; ?></td>
         <td align="left" valign="top"><?php echo _UE_NAME_STYLE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_NAME_FORMAT ?></td>
         <td align="left" valign="top"><?php echo $lists['name_format']; ?></td>
         <td align="left" valign="top"><?php echo _UE_NAME_FORMAT_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_DATE_FORMAT ?></td>
         <td align="left" valign="top"><?php echo $lists['date_format']; ?></td>
         <td align="left" valign="top"><?php echo _UE_DATE_FORMAT_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_CALENDAR_TYPE ?></td>
         <td align="left" valign="top"><?php echo $lists['calendar_type']; ?></td>
         <td align="left" valign="top"><?php echo _UE_CALENDAR_TYPE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOW_EMAIL_DISPLAY ?></td>
         <td align="left" valign="top"><?php echo $lists['allow_email_display']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOW_EMAIL_DISPLAY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOW_EMAIL_REPLYTO ?></td>
         <td align="left" valign="top"><?php echo $lists['allow_email_replyto']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOW_EMAIL_REPLYTO_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOW_EMAIL ?></td>
         <td align="left" valign="top"><?php echo $lists['allow_email']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOW_EMAIL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOW_WEBSITE ?></td>
         <td align="left" valign="top"><?php echo $lists['allow_website']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOW_WEBSITE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOW_ONLINESTATUS ?></td>
         <td align="left" valign="top"><?php echo $lists['allow_onlinestatus']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOW_ONLINESTATUS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ICONS_DISPLAY ?></td>
         <td align="left" valign="top"><?php echo $lists['icons_display']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ICONS_DISPLAY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_LOGIN_TYPE ?></td>
         <td align="left" valign="top"><?php echo $lists['login_type']; ?></td>
         <td align="left" valign="top"><?php echo _UE_LOGIN_TYPE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>
<?php
echo $tabs->endTab();
echo $tabs->startTab("CB",_UE_REGISTRATION,"tab2");
?>
   <table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _UE_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _UE_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_CB_ALLOW ?></td>
         <td align="left" valign="top"><?php echo $lists['admin_allowcbregistration']; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_CB_ALLOW_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_CB_EMAILPASS ?></td>
         <td align="left" valign="top"><?php echo $lists['emailpass']; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_CB_EMAILPASS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_ADMIN_APPROVAL ?></td>
         <td align="left" valign="top"><?php echo $lists['admin_approval']; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_ADMIN_APPROVAL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_CONFIRMATION ?></td>
         <td align="left" valign="top"><?php echo $lists['confirmation']; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_CONFIRMATION_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_USERNAMECHECKER ?></td>
         <td align="left" valign="top"><?php echo $lists['reg_username_checker']; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_USERNAMECHECKER_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_EMAILCHECKER ?></td>
         <td align="left" valign="top"><?php echo $lists['reg_email_checker']; ?></td>
         <td align="left" valign="top"><?php if ( ! function_exists( 'getmxrr' ) ) { echo _UE_REG_EMAILCHECKER_WARNING . ' --- '; } echo _UE_REG_EMAILCHECKER_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_UNIQUEEMAIL ?></td>
         <td align="left" valign="top"><?php echo $_CB_framework->getCfg( 'uniquemail') ? _UE_YES : _UE_NO; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_UNIQUEEMAIL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_SHOW_LOGIN_ON_PAGE ?></td>
         <td align="left" valign="top"><?php echo $lists['reg_show_login_on_page']; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_SHOW_LOGIN_ON_PAGE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_EMAIL_NAME ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_reg_email_name" value="<?php echo htmlspecialchars(stripslashes($ueConfig['reg_email_name'])); ?>" /></td>
         <td align="left" valign="top"><?php echo _UE_REG_EMAIL_NAME_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_EMAIL_FROM ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_reg_email_from" value="<?php echo htmlspecialchars($ueConfig['reg_email_from']); ?>" /></td>
         <td align="left" valign="top"><?php echo _UE_REG_EMAIL_FROM_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_EMAIL_REPLYTO ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_reg_email_replyto" value="<?php echo htmlspecialchars($ueConfig['reg_email_replyto']); ?>" /></td>
         <td align="left" valign="top"><?php echo _UE_REG_EMAIL_REPLYTO_DESC ?></td>
      </tr>
      <tr  align="left" valign="middle">
	 <td align="left" valign="top"></td>
	 <td align="left" valign="top" colspan="2"><?php echo _UE_REG_EMAIL_TAGS; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_PEND_APPR_SUB ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_reg_pend_appr_sub" size="50" value="<?php echo htmlspecialchars(stripslashes($ueConfig['reg_pend_appr_sub'])); ?>" /></td>
         <td align="left" valign="top"><?php echo _UE_REG_PEND_APPR_SUB_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_PEND_APPR_MSG ?></td>
         <td align="left" valign="top" colspan=2><textarea name="cfg_reg_pend_appr_msg" cols=50 rows=6><?php echo htmlspecialchars(stripslashes($ueConfig['reg_pend_appr_msg'])); ?></textarea></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_WELCOME_SUB ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_reg_welcome_sub" size="50" value="<?php echo htmlspecialchars(stripslashes($ueConfig['reg_welcome_sub'])); ?>" /></td>
         <td align="left" valign="top"><?php echo _UE_REG_WELCOME_SUB_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_WELCOME_MSG ?></td>
         <td align="left" valign="top" colspan=2><textarea name="cfg_reg_welcome_msg" cols=50 rows=6><?php echo htmlspecialchars(stripslashes($ueConfig['reg_welcome_msg'])); ?></textarea></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_SHOW_ICONS_EXPLAIN ?></td>
         <td align="left" valign="top"><?php echo $lists['reg_show_icons_explain']; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_SHOW_ICONS_EXPLAIN_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_INTRO_MSG ?></td>
         <td align="left" valign="top"><textarea name="cfg_reg_intro_msg" cols=50 rows=6><?php echo htmlspecialchars(stripslashes($ueConfig['reg_intro_msg'])); ?></textarea></td>
         <td align="left" valign="top"><?php echo _UE_REG_INTRO_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_CONCLUSION_MSG ?></td>
         <td align="left" valign="top"><textarea name="cfg_reg_conclusion_msg" cols=50 rows=6><?php echo htmlspecialchars(stripslashes($ueConfig['reg_conclusion_msg'])); ?></textarea></td>
         <td align="left" valign="top"><?php echo _UE_REG_CONCLUSION_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_TOC_MSG ?></td>
         <td align="left" valign="top"><?php echo $lists['reg_enable_toc']; ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_TOC_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_TOC_URL_MSG ?></td>
         <td align="left" valign="top"><input type="text" size="50" name="cfg_reg_toc_url" value="<?php echo htmlspecialchars($ueConfig['reg_toc_url']); ?>" /></td>
         <td align="left" valign="top"><?php echo _UE_REG_TOC_URL_DESC ?></td>
      </tr>
     <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_FIRST_VISIT_URL_MSG ?></td>
         <td align="left" valign="top"><input type="text" size="50" name="cfg_reg_first_visit_url" value="<?php echo htmlspecialchars($ueConfig['reg_first_visit_url']); ?>" /></td>
         <td align="left" valign="top"><?php echo _UE_REG_FIRST_VISIT_URL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>
<?php
echo $tabs->endTab();
echo $tabs->startTab("CB",_UE_USERLIST,"tab3");
?>
   <table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _UE_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _UE_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_NUM_PER_PAGE ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_num_per_page" value="<?php echo htmlspecialchars($ueConfig['num_per_page']); ?>" /></td>
         <td align="left" valign="top"><?php echo _UE_NUM_PER_PAGE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOW_PROFILELINK ?></td>
         <td align="left" valign="top"><?php echo $lists['allow_profilelink']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOW_PROFILELINK_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>
<?php
echo $tabs->endTab();
echo $tabs->startTab("CB",_UE_USERPROFILE,"tab4");
?>
   <table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _UE_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _UE_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_USERNAME ?></td>
         <td align="left" valign="top"><?php echo $lists['usernameedit']; ?></td>
         <td align="left" valign="top"><?php echo _UE_USERNAME_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ADMINREQUIREDFIELDS ?></td>
         <td align="left" valign="top"><?php echo $lists['adminrequiredfields']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ADMINREQUIREDFIELDS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOW_PROFILEVIEWBY ?></td>
         <td align="left" valign="top"><?php echo $lists['allow_profileviewbyGID']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOW_PROFILEVIEWBY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_MINHITSINTV ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_minHitsInterval" value="<?php echo htmlspecialchars($ueConfig['minHitsInterval']);?>" /></td>
         <td align="left" valign="top"><?php echo _UE_MINHITSINTV_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_TEMPLATEDIR ?></td>
         <td align="left" valign="top"><?php echo $lists['templatedir']; ?></td>
         <td align="left" valign="top"><?php echo _UE_TEMPLATEDIR_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_PROFILE_2COLS ?></td>
         <td align="left" valign="top"><?php echo _UE_LEFT ?>: <input type="text" size="2" name="cfg_left2colsWidth" value="<?php echo htmlspecialchars($ueConfig['left2colsWidth']);?>" /> %&nbsp;&nbsp;&nbsp;&nbsp;<?php echo _UE_REG_PROFILE_2COLS_RIGHT_REST ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_PROFILE_2COLS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_PROFILE_3COLS ?></td>
         <td align="left" valign="top"><?php echo _UE_LEFT ?>: <input type="text" size="2" name="cfg_left3colsWidth" value="<?php echo htmlspecialchars($ueConfig['left3colsWidth']);?>" /> %&nbsp;&nbsp;&nbsp;&nbsp;
         							  <?php echo _UE_RIGHT ?>: <input type="text" size="2" name="cfg_right3colsWidth" value="<?php echo htmlspecialchars($ueConfig['right3colsWidth']);?>" /> %</td>
         <td align="left" valign="top"><?php echo _UE_REG_PROFILE_3COLS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_SHOWEMPTYTABS ?></td>
         <td align="left" valign="top"><?php echo $lists['showEmptyTabs']; ?></td>
         <td align="left" valign="top"><?php echo _UE_SHOWEMPTYTABS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_SHOWEMPTYFIELDS ?></td>
         <td align="left" valign="top"><?php echo $lists['showEmptyFields']; ?></td>
         <td align="left" valign="top"><?php echo _UE_SHOWEMPTYFIELDS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_EMPTYFIELDSTEXT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_emptyFieldsText" value="<?php echo htmlspecialchars( isset( $ueConfig['emptyFieldsText'] ) ? $ueConfig['emptyFieldsText'] : '-' );?>" /></td>
         <td align="left" valign="top"><?php echo  _UE_EMPTYFIELDSTEXT_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_NESTTABS ?></td>
         <td align="left" valign="top"><?php echo $lists['nesttabs']; ?></td>
         <td align="left" valign="top"><?php echo _UE_NESTTABS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_XHTMLCOMPLY ?></td>
         <td align="left" valign="top"><?php echo $lists['xhtmlComply']; ?></td>
         <td align="left" valign="top"><?php echo _UE_XHTMLCOMPLY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_FRONTENDUSERPARAMS ?></td>
         <td align="left" valign="top"><?php echo $lists['frontend_userparams']; ?></td>
         <td align="left" valign="top"><?php echo _UE_FRONTENDUSERPARAMS_DESC ?></td>
      </tr>
<?php
if ( isset( $ueConfig['frontend_userparams'] ) ) {
	if ( ( $ueConfig['frontend_userparams'] == 1 ) !== in_array( $_CB_framework->getCfg( "frontend_userparams" ), array( '1', null) ) ) {
//TBD in CB 1.3: move those in language strings:
?>
      <tr align="center" valign="middle">
         <td align="left" valign="top"> </td>
         <td align="left" valign="top"><div class="cbSmallWarning"><?php echo htmlspecialchars( CBTxt::T('WARNING: different from the CMS setting !') ); ?></div></td>
         <td align="left" valign="top"><?php echo htmlspecialchars( CBTxt::T('This may be ok, but this warning is just to make you aware of the difference.') ); ?></td>
      </tr>
<?php
	}
}
?>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo CBTxt::Th("Use new div or old table based views") ?></td>
         <td align="left" valign="top"><?php echo $lists['use_divs']; ?></td>
         <td align="left" valign="top"><?php echo CBTxt::Th("Choose table for compatibility with old templates and div for table-less output.") ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_FILTER_ALLOWED_TAGS ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_html_filter_allowed_tags" value="<?php echo htmlspecialchars($ueConfig['html_filter_allowed_tags']);?>" /></td>
         <td align="left" valign="top"><?php echo  _UE_REG_FILTER_ALLOWED_TAGS_DESC . '<br />' . $lists['_filteredbydefault']; ?></td>
      </tr>
      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>
<?php
echo $tabs->endTab();


$imgToolBox							=	new imgToolBox();
if ( $ueConfig['im_path'] ) {
	$imgToolBox->_IM_path			=	$ueConfig['im_path'];
}
if ( $ueConfig['netpbm_path'] ) {
	$imgToolBox->_NETPBM_path		=	$ueConfig['netpbm_path'];
}
$imageLibs							=	$imgToolBox->getImageLibs();

echo $tabs->startTab("CB",_UE_AVATARS,"tab5");
?>
   <table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _UE_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _UE_EXPLANATION ?></th>
      </tr>
	 <tr align="center" valign="middle">
		<td align="left" valign="top"><?php echo _UE_IMPATH;?></td>
		<td align="left" valign="top">
			<input type="text" name="cfg_im_path" value="<?php echo ($ueConfig['im_path'] == '') ? 'auto' : htmlspecialchars($ueConfig['im_path']);?>" size="40" >
		</td>
		<td align="left" valign="top">
			<?php echo _UE_IMPATH_DESC;?>
		</td>
	</tr>
	 <tr align="center" valign="middle">
		<td align="left" valign="top"><?php echo _UE_NETPBMPATH;?></td>
		<td align="left" valign="top">
			<input type="text" name="cfg_netpbm_path" value="<?php echo ($ueConfig['netpbm_path'] == '') ? 'auto' : htmlspecialchars($ueConfig['netpbm_path']);?>" size="40" >
		</td>
		<td align="left" valign="top">
			<?php echo _UE_NETPBMPATH_DESC;?>
		</td>
	</tr>
      <tr align="center" valign="middle">
	<td align="left" valign="top">
		<?php echo _UE_CONVERSIONTYPE;?>
	</td>
	<td align="left" valign="top">
	<?php echo $lists['conversiontype']; ?>
	</td>
	<td align="left" valign="top">
		<a href="http://www.imagemagick.org" target=_blank><?php echo htmlspecialchars( CBTxt::T('ImageMagick') ); ?></a>&nbsp;&nbsp;
			<?php if(array_key_exists('imagemagick',$imageLibs)) echo '<strong><font color="green">'._UE_AUTODET.' '.$imageLibs['imagemagick'].'</font></strong>'; else echo '<strong><font color="red">' . _UE_ERROR_NOTINSTALLED . '</font></strong>'; ?>
			<br />
		<a href="http://sourceforge.net/projects/netpbm" target=_blank><?php echo htmlspecialchars( CBTxt::T('NetPBM') ); ?></a>&nbsp;&nbsp;
			<?php if(array_key_exists('netpbm',$imageLibs)) echo '<strong><font color="green">'._UE_AUTODET.' '.$imageLibs['netpbm'].'</font></strong>'; else echo '<strong><font color="red">' . _UE_ERROR_NOTINSTALLED . '</font></strong>'; ?>
			<br />
		<?php echo htmlspecialchars( CBTxt::T('GD1 library') ); ?>
			<?php if(array_key_exists('gd1',$imageLibs['gd'])) echo '&nbsp;&nbsp;<strong><font color="green">'._UE_AUTODET.', '.$imageLibs['gd']['gd1'].'</font></strong>'; else echo '<strong><font color="red">' . _UE_ERROR_NOTINSTALLED . '</font></strong>'; ?>
			<br />
		<?php echo htmlspecialchars( CBTxt::T('GD2 library') ); ?>
			<?php if(array_key_exists('gd2',$imageLibs['gd'])) echo '&nbsp;&nbsp;<strong><font color="green">'._UE_AUTODET.', '.$imageLibs['gd']['gd2'].'</font></strong>'; else echo '<strong><font color="red">' . _UE_ERROR_NOTINSTALLED . '</font></strong>'; ?>

	</td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AVATAR ?></td>
         <td align="left" valign="top"><?php echo $lists['allowAvatar']; ?></td>
         <td align="left" valign="top"><?php echo _UE_AVATAR_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AVATARUPLOAD ?></td>
         <td align="left" valign="top"><?php echo $lists['allowAvatarUpload']; ?></td>
         <td align="left" valign="top"><?php echo _UE_AVATARUPLOAD_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALWAYSRESAMPLEUPLOADS ?></td>
         <td align="left" valign="top"><?php echo $lists['avatarResizeAlways']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALWAYSRESAMPLEUPLOADS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AVATARGALLERY ?></td>
         <td align="left" valign="top"><?php echo $lists['allowAvatarGallery']; ?></td>
         <td align="left" valign="top"><?php echo _UE_AVATARGALLERY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AVHEIGHT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_avatarHeight" value="<?php echo htmlspecialchars($ueConfig['avatarHeight']);?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AVWIDTH ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_avatarWidth" value="<?php echo htmlspecialchars($ueConfig['avatarWidth']);?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AVSIZE ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_avatarSize" value="<?php echo htmlspecialchars($ueConfig['avatarSize']);?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_TNHEIGHT ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_thumbHeight" value="<?php echo htmlspecialchars($ueConfig['thumbHeight']);?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_TNWIDTH ?></td>
         <td align="left" valign="top"><input type="text" name="cfg_thumbWidth" value="<?php echo htmlspecialchars($ueConfig['thumbWidth']);?>" /></td>
      </tr>
      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>
<?php
echo $tabs->endTab();
echo $tabs->startTab("CB",_UE_MODERATE,"tab6");
?>
   <table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _UE_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _UE_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AVATARUPLOADAPPROVALGROUP ?></td>
         <td align="left" valign="top"><?php echo $lists['imageApproverGid']; ?></td>
         <td align="left" valign="top"><?php echo _UE_AVATARUPLOADAPPROVALGROUP_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_MODERATORUSERAPPOVAL ?></td>
         <td align="left" valign="top"><?php echo $lists['allowModUserApproval']; ?></td>
         <td align="left" valign="top"><?php echo _UE_MODERATORUSERAPPOVAL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_MODERATOREMAIL ?></td>
         <td align="left" valign="top"><?php echo $lists['moderatorEmail']; ?></td>
         <td align="left" valign="top"><?php echo _UE_MODERATOREMAIL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOWUSERREPORTS ?></td>
         <td align="left" valign="top"><?php echo $lists['allowUserReports']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOWUSERREPORTS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AVATARUPLOADAPPROVAL ?></td>
         <td align="left" valign="top"><?php echo $lists['avatarUploadApproval']; ?></td>
         <td align="left" valign="top"><?php echo _UE_AVATARUPLOADAPPROVAL_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOWMODERATORSUSEREDIT ?></td>
         <td align="left" valign="top"><?php echo $lists['allowModeratorsUserEdit']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOWMODERATORSUSEREDIT_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOWUSERPROFILEBANNING ?></td>
         <td align="left" valign="top"><?php echo $lists['allowUserBanning']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOWUSERPROFILEBANNING_DESC ?></td>
      </tr>

      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>
<?php
echo $tabs->endTab();
echo $tabs->startTab("CB",_UE_CONNECTION,"tab7");
?>
   <table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _UE_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _UE_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ALLOWCONNECTIONS ?></td>
         <td align="left" valign="top"><?php echo $lists['allowConnections']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ALLOWCONNECTIONS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_CONNECTIONDISPLAY ?></td>
         <td align="left" valign="top"><?php echo $lists['connectionDisplay']; ?></td>
         <td align="left" valign="top"><?php echo _UE_CONNECTIONDISPLAY_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_CONNECTIONPATH ?></td>
         <td align="left" valign="top"><?php echo $lists['connectionPath']; ?></td>
         <td align="left" valign="top"><?php echo _UE_CONNECTIONPATH_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_USEMUTUALCONNECTIONACCEPTANCE ?></td>
         <td align="left" valign="top"><?php echo $lists['useMutualConnections']; ?></td>
         <td align="left" valign="top"><?php echo _UE_USEMUTUALCONNECTIONACCEPTANCE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_CONNECTOINNOTIFYTYPE ?></td>
         <td align="left" valign="top"><?php echo $lists['conNotifyTypes']; ?></td>
         <td align="left" valign="top"><?php echo _UE_CONNECTOINNOTIFYTYPE_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_AUTOADDCONNECTIONS ?></td>
         <td align="left" valign="top"><?php echo $lists['autoAddConnections']; ?></td>
         <td align="left" valign="top"><?php echo _UE_AUTOADDCONNECTIONS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_CONNECTIONCATEGORIES ?></td>
         <td align="left" valign="top" ><textarea name="cfg_connection_categories" cols=25 rows=6><?php echo htmlspecialchars($ueConfig['connection_categories']); ?></textarea></td>
         <td align="left" valign="top"><?php echo _UE_CONNECTIONCATEGORIES_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>
<?php
echo $tabs->endTab();
echo $tabs->startTab("CB",_UE_INTEGRATION,"tab8");
?>
   <table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
      <tr align="center" valign="middle">
         <th width="20%">&nbsp;</th>
         <th width="20%"><?php echo _UE_CURRENT_SETTINGS ?></th>
         <th width="60%"><?php echo _UE_EXPLANATION ?></th>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo CBTxt::Th('Translations highlighting') ?></td>
         <td align="left" valign="top"><?php echo $lists['translations_debug']; ?></td>
         <td align="left" valign="top"><?php echo CBTxt::Th('Here you can highlight and debug your translations in various ways.') ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_ENABLESPOOFCHECK ?></td>
         <td align="left" valign="top"><?php echo $lists['enableSpoofCheck']; ?></td>
         <td align="left" valign="top"><?php echo _UE_ENABLESPOOFCHECK_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_NOVERSIONCHECK ?></td>
         <td align="left" valign="top"><?php echo $lists['noVersionCheck']; ?></td>
         <td align="left" valign="top"><?php echo _UE_NOVERSIONCHECK_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <td align="left" valign="top"><?php echo _UE_REG_FURTHER_SETTINGS ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_FURTHER_SETTINGS_MORE ?></td>
         <td align="left" valign="top"><?php echo _UE_REG_FURTHER_SETTINGS_DESC ?></td>
      </tr>
      <tr align="center" valign="middle">
         <th colspan="3">&nbsp;</th>
      </tr>
   </table>

<?php
echo $tabs->endTab();
echo $tabs->endPane();

?>
</td></tr></table>
   <input type="hidden" name="task" value="" />
   <input type="hidden" name="option" value="<?php echo $option; ?>" />
   <input type="hidden" name="cfg_version" value="<?php echo $ueConfig['version']; ?>" />
   <?php
	echo cbGetSpoofInputTag( 'config' );
	?>
</form>
<?php
		// flush();
		// ob_flush();
?>
<div style="align:center;">
   <p><?php echo _UE_BY;	// Note: the line below is not translated on purpose as it is part of the copyright notice subject to the GPL: ?>
      <a href="http://www.joomlapolis.com" target="_blank">Community Builder Team of Joomlapolis</a>
      <br />
      <font class="small"><?php echo _UE_VERSION ?>: <?php echo $ueConfig['version']; ?></font>
      <?php
		// update_checker();
?>
   </p>
</div>
<?php
	}


	static function showTools() {
		HTML_comprofiler::secureAboveForm('showTools');

		outputCbTemplate( 2 );
		outputCbJs( 2 );

		global $_CB_Backend_Title, $ueConfig;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-tools', htmlspecialchars( CBTxt::T('CB Tools Manager') ) ) );

		$cbSpoofField			=	cbSpoofField();
		$cbSpoofString			=	cbSpoofString( null, 'cbtools' );

		global $_CB_framework, $_CB_database;
		if ( ( checkJversion() == 1 ) && ( $_CB_framework->getCfg( 'sef' ) != 0 ) ) {
			// Itemid is strictly required for Joomla 1.5 with SEF ON only:
			$_CB_database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_comprofiler' AND published=1 AND access =0");
			$cbItemid = (int) $_CB_database->loadResult();
		} else {
			$cbItemid			=	true;
		}

?>
<div style="text-align:left;margin-top:30px;margin-bottom:20px;">
<?php
		if ( version_compare( phpversion(), '5.0.0', '<' ) ) {
			echo '<div class="cbWarning">' . htmlspecialchars( sprintf( CBTxt::T( 'PHP Version %s is not compatible with %s: Please upgrade to PHP %s or greater.' ), phpversion(), 'Community Builder' . ' ' . $ueConfig['version'], sprintf( CBTxt::T('at least version %s, recommended version %s'), '5.0.0', '5.2.6' ) ) ) . '</div>';
		}

		if ( ! $cbItemid ) {
			echo '<div class="cbWarning">';
			echo htmlspecialchars( CBTxt::T( 'In order for CB to function properly a Joomla/Mambo menu item must be present. This menu item must also be published for PUBLIC access. It appears that this environment is missing this mandatory menu item. Please refer to the section titled "Adding the CB Profile" of the PDF installation guide included in your CB distribution package for additional information regarding this matter.' ) );
			echo '</div>';
		}
?>
	<table width="90%" border="0" cellpadding="10" cellspacing="2" class="adminForm">
		<tr>
			<td width="25%">
				<a href="<?php echo $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=loadSampleData&$cbSpoofField=$cbSpoofString" ); ?>"><?php echo htmlspecialchars( CBTxt::T('Load Sample Data') ); ?></a>
			</td>
			<td width="75%">
				<?php echo htmlspecialchars( CBTxt::T('This will load sample data into the Joomla/Mambo Community Builder component. Precisely, an additional information tab (that you can change, unpublish or delete in CB Tabs manager) will be created containing fields for: location, occupation, interests, company, address, city, state, zipcode, country, phone and fax (you can then change, unpublish or delete those fields which you don\'t need in CB Fields Manager). Also a users-list will be created, that you can edit from the CB Lists manager. This will help you get started quicker with CB.') ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=syncUsers&$cbSpoofField=$cbSpoofString" ); ?>"><?php echo htmlspecialchars( CBTxt::T('Synchronize Users') ); ?></a>
			</td>
			<td>
				<?php echo htmlspecialchars( CBTxt::T('This will synchronize the Joomla/Mambo User table with the Joomla/Mambo Community Builder User Table.') ) . '<br />'
				. htmlspecialchars( CBTxt::T('Please make sure before synchronizing that the user name type (first/lastname mode choice) is set correctly in Components / Community Builder / Configuration / General, so that the user-synchronization imports the names in the appropriate format.') );

				?>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=checkcbdb&databaseid=0&$cbSpoofField=$cbSpoofString" ); ?>"><?php echo htmlspecialchars( CBTxt::T('Check Community Builder Database') ); ?></a>
			</td>
			<td>
				<?php echo htmlspecialchars( CBTxt::T('This will perform a series of tests on the Community Builder database and report back potential inconsistencies without changing or correcting the database.') ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=checkcbdb&databaseid=3&$cbSpoofField=$cbSpoofString" ); ?>"><?php echo htmlspecialchars( CBTxt::T('Check Community Builder User Fields Database') ); ?></a>
			</td>
			<td>
				<?php echo htmlspecialchars( CBTxt::T('This will perform a series of tests on the Community Builder User fields database and report back potential inconsistencies without changing or correcting the database.') ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=checkcbdb&databaseid=1&$cbSpoofField=$cbSpoofString" ); ?>"><?php echo htmlspecialchars( CBTxt::T('Check CB plugins database') ); ?></a>
			</td>
			<td>
				<?php echo htmlspecialchars( CBTxt::T('This will check the database of installed CB plugins and report back potential inconsistencies without changing or correcting the database.') ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=checkcbdb&databaseid=2&$cbSpoofField=$cbSpoofString" ); ?>"><?php echo htmlspecialchars( CBTxt::T('Check Users Database') ); ?></a>
			</td>
			<td>
				<?php echo htmlspecialchars( CBTxt::T('This will perform a series of tests on the Users database of the CMS, the Community Builder users database and ACL and report back potential inconsistencies without changing or correcting the database.') ); ?>
			</td>
		</tr>
	</table>
</div>
 <?php
} //end function showTools

	/**
	 * Shows result of database check or fix (with or without dryrun)
	 *
	 * @param  CBdbChecker  $dbChecker
	 * @param  boolean      $upgrade
	 * @param  boolean      $dryRun
	 * @param  boolean      $result
	 * @param  array        $messagesBefore
	 * @param  array        $messagesAfter
	 * @param  string       $dbName
	 * @param  int          $dbId
	 * @param  boolean      $showConclusion
	 */
	static function fixcbdbShowResults( &$dbChecker, $upgrade, $dryRun, $result, $messagesBefore, $messagesAfter, $dbName, $dbId, $showConclusion = true ) {
		global $_CB_framework;

		static $jsId = 0;
		++$jsId;

		$cbSpoofField			=	cbSpoofField();
		$cbSpoofString			=	cbSpoofString( null, 'cbtools' );

		foreach ( $messagesBefore as $msg ) {
			if ( $msg ) {
				echo '<p>' . $msg . '</p>';
			}
		}

		if ( $dbChecker !== null ) {
			if ( $result == true ) {
				echo '<div><font color="green">'
					. htmlspecialchars( $upgrade ? ( $dryRun ? $dbName . ' ' . CBTxt::T('Database adjustments dryrun is successful, see results below') : $dbName . ' ' . CBTxt::T('Database adjustments have been performed successfully.') ) : CBTxt::T('All') . ' ' . $dbName . ' ' . CBTxt::T('Database is up to date.') )
					. '</font></div>';
			} elseif ( is_string( $result ) ) {
				echo '<div><font color="red">' . $result . '</font></div>';
			} else {
				echo '<div style="color:red;">';
				echo '<h3><font color="red">'
					.	htmlspecialchars( $dbName . ' ' . ( $upgrade ? CBTxt::T('Database adjustments errors:') : CBTxt::T('Database structure differences:') ) )
					.	'</font></h3>';
				$errors		=	$dbChecker->getErrors( false );
				foreach ( $errors as $err ) {
					echo '<div style="font-size:115%">' . $err[0];
					if ( $err[1] ) {
						echo '<div style="font-size:90%">' . $err[1] . '</div>';
					}
					echo '</div>';
				}
				echo "</div>";
				if ( ! $upgrade ) {
					echo '<p><font color="red">'
						. htmlspecialchars( sprintf( CBtxt::T('The %s database structure differences can be fixed (adjusted) by clicking here'), $dbName ) )
						. ': <a href="' . $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=fixcbdb&dryrun=0&databaseid=$dbId&$cbSpoofField=$cbSpoofString" ) . '">'
						. '<span style="font-size:125%; padding: 4px; border: 1px red solid; background-color: #ffd">'
						. htmlspecialchars( sprintf( CBTxt::T('Click here to Fix (adjust) all %s database differences listed above'), $dbName ) )
						. '</span></a> '
						. str_replace( array( '<a href="#">', '</a>', '<strong>', '</strong>' ),
									   array( '<a href="' . $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=fixcbdb&dryrun=1&databaseid=$dbId&$cbSpoofField=$cbSpoofString" ) . '"><span style="padding: 4px; border: 1px green solid; background-color: #ffd">',
									   		  '</span></a>', '<strong><u><span style="font-size:125%;color:red;">', '</span></u></strong>' ),
									   CBTxt::T('(you can also <a href="#">Click here to preview fixing (adjusting) queries in a dry-run</a>), but <strong>in all cases you need to backup database first</strong> as this adjustment is changing the database structure to match the needed structure for the installed version.') )
						. '</font></p>';
				}
			}
			$logs			=	$dbChecker->getLogs( false );
			if ( count( $logs ) > 0 ) {
				echo "<div style='margin-bottom:15px;'><a href='#' id='cbdetailsLinkShow_" . $jsId . "'>" . htmlspecialchars( CBTxt::T('Click here to Show details') ) . "</a></div>";
				echo "<div id='cbdetailsdbcheck_" . $jsId . "' style='color:green;margin-bottom:15px;'>";
				foreach ( $logs as $err ) {
					echo '<div style="font-size:100%">' . $err[0];
					if ( $err[1] ) {
						echo '<div style="font-size:90%">' . $err[1] . '</div>';
					}
					echo '</div>';
				}
				echo '</div>';
				$_CB_framework->outputCbJQuery( "$('#cbdetailsdbcheck_" . $jsId . "').hide();      $('#cbdetailsLinkShow_" . $jsId . "').click( function() { $('#cbdetailsdbcheck_" . $jsId . "').toggle('slow'); $('#cbdetailsLinkShow_" . $jsId . "').html( $('#cbdetailsLinkShow_" . $jsId . "').html() == '" . addslashes( CBTxt::T('Click here to Show details') ) . "' ? '" . addslashes( CBTxt::T('Click here to Hide details') ) . "' : '" . addslashes( CBTxt::T('Click here to Show details') ) . "' ); return false; } );");
			}
		}
		if ( $showConclusion ) {
			if ( $upgrade ) {
				if ( $dryRun ) {
					echo "<p>" . htmlspecialchars( sprintf(CBTxt::T('Dry-run of %s database adjustments done. None of the queries listed in details have been performed.') , $dbName) ) . "</p>";
					echo '<p>' . htmlspecialchars( CBTxt::T('The database adjustments listed above can be applied by clicking here') )
						. ': <a href="' . $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=fixcbdb&dryrun=0&databaseid=$dbId&$cbSpoofField=$cbSpoofString" ) . '">'
						. '<span style="font-size:125%; padding: 4px; border: 1px red solid; background-color: #ffd">'
						. htmlspecialchars( CBTxt::T('Click here to Fix (adjust) all database differences listed above.') )
						. '</span></a> '
						. str_replace( array( '<strong>', '</strong>' ),
									   array( '<strong><u><span style="font-size:125%;color:red;">', '</span></u></strong>' ),
										CBTxt::T('<strong>You need to backup database first</strong> as this fixing/adjusting is changing the database structure to match the needed structure for the installed version.') )
						. '</p>';
				} else {
					echo '<p>' . htmlspecialchars( sprintf( CBTxt::T('The %s database adjustments have been done. If all lines above are in green, database adjustments completed successfully. Otherwise, if some lines are red, please report exact errors and queries to authors forum, and try checking database again.'), $dbName ) ) . '</p>';
					echo '<p>' . htmlspecialchars( CBTxt::T('The database structure can be checked again by clicking here') )
						. ': <a href="' . $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=checkcbdb&databaseid=$dbId&$cbSpoofField=$cbSpoofString" ) . '">'
						. '<span style="font-size:125%; padding: 4px; border: 1px orange solid; background-color: #ffd">' . htmlspecialchars( sprintf( CBTxt::T('Click here to Check %s database'), $dbName ) ) . '</span></a>'
						. '</p>';
				}
			} else {
				echo '<p>' . $dbName . ' ' . htmlspecialchars( CBTxt::T('database checks done. If all lines above are in green, test completed successfully. Otherwise, please take corrective measures proposed in red.') ) . '</p>';
			}
		}
		foreach ( $messagesAfter as $msg ) {
			if ( $msg ) {
				echo '<p>' . $msg . '</p>';
			}
		}
	}

	/**
	* Writes a list of the defined modules
	* @param array An array of category objects
	*/
	static function showPlugins( &$rows, &$pageNav, $option, &$lists, $search ) {
		global $_CB_framework, $_PLUGINS;

		HTML_comprofiler::secureAboveForm('showPlugins');

		outputCbTemplate( 2 );
		outputCbJs( 2 );
	    initToolTip( 2 );

		global $_CB_Backend_Title;
		$_CB_Backend_Title	=	array( 0 => array( 'cbicon-48-plugins', htmlspecialchars( CBTxt::T('CB Plugin Manager') )
											 . ' <small><small> &nbsp;&nbsp;&nbsp;&nbsp; <a href="#getplugins">' . htmlspecialchars( CBTxt::T('Get Plugins') ) . '</a></small></small>'
											 . ' &nbsp;&nbsp;&nbsp;'
											 . ' <small><small> &nbsp;&nbsp;&nbsp;&nbsp; <a href="#install">' . htmlspecialchars( CBTxt::T('Install Plugin') ) . '</a></small></small>' ) );
											 
		HTML_comprofiler::_saveOrderJs( 'savepluginorder' );
		ob_start();
	?>
		function submitbutton3(pressbutton) {
			var form = document.adminForm_dir;

			// do field validation
			if (form.userfile.value == ""){
				alert('<?php echo addslashes( CBTxt::T('Please select a directory') ); ?>');
			} else {
				form.submit();
			}
		}
<?php
		$js			=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->document->addHeadScriptDeclaration( $js );
?>
		<form action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm">

		<table class="adminheading" style="width:100%">
		<tr>
			<td style="width:80%">
			<?php echo htmlspecialchars( CBTxt::T('Filter') ); ?>: <input type="text" name="search" value="<?php echo htmlspecialchars( $search );?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
			<td align="right">
			<?php echo $lists['type'];?>
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<thead>
		  <tr>
			<th width="20"><?php echo htmlspecialchars( CBTxt::T('#') ); ?></th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" <?php echo 'onclick="checkAll(' . count( $rows ) . ');"';?> />
			</th>
			<th class="title">
			<?php echo htmlspecialchars( CBTxt::T('Plugin Name') ); ?>
			</th>
			<th nowrap="nowrap" width="5%">
	  		<?php echo htmlspecialchars( CBTxt::T('Installed') ); ?>
			</th>
			<th nowrap="nowrap" width="5%">
	  		<?php echo htmlspecialchars( CBTxt::T('Published') ); ?>
			</th>
			<th colspan="2" nowrap="nowrap" width="5%">
			<?php echo htmlspecialchars( CBTxt::T('Reorder') ); ?>
			</th>
			<th width="2%">
			<?php echo htmlspecialchars( CBTxt::T('Order') ); ?>
			</th>
			<th width="4%">
			<a href="javascript: cbsaveorder( <?php echo count( $rows )-1; ?> )"><img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-16-filesave.png" border="0" width="16" height="16" alt="<?php echo htmlspecialchars( CBTxt::T('Save Order') ); ?>" /></a>
			</th>
			<th nowrap="nowrap" align="left" width="10%">
			<?php echo htmlspecialchars( CBTxt::T('Access') ); ?>
			</th>
			<th nowrap="nowrap" align="left" width="10%">
			<?php echo htmlspecialchars( CBTxt::T('Type') ); ?>
			</th>
			<th nowrap="nowrap" align="left" width="10%">
			<?php echo htmlspecialchars( CBTxt::T('Directory') ); ?>
			</th>
		  </tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row 	= &$rows[$i];

			$xmlfile			=	$_PLUGINS->getPluginXmlPath( $row );
			$filesInstalled		=	file_exists($xmlfile);

			$link = $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=editPlugin&cid=$row->id" );

			//Access
			if ( !$row->access ) {
				$color_access = 'style="color: green;"';
				$task_access = 'accessregistered';
			} else if ( $row->access == 1 ) {
				$color_access = 'style="color: red;"';
				$task_access = 'accessspecial';
			} else {
				$color_access = 'style="color: black;"';
				$task_access = 'accesspublic';
			}

			$access = '	<a href="javascript: void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task_access .'\')" '. $color_access .'>
			'. $row->groupname .'
			</a>';

			//Checked Out
			if ( $filesInstalled && $row->checked_out ) {
				$hover = '';
				$date 				= cbFormatDate( $row->checked_out_time );
				$checked_out_text 	= '<table>';
				$checked_out_text 	.= '<tr><td>'. addslashes($row->editor) .'</td></tr>';
				$checked_out_text 	.= '<tr><td>'. $date .'</td></tr>';
				$checked_out_text 	.= '</table>';
				$hover = 'onMouseOver="return overlib(\''. htmlspecialchars( $checked_out_text ) .'\', CAPTION, \'Checked Out\', BELOW, RIGHT);" onMouseOut="return nd();"';

				if ( checkJversion() == 2 ) {
					$checked_img	 = 'templates/hathor/images/admin/checked_out.png';
				} else {
					$checked_img	 = 'images/checked_out.png';
				}

				$checked	 		= '<img src="'. $checked_img .'" '. $hover .'/>';
			} else {
				$checked = '<input type="checkbox" id="cb'.$i.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" />';
			}

			$imgpath='../components/com_comprofiler/images/';
			//Installedg
			$instImg 	= $filesInstalled ? 'tick.png' : 'publish_x.png';
			$instAlt 	= htmlspecialchars( $filesInstalled ? CBTxt::T('Installed') : CBTxt::T('Plugin Files missing') );
			$installed  = '<img src="' . $imgpath . $instImg .'" border="0" alt="'. $instAlt .'"  title="'. $instAlt .'" />';

			//Published
			$img 	= $row->published ? 'publish_g.png' : 'publish_x.png';
			$task 	= $row->published ? 'unpublishPlugin' : 'publishPlugin';
			$alt 	= $row->published ? CBTxt::T('Published') : CBTxt::T('Unpublished');
			$action	= $row->published ? CBTxt::T('Unpublish Item') : CBTxt::T('Publish item');
			if ( ( $row->type == "language" ) && $row->published ) {
				$published = '<img src="' . $imgpath . 'publish_g.png" border="0" alt="' . htmlspecialchars( CBTxt::T('Published') ) . '" title="' . htmlspecialchars( CBTxt::T('language plugins cannot be unpublished, only uninstalled') ) . '" />';
			} elseif ( ( $row->id == 1 ) && $row->published ) {
				$published = '<img src="' . $imgpath . 'publish_g.png" border="0" alt="' . htmlspecialchars( CBTxt::T('Published') ) . '" title="' . htmlspecialchars( CBTxt::T('CB core plugin cannot be unpublished') ) . '" />';
			} else {
				$published = '<a href="javascript: void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. htmlspecialchars( $action ) .'">
			<img src="'. $imgpath . $img .'" border="0" alt="'. htmlspecialchars( $alt ) .'" />
			</a>';
			}

			//Backend plugin menu:
			$backendPluginMenus = array();
			if ( isset( $row->backend_menu ) && $row->backend_menu ) {
				$backend = explode( ",", $row->backend_menu );
				foreach ( $backend as $backendAction ) {
					$backendActionParts = explode( ":", $backendAction );
					$backendActionLink = $_CB_framework->backendUrl( "index.php?option=com_comprofiler&task=pluginmenu&pluginid=$row->id&menu=$backendActionParts[1]" );
					$backendPluginMenus[] = '&nbsp; [<a href="' . $backendActionLink . '">' . $backendActionParts[0] . '</a>] ';
				}
			}

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="right"><?php echo $i + 1 + $pageNav->limitstart ?></td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( ($row->checked_out && ( $row->checked_out != $_CB_framework->myId() )) || !$filesInstalled ) {
					if ( ! $filesInstalled ) {
						echo '<span title="' . $instAlt , '">';
					}
					echo $row->name;
					if ( ! $filesInstalled ) {
						echo "</span>";
					}
				} else {
					?>
					<a href="<?php echo $link; ?>">
					<?php echo htmlspecialchars( $row->name ); ?>
					</a>
					<?php
					echo implode( '', $backendPluginMenus );
				}
				?>
				</td>
				<td align="center">
				<?php echo $installed;?>
				</td>
				<td align="center">
				<?php echo $published;?>
				</td>
				<td>
				<?php    if (($i > 0 || ($i+$pageNav->limitstart > 0)) && $row->type == @$rows[$i-1]->type) { ?>
			         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderupPlugin')">
			            <img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-12-uparrow.png" width="12" height="12" border="0" alt="<?php echo htmlspecialchars( CBTxt::T('Move Up') ); ?>" />
			         </a>
				<?php    } ?>
			      </td>
			      <td>
				<?php    if (($i < $n-1 || $i+$pageNav->limitstart < $pageNav->total-1) && $row->type == @$rows[$i+1]->type) { ?>
			         <a href="#reorder" onClick="return listItemTask('cb<?php echo $i;?>','orderdownPlugin')">
			            <img src="../components/com_comprofiler/plugin/templates/luna/images/mini-icons/icon-12-downarrow.png" width="12" height="12" border="0" alt="<?php echo htmlspecialchars( CBTxt::T('Move Down') ); ?>" />
			         </a>
				<?php    } ?>
				</td>
				<td align="center" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<td align="left">
				<?php echo $access;?>
				</td>
				<td align="left" nowrap="nowrap">
				<?php echo $row->type;?>
				</td>
				<td align="left" nowrap="nowrap">
				<?php
			if ( ! $filesInstalled ) {
				echo '<span style="text-decoration:line-through" title="' . $instAlt , '">';
			}
			echo $row->element;
			if ( ! $filesInstalled ) {
				echo "</span>";
			}
				?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
	</tbody>
	<tfoot>
     <tr>
      <th align="center" colspan="12"> <?php echo $pageNav->getListFooter(); ?></th>
     </tr>
    </tfoot>
  </table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="showPlugins" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<?php
	echo cbGetSpoofInputTag( 'plugin' );
		?>
</form>


	<div style="clear:both; margin:20px 0px;">
		<table class="adminheading">
		<tr>
			<th class="install">
				<a name="getplugins"><?php echo htmlspecialchars( CBTxt::T('Get Plugins') ); ?></a>
			</th>
		</tr>
		<tr>
			<td align="left" style="padding-bottom:10px;">
				<a href="http://www.joomlapolis.com/cb-solutions" target="_blank"><?php echo htmlspecialchars( CBTxt::T('Click here to see more CB Plugins (Languages, Fields, Tabs, Signup-Connect, Paid Memberships and over 30 more) by CB Team at joomlapolis.com') ); ?></a>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding-bottom:10px;">
				<a href="http://www.joomlapolis.com/cb-solutions/directory" target="_blank"><?php echo htmlspecialchars( CBTxt::T('Click here to see CB Directory listing hundreds of CB extensions at joomlapolis.com') ); ?></a>
			</td>
		</tr>
		<tr>
			<td align="left" style="padding-bottom:10px;">
				<a href="http://extensions.joomla.org/extensions/clients-a-communities/communities/210" target="_blank"><?php echo htmlspecialchars( CBTxt::T('Click here to Check our CB listing on JED and find more third-party free add-ons for your website') ); ?></a>
			</td>
		</tr>
		</table>
	</div>



	<div style="clear:both;">
		<table class="adminheading">
		<tr>
			<th class="install">
			<a name="install"><?php echo htmlspecialchars( CBTxt::T('Install New Plugin') ); ?></a>
			</th>
		</tr>
		</table>

		<form enctype="multipart/form-data" action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="filename">
		<table class="adminform">
		<tr>
			<th>
			<?php echo htmlspecialchars( CBTxt::T('Upload Package File') ); ?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo htmlspecialchars( CBTxt::T('Package File:') ); ?>
			<input class="text_area" name="userfile" type="file" size="70"/>
			<input class="button" type="submit" value="<?php echo htmlspecialchars( CBTxt::T('Upload File & Install') ); ?>" />
			<?php echo CBTxt::P( 'Maximum upload size: <strong>[filesize]</strong> <em>(upload_max_filesize setting in file [php.ini] )</em>',
							 array( '[filesize]' => ini_get( 'upload_max_filesize' ),
							 		'[php.ini]' => ( is_callable( 'php_ini_loaded_file' ) && php_ini_loaded_file() ? htmlspecialchars( php_ini_loaded_file() ) : 'php.ini' ) ) ); ?>
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="installPluginUpload"/>
		<input type="hidden" name="option" value="com_comprofiler"/>
		<input type="hidden" name="client" value=""/>
		<?php
	echo cbGetSpoofInputTag( 'plugin' );
		?>
		</form>
		<br />

		<form enctype="multipart/form-data" action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm_dir">
		<table class="adminform">
		<tr>
			<th>
			<?php echo htmlspecialchars( CBTxt::T('Install from directory') ); ?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo htmlspecialchars( CBTxt::T('Install directory') ); ?>:&nbsp;
			<input type="text" name="userfile" class="text_area" size="65" value=""/>&nbsp;
			<input type="button" class="button" value="<?php echo htmlspecialchars( CBTxt::T('Install') ); ?>" onclick="submitbutton3()" />
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="installPluginDir" />
		<input type="hidden" name="option" value="com_comprofiler"/>
		<input type="hidden" name="client" value=""/>
		<?php
	echo cbGetSpoofInputTag( 'plugin' );
		?>
		</form>
		<br />

		<form enctype="multipart/form-data" action="<?php echo $_CB_framework->backendUrl( 'index.php' ); ?>" method="post" name="adminForm_URL">
		<table class="adminform">
		<tr>
			<th>
			<?php echo htmlspecialchars( CBTxt::T('Install package from web (http/https)') ); ?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo htmlspecialchars( CBTxt::T('Installation package URL') ); ?>:&nbsp;
			<input type="text" name="userfile" class="text_area" size="65" value=""/>&nbsp;
			<input class="button" type="submit" value="<?php echo htmlspecialchars( CBTxt::T('Download Package & Install') ); ?>" />
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="installPluginURL" />
		<input type="hidden" name="option" value="com_comprofiler"/>
		<input type="hidden" name="client" value=""/>
		<?php
	echo cbGetSpoofInputTag( 'plugin' );
		?>
		</form>
		<br />
		<table class="content">
		<?php
	if (!is_callable(array("JFile","write")) || ($_CB_framework->getCfg('ftp_enable') != 1)) {
			writableCell( 'components/com_comprofiler/plugin/user' );
			// writableCell( 'components/com_comprofiler/plugin/fieldtypes' );
			writableCell( 'components/com_comprofiler/plugin/templates' );
			writableCell( 'components/com_comprofiler/plugin/language' );
	}
		writableCell( 'media' );

		?>
		</table>
	</div>
		<?php
	}

	static function showInstallMessage( $message, $title, $url ) {
		global $PHP_SELF;
		?>
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo $title; ?>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<td align="left">
			<strong><?php echo $message; ?></strong>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			[&nbsp;<a href="<?php echo $url;?>" style="font-size: 16px; font-weight: bold"><?php echo htmlspecialchars( CBTxt::T('Continue ...') ); ?></a>&nbsp;]
			</td>
		</tr>
		</table>
		<?php
	}

} // class HTML_moscomprofiler


function writableCell( $folder, $useAdminFs = true ) {
	global $_CB_framework;

	$path					=	$_CB_framework->getCfg('absolute_path') . '/' . $folder;

	if ( $useAdminFs ) {
		cbimport( 'cb.adminfilesystem' );
		$adminFS			=&	cbAdminFileSystem::getInstance();
		if ( ! $adminFS->isUsingStandardPHP() ) {
			return;
		}
		// not yet implemented in ftp layer $writable			=	( $adminFS->file_exists( $path ) && $adminFS->is_writable( $path ) );
	}
	$writable				=	is_writable( $path );
	echo '<tr>';
	echo '<td class="item">' . $folder . '/</td>';
	echo '<td align="left">';
	echo $writable ? '<b><font color="green">' . htmlspecialchars( CBTxt::T('Writeable') ) . '</font></b>' : '<b><font color="red">' . htmlspecialchars( CBTxt::T('Unwriteable') ) . '</font></b>' . '</td>';
	echo '</tr>';
}


function update_checker(){
	  global $_CB_framework, $ueConfig;

/*	  ?>

	  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
      <tr>
        <th width="100%" class="info"><?php echo htmlspecialchars( CBTxt::T('Update check') ); ?></th>
      </tr>
      </table>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
      <tr>
        <th class="title" colspan="2"><?php echo htmlspecialchars( CBTxt::T('Checking for updates...') ); ?></th>
      </tr>
*/	  ?>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
      <tr>
        <td width="15%"><?php echo _UE_VERSION; ?> : </td>
        <td><?php echo $ueConfig['version']; ?></td>
      </tr>
      <tr>
        <td><?php echo _UE_LATEST_VERSION; ?> : </td>
        <td><?php
      if (isset($ueConfig["noVersionCheck"]) && $ueConfig["noVersionCheck"] == "1") {
      	?><div id="cbLatestVersion"><a href="check_now" onclick="return cbCheckVersion();" style="cursor: pointer; text-decoration:underline;"><?php echo htmlspecialchars( CBTxt::T('check now') ); ?></a></div><?php
      } else {
        ?><div id="cbLatestVersion" style="color:#CCC">...</div><?php
      }
        ?></td>
      </tr>
    </table>
<?php
		ob_start();
?>
    function cbCheckVersion() {
    	document.getElementById('cbLatestVersion').innerHTML = '<?php echo addslashes( CBTxt::T('Checking latest version now...') ); ?>';
    	CBmakeHttpRequest('<?php echo $_CB_framework->backendUrl( 'index.php?option=com_comprofiler&task=latestVersion', false, 'raw' ); ?>', 'cbLatestVersion', '<?php echo addslashes( CBTxt::T('There was a problem with the request.') ); ?>', null);
    	return false;
    }
    function cbInitAjax() {
    	CBmakeHttpRequest('<?php echo $_CB_framework->backendUrl( 'index.php?option=com_comprofiler&task=latestVersion', false, 'raw' ); ?>', 'cbLatestVersion', '<?php echo addslashes( CBTxt::T('There was a problem with the request.') ); ?>', null);
    }
<?php
		if (!(isset($ueConfig["noVersionCheck"]) && $ueConfig["noVersionCheck"] == "1")) {
			echo "cbAddEvent(window, 'load', cbInitAjax);";
		}
		$js		=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->document->addHeadScriptDeclaration( $js );
}
?>
