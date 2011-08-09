<?php
/**
 * $Id: german.php 162 2011-07-21 02:38:15Z guilleva $
 * $LastChangedDate: 2011-07-20 20:38:15 -0600 (Wed, 20 Jul 2011) $
 * $LastChangedBy: guilleva $
 * Xmap by Guillermo Vargas
 * A sitemap component for Joomla! CMS (http://www.joomla.org)
 * Author Website: http://joomla.vargas.co.cr
 * Project License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Language file by Daniel Grothe, http://www.ko-ca.com/
*/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

if( !defined('JOOMAP_LANG')) {
    define('JOOMAP_LANG', 1);
    // -- General ------------------------------------------------------------------
    define('_XMAP_CFG_COM_TITLE',    'Xmap-Konfiguration');
    define('_XMAP_CFG_OPTIONS',    'Anzeige-Einstellungen');
    define('_XMAP_CFG_CSS_CLASSNAME',    'CSS-Klassenname');
    define('_XMAP_CFG_EXPAND_CATEGORIES',    'Kategorien ausklappen');
    define('_XMAP_CFG_EXPAND_SECTIONS',    'Bereiche ausklappen');
    define('_XMAP_CFG_SHOW_MENU_TITLES',    'Men&uuml;titel anzeigen');
    define('_XMAP_CFG_NUMBER_COLUMNS',    'Spaltenanzahl');
    define('_XMAP_EX_LINK',    'Externe Links markieren');
    define('_XMAP_CFG_CLICK_HERE',    'Hier klicken');
    define('_XMAP_CFG_GOOGLE_MAP',    'Google-Sitemap');
    define('_XMAP_EXCLUDE_MENU',    'Men&uuml;-IDs ausschließen');
    define('_XMAP_TAB_DISPLAY',    'Anzeige');
    define('_XMAP_TAB_MENUS',    'Men&uuml;s');
    define('_XMAP_CFG_WRITEABLE',    'Beschreibbar');
    define('_XMAP_CFG_UNWRITEABLE',    'Schreibgesch&uuml;tzt');
    define('_XMAP_MSG_MAKE_UNWRITEABLE',    'Nach dem Speichern auf Nur-Lesen setzen');
    define('_XMAP_MSG_OVERRIDE_WRITE_PROTECTION',    'Schreibschutz beim Speichern &uuml;berschreiben');
    define('_XMAP_GOOGLE_LINK',    'Google-Link');
    define('_XMAP_CFG_INCLUDE_LINK',    'Link zum Autor einfügen');

    // -- Tips ---------------------------------------------------------------------
    define('_XMAP_EXCLUDE_MENU_TIP',    'Geben Sie die ID des Men&uuml;s an, das in die Sitemap aufgenommen werden soll.<br /><strong>BEMERKUNG</strong><br />Mehrere IDs durch Kommata trennen!');
    // -- Menus --------------------------------------------------------------------
    define('_XMAP_CFG_SET_ORDER',    'Reihenfolge der Men&uuml;-Anzeige');
    define('_XMAP_CFG_MENU_SHOW',    'Zeigen');
    define('_XMAP_CFG_MENU_REORDER',    'Neu ordnen');
    define('_XMAP_CFG_MENU_ORDER',    'Reihenfolge');
    define('_XMAP_CFG_MENU_NAME',    'Name des Men&uuml;s');
    define('_XMAP_CFG_DISABLE',    'Zum Deaktivieren klicken');
    define('_XMAP_CFG_ENABLE',    'Zum Aktivieren klicken');
    define('_XMAP_SHOW',    'Anzeigen');
    define('_XMAP_NO_SHOW',    'Nicht anzeigen');

    // -- Toolbar ------------------------------------------------------------------
    define('_XMAP_TOOLBAR_SAVE',    'Speichern');
    define('_XMAP_TOOLBAR_CANCEL',    'Abbrechen');

    // -- Errors -------------------------------------------------------------------
    define('_XMAP_ERR_NO_LANG',    'Sprachdatei [ %s ] nicht gefunden. Die Standardsprache Englisch wird geladen<br />');
    define('_XMAP_ERR_CONF_SAVE',    'FEHLER: Konfiguration konnte nicht gespeichert werden.');
    define('_XMAP_ERR_NO_CREATE',    'FEHLER: Tabelle mit den Einstellungen konnte nicht gespeichert werden.');
    define('_XMAP_ERR_NO_DEFAULT_SET',    'FEHLER: Die Standardeinstellungen konnten nicht einf&uuml;gt werden.');
    define('_XMAP_ERR_NO_PREV_BU',    'WARNUNG: Vorherige Sicherung konnte nicht gel&ouml;scht werden.');
    define('_XMAP_ERR_NO_BACKUP',    'FEHLER: Sicherung konnte nicht erstellt werden.');
    define('_XMAP_ERR_NO_DROP_DB',    'FEHLER: Tabelle mit den Einstellungen konnte nicht gel&ouml;scht werden.');
    define('_XMAP_ERR_NO_SETTINGS',    'FEHLER: Einstellungen konnten nicht aus der Datenbank geladen werden: <a href="%s">Tabelle f&uuml;r die Einstellungen erstellen</a>');
    

    // -- Config -------------------------------------------------------------------
    define('_XMAP_MSG_SET_RESTORED',    'Einstellungen wurden wieder hergestellt');
    define('_XMAP_MSG_SET_BACKEDUP',    'Einstellungen wurden gespeichert');
    define('_XMAP_MSG_SET_DB_CREATED',    'Tabelle f&uuml;r die Einstellungen wurde erstellt');
    define('_XMAP_MSG_SET_DEF_INSERT',    'Standardeinstellungen wurden eingef&uuml;gt');
    define('_XMAP_MSG_SET_DB_DROPPED',    'Die Tabellen f&uuml;r Xmap wurden gespeichert!');
    
    // -- CSS ----------------------------------------------------------------------
    define('_XMAP_CSS',    'Xmap-CSS');
    define('_XMAP_CSS_EDIT',    'Template bearbeiten'); // Edit template
    
    // -- Sitemap (Frontend) -------------------------------------------------------
    define('_XMAP_SHOW_AS_EXTERN_ALT',    'Link &ouml;ffnet ein neues Fenster');
    
    // -- Added for Xmap
    define('_XMAP_CFG_MENU_SHOW_HTML',    'Anzeige in der Site');
    define('_XMAP_CFG_MENU_SHOW_XML',    'Anzeige in der XML-Sitemap');
    define('_XMAP_CFG_MENU_PRIORITY',    'Priorit&auml;t');
    define('_XMAP_CFG_MENU_CHANGEFREQ',    'H&auml;ufigkeit &auml;ndern');
    define('_XMAP_CFG_CHANGEFREQ_ALWAYS',    'Immer');
    define('_XMAP_CFG_CHANGEFREQ_HOURLY',    'St&uuml;ndlich');
    define('_XMAP_CFG_CHANGEFREQ_DAILY',    'T&auml;glich');
    define('_XMAP_CFG_CHANGEFREQ_WEEKLY',    'W&ouml;chentlich');
    define('_XMAP_CFG_CHANGEFREQ_MONTHLY',    'Monatlich');
    define('_XMAP_CFG_CHANGEFREQ_YEARLY',    'J&auml;hrlich');
    define('_XMAP_CFG_CHANGEFREQ_NEVER',    'Nie');
    
    define('_XMAP_TIT_SETTINGS_OF',    'Einstellungen f&uuml;r %s');
    define('_XMAP_TAB_SITEMAPS',    'Sitemaps');
    define('_XMAP_MSG_NO_SITEMAPS',    'Bisher wurden noch keine Sitemaps erstellt');
    define('_XMAP_MSG_NO_SITEMAP',    'Diese Sitemap ist nicht verf&uuml;gbar');
    define('_XMAP_MSG_LOADING_SETTINGS',    'Einstellungen laden…');
    define('_XMAP_MSG_ERROR_LOADING_SITEMAP',    'Fehler. Sitemap kann nicht geladen werden');
    define('_XMAP_MSG_ERROR_SAVE_PROPERTY',    'Fehler. Die Eigenschaften der Sitemap können nicht gespeichert werden.');
    define('_XMAP_MSG_ERROR_CLEAN_CACHE',    'Fehler. Der Cache der Sitemap kann nicht gel&ouml;scht werden.');
    define('_XMAP_ERROR_DELETE_DEFAULT',    'Die Standard-Sitemap kann nicht gel&ouml;scht werden!');
    define('_XMAP_MSG_CACHE_CLEANED',    'Cache gel&ouml;scht!');
    define('_XMAP_CHARSET',    'UTF-8');
    define('_XMAP_SITEMAP_ID',    'ID der Sitemap');
    define('_XMAP_ADD_SITEMAP',    'Sitemap hinzuf&uuml;gen');
    define('_XMAP_NAME_NEW_SITEMAP',    'Neue Sitemap');
    define('_XMAP_DELETE_SITEMAP',    'L&ouml;schen');
    define('_XMAP_SETTINGS_SITEMAP',    'Einstellungen');
    define('_XMAP_COPY_SITEMAP',    'Kopieren');
    define('_XMAP_SITEMAP_SET_DEFAULT',    'Als Standard');
    define('_XMAP_EDIT_MENU',    'Optionen');
    define('_XMAP_DELETE_MENU',    'L&ouml;schen');
    define('_XMAP_CLEAR_CACHE',    'Cache l&ouml;schen');
    define('_XMAP_MOVEUP_MENU',    'Aufwärts');
    define('_XMAP_MOVEDOWN_MENU',    'Abwärts');
    define('_XMAP_ADD_MENU',    'Neues Men&uuml;');
    define('_XMAP_COPY_OF',    'Kopie von %s');
    define('_XMAP_INFO_LAST_VISIT',    'Letzter Aufruf');
    define('_XMAP_INFO_COUNT_VIEWS',    'Anzahl der Aufrufe');
    define('_XMAP_INFO_TOTAL_LINKS',    'Anzahl der Links');
    define('_XMAP_CFG_URLS',    'URL der Sitemap');
    define('_XMAP_XML_LINK_TIP',    'Link kopieren und an Google und Yahoo senden');
    define('_XMAP_HTML_LINK_TIP',    'Dies ist die URL der Sitemap. Sie k&ouml;nnen diese benutzen, um Eintr&auml;ge in Men&uuml;s vorzunehmen.');
    define('_XMAP_CFG_XML_MAP',    'XML-Sitemap');
    define('_XMAP_CFG_HTML_MAP',    'HTML-Sitemap');
    define('_XMAP_XML_LINK',    'Google-Link');
    //define('_XMAP_CFG_XML_MAP_TIP',    'The XML file generated for the search engines');
    define('_XMAP_CFG_XML_MAP_TIP',    'Die f&uuml;r die Suchmaschine generierte XML-Datei');
    define('_XMAP_ADD',    'Speichern');
    define('_XMAP_CANCEL',    'Abbrechen');
    define('_XMAP_LOADING',    'Laden…');
    define('_XMAP_CACHE',    'Cache');
    define('_XMAP_USE_CACHE',    'Cache verwenden');
    define('_XMAP_CACHE_LIFE_TIME',    'Cache-Dauer');
    define('_XMAP_NEVER_VISITED',    'Nie');

    // New on Xmap 1.1 beta 1
    define('_XMAP_PLUGINS',    'Erweiterungen');    
    define('_XMAP_INSTALL_3PD_WARN',    'Warnung: Die Installation von Erweiterungen Dritter kann die Sicherheit des Servers beeintr&auml;chtigen.');
    define('_XMAP_INSTALL_NEW_PLUGIN',    'Neue Erweiterungen installieren');
    define('_XMAP_UNKNOWN_AUTHOR',    'Unbekannter Autor');
    define('_XMAP_PLUGIN_VERSION',    'Version %s');
    define('_XMAP_TAB_INSTALL_PLUGIN',    'Installieren');
    define('_XMAP_TAB_EXTENSIONS',    'Erweiterungen');
    define('_XMAP_TAB_INSTALLED_EXTENSIONS',    'Installierte Erweiterungen');
    define('_XMAP_NO_PLUGINS_INSTALLED',    'Keine benutzerdefinierte Erweiterung installiert');
    define('_XMAP_AUTHOR',    'Autor');
    define('_XMAP_CONFIRM_DELETE_SITEMAP',    'M&ouml;chten Sie diese Sitemap wirklich l&ouml;schen?');
    define('_XMAP_CONFIRM_UNINSTALL_PLUGIN',    'M&ouml;chten Sie diese Erweiterung wirklich deinstallieren?');
    define('_XMAP_UNINSTALL',    'Deinstallieren');
    define('_XMAP_EXT_PUBLISHED',    'Freigegeben');
    define('_XMAP_EXT_UNPUBLISHED',    'Nicht freigegeben');
    define('_XMAP_PLUGIN_OPTIONS',    'Optionen');
    define('_XMAP_EXT_INSTALLED_MSG',    'Die Erweiterung wurde erfolgreich installiert, bitte &uuml;berp&uuml;fen Sie seine Einstellungen und geben Sie die Erweiterung anschließend frei');
    define('_XMAP_CONTINUE',    'Fortfahren…');
    define('_XMAP_MSG_EXCLUDE_CSS_SITEMAP',    'Die CSS-Datei nicht f&uuml;r die Sitemap verwenden');
    define('_XMAP_MSG_EXCLUDE_XSL_SITEMAP',    'Klassische XML-Sitemap-Anzeige verwenden');

    // New on Xmap 1.1
    define('_XMAP_MSG_SELECT_FOLDER',    'Bitte w&auml;hlen Sie ein Verzeichnis aus');
    define('_XMAP_UPLOAD_PKG_FILE',    'Dateien hochladen');
    define('_XMAP_UPLOAD_AND_INSTALL',    'Dateien hochladen und installieren');
    define('_XMAP_INSTALL_F_DIRECTORY',    'Aus lokalem Verzeichnis installieren');
    define('_XMAP_INSTALL_DIRECTORY',    'Installationsverzeichnis');
    define('_XMAP_INSTALL',    'Installieren');
    define('_XMAP_WRITEABLE',    'Beschreibbar');
    define('_XMAP_UNWRITEABLE',    'Schreibgesch&uuml;tzt');

    // New on Xmap 1.2
    define('_XMAP_COMPRESSION',    'Komprimierung');
    define('_XMAP_USE_COMPRESSION',    'XML-Sitemap komprimieren, um Bandbreite zu sparen');
    
    // New on Xmap 1.2.1
    define('_XMAP_CFG_NEWS_MAP',    'News-Sitemap');
    define('_XMAP_NEWS_LINK_TIP',    'Dies ist die URL der News-Sitemap.');

    // New on Xmap 1.2.2
    define('_XMAP_CFG_MENU_MODULE',    'Modul');
    define('_XMAP_CFG_MENU_MODULE_TIP',    'Geben Sie das Modul an, mit dem auf Ihrer Site das Menü angezeigt wird (Standard: mod_mainmenu).');

    // New on Xmap 1.2.3
    define('_XMAP_TEXT',    'Link-Text');
    define('_XMAP_TITLE',    'Link-Titel');
    define('_XMAP_LINK',    'Link-URL');
    define('_XMAP_CSS_STYLE',    'CSS-Stil');
    define('_XMAP_CSS_CLASS',    'CSS-Klasse');
    define('_XMAP_INVALID_SITEMAP',    'Ungültige Sitemap');
    define('_XMAP_OK',    'OK');
    // New on Xmap 1.2.10
    define('_XMAP_CFG_IMAGES_MAP','Images Sitemap');

    // New on Xmap 1.2.13
    define('_XMAP_CACHE_TIP','The maximun number of time in minutes for a cache file to be stored before it is refreshed');
    define('_XMAP_MINUTES','Minutes');
}
