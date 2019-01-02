<?php


class AlipayProxy extends CComponent
{
    private $_input_charset = "utf-8";
    private $sign_type = "MD5";
    private $transport = "http";

    public $key = "16wi2t3j0iuizqwgsphihf7fmdget3n9";
    public $partner = "2088611504343177";
    public $seller_email = "lingyuan86@qq.com";
    public $return_url = "";
    public $notify_url = "";
    public $show_url = "";
    public $order_sn="";
    public $order_currency;
    public $subject;

    public function init()
    {
        Yii::import('ext.alipay.class.*');
    }

    public function buildForm()
    {
        $params = array(
            'partner'=>$this->partner,
            'seller_email'=>$this->seller_email,
            'return_url'=>Yii::app()->request->hostInfo . CHtml::normalizeUrl($this->return_url),
            'notify_url'=>Yii::app()->request->hostInfo . CHtml::normalizeUrl($this->notify_url),
            '_input_charset'=>$this->_input_charset,
            'show_url'=>Yii::app()->request->hostInfo . CHtml::normalizeUrl($this->show_url),
        );
        $request=new AlipayDirectRequest();
        $request->out_trade_no=$this->order_sn;
        $request->total_fee=$this->order_currency;
        $request->subject=$this->subject;

        $params = array_merge($params, $request->getParams());
        $service = new AlipayService($params, $this->key, $this->sign_type);
        return $service->build_form();
    }

    public function verifyNotify()
    {
        $notify = new AlipayNotify($this->partner, $this->key, $this->sign_type, $this->_input_charset, $this->transport);
        $result=$notify->notify_verify();
        $info=$_REQUEST;
        if($result && ($info['trade_status']=='TRADE_SUCCESS' || $info['trade_status']=='TRADE_FINISHED'))
        {
            $info['order_currency']=$info['total_fee'];
            $info['order_sn']=$info['out_trade_no'];
            return $info;
        }
        else
            return null;
    }

    public function verifyReturn()
    {
        $notify = new AlipayNotify($this->partner, $this->key, $this->sign_type, $this->_input_charset, $this->transport);
        $result=$notify->return_verify();
        $info=$_REQUEST;
        if($result && ($info['trade_status']=='TRADE_SUCCESS' || $info['trade_status']=='TRADE_FINISHED'))
        {
            $info['order_currency']=$info['total_fee'];
            $info['order_sn']=$info['out_trade_no'];
            return $info;
        }
        else
            return null;
    }
}
