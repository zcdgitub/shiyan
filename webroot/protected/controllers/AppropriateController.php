<?php

class AppropriateController extends Controller
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
			'authentic + update,create,delete,deduct',//需要二级密码
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
	 * 拨款
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Appropriate('create');
		$model->appropriate_type=0;
		if(isset($_GET['appropriate_memberinfo_id']))
		{
			$model->appropriate_memberinfo_id=$_GET['appropriate_memberinfo_id'];
		}
		if(isset($_GET['appropriate_finance_type_id']))
		{
			$model->appropriate_finance_type_id=$_GET['appropriate_finance_type_id'];
		}
		if(isset($_POST['Appropriate']))
		{
			$agent_model=Agent::model()->findByAttributes(['agent_account'=>$_POST['Appropriate']['appropriate_memberinfo_id']]);
			if(is_null($agent_model))
			{
				$agent_id=Memberinfo::name2id(@$_POST['Appropriate']['appropriate_memberinfo_id']);
			}
			else
			{
				$agent_id = $agent_model->agent_memberinfo_id;
			}
			$_POST['Appropriate']['appropriate_memberinfo_id']=$agent_id;
		}
		$this->performAjaxValidation($model);

		if(isset($_POST['Appropriate']))
		{
			$model->attributes=$_POST['Appropriate'];
			$this->log['target']=$model->appropriate_id;
			$transaction=webapp()->db->beginTransaction();
			if($model->save(true,array('appropriate_currency','appropriate_finance_type_id','appropriate_add_date','appropriate_memberinfo_id','appropriate_type')) && $model->verify())
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				$transaction->commit();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->appropriate_id));
			}
			else
			{
				$transaction->rollback();
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'financeType'=>FinanceType::model()->findAll()
		));
	}
	/**
	 * 扣款
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionDeduct()
	{
		$model=new Appropriate('create');
		$model->appropriate_type=1;
		if(isset($_GET['appropriate_memberinfo_id']))
		{
			$model->appropriate_memberinfo_id=$_GET['appropriate_memberinfo_id'];
		}
		if(isset($_GET['appropriate_finance_type_id']))
		{
			$model->appropriate_finance_type_id=$_GET['appropriate_finance_type_id'];
		}
		if(isset($_POST['Appropriate']))
		{
			$agent_model=Agent::model()->findByAttributes(['agent_account'=>$_POST['Appropriate']['appropriate_memberinfo_id']]);
			if(is_null($agent_model))
			{
				$agent_id=Memberinfo::name2id(@$_POST['Appropriate']['appropriate_memberinfo_id']);
			}
			else
			{
				$agent_id = $agent_model->agent_memberinfo_id;
			}
			$_POST['Appropriate']['appropriate_memberinfo_id']=$agent_id;
		}
		$this->performAjaxValidation($model);

		if(isset($_POST['Appropriate']))
		{
			$model->attributes=$_POST['Appropriate'];
			$this->log['target']=$model->appropriate_id;
			$transaction=webapp()->db->beginTransaction();
			if($model->save(true,array('appropriate_currency','appropriate_finance_type_id','appropriate_add_date','appropriate_memberinfo_id','appropriate_type')) && $model->verify())
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				$transaction->commit();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->appropriate_id));
			}
			else
			{
				$transaction->rollback();
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}" . t('epmms',"失败或余额不足"));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'financeType'=>FinanceType::model()->findAll()
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

		if(isset($_POST['Appropriate']))
		{
			$model->attributes=$_POST['Appropriate'];
			$this->log['target']=$model->appropriate_id;
			if($model->save(true,array('appropriate_currency','appropriate_finance_type_id','appropriate_add_date','appropriate_memberinfo_id','appropriate_type')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->appropriate_id));
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
		$dataProvider=new CActiveDataProvider('Appropriate');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new Appropriate('search');
		$model->unsetAttributes();  // clear any default values
		$model->appropriateFinanceType=new FinanceType('search');
		$model->appropriateMemberinfo=new Memberinfo('search');
		
		if(isset($_GET['Appropriate']))
		{
			$model->attributes=$_GET['Appropriate'];
			if(isset($_GET['Appropriate']['appropriateFinanceType']))
				$model->appropriateFinanceType->attributes=$_GET['Appropriate']['appropriateFinanceType'];
			if(isset($_GET['Appropriate']['appropriateMemberinfo']))
				$model->appropriateMemberinfo->attributes=$_GET['Appropriate']['appropriateMemberinfo'];
			
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
		$model=Appropriate::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='appropriate-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
