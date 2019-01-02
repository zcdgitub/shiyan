<?php
/* @var $this LogController */
/* @var $model Log */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

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
	$.fn.yiiGridView.update('log-grid', {
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
<?=CHtml::imageButton(themeBaseUrl() . '/images/Excel.png',
	['submit'=>CHtml::normalizeUrl(['','grid_mode'=>'export','exportType'=>'Excel5'])])?>
<?php
$this->widget('EExcelView', array(
	'id'=>'log-grid',
	'title'=>t('epmms',$model->modelName),
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>array(
		array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),	
		array('name'=>'log_source'),
		array('name'=>'log_operate'),
		'log_target',
		'log_value',
		['name'=>'log_info','type'=>'ntext'],
		'log_ip',
		['name'=>'log_user','filter'=>user()->isAdmin()?null:false],
		array('name'=>'log_role','type'=>'role','filter'=>user()->isAdmin()?(new Rights())->getAuthItemSelectOptions(2,array('Guest','authenticated','admin')):''),
		array('class'=>'DataColumn','name'=>'log_add_date','type'=>'datetime'),
		array('class'=>'DataColumn','name'=>'log_status','type'=>'logStatus','filter'=>[0=>'成功',1=>'失败']),
		array(
				'class'=>'ButtonColumn',
				'template'=>'{view}',
				'viewButtonImageUrl'=>themeBaseUrl() . '/images/button/yello-mid-view2.gif',
		),
	),
)); ?>
