<?php
/* @var $this BackupController */
/* @var $model Backup */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->backup_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->backup_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
		'backup_name',
		'backup_add_date',
		'backup_remark',
	),
)); ?>
</div>
