<style type="text/css">
*{margin: 0;padding: 0;}
body{
    background-image: url('../images/background.png');
    background-attachment: fixed;
    background-size:100% 100%;
}
#da{
    width:100%;
    height:100%;
    position: relative
}     
.login-box{
    width:270px;
    color:#fff;
    margin:10px auto;
    padding: 30px;
    overflow: hidden;
    background:rgba(255,255,255, 0.3);
    margin-top: 320px;
    margin-left: 50px;
    /*position: absolute;
    top:50%;
    left:50%;*/
}
.loog{
    width:280px;
    height:180px;
    margin:50px auto;
}
.loog img{
    width:100%;
    height:100%;
}

.clearfix{
    margin-top: 15px;
  overflow: hidden;
  position: relative;
}
  .clearfix label{
    width:60px;
    display: block;
    float: left;
    margin-right: 5px;
  }
  .clearfix input{
    width:150px;
    padding: 2px 0;
    float: left;
  }
  .aaa input{
    width:70px;
  }

  .login-box>div span i{
    color:red;
  }
  
  .aaa img {
    position: absolute;
    width: 80px;
    height:25px;
    right: 40px;
    color: #fff;
    top: 0px;
  }
  div .btn  input{
    height:30px;
    width:230px;
    border:none;
   cursor: pointer;
    font-size: 16px;
    color:#fff; 
    margin-top: 5px;
   margin-bottom: 5px;
    background:red;
    outline: none;
    position:relative;
    z-index:100;
    /*behavior: url(ie-css3.htc); */
  }
  .buttons div{
    margin-left: 120px;
  }
  .buttons div a{
    text-decoration: none;
    color: yellow;
  
  }
  .required{
    font-weight: bold;
    font-family: Microsoft Yahei;
    font-size: 13px;
  }
.errorMessage{
    color: red;
  
   
}
</style>

<body>
<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
<div id="da">  
      
        <div class="login-box">
            <div class="clearfix bbb">
               <?php echo $form->labelEx($model, 'username'); ?>
               <?php echo $form->textField($model, 'username'); ?>
             </div>
                <?php echo $form->error($model, 'username'); ?> 
              
              
          
            <div class="clearfix bbb">
               <?php echo $form->labelEx($model, 'password'); ?>
               <?php echo $form->passwordField($model, 'password'); ?>
            </div>
                  <?php echo $form->error($model, 'password'); ?><?php if ($model->captchaVisible()): ?>
          
            <div class="clearfix  aaa bbb">
                <?php echo $form->labelEx($model, 'verifyCode'); ?>
                <?php  echo $form->textField($model, 'verifyCode');?>
                                      
                <?php
                   $this->widget('CCaptcha', array('clickableImage' => true, 'showRefreshButton' => false, 'imageOptions' => ['style' => 'vertical-align: bottom;']));
                        ?>
             </div>
                <?php echo $form->error($model, 'verifyCode'); ?>
               
                      
         
            <?php endif; ?>
            <?php if (config('auth', 'autologin')): ?>
                <div class="row rememberMe">
                            <?php echo $form->checkBox($model, 'rememberMe'); ?>
                            <?php echo $form->label($model, 'rememberMe'); ?>
                            <?php echo $form->error($model, 'rememberMe'); ?>
                </div>
            <?php endif; ?>
      
             <div class="row buttons btn">
                              
                               
              <?php echo CHtml::submitButton(t('epmms', '登录'), array('name' => 'user')); ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
           <div>
             <?php if (user()->checkAccess('Memberinfo.Create')) echo CHtml::link('注册', ['memberinfo/create']); ?>
                              <!--   &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo CHtml::link(t('epmms', '忘记密码'), '/site/forgotPassword'); ?> -->
            </div>
           </div>
    
 </div> 
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
