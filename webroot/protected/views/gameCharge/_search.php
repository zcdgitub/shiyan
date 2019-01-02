<?php
/* @var $this GameChargeController */
/* @var $model GameCharge */
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
			<?php echo $form->label($model,'charge_recommend'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'charge_recommend',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('charge_recommend' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_name',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_phone'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_phone',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_age'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_age'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_account',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_money'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_money',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->verify($model,'charge_is_verify'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_verify_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
