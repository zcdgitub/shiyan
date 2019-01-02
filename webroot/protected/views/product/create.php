<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'浏览产品', 'url'=>array('index')),
	array('label'=>'管理产品', 'url'=>array('admin')),
	array('label'=>'缺货产品', 'url'=>array('short')),
);
?>

<h1>添加产品</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'data'=>$data)); ?>
