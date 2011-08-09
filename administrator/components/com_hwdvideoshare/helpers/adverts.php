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

global $smartyvs, $show_video_ad, $show_longtail, $longtail_channel, $mainframe;

$smartyvs->assign("print_ads", 1);

require_once(JPATH_SITE .'/components/com_hwdrevenuemanager/hwdrevenuemanager.class.php');
hwd_vs_adverts::grabVideoAdverts();
hwd_vs_adverts::grabTextAdverts();
hwd_vs_adverts::grabLongTailAdverts();
?>