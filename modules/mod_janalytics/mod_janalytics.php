<?php
/**
 * J!Analytics - Google Analytics Code module for Joomla v1.5
 *
 * @version     $Id: mod_janalytics.php 37 2010-11-06 05:11:14Z mofodojodino $
 * @author      Dean Tedesco <dino@tedesco.net.au>
 * @link        http://janalytics.tedesco.net.au
 * @copyright   Copyright (C) 2010 Dean Tedesco. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 * This file is part of J!Analytics.
 *
 * J!Analytics is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * J!Analytics is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with J!Analytics.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$tracking = $params->get('tracking', 'single');
$gaid = $params->get('gaid');
$domain = $params->get('domain', '.tedesco.net.au');
$anonymizeip = $params->get('anonymizeip', 'off');

if (!empty($gaid))
{
    require(JModuleHelper::getLayoutPath('mod_janalytics'));
}
?>
