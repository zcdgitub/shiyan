<?php
/* @var $this FinanceController */
/* @var $data Finance */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('finance_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->finance_id), array('view', 'id'=>$data->finance_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('finance_award')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->finance_award); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('finance_mod_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->finance_mod_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('finance_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->financeType->finance_type_id); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('finance_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->financeMemberinfo->memberinfo_account); ?></td>
	</tr>

</table>
</div>