<?php
/* @var $this MenuNavController */
/* @var $data MenuNav */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('menu_name')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->menu_name), array('view', 'id'=>$data->menu_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('menu_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->menu_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('menu_url')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->menu_url); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('menu_pid')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->menuP->menu_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('menu_order')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->menu_order); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('menu_mod_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->menu_mod_date); ?></td>
	</tr>

</table>
</div>