<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.7.2
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class BFIntegratorHtml{
	
	public static function listRules($rules){
		
?>
<script>
function listItemTask( id, task ) {
    var f = document.adminForm;
    id.split('cb');
    f.task.value = task;
    f.publish_id.value = id.split('cb')[1];
    submitbutton(task);
    return false;
}
</script>

<form action="index.php" method="post">
<input type="hidden" name="option" value="com_breezingforms" />
<input type="hidden" name="act" value="integrate" />
<input type="hidden" name="task" value="" />
</form>

<?php
if(count($rules) == 0){
?>

<?php echo JText::_('No rules found');?>

<?php	 
}

?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
        	<th width="5">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rules ); ?>);" />
            </th>
            <th>
                <?php echo JText::_( 'Rulename' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Type' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Form' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Table' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'published' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k = 0;
    $cnt = count( $rules );
	for($i=0; $i < $cnt; $i++){
		$rule        = $rules[$i];
		$published   = JHTML::_('grid.published', $rule, $rule->id );
        $link = JRoute::_( 'index.php?option=com_breezingforms&act=integrate&task=edit&cid[]='. $rule->id );
        $checked     = JHTML::_( 'grid.id', $i, $rule->id );
    ?>
    	<tr class="<?php echo "row$k"; ?>">
    		
    		<td><?php echo $checked; ?></td>
    		
    		<td><a href="index.php?option=com_breezingforms&act=integrate&task=edit&id=<?php echo $rule->id; ?>"><?php echo htmlentities($rule->name, ENT_COMPAT, 'UTF-8') ?></a></td>
    		<td><a href="index.php?option=com_breezingforms&act=integrate&task=edit&id=<?php echo $rule->id; ?>"><?php echo htmlentities($rule->type, ENT_COMPAT, 'UTF-8') ?></a></td>
    		<td><a href="index.php?option=com_breezingforms&act=integrate&task=edit&id=<?php echo $rule->id; ?>"><?php echo htmlentities($rule->form_name, ENT_COMPAT, 'UTF-8') ?></a></td>
    		<td><a href="index.php?option=com_breezingforms&act=integrate&task=edit&id=<?php echo $rule->id; ?>"><?php echo htmlentities($rule->reference_table, ENT_COMPAT, 'UTF-8') ?></a></td>
    		<td><?php echo $published; ?></td>
    			
    	</tr>
    <?php
    	$k = 1 - $k;
	}
    ?>
    </table>
</div>
 
<input type="hidden" name="option" value="com_breezingforms" />
<input type="hidden" name="act" value="integrate" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="publish_id" value="-1" />

</form>

<br/>
<br/>

<?php	
		
	}
	
	public static function edit($rule, $items, $tables, $forms, $formElements, $criteriaForm, $criteriaJoomla, $criteriaFixed){
?> 

<h3><?php echo JText::_('Base Data') ?></h3>

<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
            <th width="300">
                <?php echo JText::_( 'Rulename' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Form' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Table' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Type' ); ?>
            </th>
        </tr> 
       </thead>
        <tr class="row0">
    		
    		<td><input type="text" <?php echo $rule != null ? ' disabled="disabled" ' : '' ?> name="rule_name" value="<?php echo $rule != null ? htmlentities($rule->name, ENT_COMPAT, 'UTF-8') : '' ?>"/></td>
    		<td>
    		<?php
    		$disabled = '';
			foreach($forms As $form){
				if(isset($rule) && $form->id == $rule->form_id){
					$disabled = ' disabled="disabled" ';
					break;
				}
			}
    		?>
    		<select name="form_id" <?php echo $disabled ?>>
    		<?php
			foreach($forms As $form){
				$selected = '';
				if(isset($rule) && $form->id == $rule->form_id){
					$selected = ' selected="selected"';
				}
				?>
				<option <?php echo $selected ?> value="<?php echo htmlentities($form->id, ENT_COMPAT, 'UTF-8') ?>"><?php echo htmlentities($form->name, ENT_COMPAT, 'UTF-8') ?></option>
				<?php
			}
    		?>
    		</select>
    		</td>
    		<td>
    		<?php
    		$disabled = '';
    		$settings = array();
			foreach($tables As $tableName => $tableSettings){
				$selected = '';
				if(isset($rule) && $tableName == $rule->reference_table){
					$settings = $tableSettings;
					$disabled = ' disabled="disabled" ';
					break;
				}
			}
    		?>
    		<select name="reference_table" <?php echo $disabled ?>>
    		<?php
    		$refTable = '';
			foreach($tables As $tableName => $tableSettings){
				$selected = '';
				if(isset($rule) && $tableName == $rule->reference_table){
					$refTable = $tableName;
					$selected = ' selected="selected"';
				}
				?>
				<option <?php echo $selected ?> value="<?php echo htmlentities($tableName, ENT_COMPAT, 'UTF-8') ?>"><?php echo htmlentities($tableName, ENT_COMPAT, 'UTF-8') ?></option>
				<?php
			}
    		?>
    		</select>
    		</td>
    		<td>
    			<?php 
    			if(isset($rule)){
    				echo $rule->type;
    			} else {
    				echo JText::_('Insert') . ' <input checked="checked" type="radio" name="type" value="insert"/> ' . JText::_('Update') . ' <input type="radio" name="type" value="update"/>';	
    			}
    			?>
    		</td>
    	</tr>           
    
   </table>
  </div>
  
  <input type="hidden" name="option" value="com_breezingforms" />
  <input type="hidden" name="act" value="integrate" />
  <input type="hidden" name="task" value="update" />
  
 </form>

<?php 

if($rule != null){

?>

<br/>
<h3><?php echo JText::_('Data Integration') ?></h3>

<script>
function listItemTask( id, task ) {
    var f = document.addItemForm;
    id.split('cb');
    f.publish_id.value = id.split('cb')[1];
    
    if(task == 'publish' || task== 'unpublish'){
    	f.task.value = 'pub';
    	f.pub.value = task;
    	task = 'edit';
    }
    
    //f.task.value = task;
    f.submit();
    return false;
}
</script>

<form action="index.php?option=com_breezingforms&act=integrate" method="post" name="addItemForm">
<input type="hidden" name="publish_id" value="-1" />
<input type="hidden" name="task" value="addItem" />
<input type="hidden" name="id" value="<?php echo $rule->id ?>" />
<input type="hidden" name="pub" value="" />
<table class="adminlist">
    <thead>
        <tr>
            <th width="300">
                <?php echo JText::_( 'Form Element (incoming)' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Copy To' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Database Field (outgoing)' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( '' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Publish' ); ?>
            </th>
        </tr> 
       </thead>

	<tr class="<?php echo "row0"; ?>">
		<td>
		<select name="element_id">
		<?php 
		foreach($formElements As $formElement){
			if($formElement->name != 'bfFakeName' && $formElement->name != 'bfFakeName2' && $formElement->name != 'bfFakeName3' && $formElement->name != 'bfFakeName4'){
			?>
			<option value="<?php echo $formElement->id ?>"><?php echo htmlentities($formElement->name, ENT_COMPAT, 'UTF-8') ?> (<?php echo htmlentities($formElement->type, ENT_COMPAT, 'UTF-8') ?>)</option>
			<?php
			}
		}
		?>
		</select>
		</td>
	    <td align="center"><?php echo htmlentities('=>') ?></td>
	    <td>
	    <select name="reference_column">
		<?php 
		foreach($tables[$refTable] As $fieldName => $fieldSettings){
			?>
			<option value="<?php echo htmlentities($fieldName, ENT_COMPAT, 'UTF-8') ?>"><?php echo htmlentities($fieldName, ENT_COMPAT, 'UTF-8') ?> (<?php echo htmlentities($fieldSettings, ENT_COMPAT, 'UTF-8') ?>)</option>
			<?php
		}
		?>
		</select>
	    </td>
	    <td colspan="2"><a href="javascript:document.addItemForm.submit();"><?php echo JText::_('add') ?></a></td>
    </tr>

	<?php
	$k = 1;
	
	foreach($items As $item){
		$published   = JHTML::_('grid.published', $item, $item->id );
	?>
		<tr class="<?php echo "row$k"; ?>">
    		
    		<td><?php echo htmlentities($item->element_name, ENT_COMPAT, 'UTF-8') ?> (<?php echo htmlentities($item->element_type, ENT_COMPAT, 'UTF-8') ?>)</td>
    		<td align="center"><?php echo htmlentities('=>') ?></td>
    		<td><?php echo htmlentities($item->reference_column, ENT_COMPAT, 'UTF-8') ?> (<?php echo $settings[$item->reference_column] ?>)</td>
    		<td>
    		<span id="code<?php echo $item->id ?>" style="display:none">
    			<textarea id="codeText<?php echo $item->id ?>" style="width:100%;height:200px;" name="code"><?php echo htmlentities($item->code, ENT_COMPAT, 'UTF-8') ?></textarea>
    			<br/>
    			<a href="javascript:document.saveCodeForm.itemId.value=<?php echo $item->id ?>;document.saveCodeForm.code.value=document.getElementById('codeText<?php echo $item->id ?>').value;document.saveCodeForm.submit();"><?php echo JText::_('Save') ?></a> |
    		</span>
    		<a href="javascript:showCode(<?php echo $item->id ?>)"><?php echo JText::_('Code') ?></a>
    		|
    		<a href="index.php?option=com_breezingforms&act=integrate&task=removeItem&itemId=<?php echo $item->id ?>&id=<?php echo $rule->id ?>"><?php echo JText::_('Remove') ?></a></td>
    		<td><?php echo $published; ?></td>
    	</tr>
	<?php
		$k = 1 - $k;
	}
	?>
</table>
</form>

<form action="index.php?option=com_breezingforms&act=integrate" method="post" name="saveCodeForm">
<input type="hidden" name="task" value="saveCode" />
<input type="hidden" name="code" value="" />
<input type="hidden" name="itemId" value="-1" />
<input type="hidden" name="id" value="<?php echo $rule->id ?>" />
</form>

<script>
function showCode(id){
	if(document.getElementById('code'+id).style.display == 'none')
		document.getElementById('code'+id).style.display='';
	else
		document.getElementById('code'+id).style.display='none';
}
</script>

<?php
if($rule->type == 'update'){
?>

<br/>
<h3><?php echo JText::_('Update Criteria - Form') ?></h3>

<form action="index.php?option=com_breezingforms&act=integrate" method="post" name="addCriteriaForm">
<input type="hidden" name="publish_id" value="-1" />
<input type="hidden" name="task" value="addCriteria" />
<input type="hidden" name="id" value="<?php echo $rule->id ?>" />
<table class="adminlist">
    <thead>
        <tr>
            <th width="300">
            	<?php echo JText::_( 'Database Field Value' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Operation' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Form Element Value' ); ?>
            </th>
            <th width="100">
                <?php echo JText::_( 'And/Or' ); ?>
            </th>
            <th>
                <?php echo JText::_( '' ); ?>
            </th>
        </tr> 
       </thead>

	<tr class="<?php echo "row0"; ?>">
		<td>
		<select name="reference_column">
		<?php 
		foreach($tables[$refTable] As $fieldName => $fieldSettings){
			?>
			<option value="<?php echo htmlentities($fieldName, ENT_COMPAT, 'UTF-8') ?>"><?php echo htmlentities($fieldName, ENT_COMPAT, 'UTF-8') ?> (<?php echo htmlentities($fieldSettings, ENT_COMPAT, 'UTF-8') ?>)</option>
			<?php
		}
		?>
		</select>
		
		</td>
	    <td align="center">
	    
	    	<select name="operator">
	    		<option value="<?php echo htmlentities('=') ?>">equals</option>
	    		<option value="<?php echo htmlentities('<>') ?>">not equal</option>
	    		<option value="<?php echo htmlentities('>') ?>">greater than</option>
	    		<option value="<?php echo htmlentities('<') ?>">less than</option>
	    		<option value="<?php echo htmlentities('>=') ?>">equals or greater than</option>
	    		<option value="<?php echo htmlentities('<=') ?>">equals or less than</option>
	    		<option value="<?php echo htmlentities('%...%') ?>">in value</option>
	    		<option value="<?php echo htmlentities('%...') ?>">starts with</option>
	    		<option value="<?php echo htmlentities('...%') ?>">ends with</option>	    		
	    	</select>
	    
	    </td>
	    <td>
	    <select name="element_id">
		<?php 
		foreach($formElements As $formElement){
			if($formElement->name != 'bfFakeName' && $formElement->name != 'bfFakeName2' && $formElement->name != 'bfFakeName3' && $formElement->name != 'bfFakeName4'){
			?>
			<option value="<?php echo $formElement->id ?>"><?php echo htmlentities($formElement->name, ENT_COMPAT, 'UTF-8') ?> (<?php echo htmlentities($formElement->type, ENT_COMPAT, 'UTF-8') ?>)</option>
			<?php
			}
		}
		?>
		</select>
	    </td>
	    <td><?php echo JText::_('A N D') ?> <input type="radio" name="andor" value="AND" checked="checked" /> <?php echo JText::_('O R') ?> <input type="radio" name="andor" value="OR" /></td>
	    <td colspan="2"><a href="javascript:document.addCriteriaForm.submit();"><?php echo JText::_('add') ?></a></td>
    </tr>

	<?php
	$k = 1;
	
	foreach($criteriaForm As $criteria){
	?>
		<tr class="<?php echo "row$k"; ?>">
    		
    		<td><?php echo htmlentities($criteria->reference_column, ENT_COMPAT, 'UTF-8') ?> (<?php echo $settings[$criteria->reference_column] ?>)</td>
    		<td align="center"><?php echo htmlentities($criteria->operator, ENT_COMPAT, 'UTF-8') ?></td>
			<td><?php echo htmlentities($criteria->element_name, ENT_COMPAT, 'UTF-8') ?> (<?php echo htmlentities($criteria->element_type, ENT_COMPAT, 'UTF-8') ?>)</td>    		
    		<td><?php echo htmlentities($criteria->andor, ENT_COMPAT, 'UTF-8') ?> </td>
    		<td><a href="index.php?option=com_breezingforms&act=integrate&task=removeCriteria&criteriaId=<?php echo $criteria->id ?>&id=<?php echo $rule->id ?>"><?php echo JText::_('Remove') ?></a></td>
    	</tr>
	<?php
		$k = 1 - $k;
	}
	?>
</table>
</form>

<br/>
<h3><?php echo JText::_('Update Criteria - Joomla!') ?></h3>

<form action="index.php?option=com_breezingforms&act=integrate" method="post" name="addCriteriaJoomlaForm">
<input type="hidden" name="publish_id" value="-1" />
<input type="hidden" name="task" value="addCriteriaJoomla" />
<input type="hidden" name="id" value="<?php echo $rule->id ?>" />
<table class="adminlist">
    <thead>
        <tr>
            <th width="300">
            	<?php echo JText::_( 'Database Field Value' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Operation' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Joomla Object Value' ); ?>
            </th>
            <th width="100">
                <?php echo JText::_( 'And/Or' ); ?>
            </th>
            <th>
                <?php echo JText::_( '' ); ?>
            </th>
        </tr> 
       </thead>

	<tr class="<?php echo "row0"; ?>">
		<td>
		<select name="reference_column">
		<?php 
		foreach($tables[$refTable] As $fieldName => $fieldSettings){
			?>
			<option value="<?php echo htmlentities($fieldName, ENT_COMPAT, 'UTF-8') ?>"><?php echo htmlentities($fieldName, ENT_COMPAT, 'UTF-8') ?> (<?php echo htmlentities($fieldSettings, ENT_COMPAT, 'UTF-8') ?>)</option>
			<?php
		}
		?>
		</select>
		
		</td>
	    <td align="center">
	    
	    	<select name="operator">
	    		<option value="<?php echo htmlentities('=') ?>">equals</option>
	    		<option value="<?php echo htmlentities('<>') ?>">not equal</option>
	    		<option value="<?php echo htmlentities('>') ?>">greater than</option>
	    		<option value="<?php echo htmlentities('<') ?>">less than</option>
	    		<option value="<?php echo htmlentities('>=') ?>">equals or greater than</option>
	    		<option value="<?php echo htmlentities('<=') ?>">equals or less than</option>
	    		<option value="<?php echo htmlentities('%...%') ?>">in value</option>
	    		<option value="<?php echo htmlentities('%...') ?>">starts with</option>
	    		<option value="<?php echo htmlentities('...%') ?>">ends with</option>	    		
	    	</select>
	    
	    </td>
	    <td>
	    <select name="joomla_object">

			<option value="Userid"><?php echo JText::_('Userid') ?></option>
			<option value="Username"><?php echo JText::_('Username') ?></option>
			<option value="Language"><?php echo JText::_('Language') ?></option>
			<option value="Date"><?php echo JText::_('Date') ?></option>

		</select>
	    </td>
	    <td><?php echo JText::_('A N D') ?> <input type="radio" name="andor" value="AND" checked="checked" /> <?php echo JText::_('O R') ?> <input type="radio" name="andor" value="OR" /></td>
	    <td colspan="2"><a href="javascript:document.addCriteriaJoomlaForm.submit();"><?php echo JText::_('add') ?></a></td>
    </tr>

	<?php
	$k = 1;
	
	foreach($criteriaJoomla As $criteria){
	?>
		<tr class="<?php echo "row$k"; ?>">
    		
    		<td><?php echo htmlentities($criteria->reference_column, ENT_COMPAT, 'UTF-8') ?> (<?php echo $settings[$criteria->reference_column] ?>)</td>
    		<td align="center"><?php echo htmlentities($criteria->operator, ENT_COMPAT, 'UTF-8') ?></td>
			<td><?php echo htmlentities($criteria->joomla_object, ENT_COMPAT, 'UTF-8') ?></td>    		
    		<td><?php echo htmlentities($criteria->andor, ENT_COMPAT, 'UTF-8') ?> </td>
    		<td><a href="index.php?option=com_breezingforms&act=integrate&task=removeCriteriaJoomla&criteriaId=<?php echo $criteria->id ?>&id=<?php echo $rule->id ?>"><?php echo JText::_('Remove') ?></a></td>
    	</tr>
	<?php
		$k = 1 - $k;
	}
	?>
</table>
</form>

<br/>
<h3><?php echo JText::_('Update Criteria - Fixed') ?></h3>

<form action="index.php?option=com_breezingforms&act=integrate" method="post" name="addCriteriaFixedForm">
<input type="hidden" name="publish_id" value="-1" />
<input type="hidden" name="task" value="addCriteriaFixed" />
<input type="hidden" name="id" value="<?php echo $rule->id ?>" />
<table class="adminlist">
    <thead>
        <tr>
            <th width="300">
            	<?php echo JText::_( 'Database Field Value' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Operation' ); ?>
            </th>
            <th width="300">
                <?php echo JText::_( 'Fixed Value' ); ?>
            </th>
            <th width="100">
                <?php echo JText::_( 'And/Or' ); ?>
            </th>
            <th>
                <?php echo JText::_( '' ); ?>
            </th>
        </tr> 
       </thead>

	<tr class="<?php echo "row0"; ?>">
		<td>
		<select name="reference_column">
		<?php 
		foreach($tables[$refTable] As $fieldName => $fieldSettings){
			?>
			<option value="<?php echo htmlentities($fieldName, ENT_COMPAT, 'UTF-8') ?>"><?php echo htmlentities($fieldName, ENT_COMPAT, 'UTF-8') ?> (<?php echo htmlentities($fieldSettings, ENT_COMPAT, 'UTF-8') ?>)</option>
			<?php
		}
		?>
		</select>
		
		</td>
	    <td align="center">
	    
	    	<select name="operator">
	    		<option value="<?php echo htmlentities('=') ?>">equals</option>
	    		<option value="<?php echo htmlentities('<>') ?>">not equal</option>
	    		<option value="<?php echo htmlentities('>') ?>">greater than</option>
	    		<option value="<?php echo htmlentities('<') ?>">less than</option>
	    		<option value="<?php echo htmlentities('>=') ?>">equals or greater than</option>
	    		<option value="<?php echo htmlentities('<=') ?>">equals or less than</option>
	    		<option value="<?php echo htmlentities('%...%') ?>">in value</option>
	    		<option value="<?php echo htmlentities('%...') ?>">starts with</option>
	    		<option value="<?php echo htmlentities('...%') ?>">ends with</option>	    		
	    	</select>
	    
	    </td>
	    <td>
	    	<input style="width:100%" type="text" name="fixed_value" value=""/>
	    </td>
	    <td><?php echo JText::_('A N D') ?> <input type="radio" name="andor" value="AND" checked="checked" /> <?php echo JText::_('O R') ?> <input type="radio" name="andor" value="OR" /></td>
	    <td colspan="2"><a href="javascript:document.addCriteriaFixedForm.submit();"><?php echo JText::_('add') ?></a></td>
    </tr>

	<?php
	$k = 1;
	
	foreach($criteriaFixed As $criteria){
	?>
		<tr class="<?php echo "row$k"; ?>">
    		
    		<td><?php echo htmlentities($criteria->reference_column, ENT_COMPAT, 'UTF-8') ?> (<?php echo $settings[$criteria->reference_column] ?>)</td>
    		<td align="center"><?php echo htmlentities($criteria->operator, ENT_COMPAT, 'UTF-8') ?></td>
			<td><?php echo htmlentities($criteria->fixed_value, ENT_COMPAT, 'UTF-8') ?> </td>    		
    		<td><?php echo htmlentities($criteria->andor, ENT_COMPAT, 'UTF-8') ?> </td>
    		<td><a href="index.php?option=com_breezingforms&act=integrate&task=removeCriteriaFixed&criteriaId=<?php echo $criteria->id ?>&id=<?php echo $rule->id ?>"><?php echo JText::_('Remove') ?></a></td>
    	</tr>
	<?php
		$k = 1 - $k;
	}
	?>
</table>
</form>

<?php
}	
		}
if($rule != null){
?>

<br/>
<h3><?php echo JText::_('Finalize Code') ?></h3>

<form action="index.php?option=com_breezingforms&act=integrate" method="post" name="saveFinalizeCodeForm">
<input type="hidden" name="publish_id" value="-1" />
<input type="hidden" name="task" value="saveFinalizeCode" />
<input type="hidden" name="id" value="<?php echo $rule->id ?>" />
<textarea id="finalizeCodeText" style="width:100%;height:100px;" name="finalizeCode"><?php echo htmlentities($rule->finalize_code, ENT_COMPAT, 'UTF-8') ?></textarea>
<br/>
<a href="javascript:document.saveFinalizeCodeForm.submit();"><?php echo JText::_('Save') ?></a>
</form>

<?php
}
	}
}