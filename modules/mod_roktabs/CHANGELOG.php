<?php
/**
 * RokTabs Module
 *
 * @package		Joomla
 * @subpackage	RokTabs Module
 * @copyright Copyright (C) 2009 RocketTheme. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
 * @author RocketTheme, LLC
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>

1. Copyright and disclaimer
-------------


2. Changelog
------------
This is a non-exhaustive changelog for RokTabs, inclusive of any alpha, beta, release candidate and final versions.

Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

----------- 1.15 Release [-Sep-2010] -----------
+ added default readmore support.

----------- 1.14 Release [01-Sep-2010] -----------
+ added option to prefix tabs with the current tab position
+ added option to force roktabs not displaying arrows
+ added wrapping class when no arrows are detected

----------- 1.13 Release [30-Jun-2010] -----------
30-Jun-2010 Djamil Legato
+ Added support for MT 1.2

----------- 1.12 Release [09-Mar-2010] -----------
09-Mar-2010 Djamil Legato
# Fixed DS slash for URLs

----------- 1.10 Release [03-Mar-2010] -----------

03-Mar-2010 Brian Towles
# Fixed backwards compatibility with write_tabs
# Fixed missing admin and images dirs on install

----------- 1.9 Release [28-Feb-2010] -----------

17-Feb-2010 Andy Miller
+ Added basic icon support for tabs

----------- 1.8 Release [30-Jan-2010] -----------

30-Jan-2010 Andy Miller
# Changed URI to use JURI::root(true) so it works with RokGZipper

25-Jan-2010 Djamil Legato
# Suppressed a possible warning from the dispatcher

----------- 1.7 Release [24-Jan-2010] -----------

22-Jan-2010 Djamil Legato
^ Changed behaviour where the scrolling animation was in action, mouse wheeling stopped it
+ Added new option "Tabs Interaction" that allows to "Click" or "MouseOver" a tab in order to trigger it
+ Added "goTo" method for triggering specific tabs, ie: window.roktabs.goTo(0, 1); // will simulate a click of the second tab in the first roktab in the page.
# Fixed previous hook that wasn't working

----------- 1.6 Release [31-Dec-2009] -----------

31-Dec-2009 Djamil Legato
# Safari margins calculation fix

----------- 1.5 Release [25-June-2009] -----------

25-Jun-2009 Andy Miller
+ Added support for K2 content format
+ Added checks for K2 installation in the module params
+ Added more extensive ordering options

----------- 1.4 Release [16-June-2009] -----------

16-Jun-2009 Djamil Legato
# Option for taking into account some margins

----------- 1.3.3 Release [31-May-2009] -----------

31-May-2009 Djamil Legato
# Fixed Safari issue

----------- 1.3.2 Release [29-April-2009] -----------

29-Apr-2009 Djamil Legato
+ Adding RokTabs ordering feature

----------- 1.3.1 Release [02-April-2009] -----------

02-Apr-2009 Djamil Legato
# Various fixes

----------- 1.3 Release [02-April-2009] -----------

02-Apr-2009 Djamil Legato
! Initial release.

------------ Initial Changelog Creation -------------