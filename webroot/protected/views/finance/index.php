<?php
/* @var $this AwardPeriodSumController */
/* @var $model awardPeriodSum */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);
$this->menu=array(

);
$tabParameters=[];
foreach($financeTypes as $sum)
{
	if($curSumType==$sum->finance_type_id)
	{
		$tabParameters[$sum->finance_type_id]=['title'=>t('epmms',$sum->finance_type_name),'view'=>'_index','data'=>['model'=>$model,'curSumType'=>$sum->finance_type_id]];
	}
	else
	{
		$tabParameters[$sum->finance_type_id]=['title'=>t('epmms',$sum->finance_type_name),'url'=>$this->createUrl('finance/index',['curSumType'=>$sum->finance_type_id])];
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$curSumType));
?>