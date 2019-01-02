<?php
class Request extends CComponent
{
	public $attributes;
	public function __set($name,$value)
	{
		$setter='set'.$name;
		if(method_exists($this,$setter))
			$this->$setter($value);
		else
			$this->attributes[$name]=$value;
	}
	public function __get($name)
	{
		$getter='get'.$name;
		if(method_exists($this,$getter))
			return $this->$getter();
		elseif(isset($this->attributes[$name]))
			return $this->attributes[$name];
		else
			throw new CException(Yii::t('yii','Property "{class}.{property}" is not defined.',
				array('{class}'=>get_class($this), '{property}'=>$name)));
	}
	public function __construct($config=[])
	{
		$this->configure($config);
	}
	public function configure($config)
	{
		if(is_string($config))
			$config=require(Yii::getPathOfAlias($config).'.php');
		if(is_array($config))
		{
			foreach($config as $name=>$value)
				$this->$name=$value;
		}
	}
	public function getParams()
	{
        return array(
            'MerchantID'=>$this->MerchantID,
            'TradeDate'=>$this->TradeDate,
			'TransID'=>$this->TransID,
			'OrderMoney'=>$this->OrderMoney,
            'pName'=>$this->pName,
            'uName'=>$this->uName,
            'uId'=>$this->uId,
            'Merchant_url'=>$this->Merchant_url,
            'Return_url'=>$this->Return_url,
            'Md5Sign'=>$this->Md5Sign,
			'md5key'=>$this->md5key
        );
    }
	public function buildForm()
	{
		$Md5Sign = $this->MerchantID . $this->TradeDate . $this->TransID . $this->OrderMoney . $this->Merchant_url . $this->Return_url . $this->md5key;
		$this->Md5Sign = (md5($Md5Sign));
		$form=CHtml::beginForm($this->gateway,'post',['name'=>'eform','id'=>'eform']);
		foreach($this->params as $key=>$value)
		{
			$form.=CHtml::hiddenField($key,$value);
		}
		$form.=CHtml::endForm();
		$cs = Yii::app()->clientScript;
		$cs->registerScript(uniqid('pay_'),"\$('#eform').submit();",CClientScript::POS_READY);
		return $form;
	}
}