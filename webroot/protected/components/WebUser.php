<?php

/**
 *
 * @author hetao
 *
 */
class WebUser extends RWebUser
{
    public $authenticUrl=array('site/authentic');
    protected $_access=array();
    protected $_info;
    protected $_map;
    /**
     * @var array the property values (in name-value pairs) used to initialize the identity cookie.
     * Any property of {@link CHttpCookie} may be initialized.
     * This property is effective only when {@link allowAutoLogin} is true.
     */
    public $identityCookie;
    public function init()
    {

        parent::init();
    }

    /**
     * Logs in a user.
     *
     * The user identity information will be saved in storage that is
     * persistent during the user session. By default, the storage is simply
     * the session storage. If the duration parameter is greater than 0,
     * a cookie will be sent to prepare for cookie-based login in future.
     *
     * Note, you have to set {@link allowAutoLogin} to true
     * if you want to allow user to be authenticated based on the cookie information.
     *
     * @param IUserIdentity $identity the user identity (which should already be authenticated)
     * @param integer $duration number of seconds that the user can remain in logged-in status. Defaults to 0, meaning login till the user closes the browser.
     * If greater than 0, cookie-based login will be used. In this case, {@link allowAutoLogin}
     * must be set true, otherwise an exception will be thrown.
     * @return boolean whether the user is logged in
     * @throws CException
     */
    public function login($identity,$duration=0)
    {
        $id=$identity->getId();
        $states=$identity->getPersistentStates();
        if($this->beforeLogin($id,$states,false))
        {
            $this->changeIdentity($id,$identity->getName(),$states);

            if($duration>0)
            {
                if($this->allowAutoLogin)
                    $this->saveToCookie($duration);
                else
                    throw new CException(Yii::t('yii','{class}.allowAutoLogin must be set true in order to use cookie-based authentication.',
                        array('{class}'=>get_class($this))));
            }

            if ($this->absoluteAuthTimeout)
                $this->setState(self::AUTH_ABSOLUTE_TIMEOUT_VAR, time()+$this->absoluteAuthTimeout);
            $this->afterLogin(false);
        }
        return !$this->getIsGuest();
    }

    /**
     * Changes the current user with the specified identity information.
     * This method is called by {@link login} and {@link restoreFromCookie}
     * when the current user needs to be populated with the corresponding
     * identity information. Derived classes may override this method
     * by retrieving additional user-related information. Make sure the
     * parent implementation is called first.
     * @param mixed $id a unique identifier for the user
     * @param string $name the display name for the user
     * @param array $states identity states
     */
    protected function changeIdentity($id,$name,$states)
    {
        Yii::app()->getSession()->regenerateID(true);
        $this->setId($id);
        $this->setName($name);
        $this->loadIdentityStates($states);
    }
    /**
     * Actions to be taken after logging in.
     * Overloads the parent method in order to mark superusers.
     * @param boolean $fromCookie whether the login is based on cookie.
     */
    public function afterLogin($fromCookie)
    {
        // Mark the user as a superuser if necessary.
        if($this->getState('role')!='member' && $this->getState('role')!='agent')
        {
            if( Rights::getAuthorizer()->isSuperuser($this->getId())===true )
                $this->isSuperuser = true;
        }

    }

