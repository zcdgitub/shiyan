<?php
/* @var $this ConfigSiteController */
/* @var $model ConfigSite */

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
);
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters)); 
?>