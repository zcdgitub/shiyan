<?php
/* @var $this DealController */
/* @var $data Deal */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('deal_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->deal_id), array('view', 'id'=>$data->deal_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('deal_sale_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->dealSale->sale_id); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('deal_buy_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->dealBuy->buy_id); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('deal_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->deal_currency); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('deal_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->deal_date); ?></td>
	</tr>

</table>
</div>