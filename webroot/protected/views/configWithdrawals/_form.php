<?php
/* @var $this ConfigWithdrawalsController */
/* @var $model ConfigWithdrawals */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'config-withdrawals-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>false,
	)); 
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_withdrawals_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->WithdrawalsType($model,'config_withdrawals_type'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_withdrawals_type',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_withdrawals_tax'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_withdrawals_tax'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_withdrawals_tax',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_withdrawals_tax_cap'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_withdrawals_tax_cap'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_withdrawals_tax_cap',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_withdrawals_min'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_withdrawals_min'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_withdrawals_min',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_withdrawals_scale'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_withdrawals_scale'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_withdrawals_scale',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->