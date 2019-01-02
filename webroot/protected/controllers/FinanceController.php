<?php

class FinanceController extends Controller
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
		$model=new Finance('create');

		$this->performAjaxValidation($model);


		if(isset($_POST['Finance']))
		{
			$model->attributes=$_POST['Finance'];
			$this->log['target']=$model->finance_id;
			if($model->save(true,array('finance_award','finance_type','finance_memberinfo_id')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->finance_id));
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
		if(!user()->isAdmin()&&$id!=$model->finance_memberinfo_id)
			throw new CHttpException(404);
		$model->scenario='update';
		$this->performAjaxValidation($model);

		if(isset($_POST['Finance']))
		{
			$model->attributes=$_POST['Finance'];
			$this->log['target']=$model->finance_id;
			if($model->save(true,array('finance_award')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->finance_id));
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('Finance');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

    /**
     * Manages all models.
     */
    public function actionIndex($curSumType=1)
    {
        $model=new Finance('search');
        $model->unsetAttributes();  // clear any default values
        $model->financeType=new FinanceType('search');
        $model->financeMemberinfo=new Memberinfo('search');
        $model->finance_type=$curSumType;

        if(isset($_GET['Finance']))
        {
            $model->attributes=$_GET['Finance'];
            if(isset($_GET['Finance']['financeType']))
                $model->financeType->attributes=$_GET['Finance']['financeType'];
            if(isset($_GET['Finance']['financeMemberinfo']))
                $model->financeMemberinfo->attributes=$_GET['Finance']['financeMemberinfo'];
        }
        if(!user()->isAdmin())
            $model->finance_memberinfo_id=user()->id;
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $model->unsetAttributes();
            $model->finance_memberinfo_id=user()->id;
            $data['financeType']=FinanceType::model()->search()->getArrayData();
            foreach($data['financeType'] as $k=>$v)
            {
                $f = Finance::model()->findByAttributes(['finance_memberinfo_id' => user()->id, 'finance_type' => $v['finance_type_id']]);
                if ($f)
                {
                    $fa=$f->toArray();
                }
                else
                {
                    $fa=new Finance('create');
                    $fa->finance_memberinfo_id=user()->id;
                    $fa->finance_award=0;
                }
                $data['financeType'][$k]['finances']=$fa;
            }

            $data['finance']=$model->search()->getArrayData();
            foreach($data['finance'] as $k=>$v)
            {
                $data['finance'][$k]['financeMemberinfo']['memberinfoBank']=Bank::model()->find()->toArray();
            }
            echo CJSON::encode($data);
            webapp()->end();
        }
        $financeTypes=FinanceType::model()->findAll();
        $this->render('index',array(
            'model'=>$model,
            'financeTypes'=>$financeTypes,
            'curSumType'=>(int)$curSumType
        ));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Finance::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='finance-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
