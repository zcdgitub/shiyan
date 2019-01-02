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
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_account'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'userinfo_account',array('size'=>20,'maxlength'=>50)); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_account'); ?></td>
	</tr>

	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_name'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'userinfo_name',array('size'=>20,'maxlength'=>50)); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_name'); ?></td>
	</tr>

	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_sex'); ?>：</td>
		<td class="value" ><?php echo $form->sex($model,'userinfo_sex'); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_sex'); ?></td>
	</tr>

	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_email'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'userinfo_email',array('size'=>20,'maxlength'=>256)); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_email'); ?></td>
	</tr>

	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_mobi'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'userinfo_mobi',array('size'=>20,'maxlength'=>20)); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_mobi'); ?></td>
	</tr>
	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_jobtitle'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'userinfo_jobtitle',array('size'=>20,'maxlength'=>50)); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_jobtitle'); ?></td>
	</tr>
	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'userinfo_role'); ?>：</td>
		<td class="value" ><?php echo $form->role($model,'userinfo_role'); ?></td>
		<td class="error" ><?php echo $form->error($model,'userinfo_role'); ?></td>
	</tr>	
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/reg.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->