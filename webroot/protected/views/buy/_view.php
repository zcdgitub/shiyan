<?php
/* @var $this BuyController */
/* @var $data Buy */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('buy_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->buy_id), array('view', 'id'=>$data->buy_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('buy_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->buyMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('buy_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->buy_currency); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('buy_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->buy_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('buy_money')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->buy_money); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('buy_status')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->buy_status); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('buy_tax')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->buy_tax); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('buy_real_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->buy_real_currency); ?></td>
	</tr>

</table>
</div>