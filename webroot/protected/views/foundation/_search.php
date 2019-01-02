<?php
/* @var $this FoundationController */
/* @var $model Foundation */
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
			<?php echo $form->label($model,'foundation_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'foundation_member_id'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'foundation_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'foundation_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'foundation_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->verify($model,'foundation_is_verify'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'foundation_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'foundation_verify_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
