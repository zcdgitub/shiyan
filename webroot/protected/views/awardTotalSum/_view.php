<?php
/* @var $this AwardTotalSumController */
/* @var $data awardTotalSum */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_total_sum_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->award_total_sum_id), array('view', 'id'=>$data->award_total_sum_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_total_sum_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->awardTotalSumMemberinfo->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_total_sum_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->award_total_sum_currency); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_total_sum_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->award_total_sum_add_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_total_sum_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->awardTotalSumType->sum_type_name); ?></td>
	</tr>

</table>
</div>