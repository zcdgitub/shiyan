<?php
/**
 * Created by PhpStorm.
 * User: hetao
 * Date: 13-10-26
 * Time: 上午9:51
 */

class Proxy extends CComponent
{
	public $gateway='http://m.baochitong.com/receive/bankpay.asp';
	public $key;
	public $partner;
	public $return_url;
	public $notify_url;
	public $order_sn;
	public $order_currency;

	public function init()
	{
		Yii::import('ext.qianhai.Request');
		Yii::import('ext.qianhai.Notify');
	}

	public function buildForm()
	{
		$params=[
			'MerchantID'=>$this->partner,
			'Return_url'=>urlencode(Yii::app()->request->hostInfo . CHtml::normalizeUrl($this->notify_url)),
			'Merchant_url'=>urlencode(Yii::app()->request->hostInfo . CHtml::normalizeUrl($this->return_url)),
			'TradeDate'=>date('YmdHis'),
			'TransID'=>$this->order_sn,
			'OrderMoney'=>$this->order_currency,
			'pName'=>'',
			'uName'=>user()->name,
			'uId'=>user()->id,
			'md5key'=>$this->key,
			'gateway'=>$this->gateway
		];
		$sender=new Request($params);
		return $sender->buildForm();
	}
	public function buildFormTest($config=[])
	{
		$sender=new Notify($config);
		return $sender->buildForm();
	}
	public function verifyNotify()
	{
		$params=[];
		$notify = new Notify($params);
		$result=$notify->return_verify();
		if($result)
		{
			echo 'ok';
			return ['order_sn'=>$result->TransID,'order_currency'=>$result->factMoney];
		}
		else
		{
			echo 'error';
			return false;
		}
	}

	public function verifyReturn()
	{
		$params=[];
		$notify = new Notify($params);
		$result=$notify->return_verify();
		if($result)
		{
			return ['order_sn'=>$result->TransID,'order_currency'=>$result->factMoney];
		}
		else
		{
			return false;
		}
	}
}