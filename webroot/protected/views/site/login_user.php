<style type="text/css">
table.forms .row td.title
{
	width: 95px;
	text-align:right;
}
table.forms .row td.value
{
width: 175px;

}
.value input
{
	height:18px;
	width: 160px;
}
table.forms .value1 input
{
	height:18px;
	width: 80px;
}

 </style>
   <?php $form=$this->beginWidget('ActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
<table class="forms" style="background:url(/images/bg4.png) no-repeat; width:1167px; height:673px; margin:0 auto; margin-left:-200px;" border="0" cellpadding="0" cellspacing="0" align="center">
      <tbody>

   <tr>
        <td align="left" valign="top"><div style="margin:-160px 0 0 300px; width:512px; height:146px">
          <table height="154" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody><tr>
              <td width="95" align="right" height="32"><?php echo $form->labelEx($model,'username'); ?>:</td>
              <td class="value" width="175" align="left"><?php echo $form->textField($model,'username'); ?></td>
              <td class="error" style="border:none" align="left"><?php echo $form->error($model,'username'); ?></td>
            </tr>
            <tr>
              <td width="95" align="right" height="32"><?php echo $form->labelEx($model,'password'); ?>：</td>
              <td class="value"  width="175" align="left" ><?php echo $form->passwordField($model,'password'); ?></td>
              <td class="error" style="border:none" align="left"><?php echo $form->error($model,'password'); ?><?php if($model->captchaVisible()):?></td>
              
            </tr>
            	

            <tr>
              <td width="95" align="right" height="32"><?php echo $form->labelEx($model,'verifyCode'); ?>：</td>
              <td class="value1"  width="175"  align="left"><?php
				echo $form->textField($model,'verifyCode');
			?>
	
			<?php
				$this->widget('CCaptcha',array('clickableImage'=>true,'showRefreshButton'=>false,'imageOptions'=>['style'=>'vertical-align: bottom;']));
			?>
              </td>
              <td class="error" style="border:none" align="left"><?php echo $form->error($model,'verifyCode'); ?></td>
            </tr>
            	<?php endif;?>
<?php if(config('auth','autologin')):?>
	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
<?php endif;?>

            <tr>
              <td>&nbsp;</td>
              <td width="195">
	<?php if(config('auth','autologin')):?>
	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
<?php endif;?>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','登录'),array('name'=>'user')); ?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php if(user()->checkAccess('Memberinfo.Create')) echo CHtml::link('注册',['memberinfo/create']);?>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo CHtml::link('忘记密码', '/site/forgotPassword');?>
	</div>

              </td>
              <td width="307"><!--<input src="/images/bt2.jpg" name="submit2" height="29" border="0" type="image" width="48">--></td>
            </tr>
          </table>

        </div></td>
      </tr>

</tbody></table>
      <?php $this->endWidget(); ?>
