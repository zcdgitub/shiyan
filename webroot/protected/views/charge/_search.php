<?php
/* @var $this ChargeController */
/* @var $model Charge */
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
			<?php echo $form->label($model,'charge_sn'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_sn',array('size'=>20,'maxlength'=>10)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'chargeMemberinfo[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_bank_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'charge_bank_id',Bank::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('charge_bank_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_bank_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_bank_account',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_bank_sn'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_bank_sn'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_bank_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_bank_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_bank_account_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_bank_account_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_finance_type_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'charge_finance_type_id',FinanceType::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('charge_finance_type_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'charge_remark'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'charge_remark',array('rows'=>6, 'cols'=>50)); ?>
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
