<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');

class BFQuickMode{
	
	/**
	 * @var HTML_facileFormsProcessor
	 */
	private $p = null;
	
	private $dataObject = array();
	
	private $rootMdata = array();
	
	private $fading = true;
	
	private $fadingClass = '';
	
	private $fadingCall = '';
	
	private $useErrorAlerts = false;

        private $useDefaultErrors = false;

        private $useBalloonErrors = false;
	
	private $rollover = false;
	
	private $rolloverColor = '';
	
	private $toggleFields = '';
	
	private $hasFlashUpload = false;
	
	private $flashUploadTicket = '';
	
	private $cancelImagePath = '';
	
	private $uploadImagePath = '';
	
	function __construct( HTML_facileFormsProcessor $p ){
            
                // will make sure mootools loads first, important 4 jquery
                JHTML::_('behavior.mootools');
                JFactory::getDocument()->addScriptDeclaration('<!--');
                
                $this->p = $p;
		$this->dataObject = Zend_Json::decode( base64_decode($this->p->formrow->template_code) );
		$this->rootMdata = $this->dataObject['properties'];
		$this->fading = $this->rootMdata['fadeIn'];
		$this->useErrorAlerts = $this->rootMdata['useErrorAlerts'];
                $this->useDefaultErrors = isset($this->rootMdata['useDefaultErrors']) ? $this->rootMdata['useDefaultErrors'] : false;
                $this->useBalloonErrors = isset($this->rootMdata['useBalloonErrors']) ? $this->rootMdata['useBalloonErrors'] : false;
		$this->rollover = $this->rootMdata['rollover'];
		$this->rolloverColor = $this->rootMdata['rolloverColor'];
		$this->toggleFields = $this->parseToggleFields( isset($this->rootMdata['toggleFields']) ? $this->rootMdata['toggleFields'] : '[]' );
		
		mt_srand();
		$this->flashUploadTicket = md5( strtotime('now') .  mt_rand( 0, mt_getrandmax() ) );

                JFactory::getDocument()->addStyleDeclaration('.bfClearfix:after {
content: ".";
display: block;
height: 0;
clear: both;
visibility: hidden;
}
.bfInline{
float:left;
}
.bfFadingClass{
display:none;
}');
		JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_breezingforms/libraries/jquery/jquery.min.js');
		JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_breezingforms/libraries/jquery/tooltip.js');

                if($this->useBalloonErrors){
                    JFactory::getDocument()->addStyleSheet( JURI::root(true) . '/components/com_breezingforms/libraries/jquery/validationEngine.jquery.css' );
                    JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_breezingforms/libraries/jquery/jquery.validationEngine-en.js');
                    JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_breezingforms/libraries/jquery/jquery.validationEngine.js');
                }

		$toggleCode = '';
		if($this->toggleFields != '[]'){
			$toggleCode = '
			var toggleFieldsArray = '.$this->toggleFields.';
			String.prototype.beginsWith = function(t, i) { if (i==false) { return
			(t == this.substring(0, t.length)); } else { return (t.toLowerCase()
			== this.substring(0, t.length).toLowerCase()); } } 
			function bfDeactivateSectionFields(){
				for( var i = 0; i < bfDeactivateSection.length; i++ ){
                                        bfSetFieldValue(bfDeactivateSection[i], "off");
					JQuery("#"+bfDeactivateSection[i]).each(function(i){
      					if( JQuery(this).get(0).name && JQuery(this).get(0).name.beginsWith("ff_nm_", true) ){
                                            bfDeactivateField[JQuery(this).get(0).name] = true;
      					}
					});
				}
                                for( var i = 0; i < toggleFieldsArray.length; i++ ){
                                    if(toggleFieldsArray[i].state == "turn"){
                                        bfSetFieldValue(toggleFieldsArray[i].tName, "off");
                                    }
                                }
			}
			function bfToggleFields(state, tCat, tName, thisBfDeactivateField){
				// maybe a little to harsh, but currently no other workaround
				// file uploads will be removed for the complete form if a rule is executed
				// make sure you offer file uploads at the end of your form if you have visibility rules!
				if(typeof bfFlashUploadInterval != "undefined"){
					window.clearInterval( bfFlashUploadInterval );
					for(qID in bfFlashUploadAll){
						try{
							JQuery(bfFlashUploadAll[qID]).uploadifyCancel(qID);
						}catch(e){}
					}
					bfFlashUploadTooLarge = {};
					bfFlashUploadAll = {};
					JQuery("#bfFileQueue").html("")
					JQuery(".bfFlashFileQueueClass").html("");
				}
				if(state == "on"){
					if(tCat == "element"){
                                                if( JQuery("[name=ff_nm_"+tName+"[]]").parent().attr("class").substr(0, 10) == "bfElemWrap" ){
                                                    JQuery("[name=ff_nm_"+tName+"[]]").parent().css("display", "");
                                                } else if(JQuery("[name=ff_nm_"+tName+"[]]").get(0).type == "checkbox" || JQuery("[name=ff_nm_"+tName+"[]]").get(0).type == "radio"){
                                                    JQuery("[name=ff_nm_"+tName+"[]]").parent().parent().css("display", "");
                                                }
						thisBfDeactivateField["ff_nm_"+tName+"[]"] = false;
                                                bfSetFieldValue(tName, "on");
					} else {
						JQuery("#"+tName).css("display", "");
                                                bfSetFieldValue(tName, "on");
						JQuery("#"+tName).find(".ff_elem").each(function(i){
                                                    if( JQuery(this).get(0).name && JQuery(this).get(0).name.beginsWith("ff_nm_", true) ){
                                                        thisBfDeactivateField[JQuery(this).get(0).name] = false;
                                                    }
						});
					}
				} else {
					if(tCat == "element"){
                                                if( JQuery("[name=ff_nm_"+tName+"[]]").parent().attr("class").substr(0, 10) == "bfElemWrap" ){
                                                    JQuery("[name=ff_nm_"+tName+"[]]").parent().css("display", "none");
                                                } else if(JQuery("[name=ff_nm_"+tName+"[]]").get(0).type == "checkbox" || JQuery("[name=ff_nm_"+tName+"[]]").get(0).type == "radio"){
                                                    JQuery("[name=ff_nm_"+tName+"[]]").parent().parent().css("display", "none");
                                                }
						thisBfDeactivateField["ff_nm_"+tName+"[]"] = true;
                                                bfSetFieldValue(tName, "off");
					} else {
						JQuery("#"+tName).css("display", "none");
                                                bfSetFieldValue(tName, "off");
						JQuery("#"+tName+" .ff_elem").each(function(i){
                                                    if( JQuery(this).get(0).name && JQuery(this).get(0).name.beginsWith("ff_nm_", true) ){
                                                        thisBfDeactivateField[JQuery(this).get(0).name] = true;
                                                    }
						});
					}
				}
			}
                        function bfSetFieldValue(name, condition){
                            for( var i = 0; i < toggleFieldsArray.length; i++ ){
                                if( toggleFieldsArray[i].action == "if" ) {
                                    if(name == toggleFieldsArray[i].tCat && condition == toggleFieldsArray[i].statement){

                                        var element = JQuery("[name=ff_nm_"+toggleFieldsArray[i].condition+"[]]");
                                        
                                        switch(element.get(0).type){
                                            case "text":
                                            case "textarea":
                                                if(toggleFieldsArray[i].value == "!empty"){
                                                    element.val("");
                                                } else {
                                                    element.val(value);
                                                }
                                            break;
                                            case "select-multiple":
                                            case "select-one":
                                                if(toggleFieldsArray[i].value == "!empty"){
                                                    for(var i = 0; i < element.get(0).options.length; i++){
                                                        element.get(0).options[i].selected = false;
                                                    }
                                                }
                                                for(var i = 0; i < element.get(0).options.length; i++){
                                                    if(element.get(0).options[i].value == toggleFieldsArray[i].value){
                                                        element.get(0).options[i].selected = true;
                                                    }
                                                }
                                            break;
                                            case "radio":
                                            case "checkbox":
                                                var radioLength = element.size();
                                                if(toggleFieldsArray[i].value == "!empty"){
                                                    for(var j = 0; j < radioLength; j++){
                                                        element.get(j).checked = false;
                                                    }
                                                }
						for(var j = 0; j < radioLength; j++){
                                                    if( element.get(j).value == toggleFieldsArray[i].value ){
                                                        element.get(j).checked = true;
                                                    }
                                                }
                                            break;
                                        }
                                    }
                                }
                            }
                        }
			function bfRegisterToggleFields(){
                        
                                var thisToggleFieldsArray = toggleFieldsArray;
				var thisBfDeactivateField = bfDeactivateField;
                                var thisBfToggleFields = bfToggleFields;
                                
				for( var i = 0; i < toggleFieldsArray.length; i++ ){
                                        if( toggleFieldsArray[i].action == "turn" && (toggleFieldsArray[i].tCat == "element" || toggleFieldsArray[i].tCat == "section") ){
						var toggleField = toggleFieldsArray[i];
						var element = JQuery("[name=ff_nm_"+toggleFieldsArray[i].sName+"[]]");
						if(element.get(0)){
							switch(element.get(0).type){
								case "text":
								case "textarea":
									JQuery("[name=ff_nm_"+toggleField.sName+"[]]").blur(
										function(){
											for( var k = 0; k < thisToggleFieldsArray.length; k++ ){
												var regExp = "";
												if(thisToggleFieldsArray[k].value.beginsWith("!", true) && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"){
										 			regExp = thisToggleFieldsArray[k].value.substring(1, thisToggleFieldsArray[k].value.length);
										 		}

                                                                                                if(thisToggleFieldsArray[k].condition == "isnot"){
                                                                                                    if(
                                                                                                            ( ( regExp != "" && JQuery(this).val().test(regExp) <= 0 ) || JQuery(this).val() != thisToggleFieldsArray[k].value ) && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                    ){
                                                                                                            var names = thisToggleFieldsArray[k].tName.split(",");
                                                                                                            for(var n = 0; n < names.length; n++){
                                                                                                                thisBfToggleFields(thisToggleFieldsArray[k].state, thisToggleFieldsArray[k].tCat, JQuery.trim(names[n]), thisBfDeactivateField);
                                                                                                            }
                                                                                                            //break;
                                                                                                    }
                                                                                                } else if(thisToggleFieldsArray[k].condition == "is"){
                                                                                                    if(
                                                                                                            ( ( regExp != "" && JQuery(this).val().test(regExp) > 0 ) || JQuery(this).val() == thisToggleFieldsArray[k].value ) && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                    ){
                                                                                                            var names = thisToggleFieldsArray[k].tName.split(",");
                                                                                                            for(var n = 0; n < names.length; n++){
                                                                                                                thisBfToggleFields(thisToggleFieldsArray[k].state, thisToggleFieldsArray[k].tCat, JQuery.trim(names[n]), thisBfDeactivateField);
                                                                                                            }
                                                                                                            //break;
                                                                                                    }
                                                                                                }
											}
										}
									);
									break;
								case "select-multiple":
								case "select-one":
									JQuery("[name=ff_nm_"+toggleField.sName+"[]]").change(
										function(){
											var res = JQuery.isArray( JQuery(this).val() ) == false ? [ JQuery(this).val() ] : JQuery(this).val();
											for( var k = 0; k < thisToggleFieldsArray.length; k++ ){
												
												// The or-case in lists 
												var found = false;
												var chkGrpValues = new Array();
										 		if(thisToggleFieldsArray[k].value.beginsWith("#", true) && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"){
										 			chkGrpValues = thisToggleFieldsArray[k].value.substring(1, thisToggleFieldsArray[k].value.length).split("|");
										 			for(var l = 0; l < chkGrpValues.length; l++){
										 				if( JQuery.inArray(chkGrpValues[l], res) != -1 ){
										 					found = true;
										 					break;
										 				}
										 			}
										 		}
												// the and-case in lists
												var foundCount = 0;
												chkGrpValues2 = new Array();
										 		if(thisToggleFieldsArray[k].value.beginsWith("#", true) && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"){
										 			chkGrpValues2 = thisToggleFieldsArray[k].value.substring(1, thisToggleFieldsArray[k].value.length).split(";");
										 			for(var l = 0; l < res.length; l++){
										 				if( JQuery.inArray(res[l], chkGrpValues2) != -1 ){
										 					foundCount++;
										 				}
										 			}
										 		}
                                                                                                
                                                                                                if(thisToggleFieldsArray[k].condition == "isnot"){
                                                                                                
                                                                                                    if(
                                                                                                            (
                                                                                                                    !JQuery.isArray(res) && JQuery(this).val() != thisToggleFieldsArray[k].value && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                            )
                                                                                                            ||
                                                                                                            (
                                                                                                                    JQuery.isArray(res) && ( JQuery.inArray(thisToggleFieldsArray[k].value, res) == -1 || !found || ( foundCount == 0 || foundCount != chkGrpValues2.length ) ) && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                            )
                                                                                                     ){
                                                                                                            var names = thisToggleFieldsArray[k].tName.split(",");
                                                                                                            for(var n = 0; n < names.length; n++){
                                                                                                                thisBfToggleFields(thisToggleFieldsArray[k].state, thisToggleFieldsArray[k].tCat, JQuery.trim(names[n]), thisBfDeactivateField);
                                                                                                            }
                                                                                                            //break;
                                                                                                    }
                                                                                                } else if(thisToggleFieldsArray[k].condition == "is"){
                                                                                                    if(
                                                                                                            (
                                                                                                                    !JQuery.isArray(res) && JQuery(this).val() == thisToggleFieldsArray[k].value && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                            )
                                                                                                            ||
                                                                                                            (
                                                                                                                    JQuery.isArray(res) && ( JQuery.inArray(thisToggleFieldsArray[k].value, res) != -1 || found || ( foundCount != 0 && foundCount == chkGrpValues2.length ) ) && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                            )
                                                                                                     ){
                                                                                                            var names = thisToggleFieldsArray[k].tName.split(",");
                                                                                                            for(var n = 0; n < names.length; n++){
                                                                                                                thisBfToggleFields(thisToggleFieldsArray[k].state, thisToggleFieldsArray[k].tCat, JQuery.trim(names[n]), thisBfDeactivateField);
                                                                                                            }
                                                                                                            //break;
                                                                                                    }
                                                                                                }
											}
										}
									);
									break;
								case "radio":
								case "checkbox":
									var radioLength = JQuery("[name=ff_nm_"+toggleField.sName+"[]]").size();
									for(var j = 0; j < radioLength; j++){
										 JQuery("#" + JQuery("[name=ff_nm_"+toggleField.sName+"[]]").get(j).id ).click(										 	
										 	function(){
										 		// NOT O(n^2) since its ony executed on click event!
										 		for( var k = 0; k < thisToggleFieldsArray.length; k++ ){
										 			
										 			// used for complex checkbox group case below
										 			var chkGrpValues = new Array();
										 			if(JQuery(this).get(0).checked && thisToggleFieldsArray[k].value.beginsWith("#", true) && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"){
										 				chkGrpValues = thisToggleFieldsArray[k].value.substring(1, thisToggleFieldsArray[k].value.length).split("|");
										 			}

                                                                                                        if(thisToggleFieldsArray[k].condition == "isnot"){

                                                                                                            if(
                                                                                                                    // simple radio case for selected value
                                                                                                                    ( JQuery(this).get(0).type == "radio" && JQuery(this).get(0).checked && JQuery(this).val() != thisToggleFieldsArray[k].value && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]" )
                                                                                                                    ||
                                                                                                                    // single checkbox case for checked/unchecked
                                                                                                                    (
                                                                                                                            JQuery(this).get(0).type == "checkbox" &&
                                                                                                                            JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]" &&
                                                                                                                            ( JQuery(this).get(0).checked && thisToggleFieldsArray[k].value != "!checked"
                                                                                                                             ||
                                                                                                                              JQuery(this).get(0).checked && thisToggleFieldsArray[k].value == "!unchecked"
                                                                                                                            )
                                                                                                                    )
                                                                                                                    ||
                                                                                                                    // complex checkbox/radio group case by multiple values
                                                                                                                    (
                                                                                                                            JQuery(this).get(0).checked && JQuery.inArray(JQuery(this).val(), chkGrpValues) == -1 && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                                    )
                                                                                                                    ||
                                                                                                                    // simple checkbox group case by single value
                                                                                                                    (
                                                                                                                            JQuery(this).get(0).type == "checkbox" && JQuery(this).get(0).checked && JQuery(this).val() != thisToggleFieldsArray[k].value && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                                    )
                                                                                                            ){
                                                                                                                    var names = thisToggleFieldsArray[k].tName.split(",");
                                                                                                                    for(var n = 0; n < names.length; n++){
                                                                                                                        thisBfToggleFields(thisToggleFieldsArray[k].state, thisToggleFieldsArray[k].tCat, JQuery.trim(names[n]), thisBfDeactivateField);
                                                                                                                    }
                                                                                                                    //break;
                                                                                                            }
                                                                                                        }
                                                                                                        else
                                                                                                        if(thisToggleFieldsArray[k].condition == "is"){
                                                                                                            if(
                                                                                                                    // simple radio case for selected value
                                                                                                                    ( JQuery(this).get(0).type == "radio" && JQuery(this).get(0).checked && JQuery(this).val() == thisToggleFieldsArray[k].value && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]" )
                                                                                                                    ||
                                                                                                                    // single checkbox case for checked/unchecked
                                                                                                                    (
                                                                                                                            JQuery(this).get(0).type == "checkbox" &&
                                                                                                                            JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]" &&
                                                                                                                            ( JQuery(this).get(0).checked && thisToggleFieldsArray[k].value == "!checked"
                                                                                                                             ||
                                                                                                                              !JQuery(this).get(0).checked && thisToggleFieldsArray[k].value == "!unchecked"
                                                                                                                            )
                                                                                                                    )
                                                                                                                    ||
                                                                                                                    // complex checkbox/radio group case by multiple values
                                                                                                                    (
                                                                                                                            JQuery(this).get(0).checked && JQuery.inArray(JQuery(this).val(), chkGrpValues) != -1 && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                                    )
                                                                                                                    ||
                                                                                                                    // simple checkbox group case by single value
                                                                                                                    (
                                                                                                                            JQuery(this).get(0).type == "checkbox" && JQuery(this).get(0).checked && JQuery(this).val() == thisToggleFieldsArray[k].value && JQuery(this).get(0).name == "ff_nm_"+thisToggleFieldsArray[k].sName+"[]"
                                                                                                                    )
                                                                                                            ){
                                                                                                                    var names = thisToggleFieldsArray[k].tName.split(",");
                                                                                                                    for(var n = 0; n < names.length; n++){
                                                                                                                        thisBfToggleFields(thisToggleFieldsArray[k].state, thisToggleFieldsArray[k].tCat, JQuery.trim(names[n]), thisBfDeactivateField);
                                                                                                                    }
                                                                                                                    //break;
                                                                                                            }
                                                                                                        }
												}
												
											}
										 );
									}
									break;
							}
						}
					}
				}
			}
			';
			
		}
		
		JFactory::getDocument()->addScriptDeclaration('
			var JQuery = jQuery.noConflict();
                        var inlineErrorElements = new Array();
			var bfSummarizers = new Array();
			var bfDeactivateField = new Array();
			var bfDeactivateSection = new Array();
			'.$toggleCode.'
			function bfCheckMaxlength(id, maxlength, showMaxlength){
				if( JQuery("#ff_elem"+id).val().length > maxlength ){
					JQuery("#ff_elem"+id).val( JQuery("#ff_elem"+id).val().substring(0, maxlength) );
				}
				if(showMaxlength){
					JQuery("#bfMaxLengthCounter"+id).text( "(" + (maxlength - JQuery("#ff_elem"+id).val().length) + " '.BFText::_('COM_BREEZINGFORMS_CHARS_LEFT').')" );
				}
			}
			function bfRegisterSummarize(id, connectWith, type, emptyMessage, hideIfEmpty){
				bfSummarizers.push( { id : id, connectWith : connectWith, type : type, emptyMessage : emptyMessage, hideIfEmpty : hideIfEmpty } );
			}
			function bfField(name){
				var value = "";
				switch(ff_getElementByName(name).type){
					case "radio":
						if(JQuery("[name="+ff_getElementByName(name).name+"]:checked").val() != "" && typeof JQuery("[name="+ff_getElementByName(name).name+"]:checked").val() != "undefined"){
							value = JQuery("[name="+ff_getElementByName(name).name+"]:checked").val();
							if(!isNaN(value)){
								value = Number(value);
							}
						}
						break;
					case "checkbox":
					case "select-one":
					case "select-multiple":
						var nodeList = document["'.$this->p->form_id.'"][""+ff_getElementByName(name).name+""];
						if(ff_getElementByName(name).type == "checkbox" && typeof nodeList.length == "undefined"){
							if(typeof JQuery("[name="+ff_getElementByName(name).name+"]:checked").val() != "undefined"){
								value = JQuery("[name="+ff_getElementByName(name).name+"]:checked").val();
								if(!isNaN(value)){
									value = Number(value);
								}
							}
						} else {
							var val = "";
							for(var j = 0; j < nodeList.length; j++){
								if(nodeList[j].checked || nodeList[j].selected){
									val += nodeList[j].value + ", ";
								}
							}
							if(val != ""){
								value = val.substr(0, val.length - 2);
								if(!isNaN(value)){
									value = Number(value);
								}
							}
						}
						break;
					default:
						if(!isNaN(ff_getElementByName(name).value)){
							value = Number(ff_getElementByName(name).value);
						} else {
							value = ff_getElementByName(name).value;
						}
				}
				return value;
			}
			function populateSummarizers(){
				// cleaning first
                                
				for(var i = 0; i < bfSummarizers.length; i++){
					JQuery("#"+bfSummarizers[i].id).parent().css("display", "");
					JQuery("#"+bfSummarizers[i].id).html("<span class=\"bfNotAvailable\">"+bfSummarizers[i].emptyMessage+"</span>");
				}
				for(var i = 0; i < bfSummarizers.length; i++){
					var summVal = "";
					switch(bfSummarizers[i].type){
						case "bfTextfield":
						case "bfTextarea":
						case "bfHidden":
						case "bfCalendar":
						case "bfFile":
							if(JQuery("[name=ff_nm_"+bfSummarizers[i].connectWith+"[]]").val() != ""){
								JQuery("#"+bfSummarizers[i].id).text( JQuery("[name=ff_nm_"+bfSummarizers[i].connectWith+"[]]").val() ).html();
								var breakableText = JQuery("#"+bfSummarizers[i].id).html().replace(/\\r/g, "").replace(/\\n/g, "<br/>");
								
								if(breakableText != ""){
									var calc = null;
									eval( "calc = typeof bfFieldCalc"+bfSummarizers[i].id+" != \"undefined\" ? bfFieldCalc"+bfSummarizers[i].id+" : null" );
									if(calc){
										breakableText = calc(breakableText);
									}
								}
								
								JQuery("#"+bfSummarizers[i].id).html(breakableText);
								summVal = breakableText;
							}
						break;
						case "bfRadioGroup":
						case "bfCheckbox":
							if(JQuery("[name=ff_nm_"+bfSummarizers[i].connectWith+"[]]:checked").val() != "" && typeof JQuery("[name=ff_nm_"+bfSummarizers[i].connectWith+"[]]:checked").val() != "undefined"){
								var theText = JQuery("[name=ff_nm_"+bfSummarizers[i].connectWith+"[]]:checked").val();
								if(theText != ""){
									var calc = null;
									eval( "calc = typeof bfFieldCalc"+bfSummarizers[i].id+" != \"undefined\" ? bfFieldCalc"+bfSummarizers[i].id+" : null" );
									if(calc){
										theText = calc(theText);
									}
								}
								JQuery("#"+bfSummarizers[i].id).text( theText );
								summVal = theText;
							}
						break;
						case "bfCheckboxGroup":
						case "bfSelect":
							var val = "";
							var nodeList = document["'.$this->p->form_id.'"]["ff_nm_"+bfSummarizers[i].connectWith+"[]"];
							
							for(var j = 0; j < nodeList.length; j++){
								if(nodeList[j].checked || nodeList[j].selected){
									val += nodeList[j].value + ", ";
								}
							}
							if(val != ""){
								var theText = val.substr(0, val.length - 2);
								if(theText != ""){
									var calc = null;
									eval( "calc = typeof bfFieldCalc"+bfSummarizers[i].id+" != \"undefined\" ? bfFieldCalc"+bfSummarizers[i].id+" : null" );
									if(calc){
										theText = calc(theText);
									}
								}
								JQuery("#"+bfSummarizers[i].id).text( theText );
								summVal = theText;
							}
						break;
					}
					
					if( ( bfSummarizers[i].hideIfEmpty && summVal == "" ) || ( typeof bfDeactivateField != "undefined" && bfDeactivateField["ff_nm_"+bfSummarizers[i].connectWith+"[]"] ) ){
                                            JQuery("#"+bfSummarizers[i].id).parent().css("display", "none");
					}
				}
			}
');
		
		if($this->fading || !$this->useErrorAlerts || $this->rollover){
			if(!$this->useErrorAlerts){
                                $defaultErrors = '';
                                if($this->useDefaultErrors || (!$this->useDefaultErrors && !$this->useBalloonErrors)){
                                    $defaultErrors = 'JQuery(".bfErrorMessage").html("");
					JQuery(".bfErrorMessage").css("display","none");
					JQuery(".bfErrorMessage").fadeIn(1500);
					var allErrors = "";
					var errors = error.split("\n");
					for(var i = 0; i < errors.length; i++){
						allErrors += "<div class=\"bfError\">" + errors[i] + "</div>";
					}
					JQuery(".bfErrorMessage").html(allErrors);
					JQuery(".bfErrorMessage").css("display","");';
                                }
				JFactory::getDocument()->addScriptDeclaration('var bfUseErrorAlerts = false;'."\n");
				JFactory::getDocument()->addScriptDeclaration('
				function bfShowErrors(error){
                                        '.$defaultErrors.'

                                        if(JQuery.validationEngine)
                                        {
                                            JQuery("#'.$this->p->form_id.'").validationEngine({
                                              promptPosition: "bottomLeft",
                                              success :  false,
                                              failure : function() {}
                                            });

                                            for(var i = 0; i < inlineErrorElements.length; i++)
                                            {
                                                if(inlineErrorElements[i][1] != "")
                                                {
                                                    var prompt = null;
                                                    
                                                    if(inlineErrorElements[i][0] == "bfCaptchaEntry"){
                                                        prompt = JQuery.validationEngine.buildPrompt("#bfCaptchaEntry",inlineErrorElements[i][1],"error");
                                                    }
                                                    else if(inlineErrorElements[i][0] == "bfReCaptchaEntry"){
                                                        // nothing here yet for recaptcha, alert is default
                                                        alert(inlineErrorElements[i][1]);
                                                    }
                                                    else if(typeof JQuery("#flashUpload"+inlineErrorElements[i][0]).get(0) != "undefined")
                                                    {
                                                        prompt = JQuery.validationEngine.buildPrompt("#"+JQuery("#flashUpload"+inlineErrorElements[i][0]).val(),inlineErrorElements[i][1],"error");
                                                    }
                                                    else
                                                    {
                                                        prompt = JQuery.validationEngine.buildPrompt("#"+ff_getElementByName(inlineErrorElements[i][0]).id,inlineErrorElements[i][1],"error");
                                                    }
                                                    
                                                    JQuery(prompt).mouseover(
                                                        function(){
                                                            var inlineError = JQuery(this).attr("class").split(" ");
                                                            if(inlineError && inlineError.length && inlineError.length == 2){
                                                                var result = inlineError[1].split("formError");
                                                                if(result && result.length && result.length >= 1){
                                                                    JQuery.validationEngine.closePrompt("#"+result[0]);
                                                                }
                                                            }
                                                        }
                                                    );
                                                }
                                                else
                                                {
                                                    if(typeof JQuery("#flashUpload"+inlineErrorElements[i][0]).get(0) != "undefined")
                                                    {
                                                        JQuery.validationEngine.closePrompt("#"+JQuery("#flashUpload"+inlineErrorElements[i][0]).val());
                                                    }
                                                    else
                                                    {
                                                        JQuery.validationEngine.closePrompt("#"+ff_getElementByName(inlineErrorElements[i][0]).id);
                                                    }
                                                }
                                            }
                                            inlineErrorElements = new Array();
                                        }
				}');
			}
			if($this->fading){
				$this->fadingClass = ' bfFadingClass';
				$this->fadingCall  = 'bfFade();';
				JFactory::getDocument()->addScriptDeclaration('
					function bfFade(){
						JQuery(".bfPageIntro").fadeIn(1000);
						var size = 0;
						JQuery(".bfFadingClass").each(function(i,val){
							var t = this;
							setTimeout(function(){JQuery(t).fadeIn(1000)}, (i*100));
							size = i;
						});
						setTimeout(\'JQuery(".bfSubmitButton").fadeIn(1000)\', size * 100);
						setTimeout(\'JQuery(".bfPrevButton").fadeIn(1000)\', size * 100);
						setTimeout(\'JQuery(".bfNextButton").fadeIn(1000)\', size * 100);
						setTimeout(\'JQuery(".bfCancelButton").fadeIn(1000)\', size * 100);
					}
				');
			}
                        
			if($this->rollover && trim($this->rolloverColor) != ''){
				JFactory::getDocument()->addScriptDeclaration('
					var bfElemWrapBg = "";
					function bfSetElemWrapBg(){
						bfElemWrapBg = JQuery(".bfElemWrap").css("background-color");
					}
					function bfRollover() {
						JQuery(".ff_elem").focus(
							function(){
								var parent = JQuery(this).parent();
								if(parent && parent.attr("class").substr(0, 10) == "bfElemWrap"){
									parent.css("background","'.$this->rolloverColor.'");
								} else {
									parent = JQuery(this).parent().parent();
									parent.css("background","'.$this->rolloverColor.'");
								}
                                                                parent.addClass("bfRolloverBg");
							}
						).blur(
							function(){
								var parent = JQuery(this).parent();
								if(parent && parent.attr("class").substr(0, 10) == "bfElemWrap"){
									parent.css("background",bfElemWrapBg);
								} else {
									parent = JQuery(this).parent().parent();
									parent.css("background",bfElemWrapBg);
								}
                                                                parent.removeClass("bfRolloverBg");
							}
						);
					}
					function bfRollover2() {
						JQuery(".bfElemWrap").mouseover(
							function(){
								JQuery(this).css("background","'.$this->rolloverColor.'");
                                                                JQuery(this).addClass("bfRolloverBg");
							}
						);
						JQuery(".bfElemWrap").mouseout(
							function(){
								JQuery(this).css("background",bfElemWrapBg);
                                                                JQuery(this).removeClass("bfRolloverBg");
							}
						);
					}
				');
			}
		}
		JFactory::getDocument()->addScriptDeclaration('
			JQuery(document).ready(function() {
				if(typeof bfFade != "undefined")bfFade();
				if(typeof bfSetElemWrapBg != "undefined")bfSetElemWrapBg();
				if(typeof bfRollover != "undefined")bfRollover();
				if(typeof bfRollover2 != "undefined")bfRollover2();
				if(typeof bfRegisterToggleFields != "undefined")bfRegisterToggleFields();
				if(typeof bfDeactivateSectionFields != "undefined")bfDeactivateSectionFields();
                                if(JQuery.validationEngine)
                                {
                                    JQuery.validationEngineLanguage.newLang();
                                    JQuery(".ff_elem").change(
                                        function(){
                                            JQuery.validationEngine.closePrompt(this);
                                        }
                                    );
                                }
				JQuery(".hasTip").css("color","inherit"); // fixing label text color issue
				JQuery(".bfTooltip").css("color","inherit"); // fixing label text color issue
			});
		');
		// loading system css
		JFactory::getDocument()->addStyleSheet( JURI::root(true) . '/components/com_breezingforms/themes/quickmode/system.css' );
		
		$stylelink = '<!--[if IE 7]>' ."\n";
		$stylelink .= '<link rel="stylesheet" href="'.JURI::root(true) . '/components/com_breezingforms/themes/quickmode/system.ie7.css" />' ."\n";
		$stylelink .= '<![endif]-->' ."\n";
		JFactory::getDocument()->addCustomTag($stylelink);
		
		$stylelink = '<!--[if IE 6]>' ."\n";
		$stylelink .= '<link rel="stylesheet" href="'.JURI::root(true) . '/components/com_breezingforms/themes/quickmode/system.ie6.css" />' ."\n";
		$stylelink .= '<![endif]-->' ."\n";
		JFactory::getDocument()->addCustomTag($stylelink);
		
		$stylelink = '<!--[if IE]>' ."\n";
		$stylelink .= '<link rel="stylesheet" href="'.JURI::root(true) . '/components/com_breezingforms/themes/quickmode/system.ie.css" />' ."\n";
		$stylelink .= '<![endif]-->' ."\n";
		JFactory::getDocument()->addCustomTag($stylelink);
		
		// loading theme
		$this->cancelImagePath = JURI::root(true) . '/components/com_breezingforms/themes/quickmode/cancel.png';
		$this->uploadImagePath = JURI::root(true) . '/components/com_breezingforms/themes/quickmode/upload.png';
		if($this->rootMdata['theme'] != 'none' && @file_exists(JPATH_SITE .'/components/com_breezingforms/themes/quickmode/'. $this->rootMdata['theme'].'/theme.css')){
			JFactory::getDocument()->addStyleSheet( JURI::root(true) . '/components/com_breezingforms/themes/quickmode/'. $this->rootMdata['theme'].'/theme.css' );
			if(@file_exists(JPATH_SITE .'/components/com_breezingforms/themes/quickmode/'. $this->rootMdata['theme'].'/img/cancel.png')){
				$this->cancelImagePath = JURI::root(true) . '/components/com_breezingforms/themes/quickmode/'. $this->rootMdata['theme'].'/img/cancel.png';
			}
			if(@file_exists(JPATH_SITE .'/components/com_breezingforms/themes/quickmode/'. $this->rootMdata['theme'].'/img/upload.png')){
				$this->uploadImagePath = JURI::root(true) . '/components/com_breezingforms/themes/quickmode/'. $this->rootMdata['theme'].'/img/upload.png';
			}
		}
		
	}
	
	public function process(&$dataObject, $parent = null, $parentPage = null, $index = 0, $childrenLength = 0){
		if(isset($dataObject['attributes']) && isset($dataObject['properties']) ){
			
			$options = array('type' => 'normal', 'displayType' => 'breaks');
			if($parent != null && $parent['type'] == 'section'){
				$options['type'] = $parent['bfType'];
				$options['displayType'] = $parent['displayType'];
			}
			
			$class = ' class="bfBlock'.$this->fadingClass.'"';
			$wrapper = 'bfWrapperBlock';
			if($options['displayType'] == 'inline'){
				$class = ' class="bfInline'.$this->fadingClass.'"';
				$wrapper = 'bfWrapperInline';
			}
			
			$mdata = $dataObject['properties'];
			
			if($mdata['type'] == 'page'){
				
				$parentPage = $mdata;
				if($parentPage['pageNumber'] > 1){
					echo '</div><!-- bfPage end -->'."\n"; // closing previous pages
				}
				
				echo '<div id="bfPage'.$parentPage['pageNumber'].'" style="display:none">'."\n"; // opening current page
				
				if(trim($mdata['pageIntro'])!=''){
					echo '<p class="bfPageIntro'.$this->fadingClass.'">'."\n";
					echo $mdata['pageIntro']."\n";
					echo '</p>'."\n";
				}
				
				if(!$this->useErrorAlerts){
					echo '<span class="bfErrorMessage" style="display:none"></span>'."\n";
				}
				
			} else if($mdata['type'] == 'section'){

				if(isset($dataObject['properties']['name']) && isset($mdata['off']) && $mdata['off']){
					echo '<script type="text/javascript">bfDeactivateSection.push("'.$dataObject['properties']['name'].'");</script>'."\n";
				}
				
				if($mdata['bfType'] == 'section'){
					echo '<div class="bfFieldset-wrapper '.$wrapper.' bfClearfix"><div class="bfFieldset-tl"><div class="bfFieldset-tr"><div class="bfFieldset-t"></div></div></div><div class="bfFieldset-l"><div class="bfFieldset-r"><div class="bfFieldset-m bfClearfix"><fieldset'.(isset($mdata['off']) && $mdata['off'] ? ' style="display:none" ' : '').''.(isset($mdata['off']) && $mdata['off'] ? '' : $class).''.(isset($dataObject['properties']['name']) && $dataObject['properties']['name'] != "" ? ' id="'.$dataObject['properties']['name'].'"' : '').'>'."\n";
					if(trim($mdata['title']) != ''){
						echo '<legend><span class="bfLegend-l"><span class="bfLegend-r"><span class="bfLegend-m">'.htmlentities(trim($mdata['title']), ENT_QUOTES, 'UTF-8').'</span></span></span></legend>'."\n";
					}
				} 
				else if( $mdata['bfType'] == 'normal' ) {
					if($dataObject['properties']['name'] != ''){
						echo '<div '.(isset($mdata['off']) && $mdata['off'] ? 'style="display:none" ' : '').'class="bfNoSection"'.(isset($dataObject['properties']['name']) && $dataObject['properties']['name'] != "" ? ' id="'.$dataObject['properties']['name'].'"' : '').'>'."\n";
					}
				}
				
				if(trim($mdata['description'])!=''){
					echo '<p class="bfSectionDescription">'."\n";
					echo $mdata['description']."\n";
					echo '</p>'."\n";
				}
				
			} else if($mdata['type'] == 'element'){
				
				$onclick = '';
				if($mdata['actionClick'] == 1){
					$onclick = 'onclick="'.$mdata['actionFunctionName'] . '(this,\'click\');" ';	
				}
				
				$onblur = '';
				if($mdata['actionBlur'] == 1){
					$onblur = 'onblur="'.$mdata['actionFunctionName'] . '(this,\'blur\');" ';	
				}
				
				$onchange = '';
				if($mdata['actionChange'] == 1){
					$onchange = 'onchange="'.$mdata['actionFunctionName'] . '(this,\'change\');" ';	
				}
				
				$onfocus = '';
				if($mdata['actionFocus'] == 1){
					$onfocus = 'onfocus="'.$mdata['actionFunctionName'] . '(this,\'focus\');" ';	
				}
				
				$onselect = '';
				if($mdata['actionSelect'] == 1){
					$onselect = 'onselect="'.$mdata['actionFunctionName'] . '(this,\'select\');" ';	
				}
				
				if($mdata['bfType'] != 'bfHidden'){
					
					$labelPosition = '';
					switch($mdata['labelPosition']){
						case 'top':
							$labelPosition = ' bfLabelTop';
							break;
						case 'right':
							$labelPosition = ' bfLabelRight';
							break;
						case 'bottom':
							$labelPosition = ' bfLabelBottom';
							break;
						default:
							$labelPosition = ' bfLabelLeft';
					}
					
					if($options['displayType'] == 'breaks'){
						echo '<p '.(isset($mdata['off']) && $mdata['off'] ? 'style="display:none" ' : '').'class="bfElemWrap'.$labelPosition.(isset($mdata['off']) && $mdata['off'] ? '' : $this->fadingClass).'" id="bfElemWrap'.$mdata['dbId'].'">'."\n";
					} else {
						echo '<span '.(isset($mdata['off']) && $mdata['off'] ? 'style="display:none" ' : '').'class="bfElemWrap'.$labelPosition.(isset($mdata['off']) && $mdata['off'] ? '' : $this->fadingClass).'" id="bfElemWrap'.$mdata['dbId'].'">'."\n";
					}
				}
				
				if(!$mdata['hideLabel']){
					
					$maxlengthCounter = '';
					if($mdata['bfType'] == 'bfTextarea' && isset($mdata['maxlength']) && $mdata['maxlength'] > 0 && isset($mdata['showMaxlengthCounter']) && $mdata['showMaxlengthCounter']){
						$maxlengthCounter = ' <span class=***bfMaxLengthCounter*** id=***bfMaxLengthCounter'.$mdata['dbId'].'***>('.$mdata['maxlength'].' '.BFText::_('COM_BREEZINGFORMS_CHARS_LEFT').')</span>';
					}
					
					$tipScript = '';
					$tipOpen  = '';
					$tipClose = '';
					$labelText = htmlentities(trim($mdata['label']), ENT_QUOTES, 'UTF-8') . str_replace("***","\"",$maxlengthCounter);
					if(trim($mdata['hint']) != ''){
						$tipOpen   = '<span id="bfTooltip'.$mdata['dbId'].'" class="bfTooltip">';
						$tipClose  = '</span>';
                                                $style = ',style: {tip: !JQuery.browser.ie, background: "#ffc", color: "#000000", border : { color: "#C0C0C0", width: 1 }, name: "cream" }';
                                                $content = trim($mdata['hint']);
                                                $explodeHint = explode('<<<style',trim($mdata['hint']));
                                                if(count($explodeHint) > 1 && trim($explodeHint[0]) != ''){
                                                    $style = ',style: {tip: !JQuery.browser.ie,' . trim($explodeHint[0]) . '}'; // assuming style entry
                                                    $content = trim($explodeHint[1]);
                                                }
						$tipScript   = '<script type="text/javascript"><!--'."\n".'JQuery("#bfLabel'.$mdata['dbId'].'").qtip({ position: { adjust: { screen: true } }, content: "<b>'.addslashes(trim($mdata['label'])).'</b><br/>'.str_replace( array("\n","\r"), array("\\n",""), addslashes($content) ).'"'.$style.' });'."\n".'//--></script>';
					}

                                        $for = 'for="ff_elem'.$mdata['dbId'].'"';
                                        if($mdata['bfType'] == 'bfCaptcha'){
                                            $for = 'for="bfCaptchaEntry"';
                                        }
                                        else if($mdata['bfType'] == 'bfReCaptcha'){
                                            $for = 'for="recaptcha_response_field"';
                                        }

					echo '<label id="bfLabel'.$mdata['dbId'].'" '.$for.'>'.$tipOpen.str_replace("***","\"",$labelText).$tipClose.'</label>'.$tipScript."\n";
				}
				
				$readonly = '';
				if($mdata['readonly']){
					$readonly = 'readonly="readonly" ';
				}
				
				$tabIndex = '';
				if($mdata['tabIndex'] != -1 && is_numeric($mdata['tabIndex'])){
					$tabIndex = 'tabindex="'.intval($mdata['tabIndex']).'" ';
				}
			
				for($i = 0; $i < $this->p->rowcount; $i++) {
					$row = $this->p->rows[$i];
					if($mdata['bfName'] == $row->name){
						if( ( isset($mdata['value']) || isset($mdata['list']) || isset($mdata['group']))
							&& 
							( 
								$mdata['bfType'] == 'bfTextfield' ||
								$mdata['bfType'] == 'bfTextarea' ||
								$mdata['bfType'] == 'bfCheckbox' ||
								$mdata['bfType'] == 'bfCheckboxGroup' ||
								$mdata['bfType'] == 'bfSubmitButton' ||
								$mdata['bfType'] == 'bfHidden' ||
								$mdata['bfType'] == 'bfCalendar' ||
								$mdata['bfType'] == 'bfSelect' ||
								$mdata['bfType'] == 'bfRadioGroup'
							)
						){
							if($mdata['bfType'] == 'bfSelect')
							{
								$mdata['list'] = $this->p->replaceCode($row->data2, "data2 of " . $mdata['bfName'], 'e', $mdata['dbId'], 0);
							} 
							else if($mdata['bfType'] == 'bfCheckboxGroup' || $mdata['bfType'] == 'bfRadioGroup')
							{
								$mdata['group'] = $this->p->replaceCode($row->data2, "data2 of " . $mdata['bfName'], 'e', $mdata['dbId'], 0);
							} 
							else
							{
								$mdata['value'] = $this->p->replaceCode($row->data1, "data1 of " . $mdata['bfName'], 'e', $mdata['dbId'], 0);	
							}
						}
						if(isset($mdata['checked']) && $mdata['bfType'] == 'bfCheckbox'){
							$mdata['checked'] = $row->flag1 == 1 ? true : false;
						}
						break;
					}
				}

				$flashUploader = '';
				
				switch($mdata['bfType']){
					
					case 'bfTextfield':
						$type = 'text';
						
						if($mdata['password']){
							$type = 'password';
						}
						$maxlength = '';
						if(is_numeric($mdata['maxLength'])){
							$maxlength = 'maxlength="'.intval($mdata['maxLength']).'" ';
						}
						$size = '';
						if($mdata['size']!=''){
							$size = 'style="width:'.htmlentities(strip_tags($mdata['size'])).'" ';
						}
						
						echo '<input class="ff_elem" '.$size.$tabIndex.$maxlength.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="'.$type.'" name="ff_nm_'.$mdata['bfName'].'[]" value="'.htmlentities(trim($mdata['value']), ENT_QUOTES, 'UTF-8').'" id="ff_elem'.$mdata['dbId'].'"/>'."\n";
						if($mdata['mailbackAsSender']){
							echo '<input type="hidden" name="mailbackSender['.$mdata['bfName'].']" value="true"/>'."\n";
						}
						
						break;
						
					case 'bfTextarea':
						
						$width = '';
						if($mdata['width']!=''){
							$width = 'width:'.htmlentities(strip_tags($mdata['width'])).';';
						}
						$height = '';
						if($mdata['height']!=''){
							$height = 'height:'.htmlentities(strip_tags($mdata['height'])).';';
						}
						$size = '';
						if($height != '' || $width != ''){
							$size = 'style="'.$width.$height.'" ';
						}
						$onkeyup = '';
						if(isset($mdata['maxlength']) && $mdata['maxlength'] > 0){
							$onkeyup = 'onkeyup="bfCheckMaxlength('.intval($mdata['dbId']).', '.intval($mdata['maxlength']).', '.(isset($mdata['showMaxlengthCounter']) && $mdata['showMaxlengthCounter'] ? 'true' : 'false').')" ';	
						}
						echo '<textarea cols="20" rows="5" class="ff_elem" '.$onkeyup.$size.$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'name="ff_nm_'.$mdata['bfName'].'[]" id="ff_elem'.$mdata['dbId'].'">'.htmlentities(trim($mdata['value']), ENT_QUOTES, 'UTF-8').'</textarea>'."\n";
						
						break;
						
					case 'bfRadioGroup':
						
						if($mdata['group'] != ''){
							$wrapOpen = '';
							$wrapClose = '';
							if(!$mdata['wrap']){
								 $wrapOpen = '<span class="bfElementGroupNoWrap" id="bfElementGroupNoWrap'.$mdata['dbId'].'">'."\n";
								 $wrapClose = '</span>'."\n";
							} else {
								$wrapOpen = '<span class="bfElementGroup" id="bfElementGroup'.$mdata['dbId'].'">'."\n";
								$wrapClose = '</span>'."\n";
							}
							$mdata['group'] = str_replace("\r", '', $mdata['group']);
							$gEx = explode("\n", $mdata['group']);
							$lines = count($gEx);
							echo $wrapOpen;
							for($i = 0; $i < $lines; $i++){
								$idExt = $i != 0 ? '_'.$i : '';
								$iEx = explode(";", $gEx[$i]);
								$iCnt = count($iEx);
								if($iCnt == 3){
									$lblRight = '<label for="ff_elem'.$mdata['dbId'].$idExt.'">'.htmlentities(trim($iEx[1]), ENT_QUOTES, 'UTF-8').'</label>';
									$lblLeft = ''; 
									if($mdata['labelPosition'] == 'right'){
										$lblLeft = $lblRight;	
										$lblRight = '';
									}
									echo $lblLeft . '<input '.($iEx[0] == 1 ? 'checked="checked" ' : '').' class="ff_elem" '.$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="radio" name="ff_nm_'.$mdata['bfName'].'[]" value="'.htmlentities(trim($iEx[2]), ENT_QUOTES, 'UTF-8').'" id="ff_elem'.$mdata['dbId'].$idExt.'"/>'.$lblRight."\n";
									if($mdata['wrap']){
										echo '<br/>'."\n";
									}
								}
							}
							echo $wrapClose;
						}
						
						break;
						
					case 'bfCheckboxGroup':
						
						if($mdata['group'] != ''){
							$wrapOpen = '';
							$wrapClose = '';
							if(!$mdata['wrap']){
								 $wrapOpen = '<span class="bfElementGroupNoWrap" id="bfElementGroupNoWrap'.$mdata['dbId'].'">'."\n";
								 $wrapClose = '</span>'."\n";
							} else {
								$wrapOpen = '<span class="bfElementGroup" id="bfElementGroup'.$mdata['dbId'].'">'."\n";
								$wrapClose = '</span>'."\n";
							}
							$mdata['group'] = str_replace("\r", '', $mdata['group']);
							$gEx = explode("\n", $mdata['group']);
							$lines = count($gEx);
							echo $wrapOpen;
							for($i = 0; $i < $lines; $i++){
								$idExt = $i != 0 ? '_'.$i : '';
								$iEx = explode(";", $gEx[$i]);
								$iCnt = count($iEx);
								if($iCnt == 3){
									$lblRight = '<label for="ff_elem'.$mdata['dbId'].$idExt.'">'.htmlentities(trim($iEx[1]), ENT_QUOTES, 'UTF-8').'</label>';
									$lblLeft = ''; 
									if($mdata['labelPosition'] == 'right'){
										$lblLeft = $lblRight;	
										$lblRight = '';
									}
									echo $lblLeft . '<input '.($iEx[0] == 1 ? 'checked="checked" ' : '').' class="ff_elem" '.$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="checkbox" name="ff_nm_'.$mdata['bfName'].'[]" value="'.htmlentities(trim($iEx[2]), ENT_QUOTES, 'UTF-8').'" id="ff_elem'.$mdata['dbId'].$idExt.'"/>'.$lblRight."\n";
									if($mdata['wrap']){
										echo '<br/>'."\n";
									}
								}
							}
							echo $wrapClose;
						}
						
						break;
					
					case 'bfCheckbox':
						
						echo '<input class="ff_elem" '.($mdata['checked'] ? 'checked="checked" ' : '').$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="checkbox" name="ff_nm_'.$mdata['bfName'].'[]" value="'.htmlentities(trim($mdata['value']), ENT_QUOTES, 'UTF-8').'" id="ff_elem'.$mdata['dbId'].'"/>'."\n";
						if($mdata['mailbackAccept']){
							echo '<input type="hidden" class="ff_elem" name="mailbackConnectWith['.$mdata['mailbackConnectWith'].']" value="true_'.$mdata['bfName'].'"/>'."\n";
						}
						
						break;
						
					case 'bfSelect':
						
						if($mdata['list'] != ''){
							
							$width = '';
							if(isset($mdata['width']) && $mdata['width']!=''){
								$width = 'width:'.htmlentities(strip_tags($mdata['width'])).';';
							}
							$height = '';
							if(isset($mdata['height']) && $mdata['height']!=''){
								$height = 'height:'.htmlentities(strip_tags($mdata['height'])).';';
							}
							$size = '';
							if($height != '' || $width != ''){
								$size = 'style="'.$width.$height.'" ';
							}
							
							$mdata['list'] = str_replace("\r", '', $mdata['list']);
							$gEx = explode("\n", $mdata['list']);
							$lines = count($gEx);
							echo '<select class="ff_elem" '.$size.($mdata['multiple'] ? 'multiple="multiple" ' : '').$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'name="ff_nm_'.$mdata['bfName'].'[]" id="ff_elem'.$mdata['dbId'].'">'."\n";
							for($i = 0; $i < $lines; $i++){
								$iEx = explode(";", $gEx[$i]);
								$iCnt = count($iEx);
								if($iCnt == 3){
									echo '<option '.($iEx[0] == 1 ? 'selected="selected" ' : '').'value="'.htmlentities(trim($iEx[2]), ENT_QUOTES, 'UTF-8').'">'.htmlentities(trim($iEx[1]), ENT_QUOTES, 'UTF-8').'</option>'."\n";
								}
							}
							echo '</select>'."\n";
						}
						
						break;
						
					case 'bfFile':
						
						echo '<input class="ff_elem" '.$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="file" name="ff_nm_'.$mdata['bfName'].'[]" id="ff_elem'.$mdata['dbId'].'"/>'."\n";
						if(isset( $mdata['flashUploader'] ) && $mdata['flashUploader']){
                                                        echo '<input type="hidden" id="flashUpload'.$mdata['bfName'].'" name="flashUpload'.$mdata['bfName'].'" value="bfFlashFileQueue'.$mdata['dbId'].'"/>'."\n";
							$this->hasFlashUpload = true;
							//allowedFileExtensions
							$allowedExts = explode(',',$mdata['allowedFileExtensions']);
							$allowedExtsCnt = count($allowedExts);
							for($i = 0; $i < $allowedExtsCnt;$i++){
								$allowedExts[$i] = '*.'.$allowedExts[$i];
							}
							$exts = '';
							if($allowedExtsCnt != 0){
								$exts = ',fileExt: "'.implode(';',$allowedExts).'", fileDesc: "'.addslashes(str_replace(array("'",'"',"\n","\r"),'',BFText::_('COM_BREEZINGFORMS_CHOOSE_FILE'))).'"';
							}
							$flashUploader = "
							<span class=\"bfFlashFileQueueClass\" id=\"bfFlashFileQueue".$mdata['dbId']."\"></span>
							<script type=\"text/javascript\">
                                                        <!--
							bfFlashUploaders.push('ff_elem".$mdata['dbId']."');
							var bfFlashFileQueue".$mdata['dbId']." = {};
							
                                                        var bfUploadified_ff_elem".$mdata['dbId']." = {
									'scriptAccess'   : 'always',
									'buttonImg'      : '".$this->uploadImagePath."',
									'width'          : ".(isset($mdata['flashUploaderWidth']) && is_numeric($mdata['flashUploaderWidth']) && $mdata['flashUploaderWidth'] > 0 ? intval($mdata['flashUploaderWidth']) : '64').",
									'height'         : ".(isset($mdata['flashUploaderHeight']) && is_numeric($mdata['flashUploaderHeight']) && $mdata['flashUploaderHeight'] > 0 ? intval($mdata['flashUploaderHeight']) : '64').",
									". ( isset($mdata['flashUploaderTransparent']) && $mdata['flashUploaderTransparent'] ? "'wmode'          : 'transparent'," : '' ) ."
									'uploader'       : '".JURI::root(true)."/components/com_breezingforms/libraries/jquery/uploadify.swf',
									'script'         : '".JURI::root(true)."/index.php',
									'cancelImg'      : '".$this->cancelImagePath."',
									'queueID'        : 'bfFileQueue',
									'auto'           : false,
									'multi'          : ".( isset($mdata['flashUploaderMulti']) && $mdata['flashUploaderMulti'] ? 'true' : 'false' ).",
									'buttonText'     : ' ',
									'onSelect'       : function(event, queueID, fileObj){
															var tooLarge = '';
															var thebytes = ".(isset($mdata['flashUploaderBytes']) && is_numeric($mdata['flashUploaderBytes']) && $mdata['flashUploaderBytes'] > 0 ? intval($mdata['flashUploaderBytes']) : '0').";
															if(thebytes > 0 && fileObj.size > thebytes){
																bfFlashUploadTooLarge[queueID] = '#ff_elem".$mdata['dbId']."';
																tooLarge = ' (".addslashes(BFText::_('COM_BREEZINGFORMS_FLASH_UPLOADER_TOO_LARGE')).")';
															}
															bfFlashUploadAll[queueID] = '#ff_elem".$mdata['dbId']."';
															bfFlashFileQueue".$mdata['dbId']."[queueID] = {fname: fileObj.name, tooLarge: tooLarge};
															JQuery('#bfFlashFileQueue".$mdata['dbId']."').html( JQuery('#bfFlashFileQueue".$mdata['dbId']."').html() + '<br/>' + '<a href=\"javascript:JQuery(\'#ff_elem".$mdata['dbId']."\').uploadifyCancel(\''+queueID+'\')\"><img src=\"".$this->cancelImagePath."\" border=\"0\"/></a>&nbsp;' + fileObj.name + tooLarge);
														},
									'onCancel'       : function(event, queueID, fileObj){
															delete bfFlashFileQueue".$mdata['dbId']."[queueID];
															delete bfFlashUploadTooLarge[queueID];
															delete bfFlashUploadAll[queueID];
															JQuery('#bfFlashFileQueue".$mdata['dbId']."').html('');
															for(qID in bfFlashFileQueue".$mdata['dbId']."){
																JQuery('#bfFlashFileQueue".$mdata['dbId']."').html( JQuery('#bfFlashFileQueue".$mdata['dbId']."').html() + '<br/>' + '<a href=\"javascript:JQuery(\'#ff_elem".$mdata['dbId']."\').uploadifyCancel(\''+qID+'\')\"><img src=\"".$this->cancelImagePath."\" border=\"0\"/></a>&nbsp;' + bfFlashFileQueue".$mdata['dbId']."[qID].fname + bfFlashFileQueue".$mdata['dbId']."[qID].tooLarge );
															}
														},
									'scriptData'     : { form: ".$this->p->form.", itemName : '".$mdata['bfName']."', bfFlashUploadTicket: '".$this->flashUploadTicket."', option: 'com_breezingforms', format: 'raw', flashUpload: 'true', Itemid: '".JRequest::getInt('Itemid',0)."' },
									//'onError'        : function(e,q,f,err) {alert(err.info)},
									'onComplete'     : function(event, queueID, fileObj, response, data){ if(response!='1')alert( response ) }
									".$exts."
								};
                                                             JQuery('#ff_elem".$mdata['dbId']."').uploadify(bfUploadified_ff_elem".$mdata['dbId'].");
							//-->
                                                        </script>
							";
						}
						if($mdata['attachToAdminMail']){
							echo '<input type="hidden" name="attachToAdminMail['.$mdata['bfName'].']" value="true"/>'."\n";
						}
						if($mdata['attachToUserMail']){
							echo '<input type="hidden" name="attachToUserMail['.$mdata['bfName'].']" value="true"/>'."\n";
						}
						break;
						
					case 'bfSubmitButton':
						
						$value = '';
						$type = 'submit';
						$src = '';
                                                
						if($mdata['src'] != ''){
							$type = 'image';
							$src = 'src="'.$mdata['src'].'" ';
						}
						if($mdata['value'] != ''){
							$value = 'value="'.htmlentities(trim($mdata['value']), ENT_QUOTES, 'UTF-8').'" ';
						}
						if($mdata['actionClick'] == 1){
							$onclick = 'onclick="populateSummarizers();if(document.getElementById(\'bfPaymentMethod\')){document.getElementById(\'bfPaymentMethod\').value=\'\';};'.$mdata['actionFunctionName'] . '(this,\'click\');return false;" ';
						} else {
							$onclick = 'onclick="populateSummarizers();if(document.getElementById(\'bfPaymentMethod\')){document.getElementById(\'bfPaymentMethod\').value=\'\';};return false;" ';
						}
                                                if($src == ''){
                                                    echo '<button class="ff_elem" '.$value.$src.$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="'.$type.'" name="ff_nm_'.$mdata['bfName'].'[]" id="ff_elem'.$mdata['dbId'].'"><span>'.$mdata['value'].'</span></button>'."\n";
                                                }else{
                                                    echo '<input class="ff_elem" '.$value.$src.$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="'.$type.'" name="ff_nm_'.$mdata['bfName'].'[]" id="ff_elem'.$mdata['dbId'].'" value="'.$mdata['value'].'"/>'."\n";
                                                }
						break;
						
					case 'bfHidden':
						
						echo '<input class="ff_elem" type="hidden" name="ff_nm_'.$mdata['bfName'].'[]" value="'.htmlentities(trim($mdata['value']), ENT_QUOTES, 'UTF-8').'" id="ff_elem'.$mdata['dbId'].'"/>'."\n";
						break;
						
					case 'bfSummarize':
						
						echo '<span class="ff_elem bfSummarize" id="ff_elem'.$mdata['dbId'].'"></span>'."\n";
						echo '<script type="text/javascript">bfRegisterSummarize("ff_elem'.$mdata['dbId'].'", "'.$mdata['connectWith'].'", "'.$mdata['connectType'].'", "'.addslashes($mdata['emptyMessage']).'", '.($mdata['hideIfEmpty'] ? 'true' : 'false').')</script>';
						if(trim($mdata['fieldCalc']) != ''){
							echo '<script type="text/javascript">
							function bfFieldCalcff_elem'.$mdata['dbId'].'(value){
								if(!isNaN(value)){
									value = Number(value);
								}
								'.$mdata['fieldCalc'].'
								return value;
							}
							</script>';
						}
						break;

                                        case 'bfReCaptcha':

                                            if(isset($mdata['pubkey']) && $mdata['pubkey'] != ''){

                                                JFactory::getDocument()->addScript('http://api.recaptcha.net/js/recaptcha_ajax.js');
                                                JFactory::getDocument()->addScriptDeclaration(
                                                '
                                                    JQuery(document).ready(
                                                        function() {
                                                            document.getElementById("bfReCaptchaWrap").style.display = "";
                                                            Recaptcha.create("'.$mdata['pubkey'].'",
                                                                "bfReCaptchaDiv", {
                                                                theme: "'.addslashes($mdata['theme']).'"
                                                                }
                                                            );
                                                            setTimeout("document.getElementById(\"bfReCaptchaSpan\").appendChild(document.getElementById(\"bfReCaptchaWrap\"))",100);
                                                        }
                                                    );
                                                ');

                                                echo '<span id="bfReCaptchaSpan" class="bfCaptcha">'."\n";
                                                echo '</span>'."\n";
                                            }
                                            else
                                            {
                                                echo '<span class="bfCaptcha">'."\n";
                                                echo 'WARNING: No public key given for ReCaptcha element!';
                                                echo '</span>'."\n";
                                            }
                                            break;

					case 'bfCaptcha':

						echo '<span class="bfCaptcha">'."\n";
						echo '<img alt="" id="ff_capimgValue" class="ff_capimg" src="'.JURI::root(true) . '/index.php?raw=true&amp;option=com_breezingforms&amp;bfCaptcha=true&amp;Itemid='.JRequest::getInt('Itemid',0).'"/>'."\n";
						echo '<br/>';
						echo '<input class="ff_elem" type="text" name="bfCaptchaEntry" id="bfCaptchaEntry" />'."\n";
						echo '<a href="#" class="ff_elem" onclick="document.getElementById(\'bfCaptchaEntry\').value=\'\';document.getElementById(\'bfCaptchaEntry\').focus();document.getElementById(\'ff_capimgValue\').src = \''.JURI::root(true) . '/index.php?raw=true&option=com_breezingforms&bfCaptcha=true&Itemid='.JRequest::getInt('Itemid',0).'&bfMathRandom=\' + Math.random(); return false"><img alt="captcha" src="'.JURI::root(true) . '/components/com_breezingforms/images/captcha/refresh-captcha.png" border="0" /></a>'."\n";
						echo '</span>'."\n";
						
						break;
						
					case 'bfCalendar':
					
						$size = '';
						if($mdata['size']!=''){
							$size = 'style="width:'.htmlentities(strip_tags($mdata['size'])).'" ';
						}
						
						echo '<span class="bfElementGroupNoWrap" id="bfElementGroupNoWrap'.$mdata['dbId'].'">'."\n";
						echo '<input class="ff_elem" '.$size.'type="text" name="ff_nm_'.$mdata['bfName'].'[]"  id="ff_elem'.$mdata['dbId'].'" value=""/>'."\n";
						echo '<button onclick="showCalendar(\'ff_elem'.$mdata['dbId'].'\', \''.$mdata['format'].'\');" type="submit" class="bfCalendar" value="'.htmlentities(trim($mdata['value']), ENT_QUOTES, 'UTF-8').'"><span>'.htmlentities(trim($mdata['value']), ENT_QUOTES, 'UTF-8').'</span></button>'."\n";
						echo '</span>'."\n";
						
						break;	
						
					case 'bfPayPal':
						
						$value = '';
						$type = 'submit';
						$src = '';
						if($mdata['image'] != ''){
							$type = 'image';
							$src = 'src="'.$mdata['image'].'" ';
						}else{
							$value = 'value="PayPal" ';
						}
						if($mdata['actionClick'] == 1){
							$onclick = 'onclick="document.getElementById(\'bfPaymentMethod\').value=\'PayPal\';'.$mdata['actionFunctionName'] . '(this,\'click\');" ';	
						} else {
							$onclick = 'onclick="document.getElementById(\'bfPaymentMethod\').value=\'PayPal\';" ';
						}
						echo '<input class="ff_elem" '.$value.$src.$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="'.$type.'" name="ff_nm_'.$mdata['bfName'].'[]" id="ff_elem'.$mdata['dbId'].'"/>'."\n";
						break;
						
					case 'bfSofortueberweisung':
						
						$value = '';
						$type = 'submit';
						$src = '';
						if($mdata['image'] != ''){
							$type = 'image';
							$src = 'src="'.$mdata['image'].'" ';
						}else{
							$value = 'value="Sofortueberweisung" ';
						}
						if($mdata['actionClick'] == 1){
							$onclick = 'onclick="document.getElementById(\'bfPaymentMethod\').value=\'Sofortueberweisung\';'.$mdata['actionFunctionName'] . '(this,\'click\');" ';	
						} else {
							$onclick = 'onclick="document.getElementById(\'bfPaymentMethod\').value=\'Sofortueberweisung\';" ';
						}
						echo '<input class="ff_elem" '.$value.$src.$tabIndex.$onclick.$onblur.$onchange.$onfocus.$onselect.$readonly.'type="'.$type.'" name="ff_nm_'.$mdata['bfName'].'[]" id="ff_elem'.$mdata['dbId'].'"/>'."\n";
						break;
				}
				
				if(isset($mdata['bfName']) && isset($mdata['off']) && $mdata['off']){
					echo '<script type="text/javascript">bfDeactivateField["ff_nm_'.$mdata['bfName'].'[]"]=true;</script>'."\n";
				}
				
				if($mdata['required']){
					echo '<span class="bfRequired">*</span>'."\n";
				}
				
				echo $flashUploader;
				
				if($mdata['bfType'] != 'bfHidden'){
					if($options['displayType'] == 'breaks'){
						echo '</p>'."\n";
					} else {
						echo '</span>'."\n";
					}
				}
			}
		}

		/**
		 * Paging and wrapping of inline element containers
		 */
		
		if($dataObject['properties']['type'] == 'section' && $dataObject['properties']['displayType'] == 'inline'){
			echo '<div class="bfClearfix">'."\n";
		}
		
		if(isset($dataObject['children']) && count($dataObject['children']) != 0){
			$childrenAmount = count($dataObject['children']);
			for($i = 0; $i < $childrenAmount; $i++){
				$this->process( $dataObject['children'][$i], $mdata, $parentPage, $i, $childrenAmount );
			}
		}	
		
		if($dataObject['properties']['type'] == 'section' && $dataObject['properties']['displayType'] == 'inline'){
			echo '</div>'."\n";
		}
		
		if($dataObject['properties']['type'] == 'section' && $dataObject['properties']['bfType'] == 'section'){
			
			echo '</fieldset></div></div></div><div class="bfFieldset-bl"><div class="bfFieldset-br"><div class="bfFieldset-b"></div></div></div></div><!-- bfFieldset-wrapper end -->'."\n";
			
		} else if( $dataObject['properties']['type'] == 'section' && $dataObject['properties']['bfType'] == 'normal' ) {
			if($dataObject['properties']['name'] != ''){
				echo '</div>'."\n";
			}
		}
		else if($dataObject['properties']['type'] == 'page'){

			$isLastPage = false;
			if($this->rootMdata['lastPageThankYou'] && $dataObject['properties']['pageNumber'] == count($this->dataObject['children']) && count($this->dataObject['children']) > 1){
				$isLastPage = true;
			}
			
			if(!$isLastPage){
			
				$last = 0;
				if($this->rootMdata['lastPageThankYou']){
					$last = 1;
				}
				
				if($this->rootMdata['pagingInclude'] && $dataObject['properties']['pageNumber'] > 1){
					echo '<button class="bfPrevButton'.$this->fadingClass.'" type="submit" onclick="ff_validate_prevpage(this, \'click\');populateSummarizers();" value="'.htmlentities(trim($this->rootMdata['pagingPrevLabel']), ENT_QUOTES, 'UTF-8').'"><span>'.htmlentities(trim($this->rootMdata['pagingPrevLabel']), ENT_QUOTES, 'UTF-8').'</span></button>'."\n";
				}
	
				if($this->rootMdata['pagingInclude'] && $dataObject['properties']['pageNumber'] < count($this->dataObject['children']) - $last){
					echo '<button class="bfNextButton'.$this->fadingClass.'" type="submit" onclick="ff_validate_nextpage(this, \'click\');populateSummarizers();" value="'.htmlentities(trim($this->rootMdata['pagingNextLabel']), ENT_QUOTES, 'UTF-8').'"><span>'.htmlentities(trim($this->rootMdata['pagingNextLabel']), ENT_QUOTES, 'UTF-8').'</span></button>'."\n";
				}
	
				if($this->rootMdata['cancelInclude'] && $dataObject['properties']['pageNumber'] + 1 > count($this->dataObject['children']) - $last){
					echo '<button class="bfCancelButton'.$this->fadingClass.'" type="submit" onclick="ff_resetForm(this, \'click\');"  value="'.htmlentities(trim($this->rootMdata['cancelLabel']), ENT_QUOTES, 'UTF-8').'"><span>'.htmlentities(trim($this->rootMdata['cancelLabel']), ENT_QUOTES, 'UTF-8').'</span></button>'."\n";
				}
				
				$callSubmit = 'ff_validate_submit(this, \'click\')';
				if( $this->hasFlashUpload ){
					$callSubmit = 'if(typeof bfAjaxObject101 == \'undefined\' && typeof bfReCaptchaLoaded == \'undefined\'){bfDoFlashUpload()}else{ff_validate_submit(this, \'click\')}';
				}
				if($this->rootMdata['submitInclude'] && $dataObject['properties']['pageNumber'] + 1 > count($this->dataObject['children']) - $last){
					echo '<button id="bfSubmitButton" class="bfSubmitButton'.$this->fadingClass.'" type="submit" onclick="if(document.getElementById(\'bfPaymentMethod\')){document.getElementById(\'bfPaymentMethod\').value=\'\';};'.$callSubmit.';" value="'.htmlentities(trim($this->rootMdata['submitLabel']), ENT_QUOTES, 'UTF-8').'"><span>'.htmlentities(trim($this->rootMdata['submitLabel']), ENT_QUOTES, 'UTF-8').'</span></button>'."\n";
				}
			
			}
		}
	}
	
	public function render(){
		$this->process($this->dataObject);
		echo '</div>'."\n"; // closing last page
		if( $this->hasFlashUpload ){
			$tickets = JFactory::getSession()->get('bfFlashUploadTickets', array());
			$tickets[$this->flashUploadTicket] = array(); // stores file info for later processing
			JFactory::getSession()->set('bfFlashUploadTickets', $tickets);
			echo '<input type="hidden" name="bfFlashUploadTicket" value="'.$this->flashUploadTicket.'"/>'."\n";
			JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_breezingforms/libraries/jquery/swfobject.js');
			JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_breezingforms/libraries/jquery/jquery.uploadify.v2.1.0.min.js');
			JFactory::getDocument()->addScript(JURI::root(true) . '/components/com_breezingforms/libraries/jquery/center.js');
			JFactory::getDocument()->addScriptDeclaration('
			var bfFlashUploadInterval = null;
			var bfFlashUploadTooLarge = {};
			var bfFlashUploadAll = {};
			var bfFlashUploaders = new Array();
			function bfDoFlashUpload(){
                        
				JQuery("#bfSubmitMessage").css("visibility","hidden");
				JQuery(".bfErrorMessage").html("");
                                JQuery(".bfErrorMessage").css("display","none");
                                for(qID in bfFlashUploadTooLarge){
                                        try{
						JQuery(bfFlashUploadTooLarge[qID]).uploadifyCancel(qID);
					} catch(e){}
				}
				bfFlashUploadTooLarge = {};
                                if(ff_validation(0) == ""){
					try{
                                                bfFlashUploadInterval = window.setInterval( bfCheckFlashUploadProgress, 100 );
                                                JQuery("#bfFileQueue").center(true);
                                                JQuery("#bfFileQueue").css("visibility","visible");
						for(var i = 0; i < bfFlashUploaders.length;i++){
							try{
								JQuery("#"+bfFlashUploaders[i]).uploadifyUpload();
                                                               
							} catch(e){alert("FlashUpload error, try to put the upload(s) on the very last page of your form! ErrorMessage: "+e);}
						}
					} catch(e){alert(e)}
				} else {
					if(typeof bfUseErrorAlerts == "undefined"){
                                            alert(error);
                                        } else {
                                            bfShowErrors(error);
                                        }
                                        ff_validationFocus("");
				}
			}
			function bfCheckFlashUploadProgress(){
				if( JQuery("#bfFileQueue").html() == "" ){ // empty indicates that all queues are uploaded or in any way cancelled
					JQuery("#bfFileQueue").css("visibility","hidden");
					window.clearInterval( bfFlashUploadInterval );
                                        if(typeof bfAjaxObject101 != \'undefined\' || typeof bfReCaptchaLoaded != \'undefined\'){
                                            ff_submitForm2();
                                        }else{
                                            ff_validate_submit(document.getElementById("bfSubmitButton"), "click");
                                        }
					JQuery(".bfFlashFileQueueClass").html("");
					JQuery("#bfSubmitMessage").center(true);
					JQuery("#bfSubmitMessage").css("visibility","visible");
				}
			}
			');
			echo "<div style=\"visibility:hidden;\" id=\"bfFileQueue\"></div>";
			echo "<div style=\"visibility:hidden;\" id=\"bfSubmitMessage\">".BFText::_('COM_BREEZINGFORMS_SUBMIT_MESSAGE')."</div>";
		}
		echo '<script type="text/javascript">if(document.getElementById("bfPage'.$this->p->page.'"))document.getElementById("bfPage'.$this->p->page.'").style.display = "";</script>'."\n";
		echo '<noscript>Please turn on javascript to submit your data. Thank you!</noscript>'."\n";
                JFactory::getDocument()->addScriptDeclaration('//-->');
	}
	
	public function parseToggleFields( $code ){
		/*
		 	example codes:

			turn on element bla if blub is on
			turn off section bla if blub is on
			turn on section bla if blub is off
			turn off element bla if blub is off

                        if element opener is off set opener huhuu

			syntax:
			ACTION STATE TARGETCATEGORY TARGETNAME if SRCNAME is VALUE 
		 */
		
		$parsed = '';
		$code = str_replace("\r", '', $code);
		$lines = explode( "\n", $code );
		$linesCnt = count( $lines );
		
		for($i = 0; $i < $linesCnt;$i++){
			$tokens = explode( ' ', trim($lines[$i]) );
			$tokensCnt = count($tokens);
			if($tokensCnt >= 8){
				$state = '';
				// rebuilding the state as it could be a value containing blanks
				for($j = 7; $j < $tokensCnt; $j++){
					if($j+1 < $tokensCnt)
						$state .= $tokens[$j] . ' ';
					else
						$state .= $tokens[$j];
				}
				$parsed .= '{ action: "'.$tokens[0].'", state: "'.$tokens[1].'", tCat: "'.$tokens[2].'", tName: "'.$tokens[3].'", statement: "'.$tokens[4].'", sName: "'.$tokens[5].'", condition: "'.$tokens[6].'", value: "'.addslashes($state).'" },';
			}
		}
		
		return "[".rtrim($parsed, ",")."]";
	}
}