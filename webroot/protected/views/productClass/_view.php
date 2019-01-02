<?php
/* @var $this ProductClassController */
/* @var $data ProductClass */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('product_name')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->product_name), array('view', 'id'=>$data->product_class_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('product_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->product_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('product_info')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->product_info); ?></td>
	</tr>

</table>
</div>