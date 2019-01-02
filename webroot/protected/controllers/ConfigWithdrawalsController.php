<?php

class ConfigWithdrawalsController extends Controller
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
			'authentic + index',//需要二级密码
		);
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionIndex($selTab=0)
	{
		$model=ConfigWithdrawals::model()->find();
		$model->scenario='update';
		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);

		if(isset($_POST['ConfigWithdrawals']))
		{
			$model->attributes=$_POST['ConfigWithdrawals'];
			$this->log['target']=null;
			if($model->save())
			{
				webapp()->cache->set('updateConfig',webapp()->cache->get('updateConfig')+1);
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',t('epmms',"修改“{$model->modelName}”" . "成功"));
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('error',t('epmms',"修改“{$model->modelName}”" . "失败"));
			}
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
		$model=ConfigWithdrawals::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='config-withdrawals-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
