<?php
/* @var $this SigningController */
/* @var $model Signing */
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
			<?php echo $form->label($model,'signing_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'signing_member_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('signing_member_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'signing_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->verify($model,'signing_is_verify'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'signing_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'signing_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'signing_is_refund'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'signing_is_refund'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'signing_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'signing_verify_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'signing_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'signing_type'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
