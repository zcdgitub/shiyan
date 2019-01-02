<?php
/* @var $this MemberinfoItemController */
/* @var $model MemberinfoItem */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
	t('epmms','修改'),
);

$this->menu=array(
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','修改') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>