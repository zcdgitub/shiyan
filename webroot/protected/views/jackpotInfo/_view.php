<?php
/* @var $this JackpotInfoController */
/* @var $data JackpotInfo */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('info_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->info_id), array('view', 'id'=>$data->info_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('info_start_time')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->info_start_time); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('info_end_time')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->info_end_time); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('info_start_balance')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->info_start_balance); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('info_lucky_balance')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->info_lucky_balance); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('info_end_balance')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->info_end_balance); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('info_number')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->info_number); ?></td>
	</tr>

</table>
</div>