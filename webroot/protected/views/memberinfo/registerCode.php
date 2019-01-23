<?php
/**
 * Created by PhpStorm.
 * User: 涛
 * Date: 14-8-17
 * Time: 上午12:31
 */
?>
<style>
	.p-center
	{
		text-align: center;
	}
</style>
<div style="width:80%;margin:20px auto auto auto;text-align: center">
<p calss="p-center">
	扫描下面的二维可以推荐好友注册，请转发下面的二维码和链接。
</p>
<p class="p-center">
<?php
$this->widget('application.extensions.qrcode.QRCodeGenerator',array(
	'data' => $create_url,
	'filename'=>'registerCode_'. user()->id . '.png',
	'subfolderVar' => false,
	'matrixPointSize' => 6,
	'displayImage'=>true, // default to true, if set to false display a URL path
	'errorCorrectionLevel'=>'L', // available parameter is L,M,Q,H
)); // 1 to 10 only
?>
</p>
<p class="p-center">
<?php
echo CHtml::link($create_url,$create_url);
?>
</p>
</div>