<style type="text/css">
    .button2 {
        FONT-SIZE: 12px;
        border: 1px solid #C6944F;
        background-attachment: fixed;
        background-color: #FFFFFF;
    }

    table.forms .row td.title {
        width: 95px;
        text-align: right;
    }

    table.forms .row td.value {
        width: 175px;

    }

    .value input {
        height: 18px;
        width: 160px;
    }

    table.forms .value1 input {
        height: 18px;
        width: 80px;
    }
    .buttons input {
        margin-left: -62px;
         height:32px;
        width:230px;
        border:none;
        font-size: 16px;
        color:#fff; 
        margin-top: 10px;
        background:red;
        outline: none;
        cursor: pointer;
       
    }
    .buttons input{
        margin-bottom: 10px;
    }
    .buttons a{
       padding-right: 12;
        text-decoration: none;
        color: yellow;
    }
    .required{
        font-family: Microsoft Yahei;
        font-size:14px;
    }
  /*  #loginForm_username{
        margin-left: -30px;
    }*/
</style>

<body>
<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
<table class="forms" style=" width:1167px; height:673px; margin:0 auto; margin-top:-14px; margin-left:-200px; position: relative;" border="0"
       cellpadding="0" cellspacing="0" align="center">
    <tbody>

    <tr>
        <td align="left" valign="top">
            <div style="margin:50px 0 0 320px; width:450px; height:150px">
                <table height="154" border="0" cellpadding="0" cellspacing="0" width="100%" style="position: absolute; margin-top: -77px;margin-left: -25%; top: 50%; left: 50%">
                    <tbody>
                    <tr>
                        <td width="95" align="right" height="32" style="color: white;font-weight: bold "><?php echo $form->labelEx($model, 'username'); ?></td>
                        <td class="value" width="175"
                            align="left"><?php echo $form->textField($model, 'username'); ?></td>
                        <td class="error" style="border:none"
                            align="left"><?php echo $form->error($model, 'username'); ?></td>
                    </tr>
                    <tr>
                        <td width="95" align="right" height="32" style="color: white;font-weight: bold "><?php echo $form->labelEx($model, 'password'); ?></td>
                        <td class="value" width="175"
                            align="left"><?php echo $form->passwordField($model, 'password'); ?></td>
                        <td class="error" style="border:none"
                            align="left"><?php echo $form->error($model, 'password'); ?><?php if ($model->captchaVisible()): ?></td>

                    </tr>

                    <tr>
                        <td width="95" align="right" height="32" style="color: white;font-weight: bold "><?php echo $form->labelEx($model, 'verifyCode'); ?>
                        </td>
                        <td class="value1" width="175" align="left"><?php
                            echo $form->textField($model, 'verifyCode');
                            ?>

                            <?php
                            $this->widget('CCaptcha', array('clickableImage' => true, 'showRefreshButton' => false, 'imageOptions' => ['style' => 'vertical-align: bottom;']));
                            ?>
                        </td>
                        <td class="error" style="border:none"
                            align="left"><?php echo $form->error($model, 'verifyCode'); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (config('auth', 'autologin')): ?>
                        <div class="row rememberMe">
                            <?php echo $form->checkBox($model, 'rememberMe'); ?>
                            <?php echo $form->label($model, 'rememberMe'); ?>
                            <?php echo $form->error($model, 'rememberMe'); ?>
                        </div>
                    <?php endif; ?>

                    <tr>
                        <td>&nbsp;</td>
                        <td width="195">
                            <div class="row buttons">
                                <?php echo CHtml::submitButton(t('epmms', '登录'), array('name' => 'user')); ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php if (user()->checkAccess('Memberinfo.Create')) echo CHtml::link('注册', ['memberinfo/create']); ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo CHtml::link(t('epmms', '忘记密码'), '/site/forgotPassword'); ?>
                            </div>

                        </td>
                        <td width="307">
                            <!--<input src="/images/bt2.jpg" name="submit2" height="29" border="0" type="image" width="48">--></td>
                    </tr>
                </table>

            </div>
        </td>
    </tr>

    </tbody>
</table>
<?php $this->endWidget(); ?>
<script>
    <?php if(params('fecshop')):?>
    $(document).ready(function(){
        $("input[name='user']").click(function(){
            var username,password;
            username=$('#LoginForm_username').val();
            password=$('#LoginForm_password').val();
            var url="<?=params('shop_login_url')?>";
            $.ajax(url,{method:'POST',async:true,headers: {"Accept": "application/json; charset=utf-8","X-Requested-With":"XMLHttpRequest"},xhrFields:{withCredentials: true},data:{'editForm[email]':username,'editForm[password]':password,'ajax':'login-form'},success:function(data){
                    console.log(data);
                    $('#login-form').submit();
                }});
            return false;
        });
    });
    <?php endif;?>
</script>