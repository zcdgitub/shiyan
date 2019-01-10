<?php

class MybankController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
            'cors',
			'rights', // rights rbac filter
			'postOnly + delete', // 只能通过POST请求删除
			//'authentic + update,create,delete',//需要二级密码
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Mybank('create');

        if(isset($_POST['Mybank']))
        {
            $old_Sale=$_POST['Mybank'];
            if(user()->isAdmin())
            {
                $_POST['Mybank']['mybank_memberinfo_id']=Memberinfo::name2id(@$_POST['Mybank']['mybank_memberinfo_id']);
            }
            else
            {
                $_POST['Mybank']['mybank_memberinfo_id']=user()->id;
            }
        }
        if(isset($_POST['Mybank']['mybank_is_default']))
        {
            if(strtolower($_POST['Mybank']['mybank_is_default']==='true') || $_POST['Mybank']['mybank_is_default']==='1')
                $_POST['Mybank']['mybank_is_default']=1;
            else
                $_POST['Mybank']['mybank_is_default']=0;
        }
		$this->performAjaxValidation($model);


		if(isset($_POST['Mybank']))
		{
			$model->attributes=$_POST['Mybank'];
			$this->log['target']=$model->mybank_account;
			if($model->save(true,array('mybank_bank_id','mybank_name','mybank_account','mybank_memberinfo_id','mybank_add_date','mybank_is_default','mybank_address')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if(user()->hasFlash('success'))
                    {
                        $data['success'] = user()->getFlash('success', '成功', true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
				$this->redirect(array('view','id'=>$model->mybank_id));
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if($model->getErrors())
                        $data=$model->getErrors();
                    elseif(user()->hasFlash('error')){
                        $data['error']=user()->getFlash('error','失败',true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
			}
		}
        if(isset($old_Sale))
        {
            $model->attributes = $old_Sale;
        }
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='update';
        if(isset($_POST['Mybank']['mybank_is_default']))
        {
            if(strtolower($_POST['Mybank']['mybank_is_default']==='true') || $_POST['Mybank']['mybank_is_default']==='1')
                $_POST['Mybank']['mybank_is_default']=1;
            else
                $_POST['Mybank']['mybank_is_default']=0;
        }
		$this->performAjaxValidation($model);

		if(isset($_POST['Mybank']))
		{
			$model->attributes=$_POST['Mybank'];
			$this->log['target']=$model->mybank_account;
			if($model->save(true,array('mybank_bank_id','mybank_name','mybank_account','mybank_memberinfo_id','mybank_add_date','mybank_is_default','mybank_address')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if(user()->hasFlash('success'))
                    {
                        $data['success'] = user()->getFlash('success', '成功', true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
				$this->redirect(array('view','id'=>$model->mybank_id));
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if($model->getErrors())
                        $data=$model->getErrors();
                    elseif(user()->hasFlash('error')){
                        $data['error']=user()->getFlash('error','失败',true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$this->log['target']=$model->showName;
		if($model->delete())
		{
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                if(user()->hasFlash('success'))
                {
                    $data['success'] = user()->getFlash('success', '成功', true);
                }
                echo CJSON::encode($data);
                webapp()->end();
            }
		}
		else
		{
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                if($model->getErrors())
                    $data=$model->getErrors();
                elseif(user()->hasFlash('error')){
                    $data['error']=user()->getFlash('error','失败',true);
                }
                echo CJSON::encode($data);
                webapp()->end();
            }
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('Mybank');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new Mybank('search');
		$model->unsetAttributes();  // clear any default values
		$model->mybankBank=new Bank('search');
		$model->mybankMemberinfo=new Memberinfo('search');
		
		if(isset($_GET['Mybank']))
		{
			$model->attributes=$_GET['Mybank'];
			if(isset($_GET['Mybank']['mybankBank']))
				$model->mybankBank->attributes=$_GET['Mybank']['mybankBank'];
			if(isset($_GET['Mybank']['mybankMemberinfo']))
				$model->mybankMemberinfo->attributes=$_GET['Mybank']['mybankMemberinfo'];
		}
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $model->mybank_memberinfo_id=user()->id;
            $data['mybank']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Mybank::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mybank-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
