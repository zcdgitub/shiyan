<?php
/* @var $this AwardPeriodSumController */
/* @var $model awardPeriodSum */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);
$this->menu=array(

);
$tabParameters=[];
foreach($sumTypes as $sum)
{
	if(webapp()->id=='141203' && !user()->isAdmin() && user()->id!=1)
	{
		if ($sum->sum_type_id==1 && !user()->isAdmin() && user()->map->membermap_membertype_level == 4)
			continue;
		if ($sum->sum_type_id==4 && !user()->isAdmin() && user()->map->membermap_membertype_level == 4)
			continue;
		if ($sum->sum_type_id==5 && !user()->isAdmin() && user()->map->membermap_membertype_level != 4)
			continue;
	}
	if($curSumType==$sum->sum_type_id)
	{
		$tabParameters[$sum->sum_type_id]=['title'=>t('epmms',$sum->sum_type_name),'view'=>'_award','data'=>['model'=>$model,'gridColumn'=>$gridColumn,'curSumType'=>$sum->sum_type_id]];
	}
	else
	{
		$tabParameters[$sum->sum_type_id]=['title'=>t('epmms',$sum->sum_type_name),'url'=>$this->createUrl('awardPeriodSum/index',['curSumType'=>$sum->sum_type_id])];
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$curSumType));
?>