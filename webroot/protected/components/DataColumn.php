<?php

/** 
 * @author hetao
 * 
 */
class DataColumn extends CDataColumn
{
	/**
	 * Renders the data cell content.
	 * This method evaluates {@link value} or {@link name} and renders the result.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
	protected function renderDataCellContent($row,$data)
	{
		if($this->value!==null)
		{
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		}
		else if($this->name!==null)
		{
			$value=self::value($data,$this->name);
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
	/**
	 * Evaluates the value of the specified attribute for the given model.
	 * The attribute name can be given in a dot syntax. For example, if the attribute
	 * is "author.firstName", this method will return the value of "$model->author->firstName".
	 * A default value (passed as the last parameter) will be returned if the attribute does
	 * not exist or is broken in the middle (e.g. $model->author is null).
	 * The model can be either an object or an array. If the latter, the attribute is treated
	 * as a key of the array. For the example of "author.firstName", if would mean the array value
	 * "$model['author']['firstName']".
	 *
	 * Anonymous function could also be used for attribute calculation since 1.1.13
	 * ($attribute parameter; PHP 5.3+ only) as follows:
	 * <pre>
	 * $taskClosedSecondsAgo=CHtml::value($closedTask,function($model) {
	 * 	return time()-$model->closed_at;
	 * });
	 * </pre>
	 * Your anonymous function should receive one argument, which is the model, the current
	 * value is calculated from. This feature could be used together with the {@link listData}.
	 * Please refer to its documentation for more details.
	 *
	 * @param mixed $model the model. This can be either an object or an array.
	 * @param mixed $attribute the attribute name (use dot to concatenate multiple attributes)
	 * or anonymous function (PHP 5.3+). Remember that functions created by "create_function"
	 * are not supported by this method. Also note that numeric value is meaningless when
	 * first parameter is object typed.
	 * @param mixed $defaultValue the default value to return when the attribute does not exist.
	 * @return mixed the attribute value.
	 */
	public static function value($model,$attribute,$defaultValue=null)
	{
		if(is_scalar($attribute) || $attribute===null)
			foreach(explode('.',$attribute) as $name)
			{
				if(is_object($model))
					$model=$model->$name;
				elseif(is_array($model) && isset($model[$name]))
					$model=$model[$name];
				else
					return $defaultValue;
			}
		else
			return call_user_func($attribute,$model);

		return $model;
	}
}

?>