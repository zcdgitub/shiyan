<?php

class JobController extends Controller
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
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='update';
		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);

		if(isset($_POST['Job']))
		{
			$model->attributes=$_POST['Job'];
			$this->log['target']=$model->jobid;
			$model->jobchanged=new CDbExpression('now()');
			if($model->save(true,array('jobname','jobdesc','jobenabled','jobchanged')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->jobid));
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
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
		$model=new Job('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['Job']))
		{
			$model->attributes=$_GET['Job'];
			
		}

		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}
	public function actionRun($id)
	{
		$id=(int)$id;
		if($id>0)
		{
			$connection=Yii::app()->pgagent;
			$sql="select jstdbname from pgagent.pga_jobstep as s where s.jstjobid=$id";
			$command=$connection->createCommand($sql);
			$dbname=$command->queryScalar();
			if($dbname==webapp()->db->database)
			{
				$model=$this->loadModel($id);
				$model->jobnextrun=new CDbExpression('now()');
				$model->saveAttributes(['jobnextrun']);
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"任务已经执行,请检查执行结果.");
				//等待执行完成,最长等待5s
				$cnt=1;
				sleep(1);
				while($cnt<5)
				{
					$model->refresh();
					if($model->lastStatus!='r')
						break;
					$cnt++;
					sleep(1);
				}
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"非法操作");
			}
		}
		$this->redirect(array('index'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Job::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='job-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
