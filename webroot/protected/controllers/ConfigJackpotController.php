<?php

class ConfigJackpotController extends Controller
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
		$model=new ConfigJackpot('create');

		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);


		if(isset($_POST['ConfigJackpot']))
		{
			$model->attributes=$_POST['ConfigJackpot'];
			$this->log['target']=$model->config_jackpot_id;
			if($model->save(true,array('config_jackpot_start_balance','config_jackpot_lucky_balance','config_jackpot_end_balance','config_jackpot_fund','config_jackpot_start_order_ratio','config_jackpot_lucky_order_ratio','config_jackpot_end_order_ratio','config_jackpot_start_time','config_jackpot_end_time')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->config_jackpot_id));
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
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
		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);
        $model->config_jackpot_start_time = date('Y-m-d H:i:s',$model->config_jackpot_start_time);
		if(isset($_POST['ConfigJackpot']))
		{
			$model->attributes=$_POST['ConfigJackpot'];
			$model->config_jackpot_start_time = strtotime($_POST['ConfigJackpot']['config_jackpot_start_time']);
			$model->config_jackpot_end_time   = $model->config_jackpot_start_time +86400;
			$this->log['target']=$model->config_jackpot_id;
			if($model->save(true,array('config_jackpot_start_balance','config_jackpot_lucky_balance','config_jackpot_end_balance','config_jackpot_fund','config_jackpot_start_order_ratio','config_jackpot_lucky_order_ratio','config_jackpot_end_order_ratio','config_jackpot_start_time','config_jackpot_end_time')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('update','id'=>$model->config_jackpot_id));
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
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
		}
		else
		{
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
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
		$dataProvider=new CActiveDataProvider('ConfigJackpot');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
        header('Content-Type: application/json');
        $model = $this->loadModel(1);
        $model->config_jackpot_start_time = time();
        $model->config_jackpot_end_time   = time()+86400;
        echo CJSON::encode($model);
        webapp()->end();
        if(webapp()->request->isAjaxRequest){
            header('Content-Type: application/json');
            echo CJSON::encode($this->loadModel(1));
            webapp()->end();
        }
		$model=new ConfigJackpot('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['ConfigJackpot']))
		{
			$model->attributes=$_GET['ConfigJackpot'];
			
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
		$model=ConfigJackpot::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='config-jackpot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
