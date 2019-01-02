<?php
/* @var $this BankaccountController */
/* @var $model Bankaccount */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$this->widget('ext.pacSelector.SelectorWidget', array(
		'model' => $model,
		'attributeProvince'=>'bankaccount_provience',
'attributeCity'=>'bankaccount_area',

));
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'bankaccount-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>true,
	)); 
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'bankaccount_bank_id'); ?>
		</td>
		<td class="value">
			<?php 
			$listModel=Model::model('Bank')->findAll();
			$listData=CHtml::listdata($listModel,'bank_id','bank_name');
			echo $form->dropDownList($model,'bankaccount_bank_id',$listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('bankaccount_bank_id' )))
			; ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bankaccount_bank_id',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'bankaccount_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'bankaccount_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bankaccount_name',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'bankaccount_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'bankaccount_account',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bankaccount_account',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'bankaccount_provience'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'bankaccount_provience',array()); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bankaccount_provience',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'bankaccount_area'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'bankaccount_area',array()); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bankaccount_area',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'bankaccount_branch'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'bankaccount_branch',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bankaccount_branch',array(),false); ?>
		</td>
	</tr>

<!--	<tr class="row">
		<td class="title">
			<?php /*echo $form->labelEx($model,'bankaccount_mobi'); */?>
		</td>
		<td class="value">
			<?php /*echo $form->textField($model,'bankaccount_mobi',array('size'=>20,'maxlength'=>20)); */?>
		</td>
		<td class="error">
			<?php /*echo $form->error($model,'bankaccount_mobi',array(),false); */?>
		</td>
	</tr>-->

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'bankaccount_is_enable'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'bankaccount_is_enable'); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bankaccount_is_enable',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->