<?php

class GroupMapController extends Controller
{
	public $layout='//layouts/column2';


	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'closeSite',
			'rights', // rights rbac filter
			'postOnly + delete', // 只能通过POST请求删除
		);
	}
	public function actionMap($id=null)
	{
		$model=new GroupMap('search');
		if(!user()->isAdmin())
		{
			$model->groupmap_member_id=user()->id;
		}
		$this->render('map',['model'=>$model]);
	}

	// Uncomment the following methods and override them if needed
	public function actionCreate()
	{
		$model=new GroupMap('create');
		if(isset($_POST['GroupMap']))
		{
			$_POST['GroupMap']['groupmap_member_id']=Memberinfo::name2id(@$_POST['GroupMap']['groupmap_member_id']);
		}
		$this->performAjaxValidation($model);
		if(isset($_POST['GroupMap']))
		{
			$transaction=webapp()->db->beginTransaction();
			$model->attributes=$_POST['GroupMap'];
			$model->groupmap_name=$model->genUsername();
			$this->log['target']=$model->showName;
			if($model->save())
			{
				if(($status=$model->verify_b())===EError::SUCCESS)
				{
					$this->log['status']=LogFilter::SUCCESS;
					user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
					$this->log();
					$transaction->commit();
					$this->redirect(array('view','id'=>$model->groupmap_id));
				}
				elseif($status===EError::DUPLICATE)
				{
					$this->log['status']=LogFilter::FAILED;
					user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,请不要重复审核"));
				}
				elseif($status===EError::NOMONEY)
				{
					$this->log['status']=LogFilter::FAILED;
					user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,电子币不足"));
				}
				elseif($status===EError::NOVERIFY_AGENT)
				{
					$this->log['status']=LogFilter::FAILED;
					user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,代理中心未审核"));
				}
				elseif($status===EError::SAVE)
				{
					$this->log['status']=LogFilter::FAILED;
					user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,不能保存"));
				}
				elseif($status instanceof Exception)
				{
					//throw $status;
					$this->log['status']=LogFilter::FAILED;
					user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',$status->getMessage()));
				}
				else
				{
					$this->log['status']=LogFilter::FAILED;
					user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"审核失败"));
				}
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"保存失败"));
			}
			$transaction->rollback();
			$this->log();
		}
		$this->render('create',array(
			'model'=>$model,
			'financeType'=>FinanceType::model()->findAll()
		));
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=GroupMap::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-map-form')
		{
			echo ActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}