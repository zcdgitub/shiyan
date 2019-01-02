<?php
/* @var $this OrdersController */
/* @var $model Orders */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->orders_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->orders_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
		'orders_sn',
		array('name'=>'ordersMember.memberinfo_account','label'=>$model->getAttributeLabel('orders_member_id')),
		'orders_currency',
		'ordersMember.memberinfo_name',
		'ordersMember.memberinfo_phone',
		'ordersMember.memberinfo_address_provience',
		'ordersMember.memberinfo_address_area',
		'ordersMember.memberinfo_address_detail',
		'orders_remark',
		'orders_status',
		'orders_add_date',
		'orders_verify_date'
	),
));
$flag=false;
?>

</div>
<h1>购买产品</h1>
<div class="epview">

<table class="detail-view">
	<tbody>
	<tr style="background: none repeat scroll 0 0 #B7D6E7;"><th style="text-align: center;">产品</th><th style="text-align: center;">数量</th><th style="text-align: center;">价格</th><th style="text-align: center;">合计</th></tr>
	<?php foreach(OrdersProduct::model()->findAll('orders_product_orders_id=:id',[':id'=>$model->orders_id]) as $product):?>
		<tr class="<?=($flag=!$flag)?'odd':'even'?>">
			<td><?=$product->ordersProductProduct->product_name?></td>
			<td><?=$product->orders_product_count?></td>
			<td><?=$product->orders_product_price?></td>
			<td><?=$product->orders_product_currency?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
</div>