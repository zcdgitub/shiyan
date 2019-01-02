<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class MemberUserIdentity extends CUserIdentity
{
	const ERROR_NOT_ENABLE=3;
	const ERROR_NOT_VERIFY=4;
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
        $record=Memberinfo::model()->findByAttributes(array('memberinfo_account'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
		/*else if($record->memberinfo_is_verify==0)
			$this->errorCode=self::ERROR_NOT_VERIFY;*/
		else if(strlen($record->memberinfo_password)==32)
		{
			if(md5($this->password)!=$record->memberinfo_password)
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
        else if(!CPasswordHelper::verifyPassword($this->password,$record->memberinfo_password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
		else if($record->memberinfo_is_enable==0)
			$this->errorCode=self::ERROR_NOT_ENABLE;
        else
        {
            $this->setState('__id',$record->memberinfo_id);
            $this->setState('__name',$record->memberinfo_account);
            $this->setState('title', $record->memberinfo_name);
			if($record->memberinfo_is_verify==0)
				$role='unmember';
			elseif($record->memberinfo_is_agent==0)
				$role='member';
			else
				$role='agent';
			if($record->memberinfo_is_agent==2)
				$role='shop';
            $this->setState('role',$role);
            $_SESSION['isAdmin']=false;
            //webapp()->sessionCache->executeCommand('SET', ['appfront_session'. md5(json_encode(['yii\\redis\\Session', session_id()])), ['__id'=>9], 'EX', webapp()->session->getTimeout()]);
            //webapp()->sessionCache->set('appfront_session'. md5(json_encode(['yii\\redis\\Session', session_id()])),['__id'=>9],webapp()->session->getTimeout());
			$record->memberinfo_last_date=new CDbExpression('now()');
			$record->saveAttributes(['memberinfo_last_date']);
            $this->errorCode=self::ERROR_NONE;
        }
		if($this->errorCode!=self::ERROR_NONE)
		{
			LogFilter::log(['category'=>'login','source'=>'会员登录','operate'=>'登录','user'=>$this->username,'role'=>null,'target'=>$this->username,'status'=>LogFilter::FAILED,'info'=>"会员登录",'ip'=>webapp()->request->userHostAddress]);
		}
		else
		{
			LogFilter::log(['category'=>'login','source'=>'会员登录','operate'=>'登录','user'=>$this->username,'role'=>$record->memberinfo_is_agent==0?'member':'agent','target'=>$this->username,'status'=>LogFilter::SUCCESS,'info'=>"会员登录",'ip'=>webapp()->request->userHostAddress]);
		}
        return !$this->errorCode;
	}
}