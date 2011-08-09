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

function com_uninstall()
{
	$cachedir = JPATH_SITE.DS."cache".DS;
	$cacheext = 'cache';

	$page_today = 'http://xmlplaylists_today';
	$cachefile_today = $cachedir . md5($page_today) . '.' . $cacheext;

	$page_thisweek = 'http://xmlplaylists_thisweek';
	$cachefile_thisweek = $cachedir . md5($page_thisweek) . '.' . $cacheext;

	$page_thismonth = 'http://xmlplaylists_thismonth';
	$cachefile_thismonth = $cachedir . md5($page_thismonth) . '.' . $cacheext;

	$page_alltime = 'http://xmlplaylists_alltime';
	$cachefile_alltime = $cachedir . md5($page_alltime) . '.' . $cacheext;

	if (file_exists($cachefile_today)) { unlink($cachefile_today); }
	if (file_exists($cachefile_thisweek)) { unlink($cachefile_thisweek); }
	if (file_exists($cachefile_thismonth)) { unlink($cachefile_thismonth); }
	if (file_exists($cachefile_alltime)) { unlink($cachefile_alltime); }

    echo "<b>Component successfully uninstalled.</b>";
}

?>