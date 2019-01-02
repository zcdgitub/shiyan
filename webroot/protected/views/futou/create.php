<?php
/* @var $this FutouController */
/* @var $model Futou */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','我要'),
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','我要') . t('epmms',$model->modelName)?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>