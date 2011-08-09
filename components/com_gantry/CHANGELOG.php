<?php
/**
 * CHANGELOG
 *
 * @package		gantry
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>

1. Copyright and disclaimer
----------------


2. Changelog
------------
This is a non-exhaustive changelog for Gantry, inclusive of any alpha, beta, release candidate and final versions.

Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

------- 3.1.10 Release [5-Mar-2011] -------
# Added versioning of admin cache to prevent upgrade issues

------- 3.1.9 Release [03-Mar-2011] -------
# Fixes for compatibility with Nooku Server
# Fixed issue with inline-javascript causing failure in administrator
# Fixed showmax field that was cosmetically incorrect in some situations
^ Changed default setting for inline-javascript to off
+ Added configurable grid support to allow 9 grid configuration

------- 3.1.8 Release [14-Feb-2011] -------
# Fixed an issue with page suffixes not working correctly
+ Added preliminary Android Support.
^ Let scripts and styles be added to the document after the finalize

------- 3.1.7 Release [31-Jan-2011] -------
^ Admin side caching of positions
# Fixed issue with non-default position layouts getting reset on save
# Fixed page suffix feature
# Fixed broken path reference to jsinit javascript (Fusion Menu)
+ Added RequestVars feature to put the main Joomla request vars as body classes

------- 3.1.6 Release [30-Dec-2010] -------
# Fixed to allow gantry init from component
# Fix for presets
# Better cookie path handling
# Fixed typo in right/left pulls - Thanks jwg
+ Updated Google fonts - Thanks Vikram Rao

------- 3.1.5 Release [1-Dec-2010] -------
# Fix for Line endings in gzipped JS files
+ Added countSubPositionModules function to gantry.
+ Added rt-alpha and rt-omega classes to mainbody and sidebar for ordered layout
# Fix for more missing mb_regex functions

------- 3.1.4 Release [12-Nov-2010] -------
# Fixed bug in gantry admin ajax

------- 3.1.3 Release [10-Nov-2010] -------
# Fixed bug in overlay element with bad gantry function usage.
^ Cleaned up mainframe instances.
# Fix for phpQuery bug when mbstring enabled but mbregex is not

------- 3.1.2 Release [04-Nov-2010] -------
+ Added Ordered MainBody renderer and functions to support

------- 3.1.1 Release [03-Nov-2010] -------
^ Changed method for detecting mootools version
^ Changed Cache classnames for less conflict

------- 3.1.0 Release [01-Nov-2010] -------
# Fixed More Articles issue in mt1.2 version when Joomla! Cache enabled
# Slow JavaScript fix for IE8, now it loads much faster
^ Toggles optimization, 52x faster
^ mooRainbow optimization, 20x faster
^ DOM Manipulation moved from JS to PHP
+ Implemented AJAX Saving, managing template admin is now much faster
^ Gantry Admin moved to MooTools v1.3
^ Consolidated caching
+ Added Inline JS to consolidate in 1 file the whole JS of admin
+ Added admin side cache
^ Moved to SimpleXMLElement for templateDetails.xml parsing

------- 3.0.12 Release [01-Oct-2010] -------
# Fixed gantry-input checkboxes not being checked properly
^ Optimizations in admin rendering, some speed gained.

------- 3.0.11 Release [05-Sep-2010] -------
* Security fix for SQL injecttion in modules ajax.
# Added addslashes to morearticles

------- 3.0.10 Release [01-Sep-2010] -------
# Fix for morearticles feature when getting the leads articles number
# Fix for template based -override style files.
+ Added Minefield to the list of Browsers

------- 3.0.9 Release [17-Aug-2010] -------
+ Added RokNavMenu Support
# Fixes and tweaks

------- 3.0.8 Release [10-Aug-2010] -------
# Made transparent functionality in colorchooser as an option
# Fix for mootools 12 overlays
# Fix for 'transparent' trunked out to 'transpa' for colorchooser
# Fix for html issue in admin text fields

------- 3.0.7 Release [31-Jul-2010] -------
# Fix for multiple RokNavMenu themes on a single page
# Updated to latest Google Analytics JavaScript code and output
# Fix for gantry inputs fields JavaScript
# Fix for pill-animation JavaScript
^ Fixes and updates for ColorPicker widget
+ New transparent option for ColorPicker widget

------- 3.0.6 Release [09-Jul-2010] -------
# Fix for unpublished menu items configs not being save/shown
# Fix for disapearing positions when forced in rtl mode
# Fixed css for focused text inputs
^ Updated some name changes to webfonts
+ Added priorities in for css scripts
+ Added template overrides for chain, grouped param, and grouped selection elements

------- 3.0.5 Release [05-Jul-2010] -------
# Fixed php4 issues with section and category elements

------- 3.0.4 Release [01-08-2010] -------
+ Added spider bite venom
# Changed foot color
# Moved browser check generation  to browser class
+ Added gantry_addScript, gantry_addStyle, gantry_addInlineScript and gantry_addInlineStyle functions for admin side browser css/js overrides
+ Added More Articles feature for Ajax-Loading more articles in replacement of Pagination
# Fixed smartload XPath exclusions not working properly
+ Added template overrides for chain, grouped param, and grouped selection elements
^ Changed the way SmartLoad sets sizes to the images
+ Added switcher and logic for loading MooTools 1.11 or MooTools 1.2
+ Added Platform class

------- 3.0.3 Release [12-Jun-2010] -------
+ Added admin view for Custom Menu Items with ability to erase and reset
# Fix to remove empty custom menu params ini files
+ Added layput engine to browser type and css/js/template file detection
# Fixed manifest for missing base dirs.

------- 3.0.2 Release [08-Jun-2010] -------
# Fix for versioncheck element when cURL is not installed into the system.

------- 3.0.1 Release [31-May-2010] -------
+ Added option to exclude via component names and CSS rules where to not use SmartLoad
# Fix for params not working properly when new Version Check element not present.
# Fix for addScript including template and gantry scripts
# Fix for iPhone menu cache issue.


------- 3.0.0 Release [30-May-2010] -------
+ Added SmartLoad feature
+ Added Aliases element
+ Added Gradient & Gradient Preview element
+ Added Google Web Font support
+ Added Version Check element
+ Added image resizer feature for iPhone
+ Added iPhone support
+ Added per platform template page support
+ Added support for dynamic css and js files with query string
+ Added support for mootools 1.1.2 in older joomla versions (<= 1.5.14)
# Fix for php4 with gangtryurl.class and gantry.class
# Fix for Multiple copies of template showing up in uninstaller
# Fix for getParams not pulling cookie/session/menuitem/url modified params
# Fix for multiple includes of FlatFile
# Fix for caching dynamic params
# Fix for disabled fields do not reset to defaults
# Fix for default font stacks
# Fix for delete icon of presets for early templates
# Fix for splitmenu layout

------- 2.5.6 Release [12-April-2010] -------
^ Cleaned up run protects to use GANTRY_VERSION
^ Moved to GantryXML instead of Joomla one
# Fix of getCurrentUrl to work better with other components
# Fix to Gantry GZipper to work in with unwriteable css and cache dirs

------- 2.5.4 Release [12-April-2010] -------
# Fixed Per Menu Items saving
^ Added check on feature render to make sure function exists
# Fixed addStyle on full url passing

------- 2.5.3 Release [07-April-2010] -------
^ Cleaned up whats being cached
+ Added support for menuless items to pick up all params of the assigned menu item
# Fixed caching for multiple Gantry templates in the same joomla instance
# Fixed escaped version number
+ Added gantry version on diagnostic panel header
# Fixed one position issue
# Language updates to remove special characters
# Fix for content-top and content-bottom when odd mainbody
+ Added 3 grid option
# Added iPad support

------- 2.5.1 Release [09-March-2010] -------
# Minor bug fixes

------- 2.5.0 Release [22-February-2010] -------
+ New version naming

------- 2.1.0 Release [22-February-2010] -------
+ Broke out into separate libraries

------- 2.0.12 Release [12-February-2010] -------
# Fixed module collapsing

------- 2.0.11 Release [12-February-2010] -------
+ Added inherited menu item parameters
# Fixed forced layout positions.

------- 2.0.10 Release [4-February-2010] -------
# Fixed gzipper to only work if directories are writeable

------- 2.0.3 Release [10-January-2010] -------
# Fixed menuitem elements display on no menu item defined
# Fixed ajax-save issue when template is not default and default template is not a gantry template
# Fixed menu item parameter preference to not be saved in cookie and session
+ Added setby location to parameters to determine where a parameter was set by.

------- 2.0.2 Release [05-January-2010] -------
^ Moved layouts to individual layout classes to allow override
# Fix to show sidebars when component not defined
^ Moved positions cache files to a flatfile db
+ Added check to make sure presets don't get saved in cookies or sessions
+ Added spinner for Apply/Save


------- 2.0.1 Release [02-January-2010] -------
# Fixed per Menu Item saving
# JS fixes for Menu item saving and IE
+ Added copy of language files to Joomla locations if not there

------- 2.0 Release [01-January-2010] -------
+ Per-Menu configuration
+ Custom presets
+ 16 column support added
+ RTL Support (right-to-left languages)
+ Diagnostic status
+ Built-in AJAX support
+ Built-in Gantry GZipper
+ Feature order
+ Component display toggle
+ Page Suffix feature
+ Menu-less pages feature

------- 1.2 Release [11-December-2009] -------
! Changelog Creation