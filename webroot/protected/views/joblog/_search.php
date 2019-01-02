<?php
/* @var $this JoblogController */
/* @var $model Joblog */
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
			<?php echo $form->label($model,'jlgjobid'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jlgjobid'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jlgstatus'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jlgstatus',array('size'=>20,'maxlength'=>1)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jlgstart'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jlgstart'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jlgduration'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jlgduration'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
