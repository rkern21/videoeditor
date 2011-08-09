<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
// Don't allow direct linking
defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

class translationFformFilter extends translationFilter
{
	function __construct ($contentElement){
		
		$this->filterNullValue=-1;
		$this->filterType="fform";
		$this->filterField = $contentElement->getFilter("fform");
		parent::translationFilter($contentElement);
	}
	
	function _createFilter(){
		$database = JFactory::getDBO();
		if (!$this->filterField ) return "";
		$filter="";
		
		if ($this->filter_value!=$this->filterNullValue){
			$sql = "SELECT id FROM #__facileforms_elements "
			." WHERE form=$this->filter_value";
			$database->setQuery($sql);
			$elementids = $database->loadObjectList();
			
			$idstring = "";
			foreach ($elementids as $pid){
				if (strlen($idstring)>0) $idstring.=",";
				$idstring.=$pid->id;
			}
			$filter = "c.id IN($idstring)";
		}
		return $filter;
	}
 
	/**
 * Creates facileforms_forms filter
 *
 * @param unknown_type $filtertype
 * @param unknown_type $contentElement
 * @return unknown
 */
	function _createfilterHTML(){
		$db =& JFactory::getDBO();

		if (!$this->filterField) return "";
		$formOptions=array();
		$formOptions[] = JHTML::_('select.option', '-1', JText::_('All Forms') );

		//	$sql = "SELECT c.id, c.title FROM #__categories as c ORDER BY c.title";
		$sql = "SELECT DISTINCT p.id, p.name FROM #__facileforms_forms as p, #__".$this->tableName." as c
			WHERE c.".$this->filterField."=p.id ORDER BY p.name";
		$db->setQuery($sql);
	
		$cats = $db->loadObjectList();
		$catcount=0;
		foreach($cats as $cat){
			$formOptions[] = JHTML::_('select.option', $cat->id,$cat->name);
			$catcount++;
		}
		$formList=array();
		$formList["title"]= JText::_('Which Form?');
		$formList["html"] = JHTML::_('select.genericlist',  $formOptions, 'fform_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );

		return $formList;
	}

}
?>