<?php
/* @var $this AppropriateController */
/* @var $data Appropriate */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('appropriate_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->appropriate_id), array('view', 'id'=>$data->appropriate_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('appropriate_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->appropriate_currency); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('appropriate_finance_type_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->appropriateFinanceType->finance_type_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('appropriate_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->appropriate_add_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('appropriate_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->appropriateMemberinfo->memberinfo_account); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('appropriate_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->appropriate_type); ?></td>
	</tr>

</table>
</div>