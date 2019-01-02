<?php
/* @var $this MemberTypeController */
/* @var $data MemberType */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membertype_name')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->membertype_name), array('view', 'id'=>$data->membertype_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membertype_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membertype_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membertype_desc')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membertype_desc); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membertype_level')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membertype_level); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membertype_mod_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membertype_mod_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membertype_color')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membertype_color); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membertype_money')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membertype_money); ?></td>
	</tr>

</table>
</div>