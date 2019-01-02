<div style="width:500px;margin:auto auto;">
<h1><?=$result_str;?></h1>
	订单号:<?=$result['order_sn']?>
	<br/>
	支付金额:<?=$result['order_currency']?>
<p style="text-align: right;">
<?php
/**
 * Created by PhpStorm.
 * User: hetao
 * Date: 13-10-26
 * Time: 下午10:06
 */

echo CHtml::link('返回订单管理',$this->createUrl('orders/index'));
?>
</div>
</p>