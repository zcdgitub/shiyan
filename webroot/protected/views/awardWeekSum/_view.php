<?php
/* @var $this AwardWeekSumController */
/* @var $data AwardWeekSum */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_week_sum_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->award_week_sum_id), array('view', 'id'=>$data->award_week_sum_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_week_sum_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->awardWeekSumMemberinfo->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_week_sum_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->award_week_sum_currency); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_week_sum_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->award_week_sum_date,'date')); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_week_sum_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->award_week_sum_add_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_week_sum_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->award_week_sum_type); ?></td>
	</tr>

</table>
</div>