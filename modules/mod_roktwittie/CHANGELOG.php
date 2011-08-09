<?php
/**
 * RokTwittie Module
 *
 * @package RocketTheme
 * @subpackage roktwittie
 * @version   2.0 October 1, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
?>

1. Copyright and disclaimer
----------------


2. Changelog
------------
This is a non-exhaustive changelog for RokTwittie, inclusive of any alpha, beta, release candidate and final versions.
Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note


----------- 2.0 Release [29-Sep-2010] -----------
29-Sep-2010 Juozas Kaziukenas
+ ReTweets support
+ Enabled CURL extension checks
^ Changed authentication to use OAuth
^ Caching using JCache
^ Prepopulating status updates using cache
! Refactored class 

----------- 1.0 Release [16-Aug-2009] -----------
16-Aug-2010 Djamil Legato
+ MooTools 1.2 compatibility

----------- 0.9 Release [15-Jan-2009] -----------

14-Jan-2010 Andy Miller
# Reverted $doc->baseurl to JURI::base(root) for CSS and JS

14-Jan-2010 Djamil Legato
# Rewrote the Search handler to work again with the latest Twitter Search API changes
# Rewrote the date handler for IE to work again with the latest Twitter Search API changes
# Fixed merged results for users tweets
# Updated "View All" link from the old "/friends" to the new "/following".

----------- 0.8 Release [08-Dec-2009] -----------

08-Dec-2009 Djamil Legato
^ New way to gather users tweets. Not relying on Twitter Search API anymore but directly on Twitter API. This new way guarantee a more reliable output.
+ Added "Merge" options. Based on the tweets count you want to show, if you have more than 1 user you can either merge the tweets so the global count is the one you have set or not merge, so every user is going to show as many tweets as your count setting.

----------- 0.7 Release [30-Nov-2009] -----------

30-Nov-2009 Djamil Legato
# Fixed the missing ABOUT language translation
# Fixed an issue that didn't let you show only the tweets, without statuses
# Modified the way the addStyleSheet and Script are output

----------- 0.6 Release [18-Nov-2009] -----------

18-Nov-2009 Brian Towles
# Added bug fix for PHP 5.3

----------- 0.5 Release [18-June-2009] -----------

18-Jun-2009 Djamil Legato
# Optional timeout when reaching Twitter.com (with low timeout, 5s)
# Removed escaped double quotes from the searching query
# Fixed parsing issue when user tweets disabled and search enabled

----------- 0.4 Release [09-June-2009] -----------

09-Jun-2009 Djamil Legato
# HTTPS fix

----------- 0.3 Release [05-June-2009] -----------

05-Jun-2009 Djamil Legato
# Bio containing amps caused RokTwittie to not validate.
# Clicking on avatars in updates and search took always to the same page, instead of taking in consideration the "Open in new window" option.
# Several CSS tweaks
+ You can now choose between a Light and Dark header style.
+ Improved caching

----------- 0.2 Release [02-June-2009] -----------

02-Jun-2009 Andy Miller
# Fixed XML error related to caching option

----------- 0.1 Release [31-May-2009] -----------

31-May-2009 Djamil Legato
! Initial release. 

----------- Initial Changelog Creation -----------