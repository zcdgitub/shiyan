<?php
/* @var $this AwardYearSumController */
/* @var $model AwardYearSum */
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
			<?php echo $form->label($model,'award_year_sum_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'awardYearSumMemberinfo[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'award_year_sum_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'award_year_sum_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'award_year_sum_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'award_year_sum_date'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'award_year_sum_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'award_year_sum_type',SumType::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('award_year_sum_type' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
