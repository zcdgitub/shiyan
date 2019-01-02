<?php

class MessagesController extends Controller
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
			/*'authentic + index,update,create,delete',*///需要二级密码
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
	 * @param $messagesType 消息类型，0站内信，1系统留言
	 */
	public function actionCreate($messagesType=0,$id=null)
	{

		$model=new Messages('create');
		if(isset($_POST['Messages']))
		{

			if(!user()->isAdmin())
			{
				$_POST['Messages']['messages_sender_member_id']=user()->id;
				$_POST['Messages']['messages_member_id']=user()->id;
			}
			else
			{
				$_POST['Messages']['messages_sender_member_id']=null;
				$_POST['Messages']['messages_member_id']=null;
			}
			if(!isset($_POST['Messages']['messages_session']))
			{
				$_POST['Messages']['messages_session']=new CDbExpression("nextval('messages_session')");
				$_POST['Messages']['messages_receiver_member_id']=isset($_POST['Messages']['messages_receiver_member_id'])?Memberinfo::name2id($_POST['Messages']['messages_receiver_member_id']):null;
			}
			$model->attributes=$_POST['Messages'];
		}
		if(!is_null($id))
		{
			$reply_model=$this->loadModel($id);
			$model->messages_receiver_member_id=$reply_model->messages_sender_member_id;
			$model->messages_session=$reply_model->messages_session;
		}
		else
		{
			$reply_model=null;
		}
		$this->performAjaxValidation($model);


		if(isset($_POST['Messages']))
		{
			$this->log['target']=$model->messages_title;
			$model->messages_content=$_POST['Messages']['messages_content'];
			$transaction=webapp()->db->beginTransaction();
			if($model->save(true,array('messages_title','messages_content','messages_add_date','messages_sender_member_id','messages_receiver_member_id','messages_session','messages_member_id')))
			{
				$sumProc=new DbCall('epmms_send_message',array((int)$model->messages_id));
				$sumProc->run();
				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				 if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['success']=user()->getFlash('success','修改成功',true);
                    echo CJSON::encode($data);
                    webapp()->end();
                }
				$this->redirect(array('view','id'=>$model->messages_id));
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
			'messagesType'=>$messagesType,
			'reply_model'=>$reply_model
		));
	}

	/**
	 * 系统留言
	 * @param int $messagesType
	 * @param null $id
	 */
	public function actionCreateSys($messagesType=1,$id=null)
	{
		$this->actionCreate($messagesType);
	}
	/**
	 * 群发邮件
	 * @param int $messagesType
	 * @param null $id
	 */
	public function actionCreateAll($messagesType=1,$id=null)
	{
		$this->actionCreate($messagesType);
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

		if(isset($_POST['Messages']))
		{
			$model->attributes=$_POST['Messages'];
			$this->log['target']=$model->messages_title;
			if($model->save(true,array('messages_title','messages_content','messages_add_date','messages_sender_member_id','messages_receiver_member_id','messages_session','messages_member_id')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->messages_id));
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
		$dataProvider=new CActiveDataProvider('Messages');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{

		$model=new Messages('search');
		$model->unsetAttributes();  // clear any default values
		$model->messagesSenderMember=new Memberinfo('search');
		$model->messagesReceiverMember=new Memberinfo('search');
		$model->messagesMember=new Memberinfo('search');


		if(isset($_GET['Messages']))
		{
			$model->attributes=$_GET['Messages'];
			if(isset($_GET['Messages']['messagesSenderMember']))
				$model->messagesSenderMember->attributes=$_GET['Messages']['messagesSenderMember'];
			if(isset($_GET['Messages']['messagesReceiverMember']))
				$model->messagesReceiverMember->attributes=$_GET['Messages']['messagesReceiverMember'];
			if(isset($_GET['Messages']['messagesMember']))
				$model->messagesMember->attributes=$_GET['Messages']['messagesMember'];
			
		}
		if(user()->isAdmin())
		{
			$model->messages_member_id=-1;
		}
		else
		{
			$model->messages_member_id=user()->id;
		}
		if($selTab==0)
		{
			
			
			$model->messages_receiver_member_id=$model->messages_member_id;
			$model->messages_sender_member_id=user()->isAdmin()?-2:null;
		}
		else
		{
			$model->messages_sender_member_id=$model->messages_member_id;
		}
		// echo "<pre>";
		// var_dump($model);
		// die;
		 		if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
		            $data['data']=$model->search()->getArrayData();
		            echo CJSON::encode($data);
		            webapp()->end();
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
		$model=Messages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		if(!user()->isAdmin() && user()->id!=$model->messages_member_id)
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
