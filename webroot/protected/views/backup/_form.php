<?php
/* @var $this BackupController */
/* @var $model Backup */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'backup-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
	)); 
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'backup_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'backup_name',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'backup_name',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'backup_remark'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'backup_remark',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'backup_remark',array(),false); ?>
		</td>
	</tr>
	<?php if($this->action->id=='upload'):?>
		<tr class="row">
			<td class="title">
				<?php echo "上传备份文件"; ?>
			</td>
			<td class="value">
				<?php echo $form->fileField($model,'file',array('size'=>20,'maxlength'=>20)); ?>
			</td>
			<td class="hint"></td>
			<td class="error">
			</td>
		</tr>
	<?endif;?>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->