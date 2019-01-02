<?php
/* @var $this BankaccountController */
/* @var $model Bankaccount */

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
	$.fn.yiiGridView.update('bankaccount-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$listModel=Model::model('Bank')->findAll();
$listData['Bank']=CHtml::listdata($listModel,'bank_name','bank_name');
 
$this->widget('GridView', array(
	'id'=>'bankaccount-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),	
		array('class'=>'DataColumn','name'=>'bankaccountBank.bank_name','filter'=>$listData['Bank'],'header'=>$model->getAttributeLabel('bankaccount_bank_id')),
		'bankaccount_name',
		'bankaccount_account',
		'bankaccount_provience',
		'bankaccount_area',
		'bankaccount_branch',
//		'bankaccount_mobi',
		array('class'=>'DataColumn','name'=>'bankaccount_is_enable','type'=>'enable','filter'=>array(0=>t('epmms','禁用'),1=>t('epmms','启用'))),
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
