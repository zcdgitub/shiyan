<?php
/* @var $this BankController */
/* @var $model Bank */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'bank-form',
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
			<?php echo $form->labelEx($model,'bank_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'bank_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bank_name',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'bank_is_enable'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'bank_is_enable'); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'bank_is_enable',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->