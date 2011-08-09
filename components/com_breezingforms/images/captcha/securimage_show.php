<?php

/**
 * Project:     Securimage: A PHP class for creating and managing form CAPTCHA images<br />
 * File:        securimage.php<br />
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or any later version.<br /><br />
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.<br /><br />
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA<br /><br />
 *
 * Any modifications to the library should be indicated clearly in the source code
 * to inform users that the changes are not a part of the original software.<br /><br />
 *
 * If you found this script useful, please take a quick moment to rate it.<br />
 * http://www.hotscripts.com/rate/49400.html  Thanks.
 *
 * @link http://www.phpcaptcha.org Securimage PHP CAPTCHA
 * @link http://www.phpcaptcha.org/latest.zip Download Latest Version
 * @link http://www.phpcaptcha.org/Securimage_Docs/ Online Documentation
 * @copyright 2009 Drew Phillips
 * @author drew010 <drew@drew-phillips.com>
 * @version 2.0 BETA (November 15, 2009)
 * @package Securimage
 *
 */

include 'securimage.php';

$img = new securimage();

//Change some settings

$img->image_width = 230;
$img->image_height = 80;
$img->perturbation = 0.75; // 1.0 = high distortion, higher numbers = more distortion
$img->image_bg_color = new Securimage_Color(0xe3, 0xda, 0xed); // e3daed
$img->text_color = new Securimage_Color(0xff, 0x00, 0x00);
$img->text_transparency_percentage = 15; // 100 = completely transparent
$img->num_lines = 8;
$img->code_length = 5;
$img->line_color = new Securimage_Color(0x80, 0xbf, 0xff);
$img->signature_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
$img->image_type = SI_IMAGE_PNG;

/// set to true if no TTF support

$img->use_gd_font  = false;
$img->gd_font_file = JPATH_SITE . '/components/com_breezingforms/images/captcha/gdfonts/bubblebath.gdf';

//////////////////

if($img->use_gd_font)
{
	$img->text_color = '#ff0000';
}

$img->show(''); // alternate use:  $img->show('/path/to/background_image.jpg');
