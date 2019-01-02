<?php
/* @var $this ChargeController */
/* @var $model Charge */

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
		'charge_sn',
		array('name'=>'chargeMemberinfo.memberinfo_account','label'=>$model->getAttributeLabel('charge_memberinfo_id')),
		'charge_currency',
		array('name'=>'charge_is_verify','type'=>'verify'),
		'charge_add_date',
		array('name'=>'chargeBank.bank_name','label'=>$model->getAttributeLabel('charge_bank_id')),
		'charge_bank_account',
		'charge_bank_address',
		'charge_bank_sn',
		'charge_bank_date',
		'charge_bank_account_name',
		array('name'=>'chargeFinanceType.finance_type_name','label'=>$model->getAttributeLabel('charge_finance_type_id')),
		'charge_remark',
		'charge_verify_date',
	),
)); ?>
</div>
