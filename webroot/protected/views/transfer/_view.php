<?php
/* @var $this TransferController */
/* @var $data Transfer */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->transfer_id), array('view', 'id'=>$data->transfer_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_src_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->transferSrcMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_src_finance_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->transferSrcFinanceType->finance_type_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_dst_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->transferDstMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_dst_finance_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->transferDstFinanceType->finance_type_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->transfer_currency); ?></td>
	</tr>

	<tr class="even">
		<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_currency')); ?></b></td>
		<td class="value"><?php echo CHtml::encode($data->transfer_tax); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_remark')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->transfer_remark); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_is_verify')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->transfer_is_verify,'verify')); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->transfer_add_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->transfer_verify_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('transfer_sn')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->transfer_sn); ?></td>
	</tr>

</table>
</div>