<?php

/** 
 * @author hetao
 * 
 */
class RelativeFormInputElement2 extends CInputWidget
{
	public $relativeName;
	public $htmlOptions;
	public function init()
	{
		$relativeName=$this->relativeName;
		if(is_null($relativeName))
		{
			foreach($this->model->relations() as $relationName=>$relation)
			{
				if($relation[2]==$this->attribute)
				{
					$relativeName=$relationName;
				}
			}
		}
		if(!is_null($relativeName))
		{
			$model=$this->model;
			$relationModel=$model->$relativeName;
			echo CHtml::textField(CHtml::resolveName($this->model,$this->attribute),is_null($relationModel)?null: $relationModel->showName,$this->htmlOptions);
		}
		else
			throw new CException(Yii::t('epmms','{class} 提供的模型类名，关系名或外键字段无效',array('{class}'=>get_class($this))));
	}
}
?>