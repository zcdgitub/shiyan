<?php
/* @var $this LogController */
/* @var $model Log */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->log_id,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->log_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->log_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->log_id; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'log_source',
		'log_operate',
		'log_target',
		'log_value',
		'log_info',
		'log_ip',
		'log_user',
		['name'=>'log_role','type'=>'role'],
		'log_add_date',
		['name'=>'log_status','type'=>'logStatus'],
	),
)); ?>
</div>
