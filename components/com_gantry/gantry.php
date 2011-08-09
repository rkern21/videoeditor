<?php
/**
 * @package		gantry
 * @version		3.1.10 March 5, 2011
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

defined('JPATH_BASE') or die();

if (!defined('GANTRY_VERSION')) {
    /**
     * @global Gantry $gantry
     */
    global $gantry;
    
    /**
     * @name GANTRY_VERSION
     */
    define('GANTRY_VERSION', '3.1.10');

    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }

	require_once (realpath(dirname(__FILE__)) . '/core/gantryloader.class.php');

    /**
     * @param  string $path the gantry path to the class to import
     * @return 
     */
    function gantry_import($path) {
        return GantryLoader::import($path);
    }

    /**
     * Adds a script file to the document with platform based checks
     * @param  $file
     * @return void
     */
    function gantry_addScript($file) {
        gantry_import('core.gantryplatform');
        $platform = new GantryPlatform();
        $document =& JFactory::getDocument();
        $filename = basename($file);
        $relative_path = dirname($file);

        // For local url path get the local path based on checks
        $file_path = gantry_getFilePath($file);
        $url_file_checks = $platform->getJSChecks($file_path, true);
        foreach ($url_file_checks as $url_file) {
            $full_path = realpath($url_file);
            if ($full_path !== false && file_exists($full_path)) {
                $document->addScript($relative_path.'/'.basename($full_path).'?ver=3.1.10');
                break;
            }
        }
    }

    /**
     * Add inline script to the document
     * @param  $script
     * @return void
     */
    function gantry_addInlineScript($script){
        $document =& JFactory::getDocument();
        $document->addScriptDeclaration($script);
    }

    /**
     * Add a css style file to the document with browser based checks
     * @param  $file
     * @return void
     */
    function gantry_addStyle($file){
        gantry_import('core.gantrybrowser');
        $browser = new GantryBrowser();
        $document =& JFactory::getDocument();
        $filename = basename($file);
        $relative_path = dirname($file);

        // For local url path get the local path based on checks
        $file_path = gantry_getFilePath($file);
        $url_file_checks = $browser->getChecks($file_path, true);
        foreach ($url_file_checks as $url_file) {
            $full_path = realpath($url_file);
            if ($full_path !== false && file_exists($full_path)) {
                $document->addStyleSheet($relative_path.'/'.basename($full_path).'?ver=3.1.10');
            }
        }
    }

    /**
     * Add inline css to the document
     * @param  $css
     * @return void
     */
    function gantry_addInlineStyle($css){
        $document =& JFactory::getDocument();
        $document->addStyleDeclaration($css);
    }

    /**
     * Get the current template name either from the front end or the template being edited on the backend
     * @return null|string
     */
    function gantry_getTemplate()
    {
        global $option;
        $app = JFactory::getApplication();
        $template = null;
        $task = JRequest::getCmd('task');
        $client =& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

        if ($app->isAdmin() && $option == 'com_templates' && $task == 'edit' && $client->id == 0 && array_key_exists('cid', $_REQUEST)) {
            $template = $_REQUEST['cid'][0];
        }
        else if ($app->isAdmin() && $option == 'com_admin' && array_key_exists('template', $_REQUEST)){
            $template = $_REQUEST['template'];
        }
        else {
            $template = $app->getTemplate();
        }

        return $template;
    }

    function gantry_getFilePath($url) {
        $uri	    =& JURI::getInstance();
		$base	    = $uri->toString( array('scheme', 'host', 'port'));
        $path       = JURI::Root(true);
	    if ($url && $base && strpos($url,$base)!==false) $url = preg_replace('|^'.$base.'|',"",$url);
	    if ($url && $path && strpos($url,$path)!==false) $url = preg_replace('|^'.$path.'|','',$url);
	    if (substr($url,0,1) != DS) $url = DS.$url;
	    $filepath = JPATH_SITE.$url;
	    return $filepath;
	}

    function gantry_setup(){
        gantry_import('core.gantry');
        gantry_import('core.utilities.gantrycache');

        global $gantry;

        $app = JFactory::getApplication();
        $template = $app->getTemplate();
        $template_params = null;

        if (is_readable( JPATH_SITE.DS."templates".DS.$template.DS.'params.ini' ) )
		{
			$content = file_get_contents(JPATH_SITE.DS."templates".DS.$template.DS.'params.ini');
			$template_params = new JParameter($content);
		}
        $conf = & JFactory :: getConfig();

        if (!empty($template_params) && ($template_params->get("cache-enabled", 0) == 1)) {
            $cache = GantryCache::getInstance($app->isAdmin());
            $cache->setLifetime($template_params->get('cache-time', $conf->getValue('config.cachetime') * 60));
            $cache->addWatchFile(JPATH_SITE.'/templates/'.$template.'/params.ini');
            $cache->addWatchFile(JPATH_SITE.'/templates/'.$template.'/templateDetails.xml');
            $gantry = $cache->call('Gantry-'.$template, array('Gantry','getInstance'));
        }
        else {
            $gantry = Gantry::getInstance();
        }
         $gantry->init();
    }

    function gantry_template_initialize(){
        if (defined('GANTRY_INITTEMPLATE')) {
            return;
        }
        define('GANTRY_INITTEMPLATE', "GANTRY_INITTEMPLATE");
        global $gantry;
        $gantry->initTemplate();
    }

    function gantry_admin_setup(){
        gantry_import('core.gantry');
        gantry_import('core.utilities.gantrycache');

        global $gantry;

        $app = JFactory::getApplication();
        $template_name = gantry_getTemplate();
        $cache = GantryCache::getInstance($app->isAdmin());

        $cache->addWatchFile(JPATH_SITE.'/templates/'.$template_name.'/params.ini');
        $cache->addWatchFile(JPATH_SITE.'/templates/'.$template_name.'/templateDetails.xml');
        $gantry = $cache->call('Gantry-'.$template_name, array('Gantry','getInstance'));

        $gantry->adminInit();
    }


    function gantry_run_alternate_template($filename){
        global $gantry;
        // $filename comes from included scope
        $ext = substr($filename, strrpos($filename, '.'));
        $file = basename($filename, $ext);

        $checks = $gantry->browser->getChecks($filename);

        $platform = $gantry->browser->platform;
		$enabled = $gantry->get($platform.'-enabled', 0);
        $view = $gantry->get('template_prefix').$platform.'-switcher';

        // flip to get most specific first
        $checks = array_reverse($checks);

        // remove the default index.php page
        array_pop($checks);

        $template_paths = array(
           $gantry->templatePath,
           $gantry->gantryPath.DS.'tmpl'
        );

        foreach ($template_paths as $template_path) {
            if (file_exists($template_path) && is_dir($template_path)) {
                foreach ($checks as $check) {
                    $check_path = preg_replace("/\?(.*)/", '', $template_path . DS . $check);
                    if (file_exists($check_path) && is_readable($check_path) && $enabled && JRequest::getVar($view, false, 'COOKIE', 'STRING') != '0') {
                        // include the wanted index page
                        ob_start();
                        include_once($check_path);
                        $contents = ob_get_contents();
                        ob_end_clean();
                        $gantry->altindex = $contents;
                        break;
                    }
                }
                if ($gantry->altindex !== false) break;
            }
        }
    }

    function gantry_is_template_include() {
        global $gantry;
        $stack = debug_backtrace();
        if ($stack[1]['file'] == realpath($gantry->templatePath.'/lib/gantry/gantry.php')){
            return true;
        }
        return false;
    }

    // Run the appropriate init
    $app = JFactory::getApplication();
    if ($app->isAdmin()){
        gantry_admin_setup();
    }
    else {
        gantry_setup();
        if (!gantry_is_template_include()){
            // setup for post
            $dispatcher =JDispatcher::getInstance();
            $dispatcher->register('onAfterDispatch','gantry_template_initialize');
            $dispatcher->register('onGantryTemplateInit','gantry_run_alternate_template');
        }
        else {
            gantry_template_initialize();
            gantry_run_alternate_template($filename);
        }
    }
}

