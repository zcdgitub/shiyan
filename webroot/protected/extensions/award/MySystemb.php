<?php

/** 
 * @author hetao
 * 制度方案
 */
class MySystemb extends \AwardSystem
{
	protected $_name='131104A';
	public function __construct($map)
	{
		$this->_map=$map;
		$dbPeriod=new DbEvaluate("nextval('award_period')");
		$this->_period=$dbPeriod->run();
	}
	public function calc($group,$calc,$calc_type)
	{
		$sumProc=new DbCall('epmms_verify_award_group_b',array($this->map->membermap_id,$this->getPeriod(),$group,$calc,$calc_type));
		$sumProc->run();
		/*
		if($calc_type==1)
			$this->genMember();*/
	}
	public function sum()
	{

	}
	public function genMember()
	{
		//拿500的见点奖送点位
		$connection=Yii::app()->db;
		$transaction=webapp()->db->beginTransaction();
		$sql="select award_period_memberinfo_id as id from epmms_award_period where award_period_period=:period and  award_period_type_id=142 limit 1";

		$command=$connection->createCommand($sql);
		$datareader=$command->query([':period'=>$this->period]);
		foreach($datareader as $row)
		{
			$member=GroupMap::model()->find(['order'=>'groupmap_order asc']);
			$member->groupmap_is_award=1;
			$member->saveAttributes(['groupmap_is_award']);
			if($member)
			{
				$newMember=$member->genNewMember();
				if(is_object($newMember))
				{
					if(!Messages::send('生成会员','生成的会员:'.$newMember->groupmap_name,$row['id']))
					{
						$transaction->rollback();
						throw new Error('发送邮件失败');
					}
				}
				else
				{
					$status=$newMember;
					if($status===EError::DUPLICATE)
					{
						$this->log['status']=LogFilter::FAILED;
						user()->setFlash('error',"生成会员" . t('epmms',"失败,请不要重复审核"));
					}
					elseif($status===EError::NOMONEY)
					{
						$this->log['status']=LogFilter::FAILED;
						user()->setFlash('error',"生成会员" . t('epmms',"失败,电子币不足"));
					}
					elseif($status===EError::NOVERIFY_AGENT)
					{
						$this->log['status']=LogFilter::FAILED;
						user()->setFlash('error',"生成会员" . t('epmms',"失败,代理中心未审核"));
					}
					elseif($status===EError::SAVE)
					{
						$this->log['status']=LogFilter::FAILED;
						user()->setFlash('error',"生成会员" . t('epmms',"失败,不能保存"));
					}
					$transaction->rollback();
					throw new Error('自动注册会员失败');
				}
			}
		}
		$transaction->commit();
	}
}
?>