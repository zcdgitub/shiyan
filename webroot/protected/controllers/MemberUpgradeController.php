<?php

class MemberUpgradeController extends Controller
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
			'cors',
			'closeSite',
			'rights', // rights rbac filter
			'postOnly + delete', // 只能通过POST请求删除
			/*'authentic + update,create,delete,verify',*///需要二级密码
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
		


		$model=new MemberUpgrade('create');
		$model->member_upgrade_member_id=user()->id;
		
		$model->member_upgrade_old_type=user()->map->membermap_membertype_level;

		$this->performAjaxValidation($model);


		if(isset($_POST['MemberUpgrade']))
		{

			$model->member_upgrade_type=$_POST['MemberUpgrade']['member_upgrade_type'];
			$model->attributes=$_POST['MemberUpgrade'];
			$this->log['target']=$model->member_upgrade_id;
		 if($model->validate()){


			$transaction=webapp()->db->beginTransaction();
            $cmd=webapp()->db->createCommand("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;");
            $cmd->execute();
			try
            {
                $membertype = MemberType::model()->findByPk($model->member_upgrade_type);

                $model->member_upgrade_money = $membertype->membertype_money;


                if ($model->save(true,
                        array('member_upgrade_member_id', 'member_upgrade_type',
                            'member_upgrade_add_date', 'member_upgrade_old_type',
                            'member_upgrade_money')) && ($status = $model->verify()) == EError::SUCCESS)
                {
                    $activationModel = new ActivationRecord();
                    $activationModel->activation_member_id = user()->id;
                    $activationModel->activation_add_time  = date('Y-m-d H:i:s',time());
                    $activationModel->save();
                    $transaction->commit();
                    $this->log['status'] = LogFilter::SUCCESS;
                    $this->log();
                    user()->setFlash('success', "{$this->actionName}" . t('epmms', "成功"));

                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success'] = true;
                        echo CJSON::encode($data);
                        webapp()->end();
                    }

                    $this->redirect(array('view', 'id' => $model->member_upgrade_id));
                } elseif ($status === EError::DUPLICATE)
                {

                    $this->log['status'] = LogFilter::FAILED;
                    user()->setFlash('error', "{$this->actionName}“{$model->showName}”" . t('epmms', "失败,请不要重复审核"));
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success'] = false;
                        $data['msg'] = '失败,请不要重复审核';
                        echo CJSON::encode($data);
                        return;
                    }
                } elseif ($status === EError::NOMONEY)
                {

                    $this->log['status'] = LogFilter::FAILED;
                    user()->setFlash('error', "{$this->actionName}“{$model->showName}”" . t('epmms', "失败,电子币不足"));
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success'] = false;
                        $data['msg'] = '失败,报单币不足';
                        echo CJSON::encode($data);
                        return;
                    }

                } elseif ($status === EError::NOSAVE)
                {
                    $this->log['status'] = LogFilter::FAILED;
                    user()->setFlash('error', "{$this->actionName}“{$model->showName}”" . t('epmms', "失败,不能保存"));
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success'] = false;
                        $data['msg'] = '失败,不能保存';
                        echo CJSON::encode($data);
                        return;
                    }
                } elseif ($status === EError::NOVERIFY)
                {
                    $this->log['status'] = LogFilter::FAILED;
                    user()->setFlash('error', "{$this->actionName}“{$model->showName}”" . t('epmms', "失败,会员未审核"));
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success'] = false;
                        $data['msg'] = '失败,会员未审核';
                        echo CJSON::encode($data);
                        return;
                    }
                } elseif ($status instanceof Exception)
                {
                    //throw $status;
                    $this->log['status'] = LogFilter::FAILED;
                    user()->setFlash('error', "{$this->actionName}“{$model->showName}”" . t('epmms', $status->getMessage()));
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success'] = false;
                        $data['msg'] = $status->getMessage();
                        echo CJSON::encode($data);
                        return;
                    }
                } else
                {
                    $this->log['status'] = LogFilter::FAILED;
                    user()->setFlash('error', "{$this->actionName}“{$model->showName}”" . t('epmms', "失败"));
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success'] = false;
                        $data['msg'] = '失败';
                        echo CJSON::encode($data);
                        return;
                    }
                }
              
            }
            catch(CDbException $e)
            {
                $transaction->rollback();
                if($e->getCode()=='40001')
                {
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['error'] = "当前系统繁忙，请重试";
                        echo CJSON::encode($data);
                        return;
                    }
                    else
                    {
                        return EError::DUPLICATE;
                    }
                }
                $transaction->rollback();
                throw $e;
                //return EError::ERROR;
            }
            catch(Exception $e)
            {
                $transaction->rollback();
                if (webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['error'] = "升级出错";
                    echo CJSON::encode($data);
                    return;
                }
                else
                {
                    user()->setFlash('error', "{$this->actionName}“{$model->showName}”" . t('epmms', "升级出错"));
                }
            }
		  }else{
		  
		   	   $this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['msg']=$model->getErrors();
                    $data['success']=false;
                    echo CJSON::encode($data);
                    return;
                }
		   }
		}

		$this->render('create',array(
			'model'=>$model,
			'money'=>user()->map->membermap_money
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

		if(isset($_POST['MemberUpgrade']))
		{
			$model->attributes=$_POST['MemberUpgrade'];
			$this->log['target']=$model->member_upgrade_id;
			if($model->save(true,array('member_upgrade_member_id','member_upgrade_type','member_upgrade_is_verify','member_upgrade_add_date','member_upgrade_verify_date','member_upgrade_period')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->member_upgrade_id));
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
		if((user()->isAdmin() || (!user()->isAdmin() && $id=user()->id)) && $model->delete())
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
		$dataProvider=new CActiveDataProvider('MemberUpgrade');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new MemberUpgrade('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberUpgradeMember=new Membermap('search');
		$model->memberUpgradeType=new MemberType('search');
		//$model->member_upgrade_is_verify=$selTab;

		if(isset($_GET['MemberUpgrade']))
		{
			$model->attributes=$_GET['MemberUpgrade'];
			if(isset($_GET['MemberUpgrade']['memberUpgradeMember']))
				$model->memberUpgradeMember->attributes=$_GET['MemberUpgrade']['memberUpgradeMember'];
			if(isset($_GET['MemberUpgrade']['memberUpgradeType']))
				$model->memberUpgradeType->attributes=$_GET['MemberUpgrade']['memberUpgradeType'];
			
		}
		if(!user()->isAdmin())
		{
			$model->member_upgrade_member_id=user()->id;
		}
		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}
	public function actionVerify($id)
	{
		$model=$this->loadModel($id);
		if($model->verify())
		{
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('success',"{$this->actionName}" . t('epmms',"成功"));
		}
		else
		{
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"{$this->actionName}" . t('epmms',"失败"));
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=MemberUpgrade::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		if(!user()->isAdmin() && user()->id!=$model->member_upgrade_member_id)
		{
			throw new CHttpException(403,t('epmms','没有权限。'));
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='member-upgrade-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
