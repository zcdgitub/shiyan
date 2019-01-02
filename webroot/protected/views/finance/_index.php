<?php
/* @var $this FinanceController */
/* @var $model Finance */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

$this->menu=array(
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('finance-grid', {
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
<?=CHtml::imageButton(themeBaseUrl() . '/images/Excel.png',
	['submit'=>CHtml::normalizeUrl(['','grid_mode'=>'export','curSumType'=>$curSumType,'exportType'=>'Excel5'])])?>
<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#finance-grid'));
$columns=array(
	array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'headerHtmlOptions' => array('style'=>'width:40px')),
	array('class'=>'RelationDataColumn','name'=>'financeMemberinfo.memberinfo_account'),
	array('class'=>'DataColumn','header'=>'代理中心编号','value'=>'@Agent::model()->findByAttributes(["agent_memberinfo_id"=>$data->finance_memberinfo_id])->agent_account'),
	array('class'=>'DataColumn','name'=>'financeMemberinfo.memberinfo_nickname'),
	array('class'=>'DataColumn','name'=>'financeMemberinfo.memberinfo_bank_name'),
	array('class'=>'DataColumn','name'=>'financeMemberinfo.memberinfoBank.bank_name','header'=>t('epmms','银行名')),
	array('class'=>'DataColumn','name'=>'financeMemberinfo.memberinfo_bank_account'),
	['name'=>'finance_award','type'=>'money'],
	'finance_mod_date',
	array(
		'class'=>'ButtonColumn',
		'template'=>'{view}',
	),
	array(
		'class'=>'ButtonColumn',
		'template'=>'{update}',
	),
	'withdrawals'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("withdrawals/adminCreate",["id"=>$data->finance_id])',
		'label'=>t('epmms','提现')
		,'headerHtmlOptions' => array('style'=>'width:40px')
	),
	'appropriation'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("appropriate/create",["appropriate_memberinfo_id"=>$data->financeMemberinfo->memberinfo_account,"appropriate_finance_type_id"=>$data->finance_type])',
		'label'=>t('epmms','拨款'),
		'headerHtmlOptions' => array('style'=>'width:40px')
	),
	'deduct'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("appropriate/deduct",["appropriate_memberinfo_id"=>$data->financeMemberinfo->memberinfo_account,"appropriate_finance_type_id"=>$data->finance_type])',
		'label'=>t('epmms','扣款'),
		'headerHtmlOptions' => array('style'=>'width:40px')
	),
);
	if(FinanceType::model()->findByPk($curSumType)->finance_type_withdrawals!=1)
	{
		unset($columns['withdrawals']);
	}
	if(isset($_REQUEST['grid_mode']))
	{
		unset($columns['withdrawals']);
	}
	$this->widget('EExcelView', array(
		'id'=>'finance-grid',
		'title'=>t('epmms',$model->modelName),
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'ajaxUpdate'=>false,
		'columns'=>$columns
	));
?>
