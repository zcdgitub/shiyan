<?php
/* @var $this MapEditController */
/* @var $data MapEdit */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->map_edit_id), array('view', 'id'=>$data->map_edit_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_src_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->mapEditSrcMember->showName); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_dst_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->mapEditDstMember->showName); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->map_edit_type); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->map_edit_add_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->map_edit_verify_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_operate')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->map_edit_operate); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_info')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->map_edit_info); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('map_edit_is_verify')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->map_edit_is_verify); ?></td>
	</tr>

</table>
</div>