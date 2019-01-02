<?php
/* @var $this WithdrawalsController */
/* @var $model Withdrawals */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

$this->menu=array(
	array('label'=>t('epmms','汇款充值'), 'url'=>array('create')),
);
?>
<?php
$transferType=[0=>t('epmms','未审核'),1=>t('epmms','已审核'),2=>t('epmms','未支付'),3=>t('epmms','已支付')];
$tabParameters=[];
$tab=0;
foreach($transferType as $isVerify=>$title)
{
	if($isVerify%2==$isVerifyType && floor($isVerify/2)==$charge_type)
	{
		$tab=$isVerify;
		$tabParameters[$isVerify]=['title'=>$title,'view'=>'_index','data'=>['model'=>$model,'isVerifyType'=>$isVerifyType,'charge_type'=>$charge_type,'total'=>$total]];
	}
	else
	{
		$tabParameters[$isVerify]=['title'=>$title,'url'=>$this->createUrl('charge/index',['isVerifyType'=>$isVerify%2,'charge_type'=>floor($isVerify/2)])];
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$tab));
?>
