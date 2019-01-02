<?php
/*
 *类名：ChinabankNotify
 *功能：付款过程中服务器通知类
*/
class Notify extends CComponent
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

	/**
	 * 支付成功返回订单号和支付金额的数组，支付失败返回false
	 */
	function return_verify()
	{
		$this->initParams();
		$Md5Sign = $this->TransID . $this->Result . $this->factMoney  . $this->SuccTime;
		$md5info = md5($Md5Sign);
		if($md5info==$this->Md5Sign && $this->Result==1)
		{
			return $this;
		}
		else
		{
			return false;
		}
    }
	public function getParams()
	{
		return array(
			'TransID'=>$this->TransID,
			'Result'=>$this->Result,
			'factMoney'=>$this->factMoney,
			'SuccTime'=>$this->SuccTime,
			'Md5Sign'=>$this->Md5Sign,
		);
	}
	public function buildForm()
	{
		$Md5Sign =$this->TransID . $this->Result . $this->factMoney . $this->SuccTime ;
		$this->Md5Sign = (md5($Md5Sign));
		$form=CHtml::beginForm($this->gateway,'post',['name'=>'eform','id'=>'eform']);
		foreach($this->params as $key=>$value)
		{
			$form.=CHtml::TextField($key,$value);
		}
		$form.=CHtml::submitButton('Test');
		$form.=CHtml::endForm();
		//$cs = Yii::app()->clientScript;
		//$cs->registerScript(uniqid('pay_'),"\$('#eform').submit();",CClientScript::POS_READY);
		return $form;
	}

	public function initParams()
	{
		if(is_array($_REQUEST))
		{
			foreach($_REQUEST as $name=>$value)
			{
				$this->$name=$value;
			}
		}
	}
}
?>
