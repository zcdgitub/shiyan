<style type="text/css">
	body{
		background-color: #ffffff;
	}
</style>
<div style="width:600px;padding-top:50%; " >
<div style="margin:0 auto;background: #bc7d08;text-align:center">
<br />
<h3 style="color:#FFF"><?php echo t('epmms','二级密码验证')?></h3>
<div class="form" >
<?php $form=$this->beginWidget('ActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<table class="form"  width="100%">
	<tr class="row"  >
		<td class="title" align="center" style="background-color:#FFF"><?php echo $form->labelEx($model,'password'); ?></td>
		<td class="value" style="background-color:#FFF"><?php echo $form->passwordField($model,'password',['autocomplete'=>'off']); ?></td>
		<td class="error" style="background-color:#FFF"><?php echo $form->error($model,'password'); ?></td>
	</tr>
</table>
<?php if(config('auth','autologin2')):?>
	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
<?php endif;?>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','确定')); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
</div>
</div>