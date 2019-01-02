<?php
/* @var $this HelpController */
/* @var $data Help */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('help_title')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->help_title), array('view', 'id'=>$data->help_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('help_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->help_type); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('help_title')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->help_title); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('help_content')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->help_content); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('help_mod_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->help_mod_date); ?></td>
	</tr>

</table>
</div>