<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $sefConfig;
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  standard plugin initialize function - don't change ---------------------------

// do something about that Itemid thing
if (eregi('Itemid=[0-9]+', $string) === false) { // if no Itemid in non-sef URL
  //global $Itemid;
  if ($sefConfig->shInsertGlobalItemidIfNone && !empty($shCurrentItemid)) {
    $string .= '&Itemid='.$shCurrentItemid;  // append current Itemid
    $Itemid = $shCurrentItemid;
    shAddToGETVarsList('Itemid', $Itemid); // V 1.2.4.m
  }
  if ($sefConfig->shInsertTitleIfNoItemid)
  	$title[] = $sefConfig->shDefaultMenuItemName ? $sefConfig->shDefaultMenuItemName : getMenuTitle($option, (isset($task) ? @$task : null), $shCurrentItemid, null, $shLangName );
  $shItemidString = $sefConfig->shAlwaysInsertItemid ?
    _COM_SEF_SH_ALWAYS_INSERT_ITEMID_PREFIX.$sefConfig->replacement.$shCurrentItemid
    : '';
} else {  // if Itemid in non-sef URL
  $shItemidString = $sefConfig->shAlwaysInsertItemid ?
    _COM_SEF_SH_ALWAYS_INSERT_ITEMID_PREFIX.$sefConfig->replacement.$Itemid
    : '';
}

//$task = isset($task) ? @$task : null;
$ff_form = JRequest::getInt('ff_form',0);
$ff_name = JRequest::getVar('ff_name','');

if (isset($Itemid) && !$ff_form && $ff_name == '')
{
	$menus	= JSite::getMenu();
	$menu   = $menus->getItem($Itemid);
	$params = new JParameter($menu->params);

	$string .= '&ff_name='.$params->ff_com_name;
	$title[] = getMenuTitle($option, (isset($task) ? @$task : null), $Itemid, null, $shLangName );
}
elseif ($ff_form > 0)
{
	$database = JFactory::getDBO();
	$database->setQuery("SELECT `title` FROM #__facileforms_forms WHERE id = '".intval($ff_form)."'");
	$title[] = $database->loadResult();
}
elseif ($ff_name != '')
{
	$database = JFactory::getDBO();
	$database->setQuery("SELECT `title` FROM #__facileforms_forms WHERE `name` = ".$database->Quote($ff_name)."");
	$title[] = $database->loadResult();
}

if (!empty($title))
  if (!empty($sefConfig->suffix)) {
	  $title[count($title)-1] .= $sefConfig->suffix;
  }
  else {
	  $title[] = '/';
  }

shRemoveFromGETVarsList('option');
if (!empty($Itemid))
  shRemoveFromGETVarsList('Itemid');
shRemoveFromGETVarsList('lang');
if (!empty($task))
  shRemoveFromGETVarsList('task');

  if ($ff_form > 0)
  shRemoveFromGETVarsList('ff_form');

  if ($ff_name != '')
  shRemoveFromGETVarsList('ff_name');

// ------------------  standard plugin finalize function - don't change ---------------------------
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString,
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null),
      (isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------

?>