<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
class BFIntegrate{
	
	/**
	 * JDatabase
	 */
	private $db = null;
	
	private $rules = array();
	
	private $formId = -1;
	
	private $data = array();
	
	function __construct($formId){
		$this->db = JFactory::getDBO();
		$this->rules = $this->getRules($formId);
		$this->formId = $formId;
	}
	
	public function getRules($formId){
		
		$this->db->setQuery("
			Select 
				rules.*, 
				rules.id As id, 
				concat('".$this->db->getPrefix()."', rules.reference_table) As reference_table, 
				forms.name As form_name,
				forms.id As form_id
			From 
				#__facileforms_integrator_rules As rules,
				#__facileforms_forms As forms
			Where
				rules.form_id = forms.id
			And 
				rules.form_id = ".$this->db->Quote($formId)."
			And
				forms.id = ".$this->db->Quote($formId)."
			And
				rules.published = 1
			Group By 
				rules.id
			Order By 
				rules.id
			");
		
		$out = array();
		$rules = $this->db->loadObjectList();
		$i = 0;
		foreach ($rules As $rule){
			
			$out[$i]['rule'] = $rule;
			$out[$i]['items'] = array();
			$i++;
		}
		return $out;
	}
	
	public function getItems($ruleId){
		
		$this->db->setQuery("
		
			Select 
				items.*,
				elements.name As element_name,
				elements.type As element_type
			From 
				#__facileforms_integrator_items As items,
				#__facileforms_elements As elements
			Where
				items.rule_id = ".$this->db->Quote($ruleId)."
			And
				elements.id = items.element_id
			And 
				items.published = 1
			Group By items.id
			Order By items.id Desc
		");
		
		$out = array();
		$items = $this->db->loadObjectList();
		$i = 0;
		foreach ($items As $item){
			
			$out[$i] = $item;
			$i++;
		}
		return $out;
	}
	
	public function getCriteria($ruleId){
		
		$this->db->setQuery("
		
			Select 
				crit.*,
				elements.name As element_name,
				elements.type As element_type
			From 
				#__facileforms_integrator_criteria_form As crit,
				#__facileforms_elements As elements
			Where
				crit.rule_id = ".$this->db->Quote($ruleId)."
			And
				elements.id = crit.element_id
			Group By crit.id
			Order By crit.id Desc
		");
		
		$ret = $this->db->loadObjectList();
		echo $this->db->getErrorMsg();
		return $ret;
	}
	
	public function getCriteriaJoomla($ruleId){
		
		$this->db->setQuery("
		
			Select 
				crit.*
			From 
				#__facileforms_integrator_criteria_joomla As crit
			Where
				crit.rule_id = ".$this->db->Quote($ruleId)."
			Group By crit.id
			Order By crit.id Desc
		");
		
		$ret = $this->db->loadObjectList();
		return $ret;
	}

	public function getCriteriaFixed($ruleId){
		
		$this->db->setQuery("
		
			Select 
				crit.*
			From 
				#__facileforms_integrator_criteria_fixed As crit
			Where
				crit.rule_id = ".$this->db->Quote($ruleId)."
			Group By crit.id
			Order By crit.id Desc
		");
		
		$ret = $this->db->loadObjectList();
		return $ret;
	}
	
	public function field(array $data){
		$this->data['data'.$data[_FF_DATA_ID]] = $data;
		$i = 0;
		foreach($this->rules As $rule){
			$items = $this->getItems($rule['rule']->id);
			$j = 0;
			foreach($items As $item){
				if($item->element_id == $data[_FF_DATA_ID]){
					$this->rules[$i]['items'][$j]['item'] = $item;
					$this->rules[$i]['items'][$j]['data'] = $data;
				}
				$j++;
			}
			$i++;
		}
	}
	
	public function handleCode($value, $code){
		if(trim($code) != ''){
			@eval($code);
		}
		return $value;
	}
	
	public function handleFinalizeCode($code){
		if(trim($code) != ''){
			@eval($code);
		}
	}
	
	public function commit(){
		foreach($this->rules As $rule){
			
			$valOk = true;
			$sql = '';
			
			if($rule['rule']->type == 'insert'){
				$sql = 'Insert Into '.$rule['rule']->reference_table.' (<keys>) Values (<values>)';	
			}
			else if($rule['rule']->type == 'update'){
				$sql = 'Update '.$rule['rule']->reference_table.' Set <keysvals> ';
			}
			
			if($rule['rule']->type == 'insert'){
				$keys = '';
				$values = '';
				foreach($rule['items'] As $item){
					$keys .= '`'.$item['item']->reference_column . '`,';
					$value = $item['data'][_FF_DATA_VALUE];
					try{
						$value = $this->handleCode($value, $item['item']->code);
					} catch(Exception $e){
						$valOk = false;
						break;
					}
					$values .= $this->db->Quote( $value ) . ',';
				}
				$keys = rtrim($keys,',');
				$values = rtrim($values,',');
				
				$sql = str_replace('<keys>',$keys,$sql);
				$sql = str_replace('<values>',$values,$sql);
				
				if($valOk && count($rule['items']) != 0){
					$this->db->setQuery($sql);
					$this->db->query();
					
					if(trim($rule['rule']->finalize_code) != ''){
						$this->handleFinalizeCode($rule['rule']->finalize_code);
					}
					
				}
			}
			else if($rule['rule']->type == 'update'){
				
				$criteria = $this->collectCriteria($rule['rule']->id);
				$keys = '';
				foreach($rule['items'] As $item){
					$value = $item['data'][_FF_DATA_VALUE];
					try{
						$value = $this->handleCode($value, $item['item']->code);
					} catch(Exception $e){
						$valOk = false;
						break;
					}
					$keys .= $item['item']->reference_column . '=' . $this->db->Quote( $value ) . ',';
				}
				$keys = rtrim($keys,',');
				
				$sql = str_replace('<keysvals>',$keys,$sql);
				
				$clauses = '';
				
				if(count($criteria['form']) != 0){
					
					foreach($criteria['form'] As $crit){
						
						if($clauses != ''){
							$clauses .= ' ' . $crit->andor . ' ';
						}
						
						$op = ' ';
						switch($crit->operator){
							case '%...%':
								$op = ' Like ' . $this->db->Quote( '%' . $this->data['data'.$crit->element_id][_FF_DATA_VALUE] . '%' );
								break;
							case '%...':
								$op = ' Like ' . $this->db->Quote( '%' . $this->data['data'.$crit->element_id][_FF_DATA_VALUE] );
								break;
							case '...%':
								$op = ' Like ' . $this->db->Quote( $this->data['data'.$crit->element_id][_FF_DATA_VALUE] . '%' );
								break;
							default: 
								$op = ' ' . $crit->operator . ' '. $this->db->Quote( $this->data['data'.$crit->element_id][_FF_DATA_VALUE] );
						}
						
						$clauses .= ' `' . $crit->reference_column . '` ' . $op;
					}
				}
				
				if(count($criteria['joomla']) != 0){
					
					foreach($criteria['joomla'] As $crit){
						
						if($clauses != ''){
							$clauses .= ' ' . $crit->andor . ' ';
						}
						
						$jobject = '';
						
						switch($crit->joomla_object){
							case 'Userid':
								$jobject = JFactory::getUser()->get('id', '');
								break;
							case 'Username':
								$jobject = JFactory::getUser()->get('username', '');
								break;
							case 'Language':
								$jobject = JFactory::getLanguage()->getName();
								break;
							case 'Date':
								$jobject = JFactory::getDate()->toMySQL();
								break;
						}
						
						$op = ' ';
						switch($crit->operator){
							case '%...%':
								$op = ' Like ' . $this->db->Quote( '%' . $jobject . '%' );
								break;
							case '%...':
								$op = ' Like ' . $this->db->Quote( '%' . $jobject );
								break;
							case '...%':
								$op = ' Like ' . $this->db->Quote( $jobject . '%' );
								break;
							default: 
								$op = ' ' . $crit->operator . ' '. $this->db->Quote( $jobject );
						}
						
						$clauses .= ' `' . $crit->reference_column . '` ' . $op;
					}
				}
				
				if(count($criteria['fixed']) != 0){
					
					foreach($criteria['fixed'] As $crit){
						
						if($clauses != ''){
							$clauses .= ' ' . $crit->andor . ' ';
						}
						
						$op = ' ';
						switch($crit->operator){
							case '%...%':
								$op = ' Like ' . $this->db->Quote( '%' . $crit->fixed_value . '%' );
								break;
							case '%...':
								$op = ' Like ' . $this->db->Quote( '%' . $crit->fixed_value );
								break;
							case '...%':
								$op = ' Like ' . $this->db->Quote( $crit->fixed_value . '%' );
								break;
							default: 
								$op = ' ' . $crit->operator . ' '. $this->db->Quote( $crit->fixed_value );
						}
						
						$clauses .= ' `' . $crit->reference_column . '` ' . $op;
					}
				}
				
				if($clauses != ''){
					$clauses = ' Where ' . $clauses;
				}
				
				$sql .= $clauses;
				
				if($valOk && count($rule['items']) != 0){
					$this->db->setQuery($sql);
					$this->db->query();
					
					if(trim($rule['rule']->finalize_code) != ''){
						$this->handleFinalizeCode($rule['rule']->finalize_code);
					}
				}
			}
		}
	}
	
	public function collectCriteria($ruleId){
		$crit['form'] = $this->getCriteria($ruleId);
		$crit['joomla'] = $this->getCriteriaJoomla($ruleId);
		$crit['fixed'] = $this->getCriteriaFixed($ruleId);
		return $crit;
	}
}