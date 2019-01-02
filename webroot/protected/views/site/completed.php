<h2>找回密码成功</h2>
请检查<?php if($type=='email'):?>你的Email<?php else:?>请检查你的手机短信<?php endif;?>
<br/>
<?php
echo CHtml::link('登录', '/site/login');
?>
