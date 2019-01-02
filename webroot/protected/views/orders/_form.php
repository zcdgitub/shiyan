<style type="text/css">
	table.form .row .title
	{
		text-align:right;
		width:15%;
		font-size:14px;
		border:1px solid #ccc;
		border-bottom:none;
	}
	table.form .row .value
	{
		text-align:left;
		width:15%;
		height:45px;
		border:1px solid #ccc;
		border-bottom:none;
		border-left:none;
		padding-left:4px;
	}
</style>
<?php
/* @var $this OrdersController */
/* @var $model Orders */
/* @var $form CActiveForm */

?>
<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'orders-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
		'validateOnChange'=>true,
		'validateOnType'=>true,
	),
	'enableAjaxValidation'=>false,
	));
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<th class="title"><?=t('epmms','产品名称')?></th>
		<th class="title"><?=t('epmms','产品价格')?></th>
		<th class="title"><?=t('epmms','库存')?></th>
		<th class="title"><?=t('epmms','购买数量')?></th>
		<th class="title"></th>
		<th class="title"><?=t('epmms','小计')?></th>
	</tr>
	<?php foreach($ordersProduct as $i=>$product):?>
	<?php
		$htmlOptions=[];
		$attribute="[{$product->orders_product_product_id}]orders_product_currency";
		CHtml::resolveNameID($product, $attribute,$htmlOptions);
		$htmlOptions2=[];
		$attribute2="[{$product->orders_product_product_id}]orders_product_count";
		CHtml::resolveNameID($product, $attribute2,$htmlOptions2);
	?>
	<tr class="row">
		<td class="title">
			<?php echo $product->ordersProductProduct->product_name; ?>
		</td>
		<td class="title">
			<?php echo $product->ordersProductProduct->product_price; ?>
		</td>
		<td class="title">
			<?php echo $product->ordersProductProduct->product_stock; ?>
		</td>
		<td class="value">
			<?php echo $form->spinner2($product,"[{$product->orders_product_product_id}]orders_product_count",['size'=>4,'style'=>'height:20px;width:50px;margin:0 0','min'=>0,'max'=>$product->ordersProductProduct->product_stock,'price'=>$product->ordersProductProduct->product_price]); ?>
			<?php echo CHtml::ajaxLink('删除',['orders/delProduct','id'=>$product->orders_product_id],['success'=>new CJavaScriptExpression("function(data,sta){
			jQuery('#${htmlOptions2['id']}').spinner('value',0);
			jQuery('tr:has(#${htmlOptions2['id']})').remove();
			alert(data);
			}")]);?>
		</td>
		<td class="error">
			<?php echo $form->error($product,"[{$product->orders_product_product_id}]orders_product_count",array(),true); ?>
		</td>
		<td class="title money" id="<?=$htmlOptions['id']?>"><?php echo $product->ordersProductProduct->product_price*$product->orders_product_count?></td>
	</tr>
	<?php endforeach;?>
	<tr class="row">
		<th class="title"><?=t('epmms','总计')?></th>
		<td class="title" colspan="2"></td>
		<td class="value">
			<?php echo $form->hiddenField($model,"orders_currency"); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,"orders_currency",array(),true); ?>
		</td>
		<td class="title" id="Orders_orders_currency_show"><?php echo $total_currency?>￥</td>
	</tr>
	<tr class="row">
		<th class="title"><?=t('epmms','备注')?></th>
		<td class="title" colspan="2"></td>
		<td class="value">
			<?php echo $form->textArea($model,"orders_remark"); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,"orders_remark",array(),true); ?>
		</td>
		<td class="tip" ></td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->