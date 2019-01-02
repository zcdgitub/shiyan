<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-10-1
 * Time: 下午2:21
 * To change this template use File | Settings | File Templates.
 */

class mb345 extends CComponent
{
	public $username;
	public $password;
	public $number;
	public $content;
	public function __construct($username,$password)
	{
		$this->username=$username;
		$this->password=$password;
	}
	public function send()
	{
		$url="http://mb345.com:999/ws/BatchSend.aspx?CorpID={$this->username}&Pwd={$this->password}&Mobile=" . urlencode($this->phoneNumber) . "&Content=" . urlencode(iconv('UTF-8','GBK',$this->content));
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
		$result = curl_exec($ch);
		curl_close($ch);
		$log_params=['category'=>'operate','source'=>'短信','operate'=>'发送','user'=>user()->name,'role'=>user()->role,'target'=>$this->phoneNumber,'ip'=>webapp()->request->userHostAddress];
		if($result<0)
		{
			switch($result)
			{
				case -1:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n账号未注册";
					$ret=false;
					break;
				case -2:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n其它错误";
					$ret=false;
					break;
				case -3:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n密码错误";
					$ret=false;
					break;
				case -4:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n手机号格式不对";
					$ret=false;
					break;
				case -5:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n余额不足";
					$ret=false;
					break;
				case -6:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n定时发送时间不是有效的时间格式";
					$ret=false;
					break;
				case -7:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n提交信息末尾未加签名，请添加中文企业签名";
					$ret=false;
					break;
				case -8:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n发送内容需在1到500个字之间";
					$ret=false;
					break;
				case -9:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n发送号码为空";
					$ret=false;
					break;
				default:
					$log_params['status']=LogFilter::FAILED;
					$log_params['info']=$this->content . "\r\n未知错误";
					$ret=false;
					break;
			}
		}
		else
		{
			$log_params['status']=LogFilter::SUCCESS;
			$log_params['info']=$this->content ;
			$ret=true;
		}
		$log_params['info'].= "\r\n" . 'sp:' . get_class($this);
		LogFilter::log($log_params);
		return $ret;
	}
	public function getPhoneNumber()
	{
		if(is_null($this->number)||(is_string($this->number)&&$this->number=='')||(is_array($this->number)&&$this->number==[]))
			throw new Error('手机号码为空');
		if(is_string($this->number))
			return $this->number;
		if(is_array($this->number))
			return implode(';',$this->number);
		throw new Error('手机号码为空');
		return false;
	}
}