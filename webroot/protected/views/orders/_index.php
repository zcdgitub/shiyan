<?php
/* @var $this OrdersController */
/* @var $model Orders */

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	/*array('label'=>t('epmms','打印') . t('epmms',$model->modelName), 'url'=>array('print'),'linkOptions'=>['target'=>'_blank']),*/
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('orders-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<p>
<?php echo t('epmms','你可以输入一个比较运算符 ');?>(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
<?php echo t('epmms','或者');?> <b>=</b>) <?php echo t('epmms','在每个搜索值的前面来指定怎么匹配搜索结果。');?>
</p>

<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
&nbsp;&nbsp;
<?=CHtml::imageButton(themeBaseUrl() . '/images/Excel.png',
	['submit'=>CHtml::normalizeUrl(['','selTab'=>$selTab,'grid_mode'=>'export','exportType'=>'Excel5'])])?>
<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#orders-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	'orders_sn',
	array('class'=>'RelationDataColumn','name'=>'ordersMember.memberinfo_account'),
	'orders_currency',
    array('class'=>'DataColumn','name'=>'ordersMember.memberinfo_name'),
    array('class'=>'DataColumn','name'=>'ordersMember.memberinfo_phone'),
    array('class'=>'DataColumn','name'=>'ordersMember.memberinfo_address_provience'),
    array('class'=>'DataColumn','name'=>'ordersMember.memberinfo_address_area'),
    array('class'=>'DataColumn','name'=>'ordersMember.memberinfo_address_detail'),
   /* 'ordersMember.memberinfo_name',
    'ordersMember.memberinfo_phone',
    'ordersMember.memberinfo_address_provience',
    'ordersMember.memberinfo_address_area',
    'ordersMember.memberinfo_address_detail',*/
	'orders_remark',
	'log_name'=>'orders_logistics_name',
	'log_sn'=>'orders_logistics_sn',
	'orders_status',
	'orders_add_date',
	'verify_date'=>'orders_verify_date',
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	'delete'=>array(
	'class'=>'ButtonColumn',
	'template'=>'{delete}',
	),
	'verify'=>array(
	'class'=>'ButtonColumn',
	'template'=>'{verify}',
	'visible'=>true,
	'buttons'=>array('verify'=>array('isverify'=>'orders_is_verify')),
	'header'=>t('epmms','审核'),
)
);
//处理每种标签的特殊情况
unset($columns['verify']);
switch($selTab)
{
	case 0:
		//
		unset($columns['verify_date']);
		unset($columns['log_name']);
		unset($columns['log_sn']);
		if(!user()->isAdmin())
			$columns['pay']=['class'=>'CLinkColumn','label'=>'支付','urlExpression'=>'webapp()->createUrl("pay/create",["ordersId"=>$data->orders_id])'];
		break;
	case 1:
		unset($columns['delete']);
		unset($columns['verify']);
		unset($columns['log_name']);
		unset($columns['log_sn']);
		if(user()->isAdmin())
			$columns['send']=['class'=>'CLinkColumn','label'=>'发货','urlExpression'=>'webapp()->createUrl("orders/send",["id"=>$data->orders_id])'];
				if(user()->isAdmin())
			$columns['print']=['class'=>'CLinkColumn','linkHtmlOptions'=>['target'=>'_blank'] ,'label'=>'打印','urlExpression'=>'webapp()->createUrl("orders/print",["id"=>$data->orders_id])'];
		break;
	case 2:
		unset($columns['delete']);
		unset($columns['verify']);
		break;
}
$this->widget('EExcelView', array(
	'id'=>'orders-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
