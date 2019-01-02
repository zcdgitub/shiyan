<?php

class AwardPeriodController extends Controller
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
		$model=new AwardPeriod('create');

		$this->performAjaxValidation($model);


		if(isset($_POST['AwardPeriod']))
		{
			
			$model->attributes=$_POST['AwardPeriod'];
			$this->log['target']=$model->award_period_id;
			if($model->save(true,array('award_period_period','award_period_memberinfo_id','award_period_currency','award_period_type_id','award_period_add_date')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->award_period_id));
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
		$this->performAjaxValidation($model);

		if(isset($_POST['AwardPeriod']))
		{
			$model->attributes=$_POST['AwardPeriod'];
			$this->log['target']=$model->award_period_id;
			if($model->save(true,array('award_period_period','award_period_memberinfo_id','award_period_currency','award_period_type_id','award_period_add_date')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->award_period_id));
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$model=new AwardPeriod('search');
		$model->unsetAttributes();  // clear any default values
		$model->awardPeriodMemberinfo=new Memberinfo('search');
		$model->awardPeriodType=new AwardType('search');
		if(!user()->isAdmin())
			$model->award_period_memberinfo_id=user()->id;
		$this->render('list',array(
			'dataProvider'=>$model->search(),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{

		//  $count_proc=new DbCall('award.award_fanli(:id,:order,:level)', array($this->membermap_id, $this->membermap_path));
  //                               $count_proc->run();
  //                               die;
		// $a=(string)new DbEvaluate("award.award_fanli(:id,:order,:level)",[':id'=>user()->id,':order'=>2,':level'=>$i]);
		if(webapp()->id=='141203' && !user()->isAdmin() && user()->id!=1)
		{
			if(user()->map->membermap_membertype_level==4 && $selTab==1)
				$curSumType=5;
			if(user()->map->membermap_membertype_level!=4 && $selTab==5)
				$curSumType=1;
		}
		$model=new AwardPeriod('search');
		$model->unsetAttributes();  // clear any default values
		$model->awardPeriodMemberinfo=new Memberinfo('search');
		$model->awardPeriodType=new AwardType('search');
		
		if(isset($_GET['AwardPeriod']))
		{
			$model->attributes=$_GET['AwardPeriod'];
			if(isset($_GET['AwardPeriod']['awardPeriodMemberinfo']))
				$model->awardPeriodMemberinfo->attributes=$_GET['AwardPeriod']['awardPeriodMemberinfo'];
			if(isset($_GET['AwardPeriod']['awardPeriodType']))
				$model->awardPeriodType->attributes=$_GET['AwardPeriod']['awardPeriodType'];
			
		}
		if(!user()->isAdmin())
			$model->award_period_memberinfo_id=user()->id;
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
		$model=AwardPeriod::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='award-period-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
