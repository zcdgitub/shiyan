<?php
/* @var $this MemberinfoItemController */
/* @var $model MemberinfoItem */

$this->menu=array(
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('memberinfo-item-grid', {
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
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#memberinfo-item-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	'memberinfo_item_title',
	'item_visible'=>['name'=>'memberinfo_item_visible','value'=>"webapp()->format->formatYesno(\$data->memberinfo_item_visible)",'filter'=>[0=>'否',1=>'是']],
	'item_required'=>['name'=>'memberinfo_item_required','value'=>"webapp()->format->formatYesno(\$data->memberinfo_item_required)",'filter'=>[0=>'否',1=>'是']],
	['name'=>'memberinfo_item_update','value'=>"webapp()->format->formatYesno(\$data->memberinfo_item_update)",'filter'=>[0=>'否',1=>'是']],
	['name'=>'memberinfo_item_admin','value'=>"webapp()->format->formatYesno(\$data->memberinfo_item_admin)",'filter'=>[0=>'否',1=>'是']],
	['name'=>'memberinfo_item_view','value'=>"webapp()->format->formatYesno(\$data->memberinfo_item_view)",'filter'=>[0=>'否',1=>'是']],
	['name'=>'memberinfo_item_order'],
	array(
	'class'=>'ButtonColumn',
	'template'=>'{update}',
	),
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
	break;
}
$this->widget('GridView', array(
	'id'=>'memberinfo-item-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
