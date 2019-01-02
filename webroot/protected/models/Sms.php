<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-10-1
 * Time: 下午3:47
 * To change this template use File | Settings | File Templates.
 */

class Sms extends CModel
{
	protected $_sms;
	protected $_mail;
	protected static $_model;
	public function send($content,$number)
	{
		$this->sms->content=$content;
		$this->sms->number=$number;
		$cnt=1;
		while(!$this->sms->send()&&$cnt<1)
			$cnt++;
	}
	public function sendAward($period)
	{
		$this->sendSmsAward($period);
		//添加定时任务
		/*
		$yiicmd=Yii::app()->basePath . DIRECTORY_SEPARATOR . "yiic SendSMS $period";
		$jobdate=time()+3;
		$stime=date('H:i:s',$jobdate);
		$sdate=date('Y-m-d',$jobdate);
		$tn_name=Yii::app()->db->database . '-' . $period . '-' . '发送奖金短信' - time();
		$jobcmd="schtasks /create /tn \"$tn_name\" /tr \"$yiicmd\" /sc once /st $stime /sd $sdate /ru System";
		exec($jobcmd);*/
	}
	public function sendSmsAward($period)
	{
		if(config('sms','is_award')==0)
			return;
		$awards=AwardPeriodSum::model()->findAll(['select'=>'distinct award_period_sum_memberinfo_id','condition'=>'award_period_sum_period=:period','params'=>[':period'=>$period]]);
		foreach($awards as $award)
		{
			$memberinfo=Memberinfo::model()->findByPk($award->award_period_sum_memberinfo_id);
			$sms_template['{name}']=$memberinfo->memberinfo_name;
			$sms_template['{showName}']=$memberinfo->showName;
			$sms_template['{account}']=$memberinfo->memberinfo_account;
			$my_awards=AwardPeriodSum::model()->findAll(['condition'=>'award_period_sum_period=:period and award_period_sum_memberinfo_id=:id'
				,'params'=>[':period'=>$period,'id'=>$award->award_period_sum_memberinfo_id]]);
			$sms_template['{award}']='';
			foreach($my_awards as $mya)
			{
				$sumtype=$mya->awardPeriodSumType;
				$sms_template['{award}'].= "\r\n" . $sumtype->showName . ':' . $mya->award_period_sum_currency;
			}

			$this->setDate($sms_template);
			$content=strtr(config('sms','award'),$sms_template);
			if(params('send_sms'))
			{
				if(!empty($memberinfo->memberinfo_mobi))
					$this->send($content, $memberinfo->memberinfo_mobi);
                if(!empty($memberinfo->memberinfo_phone))
                    $this->send($content, $memberinfo->memberinfo_phone);
			}
			if(params('send_email'))
			{
				if(!empty($memberinfo->memberinfo_email))
				{
					$this->mail->setTo($memberinfo->memberinfo_email);
					$this->mail->setBody($content);
					$this->mail->send();
				}
			}
		}
	}
	public function sendVerify($memberinfo)
	{
		if(config('sms','is_verify')==0)
			return;
		$sms_template['{name}'] = $memberinfo->memberinfo_name;
		$sms_template['{showName}'] = $memberinfo->showName;
		$sms_template['{account}'] = $memberinfo->memberinfo_account;
		$this->setDate($sms_template);
		$content = strtr(config('sms', 'verify'), $sms_template);
		if(params('send_sms'))
		{
			if(!empty($memberinfo->memberinfo_mobi))
				$this->send($content, $memberinfo->memberinfo_mobi);
		}
		if(params('send_email'))
		{
			if(!empty($memberinfo->memberinfo_email))
			{
				$this->mail->setTo($memberinfo->memberinfo_email);
				$this->mail->setBody($content);
				$this->mail->send();
			}
		}
	}
	public function sendRegister($memberinfo,$p1,$p2)
	{
		if(config('sms','is_register')==0)
			return;
		$sms_template['{name}']=$memberinfo->memberinfo_name;
		$sms_template['{showName}']=$memberinfo->showName;
		$sms_template['{account}']=$memberinfo->memberinfo_account;
		$sms_template['{password1}']=$p1;
		$sms_template['{password2}']=$p2;
		$this->setDate($sms_template);
		$content=strtr(config('sms','register'),$sms_template);
		if(params('send_sms'))
		{
			if(!empty($memberinfo->memberinfo_mobi))
				$this->send($content, $memberinfo->memberinfo_mobi);
            if(!empty($memberinfo->memberinfo_phone))
                $this->send($content, $memberinfo->memberinfo_phone);
		}
		if(params('send_email'))
		{
			if(!empty($memberinfo->memberinfo_email))
			{
				$this->mail->setTo($memberinfo->memberinfo_email);
				$this->mail->setBody($content);
				$this->mail->send();
			}
		}
	}
	public function sendSms($code,$addr)
	{
		if(!empty($addr))
			return $this->send($code, $addr);
		return false;
	}
	public function sendMail($code,$addr)
	{
		if (!empty($addr))
		{
			$this->mail->setTo($addr);
			$this->mail->setBody($code);
			return $this->mail->send();
		}
		return false;
	}
	protected function setDate(&$template)
	{
		$template['{date}']=webapp()->format->formatDate(time());
		$template['{time}']=webapp()->format->formatTime(time());
		$template['{datetime}']=webapp()->format->formatDatetime(time());
	}
	public function getSms()
	{
		if(is_null($this->_sms))
		{
			Yii::import('ext.sms.*');
			$sms_class=params('sms.sp');
			$sms_un=params('sms.username');
			$sms_pwd=params('sms.password');
			$this->_sms=new $sms_class($sms_un,$sms_pwd);
		}
		return $this->_sms;
	}
	public function getMail()
	{
		if(is_null($this->_mail))
		{
			Yii::import('ext.YiiMailer.YiiMailer');
			$this->_mail = new YiiMailer();
			$this->_mail->clearLayout();
		}
		return $this->_mail;
	}
	public static function model()
	{
		if(is_null(self::$_model))
		{
			self::$_model=new Sms();
		}
		return self::$_model;
	}
	public function attributeNames()
	{
		return [];
	}
}