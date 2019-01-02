<?php
/* @var $this AwardDaySumController */
/* @var $model awardDaySum */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('award-period-sum-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName)?></h1>

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
<?=CHtml::imageButton(themeBaseUrl() . '/images/Excel.png',
	['submit'=>joinUrl('',['curSumType'=>$curSumType,'grid_mode'=>'export','exportType'=>'Excel5'])])?>
&nbsp;&nbsp;
<?=CHtml::imageButton(themeBaseUrl() . '/images/pdf.png',
	['submit'=>joinUrl('',['curSumType'=>$curSumType,'grid_mode'=>'export','exportType'=>'PDF'])])?>
<?php
$columns1=array(
	array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'headerHtmlOptions' => array('style'=>'width:40px')),
	array('class'=>'DataColumn','name'=>'awardDaySumMemberinfo.memberinfo_nickname','headerHtmlOptions'=>['style'=>'width:100px;']),
	array('class'=>'DataColumn','name'=>'awardDaySumMemberinfo.memberinfo_account','headerHtmlOptions'=>['style'=>'width:100px;']));
$columns2=array(
	['name'=>'award_day_sum_currency','headerHtmlOptions'=>['style'=>'width:100px;'],'type'=>'money'],
	['name'=>'award_day_sum_date','headerHtmlOptions'=>['style'=>'width:120px;']],
);
$columns=array_merge($columns1,$gridColumn,$columns2);
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#award-period-sum-grid'));
$this->widget('EExcelView', array(
	'id'=>'award-period-sum-grid',
	'ajaxUpdate'=>false,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'title'=>t('epmms',$model->modelName),
	'columns'=>$columns
)); ?>
