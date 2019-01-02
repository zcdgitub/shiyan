<?php
/*
 *类名：ChinabankNotify
 *功能：付款过程中服务器通知类
*/



class ChinabankNotify extends CComponent
{
    public $gateway;           //网关地址
	public $key;
	public $v_mid;
	//订单号
	public $v_oid;
	//支付状态
	public $v_pstatus;
	//支付结果信息
	public $v_pstring;
	//支付金额
	public $v_amount;
	//支付币种
	public $v_moneytype;
	//支付银行
	public $v_pmode;
	//订单MD5校验码
	public $v_md5str;
	//备注字段1
	public $remark1;
	//备注字段2
	public $remark2;

	public function __construct($gateway,$key,$partner)
	{
		$this->gateway=$gateway;
		$this->key=$key;
		$this->v_mid=$partner;
		$this->initParams();
	}


	/**
	 * 支付成功返回订单号和支付金额的数组，支付失败返回false
	 */
	function return_verify()
	{
		$md5_str=$this->v_oid . $this->v_pstatus . $this->v_amount . $this->v_moneytype . $this->key;
		$md5info = strtoupper(md5($md5_str));
		if($md5info==$this->v_md5str && $this->v_pstatus==20)
			return $this;
		else
			return false;
    }

	public function initParams()
	{
			$this->v_oid=isset($_REQUEST['v_oid'])?$_REQUEST['v_oid']:null;
			$this->v_md5str=isset($_REQUEST['v_md5str'])?$_REQUEST['v_md5str']:null;
			$this->v_amount=isset($_REQUEST['v_amount'])?$_REQUEST['v_amount']:null;
			$this->v_moneytype=isset($_REQUEST['v_moneytype'])?$_REQUEST['v_moneytype']:null;
			$this->v_pstatus=isset($_REQUEST['v_pstatus'])?$_REQUEST['v_pstatus']:null;
			$this->v_pstring=isset($_REQUEST['v_pstring'])?$_REQUEST['v_pstring']:null;
			$this->v_pmode=isset($_REQUEST['v_pmode'])?$_REQUEST['v_pmode']:null;
			$this->remark1=isset($_REQUEST['remark1'])?$_REQUEST['remark1']:null;
			$this->remark2=isset($_REQUEST['remark2'])?$_REQUEST['remark2']:null;
	}
}
?>
