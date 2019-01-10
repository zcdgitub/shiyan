<?php
/* @var $this SaleController */
/* @var $model Sale */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','添加'),
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','添加') . t('epmms',$model->modelName)?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>