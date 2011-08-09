<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/quickmode.html.php');
require_once($ff_admpath.'/admin/quickmode.class.php');
require_once($ff_admpath.'/libraries/Zend/Json/Decoder.php');
require_once($ff_admpath.'/libraries/Zend/Json/Encoder.php');
$iconBase = '../administrator/components/com_breezingforms/libraries/jquery/themes/quickmode/i/';

$quickMode = new QuickMode();

$form = JRequest::getInt('form', 0);

switch($task){

        case 'doAjaxSave':
            $chunksLength = JRequest::getInt('chunksLength',0);
            $form = JRequest::getInt('form',0);
            $chunkIdx = JRequest::getInt('chunkIdx',0);
            $rndAdd = JRequest::getVar('rndAdd',0);
            JFile::write(JPATH_SITE . '/administrator/components/com_breezingforms/ajax_cache/ajaxsave_' . $chunkIdx . '_' . $rndAdd . '.txt', JRequest::getVar('chunk',''));

            if($chunkIdx == $chunksLength - 1){
                $contents = '';
                for($i = 0; $i < $chunksLength;$i++){
                    $contents .= JFile::read(JPATH_SITE . '/administrator/components/com_breezingforms/ajax_cache/ajaxsave_' . $i . '_' . $rndAdd . '.txt');
                    JFile::delete(JPATH_SITE . '/administrator/components/com_breezingforms/ajax_cache/ajaxsave_' . $i . '_' . $rndAdd . '.txt');
                }

                $formId = 0;
                //ob_start();
                $formId = $quickMode->save(
			$form,
			Zend_Json::decode( base64_decode( $contents ))
		);
                //ob_end_clean();
                echo $formId;
            }

            exit;
            break;

	case 'save':

		$formId = JRequest::getInt('form',0);

		$fOptions = $quickMode->getFormOptions($formId);

		if($fOptions == null){
			$formName = 'QuickForm'.mt_rand(0, mt_getrandmax());
			$formTitle = $formName;
			$formEmailntf = 1;
			$formEmailadr = '';
			$formDesc = '';
		} else {
			$formName      = $fOptions->name;
			$formTitle     = $fOptions->title;
			$formEmailntf  = $fOptions->emailntf;
			$formEmailadr  = $fOptions->emailadr;
			$formDesc      = $fOptions->description;
		}

		echo QuickModeHtml::showApplication($formId, $formName, $formTitle, $formDesc, $formEmailntf, $formEmailadr, $quickMode->getTemplateCode($formId), $quickMode->getElementScripts(), $quickMode->getThemes());
		break;

	default:

		$fOptions = $quickMode->getFormOptions($form);

		if($fOptions == null){
			$formName = 'QuickForm'.mt_rand(0, mt_getrandmax());
			$formTitle = $formName;
			$formEmailntf = 1;
			$formEmailadr = '';
			$formDesc = '';
		} else {
			$formName      = $fOptions->name;
			$formTitle     = $fOptions->title;
			$formEmailntf  = $fOptions->emailntf;
			$formEmailadr  = $fOptions->emailadr;
			$formDesc      = $fOptions->description;
		}

		$root = "{
			      	attributes: {
			      		'class' : 'bfQuickModeRootClass',
			      		id : 'bfQuickModeRoot',
			      		mdata : JQuery.toJSON(
                                                    {
			      				type : 'root'
                                                    }
						)
			      	},
			      	properties:
			      		{
			      			type : 'root',
			      			title: '".addslashes($formName)."',
			      			name: '',
			      			rollover: true,
			      			rolloverColor : '#ffc',
			      			toggleFields : '',
			      			description: '',
			      			mailNotification : false,
			      			mailRecipient: '',
			      			submitInclude: true,
			      			submitLabel: 'submit',
			    			cancelInclude: false,
			      			cancelLabel: 'reset',
			      			pagingInclude: true,
			      			pagingNextLabel : 'next',
                                                pagingPrevLabel : 'back',
			   			theme : 'default',
			   			fadeIn : false,
			   			lastPageThankYou : false,
			   			submittedScriptCondidtion: 0,
			   			submittedScriptCode : '',
						useErrorAlerts : false,
                                                useDefaultErrors : true,
                                                useBalloonErrors: false
			   		}
			      	,
			      	state: 'open',
			      	data: { title: '".addslashes($formName)."', icon: '".$iconBase . 'icon_form.png'."'},
			      	children : []
			      	}";
		$o = $root;
		if($form != 0){
			$o = $quickMode->getTemplateCode(JRequest::getInt('form', ''));
		}

		echo QuickModeHtml::showApplication($form, $formName, $formTitle, $formDesc, $formEmailntf, $formEmailadr, $o, $quickMode->getElementScripts(), $quickMode->getThemes());
}