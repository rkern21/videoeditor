<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/integrator.class.php');
require_once($ff_admpath.'/admin/integrator.html.php');

$integrator = new BFIntegrator();

JToolBarHelper::title('<img src="'. JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/themes/easymode/i/logo-breezingforms.png" align="top" />');

switch($task){
	
	case 'add':
	case 'edit':
	case 'save':
	case 'saveCode':
	case 'addItem':
	case 'addCriteria':
	case 'removeCriteria':
	case 'addCriteriaJoomla':
	case 'removeCriteriaJoomla':
	case 'addCriteriaFixed':
	case 'removeCriteriaFixed':
	case 'removeItem':
	case 'saveFinalizeCode':
	case 'pub':
		
		if($task == 'save'){
			$id = $integrator->saveRule();
			JRequest::setVar('id', $id);
		}
		else if($task == 'saveFinalizeCode'){
			$integrator->saveFinalizeCode();
		}
		else if($task == 'addItem'){
			$integrator->addItem();
		}
		else if($task == 'saveCode'){
			$integrator->saveCode();
		}
		else if($task == 'removeItem'){
			$integrator->removeItem();
		}
		else if($task == 'addCriteria'){
			$integrator->addCriteria();
		}
		else if($task == 'removeCriteria'){
			$integrator->removeCriteria();
		}
		else if($task == 'addCriteriaJoomla'){
			$integrator->addCriteriaJoomla();
		}
		else if($task == 'removeCriteriaJoomla'){
			$integrator->removeCriteriaJoomla();
		}
		else if($task == 'addCriteriaFixed'){
			$integrator->addCriteriaFixed();
		}
		else if($task == 'removeCriteriaFixed'){
			$integrator->removeCriteriaFixed();
		}
		else if($task == 'pub'){
			if(JRequest::getVar('pub') == 'publish'){
				$integrator->publishItem();
			}
			else if(JRequest::getVar('pub') == 'unpublish'){
				$integrator->unpublishItem();
			} 
		}
		
		$rule = $integrator->getRule(JRequest::getInt('id',-1));
		
		if($rule == null){
			JToolBarHelper::save();
		}
		JToolBarHelper::cancel();
		
		echo BFIntegratorHtml::edit( 
			$rule, 
			$integrator->getItems(JRequest::getInt('id',-1)),
			$integrator->getTables(),
			$integrator->getForms(),
			$integrator->getFormElements($rule != null ? $rule->form_id : -1),
			$integrator->getCriteria(JRequest::getInt('id',-1)),
			$integrator->getCriteriaJoomla(JRequest::getInt('id',-1)),
			$integrator->getCriteriaFixed(JRequest::getInt('id',-1))
		);
		break;
	
	default:
		
		if($task == 'unpublish'){
			$integrator->unpublishRule();
		}
		else if($task == 'publish'){
			$integrator->publishRule();
		}
		else if($task == 'remove'){
			$integrator->removeRules();
		} 
		
		JToolBarHelper::addNewX();
		JToolBarHelper::deleteList();
		echo BFIntegratorHtml::listRules( $integrator->getRules() );
		break;
	
}