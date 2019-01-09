<?php
/* @var $this DupController */
/* @var $data Dup */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('dup_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->dup_id), array('view', 'id'=>$data->dup_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('dup_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->dupMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('dup_money')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->dup_money); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('dup_is_verify')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->dup_is_verify,'verify')); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('dup_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->dup_add_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('dup_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->dup_verify_date); ?></td>
	</tr>

</table>
</div>