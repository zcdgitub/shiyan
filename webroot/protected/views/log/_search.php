<?php
/* @var $this LogController */
/* @var $model Log */
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
			<?php echo $form->label($model,'log_category'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_category',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'log_source'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_source',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'log_operate'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_operate',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'log_target'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_target',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'log_value'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_value',array('size'=>20,'maxlength'=>16)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'log_info'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_info',array('size'=>20,'maxlength'=>200)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'log_user'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_user',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'log_role'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_role',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'log_status'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_status'); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
