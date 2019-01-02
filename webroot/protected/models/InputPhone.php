<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class InputPhone extends CFormModel {
	public $phone_real;
	public $phone_input;
	private $_sms;

	public function rules() {
		return array(
			array('phone_input', 'required'),
			array('phone_input', 'ext.validators.Phone'),
			array('phone_input', 'ext.validators.PhonePassword'),
			array('phone_real','safe')
		);
	}

	public function attributeLabels() {
		return array(
			'phone_input'=>'你预留的手机号码',
			'phone_real'=>'手机号码提示',
		);
	}

	public function getForm() {
		return new CForm(array(
			'showErrorSummary'=>true,
			'attributes'=>array('id'=>'phone'),
			'elements'=>array(
				'phone_real'=>array(
					'type'=>'text'
				),
				'phone_input'=>array(
					'type'=>'text'
				)
			),
			'buttons'=>array(
				'submit'=>array(
					'type'=>'submit',
					'label'=>t('epmms','下一步')
				)
			)
		), $this);
	}
	public function send($account,$passwords=array())
	{
		if(params('send_sms'))
		{
			$memberinfo=Memberinfo::model()->findByAttributes(['memberinfo_account'=>$account]);
			if(!empty($memberinfo->memberinfo_mobi))
			{
				$content=$this->account . "的新密码\r\n";
				$content.="一级密码:$passwords[password1],二级密码$passwords[password2]";
				$this->send($content, $memberinfo->memberinfo_mobi);
			}
		}
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
}