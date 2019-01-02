<?php

class ChargeController extends Controller
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
	public function allowedActions()
	{
		return 'payNotify,payReturn,payTest';
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
		$model=new Charge('create');
		$model->charge_finance_type_id=2;
		if(isset($_POST['Charge']))
		{
			$_POST['Charge']['charge_memberinfo_id']=user()->id;
		}
		$this->performAjaxValidation($model);

		if(isset($_POST['Charge']))
		{
			$model->attributes=$_POST['Charge'];
			$this->log['value']=$model->charge_currency;
			if($model->save(true,array('charge_sn','charge_memberinfo_id','charge_currency','charge_is_verify','charge_bank_id','charge_bank_account','charge_bank_address','charge_bank_sn','charge_bank_date','charge_bank_account_name','charge_finance_type_id','charge_remark')))
			{
				$this->log['target']=$model->charge_sn;
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if(user()->hasFlash('success'))
                    {
                        $data['success'] = user()->getFlash('success', '成功', true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
				$this->redirect(array('view','id'=>$model->charge_id));
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if($model->getErrors())
                        $data=$model->getErrors();
                    elseif(user()->hasFlash('error')){
                        $data['error']=user()->getFlash('error','失败',true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
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

		if(isset($_POST['Charge']))
		{
			$model->attributes=$_POST['Charge'];
			$this->log['target']=$model->charge_id;
			if($model->save(true,array('charge_currency','charge_bank_id','charge_bank_account','charge_bank_address','charge_bank_sn','charge_bank_date','charge_bank_account_name','charge_remark')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->charge_id));
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('Charge');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($isVerifyType=0,$charge_type=0)
	{
		$model=new Charge('search');
		$model->unsetAttributes();  // clear any default values
		$model->chargeMemberinfo=new Memberinfo('search');
		$model->chargeBank=new Bank('search');
		$model->chargeFinanceType=new FinanceType('search');

		if(isset($_GET['Charge']))
		{
			$model->attributes=$_GET['Charge'];
			if(isset($_GET['Charge']['chargeMemberinfo']))
				$model->chargeMemberinfo->attributes=$_GET['Charge']['chargeMemberinfo'];
			if(isset($_GET['Charge']['chargeBank']))
				$model->chargeBank->attributes=$_GET['Charge']['chargeBank'];
			if(isset($_GET['Charge']['chargeFinanceType']))
				$model->chargeFinanceType->attributes=$_GET['Charge']['chargeFinanceType'];
			
		}
		$model->charge_is_verify=(int)$isVerifyType;
		$model->charge_type=$charge_type;
		if(!user()->isAdmin())
		{
			$model->charge_memberinfo_id=user()->id;
			$total=$model->find(['select'=>'COALESCE(sum(charge_currency),0) as sum_currency','condition'=>'charge_memberinfo_id=:id and charge_is_verify=1','params'=>[':id'=>user()->id]]);
		}
		else
		{
			$total=$model->find(['select'=>'COALESCE(sum(charge_currency),0) as sum_currency','condition'=>'charge_is_verify=1','params'=>[':id'=>user()->id]]);
		}
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['charge']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
		$this->render('index',array(
			'model'=>$model,
			'isVerifyType'=>(int)$isVerifyType,
			'charge_type'=>$charge_type,
			'total'=>$total
		));
	}
	public function actionVerify($id)
	{
		$model=$this->loadModel($id);
		if($model->verify())
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
	public function actionPay()
	{
		$model=new Charge('create');
		$model->charge_finance_type_id=2;
		if(isset($_POST['Charge']))
		{
			$_POST['Charge']['charge_memberinfo_id']=user()->id;
		}
		$this->performAjaxValidation($model);

		if(isset($_POST['Charge']))
		{
			$model->attributes=$_POST['Charge'];
			$this->log['value']=$model->charge_currency;
			$model->charge_type=1;
			$model->charge_bank_id=-1;
			if($model->save(true,array('charge_sn','charge_memberinfo_id','charge_bank_id','charge_currency','charge_is_verify','charge_finance_type_id','charge_remark','charge_type')))
			{
				$this->log['target']=$model->charge_sn;
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				//user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('payOn','id'=>$model->charge_id));
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
		}

		$this->render('createPay',array(
			'model'=>$model,
			'financeType'=>FinanceType::model()->findAll()
		));
	}
	public function actionPayOn($id)
	{
		$order=Charge::model()->findByPk($id);
		if(is_null($order))
			throw new Error(t('epmms','订单错误'));
		if($order->charge_type==0)
			throw new Error(t('epmms','订单错误'));
		$pay = Yii::app()->pay;
		$pay->order_sn = $order->charge_sn;
		$pay->order_currency = $order->charge_currency;
		$pay->subject="在线充值";
		$pay->return_url=['charge/payReturn'];
		$pay->notify_url=['charge/payNotify'];
		// Set other optional params if needed
		$this->render('pay', ['pay' => $pay]);
	}
	public function actionPayNotify()
	{
		$reqinfo=implode(',',$_REQUEST);
		//LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付通知','user'=>'Guest','role'=>'Guest','target'=>'testPay','status'=>LogFilter::SUCCESS,'info'=>$reqinfo]);
		$pay = Yii::app()->pay;
		$result=$pay->verifyNotify();
		//LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付通知','user'=>'Guest','role'=>'Guest','target'=>'testPay2','status'=>LogFilter::SUCCESS,'info'=>implode(',',$result)]);
		if(is_array($result))
		{
			$order=Charge::model()->findByAttributes(['charge_sn'=>$result['order_sn'],'charge_is_verify'=>0]);
			if(is_null($order))
			{
				LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付通知','user'=>'Guest','role'=>'Guest','target'=>$result['order_sn'],'status'=>LogFilter::FAILED,'value'=>$result['order_currency'],'info'=>'充值不存在或已支付']);
				return;
			}

			if ($result['order_currency']>=$order->charge_currency)
			{
				//支付成功
				$order->payVerify();
				LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付通知','user'=>'Guest','role'=>'Guest','target'=>$result['order_sn'],'status'=>LogFilter::SUCCESS,'value'=>$result['order_currency'],'info'=>'在线支付成功']);
				echo 'success';
			}
			else
			{
				$result_str=t('epmms','支付金额不足');
				LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付通知','user'=>'Guest','role'=>'Guest','target'=>$result['order_sn'],'status'=>LogFilter::FAILED,'value'=>$result['order_currency'],'info'=>$result_str]);
			}
		}
		else
		{
			LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付通知','user'=>'Guest','role'=>'Guest','status'=>LogFilter::FAILED,'info'=>'在线支付故障']);
		}
	}
	public function actionPayTest($id)
	{
		$order=Charge::model()->findByPk($id);
		if(is_null($order))
			throw new Error(t('epmms','订单错误'));
		if($order->charge_type==0)
			throw new Error(t('epmms','订单错误'));
		$pay = Yii::app()->pay;
		$pay->gateway=CHtml::normalizeUrl(['charge/payNotify']);
		echo $pay->buildFormTest(['TransID'=>$order->charge_sn,'Result'=>1,'factMoney'=>$order->charge_currency,'SuccTime'=>date('YmdHis'),'gateway'=>$pay->gateway]);
		// Set other optional params if needed
	}
	public function actionPayReturn()
	{
		//$reqinfo=implode(',',$_REQUEST);
		//$reqinfo=null;
		//LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付返回','user'=>'Guest','role'=>'Guest','target'=>'testPay','status'=>LogFilter::SUCCESS,'info'=>$reqinfo]);
		$pay = Yii::app()->pay;
		$result=$pay->verifyReturn();
		if(is_array($result))
		{
			$order=Charge::model()->findByAttributes(['charge_sn'=>$result['order_sn']]);
			if(is_null($order) )
			{
				LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付返回','user'=>'Guest','role'=>'Guest','target'=>$result['order_sn'],'status'=>LogFilter::FAILED,'value'=>$result['order_currency'],'info'=>'充值不存']);
				echo "该充值不存在";
				return;
			}
			if($order->charge_is_verify==1)
			{
				LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付返回','user'=>'Guest','role'=>'Guest','target'=>$result['order_sn'],'status'=>LogFilter::FAILED,'value'=>$result['order_currency'],'info'=>'充值已审核']);
				$this->redirect(array('view','id'=>$order->charge_id));
				return;
			}

			if ($result['order_currency']>=$order->charge_currency)
			{
				//支付成功
				$order->payVerify();
				LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付返回','user'=>'Guest','role'=>'Guest','target'=>$result['order_sn'],'status'=>LogFilter::SUCCESS,'value'=>$result['order_currency'],'info'=>'在线支付成功']);
			}
			else
			{
				$result_str=t('epmms','支付金额不足');
				LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付返回','user'=>'Guest','role'=>'Guest','target'=>$result['order_sn'],'status'=>LogFilter::FAILED,'value'=>$result['order_currency'],'info'=>$result_str]);
			}
			$this->redirect(array('view','id'=>$order->charge_id));
		}
		else
		{
			LogFilter::log(['category'=>'operate','source'=>'在线充值','operate'=>'支付返回','user'=>'Guest','role'=>'Guest','status'=>LogFilter::FAILED,'info'=>'在线支付故障']);
			echo "在线支付故障";
		}

	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Charge::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if(!user()->isAdmin() && user()->id!=$model->charge_memberinfo_id)
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='charge-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
