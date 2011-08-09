<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class QuickModeHtml{
	
	public static function showApplication($formId = 0, $formName, $formTitle, $formDesc, $formEmailntf, $formEmailadr, $dataObjectString, $elementScripts, $themes){
		JHTML::_('behavior.keepalive');
		JHTML::_('behavior.modal');
		$iconBase = '../administrator/components/com_breezingforms/libraries/jquery/themes/quickmode/i/';
?>
	<style>
	<!--
	#menutab { float: left; width: 500px; height: 100%; }
	-->
	</style>
	
	<link rel="stylesheet" href="<?php echo JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/themes/quickmode/quickmode.all.css' ;?>" type="text/css" media="screen" title="Flora (Default)"/>
	<link rel="stylesheet" type="text/css" href="<?php echo JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/jtree/' ;?>tree_component.css" />
	<script type="text/javascript" src="<?php echo JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/jtree/' ;?>_lib.js"></script>	
	<script type="text/javascript" src="<?php echo JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/jtree/' ;?>tree_component.js"></script>
	<script
	type="text/javascript"
	src="<?php echo JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/' ;?>jquery-ui.min.js"></script>
	<script
	type="text/javascript"
	src="<?php echo JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/plugins/' ;?>base64.js"></script>
	<script
	type="text/javascript"
	src="<?php echo JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/plugins/' ;?>json.js"></script>
	<script
	type="text/javascript"
	src="<?php echo JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/plugins/' ;?>md5.js"></script>
        <script
	type="text/javascript"
	src="<?php echo JURI::root()?>components/com_breezingforms/libraries/jquery/center.js"></script>

	<script type="text/javascript">
	var JQuery = jQuery.noConflict();
	var app = null;
	
	function BF_QuickModeApp(){
		
		var selectedTreeElement = null;
		var copyTreeElement = null;
		var appScope = this;
		this.elementScripts = <?php echo Zend_Json::encode($elementScripts)?>;	      
		this.dataObject = <?php echo str_replace("..\\/administrator\\/components\\/com_facileforms", "..\\/administrator\\/components\\/com_breezingforms",$dataObjectString) ?>;
		
		<?php require_once(JPATH_SITE . '/administrator/components/com_breezingforms/admin/quickmode-elements-js.php'); ?>
		
		/**
			Helper methods
		*/
		this.getNodeClass = function(node){
			if(JQuery(node).attr('class')){
				var splitted = JQuery(appScope.selectedTreeElement).attr('class').split(' ');
				if(splitted.length != 0){
					return splitted[0]; 
				}
			}
			return '';
		};
		
		this.setProperties = function(node, props){
			var item = this.findDataObjectItem(JQuery(node).attr('id'), appScope.dataObject);
			item.properties = props;
		};
		
		this.getProperties = function(node){
			
			var item = this.findDataObjectItem(JQuery(node).attr('id'), appScope.dataObject)
			return item.properties;
		};
		
		/**
			searches for the id in a given object item.
		*/
		this.findDataObjectItem = function(id, startObj){
			if( id && startObj && startObj.attributes && startObj.attributes.id ){
				if( startObj.attributes.id == id ){
					return startObj;
				} else { 
					if(startObj.children){
						var child = null;
						for(var i = 0; i < startObj.children.length; i++){
							child = appScope.findDataObjectItem(id, startObj.children[i]);
							if(child){
								return child;
							}
						}
					}
				}
				return null;
			}
			return null;
		};
		
		this.getItemsFlattened = function(startObj, arr){
			if( startObj && startObj.properties && startObj.properties.type == 'element' ){
				arr.push(startObj);
				
			}
			if(startObj.children){
				var child = null;
				for(var i = 0; i < startObj.children.length; i++){
					appScope.getItemsFlattened(startObj.children[i], arr);
				}
			}
		};
		
		this.replaceDataObjectItem = function(id, replacement, startObj){
			if( id && startObj && startObj.attributes && startObj.attributes.id ){
				if(startObj.children){
					var child = null;
					for(var i = 0; i < startObj.children.length; i++){
						if(startObj.children[i].attributes.id == id){
							startObj.children[i] = replacement;
							break;
						}
						appScope.replaceDataObjectItem(id, replacement, startObj.children[i]);
					}
				}
			}
		}
		
		/**
			searches for the id in a given object item and deletes it.
			returns the deleted child.
		*/
		this.deleteDataObjectItem = function(id, startObj, previous){
			if( id && startObj && startObj.attributes && startObj.attributes.id ){
				if( startObj.attributes.id == id ){
					if(previous){
						var newChildren = new Array();
						for(var j = 0; j < previous.children.length; j++){
							if(previous.children[j].attributes.id != startObj.attributes.id){
								newChildren.push(previous.children[j]);
							}
						}
						previous.children = newChildren;
					}
					return startObj;
				} else { 
					if(startObj.children){
						var child = null;
						for(var i = 0; i < startObj.children.length; i++){
							child = appScope.deleteDataObjectItem(id, startObj.children[i], startObj);
							if(child){
								return child;
							}
						}
					}
				}
				return null;
			}
			return null;
		};
		
		this.moveDataObjectItem = function( sourceId, targetId, index, obj ){
			var source = appScope.deleteDataObjectItem(sourceId, obj);
			var target = appScope.findDataObjectItem( targetId, obj );
			if(target && !target.children && ( target.attributes['class'] == 'bfQuickModePageClass' || target.attributes['class'] == 'bfQuickModeSectionClass' || target.attributes['class'] == 'bfQuickModeRootClass' )){
				target.children = new Array();
			}
			if(target && target.children){
				target.children.splice(index,0,source);
				if(target.attributes['class'] == 'bfQuickModeRootClass'){
					for(var i = 0; i < target.children.length; i++){
						var mdata = appScope.getProperties(JQuery('#'+target.children[i].attributes.id));
						if(mdata){
							if(target.children[i].attributes['class'] == 'bfQuickModePageClass'){
								target.children[i].attributes.id = 'bfQuickModePage' + (i+1);
								target.children[i].data.title = "<?php echo addslashes( BFText::_('COM_BREEZINGFORMS_PAGE') ) ?> " + (i+1);
								target.children[i].properties.pageNumber = i + 1;
							}
						}
					}
				}
				return true;
			}
			return false;
		};

		this.insertElementInto = function (source, target){
			if(target && target.children){
				if(target.attributes['class'] == 'bfQuickModeSectionClass' || target.attributes['class'] == 'bfQuickModePageClass'){
					this.recreatedIds(source);
					target.children.push(source);
				}
			}
		};

		this.recreatedIds = function(startObj){
			if( startObj && startObj.attributes && startObj.attributes.id ){
				if(startObj.attributes['class'] == 'bfQuickModeSectionClass'){
					type = 'bfQuickModeSection';
				} else {
					type = 'bfQuickMode';
				}
				var id = type + ( Math.floor(Math.random() * 100000) );
				startObj.attributes.id = id;
				if(startObj.attributes['class'] == 'bfQuickModeSectionClass'){
					startObj.properties.name = id;
				} else {
					startObj.properties.bfName = id;
					startObj.properties.dbId = 0;
				}
				startObj.properties.name = id;
				if(startObj.children){
					var child = null;
					for(var i = 0; i < startObj.children.length; i++){
						child = appScope.recreatedIds(startObj.children[i]);
						if(child){
							return child;
						}
					}
				}
				return null;
			}
			return null;
		};
		
		/**
			Element properties
		*/
		
		// TEXTFIELD
		this.saveTextProperties = function(mdata, item){
			mdata.value = JQuery('#bfElementTypeTextValue').val();
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.logging = JQuery('#bfElementAdvancedLogging').attr('checked');
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.maxLength = JQuery('#bfElementTypeTextMaxLength').val();
			mdata.hint = JQuery('#bfElementTypeTextHint').val();
			mdata.password = JQuery('#bfElementAdvancedPassword').attr('checked');
			mdata.readonly = JQuery('#bfElementAdvancedReadOnly').attr('checked');
			mdata.mailback = JQuery('#bfElementAdvancedMailback').attr('checked');
			mdata.mailbackAsSender = JQuery('#bfElementAdvancedMailbackAsSender').attr('checked');
			mdata.mailbackfile = JQuery('#bfElementAdvancedMailbackfile').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.hideLabel = JQuery('#bfElementAdvancedHideLabel').attr('checked');
			mdata.size = JQuery('#bfElementTypeTextSize').val();
			mdata.orderNumber = JQuery('#bfElementOrderNumber').val();
			mdata.required = JQuery('#bfElementValidationRequired').attr('checked');
			item.properties = mdata;
		};
		
		this.populateTextProperties = function(mdata){
			JQuery('#bfElementTypeTextValue').val(mdata.value);
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedLogging').attr('checked', mdata.logging);
			JQuery('#bfElementTypeTextMaxLength').val(mdata.maxLength);
			JQuery('#bfElementTypeTextHint').val(mdata.hint);
			JQuery('#bfElementAdvancedPassword').attr('checked', mdata.password);
			JQuery('#bfElementAdvancedReadOnly').attr('checked', mdata.readonly);
			JQuery('#bfElementAdvancedMailback').attr('checked', mdata.mailback);
			JQuery('#bfElementAdvancedMailbackAsSender').attr('checked', mdata.mailbackAsSender);
			JQuery('#bfElementAdvancedMailbackfile').val(mdata.mailbackfile);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedHideLabel').attr('checked', mdata.hideLabel);
			JQuery('#bfElementTypeTextSize').val(mdata.size);
			JQuery('#bfElementOrderNumber').val(mdata.orderNumber);
			JQuery('#bfElementValidationRequired').attr('checked', mdata.required);
		};
		
		// TEXTAREA
		this.saveTextareaProperties = function(mdata, item){
			mdata.value = JQuery('#bfElementTypeTextareaValue').val();
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.logging = JQuery('#bfElementTextareaAdvancedLogging').attr('checked');
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.hint = JQuery('#bfElementTypeTextareaHint').val();
			mdata.width = JQuery('#bfElementTypeTextareaWidth').val();
			mdata.height = JQuery('#bfElementTypeTextareaHeight').val();
			mdata.maxlength = JQuery('#bfElementTypeTextareaMaxLength').val();
			mdata.showMaxlengthCounter = JQuery('#bfElementTypeTextareaMaxLengthShow').attr('checked');
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			mdata.hideLabel = JQuery('#bfElementTextareaAdvancedHideLabel').attr('checked');
			mdata.orderNumber = JQuery('#bfElementTextareaAdvancedOrderNumber').val();
			mdata.required = JQuery('#bfElementValidationRequired').attr('checked');
			item.properties = mdata;
		};
		
		this.populateTextareaProperties = function(mdata){
			JQuery('#bfElementTypeTextareaValue').val(mdata.value);
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementTextareaAdvancedLogging').attr('checked', mdata.logging);
			JQuery('#bfElementTypeTextareaHint').val(mdata.hint);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementTextareaAdvancedHideLabel').attr('checked', mdata.hideLabel);
			JQuery('#bfElementTypeTextareaWidth').val(mdata.width);
			JQuery('#bfElementTypeTextareaHeight').val(mdata.height);
			// compat 723
			if(typeof mdata.maxlength == "undefined"){
				mdata["maxlength"] = 0;
			}
			if(typeof mdata.showMaxlengthCounter == "undefined"){
				mdata["showMaxlengthCounter"] = true;
			}
			// end compat 723
			JQuery('#bfElementTypeTextareaMaxLength').val(!isNaN(mdata.maxlength) ? mdata.maxlength : 0);
			JQuery('#bfElementTypeTextareaMaxLengthShow').attr('checked', mdata.showMaxlengthCounter);
			JQuery('#bfElementTextareaAdvancedOrderNumber').val(mdata.orderNumber);
			JQuery('#bfElementValidationRequired').attr('checked', mdata.required);
		};
		
		// RADIOS
		this.saveRadioGroupProperties = function(mdata, item){
			// dynamic properties
			mdata.group = JQuery('#bfElementTypeRadioGroupGroups').val();
			mdata.readonly = JQuery('#bfElementTypeRadioGroupReadonly').attr('checked');
			mdata.wrap = JQuery('#bfElementTypeRadioGroupWrap').attr('checked');
			mdata.hint = JQuery('#bfElementTypeRadioGroupHint').val();
			mdata.hideLabel = JQuery('#bfElementRadioGroupAdvancedHideLabel').attr('checked');
			mdata.logging = JQuery('#bfElementRadioGroupAdvancedLogging').attr('checked');
			mdata.orderNumber = JQuery('#bfElementRadioGroupAdvancedOrderNumber').val();
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			mdata.required = JQuery('#bfElementValidationRequired').attr('checked');
			
			item.properties = mdata;
		};
		
		this.populateRadioGroupProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementTypeRadioGroupGroups').val(mdata.group);
			JQuery('#bfElementTypeRadioGroupReadonly').attr('checked', mdata.readonly);
			JQuery('#bfElementTypeRadioGroupWrap').attr('checked', mdata.wrap);
			JQuery('#bfElementTypeRadioGroupHint').val(mdata.hint);
			JQuery('#bfElementRadioGroupAdvancedHideLabel').attr('checked', mdata.hideLabel);
			JQuery('#bfElementRadioGroupAdvancedLogging').attr('checked', mdata.logging);
			JQuery('#bfElementRadioGroupAdvancedOrderNumber').val(mdata.orderNumber);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
			JQuery('#bfElementValidationRequired').attr('checked', mdata.required);
		};
		
		// Checkboxgroup
		this.saveCheckboxGroupProperties = function(mdata, item){
			// dynamic properties
			mdata.group = JQuery('#bfElementTypeCheckboxGroupGroups').val();
			mdata.readonly = JQuery('#bfElementTypeCheckboxGroupReadonly').attr('checked');
			mdata.wrap = JQuery('#bfElementTypeCheckboxGroupWrap').attr('checked');
			mdata.hint = JQuery('#bfElementTypeCheckboxGroupHint').val();
			mdata.hideLabel = JQuery('#bfElementCheckboxGroupAdvancedHideLabel').attr('checked');
			mdata.logging = JQuery('#bfElementCheckboxGroupAdvancedLogging').attr('checked');
			mdata.orderNumber = JQuery('#bfElementCheckboxGroupAdvancedOrderNumber').val();
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			mdata.required = JQuery('#bfElementValidationRequired').attr('checked');
			
			item.properties = mdata;
		};
		
		this.populateCheckboxGroupProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementTypeCheckboxGroupGroups').val(mdata.group);
			JQuery('#bfElementTypeCheckboxGroupReadonly').attr('checked', mdata.readonly);
			JQuery('#bfElementTypeCheckboxGroupWrap').attr('checked', mdata.wrap);
			JQuery('#bfElementTypeCheckboxGroupHint').val(mdata.hint);
			JQuery('#bfElementCheckboxGroupAdvancedHideLabel').attr('checked', mdata.hideLabel);
			JQuery('#bfElementCheckboxGroupAdvancedLogging').attr('checked', mdata.logging);
			JQuery('#bfElementCheckboxGroupAdvancedOrderNumber').val(mdata.orderNumber);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
			JQuery('#bfElementValidationRequired').attr('checked', mdata.required);
		};
		
		// Checkbox
		this.saveCheckboxProperties = function(mdata, item){
			// dynamic properties
			mdata.value = JQuery('#bfElementTypeCheckboxValue').val() == '' ? 'checked' : JQuery('#bfElementTypeCheckboxValue').val();
			mdata.checked = JQuery('#bfElementTypeCheckboxChecked').attr('checked');
			mdata.readonly = JQuery('#bfElementTypeCheckboxReadonly').attr('checked');
			mdata.mailbackAccept = JQuery('#bfElementCheckboxAdvancedMailbackAccept').attr('checked');
			mdata.mailbackConnectWith = JQuery('#bfElementCheckboxAdvancedMailbackConnectWith').val();
			mdata.hint = JQuery('#bfElementTypeCheckboxHint').val();
			mdata.hideLabel = JQuery('#bfElementCheckboxAdvancedHideLabel').attr('checked');
			mdata.logging = JQuery('#bfElementCheckboxAdvancedLogging').attr('checked');
			mdata.orderNumber = JQuery('#bfElementCheckboxAdvancedOrderNumber').val();
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			mdata.required = JQuery('#bfElementValidationRequired').attr('checked');
			
			item.properties = mdata;
		};
		
		this.populateCheckboxProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementTypeCheckboxValue').val(mdata.value);
			JQuery('#bfElementTypeCheckboxChecked').attr('checked', mdata.checked);
			JQuery('#bfElementCheckboxAdvancedMailbackAccept').attr('checked', mdata.mailbackAccept);
			JQuery('#bfElementCheckboxAdvancedMailbackConnectWith').val(mdata.mailbackConnectWith);
			JQuery('#bfElementTypeCheckboxReadonly').attr('checked', mdata.readonly);
			JQuery('#bfElementTypeCheckboxHint').val(mdata.hint);
			JQuery('#bfElementCheckboxAdvancedHideLabel').attr('checked', mdata.hideLabel);
			JQuery('#bfElementCheckboxAdvancedLogging').attr('checked', mdata.logging);
			JQuery('#bfElementCheckboxAdvancedOrderNumber').val(mdata.orderNumber);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
			JQuery('#bfElementValidationRequired').attr('checked', mdata.required);
		};
		
		// Select
		this.saveSelectProperties = function(mdata, item){
			// dynamic properties
			mdata.list = JQuery('#bfElementTypeSelectList').val();
			mdata.width = JQuery('#bfElementTypeSelectListWidth').val();
			mdata.height = JQuery('#bfElementTypeSelectListHeight').val();
			mdata.readonly = JQuery('#bfElementTypeSelectReadonly').attr('checked');
			mdata.multiple = JQuery('#bfElementTypeSelectMultiple').attr('checked');
			mdata.mailback = JQuery('#bfElementSelectAdvancedMailback').attr('checked');
			mdata.hint = JQuery('#bfElementTypeSelectHint').val();
			mdata.hideLabel = JQuery('#bfElementSelectAdvancedHideLabel').attr('checked');
			mdata.logging = JQuery('#bfElementSelectAdvancedLogging').attr('checked');
			mdata.orderNumber = JQuery('#bfElementSelectAdvancedOrderNumber').val();
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			mdata.required = JQuery('#bfElementValidationRequired').attr('checked');
			
			item.properties = mdata;
		};
		
		this.populateSelectProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementTypeSelectList').val(mdata.list);
			// compat 723
			if(typeof mdata.width == "undefined"){
				mdata['width'] = '';
			}
			if(typeof mdata.height == "undefined"){
				mdata['height'] = '';
			}
			// compat 723 end
			JQuery('#bfElementTypeSelectListWidth').val(mdata.width);
			JQuery('#bfElementTypeSelectListHeight').val(mdata.height);
			JQuery('#bfElementTypeSelectReadonly').attr('checked', mdata.readonly);
			JQuery('#bfElementTypeSelectMultiple').attr('checked', mdata.multiple);
			JQuery('#bfElementSelectAdvancedMailback').attr('checked', mdata.mailback);
			JQuery('#bfElementTypeSelectHint').val(mdata.hint);
			JQuery('#bfElementSelectAdvancedHideLabel').attr('checked', mdata.hideLabel);
			JQuery('#bfElementSelectAdvancedLogging').attr('checked', mdata.logging);
			JQuery('#bfElementSelectAdvancedOrderNumber').val(mdata.orderNumber);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
			JQuery('#bfElementValidationRequired').attr('checked', mdata.required);
		};	
		
		// File
		this.saveFileProperties = function(mdata, item){
			// dynamic properties
			mdata.uploadDirectory = JQuery('#bfElementFileAdvancedUploadDirectory').val();
			mdata.timestamp = JQuery('#bfElementFileAdvancedTimestamp').attr('checked');
			mdata.allowedFileExtensions = JQuery('#bfElementFileAdvancedAllowedFileExtensions').val();
			mdata.attachToUserMail = JQuery('#bfElementFileAdvancedAttachToUserMail').attr('checked');
			mdata.attachToAdminMail = JQuery('#bfElementFileAdvancedAttachToAdminMail').attr('checked');
			
			mdata.readonly = JQuery('#bfElementTypeFileReadonly').attr('checked');
			mdata.hint = JQuery('#bfElementTypeFileHint').val();
                        mdata.useUrl = JQuery('#bfElementFileAdvancedUseUrl').attr('checked');
                        mdata.useUrlDownloadDirectory = JQuery('#bfElementFileAdvancedUseUrlDownloadDirectory').val();
			mdata.hideLabel = JQuery('#bfElementFileAdvancedHideLabel').attr('checked');
			mdata.logging = JQuery('#bfElementFileAdvancedLogging').attr('checked');
			mdata.orderNumber = JQuery('#bfElementFileAdvancedOrderNumber').val();
			mdata.flashUploader = JQuery('#bfElementFileAdvancedFlashUploader').attr('checked');
			mdata.flashUploaderMulti = JQuery('#bfElementFileAdvancedFlashUploaderMulti').attr('checked');
			mdata.flashUploaderBytes = JQuery('#bfElementFileAdvancedFlashUploaderBytes').val();
			mdata.flashUploaderWidth = JQuery('#bfElementFileAdvancedFlashUploaderWidth').val();
			mdata.flashUploaderHeight = JQuery('#bfElementFileAdvancedFlashUploaderHeight').val();
			mdata.flashUploaderTransparent = JQuery('#bfElementFileAdvancedFlashUploaderTransparent').attr('checked');
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			mdata.required = JQuery('#bfElementValidationRequired').attr('checked');
			
			item.properties = mdata;
		};
		
		this.populateFileProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementFileAdvancedUploadDirectory').val(mdata.uploadDirectory);
			JQuery('#bfElementFileAdvancedTimestamp').attr('checked', mdata.timestamp);
			JQuery('#bfElementFileAdvancedAllowedFileExtensions').val(mdata.allowedFileExtensions);
			JQuery('#bfElementFileAdvancedAttachToUserMail').attr('checked', mdata.attachToUserMail);
			JQuery('#bfElementFileAdvancedAttachToAdminMail').attr('checked', mdata.attachToAdminMail);
			
			JQuery('#bfElementTypeFileReadonly').attr('checked', mdata.readonly);
			JQuery('#bfElementTypeFileHint').val(mdata.hint);
			JQuery('#bfElementFileAdvancedHideLabel').attr('checked', mdata.hideLabel);
                        if(mdata.useUrl && mdata.useUrlDownloadDirectory == ''){
                            mdata.useUrlDownloadDirectory = '<?php echo JURI::root() . 'components/com_breezingforms/uploads'  ;?>';
                        }
                        JQuery('#bfElementFileAdvancedUseUrl').attr('checked', mdata.useUrl);
                        JQuery('#bfElementFileAdvancedUseUrlDownloadDirectory').val(mdata.useUrlDownloadDirectory);
			JQuery('#bfElementFileAdvancedLogging').attr('checked', mdata.logging);
			JQuery('#bfElementFileAdvancedOrderNumber').val(mdata.orderNumber);
			JQuery('#bfElementFileAdvancedFlashUploader').attr('checked', mdata.flashUploader);
			JQuery('#bfElementFileAdvancedFlashUploaderMulti').attr('checked', mdata.flashUploaderMulti);
			JQuery('#bfElementFileAdvancedFlashUploaderBytes').val(mdata.flashUploaderBytes);
			JQuery('#bfElementFileAdvancedFlashUploaderWidth').val(mdata.flashUploaderWidth);
			JQuery('#bfElementFileAdvancedFlashUploaderHeight').val(mdata.flashUploaderHeight);
			JQuery('#bfElementFileAdvancedFlashUploaderTransparent').attr('checked', mdata.flashUploaderTransparent);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
			JQuery('#bfElementValidationRequired').attr('checked', mdata.required);
		};

		// SUBMIT BUTTON
		this.saveSubmitButtonProperties = function(mdata, item){
			// dynamic properties
			mdata.src = JQuery('#bfElementSubmitButtonAdvancedSrc').val();
			mdata.value = JQuery('#bfElementTypeSubmitButtonValue').val();
			mdata.hint = JQuery('#bfElementTypeSubmitButtonHint').val();
			mdata.hideLabel = JQuery('#bfElementSubmitButtonAdvancedHideLabel').attr('checked');
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			
			item.properties = mdata;
		};
		
		this.populateSubmitButtonProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementSubmitButtonAdvancedSrc').val(mdata.src);
			JQuery('#bfElementTypeSubmitButtonValue').val(mdata.value);
			JQuery('#bfElementTypeSubmitButtonHint').val(mdata.hint);
			JQuery('#bfElementSubmitButtonAdvancedHideLabel').attr('checked', mdata.hideLabel);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
		};
			
		// CAPTCHA
		this.saveCaptchaProperties = function(mdata, item){
			// dynamic properties
			mdata.hint = JQuery('#bfElementTypeCaptchaHint').val();
			mdata.hideLabel = JQuery('#bfElementCaptchaAdvancedHideLabel').attr('checked');
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			item.properties = mdata;
		};

                // RECAPTCHA
		this.saveReCaptchaProperties = function(mdata, item){
			// dynamic properties
			mdata.hint = JQuery('#bfElementTypeReCaptchaHint').val();
			mdata.hideLabel = JQuery('#bfElementReCaptchaAdvancedHideLabel').attr('checked');

                        mdata.pubkey = JQuery('#bfElementTypeReCaptchaPubkey').val();
                        mdata.privkey = JQuery('#bfElementTypeReCaptchaPrivkey').val();
                        mdata.theme = JQuery('#bfElementTypeReCaptchaTheme').val();

			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			item.properties = mdata;
		};

                this.populateReCaptchaProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementTypeReCaptchaHint').val(mdata.hint);
			JQuery('#bfElementReCaptchaAdvancedHideLabel').attr('checked', mdata.hideLabel);

                        JQuery('#bfElementTypeReCaptchaPubkey').val(mdata.pubkey);
                        JQuery('#bfElementTypeReCaptchaPrivkey').val(mdata.privkey);
                        JQuery('#bfElementTypeReCaptchaTheme').val(mdata.theme);

			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
		};

		this.populateCaptchaProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementTypeCaptchaHint').val(mdata.hint);
			JQuery('#bfElementCaptchaAdvancedHideLabel').attr('checked', mdata.hideLabel);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
		};
		
		// CALENDAR
		this.saveCalendarProperties = function(mdata, item){
			// dynamic properties
			mdata.format = JQuery('#bfElementTypeCalendarFormat').val();
			mdata.value = JQuery('#bfElementTypeCalendarValue').val();
			mdata.size = JQuery('#bfElementTypeCalendarSize').val();
			mdata.hint = JQuery('#bfElementTypeCalendarHint').val();
			mdata.hideLabel = JQuery('#bfElementCalendarAdvancedHideLabel').attr('checked');
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			mdata.required = JQuery('#bfElementValidationRequired').attr('checked');
			
			item.properties = mdata;
		};
		
		this.populateCalendarProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementTypeCalendarFormat').val(mdata.format);
			JQuery('#bfElementTypeCalendarValue').val(mdata.value);
			JQuery('#bfElementTypeCalendarSize').val(mdata.size);
			JQuery('#bfElementTypeCalendarHint').val(mdata.hint);
			JQuery('#bfElementCalendarAdvancedHideLabel').attr('checked', mdata.hideLabel);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
			JQuery('#bfElementValidationRequired').attr('checked', mdata.required);
		};
			
		// Hidden
		this.saveHiddenProperties = function(mdata, item){
			// dynamic properties
			mdata.value = JQuery('#bfElementTypeHiddenValue').val();
			mdata.logging = JQuery('#bfElementHiddenAdvancedLogging').attr('checked');
			mdata.orderNumber = JQuery('#bfElementHiddenAdvancedOrderNumber').val();
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			
			item.properties = mdata;
		};
		
		this.populateHiddenProperties = function(mdata){
			// dynamic properties
			JQuery('#bfElementTypeHiddenValue').val(mdata.value);
			JQuery('#bfElementHiddenAdvancedLogging').attr('checked', mdata.logging);
			JQuery('#bfElementHiddenAdvancedOrderNumber').val(mdata.orderNumber);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
		};
		
		// SUMMARIZE
		this.saveSummarizeProperties = function(mdata, item){
			// dynamic properties
			var val = JQuery('#bfElementTypeSummarizeConnectWith').val();
			if(val != ''){
				var name = val.split(":")[0];
				var type = val.split(":")[1];
				mdata.connectWith = name;
				mdata.connectType = type;
			}
			
			mdata.useElementLabel = JQuery('#bfElementTypeSummarizeUseElementLabel').attr('checked');
			mdata.hideIfEmpty = JQuery('#bfElementTypeSummarizeHideIfEmpty').attr('checked');
			mdata.fieldCalc = JQuery('#bfElementAdvancedSummarizeCalc').val();
				
			mdata.emptyMessage = JQuery('#bfElementTypeSummarizeEmptyMessage').val();
			if(mdata.useElementLabel){
				var items = new Array();
				appScope.getItemsFlattened(appScope.dataObject, items);
				for(var i = 0; i < items.length;i++){
					if(items[i].properties.bfName == name){
						JQuery('#bfElementLabel').val(items[i].properties.label);
						break;
					}
				}		
			}
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			item.properties = mdata;
		};
		
		this.populateSummarizeProperties = function(mdata){
			var items = new Array();
			appScope.getItemsFlattened(appScope.dataObject, items);
			JQuery('#bfElementTypeSummarizeConnectWith').empty();
			var option = document.createElement('option');
			JQuery(option).val('');
			JQuery(option).text("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_CHOOSE_ONE')); ?>");
			JQuery('#bfElementTypeSummarizeConnectWith').append(option);
			for(var i = 0; i < items.length;i++){
				switch(items[i].properties.bfType){
					case 'bfTextfield':
					case 'bfTextarea':
					case 'bfRadioGroup':
					case 'bfCheckboxGroup':
					case 'bfCheckbox':
					case 'bfSelect':
					case 'bfFile':
					case 'bfHidden':
					case 'bfCalendar':
						var option = document.createElement('option');
						JQuery(option).val(items[i].properties.bfName + ":" + items[i].properties.bfType);
						JQuery(option).text(items[i].properties.label + " ("+items[i].properties.bfName+")"); 
						JQuery('#bfElementTypeSummarizeConnectWith').append(option);
					break;
				}
			}
			// dynamic properties
			JQuery('#bfElementTypeSummarizeConnectWith').val(mdata.connectWith+":"+mdata.connectType);
			JQuery('#bfElementTypeSummarizeEmptyMesssage').val(mdata.emptyMessage);
			JQuery('#bfElementTypeSummarizeUseElementLabel').attr('checked', mdata.useElementLabel);
			JQuery('#bfElementTypeSummarizeEmptyMessage').val(mdata.emptyMessage);
			JQuery('#bfElementTypeSummarizeHideIfEmpty').attr('checked', mdata.hideIfEmpty);
			JQuery('#bfElementAdvancedSummarizeCalc').val(mdata.fieldCalc);
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
		};
		
		// PAYPAL BUTTON
		this.savePayPalProperties = function(mdata, item){
			// dynamic properties
			
			// DEFAULT
			
			// account
			mdata.business = JQuery('#bfElementTypePayPalBusiness').val();
			mdata.token = JQuery('#bfElementTypePayPalToken').val();
			
			mdata.itemname = JQuery('#bfElementTypePayPalItemname').val();
			mdata.itemnumber = JQuery('#bfElementTypePayPalItemnumber').val();
			mdata.amount = JQuery('#bfElementTypePayPalAmount').val();
			mdata.tax = JQuery('#bfElementTypePayPalTax').val();
			mdata.thankYouPage = JQuery('#bfElementTypePayPalThankYouPage').val();
			mdata.locale = JQuery('#bfElementTypePayPalLocale').val();
			mdata.currencyCode = JQuery('#bfElementTypePayPalCurrencyCode').val();
			mdata.sendNotificationAfterPayment = JQuery('#bfElementTypePayPalSendNotificationAfterPayment').attr('checked');
			
			// ADVANCED

                        mdata.useIpn = JQuery('#bfElementPayPalAdvancedUseIpn').attr('checked');

			mdata.image = JQuery('#bfElementPayPalAdvancedImage').val();
			
			// testaccount
			mdata.testaccount = JQuery('#bfElementPayPalAdvancedTestaccount').attr('checked');
			mdata.testBusiness = JQuery('#bfElementPayPalAdvancedTestBusiness').val();
			mdata.testToken = JQuery('#bfElementPayPalAdvancedTestToken').val();
			
			// file
			mdata.downloadableFile = JQuery('#bfElementPayPalAdvancedDownloadableFile').attr('checked');
			mdata.filepath = JQuery('#bfElementPayPalAdvancedFilepath').val();
			mdata.downloadTries = JQuery('#bfElementPayPalAdvancedDownloadTries').val();
			
			// OTHER ADVANCED
			mdata.hint = JQuery('#bfElementTypePayPalHint').val();
			mdata.hideLabel = JQuery('#bfElementPayPalAdvancedHideLabel').attr('checked');
			
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			item.properties = mdata;
		};
		
		this.populatePayPalProperties = function(mdata){
			// dynamic properties
			
			// DEFAULT
			
			// account
			JQuery('#bfElementTypePayPalBusiness').val(mdata.business);
			JQuery('#bfElementTypePayPalToken').val(mdata.token);
			
			JQuery('#bfElementTypePayPalItemname').val(mdata.itemname);
			JQuery('#bfElementTypePayPalItemnumber').val(mdata.itemnumber);
			JQuery('#bfElementTypePayPalAmount').val(mdata.amount);
			JQuery('#bfElementTypePayPalTax').val(mdata.tax);
			JQuery('#bfElementTypePayPalThankYouPage').val(mdata.thankYouPage);
			JQuery('#bfElementTypePayPalLocale').val(mdata.locale);
			JQuery('#bfElementTypePayPalCurrencyCode').val(mdata.currencyCode);
			JQuery('#bfElementTypePayPalSendNotificationAfterPayment').attr('checked', mdata.sendNotificationAfterPayment);
			// ADVANCED
			
			JQuery('#bfElementPayPalAdvancedImage').val(mdata.image);
			
			// testaccount
			JQuery('#bfElementPayPalAdvancedTestaccount').attr('checked', mdata.testaccount);
			JQuery('#bfElementPayPalAdvancedTestBusiness').val(mdata.testBusiness);
			JQuery('#bfElementPayPalAdvancedTestToken').val(mdata.testToken);
			
			// file
			JQuery('#bfElementPayPalAdvancedDownloadableFile').attr('checked', mdata.downloadableFile);
			JQuery('#bfElementPayPalAdvancedFilepath').val(mdata.filepath);
			JQuery('#bfElementPayPalAdvancedDownloadTries').val(mdata.downloadTries);
                        if(typeof mdata.useIpn == "undefined"){
                            mdata['useIpn'] = false;
                        }
                        JQuery('#bfElementPayPalAdvancedUseIpn').attr('checked', mdata.useIpn);
			JQuery('#bfElementTypePayPalHint').val(mdata.hint);
			JQuery('#bfElementPayPalAdvancedHideLabel').attr('checked', mdata.hideLabel);
			
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
		};
		
		// SOFORTUEBERWEISUNG BUTTON
		this.saveSofortueberweisungProperties = function(mdata, item){
			// dynamic properties
			
			// DEFAULT

			// account
			mdata.user_id = JQuery('#bfElementTypeSofortueberweisungUserId').val();
			mdata.project_id = JQuery('#bfElementTypeSofortueberweisungProjectId').val();
			mdata.project_password = JQuery('#bfElementTypeSofortueberweisungProjectPassword').val();
			
			mdata.reason_1 = JQuery('#bfElementTypeSofortueberweisungReason1').val();
			mdata.reason_2 = JQuery('#bfElementTypeSofortueberweisungReason2').val();
			mdata.amount = JQuery('#bfElementTypeSofortueberweisungAmount').val();
			mdata.thankYouPage = JQuery('#bfElementTypeSofortueberweisungThankYouPage').val();
			mdata.language_id = JQuery('#bfElementTypeSofortueberweisungLanguageId').val();
			mdata.currency_id = JQuery('#bfElementTypeSofortueberweisungCurrencyId').val();
			mdata.mailback = JQuery('#bfElementTypeSofortueberweisungMailback').attr('checked');
			
			// ADVANCED
			
			mdata.image = JQuery('#bfElementSofortueberweisungAdvancedImage').val();
			
			// file
			mdata.downloadableFile = JQuery('#bfElementSofortueberweisungAdvancedDownloadableFile').attr('checked');
			mdata.filepath = JQuery('#bfElementSofortueberweisungAdvancedFilepath').val();
			mdata.downloadTries = JQuery('#bfElementSofortueberweisungAdvancedDownloadTries').val();
			
			// OTHER ADVANCED
			mdata.hint = JQuery('#bfElementTypeSofortueberweisungHint').val();
			mdata.hideLabel = JQuery('#bfElementSofortueberweisungAdvancedHideLabel').attr('checked');
			
			// static properties
			mdata.bfName = JQuery('#bfElementName').val();
			mdata.label = JQuery('#bfElementLabel').val();
			mdata.labelPosition = JQuery('#bfElementAdvancedLabelPosition').val();
			mdata.tabIndex = JQuery('#bfElementAdvancedTabIndex').val();
                        mdata.hideInMailback = JQuery('#bfElementAdvancedHideInMailback').attr('checked');
			mdata.off = JQuery('#bfElementAdvancedTurnOff').attr('checked');
			item.properties = mdata;
		};
		
		this.populateSofortueberweisungProperties = function(mdata){
			// dynamic properties
			
			// DEFAULT
			
			// account
			JQuery('#bfElementTypeSofortueberweisungUserId').val(mdata.user_id);
			JQuery('#bfElementTypeSofortueberweisungProjectId').val(mdata.project_id);
			JQuery('#bfElementTypeSofortueberweisungProjectPassword').val(mdata.project_password);
			
			JQuery('#bfElementTypeSofortueberweisungReason1').val(mdata.reason_1);
			JQuery('#bfElementTypeSofortueberweisungReason2').val(mdata.reason_2);
			JQuery('#bfElementTypeSofortueberweisungAmount').val(mdata.amount);
			JQuery('#bfElementTypeSofortueberweisungThankYouPage').val(mdata.thankYouPage);
			JQuery('#bfElementTypeSofortueberweisungLanguageId').val(mdata.language_id);
			JQuery('#bfElementTypeSofortueberweisungCurrencyId').val(mdata.currency_id);
			JQuery('#bfElementTypeSofortueberweisungMailback').attr('checked', mdata.mailback);
			
			// ADVANCED
			
			JQuery('#bfElementSofortueberweisungAdvancedImage').val(mdata.image);
			
			// file
			JQuery('#bfElementSofortueberweisungAdvancedDownloadableFile').attr('checked', mdata.downloadableFile);
			JQuery('#bfElementSofortueberweisungAdvancedFilepath').val(mdata.filepath);
			JQuery('#bfElementSofortueberweisungAdvancedDownloadTries').val(mdata.downloadTries);
			
			// OTHER ADVANCED
			JQuery('#bfElementTypeSofortueberweisungHint').val(mdata.hint);
			JQuery('#bfElementSofortueberweisungAdvancedHideLabel').attr('checked', mdata.hideLabel);
			
			// static properties
			JQuery('#bfElementName').val(mdata.bfName);
			JQuery('#bfElementLabel').val(mdata.label);
			JQuery('#bfElementAdvancedTabIndex').val(mdata.tabIndex);
                        JQuery('#bfElementAdvancedHideInMailback').attr('checked', mdata.hideInMailback);
			JQuery('#bfElementAdvancedTurnOff').attr('checked', mdata.off);
			JQuery('#bfElementAdvancedLabelPosition').val(mdata.labelPosition);
		};
			
		this.saveSelectedElementProperties = function(){
			if(appScope.selectedTreeElement){
				var mdata = appScope.getProperties(appScope.selectedTreeElement);
				if(mdata){
					var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
					if(item){
						switch(mdata.bfType){
							case 'bfSummarize':
								appScope.saveSummarizeProperties(mdata, item);
							break;
							case 'bfHidden':
								appScope.saveHiddenProperties(mdata, item);
								appScope.saveValidation(mdata, item);
								appScope.saveInit(mdata, item);
							break;
							case 'bfTextfield':
								appScope.saveTextProperties(mdata, item);
								appScope.saveValidation(mdata, item);
								appScope.saveInit(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfTextarea':
								appScope.saveTextareaProperties(mdata, item);
								appScope.saveValidation(mdata, item);
								appScope.saveInit(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfRadioGroup':
								appScope.saveRadioGroupProperties(mdata, item);
								appScope.saveValidation(mdata, item);
								appScope.saveInit(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfSubmitButton':
								appScope.saveSubmitButtonProperties(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfPayPal':
								appScope.savePayPalProperties(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfSofortueberweisung':
								appScope.saveSofortueberweisungProperties(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfCaptcha':
								appScope.saveCaptchaProperties(mdata, item);
								appScope.saveAction(mdata, item);
							break;
                                                        case 'bfReCaptcha':
								appScope.saveReCaptchaProperties(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfCalendar':
								appScope.saveCalendarProperties(mdata, item);
								appScope.saveValidation(mdata, item);
							break;
							case 'bfCheckboxGroup':
								appScope.saveCheckboxGroupProperties(mdata, item);
								appScope.saveValidation(mdata, item);
								appScope.saveInit(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfCheckbox':
								appScope.saveCheckboxProperties(mdata, item);
								appScope.saveValidation(mdata, item);
								appScope.saveInit(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfSelect':
								appScope.saveSelectProperties(mdata, item);
								appScope.saveValidation(mdata, item);
								appScope.saveInit(mdata, item);
								appScope.saveAction(mdata, item);
							break;
							case 'bfFile':
								appScope.saveFileProperties(mdata, item);
								appScope.saveValidation(mdata, item);
								appScope.saveInit(mdata, item);
								appScope.saveAction(mdata, item);
							break;
						}
					}
				}
			}
		};
		
		this.saveValidation = function(mdata, item){
			mdata.validationId = JQuery('#bfValidationScriptSelection').val();
			mdata.validationCode = JQuery('#bfValidationCode').val();
			mdata.validationMessage = JQuery('#bfValidationMessage').val();
				
			if(JQuery('#bfValidationTypeLibrary').get(0).checked){
				mdata.validationCondition = 1;
				for(var i = 0; i < appScope.elementScripts.validation.length;i++){
					if(appScope.elementScripts.validation[i].id == JQuery('#bfValidationScriptSelection').val()){
						mdata.validationFunctionName = appScope.elementScripts.validation[i].name;
						break;
					}
				}
				
			} else if(JQuery('#bfValidationTypeCustom').get(0).checked){
				mdata.validationCondition = 2;
				mdata.validationFunctionName = 'ff_' + mdata.bfName + '_validation';
			} else {
				mdata.validationCondition = 0;
			}
			item.properties = mdata;
		};
		
		this.saveInit = function(mdata, item){
			if(JQuery('#bfInitFormEntry').get(0).checked){
				mdata.initFormEntry = 1;
			} else {
				mdata.initFormEntry = 0;
			}
				
			if(JQuery('#bfInitPageEntry').get(0).checked){
				mdata.initPageEntry = 1;
			} else {
				mdata.initPageEntry = 0;
			}
				
			mdata.initId = JQuery('#bfInitScriptSelection').val();
			mdata.initCode = JQuery('#bfInitCode').val();
				
			if(JQuery('#bfInitTypeLibrary').get(0).checked){
				mdata.initCondition = 1;
				for(var i = 0; i < appScope.elementScripts.init.length;i++){
					if(appScope.elementScripts.init[i].id == JQuery('#bfInitScriptSelection').val()){
						mdata.initScript = appScope.elementScripts.init[i].name;
						break;
					}
				}
				
			} else if(JQuery('#bfInitTypeCustom').get(0).checked){
				mdata.initCondition = 2;
				mdata.initFunctionName = 'ff_' + mdata.bfName + '_init';
			} else {
				mdata.initCondition = 0;
			}
			item.properties = mdata;
		};
		
		this.saveAction = function(mdata, item){
				
				mdata.actionId = JQuery('#bfActionsScriptSelection').val();
				mdata.actionCode = JQuery('#bfActionCode').val();
				
				if(JQuery('#bfActionTypeLibrary').get(0).checked){
					mdata.actionCondition = 1;
					for(var i = 0; i < appScope.elementScripts.action.length;i++){
						if(appScope.elementScripts.action[i].id == JQuery('#bfActionsScriptSelection').val()){
							mdata.actionFunctionName = appScope.elementScripts.action[i].name;
							break;
						}
					}
				} else if(JQuery('#bfActionTypeCustom').get(0).checked){
					mdata.actionCondition = 2;
					mdata.actionFunctionName = 'ff_' + mdata.bfName + '_action';
				} else {
					mdata.actionCondition = 0;
				}
				
				if(JQuery('#bfActionClick').get(0).checked && mdata.actionCondition > 0){
					mdata.actionClick = 1;
				} else {
					mdata.actionClick = 0;
				}
				
				if(JQuery('#bfActionBlur').get(0).checked && mdata.actionCondition > 0){
					mdata.actionBlur = 1;
				} else {
					mdata.actionBlur = 0;
				}
				
				if(JQuery('#bfActionChange').get(0).checked && mdata.actionCondition > 0){
					mdata.actionChange = 1;
				} else {
					mdata.actionChange = 0;
				}
				
				if(JQuery('#bfActionFocus').get(0).checked && mdata.actionCondition > 0){
					mdata.actionFocus = 1;
				} else {
					mdata.actionFocus = 0;
				}
				
				if(JQuery('#bfActionSelect').get(0).checked && mdata.actionCondition > 0){
					mdata.actionSelect = 1;
				} else {
					mdata.actionSelect = 0;
				}
				
				item.properties = mdata;
		};
		
		this.populateSelectedElementProperties = function(){
			if(appScope.selectedTreeElement){
				var mdata = appScope.getProperties(appScope.selectedTreeElement);
				
				// compat 723
				if(typeof mdata.off == "undefined"){
					mdata['off'] = false;
				}
				// compat 723 end
				
				if(mdata){
					var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
					if(item){
						item.data.title = mdata.label;
						JQuery('#bfValidationScript').css('display','none');
						JQuery('#bfInitScript').css('display','none');
						JQuery('#bfActionScript').css('display','none');
						
						JQuery('#bfElementTypeText').css('display','none');
						JQuery('#bfElementTypeTextarea').css('display','none');
						JQuery('#bfElementTypeRadioGroup').css('display','none');
						JQuery('#bfElementTypeSubmitButton').css('display','none');
						JQuery('#bfElementTypePayPal').css('display','none');
						JQuery('#bfElementTypeSofortueberweisung').css('display','none');
						JQuery('#bfElementTypeCaptcha').css('display','none');
                                                JQuery('#bfElementTypeReCaptcha').css('display','none');
						JQuery('#bfElementTypeCalendar').css('display','none');
						JQuery('#bfElementTypeCheckboxGroup').css('display','none');
						JQuery('#bfElementTypeCheckbox').css('display','none');
						JQuery('#bfElementTypeSelect').css('display','none');
						JQuery('#bfElementTypeFile').css('display','none');
						JQuery('#bfElementTypeHidden').css('display','none');
						JQuery('#bfElementTypeSummarize').css('display','none');
						
						JQuery('#bfElementTypeTextAdvanced').css('display','none');
						JQuery('#bfElementTypeTextareaAdvanced').css('display','none');
						JQuery('#bfElementTypeRadioGroupAdvanced').css('display','none');
						JQuery('#bfElementTypeSubmitButtonAdvanced').css('display','none');
						JQuery('#bfElementTypePayPalAdvanced').css('display','none');
						JQuery('#bfElementTypeSofortueberweisungAdvanced').css('display','none');
						JQuery('#bfElementTypeCaptchaAdvanced').css('display','none');
                                                JQuery('#bfElementTypeReCaptchaAdvanced').css('display','none');
						JQuery('#bfElementTypeCalendarAdvanced').css('display','none');
						JQuery('#bfElementTypeCheckboxGroupAdvanced').css('display','none');
						JQuery('#bfElementTypeCheckboxAdvanced').css('display','none');
						JQuery('#bfElementTypeSelectAdvanced').css('display','none');
						JQuery('#bfElementTypeFileAdvanced').css('display','none');
						JQuery('#bfElementTypeHiddenAdvanced').css('display','none');
						JQuery('#bfElementTypeSummarizeAdvanced').css('display','none');
						JQuery('#bfElementValidationRequiredSet').css('display','none');
						
						JQuery('#bfAdvancedLeaf').css('display','');
                                                JQuery('#bfHideInMailback').css('display','');
						
						switch(mdata.bfType){
							case 'bfSummarize':
                                                                JQuery('#bfHideInMailback').css('display','none');
								JQuery('#bfElementType').val('bfElementTypeSummarize');
								appScope.populateSummarizeProperties(mdata);
							break;
							case 'bfHidden':
								JQuery('#bfElementType').val('bfElementTypeHidden');
								JQuery('#bfAdvancedLeaf').css('display','none');
								appScope.populateHiddenProperties(mdata);
								appScope.populateElementValidationScript();
								appScope.populateElementInitScript();
							break;
							case 'bfTextfield':
								JQuery('#bfElementType').val('bfElementTypeText');
								appScope.populateTextProperties(mdata);
								appScope.populateElementValidationScript();
								appScope.populateElementInitScript();
								appScope.populateElementActionScript();
							break;
							case 'bfTextarea':
								JQuery('#bfElementType').val('bfElementTypeTextarea');
								appScope.populateTextareaProperties(mdata);
								appScope.populateElementValidationScript();
								appScope.populateElementInitScript();
								appScope.populateElementActionScript();
							break;
							case 'bfRadioGroup':
								JQuery('#bfElementType').val('bfElementTypeRadioGroup');
								appScope.populateRadioGroupProperties(mdata);
								appScope.populateElementValidationScript();
								appScope.populateElementInitScript();
								appScope.populateElementActionScript();
							break;
							case 'bfSubmitButton':
								JQuery('#bfElementType').val('bfElementTypeSubmitButton');
								appScope.populateSubmitButtonProperties(mdata);
								appScope.populateElementActionScript();
							break;
							case 'bfPayPal':
								JQuery('#bfElementType').val('bfElementTypePayPal');
								appScope.populatePayPalProperties(mdata);
								appScope.populateElementActionScript();
							break;
							case 'bfSofortueberweisung':
								JQuery('#bfElementType').val('bfElementTypeSofortueberweisung');
								appScope.populateSofortueberweisungProperties(mdata);
								appScope.populateElementActionScript();
							break;
							case 'bfCaptcha':
                                                                JQuery('#bfHideInMailback').css('display','none');
								JQuery('#bfElementType').val('bfElementTypeCaptcha');
								appScope.populateCaptchaProperties(mdata);
							break;
                                                        case 'bfReCaptcha':
                                                                JQuery('#bfHideInMailback').css('display','none');
								JQuery('#bfElementType').val('bfElementTypeReCaptcha');
								appScope.populateReCaptchaProperties(mdata);
							break;
							case 'bfCalendar':
								JQuery('#bfElementType').val('bfElementTypeCalendar');
								appScope.populateCalendarProperties(mdata);
								appScope.populateElementValidationScript();
							break;
							case 'bfCheckboxGroup':
								JQuery('#bfElementType').val('bfElementTypeCheckboxGroup');
								appScope.populateCheckboxGroupProperties(mdata);
								appScope.populateElementValidationScript();
								appScope.populateElementInitScript();
								appScope.populateElementActionScript();
							break;
							case 'bfCheckbox':
								JQuery('#bfElementType').val('bfElementTypeCheckbox');
								appScope.populateCheckboxProperties(mdata);
								appScope.populateElementValidationScript();
								appScope.populateElementInitScript();
								appScope.populateElementActionScript();
							break;
							case 'bfSelect':
								JQuery('#bfElementType').val('bfElementTypeSelect');
								appScope.populateSelectProperties(mdata);
								appScope.populateElementValidationScript();
								appScope.populateElementInitScript();
								appScope.populateElementActionScript();
							break;
							case 'bfFile':
								JQuery('#bfElementType').val('bfElementTypeFile');
								appScope.populateFileProperties(mdata);
								appScope.populateElementValidationScript();
								appScope.populateElementInitScript();
								appScope.populateElementActionScript();
							break;
						}
						
						if(JQuery('#bfElementType').val() != ''){
							JQuery('#bfElementTypeClass').css('display','none');
							JQuery('#'+JQuery('#bfElementType').val()).css('display','');
							JQuery('#'+JQuery('#bfElementType').val()+"Advanced").css('display','');
							if(mdata.bfType != 'bfHidden'){
								JQuery('#bfElementValidationRequiredSet').css('display','');
							}
						}
					}
				}
			}
		};
		
		this.populateElementValidationScript = function(){
			
			var mdata = appScope.getProperties(appScope.selectedTreeElement);
			if(mdata){
			
				JQuery('#bfValidationScript').css('display','');
	
				JQuery('#bfValidationScriptSelection').empty();
				for(var i = 0; i < appScope.elementScripts.validation.length;i++){
					var option = document.createElement('option');
					JQuery(option).val(appScope.elementScripts.validation[i].id);
					JQuery(option).text(appScope.elementScripts.validation[i].package + '::' + appScope.elementScripts.validation[i].name); 
					if(appScope.elementScripts.validation[i].id == mdata.validationId){
						JQuery(option).get(0).setAttribute('selected', true);
					}
					JQuery('#bfValidationScriptSelection').append(option);
				}
				
				JQuery('#bfValidationMessage').val(mdata.validationMessage);
				JQuery('#bfValidationCode').val(mdata.validationCode);
				
				switch(mdata.validationCondition){
					case 1:
						JQuery('.bfValidationType').attr('checked','');
						JQuery('#bfValidationTypeLibrary').attr('checked',true);
						JQuery('#bfValidationScriptLibrary').css('display','');
						JQuery('#bfValidationScriptCustom').css('display','none');
						JQuery('#bfValidationScriptFlags').css('display','');
						JQuery('#bfValidationScriptLibrary').css('display','');
						JQuery('#bfValidationScriptCustom').css('display','none');
						appScope.setValidationScriptDescription();
						break;
					case 2:
						JQuery('.bfValidationType').attr('checked','');
						JQuery('#bfValidationTypeCustom').attr('checked',true);
						JQuery('#bfValidationScriptFlags').css('display','');
						JQuery('#bfValidationScriptLibrary').css('display','none');
						JQuery('#bfValidationScriptCustom').css('display','');
						break;
					default:
						JQuery('.bfValidationType').attr('checked','');
						JQuery('#bfValidationTypeNone').attr('checked',true);
						JQuery('#bfValidationScriptFlags').css('display','none');
						JQuery('#bfValidationScriptLibrary').css('display','none');
						JQuery('#bfValidationScriptCustom').css('display','none');
				}
			}
			
		};
		
		this.populateElementInitScript = function(){
			
			var mdata = appScope.getProperties(appScope.selectedTreeElement);
			if(mdata){
			
				JQuery('#bfInitScript').css('display','');
	
				JQuery('#bfInitScriptSelection').empty();
				for(var i = 0; i < appScope.elementScripts.init.length;i++){
					var option = document.createElement('option');
					JQuery(option).val(appScope.elementScripts.init[i].id);
					JQuery(option).text(appScope.elementScripts.init[i].package + '::' + appScope.elementScripts.init[i].name); 
					if(appScope.elementScripts.init[i].id == mdata.initId){
						JQuery(option).get(0).setAttribute('selected', true);
					}
					JQuery('#bfInitScriptSelection').append(option);
				}
				
				if(mdata.initFormEntry == 1){
					JQuery('#bfInitFormEntry').get(0).checked = true;
				} else {
					JQuery('#bfInitFormEntry').get(0).checked = false;
				}
				
				if(mdata.initPageEntry == 1){
					JQuery('#bfInitPageEntry').get(0).checked = true;
				} else {
					JQuery('#bfInitPageEntry').get(0).checked = false;
				}
				
				JQuery('#bfInitCode').val(mdata.initCode);
				
				switch(mdata.initCondition){
					case 1:
						JQuery('.bfInitType').attr('checked','');
						JQuery('#bfInitTypeLibrary').attr('checked',true);
						JQuery('#bfInitScriptLibrary').css('display','');
						JQuery('#bfInitScriptCustom').css('display','none');
						JQuery('#bfInitScriptFlags').css('display','');
						JQuery('#bfInitScriptLibrary').css('display','');
						JQuery('#bfInitScriptCustom').css('display','none');
						appScope.setInitScriptDescription();
						break;
					case 2:
						JQuery('.bfInitType').attr('checked','');
						JQuery('#bfInitTypeCustom').attr('checked',true);
						JQuery('#bfInitScriptFlags').css('display','');
						JQuery('#bfInitScriptLibrary').css('display','none');
						JQuery('#bfInitScriptCustom').css('display','');
						break;
					default:
						JQuery('.bfInitType').attr('checked','');
						JQuery('#bfInitTypeNone').attr('checked',true);
						JQuery('#bfInitScriptFlags').css('display','none');
						JQuery('#bfInitScriptLibrary').css('display','none');
						JQuery('#bfInitScriptCustom').css('display','none');
				}
			
			}
		};
		
		this.populateElementActionScript = function(){
			
			var mdata = appScope.getProperties(appScope.selectedTreeElement);
			if(mdata){
				
				JQuery('#bfActionScript').css('display','');
				
				if(mdata.bfType == 'bfSofortueberweisung' || mdata.bfType == 'bfPayPal' || mdata.bfType == 'bfIcon' || mdata.bfType == 'bfImageButton' || mdata.bfType == 'bfSubmitButton'){
					JQuery('.bfAction').css('display','none');
					JQuery('.bfActionLabel').css('display','none');
					JQuery('#bfActionClick').css('display','');
					JQuery('#bfActionClickLabel').css('display','');
				} else {
					JQuery('.bfAction').css('display','');
					JQuery('.bfActionLabel').css('display','');
				}
				
				JQuery('#bfActionsScriptSelection').empty();
				
				for(var i = 0; i < appScope.elementScripts.action.length;i++){
				
					var option = document.createElement('option');
					
					JQuery(option).val(appScope.elementScripts.action[i].id);
					JQuery(option).text(appScope.elementScripts.action[i].package + '::' + appScope.elementScripts.action[i].name); 
					
					if(appScope.elementScripts.action[i].id == mdata.actionId){
						
						JQuery(option).get(0).setAttribute('selected', true);
					}
					
					JQuery('#bfActionsScriptSelection').append(option);
				}
				
				if(mdata.actionClick == 1){
					JQuery('#bfActionClick').get(0).checked = true;
				} else {
					JQuery('#bfActionClick').get(0).checked = false;
				}
				
				if(mdata.actionBlur == 1){
					JQuery('#bfActionBlur').get(0).checked = true;
				} else {
					JQuery('#bfActionBlur').get(0).checked = false;
				}
				
				if(mdata.actionChange == 1){
					JQuery('#bfActionChange').get(0).checked = true;
				} else {
					JQuery('#bfActionChange').get(0).checked = false;
				}
				
				if(mdata.actionFocus == 1){
					JQuery('#bfActionFocus').get(0).checked = true;
				} else {
					JQuery('#bfActionFocus').get(0).checked = false;
				}
				
				if(mdata.actionSelect == 1){
					JQuery('#bfActionSelect').get(0).checked = true;
				} else {
					JQuery('#bfActionSelect').get(0).checked = false;
				}
				
				JQuery('#bfActionCode').val(mdata.actionCode);
				
				switch(mdata.actionCondition){
					case 1:
						JQuery('.bfActionType').attr('checked','');
						JQuery('#bfActionTypeLibrary').attr('checked',true);
						JQuery('#bfActionScriptLibrary').css('display','');
						JQuery('#bfActionScriptCustom').css('display','none');
						JQuery('#bfActionScriptFlags').css('display','');
						JQuery('#bfActionScriptLibrary').css('display','');
						JQuery('#bfActionScriptCustom').css('display','none');
						appScope.setActionScriptDescription();
						break;
					case 2:
						JQuery('.bfActionType').attr('checked','');
						JQuery('#bfActionTypeCustom').attr('checked',true);
						JQuery('#bfActionScriptFlags').css('display','');
						JQuery('#bfActionScriptLibrary').css('display','none');
						JQuery('#bfActionScriptCustom').css('display','');
						break;
					default:
						JQuery('.bfActionType').attr('checked','');
						JQuery('#bfActionTypeNone').attr('checked',true);
						JQuery('#bfActionScriptFlags').css('display','none');
						JQuery('#bfActionScriptLibrary').css('display','none');
						JQuery('#bfActionScriptCustom').css('display','none');
				}
			
			}
		};
		
		this.createTreeItem = function(obj){
				if(appScope.selectedTreeElement){
					switch(appScope.getNodeClass(appScope.selectedTreeElement)){
						case 'bfQuickModePageClass':
						case 'bfQuickModeSectionClass':
							if(obj.attributes['class'] != 'bfQuickModePageClass'){
								var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
								if(item){
						      		if(item.children){
						      			item.children[item.children.length] = obj;
						      		} else {
						      			alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_NO_CHILDREN_ERROR')); ?>");
						      		}
								}
							} else {
								alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_NEW_SECTION_ERROR')); ?>");
							}
						break;
						case 'bfQuickModeRootClass':
							if(obj.attributes['class'] == 'bfQuickModePageClass' && appScope.dataObject && appScope.dataObject.children){
					      		appScope.dataObject.children[appScope.dataObject.children.length] = obj;
							} else {
								alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_NEW_SECTION_ERROR')); ?>");
							}
						break;
						default: alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_NEW_SECTION_ERROR')); ?>");
					}
					JQuery.tree_reference('bfElementExplorer').refresh();
				}
		};
		
		/**
			Section properties
		*/
		this.saveSectionProperties = function(){
			var mdata = appScope.getProperties(appScope.selectedTreeElement);
			if(mdata){
				var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
				if(item){
					mdata.bfType = JQuery('#bfSectionType').val();
					mdata.displayType = JQuery('#bfSectionDisplayType').val();
					mdata.title = JQuery('#bfSectionTitle').val();
					mdata.name = JQuery('#bfSectionName').val();
					mdata.off = JQuery('#bfSectionAdvancedTurnOff').attr('checked');
					
					item.properties = mdata;
					item.data.title = JQuery('#bfSectionTitle').val();
				}
			}
		};
		
		this.populateSectionProperties = function(){
			if(appScope.selectedTreeElement){
				var mdata = appScope.getProperties(appScope.selectedTreeElement);
				// compat 723
				if(typeof mdata.off == "undefined"){
					mdata['off'] = false;
				}
				// compat 723 end
				if(mdata){
					var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
					if(item){
						item.data.title = mdata.title;
						JQuery('#bfSectionType').val( mdata.bfType );
						JQuery('#bfSectionDisplayType').val( mdata.displayType );
						JQuery('#bfSectionTitle').val( mdata.title );
						// compat 723
						JQuery('#bfSectionName').val( typeof mdata.name == "undefined" ? '' : mdata.name );
						// compat 723 end
						JQuery('#bfSectionAdvancedTurnOff').attr( 'checked', mdata.off );
					}	
				}
			}
		};
		
		/**
			Form properties
		*/
		this.saveFormProperties = function(){
			var mdata = appScope.getProperties(appScope.selectedTreeElement);
			if(mdata){
				var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
				if(item){
					mdata.title = JQuery('#bfFormTitle').val();
					mdata.name  = JQuery('#bfFormName').val();
					mdata.description = JQuery('#bfFormDescription').val();
					mdata.mailRecipient = JQuery('#bfFormMailRecipient').val();
					mdata.mailNotification = JQuery('#bfFormMailNotification').attr('checked'); 
					mdata.submitInclude = JQuery('#bfSubmitIncludeYes').attr('checked'); 
					mdata.submitLabel = JQuery('#bfFormSubmitLabel').val();
					mdata.cancelInclude = JQuery('#bfCancelIncludeYes').attr('checked'); 
					mdata.cancelLabel = JQuery('#bfFormCancelLabel').val();
					mdata.pagingInclude = JQuery('#bfPagingIncludeYes').attr('checked'); 
					mdata.pagingNextLabel = JQuery('#bfFormPagingNextLabel').val();
					mdata.pagingPrevLabel = JQuery('#bfFormPagingPrevLabel').val();
					mdata.theme = JQuery('#bfTheme').val();
					mdata.fadeIn = JQuery('#bfElementAdvancedFadeIn').attr('checked');
					mdata.useErrorAlerts = JQuery('#bfElementAdvancedUseErrorAlerts').attr('checked');
                                        mdata.useDefaultErrors = JQuery('#bfElementAdvancedUseDefaultErrors').attr('checked');
                                        mdata.useBalloonErrors = JQuery('#bfElementAdvancedUseBalloonErrors').attr('checked');
					mdata.lastPageThankYou = JQuery('#bfFormLastPageThankYou').attr('checked');
					mdata.rollover = JQuery('#bfElementAdvancedRollover').attr('checked');
					mdata.rolloverColor = JQuery('#bfElementAdvancedRolloverColor').val();
					mdata.toggleFields = JQuery('#bfElementAdvancedToggleFields').val();
					var pagesSize = JQuery('#bfQuickModeRoot').children("ul").children("li").size();
					if(mdata.lastPageThankYou && pagesSize > 1){
						mdata.submittedScriptCondidtion = 2;
						mdata.submittedScriptCode = 'function ff_'+mdata.name+'_submitted(status, message){ff_switchpage('+pagesSize+');}';
					} else {
						mdata.submittedScriptCondidtion = -1;
					}
					item.properties = mdata;
				}
			}
		};
		
		this.populateFormProperties = function(){
			if(appScope.selectedTreeElement){
				var mdata = appScope.getProperties(appScope.selectedTreeElement);
				if(mdata){
					// setting the node's data
					var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
					if(item){
						item.data.title = mdata.title;
						JQuery('#bfElementAdvancedFadeIn').attr('checked', mdata.fadeIn);
						JQuery('#bfFormLastPageThankYou').attr('checked', mdata.lastPageThankYou);
						JQuery('#bfElementAdvancedUseErrorAlerts').attr('checked', mdata.useErrorAlerts);
                                                JQuery('#bfElementAdvancedUseDefaultErrors').attr('checked', mdata.useDefaultErrors);
                                                JQuery('#bfElementAdvancedUseBalloonErrors').attr('checked', mdata.useBalloonErrors);
						if(mdata.submitInclude){
							JQuery('#bfSubmitIncludeYes').attr('checked', true);
							JQuery('#bfSubmitIncludeNo').attr('checked', false);
						}else{
							JQuery('#bfSubmitIncludeYes').attr('checked', false);
							JQuery('#bfSubmitIncludeNo').attr('checked', true);
						}
						JQuery('#bfFormSubmitLabel').val( mdata.submitLabel );
						if(mdata.cancelInclude){
							JQuery('#bfCancelIncludeYes').attr('checked', true);
							JQuery('#bfCancelIncludeNo').attr('checked', false);
						}else{
							JQuery('#bfCancelIncludeYes').attr('checked', false);
							JQuery('#bfCancelIncludeNo').attr('checked', true);
						}
						JQuery('#bfFormCancelLabel').val( mdata.cancelLabel );
						if(mdata.pagingInclude){
							JQuery('#bfPagingIncludeYes').attr('checked', true);
							JQuery('#bfPagingIncludeNo').attr('checked', false);
						}else{
							JQuery('#bfPagingIncludeYes').attr('checked', false);
							JQuery('#bfPagingIncludeNo').attr('checked', true);
						}
						JQuery('#bfFormPagingNextLabel').val( mdata.pagingNextLabel );
						JQuery('#bfFormPagingPrevLabel').val( mdata.pagingPrevLabel );
						JQuery('#bfTheme').val( mdata.theme );
						JQuery('#bfElementAdvancedRollover').attr('checked', mdata.rollover);
					 	JQuery('#bfElementAdvancedRolloverColor').val(mdata.rolloverColor);
					 	JQuery('#bfElementAdvancedToggleFields').val(mdata.toggleFields);
					}
				}
			}
		};
		
		/**
			Page Properties
		*/
		this.savePageProperties = function(){
			var mdata = appScope.getProperties(appScope.selectedTreeElement);
			if(mdata){
				var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
				if(item){
					item.properties = mdata;
				}
			}
		};
		
		this.populatePageProperties = function(){
			if(appScope.selectedTreeElement){
				var mdata = appScope.getProperties(appScope.selectedTreeElement);
				if(mdata){
					// setting the node's data
					var item = appScope.findDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), appScope.dataObject);
					if(item){
						// no properties yet to set
					}
				}
			}
		};
		
		/**
			Main application
		*/
		this.toggleProperties = function (property){
			JQuery('.bfProperties').css('display', 'none');
			JQuery('#'+property).css('display', '');
		};
		
		this.toggleAdvanced = function (property){
			JQuery('.bfAdvanced').css('display', 'none');
			JQuery('#'+property).css('display', '');
		};
		
		JQuery('#bfElementExplorer').tree(
			{
			  ui : {
			    theme_name : "apple",
			    context: [
					{
						id    : 'copy',
						label :  'Copy',
						visible : function (NODE, TREE_OBJ) {
							var source = appScope.findDataObjectItem( JQuery(NODE).attr('id'), appScope.dataObject );
							if(source.attributes['class'] == 'bfQuickModeSectionClass' || source.attributes['class'] == 'bfQuickModeElementClass'){
								return true;
							} 
							return false;
						},
						action  : function (NODE, TREE_OBJ) {
							var source = appScope.findDataObjectItem( JQuery(NODE).attr('id'), appScope.dataObject );
							if(source.attributes['class'] == 'bfQuickModeSectionClass' || source.attributes['class'] == 'bfQuickModeElementClass'){
								if(source && source.attributes && source.attributes.id){
									appScope.copyTreeElement = source;
								}
							}
						}
			    	},
			    	{
						id    : 'paste',
						label :  'Paste',
						visible : function (NODE, TREE_OBJ) {
                                                        if(appScope.copyTreeElement){
								var target = appScope.findDataObjectItem( JQuery(NODE).attr('id'), appScope.dataObject );
								if(target.attributes['class'] == 'bfQuickModeSectionClass' || target.attributes['class'] == 'bfQuickModePageClass'){
									return true;
								}
								return false;
							} 
							return false;
						},
						action  : function (NODE, TREE_OBJ) {
							if(appScope.copyTreeElement){
								var target = appScope.findDataObjectItem( JQuery(NODE).attr('id'), appScope.dataObject );
								if(target.attributes['class'] == 'bfQuickModeSectionClass' || target.attributes['class'] == 'bfQuickModePageClass'){
									appScope.insertElementInto(clone_obj(appScope.copyTreeElement), target);
									setTimeout("JQuery.tree_reference('bfElementExplorer').refresh()", 10); // give it time to close the context menu
								}
							}
						}
			    	},
			    	{ 
		                id      : "delete",
		                label   : "Delete",
		                icon    : "remove.png",
		                visible : function (NODE, TREE_OBJ) { var ok = true; JQuery.each(NODE, function () { if(TREE_OBJ.check("deletable", this) == false) ok = false; return false; }); return ok; }, 
		                action  : function (NODE, TREE_OBJ) { JQuery.each(NODE, function () { TREE_OBJ.remove(this); }); } 
		            }
					    	
				]
				    
			  },
			  selected : 'bfQuickModeRoot',
			  callback: {
			  	onselect : function(node,obj) {
			  		appScope.selectedTreeElement = node;
			  		JQuery('#bfPropertySaveButton').css('display','');
			  		JQuery('#bfPropertySaveButtonTop').css('display','');
			  		JQuery('#bfAdvancedSaveButton').css('display','');
			  		JQuery('#bfAdvancedSaveButtonTop').css('display','');
			  		switch( appScope.getNodeClass(node) ) {
			  			case 'bfQuickModeRootClass':
			  				appScope.toggleProperties('bfFormProperties');
			  				appScope.toggleAdvanced('bfFormAdvanced');
			  				appScope.populateFormProperties();
							break;
				  		case 'bfQuickModeSectionClass':
				  			appScope.toggleProperties('bfSectionProperties');
				  			appScope.toggleAdvanced('bfSectionAdvanced');
				  			appScope.populateSectionProperties();
				  			//JQuery('#bfAdvancedSaveButton').css('display','none');
				  			//JQuery('#bfAdvancedSaveButtonTop').css('display','none');
				  			break;
				  		case 'bfQuickModeElementClass':
				  			appScope.toggleProperties('bfElementProperties');
				  			appScope.toggleAdvanced('bfElementAdvanced');
				  			appScope.populateSelectedElementProperties();
				  			break;
				  		case 'bfQuickModePageClass':
				  			appScope.toggleProperties('bfPageProperties');
				  			appScope.toggleAdvanced('bfPageAdvanced');
				  			appScope.populatePageProperties();
				  			JQuery('#bfAdvancedSaveButton').css('display','none');
				  			JQuery('#bfAdvancedSaveButtonTop').css('display','none');
				  			break;
				  	}
			  	},
			  	onload : function(obj) {
			  		
			  	},
				onopen : function(NODE, TREE_OBJ) {
			  		var source = appScope.findDataObjectItem( JQuery(NODE).attr('id'), appScope.dataObject );
			  		source.state = 'open';
			  	},
			  	onclose : function(NODE, TREE_OBJ) {
			  		var source = appScope.findDataObjectItem( JQuery(NODE).attr('id'), appScope.dataObject );
			  		source.state = 'close';
			  	},
			  	ondelete : function(NODE, TREE_OBJ,RB) {
			  		appScope.selectedTreeElement = null;
			  		appScope.deleteDataObjectItem( JQuery(NODE).attr('id'), appScope.dataObject );
			  		var target = appScope.findDataObjectItem( JQuery('#bfQuickModeRoot').attr('id'), appScope.dataObject );
					if(target && !target.children){
						target.children = new Array();
					}
					// restoring page numbers
					if(target && target.children){
						if(target.attributes['class'] == 'bfQuickModeRootClass'){
							for(var i = 0; i < target.children.length; i++){
								if(target.children[i].attributes['class'] == 'bfQuickModePageClass'){
									var mdata = appScope.getProperties(JQuery('#'+target.children[i].attributes.id));
									if(mdata){
										target.children[i].attributes.id = 'bfQuickModePage' + (i+1);
										target.children[i].data.title = "<?php echo addslashes( BFText::_('COM_BREEZINGFORMS_PAGE') ) ?> " + (i+1);
										target.children[i].properties.pageNumber = i + 1;
									}
								}
							}
							// taking care of last page as thank you page
							var pagesSize = target.children.length;
							if(target.properties.lastPageThankYou && pagesSize > 1){
								target.properties.submittedScriptCondidtion = 2;
								target.properties.submittedScriptCode = 'function ff_'+target.properties.name+'_submitted(status, message){ff_switchpage('+pagesSize+');}';
							} else {
								target.properties.submittedScriptCondidtion = -1;
							}
						}
					}
			  		setTimeout("JQuery.tree_reference('bfElementExplorer').refresh()", 10); // give it time to close the context menu 
			  	},
			  	onmove : function(NODE,REF_NODE,TYPE,TREE_OBJ,RB){
			  		var parent = JQuery.tree_reference('bfElementExplorer').parent(NODE);
			  		if(!parent){
			  			parent = '#bfQuickModeRoot';
			  		}
			  		children = parent.children("ul").children("li");
				  	if( children && children.length && children.length > 0 ){
				  		for(var i = 0; i < children.length; i++){
				  			if(JQuery(NODE).attr('id') == children[i].id){
				  				appScope.moveDataObjectItem( JQuery(NODE).attr('id'), JQuery(parent).attr('id'), i, appScope.dataObject );
				  				break;
				  			}
				  		}
				  	} 
			  		JQuery.tree_reference('bfElementExplorer').refresh(); 
			  	}
			  },
			  rules : {
			  	metadata   : 'mdata',
			  	use_inline : true,
			  	deletable : 'none',
			  	creatable : 'none',
			  	renameable : 'none',
			  	
			  	draggable : ['section', 'element', 'page'],
			  	dragrules : [ 
			  					'element inside section', 
			  					'section inside section', 
			  					'element inside page', 
			  					'section inside page',
			  					'element after element',
			  					'element before element',
			  					'element after section',
			  					'element before section',
			  					'section after element',
			  					'section before element',
			  					'section after section',
			  					'section before section',
			  					'page before page',
			  					'page after page'
			  				]
			  },
			  data  : {
			    type  : "json",
			    json  : [appScope.dataObject]
			  }
			}
		
		);
		
		this.saveButton = function(){
			
			if(appScope.selectedTreeElement){
				var error = false;
				switch( appScope.getNodeClass(appScope.selectedTreeElement) ) {
			  		case 'bfQuickModeRootClass':
			  			if(JQuery.trim(JQuery('#bfFormTitle').val()) == ''){
							alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_ERROR_ENTER_TITLE')) ?>");
							error = true;
						} 
						if(JQuery.trim(JQuery('#bfFormName').val()) == ''){
							alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_ERROR_ENTER_NAME')) ?>");
							error = true;
						}
						var myRegxp = /^([a-zA-Z0-9_]+)$/;
						if(!myRegxp.test(JQuery('#bfFormName').val())){
							alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_ERROR_ENTER_NAME_CHARACTERS')) ?>");
							error = true;
						}
						if(!error) {
			  				appScope.saveFormProperties();
			  			}
					break;
			  		case 'bfQuickModeSectionClass':
			  			if(JQuery.trim(JQuery('#bfSectionName').val()) == ''){
							alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_ERROR_ENTER_NAME')) ?>");
							error = true;
						}
						if(!error) {
			  				appScope.saveSectionProperties();
			  			}
				  	break;
			  		case 'bfQuickModeElementClass':
						if(JQuery.trim(JQuery('#bfElementLabel').val()) == ''){
							alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_ERROR_ENTER_LABEL')) ?>");
							error = true;
						} 
						if(JQuery.trim(JQuery('#bfElementName').val()) == ''){
							alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_ERROR_ENTER_NAME')) ?>");
							error = true;
						}
						var myRegxp = /^([a-zA-Z0-9_]+)$/;
						if(!myRegxp.test(JQuery('#bfElementName').val())){
							alert("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_ERROR_ENTER_NAME_CHARACTERS')) ?>");
							error = true;
						}
						if(!error) {
			  				appScope.saveSelectedElementProperties();
			  			}
			  		case 'bfQuickModePageClass':
			  			appScope.savePageProperties();
			 		break;
				}
				if(!error){
					// TODO: remove the 2nd refresh if found out why this works only on the 2nd
					JQuery.tree_reference('bfElementExplorer').refresh();
					JQuery.tree_reference('bfElementExplorer').refresh();
					
					JQuery(".bfFadingMessage").html("<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_SETTINGS_UPDATED')) ?>");
					JQuery(".bfFadingMessage").fadeIn(1000);
					setTimeout('JQuery(".bfFadingMessage").fadeOut(1000);',1500);
				}
			}
		};
		
		JQuery('#bfPropertySaveButton').click(
			appScope.saveButton
		);

		JQuery('#bfPropertySaveButtonTop').click(
			appScope.saveButton
		);

		JQuery('#bfAdvancedSaveButton').click(
			appScope.saveButton
		);

		JQuery('#bfAdvancedSaveButtonTop').click(
			appScope.saveButton
		);

		JQuery('#bfNewSectionButton').click(
			function(){
				var id = "bfQuickModeSection" + ( Math.floor(Math.random() * 100000) );
				var obj = {
			      			attributes : {
			      				"class" : 'bfQuickModeSectionClass', 
			      				id : id, 
			      				mdata : JQuery.toJSON( { deletable : true, type: 'section' } ) 
			      			},
			      			properties :
			      			{ bfType : 'normal', type: 'section', displayType: 'breaks', title: "untitled section", name: id, description: '', off : false }
				      		, 
			      			state: "open", 
			      			data: { title: "untitled section", icon : '<?php echo $iconBase . 'icon_section.png'?>'},
			      			children : []
			      		};
				appScope.createTreeItem(obj);
				JQuery.tree_reference('bfElementExplorer').select_branch(JQuery('#'+id));
			}
		);
		
		JQuery('#bfElementType').change(
			function(){
				var obj = null;
				var id = "bfQuickMode" + ( Math.floor(Math.random() * 10000000) );
				var selected = JQuery('#bfElementType').val();
				switch(selected){
					case 'bfElementTypeText': obj = appScope.createTextfield(id); break;
					case 'bfElementTypeRadioGroup': obj = appScope.createRadioGroup(id); break;
					case 'bfElementTypeCheckboxGroup': obj = appScope.createCheckboxGroup(id); break;
					case 'bfElementTypeCheckbox': obj = appScope.createCheckbox(id); break;
					case 'bfElementTypeSelect': obj = appScope.createSelect(id); break;
					case 'bfElementTypeTextarea': obj = appScope.createTextarea(id); break;
					case 'bfElementTypeFile': obj = appScope.createFile(id); break;
					case 'bfElementTypeSubmitButton': obj = appScope.createSubmitButton(id); break;
					case 'bfElementTypeHidden': obj = appScope.createHidden(id); break;
					case 'bfElementTypeSummarize': obj = appScope.createSummarize(id); break;
					case 'bfElementTypeCaptcha': obj = appScope.createCaptcha(id); break;
                                        case 'bfElementTypeReCaptcha': obj = appScope.createReCaptcha(id); break;
					case 'bfElementTypeCalendar': obj = appScope.createCalendar(id); break;
					case 'bfElementTypePayPal': obj = appScope.createPayPal(id); break;
					case 'bfElementTypeSofortueberweisung': obj = appScope.createSofortueberweisung(id); break;
				}
				if(obj){
					appScope.replaceDataObjectItem(JQuery(appScope.selectedTreeElement).attr('id'), obj, appScope.dataObject);
					JQuery.tree_reference('bfElementExplorer').refresh();
					JQuery.tree_reference('bfElementExplorer').select_branch(JQuery('#'+id));
				}
			}
		);
		
		this.setActionScriptDescription = function(){
				for(var i = 0; i < appScope.elementScripts.action.length;i++){
					if(JQuery('#bfActionsScriptSelection').val() == appScope.elementScripts.action[i].id){
						JQuery('#bfActionsScriptSelectionDescription').text(appScope.elementScripts.action[i].description);
					}
				}
		};
		
		JQuery('#bfActionsScriptSelection').change(
			function(){
				appScope.setActionScriptDescription();
			}
		);
		
		this.setInitScriptDescription = function(){
				for(var i = 0; i < appScope.elementScripts.init.length;i++){
					if(JQuery('#bfInitScriptSelection').val() == appScope.elementScripts.init[i].id){
						JQuery('#bfInitSelectionDescription').text(appScope.elementScripts.init[i].description);
					}
				}
		};
		
		JQuery('#bfInitScriptSelection').change(
			function(){
				appScope.setInitScriptDescription();
			}
		);
		
		this.setValidationScriptDescription = function(){
				for(var i = 0; i < appScope.elementScripts.validation.length;i++){
					if(JQuery('#bfValidationScriptSelection').val() == appScope.elementScripts.validation[i].id){
						JQuery('#bfValidationScriptSelectionDescription').text(appScope.elementScripts.validation[i].description);
					}
				}
		};
		
		JQuery('#bfValidationScriptSelection').change(
			function(){
				appScope.setValidationScriptDescription();
			}
		);
		
		JQuery('#bfNewElementButton').click(
			function(){
				var id = "bfQuickMode" + ( Math.floor(Math.random() * 10000000) );
				var obj = appScope.createTextfield(id);
				appScope.createTreeItem(obj);
				JQuery.tree_reference('bfElementExplorer').select_branch(JQuery('#'+id));
			}
		);
		
		JQuery('#bfNewPageButton').click(
			function(){
				var pageNumber = JQuery('#bfQuickModeRoot').children("ul").children("li").size() == 0 ? 1 : JQuery('#bfQuickModeRoot').children("ul").children("li").size() + 1;
				var id = "bfQuickModePage" + pageNumber;
				
				// taking care of thank you page if a new page is added
				var item = appScope.findDataObjectItem('bfQuickModeRoot', appScope.dataObject);	
				var pagesSize = JQuery('#bfQuickModeRoot').children("ul").children("li").size();
				if(item.properties.lastPageThankYou && pagesSize > 0){
					item.properties.submittedScriptCondidtion = 2;
					item.properties.submittedScriptCode = 'function ff_'+item.properties.name+'_submitted(status, message){ff_switchpage('+(pagesSize+1)+');}';
				} else {
					item.properties.submittedScriptCondidtion = -1;
				}
				
				var obj = {
				  attributes : {
				      	"class" : 'bfQuickModePageClass', 
				      	id : id,
				      	mdata : JQuery.toJSON( { deletable : true, type : 'page'  } ) 
				  }, 
				  properties: { type : 'page', pageNumber : pageNumber, pageIntro : '' },
				  state: "open", 
				  data: { title: "<?php echo addslashes( BFText::_('COM_BREEZINGFORMS_PAGE') ) ?> " + pageNumber, icon: '<?php echo $iconBase . 'icon_page.png'?>'},
			      children : []
				};
				appScope.createTreeItem(obj);
				JQuery.tree_reference('bfElementExplorer').select_branch(JQuery('#'+id));
			}
		);
		
		JQuery('#menutab').tabs( { select: function(e, ui){  } } );
	}
	
	JQuery(document).ready(function() {
		app = new BF_QuickModeApp();
		var mdata = app.getProperties(app.selectedTreeElement);
		if(mdata){
			var item = app.findDataObjectItem('bfQuickModeRoot', app.dataObject);
			if(item){
				mdata.title = "<?php echo addslashes($formTitle) ?>";
				mdata.name  = "<?php echo addslashes($formName) ?>";
				mdata.description = "<?php echo addslashes(str_replace("\n",'',str_replace("\r",'',$formDesc))) ?>";
				mdata.mailRecipient = "<?php echo addslashes($formEmailadr) ?>";
				mdata.mailNotification = "<?php echo addslashes($formEmailntf) == 2 ? true : false ?>"; 
				item.properties = mdata;
			}
		}
	});
	
	function createInitCode()
	{
		var mdata = app.getProperties(app.selectedTreeElement);
		if(mdata){
			form = document.bfForm;
			name = mdata.bfName;
			if (name=='') {
				alert('Please enter the element name first.');
				return;
			} // if
			if (!confirm("<?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_CREAINIT'); ?>\n<?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_EXISTAPP'); ?>")) return;
			code =
				"function ff_"+name+"_init(element, condition)\n"+
				"{\n"+
				"    switch (condition) {\n";
			if (form.bfInitFormEntry.checked)
				code +=
					"        case 'formentry':\n"+
					"            break;\n";
			if (form.bfInitPageEntry.checked)
				code +=
					"        case 'pageentry':\n"+
					"            break;\n";
			code +=
				"        default:;\n"+
				"    } // switch\n"+
				"} // ff_"+name+"_init\n";
			oldcode = form.bfInitCode.value;
			if (oldcode != '')
				form.bfInitCode.value =
					code+
					"\n// -------------- <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_OLDBELOW'); ?> --------------\n\n"+
					oldcode;
			else
				form.bfInitCode.value = code;
		}
	} // createInitCode
	
	function createValidationCode()
	{
		var mdata = app.getProperties(app.selectedTreeElement);
		if(mdata){
			form = document.bfForm;
			name = mdata.bfName;
			if (name=='') {
				alert('Please enter the element name first.');
				return;
			} // if
			if (!confirm("<?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_CREAVALID'); ?>\n<?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_EXISTAPP'); ?>")) return;
			code =
				"function ff_"+name+"_validation(element, message)\n"+
				"{\n"+
				"    if (element_fails_my_test) {\n"+
				"        if (message=='') message = element.name+\" faild in my test.\\n\"\n"+
				"        ff_validationFocus(element.name);\n"+
				"        return message;\n"+
				"    } // if\n"+
				"    return '';\n"+
				"} // ff_"+name+"_validation\n";
			oldcode = form.bfValidationCode.value;
			if (oldcode != '')
				form.bfValidationCode.value =
					code+
					"\n// -------------- <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_OLDBELOW'); ?> --------------\n\n"+
					oldcode;
			else
				form.bfValidationCode.value = code;
		}
	} // createValidationCode
	
	function createActionCode(element)
	{
		var mdata = app.getProperties(app.selectedTreeElement);
		if(mdata){
			form = document.bfForm;
			name = mdata.bfName;
			if (name=='') {
				alert('Please enter the element name first.');
				return;
			} // if
			if (!confirm("<?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_CREAACTION'); ?>\n<?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_EXISTAPP'); ?>")) return;
			code =
				"function ff_"+name+"_action(element, action)\n"+
				"{\n"+
				"    switch (action) {\n";
			if (form.bfActionClick)
				if (form.bfActionClick.checked)
					code +=
						"        case 'click':\n"+
						"            break;\n";
			if (form.bfActionBlur)
				if (form.bfActionBlur.checked)
					code +=
						"        case 'blur':\n"+
						"            break;\n";
			if (form.bfActionChange)
				if (form.bfActionChange.checked)
					code +=
						"        case 'change':\n"+
						"            break;\n";
			if (form.bfActionFocus)
				if (form.bfActionFocus.checked)
					code +=
						"        case 'focus':\n"+
						"            break;\n";
			if (form.bfActionSelect)
				if (form.bfActionSelect.checked)
					code +=
						"        case 'select':\n"+
						"            break;\n";
			code +=
				"        default:;\n"+
				"    } // switch\n"+
				"} // ff_"+name+"_action\n";
				
			oldcode = form.bfActionCode.value;
			if (oldcode != '')
				form.bfActionCode.value =
					code+
					"\n// -------------- <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_OLDBELOW'); ?> --------------\n\n"+
					oldcode;
			else
				form.bfActionCode.value = code;
		}
	} // createActionCode
	
	var bf_submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		
		switch (pressbutton) {

                        case 'close':
                            location.href="index.php?option=com_breezingforms&act=manageforms";
                            break;
			case 'save':
				form.task.value = 'save';
				form.act.value = 'quickmode';
				var cVal = JQuery.base64Encode( JQuery.toJSON( app.dataObject ) );
                                JQuery.ajaxSetup({async:false});
                                var rndAdd = Math.random();
                                var chunks = new Array();
                                var chunk = '';
                                if(cVal.length > 30000){
                                    var cnt = 0;
                                    for( var i = 0; i < cVal.length; i++ ){
                                        chunk += cVal[i];
                                        cnt++;
                                        if( cnt == 60000 || ( i+1 == cVal.length && cnt+1 < 60000 ) ){
                                            chunks.push(chunk);
                                            chunk = '';
                                            cnt = 0;
                                        }
                                    }
                                }else{
                                    chunks.push(cVal);
                                }

                                if(chunks.length > 1){
                                    JQuery("#bfSaveQueue").css("display","");
                                    JQuery("#bfSaveQueue").center(true);
                                    JQuery("#bfSaveQueue").css("visibility","visible");
                                }
                                
                                for(var i = 0; i < chunks.length; i++){
                                    JQuery.post('index.php', { option: 'com_breezingforms', act: "quickmode", task: "doAjaxSave", form: document.adminForm.form.value, chunksLength: chunks.length, chunkIdx: i, chunk: chunks[i], rndAdd: rndAdd }, function(data){if(data!='' && isNaN(data))alert(data);if(data!='' && !isNaN(data)){document.adminForm.form.value=data;document.adminForm.submit()}});
                                    JQuery("#bfSaveQueue").get(0).innerHTML = "<?php echo addslashes(BFText::_('COM_BREEZINGFORMS_LOAD_PACKAGE'));?> " + (i+1) + " <?php echo addslashes(BFText::_('COM_BREEZINGFORMS_LOAD_PACKAGE_OF'));?> " + (chunks.length - 1);
                                }

                                JQuery("#bfSaveQueue").css("visibility","hidden");
                                JQuery("#bfSaveQueue").css("display","none");
				break;
			case 'preview':
				
				SqueezeBox.initialize({});               
			         
			    SqueezeBox.loadModal = function(modalUrl,handler,x,y) {
                                        this.presets.size.x = 820;
			    		this.initialize();      
			      		var options = $merge(options || {}, JQuery.toJSON("{handler: \'" + handler + "\', size: {x: " + x +", y: " + y + "}}"));      
						this.setOptions(this.presets, options);
						this.assignOptions();
						this.setContent(handler,modalUrl);
			   	};
			         
			    SqueezeBox.loadModal("<?php echo JURI::root()?>index.php?format=html&tmpl=component&option=com_breezingforms&ff_form=<?php echo $formId ?>&ff_page=1","iframe",820,400);
				break; 
			case 'preview_site':
				SqueezeBox.initialize({});               
			         
			    SqueezeBox.loadModal = function(modalUrl,handler,x,y) {
                                        this.presets.size.x = 820;
			    		this.initialize();      
			      		var options = $merge(options || {}, JQuery.toJSON("{handler: \'" + handler + "\', size: {x: " + x +", y: " + y + "}}"));      
						this.setOptions(this.presets, options);
						this.assignOptions();
						this.setContent(handler,modalUrl);
			   	};
			         
			    SqueezeBox.loadModal("<?php echo JURI::root()?>index.php?option=com_breezingforms&ff_form=<?php echo $formId ?>&ff_page=1","iframe",820,400);
				break; 
		}
	};

	if(typeof Joomla != "undefined"){
		Joomla.submitbutton = bf_submitbutton;
	}else{
		submitbutton = bf_submitbutton;
	}
	
	function addslashes( str ) {
    	return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
	}

	function clone_obj(obj) {
		    var c = obj instanceof Array ? [] : {};
		 
		    for (var i in obj) {
		        var prop = obj[i];
		 
		        if (typeof prop == 'object') {
		           if (prop instanceof Array) {
		               c[i] = [];
		 
		               for (var j = 0; j < prop.length; j++) {
		                   if (typeof prop[j] != 'object') {
		                       c[i].push(prop[j]);
		                   } else {
		                       c[i].push(clone_obj(prop[j]));
		                   }
		               }
		           } else {
		               c[i] = clone_obj(prop);
		           }
		        } else {
		           c[i] = prop;
		        }
		    }
		 
		    return c;
		}
	
	</script>
	
	<div style="float:left; margin-right: 3px;">
		<?php JToolBarHelper::custom('save', 'save.png', 'save_f2.png', BFText::_('COM_BREEZINGFORMS_TOOLBAR_QUICKMODE_SAVE'), false); ?>
		<?php
			
			if($formId != 0){
				JToolBarHelper::custom('preview', 'publish.png', 'save_f2.png', BFText::_('COM_BREEZINGFORMS_TOOLBAR_QUICKMODE_PREVIEW'), false);
				JToolBarHelper::custom('preview_site', 'publish.png', 'save_f2.png', BFText::_('COM_BREEZINGFORMS_SITE_PREVIEW'), false);
			}
		?>
		<?php JToolBarHelper::title('<img src="'. JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/themes/easymode/i/logo-breezingforms.png'.'" align="top"/>'); ?>
                <?php JToolBarHelper::custom('close', 'cancel.png', 'cancel_f2.png', BFText::_('COM_BREEZINGFORMS_TOOLBAR_QUICKMODE_CLOSE'), false); ?>
		<form action="index.php" method="post" name="adminForm">
			<input type="hidden" name="option" value="com_breezingforms" />
			<input type="hidden" name="act" value="quickmode" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="form" value="<?php echo $formId;?>" />
                        <input type="hidden" name="sizeTplCode" value="0" />
		</form>
	</div>
	
	<!-- ##### CSS ######## -->
	<!-- ####### CSS ######## -->
	<!-- ####### CSS ######## -->
	<!-- ####### CSS ######## -->
<?php 
jimport('joomla.version');
$version = new JVersion();

if(version_compare($version->getShortVersion(), '1.6', '>=')){
?>
<link rel="stylesheet" href="<?php echo JURI::root(true)?>/administrator/components/com_breezingforms/admin/bluestork.fix.css" type="text/css" />
<?php 
}
?>
<style type="text/css">

#bfSaveQueue{
	font: 11px Verdana, Geneva, sans-serif;
	border: 2px solid #E5E5E5;
	background-color: #F5F5F5;
	margin-top: 5px;
	padding: 10px;
	width: 350px;
        text-align: center;
        font-weight: bold;
}


#bfQuickModeWrapper {
	width: 100%;
}

#bfQuickModeLeft {
	float: left;
	width: 240px;
}

#bfQuickModeRight {
	padding-left: 250px;
}

#bfElementExplorer {
	width: auto;
	overflow: auto;
}

#bfQuickModeRight #menutab {
	width: 100%;
}

