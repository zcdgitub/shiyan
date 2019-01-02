<?php

class SigningController extends Controller
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Signing('create');

		$this->performAjaxValidation($model);


		if(isset($_POST['Signing']))
		{
			$model->attributes=$_POST['Signing'];
			$this->log['target']=$model->signing_id;
			if($model->save(true,array('signing_member_id','signing_is_verify','signing_date','signing_is_refund','signing_verify_date','signing_type')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->signing_id));
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
	public function actionVerify($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='verify';
		$this->performAjaxValidation($model);

		if(isset($_POST['Signing']))
		{
			$model->attributes=$_POST['Signing'];
			$model->signing_is_verify=1;
			$model->signing_verify_date=new CDbExpression('now()');
			$this->log['target']=$model->signing_id;
			if($model->save(true,array('signing_is_verify','signing_date','signing_verify_date')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->signing_id));
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
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionRefund($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='refuse';
		if($model->signing_type==1)
		{
			$this->log['target']=$model->signingMember->memberinfo_account;
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"购物签约不能退款"));
			$this->redirect(webapp()->request->urlReferrer);
			return;
		}
		$signing_date=strtotime($model->signing_date);
		if(time()-$signing_date<60*60*24*41)
		{
			$model->signing_is_verify=2;
			$model->signing_is_refund=1;
			$this->log['target']=$model->signing_id;
			if($model->save(true,array('signing_is_verify','signing_is_refund')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->signing_id));
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"退款失败"));
			}
		}
		else
		{
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('error', t('epmms',"已超过40天，不能退款"));
		}
		$this->redirect(array('index','selTab'=>1));

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
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new Signing('search');
		$model->unsetAttributes();  // clear any default values
		$model->signingMember=new Memberinfo('search');
		
		if(isset($_GET['Signing']))
		{
			$model->attributes=$_GET['Signing'];
			if(isset($_GET['Signing']['signingMember']))
				$model->signingMember->attributes=$_GET['Signing']['signingMember'];
			
		}
		$model->signing_is_verify=$selTab;
		if(!user()->isAdmin())
		{
			$model->signing_member_id=user()->id;
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
		$model=Signing::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		if(!user()->isAdmin())
		{
			if($model->signing_member_id!=user()->id)
				throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='signing-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
