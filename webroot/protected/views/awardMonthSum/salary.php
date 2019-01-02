<?php
/* @var $this AwardMonthSumController */
/* @var $model AwardMonthSum */

$this->breadcrumbs=array(
	t('epmms','月工资'),
);

$this->menu=array(
	array('label'=>t('epmms','工资日发放') , 'url'=>array('awardDaySum/salary')),
	array('label'=>t('epmms','工资周发放') , 'url'=>array('awardWeekSum/salary')),
	array('label'=>t('epmms','工资月发放') , 'url'=>array('awardMonthSum/salary')),
);

$tabParameters=[];

if($withdrawals==0)
{
	$tabParameters[0]=['title'=>t('epmms','月工资' .'(未提现)'),'view'=>'_salary','data'=>['model'=>$model,'withdrawals'=>0]];
	$tabParameters[1]=['title'=>t('epmms','月工资' .'(已提现)'),'url'=>$this->createUrl('awardMonthSum/salary',['withdrawals'=>1])];
}
else
{
	$tabParameters[0]=['title'=>t('epmms','月工资' .'(未提现)'),'url'=>$this->createUrl('awardMonthSum/salary',['withdrawals'=>0])];
	$tabParameters[1]=['title'=>t('epmms','月工资' .'(已提现)'),'view'=>'_salary','data'=>['model'=>$model,'withdrawals'=>1]];
}

$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>(int)$withdrawals));
?>