/* ##### hr ##### */

#bfQuickModeWrapper hr {
	color:#ccc;
	background-color:#ccc;
	height:1px;
	border:none;
	margin: 10px 0px 10px 0px;
}

/* ##### inputs ##### */

#bfQuickModeWrapper fieldset {
	padding: 10px;
}

#bfQuickModeWrapper label.bfPropertyLabel {
	float: left;
	width: 20%;
	margin-right: 10px;
}

#bfQuickModeWrapper textarea {
	height: 100px;
}

#bfQuickModeWrapper input[type=text], #bfQuickModeWrapper input[type=password], #bfQuickModeWrapper textarea, #bfQuickModeWrapper select {
    border: 1px solid #bbb;
    padding: 2px;
    line-height: normal;
    background: #f8f8f8;
    font-size: 100%;
    width: 50%;
}

#bfQuickModeWrapper textarea:hover, #bfQuickModeWrapper input[type='text']:hover, #bfQuickModeWrapper input[type='password']:hover, #bfQuickModeWrapper select:hover {
    border-color: #92c1ff;
}

#bfQuickModeWrapper textarea:focus, #bfQuickModeWrapper input[type='text']:focus, #bfQuickModeWrapper input[type='password']:focus, #bfQuickModeWrapper select:focus {
    border-color: #0071bc; outline: 2px solid #92c1ff;
}

