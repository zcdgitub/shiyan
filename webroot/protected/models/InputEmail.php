<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class InputEmail extends CFormModel {
	public $email_real;
	public $email_input;
	public $account;
	private $_mail;

	public function rules() {
		return array(
			array('email_input', 'required'),
			array('email_input', 'email'),
			array('email_input', 'ext.validators.EmailPassword'),
			array('email_real','safe')
		);
	}

	public function attributeLabels() {
		return array(
			'email_input'=>'你预留的Emaill地址',
			'email_real'=>'Email地址提示',
		);
	}

	public function getForm() {
		return new CForm(array(
			'activeForm'=>array(
				'class'=>'ActiveForm',
				'enableClientValidation'=>true,
				'enableAjaxValidation'=>true,
				'clientOptions'=>array(	'validateOnSubmit'=>true)
			),
			'inputElementClass'=>'FormInputElement',
			'showErrorSummary'=>true,
			'attributes'=>array('id'=>'email'),
			'elements'=>array(
				'email_real'=>array(
					'type'=>'textPlain'
				),
				'email_input'=>array(
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
		if(params('send_email'))
		{
			$memberinfo=Memberinfo::model()->findByAttributes(['memberinfo_account'=>$account]);
			if(!empty($memberinfo->memberinfo_email))
			{
				$this->mail->setTo($memberinfo->memberinfo_email);
				$content=$this->account . "的新密码\r\n";
				$content.="一级密码:$passwords[password1],二级密码$passwords[password2]";
				$this->mail->setBody($content);
				$this->mail->send();
			}
		}
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
}