<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->product_id=>array('view','id'=>$model->product_id),
	'Update',
);

$this->menu=array(
	array('label'=>t('epmms','浏览产品'), 'url'=>array('index')),
	array('label'=>t('epmms','添加产品'), 'url'=>array('create')),
	array('label'=>t('epmms','查看产品'), 'url'=>array('view', 'id'=>$model->product_id)),
	array('label'=>t('epmms','管理产品'), 'url'=>array('admin')),
);
?>

<h1><?=t('epmms','修改产品')?> <?php echo $model->product_name; ?></h1>


<?php echo $this->renderPartial('_form', array('model'=>$model,'model1'=>$model1,'data'=>$data)); ?>