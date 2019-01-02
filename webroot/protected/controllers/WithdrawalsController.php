<?php

class WithdrawalsController extends Controller
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
			//'postOnly + delete', // 只能通过POST请求删除
			'authentic + update,delete',//需要二级密码
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

		$model=new Withdrawals('create');
		//$model->withdrawals_finance_type_id=1;
		if(isset($_POST['Withdrawals']))
		{


			$_POST['Withdrawals']['withdrawals_member_id']=user()->id;
			if(empty($_POST['Withdrawals']['withdrawals_finance_type_id']))
			    $_POST['Withdrawals']['withdrawals_finance_type_id']=1;
		}
		$this->performAjaxValidation($model);


		if(isset($_POST['Withdrawals']))
		{

      
       
			$model->attributes=$_POST['Withdrawals'];
		  if($model->validate()){

			$fee_config=config('withdrawals','tax');
			$award_config_currency=abs($fee_config);
			$tax=$model->withdrawals_currency*$award_config_currency;
			if(webapp()->id=='150608')
			{
				$tax=ceil($model->withdrawals_currency/100)*100*$award_config_currency;
				if(abs($tax)<2)
					$tax=2;
			}

			$model->withdrawals_tax=-abs($tax);
			//提现封顶
			$tax_cap=config('withdrawals','tax_cap');
			if(!empty($tax_cap) && $tax_cap!=0)
			{
				if(abs($model->withdrawals_tax)>abs($tax_cap))
					$model->withdrawals_tax=-abs($tax_cap);
			}
			if(config('withdrawals','type')==1)
				$model->withdrawals_real_currency=$model->withdrawals_currency-abs($model->withdrawals_tax);
			else
				$model->withdrawals_real_currency=$model->withdrawals_currency+abs($model->withdrawals_tax);
			$this->log['target']=user()->name;
			$transaction=webapp()->db->beginTransaction();

			if($model->save(true,array('withdrawals_member_id','withdrawals_currency',
					'withdrawals_add_date','withdrawals_is_verify',
					'withdrawals_verify_date','withdrawals_remark',
					'withdrawals_finance_type_id','withdrawals_sn',
					'withdrawals_tax','withdrawals_real_currency')) && $model->verify()
			)
			{

				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if(user()->hasFlash('success'))
                    {
                        $data['success']=true;
                        $data['msg'] = user()->getFlash('success', '成功', true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
				$this->redirect(array('view','id'=>$model->withdrawals_id));
			}
			else
			{
				
				$transaction->rollback();
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data=[];
                    if(user()->hasFlash('error'))
                    {
                        $data['success']=false;
                        $data['error']=$model->getErrors();
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
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
			'financeType'=>FinanceType::model()->findAll()
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdminCreate($id=null,$award=null)
	{
		$model=new Withdrawals('create');
		$finance=user()->isAdmin()?Finance::model()->findByPk($id):null;
		if(user()->isAdmin())
		{
			$model->withdrawals_member_id=$finance->finance_memberinfo_id;
			$model->withdrawalsMember=Memberinfo::model()->findByPk($finance->finance_memberinfo_id);
			$model->withdrawals_finance_type_id=$finance->finance_type;
			$model->withdrawalsFinanceType=FinanceType::model()->findByPk($finance->finance_type);
			if(!is_null($award))
				$model->withdrawals_currency=$award;
		}
		//$model->withdrawals_finance_type_id=1;

		if(isset($_POST['Withdrawals']))
		{
			if(user()->isAdmin())
			{
				$_POST['Withdrawals']['withdrawals_member_id']=$finance->finance_memberinfo_id;
				$_POST['Withdrawals']['withdrawals_finance_type_id']=$finance->finance_type;
			}
			else
			{
				$_POST['Withdrawals']['withdrawals_member_id']=user()->id;
			}
		}
		$this->performAjaxValidation($model);

		if(isset($_POST['Withdrawals']))
		{
			$model->attributes=$_POST['Withdrawals'];

			$fee_config=config('withdrawals','tax');
			$award_config_currency=abs($fee_config);
			$model->withdrawals_tax=-$model->withdrawals_currency*$award_config_currency;
			//提现封顶
			$tax_cap=config('withdrawals','tax_cap');
			if(!empty($tax_cap))
			{
				if(abs($model->withdrawals_tax)>abs($tax_cap))
					$model->withdrawals_tax=-abs($tax_cap);
			}
			if(config('withdrawals','type')==1)
				$model->withdrawals_real_currency=$model->withdrawals_currency-abs($model->withdrawals_tax);
			else
				$model->withdrawals_real_currency=$model->withdrawals_currency+abs($model->withdrawals_tax);
			$this->log['target']=user()->name;
			$transaction=webapp()->db->beginTransaction();
			if($model->save(true,array('withdrawals_member_id','withdrawals_currency',
					'withdrawals_add_date','withdrawals_is_verify',
					'withdrawals_verify_date','withdrawals_remark',
					'withdrawals_finance_type_id','withdrawals_sn',
					'withdrawals_tax','withdrawals_real_currency')) && $model->verify()
			)
			{
				if(user()->isAdmin())
				{
					if($model->send())
					{
						$transaction->commit();
						$this->log['status']=LogFilter::SUCCESS;
						$this->log();
						user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
						$this->redirect(array('view','id'=>$model->withdrawals_id));
					}
					else
					{
						$transaction->rollback();
						$this->log['status']=LogFilter::FAILED;
						$this->log();
						user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
					}
				}
				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->withdrawals_id));
			}
			$transaction->rollback();
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
		}

		$this->render('create',array(
			'model'=>$model,
			'financeType'=>FinanceType::model()->findAll(),
			'finance'=>$finance
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

		if(isset($_POST['Withdrawals']))
		{
			$model->attributes=$_POST['Withdrawals'];
			$this->log['target']=$model->withdrawals_id;
			if($model->save(true,array('withdrawals_currency','withdrawals_remark')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->withdrawals_id));
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
		if(!user()->isAdmin())
		{
			if($model->withdrawals_member_id!=user()->id)
				throw new CHttpException(404,t('epmms','请求的页面不存在'));
		}
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
		$dataProvider=new CActiveDataProvider('Withdrawals');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($isVerifyType=0)
	{
		$model=new Withdrawals('search');
		$model->unsetAttributes();  // clear any default values
		$model->withdrawalsMember=new Memberinfo('search');
		$model->withdrawalsFinanceType=new FinanceType('search');
		$model->withdrawals_is_verify=(int)$isVerifyType;

		if(isset($_GET['Withdrawals']))
		{
			$model->attributes=$_GET['Withdrawals'];
			if(isset($_GET['Withdrawals']['withdrawalsMember']))
				$model->withdrawalsMember->attributes=$_GET['Withdrawals']['withdrawalsMember'];
			if(isset($_GET['Withdrawals']['withdrawalsFinanceType']))
				$model->withdrawalsFinanceType->attributes=$_GET['Withdrawals']['withdrawalsFinanceType'];
			
		}
		if(!user()->isAdmin())
		{
			$model->withdrawals_member_id=user()->id;
		}
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['withdrawals']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
		$this->render('index',array(
			'model'=>$model,
			'isVerifyType'=>(int)$isVerifyType
		));
	}
	/**
	 * 申核转帐申请
	 * @param $id 转帐申请id
	 */
	public function actionVerify($id)
	{
		$model=Withdrawals::model()->findByPk($id);
		if($model->send())
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
		$this->redirect(array('index'),true);
	}
	/**
	 * 申核转帐申请
	 * @param $id 转帐申请id
	 */
	public function actionSend($id)
	{
		$model=Withdrawals::model()->findByPk($id);
		if($model->send2())
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
		$this->redirect(array('index','isVerifyType'=>1),true);
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Withdrawals::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if(!user()->isAdmin() && user()->id!=$model->withdrawals_member_id)
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='withdrawals-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
