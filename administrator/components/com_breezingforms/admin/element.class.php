<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.4.6
* @package BreezingForms
* @copyright (C) 2008-2010 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/element.html.php');

class facileFormsElement
{
	function edit($option, $tabpane, $pkg, $form, $page, $ids, $newtype)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsElements($database);
		if ($newtype=='')
			$row->load($ids[0]);
		else {
			$row->form = $form;
			$row->page = $page;
			$row->published = 1;
			$row->logging = 1;
			$row->posx = 0;
			$row->posy = 0;
			$row->width = 0;
			$row->height = 0;
			$row->flag1 = 0;
			$row->flag2 = 0;
			$row->script1flag1 = 1;
			$row->script1flag2 = 0;
			$row->script2flag1 = 1;
			$row->script2flag2 = 0;
			$row->script2flag3 = 0;
			$row->script2flag4 = 0;
			$row->script2flag5 = 0;
			$database->setQuery(
				"select max(ordering)+1 from #__facileforms_elements ".
				 "where form=$form and page=$page"
			);
			$row->ordering = $database->loadResult();

			switch ($newtype) {
				case 'Rectangle':
					$row->type      = $newtype;
					$row->logging   = 0;
					$row->posx      = 10;
					$row->posy      = 10;
					$row->width     = 100;
					$row->height    = 50;
					$row->data1     = '1px solid black';    // border
					$row->data2     = '#eeeeee';            // background color
					break;
				case 'Hidden Input':
					$row->type      = $newtype;
					$row->data1     = 'Hiddeninput';
					break;
				case 'Image':
					$row->type      = $newtype;
					$row->logging   = 0;
					$row->posx      = 10;
					$row->posy      = 50;
					$row->data1     = '{ff_images}/pizzashop/margherita.jpg';
					$row->data2     = 'Margherita';
					break;
				case 'Tooltip':
					$row->type      = $newtype;
					$row->logging   = 0;
					$row->posx      = 100;
					$row->posy      = 50;
					$row->flag1     = 0; // 0-tooltip 1-warning 2-custom
					$row->data2     = 'Some <em>hint</em> or <strong>warning</strong> for the user';
					break;
				case 'Checkbox':
					$row->type      = $newtype;
					$row->posx      = 10;
					$row->posy      = 50;
					$row->data1     = 'cb';
					$row->data2     = 'Checkbox';
					break;
				case 'Radio Button':
					$row->type      = $newtype;
					$row->posx      = 10;
					$row->posy      = 50;
					$row->data1     = 'rb';
					$row->data2     = 'Radiobutton';
					break;
				case 'Regular Button':
					$row->type      = $newtype;
					$row->class2    = 'button';
					$row->logging   = 0;
					$row->posx      = 10;
					$row->posy      = 100;
					$row->data2     = 'Regularbutton';
					break;
				case 'Graphic Button':
					$row->type      = $newtype;
					$row->class2    = 'button';
					$row->logging   = 0;
					$row->posx      = 10;
					$row->posy      = 40;
					$row->flag1     = 1; // caption below
					$row->data1     = '{ff_images}/icons/movert_f2.png';
					$row->data2     = 'Next';
					break;
				case 'Icon':
					$row->type      = $newtype;
					$row->logging   = 0;
					$row->posx      = 10;
					$row->posy      = 80;
					$row->flag1     = 1; // caption below
					$row->data1     = '{mossite}/components/com_breezingforms/images/next.png';
					$row->data3     = '{mossite}/components/com_breezingforms/images/next_f2.png';
					$row->data2     = '<font size="2"><strong>Next</strong></font>';
					break;
				case 'Text':
					$row->type      = $newtype;
					$row->class2    = 'inputbox';
					$row->posx      = 10;
					$row->posy      = 10;
					$row->width     = 6;
					$row->height    = 6;
					break;
				case 'File Upload':
					$row->type      = $newtype;
					$row->class2    = 'inputbox';
					$row->posx      = 10;
					$row->posy      = 30;
					$row->width     = 50;
					$row->height    = 2000000;
					$row->data1     = '{ff_uploads}';
					$row->data2     = 'text/*,application/zip';
					break;
				case 'Textarea':
					$row->type      = $newtype;
					$row->class2    = 'inputbox';
					$row->posx      = 10;
					$row->posy      = 20;
					$row->width     = 20;
					$row->height    = 15;
					break;
				case 'Select List':
					$row->type      = $newtype;
					$row->class2    = 'inputbox';
					$row->posx      = 50;
					$row->posy      = 30;
					$row->data1     = 1;
					$row->data2     = "1;Select Color;''\r\n".
									  "0;Red;red\r\n".
									  "0;Green;green\r\n".
									  "0;Blue;blue\r\n";
					break;
				case 'Query List':
					$row->type      = $newtype;
					$row->class2    = 'moduletable';
					$row->posx      = 5;
					$row->posxmode  = 1;
					$row->posy      = 100;
					$row->width     = 90;
					$row->widthmode = 1;
					$row->height    = 15;
					$row->flag1     = 1; // show header
					$row->flag2     = 1; // selection checkboxes
					$row->data1     = "0\n".    // border
									  "0\n".    // cellspacing
									  "0\n".    // cellpadding
									  "\n".     // <tr(header)> class
									  "\n".     // <tr(row1)> class
									  "\n".     // <tr(row2)> class
									  "\n";     // <tr(footer)> class
					break;
				case 'Captcha':
					$row->type      = 'Captcha';
					$row->posx      = 10;
					$row->posy      = 20;
					break;
				default: // assume Static Text/HTML
					$row->type = 'Static Text/HTML';
					$row->logging   = 0;
					$row->posx      = 10;
					$row->posy      = 20;
					$row->data1     = 'The text to display';
					break;
			} // switch
		} // if
		$lists = array();

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_scripts ".
			"where published=1 and type='Element Init' ".
			"order by text, id desc"
		);
		$lists['scripts1'] = $database->loadObjectList();
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} // if

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_scripts ".
			"where published=1 and type='Element Action' ".
			"order by text, id desc"
		);
		$lists['scripts2'] = $database->loadObjectList();
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} // if

		$database->setQuery(
			"select id, concat(package,'::',name) as text ".
			"from #__facileforms_scripts ".
			"where published=1 and type='Element Validation' ".
			"order by text, id desc"
		);
		$lists['scripts3'] = $database->loadObjectList();
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} // if

		$order = JHTML::_('list.genericordering',
					 "select ordering as value, title as text ".
					   "from #__facileforms_elements ".
					  "where form=$form and page=$page ".
					  "order by ordering"
				 );
		$lists['ordering'] =
			JHTML::_('select.genericlist',
				$order, 'ordering', 'class="inputbox" size="1" style="z-index:1" ',
				'value', 'text', intval($row->ordering)
			);

		HTML_facileFormsElement::edit($option, $tabpane, $pkg, $row, $lists);
	} // edit

	function newElement($option, $pkg, $form, $page)
	{
		HTML_facileFormsElement::newitem($option, $pkg, $form, $page);
	} // newElement

	function save($option, $pkg, $form, $page)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsElements($database);

		// bind it to the table
		if (!$row->bind($_POST)) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		
		if ($row->script1flag1==null) $row->script1flag1=0;
		if ($row->script1flag2==null) $row->script1flag2=0;
		if ($row->script2flag1==null) $row->script2flag1=0;
		if ($row->script2flag2==null) $row->script2flag2=0;
		if ($row->script2flag3==null) $row->script2flag3=0;
		if ($row->script2flag4==null) $row->script2flag4=0;
		if ($row->script2flag5==null) $row->script2flag5=0;

		// store it in the db
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		$row->reorder( "form=$form and page=$page" );
		JFactory::getApplication()->redirect(
			"index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg",
			BFText::_('COM_BREEZINGFORMS_ELEMENTS_SAVED'));
	} // save

	function cancel($option, $pkg, $form, $page)
	{
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // cancel

	function del($option, $pkg, $form, $page, $ids)
	{
		global $database;
		$database = JFactory::getDBO();
		$ids = implode(',', $ids);
		$database->setQuery("delete from #__facileforms_elements where form=$form and page=$page and id in ($ids)");
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		} // if
		$database->setQuery("delete from #__facileforms_forms where id in ($forms)");
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		} // if
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // del

	function getDestination($option, $pkg, $form, $page, $ids, $action)
	{
		global $database;
		$database = JFactory::getDBO();
		$fff = array();

		$database->setQuery(
			"select * from #__facileforms_forms ".
			"where id = $form order by ordering"
		);
		$rows = $database->loadObjectList();
		$package = '';
		if (count($rows)) foreach ($rows as $row) {
			$package = $row->package;
			for ($p = 1; $p <= $row->pages; $p++)
				$fff[] = JHTML::_('select.option', $row->id.','.$p, $row->package.'::'.$row->name.' / '.BFText::_('COM_BREEZINGFORMS_ELEMENTS_PAGE').' '.$p);
		} // for

		$database->setQuery(
			"select * from #__facileforms_forms ".
			"where package= '$package' and id!=$form ".
			"order by ordering"
		);
		$rows = $database->loadObjectList();
		if (count($rows)) foreach ($rows as $row)
			for ($p = 1; $p <= $row->pages; $p++)
				$fff[] = JHTML::_('select.option', $row->id.','.$p, $row->package.'::'.$row->name.' / '.BFText::_('COM_BREEZINGFORMS_ELEMENTS_PAGE').' '.$p);

		$database->setQuery(
			"select * from #__facileforms_forms ".
			"where package!= '$package' ".
			"order by package, ordering"
		);
		$rows = $database->loadObjectList();
		if (count($rows)) foreach ($rows as $row)
			for ($p = 1; $p <= $row->pages; $p++)
				$fff[] = JHTML::_('select.option', $row->id.','.$p, $row->package.'::'.$row->name.' / '.BFText::_('COM_BREEZINGFORMS_ELEMENTS_PAGE').' '.$p);

		$sellist =
			JHTML::_('select.genericlist',
				$fff, 'destination', 'class="inputbox" size="15"',
				'value', 'text', $form.','.$page
			);
		HTML_facileFormsElement::getDestination($option, $pkg, $form, $page, $ids, $sellist, $action);
	} // getDestination

	function copy($option, $pkg, $form, $page, $ids)
	{
		global $database;
		$database = JFactory::getDBO();
		$destination = explode( ',', JRequest::getVar('destination',''));
		list($newform, $newpage ) = $destination;
		if (!$newform && !$newpage)
			JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg&mosmsg=".BFText::_('COM_BREEZINGFORMS_ELEMENTS_ANERROR'));
		$total = count($ids);
		$row = new facileFormsElements($database);
		if (count($ids)) foreach ($ids as $id) {
			$row->load( intval($id) );
			$row->id       = NULL;
			$row->form     = $newform;
			$row->page     = $newpage;
			$row->ordering = 999999;
			$row->store();
			$row->reorder( 'form='.$newform.' and page = '.$newpage );
		} // foreach
		$msg = $total. ' '.BFText::_('COM_BREEZINGFORMS_ELEMENTS_COPIED').$newform.', '.BFText::_('COM_BREEZINGFORMS_ELEMENTS_PAGE2').$newpage;
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg&mosmsg=$msg");
	} // copy

	function move($option, $pkg, $form, $page, $ids)
	{
		global $database;
		$database = JFactory::getDBO();
		$destination = explode( ',', JRequest::getVar('destination',''));
		list($newform, $newpage ) = $destination;
		if (!$newform && !$newpage)
			JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg&mosmsg=".BFText::_('COM_BREEZINGFORMS_ELEMENTS_ANERROR'));
		if ($newform != $form || $newpage != $page) {
			$total = count($ids);
			$row = new facileFormsElements($database);
			if (count($ids)) foreach ($ids as $id) {
				$row->load( intval($id) );
				$row->form = $newform;
				$row->page = $newpage;
				$row->ordering = 999999;
				$row->store();
				$row->reorder( 'form='.$newform.' and page = '.$newpage );
			} // foreach
			$msg = $total. ' '.BFText::_('COM_BREEZINGFORMS_ELEMENTS_MOVED').$newform.', '.BFText::_('COM_BREEZINGFORMS_ELEMENTS_PAGE2').$newpage;
		} else
			$msg = BFText::_('COM_BREEZINGFORMS_ELEMENTS_NOTMOVED');
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg&mosmsg=$msg");
	} // move

	function sort($option, $pkg, $form, $page)
	{
		$rows = _ff_select("select * from `#__facileforms_forms` where id = $form");
		$width = $height = 600;
		if (count($rows)) {
			$f = $rows[0];
			if ($f->widthmode ==0) $width  = $f->width;  else $width = $f->prevwidth;
			if ($f->heightmode==0) $height = $f->height;
			if ($width < 1) $width = 600;
			if ($height < 1) $height = 600;
		} // if
		$rows = _ff_select(
			"select id ".
			"from `#__facileforms_elements` ".
			"where form=$form and page=$page ".
			"order by ".
				"if(`type`='Hidden Input',1,0), ".
				"if(`type`='Hidden Input',0,if(posy<0,$height+if(posymode=0,posy,(posy*$height)/100),if(posymode=0,posy,(posy*$height)/100))), ".
				"if(`type`='Hidden Input',0,if(posx<0,$width+if(posxmode=0,posx,(posx*$width)/100),if(posxmode=0,posx,(posx*$width)/100))), ".
				"name, title, id"
		);
		$o = 1;
		if (count($rows)) foreach ($rows as $row) {
			_ff_query("update `#__facileforms_elements` set ordering=$o where id=$row->id");
			$o++;
		} // foreach
		JFactory::getApplication()->redirect(
			"index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg",
			BFText::_('COM_BREEZINGFORMS_ELEMENTS_SORTED'));
	} // save

	function movePos($option, $pkg, $form, $page, $ids, $task)
	{
		global $ff_config, $database;
		$database = JFactory::getDBO();
		$ff_config->movepixels = JRequest::getVar('movepixels', '');
		$ff_config->store();
		$pos = explode(',', JRequest::getVar('movepositions', ''));
		$cnt = floor(count($pos)/3);
		$elem = new facileFormsElements($database);
		$p = 0;
		for ($i = 0; $i < $cnt; $i++) {
			$elem->load($pos[$p++]);
			$elem->posx = $pos[$p++];
			$elem->posy = $pos[$p++];
			$elem->store();
		} // for
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // movePos

	function gridshow($option, $pkg, $form, $page, $ids, $task)
	{
		global $ff_config, $database;
		$database = JFactory::getDBO();
		$ff_config->gridshow = JRequest::getVar('gridshow', 0);
		$ff_config->store();
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg&checkedIds=".implode(',', $ids));
	} // gridshow

	function publish($option, $pkg, $form, $page, $ids, $publish)
	{
		global $database, $my;
		$database = JFactory::getDBO();
		$ids = implode( ',', $ids );
		$database->setQuery(
			"update #__facileforms_elements set published=$publish where form=$form and page=$page and id in ($ids)"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // publish

	function order($option, $pkg, $form, $page, $ids, $inc)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsElements($database);
		$row->load($ids[0]);
		$row->move($inc, "form=$form and page=$page" );
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // order

	function orderWithoutRedirect($option, $pkg, $form, $page, $positions)
	{
		if(trim($positions) != ''){

			$database = JFactory::getDBO();
			
			$expl = explode(',',$positions);
			$explSize = count($expl);
			
			for($i = 0; $i < $explSize;$i++){
				
				$idAndPos = explode(':',$expl[$i]);
				
				if(count($idAndPos) == 2 && is_numeric($idAndPos[0]) && is_numeric($idAndPos[1])){
					
					$database->setQuery("Update #__facileforms_elements Set ordering = '".intval($idAndPos[1])."' Where id = '".intval($idAndPos[0])."' And form=$form and page=$page");
					$database->query();
					
					//$jtable = new BFTableElements($database);
					//$jtable->reorder("form=$form and page=$page");
				}
			}
		}
		
	} // orderWithoutRedirect
	
	function listitems($option, $pkg, $form, $page, $prevmode)
	{
		global $database;
		$database = JFactory::getDBO();
		$database->setQuery(
			"select * from #__facileforms_elements where form=$form and page=$page order by ordering"
		);
		$rows = $database->loadObjectList();
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} // if
		$formrow = new facileFormsForms($database);
		$formrow->load($form);
		$checkedIds = explode(',', JRequest::getVar( 'checkedIds', ''));
		HTML_facileFormsElement::listitems($option, $pkg, $formrow, $page, $rows, $prevmode, $checkedIds);
	} // listitems

	function addPageBehind($option, $pkg, $form, $page)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsForms($database);
		$row->load($form);
		if ($page < $row->pages) {
			$database->setQuery(
				"update #__facileforms_elements set page=page+1 where form=$form and page>$page"
			);
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
		} // if
		$row->pages++;
		$row->store();
		$page++;
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // addPageBehind

	function addPageBefore($option, $pkg, $form, $page)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsForms($database);
		$row->load($form);
		$database->setQuery(
			"update #__facileforms_elements set page=page+1 where form=$form and page>=$page"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		$row->pages++;
		$row->store();
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // addPageBefore

	function delPage($option, $pkg, $form, $page)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsForms($database);
		$row->load($form);
		$database->setQuery(
			"delete from #__facileforms_elements where form=$form and page=$page"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		$database->setQuery(
			"update #__facileforms_elements set page=page-1 where form=$form and page>$page"
		);
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		} // if
		$row->pages--;
		$row->store();
		if ($page > $row->pages) $page--;
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // delPage

	function getPageDestination($option, $pkg, $form, $page)
	{
		global $database;
		$database = JFactory::getDBO();
		$row = new facileFormsForms($database);
		$row->load($form);
		$lst = array();
		for($p = 1; $p <= $row->pages; $p++)
			$lst[] = JHTML::_('select.option', $p, BFText::_('COM_BREEZINGFORMS_ELEMENTS_PAGE').' '.$p);
		$sellist =
			JHTML::_('select.genericlist',
				$lst, 'destination', 'class="inputbox" size="15"',
				'value', 'text', $page
			);
		HTML_facileFormsElement::getPagedest($option, $pkg, $form, $page, $sellist);
	} // getPageDestination

	function movePage($option, $pkg, $form, $page)
	{
		global $database;
		$database = JFactory::getDBO();
		$newpage = JRequest::getVar('destination','');
		if ($newpage != $page) {
			$database->setQuery(
				"update #__facileforms_elements set page=0 where form=$form and page=$page"
			);
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
			if ($newpage > $page) {
				$database->setQuery(
					"update #__facileforms_elements set page=page-1 where form=$form and page>$page and page<=$newpage"
				);
			} else {
				$database->setQuery(
					"update #__facileforms_elements set page=page+1 where form=$form and page>=$newpage and page<$page"
				);
			} // if
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
			$database->setQuery(
				"update #__facileforms_elements set page=$newpage where form=$form and page=0"
			);
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			} // if
			$page = $newpage;
		} // if
		JFactory::getApplication()->redirect("index.php?option=$option&act=editpage&form=$form&page=$page&pkg=$pkg");
	} // movePage

} // class facileFormsElement
?>