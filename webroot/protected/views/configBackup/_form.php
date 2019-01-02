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

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_backup_days'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_backup_days'); ?>
		</td>
		<td class="hint">0表示不限制</td>
		<td class="error">
			<?php echo $form->error($model,'config_backup_days',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_backup_count'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_backup_count'); ?>
		</td>
		<td class="hint">0表示不限制</td>
		<td class="error">
			<?php echo $form->error($model,'config_backup_count',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->