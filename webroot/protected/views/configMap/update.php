<?php
/* @var $this ConfigMapController */
/* @var $model ConfigMap */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','修改'),
);
$tabParameters=array
(
	'tab1'=>array(
		'title'=>t('epmms',Model::model('ConfigAuth')->modelName),
		'url'=>$this->createUrl('configAuth/update'),
	),
	'tab2'=>array(
		'title'=>t('epmms',Model::model('ConfigSmtp')->modelName),
		'url'=>$this->createUrl('configSmtp/update'),
	),
	'tab3'=>array(
		'title'=>t('epmms',$model->modelName),
		'view'=>'_form',
		'data'=>array('model'=>$model)
	),
);
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>'tab3'));
?>