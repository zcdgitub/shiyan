<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UpdatePasswordAuth extends CFormModel
{
	public $password;
	public $password2;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			//array('password, password2', 'required'),
			// password needs to be authenticated
			array('password', 'authenticate'),
            array('password,password2','safe')
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password' => t('epmms','原登录密码'),
			'password2' => t('epmms','原交易密码'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
		    if(!empty($_POST['Memberinfo']['memberinfo_password']))
            {
                if(!CPasswordHelper::verifyPassword($this->password,user()->info->memberinfo_password))
                    $this->addError('password',$this->getAttributeLabel('password') . t('epmms','不正确'));
            }
            if(!empty($_POST['Memberinfo']['memberinfo_password2']))
            {
                if(!CPasswordHelper::verifyPassword($this->password2,user()->info->memberinfo_password2))
                    $this->addError('password2',$this->getAttributeLabel('password2') . t('epmms','不正确'));
            }

		}
	}
}
