<?php

class AwardPeriodSumController extends Controller
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
			//'authentic + index,update,create,delete',//需要二级密码
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($curSumType=1)
	{
		
		if(webapp()->id=='141203' && user()->id!=1)
		{
			if(user()->map->membermap_membertype_level==4 && $curSumType==1)
				$curSumType=5;
			if(user()->map->membermap_membertype_level!=4 && $curSumType==5)
				$curSumType=1;
		}
		$model=new AwardPeriodSum('search');
		$model->unsetAttributes();  // clear any default values
		$model->awardPeriodSumMemberinfo=new Memberinfo('search');
		$model->awardPeriodSumSrcMemberinfo=new Memberinfo('search');
		$model->awardPeriodSumType=new SumType('search');

		$model->awardPeriodSumType->sum_type_id=$curSumType;


		if(isset($_GET['awardPeriodSum']))
		{
			$model->attributes=$_GET['awardPeriodSum'];
			if(isset($_GET['awardPeriodSum']['awardPeriodSumMemberinfo']))
				$model->awardPeriodSumMemberinfo->attributes=$_GET['awardPeriodSum']['awardPeriodSumMemberinfo'];
			if(isset($_GET['awardPeriodSum']['awardPeriodSumSrcMemberinfo']))
				$model->awardPeriodSumSrcMemberinfo->attributes=$_GET['awardPeriodSum']['awardPeriodSumSrcMemberinfo'];
			if(isset($_GET['awardPeriodSum']['awardPeriodSumType']))
				$model->awardPeriodSumType->attributes=$_GET['awardPeriodSum']['awardPeriodSumType'];
			
		}
		//获取奖金名称列表及生成grid列
		$awardType=AwardType::model()->getTypes($curSumType);
		// echo "<Pre>";
		// var_dump($awardType);
		// die;
		if(!user()->isAdmin())
			$model->award_period_sum_memberinfo_id=user()->id;
		$gridColumn=[];
		foreach($awardType as $award)
		{
			if($award->award_type_id==309)
			{
				$gridColumn[]=['name'=>'plusSum','headerHtmlOptions'=>['style'=>'width:80px;'],'type'=>'money'];
			}
			//构造grid列
			$gridColumn[]=['name'=>'_award_' . $award->award_type_id,'header'=>t('epmms',$award->award_type_name),'filter'=>false,'type'=>'money'];
		}
		$sumTypes=SumType::model()->findAll(['with'=>['sumConfigs'],'condition'=>"sum_config_date='period'",'order'=>'t.sum_type_id asc']);
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data=[];
            foreach($sumTypes as $key=>$sum)
            {
                $data['sumtype'][$key]=$sum->toArray();
                //$model->awardPeriodSumType->sum_type_id=$sum->sum_type_id;
                //$data['sumtype'][$key]['awardPeriodSum']=$model->search()->getArrayData();
            }
            unset($data['sumtype'][3]);
            unset($data['sumtype'][4]);
//            $data['sumtype'][5]['sum_type_name'] = '奖池奖金';
            var_dump($data['sumtype']);exit;

//            $data['sumtype'][5]->sum_type_name = '奖池奖金';
//            $model->awardPeriodSumType->sum_type_id=$curSumType;
            $data['periodsum']=$model->search()->getArrayData();
         
            foreach($data['periodsum']['data'] as $key=>$sum)
            {
                $periodModel=new AwardPeriod('search');
                $periodModel->unsetAttributes();
                $periodModel->award_period_sum_type=$sum['award_period_sum_type'];
                $periodModel->award_period_period=$sum['award_period_sum_period'];
                $periodModel->award_period_memberinfo_id=$sum['award_period_sum_memberinfo_id'];
                $data['periodsum']['data'][$key]['awardPeriod']=$periodModel->search()->getArrayData();
            }
            echo CJSON::encode($data);
            webapp()->end();
        }

		$this->render('index',array(
			'model'=>$model,
			'gridColumn'=>$gridColumn,
			'sumTypes'=>$sumTypes,
			'curSumType'=>(int)$curSumType
		));
	}

	public function actionView()
	{

	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=awardPeriodSum::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='award-period-sum-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
