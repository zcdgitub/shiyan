<?php

class SiteController extends Controller
{
	public $layout='login';
	//设置各种权限控制filter
	public function filters()
	{
		return array(
			   'cors',
		
			
			//'rights +index',//rights rbac
            'cors + login,logout,authentic',
			'accessControl +index'
		);
	}
	public function allowedActions()
	{
		return 'login,error,logout,captcha,authentic,adminLogin,Adminlogin';
	}
	public function accessRules()
	{
		return [['deny',
			'actions'=>array('index'),
			'users'=>array('?'),
		]];
	}
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CaptchaAction',
				'backColor'=>0xFFFFFF,
				'foreColor'=>0x000000,
				'padding'=>0,
				'offset'=>1,
				'transparent'=>false,
				'maxLength'=>4,
				'minLength'=>4,
				'fontFile'=>webapp()->getExtensionPath() . '/fonts/georgia.ttf',
				'width'=>80,
				'height'=>26,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
				'layout'=>'frame',
				'renderAsText'=>false  //为true时把static pages作为真正的static page处理，不会解析其中的php
			)
		);
	}
	public function beforeAction($action) {
		$config = array();
		switch ($action->id) {
			case 'forgotPassword':
				$config = array(
					'steps'=>array('要找回的账号'=>'inputAccount','选择找回方式'=>'emailphone',
						array('email'=>array('输入Email'=>'inputEmail'),'phone'=>array('输入手机号'=>'inputPhone'))),
					'defaultBranch'=>false,
					'events'=>array(
						'onStart'=>'wizardStart',
						'onProcessStep'=>'passwordWizardProcessStep',
						'onFinished'=>'wizardFinished',
						'onInvalidStep'=>'wizardInvalidStep'
					),
					'menuLastItem'=>'完成'
				);
				break;
			default:
				break;
		}
		if (!empty($config)) {
			$config['class']='application.components.WizardBehavior';
			$this->attachBehavior('wizard', $config);
		}
		return parent::beforeAction($action);
	}
	// Wizard Behavior Event Handlers
	/**
	 * Raised when the wizard starts; before any steps are processed.
	 * MUST set $event->handled=true for the wizard to continue.
	 * Leaving $event->handled===false causes the onFinished event to be raised.
	 * @param WizardEvent The event
	 */
	public function wizardStart($event) {
		$event->handled = true;
	}
	/**
	 * The wizard has finished; use $event->step to find out why.
	 * Normally on successful completion ($event->step===true) data would be saved
	 * to permanent storage; the demo just displays it
	 * @param WizardEvent The event
	 */
	public function wizardFinished($event)
	{
		$data=$this->read('emailphone');
		$type=$data['type'];
		if ($event->step===true)
			$this->render('completed', compact('event','type'));
		else
			$this->render('finished', compact('event','type'));

		$event->sender->reset();
		Yii::app()->end();
	}
	/**
	 * Raised when the wizard detects an invalid step
	 * @param WizardEvent The event
	 */
	public function wizardInvalidStep($event) {
		Yii::app()->getUser()->setFlash('notice', $event->step.' is not a vaild step in this wizard');
	}
	/**
	 * Process wizard steps.
	 * The event handler must set $event->handled=true for the wizard to continue
	 * @param WizardEvent The event
	 */
	public function passwordWizardProcessStep($event) {
		$modelName = ucfirst($event->step);
		$model = new $modelName();

	
		$model->attributes = $event->data;
		

		$form = $model->getForm();

// try {



		if($modelName=='InputEmail' )
		{

			$data=$this->read('inputAccount');
			$info=Memberinfo::model()->findByAttributes(['memberinfo_account'=>$data['account']]);
			$model->email_real=maskEmail($info->memberinfo_email);

			$model->account=$data['account'];
		}
		if( $modelName=='InputPhone')
		{
// var_dump($this->read('inputAccount'));
// die;
			$data=$this->read('inputAccount');

			$info=Memberinfo::model()->findByAttributes(['memberinfo_account'=>$data['account']]);
			$model->phone_real=maskPhone($info->memberinfo_phone);
// var_dump($model->attributes);
// die;
		
			$model->account=$data['account'];
		
		
		}
		if (($form->submitted()||$form->submitted('save_draft')) && $form->validate()) {
			$event->sender->save(empty($model->attributes)?array():$model->attributes);
			$event->handled = true;

			switch ($event->step) {
				case 'emailphone':
					$branches = array(
						'email'=>WizardBehavior::BRANCH_DESELECT,
						'phone'=>WizardBehavior::BRANCH_DESELECT
					);
					if ($model->type==='email')
						$branches['email'] = WizardBehavior::BRANCH_SELECT;
					else
						$branches['phone'] = WizardBehavior::BRANCH_SELECT;
					break;
				case 'inputEmail':
					//复位密码，并用邮件发送
					$pass=$info->resetPassword();
					$model->send($data['account'],$pass);
					break;
				case 'inputPhone':
					//复位密码,并用短信发磅
					$pass=$info->resetPassword();
					$model->send($data['account'],$pass);
					break;
				default:
					break;
			}
			if (!empty($branches))
				$event->sender->branch($branches);
		}
		else
			$this->render('form', compact('event','form'));
	// }catch (Exception $e){
	// 	print_r($e->getMessage());
	// }
}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
	
		if(webapp()->theme->name!='classic')
		{
			$this->redirect(['start/index']);
		}
		else
		{
			if($this->beginCache('site/index',['duration'=>60]))
			{
				$this->renderPartial('index');
				$this->endCache();
			}
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin($isAdmin=false)
	{
		$model=new LoginForm;

		// if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            $model->attributes=$_POST['LoginForm'];
            if(isset($_POST['LoginForm']['username']) && Userinfo::model()->exists('userinfo_account=:name',[':name'=>$_POST['LoginForm']['username']]))
                $model->_identity=new UserIdentity($model->username,$model->password);
            else
            {
                if(config('site','is_enable')===0)
                {
                    throw new CHttpException(503);
                }
                $model->_identity=new MemberUserIdentity($model->username,$model->password);
            }
            if($model->validate() && $model->login())
            {
                if(isset($_SESSION['loginError']))
                    unset($_SESSION['loginError']);
                echo json_encode(array('success'=>true));
            }
            else
            {
                $post=$_POST;
                $error=$model->getErrors();
                echo json_encode(array('success'=>false,'msg'=>$error));
            }
            Yii::app()->end();
        }

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			if(isset($_POST['LoginForm']['username']) && Userinfo::model()->exists('userinfo_account=:name',[':name'=>$_POST['LoginForm']['username']]))
				$model->_identity=new UserIdentity($model->username,$model->password);
			else
			{
				if(config('site','is_enable')===0)
				{
					throw new CHttpException(503);
				}
				$model->_identity=new MemberUserIdentity($model->username,$model->password);
			}
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				if(isset($_SESSION['loginError']))
					unset($_SESSION['loginError']);
				$this->redirect(Yii::app()->user->returnUrl);
			}
			else
			{
				$_SESSION['loginError']=empty($_SESSION['loginError'])?1:$_SESSION['loginError']+1;
			}
		}
		//$dep=new CFileCacheDependency(__FILE__);
		// display the login form
		if(webapp()->theme->name=='user')
		{
			//if($this->beginCache('site/login_user',['duration'=>600,'dependency'=>$dep]))
			//{
				$this->render('login_user', array('model' => $model, 'isAdmin' => $isAdmin));
			//	$this->endCache();
			//}
		}
		else
		{
			//if($this->beginCache('site/login',['duration'=>600,'dependency'=>$dep]))
			//{
				$this->render('login', array('model' => $model, 'isAdmin' => $isAdmin));
			//	$this->endCache();
			//}
		}
	}
	public function actionAdminLogin()
	{
		$this->actionLogin($isAdmin=true);
	}
	public function actionAuthentic()
	{
		$this->layout='login_password';
		$model=new AuthenticForm();

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='authentic-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['AuthenticForm']))
		{
			$model->attributes=$_POST['AuthenticForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				$this->redirect(Yii::app()->user->returnUrl);
			}

		}
		// display the authentic form
		$this->render('authentic',array('model'=>$model));
	}

	/**
	 * 忘记密码
	 */
	public function actionForgotPassword($step=null)
	{
		$this->process($step);
		
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		$url='site/login';
		Yii::app()->user->logout();
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['success']=true;
            echo CJSON::encode($data);
            webapp()->end();
        }
		//$this->redirect(array('login'));
		client_redirect($this->createUrl($url),null,'parent');
	}
}