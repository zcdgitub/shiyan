<?php
/* @var $this ConfigBackupController */
/* @var $model ConfigBackup */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'config-backup-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>false,
	)); 
?>

	<p class="note">
		<?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?>
		<p style="width:500px;margin:10px auto 10px auto;text-align: left">
		占位符说明:<br/>{name}姓名,{account}帐号,{showName}姓名或帐号,{password1}一级密码,{password2}二级密码,{award}奖金,{date}日期,{time}时间,{datetime}日期时间
		<p/>
	</p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_sms_is_register'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'config_sms_is_register'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_sms_is_register',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_sms_register'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'config_sms_register',['cols'=>30,'rows'=>10]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_sms_register',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_sms_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'config_sms_is_verify'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_sms_is_verify',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_sms_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'config_sms_verify',['cols'=>30,'rows'=>10]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_sms_verify',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_sms_is_award'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'config_sms_is_award'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_sms_is_award',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_sms_award'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'config_sms_award',['cols'=>30,'rows'=>10]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_sms_award',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->