<?php
/* @var $this MemberTypeController */
/* @var $model MemberType */
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
			<?php echo $form->label($model,'membertype_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membertype_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'membertype_desc'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membertype_desc',array('size'=>20,'maxlength'=>200)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'membertype_level'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membertype_level'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'membertype_bill'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membertype_bill'); ?>
		</td>
		<td class="hint"></td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'membertype_mod_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membertype_mod_date',array('size'=>20,'maxlength'=>3)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'membertype_color'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membertype_color',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'membertype_money'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membertype_money',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
