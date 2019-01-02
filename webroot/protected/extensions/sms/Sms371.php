<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-10-1
 * Time: 下午2:21
 * To change this template use File | Settings | File Templates.
 */

class Sms371 extends CComponent
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
		$url="http://sms2.371.com/mingnet.asp?username={$this->username}&pwd={$this->password}&tel=" . urlencode($this->phoneNumber) . "&content=" . urlencode(iconv('UTF-8','GBK',$this->content));
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
		$result = curl_exec($ch);
		curl_close($ch);
		$log_params=['category'=>'operate','source'=>'短信','operate'=>'发送','user'=>user()->name,'role'=>user()->role,'target'=>$this->phoneNumber,'ip'=>webapp()->request->userHostAddress];
		switch($result)
		{
			case 0:
				$log_params['status']=LogFilter::SUCCESS;
				$log_params['info']=$this->content ;
				$ret=true;
				break;
			case 1:
				$log_params['status']=LogFilter::FAILED;
				$log_params['info']=$this->content . "\r\n内容违规";
				$ret=false;
				break;
			case 2:
				$log_params['status']=LogFilter::FAILED;
				$log_params['info']=$this->content . "\r\n内容过长";
				$ret=false;
				break;
			case 3:
				$log_params['status']=LogFilter::FAILED;
				$log_params['info']=$this->content . "\r\n用户或密码错误码或禁止";
				$ret=false;
				break;
			case 4:
				$log_params['status']=LogFilter::FAILED;
				$log_params['info']=$this->content . "\r\n余额不足";
				$ret=false;
				break;
			case 5:
				$log_params['status']=LogFilter::FAILED;
				$log_params['info']=$this->content . "\r\n号码不能为空";
				$ret=false;
				break;
			default:
				$log_params['status']=LogFilter::FAILED;
				$log_params['info']=$this->content . "\r\n未知错误";
				$ret=false;
				break;
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