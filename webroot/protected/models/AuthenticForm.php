<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class AuthenticForm extends CFormModel
{
	public $password;
	public $rememberMe;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			array('password','ext.validators.Password'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>t('epmms','记住密码'),
			'password' => t('epmms','二级密码'),
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
			if(webapp()->user->isAdmin())
			{
				$record=new Userinfo();
				$record=$record->findByPk(webapp()->user->id);
				$hashPassword=$record->userinfo_password2;
			}
			else
			{
				$record=new Memberinfo();
				$record=$record->findByPk(webapp()->user->id);
				$hashPassword=$record->memberinfo_password2;
			}
			if(!CPasswordHelper::verifyPassword($this->password, $hashPassword))
				$this->addError('password',t('epmms','二级密码不正确'));
		}
	}

	/**
	 * 写入会话状态信息
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if(!$this->hasErrors())
		{
			if($this->rememberMe)
			{
				$_SESSION['isAuthentic']=0;
			}
			else
			{
				$_SESSION['isAuthentic']=time();
			}
			return true;
		}
		else
			return false;
	}
}
