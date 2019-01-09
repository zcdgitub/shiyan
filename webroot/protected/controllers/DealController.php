<?php

class DealController extends Controller
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
			//'authentic + verify',//需要二级密码
		);
	}
	public function allowedActions()
	{
		return '';
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
		$model=new Deal('create');

		$this->performAjaxValidation($model);


		if(isset($_POST['Deal']))
		{
			$model->attributes=$_POST['Deal'];
			$this->log['target']=$model->deal_id;
			if($model->save(true,array('deal_sale_id','deal_buy_id','deal_currency','deal_date')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->deal_id));
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

		if(isset($_POST['Deal']))
		{
			$model->attributes=$_POST['Deal'];
			$this->log['target']=$model->deal_id;
			if($model->save(true,array('deal_sale_id','deal_buy_id','deal_currency','deal_date')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->deal_id));
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
        try
        {
            $transaction = webapp()->db->beginTransaction();
            $model = $this->loadModel($id);
            $this->log['target'] = $model->showName;
            if ($model->delete())
            {
                $transaction->commit();
                $this->log['status'] = LogFilter::SUCCESS;
                $this->log();
                user()->setFlash('success', "{$this->actionName}“{$model->showName}”" . t('epmms', "成功"));
            }
        }
        catch(CException $e)
        {
            $transaction->rollback();
            $this->log['status'] = LogFilter::FAILED;
            $this->log();
            user()->setFlash('error', $e->getMessage());
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
		$dataProvider=new CActiveDataProvider('Deal');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{

		$model=new Deal('search');
		$model->unsetAttributes();  // clear any default values
		$model->dealSale=new Sale('search');
		$model->dealBuy=new Buy('search');
		
		if(isset($_GET['Deal']))
		{
			$model->attributes=$_GET['Deal'];
			if(isset($_GET['Deal']['dealSale']))
            {
                $_GET['Deal']['dealSale']['sale_member_id']=Memberinfo::name2id($_GET['Deal']['dealSale']['saleMember']['memberinfo_account']);
                $model->dealSale->attributes = $_GET['Deal']['dealSale'];
            }
			if(isset($_GET['Deal']['dealBuy']))
            {
                $_GET['Deal']['dealBuy']['buy_member_id']=Memberinfo::name2id(@$_GET['Deal']['dealBuy']['buyMember']['memberinfo_account']);
                $model->dealBuy->attributes = $_GET['Deal']['dealBuy'];
            }
		}
		  $model->dealSale->sale_member_id=user()->id;
            $model->dealBuy->buy_member_id=user()->id;
            $model->deal_status="<2";
            $data['deal']=$model->search()->getArrayData();
            echo "<pre>";
            var_dump($data['deal']);
            die;
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $model->dealSale->sale_member_id=user()->id;
            $model->dealBuy->buy_member_id=user()->id;
            $model->deal_status="<2";
            $data['deal']=$model->search()->getArrayData();
            foreach($data['deal']['data'] as $key=>$deal)
            {
                $saleMember=$deal['dealSale']['saleMember'];
                $saleBank=Mybank::model()->find(['condition'=>'mybank_memberinfo_id=:id','order'=>'mybank_is_default desc,mybank_id asc','params'=>[':id'=>$saleMember['memberinfo_id']]]);
                if($saleBank)
                {
                    $data['deal']['data'][$key]['dealSale']['saleMember']['memberinfoMybank']=$saleBank->toArray(['mybankBank']);
                }

                $buyMember=$deal['dealBuy']['buyMember'];
                $buyBank=Mybank::model()->find(['condition'=>'mybank_memberinfo_id=:id and mybank_is_default=true','order'=>'mybank_is_default desc,mybank_id asc','params'=>[':id'=>$buyMember['memberinfo_id']]]);
                if($buyBank)
                {
                    $data['deal']['data'][$key]['dealBuy']['buyMember']['memberinfoMybank']=$buyBank->toArray(['mybankBank']);
                }
            }
            echo CJSON::encode($data);
            webapp()->end();
        }
		$sale=new Sale('search');
        $sale->unsetAttributes();
		$buy=new buy('search');
		$buy->unsetAttributes();
		$buy->buy_status="<2";
		$buy->buy_member_id=user()->id;
		$buys=$buy->search();
		$sale->unsetAttributes();
		$sale->sale_status="<2";
		$sale->sale_member_id=user()->id;
		$sale_current=$sale->search();
        $model->unsetAttributes();
		$model->dealSale->unsetAttributes();
		$model->dealBuy->unsetAttributes();
        $model->deal_status="<2";
		$model->dealSale->sale_member_id=user()->id;
		$deal_sales=$model->search();
		$model->unsetAttributes();
        $model->dealSale->unsetAttributes();
        $model->dealBuy->unsetAttributes();
		$model->dealBuy->buy_member_id=user()->id;
		$model->deal_status="<2";
		$deal_buys=$model->search();

		//$benxi_finance=Finance::getMemberFinance(user()->id,3);
		//$benxi_currency=$benxi_finance->finance_award;
		//$guanli_finance=Finance::getMemberFinance(user()->id,1);
		//$guanli_currency=$guanli_finance->finance_award;
        $model->unsetAttributes();
        $model->dealSale->unsetAttributes();
        $model->dealBuy->unsetAttributes();
        if($selTab==0)
        {
            $model->deal_status="<2";
        }
        else
        {
            $model->deal_status=2;
        }
        $model->deal_status=$selTab;
		$this->render('index',array(
			'model'=>$model,
			'buys'=>$buys,
			'sale_current'=>$sale_current,
			//'benxi_currency'=>$benxi_currency,
            'deal_sales'=>$deal_sales,
            'deal_buys'=>$deal_buys,
			//'guanli_currency'=>$guanli_currency,
			'selTab'=>(int)$selTab
		));
	}
	/**
	 * FineUploader action
	 */
	public function actionUpload()
	{
        $this->performAjaxValidation(new Sale('create'));
        $folder=params('trade_upload');
        $image = CUploadedFile::getInstanceByName("Deal[deal_image]");
        $model=$this->loadModel($_POST['Deal']['deal_id']);
        if($model->deal_status==2)
        {
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['deal'] = $model->toArray();
                $data['success']=false;
                $data['msg']='确认后不能再上传图片';
                echo CJSON::encode($data);
                webapp()->end();
            }
            throw new EError("确认后不能再上传图片");
        }
        $filename = md5(uniqid());
        $model->deal_image=$folder . $filename . '.' . $image->extensionName;
        $model->deal_image_date=new CDbExpression('now()');
        if($model->deal_status==0)
            $model->deal_status=1;
        if($model->save())
        {
            $image->saveAs('.' . $folder . $filename . '.' . $image->extensionName);
            $model->sendUpload();
            $this->log['target']=$model->showName;
            $this->log['status']=LogFilter::SUCCESS;
            $this->log();
            user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['deal'] = $model->toArray();
                $data['success']=true;
                echo CJSON::encode($data);
                webapp()->end();
            }
            $this->redirect(array('deal/index'));
        }
        else
        {
            $this->log['target']=$model->showName;
            $this->log['status']=LogFilter::FAILED;
            $this->log();
            user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['deal'] = $model->toArray();
                $data['success']=false;
                echo CJSON::encode($data);
                webapp()->end();
            }
            throw new EError("上传图片非法");
        }

	}
	public function actionVerify($id)
	{
	    try
        {
            $t = webapp()->db->beginTransaction();
            webapp()->db->createCommand("SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;")->execute();
            $model = $this->loadModel($id);
            $ret = $model->verify();
        }
        catch(CDbException $e)
        {
            $t->rollback();
            if (webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['success'] = false;
                $data['msg']=$e->getMessage();

                echo CJSON::encode($data);
                webapp()->end();
            }
            throw new EError("确认出错(可能重复确认)");
            return;
        }
        catch(Exception $e)
        {
            $t->rollback();
            if (webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['success'] = false;
                $data['msg']=$e->getMessage();

                echo CJSON::encode($data);
                webapp()->end();
            }
            throw new EError("确认出错");
            return;
        }
        $t->commit();
		if($ret===EError::DUPLICATE)
		{
			$this->log['target']=$model->showName;
            $this->log['info']='不能重复确认';
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
            if (webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['success'] = false;
                $data['msg']='不能重复确认';

                echo CJSON::encode($data);
                webapp()->end();
            }
			throw new EError("不能重复确认");
		}
		else
		{
			$this->log['target']=$model->showName;
            $this->log['info']='确认成功';
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
            if (webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                if (user()->hasFlash('success'))
                {
                    $data['success'] = user()->getFlash('success', '成功', true);
                    $data['deal'] = $model->toArray();
                }
                echo CJSON::encode($data);
                webapp()->end();
            }
		}
		$this->redirect(array('deal/index'));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Deal::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='deal-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
