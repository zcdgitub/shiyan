<?php
/* @var $this WithdrawalsController */
/* @var $data Withdrawals */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->withdrawals_id), array('view', 'id'=>$data->withdrawals_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->withdrawalsMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->withdrawals_currency); ?></td>
	</tr>
	<tr class="odd">
		<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_tax')); ?></b></td>
		<td class="value"><?php echo CHtml::encode($data->withdrawals_tax); ?></td>
	</tr>
	<tr class="odd">
		<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_real_currency')); ?></b></td>
		<td class="value"><?php echo CHtml::encode($data->withdrawals_real_currency); ?></td>
	</tr>
	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->withdrawals_add_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_is_verify')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->withdrawals_is_verify,'verify')); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->withdrawals_verify_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_remark')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->withdrawals_remark); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_finance_type_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->withdrawalsFinanceType->finance_type_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('withdrawals_sn')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->withdrawals_sn); ?></td>
	</tr>

</table>
</div>