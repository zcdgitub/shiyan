<?php
/* @var $this ProductController */
/* @var $data Product */
?>

<div class="view">
	<div class="p_image">
	<?php 
	$p_image=image(path2url(params('product.image')) . $data->product_image_url,'产品图片');
	echo hyperlink($p_image,array('view','id'=>$data->product_id))
	?>
	</div>
	<div class="p_title">
	<?php echo hyperlink(encode($data->product_title),array('view','id'=>$data->product_id));?>


	<?php if(!user()->isAdmin())echo CHtml::ajaxLink('添加到购物车',['orders/add','id'=>$data->product_id],['success'=>new CJavaScriptExpression("function(data,sta){if(data.success)alert('添加成功'); else alert('添加失败');}")]);?>
	</div>
	<div class="p_name">
	<b><?php echo CHtml::encode($data->getAttributeLabel('product_name')); ?>:</b>
	<?php echo CHtml::encode($data->product_name); ?>
	</div>
	<div class="p_name">
		<b><?php echo CHtml::encode($data->getAttributeLabel('product_class_id')); ?>:</b>
		<?php echo @CHtml::encode($data->productClass->product_name); ?>
	</div>
	<div class="p_price">
	<b><?php echo CHtml::encode($data->getAttributeLabel('product_price')); ?>:</b>
	<?php echo CHtml::encode($data->product_price); ?>
	</div>
	
	<div class="p_price">
		<b><?php echo CHtml::encode($data->getAttributeLabel('product_stock')); ?>:</b>
		<?php echo CHtml::encode($data->product_stock); ?>
	</div>
	<?if(!user()->isAdmin()):?>
	<div class="p_star">
		<b>我的评级:</b>
		<?php
		$my_star=Star::model()->findByAttributes(['star_product_id'=>$data->product_id,'star_member_id'=>user()->id]);
		$this->widget('ext.DzRaty.DzRaty', array(
			'name' => $data->product_name . "_my_star",
			'value' =>is_null($my_star)?null:$my_star->star_grade,
			'options' => array(
				'cancel' => TRUE,
				'click' => "js:function(score, evt){\$.get('setStar',{product:{$data->product_id},star:score},function(data){alert('评级成功')});}",
				'mouseover' => "js:function(score, evt){ $('#score-info').html('点击星星! 当前评级是: '+score); }",
			),
		));
		?>
	</div>
	<?endif;?>
	<div class="p_star">
		<b>产品评级:</b>
		<?php
		$this->widget('ext.DzRaty.DzRaty', array(
			'model' => $data,
			'attribute' => 'product_star',
			'name'=>$data->product_name . "_product_star",
			'options' => array(
				'readOnly' => TRUE
			)
		));
		
		?>
	</div>
	<div class="p_date">
	<b><?php echo CHtml::encode($data->getAttributeLabel('product_mod_date')); ?>:</b>
	<?php
		$product_date=new DateTime($data->product_mod_date);
		echo CHtml::encode($product_date->format('Y-m-d')); 
	?>
	</div>
</div>