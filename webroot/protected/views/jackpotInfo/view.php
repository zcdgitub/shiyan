<?php
/* @var $this JackpotInfoController */
/* @var $model JackpotInfo */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
//	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
//	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->info_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->info_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
		'info_start_time',
		'info_end_time',
		'info_start_balance',
		'info_lucky_balance',
		'info_end_balance',
		'info_number',
	),
)); ?>
</div>