#bfQuickModeWrapper input[type='button'], #bfQuickModeWrapper input[type='submit'], #bfQuickModeWrapper input[type='checkbox'], #bfQuickModeWrapper input[type='image'], #bfQuickModeWrapper input[type='radio'], #bfQuickModeWrapper input[type='reset'], #bfQuickModeWrapper select, #bfQuickModeWrapper button {
    cursor: pointer;
}

#bfQuickModeWrapper input[type='hidden'] { display: none; }

.bfClearfix:after {
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
}


</style>
	<!-- ####### CSS ######## -->
	<!-- ####### CSS ######## -->
	<!-- ####### CSS ######## -->
	<!-- ####### CSS ######## -->
<div style="display:none;visibility:hidden;" id="bfSaveQueue"></div>
<div id="bfQuickModeWrapper" class="bfClearfix">
	
	<div id="bfQuickModeLeft" class="bfClearfix">
		
	<div style="float:left">
		<form onsubmit="return false;">
			<input id="bfNewPageButton" type="submit" value="<?php echo BFText::_('COM_BREEZINGFORMS_NEW_PAGE'); ?>"/>
			<input id="bfNewSectionButton" type="submit" value="<?php echo BFText::_('COM_BREEZINGFORMS_NEW_SECTION'); ?>"/>
			<input id="bfNewElementButton" type="submit" value="<?php echo BFText::_('COM_BREEZINGFORMS_NEW_ELEMENT'); ?>"/>
			<span class="bfFadingMessage" style="display:none"></span>
		</form>
	</div>
	<div style="clear:both"></div>
	<br/>
	<div id="bfElementExplorer"></div>
	
	</div> <!-- ##### bfQuickModeLeft end ##### -->
	
	
	<div id="bfQuickModeRight" class="bfClearfix">
		
	<form name="bfForm" onsubmit="return false">
	
	<div id="menutab" class="flora">
            <ul>
                <li><a onclick="JQuery('.bfFadingMessage').css('display','none')" href="#fragment-1"><span><div class="tab-items"><?php echo BFText::_('COM_BREEZINGFORMS_PROPERTIES') ?></div></span></a></li>
                <li><a onclick="JQuery('.bfFadingMessage').css('display','none')" href="#fragment-2"><span><div class="tab-element"><?php echo BFText::_('COM_BREEZINGFORMS_ADVANCED') ?></div></span></a></li>
            </ul>

			<div class="t">

				<div class="t">
					<div class="t"></div>
		 		</div>
	 		</div>

			<div class="m">

	            <div id="fragment-1">
		            <div>
		            	<div class="bfFadingMessage" style="display:none"></div>
		            	<input type="submit" value="<?php echo BFText::_('COM_BREEZINGFORMS_PROPERTIES_SAVE'); ?>" id="bfPropertySaveButtonTop"/>
		            	<!-- FORM PROPERTIES BEGIN -->
		            	<div class="bfProperties" id="bfFormProperties" style="display:none">
		            		<br/>
		            		<fieldset>
		            			<legend><?php echo BFText::_('COM_BREEZINGFORMS_FORM_PROPERTIES'); ?></legend>
		            			<label class="bfPropertyLabel" for="bfFormTitle"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_TITLE'); ?></label>
		            			<input type="text" value="<?php echo htmlentities($formTitle,ENT_QUOTES,'UTF-8') ?>" id="bfFormTitle"/>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfFormName"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_NAME'); ?></label>
		            			<input type="text" value="<?php echo htmlentities($formName,ENT_QUOTES,'UTF-8') ?>" id="bfFormName"/>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfFormDescription"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_DESC'); ?></label>
		            			<textarea id="bfFormDescription"><?php echo htmlentities($formDesc,ENT_QUOTES,'UTF-8') ?></textarea>
		            			<br/><br/>
			            		<label class="bfPropertyLabel" for="bfFormLastPageThankYou"><?php echo BFText::_('COM_BREEZINGFORMS_LAST_PAGE_THANK_YOU'); ?></label>
			            		<input type="checkbox" value="" id="bfFormLastPageThankYou"/>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfFormMailNotification"><?php echo BFText::_('COM_BREEZINGFORMS_MAIL_NOTIFICATION'); ?></label>
		            			<input <?php echo $formEmailntf == 2 ? 'checked="checked"' : '' ?> type="checkbox" value="<?php echo htmlentities($formEmailntf,ENT_QUOTES,'UTF-8') ?>" id="bfFormMailNotification"/>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfFormMailRecipient"><?php echo BFText::_('COM_BREEZINGFORMS_MAIL_RECIPIENT'); ?></label>
		            			<input type="text" value="<?php echo htmlentities($formEmailadr,ENT_QUOTES,'UTF-8') ?>" id="bfFormMailRecipient"/>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfSubmitIncludeYes"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_SUBMIT_INCLUDE'); ?></label>
		            			
			            			<input checked="checked" type="radio" name="bfSubmitInclude" value="" id="bfSubmitIncludeYes"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES'); ?>
			            			<input type="radio" name="bfSubmitInclude" value="" id="bfSubmitIncludeNo"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO'); ?>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfFormSubmitLabel"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_SUBMIT_LABEL'); ?></label>
		            			<input type="text" value="save" id="bfFormSubmitLabel"/>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfPagingIncludeYes"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_PAGING_INCLUDE'); ?></label>
		            			<input checked="checked" type="radio" name="bfPagingInclude" value="" id="bfPagingIncludeYes"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES'); ?>
		            			<input type="radio" name="bfPagingInclude" value="" id="bfPagingIncludeNo"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO'); ?>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfFormPagingNextLabel"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_PAGING_NEXT_LABEL'); ?></label>
		            			<input type="text" value="next" id="bfFormPagingNextLabel"/>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfFormPagingPrevLabel"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_PAGING_PREV_LABEL'); ?></label>
		            			<input type="text" value="back" id="bfFormPagingPrevLabel"/>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfCancelIncludeYes"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_CANCEL_INCLUDE'); ?></label>
		            			<input checked="checked" type="radio" name="bfCancelInclude" value="" id="bfCancelIncludeYes"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES'); ?>
		            			<input type="radio" name="bfCancelInclude" value="" id="bfCancelIncludeNo"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO'); ?>
		            			<br/><br/>
		            			<label class="bfPropertyLabel" for="bfFormCancelLabel"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_CANCEL_LABEL'); ?></label>
		            			<input type="text" value="reset" id="bfFormCancelLabel"/>
		            		</fieldset>
		            	</div>
		            	<!-- FORM PROPERTIES END -->
		            	
		            	<!-- PAGE PROPERTIES BEGIN -->
		            	<div class="bfProperties" id="bfPageProperties" style="display:none">
		            		<br/>
		            		<fieldset>
		            		<legend><?php echo BFText::_('COM_BREEZINGFORMS_PAGE_PROPERTIES'); ?></legend>
		            		<label class="bfPropertyLabel" for="bfPageIntro"><?php echo BFText::_('COM_BREEZINGFORMS_PAGE_INTRO'); ?></label>
		            		<!-- <textarea id="bfPageIntro"></textarea>-->
		            		<a href="index.php?option=com_breezingforms&tmpl=component&act=quickmode_editor" title="<?php echo BFText::_('COM_BREEZINGFORMS_EDIT_INTRO');?>" class="modal" rel="{handler: 'iframe', size: {x: 820, y: 400}}"><?php echo BFText::_('COM_BREEZINGFORMS_EDIT_INTRO'); ?></a>
		            		</fieldset>
		            	</div>
		            	<!-- PAGE PROPERTIES END -->
		            	
		            	<!-- SECTION PROPERTIES BEGIN -->
		            	<div class="bfProperties" id="bfSectionProperties" style="display:none">
		            		<br/>
		            		<fieldset>
		            			<legend><?php echo BFText::_('COM_BREEZINGFORMS_SECTION_PROPERTIES'); ?></legend>
		            			<label class="bfPropertyLabel" for="bfSectionType"><?php echo BFText::_('COM_BREEZINGFORMS_SECTION_TYPE'); ?></label>
		            			<select id="bfSectionType">
		            				<option value="normal"><?php echo BFText::_('COM_BREEZINGFORMS_NORMAL'); ?></option>
		            				<option value="section"><?php echo BFText::_('COM_BREEZINGFORMS_FIELDSET'); ?></option>
		            			</select>
		            			<br/>
		            			<br/>
		            			<label class="bfPropertyLabel" for="bfSectionDisplayType"><?php echo BFText::_('COM_BREEZINGFORMS_SECTION_DISPLAY_TYPE'); ?></label>
		            			<select id="bfSectionDisplayType">
		            				<option value="inline"><?php echo BFText::_('COM_BREEZINGFORMS_INLINE'); ?></option>
		            				<option value="breaks"><?php echo BFText::_('COM_BREEZINGFORMS_BREAKS'); ?></option>
		            			</select>
		            			<br/>
		            			<br/>
		            			<label class="bfPropertyLabel" for="bfSectionTitle"><?php echo BFText::_('COM_BREEZINGFORMS_SECTION_TITLE'); ?></label>
		            			<input type="text" value="" id="bfSectionTitle"/>
		            			<br/>
		            			<br/>
		            			<label class="bfPropertyLabel" for="bfSectionName"><?php echo BFText::_('COM_BREEZINGFORMS_SECTION_NAME'); ?></label>
		            			<input type="text" value="" id="bfSectionName"/>
		            			<br/>
		            			<br/>
		            			<label class="bfPropertyLabel" for="bfSectionDescription"><?php echo BFText::_('COM_BREEZINGFORMS_SECTION_DESCRIPTION'); ?></label>
		            			<a href="index.php?option=com_breezingforms&tmpl=component&act=quickmode_editor" title="<?php echo BFText::_('COM_BREEZINGFORMS_EDIT_DESCRIPTION');?>" class="modal" rel="{handler: 'iframe', size: {x: 820, y: 400}}"><?php echo BFText::_('COM_BREEZINGFORMS_EDIT_DESCRIPTION'); ?></a>
		            		</fieldset>
		            	</div>
		            	<!-- SECTION PROPERTIES END -->
		            	
		            	<!-- ELEMENT PROPERTIES BEGIN -->
		            	<div class="bfProperties" id="bfElementProperties" style="display:none">
		            		<br/>
		            		<fieldset>
		            			<label class="bfPropertyLabel" for="bfElementType"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_TYPE'); ?></label>
		            			<select id="bfElementType">
		            				<option value=""><?php echo BFText::_('COM_BREEZINGFORMS_CHOOSE_ONE'); ?></option>
		            				<option value="bfElementTypeText"><?php echo BFText::_('COM_BREEZINGFORMS_TEXTFIELD'); ?></option>
		            				<option value="bfElementTypeTextarea"><?php echo BFText::_('COM_BREEZINGFORMS_TEXTAREA'); ?></option>
		            				<option value="bfElementTypeRadioGroup"><?php echo BFText::_('COM_BREEZINGFORMS_RADIO_GROUP'); ?></option>
		            				<option value="bfElementTypeCheckboxGroup"><?php echo BFText::_('COM_BREEZINGFORMS_CHECKBOX_GROUP'); ?></option>
		            				<option value="bfElementTypeCheckbox"><?php echo BFText::_('COM_BREEZINGFORMS_CHECKBOX'); ?></option>
		            				<option value="bfElementTypeSelect"><?php echo BFText::_('COM_BREEZINGFORMS_SELECT'); ?></option>
		            				<option value="bfElementTypeFile"><?php echo BFText::_('COM_BREEZINGFORMS_FILE'); ?></option>
		            				<option value="bfElementTypeSubmitButton"><?php echo BFText::_('COM_BREEZINGFORMS_SUBMIT_BUTTON'); ?></option>
		            				<option value="bfElementTypeHidden"><?php echo BFText::_('COM_BREEZINGFORMS_HIDDEN'); ?></option>
		            				<option value="bfElementTypeSummarize"><?php echo BFText::_('COM_BREEZINGFORMS_SUMMARIZE'); ?></option>
		            				<option value="bfElementTypeCaptcha"><?php echo BFText::_('COM_BREEZINGFORMS_CAPTCHA'); ?></option>
                                                        <option value="bfElementTypeReCaptcha"><?php echo BFText::_('COM_BREEZINGFORMS_ReCaptcha'); ?></option>
		            				<option value="bfElementTypeCalendar"><?php echo BFText::_('COM_BREEZINGFORMS_CALENDAR'); ?></option>
		            				<option value="bfElementTypePayPal"><?php echo BFText::_('COM_BREEZINGFORMS_PAYPAL'); ?></option>
		            				<option value="bfElementTypeSofortueberweisung"><?php echo BFText::_('COM_BREEZINGFORMS_SOFORTUEBERWEISUNG'); ?></option>
		            			</select>
		            			<br/>
		            			<br/>
		            			<legend><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_PROPERTIES'); ?></legend>
		            			<label class="bfPropertyLabel" for="bfElementLabel"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_LABEL'); ?></label>
		            			<input type="text" value="" id="bfElementLabel"/>
		            			<br/>
		            			<br/>
		            			<label class="bfPropertyLabel" for="bfElementName"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_NAME'); ?></label>
			            		<input type="text" value="" id="bfElementName"/>
			            		<!-- HIDDEN BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeHidden" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeHiddenValue"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALUE'); ?></label>
			            			<input type="text" value="" id="bfElementTypeHiddenValue"/>
		            			</div>
		            			<!-- HIDDEN END -->
		            			<!-- SUMMARIZE BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeSummarize" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSummarizeConnectWith"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_SUMMARIZE_CONNECT_WITH'); ?></label>
			            			<select id="bfElementTypeSummarizeConnectWith">
		            					<option value=""><?php echo BFText::_('COM_BREEZINGFORMS_CHOOSE_ONE'); ?></option>
		            				</select>
		            				<br/>
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSummarizeEmptyMessage"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_EMPTY_MESSAGE'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSummarizeEmptyMessage"/>
			            			<br/>
			            			<br/>
		            				<label class="bfPropertyLabel" for="bfElementTypeSummarizeHideIfEmpty"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HIDE_EMPTY'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeSummarizeHideIfEmpty"/>
		            				<br/>
			            			<br/>
		            				<label class="bfPropertyLabel" for="bfElementTypeSummarizeUseElementLabel"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_USE_LABEL'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeSummarizeUseElementLabel"/>
		            			</div>
		            			<!-- SUMMARIZE END -->
			            		<!-- TEXTFIELD BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeText" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextValue"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALUE'); ?></label>
			            			<input type="text" value="" id="bfElementTypeTextValue"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextSize"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_SIZE'); ?></label>
			            			<input type="text" value="" id="bfElementTypeTextSize"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextMaxLength"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_MAX_LENGTH'); ?></label>
			            			<input type="text" value="" id="bfElementTypeTextMaxLength"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeTextHint"></textarea>
		            			</div>
		            			<!-- TEXTFIELD END -->
		            			<!-- TEXTAREA BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeTextarea" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextareaValue"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALUE'); ?></label>
			            			<textarea id="bfElementTypeTextareaValue"></textarea>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextareaWidth"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_WIDTH'); ?></label>
			            			<input type="text" value="" id="bfElementTypeTextareaWidth"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextareaHeight"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HEIGHT'); ?></label>
			            			<input type="text" value="" id="bfElementTypeTextareaHeight"/>
			            			<br/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextareaMaxLength"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_MAX_LENGTH'); ?></label>
			            			<input type="text" value="" id="bfElementTypeTextareaMaxLength"/>
			            			<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for=bfElementTypeTextareaMaxLengthShow><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_MAX_LENGTH_SHOW'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeTextareaMaxLengthShow"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeTextareaHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeTextareaHint"></textarea>
		            			</div>
		            			<!-- TEXTAREA END -->
		            			<!-- RADIOGROUP BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeRadioGroup" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeRadioGroupGroups"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_GROUP'); ?></label>
			            			<textarea id="bfElementTypeRadioGroupGroups"></textarea>
				            		<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeRadioGroupReadonly"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_READONLY'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeRadioGroupReadonly"/>
				            		<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeRadioGroupWrap"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_WRAP'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeRadioGroupWrap"/>
				            		<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeRadioGroupHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeRadioGroupHint"></textarea>
		            			</div>
		            			<!-- RADIOGROUP END -->
		            			<!-- SUBMITBUTTON BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeSubmitButton" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSubmitButtonValue"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALUE'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSubmitButtonValue"/>
				            		<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSubmitButtonHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeSubmitButtonHint"></textarea>
		            			</div>
		            			<!-- SUBMITBUTTON END -->
								<!-- PAYPAL BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypePayPal" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalBusiness"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_BUSINESS'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalBusiness"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalToken"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_TOKEN'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalToken"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalItemname"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_ITEMNAME'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalItemname"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalItemnumber"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_ITEMNUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalItemnumber"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalAmount"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_AMOUNT'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalAmount"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalTax"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_TAX'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalTax"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalThankYouPage"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_THANKYOU_PAGE'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalThankYouPage"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalLocale"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_LOCALE'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalLocale"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalCurrencyCode"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_CURRENCY_CODE'); ?></label>
			            			<input type="text" value="" id="bfElementTypePayPalCurrencyCode"/>
			            			<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for=bfElementTypePayPalSendNotificationAfterPayment><?php echo BFText::_('COM_BREEZINGFORMS_NOTIFICATION_AFTER_PAYMENT'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypePayPalSendNotificationAfterPayment"/>
			            			
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypePayPalHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypePayPalHint"></textarea>
			            			
			            			
		            			</div>
		            			<!-- PAYPAL END -->
								<!-- SOFORTUEBERWEISUNG BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeSofortueberweisung" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungUserId"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_USERID'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSofortueberweisungUserId"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungProjectId"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_PROJECTID'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSofortueberweisungProjectId"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungProjectPassword"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_PROJECT_PASSWORD'); ?></label>
			            			<input type="password" value="" id="bfElementTypeSofortueberweisungProjectPassword"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungReason1"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_REASON1'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSofortueberweisungReason1"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungReason2"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_REASON2'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSofortueberweisungReason2"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungAmount"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_AMOUNT'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSofortueberweisungAmount"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungThankYouPage"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_THANKYOU_PAGE'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSofortueberweisungThankYouPage"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungLanguageId"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_LANGUAGE_ID'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSofortueberweisungLanguageId"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungCurrencyId"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_CURRENCY_ID'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSofortueberweisungCurrencyId"/>
			            			<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for=bfElementTypeSofortueberweisungMailback><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_MAILBACK'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeSofortueberweisungMailback"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSofortueberweisungHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeSofortueberweisungHint"></textarea>
		            			</div>
		            			<!-- SOFORTUEBERWEISUNG END -->
		            			<!-- CAPTCHA BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeCaptcha" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeCaptchaHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeCaptchaHint"></textarea>
		            			</div>
		            			<!-- CAPTCHA END -->
                                                <!-- RECAPTCHA BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeReCaptcha" style="display:none">
		            				<br/>

                                                        <label class="bfPropertyLabel" for=bfElementTypeReCaptchaPubkey><?php echo BFText::_('COM_BREEZINGFORMS_PUBLIC_KEY'); ?></label>
				            		<input type="text" value="" id="bfElementTypeReCaptchaPubkey"/>
                                                        <br/>
		            				<br/>
                                                        <label class="bfPropertyLabel" for=bfElementTypeReCaptchaPrivkey><?php echo BFText::_('COM_BREEZINGFORMS_PRIVATE_KEY'); ?></label>
				            		<input type="text" value="" id="bfElementTypeReCaptchaPrivkey"/>
                                                        <br/>
		            				<br/>
                                                        <label class="bfPropertyLabel" for=bfElementTypeReCaptchaTheme><?php echo BFText::_('COM_BREEZINGFORMS_Theme'); ?></label>
				            		<input type="text" value="red" id="bfElementTypeReCaptchaTheme"/> ('red', 'white', 'blackglass', 'clean', 'custom')
                                                        <br/>
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeReCaptchaHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeReCaptchaHint"></textarea>
		            			</div>
		            			<!-- RECAPTCHA END -->
		            			<!-- CALENDAR BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeCalendar" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeCalendarFormat"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_FORMAT'); ?></label>
			            			<input type="text" value="" id="bfElementTypeCalendarFormat"/>
		            				<br/>
		            				<br/>
		            				<label class="bfPropertyLabel" for="bfElementTypeCalendarValue"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALUE'); ?></label>
			            			<input type="text" value="" id="bfElementTypeCalendarValue"/>
			            			<br/>
		            				<br/>
		            				<label class="bfPropertyLabel" for="bfElementTypeCalendarSize"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_SIZE'); ?></label>
			            			<input type="text" value="" id="bfElementTypeCalendarSize"/>
			            			<br/>
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeCalendarHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeCalendarHint"></textarea>
		            			</div>
		            			<!-- CALENDAR END -->
		            			<!-- CHECKBOXGROUP BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeCheckboxGroup" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeCheckboxGroupGroups"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_GROUP'); ?></label>
			            			<textarea id="bfElementTypeCheckboxGroupGroups"></textarea>
				            		<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeCheckboxGroupReadonly"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_READONLY'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeCheckboxGroupReadonly"/>
				            		<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeCheckboxGroupWrap"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_WRAP'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeCheckboxGroupWrap"/>
				            		<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeCheckboxGroupHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeCheckboxGroupHint"></textarea>
		            			</div>
		            			<!-- CHECKBOXGROUP END -->
		            			<!-- CHECKBOX BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeCheckbox" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeCheckboxValue"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALUE'); ?></label>
			            			<textarea id="bfElementTypeCheckboxValue"></textarea>
			            			<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeCheckboxChecked"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_CHECKED'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeCheckboxChecked"/>
				            		<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeCheckboxReadonly"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_READONLY'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeCheckboxReadonly"/>
				            		<br/>
				            		<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeCheckboxHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeCheckboxHint"></textarea>
		            			</div>
		            			<!-- CHECKBOX END -->
		            			<!-- SELECT BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeSelect" style="display:none">
		            				<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSelectList"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_LIST'); ?></label>
			            			<textarea id="bfElementTypeSelectList"></textarea>
			            			<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeSelectMultiple"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_MULTIPLE'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeSelectMultiple"/>
				            		<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSelectListWidth"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_WIDTH'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSelectListWidth"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSelectListHeight"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HEIGHT'); ?></label>
			            			<input type="text" value="" id="bfElementTypeSelectListHeight"/>
				            		<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeSelectReadonly"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_READONLY'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeSelectReadonly"/>
				            		<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeSelectHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeSelectHint"></textarea>
		            			</div>
		            			<!-- SELECT END -->
		            			<!-- FILE BEGIN -->
		            			<div class="bfElementTypeClass" id="bfElementTypeFile" style="display:none">
		            				<br/>
				            		<label class="bfPropertyLabel" for="bfElementTypeFileReadonly"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_READONLY'); ?></label>
				            		<input type="checkbox" value="" id="bfElementTypeFileReadonly"/>
				            		<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTypeFileHint"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_HINT'); ?></label>
			            			<textarea id="bfElementTypeFileHint"></textarea>
		            			</div>
		            			<!-- FILE END -->
		            		</fieldset>
		            		<fieldset id="bfValidationScript" style="display:none">
		            			<legend><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALIDATION'); ?></legend>
		            			<span id="bfElementValidationRequiredSet" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementValidationRequired"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALIDATION_REQUIRED'); ?></label>
				            		<input type="checkbox" value="" id="bfElementValidationRequired"/>
				            		<br/>
				            		<br/>
			            		</span>
		            			<label class="bfPropertyLabel" for="bfElementValidation"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_VALIDATION_LABEL'); ?></label>
		            			<div>
								
									<?php echo BFText::_('COM_BREEZINGFORMS_TYPE') ?>:
									<?php echo BFText::_('COM_BREEZINGFORMS_NONE') ?> <input onclick="JQuery('#bfValidationScriptFlags').css('display','none');JQuery('#bfValidationScriptLibrary').css('display','none');JQuery('#bfValidationScriptCustom').css('display','none');" type="radio" name="validationType" id="bfValidationTypeNone" class="bfValidationType" value="0"/>
									<?php echo BFText::_('COM_BREEZINGFORMS_LIBRARY') ?> <input onclick="JQuery('#bfValidationScriptFlags').css('display','');JQuery('#bfValidationScriptLibrary').css('display','');JQuery('#bfValidationScriptCustom').css('display','none');" type="radio" name="validationType" id="bfValidationTypeLibrary" class="bfValidationType" value="1"/>
									<?php echo BFText::_('COM_BREEZINGFORMS_CUSTOM') ?> <input onclick="JQuery('#bfValidationScriptFlags').css('display','');JQuery('#bfValidationScriptLibrary').css('display','none');JQuery('#bfValidationScriptCustom').css('display','');" type="radio" name="validationType" id="bfValidationTypeCustom" class="bfValidationType" value="2"/>
									
									<div id="bfValidationScriptFlags" style="display:none">
										<hr/>
										<?php echo BFText::_('COM_BREEZINGFORMS_ERROR_MESSAGE') ?>: <input type="text" style="width:100%" maxlength="255" class="bfValidationMessage" id="bfValidationMessage" name="bfValidationMessage" value="" class="inputbox"/>
									</div>
									
									<div id="bfValidationScriptLibrary" style="display:none">
										<hr/>
										<?php echo BFText::_('COM_BREEZINGFORMS_SCRIPT') ?>: <select id="bfValidationScriptSelection"></select>
										<br/>
										<br/>
										<div id="bfValidationScriptSelectionDescription"></div>
									</div>
									
									<div id="bfValidationScriptCustom" style="display:none">
										<hr/>
										<div style="cursor: pointer;" onclick="createValidationCode()"><?php echo BFText::_('COM_BREEZINGFORMS_CREATE_CODE_FRAMEWORK') ?></div>
										<textarea name="bfValidationCode" id="bfValidationCode" rows="10" style="width:100%" wrap="off"></textarea>
									</div>
								</div>
		            		</fieldset>
		            	</div>
		            	<!-- ELEMENT PROPERTIES END -->
		            	<div class="bfFadingMessage" style="display:none"></div>
		            	<input type="submit" value="<?php echo BFText::_('COM_BREEZINGFORMS_PROPERTIES_SAVE'); ?>" id="bfPropertySaveButton"/>
		            </div>
	            </div>
	            
	            <div id="fragment-2">
	            	<div>
	            		<div class="bfFadingMessage" style="display:none"></div>
			            <input type="submit" value="<?php echo BFText::_('COM_BREEZINGFORMS_PROPERTIES_SAVE'); ?>" id="bfAdvancedSaveButtonTop"/>
			            <div class="bfAdvanced" id="bfPageAdvanced" style="display:none">
		            	</div>
		            	<div class="bfAdvanced" id="bfFormAdvanced" style="display:none">
		            		<br/>
			            	<fieldset>
			            		<legend><?php echo BFText::_('COM_BREEZINGFORMS_ADVANCED_FORM_OPTIONS'); ?></legend>
			            		<?php if($formId != 0){ ?>
			            		<a href="index.php?option=com_breezingforms&tmpl=component&task=editform&act=editpage&form=<?php echo $formId ?>&pkg=QuickModeForms" title="<?php echo BFText::_('COM_BREEZINGFORMS_MORE_OPTIONS');?>" class="modal" rel="{handler: 'iframe', size: {x: 820, y: 400}}"><?php echo htmlentities( BFText::_('COM_BREEZINGFORMS_MORE_OPTIONS'), ENT_QUOTES, 'UTF-8') ?></a>
			            		<?php } ?>
			            	</fieldset>
			            	<fieldset>
			            		<legend><?php echo BFText::_('COM_BREEZINGFORMS_ADVANCED_FORM_OTHER'); ?></legend>
			            		<label class="bfPropertyLabel" for="bfTheme"><?php echo BFText::_('COM_BREEZINGFORMS_THEME'); ?></label>
			            		<select id="bfTheme">
			            		<?php
								$tCount = count($themes);
								for($i = 0; $i < $tCount; $i++){
									echo '<option value="'.$themes[$i].'">'.$themes[$i].'</option>'."\n";
								}
			            		?>
			            		</select>
			            		<br/>
			            		<br/>
			            		<label class="bfPropertyLabel" for="bfElementAdvancedUseErrorAlerts"><?php echo BFText::_('COM_BREEZINGFORMS_USE_ERROR_ALERTS'); ?></label>
			            		<input type="checkbox" value="" id="bfElementAdvancedUseErrorAlerts"/>
                                                <br/>
			            		<br/>
                                                <label class="bfPropertyLabel" for="bfElementAdvancedUseDefaultErrors"><?php echo BFText::_('COM_BREEZINGFORMS_IF_NOT_USE_ERROR_ALERTS'); ?></label>
			            		<?php echo BFText::_('COM_BREEZINGFORMS_IF_USE_DEFAULT_ERRROS'); ?> <input type="checkbox" value="" id="bfElementAdvancedUseDefaultErrors"/>
                                                <?php echo BFText::_('COM_BREEZINGFORMS_IF_USE_BALLOON_ERRORS'); ?> <input type="checkbox" value="" id="bfElementAdvancedUseBalloonErrors"/>
			            		<br/>
			            		<br/>
			            		<label class="bfPropertyLabel" for="bfElementAdvancedFadeIn"><?php echo BFText::_('COM_BREEZINGFORMS_FADE_IN'); ?></label>
			            		<input type="checkbox" value="" id="bfElementAdvancedFadeIn"/>
			            		<br/>
			            		<br/>
			            		<label class="bfPropertyLabel" for="bfElementAdvancedRollover"><?php echo BFText::_('COM_BREEZINGFORMS_ROLLOVER'); ?></label>
			            		<input type="checkbox" value="" id="bfElementAdvancedRollover"/>
			            		<br/>
			            		<br/>
			            		<label class="bfPropertyLabel" for="bfElementAdvancedRolloverColor"><?php echo BFText::_('COM_BREEZINGFORMS_ROLLOVER_COLOR'); ?></label>
			            		<input type="text" value="" id="bfElementAdvancedRolloverColor"/>
			            		<br/>
		            			<br/>
		            			<label class="bfPropertyLabel" for="bfElementAdvancedToggleFields"><?php echo BFText::_('COM_BREEZINGFORMS_FORM_TOGGLEFIELDS'); ?></label>
		            			<textarea id="bfElementAdvancedToggleFields"></textarea>
			            	</fieldset>
			            </div>
			            <div class="bfAdvanced" id="bfSectionAdvanced" style="display:none">
			            	<br/>
			            	<label class="bfPropertyLabel" for="bfSectionAdvancedTurnOff"><?php echo BFText::_('COM_BREEZINGFORMS_TURN_OFF_INITIALLY'); ?></label>
			            	<input type="checkbox" value="" id="bfSectionAdvancedTurnOff"/>
			            </div>
			            <div class="bfAdvanced" id="bfElementAdvanced" style="display:none">
			            	<br/>
			            	<fieldset>
			            		<legend><?php echo BFText::_('COM_BREEZINGFORMS_ADVANCED_ELEMENT_OPTIONS'); ?></legend>
			            		<!-- HIDDEN BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeHiddenAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementHiddenAdvancedOrderNumber"><?php echo BFText::_('COM_BREEZINGFORMS_ORDER_NUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementHiddenAdvancedOrderNumber"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementHiddenAdvancedLogging"><?php echo BFText::_('COM_BREEZINGFORMS_LOGGING'); ?></label>
			            			<input type="checkbox" value="" id="bfElementHiddenAdvancedLogging"/>
			            		</div>
			            		<!-- HIDDEN END -->
			            		<!--  SUMMARIZE BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeSummarizeAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementAdvancedSummarizeCalc"><?php echo BFText::_('COM_BREEZINGFORMS_ELEMENT_CALC'); ?></label>
			            			<textarea id="bfElementAdvancedSummarizeCalc"></textarea>
			            		</div>
			            		<!--  SUMMARIZE END -->
			            		<!-- TEXTFIELD BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeTextAdvanced" style="display:none">
				            		<label class="bfPropertyLabel" for="bfElementAdvancedPassword"><?php echo BFText::_('COM_BREEZINGFORMS_PASSWORD'); ?></label>
			            			<input type="checkbox" value="" id="bfElementAdvancedPassword"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementAdvancedReadOnly"><?php echo BFText::_('COM_BREEZINGFORMS_READONLY'); ?></label>
			            			<input type="checkbox" value="" id="bfElementAdvancedReadOnly"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementAdvancedMailback"><?php echo BFText::_('COM_BREEZINGFORMS_MAILBACK'); ?></label>
			            			<input type="checkbox" value="" id="bfElementAdvancedMailback"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementAdvancedMailbackAsSender"><?php echo BFText::_('COM_BREEZINGFORMS_MAILBACK_AS_SENDER'); ?></label>
			            			<input type="checkbox" value="" id="bfElementAdvancedMailbackAsSender"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementAdvancedMailbackfile"><?php echo BFText::_('COM_BREEZINGFORMS_MAILBACKFILE'); ?></label>
			            			<input type="text" value="" id="bfElementAdvancedMailbackfile"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementAdvancedLogging"><?php echo BFText::_('COM_BREEZINGFORMS_LOGGING'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementAdvancedLogging"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementOrderNumber"><?php echo BFText::_('COM_BREEZINGFORMS_ORDER_NUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementOrderNumber"/>
			            		</div>
			            		<!-- TEXTFIELD END -->
			            		<!-- TEXTAREA BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeTextareaAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementTextareaAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementTextareaAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTextareaAdvancedLogging"><?php echo BFText::_('COM_BREEZINGFORMS_LOGGING'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementTextareaAdvancedLogging"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementTextareaAdvancedOrderNumber"><?php echo BFText::_('COM_BREEZINGFORMS_ORDER_NUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementTextareaAdvancedOrderNumber"/>
			            		</div>
			            		<!-- TEXTAREA END -->
			            		<!-- RADIOGROUP BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeRadioGroupAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementRadioGroupAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementRadioGroupAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementRadioGroupAdvancedLogging"><?php echo BFText::_('COM_BREEZINGFORMS_LOGGING'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementRadioGroupAdvancedLogging"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementRadioGroupAdvancedOrderNumber"><?php echo BFText::_('COM_BREEZINGFORMS_ORDER_NUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementRadioGroupAdvancedOrderNumber"/>
			            		</div>
			            		<!-- RADIOGROUP END -->
			            		<!-- SUBMITBUTTON BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeSubmitButtonAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementSubmitButtonAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementSubmitButtonAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementSubmitButtonAdvancedSrc"><?php echo BFText::_('COM_BREEZINGFORMS_SOURCE'); ?></label>
			            			<input type="text" value="" id="bfElementSubmitButtonAdvancedSrc"/>
			            		</div>
			            		<!-- SUBMITBUTTON END -->
								<!-- PAYPAL BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypePayPalAdvanced" style="display:none">
                                                        <label class="bfPropertyLabel" for="bfElementPayPalAdvancedUseIpn"><?php echo BFText::_('COM_BREEZINGFORMS_USE_IPN'); ?></label>
			            			<input type="checkbox" value="" id="bfElementPayPalAdvancedUseIpn"/><?php echo BFText::_('COM_BREEZINGFORMS_USE_IPN_DESCRIPTION'); ?>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementPayPalAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementPayPalAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementPayPalAdvancedImage"><?php echo BFText::_('COM_BREEZINGFORMS_IMAGE'); ?></label>
			            			<input type="text" value="" id="bfElementPayPalAdvancedImage"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementPayPalAdvancedTestaccount"><?php echo BFText::_('COM_BREEZINGFORMS_TESTACCOUNT'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementPayPalAdvancedTestaccount"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementPayPalAdvancedTestBusiness"><?php echo BFText::_('COM_BREEZINGFORMS_TESTBUSINESS'); ?></label>
			            			<input type="text" value="" id="bfElementPayPalAdvancedTestBusiness"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementPayPalAdvancedTestToken"><?php echo BFText::_('COM_BREEZINGFORMS_TESTTOKEN'); ?></label>
			            			<input type="text" value="" id="bfElementPayPalAdvancedTestToken"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementPayPalAdvancedDownloadableFile"><?php echo BFText::_('COM_BREEZINGFORMS_DOWNLOADABLE_FILE'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementPayPalAdvancedDownloadableFile"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementPayPalAdvancedFilepath"><?php echo BFText::_('COM_BREEZINGFORMS_FILEPATH'); ?></label>
			            			<input type="text" value="" id="bfElementPayPalAdvancedFilepath"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementPayPalAdvancedDownloadTries"><?php echo BFText::_('COM_BREEZINGFORMS_DOWNLOAD_TRIES'); ?></label>
			            			<input type="text" value="" id="bfElementPayPalAdvancedDownloadTries"/>
			            		</div>
			            		<!-- PAYPAL END -->
								<!-- SOFORTUEBERWEISUNG BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeSofortueberweisungAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementSofortueberweisungAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementSofortueberweisungAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementSofortueberweisungAdvancedImage"><?php echo BFText::_('COM_BREEZINGFORMS_IMAGE'); ?></label>
			            			<input type="text" value="" id="bfElementSofortueberweisungAdvancedImage"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementSofortueberweisungAdvancedDownloadableFile"><?php echo BFText::_('COM_BREEZINGFORMS_DOWNLOADABLE_FILE'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementSofortueberweisungAdvancedDownloadableFile"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementSofortueberweisungAdvancedFilepath"><?php echo BFText::_('COM_BREEZINGFORMS_FILEPATH'); ?></label>
			            			<input type="text" value="" id="bfElementSofortueberweisungAdvancedFilepath"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementSofortueberweisungAdvancedDownloadTries"><?php echo BFText::_('COM_BREEZINGFORMS_DOWNLOAD_TRIES'); ?></label>
			            			<input type="text" value="" id="bfElementSofortueberweisungAdvancedDownloadTries"/>
			            		</div>
			            		<!-- SOFORTUEBERWEISUNG END -->
			            		<!-- CAPTCHA BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeCaptchaAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementCaptchaAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementCaptchaAdvancedHideLabel"/>
			            		</div>
			            		<!-- CAPTCHA END -->
                                                <!-- RECAPTCHA BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeReCaptchaAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementReCaptchaAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementReCaptchaAdvancedHideLabel"/>
			            		</div>
			            		<!-- RECAPTCHA END -->
			            		<!-- CALENDAR BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeCalendarAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementCalendarAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementCalendarAdvancedHideLabel"/>
			            		</div>
			            		<!-- CALENDAR END -->
			            		<!-- CHECKBOXGROUP BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeCheckboxGroupAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementCheckboxGroupAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementCheckboxGroupAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementCheckboxGroupAdvancedLogging"><?php echo BFText::_('COM_BREEZINGFORMS_LOGGING'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementCheckboxGroupAdvancedLogging"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementCheckboxGroupAdvancedOrderNumber"><?php echo BFText::_('COM_BREEZINGFORMS_ORDER_NUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementCheckboxGroupAdvancedOrderNumber"/>
			            		</div>
			            		<!-- CHECKBOXGROUP END -->
			            		<!-- CHECKBOX BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeCheckboxAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementCheckboxAdvancedMailbackAccept"><?php echo BFText::_('COM_BREEZINGFORMS_MAILBACK_ACCEPT'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementCheckboxAdvancedMailbackAccept"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementCheckboxAdvancedMailbackConnectWith"><?php echo BFText::_('COM_BREEZINGFORMS_MAILBACK_CONNECT_WITH'); ?></label>
			            			<input type="text" value="" id="bfElementCheckboxAdvancedMailbackConnectWith"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementCheckboxAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementCheckboxAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementCheckboxAdvancedLogging"><?php echo BFText::_('COM_BREEZINGFORMS_LOGGING'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementCheckboxAdvancedLogging"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementCheckboxAdvancedOrderNumber"><?php echo BFText::_('COM_BREEZINGFORMS_ORDER_NUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementCheckboxAdvancedOrderNumber"/>
			            		</div>
			            		<!-- CHECKBOX END -->
			            		<!-- CHECKBOXGROUP BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeSelectAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementSelectAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementSelectAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementSelectAdvancedMailback"><?php echo BFText::_('COM_BREEZINGFORMS_MAILBACK'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementSelectAdvancedMailback"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementSelectAdvancedLogging"><?php echo BFText::_('COM_BREEZINGFORMS_LOGGING'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementSelectAdvancedLogging"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementSelectAdvancedOrderNumber"><?php echo BFText::_('COM_BREEZINGFORMS_ORDER_NUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementSelectAdvancedOrderNumber"/>
			            		</div>
			            		<!-- CHECKBOXGROUP END -->
								<!-- FILE BEGIN -->
			            		<div class="bfElementTypeClass" id="bfElementTypeFileAdvanced" style="display:none">
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedFlashUploader"><?php echo BFText::_('COM_BREEZINGFORMS_FLASH_UPLOADER'); ?></label>
			            			<input type="checkbox" value="" id="bfElementFileAdvancedFlashUploader"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedFlashUploaderMulti"><?php echo BFText::_('COM_BREEZINGFORMS_FLASH_UPLOADER_MULTI'); ?></label>
			            			<input type="checkbox" value="" id="bfElementFileAdvancedFlashUploaderMulti"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedFlashUploaderBytes"><?php echo BFText::_('COM_BREEZINGFORMS_FLASH_UPLOADER_BYTES'); ?></label>
			            			<input type="text" value="" id="bfElementFileAdvancedFlashUploaderBytes"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedFlashUploaderWidth"><?php echo BFText::_('COM_BREEZINGFORMS_FLASH_UPLOADER_WIDTH'); ?></label>
			            			<input type="text" value="" id="bfElementFileAdvancedFlashUploaderWidth"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedFlashUploaderHeight"><?php echo BFText::_('COM_BREEZINGFORMS_FLASH_UPLOADER_HEIGHT'); ?></label>
			            			<input type="text" value="" id="bfElementFileAdvancedFlashUploaderHeight"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedFlashUploaderTransparent"><?php echo BFText::_('COM_BREEZINGFORMS_FLASH_UPLOADER_TRANSPARENT'); ?></label>
			            			<input type="checkbox" value="" id="bfElementFileAdvancedFlashUploaderTransparent"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedUploadDirectory"><?php echo BFText::_('COM_BREEZINGFORMS_UPLOAD_DIRECTORY'); ?></label>
			            			<input type="text" value="" id="bfElementFileAdvancedUploadDirectory"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedTimestamp"><?php echo BFText::_('COM_BREEZINGFORMS_TIMESTAMP'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementFileAdvancedTimestamp"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedAllowedFileExtensions"><?php echo BFText::_('COM_BREEZINGFORMS_ALLOWED_FILE_EXTENSIONS'); ?></label>
			            			<input type="text" value="" id="bfElementFileAdvancedAllowedFileExtensions"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedAttachToUserMail"><?php echo BFText::_('COM_BREEZINGFORMS_ATTACH_TO_USERMAIL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementFileAdvancedAttachToUserMail"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedAttachToAdminMail"><?php echo BFText::_('COM_BREEZINGFORMS_ATTACH_TO_ADMINMAIL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementFileAdvancedAttachToAdminMail"/>
			            			<br/>
			            			<br/>
                                                        <label class="bfPropertyLabel" for="bfElementFileAdvancedUseUrl"><?php echo BFText::_('COM_BREEZINGFORMS_USE_URL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementFileAdvancedUseUrl"/>
			            			<br/>
			            			<br/>
                                                        <label class="bfPropertyLabel" for="bfElementFileAdvancedUseUrlDownloadDirectory"><?php echo BFText::_('COM_BREEZINGFORMS_USE_URL_DOWNLOAD_DIRECTORY'); ?></label>
			            			<input type="text" value="" id="bfElementFileAdvancedUseUrlDownloadDirectory"/> <?php echo BFText::_('COM_BREEZINGFORMS_USE_URL_DOWNLOAD_DIRECTORY_SET_SYNCH'); ?>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedHideLabel"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_LABEL'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementFileAdvancedHideLabel"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedLogging"><?php echo BFText::_('COM_BREEZINGFORMS_LOGGING'); ?></label>
			            			<input checked="checked" type="checkbox" value="" id="bfElementFileAdvancedLogging"/>
			            			<br/>
			            			<br/>
			            			<label class="bfPropertyLabel" for="bfElementFileAdvancedOrderNumber"><?php echo BFText::_('COM_BREEZINGFORMS_ORDER_NUMBER'); ?></label>
			            			<input type="text" value="" id="bfElementFileAdvancedOrderNumber"/>
			            		</div>
			            		<!-- FILE END -->
                                                <div id="bfHideInMailback">
                                                    <br/>
                                                    <label class="bfPropertyLabel" for="bfElementAdvancedHideInMailback"><?php echo BFText::_('COM_BREEZINGFORMS_HIDE_IN_MAILBACK'); ?></label>
                                                    <input type="checkbox" value="" id="bfElementAdvancedHideInMailback"/>
                                                    <br/>
                                                </div>

                                                <div id="bfAdvancedLeaf">
				            		<br/>
				            		<label class="bfPropertyLabel" id="bfElementAdvancedTabIndexLabel" for="bfElementAdvancedTabIndex"><?php echo BFText::_('COM_BREEZINGFORMS_TAB_INDEX'); ?></label>
				            		<input type="text" value="" id="bfElementAdvancedTabIndex"/>
				            		<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" for="bfElementAdvancedTurnOff"><?php echo BFText::_('COM_BREEZINGFORMS_TURN_OFF_INITIALLY'); ?></label>
			            			<input type="checkbox" value="" id="bfElementAdvancedTurnOff"/>
				            		<br/>
				            		<br/>
				            		<label class="bfPropertyLabel" id="bfElementAdvancedLabelPositionLabel" for="bfElementAdvancedLabelPosition"><?php echo BFText::_('COM_BREEZINGFORMS_LABEL_POSITION'); ?></label>
				            		<select id="bfElementAdvancedLabelPosition">
				            			<option value="left"><?php echo BFText::_('COM_BREEZINGFORMS_LEFT'); ?></option>
				            			<option value="top"><?php echo BFText::_('COM_BREEZINGFORMS_TOP'); ?></option>
				            			<option value="right"><?php echo BFText::_('COM_BREEZINGFORMS_RIGHT'); ?></option>
				            			<option value="bottom"><?php echo BFText::_('COM_BREEZINGFORMS_BOTTOM'); ?></option>
				            		</select>
			            		</div>
			            	</fieldset>
			            	
			            	<fieldset id="bfInitScript" style="display:none">
			            		<br/>
			            		<legend><?php echo BFText::_('COM_BREEZINGFORMS_ADVANCED_ELEMENT_INITSCRIPT'); ?></legend>
			            		<?php echo BFText::_('COM_BREEZINGFORMS_TYPE') ?>:
								<?php echo BFText::_('COM_BREEZINGFORMS_NONE') ?> <input onclick="JQuery('#bfInitScriptFlags').css('display','none');JQuery('#bfInitScriptLibrary').css('display','none');JQuery('#bfInitScriptCustom').css('display','none');" type="radio" name="initType" id="bfInitTypeNone" class="bfInitType" value="0"/>
								<?php echo BFText::_('COM_BREEZINGFORMS_LIBRARY') ?> <input onclick="JQuery('#bfInitScriptFlags').css('display','');JQuery('#bfInitScriptLibrary').css('display','');JQuery('#bfInitScriptCustom').css('display','none');" type="radio" name="initType" id="bfInitTypeLibrary" class="bfInitType" value="1"/>
								<?php echo BFText::_('COM_BREEZINGFORMS_CUSTOM') ?> <input onclick="JQuery('#bfInitScriptFlags').css('display','');JQuery('#bfInitScriptLibrary').css('display','none');JQuery('#bfInitScriptCustom').css('display','');" type="radio" name="initType" id="bfInitTypeCustom" class="bfInitType" value="2"/>
									
								<div id="bfInitScriptFlags" style="display:none">
									<hr/>
									<input type="checkbox" id="bfInitFormEntry" class="bfInitFormEntry" name="bfInitFormEntry" value="1"/><label for="bfInitFormEntry"> <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_FORMENTRY'); ?></label>
									<input type="checkbox" id="bfInitPageEntry" class="bfInitPageEntry" name="bfInitPageEntry" value="1"/><label for="bfInitPageEntry"> <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_PAGEENTRY'); ?></label>
								</div>
									
								<div id="bfInitScriptLibrary" style="display:none">
									<hr/>
									<?php echo BFText::_('COM_BREEZINGFORMS_SCRIPT') ?>: <select id="bfInitScriptSelection"></select>
									<br/>
									<br/>
									<div id="bfInitSelectionDescription"></div>
								</div>
									
								<div id="bfInitScriptCustom" style="display:none">
									<hr/>
									<div style="cursor: pointer;" onclick="createInitCode()"><?php echo BFText::_('COM_BREEZINGFORMS_CREATE_CODE_FRAMEWORK') ?></div>
									<textarea name="bfInitCode" id="bfInitCode" rows="10" style="width:100%" wrap="off"></textarea>
								</div>
			            	</fieldset>
			            	
			            	<fieldset id="bfActionScript" style="display:none">
			            		<br/>
			            		<legend><?php echo BFText::_('COM_BREEZINGFORMS_ADVANCED_ELEMENT_ACTIONSCRIPT'); ?></legend>
			            		
			            		<?php echo BFText::_('COM_BREEZINGFORMS_TYPE') ?>:
								<?php echo BFText::_('COM_BREEZINGFORMS_NONE') ?> <input onclick="JQuery('#bfActionScriptFlags').css('display','none');JQuery('#bfActionScriptLibrary').css('display','none');JQuery('#bfActionScriptCustom').css('display','none');" type="radio" name="actionType" name="actionType" id="bfActionTypeNone" class="bfActionType" value="0"/>
								<?php echo BFText::_('COM_BREEZINGFORMS_LIBRARY') ?> <input onclick="JQuery('#bfActionScriptFlags').css('display','');JQuery('#bfActionScriptLibrary').css('display','');JQuery('#bfActionScriptCustom').css('display','none');" type="radio" name="actionType" id="bfActionTypeLibrary" class="bfActionType" value="1"/>
								<?php echo BFText::_('COM_BREEZINGFORMS_CUSTOM') ?> <input onclick="JQuery('#bfActionScriptFlags').css('display','');JQuery('#bfActionScriptLibrary').css('display','none');JQuery('#bfActionScriptCustom').css('display','');" type="radio" name="actionType" id="bfActionTypeCustom" class="bfActionType" value="2"/>
									
								<div id="bfActionScriptFlags" style="display:none">
									<hr/>
										
									<?php echo BFText::_('COM_BREEZINGFORMS_ACTIONS') ?>:
									<input style="display:none" type="checkbox" class="bfAction" id="bfActionClick" name="bfActionClick" value="1"/><label style="display:none" class="bfActionLabel" id="bfActionClickLabel"> <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_CLICK'); ?></label>
									<input style="display:none" type="checkbox" class="bfAction" id="bfActionBlur" name="bfActionBlur" value="1"/><label style="display:none" class="bfActionLabel" id="bfActionBlurLabel"> <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_BLUR'); ?></label>
									<input style="display:none" type="checkbox" class="bfAction" id="bfActionChange" name="bfActionChange" value="1"/><label style="display:none" class="bfActionLabel" id="bfActionChangeLabel"> <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_CHANGE'); ?></label>
									<input style="display:none" type="checkbox" class="bfAction" id="bfActionFocus" name="bfActionFocus" value="1"/><label style="display:none" class="bfActionLabel" id="bfActionFocusLabel"> <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_FOCUS'); ?></label>
									<input style="display:none" type="checkbox" class="bfAction" id="bfActionSelect" name="bfActionSelect" value="1"/><label style="display:none" class="bfActionLabel" id="bfActionSelectLabel"> <?php echo BFText::_('COM_BREEZINGFORMS_ELEMENTS_SELECTION'); ?></label>
								</div>
									
								<div id="bfActionScriptLibrary" style="display:none">
									<hr/>
									<?php echo BFText::_('COM_BREEZINGFORMS_SCRIPT') ?>: <select id="bfActionsScriptSelection"></select>
									<br/>
									<br/>
									<div id="bfActionsScriptSelectionDescription"></div>
								</div>
									
								<div id="bfActionScriptCustom" style="display:none">
									<hr/>
									<div style="cursor: pointer;" onclick="createActionCode()"><?php echo BFText::_('COM_BREEZINGFORMS_CREATE_CODE_FRAMEWORK') ?></div>
									<textarea name="bfActionCode" id="bfActionCode" rows="10" style="width:100%" wrap="off"></textarea>
								</div>
			            		
			            	</fieldset>
			            	
			            </div>
			            <div class="bfFadingMessage" style="display:none"></div>
			            <input type="submit" value="<?php echo BFText::_('COM_BREEZINGFORMS_PROPERTIES_SAVE'); ?>" id="bfAdvancedSaveButton"/>
	            	</div>
	            </div>
            </div>
            
            
            <div class="b">
				<div class="b">
		 			<div class="b"></div>
				</div>
			</div>
  </div>
  
  </form>
  
	</div> <!-- ##### bfQuickModeRight end ##### -->
	
	</div> <!-- ##### bfQuickModeWrapper end ##### -->
        
<?php
	}
}