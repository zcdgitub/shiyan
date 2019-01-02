<?php
/* @var $this WithdrawalsController */
/* @var $model Withdrawals */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('withdrawals-grid', {
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
		['submit'=>joinUrl('',['isVerifyType'=>$isVerifyType,'grid_mode'=>'export','exportType'=>'Excel5'])])?>
<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#withdrawals-grid'));
$columns=array(
	array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'headerHtmlOptions' => array('style'=>'width:40px')),
	'withdrawals_sn',
	array('class'=>'RelationDataColumn','name'=>'withdrawalsMember.memberinfo_account'),
	array('class'=>'RelationDataColumn','name'=>'withdrawalsFinanceType.finance_type_name','header'=>'帐户类型','releationClass'=>'FinanceType'),
	'withdrawals_currency',
	'withdrawals_tax',
	'withdrawals_real_currency',
	['name'=>'withdrawalsMember.memberinfo_bank_name','headerHtmlOptions'=>['style'=>'width:60px']],
	'withdrawalsMember.memberinfo_bank_account',
	['name'=>'withdrawalsMember.memberinfoBank.bank_name','headerHtmlOptions'=>['style'=>'width:80px'],'header'=>'银行名称'],
	'withdrawalsMember.memberinfo_bank_provience',
	'withdrawalsMember.memberinfo_bank_area',
	'withdrawalsMember.memberinfo_bank_branch',
	'withdrawals_remark',
	'withdrawals_add_date',
	'verify_date'=>'withdrawals_verify_date',
	'view'=>array(
		'class'=>'ButtonColumn',
		'template'=>'{view}',
		'buttons'=>['view'=>['label'=>'查看小票','imageUrl'=>false]]
	),
);

if($isVerifyType==0)
{
	$columns['verify']=	array(
		'class'=>'ButtonColumn',
		'template'=>'{verify}',
		'buttons'=>array('verify'=>array('isverify'=>'withdrawals_is_verify')),
	);
	$columns['del']=array(
		'class'=>'ButtonColumn',
		'template'=>'{delete}',
		'buttons'=>array('delete'=>array('visible'=>"!$isVerifyType")),
	);
	unset($columns['verify_date']);
	unset($columns['view']);
	unset($columns['send']);
}
elseif($isVerifyType==1)
{
	unset($columns['verify']);
	$columns['send']=array(
		'class'=>'ButtonColumn',
		'template'=>'{verify}',
		'verifyButtonUrl'=>'Yii::app()->controller->createUrl("send",array("id"=>$data->primaryKey))'
	);
}
elseif($isVerifyType==2)
{
	unset($columns['send']);
}

try
{
	$this->widget('EExcelView', array(
			'id'=>'withdrawals-grid',
			'title'=>t('epmms',$model->modelName),
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'ajaxUpdate'=>false,
			'columns'=>$columns
		)
	);
}
catch(CDbException $e)
{
	//print_r($e->getCode());
	user()->setFlash('error',"“{$model->modelName}”" . t('epmms',"查询错误"));
	$this->redirect(['index' , 'isVerifyType'=>$isVerifyType ],true);
}
?>
