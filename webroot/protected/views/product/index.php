<?php
/* @var $this ProductController */
/* @var $dataProvider CActiveDataProvider */
registerCssFile(themeBaseUrl() . '/css/product.css');
$this->breadcrumbs=array(
	t('epmms','产品'),
);

$this->menu=array(
	array('label'=>t('epmms','添加产品'), 'url'=>array('create')),
	array('label'=>t('epmms','管理产品'), 'url'=>array('admin')),
	array('label'=>t('epmms','缺货产品'), 'url'=>array('short')),
);
?>

<h1><?=t('epmms','浏览产品')?></h1>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'ajaxUpdate'=>false,
	'itemView'=>'_view',
)); ?>
