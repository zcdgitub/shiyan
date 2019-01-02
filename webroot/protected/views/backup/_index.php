<?php
/* @var $this BackupController */
/* @var $model Backup */
if($selTab==0)
{
	$this->menu=array(
		array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
		array('label'=>t('epmms','上传') . t('epmms',$model->modelName), 'url'=>array('upload')),
	);
}
else
{
	$this->menu[]=array('label'=>t('epmms','清空') . t('epmms',$model->modelName), 'url'=>array('clean'));
}
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('backup-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<p>
<?php echo t('epmms','你可以输入一个比较运算符 ');?>(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
<?php echo t('epmms','或者');?> <b>=</b>) <?php echo t('epmms','在每个搜索值的前面来指定怎么匹配搜索结果。');?>
</p>

<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#backup-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	'backup_name',
	'backup_add_date',
	'backup_remark',
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	'update'=>array(
	'class'=>'ButtonColumn',
	'template'=>'{update}',
	),
	'del'=>array(
	'class'=>'ButtonColumn',
	'template'=>'{delete}',
	),
	'restore'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("restore",["id"=>$data->backup_id])',
		'label'=>t('epmms','恢复'),
	),
	'download'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("download",["id"=>$data->backup_id])',
		'label'=>t('epmms','下载'),
	),
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
	break;
	case 1:
		unset($columns['update']);
		unset($columns['del']);
	break;
}
$this->widget('GridView', array(
	'id'=>'backup-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
