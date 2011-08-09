<?php
// WARNING: No blank line or spaces before the "< ? p h p" above this.


// THIS IS ONLY A REFERENCE FILE FOR TRANSLATORS, NOT USED IN CB. THE FILE admin_language.php IS USED.


// IMPORTANT: This file should be made in UTF-8 (without BOM) only.
// CB will automatically convert to site's local character set.

/**
* Joomla/Mambo Community Builder
* @version $Id: admin_reference_language.php 1456 2011-02-13 23:39:34Z beat $
* @package Community Builder
* @subpackage Core CB Admin Language file (English)
* @since 1.2.2
* @author Beat
* @copyright (C) 2005 - 2010 www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file:
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }


// Important:
// Please check on Joomlapolis.com forum in CB Language translations subforum first before translating:
// 1) if there is a translation already done or on the way where you can cooperate
// 2) if a newer version of this reference file exists and for translation instructions
// 3) consider joining CB translations workgroup (again see forum for instructions)
// THIS IS ONLY A REFERENCE FILE FOR TRANSLATORS, NOT USED IN CB. THE FILE admin_language.php IS USED (but not needed in default language, so empty).


// 1.2.2 Stable: (new method: UTF8 encoding here):
CBTxt::addStrings( array(

// XML files:

// cb.authortab.xml
'Provides an User Tab that shows all articles written by the user.' => 'Provides an User Tab that shows all articles written by the user.',

// cb.connections.xml
// some also used in other files (php and xml)
'Provides CB Core Connections functionality' => 'Provides CB Core Connections functionality',
'Display Settings' => 'Display Settings',
'User Profile Status :' => 'User Profile Status :',
'see Field: Connections: Parameters' => 'see Field: Connections: Parameters',
'Connections Status Settings :' => 'Connections Status Settings :',
'Show Title' => 'Show Title',
'Show a title' => 'Show a title',
'Show Summary' => 'Show Summary',
'Shows a small number of connections with a link to see them all in paginated form.' => 'Shows a small number of connections with a link to see them all in paginated form.',
'Entries shown in Summary' => 'Entries shown in Summary',
'If Show Summary is enabled, this is the number of connections displayed. Otherwise, this is ignored. Default is 4.' => 'If Show Summary is enabled, this is the number of connections displayed. Otherwise, this is ignored. Default is 4.',
'Enable Paging' => 'Enable Paging',
'Allow entries to automatically page when they exceed the number per page limit.' => 'Allow entries to automatically page when they exceed the number per page limit.',
'Max entries shown or per Page' => 'Max entries shown or per Page',
'If paging is enabled, this is the number of connections per page. Otherwise, this is the number of connections to show. Default is 10.' => 'If paging is enabled, this is the number of connections per page. Otherwise, this is the number of connections to show. Default is 10.',

// cb.core.xml
// some also used in other files (php and xml)
'Core CB Tabs and Events.' => 'Core CB Tabs and Events.',
'Display description' => 'Display description',
'To display &quot;[edit photo]&quot;, type following into description: [menu style=&quot;color:green;&quot; caption=&quot;&amp;91;edit your photo&amp;93;&quot; img=&quot;&quot;] _UE_MENU_EDIT : _UE_UPDATEAVATAR [/menu]' => 'To display &quot;[edit photo]&quot;, type following into description: [menu style=&quot;color:green;&quot; caption=&quot;&amp;91;edit your photo&amp;93;&quot; img=&quot;&quot;] _UE_MENU_EDIT : _UE_UPDATEAVATAR [/menu]',
'User Profile Title text' => 'User Profile Title text',
'Page title text. Enter text to be displayed as profile page title. %s will be replaced by user-name depending on global settings. Or use language-dependant _UE_PROFILE_TITLE_TEXT (default)' => 'Page title text. Enter text to be displayed as profile page title. %s will be replaced by user-name depending on global settings. Or use language-dependant _UE_PROFILE_TITLE_TEXT (default)',
'Check Box (Single)' => 'Check Box (Single)',
'Check Box (Multiple)' => 'Check Box (Multiple)',
'Display on profiles as' => 'Display on profiles as',
'How to display the values of this multi-valued field' => 'How to display the values of this multi-valued field',
'Comma ","-separated line' => 'Comma ","-separated line',
'Unnumbered list "ul"' => 'Unnumbered list "ul"',
'Ordered list "ol"' => 'Ordered list "ol"',
'CSS class of the list' => 'CSS class of the list',
'Enter the name of the list class (optional) for OL or UL tag' => 'Enter the name of the list class (optional) for OL or UL tag',
'Integer Number' => 'Integer Number',
'Field entry validation' => 'Field entry validation',
'Minimum value allowed' => 'Minimum value allowed',
'Enter the minimum integer value allowed. Default is 0.' => 'Enter the minimum integer value allowed. Default is 0.',
'Maximum value allowed' => 'Maximum value allowed',
'Enter the maximum value allowed. Default is 1000000.' => 'Enter the maximum value allowed. Default is 1000000.',
'Forbidden values at registration' => 'Forbidden values at registration',
'You can set a list of values (separated by comma ,) which are not allowed in this field for registration.' => 'You can set a list of values (separated by comma ,) which are not allowed in this field for registration.',
'Forbidden values in user profile edits' => 'Forbidden values in user profile edits',
'You can set a list of values (separated by comma ,) which are not allowed in this field when user updates his profile in profile edits.' => 'You can set a list of values (separated by comma ,) which are not allowed in this field when user updates his profile in profile edits.',
'Authorized input' => 'Authorized input',
'Type of input authorized.' => 'Type of input authorized.',
'Any string ( /.*/ )' => 'Any string ( /.*/ )',
'Custom PERL regular expression' => 'Custom PERL regular expression',
'Perl Regular Expression' => 'Perl Regular Expression',
'Any string: /^.*$/ , only digits: /^[0-9]*$/, only a-z + A-Z + digits: /^[0-9a-z]*$/i' => 'Any string: /^.*$/ , only digits: /^[0-9]*$/, only a-z + A-Z + digits: /^[0-9a-z]*$/i',
'Error in case of invalid input' => 'Error in case of invalid input',
'Enter a clear and helpful error message in case of validation pattern mismatch.' => 'Enter a clear and helpful error message in case of validation pattern mismatch.',
'Date' => 'Date',
'Minimum Year shown' => 'Minimum Year shown',
'Type +0 for this year, type-in 4-digits year, for example 1923, or just a number prefixed with + or - sign, for example +25 or -110, to set a value relative to current year, e.g. -99 for maximum age of 99 years' => 'Type +0 for this year, type-in 4-digits year, for example 1923, or just a number prefixed with + or - sign, for example +25 or -110, to set a value relative to current year, e.g. -99 for maximum age of 99 years',
'Maximum Year shown' => 'Maximum Year shown',
'Type +0 for this year, type-in 4-digits year, for example 1923, or just a number prefixed with + or - sign, for example +25 or -110, to set a value relative to current year, e.g. -12 for minimum age of 12 years' => 'Type +0 for this year, type-in 4-digits year, for example 1923, or just a number prefixed with + or - sign, for example +25 or -110, to set a value relative to current year, e.g. -12 for minimum age of 12 years',
'Whether you want users to see this date on profile as a date or as an age' => 'Whether you want users to see this date on profile as a date or as an age',
'Full date' => 'Full date',
'Age in years' => 'Age in years',
'time ago' => 'time ago',
'birthday only without year' => 'birthday only without year',
'Display N years text' => 'Display N years text',
'Whether you want to display just number N of years (e.g. Age: 20), or add \' years\' behind the age N (e.g. Age: 20 years). Uses language-string _UE_AGE_YEARS.' => 'Whether you want to display just number N of years (e.g. Age: 20), or add \' years\' behind the age N (e.g. Age: 20 years). Uses language-string _UE_AGE_YEARS.',
'Display just \'N\'' => 'Display just \'N\'',
'Display \'N years\'' => 'Display \'N years\'',
'Display T ago text' => 'Display T ago text',
'Whether you want to display just the time T ago (e.g. 3 months), or add \' ago\' behind the time T (e.g. 3 months ago). Uses language-string _UE_ANYTHING_AGO.' => 'Whether you want to display just the time T ago (e.g. 3 months), or add \' ago\' behind the time T (e.g. 3 months ago). Uses language-string _UE_ANYTHING_AGO.',
'Display just \'T\'' => 'Display just \'T\'',
'Display \'T ago\'' => 'Display \'T ago\'',
'If searchable, then search by' => 'If searchable, then search by',
'Whether you want users to search by date or by age' => 'Whether you want users to search by date or by age',
'Age' => 'Age',
'Alternate field title for age/time ago/birthday only display' => 'Alternate field title for age/time ago/birthday only display',
'Leave blank for using same title as in normal date display mode, or enter alternate text, e.g. \'Age\' (multilinguale: type just: _UE_AGE) or \'Birthday\' (_UE_Birthday) instead of normal birthdate title. CB translation strings, as well as fields-substitutions, e.g. \'[name]\'s age\' can be used.' => 'Leave blank for using same title as in normal date display mode, or enter alternate text, e.g. \'Age\' (multilinguale: type just: _UE_AGE) or \'Birthday\' (_UE_Birthday) instead of normal birthdate title. CB translation strings, as well as fields-substitutions, e.g. \'[name]\'s age\' can be used.',
'Display Date and time' => 'Display Date and time',
'Whether you want to display date and time' => 'Whether you want to display date and time',
'Yes date and also time if available' => 'Yes date and also time if available',
'Date only' => 'Date only',
'Date and time' => 'Date and time',
'Yes date and also time' => 'Yes date and also time',
'Drop Down (Single Select)' => 'Drop Down (Single Select)',
'Drop Down (Multi-select)' => 'Drop Down (Multi-select)',
'Email Address' => 'Email Address',
'Enable Email checker' => 'Enable Email checker',
'Whether you want to feedback to user if email is valid or not.' => 'Whether you want to feedback to user if email is valid or not.',
'No: no ajax email checking' => 'No: no ajax email checking',
'Yes: Check email address and server' => 'Yes: Check email address and server',
'Forbidden words at registration' => 'Forbidden words at registration',
'You can set a list of bad words (separated by comma ,) which are not allowed in this field for registration. Use comma twice (,,) to add comma as bad character.' => 'You can set a list of bad words (separated by comma ,) which are not allowed in this field for registration. Use comma twice (,,) to add comma as bad character.',
'Forbidden words in user profile edits' => 'Forbidden words in user profile edits',
'You can set a list of bad words (separated by comma ,) which are not allowed in this field when user updates his profile in profile edits. Use comma twice (,,) to add comma as bad character.' => 'You can set a list of bad words (separated by comma ,) which are not allowed in this field when user updates his profile in profile edits. Use comma twice (,,) to add comma as bad character.',
'Email address (main)' => 'Email address (main)',
'Editor Text Area' => 'Editor Text Area',
'Minimum length' => 'Minimum length',
'Minimum length of content (0 = no minimum)' => 'Minimum length of content (0 = no minimum)',
'Text Area' => 'Text Area',
'Text Field' => 'Text Field',
'Single word ( /^[a-z]*$/ )' => 'Single word ( /^[a-z]*$/ )',
'Multiple words with spaces ( /^([a-z]+ *)*$/ )' => 'Multiple words with spaces ( /^([a-z]+ *)*$/ )',
'Single a-z,A-Z,0-9,_ word ( /^[a-z]+[a-z0-9_]*$/i )' => 'Single a-z,A-Z,0-9,_ word ( /^[a-z]+[a-z0-9_]*$/i )',
'At least 6 chars, 1 a-z, 1 A-Z, 1 0-9, 1 special' => 'At least 6 chars, 1 a-z, 1 A-Z, 1 0-9, 1 special',
'Radio Buttons' => 'Radio Buttons',
'Web Address' => 'Web Address',
'Predefined name and username fields' => 'Predefined name and username fields',
'Minimum length of content (0 = no minimum, empty = default system minimum length)' => 'Minimum length of content (0 = no minimum, empty = default system minimum length)',
'Password' => 'Password',
'Minimum length of password (0 = no minimum, empty = default system minimum length)' => 'Minimum length of password (0 = no minimum, empty = default system minimum length)',
'Image' => 'Image',
'Image limits' => 'Image limits',
'If left empty, the default settings from global Community Builder configuration will be taken. Other settings, like images-library, systematic resampling and so on is done in the CB global configuration.' => 'If left empty, the default settings from global Community Builder configuration will be taken. Other settings, like images-library, systematic resampling and so on is done in the CB global configuration.',
'Maximum height in pixels to which the image on the profile will be resized' => 'Maximum height in pixels to which the image on the profile will be resized',
'Maximum width in pixels to which the image on the profile will be resized' => 'Maximum width in pixels to which the image on the profile will be resized',
'Maximum size of file upload in kilobytes: recommended: 4000 for modern cameras (if your server supports that)' => 'Maximum size of file upload in kilobytes: recommended: 4000 for modern cameras (if your server supports that)',
'Maximum height in pixels to which the image on a users-list be resized' => 'Maximum height in pixels to which the image on a users-list be resized',
'Maximum width in pixels to which the image on a users-list be resized' => 'Maximum width in pixels to which the image on a users-list be resized',
'&lt;strong&gt;WARNING&lt;/strong&gt;' => '&lt;strong&gt;WARNING&lt;/strong&gt;',
'Only the main avatar is moderated for now, other image field types are not moderated in this release.' => 'Only the main avatar is moderated for now, other image field types are not moderated in this release.',
'Online Status' => 'Online Status',
'Connections' => 'Connections',
'Counter' => 'Counter',
'Formatted name' => 'Formatted name',
'User parameters' => 'User parameters',
'Fields delimiter' => 'Fields delimiter',

