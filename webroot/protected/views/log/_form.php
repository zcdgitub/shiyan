<?php
/* @var $this LogController */
/* @var $model Log */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'log-form',
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
			<?php echo $form->labelEx($model,'log_category'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_category',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_category',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'log_source'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_source',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_source',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'log_operate'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_operate',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_operate',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'log_target'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_target',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_target',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'log_value'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_value',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_value',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'log_info'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_info',array('size'=>20,'maxlength'=>200)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_info',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'log_user'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_user',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_user',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'log_role'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_role',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_role',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'log_status'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'log_status'); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'log_status',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->