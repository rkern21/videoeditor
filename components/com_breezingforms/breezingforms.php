<?php
/**
 * BreezingForms - A Joomla Forms Application
 * @version 1.7.2
 * @package BreezingForms
 * @copyright (C) 2008-2010 by Markus Bopp
 * @license Released under the terms of the GNU General Public License
 *
 * This is the main component entry point that will be called by joomla or mambo
 * after after calling
 *
 *     http://siteurl/index.php?option=com_breezingforms......
 * The first form is the normal call from frontend where the whole page is
 * displayed by uting the template. The second form is a display of the plain
 * form, wich is used to run in iframe or in popup windows.
 **/
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$mainframe = JFactory::getApplication();

$cache = JFactory::getCache();
$cache->setCaching(false);

jimport('joomla.filesystem.file');

require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/classes/BFText.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/classes/BFTableElements.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/functions/helpers.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/constants.php');

// declare global variables
global
$database,				// joomla/mambo database object
$ff_version,			// FacileForms version number
$ff_config,				// FacileForms configuration object
$ff_mospath,			// path to root of joomla/mambo
$ff_compath,			// path to component frontend root
$ff_mossite,			// url of the site root
$ff_request,			// array of request parameters ff_param_*
$ff_processor,			// current form procesor object
$ff_target;				// index of form on current page

$database = $db = JFactory::getDBO();

// declare local vars
// (1) only used in component space and not plain form)
$plainform	= 0;		// running as plain form by index.php?tmpl=component
$formid		= null;		// form id number
$formname	= null;		// form name
$task		= 'view';	// either 'view' or 'submit'
$page		= 1;		// page to display
$inframe	= 0;		// run in iframe
$border		= 0;		// show a border around the form (1)
$align		= 1;		// 0-left 1-center 2-right (1)
$left		= 0;		// left margin in px (1)
$top		= 0;		// top margin in px (1)
$suffix		= '';		// CSS class suffix
$parprv		= '';		// private parameters
$runmode	= 0;		// run mode
$pagetitle	= true;		// set page title
$editable   = 0;
$editable_override = 0;

if(!isset($xModuleId)){
	$xModuleId = 0;
}

if(!isset($ff_applic)){
	$ff_applic = '';
}

$runmode = @JRequest::getVar('ff_runmode', $runmode);

// get paths
$ff_mospath = JPATH_SITE;
$ff_compath = $ff_mospath.'/components/com_breezingforms';

// load config and initialize globals
require_once($ff_compath.'/facileforms.class.php');
$ff_config = new facileFormsConf();
initFacileForms();

// check for plain form

$plainform = JRequest::getWord('tmpl','') == 'component';

// create target id for this form and check if ff params are ment for this target
if (!$ff_target) $ff_target = 1; else $ff_target++;
$parent_target = @JRequest::getVar( 'ff_target', 1);
$my_ff_params = $plainform || $parent_target==$ff_target;

// clear list of request parameters
$ff_request = array();

