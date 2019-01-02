<?php
/* @var $this MemberinfoItemController */
/* @var $model MemberinfoItem */
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
			<?php echo $form->label($model,'memberinfo_item_field'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'memberinfo_item_field',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'memberinfo_item_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'memberinfo_item_title',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'memberinfo_item_visible'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'memberinfo_item_visible'); ?>
		</td>
		<td class="hint"></td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'memberinfo_item_required'); ?>
		</td>
		<td class="value">
			<?php echo $form->yesno($model,'memberinfo_item_required'); ?>
		</td>
		<td class="hint"></td>
	</tr>
<!--	<tr class="row">
		<td class="title">
			<?php /*echo $form->label($model,'memberinfo_item_required'); */?>
		</td>
		<td class="value">
			<?php /*echo $form->textField($model,'memberinfo_item_required'); */?>
		</td>
		<td class="hint"></td>
	</tr>-->

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
