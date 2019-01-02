<?php
/* @var $this TransferController */
/* @var $model Transfer */
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
			<?php echo $form->label($model,'transfer_src_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transferSrcMember[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'transfer_src_finance_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'transfer_src_finance_type',FinanceType::model()->getTransferSrcListData(),array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('transfer_src_finance_type' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'transfer_dst_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transferDstMember[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'transfer_dst_finance_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'transfer_dst_finance_type',FinanceType::model()->getTransferDstListData(),array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('transfer_dst_finance_type' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'transfer_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transfer_currency'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'transfer_add_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transfer_add_date'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'transfer_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transfer_verify_date'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'transfer_sn'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transfer_sn',array('size'=>20,'maxlength'=>10)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
