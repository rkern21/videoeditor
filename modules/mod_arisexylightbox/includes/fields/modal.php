<?php
/*
 * ARI Framework Lite
 *
 * @package		ARI Framework Lite
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldModal extends JFormField
{
	protected $type = 'Article';
	
	protected $hidden = true;
	
	protected function getInput()
	{
		JHtml::_('behavior.modal', 'a.modal');
		
		return '';
	}
	
	protected function getLabel()
	{
		return '';
	}
}
?>