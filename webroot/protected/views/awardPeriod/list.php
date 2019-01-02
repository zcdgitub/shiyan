<?php
/* @var $this AwardPeriodController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	t('epmms',$dataProvider->model->modelName),
	t('epmms','浏览'),
);

$this->menu=array(
);
?>

<h1><?php echo t('epmms',$dataProvider->model->modelName)?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemsTagName'=>'table',
	'itemsCssClass'=>'viewtable',
	'itemView'=>'_view',
)); ?>
