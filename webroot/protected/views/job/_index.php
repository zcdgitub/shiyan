<?php
/* @var $this JobController */
/* @var $model Job */

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('job-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>


<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#job-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	'jobname',
	'jobdesc',
	['name'=>'jobenabled','type'=>'enable','filter'=>[0=>'禁用',1=>'启用']],
	'jobnextrun',
	'joblastrun',
	['name'=>'lastStatus','type'=>'jobstatus','filter'=>false],
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{update}',
	),
	'joblog'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("joblog/index",["id"=>$data->jobid])',
		'label'=>t('epmms','运行历史'),
	),
	'run'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("run",["id"=>$data->jobid])',
		'label'=>t('epmms','立即运行'),
	),
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
	break;
}
$this->widget('GridView', array(
	'id'=>'job-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
