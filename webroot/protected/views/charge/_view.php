<?php
/* @var $this ChargeController */
/* @var $data Charge */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->charge_id), array('view', 'id'=>$data->charge_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_sn')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_sn); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->chargeMemberinfo->memberinfo_account); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_currency); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_is_verify')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->charge_is_verify,'verify')); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_add_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_bank_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->chargeBank->bank_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_bank_account')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_bank_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_bank_address')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_bank_address); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_bank_sn')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_bank_sn); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_bank_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_bank_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_bank_account_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_bank_account_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_finance_type_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->chargeFinanceType->finance_type_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_remark')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_remark); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('charge_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->charge_verify_date); ?></td>
	</tr>

</table>
</div>