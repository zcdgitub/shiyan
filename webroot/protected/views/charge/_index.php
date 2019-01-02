<?php
/* @var $this ChargeController */
/* @var $model Charge */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('charge-grid', {
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
	['submit'=>CHtml::normalizeUrl(['','isVerifyType'=>$isVerifyType,'grid_mode'=>'export','exportType'=>'Excel5'])])?>
<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#charge-grid'));
?>
<p style="padding-top: 30px;">
	<?php
	if($isVerifyType==1)
		echo t('epmms','充值总计：') . webapp()->format->formatNumber($total->sum_currency);
	?>
</p>

<?php
$columns=array(
	array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'headerHtmlOptions' => array('style'=>'width:40px')),
	'charge_sn',
	array('class'=>'RelationDataColumn','name'=>'chargeMemberinfo.memberinfo_account'),
	'charge_currency',
	array('class'=>'RelationDataColumn','name'=>'chargeBank.bank_name','header'=> t('epmms','汇款银行'),'releationClass'=>'Bank'),
	'charge_bank_account',
	'charge_bank_address',
	'charge_bank_sn',
	'charge_bank_date',
	'charge_bank_account_name',
	array('class'=>'RelationDataColumn','name'=>'chargeFinanceType.finance_type_name','header'=>t('epmms','帐户类型'),'releationClass'=>'FinanceType'),
	'charge_remark',
	'charge_add_date',
	/*
	'charge_verify_date',
	*/
	'verify_date'=>'charge_verify_date',
	'view'=>array(
		'class'=>'ButtonColumn',
		'template'=>'{view}',
		'buttons'=>['view'=>['label'=>t('epmms','查看小票'),'imageUrl'=>false]]
	)
);
if($isVerifyType==0)
{
	$columns[]=	array(
		'class'=>'ButtonColumn',
		'template'=>'{verify}',
		'buttons'=>array('verify'=>array('isverify'=>'charge_is_verify')),
	);
	$columns[]=array(
		'class'=>'ButtonColumn',
		'template'=>'{delete}',
		'buttons'=>array('delete'=>array('visible'=>"!$isVerifyType")),
	);
	unset($columns['verify_date']);
	unset($columns['view']);
}
if($charge_type==1)
{
	$columns=array(
		array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'headerHtmlOptions' => array('style'=>'width:40px')),
		'charge_sn',
		array('class'=>'RelationDataColumn','name'=>'chargeMemberinfo.memberinfo_account'),
		'charge_currency',
		array('class'=>'RelationDataColumn','name'=>'chargeFinanceType.finance_type_name','header'=>'帐户类型','releationClass'=>'FinanceType'),
		'charge_remark',
		'charge_add_date',
		'verify_date'=>'charge_verify_date',
		'view'=>array(
			'class'=>'ButtonColumn',
			'template'=>'{view}',
			'buttons'=>['view'=>['label'=>'查看小票','imageUrl'=>false]]
		)
	);
	if($isVerifyType==0)
	{
		$columns['pay']=['class'=>'CLinkColumn','label'=>'支付','urlExpression'=>'webapp()->createUrl("charge/payOn",["id"=>$data->charge_id])'];
		$columns[]=array(
			'class'=>'ButtonColumn',
			'template'=>'{delete}',
			'buttons'=>array('delete'=>array('visible'=>"!$isVerifyType")),
		);
		unset($columns['verify_date']);
		unset($columns['view']);
	}
}

try
{
	$this->widget('EExcelView', array(
			'id'=>'charge-grid',
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
