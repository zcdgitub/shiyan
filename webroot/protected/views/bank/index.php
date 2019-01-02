<?php
/* @var $this BankController */
/* @var $model Bank */

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
	$.fn.yiiGridView.update('bank-grid', {
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
 
$this->widget('GridView', array(
	'id'=>'bank-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),	
		'bank_name',
		array('class'=>'DataColumn','name'=>'bank_is_preset','type'=>'preset','filter'=>array(0=>t('epmms','自定义'),1=>t('epmms','预置'))),
		array('class'=>'DataColumn','name'=>'bank_is_enable','type'=>'enable','filter'=>array(0=>t('epmms','禁用'),1=>t('epmms','启用'))),
		array(
				'class'=>'ButtonColumn',
				'template'=>'{view}',
				'viewButtonImageUrl'=>themeBaseUrl() . '/images/button/yello-mid-view2.gif',
		),
		array(
				'class'=>'ButtonColumn',
				'template'=>'{update}',
				'updateButtonImageUrl' =>themeBaseUrl() . '/images/button/yello-mid-edit2.gif',
		
		),
		array(
				'class'=>'ButtonColumn',
				'template'=>'{delete}',
				'deleteButtonImageUrl'=>themeBaseUrl() . '/images/button/yello-mid-del2.gif',
		),

	),
)); ?>
