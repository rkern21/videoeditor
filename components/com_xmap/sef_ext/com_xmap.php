<?php
/**
 * $Id: com_xmap.php 133 2010-08-16 02:15:19Z guilleva $
 * $LastChangedDate: 2010-04-14 18:55:27 -0600 (mié, 14 abr 2010) $
 * $LastChangedBy: guilleva $
 * Xmap by Guillermo Vargas
 * a sitemap component for Joomla! CMS (http://www.joomla.org)
 * Author Website: http://joomla.vargas.co.cr
 * Project License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * 
 * Extension for sh404sef component
*/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG;
$sefConfig = & shRouter::shGetConfig();  
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$sefSuffix = $sefConfig->suffix;
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  standard plugin initialize function - don't change ---------------------------


$view = isset($view) ? $view : null; 
if (empty($Itemid )) {
    $component =& JComponentHelper::getComponent('com_xmap');
    $menus    = &JApplication::getMenu('site', array());
    $items    = $menus->getItems('componentid', $component->id);
    if (count($items)) {
        $match = null;
        foreach($items as $item)
        {
            if ((@$item->query['view'] == $view) && (@$item->query['sitemap'] == $sitemap)) {
                $match = $item;
                break;
            }
        }
        if (!$match) {
            $match = $items[0];
        }

        $Itemid = $match->id;
    }
}

switch ($view) {
  case 'xml':
      $title[] = 'sitemap-xml' .((!empty($sitemap)&& $sitemap > 1)?"-$sitemap":'');
      //$sefConfig->suffix='.xml';
    break;
  default:
    $shXmapName = getMenuTitle($option, (isset($view) ? @$view : null), $Itemid, '', $shLangName );
    $shXmapName = (empty($shXmapName) || $shXmapName == '/') ? 'Site Map':$shXmapName; // V 1.2.4.t 
    $title[]  =  $shXmapName . (!empty($sitemap)? "-$sitemap":'');
    break;
}
    
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('Itemid');
shRemoveFromGETVarsList('lang');
if (!empty($view))
  shRemoveFromGETVarsList('view');
if (!empty($sitemap) && $view != 'xml') {
  shRemoveFromGETVarsList('sitemap');
}

// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString, 
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), 
      (isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------
$sefConfig->suffix=$sefSuffix;