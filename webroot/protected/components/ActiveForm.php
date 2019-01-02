<?php
/**
 *
 * @author hetao
 *
 */
class ActiveForm extends CActiveForm
{

	/**
	 * 姓名输入
	 * @param unknown_type $model
	 * @param unknown_type $attribute
	 * @param unknown_type $htmlOptions
	 * @return string
	 */
	public function sex($model,$attribute,$htmlOptions=array())
	{
		$htmlOptions['empty']=t('epmms','请选择姓别');
		return CHtml::activeDropDownList($model,$attribute,array(0=>t('epmms','男'),1=>t('epmms','女'),2=>t('epmms','保密')),$htmlOptions);
	}
	/**
	 * 启用设置
	 * @param unknown_type $model
	 * @param unknown_type $attribute
	 * @param unknown_type $htmlOptions
	 * @return string
	 */
	public function enable($model,$attribute,$htmlOptions=array())
	{
		$htmlOptions['prompt']=t('epmms','请选择是否启用');
		return CHtml::activeDropDownList($model,$attribute,array(1=>t('epmms','启用'),0=>t('epmms','禁用')),$htmlOptions);
	}

	/**
	 * 是否设置
	 * @param unknown_type $model
	 * @param unknown_type $attribute
	 * @param unknown_type $htmlOptions
	 * @return string
	 */
	public function yesno($model,$attribute,$htmlOptions=array())
	{
		$htmlOptions['prompt']=t('epmms','请选择是否');
		return CHtml::activeDropDownList($model,$attribute,array(1=>t('epmms','是'),0=>t('epmms','否')),$htmlOptions);
	}
	/**
	 * 预置选项
	 * @param unknown_type $model
	 * @param unknown_type $attribute
	 * @param unknown_type $htmlOptions
	 * @return string
	 */
	public function preset($model,$attribute,$htmlOptions=array())
	{
		return CHtml::activeDropDownList($model,$attribute,array(1=>t('epmms','预置'),0=>t('epmms','自定义')),$htmlOptions);
	}
	public function idCardType($model,$attribute,$htmlOptions=array())
	{
		$htmlOptions['prompt']=t('epmms','请选择证件类型');
		return CHtml::activeDropDownList($model,$attribute,array(0=>t('epmms','居民身份证'),1=>t('epmms','士兵证'),2=>t('epmms','军官证'),3=>t('epmms','警官证'),4=>t('epmms','护照'),5=>t('epmms','其它')),$htmlOptions);
	}
	public function date($model,$attribute,$htmlOptions=array())
	{
		return $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'language'=>webapp()->language,
				'options'=>array('dateFormat'=>'yy-mm-dd','changeYear'=>true,'changeMonth'=>true,'showWeek'=>true),
				'model'=>$model,
				'name'=>CHtml::resolveName($model, $attribute),
				'value'=>empty($model->$attribute)?null:webapp()->format->format($model->$attribute,'date')
				),true);
	}

	public function datetime($model,$attribute,$htmlOptions=array())
	{
		return $this->widget(
			'ext.EJuiDateTimePicker.EJuiDateTimePicker',
			array(
				'model'     => $model,
				'attribute' => $attribute,
				'language'=> webapp()->language,//default Yii::app()->language
				//'mode'    => 'datetime',//'datetime' or 'time' ('datetime' default)
				'options'   => array(
					'dateFormat' => 'yy-mm-dd',
					'timeFormat' => 'HH:mm',//'hh:mm tt' default
					'changeYear'=>true,
					'changeMonth'=>true,
					'showWeek'=>true
				),
			),true
		);
	}

	public function role($model,$attribute,$htmlOptions=array())
	{
		$rbac=new Rights();
		$roles=$rbac->getAuthItemSelectOptions(2,array('Guest','authenticated','admin','member','agent','epmms'));
		return CHtml::activeDropDownList($model,$attribute,$roles,$htmlOptions);
	}
	public function bank($model,$attribute,$htmlOptions=array())
	{
		$bank=new Bank();
		$banks=$bank->findAll(array(
				'select'=>'bank_id,bank_name',
				'condition'=>'bank_is_enable=:bank_is_enable',
				'params'=>array('bank_is_enable'=>1)
		)
		);
		$listData=CHtml::listdata($banks,'bank_id','bank_name');
		return CHtml::activeDropDownList($model,$attribute,$listData,array('prompt'=>t('epmms',t('epmms','请选择')) . $model->getAttributeLabel($attribute)));
	}
	public function robots($model,$attribute,$htmlOptions=array())
	{
		return CHtml::activeDropDownList($model,$attribute,array(0=>t('epmms','阻止搜索'),1=>t('epmms','允许搜索')),$htmlOptions);
	}	
	public function autologin($model,$attribute,$htmlOptions=array())
	{
		return CHtml::activeDropDownList($model,$attribute,array(0=>t('epmms','关闭自动登录'),1=>t('epmms','启用自动登录')),$htmlOptions);
	}
	public function captcha($model,$attribute,$htmlOptions=array())
	{
		return CHtml::activeDropDownList($model,$attribute,array(0=>t('epmms','关闭验证码'),1=>t('epmms','启用验证码'),2=>t('epmms','登录失败后显示')),$htmlOptions);
	}	
	public function verify($model,$attribute,$htmlOptions=array())
	{
		$htmlOptions['empty']=t('epmms','请选择审核状态');
		return CHtml::activeDropDownList($model,$attribute,array(0=>t('epmms','未审核'),1=>t('epmms','已审核')),$htmlOptions);
	}
	public function spinner2($model,$attribute,$htmlOptions=array())
	{
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$htmlOptions2=[];
		$attribute2="[{$model->orders_product_id}]orders_product_currency";
		CHtml::resolveNameID($model, $attribute2,$htmlOptions2);
		$options=['change'=> new CJavaScriptExpression("function(event,ui){\$('#{$htmlOptions2['id']}').text(jQuery('#{$htmlOptions['id']}').spinner('value')*{$htmlOptions['price']});var product_money=0;\$('.money').each(function(){product_money+=parseFloat($(this).text());});\$('#Orders_orders_currency_show').text(product_money + '￥');\$('#Orders_orders_currency').val(product_money);return true;}")];
		unset($htmlOptions['price']);
		if(isset($htmlOptions['min']))
			$options['min']=$htmlOptions['min'];
		if(isset($htmlOptions['max']))
			$options['max']=$htmlOptions['max'];
		$output=CHtml::activeTextField($model,$attribute,$htmlOptions);
		$this->widget('ext.JuiSpinner.JuiSpinner',array(
									'options'=>$options,
									'htmlOptions'=>$htmlOptions
									)
									
		);
		return $output;
	}
	public function spinner($model,$attribute,$htmlOptions=array())
	{
		$options=[];
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		if(isset($htmlOptions['min']))
			$options['min']=$htmlOptions['min'];
		if(isset($htmlOptions['max']))
			$options['max']=$htmlOptions['max'];
		$output=CHtml::activeTextField($model,$attribute,$htmlOptions);
		$this->widget('ext.JuiSpinner.JuiSpinner',array(
				'options'=>$options,
				'htmlOptions'=>$htmlOptions
			)

		);
		return $output;
	}
	public function editor($model,$attribute,$htmlOptions=array())
	{
		$items=['minWidth'=>'10px',
			'minHeight'=>'10px',
			'themeType'=>'simple',
			'langType'=>webapp()->language,
			'afterBlur'=>new CJavaScriptExpression('function(){this.sync();}'),
			'uploadJson'=>webapp()->createUrl('/product/fileUpload'),
			'fileManagerJson'=>webapp()->createUrl('/product/fileManager'),
			'allowFileManager'=>true,
			 'urlType'=>'domain',
			'items'=>[
				'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'cut', 'copy', 'paste',
				'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
				'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				'superscript', 'clearhtml', 'quickformat', 'selectall', '/',
				'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
				'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
				'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons',
				'anchor', 'link', 'unlink',
			]
		];
		if(isset($htmlOptions['items']))
		{
			$items=array_merge($items,$htmlOptions['items']);
			unset($htmlOptions['items']);
		}
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$output=CHtml::activeTextArea($model,$attribute,$htmlOptions);
		$this->widget('ext.kindeditor.KindEditor',
			array(
			'id'=>$htmlOptions['id'],	//Textarea id
			// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
			'items' =>$items
			)
		);
		return $output;
	}
	public function textPlain($model,$attribute)
	{
		return CHtml::resolveValue($model,$attribute);
	}
	public function mapOrder($model,$attribute,$htmlOptions=array())
	{
		$max_order=config('map','branch');
		$order=[];
		for($i=1;$i<=$max_order;$i++)
		{
			$order[$i]=chr(64+$i);
		}
		return CHtml::activeRadioButtonList($model,$attribute,$order,array_merge($htmlOptions,['separator'=>' ','style'=>'width:13px;']));
	}
	public function colorPicker($model,$attribute,$htmlOptions)
	{
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$options['onBeforeShow']=new CJavaScriptExpression('function(){$(this).ColorPickerSetColor(this.value);}');
		$options['onSubmit']=new CJavaScriptExpression('function(hsb,hex,rgb,el){$(el).val(hex);$(el).ColorPickerHide();}');
		$this->widget('ext.colorpicker.ColorPicker',['id'=>$htmlOptions['id'],'options'=>$options]);
		return CHtml::activeTextField($model,$attribute,$htmlOptions);
	}
	public function autoAccount($model,$attribute,$htmlOptions)
	{
		$htmlOptions=array_merge(['style'=>'width:60px;'],$htmlOptions);
		$output='<span class="autoaccount">'. CHtml::activeHiddenField($model,$attribute);
		$output.=CHtml::resolveValue($model,$attribute) . '</span>';
		$output.= '&nbsp;&nbsp;' . CHtml::ajaxButton(t('epmms','换一个'),'/memberinfo/autoAccount',['cache'=>false,'update'=>'.autoaccount'],$htmlOptions);
		return $output;
	}
	public function WithdrawalsType($model,$attribute,$htmlOptions=array())
	{
		return CHtml::activeDropDownList($model,$attribute,array(1=>t('epmms','提现中扣'),0=>t('epmms','余额中扣')),$htmlOptions);
	}
	/**
	 * Validates one or several models and returns the results in JSON format.
	 * This is a helper method that simplifies the way of writing AJAX validation code.
	 * @param mixed $models a single model instance or an array of models.
	 * @param array $attributes list of attributes that should be validated. Defaults to null,
	 * meaning any attribute listed in the applicable validation rules of the models should be
	 * validated. If this parameter is given as a list of attributes, only
	 * the listed attributes will be validated.
	 * @param boolean $loadInput whether to load the data from $_POST array in this method.
	 * If this is true, the model will be populated from <code>$_POST[ModelClass]</code>.
	 * @return string the JSON representation of the validation error messages.
	 */
	public static function validate($models, $attributes=null, $loadInput=true)
	{
		$result=array();
		if(!is_array($models))
			$models=array($models);
		foreach($models as $model)
		{
			$modelName=CHtml::modelName($model);
			if($loadInput && isset($_POST[$modelName]))
				$model->attributes=$_POST[$modelName];
			$model->validate($attributes);
			foreach($model->getErrors() as $attribute=>$errors)
				$result[CHtml::activeId($model,$attribute)]=$errors;
		}
		return function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);
	}
}

?>