<?php
class ChinabankDirectRequest extends CComponent
{
	public $key;
	public $gateway;
	//商户编号
	public $v_mid;
	//订单编号
    public $v_oid ;
	public $v_md5info;
	//支付金额
    public $v_amount = 0;
	//币种
	public $v_moneytype='CNY';
	//返回地址
	public $v_url;

    //以下几项为可选信息,如果发送网银在线会保存此信息,使用和不使用都不影响支付！
	//收货人
	public $v_rcvname;
	//收货地址
	public $v_rcvaddr;
	//收货人电话
	public $v_rcvtel;
	//收货人邮编
	public $v_rcvpost;
	//收货人邮件
	public $v_rcvemail;
	//收货人手机号
	public $v_rcvmobile;
	//订货人姓名
	public $v_ordername;
	//订货人地址
	public $v_orderaddr;
	//订货人电话
	public $v_ordertel;
	//订货人邮编
	public $v_orderpost;
	//订货人邮件
	public $v_orderemail;
	//订货人手机号
	public $v_ordermobile;
	//备注字段1
	public $remark1;
	//备注字段2
	public $remark2;
	public function __construct($gateway,$key,$returnUrl,$partner)
	{
		$this->gateway=$gateway;
		$this->key=$key;
		$this->v_url=$returnUrl;
		$this->v_mid=$partner;
	}
    public function getParams()
	{
        return array(
            'v_mid'=>$this->v_mid,
            'v_oid'=>$this->v_oid,
			'v_md5info'=>$this->v_md5info,
            'v_amount'=>$this->v_amount,
            'v_moneytype'=>$this->v_moneytype,
			'v_url'=>$this->v_url,
            'v_rcvname'=>$this->v_rcvname,
            'v_rcvaddr'=>$this->v_rcvaddr,
            'v_rcvtel'=>$this->v_rcvtel,
            'v_rcvemail'=>$this->v_rcvemail,
            'v_rcvmobile'=>$this->v_rcvmobile,
            'v_ordername'=>$this->v_ordername,
            'v_orderaddr'=>$this->v_orderaddr,
            'v_ordertel'=>$this->v_ordertel,
            'v_orderpost'=>$this->v_orderpost,
			'v_orderemail'=>$this->v_orderpost,
			'v_ordermobile'=>$this->v_ordermobile,
			'v_remark1'=>$this->remark1,
			'v_remark2'=>$this->remark2
        );
    }
	public function buildForm()
	{
		$md5_str=$this->v_amount . $this->v_moneytype . $this->v_oid . $this->v_mid . $this->v_url . $this->key;
		$this->v_md5info = strtoupper(md5($md5_str));
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