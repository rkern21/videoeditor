<?php
/**
* User Menu Button Bar HTML
* @version $Id: toolbar.comprofiler.html.php 1218 2010-11-03 17:35:52Z beat $
* @package Community Builder
* @subpackage toolbar.comprofiler.html.php
* @author JoomlaJoe and Beat
* @copyright (C) JoomlaJoe and Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

/**
* Utility class for the button bar
* @author Mambo Foundation Inc http://www.mambo-foundation.org
* @copyright 2005-2007 Mambo Foundation Inc.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/
class cbMenuBarBase {

    /**
	* Writes the start of the button bar table
	*/
    static function startTable() {
		echo '<div class="cbtoolbarbar">';
    }
    static function _output( $onClick, $icon, $alt, $link = '#' ) {
    	$translated	=	CBTxt::T( $alt );
		$html	=	'<a href="' . $link . '"'
				.		( $onClick ? ' onclick="' . $onClick . '" ' : '' )
				.		' class="cbtoolbar">'
				.	'<span class="cbicon-32-' . $icon . '" title="' . htmlspecialchars( $translated ) . '"></span>'
				.	htmlspecialchars( $translated )
				.	"</a>\n";
		return $html;
    }
    /**
	* Writes a custom option and task button for the button bar
	* @param string The task to perform (picked up by the switch($task) blocks
	* @param string The image to display
	* @param string The image to display when moused over
	* @param string The alt text for the icon image
	* @param boolean True if required to check that a standard list item is checked
	*/
    static function custom( $task='', $icon='', $iconOver='', $alt='', $listSelect=true, $prefix='' ) {
        if ($listSelect) {
            $onClick = "if (document.adminForm.boxchecked.value == 0){
				alert('" . addslashes( sprintf( CBTxt::T('Please make a selection from the list to %s'), $alt) )."');
			}else {
				".$prefix."submitbutton('$task');
			}";
        } else {
            $onClick	=	$prefix . "submitbutton('$task')";
        }
     //   if ( $icon ) {
		$icon			=	preg_replace( '/\.[^.]*$/', '', $icon );
        echo cbMenuBarBase::_output( $onClick, $icon, $alt );
     //   }
    }

    /**
	* Writes a custom option and task button for the button bar.
	* Extended version of custom() calling hideMainMenu() before submitbutton().
	* @param string The task to perform (picked up by the switch($task) blocks
	* @param string The image to display
	* @param string The image to display when moused over
	* @param string The alt text for the icon image
	* @param boolean True if required to check that a standard list item is checked
	*/
    static function customX( $task='', $icon='', $iconOver='', $alt='', $listSelect=true ) {
        CBtoolmenuBar::custom ($task, $icon, $iconOver, $alt, $listSelect, 'hideMainMenu();');
    }

    /**
	* Standard routine for displaying toolbar icon
	* @param string An override for the task
	* @param string An override for the alt text
	* @param string The name to be used as a legend and as the image name
	* @param
	*/
    static function addToToolBar( $task, $alt, $name, $imagename, $extended = false, $listprompt = '', $confirmMsg = '', $inlineJs = true ) {
        if ( is_null( $alt ) ) {
        	$alt	=	$name;
        }
        echo CBtoolmenuBar::_output( $inlineJs ? CBtoolmenuBar::makeJavaScript( $task, $extended, $listprompt, $confirmMsg ) : null, $imagename, $alt, '#' . $task );
    }

    static function makeJavaScript ($task, $extended, $listprompt='', $confirmMsg = '' ) {
        $script = '';
        if ( $listprompt ) {
        	$script .= "if (document.adminForm.boxchecked.value == 0){ alert('$listprompt'); } else {";
        }
        if ( $confirmMsg ) {
        	$script	.=	"if (confirm('" . addslashes( $confirmMsg ) . "')) { ";
        }
        if ( $extended ) {
        	$script .= 'hideMainMenu();';
        }
        $script .= "submitbutton('$task')";
        if ( $confirmMsg ) {
        	$script	.=	'}';
        }
        if ( $listprompt ) {
        	$script	.=	'}';
        }
        return $script;
    }

    static function getTemplate( ) {
        global $_CB_database;
        $sql = "SELECT template FROM #__templates_menu WHERE client_id='1' AND menuid='0'";
        $_CB_database->setQuery( $sql );
        return $_CB_database->loadResult();
    }

    /**
	* Writes the common 'new' icon for the button bar
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function addNew( $task='new', $alt=null ) {
        CBtoolmenuBar::addToToolBar($task, $alt, 'New', 'new');	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes the common 'new' icon for the button bar.
	* Extended version of addNew() calling hideMainMenu() before submitbutton().
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function addNewX( $task='new', $alt=null ) {
        CBtoolmenuBar::addToToolBar($task, $alt, 'New', 'new', true);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'publish' button
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function publish( $task='publish', $alt=null ) {
        CBtoolmenuBar::addToToolBar($task, $alt, 'Publish', 'publish');	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'publish' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function publishList( $task='publish', $alt=null ) {
        $listprompt = CBTxt::T('Please make a selection from the list to publish');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Publish', 'publish', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'default' button for a record
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function makeDefault( $task='default', $alt=null ) {
        $listprompt = CBTxt::T('Please select an item to make default');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Default', 'publish', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'assign' button for a record
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function assign( $task='assign', $alt=null ) {
        $listprompt = CBTxt::T('Please select an item to assign');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Assign', 'publish', false, $listprompt);		// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'unpublish' button
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function unpublish( $task='unpublish', $alt=null ) {
        CBtoolmenuBar::addToToolBar($task, $alt, 'Unpublish', 'unpublish');	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'unpublish' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function unpublishList( $task='unpublish', $alt=null ) {
        $listprompt = CBTxt::T('Please make a selection from the list to unpublish');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Unpublish', 'unpublish', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'archive' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function archiveList( $task='archive', $alt=null ) {
        $listprompt = CBTxt::T('Please make a selection from the list to archive');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Archive', 'archive', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes an unarchive button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function unarchiveList( $task='unarchive', $alt=null ) {
        $listprompt = CBTxt::T('Please select a news story to unarchive');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Unarchive', 'unarchive', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'edit' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function editList( $task='edit', $alt=null ) {
        $listprompt = CBTxt::T('Please select an item from the list to edit');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Edit', 'edit', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'edit' button for a list of records.
	* Extended version of editList() calling hideMainMenu() before submitbutton().
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function editListX( $task='edit', $alt=null ) {
        $listprompt = CBTxt::T('Please select an item from the list to edit');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Edit', 'edit', true, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'edit' button for a template html
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function editHtml( $task='edit_source', $alt=null ) {
        $listprompt = CBTxt::T('Please select an item from the list to edit');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Edit HTML', 'html', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'edit' button for a template html.
	* Extended version of editHtml() calling hideMainMenu() before submitbutton().
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function editHtmlX( $task='edit_source', $alt=null ) {
        $listprompt = CBTxt::T('Please select an item from the list to edit');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Edit HTML', 'html', true, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'edit' button for a template css
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function editCss( $task='edit_css', $alt=null ) {
        $listprompt = CBTxt::T('Please select an item from the list to edit');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Edit CSS', 'css', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'edit' button for a template css.
	* Extended version of editCss() calling hideMainMenu() before submitbutton().
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function editCssX( $task='edit_css', $alt=null ) {
        $listprompt = CBTxt::T('Please select an item from the list to edit');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Edit CSS', 'css', true, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'delete' button for a list of records
	* @param string  Postscript for the 'are you sure' message
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function deleteList( $msg='', $task='remove', $alt=null ) {
        $listprompt	=	CBTxt::T('Please make a selection from the list to delete');
        $msgIntro	=	CBTxt::T('Are you sure you want to delete the selected items ?');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Delete', 'delete', false, $listprompt, $msgIntro . ' ' .$msg );	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a common 'delete' button for a list of records.
	* Extended version of deleteList() calling hideMainMenu() before submitbutton().
	* @param string  Postscript for the 'are you sure' message
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function deleteListX( $msg='', $task='remove', $alt=null ) {
        $listprompt =	CBTxt::T('Please make a selection from the list to delete');
        $msgIntro	=	CBTxt::T('Are you sure you want to delete the selected items ?');
        CBtoolmenuBar::addToToolBar($task, $alt, 'Delete', 'delete', true, $listprompt, $msgIntro .  ' ' . $msg );	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Write a trash button that will move items to Trash Manager
	*/
    static function trash( $task='remove', $alt=null ) {
        CBtoolmenuBar::addToToolBar($task, $alt, CBTxt::T('Trash'), 'delete');
    }

    /**
	* Writes a preview button for a given option (opens a popup window)
	* @param string The name of the popup file (excluding the file extension)
	*/
    static function preview( $popup='', $updateEditors=false ) {
    	global $_CB_framework;
        $image = cbMenuBarBase::ImageCheckAdmin( 'preview.png', '/administrator/images/', NULL, NULL, CBTxt::T('Preview'), 'preview' );
        $image2 = cbMenuBarBase::ImageCheckAdmin( 'preview_f2.png', '/administrator/images/', NULL, NULL, CBTxt::T('Preview'), 'preview', 0 );
        $cur_template = CBtoolmenuBar::getTemplate();

        ob_start();
		?>
		function popup() {
		    <?php
		    if ($popup == 'contentwindow') {
		        echo $_CB_framework->saveCmsEditorJS( 'introtext' );
		        echo $_CB_framework->saveCmsEditorJS( 'fulltext' );
		    } elseif ($popup == 'modulewindow') {
		    	$_CB_framework->saveCmsEditorJS( 'content' );
		    }
		    ?>
		    window.open('<?php echo $_CB_framework->backendUrl( "index.php?pop=/$popup.php&t=$cur_template", true, 'component' ); ?>', 'win1', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');
		}
		<?php
		$cbjavascript	=	ob_get_contents();
		ob_end_clean();
		$_CB_framework->document->addHeadScriptDeclaration( $cbjavascript );

		echo CBtoolmenuBar::_output( 'popup();', 'preview', 'Preview' );	// CBTxt::T("....") done in _output
    }

    /**
	* Writes a preview button for a given option (opens a popup window)
	* @param string The name of the popup file (excluding the file extension for an xml file)
	* @param boolean Use the help file in the component directory
	*/
    static function help( $ref, $com=false ) {
    	global $_CB_framework;

        $image		=	cbMenuBarBase::ImageCheckAdmin( 'help.png', '/administrator/images/', NULL, NULL, CBTxt::T('Help'), 'help' );
        $image2		=	cbMenuBarBase::ImageCheckAdmin( 'help_f2.png', '/administrator/images/', NULL, NULL, CBTxt::T('Help'), 'help', 0 );
        $live_site	=	$_CB_framework->getCfg( 'live_site' );
        $rootpath	=	$_CB_framework->getCfg( 'absolute_path' );
        /*$helpUrl = mosGetParam( $GLOBALS, 'mosConfig_helpurl', '' );
        if ($helpUrl) {
        $url = $_CB_framework->backendUrl( $helpUrl . '/index.php?option=com_content&task=findkey&pop=1&keyref=' . urlencode( $ref ) );
        } else {*/
        $option = $GLOBALS['option'];
        if (substr($option,0,4) != 'com_') $option = "com_$option";
        $component = substr($option, 4);
        if ($com) {
            $url = '/administrator/components/' . $option . '/help/';
        }else{
            $url = '/help/';
        }
        $ref = $component.'.'.$ref . '.html';
        $url .= $ref;

        if (!file_exists($rootpath.'/help/'.$ref)) return false;
        $url = $live_site . $url;

        $onClickJs	=	"window.open('$url', 'mambo_help_win', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');";
        echo CBtoolmenuBar::_output( $onClickJs, 'help', 'Help' );	// CBTxt::T("....") done in _output
        /*}*/
    }

    /**
	* Writes a save button for a given option
	* Apply operation leads to a save action only (does not leave edit mode)
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function apply( $task='apply', $alt=null, $inlineJs=true  ) {
        CBtoolmenuBar::addToToolBar($task, $alt, 'Apply', 'apply', false, '', '', $inlineJs );		// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a save button for a given option
	* Save operation leads to a save and then close action
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function save( $task='save', $alt=null, $inlineJs=true ) {
        CBtoolmenuBar::addToToolBar($task, $alt, 'Save', 'save', false, '', '', $inlineJs );		// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a save button for a given option (NOTE this is being deprecated)
	*/
    static function savenew() {
        CBtoolmenuBar::addToToolBar($task, $alt, 'Save', 'savenew');	// CBTxt::T("....") done in addToToolBar		//should be save image
    }

    /**
	* Writes a save button for a given option (NOTE this is being deprecated)
	*/
    static function saveedit() {
        CBtoolmenuBar::addToToolBar($task, $alt, 'Save', 'saveedit');		// CBTxt::T("....") done in addToToolBar	//should be save image
    }

    /**
	* Writes a cancel button and invokes a cancel operation (eg a checkin)
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    static function cancel( $task='cancel', $alt=null, $inlineJs=true ) {
        CBtoolmenuBar::addToToolBar($task, $alt, 'Cancel', 'cancel', false, '', '', $inlineJs );
    }

    /**
	* Writes a cancel button that will go back to the previous page without doing
	* any other operation
	*/
    static function back( $alt = null, $href = '' ) {
        if ( is_null( $alt ) ) {
        	$alt 		= 'Back';
        }
        if ( $href ) {
            $link		=	$href;
            $onClickJs	=	null;
        } else {
        	$link		=	'#';
            $onClickJs	=	'window.history.go(-1);return false;';
        }
        echo CBtoolmenuBar::_output( $onClickJs, 'back', $alt, $link );		// CBTxt::T("....") done in _output
    }

    /**
	* Write a divider between menu buttons
	*/
    static function divider() {
        $image = cbMenuBarBase::ImageCheckAdmin( 'menu_divider.png', '/administrator/images/' );
		?>
		<span class="cbtoolbardivider">
		<?php echo $image; ?>
		</span>
		<?php
    }

    /**
	* Writes a media_manager button
	* @param string The sub-drectory to upload the media to
	*/
    static function media_manager( $directory = '', $alt=null ) {
    	global $_CB_framework;

        if ( is_null( $alt ) ) {
        	$alt		=	'Upload';
        }
        $cur_template = CBtoolmenuBar::getTemplate();
        $image = cbMenuBarBase::ImageCheckAdmin( 'upload.png', '/administrator/images/', NULL, NULL, CBTxt::T('Upload Image'), 'uploadPic' );
        // $image2 = cbMenuBarBase::ImageCheckAdmin( 'upload_f2.png', '/administrator/images/', NULL, NULL, CBTxt::T('Upload Image'), 'uploadPic', 0 );

        $onClickJs	=	"popupWindow('" . $_CB_framework->backendUrl( "index.php?pop=uploadimage.php&directory=$directory&t=$cur_template", true, 'component' ) . "','win1',350,100,'no');";
      	echo CBtoolmenuBar::_output( $onClickJs, $image, $alt );	// CBTxt::T("....") done in addToToolBar
    }

    /**
	* Writes a spacer cell
	* @param string The width for the cell
	*/
    static function spacer( $width='' )
    {
        if ($width != '') {
?>
			<span class="cbtoolbarspacer" style="width:<?php echo $width;?>;">&nbsp;</span>
<?php
        } else {
?>
			<span class="cbtoolbarspacer">&nbsp;</span>
<?php
        }
    }

    /**
	* Writes the end of the menu bar table
	*/
    static function endTable() {
		echo '</div>';
    }
	/**
	* Checks to see if an image exists in the current templates image directory
 	* if it does it loads this image.  Otherwise the default image is loaded.
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*/
	static function ImageCheckAdmin( $file, $directory='/administrator/images/', $param=NULL, $param_directory='/administrator/images/', $alt=NULL, $name=NULL, $type=1, $align='middle' ) {
		global $_CB_framework;

		$live_site		=	$_CB_framework->getCfg( 'live_site' );
		$mainframe		=&	$_CB_framework->_baseFramework;
		$cur_template 	=	$mainframe->getTemplate();
// ECHO $_CB_framework->getCfg( 'absolute_path' ) . '/administrator/templates/' . $cur_template . '/images/' . $file;
		if ( $param ) {
			$image		=	$live_site . $param_directory . $param;
		} else {
			if ( file_exists($_CB_framework->getCfg( 'absolute_path' ) . '/administrator/templates/' . $cur_template . '/images/' . $file ) ) {
				$image	=	$live_site . '/administrator/templates/' . $cur_template . '/images/' . $file;
			}
			else $image	=	$live_site . $directory . $file;
		}
		// outputs actual html <img> tag
		if ( $type ) {
			$image		=	'<img src="'. $image .'" alt="'. $alt .'" align="'. $align .'" name="'. $name .'" border="0" />';
		}
		return $image;
	}
}
class CBtoolmenuBar extends cbMenuBarBase {
	/**
	* Writes the common $action icon for the button bar
	* @param string url link
	* @param string action (for displaying correct icon))
	* @param string An override for the alt text
	*/
	function linkAction( $action='new', $link='', $alt='New' ) {
		if ( cbStartOfStringMatch( $link, 'javascript:' ) ) {
			$href	=	'#';
			$onClickJs	=	substr( $link, 11 );
		} else {
			$href		=	$link;
			$onClickJs	=	null;
		}
		echo CBtoolmenuBar::_output( $onClickJs, $action, $alt, $href );	// CBTxt::T("....") done in _output
	}
	/**
	* Writes a common 'edit' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
	function editListNoSelect( $task='edit', $alt='Edit' ) {
        // $listprompt = CBTxt::T('Please select an item from the list to edit');
        $listprompt		=	'';
        CBtoolmenuBar::addToToolBar($task, $alt, 'Edit', 'edit', false, $listprompt);	// CBTxt::T("....") done in addToToolBar
    }
}

class TOOLBAR_usersextras {
	/**
	* Draws the menu for a New users
	*/
	static function _NEW() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save( 'save', null, false );
		CBtoolmenuBar::cancel('showusers', null, false );
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}
	/** Edit user */
	static function _EDIT() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save( 'save', null, false );
		CBtoolmenuBar::cancel('showusers', null, false );
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _NEW_TAB() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('saveTab');
		CBtoolmenuBar::cancel('showTab');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _EDIT_TAB() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('saveTab');
		CBtoolmenuBar::cancel('showTab');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _DEFAULT_TAB() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::custom( 'newTab', 'new.png', 'new_f2.png', 'New Tab', false );
		CBtoolmenuBar::editList('editTab');
		CBtoolmenuBar::deleteList( CBTxt::T('The tab will be deleted and this cannot be undone!'),'removeTab');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _NEW_FIELD() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('saveField');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::apply('applyField');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::cancel('showField');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _EDIT_FIELD() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('saveField');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::apply('applyField');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::cancel('showField');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _DEFAULT_FIELD() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::custom( 'newField', 'new.png', 'new_f2.png', 'New Field', false );
		CBtoolmenuBar::editList('editField');
		CBtoolmenuBar::deleteList( CBTxt::T('The Field and all user data associated to this field will be lost and this cannot be undone!'), 'removeField');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _NEW_LIST() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('saveList');
		CBtoolmenuBar::cancel('showLists');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _EDIT_LIST() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('saveList');
		CBtoolmenuBar::cancel('showLists');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _DEFAULT_LIST() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::custom( 'newList', 'new.png', 'new_f2.png', 'New List', false );
		CBtoolmenuBar::editList('editList');
		CBtoolmenuBar::deleteList( CBTxt::T('The selected List(s) will be deleted and this cannot be undone!'), 'removeList');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _EDIT_CONFIG() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('saveconfig');
		CBtoolmenuBar::cancel();
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _DEFAULT() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::addNew();
		CBtoolmenuBar::editList();
		CBtoolmenuBar::custom( 'emailusers', 'mail.png', 'mail.png', 'Mass Mail', false );
		CBtoolmenuBar::deleteList();
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _EMAIL_USERS() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::addToToolBar( 'startemailusers', 'Send Mails', 'Send Mails', 'mail', false, '', '', false);
		//CBtoolmenuBar::custom( 'startemailusers', 'mail.png', 'mail.png', 'Send Mails', false );
		CBtoolmenuBar::cancel('showusers', null, false );
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}

	static function _EDIT_PLUGIN() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('savePlugin');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::apply('applyPlugin');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::cancel( 'cancelPlugin', 'Close' );
		/*
		if ( $id ) {
			// for existing content items the button is renamed `close`
			CBtoolmenuBar::cancel( 'cancelPlugin', 'Close' );
		} else {
			CBtoolmenuBar::cancel('showPlugins');
		}
		*/
		CBtoolmenuBar::endTable();
	}

	static function _PLUGIN_ACTION_SHOW() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::cancel( 'cancelPluginAction', 'Close' );
		CBtoolmenuBar::endTable();
	}

	static function _PLUGIN_ACTION_EDIT() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::save('savePlugin');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::apply('applyPlugin');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::cancel( 'cancelPluginAction', 'Close' );
		/*
		if ( $id ) {
			// for existing content items the button is renamed `close`
			CBtoolmenuBar::cancel( 'cancelPlugin', 'Close' );
		} else {
			CBtoolmenuBar::cancel('showPlugins');
		}
		*/
		CBtoolmenuBar::endTable();
	}

	static function _PLUGIN_MENU( &$xmlToolbarMenuArray ) {
		if ( $xmlToolbarMenuArray && ( count( $xmlToolbarMenuArray ) > 0 ) ) {
			CBtoolmenuBar::startTable();
			foreach ( $xmlToolbarMenuArray as $xmlTBmenu ) {
				if ( $xmlTBmenu && ( count( $xmlTBmenu->children() ) > 0 ) ) {
					foreach ( $xmlTBmenu->children() as $menu ) {
						if ( $menu->name() == 'menu' ) {
							// $name			=	$menu->attributes( 'name' );
							$action			=	$menu->attributes( 'action' );
							$task			=	$menu->attributes( 'task' );
							$label			=	$menu->attributes( 'label' );
							// $description	=	$menu->attributes( 'description' );

							if ( in_array( $action, get_class_methods( 'CBtoolmenuBar' ) ) || in_array( strtolower( $action ), get_class_methods( 'CBtoolmenuBar' ) ) ) {		// PHP 5 || PHP 4
								switch ( $action ) {
									case 'custom':
									case 'customX':
										$icon		=	$menu->attributes( 'icon' );
										$iconOver	=	$menu->attributes( 'iconover' );
										CBtoolmenuBar::$action( $task, $icon, $iconOver, $label, false );
										break;
									case 'editList':
										CBtoolmenuBar::editListNoSelect( $task, $label );
										break;
									case 'deleteList':
									case 'deleteListX':
										$message	=	$menu->attributes( 'message' );
										CBtoolmenuBar::$action( $message, $task, $label );
										break;
									case 'trash':
										CBtoolmenuBar::$action( $task, $label, false );
										break;
									case 'preview':
										$popup	=	$menu->attributes( 'popup' );
										CBtoolmenuBar::$action( $popup, true );
										break;
									case 'help':
										$ref	=	$menu->attributes( 'ref' );
										CBtoolmenuBar::$action( $ref, true );
										break;
									case 'savenew':
									case 'saveedit':
									case 'divider':
									case 'spacer':
										CBtoolmenuBar::$action();
										break;
									case 'back':
										$href	=	$menu->attributes( 'href' );
										CBtoolmenuBar::$action( $label, $href );
										break;
									case 'media_manager':
										$directory	=	$menu->attributes( 'directory' );
										CBtoolmenuBar::$action( $directory, $label );
										break;
									case 'linkAction':
										$urllink	=	$menu->attributes( 'urllink' );
										CBtoolmenuBar::$action( $task, $urllink, $label );
										break;
									default:
										CBtoolmenuBar::$action( $task, $label );
										break;
								}

							}
							// if ( in_array( $action, array(	'customX', 'addNew', 'addNewX', 'publish', 'publishList', 'makeDefault', 'assign', 'unpublish', 'unpublishList',
							//								'archiveList', 'unarchiveList', ) ) ) {
								// nothing
							// }
						}
					}
				}
			}
			CBtoolmenuBar::endTable();
		}
	}

	static function _DEFAULT_PLUGIN_MENU() {
		global $_CB_framework;

		CBtoolmenuBar::startTable();
		CBtoolmenuBar::linkAction( 'cancel', $_CB_framework->backendUrl( 'index.php?option=com_comprofiler&task=showPlugins' ), 'Close' );
		CBtoolmenuBar::endTable();
	}

	static function _DEFAULT_PLUGIN() {
		CBtoolmenuBar::startTable();
		CBtoolmenuBar::publishList('publishPlugin');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::unpublishList('unpublishPlugin');
		// CBtoolmenuBar::spacer();
		// CBtoolmenuBar::   "addInstall" link ('newPlugin');
/*
		CBtoolmenuBar::spacer();
		if (is_callable(array("CBtoolmenuBar","addNewX"))) {		// Mambo 4.5.0 support:
			CBtoolmenuBar::addNewX('newPlugin');
		} else {
			CBtoolmenuBar::addNew('newPlugin');
		}
*/
		CBtoolmenuBar::spacer();
		if (is_callable(array("CBtoolmenuBar","editListX"))) {		// Mambo 4.5.0 support:
			CBtoolmenuBar::editListX('editPlugin');
		} else {
			CBtoolmenuBar::editList('editPlugin');
		}
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::deleteList('','deletePlugin');
		CBtoolmenuBar::spacer();
		CBtoolmenuBar::endTable();
	}
}
?>