<?php

class StartController extends Controller
{
	public $layout='//layouts/column2';
	public function actionIndex()
	{

		$announcement=Announcement::model()->findAll(['limit'=>10,'order'=>'announcement_mod_date desc']);
		$award=AwardPeriod::model()->findAll(['condition'=>user()->isAdmin()?null:'award_period_memberinfo_id=:id','limit'=>10,'params'=>[':id'=>user()->id]]);
		$awardType=AwardType::model()->getTypes(1);


		$sql="select count(*)  from epmms_membermap where membermap_verify_date::date=current_date;";
		$day_cnt=webapp()->db->createCommand($sql)->queryScalar();
		$sql="select coalesce(sum(membermap_money),0) from epmms_membermap where membermap_verify_date::date=current_date;";
		$day_money=webapp()->db->createCommand($sql)->queryScalar();
		if(user()->isAdmin())
		{

			//提醒空间，域名到期，配额超限提醒
			//还有30天空间到期
			$spaceDate=params('licence.spaceExpiry');
			if(!empty($spaceDate))
			{
				$spaceExpiry = new DateTime($spaceDate);
				$datetimeNow = new DateTime('today');
				$dateinter=$datetimeNow->diff($spaceExpiry);
				if($dateinter->invert==0 and $dateinter->days<30)
				{
					user()->setFlash('success',"您的服务器空间将于" . params('licence.spaceExpiry') . "到期，界时系统将无法使用，请及时续费以免影响您的使用。");
				}
			}
			//还有30天域名到期
			$domainDate=params('licence.domainExpiry');
			if(!empty($domainDate))
			{
				$domainExpiry = new DateTime($domainDate);
				$datetimeNow = new DateTime('today');
				$dateinter=$datetimeNow->diff($domainExpiry);
				if($dateinter->invert==0 and $dateinter->days<30)
				{
					user()->setFlash('success',"您的网站域名将于" . params('licence.spaceExpiry') . "到期，届时系统将无法使用，请及时续费以免影响您的使用。");
				}
			}

			//还有2天试用到期,如果是试用模式
			$tryDate=params('licence.tryExpiry');
			if(!empty($tryDate))
			{
				$tryExpiry=new DateTime($tryDate);
				$datetimeNow = new DateTime('today');
				$dateinter=$datetimeNow->diff($tryExpiry);
				if($dateinter->invert==0 and $dateinter->days<2)
				{
					user()->setFlash('success',"您的试用将于" . params('licence.spaceExpiry') . "到期，届时系统将无法使用，请及时续费以免影响您的使用。");
				}
			}
			//空间限额
			$path="../";
			$ar=getDirectorySize($path);
			$dirSize=sizeFormat($ar['size']);
			$dirSizeMB=round($ar['size']/(1024*1024),1);
			$spaceQuota=params('spaceQuota');
			if($spaceQuota-$dirSizeMB<250)
			{
				user()->setFlash('success',"您的空间即将用尽，届时系统将无法使用，请及时续费以免影响您的使用。");
			}
			$award_sum=null;
			$award_total=null;
	        $award_total=AwardTotalSum::model()->find();
			
			$award_sum=Yii::app()->db->createCommand()
				->select('sum(award_total_sum_currency) as total')
				->from('epmms_award_total_sum')
                ->where('award_total_sum_currency>0')
				->queryRow();
			
			//系统状态
			$sys_status=SystemStatus::model()->find();
			$member_count=Membermap::model()->count('membermap_is_verify=1');
			$agent_count=Membermap::model()->count('membermap_is_agent=1');
			$last_verify_date=Membermap::model()->find(['condition'=>'membermap_is_verify=1','order'=>'membermap_verify_date desc'])->membermap_verify_date;
			$transfer_count=Transfer::model()->count('transfer_is_verify=0');
			$charge_count=Charge::model()->count('charge_is_verify=0');
			$agent_count2=Agent::model()->count('agent_is_verify=0');
			$member_count2=Memberinfo::model()->count('memberinfo_is_verify=0');
			$withdrawals_count=Withdrawals::model()->count('withdrawals_is_verify=0');

            $baodan=yii::app()->db->createCommand()->select('sum(finance_award)')->from('epmms_finance')->where('finance_type=1')->queryRow() ;
            $dianzi=yii::app()->db->createCommand()->select('sum("finance_award")')->from('epmms_finance')->where('finance_type=2')->queryRow() ;
            $jifen= yii::app()->db->createCommand()->select('sum("finance_award")')->from('epmms_finance')->where('finance_type=3')->queryRow() ;
              $product_count= yii::app()->db->createCommand()->select('sum("membermap_recommend_under_product_count")')->from('epmms_membermap')->queryAll() ;
            foreach ($product_count as $key => $value) {
            	$productCount=$value['sum'];
            }
			echo $this->render('index',['announcement'=>$announcement,
				'award'=>$award,'award_total'=>$award_total,
				'award_sum'=>$award_sum,
				'baodan'=>$baodan,
				'dianzi'=>$dianzi,
				'jifen'=>$jifen,
				'productCount'=>$productCount,
				'award_type'=>$awardType,
				'sys_status'=>$sys_status,
				'member_count'=>$member_count,
				'agent_count'=>$agent_count,
				'last_verify_date'=>$last_verify_date,
				'transfer_count'=>$transfer_count,
				'charge_count'=>$charge_count,
				'agent_count2'=>$agent_count2,
				'member_count2'=>$member_count2,
				'withdrawals_count'=>$withdrawals_count,
				'dirSize'=>$dirSize,
				'spaceExpiry'=>$spaceDate,
				'domainExpiry'=>$domainDate,
				'tryDate'=>$tryDate,
				'spaceQuota'=>sizeFormat($spaceQuota*1024*1024),
				'day_cnt'=>$day_cnt,
				'day_money'=>$day_money
			]);
		}
		else
		{

            if(webapp()->request->isAjaxRequest)
            {
                $data=array_merge(Rest::myinfo(),Rest::finance(),Rest::announcement(),Rest::bank(),Rest::memberType());
                header('Content-Type: application/json');
                $data=array_merge(Rest::myinfo(),Rest::finance(),Rest::announcement(),Rest::bank(),Rest::memberType());
         
                echo CJSON::encode($data);
                return;

            }
	     $award_total=AwardTotalSum::model()->find(['condition'=>'award_total_sum_memberinfo_id=:id','params'=>[':id'=>user()->id]]);

			$award_sum=Yii::app()->db->createCommand()
				->select('sum(award_total_sum_currency) as total')
				->from('epmms_award_total_sum')
				->where('award_total_sum_currency>0 and award_total_sum_memberinfo_id=:id',[':id'=>user()->id])
				->queryRow();
			$unread_count=Messages::model()->count('messages_is_read=0 and messages_member_id=' . user()->id);
			$member_count3=Membermap::model()->count('membermap_is_verify=0 and membermap_agent_id=' . user()->id);
			$member_count4=Membermap::model()->count('membermap_is_verify=1 and membermap_agent_id=' . user()->id);
			$user=user()->getMap();
			$financeType=FinanceType::model()->findAll();
			 $product_count= yii::app()->db->createCommand()->select('sum("membermap_recommend_under_product_count")')->from('epmms_membermap')->where('membermap_id='.user()->id)->queryAll() ;
            foreach ($product_count as $key => $value) {
            	$productCount=$value['sum'];
            }
			echo $this->render('index',['announcement'=>$announcement,
				'award'=>$award,'award_total'=>$award_total,
				'award_sum'=>$award_sum,
				'award_type'=>$awardType,
			    'productCount'=>$productCount,
				'unread_count'=>$unread_count,
				'member_count3'=>$member_count3,
				'member_count4'=>$member_count4,
				'user'=>$user,
				'financeType'=>$financeType,
				'day_cnt'=>$day_cnt,
				'day_money'=>$day_money
			]);
		}
	}

	// Uncomment the following methods and override them if needed

	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
		    'cors',
			'closeSite',
			'rights', // rights rbac filter
			'postOnly + delete', // 只能通过POST请求删除
		);
	}
    public function actionNews(){
    	  
    	  if(webapp()->request->isAjaxRequest)
            {

                header('Content-Type: application/json');
            
               $dataProvider=new JSonActiveDataProvider('Announcement');
               $data['announcement']=$dataProvider->getArrayData();
  
                echo CJSON::encode($data);
                return;
            }
    }
}