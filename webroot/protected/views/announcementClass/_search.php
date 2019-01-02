<?php
/* @var $this AnnouncementClassController */
/* @var $model AnnouncementClass */
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
			<?php echo $form->label($model,'class_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'class_name',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'class_intro'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'class_intro',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'class_sort'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'class_sort'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