    /**
     * 是否是公司登录
     * @return bool
     */
    public function isAdmin()
    {
        if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])
            return true;
        else
            return false;
    }
    public function isAuthentic()
    {
        //检查是否超时
        if(isset($_SESSION['isAuthentic']))
        {
            if($_SESSION['isAuthentic']===0)
            {
                return true;
            }
            if(!isEmpty(config('auth','timeout2')))
                $duration=config('auth','timeout2');
            else
                return true;
            if(time()-$_SESSION['isAuthentic']>$duration)
                return false;
            else
            {
                return true;
            }
        }
        return false;
    }
    //二级密码输入
    public function authenticRequired()
    {
        $app=Yii::app();
        $request=$app->getRequest();

        if(!$request->getIsAjaxRequest())
            $this->setReturnUrl($request->getUrl());
        elseif(isset($this->loginRequiredAjaxResponse))
        {
            header('HTTP/1.1 403 Forbidden');
            echo 'password2';
            Yii::app()->end();
        }

        if(($url=$this->authenticUrl)!==null)
        {
            if(is_array($url))
            {
                $route=isset($url[0]) ? $url[0] : $app->defaultController;
                $url=$app->createUrl($route,array_splice($url,1));
            }
            $request->redirect($url);
        }
        else
            throw new CHttpException(403,Yii::t('epmms','要求验证二级密码'));
    }
    /**
     * 检查当前登录角色的权限
     * 覆盖RWebUser和CWebUser中的同名方法
     * @param string $operation the name of the operation that need access check.
     * @param array $params name-value pairs that would be passed to business rules associated
     * with the tasks and roles assigned to the user.
     * @param boolean $allowCaching whether to allow caching the result of access checki.
     * This parameter has been available since version 1.0.5. When this parameter
     * is true (default), if the access check of an operation was performed before,
     * its result will be directly returned when calling this method to check the same operation.
     * If this parameter is false, this method will always call {@link CAuthManager::checkAccess}
     * to obtain the up-to-date access result. Note that this caching is effective
     * only within the same request.
     * @return boolean whether the operations can be performed by this user.
     */
    public function checkAccess($operation, $params=array(), $allowCaching=true)
    {
        $moduleConfig=new ModuleConfig();
        if($moduleConfig->check($operation))
        {
            return false;
        }
        // Allow superusers access implicitly and do CWebUser::checkAccess for others.
        if($this->isSuperuser===true)
        {
            return true;
        }
        else
        {
            if($allowCaching && $params===array() && isset($this->_access[$operation]))
                return $this->_access[$operation];
            $roles=Yii::app()->getAuthManager()->getAuthItemsByNames(array($this->getRole()));
            $pos=strpos($operation,'.');
            $controllerItem=left($operation,$pos) . '.*';
            $access=Yii::app()->getAuthManager()->checkAccessRecursive($controllerItem,$this->getId(),$params,$roles);
            if(!$access)
                $access=Yii::app()->getAuthManager()->checkAccessRecursive($operation,$this->getId(),$params,$roles);
            if($allowCaching && $params===array())
                $this->_access[$operation]=$access;
            return $access;
        }
    }
    public function getRole()
    {
        return $this->getState('role');
    }
    public function getRoleName()
    {
        return webapp()->AuthManager->getAuthItem($this->role)->description;
    }
    public function getInfo()
    {
        if(!$this->isGuest)
        {
            if($this->isAdmin())
            {
                if(is_null($this->_info))
                {
                    $this->_info=Userinfo::model()->findByPk($this->id);
                }
                return $this->_info;
            }
            else
            {
                if(is_null($this->_info))
                {
                    $this->_info=Memberinfo::model()->findByPk($this->id);
                }
                return $this->_info;
            }
        }
        return null;
    }
    public function getMap()
    {
        if(!$this->isGuest)
        {
            if($this->isAdmin())
            {
                return null;
            }
            else
            {
                if(is_null($this->_map))
                {
                    $this->_map=Membermap::model('Membermap')->findByPk($this->id);
                }
                return $this->_map;
            }
        }
        return null;
    }
    /**
     * Redirects the user browser to the login page.
     * Before the redirection, the current URL (if it's not an AJAX url) will be
     * kept in {@link returnUrl} so that the user browser may be redirected back
     * to the current page after successful login. Make sure you set {@link loginUrl}
     * so that the user browser can be redirected to the specified login URL after
     * calling this method.
     * After calling this method, the current request processing will be terminated.
     */
    public function loginRequired()
    {
        $app=Yii::app();
        $request=$app->getRequest();
        if(!$request->getIsAjaxRequest())
        {
            $this->setReturnUrl($request->getUrl());
            if(($url=$this->loginUrl)!==null)
            {
                if(is_array($url))
                {
                    $route=isset($url[0]) ? $url[0] : $app->defaultController;
                    $url=$app->createUrl($route,array_splice($url,1));
                }
                $request->redirect($url);
            }
        }
        elseif(isset($this->loginRequiredAjaxResponse))
        {
            header('HTTP/1.1 403 Forbidden');
            echo $this->loginRequiredAjaxResponse;
            Yii::app()->end();
        }
        throw new CHttpException(403,Yii::t('yii','Login Required'));
    }

}

?>