// cb.lists.xml
'Multi-Criteria Searches' => 'Multi-Criteria Searches',
'Users-lists can be searchable by multiple criterias, according to settings below, and the \'searchable\' attribute of the listed fields.' => 'Users-lists can be searchable by multiple criterias, according to settings below, and the \'searchable\' attribute of the listed fields.',
'Searchable fields' => 'Searchable fields',
'Whether this list has user-searchable fields' => 'Whether this list has user-searchable fields',
'Searchable fields, displayed ones only' => 'Searchable fields, displayed ones only',
'All searchable fields' => 'All searchable fields',
'Search crieteria' => 'Search crieteria',
'If users should be able to choose the type of comparison to be made (only standard \'is\' and ranges can be optimized in mysql with proper indexes).' => 'If users should be able to choose the type of comparison to be made (only standard \'is\' and ranges can be optimized in mysql with proper indexes).',
'Simple Exact match: Only \'is\' and ranges' => 'Simple Exact match: Only \'is\' and ranges',
'Simple Any word match: Only \'any of\' and ranges (WARNING: can be slow)' => 'Simple Any word match: Only \'any of\' and ranges (WARNING: can be slow)',
'Advanced: all possibilities (WARNING: can be slow)' => 'Advanced: all possibilities (WARNING: can be slow)',
'General list settings' => 'General list settings',
'Number of entries per page' => 'Number of entries per page',
'Number of users appearing per page. Leave empty to use the default CB setting.' => 'Number of users appearing per page. Leave empty to use the default CB setting.',
'Show pagination' => 'Show pagination',
'Whether this list shows links for paging or just displays entries from first page. Default is yes.' => 'Whether this list shows links for paging or just displays entries from first page. Default is yes.',
'Hot-linking protection for this users-list' => 'Hot-linking protection for this users-list',
'Whether you want the links to the pages and searches in this list to not be permanent (we add a parameter which is valid for a few hours to all urls except first page and check it), so that except first page it\'s not hotlinkable and there are no permanent links on paging and on search criterias. Default is NO.' => 'Whether you want the links to the pages and searches in this list to not be permanent (we add a parameter which is valid for a few hours to all urls except first page and check it), so that except first page it\'s not hotlinkable and there are no permanent links on paging and on search criterias. Default is NO.',
'Setting hot-linking protection to \'Yes\' will prevent all pages from this list (if everybody has allowed access to it), except first page of list, to be bookmarkable and indexable by slow-pace search bots and search engines such as google, making the user profiles not indexed in search engines (if they are publicly accessible). This may be desirable in some cases, but removes all users-pages from the search-engines indexing, except for the users of the first page.' => 'Setting hot-linking protection to \'Yes\' will prevent all pages from this list (if everybody has allowed access to it), except first page of list, to be bookmarkable and indexable by slow-pace search bots and search engines such as google, making the user profiles not indexed in search engines (if they are publicly accessible). This may be desirable in some cases, but removes all users-pages from the search-engines indexing, except for the users of the first page.',

// cb.mamblogtab.xml
'Provides a User Tab that shows all Mamblog entries written by the user.' => 'Provides a User Tab that shows all Mamblog entries written by the user.',
'List Settings' => 'List Settings',
'Blog Entries:' => 'Blog Entries:',
'Number of blog entries to display' => 'Number of blog entries to display',
'If showing all posts, this is the number of posts per page. If showing only last ones, this is the number of blog entries to show. Default is 10' => 'If showing all posts, this is the number of posts per page. If showing only last ones, this is the number of blog entries to show. Default is 10',
'Show all blogs with paging' => 'Show all blogs with paging',
'If set to -show all- all blog entries will become visible in the user profile. Otherwise, only the last entries will be visible.' => 'If set to -show all- all blog entries will become visible in the user profile. Otherwise, only the last entries will be visible.',
'Only last ones' => 'Only last ones',
'Show all' => 'Show all',
'Allow search function' => 'Allow search function',
'IMPORTANT: Show all blog entries must also be set. Allows a search on user blog entries.' => 'IMPORTANT: Show all blog entries must also be set. Allows a search on user blog entries.',
'Disabled' => 'Disabled',

// cb.menu.xml
'Core CB Menu and User Status tabs.' => 'Core CB Menu and User Status tabs.',
'User Profile Menu :' => 'User Profile Menu :',
'Menu display type' => 'Menu display type',
'Menu can be displayed as a menubar, a list of menu links, or not displayed in this tab.' => 'Menu can be displayed as a menubar, a list of menu links, or not displayed in this tab.',
'Menu Bar' => 'Menu Bar',
'Menu List table 2 columns' => 'Menu List table 2 columns',
'Menu List table 1 column' => 'Menu List table 1 column',
'Menu List ul-li-spans' => 'Menu List ul-li-spans',
'No Display' => 'No Display',
'Status display type' => 'Status display type',
'Status can be displayed as a list or not displayed in this tab.' => 'Status can be displayed as a list or not displayed in this tab.',
'Status List ul-li-spans' => 'Status List ul-li-spans',
'see Plugins: Menu: Parameters' => 'see Plugins: Menu: Parameters',
'Menu Settings :' => 'Menu Settings :',
'Settings' => 'Settings',
'Heading Menu :' => 'Heading Menu :',
'First Menu Name' => 'First Menu Name',
'First menu name before &quot;Edit&quot;. Default is &quot;Community&quot; = _UE_MENU_CB. Leave empty to not appear.' => 'First menu name before &quot;Edit&quot;. Default is &quot;Community&quot; = _UE_MENU_CB. Leave empty to not appear.',
'First Sub-Menu Name' => 'First Sub-Menu Name',
'First sub-menu name. Default is &quot;About Community Builder...&quot; = _UE_MENU_ABOUT_CB. Leave empty to not appear.' => 'First sub-menu name. Default is &quot;About Community Builder...&quot; = _UE_MENU_ABOUT_CB. Leave empty to not appear.',
'First Sub-Menu URL' => 'First Sub-Menu URL',
'First sub-menu URL. Default is index.php?option=com_comprofiler&amp;task=teamCredits' => 'First sub-menu URL. Default is index.php?option=com_comprofiler&amp;task=teamCredits',
'Second Sub-Menu Name' => 'Second Sub-Menu Name',
'Second sub-menu name. Leave empty to not appear.' => 'Second sub-menu name. Leave empty to not appear.',
'Second Sub-Menu URL' => 'Second Sub-Menu URL',
'Second sub-menu URL.' => 'Second sub-menu URL.',
'Display Settings: Hits, Online, Member since, last online, last updated on have moved to core Community Builder fields, see fields management.' => 'Display Settings: Hits, Online, Member since, last online, last updated on have moved to core Community Builder fields, see fields management.',
'see Plugin: Connections: Parameters' => 'see Plugin: Connections: Parameters',
'Connections Settings :' => 'Connections Settings :',

