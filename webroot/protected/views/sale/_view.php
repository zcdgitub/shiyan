<?php
/* @var $this SaleController */
/* @var $data Sale */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('sale_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->sale_id), array('view', 'id'=>$data->sale_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('sale_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->saleMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('sale_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->sale_currency); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('sale_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->sale_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('sale_money')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->sale_money); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('sale_remain_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->sale_remain_currency); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('sale_status')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->sale_status); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('sale_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->sale_verify_date); ?></td>
	</tr>

</table>
</div>