<?php
/* @var $this WithdrawalsController */
/* @var $model Withdrawals */
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
			<?php echo $form->label($model,'withdrawals_sn'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'withdrawals_sn',array('size'=>20,'maxlength'=>10)); ?>
		</td>
		<td class="hint"></td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'withdrawals_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'withdrawalsMember[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'withdrawals_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'withdrawals_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>


	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'withdrawals_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'withdrawals_verify_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'withdrawals_remark'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'withdrawals_remark',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'withdrawals_finance_type_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'withdrawals_finance_type_id',FinanceType::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('withdrawals_finance_type_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>



</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
