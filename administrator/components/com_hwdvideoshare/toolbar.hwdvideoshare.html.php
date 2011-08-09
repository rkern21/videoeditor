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

class hwdvidmenu
{
	function HOMEPAGE_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function VIDEO_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::publishList();
		JToolBarHelper::spacer();
		JToolBarHelper::unpublishList();
		JToolBarHelper::spacer();
		JToolBarHelper::custom('feature', 'publish.png', 'publish_f2.png', _HWDVIDS_TOOLBAR_FEATURE, false);
		JToolBarHelper::spacer();
		JToolBarHelper::custom('unfeature', 'unpublish.png', 'unpublish_f2.png', _HWDVIDS_TOOLBAR_UNFEATURE, false);
		JToolBarHelper::spacer();
		JToolBarHelper::editListX('editvids', _HWDVIDS_TOOLBAR_EDIT);
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList(_HWDVIDS_INFO_CONFIRMBACKDEL, 'delete', _HWDVIDS_TOOLBAR_REMOVE);
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function EDITVID_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::save('savevid');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('cancelvid');
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function CAT_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::custom('publishcat', 'publish.png', 'publish_f2.png', _HWDVIDS_TOOLBAR_PUBLISH, false);
		JToolBarHelper::spacer();
		JToolBarHelper::custom('unpublishcat', 'unpublish.png', 'unpublish_f2.png', _HWDVIDS_TOOLBAR_UNPUBLISH, false);
		JToolBarHelper::spacer();
		JToolBarHelper::addNewX('newcat');
		JToolBarHelper::spacer();
		JToolBarHelper::editListX('editcat', _HWDVIDS_TOOLBAR_EDIT);
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList(_HWDVIDS_INFO_CONFIRMBACKCDEL, 'deletecat', _HWDVIDS_TOOLBAR_REMOVE);
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function EDITCAT_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::save('savecat');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('cancelcat');
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function GROUPS_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::custom('publishg', 'publish.png', 'publish_f2.png', _HWDVIDS_TOOLBAR_PUBLISH, false);
		JToolBarHelper::spacer();
		JToolBarHelper::custom('unpublishg', 'unpublish.png', 'unpublish_f2.png', _HWDVIDS_TOOLBAR_UNPUBLISH, false);
		JToolBarHelper::spacer();
		JToolBarHelper::editListX('editgrp', _HWDVIDS_TOOLBAR_EDIT);
		JToolBarHelper::spacer();
		JToolBarHelper::custom('featureg', 'publish.png', 'publish_f2.png', _HWDVIDS_TOOLBAR_FEATURE, false);
		JToolBarHelper::spacer();
		JToolBarHelper::custom('unfeatureg', 'unpublish.png', 'unpublish_f2.png', _HWDVIDS_TOOLBAR_UNFEATURE, false);
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList(_HWDVIDS_INFO_CONFIRMBACKGDEL, 'deletegroups', _HWDVIDS_TOOLBAR_DELETE);
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function EDITGRP_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::save('savegrp');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('cancelgrp');
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function APPROVE_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::back();
		JToolBarHelper::spacer();
		JToolBarHelper::custom('approve', 'publish.png', 'publish_f2.png', _HWDVIDS_TOOLBAR_APPROVE, false);
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList(_HWDVIDS_INFO_CONFIRMBACKDEL, 'delete', _HWDVIDS_TOOLBAR_REMOVE);
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function SSETTINGS_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::save('saveserver');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('cancel');
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function GSETTINGS_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::save('savegeneral');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('cancel');
		JToolBarHelper::spacer();
		JToolBarHelper::custom('restoreDefaults', 'restore.png', 'restore_f2.png', _HWDVIDS_TOOLBAR_RESTOREDEFAULTS, false);
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function LSETTINGS_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::save('savelayout');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('cancel');
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function MAINTENANCE_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::back();
		JToolBarHelper::spacer();
		JToolBarHelper::custom('runmaintenance', 'config.png', 'config_f2.png', _HWDVIDS_TOOLBAR_RUN, false);
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function INFO_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::back();
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function EXPORT_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::custom('botJombackup', 'archive.png', 'archive_f2.png', _HWDVIDS_TOOLBAR_BKUP, false);
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}

	function IMPORT_MENU()
	{
		global $j16;

        JToolBarHelper::title( _HWDVIDS_TOOLBARTITLE, 'logo' );
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();
		if ($j16)
		{
			JToolBarHelper::preferences('com_hwdvideoshare');
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom('homepage', 'help.png', 'help_f2.png', _HWDVIDS_TOOLBAR_HOME, false);
	}
}
?>