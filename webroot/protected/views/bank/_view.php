<?php
/* @var $this BankController */
/* @var $data Bank */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bank_name')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->bank_name), array('view', 'id'=>$data->bank_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bank_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->bank_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bank_is_preset')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->bank_is_preset,'preset')); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('bank_is_enable')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->bank_is_enable,'enable')); ?></td>
	</tr>

</table>
</div>