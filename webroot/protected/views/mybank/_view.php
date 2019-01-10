<?php
/* @var $this MybankController */
/* @var $data Mybank */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('mybank_account')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->mybank_account), array('view', 'id'=>$data->mybank_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('mybank_bank_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->mybankBank->bank_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('mybank_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->mybank_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('mybank_account')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->mybank_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('mybank_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->mybankMemberinfo->memberinfo_account); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('mybank_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->mybank_add_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('mybank_is_default')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->mybank_is_default); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('mybank_address')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->mybank_address); ?></td>
	</tr>

</table>
</div>