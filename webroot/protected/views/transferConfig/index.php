<?php
/* @var $this TransferConfigController */
/* @var $model TransferConfig */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','修改'),
);
if(user()->isAdmin())
	$tabParameters[1]=['title'=>'转账配置','view'=>'_form','data'=>['model'=>$model]];

$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>1));
?>
