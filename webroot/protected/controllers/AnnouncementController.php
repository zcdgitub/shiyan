<?php

class AnnouncementController extends Controller
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
			'closeSite',
			'rights', // rights rbac filter
			'postOnly + delete', // 只能通过POST请求删除
			'authentic + update,create,delete',//需要二级密码
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
		$model=new Announcement('create');

		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);


		if(isset($_POST['Announcement']))
		{
			$model->attributes=$_POST['Announcement'];
			$model->announcement_userinfo_id=user()->id;
			$this->log['target']=$model->announcement_title;
			if($model->save(true,array('announcement_title','announcement_content','announcement_add_date','announcement_userinfo_id','announcement_class')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->announcement_id));
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

		if(isset($_POST['Announcement']))
		{
			$model->attributes=$_POST['Announcement'];
			$model->announcement_userinfo_id=user()->id;
			$this->log['target']=$model->announcement_title;
			if($model->save(true,array('announcement_title','announcement_content','announcement_mod_date','announcement_userinfo_id','announcement_class')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->announcement_id));
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
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
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
    public function actionList($class_id=null)
    {
        $criteria=new CDbCriteria;
        $criteria->compare('announcement_class',$class_id);
        $dataProvider=new JSonActiveDataProvider('Announcement',['criteria'=>$criteria]);
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data=$dataProvider->getJsonData();
            echo $data;
            webapp()->end();
        }
        $this->render('list',array(
            'dataProvider'=>$dataProvider,
        ));
    }

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new Announcement('search');
		$model->unsetAttributes();  // clear any default values
		$model->announcementUserinfo=new Userinfo('search');
		$model->announcementClass=new AnnouncementClass('search');
		if(isset($_GET['Announcement']))
		{
			$model->attributes=$_GET['Announcement'];
			if(isset($_GET['Announcement']['announcementUserinfo']))
				$model->announcementUserinfo->attributes=$_GET['Announcement']['announcementUserinfo'];
			if(isset($_GET['Announcement']['announcementClass']))
				$model->announcementClass->attributes=$_GET['Announcement']['announcementClass'];
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
		$model=Announcement::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='announcement-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
