<?php
/* @var $this AwardMonthSumController */
/* @var $model AwardMonthSum */
/* @var $form CActiveForm */
?>

<table class="wide form">
<?php
$form=$this->beginWidget('ActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'award_month_sum_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'awardMonthSumMemberinfo[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'award_month_sum_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'award_month_sum_currency'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'award_month_sum_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'award_month_sum_date'); ?>
		</td>
		<td class="hint"></td>
	</tr>


</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
