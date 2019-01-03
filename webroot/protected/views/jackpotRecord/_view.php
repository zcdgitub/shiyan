<?php
/* @var $this JackpotRecordController */
/* @var $data JackpotRecord */
$type = [1=>'首单奖',2=>'幸运奖',3=>'尾单奖'];
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jackpot_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->jackpot_id), array('view', 'id'=>$data->jackpot_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jackpot_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->jackpot->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jackpot_money')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->jackpot_money); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jackpot_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($type[$data->jackpot_type]); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jackpot_start_time')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(date('Y-m-d H:i:s',$data->jackpot_start_time)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jackpot_end_time')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(date('Y-m-d H:i:s',$data->jackpot_end_time)); ?></td>
	</tr>

</table>
</div>