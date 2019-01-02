<?php

/** 
 * @author hetao
 * 
 */
class OrdersForm extends Form
{
	public $activeForm=array(
		'class'=>'ActiveForm',
		'enableClientValidation'=>true,
		'enableAjaxValidation'=>true,
		'clientOptions'=>array(	'validateOnSubmit'=>true)
	);
	public $inputElementClass='FormInputElement';
	/**
	 * Renders the {@link elements} in this form.
	 * @return string the rendering result
	 */
	public function renderElements()
	{
		$output= <<<EOT
		<style type="text/css">
	table.orders .row .title
	{
		text-align:right;
		width:15%;
		font-size:14px;
		border:1px solid #ccc;
		border-bottom:none;
	}
	table.orders .row .value
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
EOT;
		$output.="<table class=\"form orders\">\n";
		$output.=<<<EOT
	<tr class="row">
		<th class="title"><?=t('epmms','产品名称')?></th>
		<th class="title"><?=t('epmms','产品价格')?></th>
		<th class="title"><?=t('epmms','库存')?></th>
		<th class="title"><?=t('epmms','购买数量')?></th>
		<th class="title"></th>
		<th class="title"><?=t('epmms','小计')?></th>
	</tr>
EOT;
		$output.=$this->renderElement(null);
		$output.="</table>\n";
		return $output;
	}


	/**
	 * Renders a single element which could be an input element, a sub-form, a string, or a button.
	 * @param mixed $element the form element to be rendered. This can be either a {@link CFormElement} instance
	 * or a string representing the name of the form element.
	 * @return string the rendering result
	 */
	public function renderElement($element)
	{
		$output='';
		$products=Product::model()->findAll();
		$ordersProduct=new OrdersProduct('create');
		$model=$this->getModel();
		$form=$this->getActiveFormWidget();
		foreach($products as $i=>$product)
		{
			$ordersProduct->orders_product_product_id=$product->product_id;
			$htmlOptions=[];
			$attribute="[{$product->product_id}orders_product_currency";
			CHtml::resolveNameID($ordersProduct, $attribute,$htmlOptions);
			$str_value=$form->spinner2($ordersProduct,"[{$product->product_id}]orders_product_count",['size'=>4,'style'=>'height:20px;width:50px;margin:0 0','min'=>0,'max'=>$product->product_stock,'price'=>$product->product_price]);
			$str_error=$form->error($ordersProduct,"[{$product->product_id}]orders_product_count",array(),true);
			$str_id=$htmlOptions['id'];
			$output.= <<<EOT
			<tr class="row">
				<td class="title">
					{$product->product_name}
				</td>
				<td class="title">
					{$product->product_price}
				</td>
				<td class="title">
					{$product->product_stock}
				</td>
				<td class="value">
					{$str_value}
				</td>
				<td class="error">
					{$str_error}
				</td>
				<td class="title money" id="{$str_id}">0</td>
			</tr>
EOT;
		}
		$str1=$form->hiddenField($model,"orders_currency");
		$str2=$form->error($model,"orders_currency",array(),true);
		$str3=$form->textArea($model,"orders_remark");
		$str4=$form->error($model,"orders_remark",array(),true);
		$output.= <<<EOT
		<tr class="row">
			<th class="title"><?=t('epmms','总计')?></th>
			<td class="title" colspan="2"></td>
			<td class="value">
				$str1
			</td>
			<td class="error">
				$str2
			</td>
			<td class="title" id="Orders_orders_currency_show">0</td>
		</tr>
		<tr class="row">
			<th class="title"><?=t('epmms','备注')?></th>
			<td class="title" colspan="2"></td>
			<td class="value">
				$str3
			</td>
			<td class="error">
				$str4
			</td>
			<td class="tip" ></td>
		</tr>
EOT;
		return $output;
	}
	/**
	 * Renders the body content of this form.
	 * This method mainly renders {@link elements} and {@link buttons}.
	 * If {@link title} or {@link description} is specified, they will be rendered as well.
	 * And if the associated model contains error, the error summary may also be displayed.
	 * The form tag will not be rendered. Please call {@link renderBegin} and {@link renderEnd}
	 * to render the open and close tags of the form.
	 * You may override this method to customize the rendering of the form.
	 * @return string the rendering result
	 */
	public function renderBody()
	{
		$output='';
		if($this->title!==null)
		{
			if($this->getParent() instanceof self)
			{
				$attributes=$this->attributes;
				unset($attributes['name'],$attributes['type']);
				$attributes['legend']=$this->title;
				$output='<tr><td>';
				ob_start();
				webapp()->controller->beginWidget('ext.coolfieldset.JCollapsibleFieldset',$attributes);
				$output.=ob_get_clean();
			}
			else
			{
				$output='<tr><td>';
				ob_start();
				webapp()->controller->beginWidget(
					'ext.coolfieldset.JCollapsibleFieldset',
					array(
						//'onlyFieldset'=>true,
						'legend'=>$this->title
					)
				);
				$output.=ob_get_clean();
			}
		}

		if($this->description!==null)
			$output.="<div class=\"description\">\n".$this->description."</div>\n";

		if($this->showErrorSummary && ($model=$this->getModel(false))!==null)
		{
			$output .= $this->getActiveFormWidget()->errorSummary($model) . "\n";
			if(!is_null($model->membermap))
			{
				$output .= $this->getActiveFormWidget()->errorSummary($model->membermap);
			}
		}

		$output.=$this->renderElements()."\n".$this->renderButtons()."\n";

		if($this->title!==null)
		{
			ob_start();
			webapp()->controller->endWidget('ext.coolfieldset.JCollapsibleFieldset');
			$output.=ob_get_clean();
			$output.="</td></tr>\n";
		}
		return $output;
	}
}
?>