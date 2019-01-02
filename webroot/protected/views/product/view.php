<?php
/* @var $this ProductController */
/* @var $model Product */

registerCssFile(themeBaseUrl() . '/css/product.css');
$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->product_id,
);

$this->menu=array(
	array('label'=>t('epmms','浏览产品'), 'url'=>array('index')),
	array('label'=>t('epmms','添加产品'), 'url'=>array('create')),
	array('label'=>t('epmms','修改产品'), 'url'=>array('update', 'id'=>$model->product_id)),
	array('label'=>t('epmms','删除产品'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->product_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>t('epmms','管理产品'), 'url'=>array('admin')),
);
?>

<h1><?=t('epmms','查看产品')?> #<?php echo $model->product_name; ?></h1>
<div class="product_head" >
	<div class="product_image" ><img src="<?php echo path2url(params('product.image')) . $model->product_image_url?>" /></div>
	<div class="product_summary" >
		<div class="title"><h1><?php echo $model->product_title?></h1></div>
		<ul id="summary">
			<li>
				<div class="dt" ><?php echo $model->getAttributeLabel('product_name')?>：</div>
				<div class="dd" ><?php echo $model->product_name?></div>
			</li>
			<li>
				<div class="dt" ><?php echo $model->getAttributeLabel('product_price')?>：</div>
				<div class="dd" ><?php echo $model->product_price?></div>
			</li>
			<li>
				<div class="dt" ><?php echo $model->getAttributeLabel('product_stock')?>：</div>
				<div class="dd" ><?php echo $model->product_stock?></div>
			</li>
			<li>
				<div class="dt" ><?php echo $model->getAttributeLabel('product_mod_date')?>：</div>
				<div class="dd" ><?php echo $model->product_mod_date?></div>
			</li>

		</ul>
	</div>
</div>
<div class="clear"></div>
<div class="product_info" >
<div class="product_info_head" ><h1><?=t('epmms','查看产品')?></h1></div>
<div class="product_info_content">
<?php 
echo $model->product_info;
?>
</div>
</div>