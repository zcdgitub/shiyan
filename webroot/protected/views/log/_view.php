<?php
/* @var $this LogController */
/* @var $data Log */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->log_id), array('view', 'id'=>$data->log_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_category')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_category); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_source')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_source); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_operate')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_operate); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_target')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_target); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_value')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_value); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_info')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_info); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_ip')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_ip); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_user')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_user); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_role')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_role); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_add_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('log_status')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->log_status); ?></td>
	</tr>

</table>
</div>