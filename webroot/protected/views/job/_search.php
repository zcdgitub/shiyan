<?php
/* @var $this JobController */
/* @var $model Job */
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
			<?php echo $form->label($model,'jobname'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'jobname',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jobdesc'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'jobdesc',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jobenabled'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'jobenabled'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jobnextrun'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jobnextrun'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'joblastrun'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'joblastrun'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
