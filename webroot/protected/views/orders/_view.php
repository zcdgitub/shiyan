<?php
/* @var $this OrdersController */
/* @var $data Orders */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('orders_sn')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->orders_id), array('view', 'id'=>$data->orders_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('orders_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->ordersMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('orders_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->orders_currency); ?></td>
	</tr>

	<tr class="odd">
		<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('orders_remark')); ?></b></td>
		<td class="value"><?php echo CHtml::encode($data->orders_remark); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('orders_status')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->orders_status); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('orders_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->orders_add_date); ?></td>
	</tr>

</table>
</div>