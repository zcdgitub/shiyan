<?php

class AwardDaySumController extends Controller
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
		$model=new AwardDaySum('search');
		$model->unsetAttributes();  // clear any default values
		$model->awardDaySumMemberinfo=new Memberinfo('search');
		$model->awardDaySumType=new SumType('search');
		$model->awardDaySumType->sum_type_id=$curSumType;

		if(isset($_GET['AwardDaySum']))
		{
			$model->attributes=$_GET['AwardDaySum'];
			if(isset($_GET['AwardDaySum']['awardDaySumMemberinfo']))
				$model->awardDaySumMemberinfo->attributes=$_GET['AwardDaySum']['awardDaySumMemberinfo'];
			
		}
		if(!user()->isAdmin())
			$model->award_day_sum_memberinfo_id=user()->id;
		//获取奖金名称列表及生成grid列
		$awardType=AwardType::model()->getTypes($curSumType);
		$gridColumn=[];
		foreach($awardType as $award)
		{
			//构造grid列
			$gridColumn[]=['name'=>'_award_' . $award->award_type_id,'header'=>t('epmms',$award->award_type_name),'filter'=>false,'type'=>'money'];
		}
		$sumTypes=SumType::model()->findAll(['with'=>['sumConfigs'],'condition'=>"sum_config_date='day'",'order'=>'t.sum_type_id asc']);

        if(webapp()->request->isAjaxRequest){
            header('Content-Type: application/json');
            $data=[];
            foreach($sumTypes as $key=>$sum)
            {
                $data['sumtype'][$key]=$sum->toArray();
                //$model->awardPeriodSumType->sum_type_id=$sum->sum_type_id;
                //$data['sumtype'][$key]['awardPeriodSum']=$model->search()->getArrayData();
            }
//            unset($data['sumtype'][3]);
//            unset($data['sumtype'][4]);
//            $data['sumtype'][5]['sum_type_name'] = '奖池奖金';
//            $model->awardPeriodSumType->sum_type_id=$curSumType;
            $data['periodsum']=$model->search()->getArrayData();
            foreach($data['periodsum']['data'] as $key=>$sum)
            {
                $dayModel=new AwardDay('search');
                $dayModel->unsetAttributes();
                $dayModel->award_day_sum_type=$sum['award_day_sum_type'];
                $dayModel->award_day_memberinfo_id=$sum['award_day_sum_memberinfo_id'];
                $data['periodsum']['data'][$key]=$dayModel->search()->getArrayData();
            }

            foreach ($data['periodsum']['data'][0] as $key=>$val){
                $val['award_day_sum_type'] = $data['sumtype'][$val['award_day_sum_type']]['sum_type_name'];
                $info['data'][$val['award_day_date']]['data'][] = $val;
                if(!isset($info['data'][$val['award_day_date']]['sumMoney'])){
                    $info['data'][$val['award_day_date']]['sumMoney'] = 0;
                }
                $info['data'][$val['award_day_date']]['sumMoney'] += $val['award_day_currency'];
                $info['data'][$val['award_day_date']]['time'] = $val['award_day_date'];
            }
            echo CJSON::encode($info);
            webapp()->end();
        }

		$this->render('index',array(
			'model'=>$model,
			'gridColumn'=>$gridColumn,
			'sumTypes'=>$sumTypes,
			'curSumType'=>(int)$curSumType
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionSalary($withdrawals=0)
	{
		$model=new AwardDaySumAll('search');
		$model->unsetAttributes();  // clear any default values
		$model->awardDaySumMemberinfo=new Memberinfo('search');
		$model->award_day_sum_is_withdrawals=$withdrawals;
		if($withdrawals==0)
			$model->award_day_sum_date="<" . date('Y-m-d');;
		if(!user()->isAdmin())
			$model->award_day_sum_memberinfo_id=user()->id;
		if(isset($_GET['AwardDaySumAll']))
		{
			$model->attributes=$_GET['AwardDaySumAll'];
			if(isset($_GET['AwardDaySumAll']['awardDaySumMemberinfo']))
				$model->awardDaySumMemberinfo->attributes=$_GET['AwardDaySumAll']['awardDaySumMemberinfo'];
		}
		if(isset($_REQUEST['allWithdrawals']) && $_REQUEST['allWithdrawals']==1 && $model->award_day_sum_is_withdrawals==0)
		{
			foreach($model->search()->data as $memberAward)
			{
				$memberAward->payoff();
			}
			user()->setFlash('success','全部提现成功');
		}
		$this->render('salary',array(
			'model'=>$model,
			'withdrawals'=>$withdrawals
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionSalaryDel($id)
	{
		$model=$this->loadModel2($id);
		$model->award_day_sum_is_withdrawals=1;
		$model->saveAttributes(['award_day_sum_is_withdrawals']);
		$this->log['target']=$model->awardDaySumMemberinfo->memberinfo_account;
		$this->log['status']=LogFilter::SUCCESS;
		$this->log();
		user()->setFlash('success','会员' . $model->awardDaySumMemberinfo->memberinfo_account . t('epmms',"工资单已删除并移入已提现"));
		$this->redirect(array('salary'));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionPayoff($member_id)
	{
		$model=new Withdrawals('create');
		$award=AwardDaySumAll::model()->findByAttributes(['award_day_sum_memberinfo_id'=>$member_id]);
		$model->withdrawals_currency=$award->award_day_sum_currency;

		//$sumtype-id为null,为第1个财务类型
		$finance=Finance::getFinanceSumType($member_id);
		$model->withdrawals_member_id=$finance->finance_memberinfo_id;
		$model->withdrawalsMember=Memberinfo::model()->findByPk($finance->finance_memberinfo_id);
		$model->withdrawals_finance_type_id=$finance->finance_type;
		$model->withdrawalsFinanceType=FinanceType::model()->findByPk($finance->finance_type);
		$model->withdrawals_currency=$award->award_day_sum_currency;

		$fee_config=config('withdrawals','tax');
		$award_config_currency=abs($fee_config);
		$model->withdrawals_tax=-$model->withdrawals_currency*$award_config_currency;
		//提现封顶
		$tax_cap=config('withdrawals','tax_cap');
		if(!empty($tax_cap))
		{
			if(abs($model->withdrawals_tax)>abs($tax_cap))
				$model->withdrawals_tax=-abs($tax_cap);
		}
		if(config('withdrawals','type')==1)
			$model->withdrawals_real_currency=$model->withdrawals_currency-abs($model->withdrawals_tax);
		else
			$model->withdrawals_real_currency=$model->withdrawals_currency+abs($model->withdrawals_tax);
		if($model->deduct_money>$finance->finance_award)
		{
			user()->setFlash('error',t("epmms",'余额不足'));
			$this->redirect(array('salary'));
		}
		$this->log['target']=$model->withdrawalsMember->memberinfo_account;
		$transaction=webapp()->db->beginTransaction();
		if($model->save(false,array('withdrawals_member_id','withdrawals_currency',
				'withdrawals_add_date','withdrawals_is_verify',
				'withdrawals_verify_date','withdrawals_remark',
				'withdrawals_finance_type_id','withdrawals_sn',
				'withdrawals_tax','withdrawals_real_currency')) && $model->verify() && $model->send()
		)
		{
				$award->award_day_sum_is_withdrawals=1;
				$award->saveAttributes(['award_day_sum_is_withdrawals']);
				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success','会员' . $model->withdrawalsMember->memberinfo_account . t('epmms',"工资发放成功,所发工资已从相应账户中扣除"));
				$this->redirect(array('salary'));
			}
			else
			{
				$transaction->rollback();
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
				foreach($model->errors as $err)
				{
					foreach($err as $estr)
						user()->setFlash('error',$estr);
				}

				$this->redirect(array('salary'));
			}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=AwardDaySum::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	public function loadModel2($id)
	{
		$model=AwardDaySumAll::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='award-day-sum-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
