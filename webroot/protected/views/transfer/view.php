<?php
/* @var $this TransferController */
/* @var $model Transfer */

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
		'transfer_sn',
		array('name'=>'transferSrcMember.memberinfo_account','label'=>$model->getAttributeLabel('transfer_src_member_id')),
		array('name'=>'transferSrcFinanceType.finance_type_name','label'=>$model->getAttributeLabel('transfer_src_finance_type')),
		array('name'=>'transferDstMember.memberinfo_account','label'=>$model->getAttributeLabel('transfer_dst_member_id')),
		array('name'=>'transferDstFinanceType.finance_type_name','label'=>$model->getAttributeLabel('transfer_dst_finance_type')),
		'transfer_currency',
		'transfer_tax',
		'transfer_remark',
		array('name'=>'transfer_is_verify','type'=>'verify'),
		'transfer_add_date',
		'transfer_verify_date',

	),
)); ?>
</div>
