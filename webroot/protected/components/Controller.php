<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	/**
	 * @return array action filters
	 */

	public $log=array();
	protected $_controllerName;
	protected $_actionName;
	public function init()
	{
		
/*        $detect = Yii::app()->mobileDetect;
        $isRest=0;
        if(isset($_REQUEST['isRest']))
            $isRest=$_REQUEST['isRest'];
        if(!webapp()->request->isAjaxRequest && $isRest==0)
        {
            if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS')
            {
                if($detect->isMobile())
                {
                    header('Location: '. params('mobile_url'));
                    webapp()->end();
                }
            }
        }*/
		Yii::setPathOfAlias('theme',themeBasePath());
		Yii::setPathOfAlias('view',viewBasePath());
		//初始化语言设置
		if(params('multi_language'))
		{
			if (isset($_REQUEST['lang']) && $_REQUEST['lang'] != "")
			{
				Yii::app()->language = $_REQUEST['lang'];
				Yii::app()->request->cookies['lang'] = new CHttpCookie('lang', $_REQUEST['lang']);
			}
			else if (!empty(Yii::app()->request->cookies['lang']))
			{
				Yii::app()->language = Yii::app()->request->cookies['lang']->value;
			}
			elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
				$lang_first = strtolower(str_replace('-', '_', $lang[0]));
				Yii::app()->language = ($lang_first == 'zh_hans_cn' ? 'zh_cn' : $lang_first);
			}
		}
		//初始化站点设置
		$this->pageTitle=config('site','title');
		if($keyword=config('site','keyword'))
			Yii::app()->clientScript->registerMetaTag($keyword,'keywords');
		if($desc=config('site','desc'))
			Yii::app()->clientScript->registerMetaTag($desc,'description');
		$deny_robots=config('site','deny_robots');
		Yii::app()->clientScript->registerMetaTag((int)$deny_robots===0?'none':'all','robots');
		//初始化认证设置
		user()->allowAutoLogin=(int)config('auth','autologin')==1?true:false;
		user()->authTimeout=empty2null(config('auth','timeout'));
		user()->autoRenewCookie=true;
		webapp()->session->timeout=empty2null(config('auth','timeout'));
		if(user()->isAdmin() && webapp()->theme->name!='classic')
		{
			webapp()->theme='classic';
		}
		/*
		if(!isset($_COOKIE["lastPush"]))
			setcookie("lastPush",time(), time()+60*60*24*7,'/');*/
	}
	//设置各种权限控制filter
	public function filters()
	{
		return array(
				'rights',//rights rbac
		);
	}

	//在这里更新请求时间
	protected function afterAction($action)
	{
		if(webapp()->user->isAuthentic() && $_SESSION['isAuthentic']>0)
			$_SESSION['isAuthentic']=time();
		parent::afterAction($action);
	}
	//检查权限
	public function checkAccess($actionID=null,$controllerID=null)
	{
		if(is_null($actionID))
			$actionID=$this->action->getId();
		if(is_null($controllerID))
			$controllerID=$this->getId();
		$filters=$this->filters();
		$user = Yii::app()->getUser();
		$rightsFilter=false;
		foreach($filters as $filter)
		{
			if(is_string($filter))  // filterName [+|- action1 action2]
			{
				if(($pos=strpos($filter,'+'))!==false || ($pos=strpos($filter,'-'))!==false)
				{
					$matched=preg_match("/\b{$actionID}\b/i",substr($filter,$pos+1))>0;
					if(($filter[$pos]==='+')===$matched)
						$filter=CInlineFilter::create($this,trim(substr($filter,0,$pos)));
				}
				else
					$filter=CInlineFilter::create($this,$filter);
				if(is_object($filter) && $filter->name=='role')
				{
					$roleFilter=true;
				}
				else if(is_object($filter) && $filter->name=='rights')
				{
					$rightsFilter=true;
				}
			}
		}
		// By default we assume that the user is allowed access
		$allow = true;
		$allowedActions=explode(',', $this->allowedActions());
		if($rightsFilter)
		{
			// Check if the action should be allowed
			if( $allowedActions!=='*' && in_array($actionID, $allowedActions)===false )
			{
				// Initialize the authorization item as an empty string
				$authItem = '';

				// Append the controller id to the authorization item name
				$authItem .= ucfirst($controllerID);

				// Check if user has access to the controller
				if( $user->checkAccess($authItem.'.*')!==true )
				{
					// Append the action id to the authorization item name
					$authItem .= '.'.ucfirst($actionID);
					// Check if the user has access to the controller action
					if( $user->checkAccess($authItem)!==true )
						$allow = false;
				}
			}
		}
		return $allow;
	}
    public function filterCors($filterChain)
    {
        if(isset($_SERVER['HTTP_ORIGIN']))
        {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            //header("Access-Control-Allow-Origin: localhost:9112");
            header("Access-Control-Allow-Credentials: true");
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']}");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header('Access-Control-Max-Age: 86400');
            webapp()->end();
            return;
        }
        $filterChain->run();
    }
	//需要二级密码验证
	public function filterAuthentic($filterChain)
	{
		if(isset($_SERVER['HTTP_ORIGIN']) || webapp()->user->isAuthentic())
			$filterChain->run();
		else
			webapp()->user->authenticRequired();
	}
	/**
	 * 记录操作日志
	 * @param unknown_type $filterChain
	 */
	public function filterlog($filterChain)
	{
	    $filter=new LogFilter;
	    $filter->setRules($this->logRules());
	    $filter->filter($filterChain);
	}

	/**
	 * 关闭网站
	 * @param $filterChain
	 * @throws CHttpException
	 */
	public function filterCloseSite($filterChain)
	{
		if(!user()->isAdmin())
		{
			$filterChain->run();
			return;
		}
		//试用到期
		$tryExpiry=params('licence.tryExpiry');
		if(!empty($tryExpiry))
		{
			$datetimeExpiry  = new  DateTime ( params('licence.tryExpiry') );
			$datetimeNow  = new  DateTime ('today');
			if($datetimeNow>$datetimeExpiry)
			{
				throw new CHttpException(503,'试用到期，请续费');
			}
		}
		else
		{
			//空间到期
			$datetimeExpiry = new DateTime(params('licence.spaceExpiry'));
			$datetimeNow = new DateTime('today');
			if($datetimeNow>$datetimeExpiry)
			{
				throw new CHttpException(503,'空间到期，请续费');
			}
		}
		//空间限额
		$path="../";
		$ar=getDirectorySize($path);
		$dirSizeMB=round($ar['size']/(1024*1024),1);
		$spaceQuota=params('spaceQuota');
		if($spaceQuota<$dirSizeMB)
		{
			throw new CHttpException(503,'空间超过限额，请续费');
		}
		if(config('site','is_enable')===0 && !user()->isAdmin())
		{
			throw new CHttpException(503,'站点临时关闭');
		}
		$filterChain->run();
	}
	public function allowedActions()
	{
		return '';
	}
	public function log($logParam=array())
	{
		$activeLog=$this->log;
		$log['status']=LogFilter::SUCCESS;
		$log['source']=$this->controllerName;
		$log['operate']=$this->actionName;
		$log['ip']=webapp()->request->userHostAddress;
		$log['user']=user()->name;
		$log['role']=user()->role;
		$logParam=array_merge($log,$activeLog,$logParam);
		LogFilter::log($logParam);
	}
	public function logRules()
	{
		return array();
	}
	public function getControllerName()
	{
		if(is_null($this->_controllerName))
		{
			$source=ucfirst($this->id). '.*';
			$rbac=new Rights();
			$tasks=$rbac->getAuthItemSelectOptions(1);
			$this->_controllerName=isset($tasks[$source]) && $tasks[$source]!==$source?$tasks[$source]:t('log',$this->id,array(),'logOperation');
		}
		return $this->_controllerName;
	}
	public function getActionName()
	{
		if(is_null($this->_actionName))
		{
			$source=ucfirst($this->id) . '.' . ucfirst($this->action->id);
			$rbac=new Rights();
			$operate=$rbac->getAuthItemSelectOptions(0);
			if(isset($operate[$source]) && ($operate[$source]!==$source))
				$this->_actionName= $operate[$source];
			else
				$this->_actionName= t('log',$this->action->id,array(),'logOperation');
		}
		return $this->_actionName;
	}
}