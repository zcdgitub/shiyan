<?php
/* @var $this PayLogController */
/* @var $model PayLog */

$this->breadcrumbs=array(
	t('epmms',$order->modelName)=>array('orders/index'),
	t('epmms','支付'),
);

$this->menu=array(
	array('label'=>t('epmms','管理') . t('epmms',$order->modelName), 'url'=>array('orders/index')),
);
?>

<h1><?php echo t('epmms','支付订单') ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'order'=>$order)); ?>