<?php
/* @var $this AgentController */
/* @var $data Agent */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('agent_title')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->agent_title), array('view', 'id'=>$data->agent_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('agent_title')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->agent_title); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('agent_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->agentMemberinfo->memberinfo_nickname); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('agent_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->agent_currency); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('agent_memo')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->agent_memo); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('agent_is_verify')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->agent_is_verify); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('agent_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->agent_add_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('agent_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->agent_verify_date); ?></td>
	</tr>

</table>
</div>