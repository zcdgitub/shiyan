<?php
/* @var $this HelpController */
/* @var $model Help */
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
			<?php echo $form->label($model,'help_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'help_type',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'help_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'help_title',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'help_content'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'help_content',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'help_mod_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'help_mod_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
