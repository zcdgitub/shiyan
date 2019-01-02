<?php

class PrizeController extends Controller
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
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionVerify($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='update';
		$this->log['target']=$model->prize_info;
		$model->prize_is_verify=1;
		$model->prize_verify_date=new CDbExpression('now()');
		if($model->save(true,array('prize_is_verify','prize_verify_date')))
		{
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('success',"{$this->actionName}" . t('epmms',"成功"));
			$this->redirect(array('view','id'=>$model->prize_id));
		}
		else
		{
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('error',"{$this->actionName}" . t('epmms',"失败"));
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}




	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new Prize('search');
		$model->unsetAttributes();  // clear any default values
		$model->prizeMember=new Memberinfo('search');
		
		if(isset($_GET['Prize']))
		{
			$model->attributes=$_GET['Prize'];
			if(isset($_GET['Prize']['prizeMember']))
				$model->prizeMember->attributes=$_GET['Prize']['prizeMember'];
			
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
		$model=Prize::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		if(!user()->isAdmin() && $model->prize_member_id!=user()->id)
			throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='prize-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
