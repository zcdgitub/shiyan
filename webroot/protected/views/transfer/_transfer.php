<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('transfer-grid', {
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
	['submit'=>CHtml::normalizeUrl(['','grid_mode'=>'export','exportType'=>'Excel5'])])?>
<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#transfer-grid'));
$columns=array(
	array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'headerHtmlOptions' => array('style'=>'width:40px')),
	'transfer_sn',
	array('class'=>'RelationDataColumn','name'=>'transferSrcMember.memberinfo_account','header'=>t('epmms','转出会员')),
	array('class'=>'RelationDataColumn','name'=>'transferSrcFinanceType.finance_type_name','header'=>t('epmms','转出会员帐户'),'releationClass'=>'FinanceType'),
	array('class'=>'RelationDataColumn','name'=>'transferDstMember.memberinfo_account','header'=>t('epmms','转入会员')),
	array('class'=>'RelationDataColumn','name'=>'transferDstFinanceType.finance_type_name','header'=>t('epmms','转入会员帐户'),'releationClass'=>'FinanceType'),
	'transfer_currency',
	'transfer_tax',
	'transfer_remark',
	'transfer_add_date',
	'verify_date'=>'transfer_verify_date',
	'view'=>array(
		'class'=>'ButtonColumn',
		'template'=>'{view}',
		'buttons'=>['view'=>['label'=>t('epmms','查看小票'),'imageUrl'=>false]]
	),
);
if($isVerifyType==0)
{
	$columns[]=	array(
		'class'=>'ButtonColumn',
		'template'=>'{verify}',
		'buttons'=>array('verify'=>array('isverify'=>'transfer_is_verify')),
	);
/*	$columns[]=array(
		'class'=>'ButtonColumn',
		'template'=>'{delete}',
		'buttons'=>array('delete'=>array('visible'=>"!$isVerifyType")),
	);*/
	unset($columns['verify_date']);
	unset($columns['view']);
}

try
{
	$this->widget('EExcelView', array(
		'id'=>'transfer-grid',
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