// cb.simpleboardtab.xml
'Provides a User Tab that shows top Fireboard/Joomlaboard/Simpleboard posts as well as forum statistics for the user.' => 'Provides a User Tab that shows top Fireboard/Joomlaboard/Simpleboard posts as well as forum statistics for the user.',
'Forum component' => 'Forum component',
'Choose the type of forum for integration. &lt;strong&gt;IMPORTANT: Fireboard/Joomlaboard/Simpleboard configuration integration with CB must be enabled and fields created from that same forum configuration integration tab.&lt;/strong&gt;' => 'Choose the type of forum for integration. &lt;strong&gt;IMPORTANT: Fireboard/Joomlaboard/Simpleboard configuration integration with CB must be enabled and fields created from that same forum configuration integration tab.&lt;/strong&gt;',
'Auto-detect' => 'Auto-detect',
'Kunena from www.kunena.com' => 'Kunena from www.kunena.com',
'Fireboard from www.bestofjoomla.com' => 'Fireboard from www.bestofjoomla.com',
'Joomlaboard from www.tsmf.net' => 'Joomlaboard from www.tsmf.net',
'Simpleboard' => 'Simpleboard',
'Detected Forums' => 'Detected Forums',
'Sidebar:' => 'Sidebar:',
'Sidebar Mode' => 'Sidebar Mode',
'Kunena sidebar is displayed on the right of every post. This sidebar can be customized to display any information so desired using the various supported modes.' => 'Kunena sidebar is displayed on the right of every post. This sidebar can be customized to display any information so desired using the various supported modes.',
'Basic (default kunena)' => 'Basic (default kunena)',
'Beginner (field selection)' => 'Beginner (field selection)',
'Advanced (subsitution textarea)' => 'Advanced (subsitution textarea)',
'Expert (PHP file)' => 'Expert (PHP file)',
'Name Field' => 'Name Field',
'Field displayed in position of Username in sidebar.' => 'Field displayed in position of Username in sidebar.',
'Avatar Field' => 'Avatar Field',
'Field displayed in position of Avatar in sidebar.' => 'Field displayed in position of Avatar in sidebar.',
'Personal Text Field' => 'Personal Text Field',
'Field displayed in position of Personal Text in sidebar.' => 'Field displayed in position of Personal Text in sidebar.',
'Birthday Field' => 'Birthday Field',
'Field displayed in position of Birthday icon in sidebar.' => 'Field displayed in position of Birthday icon in sidebar.',
'Location Field' => 'Location Field',
'Field displayed in position of Location icon in sidebar.' => 'Field displayed in position of Location icon in sidebar.',
'Gender Field' => 'Gender Field',
'Field displayed in position of Gender icon in sidebar.' => 'Field displayed in position of Gender icon in sidebar.',
'ICQ Field' => 'ICQ Field',
'Field displayed in position of ICQ icon in sidebar.' => 'Field displayed in position of ICQ icon in sidebar.',
'AIM Field' => 'AIM Field',
'Field displayed in position of AIM icon in sidebar.' => 'Field displayed in position of AIM icon in sidebar.',
'YIM Field' => 'YIM Field',
'Field displayed in position of YIM icon in sidebar.' => 'Field displayed in position of YIM icon in sidebar.',
'MSN Field' => 'MSN Field',
'Field displayed in position of MSN icon in sidebar.' => 'Field displayed in position of MSN icon in sidebar.',
'SKYPE Field' => 'SKYPE Field',
'Field displayed in position of SKYPE icon in sidebar.' => 'Field displayed in position of SKYPE icon in sidebar.',
'GTALK Field' => 'GTALK Field',
'Field displayed in position of GTALK icon in sidebar.' => 'Field displayed in position of GTALK icon in sidebar.',
'Website Field' => 'Website Field',
'Field displayed in position of Website icon in sidebar.' => 'Field displayed in position of Website icon in sidebar.',
'Example' => 'Example',
'Existing Users Sidebar' => 'Existing Users Sidebar',
'Advanced sidebar supports html and substitutions to fully design display of sidebar. Will display the sidebar of existing users.' => 'Advanced sidebar supports html and substitutions to fully design display of sidebar. Will display the sidebar of existing users.',
'Deleted Users Sidebar' => 'Deleted Users Sidebar',
'Advanced sidebar supports html and substitutions to fully design display of sidebar. Will display the sidebar of deleted users.' => 'Advanced sidebar supports html and substitutions to fully design display of sidebar. Will display the sidebar of deleted users.',
'Public Users Sidebar' => 'Public Users Sidebar',
'Advanced sidebar supports html and substitutions to fully design display of sidebar. Will display the sidebar of public users.' => 'Advanced sidebar supports html and substitutions to fully design display of sidebar. Will display the sidebar of public users.',
'Expert PHP file can be located at the following location: components/com_comprofiler/plugin/user/plug_cbsimpleboardtab/view/ within the file cb.simpleboardtab.sidebar.php as the function ShowExpert.' => 'Expert PHP file can be located at the following location: components/com_comprofiler/plugin/user/plug_cbsimpleboardtab/view/ within the file cb.simpleboardtab.sidebar.php as the function ShowExpert.',
'Expert Sidebar' => 'Expert Sidebar',
'Forum Status:' => 'Forum Status:',
'Display forum statistics' => 'Display forum statistics',
'Display the forum statistics. &lt;strong&gt;IMPORTANT: Kunena/Fireboard/Joomlaboard/Simpleboard configuration must also allow to show this!&lt;/strong&gt;' => 'Display the forum statistics. &lt;strong&gt;IMPORTANT: Kunena/Fireboard/Joomlaboard/Simpleboard configuration must also allow to show this!&lt;/strong&gt;',
'In User Profile Status' => 'In User Profile Status',
'In Forum Tab' => 'In Forum Tab',
'Path Template rank' => 'Path Template rank',
'Ranking' => 'Ranking',
'Display the forum ranking text. &lt;strong&gt;IMPORTANT: Kunena/Fireboard/Joomlaboard/Simpleboard configuration must also allow to show this!&lt;/strong&gt;' => 'Display the forum ranking text. &lt;strong&gt;IMPORTANT: Kunena/Fireboard/Joomlaboard/Simpleboard configuration must also allow to show this!&lt;/strong&gt;',
'Ranking Slider' => 'Ranking Slider',
'Display the forum ranking graphic' => 'Display the forum ranking graphic',
'Show Slider' => 'Show Slider',
'Total Posts' => 'Total Posts',
'Display the forum total posts. &lt;strong&gt;IMPORTANT: Kunena/Fireboard/Joomlaboard/Simpleboard configuration must also allow to show this!&lt;/strong&gt;' => 'Display the forum total posts. &lt;strong&gt;IMPORTANT: Kunena/Fireboard/Joomlaboard/Simpleboard configuration must also allow to show this!&lt;/strong&gt;',
'Show if not 0' => 'Show if not 0',
'Karma' => 'Karma',
'Display the forum karma. &lt;strong&gt;IMPORTANT: Kunena/Fireboard/Joomlaboard/Simpleboard configuration must also allow to show this!&lt;/strong&gt;' => 'Display the forum karma. &lt;strong&gt;IMPORTANT: Kunena/Fireboard/Joomlaboard/Simpleboard configuration must also allow to show this!&lt;/strong&gt;',
'List Settings: see tab configuration: parameters' => 'List Settings: see tab configuration: parameters',
'Forum Posts:' => 'Forum Posts:',
'Number of posts to display' => 'Number of posts to display',
'If showing all posts, this is the number of posts per page. If showing only last ones, this is the number of posts to show. Default is 10' => 'If showing all posts, this is the number of posts per page. If showing only last ones, this is the number of posts to show. Default is 10',
'Show all forum posts with paging' => 'Show all forum posts with paging',
'If set to -show all- all forum posts will become visible in the user profile. Otherwise, only the last posts will be visible.' => 'If set to -show all- all forum posts will become visible in the user profile. Otherwise, only the last posts will be visible.',
'IMPORTANT: Show all posts must also be set. Allows a search on posts from the user.' => 'IMPORTANT: Show all posts must also be set. Allows a search on posts from the user.',
'see plugin configuration: Forum: parameters' => 'see plugin configuration: Forum: parameters',
'More settings:' => 'More settings:',
'Forum Status' => 'Forum Status',
'Forum Settings' => 'Forum Settings',

// pms.mypmspro.xml
'Provides the myPMS, PMS Pro, PMS Enhanced, JIM and uddeIM 0.4/1.0 integration for Community Builder.' => 'Provides the myPMS, PMS Pro, PMS Enhanced, JIM and uddeIM 0.4/1.0 integration for Community Builder.',
'PMS Component type' => 'PMS Component type',
'Choose type of component installed. &lt;strong&gt;IMPORTANT: Component configuration must also be done!&lt;/strong&gt;' => 'Choose type of component installed. &lt;strong&gt;IMPORTANT: Component configuration must also be done!&lt;/strong&gt;',
'MyPMS Open Source' => 'MyPMS Open Source',
'uddeIM 0.4' => 'uddeIM 0.4',
'uddeIM 1.0' => 'uddeIM 1.0',
'JIM 1.0.1' => 'JIM 1.0.1',
'PMS Send Menu/Link text' => 'PMS Send Menu/Link text',
'Default is _UE_PM_USER, the local translation of &quot;Send Private Message&quot;' => 'Default is _UE_PM_USER, the local translation of &quot;Send Private Message&quot;',
'PMS Send Menu/Link Description' => 'PMS Send Menu/Link Description',
'Default is _UE_MENU_PM_USER_DESC, the local translation of &quot;Send a Private Message to this user&quot;' => 'Default is _UE_MENU_PM_USER_DESC, the local translation of &quot;Send a Private Message to this user&quot;',
'PMS Inbox Menu/Link text' => 'PMS Inbox Menu/Link text',
'Default is _UE_PM_INBOX, the local translation of &quot;Show Private Inbox&quot;' => 'Default is _UE_PM_INBOX, the local translation of &quot;Show Private Inbox&quot;',
'PMS Menu/Link Description' => 'PMS Menu/Link Description',
'Default is _UE_MENU_PM_INBOX_DESC, the local translation of &quot;Show Received Private Messages&quot;' => 'Default is _UE_MENU_PM_INBOX_DESC, the local translation of &quot;Show Received Private Messages&quot;',
'only for PMS Pro/uddeIM' => 'only for PMS Pro/uddeIM',
'Following parameters:' => 'Following parameters:',
'PMS Outbox Menu/Link text' => 'PMS Outbox Menu/Link text',
'Default is _UE_PM_OUTBOX, the local translation of &quot;Show Private Outbox&quot;' => 'Default is _UE_PM_OUTBOX, the local translation of &quot;Show Private Outbox&quot;',
'PMS Outbox Menu/Link Description' => 'PMS Outbox Menu/Link Description',
'Default is _UE_MENU_PM_OUTBOX_DESC, the local translation of &quot;Show Sent Private Messages&quot;' => 'Default is _UE_MENU_PM_OUTBOX_DESC, the local translation of &quot;Show Sent Private Messages&quot;',
'PMS Trash Menu/Link text' => 'PMS Trash Menu/Link text',
'Default is _UE_PM_TRASHBOX, the local translation of &quot;Show Trash&quot;' => 'Default is _UE_PM_TRASHBOX, the local translation of &quot;Show Trash&quot;',
'PMS Trash Menu/Link Description' => 'PMS Trash Menu/Link Description',
'Default is _UE_MENU_PM_TRASHBOX_DESC, the local translation of &quot;Show Trashed Private Messages&quot;' => 'Default is _UE_MENU_PM_TRASHBOX_DESC, the local translation of &quot;Show Trashed Private Messages&quot;',
'only for PMS Pro/uddeIM 0.5' => 'only for PMS Pro/uddeIM 0.5',
'PMS Options Menu/Link text' => 'PMS Options Menu/Link text',
'Default is _UE_PM_OPTIONS, the local translation of &quot;Show PMS Options&quot;' => 'Default is _UE_PM_OPTIONS, the local translation of &quot;Show PMS Options&quot;',
'PMS Options Menu/Link Description' => 'PMS Options Menu/Link Description',
'Default is _UE_MENU_PM_OPTIONS_DESC, the local translation of &quot;Show PMS Options&quot;' => 'Default is _UE_MENU_PM_OPTIONS_DESC, the local translation of &quot;Show PMS Options&quot;',
'PMS User Deletion' => 'PMS User Deletion',
'Choose how you want PMS messages to be handled when a user is removed' => 'Choose how you want PMS messages to be handled when a user is removed',
'Keep all messages' => 'Keep all messages',
'Remove all messages (received and sent)' => 'Remove all messages (received and sent)',
'Remove received messages only' => 'Remove received messages only',
'Remove sent message only' => 'Remove sent message only',
'PMS Deletion Function to use' => 'PMS Deletion Function to use',
'Choose which function to be called when user is deleted (PMS component specific cleanup functions must be stored in cb_extra.php file in component root).' => 'Choose which function to be called when user is deleted (PMS component specific cleanup functions must be stored in cb_extra.php file in component root).',
'Use CB Plugin Function' => 'Use CB Plugin Function',
'Use PMS Component Function' => 'Use PMS Component Function',
'see tab manager: MyPMSPro: parameters' => 'see tab manager: MyPMSPro: parameters',
'Quick Message Settings' => 'Quick Message Settings',
'Show Tab title' => 'Show Tab title',
'Show the title of the tab inside this tab. The description is also shown, if present. &lt;strong&gt;IMPORTANT: The title is the tab title here.&lt;/strong&gt;' => 'Show the title of the tab inside this tab. The description is also shown, if present. &lt;strong&gt;IMPORTANT: The title is the tab title here.&lt;/strong&gt;',
'Show Subject Field' => 'Show Subject Field',
'Show the subject field. If hidden, subject will be &quot;Message from your profile view&quot; = _UE_PM_PROFILEMSG' => 'Show the subject field. If hidden, subject will be &quot;Message from your profile view&quot; = _UE_PM_PROFILEMSG',
'Width (chars)' => 'Width (chars)',
'Height (lines)' => 'Height (lines)',
'see plugin manager: MyPMSPro: parameters' => 'see plugin manager: MyPMSPro: parameters',

