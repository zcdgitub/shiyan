<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'浏览产品', 'url'=>array('index')),
	array('label'=>'添加产品', 'url'=>array('create')),
	array('label'=>'缺货产品', 'url'=>array('short')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('product-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>缺货产品</h1>



<?php $this->widget('GridView', array(
	'id'=>'product-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>array(
		array('class'=>'DataColumn','value'=>'$row+1','header'=>'序号','htmlOptions' => array('style'=>'width:40px')),
		'product_name',
		'product_title',
		/* array('class'=>'DataColumn','name'=>'productClass.product_name','header'=>'分类名称'),*/
		array('class'=>'RelationDataColumn','name'=>'productClass.product_name','header'=>'分类名称','releationClass'=>'productClass'),
		'product_price',
		'product_mod_date',
		array(
				'class'=>'ButtonColumn',
				'template'=>'{view}',
				'viewButtonImageUrl'=>themeBaseUrl() . '/images/button/yello-mid-view2.gif',
		),
		array(
				'class'=>'ButtonColumn',
				'template'=>'{update}',
				'updateButtonImageUrl' =>themeBaseUrl() . '/images/button/yello-mid-edit2.gif',
		
		),
		array(
				'class'=>'ButtonColumn',
				'template'=>'{delete}',
				'deleteButtonImageUrl'=>themeBaseUrl() . '/images/button/yello-mid-del2.gif',
		),

	),
)); ?>