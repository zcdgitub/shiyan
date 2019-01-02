<?php
/* @var $this MessagesController */
/* @var $data Messages */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('messages_title')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->messages_title), array('view', 'id'=>$data->messages_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('messages_title')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->messages_title); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('messages_content')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->messages_content); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('messages_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->messages_add_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('messages_sender_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->messagesSenderMember->memberinfo_account); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('messages_receiver_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->messagesReceiverMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('messages_session')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->messages_session); ?></td>
	</tr>

</table>
</div>