// yanc.xml
'Provides integration between CB and Yanc.' => 'Provides integration between CB and Yanc.',
'This tab only appears in User profile EDIT mode.' => 'This tab only appears in User profile EDIT mode.',
'Important:' => 'Important:',

// PHP files (without duplicates):

// Menus: file administrator/components/com_comprofiler/toolbar.comprofiler.html.php :
'New'		=>	'New',
'Publish'	=>	'Publish',
'Default'	=>	'Default',
'Assign'	=>	'Assign',
'Unpublish'	=>	'Unpublish',
'Archive'	=>	'Archive',
'Unarchive'	=>	'Unarchive',
'Edit'		=>	'Edit',
'Edit HTML'	=>	'Edit HTML',
'Edit CSS'	=>	'Edit CSS',
'Delete'	=>	'Delete',
'Trash'		=>	'Trash',
'Preview'	=>	'Preview',
'Help'		=>	'Help',
'Apply'		=>	'Apply',
'Save'		=>	'Save',
'Cancel'	=>	'Cancel',
'Back'		=>	'Back',
'Upload'	=>	'Upload',
'New Tab'	=>	'New Tab',
'New Field'	=>	'New Field',
'New List'	=>	'New List',
'Close'		=>	'Close',
'Mass Mail'	=>	'Mass Mail',
'Send Mails'	=>	'Send Mails',
'Please make a selection from the list to %s'	=>	'Please make a selection from the list to %s',
'Please make a selection from the list to publish'	=>	'Please make a selection from the list to publish',
'Please select an item to make default'	=>	'Please select an item to make default',
'Please select an item to assign'	=>	'Please select an item to assign',
'Upload Image' => 'Upload Image',
'Please make a selection from the list to unpublish'	=>	'Please make a selection from the list to unpublish',
'Please make a selection from the list to archive'	=>	'Please make a selection from the list to archive',
'Please select a news story to unarchive'	=>	'Please select a news story to unarchive',
'Please select an item from the list to edit'	=>	'Please select an item from the list to edit',
'Please make a selection from the list to delete'	=>	'Please make a selection from the list to delete',
'Are you sure you want to delete the selected items ?'	=>	'Are you sure you want to delete the selected items ?',
'The tab will be deleted and this cannot be undone!'	=>	'The tab will be deleted and this cannot be undone!',
'The Field and all user data associated to this field will be lost and this cannot be undone!'	=>	'The Field and all user data associated to this field will be lost and this cannot be undone!',
'The selected List(s) will be deleted and this cannot be undone!'	=>	'The selected List(s) will be deleted and this cannot be undone!',




// .../administrator/components/com_comprofiler/admin.comprofiler.controller.php (344 in CBTxt format) //

