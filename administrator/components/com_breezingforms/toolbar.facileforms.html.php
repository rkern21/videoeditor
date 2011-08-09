<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.4.4
* @package BreezingForms
* @copyright (C) 2004-2005 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class menuFacileForms {

	function button($value, $disaction, $action, $button='')
	{
		echo '<span style="margin:1px;padding:2px 4px 2px 4px;color:#';
		if ($action!=$disaction) {
			echo '000000;border:1px outset;"'.
				 ' onclick="';
			if ($action) echo 'document.adminForm.act.value=\''.$action.'\'; ';
			echo 'submitbutton(\''.$button.'\');">';
		} else
			echo '707070;border:1px inset;">';
		echo $value.'</span>';
	} // button

	function buttons($action)
	{
		echo '<span style="background-color:#f4f4f4;font-weight:bold;">';
		menuFacileForms::button(BFText::_('COM_BREEZINGFORMS_TOOLBAR_MANAGERECS'),    $action, 'managerecs'    );
		menuFacileForms::button(BFText::_('COM_BREEZINGFORMS_TOOLBAR_MANAGEMENUS'),   $action, 'managemenus'   );
		menuFacileForms::button(BFText::_('COM_BREEZINGFORMS_TOOLBAR_MANAGEFORMS'),   $action, 'manageforms'   );
		menuFacileForms::button(BFText::_('COM_BREEZINGFORMS_TOOLBAR_MANAGESCRIPTS'), $action, 'managescripts' );
		menuFacileForms::button(BFText::_('COM_BREEZINGFORMS_TOOLBAR_MANAGEPIECES'),  $action, 'managepieces'  );
		menuFacileForms::button(BFText::_('COM_BREEZINGFORMS_TOOLBAR_CONFIGURATION'), $action, '', 'config'    );
		echo '</span>';
	} // buttons

	function MANAGERECS_MENU()    { menuFacileForms::buttons('managerecs');    }

	function MANAGEMENU_MENU()    { menuFacileForms::buttons('managemenus');   }

	function MANAGEFORM_MENU()    { menuFacileForms::buttons('manageforms');   }

	function MANAGESCRIPTS_MENU() { menuFacileForms::buttons('managescripts'); }

	function MANAGEPIECES_MENU()  { menuFacileForms::buttons('managepieces');  }

	function EDITPAGE_MENU()      { menuFacileForms::buttons('none');          }

	function INSTPACKAGE_MENU() {
		
		JToolBarHelper::custom('uploadpackage',   'upload.png', 'upload_f2.png', BFText::_('COM_BREEZINGFORMS_TOOLBAR_INSTPKG'),   false);
		JToolBarHelper::custom('delpkgs', 'delete.png', 'delete_f2.png', BFText::_('COM_BREEZINGFORMS_TOOLBAR_UINSTPKGS'), false);
		JToolBarHelper::custom('edit',    'cancel.png', 'cancel_f2.png', BFText::_('COM_BREEZINGFORMS_TOOLBAR_CANCEL'),    false);
		
	}
} // menuFacileForms

?>