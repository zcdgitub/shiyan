<?php

/**
 * Class AwardTotalSumController
 */
class AwardTotalSumController extends Controller
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
			'closeSite',
			'rights', // rights rbac filter
			'postOnly + delete', // 只能通过POST请求删除
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
	public function actionCreate()
	{
		$model=new AwardTotalSum('create');

		$this->performAjaxValidation($model);


		if(isset($_POST['awardTotalSum']))
		{
			$model->attributes=$_POST['awardTotalSum'];
			$this->log['target']=$model->award_total_sum_id;
			if($model->save(true,array('award_total_sum_memberinfo_id','award_total_sum_currency','award_total_sum_add_date','award_total_sum_type')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->award_total_sum_id));
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

		if(isset($_POST['awardTotalSum']))
		{
			$model->attributes=$_POST['awardTotalSum'];
			$this->log['target']=$model->award_total_sum_id;
			if($model->save(true,array('award_total_sum_memberinfo_id','award_total_sum_currency','award_total_sum_add_date','award_total_sum_type')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->award_total_sum_id));
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
		$dataProvider=new CActiveDataProvider('awardTotalSum');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($curSumType=1)
	{
		if(webapp()->id=='141203' && !user()->isAdmin() && user()->id!=1)
		{
			if(user()->map->membermap_membertype_level==4 && $curSumType==1)
				$curSumType=5;
			if(user()->map->membermap_membertype_level!=4 && $curSumType==5)
				$curSumType=1;
		}
		$model=new AwardTotalSum('search');
		$model->unsetAttributes();  // clear any default values
		$model->awardTotalSumMemberinfo=new Memberinfo('search');
		$model->awardTotalSumType=new SumType('search');
		$model->awardTotalSumType->sum_type_id=$curSumType;

		if(isset($_GET['AwardTotalSum']))
		{
			$model->attributes=$_GET['AwardTotalSum'];
			if(isset($_GET['AwardTotalSum']['awardTotalSumMemberinfo']))
			{
				$model->awardTotalSumMemberinfo->attributes=$_GET['AwardTotalSum']['awardTotalSumMemberinfo'];
			}

		}
		if(!user()->isAdmin())
			$model->award_total_sum_memberinfo_id=user()->id;
		//获取奖金名称列表及生成grid列
		$awardType=AwardType::model()->getTypes($curSumType);
		$gridColumn=[];
		foreach($awardType as $award)
		{
			//构造grid列
			$gridColumn[]=['name'=>'_award_' . $award->award_type_id,'header'=>t('epmms',$award->award_type_name),'filter'=>false,'type'=>'money'];
		}
		$sumTypes=SumType::model()->findAll(['with'=>['sumConfigs'],'condition'=>"sum_config_date='total'",'order'=>'t.sum_type_id asc']);
		$this->render('index',array(
			'model'=>$model,
			'gridColumn'=>$gridColumn,
			'sumTypes'=>$sumTypes,
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
		$model=awardTotalSum::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='award-total-sum-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    // public function actionAwardSum(){//龙腾日返利和推荐奖
    	
    // 	// $prize=AwardTotalSum::model()->find(['condition'=>'award_total_sum_memberinfo_id=:id and award_total_sum_type=1','params'=>[':id'=>user()->id]])->toArray();
    // 	$prize=Yii::app()->db->createCommand()
				// ->select('*')
				// ->from('epmms_award_period')
				// // ->where(['and','award_period_memberinfo_id=:id','award_total_sum_type=1'],['or','award_period_type_id=:id1','award_period_type_id=:id2'],[':id'=>user()->id,':id1'=>614,':id2'=>615])
				// ->where(['and','award_period_memberinfo_id=:id','award_total_sum_type=:id1'],['in','award_period_type_id',[614,615]],[':id'=>user()->id,':id1'=>1])
				// //->where('award_period_memberinfo_id='.user()->id." and  award_period_type_id in (614,615) and award_total_sum_type=1")
				// ->queryAll();
				// var_dump($prize);
				// die;
    	
    // 	if($prize){
    // 		if(webapp()->request->isAjaxRequest){
	   //        header("content-type:application/json");
	   //        $data['success']=true;
	   //        $data['prize']=$prize;
	   //        return;
    // 		}
    
    // 	}
    	
    // }
}
