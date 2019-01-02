<?php
/* @var $this TransferController */
/* @var $model Transfer */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

$this->menu=array(
	array('label'=>t('epmms','申请转帐'), 'url'=>array('create')),
);
?>
<?php
$tabParameters[0]=['title'=>t('epmms','转帐记录'),'view'=>'_transfer','data'=>['model'=>$model,'isVerifyType'=>$isVerifyType]];
if(user()->isAdmin())
	$tabParameters[1]=['title'=>t('epmms','转账配置'),'url'=>$this->createUrl('transferConfig/index')];

$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$isVerifyType));
?>
