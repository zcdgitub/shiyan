<?php
/* @var $this BackupController */
/* @var $data Backup */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('backup_name')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->backup_name), array('view', 'id'=>$data->backup_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('backup_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->backup_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('backup_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->backup_add_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('backup_remark')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->backup_remark); ?></td>
	</tr>

</table>
</div>