<?php
/* @var $this AwardWeekSumController */
/* @var $model awardWeekSum */

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

<h1><?php echo t('epmms','周工资')?></h1>

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
	['submit'=>joinUrl('',['withdrawals'=>$withdrawals,'grid_mode'=>'export','exportType'=>'Excel5'])])?>
&nbsp;&nbsp;
<?=CHtml::imageButton(themeBaseUrl() . '/images/pdf.png',
	['submit'=>joinUrl('',['withdrawals'=>$withdrawals,'grid_mode'=>'export','exportType'=>'PDF'])])?>
<?php
if((int)$withdrawals==0)
	echo CHtml::linkButton('全部提现',['confirm'=>'确定要全部提现吗？','submit'=>joinUrl('',['withdrawals'=>0,'allWithdrawals'=>1])])
?>
<?php
$columns1=array(
	array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'headerHtmlOptions' => array('style'=>'width:40px')),
	array('class'=>'DataColumn','name'=>'awardWeekSumMemberinfo.memberinfo_nickname','headerHtmlOptions'=>['style'=>'width:100px;']),
	array('class'=>'DataColumn','name'=>'awardWeekSumMemberinfo.memberinfo_account','headerHtmlOptions'=>['style'=>'width:100px;']));
$columns2=array(
	['name'=>'award_week_sum_currency','headerHtmlOptions'=>['style'=>'width:100px;'],'type'=>'money'],
	['name'=>'withdrawalsTax','type'=>'money','filter'=>false,'headerHtmlOptions'=>['style'=>'width:100px;']],
	['name'=>'realCurrency','type'=>'money','filter'=>false,'headerHtmlOptions'=>['style'=>'width:100px;']],
	['name'=>'award_week_sum_date','headerHtmlOptions'=>['style'=>'width:120px;']],
	'bank'=>array('class'=>'DataColumn','name'=>'awardWeekSumMemberinfo.memberinfoBank.bank_name'
	,'headerHtmlOptions'=>['style'=>'width:100px;']),
	array('class'=>'DataColumn','name'=>'awardWeekSumMemberinfo.memberinfo_bank_name'),
	'bank_account'=>array('class'=>'DataColumn','name'=>'awardWeekSumMemberinfo.memberinfo_bank_account'),
	'withdrawals'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("awardWeekSum/payoff",["member_id"=>$data->award_week_sum_memberinfo_id])',
		'label'=>t('epmms','提现')
	,'headerHtmlOptions' => array('style'=>'width:40px')
	),
	'salary_del'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("awardWeekSum/salaryDel",["id"=>$data->award_week_sum_id])',
		'label'=>t('epmms','删除')
	,'headerHtmlOptions' => array('style'=>'width:40px'),
	),
);
if($withdrawals==1)
{
	unset($columns2['withdrawals']);
	unset($columns2['salary_del']);
}
if(isset($_REQUEST['grid_mode']) && $_REQUEST['grid_mode']=='export')
{
	unset($columns2['withdrawals']);
	unset($columns2['salary_del']);
}
$columns=array_merge($columns1,$columns2);
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#award-period-sum-grid'));
$this->widget('EExcelView', array(
	'id'=>'award-period-sum-grid',
	'ajaxUpdate'=>false,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'title'=>t('epmms',$model->modelName),
	'columns'=>$columns
)); ?>
