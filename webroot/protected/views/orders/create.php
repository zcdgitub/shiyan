<?php
/* @var $this OrdersController */
/* @var $model Orders */

if(is_null($model) || is_null($total_currency))
{
    echo "<h1 style='text-align: center'>购物车为空，请先购物</h1>";
    webapp()->end();
}

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','添加'),
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1>我的购物车</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'ordersProduct'=>$ordersProduct,'total_currency'=>@$total_currency)); ?>