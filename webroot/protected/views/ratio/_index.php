<?php
/* @var $this RatioController */
/* @var $model Ratio */

$this->menu=array(
	array('label'=>t('epmms','详细') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'period')),
	array('label'=>t('epmms','每日') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'day')),
	array('label'=>t('epmms','每周') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'week')),
	array('label'=>t('epmms','每月') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'month')),
	array('label'=>t('epmms','每年') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'year')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('ratio-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','拨出比历史记录')?></h1>

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
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#ratio-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	['name'=>'ratio_value','type'=>'percentage'],
	'ratio_add_date',
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{update}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{delete}',
	)
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
	break;
}
$this->widget('GridView', array(
	'id'=>'ratio-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
