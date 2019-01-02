<?php
/* @var $this UserinfoController */
/* @var $model Userinfo */
/* @var $form CActiveForm */
?>

<div class="form" >

<?php

$form=$this->beginWidget('ActiveForm', array(
	'id'=>'memberinfo-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>true,
));

?>

	<p class="note"><?php echo t('epmms','带{ss}的字段是必填项。',['{ss}'=>'<span class="required">*</span>']);?></p>

	<?php echo $form->errorSummary($model); ?>
	<table class="form">
		<?php if($isMy):?>
		<tr class="row">
			<td class="title" ><?php echo $form->labelEx($passwordAuth,'password'); ?></td>
			<td class="value" ><?php echo $form->passwordField($passwordAuth,'password',array('size'=>20,'maxlength'=>50,'autocomplete'=>'off')); ?></td>
			<td class="error" ><?php echo $form->error($passwordAuth,'password'); ?></td>
		</tr>
		<tr class="row">
			<td class="title" ><?php echo $form->labelEx($passwordAuth,'password2'); ?></td>
			<td class="value" ><?php echo $form->passwordField($passwordAuth,'password2',array('size'=>20,'maxlength'=>50,'autocomplete'=>'off')); ?></td>
			<td class="error" ><?php echo $form->error($passwordAuth,'password2'); ?></td>
		</tr>
		<?php endif;?>
		<tr class="row">
			<td class="title" ><?php echo $form->labelEx($model,'memberinfo_password'); ?></td>
			<td class="value" ><?php echo $form->passwordField($model,'memberinfo_password',array('size'=>20,'maxlength'=>50,'autocomplete'=>'off')); ?>
			</td>
			<td class="error" ><?php echo $form->error($model,'memberinfo_password'); ?></td>
		</tr>
		<tr class="row">
			<td class="title" ><?php echo $form->labelEx($model,'memberinfo_password_repeat'); ?></td>
			<td class="value" ><?php echo $form->passwordField($model,'memberinfo_password_repeat',array('size'=>20,'maxlength'=>50,'autocomplete'=>'off')); ?></td>
			<td class="error" ><?php echo $form->error($model,'memberinfo_password_repeat'); ?></td>
		</tr>
		<tr class="row">
			<td class="title" ><?php echo $form->labelEx($model,'memberinfo_password2'); ?></td>
			<td class="value" ><?php echo $form->passwordField($model,'memberinfo_password2',array('size'=>20,'maxlength'=>50,'autocomplete'=>'off')); ?></td>
			<td class="error" ><?php echo $form->error($model,'memberinfo_password2'); ?></td>
		</tr>
		<tr class="row">
			<td class="title" ><?php echo $form->labelEx($model,'memberinfo_password_repeat2'); ?></td>
			<td class="value" ><?php echo $form->passwordField($model,'memberinfo_password_repeat2',array('size'=>20,'maxlength'=>50,'autocomplete'=>'off')); ?></td>
			<td class="error" ><?php echo $form->error($model,'memberinfo_password_repeat2'); ?></td>
		</tr>
	</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton( t('epmms','保存')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->