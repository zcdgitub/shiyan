<?php
/* @var $this WithdrawalsController */
/* @var $model Withdrawals */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',
	'attributes'=>array(
		'withdrawals_sn',
		array('name'=>'withdrawalsMember.memberinfo_account','label'=>$model->getAttributeLabel('withdrawals_member_id')),
		array('name'=>'withdrawalsFinanceType.finance_type_name','label'=>$model->getAttributeLabel('withdrawals_finance_type_id')),
		'withdrawals_currency',
		'withdrawals_tax',
		'withdrawals_real_currency',
		'withdrawalsMember.memberinfo_bank_name',
		'withdrawalsMember.memberinfo_bank_account',
		'withdrawalsMember.memberinfoBank.bank_name',
		'withdrawalsMember.memberinfo_bank_provience',
		'withdrawalsMember.memberinfo_bank_area',
		'withdrawalsMember.memberinfo_bank_branch',
		'withdrawals_add_date',
		array('name'=>'withdrawals_is_verify','type'=>'withdrawalsStatus'),
		'withdrawals_verify_date',
		'withdrawals_remark',
	),
)); ?>
</div>
