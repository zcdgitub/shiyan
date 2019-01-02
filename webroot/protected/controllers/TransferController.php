<?php

class TransferController extends Controller
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
			'authentic + create,createSelf,update,delete',//需要二级密码
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

		$model=new Transfer('create');
		$this->log['target']=isset($_POST['Transfer']['transfer_dst_member_id'])?$_POST['Transfer']['transfer_dst_member_id']:null;
		if(isset($_POST['Transfer']))
		{
			$_POST['Transfer']['transfer_dst_member_id']=isset($_POST['Transfer']['transfer_dst_member_id'])?Memberinfo::name2id($_POST['Transfer']['transfer_dst_member_id']):null;
			$_POST['Transfer']['transfer_src_member_id']=user()->id;
		}
		$this->performAjaxValidation($model);
	
		if(isset($_POST['Transfer']))
		{
		// 		echo "<pre>";
		// var_dump($_POST['Transfer']);
		// die;

			$model->attributes=$_POST['Transfer'];
		  if($model->validate()){
		  	/*echo "nihao";
		  	die;*/
			$transaction=webapp()->db->beginTransaction();
			$tax_config=TransferConfig::model()->find()->transfer_config_tax;
			if(webapp()->id=='150509')
			{
				if($model->transfer_src_finance_type==2)
					$tax_config=0;
			}
			$model->transfer_tax=-$model->transfer_currency*$tax_config;
			if($model->save(false,array('transfer_src_member_id','transfer_src_finance_type','transfer_dst_member_id','transfer_dst_finance_type','transfer_currency','transfer_remark','transfer_is_verify','transfer_add_date','transfer_verify_date','transfer_sn','transfer_tax'))
			&& $model->verify())
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
                        $data['success'] = user()->getFlash('success', '成功', true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
				$this->redirect(array('view','id'=>$model->transfer_id));
			}
			else
			{
				$transaction->rollback();
				$model->transfer_dst_member_id=isset($_POST['Transfer']['transfer_dst_member_id'])?Memberinfo::id2name($_POST['Transfer']['transfer_dst_member_id']):null;;
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
			'self'=>false,
			'financeType'=>FinanceType::model()->findAll()
		));
	}
    public function actionCreateSelf()
    {
        $model=new Transfer('create');

        $model->transfer_dst_member_id=user()->id;
        $model->transfer_src_member_id=user()->id;
        $this->performAjaxValidation($model);
        if(isset($_POST['Transfer']))
        {
        	/*echo "<pre>";
        	var_dump($_POST['Transfer']);
        	die;*/
            $model->attributes=$_POST['Transfer'];
           if($model->validate()){
            $this->log['target']=user()->name;
            $transaction=webapp()->db->beginTransaction();
            if($model->save(true,array('transfer_src_member_id','transfer_src_finance_type','transfer_dst_member_id','transfer_dst_finance_type','transfer_currency','transfer_remark','transfer_is_verify','transfer_add_date','transfer_verify_date','transfer_sn'))
                && $model->verify())
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
                        $data['success'] = user()->getFlash('success', '成功', true);
                    }
                    echo CJSON::encode($data);
                    webapp()->end();
                }
                $this->redirect(array('view','id'=>$model->transfer_id));
            }
            else
            {
                $transaction->rollback();
                $model->transfer_dst_member_id=isset($_POST['Transfer']['transfer_dst_member_id'])?Memberinfo::id2name($_POST['Transfer']['transfer_dst_member_id']):null;;
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
            'self'=>true,
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

		if(isset($_POST['Transfer']))
		{
			$model->attributes=$_POST['Transfer'];
			$this->log['target']=$model->transfer_id;
			if($model->save(true,array('transfer_src_member_id','transfer_src_finance_type','transfer_dst_member_id','transfer_dst_finance_type','transfer_currency','transfer_remark','transfer_is_verify','transfer_add_date','transfer_verify_date','transfer_sn')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->transfer_id));
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
		$dataProvider=new CActiveDataProvider('Transfer');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($isVerifyType=0)
	{
		$model=new Transfer('search');
		$model->unsetAttributes();  // clear any default values
		$model->transferSrcMember=new Memberinfo('search');
		$model->transferSrcFinanceType=new FinanceType('search');
		$model->transferDstMember=new Memberinfo('search');
		$model->transferDstFinanceType=new FinanceType('search');
		if(isset($_GET['Transfer']))
		{
			$model->attributes=$_GET['Transfer'];
			if(isset($_GET['Transfer']['transferSrcMember']))
				$model->transferSrcMember->attributes=$_GET['Transfer']['transferSrcMember'];
			if(isset($_GET['Transfer']['transferSrcFinanceType']))
							$model->transferSrcFinanceType->attributes=$_GET['Transfer']['transferSrcFinanceType'];
			if(isset($_GET['Transfer']['transferDstMember']))
							$model->transferDstMember->attributes=$_GET['Transfer']['transferDstMember'];
			if(isset($_GET['Transfer']['transferDstFinanceType']))
							$model->transferDstFinanceType->attributes=$_GET['Transfer']['transferDstFinanceType'];
			
		}
	
		//$model->transfer_is_verify=(int)$isVerifyType;
		if(!user()->isAdmin())
		{
			//$model->transfer_src_member_id=user()->id;
			//$model->transfer_dst_member_id=user()->id;
		}
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['transfer']=$model->search()->getArrayData();
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
		$this->redirect(['index'],true);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Transfer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if(!user()->isAdmin() && user()->id!=$model->transfer_src_member_id)
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='transfer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
