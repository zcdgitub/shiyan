<?php
/* @var $this UserinfoController */
/* @var $model Userinfo */
/* @var $form CActiveForm */
?>

<div class="form" >

<?php
CHtml::$beforeRequiredLabel='<span class="required">*</span>';
CHtml::$afterRequiredLabel='';
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'userinfo-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo t('epmms','带{ss}的字段是必填项。',['{ss}'=>'<span class="required">*</span>']);?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_password'); ?>：</td>
		<td class="value" ><?php echo $form->passwordField($model,'userinfo_password',array('size'=>20,'maxlength'=>50)); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_password'); ?></td>
	</tr>
	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_password2'); ?>：</td>
		<td class="value" ><?php echo $form->passwordField($model,'userinfo_password2',array('size'=>20,'maxlength'=>50)); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_password2'); ?></td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '注册' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->