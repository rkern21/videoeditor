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
$c = hwd_vs_Config::get_instance();

if (substr($c->vsdirectory, -1) == "/")
{
	$c->vsdirectory = substr($c->vsdirectory, 0, -1);
}

// setup main directory location
if (!empty($c->vsdirectory))
{
	if (file_exists($c->vsdirectory))
	{
		define('PATH_HWDVS_DIR', $c->vsdirectory);

		define('PATH_HWDVS_DIR_REL', str_replace(JPATH_SITE.DS, "", $c->vsdirectory));
		if (PATH_HWDVS_DIR_REL !== $c->vsdirectory)
		{
			define('URL_HWDVS_DIR', JURI::root( false ).PATH_HWDVS_DIR_REL);
		}
		else
		{
			define('URL_HWDVS_DIR', 'UNKNOWN');
		}
	}
}

// setup defaults
if (!defined('URL_HWDVS_DIR'))
{
	define('URL_HWDVS_DIR', JURI::root( false )."hwdvideos");
}
if (!defined('PATH_HWDVS_DIR'))
{
	define('PATH_HWDVS_DIR', JPATH_SITE.DS."hwdvideos");
}

?>