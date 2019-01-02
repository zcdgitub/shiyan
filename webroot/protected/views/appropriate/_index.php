<?php
/* @var $this AppropriateController */
/* @var $model Appropriate */

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','拨款'), 'url'=>array('create')),
	array('label'=>t('epmms','扣款'), 'url'=>array('deduct')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('appropriate-grid', {
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
&nbsp;&nbsp;
<?=CHtml::imageButton(themeBaseUrl() . '/images/Excel.png', ['submit'=>joinUrl('',['selTab'=>$selTab,'grid_mode'=>'export','exportType'=>'Excel5'])])?>
<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#appropriate-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	'appropriate_currency',
	array('class'=>'DataColumn','name'=>'appropriateFinanceType.finance_type_name'),
	'appropriate_add_date',
	array('class'=>'DataColumn','name'=>'appropriateMemberinfo.memberinfo_account'),
	array('class'=>'DataColumn','header'=>'代理中心编号','value'=>'@Agent::model()->findByAttributes(["agent_memberinfo_id"=>$data->appropriate_memberinfo_id])->agent_account'),
	['name'=>'appropriate_type','type'=>'appropriateType'],
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
$this->widget('EExcelView', array(
	'id'=>'appropriate-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
