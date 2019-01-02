<?php

class ConfigSmtpController extends Controller
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
			//'authentic + update,create,delete',//需要二级密码
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$model=ConfigSmtp::model()->find();
		$model->scenario='update';
		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);

		if(isset($_POST['ConfigSmtp']))
		{
			webapp()->cache->set('updateConfig',webapp()->cache->get('updateConfig')+1);
			$model->attributes=$_POST['ConfigSmtp'];
			$model->save(true,array('config_smtp_server','config_smtp_port','config_smtp_account','config_smtp_password','config_smtp_email','config_smtp_enable'));
			$this->log['target']=null;
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='config-smtp-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
