<?php
/* @var $this JackpotInfoController */
/* @var $model JackpotInfo */
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
			<?php echo $form->label($model,'info_start_time'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'info_start_time'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'info_end_time'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'info_end_time'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'info_start_balance'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'info_start_balance',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'info_lucky_balance'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'info_lucky_balance',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'info_end_balance'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'info_end_balance',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'info_number'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'info_number'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
