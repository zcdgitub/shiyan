<?php

class AgentController extends Controller
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
			//'authentic + update,create,delete,verify,register',//需要二级密码
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
		if(!user()->isAdmin())
			$model=Agent::model()->find('agent_memberinfo_id=:id',[':id'=>user()->id]);
		else
			$model=null;
        if (webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(empty($model))
            {
                echo CJSON::encode(['success'=>false,'msg'=>'您还不是代理中心']);
                webapp()->end();
            }

            $data=$model->toArray();
            echo CJSON::encode($data);
            webapp()->end();
        }
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

		$model=new Agent('create');
		if(isset($_POST['Agent']))
		{
			$_POST['Agent']['agent_memberinfo_id']=Memberinfo::name2id($_POST['Agent']['agent_memberinfo_id']);
		}
		$this->performAjaxValidation($model);

		if(isset($_POST['Agent']))
		{

			$model->attributes=$_POST['Agent'];
			$this->log['target']=$model->agentTitle;
			if($model->agent_type==1)
			{
				$model->agent_area=null;
				$model->agent_county=null;
			}
			else if($model->agent_type==2)
			{
				$model->agent_county=null;
			}

			if($model->save(true,array('agent_memberinfo_id','agent_memo','agent_is_verify','agent_is_agent','agent_add_date','agent_verify_date','agent_type','agent_province','agent_area','agent_county','agent_account')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				$this->redirect(array('index'));
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

    /**
     *
     */
    public function actionRegister()
	{
		$model=new Agent('create');
		$model->agent_memberinfo_id=user()->id;

		$this->performAjaxValidation($model);
		
		
		if(isset($_POST['Agent']))
		{

			$model->attributes=$_POST['Agent'];
			$model->agent_memberinfo_id=user()->id;
			if($model->validate()){
				$this->log['target']=user()->name;
				if($model->agent_type==1)
				{
					$model->agent_area=null;
					$model->agent_county=null;
				}
				else if($model->agent_type==2)
				{
					$model->agent_county=null;
				}

				if($model->save(false,array('agent_memberinfo_id','agent_memo','agent_is_verify','agent_add_date','agent_verify_date','agent_type','agent_province','agent_area','agent_county','agent_account')))
				{
					$this->log['status']=LogFilter::SUCCESS;
					$this->log();
	                if (webapp()->request->isAjaxRequest)
	                {
	                    header('Content-Type: application/json');
	                    $data['success'] = true;
	                    $data['agent'] = $model->toArray();
	                    echo CJSON::encode($data);
	                    webapp()->end();
	                }
					$this->redirect(array('register'));
				}
				else
				{
					$this->log['status']=LogFilter::FAILED;
					$this->log();
	                if (webapp()->request->isAjaxRequest)
	                {
	                    header('Content-Type: application/json');
	                    if ($model->getErrors())
	                        $data = $model->getErrors();
	                    $data['success']=false;
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

		$this->render('register',array(
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

		if(isset($_POST['Agent']))
		{
			$model->attributes=$_POST['Agent'];
			$this->log['target']=$model->agentMemberinfo->memberinfo_account;
			if($model->agent_type==1)
			{
				$model->agent_area=null;
				$model->agent_county=null;
			}
			else if($model->agent_type==2)
			{
				$model->agent_county=null;
			}

			if($model->save(true,array('agent_currency','agent_memo','agent_is_verify','agent_verify_date','agent_type','agent_province','agent_area','agent_county','agent_account')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				$this->redirect(array('index'));
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
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

		$this->log['target']=$this->loadModel($id)->agentTitle;
		$model=$this->loadModel($id);
		$memberinfo=Memberinfo::model()->find('memberinfo_id=:id',[':id'=> $model->agent_memberinfo_id]);
		$membermap=Membermap::model()->find('membermap_id=:id' ,[':id'=>$model->agent_memberinfo_id]);
		$memberinfo->memberinfo_is_agent=0;
		$membermap->membermap_is_agent=0;
		$membermap->membermap_agent_type=null;
		if($model->agent_is_verify==1)
		{
			$memberinfo->saveAttributes(['memberinfo_is_agent']) ;
			$membermap->saveAttributes(['membermap_is_agent','membermap_agent_type']);
		}
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
		 if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(user()->hasFlash('success'))
                $data['success']=true;
            if(user()->hasFlash('error'))
                $data['error']=user()->getFlash('success','',true);
            echo CJSON::encode($data);
            return;
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
		$dataProvider=new CActiveDataProvider('Agent');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
	
		$model=new Agent('search');
		/*$data=Agent::model()->findAll();
		echo "<pre>";
		var_dump($data);
	*/
		$model->agentMemberinfo=new Memberinfo('search');
		$model->agentMembermap=new Membermap('search');
		$model->agenttype=new AgentType('search');


		$model->unsetAttributes();  // clear any default values
		if($selTab==0)
		{
			$model->agent_is_verify=0;
		}
		else
		{
			$model->agent_is_verify=1;
		}
		if(isset($_GET['Agent']))
		{
			$model->attributes=$_GET['Agent'];
			if(isset($_GET['Agent']['agentMemberinfo']))
			{
				$model->agentMemberinfo->attributes=$_GET['Agent']['agentMemberinfo'];
			}
		}
		   if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $model->agent_is_verify="";
            $model->agentMembermap->membermap_agent_id=user()->id;
            $data['agent']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
      
		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}
	/**
	 * 审核代理中心
	 * @param unknown_type $id
	 */
	public function actionVerify($id)
	{
		$model=$this->loadModel($id);
		$this->log['target']=$model->showName;
        $t=webapp()->db->beginTransaction();
		if(($status=$model->verifyAgent())===EError::SUCCESS)
		{
		    $t->commit();
			$this->log['status']=LogFilter::SUCCESS;
			user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                if (webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if (user()->hasFlash('success'))
                    {
                        $data['success'] = true;
                        $data['msg'] = '成功';
                    }

                    echo CJSON::encode($data);
                    return;
                }
		}
		elseif($status===EError::DUPLICATE)
		{
		    $t->rollback();
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,请不要重复审核"));
                if (webapp()->request->isAjaxRequest){
                    header('Content-Type: application/json');
                    if (user()->hasFlash('error')){
                        $data['success'] = false;
                        $data['msg'] = '请不要重复审核';
                    }

                    echo CJSON::encode($data);
                    return;
                }
		}
		else
		{
		    $t->rollback();
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			 if (webapp()->request->isAjaxRequest){
                    header('Content-Type: application/json');
                    if (user()->hasFlash('error'))
                    {
                        $data['success'] = false;
                        $data['msg'] = '失败';
                    }
                    echo CJSON::encode($data);
                    return;
                }
		}
		$this->log();
		$this->actionIndex();
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{

		$model=Agent::model()->findByPk($id,array('with'=>array('agentMemberinfo','agentMemberinfo.membermap')));

		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		// if(!user()->isAdmin() && user()->id!=$model->agent_memberinfo_id)
		// {
			
		// 	throw new CHttpException(403,t('epmms','没有权限。'));
		// }
	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='agent-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
