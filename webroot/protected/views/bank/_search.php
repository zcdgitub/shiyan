<?php
/* @var $this BankController */
/* @var $model Bank */
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
			<?php echo $form->label($model,'bank_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'bank_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bank_is_preset'); ?>
		</td>
		<td class="value">
			<?php echo $form->preset($model,'bank_is_preset'); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bank_is_enable'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'bank_is_enable'); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
