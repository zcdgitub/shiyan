<?php
/* @var $this AwardPeriodSumController */
/* @var $model awardPeriodSum */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

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
	['name'=>'award_period_sum_add_date','headerHtmlOptions'=>['style'=>'width:120px;']],
	array('class'=>'DataColumn','name'=>'awardPeriodSumMemberinfo.memberinfo_nickname','headerHtmlOptions'=>['style'=>'width:100px;']),
	array('class'=>'DataColumn','name'=>'awardPeriodSumMemberinfo.memberinfo_account','headerHtmlOptions'=>['style'=>'width:100px;']),
	// 'source_member'=>array('class'=>'DataColumn','name'=>'awardPeriodSumSrcMemberinfo.memberinfo_account',
	// 	'header'=>t('epmms','来源会员'),'headerHtmlOptions'=>['style'=>'width:100px;']),
	// 'source_nickname'=>array('class'=>'DataColumn','name'=>'awardPeriodSumSrcMemberinfo.memberinfo_nickname',
	// 	'header'=>t('epmms','来源昵称'),'headerHtmlOptions'=>['style'=>'width:100px;']),
	// 'source_membertype'=>array('class'=>'DataColumn','name'=>'awardPeriodSumSrcMemberinfo.membermap.membermapMembertypeLevel.showName',
	// 	'header'=>t('epmms','来源类型'),'headerHtmlOptions'=>['style'=>'width:100px;']),
	'source_member_b'=>array('class'=>'DataColumn','name'=>'awardPeriodSumSrcMemberinfo_b.showName',
		'header'=>t('epmms','B网来源'),'headerHtmlOptions'=>['style'=>'width:100px;']),
);
if(!user()->isAdmin())
{
	//unset($columns1['source_member']);
}

	if(webapp()->db->database=='141128')
	{
		if($curSumType!=4)
		{
			unset($columns1['source_member_b']);
		}
		else
		{
			//unset($columns1['source_membertype']);
		}
	}
	else
	{
		unset($columns1['source_member_b']);
	}

$columns2=array(
	['name'=>'award_period_sum_currency','headerHtmlOptions'=>['style'=>'width:80px;'],'type'=>'money'],
	/*['name'=>'award_period_sum_period','headerHtmlOptions'=>['style'=>'width:60px;']],
	['name'=>'award_period_sum_add_date','headerHtmlOptions'=>['style'=>'width:120px;']],*/
	array(
			'class'=>'CLinkColumn',
			'urlExpression'=>'$this->grid->controller->createUrl("awardPeriod/index",["AwardPeriod[award_period_memberinfo_id]"=>$data->award_period_sum_memberinfo_id,"AwardPeriod[award_period_period]"=>$data->award_period_sum_period,"AwardPeriod[award_period_sum_type]"=>$data->award_period_sum_type])',
			'label'=>t('epmms','查看')
	)
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