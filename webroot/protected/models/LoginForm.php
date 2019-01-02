<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
	public $verifyCode;
	public $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password' . (self::captchaVisible()?',verifyCode':''), 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!$this->captchaVisible()),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>t('epmms','自动登录'),
			'username' => t('epmms','用户名'),
			'password' => t('epmms','密码'),
			'verifyCode'  => t('epmms','验证码')
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
			if(!$this->_identity->authenticate())
			{
				if($this->_identity->errorCode==MemberUserIdentity::ERROR_USERNAME_INVALID)
					$this->addError('username',t('epmms','不正确的帐号'));
				if($this->_identity->errorCode==MemberUserIdentity::ERROR_PASSWORD_INVALID)
					$this->addError('password',t('epmms','不正确的密码'));
				if($this->_identity->errorCode==3)
					$this->addError('username',t('epmms','账号已锁定'));
				if($this->_identity->errorCode==4)
					$this->addError('username',t('epmms','账号未审核'));
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*7 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
		{
			return false;
		}
	}
	public function captchaVisible()
	{
		$cfg=(int)config('auth','captcha');
		if($cfg===0)
		{
			return false;
		}
		else if($cfg===1)
			return true;
		else if($cfg===2)
		{
			return empty($_SESSION['loginError'])?true:false;
		}
		return true;
	}
}