'Warning: file %s still exists. This is probably due to the fact that first installation step did not complete, or second installation step did not take place. If you are sure that first step has been performed, you need to execute second installation step before using CB. You can do this now by clicking here:' => 'Warning: file %s still exists. This is probably due to the fact that first installation step did not complete, or second installation step did not take place. If you are sure that first step has been performed, you need to execute second installation step before using CB. You can do this now by clicking here:',
'please click here to continue next and last installation step' => 'please click here to continue next and last installation step',
'Successfully Saved List: %s' => 'Successfully Saved List: %s',
'User Groups' => 'User Groups',
'Everybody' => 'Everybody',
'All Registered Users' => 'All Registered Users',
'List parameters' => 'List parameters',
'Field-specific Parameters' => 'Field-specific Parameters',
'Select an item to delete' => 'Select an item to delete',
'Parameters' => 'Parameters',
'To see Parameters, first save new field' => 'To see Parameters, first save new field',
'Unauthorized Access' => 'Unauthorized Access',
'Tab' => 'Tab',
'URL only' => 'URL only',
'Hypertext and URL' => 'Hypertext and URL',
'No' => 'No',
'Yes: on 1 Line' => 'Yes: on 1 Line',
'Yes: on 2 Lines' => 'Yes: on 2 Lines',
'Innexistant field' => 'Innexistant field',
'Successfully Saved changes to Field' => 'Successfully Saved changes to Field',
'Successfully Saved Field' => 'Successfully Saved Field',
'%s cannot be deleted because it is on a List.' => '%s cannot be deleted because it is on a List.',
'%s cannot be deleted because it is a system field.' => '%s cannot be deleted because it is a system field.',
'Successfully Deleted Fields' => 'Successfully Deleted Fields',
'first' => 'first',
'last' => 'last',
'Line' => 'Line',
'Column' => 'Column',
'Not displayed on profile' => 'Not displayed on profile',
'This plugin cannot be reordered' => 'This plugin cannot be reordered',
'New items default to the last place. Ordering can be changed after this item is saved.' => 'New items default to the last place. Ordering can be changed after this item is saved.',
'Missing post values' => 'Missing post values',
'Successfully Saved Tab' => 'Successfully Saved Tab',
'%s cannot be deleted because it is a system tab.' => '%s cannot be deleted because it is a system tab.',
'%s cannot be deleted because it is a tab belonging to an installed plugin.' => '%s cannot be deleted because it is a tab belonging to an installed plugin.',
'%s is being referenced by an existing field and cannot be deleted!' => '%s is being referenced by an existing field and cannot be deleted!',
'Blocked' => 'Blocked',
'Enabled' => 'Enabled',
'Unconfirmed' => 'Unconfirmed',
'Confirmed' => 'Confirmed',
'Unapproved' => 'Unapproved',
'Disapproved' => 'Disapproved',
'Approved' => 'Approved',
'Banned' => 'Banned',
'Avatar not approved' => 'Avatar not approved',
'- Select Login State -' => '- Select Login State -',
'Logged In' => 'Logged In',
'- Select Group -' => '- Select Group -',
'- Select User Status -' => '- Select User Status -',
'email not send: simulation mode' => 'email not send: simulation mode',
'Error sending email!' => 'Error sending email!',
'Test email sent to %s' => 'Test email sent to %s',
'Waiting delay for next batch...' => 'Waiting delay for next batch...',
'Executing' => 'Executing',
'Done' => 'Done',
'Pause' => 'Pause',
'Resume' => 'Resume',
'ERROR!' => 'ERROR!',
'Not Authorized' => 'Not Authorized',
'Successfully Saved User: %s' => 'Successfully Saved User: %s',
'You cannot delete this Super Administrator as it is the only active Super Administrator for your site' => 'You cannot delete this Super Administrator as it is the only active Super Administrator for your site',
'User not found' => 'User not found',
'Select an item to %s' => 'Select an item to %s',
'unknown action %s' => 'unknown action %s',
'Email' => 'Email',
'PMS' => 'PMS',
'PMS+Email' => 'PMS+Email',
'yyyy/mm/dd' => 'yyyy/mm/dd',
'dd/mm/yy' => 'dd/mm/yy',
'yy/mm/dd' => 'yy/mm/dd',
'dd/mm/yyyy' => 'dd/mm/yyyy',
'mm/dd/yy' => 'mm/dd/yy',
'mm/dd/yyyy' => 'mm/dd/yyyy',
'yyyy-mm-dd' => 'yyyy-mm-dd',
'dd-mm-yy' => 'dd-mm-yy',
'yy-mm-dd' => 'yy-mm-dd',
'dd-mm-yyyy' => 'dd-mm-yyyy',
'mm-dd-yy' => 'mm-dd-yy',
'mm-dd-yyyy' => 'mm-dd-yyyy',
'yyyy.mm.dd' => 'yyyy.mm.dd',
'dd.mm.yy' => 'dd.mm.yy',
'yy.mm.dd' => 'yy.mm.dd',
'dd.mm.yyyy' => 'dd.mm.yyyy',
'mm.dd.yy' => 'mm.dd.yy',
'mm.dd.yyyy' => 'mm.dd.yyyy',
'ImageMagick' => 'ImageMagick',
'NetPBM' => 'NetPBM',
'GD1 library' => 'GD1 library',
'GD2 library' => 'GD2 library',
'Display text markers' => 'Display text markers',
'Display html and text markers' => 'Display html and text markers',
'Display markers and list untranslated strings' => 'Display markers and list untranslated strings',
'Display markers and list all strings' => 'Display markers and list all strings',
'Use tables' => 'Use tables',
'Use divs (table-less output)' => 'Use divs (table-less output)',
'FATAL ERROR: Config File Not writeable' => 'FATAL ERROR: Config File Not writeable',
'Configuration file saved' => 'Configuration file saved',
'Failed to change the permissions of the config file %s' => 'Failed to change the permissions of the config file %s',
'Failed to create and write config file in %s' => 'Failed to create and write config file in %s',
'ERROR: Configuration file administrator/components/com_comprofiler/ue_config.php could not be written by webserver. Please change file permissions in your web-pannel.' => 'ERROR: Configuration file administrator/components/com_comprofiler/ue_config.php could not be written by webserver. Please change file permissions in your web-pannel.',
'Make Required' => 'Make Required',
'Make Non-required' => 'Make Non-required',
'Publish' => 'Publish',
'UnPublish' => 'UnPublish',
'Add to Registration' => 'Add to Registration',
'Remove from Registration' => 'Remove from Registration',
'field searchable in users-lists' => 'field searchable in users-lists',
'field not searchable in users-lists' => 'field not searchable in users-lists',
'Select an item to make %s' => 'Select an item to make %s',
'Make Default' => 'Make Default',
'Reset Default' => 'Reset Default',
'Add to Profile' => 'Add to Profile',
'Remove from Profile' => 'Remove from Profile',
'Tab Added Successfully!' => 'Tab Added Successfully!',
'Schema Changes Added Successfully!' => 'Schema Changes Added Successfully!',
'Fields Added Successfully!' => 'Fields Added Successfully!',
'List Added Successfully!' => 'List Added Successfully!',
'SQL error %s' => 'SQL error %s',
'Sample Data is already loaded!' => 'Sample Data is already loaded!',
'Deleted %s not allowed user id 0 entry.' => 'Deleted %s not allowed user id 0 entry.',
'Added %s new entries to Community Builder from users Table.' => 'Added %s new entries to Community Builder from users Table.',
'Fixed %s existing entries in Community Builder: fixed wrong user_id.' => 'Fixed %s existing entries in Community Builder: fixed wrong user_id.',
'Removing %s entries from Community Builder missing in users Table.' => 'Removing %s entries from Community Builder missing in users Table.',
'Joomla/Mambo User Table and Joomla/Mambo Community Builder User Table now in sync!' => 'Joomla/Mambo User Table and Joomla/Mambo Community Builder User Table now in sync!',
'CB Tools: Check database: Results' => 'CB Tools: Check database: Results',
'Checking Community Builder Database' => 'Checking Community Builder Database',
'ERROR: sql query: %s : returned error: %s' => 'ERROR: sql query: %s : returned error: %s',
'Warning: %s entries in Community Builder comprofiler_field_values have bad fieldid values.' => 'Warning: %s entries in Community Builder comprofiler_field_values have bad fieldid values.',
'ZERO fieldvalueid illegal: fieldvalueid=%s fieldid=0' => 'ZERO fieldvalueid illegal: fieldvalueid=%s fieldid=0',
'This one can be fixed by <strong>first backing up database</strong>' => 'This one can be fixed by <strong>first backing up database</strong>',
'then by clicking here' => 'then by clicking here',
'All Community Builder comprofiler_field_values table fieldid rows all match existing fields.' => 'All Community Builder comprofiler_field_values table fieldid rows all match existing fields.',
'Warning: %s entries in Community Builder comprofiler_field_values link back to fields of wrong fieldtype.' => 'Warning: %s entries in Community Builder comprofiler_field_values link back to fields of wrong fieldtype.',
'This one can be fixed in SQL using a tool like phpMyAdmin.' => 'This one can be fixed in SQL using a tool like phpMyAdmin.',
'All Community Builder comprofiler_field_values table rows link to correct fieldtype fields in comprofiler_field table.' => 'All Community Builder comprofiler_field_values table rows link to correct fieldtype fields in comprofiler_field table.',
' - Field %s - Column %s is missing from comprofiler table.' => ' - Field %s - Column %s is missing from comprofiler table.',
' - Column %s is missing from comprofiler table.' => ' - Column %s is missing from comprofiler table.',
'There are %s column(s) missing in the comprofiler table, which are defined as fields (rows in comprofiler_fields):' => 'There are %s column(s) missing in the comprofiler table, which are defined as fields (rows in comprofiler_fields):',
'This one can be fixed by deleting and recreating the field(s) using components / Community Builder / Field Management.' => 'This one can be fixed by deleting and recreating the field(s) using components / Community Builder / Field Management.',
'Please additionally make sure that columns in comprofiler table <strong>are not also duplicated in users table</strong>.' => 'Please additionally make sure that columns in comprofiler table <strong>are not also duplicated in users table</strong>.',
'All Community Builder fields from comprofiler_fields are present as columns in the comprofiler table, but comprofiler_fields table is not yet upgraded to CB 1.2 table structure. Just going to Community Builder Fields Management will fix this automatically.' => 'All Community Builder fields from comprofiler_fields are present as columns in the comprofiler table, but comprofiler_fields table is not yet upgraded to CB 1.2 table structure. Just going to Community Builder Fields Management will fix this automatically.',
'All Community Builder fields from comprofiler_fields are present as columns in the comprofiler table.' => 'All Community Builder fields from comprofiler_fields are present as columns in the comprofiler table.',
'Avatars and thumbnails folder: %s/%s is NOT writeable by the webserver.' => 'Avatars and thumbnails folder: %s/%s is NOT writeable by the webserver.',
'Avatars and thumbnails folder is Writeable.' => 'Avatars and thumbnails folder is Writeable.',
'Core CB mandatory basics' => 'Core CB mandatory basics',
'Core CB' => 'Core CB',
'CB plugin' => 'CB plugin',
'%s "%s": no database or no database description.' => '%s "%s": no database or no database description.',
'CB plugins' => 'CB plugins',
'Checking Users Database' => 'Checking Users Database',
'Warning: %s entries in Community Builder comprofiler table without corresponding user table rows.' => 'Warning: %s entries in Community Builder comprofiler table without corresponding user table rows.',
'Following comprofiler id: %s are missing in user table' => 'Following comprofiler id: %s are missing in user table',
'This comprofiler entry with id 0 should be removed, as it\'s not allowed.' => 'This comprofiler entry with id 0 should be removed, as it\'s not allowed.',
'This one can be fixed using menu Components-&gt; Community Builder-&gt; tools and then click `Synchronize users`.' => 'This one can be fixed using menu Components-&gt; Community Builder-&gt; tools and then click `Synchronize users`.',
'All Community Builder comprofiler table rows have links to user table.' => 'All Community Builder comprofiler table rows have links to user table.',
'Warning: %s entries in users table without corresponding comprofiler table rows.' => 'Warning: %s entries in users table without corresponding comprofiler table rows.',
'users id: %s are missing in comprofiler table' => 'users id: %s are missing in comprofiler table',
'All users table rows have links to comprofiler table.' => 'All users table rows have links to comprofiler table.',
'Warning: %s entries in users table with id=0.' => 'Warning: %s entries in users table with id=0.',
'users id=%s is not allowed.' => 'users id=%s is not allowed.',
'users table has no zero id row.' => 'users table has no zero id row.',
'Warning: %s entries in comprofiler table with id=0.' => 'Warning: %s entries in comprofiler table with id=0.',
'comprofiler id=%s is not allowed.' => 'comprofiler id=%s is not allowed.',
'This one can be fixed using menu Components / Community Builder / Tools and then click "Synchronize users".' => 'This one can be fixed using menu Components / Community Builder / Tools and then click "Synchronize users".',
'comprofiler table has no zero id row.' => 'comprofiler table has no zero id row.',
'Warning: %s entries in comprofiler table with user_id <> id.' => 'Warning: %s entries in comprofiler table with user_id <> id.',
'comprofiler id=%s is different from user_id=%s.' => 'comprofiler id=%s is different from user_id=%s.',
'All rows in comprofiler table have user_id columns identical to id columns.' => 'All rows in comprofiler table have user_id columns identical to id columns.',
'Warning: %s entries in the users table without corresponding core_acl_aro table rows.' => 'Warning: %s entries in the users table without corresponding core_acl_aro table rows.',
'Warning: %s entries in the users table without corresponding user_usergroup_map table rows.' => 'Warning: %s entries in the users table without corresponding user_usergroup_map table rows.',
'user id: %s are missing in core_acl_aro table' => 'user id: %s are missing in core_acl_aro table',
'user id: %s are missing in user_usergroup_map table' => 'user id: %s are missing in user_usergroup_map table',
'This user entry with id 0 should be removed, as it\'s not allowed.' => 'This user entry with id 0 should be removed, as it\'s not allowed.',
'All users table rows have ACL entries in core_acl_aro table.' => 'All users table rows have ACL entries in core_acl_aro table.',
'All users table rows have ACL entries in user_usergroup_map table.' => 'All users table rows have ACL entries in user_usergroup_map table.',
'Warning: %s entries in the core_acl_aro table without corresponding users table rows.' => 'Warning: %s entries in the core_acl_aro table without corresponding users table rows.',
'Following entries of [tablename1] table are missing in [tablename2] table: [badids].' => 'Following entries of [tablename1] table are missing in [tablename2] table: [badids].',
'This core_acl_aro entry with (user) value 0 should be removed, as it\'s not allowed.' => 'This core_acl_aro entry with (user) value 0 should be removed, as it\'s not allowed.',
'This core_acl_aro entry with aro_id 0 should be removed, as it\'s not allowed.' => 'This core_acl_aro entry with aro_id 0 should be removed, as it\'s not allowed.',
'All [tablename1] table rows have corresponding entries in [tablename2] table.' => 'All [tablename1] table rows have corresponding entries in [tablename2] table.',
'Warning: %s entries in the core_acl_aro table without corresponding core_acl_groups_aro_map table rows.' => 'Warning: %s entries in the core_acl_aro table without corresponding core_acl_groups_aro_map table rows.',
'Following entries of core_acl_aro table are missing in core_acl_groups_aro_map table: %s.' => 'Following entries of core_acl_aro table are missing in core_acl_groups_aro_map table: %s.',
'All core_acl_aro table rows have ACL entries in core_acl_groups_aro_map table.' => 'All core_acl_aro table rows have ACL entries in core_acl_groups_aro_map table.',
'Warning: %s entries in the core_acl_groups_aro_map without corresponding core_acl_aro table table rows.' => 'Warning: %s entries in the core_acl_groups_aro_map without corresponding core_acl_aro table table rows.',
'aro_id = %s are missing in core_acl_aro table table.' => 'aro_id = %s are missing in core_acl_aro table table.',
'This entry with aro_id 0 should be removed, as it\'s not allowed.' => 'This entry with aro_id 0 should be removed, as it\'s not allowed.',
'by clicking here' => 'by clicking here',
'Users' => 'Users',
'CB fields data storage' => 'CB fields data storage',
'CB Tools: Check %s database: Results' => 'CB Tools: Check %s database: Results',
'Added %s new entries to core_acl_aro table from users Table.' => 'Added %s new entries to core_acl_aro table from users Table.',
'Deleted %s core_acl_aro entries which didn\'t correspond to users table.' => 'Deleted %s core_acl_aro entries which didn\'t correspond to users table.',
'Added %s new entries to core_acl_groups_aro_map table from core_acl_aro Table.' => 'Added %s new entries to core_acl_groups_aro_map table from core_acl_aro Table.',
'Deleted %s core_acl_groups_aro_map entries which didn\'t correspond to core_acl_aro table.' => 'Deleted %s core_acl_groups_aro_map entries which didn\'t correspond to core_acl_aro table.',
'Joomla/Mambo User Table and Joomla/Mambo ACL Table should now be in sync!' => 'Joomla/Mambo User Table and Joomla/Mambo ACL Table should now be in sync!',
'CB Tools: Fix %s database: ' => 'CB Tools: Fix %s database: ',
'Dry-run:' => 'Dry-run:',
'Fixed:' => 'Fixed:',
'Results' => 'Results',
'Deleted %s comprofiler_field_values entries which didn\'t match any field.' => 'Deleted %s comprofiler_field_values entries which didn\'t match any field.',
'saveOrder:%s' => 'saveOrder:%s',
'New ordering saved' => 'New ordering saved',
'Select Type' => 'Select Type',
'Successfully Saved changes to Plugin: %s' => 'Successfully Saved changes to Plugin: %s',
'Successfully Saved Plugin: %s' => 'Successfully Saved Plugin: %s',
'The plugin %s is currently being edited by another administrator' => 'The plugin %s is currently being edited by another administrator',
'Administrator' => 'Administrator',
'Plugin id not found.' => 'Plugin id not found.',
'No plugin XML found.' => 'No plugin XML found.',
'No admin handler defined in XML' => 'No admin handler defined in XML',
'Admin handler class %s does not exist.' => 'Admin handler class %s does not exist.',
'No plugin selected' => 'No plugin selected',
'The plugin %s has no administrator file %s' => 'The plugin %s has no administrator file %s',
'Select a plugin to delete' => 'Select a plugin to delete',
'Uninstall Plugin' => 'Uninstall Plugin',
'Get Plugins' => 'Get Plugins',
'Success' => 'Success',
'Failed' => 'Failed',
'publish' => 'publish',
'unpublish' => 'unpublish',
'Select a plugin to %s' => 'Select a plugin to %s',
'Language plugins cannot be unpublished, only uninstalled' => 'Language plugins cannot be unpublished, only uninstalled',
'Core plugin cannot be unpublished' => 'Core plugin cannot be unpublished',
'Plugin can not be found' => 'Plugin can not be found',
'The installer cannot continue before file uploads are enabled. Please use the install from directory method.' => 'The installer cannot continue before file uploads are enabled. Please use the install from directory method.',
'Installer - Error' => 'Installer - Error',
'The installer cannot continue before zlib is installed' => 'The installer cannot continue before zlib is installed',
'No file selected' => 'No file selected',
'Upload new plugin - error' => 'Upload new plugin - error',
'Upload %s - Upload Failed' => 'Upload %s - Upload Failed',
'Upload %s - ' => 'Upload %s - ',
'Upload %s - Upload Error' => 'Upload %s - Upload Error',
'Failed to move uploaded file to %s directory.' => 'Failed to move uploaded file to %s directory.',
'Upload failed as %s directory is not writable.' => 'Upload failed as %s directory is not writable.',
'Upload failed as %s directory does not exist.' => 'Upload failed as %s directory does not exist.',
'Install new plugin from directory - error' => 'Install new plugin from directory - error',
'Install new plugin from directory %s' => 'Install new plugin from directory %s',
'No URL selected' => 'No URL selected',
'Download %s - Upload Failed' => 'Download %s - Upload Failed',
'Download %s' => 'Download %s',
'Download %s - Download Error' => 'Download %s - Download Error',
'Failed to change the permissions of the uploaded file %s' => 'Failed to change the permissions of the uploaded file %s',
'Failed to create and write uploaded file in %s' => 'Failed to create and write uploaded file in %s',
'Failed to download package file from <code>%s</code> to webserver due to following error: %s' => 'Failed to download package file from <code>%s</code> to webserver due to following error: %s',
'Failed to download package file from <code>%s</code> to webserver due to following status: %s' => 'Failed to download package file from <code>%s</code> to webserver due to following status: %s',
'Connection to update server failed' => 'Connection to update server failed',
'ERROR' => 'ERROR',
'Timeout' => 'Timeout',
'no field' => 'no field',
'Uncompressing %s failed.' => 'Uncompressing %s failed.',
'Failed to create directory "%s"' => 'Failed to create directory "%s"',
'Copying plugin files failed with error: %s' => 'Copying plugin files failed with error: %s',
'Deleting expanded tgz file directory failed with an error.' => 'Deleting expanded tgz file directory failed with an error.',
'Deleting file %s failed with an error.' => 'Deleting file %s failed with an error.',
'Second and last installation step of Community Builder Component (comprofiler) done successfully.' => 'Second and last installation step of Community Builder Component (comprofiler) done successfully.',
'Installation finished. Important: Please read README.TXT and installation manual for further settings.' => 'Installation finished. Important: Please read README.TXT and installation manual for further settings.',
'We also have a PDF installation guide as well as a complete documentation available on' => 'We also have a PDF installation guide as well as a complete documentation available on',

