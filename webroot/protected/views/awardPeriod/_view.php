<?php
/* @var $this AwardPeriodController */
/* @var $data AwardPeriod */
?>

<tr class="<?=$index%2==0?'odd':'even'?>">
<td class="value" ><?php echo CHtml::encode(@$data->awardPeriodMemberinfo->memberinfo_account); ?></td>
<td class="value" ><?php echo CHtml::encode($data->award_period_currency); ?></td>
<td class="value" ><?php echo CHtml::encode(@$data->awardPeriodType->award_type_name); ?></td>
<td class="value" ><?php echo CHtml::encode($data->award_period_add_date); ?></td>
</tr>
