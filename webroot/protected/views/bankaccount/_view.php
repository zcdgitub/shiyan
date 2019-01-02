<?php
/* @var $this BankaccountController */
/* @var $data Bankaccount */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_account')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->bankaccount_account), array('view', 'id'=>$data->bankaccount_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_bank_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bankaccountBank->bank_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_account')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bankaccount_account); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_provience')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bankaccount_provience); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_area')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bankaccount_area); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_branch')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bankaccount_branch); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_mobi')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bankaccount_mobi); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_phone')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bankaccount_phone); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_qq')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bankaccount_qq); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bankaccount_is_enable')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->bankaccount_is_enable,'enable')); ?></td>
	</tr>

</table>
</div>