if(
	!JRequest::getBool('showSecImage') &&
	!JRequest::getBool('bfCaptcha') &&
        !JRequest::getBool('bfReCaptcha') &&
	!JRequest::getBool('checkCaptcha') && 
	!JRequest::getBool('confirmPayPal')  &&
        !JRequest::getBool('confirmPayPalIpn')  &&
	!JRequest::getBool('paypalDownload') &&
	!JRequest::getBool('showPayPalConnectMsg') &&
	!JRequest::getBool('successSofortueberweisung') &&
	!JRequest::getBool('confirmSofortueberweisung') &&
	!JRequest::getBool('sofortueberweisungDownload') &&
	!JRequest::getBool('flashUpload')
) {

	JRequest::setVar('format', 'html');
	
	if ($runmode==_FF_RUNMODE_FRONTEND) {
		
		// is this called by a module?
		if (isset($ff_applic) && $ff_applic=='mod_facileforms') {
			
			// get the module parameters
			$formname = $params->get('ff_mod_name');
			$page     = intval($params->get('ff_mod_page', $page));
			$inframe  = intval($params->get('ff_mod_frame', $inframe));
			$border   = intval($params->get('ff_mod_border', $border));
			$align    = intval($params->get('ff_mod_align', $align));
			$left     = intval($params->get('ff_mod_left', $left));
			$top      = intval($params->get('ff_mod_top', $top));
			$suffix   = $params->get('ff_mod_suffix', '');
			$parprv   = $params->get('ff_mod_parprv', '');
			addRequestParams($params->get('ff_mod_parpub', ''));
			$pagetitle = false;
			
			JFactory::getSession()->set('ff_editableMod'. $xModuleId . $formname, intval($params->get('ff_mod_editable', $editable)));
			JFactory::getSession()->set('ff_editable_overrideMod'. $xModuleId . $formname, intval($params->get('ff_mod_editable_override', $editable_override)));
				
		} else if (isset($ff_applic) && $ff_applic=='plg_facileforms') {

			$formname = JRequest::getVar('ff_name','');
			$page     = JRequest::getVar('ff_page',1);
			$inframe  = JRequest::getVar('ff_frame','');
			$border   = JRequest::getVar('ff_border','');
			$align    = JRequest::getVar('ff_border','');
			$editable = intval($plg_editable);
			$editable_override = intval($plg_editable_override);
			$left     = '';
			$top      = '';
			$suffix   = JRequest::getVar('ff_suffix','');
			$parprv   = '';
			addRequestParams('');
				
		} else {
			
			// is this called with an Itemid?
			if (JRequest::getInt( 'Itemid', 0) > 1 && JRequest::getVar('ff_applic','') != 'mod_facileforms' && JRequest::getVar('ff_applic','') != 'plg_facileforms') {
				
				// get parameters from menu
				$menu = JTable::getInstance('menu');
				$menu->load(JRequest::getInt( 'Itemid', 0));
                                jimport( 'joomla.html.parameter' );
				$params   = new JParameter($menu->params);
				$formname = $params->get('ff_com_name');
				$page     = intval($params->get('ff_com_page', $page));
				$inframe  = intval($params->get('ff_com_frame', $inframe));
				$border   = intval($params->get('ff_com_border', $border));
				$align    = intval($params->get('ff_com_align', $align));
				$left     = intval($params->get('ff_com_left', $left));
				$top      = intval($params->get('ff_com_top', $top));
				$editable = intval($params->get('ff_com_editable', $editable));
				$editable_override = intval($params->get('ff_com_editable_override', $editable_override));
				$suffix   = $params->get('ff_com_suffix', '');
				$parprv   = $params->get('ff_com_parprv', '');
				addRequestParams($params->get('ff_com_parpub', ''));
			} // if
		}
	} // if
	
	if ($my_ff_params) {
		// allow overriding by url params
		$formid = @JRequest::getVar( 'ff_form', $formid);

		if ($formid==null)
			$formname = @JRequest::getVar('ff_name', $formname);
		else
			$formname = null;
			
		$task = @JRequest::getVar('ff_task', $task);
		$page = @JRequest::getVar('ff_page', $page);
		$inframe = @JRequest::getVar('ff_frame', $inframe);
		$border = @JRequest::getVar('ff_border', $border);
		$align1 = @JRequest::getVar('ff_align', -1);
		if ($align1>=0) {
			$align = @JRequest::getVar( 'ff_align', $align);
			$left = 0;
			if ($align>2) { $left = $align; $align = 3; }
		} // if
		$top = @JRequest::getVar('ff_top',$top);
		$suffix = @JRequest::getVar('ff_suffix',$suffix);
	}

	
	
	// load form
	$ok = true;
	if (is_numeric($formid)) {
		$database->setQuery(
			"select * from #__facileforms_forms ".
			"where id=$formid and published=1"
		);
		$forms = $database->loadObjectList();
		if (count($forms) < 1) {
			echo '[Form '.$formid.' not found!]';
			$ok = false;
		} else
		$form = $forms[0];
	} else
	if ($formname != null) {
		$database->setQuery(
				"select * from #__facileforms_forms ".
				"where name='$formname' and published=1 ".
				"order by ordering, id"
				);
				$forms = $database->loadObjectList();
				if (count($forms) < 1) {
					echo '[Form '.$formname.' not found!]';
					$ok = false;
				} else
				$form = $forms[0];
	} else {
		echo '[No form id or name provided!]';
		$ok = false;
	} // if

	if ($ok) {
		 
		// set by plugin
		if(isset($_SESSION['ff_editablePlg'.$form->name]) && $_SESSION['ff_editablePlg'.JRequest::getInt('ff_contentid',0) . $form->name] != 0 && ( JRequest::getVar('ff_applic')=='plg_facileforms' || ( isset($ff_applic) && $ff_applic == 'plg_facileforms' )) ){
			$editable = $_SESSION['ff_editablePlg'.JRequest::getInt('ff_contentid',0) . $form->name];
		}
		
		// set by plugin
		if(isset($_SESSION['ff_editable_overridePlg'.$form->name]) && $_SESSION['ff_editable_overridePlg'.JRequest::getInt('ff_contentid',0) . $form->name] != 0 && ( JRequest::getVar('ff_applic')=='plg_facileforms' || ( isset($ff_applic) && $ff_applic == 'plg_facileforms' )) ){
			$editable_override = $_SESSION['ff_editable_overridePlg'.JRequest::getInt('ff_contentid',0) . $form->name];
		}
		
		// set by module
		if(( JRequest::getVar('ff_applic')=='mod_facileforms' || ( isset($ff_applic) && $ff_applic == 'mod_facileforms' )) ){
			if(JFactory::getSession()->get('ff_editableMod'. $xModuleId . $form->name, 0) != 0){
				$editable = JFactory::getSession()->get('ff_editableMod'.$xModuleId . $form->name, 0);
			} else if(JFactory::getSession()->get('ff_editableMod'. JRequest::getInt('ff_module_id',0) . $form->name, 0) != 0){
				$editable = JFactory::getSession()->get('ff_editableMod'.JRequest::getInt('ff_module_id',0) . $form->name, 0);
			}
		}
			
		// set by module
		if(( JRequest::getVar('ff_applic')=='mod_facileforms' || ( isset($ff_applic) && $ff_applic == 'mod_facileforms' )) ){
			if(JFactory::getSession()->get('ff_editable_overrideMod'. $xModuleId . $form->name, 0) != 0){
				$editable_override = JFactory::getSession()->get('ff_editable_overrideMod'.$xModuleId . $form->name, 0);
			} else if(JFactory::getSession()->get('ff_editable_overrideMod'. JRequest::getInt('ff_module_id',0) . $form->name, 0) != 0){
				$editable_override = JFactory::getSession()->get('ff_editable_overrideMod'.JRequest::getInt('ff_module_id',0) . $form->name, 0);
			}
		}
		
			
		if ($pagetitle && $form->title != '') JFactory::getDocument()->setTitle(htmlentities($form->title, ENT_QUOTES, 'UTF-8'));
		if ($form->name==$formname) addRequestParams($parprv);
		if ($my_ff_params) {
			reset($_REQUEST);
			while (list($prop, $val) = each($_REQUEST))
			if (!is_array($val) && substr($prop,0,9)=='ff_param_')
			$ff_request[$prop] = $val;
		} // if

		if ($inframe && !$plainform) {
			
			// open frame and detach processing
			$divstyle = 'width:100%;';
			switch ($align) {
				case 0: $divstyle .= 'text-align:left;';   break;
				case 1: $divstyle .= 'text-align:center;'; break;
				case 2: $divstyle .= 'text-align:right;';  break;
				case 3: if ($left > 0) $divstyle .= 'padding-left:'.$left.'px;'; break;
				default: break;
			} // switch
			if ($top > 0) $divstyle .= 'padding-top:'.$top.'px;';
			$framewidth = 'width="'.$form->width.($form->widthmode?'%" ':'" ');
			$frameheight = '';
			if (!$form->heightmode) $frameheight = 'height="'.$form->height.'" ';
			$url = $ff_mossite.'/index.php'
			.'?option=com_breezingforms'
			.'&amp;Itemid='.((JRequest::getInt( 'Itemid', 0) > 0 && JRequest::getInt( 'Itemid', 0) < 99999999) ? JRequest::getInt( 'Itemid', 0) : 0)
			.'&amp;ff_form='.$form->id
			.'&amp;ff_applic='.$ff_applic
			.'&amp;ff_module_id='.$xModuleId
			.'&amp;format=html'
                        .'&amp;tmpl=component'
			.'&amp;ff_frame=1';
			if ($page != 1) $url .= '&amp;ff_page='.$page;
			if ($border) $url .= '&amp;ff_border=1';
			if ($parent_target > 1) $url .= '&amp;ff_target='.$parent_target;
			reset($ff_request);
			while (list($prop, $val) = each($ff_request)) $url .= '&amp;'.$prop.'='.urlencode($val);
			$params =   'id="ff_frame'.$form->id.'" '.
						'src="'.$url.'" '.
			$framewidth.
			$frameheight.
						'frameborder="'.$border.'" '.
						'allowtransparency="true" '.
						'scrolling="no" ';
			// DO NOT REMOVE OR CHANGE OR OTHERWISE MAKE INVISIBLE THE FOLLOWING COPYRIGHT MESSAGE
			// FAILURE TO COMPLY IS A DIRECT VIOLATION OF THE GNU GENERAL PUBLIC LICENSE
			// http://www.gnu.org/copyleft/gpl.html
			echo "\n<!-- BreezingForms V".$ff_version." Copyright(c) 2008-2009 by Markus Bopp | FacileForms Copyright 2004-2006 by Peter Koch, Chur, Switzerland.  All rights reserved. -->\n";
			// END OF COPYRIGHT
			echo '<div class="bfClearfix" style="'.$divstyle.'">'."\n".
				 "<iframe ".$params.">\n".
				 "<p>Sorry, your browser cannot display frames!</p>\n".
				 "</iframe>\n".
				 "</div>\n";
		} else {
                   
			// process inline
			$myUser = JFactory::getUser();
				
			$database->setQuery("select id from #__users where lower(username)=lower('".$myUser->get('username','')."')");
			$id = $database->loadResult();
			if ($id) $myUser->get('id',-1);
			require_once($ff_compath.'/facileforms.process.php');
			if ($task == 'view') {
				$div1style = '';
				$div2style = '';
				if ($form->template_code == '') {
					$fullwidth = $form->widthmode && $form->width>=100;
					if ($form->widthmode) {
						$div1style .= 'min-width:10px;';
						$div2style .= 'min-width:10px;';
					} // if
					$div2style .= 'width:'.($fullwidth?'100':$form->width).($form->widthmode?'%':'px').';';
					if (!$form->heightmode) $div2style .= 'height:'.$form->height.'px;';
					if ($plainform) {
						$div2style .= 'position:absolute;top:0px;left:0px;margin:0px;';
					} else {
						$div1style .= 'width:100%;';
						$div2style .= 'position:relative;overflow:hidden;';
						if ($border) $div2style .= 'border:1px solid black;';
						if (!$fullwidth) {
							switch ($align) {
								case 1:
									$div1style .= 'text-align:center;';
									$div2style .= 'text-align:left;margin-left:auto;margin-right:auto;';
									break;
								case 2:
									$div1style .= 'text-align:right;';
									$div2style .= 'text-align:left;margin-left:auto;margin-right:0px;';
									break;
								case 3:
									if ($left > 0) $div2style .= 'margin-left:'.$left.'px;';
								default:
									break;
							} // switch
						} // if
						if ($top > 0) $div2style .= 'margin-top:'.$top.'px;';
					} // if
				}
				ob_start();
				// DO NOT REMOVE OR CHANGE OR OTHERWISE MAKE INVISIBLE THE FOLLOWING COPYRIGHT MESSAGE
				// FAILURE TO COMPLY IS A DIRECT VIOLATION OF THE GNU GENERAL PUBLIC LICENSE
				// http://www.gnu.org/copyleft/gpl.html
				echo "\n<!-- BreezingForms V".$ff_version." Copyright(c) 2008-2009 by Markus Bopp | FacileForms Copyright 2004-2006 by Peter Koch, Chur, Switzerland.  All rights reserved. -->\n";
				// END OF COPYRIGHT
				$bfStyle = '';
				if ($form->template_code == '') {
					$bfStyle = ' style="'.$div1style.'"';
				}
				if (!$plainform) echo '<div class="bfClearfix"'.$bfStyle.'>'."\n";
				if(trim($form->template_code_processed) == ''){
					echo '<div class="bfClearfix" style="'.$div2style.'">'."\n";
				}
			} // if task = view
			if ($left > 3) $align = $left;
				
			// remove temporary flash upload files if any	
			$sourcePath = JPATH_SITE . '/components/com_breezingforms/uploads/';
			if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
				while (false !== ($file = @readdir($handle))) {
					if($file!="." && $file!=".."){
						$parts = explode('_', $file);
						if(count($parts)>=5){
							if($parts[count($parts)-1] == 'flashtmp'){
								if (@JFile::exists($sourcePath.$file) && @is_readable($sourcePath.$file)){
									$fileCreationTime = @filectime($sourcePath.$file);
	 								$fileAge = time() - $fileCreationTime; 
									if($fileAge >= 3600){
										@JFile::delete($sourcePath.$file);
									}
								}
							}
						}
					}
				}
				@closedir($handle);
			}
                        // purge payment cache
                        $sourcePath = JPATH_SITE . '/administrator/components/com_breezingforms/payment_cache/';
                        if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
                            while (false !== ($file = @readdir($handle))) {
                                if($file!="." && $file!="..") {
                                    $parts = explode('_', $file);
                                    if(count($parts)==4) {
                                        if (@JFile::exists($sourcePath.$file) && @is_readable($sourcePath.$file)) {
                                            $fileCreationTime = @filectime($sourcePath.$file);
                                            $fileAge = time() - $fileCreationTime;
                                            if($fileAge >= 3600) {
                                                @JFile::delete($sourcePath.$file);
                                            }
                                        }
                                    }
                                }
                            }
                            @closedir($handle);
                        }

			$ff_processor = new HTML_facileFormsProcessor(
				$runmode, $inframe, $form->id, $page, $border,
				$align, $top, $ff_target, $suffix, $editable, $editable_override
			);
			
			if ($task == 'submit'){
				$ff_processor->submit();
			} else {
                            
				$ff_processor->view();
				if(trim($form->template_code_processed) == ''){
					echo "</div>\n";
				}
				if (!$plainform) echo "</div>\n";

				if ($runmode==_FF_RUNMODE_PREVIEW) {
						
					$mouseOvers = '';
					$draggableIds = '';
					$draggableSize = count($ff_processor->draggableDivIds);
					for($x  = 0; $x < $draggableSize;$x++){
						if($x+1 < $draggableSize){
							$draggableIds .= '"'.$ff_processor->draggableDivIds[$x].'",';
						} else {
							$draggableIds .= '"'.$ff_processor->draggableDivIds[$x].'"';
						}

						$mouseOvers .= '
						
							var '.$ff_processor->draggableDivIds[$x].'_paddingRTmp;
							var '.$ff_processor->draggableDivIds[$x].'_paddingLTmp;
							var '.$ff_processor->draggableDivIds[$x].'_colorTmp;
							
							'.$ff_processor->draggableDivIds[$x].'_colorTmp    = document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.backgroundColor;
							'.$ff_processor->draggableDivIds[$x].'_paddingRTmp = document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingRight;
							'.$ff_processor->draggableDivIds[$x].'_paddingLTmp = document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingLeft;
							'.$ff_processor->draggableDivIds[$x].'_paddingTTmp = document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingTop;
							'.$ff_processor->draggableDivIds[$x].'_paddingBTmp = document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingBottom;
							
							function bfItemOver_'.$ff_processor->draggableDivIds[$x].'(e){
								if(document.getElementById("'.$ff_processor->draggableDivIds[$x].'")){
									
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.cursor="pointer";
								
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingRight = "10px";
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingLeft = "10px";
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingTop = "0px";
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingBottom = "0px";
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.backgroundColor = "red";
									
									parent.document.getElementById("hoverItem_'.$ff_processor->draggableDivIds[$x].'").style.backgroundColor = "#cccccc";
								}
							}
						
							function bfItemOut_'.$ff_processor->draggableDivIds[$x].'(e){
								if(document.getElementById("'.$ff_processor->draggableDivIds[$x].'")){
								
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.cursor="";
								
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingRight= '.$ff_processor->draggableDivIds[$x].'_paddingRTmp;
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingLeft= '.$ff_processor->draggableDivIds[$x].'_paddingLTmp;
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingTop= '.$ff_processor->draggableDivIds[$x].'_paddingTTmp;
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.paddingBottom= '.$ff_processor->draggableDivIds[$x].'_paddingBTmp;
									document.getElementById("'.$ff_processor->draggableDivIds[$x].'").style.backgroundColor = '.$ff_processor->draggableDivIds[$x].'_colorTmp;
									
									parent.document.getElementById("hoverItem_'.$ff_processor->draggableDivIds[$x].'").style.backgroundColor = "";
								}
							}
							
							if(document.getElementById("'.$ff_processor->draggableDivIds[$x].'")){
								document.getElementById("'.$ff_processor->draggableDivIds[$x].'").onmouseover = bfItemOver_'.$ff_processor->draggableDivIds[$x].';
								document.getElementById("'.$ff_processor->draggableDivIds[$x].'").onmouseout = bfItemOut_'.$ff_processor->draggableDivIds[$x].';
							}
						';
					}
						
					echo '
					<script>
					
					SET_DHTML('.$draggableIds.');
					
					'.$mouseOvers.'
					
					function my_DragFunc(){
						parent.document.adminForm.savepos.disabled = false;
						// TODO: when undo is enabled, drag and drop is not possible. needs to be solved
						//parent.document.adminForm.restpos.disabled = false;
					}
					
					function my_DropFunc(){
					
						parent.document.getElementById("ff_itemPositions").value = "";
					
						for(var i = 0; i < parent.ff_coords.length;i++){
						
							eval("var cb = parent.document.adminForm.cb"+i+";");
							
							var itemComma = "";
							if(i+1 < parent.ff_coords.length){
								itemComma = ",";
							} else {
								itemComma = "";
							}
							
							parent.document.getElementById("ff_itemPositions").value += 
								cb.value+":"+document.getElementById("ff_div"+cb.value).style.zIndex+itemComma;
						
						}
						
						for(var i = 0; i < parent.ff_coords.length;i++){
							
							eval("var cb = parent.document.adminForm.cb"+i+";");
							
							if(document.getElementById("ff_div"+cb.value) == document.getElementById(dd.obj.id)){
								
								parent.ff_coords[i][2] = dd.obj.x;
								parent.ff_coords[i][5] = dd.obj.y;
								break;
							}
						}
					}
					
					</script>';
				}

				ob_end_flush();
			} // if
		} // if
	} // if

} else if(JRequest::getBool('showSecImage')) {

	JRequest::setVar('format', 'raw');
	
	header("Content-Type: image/png");

	$captchaDir = JPATH_SITE . '/administrator/components/com_breezingforms/captchas';

	if(file_exists($captchaDir) && is_dir($captchaDir)){

		$sizeAvailableCaptchas = count(glob("$captchaDir/*.png"));
		$sizeAvailableCaptchas = $sizeAvailableCaptchas > mt_getrandmax() ? mt_getrandmax() : $sizeAvailableCaptchas;
		mt_srand();
		$captchaBgNum = mt_rand(0, $sizeAvailableCaptchas-1);
			
		$i = 0;
		$handle = opendir($captchaDir);
		while (false!==($file = readdir($handle))) {
			if ($file != "." && $file != ".." && strtolower($file) != ".svn" && strtolower($file) != ".cvs") {
				$pathinfo = pathinfo($file);
				$extension = $pathinfo['extension'];
				if(strtolower($extension) == 'png'){
					if($captchaBgNum == $i){
						$bbox = imagettfbbox (14, 0, $captchaDir . '/fontfile.ttf', JFactory::getSession()->get('ff_seccode'));
						//print_r($bbox);
						$textWidth  = $bbox[2] - $bbox[0];
						$textHeight  = $bbox[3] - $bbox[5];
						$imgHandle = imagecreatefrompng($captchaDir . '/' . $file);
						imagettftext($imgHandle, 14, 0, (imagesx($imgHandle)-$textWidth)/2, (imagesy($imgHandle)+$textHeight)/2, '0x000000', $captchaDir . '/fontfile.ttf', JFactory::getSession()->get('ff_seccode'));
						imagepng($imgHandle);
						imagedestroy($imgHandle);
						break;
					}
					$i++;
				}
			}
		}

		closedir($handle);
	}
	exit;

} else if(JRequest::getBool('bfCaptcha')){

	ob_end_clean();
	require_once(JPATH_SITE . '/components/com_breezingforms/images/captcha/securimage_show.php');
	exit;

} else if(JRequest::getBool('bfReCaptcha')){

	ob_end_clean();
        require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');
        $db->setQuery( "Select * From #__facileforms_forms Where id = " . $db->Quote( JRequest::getInt('form',-1) ) );
	$list = $db->loadObjectList();
	if(count($list) == 0){
		exit;
	}
	$form = $list[0];
	$areas = Zend_Json::decode($form->template_areas);
        foreach($areas As $area){
		foreach($area['elements'] As $element){

                    if($element['bfType'] == 'ReCaptcha'){
                        if(!function_exists('recaptcha_check_answer')){
                            require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/recaptcha/recaptchalib.php');
                        }
                        
                        $publickey = $element['pubkey']; // you got this from the signup page
                        $privatekey = $element['privkey'];

                        $resp = recaptcha_check_answer ($privatekey,
                                                        $_SERVER["REMOTE_ADDR"],
                                                        isset( $_POST["recaptcha_challenge_field"] ) ? $_POST["recaptcha_challenge_field"] : '' ,
                                                        isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : '' );

                        JFactory::getSession()->set('bfrecapsuccess',false);
                        if ($resp->is_valid) {
                            echo 'success';
                            JFactory::getSession()->set('bfrecapsuccess',true);
                        }
                        else
                        {
                            die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
                               "(reCAPTCHA said: " . $resp->error . ")");
                        }
                        exit;
                    }
                }
        }
	
	exit;

} else if(JRequest::getBool('checkCaptcha')){
	
	ob_end_clean();
	require_once(JPATH_SITE . '/components/com_breezingforms/images/captcha/securimage.php');
	$securimage = new Securimage();
	if(!$securimage->check(str_replace('?','',JRequest::getVar('value', '')))){
		echo 'capResult=>false';
	} else {
		echo 'capResult=>true';
	}
	exit;
	
} else if(JRequest::getBool('confirmPayPalIpn') && ( !isset($ff_applic) || $ff_applic == '' ) ){

	JRequest::setVar('format', 'html');

	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');

	$db->setQuery( "Select * From #__facileforms_forms Where id = " . $db->Quote( JRequest::getInt('form_id',-1) ) );
	$list = $db->loadObjectList();
	if(count($list) == 0){
		header("Status: 200 OK");
		exit;
	}

	$form = $list[0];

	$areas = Zend_Json::decode($form->template_areas);
	if(!is_array($areas)){
		header("Status: 200 OK");
                exit;
	}

	foreach($areas As $area){

		foreach($area['elements'] As $element){
			if($element['internalType'] == 'bfPayPal'){

				$options = $element['options'];

				$auth_token = $options['token'];
				$paypal = 'https://www.paypal.com';
				if($options['testaccount']){
					$paypal = 'https://www.sandbox.paypal.com';
					$auth_token = $options['testToken'];
				}

				$req = 'cmd=_notify-validate';

				$tx_token = JRequest::getVar('txn_id', 0 );
				foreach ($_POST as $key => $value) {
                                    $value = urlencode(stripslashes($value));
                                    $req .= "&$key=$value";
                                }

				$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
				$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
				$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

                                $pointer = null;
                                $res = '';
                                
				if (function_exists('curl_init')) {
					$ch = curl_init();
                                        $pointer = $ch;
					curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
					curl_setopt($ch,CURLOPT_URL, $paypal.'/cgi-bin/webscr');
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_POSTFIELDS,$req);

					ob_start();
					curl_exec($ch);
					$res=ob_get_contents();

				} else {
					// try fsockopen
					$fp = fsockopen ($paypal, 80, $errno, $errstr, 30);
                                        $pointer = $fp;
					fputs ($fp, $header . $req);
					$headerdone = false;
					while (!feof($fp)) {
						$line = fgets ($fp, 1024);
						if (strcmp($line, "\r\n") == 0) {
							$headerdone = true;
						}
						else if ($headerdone)
						{
							$res .= $line;
						}
					}
					
				}

				$lines = explode("\n", $res);

				if (strcmp ($lines[0], "VERIFIED") == 0) {

                                        $query = "SELECT * FROM #__facileforms_records WHERE id = '".JRequest::getInt('record_id', -1)."' LIMIT 1";
                                        $db->setQuery($query);
                                        $txid = $db->loadObjectList();

                                        if (count($txid) != 0) {

                                            if($txid[0]->paypal_tx_id == ''){

                                                $db->setQuery("
										Update
											#__facileforms_records
										Set
											paypal_tx_id = ".$db->Quote('PayPal: ' . $tx_token . ' (VALID)').",
											paypal_payment_date = ".$db->Quote(date('Y-m-d H:i:s')).",
											paypal_testaccount = ".$db->Quote($options['testaccount'] ? 1 : 0).",
											paypal_download_tries = 0
										Where
											id = '".JRequest::getInt('record_id', -1)."'
											");

                                                $db->query();

                                                // trigger a script after succeeded payment?
                                                if(JFile::exists(JPATH_SITE . '/bf_paypalipn_success.php')){
                                                    require_once(JPATH_SITE . '/bf_paypalipn_success.php');
                                                }

                                                // send mail after succeeded payment?
						if( isset( $options['sendNotificationAfterPayment'] ) && $options['sendNotificationAfterPayment'] ) {
                                                    bf_sendNotificationByPaymentCache(JRequest::getInt('form_id',-1),JRequest::getInt('record_id', -1),'admin');
                                                    bf_sendNotificationByPaymentCache(JRequest::getInt('form_id',-1),JRequest::getInt('record_id', -1),'mailback');
                                                }
                                            }
                                            
                                            header("Status: 200 OK");
                                        }

                                        header("Status: 200 OK");
					
				}
				else if (strcmp ($lines[0], "INVALID") == 0) {

                                    $query = "SELECT * FROM #__facileforms_records WHERE id = '".JRequest::getInt('record_id', -1)."' LIMIT 1";
                                    $db->setQuery($query);
                                    $txid = $db->loadObjectList();

                                    if (count($txid) != 0) {

                                            $db->setQuery("
										Update
											#__facileforms_records
										Set
											paypal_tx_id = ".$db->Quote('PayPal: ' . $tx_token . ' (INVALID)').",
											paypal_payment_date = ".$db->Quote(date('Y-m-d H:i:s')).",
											paypal_testaccount = ".$db->Quote($options['testaccount'] ? 1 : 0).",
											paypal_download_tries = 0
										Where
											id = '".JRequest::getInt('record_id', -1)."'
											");

                                            $db->query();
                                    }

                                    header("Status: 200 OK");
				}

                                header("Status: 200 OK");

                                // should be kept open until sending the status headers
                                if (function_exists('curl_init')) {
                                    curl_close($pointer);
                                    ob_end_clean();
                                }
                                else
                                {
                                    fclose ($pointer);
                                }

				break;
			}
		}
	}

} else if(JRequest::getBool('confirmPayPal') && ( !isset($ff_applic) || $ff_applic == '' ) ){
	
	JRequest::setVar('format', 'html');
	
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');
	
	$db->setQuery( "Select * From #__facileforms_forms Where id = " . $db->Quote( JRequest::getInt('form_id',-1) ) );
	$list = $db->loadObjectList();
	if(count($list) == 0){
		BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_FORM_DOES_NOT_EXIST'));
		exit;
	}
	
	$form = $list[0];
	
	$areas = Zend_Json::decode($form->template_areas);
	if(!is_array($areas)){
		BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_COULD_NOT_FIND_PAYPAL_DATA'));
                exit;
	}
	
	foreach($areas As $area){
		$checkPP = true;
		foreach($area['elements'] As $element){
			if($element['name'] == 'PayPalSelect' || $element['name'] == 'BfPaymentSelect'){
				$checkPP = false;
				break;
			}
		}
		foreach($area['elements'] As $element){
			if($element['internalType'] == 'bfPayPal'){

				$options = $element['options'];

				$auth_token = $options['token'];
				$paypal = 'https://www.paypal.com';
				if($options['testaccount']){
					$paypal = 'https://www.sandbox.paypal.com';
					$auth_token = $options['testToken'];
				}

				$req = 'cmd=_notify-synch';

				$tx_token = JRequest::getVar('tx', 0 );
				$req .= "&tx=".urlencode($tx_token)."&at=".urlencode($auth_token);

				$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
				$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
				$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
					
				if (function_exists('curl_init')) {
					$ch = curl_init();

					curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
					curl_setopt($ch,CURLOPT_URL, $paypal.'/cgi-bin/webscr');
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_POSTFIELDS,$req);

					ob_start();
					curl_exec($ch);
					$res=ob_get_contents();
					curl_close($ch);
					ob_end_clean();

				} else {
					// try fsockopen
					$fp = fsockopen ($paypal, 80, $errno, $errstr, 30);
					fputs ($fp, $header . $req);
					$res = '';
					$headerdone = false;
					while (!feof($fp)) {
						$line = fgets ($fp, 1024);
						if (strcmp($line, "\r\n") == 0) {
							$headerdone = true;
						}
						else if ($headerdone)
						{
							$res .= $line;
						}
					}
					fclose ($fp);
				}

				$lines = explode("\n", $res);
				$keyarray = array();
					
				if (strcmp ($lines[0], "SUCCESS") == 0) {
					for ($i=1; $i<count($lines);$i++){
						if ($lines[$i] != "") {
							list($key,$val) = explode("=", $lines[$i]);
							$keyarray[urldecode($key)] = urldecode($val);
						}
					}

					if ($checkPP && ( $keyarray['mc_gross'] != (doubleval($options['amount'])+doubleval($options['tax'])) || $keyarray['mc_currency'] != strtoupper($options['currencyCode']) ) ) {
						
						$success = false;
						$msg = JText::_("Payment was not correct (amount/currency)");
						require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/error.php');
						
					}else{

						$query = "SELECT * FROM #__facileforms_records WHERE id = '".JRequest::getInt('record_id', -1)."' LIMIT 1";
						$db->setQuery($query);
						$txid = $db->loadObjectList();
	
						if (count($txid) != 0) {
								
							if($txid[0]->paypal_tx_id == ''){
									
								$db->setQuery("
										Update 
											#__facileforms_records 
										Set 
											paypal_tx_id = ".$db->Quote('PayPal: ' . $tx_token).", 
											paypal_payment_date = ".$db->Quote(date('Y-m-d H:i:s',strtotime($keyarray["payment_date"]))).",
											paypal_testaccount = ".$db->Quote($options['testaccount'] ? 1 : 0).",
											paypal_download_tries = 0
										Where 
											id = '".JRequest::getInt('record_id', -1)."'
											");
	
								$db->query();

                                                                // trigger a script after succeeded payment?
                                                                if(JFile::exists(JPATH_SITE . '/bf_paypal_success.php')){
                                                                    require_once(JPATH_SITE . '/bf_paypal_success.php');
                                                                }

								// send mail after succeeded payment?
								if( isset( $options['sendNotificationAfterPayment'] ) && $options['sendNotificationAfterPayment'] ){
                                                                        bf_sendNotificationByPaymentCache(JRequest::getInt('form_id',-1),JRequest::getInt('record_id', -1),'admin');
                                                                        bf_sendNotificationByPaymentCache(JRequest::getInt('form_id',-1),JRequest::getInt('record_id', -1),'mailback');
								}
								
								if($options['downloadableFile']){
	
									$record_id = JRequest::getInt('record_id', -1);
									$tries     = $options['downloadTries'];
									$form_id   = JRequest::getInt('form_id',-1);
									require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/download.php');
	
								} else {
										
									if($options['thankYouPage'] != ''){
										BFRedirect($options['thankYouPage']);
									} else {
										BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_THANK_YOU_FOR_PAYING_WITH_PAYPAL'));
									}
								}
	
								$success = true;
	
							} else {
                                                                if($options['downloadableFile']){

                                                                    $record_id = JRequest::getInt('record_id', -1);
                                                                    $tries     = $options['downloadTries'];
                                                                    $form_id   = JRequest::getInt('form_id',-1);
                                                                    require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/download.php');

								}
                                                                else
                                                                {
                                                                    if($options['useIpn'])
                                                                    {
                                                                        if($options['thankYouPage'] != ''){
										BFRedirect($options['thankYouPage']);
									} else {
										BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_THANK_YOU_FOR_PAYING_WITH_PAYPAL'));
									}
                                                                    }
                                                                    else
                                                                    {
                                                                        $success = false;
                                                                        $msg = JText::_("This transaction was already processed");
                                                                        require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/error.php');
                                                                    }
                                                                }
							}
						}
						else
                                                {
							$success = false;
							$msg = JText::_("Could not find record!");
							require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/error.php');
						}
					}
				}
				else if (strcmp ($lines[0], "FAIL") == 0) {
					$success = false;
					$msg = JText::_("Verification failed");
					require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/error.php');

				}
				else {
					$success = false;
					$msg = JText::_("Verification did not return any values");
					require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/error.php');
				}

				break;
			}
		}	
	} 
	
} else if(JRequest::getBool('paypalDownload') && ( !isset($ff_applic) || $ff_applic == '' ) ){

	JRequest::setVar('format', 'raw');
	
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');
	
	$db->setQuery( "Select * From #__facileforms_forms Where id = " . $db->Quote( JRequest::getInt('form',-1) ) );
	$list = $db->loadObjectList();
	if(count($list) == 0){
		BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_FORM_DOES_NOT_EXIST'));
		exit;
	}
	
	$form = $list[0];
	
	$areas = Zend_Json::decode($form->template_areas);
	if(!is_array($areas)){
		BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_COULD_NOT_FIND_PAYPAL_DATA'));
	}
	
	foreach($areas As $area){
		foreach($area['elements'] As $element){
			if($element['internalType'] == 'bfPayPal'){
	
				$options = $element['options'];

				if($options['downloadableFile']){
				
					$file = $options['filepath'];
				
					$db->setQuery("
									Select paypal_download_tries From 
										#__facileforms_records 
									Where 
										id = '".JRequest::getInt('record_id', -1)."'
									And
										( 
                                                                                    paypal_tx_id = ".$db->Quote('PayPal: ' . JRequest::getVar('tx',''))."
                                                                                  Or
                                                                                    paypal_tx_id = ".$db->Quote('PayPal: ' . JRequest::getVar('tx','') . ' (VALID)')."
                                                                                )
									");
					
					$downloads = $db->loadObjectList();
					
					if(count($downloads) == 1){
						
						if($downloads[0]->paypal_download_tries < $options['downloadTries']){
						
							$db->setQuery("
											Update 
												#__facileforms_records 
											Set
												paypal_download_tries = paypal_download_tries + 1 
											Where 
												id = '".JRequest::getInt('record_id', -1)."'
											And
												(
                                                                                                    paypal_tx_id = ".$db->Quote('PayPal: ' . JRequest::getVar('tx',''))."
                                                                                                  Or
                                                                                                    paypal_tx_id = ".$db->Quote('PayPal: ' . JRequest::getVar('tx','') . ' (VALID)')."
                                                                                                )
											");
										
							$db->query();
							
							if(!file_exists($file)) {
								BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_COULD_NOT_FIND_DOWNLOAD_FILE'));
							}
							
							header('Content-Description: File Transfer');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.basename($file));
							header('Content-Transfer-Encoding: binary');
							header('Expires: 0');
							header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
							header('Pragma: public');
							header('Content-Length: ' . filesize($file));
							ob_clean();
							flush();
							readfile($file) or die("Error reading the file ".$file);
							exit;
				
						} else {
							
							BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_MAX_DOWNLOAD_TRIES_REACHED'));
						}
						
					} else {
						
						BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_DOWNLOAD_NOT_POSSIBLE'));
					}
					
				} else {

					BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_NO_DOWNLOADABLE_PRODUCT'));
				}
				
				break;
			}
		}
	}
	
} else if(JRequest::getBool('showPayPalConnectMsg')){

	JRequest::setVar('format', 'html');
	
	$style = '<link rel="stylesheet" href="'.JURI::root().'templates/'.$mainframe->getTemplate().'/css/template.css" type="text/css" />';
						
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.strtolower(JFactory::getLanguage()->getTag()).'" lang="'.strtolower(JFactory::getLanguage()->getTag()).'" >
<head>'.$style.'</head>
<div class="payPalConnectMsg">
<div class="paymentConnectMsg">
'.BFText::_('COM_BREEZINGFORMS_PLEASE_WAIT_REQUEST').'
</div>
</div>
</body>';
	
} else if(JRequest::getBool('successSofortueberweisung')){
	
	JRequest::setVar('format', 'html');
	
	$tx_token = JRequest::getVar('tx','');
	if($tx_token == ''){
		$msg = JText::_("This transaction id is empty!");
		require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/error.php');
	}
	else {
		
		$formId = JRequest::getInt('user_variable_0','');
		$recordId = JRequest::getInt('user_variable_1','');
		
		if($formId != '' && $recordId != ''){
			
			require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
			require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');
			
			$db->setQuery( "Select * From #__facileforms_forms Where id = " . $db->Quote( $formId ) );
			$list = $db->loadObjectList();
			if(count($list) == 0){
				BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_FORM_DOES_NOT_EXIST'));
				exit;
			}
			
			$form = $list[0];
			
			$areas = Zend_Json::decode($form->template_areas);
			if(!is_array($areas)){
				BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_COULD_NOT_FIND_SU_DATA'));
			}
			
			foreach($areas As $area){
				foreach($area['elements'] As $element){
					if($element['internalType'] == 'bfSofortueberweisung'){
						$options = $element['options'];
						if($options['downloadableFile']){
							$tx_token = JRequest::getVar('tx','');
							$tries    = $options['downloadTries'];
							
							$db->setQuery("
									Select paypal_download_tries From 
										#__facileforms_records 
									Where 
										id = '".$recordId."'
									And
										paypal_tx_id = ".$db->Quote('Sofortüberweisung: ' . JRequest::getVar('tx',''))."
									");
					
							$downloads = $db->loadObjectList();
					
							$confirmed = false;
							if(count($downloads) == 1){
								$confirmed = true;	
							}
							
							require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/sofort_download.php');
						}
						else {
							if($options['thankYouPage'] != ''){
								BFRedirect($options['thankYouPage']);
							} else {
								BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_THANK_YOU_FOR_PAYING_WITH_SU'));
							}
						}
						
						break;
					}
				}
			}
			
		} else {
			$msg = JText::_("COM_BREEZINGFORMS_MISSING_PAYMENT_INFORMATION");
			$tx_token = JText::_("COM_BREEZINGFORMS_NOT_AVAILABLE");
			if(JRequest::getVar('tx','') != ''){
				$tx_token = JRequest::getVar('tx','');
			} 
			require_once(JPATH_SITE . '/components/com_breezingforms/downloadtpl/error.php');
		}
	}
	
} else if( JRequest::getBool('confirmSofortueberweisung') ){
	
	JRequest::setVar('format', 'raw');
	
	$formId = JRequest::getInt('user_variable_0',-1);
	$recordId = JRequest::getInt('user_variable_1',-1);

	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');
	
	$db->setQuery( "Select * From #__facileforms_forms Where id = " . $db->Quote( $formId ) );
	$list = $db->loadObjectList();
	if(count($list) == 0){
		exit;
	}
	
	$form = $list[0];
	
	$areas = Zend_Json::decode($form->template_areas);
	if(!is_array($areas)){
		exit;
	}
	
	foreach($areas As $area){
		foreach($area['elements'] As $element){
			if($element['internalType'] == 'bfSofortueberweisung'){

				$options = $element['options'];
				
				$data = array(
				        'transaction' => JRequest::getVar('transaction',''),
				        'user_id' => JRequest::getVar('user_id',''),
				        'project_id' => JRequest::getVar('project_id',''),
				        'sender_holder' => JRequest::getVar('sender_holder',''),
				        'sender_account_number' => JRequest::getVar('sender_account_number',''),
				        'sender_bank_code' => JRequest::getVar('sender_bank_code',''),
				        'sender_bank_name' => JRequest::getVar('sender_bank_name',''),
				        'sender_bank_bic' => JRequest::getVar('sender_bank_bic',''),
				        'sender_iban' => JRequest::getVar('sender_iban',''),
				        'sender_country_id' => JRequest::getVar('sender_country_id',''),
				        'recipient_holder' => JRequest::getVar('recipient_holder',''),
				        'recipient_account_number' => JRequest::getVar('recipient_account_number',''),
				        'recipient_bank_code' => JRequest::getVar('recipient_bank_code',''),
				        'recipient_bank_name' => JRequest::getVar('recipient_bank_name',''),
				        'recipient_bank_bic' => JRequest::getVar('recipient_bank_bic',''),
				        'recipient_iban' => JRequest::getVar('recipient_iban',''),
				        'recipient_country_id' => JRequest::getVar('recipient_country_id',''),
				        'international_transaction' => JRequest::getVar('international_transaction',''),
				        'amount' => JRequest::getVar('amount',''),
				        'currency_id' => JRequest::getVar('currency_id',''),
				        'reason_1' => JRequest::getVar('reason_1',''),
				        'reason_2' => JRequest::getVar('reason_2',''),
				        'security_criteria' => JRequest::getVar('security_criteria',''),
				        'user_variable_0' => JRequest::getVar('user_variable_0',''),
				        'user_variable_1' => JRequest::getVar('user_variable_1',''),
				        'user_variable_2' => JRequest::getVar('user_variable_2',''),
				        'user_variable_3' => JRequest::getVar('user_variable_3',''),
				        'user_variable_4' => JRequest::getVar('user_variable_4',''),
				        'user_variable_5' => JRequest::getVar('user_variable_5',''),
				        'created' => JRequest::getVar('created',''),
				        'project_password' => $options['project_password']
				);
				
				$data_implode = implode('|', $data);
				$hash = sha1($data_implode);
				
				$query = "SELECT * FROM #__facileforms_records WHERE id = '".$recordId."' And paypal_tx_id = '' LIMIT 1";
				$db->setQuery($query);
				$txid = $db->loadObjectList();

				if($hash == JRequest::getVar('hash','')){
					
					if (count($txid) != 0) {
							
						if($txid[0]->paypal_tx_id == ''){
								
							$db->setQuery("
										Update 
											#__facileforms_records 
										Set 
											paypal_tx_id = ".$db->Quote('Sofortüberweisung: ' . JRequest::getVar('transaction','')).", 
											paypal_payment_date = ".$db->Quote(date('Y-m-d H:i:s',strtotime(JRequest::getVar('created','')))).",
											paypal_testaccount = 0,
											paypal_download_tries = 0
										Where 
											id = '".$recordId."'
											");
	
							$db->query();
							
							$recipients = explode('###', JRequest::getVar('user_variable_2',''));
							$recipientsSize = count($recipients);
							$mailer = JFactory::getMailer();
							$mailer->Subject = BFText::_('COM_BREEZINGFORMS_YOUR_PAYMENT_AT_SU');
							$mailer->Body 	 = BFText::_('COM_BREEZINGFORMS_HALLO')."\n\n";
							$mailer->Body 	.= BFText::_('COM_BREEZINGFORMS_YOUR_PAYMENT_SUCCEEDED')."\n\n";
							$mailer->Body 	.= '--------------------------------------'."\n\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_REASON1').': '.JRequest::getVar('reason_1','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_REASON2').': '.JRequest::getVar('reason_2','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_AMOUNT').': '.str_replace('.',',',JRequest::getVar('amount','')).' '. JRequest::getVar('currency_id','') ."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_TRANSACTION').': '.JRequest::getVar('transaction','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_ACCOUNT_HOLDER').': '.JRequest::getVar('sender_holder','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_ACCOUNT_NUMBER').': '.JRequest::getVar('sender_account_number','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_BANK_CODE').': '.JRequest::getVar('recipient_bank_code','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_BANK_NAME').': '.JRequest::getVar('sender_bank_name','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_BIC').': '.JRequest::getVar('sender_bank_bic','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_IBAN').': '.JRequest::getVar('sender_iban','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_PAYMENT_DATE').': '.JRequest::getVar('created','')."\n\n";
							
							$mailer->Body 	.= '--------------------------------------'."\n\n";
							$mailer->Body 	.= BFText::_('COM_BREEZINGFORMS_RECEIPT_FOR_YOUR_PAYMENT')."\n\n";
							$mailer->Body 	.= '--------------------------------------'."\n\n";
							
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_ACCOUNT_HOLDER').': '.JRequest::getVar('recipient_holder','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_ACCOUNT_NUMBER').': '.JRequest::getVar('recipient_account_number','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_BANK_CODE').': '.JRequest::getVar('recipient_bank_code','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_BANK_NAME').': '.JRequest::getVar('recipient_bank_name','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_BIC').': '.JRequest::getVar('recipient_bank_bic','')."\n";
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_IBAN').': '.JRequest::getVar('recipient_iban','')."\n\n";
							
							$mailer->Body 	.= '--------------------------------------'."\n\n";
							
							$mailer->Body   .= BFText::_('COM_BREEZINGFORMS_PAYMENT_GATEWAY_SU');
							
							for($i = 0; $i < $recipientsSize;$i++){
								if(bf_is_email($recipients[$i])){
									$mailer->AddAddress($recipients[$i]);
									$mailer->Send();
								}
							}

                                                        // trigger a script after succeeded payment?
                                                        if(JFile::exists(JPATH_SITE . '/bf_sofortueberweisung_success.php')){
                                                            require_once(JPATH_SITE . '/bf_sofortueberweisung_success.php');
                                                        }

							// send mail after succeeded payment?
                                                        if( isset( $options['sendNotificationAfterPayment'] ) && $options['sendNotificationAfterPayment'] ) {
                                                            bf_sendNotificationByPaymentCache($formId,$recordId,'admin');
                                                            bf_sendNotificationByPaymentCache($formId,$recordId,'mailback');
                                                        }
						}
					}
					
				}
				
				break;	
			}
		}
	}	
}  else if(JRequest::getBool('sofortueberweisungDownload')  && ( !isset($ff_applic) || $ff_applic == '' ) ){

	JRequest::setVar('format', 'raw');
	
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');
	
	$db->setQuery( "Select * From #__facileforms_forms Where id = " . $db->Quote( JRequest::getInt('form',-1) ) );
	$list = $db->loadObjectList();
	if(count($list) == 0){
		BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_FORM_DOES_NOT_EXIST'));
		exit;
	}
	
	$form = $list[0];
	
	$areas = Zend_Json::decode($form->template_areas);
	if(!is_array($areas)){
		BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_COULD_NOT_FIND_PAYMENT_DATA'));
	}
	
	foreach($areas As $area){
		foreach($area['elements'] As $element){
			if($element['internalType'] == 'bfSofortueberweisung'){
	
				$options = $element['options'];

				if($options['downloadableFile']){
				
					$file = $options['filepath'];
				
					$db->setQuery("
									Select paypal_download_tries From 
										#__facileforms_records 
									Where 
										id = '".JRequest::getInt('record_id', -1)."'
									And
										paypal_tx_id = ".$db->Quote('Sofortüberweisung: ' . JRequest::getVar('tx',''))."
									");
					
					$downloads = $db->loadObjectList();
					
					if(count($downloads) == 1){
						
						if($downloads[0]->paypal_download_tries < $options['downloadTries']){
						
							$db->setQuery("
											Update 
												#__facileforms_records 
											Set
												paypal_download_tries = paypal_download_tries + 1 
											Where 
												id = '".JRequest::getInt('record_id', -1)."'
											And
												paypal_tx_id = ".$db->Quote('Sofortüberweisung: ' . JRequest::getVar('tx',''))."
											");
										
							$db->query();
							
							if(!file_exists($file)) {
								BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_COULD_NOT_FIND_DOWNLOAD_FILE'));
							}
							
							header('Content-Description: File Transfer');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.basename($file));
							header('Content-Transfer-Encoding: binary');
							header('Expires: 0');
							header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
							header('Pragma: public');
							header('Content-Length: ' . filesize($file));
							ob_clean();
							flush();
							readfile($file) or die("Error reading the file ".$file);
							exit;
				
						} else {
							
							BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_MAX_DOWNLOAD_TRIES_REACHED'));
						}
						
					} else {
						
						BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_DOWNLOAD_NOT_POSSIBLE'));
					}
					
				} else {

					BFRedirect(JURI::root(), BFText::_('COM_BREEZINGFORMS_NO_DOWNLOADABLE_PRODUCT'));
				}
				
				break;
			}
		}
	}
	
} else if( JRequest::getBool('flashUpload') ){
	if (is_numeric(JRequest::getVar('form','')) && !empty($_FILES) && JRequest::getVar('bfFlashUploadTicket','') != '') {
		$db->setQuery("Select form.id From #__facileforms_forms as form, #__facileforms_elements as element Where form.id = ".$db->Quote(JRequest::getInt('form',-1)) . " And element.name = " . $db->Quote(JRequest::getVar('itemName','')) . " And element.form = " . $db->Quote(JRequest::getInt('form',-1)));
		$formIdCount = count($db->loadObjectList());
		if($formIdCount > 0){
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = JPATH_SITE . '/components/com_breezingforms/uploads/';
			if( @file_exists( $targetPath ) && @is_dir( $targetPath ) && @is_writeable( $targetPath ) ){
				mt_srand();
				$secureTicket = md5( strtotime('now') .  mt_rand( 0, mt_getrandmax() ) );
                                $allowed = "/[^a-z0-9\\.\\-\\_]/i";
				$targetFile = str_replace('//','/',$targetPath) . preg_replace($allowed,"_",$_FILES['Filedata']['name']) . '_' . JRequest::getVar('itemName','') . '_' . JRequest::getVar('bfFlashUploadTicket') . '_' . $secureTicket . '_flashtmp';
				if(@move_uploaded_file($tempFile,$targetFile)){
					echo "1";
				} else {
					echo 'Could not upload file '.addslashes($_FILES['Filedata']['name']).'!';
				}
			} else {
				echo 'Invalid file storage path for file '.addslashes($_FILES['Filedata']['name']).'! Please check the upload folder path and its permissions!';
			}
		} else {
			echo 'Form id and element do not match!';
		}
	}
}

if( JRequest::getBool('raw', false) )
{
	session_write_close();
	exit;
}

$cache->setCaching(true);