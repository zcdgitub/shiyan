<?php
/* @var $this ConfigSmtpController */
/* @var $model ConfigSmtp */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'config-smtp-form',
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
			<?php echo $form->labelEx($model,'config_smtp_server'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_smtp_server',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'config_smtp_server',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_smtp_port'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_smtp_port'); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'config_smtp_port',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_smtp_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_smtp_account',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'config_smtp_account',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_smtp_password'); ?>
		</td>
		<td class="value">
			<?php echo $form->passwordField($model,'config_smtp_password',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'config_smtp_password',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_smtp_email'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_smtp_email',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'config_smtp_email',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_smtp_enable'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'config_smtp_enable'); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'config_smtp_enable',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->