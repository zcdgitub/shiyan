<?php
/* @var $this AwardPeriodSumController */
/* @var $data awardPeriodSum */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_period_sum_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->award_period_sum_id), array('view', 'id'=>$data->award_period_sum_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_period_sum_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->awardPeriodSumMemberinfo->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_period_sum_src_memberinfo_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->awardPeriodSumSrcMemberinfo->memberinfo_account); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_period_sum_currency')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->award_period_sum_currency); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_period_sum_period')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->award_period_sum_period); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_period_sum_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->award_period_sum_add_date); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('award_period_sum_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->awardPeriodSumType->sum_type_id); ?></td>
	</tr>

</table>
</div>