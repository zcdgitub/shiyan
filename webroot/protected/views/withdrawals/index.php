<?php
/* @var $this WithdrawalsController */
/* @var $model Withdrawals */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

$this->menu=array(
	array('label'=>t('epmms','申请提现'), 'url'=>array('create')),
);
?>
<?php
$transferType=[0=>t('epmms','未审核'),1=>t('epmms','已审核'),2=>t('epmms','已发放')];
$tabParameters=[];
foreach($transferType as $isVerify=>$title)
{
	if($isVerifyType==$isVerify)
	{
		$tabParameters[$isVerify]=['title'=>$title,'view'=>'_index','data'=>['model'=>$model,'isVerifyType'=>$isVerifyType]];
	}
	else
	{
		$tabParameters[$isVerify]=['title'=>$title,'url'=>$this->createUrl('withdrawals/index',['isVerifyType'=>$isVerify])];
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$isVerifyType));
?>
