<?php

/**
 *
 * @author hetao
 *        
 */
class RelationDataColumn extends \CDataColumn
{
	/**
	 * 外键类
	 * @var mixed
	 */
	public $releationClass=false;
	public $filter;
	public function init()
	{
		parent::init();
		//生成关系列的filter
		if($this->releationClass)
		{
			$list1=Model::model($this->releationClass)->listData;
			$list2=[];
			foreach($list1 as $row)
			{
				$list2[$row]=$row;
			}
			$this->filter=$list2;
		}
	}
	/**
	 * Renders the data cell content.
	 * This method evaluates {@link value} or {@link name} and renders the result.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
	protected function renderDataCellContent($row,$data)
	{
		if($this->value!==null)
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		else if($this->name!==null)
		{
			if($model=resolveModel($data,$this->name))
				$value=$model->showName;
			else 
				$value=null;
		}

		echo $this->grid->getFormatter()->format($value,$this->type);
	}
	protected function renderFilterCellContent()
	{
		if(is_string($this->filter))
			echo $this->filter;
		else if($this->filter!==false && $this->grid->filter!==null && $this->name!==null)
		{
			if(is_array($this->filter))
				echo CHtml::activeDropDownList($this->grid->filter, dot2square($this->name), $this->filter, array('id'=>false,'prompt'=>''));
			else if($this->filter===null)
				echo CHtml::activeTextField($this->grid->filter, dot2square($this->name), array('id'=>false));
		}
		else
			parent::renderFilterCellContent();
	}
}

?>