// .../administrator/components/com_comprofiler/admin.comprofiler.html.php (357 in CBTxt format) //
'In order for CB to function properly a Joomla/Mambo menu item must be present. This menu item must also be published for PUBLIC access. It appears that this environment is missing this mandatory menu item. Please refer to the section titled "Adding the CB Profile" of the PDF installation guide included in your CB distribution package for additional information regarding this matter.'	=>	'In order for CB to function properly a Joomla/Mambo menu item must be present. This menu item must also be published for PUBLIC access. It appears that this environment is missing this mandatory menu item. Please refer to the section titled "Adding the CB Profile" of the PDF installation guide included in your CB distribution package for additional information regarding this matter.',
'PHP Version %s is not compatible with %s: Please upgrade to PHP %s or greater.'	=>	'PHP Version %s is not compatible with %s: Please upgrade to PHP %s or greater.',
'at least version %s, recommended version %s'	=>	'at least version %s, recommended version %s',
'CB List Manager' => 'CB List Manager',
'Search' => 'Search',
'#' => '#',
'Title' => 'Title',
'Description' => 'Description',
'Published' => 'Published',
'Access' => 'Access',
'Re-Order' => 'Re-Order',
'Save Order' => 'Save Order',
'listid' => 'listid',
'Move Up' => 'Move Up',
'Move Down' => 'Move Down',
'Community Builder List' => 'Community Builder List',
'List is not published' => 'List is not published',
'Sort Randomly' => 'Sort Randomly',
'Non-existing field' => 'Non-existing field',
'Following fields are in list but not visible in here for following reason(s)' => 'Following fields are in list but not visible in here for following reason(s)',
'Field "%s (%s)" is not published !' => 'Field "%s (%s)" is not published !',
'Field "%s (%s)" is not displayed on profile !' => 'Field "%s (%s)" is not displayed on profile !',
'Field "%s (%s)" is from plugin "%s" but this plugin is not published !' => 'Field "%s (%s)" is from plugin "%s" but this plugin is not published !',
'If you save this users list now, the fields listed above will be removed from this users list. If you want to keep these fields in this list, cancel now and go to Components / Community Builder / Field Manager.' => 'If you save this users list now, the fields listed above will be removed from this users list. If you want to keep these fields in this list, cancel now and go to Components / Community Builder / Field Manager.',
'Commas are not allowed in size values' => 'Commas are not allowed in size values',
'You must define a condition text!' => 'You must define a condition text!',
'URL for menu link to this list' => 'URL for menu link to this list',
'You need to save this new list first to see the direct menu link url.' => 'You need to save this new list first to see the direct menu link url.',
'URL for search link to this list' => 'URL for search link to this list',
'Only fields appearing in list columns and on profiles and which are have the searchable attribute ON will appear in search criterias of the list.' => 'Only fields appearing in list columns and on profiles and which are have the searchable attribute ON will appear in search criterias of the list.',
'Title appears in frontend on top of the list.' => 'Title appears in frontend on top of the list.',
'Description appears in frontend under the title of the list.' => 'Description appears in frontend under the title of the list.',
'User Group to allow access to' => 'User Group to allow access to',
'All groups above that level will also have access to the list.' => 'All groups above that level will also have access to the list.',
'User Groups to Include in List' => 'User Groups to Include in List',
'Multiple choices' => 'Multiple choices',
'CTRL/CMD-click to add/remove single choices.' => 'CTRL/CMD-click to add/remove single choices.',
'WARNING' => 'WARNING',
'The default list should be the one with the lowest user groups access rights !' => 'The default list should be the one with the lowest user groups access rights !',
'Sort By' => 'Sort By',
'ASC' => 'ASC',
'DESC' => 'DESC',
'Add' => 'Add',
'+' => '+',
'-' => '-',
'Remove' => 'Remove',
'Filter' => 'Filter',
'Simple' => 'Simple',
'Advanced' => 'Advanced',
'Greater Than' => 'Greater Than',
'Greater Than or Equal To' => 'Greater Than or Equal To',
'Less Than' => 'Less Than',
'Less Than or Equal To' => 'Less Than or Equal To',
'Equal To' => 'Equal To',
'Not Equal To' => 'Not Equal To',
'Is Empty' => 'Is Empty',
'Is Not Empty' => 'Is Not Empty',
'Is NULL' => 'Is NULL',
'Is Not NULL' => 'Is Not NULL',
'Like' => 'Like',
'Filter By' => 'Filter By',
'<strong>Note:</strong> fields must be on profile to appear in this list and be visible on the users-list.' => '<strong>Note:</strong> fields must be on profile to appear in this list and be visible on the users-list.',
'Enable Column 1' => 'Enable Column 1',
'Column 1 Title' => 'Column 1 Title',
'Column 1 Captions' => 'Column 1 Captions',
'Field List' => 'Field List',
'<- Add' => '<- Add',
'Add ->' => 'Add ->',
'Enable Column 2' => 'Enable Column 2',
'Column 2 Title' => 'Column 2 Title',
'Column 2 Captions' => 'Column 2 Captions',
'Enable Column 3' => 'Enable Column 3',
'Column 3 Title' => 'Column 3 Title',
'Column 3 Captions' => 'Column 3 Captions',
'Enable Column 4' => 'Enable Column 4',
'Column 4 Title' => 'Column 4 Title',
'Column 4 Captions' => 'Column 4 Captions',
'CB Field Manager' => 'CB Field Manager',
'Name' => 'Name',
'Type' => 'Type',
'Required' => 'Required',
'Profile' => 'Profile',
'Registration' => 'Registration',
'Searchable' => 'Searchable',
'(1 Line)' => '(1 Line)',
'(2 Lines)' => '(2 Lines)',
'field will not be visible as field plugin "%s" is not published.' => 'field will not be visible as field plugin "%s" is not published.',
'field will not be visible as connections are not enabled in CB configuration.' => 'field will not be visible as connections are not enabled in CB configuration.',
'field will not be visible as tab is not enabled.' => 'field will not be visible as tab is not enabled.',
'field will not be visible as tab\'s plugin "%s" is not published.' => 'field will not be visible as tab\'s plugin "%s" is not published.',
'System-fields cannot be published/unpublished here.' => 'System-fields cannot be published/unpublished here.',
'Name-fields publishing depends on your setting in global CB config.' => 'Name-fields publishing depends on your setting in global CB config.',
'Community Builder Field' => 'Community Builder Field',
'Field is not published' => 'Field is not published',
'Plugin is not installed' => 'Plugin is not installed',
'Plugin is not published' => 'Plugin is not published',
'Warning: SQL name of field has been changed to fit SQL constraints' => 'Warning: SQL name of field has been changed to fit SQL constraints',
'Description/"i" field-tip: text or HTML' => 'Description/"i" field-tip: text or HTML',
'Pre-filled default value at registration only' => 'Pre-filled default value at registration only',
'Default value' => 'Default value',
'Show on Profile' => 'Show on Profile',
'Display field title in Profile' => 'Display field title in Profile',
'Searchable in users-lists' => 'Searchable in users-lists',
'User Read Only' => 'User Read Only',
'Show at Registration' => 'Show at Registration',
'Size' => 'Size',
'Max Length' => 'Max Length',
'Cols' => 'Cols',
'Rows' => 'Rows',
'Use the table below to add new values.' => 'Use the table below to add new values.',
'Add a Value' => 'Add a Value',
'Value' => 'Value',
'CB Tab Manager' => 'CB Tab Manager',
'Display' => 'Display',
'Plugin' => 'Plugin',
'Position' => 'Position',
'Tabid' => 'Tabid',
'tab will not be visible as plugin is not published.' => 'tab will not be visible as plugin is not published.',
'Community Builder Tab' => 'Community Builder Tab',
'Tab is not published' => 'Tab is not published',
'You must provide a title.' => 'You must provide a title.',
'Tab Details' => 'Tab Details',
'Title as will appear on tab.' => 'Title as will appear on tab.',
'Description: This description appears only on user edit, not on profile (For profile text, use delimiter fields)' => 'Description: This description appears only on user edit, not on profile (For profile text, use delimiter fields)',
'Publish' => 'Publish',
'Profile ordering' => 'Profile ordering',
'Tabs and fields on profile are ordered as follows:' => 'Tabs and fields on profile are ordered as follows:',
'position of tab on user profile (top-down, left-right)' => 'position of tab on user profile (top-down, left-right)',
'This ordering of tab on position of user profile' => 'This ordering of tab on position of user profile',
'ordering of field within tab position of user profile.' => 'ordering of field within tab position of user profile.',
'Registration ordering' => 'Registration ordering',
'(default value: 10)' => '(default value: 10)',
'Tabs and fields on registration are ordered as follows:' => 'Tabs and fields on registration are ordered as follows:',
'This registration ordering of tab' => 'This registration ordering of tab',
'ordering of tab on position of user profile' => 'ordering of tab on position of user profile',
'Position on profile and ordering on registration.' => 'Position on profile and ordering on registration.',
'Display type' => 'Display type',
'In which way the content of this tab will be displayed on the profile.' => 'In which way the content of this tab will be displayed on the profile.',
'No Parameters' => 'No Parameters',
'Advanced Search' => 'Advanced Search',
'CB Email Users' => 'CB Email Users',
'Send Email to %s users' => 'Send Email to %s users',
'and %s more users.' => 'and %s more users.',
'Simulation mode' => 'Simulation mode',
'Do not send emails, just show me how it works' => 'Do not send emails, just show me how it works',
'Email Subject' => 'Email Subject',
'Send me a test email' => 'Send me a test email',
'Email Message' => 'Email Message',
'CB substitutions for subject and message' => 'CB substitutions for subject and message',
'You can use all CB substitutions as in most parts: e.g.: [cb:if team="winners"] Congratulations [cb:userfield field="name" /], you are in the winning team! [/cb:if]' => 'You can use all CB substitutions as in most parts: e.g.: [cb:if team="winners"] Congratulations [cb:userfield field="name" /], you are in the winning team! [/cb:if]',
'Emails per batch' => 'Emails per batch',
'Seconds of pause between batches' => 'Seconds of pause between batches',
'CB Sending emails to users...please wait and do not interrupt!' => 'CB Sending emails to users...please wait and do not interrupt!',
'Sending a batch of maximum %s emails...' => 'Sending a batch of maximum %s emails...',
'Sending now %s emails...' => 'Sending now %s emails...',
'Sent your email.' => 'Sent your email.',
'Sent all %s emails.' => 'Sent all %s emails.',
'CB Sending emails to users' => 'CB Sending emails to users',
'Sending Email to %s users' => 'Sending Email to %s users',
'Initiating...' => 'Initiating...',
'Sent email to %s of %s users' => 'Sent email to %s of %s users',
'Just sent %s emails to following users:' => 'Just sent %s emails to following users:',
'Still %s emails remaining to send.' => 'Still %s emails remaining to send.',
'Your email has been sent.' => 'Your email has been sent.',
'All %s emails have been sent.' => 'All %s emails have been sent.',
'Email Sent' => 'Email Sent',
'Click here to go back to users management' => 'Click here to go back to users management',
'CB User Manager' => 'CB User Manager',
'UserName' => 'UserName',
'Group' => 'Group',
'E-Mail' => 'E-Mail',
'Registered' => 'Registered',
'Last Visit' => 'Last Visit',
'ID' => 'ID',
'Pending Approval' => 'Pending Approval',
'Rejected' => 'Rejected',
'confirmed' => 'confirmed',
'unconfirmed' => 'unconfirmed',
'Community Builder User' => 'Community Builder User',
'You must assign user to a group.' => 'You must assign user to a group.',
'Use new div or old table based views' => 'Use new div or old table based views',
'Choose table for compatibility with old templates and div for table-less output.' => 'Choose table for compatibility with old templates and div for table-less output.',
'WARNING: different from the CMS setting !' => 'WARNING: different from the CMS setting !',
'This may be ok, but this warning is just to make you aware of the difference.' => 'This may be ok, but this warning is just to make you aware of the difference.',
'Translations highlighting' => 'Translations highlighting',
'Here you can highlight and debug your translations in various ways.' => 'Here you can highlight and debug your translations in various ways.',
'CB Tools Manager' => 'CB Tools Manager',
'Load Sample Data' => 'Load Sample Data',
'This will load sample data into the Joomla/Mambo Community Builder component. Precisely, an additional information tab (that you can change, unpublish or delete in CB Tabs manager) will be created containing fields for: location, occupation, interests, company, address, city, state, zipcode, country, phone and fax (you can then change, unpublish or delete those fields which you don\'t need in CB Fields Manager). Also a users-list will be created, that you can edit from the CB Lists manager. This will help you get started quicker with CB.' => 'This will load sample data into the Joomla/Mambo Community Builder component. Precisely, an additional information tab (that you can change, unpublish or delete in CB Tabs manager) will be created containing fields for: location, occupation, interests, company, address, city, state, zipcode, country, phone and fax (you can then change, unpublish or delete those fields which you don\'t need in CB Fields Manager). Also a users-list will be created, that you can edit from the CB Lists manager. This will help you get started quicker with CB.',
'Synchronize Users' => 'Synchronize Users',
'This will synchronize the Joomla/Mambo User table with the Joomla/Mambo Community Builder User Table.' => 'This will synchronize the Joomla/Mambo User table with the Joomla/Mambo Community Builder User Table.',
'Please make sure before synchronizing that the user name type (first/lastname mode choice) is set correctly in Components / Community Builder / Configuration / General, so that the user-synchronization imports the names in the appropriate format.' => 'Please make sure before synchronizing that the user name type (first/lastname mode choice) is set correctly in Components / Community Builder / Configuration / General, so that the user-synchronization imports the names in the appropriate format.',
'Check Community Builder Database' => 'Check Community Builder Database',
'This will perform a series of tests on the Community Builder database and report back potential inconsistencies without changing or correcting the database.' => 'This will perform a series of tests on the Community Builder database and report back potential inconsistencies without changing or correcting the database.',
'Check Community Builder User Fields Database' => 'Check Community Builder User Fields Database',
'This will perform a series of tests on the Community Builder User fields database and report back potential inconsistencies without changing or correcting the database.' => 'This will perform a series of tests on the Community Builder User fields database and report back potential inconsistencies without changing or correcting the database.',
'Check CB plugins database' => 'Check CB plugins database',
'This will check the database of installed CB plugins and report back potential inconsistencies without changing or correcting the database.' => 'This will check the database of installed CB plugins and report back potential inconsistencies without changing or correcting the database.',
'Check Users Database' => 'Check Users Database',
'This will perform a series of tests on the Users database of the CMS, the Community Builder users database and ACL and report back potential inconsistencies without changing or correcting the database.' => 'This will perform a series of tests on the Users database of the CMS, the Community Builder users database and ACL and report back potential inconsistencies without changing or correcting the database.',
'Database adjustments dryrun is successful, see results below' => 'Database adjustments dryrun is successful, see results below',
'Database adjustments have been performed successfully.' => 'Database adjustments have been performed successfully.',
'All' => 'All',
'Database is up to date.' => 'Database is up to date.',
'Database adjustments errors:' => 'Database adjustments errors:',
'Database structure differences:' => 'Database structure differences:',
'The %s database structure differences can be fixed (adjusted) by clicking here' => 'The %s database structure differences can be fixed (adjusted) by clicking here',
'Click here to Fix (adjust) all %s database differences listed above' => 'Click here to Fix (adjust) all %s database differences listed above',
'(you can also <a href="#">Click here to preview fixing (adjusting) queries in a dry-run</a>), but <strong>in all cases you need to backup database first</strong> as this adjustment is changing the database structure to match the needed structure for the installed version.' => '(you can also <a href="#">Click here to preview fixing (adjusting) queries in a dry-run</a>), but <strong>in all cases you need to backup database first</strong> as this adjustment is changing the database structure to match the needed structure for the installed version.',
'Click here to Show details' => 'Click here to Show details',
'Click here to Hide details' => 'Click here to Hide details',
'Dry-run of %s database adjustments done. None of the queries listed in details have been performed.' => 'Dry-run of %s database adjustments done. None of the queries listed in details have been performed.',
'The database adjustments listed above can be applied by clicking here' => 'The database adjustments listed above can be applied by clicking here',
'Click here to Fix (adjust) all database differences listed above.' => 'Click here to Fix (adjust) all database differences listed above.',
'<strong>You need to backup database first</strong> as this fixing/adjusting is changing the database structure to match the needed structure for the installed version.' => '<strong>You need to backup database first</strong> as this fixing/adjusting is changing the database structure to match the needed structure for the installed version.',
'The %s database adjustments have been done. If all lines above are in green, database adjustments completed successfully. Otherwise, if some lines are red, please report exact errors and queries to authors forum, and try checking database again.' => 'The %s database adjustments have been done. If all lines above are in green, database adjustments completed successfully. Otherwise, if some lines are red, please report exact errors and queries to authors forum, and try checking database again.',
'The database structure can be checked again by clicking here' => 'The database structure can be checked again by clicking here',
'Click here to Check %s database' => 'Click here to Check %s database',
'database checks done. If all lines above are in green, test completed successfully. Otherwise, please take corrective measures proposed in red.' => 'database checks done. If all lines above are in green, test completed successfully. Otherwise, please take corrective measures proposed in red.',
'CB Plugin Manager' => 'CB Plugin Manager',
'Install Plugin' => 'Install Plugin',
'Please select a directory' => 'Please select a directory',
'Plugin Name' => 'Plugin Name',
'Installed' => 'Installed',
'Reorder' => 'Reorder',
'Order' => 'Order',
'Directory' => 'Directory',
'Plugin Files missing' => 'Plugin Files missing',
'Unpublished' => 'Unpublished',
'Unpublish Item' => 'Unpublish Item',
'Publish item' => 'Publish item',
'language plugins cannot be unpublished, only uninstalled' => 'language plugins cannot be unpublished, only uninstalled',
'CB core plugin cannot be unpublished' => 'CB core plugin cannot be unpublished',
'Click here to see more CB Plugins (Languages, Fields, Tabs, Signup-Connect, Paid Memberships and over 30 more) by CB Team at joomlapolis.com' => 'Click here to see more CB Plugins (Languages, Fields, Tabs, Signup-Connect, Paid Memberships and over 30 more) by CB Team at joomlapolis.com',
'Click here to see CB Directory listing hundreds of CB extensions at joomlapolis.com' => 'Click here to see CB Directory listing hundreds of CB extensions at joomlapolis.com',
'Click here to Check our CB listing on JED and find more third-party free add-ons for your website' => 'Click here to Check our CB listing on JED and find more third-party free add-ons for your website',
'Install New Plugin' => 'Install New Plugin',
'Upload Package File' => 'Upload Package File',
'Maximum upload size: <strong>[filesize]</strong> <em>(upload_max_filesize setting in file [php.ini] )</em>' => 'Maximum upload size: <strong>[filesize]</strong> <em>(upload_max_filesize setting in file [php.ini] )</em>',
'Package File:' => 'Package File:',
'Upload File & Install' => 'Upload File & Install',
'Install from directory' => 'Install from directory',
'Install directory' => 'Install directory',
'Install' => 'Install',
'Install package from web (http/https)' => 'Install package from web (http/https)',
'Installation package URL' => 'Installation package URL',
'Download Package & Install' => 'Download Package & Install',
'Community Builder Plugin' => 'Community Builder Plugin',
'Plugin Common Settings' => 'Plugin Common Settings',
'Plugin Order' => 'Plugin Order',
'Access Level' => 'Access Level',
'Folder / File' => 'Folder / File',
'Specific Plugin Settings' => 'Specific Plugin Settings',
'Plugin not installed' => 'Plugin not installed',
'Continue ...' => 'Continue ...',
'Writeable' => 'Writeable',
'Unwriteable' => 'Unwriteable',
'Update check' => 'Update check',
'Checking for updates...' => 'Checking for updates...',
'check now' => 'check now',
'Checking latest version now...' => 'Checking latest version now...',
'There was a problem with the request.' => 'There was a problem with the request.',

