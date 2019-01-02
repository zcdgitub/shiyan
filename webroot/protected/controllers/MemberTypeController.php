<?php

class MemberTypeController extends Controller
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
			//'authentic + update,create,delete',//需要二级密码
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
		$model=new MemberType('create');

		$this->performAjaxValidation($model);


		if(isset($_POST['MemberType']))
		{
			$model->attributes=$_POST['MemberType'];
			$this->log['target']=$model->membertype_name;
			if($model->save(true,array('membertype_name','membertype_desc','membertype_level','membertype_color','membertype_money','membertype_bill')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->membertype_level));
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

		if(isset($_POST['MemberType']))
		{
			$model->attributes=$_POST['MemberType'];
			$this->log['target']=$model->membertype_name;
			if($model->save(true,array('membertype_name','membertype_desc','membertype_level','membertype_color','membertype_money','membertype_bill','membertype_mod_date')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->membertype_level));
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
		$dataProvider=new CActiveDataProvider('MemberType');
// 		echo "<Pre>";
// var_dump($dataProvider);
// die;
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{

		$model=new MemberType('search');
	
		$model->unsetAttributes();  // clear any default values
	
		if(isset($_GET['MemberType']))
		{
			$model->attributes=$_GET['MemberType'];
			
		}
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
/*            if($isSale==1)
                $model->membertype_agent_money=0;*/
            $data['memberType']=$model->search()->getArrayData();
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
		$model=MemberType::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='member-type-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
