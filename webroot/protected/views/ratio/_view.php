<?php
/* @var $this RatioController */
/* @var $data Ratio */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('ratio_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->ratio_id), array('view', 'id'=>$data->ratio_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('ratio_value')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->ratio_value); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('ratio_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->ratio_add_date); ?></td>
	</tr>

</table>
</div>