<?php

/** 
 * @author hetao
 * 
 */
class RelativeFormInputElement extends CInputWidget
{
	public $className;
	public $relativeName;
	public $condition;
	public function init()
	{
		$relativeClass=false;
		if(!is_null($this->className))
			$relativeClass=$this->className;
		elseif(!is_null($this->relativeName))
			$relativeClass=$this->model->getActiveRelation($this->relativeName)->className;
		else
		{
			foreach($this->model->relations() as $relationName=>$relation)
			{
				if($relation[2]==$this->attribute)
				{
					$relativeClass=$relation[1];
				}
			}
		}
		
		if($relativeClass!==false)
		{
			if(!isset($this->htmlOptions['prompt']))
			{
				$this->htmlOptions['prompt']=t('epmms','请选择') . $this->model->getAttributeLabel($this->attribute);
			}
			echo CHtml::activeDropDownList($this->model, $this->attribute, is_null($this->condition)?$relativeClass::model()->listData:$relativeClass::model()->getListData($this->condition),$this->htmlOptions);
		}
		else
			throw new CException(Yii::t('epmms','{class} 提供的模型类名，关系名或外键字段无效',array('{class}'=>get_class($this))));
	}
}

?>