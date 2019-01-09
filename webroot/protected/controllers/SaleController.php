<?php

class SaleController extends Controller
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
		$model=new Sale('create');

		if(isset($_POST['Sale']))
		{
            $old_Sale=$_POST['Sale'];
			if(user()->isAdmin())
			{
				$_POST['Sale']['sale_member_id']=Memberinfo::name2id(@$_POST['Sale']['sale_member_id']);
			}
			else
			{
				$_POST['Sale']['sale_member_id']=user()->id;
			}
		}
		$this->performAjaxValidation($model);

		if(isset($_POST['Sale']))
		{
			$model->attributes=$_POST['Sale'];
			$model->sale_money=$model->sale_currency;
			//$model->sale_type=Membermap::model()->findByPk($model->sale_member_id)->membermap_type;
			$model->sale_tax=0;

			$model->sale_remain_currency=$model->sale_currency;
			$model->sale_status=0;
			$this->log['target']=$model->sale_id;
				
			$t=webapp()->db->beginTransaction();
			try
            {
                if ($model->validate() && $model->save(true, array('sale_member_id', 'sale_currency', 'sale_money', 'sale_remain_currency', 'sale_status', 'sale_tax','sale_type')) && $model->verify())
                {
                    if(isset($_POST['Sale']['buy_id']))
                    {
                        $buy_id=$_POST['Sale']['buy_id'];
                        $model->refresh();
                        $ret=Deal::match($buy_id,$model->sale_id);
                        $this->log['status'] = LogFilter::SUCCESS;
                        //echo "t1;";

                        $this->log();
                        user()->setFlash('success', "{$this->actionName}" . t('epmms', "成功"));
                        if($ret['success']==true)
                        {
                            $t->commit();
                            $model->refresh();
                            header('Content-Type: application/json');
                            $data['success'] = '成功';
                            $data['sale'] = $model->toArray();
                            echo CJSON::encode($data);
                            webapp()->end();
                        }
                        else
                        {
                            //echo "t2;";
                            $t->rollback();
                            header('Content-Type: application/json');
                            $data['error'] = $ret['msg'];
                            $data['debug']=$ret['error'];
                            echo CJSON::encode($data);
                            webapp()->end();
                        }
                    }
                    else
                    {
                        //echo "t3;";
                        $t->rollback();
                        header('Content-Type: application/json');
                        $data['error'] = '没有提供对方订单ID';
                        echo CJSON::encode($data);
                        webapp()->end();
                    }
                    if (user()->isAdmin())
                        $this->redirect(array('sale/index'));
                    $this->redirect(array('deal/index'));
                } else
                {
                    //echo "t4;";
                    $t->rollback();
                    $this->log['status'] = LogFilter::FAILED;
                    $this->log();
                    user()->setFlash('error', "{$this->actionName}“{$model->showName}”" . t('epmms', "失败"));
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
            }catch(Exception $e)
            {
                //$t->rollback();
                if (webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['error'] = user()->getFlash('error', $e->getMessage(), true);
                    echo CJSON::encode($data);
                    webapp()->end();
                }
                else
                {
                    user()->setFlash('error',  $e->getMessage());
                    client_redirect('/deal/index',$e->getMessage());
                }
            }
		}
        if(isset($old_Sale))
        {
            $model->attributes = $old_Sale;
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

		if(isset($_POST['Sale']))
		{
			$model->attributes=$_POST['Sale'];
			$this->log['target']=$model->sale_id;
			if($model->save(true,array('sale_member_id','sale_currency','sale_date','sale_money','sale_remain_currency','sale_status','sale_verify_date')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->sale_id));
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
			if($model->sale_member_id!=user()->id)
			{
				throw new CHttpException(404,t('epmms','请求的页面不存在。'));
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

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('Sale');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new Sale('search');
		$model->unsetAttributes();  // clear any default values
		$model->saleMember=new Memberinfo('search');
		
		if(isset($_GET['Sale']))
		{
			$model->attributes=$_GET['Sale'];
			if(isset($_GET['Sale']['saleMember']))
				$model->saleMember->attributes=$_GET['Sale']['saleMember'];
		}
		if($selTab==0)
		{
			$model->sale_status="<=1";
		}
		elseif($selTab==1)
		{
			$model->sale_status=2;
		}

		if(!user()->isAdmin())
		{
			$model->sale_member_id=user()->id;
		}
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['sale']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}
	public function actionIndexMy($selTab=0)
	{
		$model=new Sale('search');
		$model->unsetAttributes();  // clear any default values
		$model->saleMember=new Memberinfo('search');

		if(isset($_GET['Sale']))
		{
			$model->attributes=$_GET['Sale'];
			if(isset($_GET['Sale']['saleMember']))
				$model->saleMember->attributes=$_GET['Sale']['saleMember'];

		}
		if($selTab==0)
		{
			$model->sale_status=0;
		}
		elseif($selTab==1)
		{
			$model->sale_status=">=1";
		}
		$model->sale_member_id=user()->id;
		$this->render('indexMy',array(
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
		$model=Sale::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='sale-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