// .../administrator/components/com_comprofiler/imgToolbox.class.php (31 in CBTxt format) //
'Error: your ImageMagick path is not correct! Please (re)specify it in the Admin-system under "Settings"' => 'Error: your ImageMagick path is not correct! Please (re)specify it in the Admin-system under "Settings"',
'Error: your NetPBM path is not correct! Please (re)specify it in the Admin-system under "Settings"' => 'Error: your NetPBM path is not correct! Please (re)specify it in the Admin-system under "Settings"',
'PHP running on your server does not support the GD image library, check with your webhost if ImageMagick is installed' => 'PHP running on your server does not support the GD image library, check with your webhost if ImageMagick is installed',
'Error: PHP running on your server does not support the GD image library, check with your webhost if ImageMagick is installed' => 'Error: PHP running on your server does not support the GD image library, check with your webhost if ImageMagick is installed',
'Error: PHP running on your server does not support GD graphics library version 2.x, please install GD version 2.x or switch to another images library in Community Builder Configuration.' => 'Error: PHP running on your server does not support GD graphics library version 2.x, please install GD version 2.x or switch to another images library in Community Builder Configuration.',
'The file exceeds the maximum size of %s kilobytes' => 'The file exceeds the maximum size of %s kilobytes',
'Error occurred during the moving of the uploaded file. Method: %s' => 'Error occurred during the moving of the uploaded file. Method: %s',
'Move' => 'Move',
'Rename' => 'Rename',
'Copy' => 'Copy',
'In Memory' => 'In Memory',
'Error rotating image' => 'Error rotating image',
'Error: resizing image failed.' => 'Error: resizing image failed.',
'Error: resizing thumbnail image failed.' => 'Error: resizing thumbnail image failed.',
'Error: image format is not supported.' => 'Error: image format is not supported.',
'Error: %s is not a supported image format.' => 'Error: %s is not a supported image format.',
'Error: Unable to execute getimagesize function' => 'Error: Unable to execute getimagesize function',
'Error: NetPBM does not support this file type.' => 'Error: NetPBM does not support this file type.',
'Error: GD1 does not support this file type.' => 'Error: GD1 does not support this file type.',
'Error: GD1 Unable to create image from imagetype function' => 'Error: GD1 Unable to create image from imagetype function',
'Error: GD2 Unable to create image from imagetype function' => 'Error: GD2 Unable to create image from imagetype function',
'Error: GIF Uploads are not supported by this version of GD' => 'Error: GIF Uploads are not supported by this version of GD',
'Image Name' => 'Image Name',
'Error type' => 'Error type',

