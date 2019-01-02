<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        $record=Userinfo::model()->findByAttributes(array('userinfo_account'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(!CPasswordHelper::verifyPassword($this->password,$record->userinfo_password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->setState('__id',$record->userinfo_id);
            $this->setState('__name',$record->userinfo_account);
            $this->setState('title', $record->userinfo_name);
            $this->setState('role',$record->userinfo_role);
			$_SESSION['isAdmin']=true;
            $this->errorCode=self::ERROR_NONE;
        }
		if($this->errorCode!=self::ERROR_NONE)
		{
			LogFilter::log(['category'=>'login','source'=>'会员登录','operate'=>'登录','user'=>$this->username,'role'=>null,'target'=>$this->username,'status'=>LogFilter::FAILED,'info'=>"管理员登录",'ip'=>webapp()->request->userHostAddress]);
		}
		else
		{
			LogFilter::log(['category'=>'login','source'=>'会员登录','operate'=>'登录','user'=>$this->username,'role'=>$record->userinfo_role,'target'=>$this->username,'status'=>LogFilter::SUCCESS,'info'=>"管理员登录",'ip'=>webapp()->request->userHostAddress]);
		}
        return !$this->errorCode;
	}
}