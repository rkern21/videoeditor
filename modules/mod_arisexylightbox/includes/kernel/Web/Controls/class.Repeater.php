<?php
defined('ARI_FRAMEWORK_LOADED') or die('Direct Access to this location is not allowed.');

AriKernel::import('Mambot.MambotBase');
AriKernel::import('SimpleTemplate.SimpleTemplate');

class AriRepeaterWebControl extends AriObject
{
	var $_template;
	var $_data;
	
	function __construct($template, $data)
	{
		$this->_template = $template;
		$this->_data = $data;
	}

	function render($attrs = null)
	{
		$rpt = new AriRepeaterMainTemplate();
		$rpt->processContent($this->_template);
		
		echo $rpt->render($this->_data);
	}
}

class AriRepeaterMainTemplate extends AriMambotBase
{
	var $_headerTemplate;
	var $_footerTemplate;
	var $_rowTemplate;
	var $_cellTemplate;
	var $_emptyCellTemplate;
	var $_emptyTemplate;
	
	function __construct()
	{
		$this->_headerTemplate = new AriRepeaterGenericTemplate('headertemplate');
		$this->_footerTemplate = new AriRepeaterGenericTemplate('footertemplate');
		$this->_emptyTemplate = new AriRepeaterGenericTemplate('emptytemplate');
		$this->_cellTemplate = new AriRepeaterCellTemplate('celltemplate');
		$this->_emptyCellTemplate = new AriRepeaterGenericTemplate('emptycelltemplate');
		$this->_rowTemplate = new AriRepeaterRowTemplate('rowtemplate');
		
		parent::__construct('repeater');
	}
	
	function processContent(&$text)
	{
		$params = null;
		
		return parent::processContent(true, $text, $params);
	}
	
	function replaceCallback($attrs, $content = '')
	{
		$this->_headerTemplate->processContent($content);
		$this->_footerTemplate->processContent($content);
		$this->_emptyTemplate->processContent($content);
		$this->_cellTemplate->processContent($content);
		$this->_emptyCellTemplate->processContent($content);
		$this->_rowTemplate->processContent($content);

		return '';
	}
	
	function render($data)
	{
		if (!is_array($data) || count($data) == 0)
			return $this->_emptyTemplate->getContent();

		$rowTemplate = $this->_rowTemplate->getContent();
		$cellTemplate = $this->_cellTemplate->getContent();
		$emptyCellTemplate = $this->_emptyCellTemplate->getContent();
		$content = $this->_headerTemplate->getContent();
		$columnPerRow = $this->_rowTemplate->getColumnCount();
		$itemIndex = 0;
		$rowIndex = 0;
		$columns = '';
		foreach ($data as $dataItem)
		{
			$columns .= AriSimpleTemplate::parse($cellTemplate, array('data' => $dataItem), true);
			++$itemIndex;
			
			if ($itemIndex && ($itemIndex % $columnPerRow == 0))
			{
				$content .= AriSimpleTemplate::parse(
					str_replace('#{cellTemplate}', $columns, $rowTemplate), 
					array('rowClass' => $this->_rowTemplate->getRowClass($rowIndex)));
				$columns = '';
				++$rowIndex;
			}
		}

		if ($itemIndex % $columnPerRow != 0)
		{
			$columns .= str_repeat($emptyCellTemplate, $columnPerRow - ($itemIndex % $columnPerRow));
			$content .= AriSimpleTemplate::parse(
				str_replace('#{cellTemplate}', $columns, $rowTemplate), 
				array('rowClass' => $this->_rowTemplate->getRowClass($rowIndex)));
		}
		
		$content .= $this->_footerTemplate->getContent();

		return $content;
	}
}

class AriRepeaterGenericTemplate extends AriMambotBase
{
	var $_content = '';
	
	function __construct($tag)
	{
		parent::__construct($tag);
	}

	function processContent(&$text)
	{
		$params = null;
		
		return parent::processContent(true, $text, $params);
	}
	
	function replaceCallback($attrs, $content = '')
	{
		$this->_content = trim($content);

		return '';
	}
	
	function getContent()
	{
		return $this->_content;
	}
}

class AriRepeaterCellTemplate extends AriRepeaterGenericTemplate 
{
	function replaceCallback($attrs, $content = '')
	{
		parent::replaceCallback($attrs, $content);
		
		return '#{cellTemplate}';
	}
}

class AriRepeaterRowTemplate extends AriRepeaterGenericTemplate 
{
	var $_columnCount = 1;
	var $_rowClasses = array();
	
	function replaceCallback($attrs, $content = '')
	{
		$this->_columnCount = intval(AriUtils2::getParam($attrs, 'itemCount', 1), 10);
		$rowClasses = AriUtils2::getParam($attrs, 'rowClass', '');
		$rowClasses = explode(';', $rowClasses);
		array_walk($rowClasses, 'trim');
		$this->_rowClasses = $rowClasses;
		
		parent::replaceCallback($attrs, $content);
	}
	
	function getColumnCount()
	{
		return $this->_columnCount;
	}
	
	function getRowClass($rowIndex)
	{
		$rowClasses = $this->_rowClasses;
		$cnt = count($rowClasses);
		if ($cnt < 1)
			return '';

		return $rowClasses[$rowIndex % $cnt];
	}
}
?>