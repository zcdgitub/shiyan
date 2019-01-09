<?php

class BuyController extends Controller
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
			'rights', // rights rbac filter
			'closeSite',
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
		if(isset($_POST['Buy']))
		{
            $old_Sale=$_POST['Buy'];
			if(user()->isAdmin())
			{
				$_POST['Buy']['buy_member_id']=Memberinfo::name2id(@$_POST['Buy']['buy_member_id']);
			}
			else
			{
				$_POST['Buy']['buy_member_id']=user()->id;
			}
		}
		$model=new Buy('create');
		$this->performAjaxValidation($model);

		if(isset($_POST['Buy']))
        {
            $model->attributes = $_POST['Buy'];
            $model->buy_date = new CDbExpression("now()");
            $model->buy_money = $model->buy_currency;
            $model->buy_real_currency = $model->buy_currency;
            $model->buy_remain_currency=$model->buy_currency;
            $model->buy_status = 0;
            $this->log['target'] = $model->buy_id;
            $transaction = webapp()->db->beginTransaction();
            try
            {
                if ($model->validate() && $model->save(true, array('buy_member_id', 'buy_currency', 'buy_date', 'buy_money', 'buy_status', 'buy_tax', 'buy_real_currency', 'buy_type','buy_remain_currency')) && $model->verify())
                {
                    $this->log['status'] = LogFilter::SUCCESS;
                    $this->log();
                    $transaction->commit();
                    user()->setFlash('success', "{$this->actionName}" . t('epmms', "成功"));
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        if (user()->hasFlash('success'))
                        {
                            $data['success'] = user()->getFlash('success', '成功', true);
                            $data['buy'] = $model->toArray();
                        }
                        echo CJSON::encode($data);
                        webapp()->end();
                    }
                    if (user()->isAdmin())
                        $this->redirect(array('buy/index'));
                    $this->redirect(array('deal/index'));
                } else
                {
                    $transaction->rollback();
                    $this->log['status'] = LogFilter::FAILED;
                    $this->log();
                    user()->setFlash('error', "{$this->actionName}" . t('epmms', "失败"));
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        if ($model->getErrors())
                            $data = $model->getErrors();
                        elseif (user()->hasFlash('error'))
                        {
                            $data['error'] = user()->getFlash('error', '失败', true);
                        }
                        echo CJSON::encode($data);
                        webapp()->end();
                    }
                }
            } catch (Exception $e)
            {
                if (webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['error'] = user()->getFlash('error', $e->getMessage(), true);
                    echo CJSON::encode($data);
                    webapp()->end();
                } else
                {
                    user()->setFlash('error', $e->getMessage());
                }
            }
        }
        if(isset($old_Sale))
        {
            //$model->attributes = $old_Sale;
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

		if(isset($_POST['Buy']))
		{
			$model->attributes=$_POST['Buy'];
			$this->log['target']=$model->buy_id;
			if($model->save(true,array('buy_date')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->buy_id));
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
	    if(!user()->isAdmin())
        {
            if($model->buy_member_id!=user()->id)
            {
                throw new HttpException('页面不存在',404);
            }
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
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(user()->hasFlash('success'))
                $data['success']=true;
            if(user()->hasFlash('error'))
                $data['success']=false;
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
		$dataProvider=new CActiveDataProvider('Buy');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0,$all=0)
	{
		$model=new Buy('search');
		$model->unsetAttributes();  // clear any default values
		$model->buyMember=new Memberinfo('search');
		
		if(isset($_GET['Buy']))
		{
			$model->attributes=$_GET['Buy'];
			if(isset($_GET['Buy']['buyMember']))
				$model->buyMember->attributes=$_GET['Buy']['buyMember'];
		}
		if($all==0 && !user()->isAdmin())
		{
			$model->buy_member_id=user()->id;
		}
        if($selTab==0)
        {
            $model->buy_status="<=1";
        }
        elseif($selTab==1)
        {
            $model->buy_status=2;
        }
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['buy']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
		$model->buy_status=$selTab;
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
		$model=Buy::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='buy-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
