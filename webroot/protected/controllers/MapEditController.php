<?php

class MapEditController extends Controller
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
			//'postOnly + delete', // 只能通过POST请求删除
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
	public function actionDeleteMap()
	{
		$model=new MapEdit('createDeleteMap');
		$model->map_edit_operate=1;
		if(isset($_POST['MapEdit']))
			$_POST['MapEdit']['map_edit_src_member_id']=isset($_POST['MapEdit']['map_edit_src_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_src_member_id']):null;

		$this->performAjaxValidation($model);

		if(isset($_POST['MapEdit']))
		{
			$model->attributes=$_POST['MapEdit'];
			$this->log['target']=$model->map_edit_id;
			if($model->save(true,array('map_edit_src_member_id','map_edit_add_date','map_edit_operate','map_edit_info')))
			{
				$ss=SystemStatus::model()->find();
				$ss->system_status_mapedit=2;
				$ss->saveAttributes(['system_status_mapedit']);
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->map_edit_id));
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
			'operate'=>$model->map_edit_operate
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAddMap()
	{
		if(MemberinfoItem::model()->itemVisible('membermap_parent_id'))
			$model=new MapEdit('createAddMap');
		else
			$model=new MapEdit('createAddMap2');
		$model->map_edit_operate=2;
		if(isset($_POST['MapEdit']))
		{
			$_POST['MapEdit']['map_edit_src_member_id']=isset($_POST['MapEdit']['map_edit_src_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_src_member_id']):null;
			$_POST['MapEdit']['map_edit_dst_member_id']=isset($_POST['MapEdit']['map_edit_dst_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_dst_member_id']):null;
			$_POST['MapEdit']['map_edit_dst_recommend_id']=isset($_POST['MapEdit']['map_edit_dst_recommend_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_dst_recommend_id']):null;
		}

		$this->performAjaxValidation($model);

		if(isset($_POST['MapEdit']))
		{
			$model->attributes=$_POST['MapEdit'];
			$this->log['target']=$model->map_edit_id;
			if($model->save(true,array('map_edit_src_member_id','map_edit_dst_member_id','map_edit_add_date','map_edit_operate','map_edit_info','map_edit_dst_order','map_edit_dst_recommend_id')))
			{
				$ss=SystemStatus::model()->find();
				$ss->system_status_mapedit=2;
				$ss->saveAttributes(['system_status_mapedit']);
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->map_edit_id));
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
			'operate'=>$model->map_edit_operate
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionMoveMap()
	{
		$model=new MapEdit('createMoveMap');
		$model->map_edit_operate=3;
		if(isset($_POST['MapEdit']))
		{
			$_POST['MapEdit']['map_edit_src_member_id']=isset($_POST['MapEdit']['map_edit_src_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_src_member_id']):null;
			$_POST['MapEdit']['map_edit_dst_member_id']=isset($_POST['MapEdit']['map_edit_dst_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_dst_member_id']):null;
		}

		$this->performAjaxValidation($model);

		if(isset($_POST['MapEdit']))
		{
			$model->attributes=$_POST['MapEdit'];
			$this->log['target']=$model->map_edit_id;
			if($model->save(true,array('map_edit_src_member_id','map_edit_dst_member_id','map_edit_type','map_edit_add_date','map_edit_operate','map_edit_info','map_edit_dst_order')))
			{
				$ss=SystemStatus::model()->find();
				$ss->system_status_mapedit=2;
				$ss->saveAttributes(['system_status_mapedit']);
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->map_edit_id));
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
			'operate'=>$model->map_edit_operate
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionSwapMap()
	{
		$model=new MapEdit('createSwapMap');
		$model->map_edit_operate=4;
		if(isset($_POST['MapEdit']))
		{
			$_POST['MapEdit']['map_edit_src_member_id']=isset($_POST['MapEdit']['map_edit_src_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_src_member_id']):null;
			$_POST['MapEdit']['map_edit_dst_member_id']=isset($_POST['MapEdit']['map_edit_dst_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_dst_member_id']):null;
		}

		$this->performAjaxValidation($model);

		if(isset($_POST['MapEdit']))
		{
			$model->attributes=$_POST['MapEdit'];
			$this->log['target']=$model->map_edit_id;
			if($model->save(true,array('map_edit_src_member_id','map_edit_dst_member_id','map_edit_type','map_edit_add_date','map_edit_operate','map_edit_info')))
			{
				$ss=SystemStatus::model()->find();
				$ss->system_status_mapedit=2;
				$ss->saveAttributes(['system_status_mapedit']);
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->map_edit_id));
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
			'operate'=>$model->map_edit_operate
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
		switch($model->map_edit_operate)
		{
			case 1:
				$model->scenario='createDeleteMap';
				break;
			case 2:
				$model->scenario='createAddMap';
				break;
			case 3:
				$model->scenario='createMoveMap';
				break;
			case 4:
				$model->scenario='createSwapMap';
				break;
		}
		if(isset($_POST['MapEdit']))
		{
			$_POST['MapEdit']['map_edit_src_member_id']=isset($_POST['MapEdit']['map_edit_src_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_src_member_id']):null;
			$_POST['MapEdit']['map_edit_dst_member_id']=isset($_POST['MapEdit']['map_edit_dst_member_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_dst_member_id']):null;
			$_POST['MapEdit']['map_edit_dst_recommend_id']=isset($_POST['MapEdit']['map_edit_dst_recommend_id'])?Memberinfo::name2id($_POST['MapEdit']['map_edit_dst_recommend_id']):null;
		}
		$this->performAjaxValidation($model);

		if(isset($_POST['MapEdit']))
		{
			$model->attributes=$_POST['MapEdit'];
			$this->log['target']=$model->map_edit_id;
			if($model->save(true,array('map_edit_src_member_id','map_edit_dst_member_id','map_edit_dst_order','map_edit_type','map_edit_info','map_edit_dst_recommend_id')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->map_edit_id));
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
		$dataProvider=new CActiveDataProvider('MapEdit');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new MapEdit('search');
		$model->unsetAttributes();  // clear any default values
		$model->mapEditSrcMember=new Membermap('search');
		$model->mapEditDstMember=new Membermap('search');
		$model->map_edit_is_verify=$selTab;
		if(isset($_GET['MapEdit']))
		{
			$model->attributes=$_GET['MapEdit'];
			if(isset($_GET['MapEdit']['mapEditSrcMember']))
				$model->mapEditSrcMember->attributes=$_GET['MapEdit']['mapEditSrcMember'];
			if(isset($_GET['MapEdit']['mapEditDstMember']))
				$model->mapEditDstMember->attributes=$_GET['MapEdit']['mapEditDstMember'];
			
		}
		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}

	public function actionVerify()
	{
		if($ret=MapEdit::model()->verifyAll()==EError::SUCCESS)
		{
			$ss=SystemStatus::model()->find();
			$ss->system_status_mapedit=1;
			$ss->saveAttributes(['system_status_mapedit']);
			$this->log['target']='图谱编辑';
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('success',"审核图谱编辑" . t('epmms',"成功"));
		}
		else
		{
			$this->log['target']='图谱编辑';
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"审核图谱编辑" . t('epmms',"失败")) . $ret->message;
		}
		$this->redirect(['index'],true);
	}
	public function actionRemap()
	{
		if($ret=MapEdit::model()->remapAll()==EError::SUCCESS)
		{
			$ss=SystemStatus::model()->find();
			$ss->system_status_mapedit=0;
			$ss->saveAttributes(['system_status_mapedit']);
			$this->log['target']='图谱编辑';
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('success',"重新计算业绩" . t('epmms',"成功"));
		}
		else
		{
			$this->log['target']='图谱编辑';
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"审核图谱编辑" . t('epmms',"失败")) . $ret->message;
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
		$model=MapEdit::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='map-edit-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
