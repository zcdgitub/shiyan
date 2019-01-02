<?php
/* @var $this OrdersController */
/* @var $model Orders */
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
			<?php echo $form->label($model,'orders_sn'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'orders_sn'); ?>
		</td>
		<td class="hint"></td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'orders_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'ordersMember[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'orders_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'orders_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'orders_status'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'orders_status'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
