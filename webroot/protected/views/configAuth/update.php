<?php
/* @var $this ConfigAuthController */
/* @var $model ConfigAuth */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','修改'),
);
$tabParameters=array
(
		'tab1'=>array(
				'title'=>t('epmms',$model->modelName),
				'view'=>'_form',
				'data'=>array('model'=>$model)
		),
		'tab2'=>array(
				'title'=>t('epmms',Model::model('ConfigSmtp')->modelName),
				'url'=>$this->createUrl('configSmtp/update'),
		),
		'tab3'=>array(
			'title'=>t('epmms',Model::model('ConfigMap')->modelName),
			'url'=>$this->createUrl('configMap/update'),
		),
);
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>'tab1')); 
?>

