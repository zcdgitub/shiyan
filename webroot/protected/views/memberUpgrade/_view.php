<?php
/* @var $this MemberUpgradeController */
/* @var $data MemberUpgrade */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('member_upgrade_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->member_upgrade_id), array('view', 'id'=>$data->member_upgrade_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('member_upgrade_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->memberUpgradeMember->showName); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('member_upgrade_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->memberUpgradeType->membertype_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('member_upgrade_is_verify')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->member_upgrade_is_verify); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('member_upgrade_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->member_upgrade_add_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('member_upgrade_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->member_upgrade_verify_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('member_upgrade_period')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->member_upgrade_period); ?></td>
	</tr>

</table>
</div>