<?php
/* @var $this LogController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	t('epmms',$dataProvider->model->modelName),
	t('epmms','浏览'),
);

$this->menu=array(
	array('label'=>t('epmms','添加') . t('epmms',$dataProvider->model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','管理') . t('epmms',$dataProvider->model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms',$dataProvider->model->modelName)?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
