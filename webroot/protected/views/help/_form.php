<?php
/* @var $this HelpController */
/* @var $model Help */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'help-form',
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
	<?php if($this->action->id=='create'):?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'help_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'help_type',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'help_type',array(),false); ?>
		</td>
	</tr>
	<?php endif;?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'help_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'help_title'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'help_title',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'help_content'); ?>
		</td>
		<td class="value">
			<?php echo $form->editor($model,'help_content',array('rows'=>25, 'cols'=>60)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'help_content',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->