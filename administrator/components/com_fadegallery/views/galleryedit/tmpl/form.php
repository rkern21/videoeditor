<?php
/**
* Fade Javascript Image Gallery Joomla! 1.5 Native Component
* @version 1.2.7
* @author DesignCompass corp <admin@designcompasscorp.com>
* @link http://www.designcompasscorp.com
* @license GNU/GPL **/


defined('_JEXEC') or die('Restricted access');


?>


<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (trim(form.galleryname.value) == "") {
			alert( "<?php echo JText::_( 'You Must Provide a Gallery Name.', true ); ?>" );
		}
		else {
			submitform( pressbutton );
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	
	<fieldset class="adminform">
		<div style="position: relative;">
		<div style="position: absolute; right:0">
		<?php
		echo '<a href="http://www.designcompasscorp.com/index.php?option=com_content&view=article&id=508&Itemid=709" target="_blank"><img src="../components/com_fadegallery/images/compasslogo.png" border=0></a>';
		?>
		</div>
		<legend><?php echo JText::_( 'YouTube Gallery Details' ); ?></legend>
			<table class="admintable" cellspacing="1" width="100%">

				<?php if($this->row->id!=0):?>
				<tr>
					<td width="150" class="key">
						<label for="id">
							<?php echo JText::_( 'ID' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php echo $this->row->id; ?>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td width="150" class="key">
						<label for="galleryname">
							<?php echo JText::_( 'GALLERY NAME' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="galleryname" id="galleryname" class="inputbox" size="40" value="<?php echo $this->row->galleryname; ?>" />
					</td>
				</tr>
				
				<tr>
					<td colspan="2"><hr></td>
				</tr>
				<tr>
					<td width="150" class="key">
						<label for="folder">
							<?php echo JText::_( 'FOLDER' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="folder" id="folder" class="inputbox" size="40" value="<?php echo $this->row->folder; ?>" />
					</td>
				</tr>
				<tr>
					<td width="150" class="key" align="right">or</td>
						<td></td>
				</tr>
				<tr>
					<td width="150" class="key">
						<label for="filelist">
							<?php echo JText::_( 'FILE LIST' ); ?> (optional)
							
						</label><br>
					</td>
					<td>
						<input type="text" name="filelist" id="filelist" class="inputbox" size="150" value="<?php echo $this->row->filelist; ?>" />
						<?php echo JText::_( 'SEPARATED BY ' ).' ;'; ?>
						<i>Example: image1.jpg;img2.png,aug2010.jpg</i>
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr></td>
				</tr>
				
								
				<tr>
					<td width="150" class="key">
						<label for="width">
							<?php echo JText::_( 'WIDTH' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="width" id="width" class="inputbox" size="40" value="<?php echo $this->row->width; ?>" />
						px
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="height">
							<?php echo JText::_( 'HEIGHT' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="height" id="height" class="inputbox" size="40" value="<?php echo $this->row->height; ?>" />
						px
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="interval">
							<?php echo JText::_( 'INTERVAL' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="interval" id="interval" class="inputbox" size="40" value="<?php echo $this->row->interval; ?>" />
						<?php echo JText::_( 'IN MILSEC' ); ?>
						(<?php echo JText::_( 'DEFAULT' ).' 6000'; ?>)
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="fadetime">
							<?php echo JText::_( 'FADE TIME' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="fadetime" id="fadetime" class="inputbox" size="40" value="<?php echo $this->row->fadetime; ?>" />
						<?php echo JText::_( 'IN MILSEC' ); ?>
						(<?php echo JText::_( 'DEFAULT' ).' 2000'; ?>)
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="fadestep">
							<?php echo JText::_( 'FADE STEP' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="fadestep" id="fadestep" class="inputbox" size="40" value="<?php echo $this->row->fadestep; ?>" />
						<?php echo JText::_( 'IN MILSEC' ); ?>
						(<?php echo JText::_( 'DEFAULT' ).' 20'; ?>)
					</td>
				</tr>
		

				
				<tr>
					<td width="150" class="key">
						<label for="align">
							<?php echo JText::_( 'ALIGN' ); ?>
							
						</label><br>
					</td>
					<td>
						<?php
							$alignlist=array();
							$alignlist[]=array(name=>JText::_( 'LEFT' ), value=>"left");
							$alignlist[]=array(name=>JText::_( 'CENTER' ), value=>"center");
							$alignlist[]=array(name=>JText::_( 'RIGHT' ), value=>"right");
							
							
							echo JHTML::_('select.genericlist', $alignlist, 'align', '' ,'value','name', $this->row->align);
						 ?>
						
					</td>
				</tr>
				
				<tr>
					<td width="150" class="key">
						<label for="padding">
							<?php echo JText::_( 'PADDING' ); ?>
							
						</label><br>
					</td>
					<td>
						<input type="text" name="padding" id="padding" class="inputbox" size="40" value="<?php echo $this->row->padding; ?>" />
						px
					</td>
				</tr>
				<tr>
					<td width="150" class="key">
						<label for="cssstyle">
							<?php echo JText::_( 'CSS Style' ); ?>
							
						</label><br>
					</td>
					<td>
						<textarea cols=60 rows=5 name="cssstyle" id="cssstyle" class="inputbox" ><?php echo $this->row->cssstyle; ?></textarea>
						<br>
						EXAMPLE:  border: solid 1px #ff0000; //to set black solid border
					</td>
				</tr>
				
			</table>
		</div>
	</fieldset>
</div>
	<input type="hidden" name="option" value="com_fadegallery" />
	<input type="hidden" name="controller" value="galleries" />
	
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="task" value="" />


</form>