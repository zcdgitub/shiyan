<?php
/* @var $this AwardWeekSumController */
/* @var $model AwardWeekSum */

$this->breadcrumbs=array(
	t('epmms','周工资'),
);

$tabParameters=[];
if($withdrawals==0)
{
	$tabParameters[0]=['title'=>t('epmms','周工资' .'(未提现)'),'view'=>'_salary','data'=>['model'=>$model,'withdrawals'=>0]];
	$tabParameters[1]=['title'=>t('epmms','周工资' .'(已提现)'),'url'=>$this->createUrl('awardWeekSum/salary',['withdrawals'=>1])];
}
else
{
	$tabParameters[0]=['title'=>t('epmms','周工资' .'(未提现)'),'url'=>$this->createUrl('awardWeekSum/salary',['withdrawals'=>0])];
	$tabParameters[1]=['title'=>t('epmms','周工资' .'(已提现)'),'view'=>'_salary','data'=>['model'=>$model,'withdrawals'=>1]];
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>(int)$withdrawals));
?>