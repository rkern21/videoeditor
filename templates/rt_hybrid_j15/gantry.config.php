<?php
/**
 * @package   Hybrid Template - RocketTheme
 * @version   1.5.4 November 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Hybrid Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

$gantry_config_mapping = array(
    'belatedPNG' => 'belatedPNG',
	'ie6Warning' => 'ie6Warning'
);

$gantry_presets = array (
    'presets' => array (
        'preset1' => array (
            'name' => 'Preset 1',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header1',
			'bodystyle' => 'body1',
			'bodyaccent' => 'accent1',
			'footerstyle' => 'footer1',
            'font-family' => 'hybrid'
        ),

		'preset2' => array (
            'name' => 'Preset 2',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header2',
			'bodystyle' => 'body4',
			'bodyaccent' => 'accent2',
			'footerstyle' => 'footer2',
            'font-family' => 'hybrid'
        ),

		'preset3' => array (
            'name' => 'Preset 3',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header3',
			'bodystyle' => 'body2',
			'bodyaccent' => 'accent3',
			'footerstyle' => 'footer3',
            'font-family' => 'hybrid'
        ),

		'preset4' => array (
            'name' => 'Preset 4',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header4',
			'bodystyle' => 'body2',
			'bodyaccent' => 'accent4',
			'footerstyle' => 'footer4',
            'font-family' => 'hybrid'
        ),

		'preset5' => array (
            'name' => 'Preset 5',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header5',
			'bodystyle' => 'body3',
			'bodyaccent' => 'accent5',
			'footerstyle' => 'footer5',
            'font-family' => 'hybrid'
        ),

		'preset6' => array (
            'name' => 'Preset 6',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header6',
			'bodystyle' => 'body5',
			'bodyaccent' => 'accent6',
			'footerstyle' => 'footer6',
            'font-family' => 'hybrid'
        ),

		'preset7' => array (
            'name' => 'Preset 7',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header7',
			'bodystyle' => 'body4',
			'bodyaccent' => 'accent7',
			'footerstyle' => 'footer7',
            'font-family' => 'hybrid'
        ),

		'preset8' => array (
            'name' => 'Preset 8',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header8',
			'bodystyle' => 'body5',
			'bodyaccent' => 'accent8',
			'footerstyle' => 'footer8',
            'font-family' => 'hybrid'
        ),

		'preset9' => array (
            'name' => 'Preset 9',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header9',
			'bodystyle' => 'body3',
			'bodyaccent' => 'accent1',
			'footerstyle' => 'footer9',
            'font-family' => 'hybrid'
        ),

		'preset10' => array (
            'name' => 'Preset 10',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header10',
			'bodystyle' => 'body1',
			'bodyaccent' => 'accent4',
			'footerstyle' => 'footer10',
            'font-family' => 'hybrid'
        ),

		'preset11' => array (
		    'name' => 'Preset 11',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header11',
			'bodystyle' => 'body4',
			'bodyaccent' => 'accent7',
			'footerstyle' => 'footer11',
		    'font-family' => 'hybrid'
		),
		
		'preset12' => array (
		    'name' => 'Preset 12',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header12',
			'bodystyle' => 'body3',
			'bodyaccent' => 'accent8',
			'footerstyle' => 'footer12',
		    'font-family' => 'hybrid'
		),
		
		'preset13' => array (
		    'name' => 'Preset 13',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header11',
			'bodystyle' => 'body5',
			'bodyaccent' => 'accent4',
			'footerstyle' => 'footer7',
		    'font-family' => 'hybrid'
		),
		
		'preset14' => array (
		    'name' => 'Preset 14',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header8',
			'bodystyle' => 'body4',
			'bodyaccent' => 'accent8',
			'footerstyle' => 'footer12',
		    'font-family' => 'hybrid'
		),
		
		'preset15' => array (
		    'name' => 'Preset 15',
			'bodylevel' => 'high',
			'backgroundlevel' => 'high',
			'headerstyle' => 'header2',
			'bodystyle' => 'body2',
			'bodyaccent' => 'accent4',
			'footerstyle' => 'footer9',
		    'font-family' => 'hybrid'
		)

    )
);

$gantry_browser_params = array(
    'ie6' => array(
        'bodylevel' => 'low',
		'readonstyle' => 'button'
    )
);

$gantry_belatedPNG = array('.png', '#rt-logo', '#rt-header-bg', '#rt-header-overlay', '#rt-bottom-overlay', '#rt-feature', '#rt-lowerpanel', '.panel-control', '.panel-control span', '#rt-header', '.title1 .title', '.title2 .title', '.title3 .title', '.title4 .title', '.title .title', '#rocket', '.totop-desc', '.panel-control.open span', '.vertical-list-wrapper', '#form-login .inputbox', '.module-content ul.menu a', '.module-content ul.menu .separator', '.module-content ul.menu .item', '#more-articles span', '#form-login ul li a', '#com-form-login ul li a', 'ul.rt-more-articles li a', '.rt-section-list ul li a', 'ul.mostread li a', 'ul.latestnews li a', '.weblinks ul li a', '.roktabs-wrapper .arrow-next', '.roktabs-wrapper .arrow-prev', '#rt-popuplogin ul li a', '#rt-header-surround .vertical-list li', '#rt-header-surround .vertical-list li.active', '#rt-footerbar', '.quote-l', '.quote-r', '#breadcrumbs-home');

$gantry_ie6Warning = "<h3>IE6 DETECTED: Currently Running in Compatibility Mode</h3><h4>This site is compatible with IE6, however your experience will be enhanced with a newer browser</h4><p>Internet Explorer 6 was released in August of 2001, and the latest version of IE6 was released in August of 2004.  By continuing to run Internet Explorer 6 you are open to any and all security vulnerabilities discovered since that date.  In March of 2009, Microsoft released version 8 of Internet Explorer that, in addition to providing greater security, is faster and more standards compliant than both version 6 and 7 that came before it.</p> <br /><a class='external'  href='http://www.microsoft.com/windows/internet-explorer/?ocid=ie8_s_cfa09975-7416-49a5-9e3a-c7a290a656e2'>Download Internet Explorer 8 NOW!</a>";