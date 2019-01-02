<?php

class LogController extends Controller
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
			'closeSite',
			'rights', // rights rbac filter
			//'postOnly + delete', // 只能通过POST请求删除
			'authentic + index,update,create,delete',//需要二级密码
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
		$model=new Log('create');

		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);


		if(isset($_POST['Log']))
		{
			$model->attributes=$_POST['Log'];
			if($model->save(true,array('log_category','log_source','log_operate','log_target','log_value','log_info','log_ip','log_user','log_role','log_add_date','log_status')))
				$this->redirect(array('view','id'=>$model->log_id));
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

		if(isset($_POST['Log']))
		{
			$model->attributes=$_POST['Log'];
			if($model->save(true,array('log_category','log_source','log_operate','log_target','log_value','log_info','log_ip','log_user','log_role','log_add_date','log_status')))
				$this->redirect(array('view','id'=>$model->log_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('Log');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new Log('search');
		$model->unsetAttributes();  // clear any default values

		$model->log_category='operate';
		if(isset($_GET['Log']))
		{
			$model->attributes=$_GET['Log'];
						
		}
		if(user()->role=='member' || user()->role=='agent' || user()->role=='shop')
		{
			$model->log_role==user()->role;
			$model->log_user=user()->name;
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}

    /**
     * Manages all models.
     */
    public function actionIndexFinance()
    {
        $model=new Log('search');
        $model->unsetAttributes();  // clear any default values
        if(user()->role=='member' || user()->role=='agent' || user()->role=='shop')
        {
            //$model->log_role==user()->role;
            $model->log_user=user()->name;
        }
        $model->log_category='finance';
        if(isset($_GET['Log']))
        {
            $model->attributes=$_GET['Log'];
        }
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['log']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
        $this->render('index',array(
            'model'=>$model,
        ));
    }
	/**
	 * Manages all models.
	 */
	public function actionIndexLogin()
	{
		$model=new Log('search');
		$model->unsetAttributes();  // clear any default values
		if(user()->role=='member' || user()->role=='agent')
		{
			$model->log_role==user()->role;
			$model->log_user=user()->name;
		}
		$model->log_category='login';
		if(isset($_GET['Log']))
		{
			$model->attributes=$_GET['Log'];

		}

		$this->render('index',array(
			'model'=>$model,
		));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Log::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='log-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
