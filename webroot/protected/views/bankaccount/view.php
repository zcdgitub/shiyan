<?php
/* @var $this BankaccountController */
/* @var $model Bankaccount */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->bankaccount_account,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->bankaccount_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->bankaccount_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->bankaccount_account; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array('name'=>'bankaccountBank.bank_name','label'=>$model->getAttributeLabel('bankaccount_bank_id')),
		'bankaccount_name',
		'bankaccount_account',
		'bankaccount_provience',
		'bankaccount_area',
		'bankaccount_branch',
//		'bankaccount_mobi',
		array('name'=>'bankaccount_is_enable','type'=>'enable'),
	),
)); ?>
</div>
