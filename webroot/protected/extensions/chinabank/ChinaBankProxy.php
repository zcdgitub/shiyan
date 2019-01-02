<?php
/**
 * Created by PhpStorm.
 * User: hetao
 * Date: 13-10-26
 * Time: 上午9:51
 */

class ChinaBankProxy extends CComponent
{
	public $gateway='http://pay3.chinabank.com.cn/PayGate';
	//public $gateway='http://localhost:9022/User/chinabank/chinabank/test-rev.asp';
	public $key;
	public $partner;
	public $return_url;
	public $order_sn;
	public $order_currency;

	public function init()
	{
		Yii::import('ext.chinabank.ChinabankDirectRequest');
		Yii::import('ext.chinabank.ChinabankNotify');
	}

	public function buildForm()
	{
		$sender=new ChinabankDirectRequest($this->gateway,$this->key,Yii::app()->request->hostInfo . CHtml::normalizeUrl($this->return_url),$this->partner);
		$sender->v_amount=$this->order_currency;
		$sender->v_oid=$this->order_sn;
		return $sender->buildForm();
	}

	public function verifyNotify()
	{
		$notify = new ChinabankNotify($this->gateway,$this->key,$this->partner);
		$result=$notify->return_verify();
		if($result)
		{
			echo 'ok';
			return ['order_sn'=>$result->v_oid,'order_currency'=>$result->v_amount];
		}
		else
		{
			echo 'error';
			return false;
		}

	}

	public function verifyReturn()
	{
		$notify = new ChinabankNotify($this->gateway,$this->key,$this->partner);
		$result=$notify->return_verify();
		if($result)
		{
			return ['order_sn'=>$result->v_oid,'order_currency'=>$result->v_amount];
		}
		else
		{
			return false;
		}
	}
}