<?php

class ConfigSiteController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='config';

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
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{

		$model=ConfigSite::model()->find();

		$model->scenario='update';
		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);

		if(isset($_POST['ConfigSite']))
		{
			
			webapp()->cache->set('updateConfig',webapp()->cache->get('updateConfig')+1);
			$model->attributes=$_POST['ConfigSite'];
			$model->save();
			if((int)$model->config_site_deny_robots===0)
			{
				file_put_contents(dirname(Yii::app()->BasePath) . DIRECTORY_SEPARATOR . 'robots.txt', "User-agent: *\nDisallow: /");
			}
			else
			{
				file_put_contents(dirname(Yii::app()->BasePath) . DIRECTORY_SEPARATOR . 'robots.txt', "User-agent: *\nAllow:/");
			}
			$this->log['target']=null;
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
}