// .../administrator/components/com_comprofiler/library/cb/cb.pagination.php (13 in CBTxt format) //
'Display #' => 'Display #',
'Yes' => 'Yes',
'Disable Item' => 'Disable Item',
'Enable item' => 'Enable item',

// .../administrator/components/com_comprofiler/plugin.class.php (2 in CBTxt format) //
'is now' => 'is now',
'%s is now %s' => '%s is now %s',

// .../components/com_comprofiler/plugin/user/plug_cbcore/cb.core.php (22 in CBTxt format) //
'Not a valid input' => 'Not a valid input',
'Unknown field %s' => 'Unknown field %s',
'********' => '********',
'Unknown Output Format' => 'Unknown Output Format',
'Min setting > Max setting !' => 'Min setting > Max setting !',
'Not an integer' => 'Not an integer',
'Unexpected cbCheckMail result: %s' => 'Unexpected cbCheckMail result: %s',
'Block User' => 'Block User',
'Approve User' => 'Approve User',
'Confirm User' => 'Confirm User',
'Receive Moderator Emails' => 'Receive Moderator Emails',
'No (User\'s group-level doesn\'t allow this)' => 'No (User\'s group-level doesn\'t allow this)',
'Register Date' => 'Register Date',
'Last Visit Date' => 'Last Visit Date',

// .../components/com_comprofiler/plugin/user/plug_cbsimpleboardtab/cb.simpleboardtab.model.php (27 in CBTxt format) //
'Moderator' => 'Moderator',
'ONLINE' => 'ONLINE',
'OFFLINE' => 'OFFLINE',
'Online Status: ' => 'Online Status: ',
'View Profile: ' => 'View Profile: ',
'Send Private Message: ' => 'Send Private Message: ',
'Subject' => 'Subject',
'Category' => 'Category',
'Hits' => 'Hits',
'Joomlaboard' => 'Joomlaboard',
'Simpleboard' => 'Simpleboard',
'Fireboard' => 'Fireboard',
'Kunena (It is advised to select Kunena manually as Kunena has additional options)' => 'Kunena (It is advised to select Kunena manually as Kunena has additional options)',
'Kunena' => 'Kunena',

// .../components/com_comprofiler/plugin/user/plug_cbsimpleboardtab/cb.simpleboardtab.php (4 in CBTxt format) //
'The forum component is not installed.  Please contact your site administrator.' => 'The forum component is not installed.  Please contact your site administrator.',
'Found %s Forum Posts' => 'Found %s Forum Posts',
'Forum Posts' => 'Forum Posts',
'Last %s Forum Posts' => 'Last %s Forum Posts',

// .../components/com_comprofiler/plugin/user/plug_cbsimpleboardtab/view/cb.simpleboardtab.sidebar.php (2 in CBTxt format) //
'Karma: ' => 'Karma: ',
'Posts: ' => 'Posts: ',

// .../components/com_comprofiler/plugin/user/plug_cbsimpleboardtab/view/cb.simpleboardtab.tab.php (20 in CBTxt format) //
'Forum Statistics' => 'Forum Statistics',
'Forum Ranking' => 'Forum Ranking',
'No matching forum posts found.' => 'No matching forum posts found.',
'This user has no forum posts.' => 'This user has no forum posts.',
'Your Subscriptions' => 'Your Subscriptions',
'Action' => 'Action',
'Are you sure you want to unsubscribe from this forum subscription?' => 'Are you sure you want to unsubscribe from this forum subscription?',
'Unsubscribe' => 'Unsubscribe',
'Are you sure you want to unsubscribe from all your forum subscriptions?' => 'Are you sure you want to unsubscribe from all your forum subscriptions?',
'Unsubscribe All' => 'Unsubscribe All',
'No subscriptions found for you.' => 'No subscriptions found for you.',
'Your Favorites' => 'Your Favorites',
'Are you sure you want to remove this favorite thread?' => 'Are you sure you want to remove this favorite thread?',
'Are you sure you want to remove all your favorite threads?' => 'Are you sure you want to remove all your favorite threads?',
'Remove All' => 'Remove All',
'No favorites found for you.' => 'No favorites found for you.',

));

// IMPORTANT WARNING: The closing tag, "?" and ">" has been intentionally omitted - CB works fine without it.
// This was done to avoid errors caused by custom strings being added after the closing tag. ]
// With such tags, always watchout to NOT add any line or space or anything after the "?" and the ">".
