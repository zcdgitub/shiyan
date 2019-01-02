<?php

class GameChargeController extends Controller
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
	public function actionCreate($id=null)
	{
		if(is_null($id))
		{
			throw new Error(400,t('epmms','链接错误'));
		}
		$model=new GameCharge('create');
		$model->charge_recommend=$id;
		$this->performAjaxValidation($model);


		if(isset($_POST['GameCharge']))
		{
			$model->attributes=$_POST['GameCharge'];
			$this->log['target']=$model->charge_account;
			if($model->save(true,array('charge_recommend','charge_name','charge_phone','charge_age','charge_account','charge_money')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->charge_id));
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

		if(isset($_POST['GameCharge']))
		{
			$model->attributes=$_POST['GameCharge'];
			$this->log['target']=$model->charge_account;
			if($model->save(true,array('charge_recommend','charge_name','charge_phone','charge_age','charge_account','charge_money')))
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
		$dataProvider=new CActiveDataProvider('GameCharge');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new GameCharge('search');
		$model->unsetAttributes();  // clear any default values
		$model->chargeRecommend=new Memberinfo('search');
		$model->charge_is_verify=intval($selTab);
		if(isset($_GET['GameCharge']))
		{
			$model->attributes=$_GET['GameCharge'];
			if(isset($_GET['GameCharge']['chargeRecommend']))
				$model->chargeRecommend->attributes=$_GET['GameCharge']['chargeRecommend'];
			
		}

		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}
	public function actionVerify($id)
	{
        $model=$this->loadModel($id);
        $finance=Finance::getMemberFinance($model->charge_recommend,2);
		$this->log['target']=$model->showName;
		$trans=webapp()->db->beginTransaction();
        if($finance->deduct($model->charge_money))
        {
			Yii::import('ext.award.MySystemGameCharge');
			$mysystem=new MySystemGameCharge($model->chargeRecommend->membermap);
			$mysystem->run(4,1,1,$model->charge_money);
            if($this->send($model))
            {
                $model->charge_is_verify=1;
                $model->charge_verify_date=new CDbExpression('now()');
                $model->saveAttributes(['charge_is_verify','charge_verify_date']);
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				$trans->commit();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('index'),true);
            }
        }
		$this->log['status']=LogFilter::FAILED;
		$this->log();
		$trans->rollback();
		user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
		$this->redirect(array('index'),true);
	}
    public function send($model)
    {
		$ret=false;
		$md5str=md5($model->charge_account . $model->charge_id . params('game_charge_key'));
        $url="http://127.0.0.1:9006/gameCharge/charge.html?account={$model->charge_account}&order={$model->charge_id}&money={$model->charge_money}&md5str={$md5str}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        $result = curl_exec($ch);
        curl_close($ch);
        $log_params=['category'=>'operate','source'=>'游戏充值','operate'=>'游戏充值','user'=>'guest','role'=>'guest','target'=>$model->chargeRecommend->showName,'ip'=>webapp()->request->userHostAddress];
        if($result<0)
        {
            switch($result)
            {
                case -1:
                    $log_params['status']=LogFilter::FAILED;
                    $log_params['info']="账号不存在";
                    $ret=false;
                    break;
                default:
                    $log_params['status']=LogFilter::FAILED;
                    $log_params['info']="未知错误";
                    $ret=false;
                    break;
            }
        }
        elseif($result==1)
        {
            $log_params['status']=LogFilter::SUCCESS;
            $log_params['info']=$model->charge_name . '充值' . $model->charge_money ;
            $ret=true;
        }
		else
		{
			$log_params['status']=LogFilter::FAILED;
			$log_params['info']="未知错误";
			$ret=false;
		}
        LogFilter::log($log_params);
        return $ret;
    }
	public function actionGenUrl()
	{
		$create_url=webapp()->getBaseUrl(true) . $this->createUrl('gameCharge/create',['id'=>user()->id]);
		$this->render('genUrl',['create_url'=>$create_url]);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=GameCharge::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		if(!user()->isAdmin() && $model->charge_recommend!=user()->id)
			throw new CHttpException(503,t('epmms','没有权限。'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='game-charge-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
