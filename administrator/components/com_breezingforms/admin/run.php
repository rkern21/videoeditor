<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

$database = JFactory::getDBO();

/*
ob_start();
print_r($_REQUEST);
$ffCheck = ob_get_contents();
ob_end_clean();
echo 'run out: <pre>'.$ffCheck.'</pre>';
*/

// process form parameters
$formid = JRequest::getVar('ff_form',null);
if ($formid!=null) {
	$database->setQuery(
		"select * from #__facileforms_forms ".
		"where id=$formid ".
		"and published=1 ".
		"and (runmode=0 or runmode=2)"
	);
	$forms = $database->loadObjectList();
	if (count($forms) < 1) { echo 'Form '.$formid.' not found!'; exit; }
	$form = $forms[0];
} else {
	$formname = JRequest::getVar('ff_name','');
	if ($formname == '') {
		echo 'Parameter <em>&ff_name=FormName</em> missing in URL.';
		exit;
	} // if
	$database->setQuery(
		"select * from #__facileforms_forms ".
		"where name='".$formname."' ".
		"and published=1 ".
		"and (runmode=0 or runmode=2)".
		"order by ordering, id"
	);
	$forms = $database->loadObjectList();
	if (count($forms) < 1) { echo 'Form <em>'.$formname.'</em> not found!'; exit; }
	$form = $forms[0];
} // if

$page    = JRequest::getVar('ff_page',1);
$inframe = JRequest::getVar('ff_frame',0);
$border  = JRequest::getVar('ff_border',0);
$task    = JRequest::getVar('ff_task','view');

if ($inframe) {
	// create url for the frame
	$url =
		$ff_mossite = JURI::root().'index.php'
			.'?option=com_breezingforms'
                        .'&amp;tmpl=component'
			.'&amp;Itemid=0'
			.'&amp;ff_form='.$form->id
			
			.'&amp;ff_runmode='._FF_RUNMODE_BACKEND;
	if ($page != 1) $url .= '&amp;ff_page='.$page;
	if ($border) $url .= '&amp;ff_border=1';
	reset($ff_request);
	while (list($prop, $val) = each($ff_request))
		$url .= '&amp;'.$prop.'='.urlencode($val);

	// prepare iframe width
	$framewidth = 'width="'.$form->width;
	if ($form->widthmode) $framewidth .= '%" '; else $framewidth .= '" ';

	// prepare iframe height
	$frameheight = '';
	if (!$form->heightmode) $frameheight = 'height="'.$form->height.'" ';

	// assemble iframe parameters
	$params =   'id="ff_frame'.$form->id.'" '.
				'src="'.$url.'" '.
				$framewidth.
				$frameheight.
				'frameborder="'.$border.'" '.
				'allowtransparency="true" '.
				'scrolling="no"';

	// emit frame code
	echo "<iframe ".$params.">\n".
		 "<p>Sorry, your browser cannot display frames!</p>\n".
		 "</iframe>\n".
		 "</div>\n";
} else {
	// process inline
	
	/**
	 * @var JUser
	 */
	$myUser = JFactory::getUser();
	
	$database->setQuery("select id from #__users where lower(username)=lower('".$myUser->get('username','')."')");
	$id = $database->loadResult();
	if ($id) $myUser->get('id',-1);
	require_once($ff_compath.'/facileforms.process.php');
	if ($task == 'view') {
		echo '<div id="overDiv" style="position:absolute;visibility:hidden;z-index:1000;"></div>'."\n";
		$divstyle = 'width:'.$form->width;
		$divstyle .= ($form->widthmode) ? '%;' : 'px;';;
		if (!$form->heightmode) $divstyle .= 'height:'.$form->height.'px;';
		$tablestyle = ($divstyle=='') ? '' : ' style="'.$divstyle.'"';
		echo '<table cellpadding="0" cellspacing="0" border="'.$border.'"'.$tablestyle.'>'."\n".
			 "<tr><td>\n".
			 '<div style="left:0px;top:0px;'.$divstyle.'position:relative;">'."\n";
	} // if
	$curdir = getcwd();
	chdir($ff_mospath);
	$ff_processor = new HTML_facileFormsProcessor(
		_FF_RUNMODE_BACKEND, false, $form->id, $page, $option,
		null, $border
	);
	chdir($curdir);
	if ($task == 'submit')
		$ff_processor->submit();
	else {
		$ff_processor->view();
		echo "</div>\n</td></tr>\n</table>\n";
	} // if
} // if

?>